<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Throwable;

// Buscador semantico. Puerto 1:1 de public/articulos/lib/busqueda.php (misma logica,
// solo envuelta en clase con namespace). Con pgvector, la similitud de coseno se
// calcula en SQL con el operador <=> sobre un indice HNSW (embeddings_small).
final class SearchModel
{
    // Mismo hash para el embedding y para la sintesis con IA de una misma consulta
    // (viven juntos en query_embeddings, una fila por texto de busqueda distinto).
    public static function hashConsulta(string $texto): string
    {
        return hash('sha256', 'text-embedding-3-small|' . mb_strtolower(trim($texto)));
    }

    // Regenera el embedding de UN articulo completo (titulo + cuerpo) y lo guarda en
    // embeddings_small — a diferencia del batch (que solo llena articulos sin fila
    // todavia), esto SIEMPRE sobreescribe. Es la pieza que consume rag_status='pending'
    // tras editar un articulo.
    public static function reindexarEmbeddingArticulo(PDO $pdo, array $config, int $articleId, string $title, string $body): bool
    {
        $texto = $title . "\n\n" . $body;
        // Aproximacion de ~4 caracteres/token: 6000 tokens de margen.
        if (mb_strlen($texto) > 24000) {
            $texto = mb_substr($texto, 0, 24000);
        }

        // Si la API igual rechaza por "maximum context length", se reintenta con la
        // mitad del texto en vez de fallar el articulo entero. embedding-3-small
        // acepta 8192 tokens.
        $intentos = 0;
        do {
            $payload = json_encode(['model' => 'text-embedding-3-small', 'input' => $texto]);
            $ch = curl_init('https://api.openai.com/v1/embeddings');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $config['openai_api_key'],
                ],
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_TIMEOUT => 15,
            ]);
            $respuesta = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $excedeContexto = $httpCode === 400
                && is_string($respuesta)
                && str_contains($respuesta, 'maximum context length');
            if (!$excedeContexto) {
                break;
            }
            $texto = mb_substr($texto, 0, (int) (mb_strlen($texto) / 2));
            $intentos++;
        } while ($intentos < 3);

        if ($respuesta === false || $httpCode >= 400) {
            error_log('reindexarEmbeddingArticulo: error OpenAI HTTP ' . $httpCode . ' body=' . $respuesta);
            $pdo->prepare("UPDATE articles SET rag_status = 'error', rag_error_detail = :err WHERE id = :id")
                ->execute(['err' => 'Error OpenAI HTTP ' . $httpCode, 'id' => $articleId]);
            return false;
        }

        $datos = json_decode($respuesta, true);
        $vector = $datos['data'][0]['embedding'] ?? null;
        if (!is_array($vector)) {
            $pdo->prepare("UPDATE articles SET rag_status = 'error', rag_error_detail = 'Respuesta sin embedding' WHERE id = :id")
                ->execute(['id' => $articleId]);
            return false;
        }

        $literal = '[' . implode(',', $vector) . ']';
        $pdo->prepare('
            INSERT INTO embeddings_small (article_id, model, dimensions, embedding)
            VALUES (:aid, :model, :dim, :emb::vector)
            ON CONFLICT (article_id) DO UPDATE SET
                model = EXCLUDED.model, dimensions = EXCLUDED.dimensions,
                embedding = EXCLUDED.embedding, created_at = now()
        ')->execute([
            'aid' => $articleId, 'model' => 'text-embedding-3-small',
            'dim' => count($vector), 'emb' => $literal,
        ]);

        $pdo->prepare("
            UPDATE articles
            SET rag_status = 'ready', rag_indexed_at = now(), rag_chunk_count = 1, rag_error_detail = NULL
            WHERE id = :id
        ")->execute(['id' => $articleId]);

        try {
            $tokensIn = $datos['usage']['prompt_tokens'] ?? null;
            $pdo->prepare("
                INSERT INTO ai_usage (origen, kind, tokens_in, article_id)
                VALUES ('reindexar_articulo', 'embedding_articulo', :tin, :aid)
            ")->execute(['tin' => $tokensIn, 'aid' => $articleId]);
        } catch (Throwable $e) {
            error_log('reindexarEmbeddingArticulo: error registrando ai_usage: ' . $e->getMessage());
        }

        return true;
    }

    // Llama a la API de embeddings de OpenAI para el texto de la consulta, con cache
    // en query_embeddings (repetir una pregunta es gratis).
    public static function embeberConsulta(PDO $pdo, array $config, string $texto): ?array
    {
        $hash = self::hashConsulta($texto);

        $cache = $pdo->prepare('SELECT embedding FROM query_embeddings WHERE hash = :h');
        $cache->execute(['h' => $hash]);
        $embRaw = $cache->fetchColumn();
        if ($embRaw !== false) {
            return parse_pg_vector($embRaw);
        }

        $payload = json_encode(['model' => 'text-embedding-3-small', 'input' => $texto]);
        $ch = curl_init('https://api.openai.com/v1/embeddings');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $config['openai_api_key'],
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 15,
        ]);
        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($respuesta === false || $httpCode >= 400) {
            error_log('embeberConsulta: error OpenAI HTTP ' . $httpCode . ' body=' . $respuesta);
            return null;
        }

        $datos = json_decode($respuesta, true);
        $vector = $datos['data'][0]['embedding'] ?? null;
        if (!is_array($vector)) {
            return null;
        }

        $literal = '[' . implode(',', $vector) . ']';
        $pdo->prepare('INSERT INTO query_embeddings (hash, embedding) VALUES (:h, :e::vector) ON CONFLICT (hash) DO NOTHING')
            ->execute(['h' => $hash, 'e' => $literal]);

        return $vector;
    }

    // Top-N articulos publicados mas parecidos al vector de consulta — KNN real
    // contra el indice HNSW de embeddings_small.
    public static function buscarArticulosSimilares(PDO $pdo, array $vecConsulta, int $topN = 12): array
    {
        $literal = '[' . implode(',', $vecConsulta) . ']';
        $topN = max(1, $topN);
        // hnsw.ef_search debe ser >= el LIMIT de la consulta; arranque en 100, o el
        // doble del top-N si se pide mas de 50.
        $efSearch = max(100, $topN * 2);

        // SET LOCAL solo dura la transaccion actual: sin un BEGIN explicito, cada
        // exec()/execute() de PDO en autocommit es su propia transaccion implicita y
        // el ajuste se perderia antes de llegar a la consulta real.
        $pdo->beginTransaction();
        try {
            $pdo->exec('SET LOCAL hnsw.ef_search = ' . $efSearch);

            // :vec aparece UNA sola vez (gotcha PDO/pgsql HY093): se ordena por el
            // alias "distancia", nunca repitiendo la expresion <=>.
            $stmt = $pdo->prepare("
                SELECT a.id, a.title, a.slug, a.summary, a.body, c.name AS categoria_nombre,
                       (e.embedding <=> :vec::vector) AS distancia
                FROM embeddings_small e
                JOIN articles a ON a.id = e.article_id AND a.estado = 'publicado'
                LEFT JOIN categories c ON c.id = a.category_id
                ORDER BY distancia ASC
                LIMIT :lim
            ");
            $stmt->bindValue('vec', $literal);
            $stmt->bindValue('lim', $topN, PDO::PARAM_INT);
            $stmt->execute();
            $filas = $stmt->fetchAll();
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        $resultado = [];
        foreach ($filas as $fila) {
            $fila['similitud'] = 1 - (float) $fila['distancia'];
            unset($fila['distancia']);
            $resultado[] = $fila;
        }
        return $resultado;
    }

    // Texto generado por IA que antecede a la lista de resultados de busqueda:
    // responde si la busqueda es una pregunta, resume si es un tema. Cacheado junto
    // al embedding de la misma consulta.
    public static function obtenerSintesisBusqueda(PDO $pdo, array $config, string $consulta, array $resultados): ?array
    {
        $hash = self::hashConsulta($consulta);

        $cache = $pdo->prepare('SELECT sintesis_respuesta, sintesis_articulos FROM query_embeddings WHERE hash = :h');
        $cache->execute(['h' => $hash]);
        $fila = $cache->fetch();
        if ($fila && $fila['sintesis_respuesta'] !== null) {
            return ['respuesta' => $fila['sintesis_respuesta'], 'citados' => json_decode($fila['sintesis_articulos'], true) ?: []];
        }

        $top = array_slice($resultados, 0, 5);
        if (!$top) {
            return null;
        }

        $bloques = [];
        foreach ($top as $i => $r) {
            $extracto = mb_substr(strip_tags(markdown_render($r['body'])), 0, 1200);
            $etiquetaCategoria = $r['categoria_nombre'] ? ' (' . $r['categoria_nombre'] . ')' : '';
            $bloques[] = '[' . ($i + 1) . '] "' . $r['title'] . '"' . $etiquetaCategoria . ":\n" . $extracto;
        }
        $contexto = implode("\n\n---\n\n", $bloques);

        $prompt = "Eres el asistente de búsqueda del portal educativo Eduteka. Te doy el término o "
            . "pregunta que un usuario buscó, y fragmentos numerados de los artículos más relacionados "
            . "que encontró el buscador semántico.\n\n"
            . "Búsqueda del usuario: \"" . $consulta . "\"\n\n"
            . "--- ARTÍCULOS ENCONTRADOS ---\n" . $contexto . "\n\n"
            . "Instrucciones:\n"
            . "- Si la búsqueda es una PREGUNTA, respóndela basándote ÚNICAMENTE en los artículos de arriba. "
            . "Si no la responden con certeza, dilo honestamente en vez de inventar.\n"
            . "- Si la búsqueda es un TEMA o palabra clave (no una pregunta), resume brevemente de qué tratan "
            . "estos artículos en relación con ese tema.\n"
            . "- OBLIGATORIO: cita [n] justo después de cada afirmación que venga de un artículo, igual que una "
            . "nota al pie, usando el número del fragmento del que sale. Ejemplo: \"Los proyectos colaborativos "
            . "mejoran la interacción entre estudiantes [1] y facilitan evaluar el trabajo en equipo [3].\" "
            . "Ninguna oración basada en los artículos debe quedar sin su [n].\n"
            . "- Nunca inventes datos, cifras o nombres que no estén en los fragmentos.\n"
            . "- Responde en español, claro y breve (máximo 100 palabras).\n\n"
            . 'Responde solo con JSON: {"respuesta": "texto con [n] inline en cada afirmación, como en el ejemplo", "citados": [números [n] de los artículos que realmente usaste]}';

        $payload = json_encode([
            'model' => $config['chat_model'],
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.3,
            'max_tokens' => 350,
        ]);

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $config['openai_api_key'],
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 10,
        ]);
        $respuestaCruda = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($respuestaCruda === false || $httpCode >= 400) {
            error_log('obtenerSintesisBusqueda: error OpenAI HTTP ' . $httpCode . ' body=' . $respuestaCruda);
            return null;
        }

        $datos = json_decode($respuestaCruda, true);
        $contenido = json_decode($datos['choices'][0]['message']['content'] ?? '', true);
        $respuestaTexto = trim((string) ($contenido['respuesta'] ?? ''));
        $citados = is_array($contenido['citados'] ?? null) ? array_values(array_map('intval', $contenido['citados'])) : [];

        if ($respuestaTexto === '') {
            return null; // fail-open: sin sintesis, pero la lista de resultados se muestra igual
        }

        try {
            $pdo->prepare('
                UPDATE query_embeddings
                SET sintesis_respuesta = :r, sintesis_articulos = :c
                WHERE hash = :h
            ')->execute(['r' => $respuestaTexto, 'c' => json_encode($citados), 'h' => $hash]);

            $tokensIn = $datos['usage']['prompt_tokens'] ?? null;
            $tokensOut = $datos['usage']['completion_tokens'] ?? null;
            $pdo->prepare("
                INSERT INTO ai_usage (origen, kind, tokens_in, tokens_out, ip)
                VALUES ('busqueda_publica', 'busqueda_sintesis', :tin, :tout, :ip)
            ")->execute(['tin' => $tokensIn, 'tout' => $tokensOut, 'ip' => ip_cliente()]);
        } catch (Throwable $e) {
            error_log('obtenerSintesisBusqueda: error guardando cache/uso: ' . $e->getMessage());
        }

        return ['respuesta' => $respuestaTexto, 'citados' => $citados];
    }
}
