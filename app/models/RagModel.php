<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Throwable;

// Chatbot general de Eduteka. Puerto 1:1 de public/articulos/lib/chat_general.php.
// A diferencia del chat por articulo (que responde sobre UN articulo que ya cabe
// entero en el contexto), aqui hay un corpus completo de 1.577 articulos — RAG real:
// condensar -> embeber -> recuperar top-K -> podar por umbral -> contexto numerado
// -> generar con grounding -> persistir.
final class RagModel
{
    public const UMBRAL_SIMILITUD_MINIMA = 0.25;
    public const TOP_K_CHAT_GENERAL = 8;

    // Si el mensaje es corto y hay historial previo, probablemente depende del hilo
    // ("¿y eso cómo se aplica?") — se le pide al modelo barato reescribirlo de forma
    // autocontenida antes de embeberlo.
    public static function condensarPregunta(array $config, string $mensaje, array $historial): string
    {
        if (mb_strlen($mensaje) >= 80 || !$historial) {
            return $mensaje;
        }

        $turnos = [];
        foreach (array_slice($historial, -4) as $t) {
            $turnos[] = ($t['role'] === 'assistant' ? 'Asistente' : 'Usuario') . ': ' . $t['content'];
        }
        $contexto = implode("\n", $turnos);

        $prompt = "Reescribe la última pregunta del usuario como una pregunta autocontenida (que se "
            . "entienda sin el resto de la conversación), basándote en este historial:\n\n$contexto\n\n"
            . "Última pregunta: \"$mensaje\"\n\n"
            . "Responde ÚNICAMENTE con la pregunta reescrita, sin comillas ni explicaciones.";

        $payload = json_encode([
            'model' => $config['chat_model'],
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'temperature' => 0.2,
            'max_tokens' => 100,
        ]);

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $config['openai_api_key']],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 8,
        ]);
        $resp = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resp === false || $http >= 400) {
            return $mensaje; // fail-open: si falla la condensacion, se embebe el mensaje tal cual
        }
        $datos = json_decode($resp, true);
        $reescrita = trim((string) ($datos['choices'][0]['message']['content'] ?? ''));
        return $reescrita !== '' ? $reescrita : $mensaje;
    }

    // Orquesta todo el flujo del RAG general para un turno de conversacion.
    // Devuelve ['respuesta','grounding','citados' => [['id'=>,'title'=>], ...]].
    public static function generarRespuestaRagGeneral(PDO $pdo, array $config, string $mensaje, array $historial): array
    {
        $preguntaCondensada = self::condensarPregunta($config, $mensaje, $historial);

        $vecConsulta = SearchModel::embeberConsulta($pdo, $config, $preguntaCondensada);
        if ($vecConsulta === null) {
            return ['respuesta' => 'No pude procesar tu pregunta en este momento. Intenta de nuevo.', 'grounding' => 'error', 'citados' => [], 'pregunta_condensada' => $preguntaCondensada];
        }

        $candidatos = SearchModel::buscarArticulosSimilares($pdo, $vecConsulta, self::TOP_K_CHAT_GENERAL);
        // Se poda por umbral: mejor poco y bueno que relleno de ruido.
        $relevantes = array_values(array_filter($candidatos, fn($c) => $c['similitud'] >= self::UMBRAL_SIMILITUD_MINIMA));

        if (!$relevantes) {
            $respuesta = 'No encontré artículos de Eduteka relacionados con tu pregunta. '
                . '¿Puedes reformularla o preguntar sobre otro tema educativo?';
            self::registrarChatGeneral($pdo, $mensaje, $preguntaCondensada, $respuesta, 'sin_resultados', [], null, null);
            return ['respuesta' => $respuesta, 'grounding' => 'sin_resultados', 'citados' => [], 'pregunta_condensada' => $preguntaCondensada];
        }

        $bloques = [];
        foreach ($relevantes as $i => $r) {
            $extracto = mb_substr(strip_tags(markdown_render($r['body'])), 0, 1200);
            $etiquetaCategoria = $r['categoria_nombre'] ? ' (' . $r['categoria_nombre'] . ')' : '';
            $bloques[] = '[' . ($i + 1) . '] "' . $r['title'] . '"' . $etiquetaCategoria . ":\n" . $extracto;
        }
        $contexto = implode("\n\n---\n\n", $bloques);

        $historialSaneado = [];
        foreach (array_slice($historial, -6) as $t) {
            $rol = ($t['role'] ?? '') === 'assistant' ? 'assistant' : 'user';
            $contenido = trim((string) ($t['content'] ?? ''));
            if ($contenido !== '') {
                $historialSaneado[] = ['role' => $rol, 'content' => mb_substr($contenido, 0, 2000)];
            }
        }

        $systemPrompt = "Eres el asistente de Eduteka, un portal educativo. Respondes preguntas de "
            . "docentes y estudiantes basándote en los artículos de Eduteka que te doy como contexto.\n\n"
            . "Reglas:\n"
            . "- Básate siempre en los fragmentos numerados de abajo y cita [n] en cada afirmación que venga de ellos.\n"
            . "- Si los fragmentos no bastan para responder del todo, puedes complementar con tu conocimiento "
            . "general, pero avísalo explícitamente (\"Fuera de tus documentos de Eduteka...\").\n"
            . "- Nunca inventes citas, cifras, nombres o datos que no estén en el contexto.\n"
            . "- Si varios artículos tratan el tema, compáralos brevemente.\n"
            . "- Responde en español, clara y concisa (máximo 150 palabras).\n\n"
            . "--- ARTÍCULOS DE EDUTEKA ---\n$contexto";

        $mensajes = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($historialSaneado as $t) {
            $mensajes[] = $t;
        }
        $mensajes[] = ['role' => 'user', 'content' =>
            $mensaje . "\n\n(Al final de tu respuesta agrega una última línea con SOLO una de estas "
            . 'tres opciones, elige exactamente una y escribe nada más que eso en esa línea: '
            . '"GROUNDING: rag" (si respondiste solo con los artículos), "GROUNDING: general" (si solo '
            . 'usaste conocimiento general) o "GROUNDING: integrado" (si combinaste ambos).)'];

        $payload = json_encode([
            'model' => $config['chat_model'],
            'messages' => $mensajes,
            'temperature' => 0.3,
            'max_tokens' => 500,
        ]);

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $config['openai_api_key']],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 20,
        ]);
        $resp = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resp === false || $http >= 400) {
            error_log('generarRespuestaRagGeneral: error OpenAI HTTP ' . $http . ' body=' . $resp);
            return ['respuesta' => 'El servicio de IA no respondió. Intenta de nuevo en un momento.', 'grounding' => 'error', 'citados' => [], 'pregunta_condensada' => $preguntaCondensada];
        }

        $datos = json_decode($resp, true);
        $textoCompleto = trim((string) ($datos['choices'][0]['message']['content'] ?? ''));

        // Etiqueta en la ultima linea, parseada con regex.
        $grounding = 'integrado';
        if (preg_match('/GROUNDING:\s*(rag|general|integrado)/i', $textoCompleto, $m)) {
            $grounding = mb_strtolower($m[1]);
        }
        // Se quita TODO desde "GROUNDING:" hasta el final (no solo la palabra
        // reconocida): a veces el modelo repite "rag|general|integrado" tal cual.
        $textoCompleto = trim((string) preg_replace('/\s*GROUNDING:.*/is', '', $textoCompleto));

        // Detectar que [n] realmente aparecen citados en el texto para armar "fuentes".
        $citados = [];
        foreach ($relevantes as $i => $r) {
            if (preg_match('/\[' . ($i + 1) . '\]/', $textoCompleto)) {
                $citados[] = ['id' => (int) $r['id'], 'title' => $r['title']];
            }
        }

        $tokensIn = $datos['usage']['prompt_tokens'] ?? null;
        $tokensOut = $datos['usage']['completion_tokens'] ?? null;
        self::registrarChatGeneral($pdo, $mensaje, $preguntaCondensada, $textoCompleto, $grounding, $citados, $tokensIn, $tokensOut);

        return ['respuesta' => $textoCompleto, 'grounding' => $grounding, 'citados' => $citados, 'pregunta_condensada' => $preguntaCondensada];
    }

    public static function registrarChatGeneral(PDO $pdo, string $pregunta, string $preguntaCondensada, string $respuesta, string $grounding, array $citados, ?int $tokensIn, ?int $tokensOut): void
    {
        try {
            $pdo->prepare('
                INSERT INTO chat_general_log (ip, pregunta, pregunta_condensada, respuesta, grounding, articulos_citados, tokens_in, tokens_out)
                VALUES (:ip, :p, :pc, :r, :g, :c, :tin, :tout)
            ')->execute([
                'ip' => ip_cliente(),
                'p' => $pregunta,
                'pc' => $preguntaCondensada !== $pregunta ? $preguntaCondensada : null,
                'r' => $respuesta,
                'g' => $grounding,
                'c' => json_encode($citados),
                'tin' => $tokensIn,
                'tout' => $tokensOut,
            ]);
            if ($tokensIn !== null) {
                $pdo->prepare("INSERT INTO ai_usage (origen, kind, tokens_in, tokens_out, ip) VALUES ('chat_general', 'chat_general', :tin, :tout, :ip)")
                    ->execute(['tin' => $tokensIn, 'tout' => $tokensOut, 'ip' => ip_cliente()]);
            }
        } catch (Throwable $e) {
            error_log('registrarChatGeneral: ' . $e->getMessage());
        }
    }
}
