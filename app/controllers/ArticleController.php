<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Db;
use App\Models\ChatModel;
use App\Models\RagModel;
use App\Models\SearchModel;
use Base;
use Dompdf\Dompdf;
use Dompdf\Options;

final class ArticleController
{
    private function config(Base $f3): array
    {
        return [
            'openai_api_key' => (string) $f3->get('OPENAI_API_KEY'),
            'chat_model' => (string) $f3->get('OPENAI_CHAT_MODEL'),
        ];
    }

    // POST /articulos/chat.php — chat por artículo: responde ÚNICAMENTE con base en
    // el artículo completo (cabe entero en el contexto, sin necesidad de recuperación).
    public function chatArticulo(Base $f3): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pdo = Db::pdo($f3);
        $ip = ip_cliente();
        $config = $this->config($f3);

        if (ChatModel::limiteExcedido($pdo, $ip, 'chat_articulo')) {
            http_response_code(429);
            echo json_encode(['error' => 'Demasiadas preguntas seguidas. Espera unos minutos e intenta de nuevo.']);
            return;
        }

        $entrada = json_decode(file_get_contents('php://input'), true);
        if (!is_array($entrada)) {
            http_response_code(400);
            echo json_encode(['error' => 'Solicitud inválida.']);
            return;
        }

        $articuloId = (int) ($entrada['article_id'] ?? 0);
        $mensaje = trim((string) ($entrada['message'] ?? ''));
        $historialCrudo = is_array($entrada['history'] ?? null) ? $entrada['history'] : [];

        if ($mensaje === '' || mb_strlen($mensaje) > 2000) {
            http_response_code(400);
            echo json_encode(['error' => 'Pregunta vacía o demasiado larga.']);
            return;
        }

        $stmt = $pdo->prepare("SELECT title, body FROM articles WHERE id = :id AND estado = 'publicado'");
        $stmt->execute(['id' => $articuloId]);
        $articulo = $stmt->fetch();
        if (!$articulo) {
            http_response_code(404);
            echo json_encode(['error' => 'Artículo no encontrado.']);
            return;
        }

        // Roles saneados a user/assistant, últimos 6 turnos — un cliente malicioso
        // no puede inyectar un mensaje "system".
        $historial = [];
        foreach (array_slice($historialCrudo, -12) as $turno) {
            $rol = ($turno['role'] ?? '') === 'assistant' ? 'assistant' : 'user';
            $contenido = trim((string) ($turno['content'] ?? ''));
            if ($contenido !== '') {
                $historial[] = ['role' => $rol, 'content' => mb_substr($contenido, 0, 2000)];
            }
        }

        $contexto = truncar_para_ia($articulo['title'] . "\n\n" . $articulo['body']);

        $mensajes = [
            [
                'role' => 'system',
                'content' => "Eres un asistente que responde preguntas ÚNICAMENTE con base en el siguiente artículo de Eduteka. "
                    . "Si la respuesta no está en el artículo, dilo explícitamente en vez de inventar. "
                    . "Responde en español, de forma breve y clara.\n\n--- ARTÍCULO ---\n" . $contexto,
            ],
        ];
        foreach ($historial as $turno) {
            $mensajes[] = $turno;
        }
        $mensajes[] = ['role' => 'user', 'content' => $mensaje];

        $payload = json_encode([
            'model' => $config['chat_model'],
            'messages' => $mensajes,
            'temperature' => 0.3,
            'max_tokens' => 600,
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
            CURLOPT_TIMEOUT => 30,
        ]);
        $respuestaCruda = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorCurl = curl_error($ch);
        curl_close($ch);

        if ($respuestaCruda === false || $httpCode >= 400) {
            http_response_code(502);
            echo json_encode(['error' => 'El servicio de IA no respondió. Intenta de nuevo en un momento.']);
            error_log('chatArticulo OpenAI error: ' . $errorCurl . ' HTTP ' . $httpCode . ' body=' . $respuestaCruda);
            return;
        }

        $datos = json_decode($respuestaCruda, true);
        $respuesta = $datos['choices'][0]['message']['content'] ?? null;

        if (!$respuesta) {
            http_response_code(502);
            echo json_encode(['error' => 'No se pudo generar una respuesta.']);
            return;
        }

        $tokensIn = $datos['usage']['prompt_tokens'] ?? null;
        $tokensOut = $datos['usage']['completion_tokens'] ?? null;
        ChatModel::registrarChatArticulo($pdo, $ip, $articuloId, $mensaje, $respuesta, $tokensIn, $tokensOut);

        echo json_encode(['reply' => $respuesta]);
    }

    // POST /articulos/chat_general.php — chat general: RAG real sobre los 1.577
    // artículos (condensar -> embeber -> recuperar -> generar con grounding).
    public function chatGeneral(Base $f3): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pdo = Db::pdo($f3);
        $ip = ip_cliente();
        $config = $this->config($f3);

        if (ChatModel::limiteExcedido($pdo, $ip, 'chat_general')) {
            http_response_code(429);
            echo json_encode(['error' => 'Demasiadas preguntas seguidas. Espera unos minutos e intenta de nuevo.']);
            return;
        }

        $entrada = json_decode(file_get_contents('php://input'), true);
        if (!is_array($entrada)) {
            http_response_code(400);
            echo json_encode(['error' => 'Solicitud inválida.']);
            return;
        }

        $mensaje = trim((string) ($entrada['message'] ?? ''));
        $historialCrudo = is_array($entrada['history'] ?? null) ? $entrada['history'] : [];

        if ($mensaje === '' || mb_strlen($mensaje) > 2000) {
            http_response_code(400);
            echo json_encode(['error' => 'Pregunta vacía o demasiado larga.']);
            return;
        }

        $historial = [];
        foreach (array_slice($historialCrudo, -12) as $turno) {
            $rol = ($turno['role'] ?? '') === 'assistant' ? 'assistant' : 'user';
            $contenido = trim((string) ($turno['content'] ?? ''));
            if ($contenido !== '') {
                $historial[] = ['role' => $rol, 'content' => mb_substr($contenido, 0, 2000)];
            }
        }

        $resultado = RagModel::generarRespuestaRagGeneral($pdo, $config, $mensaje, $historial);

        echo json_encode([
            'reply' => $resultado['respuesta'],
            'grounding' => $resultado['grounding'],
            'citados' => $resultado['citados'],
        ]);
    }

    // GET /articulos/descargar.php?id= — exporta un artículo publicado como PDF.
    public function descargarPdf(Base $f3): void
    {
        $pdo = Db::pdo($f3);

        $id = (int) ($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("
            SELECT a.title, a.slug, a.body, a.author, a.article_date, c.name AS categoria_nombre
            FROM articles a
            LEFT JOIN categories c ON c.id = a.category_id
            WHERE a.id = :id AND a.estado = 'publicado'
        ");
        $stmt->execute(['id' => $id]);
        $articulo = $stmt->fetch();

        if (!$articulo) {
            http_response_code(404);
            echo 'Artículo no encontrado.';
            return;
        }

        $contenidoHtml = markdown_render(quitar_imagenes_rotas($articulo['body']));
        $metaPartes = [];
        if ($articulo['author']) {
            $metaPartes[] = 'Por ' . e($articulo['author']);
        }
        if ($articulo['article_date']) {
            $metaPartes[] = e((string) $articulo['article_date']);
        }
        $meta = implode(' · ', $metaPartes);

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
            @page { margin: 2.2cm 2cm; }
            body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; color: #1a1a1a; line-height: 1.5; }
            h1 { color: #5454E9; font-size: 20pt; margin-bottom: 4px; }
            h2 { color: #5454E9; font-size: 15pt; margin-top: 22px; }
            h3 { font-size: 12.5pt; margin-top: 16px; }
            .categoria { display: inline-block; background: #CECFF4; color: #4a2d8a; font-size: 9pt;
                         padding: 3px 10px; border-radius: 4px; margin-bottom: 10px; }
            .meta { color: #88898C; font-size: 9.5pt; margin-bottom: 18px; }
            .regla { border: none; border-top: 1px solid #CECFF4; margin: 4px 0 20px; }
            table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
            table, th, td { border: 1px solid #ccc; }
            th, td { padding: 5px 7px; text-align: left; }
            blockquote { border-left: 3px solid #5454E9; margin: 10px 0; padding: 4px 14px; color: #444; font-style: italic; }
            img { max-width: 100%; }
            a { color: #5454E9; }
            .pie { margin-top: 28px; padding-top: 10px; border-top: 1px solid #eee; color: #999; font-size: 8.5pt; }
        </style></head><body>';

        if ($articulo['categoria_nombre']) {
            $html .= '<div class="categoria">' . e($articulo['categoria_nombre']) . '</div>';
        }
        $html .= '<h1>' . e($articulo['title']) . '</h1>';
        if ($meta !== '') {
            $html .= '<div class="meta">' . $meta . '</div>';
        }
        $html .= '<hr class="regla">';
        $html .= $contenidoHtml;
        $html .= '<div class="pie">Descargado desde eduteka.co · edukatic.co/articulos/ver.php?id=' . $id . '</div>';
        $html .= '</body></html>';

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $articulo['slug'] . '.pdf"');
        echo $dompdf->output();
    }

    // GET /articulos/etiquetas_todas.php — fragmento liviano con TODAS las etiquetas,
    // cargado de forma diferida (fetch) desde articulos/index.php.
    public function etiquetasTodas(Base $f3): void
    {
        $pdo = Db::pdo($f3);
        $etiquetas = $pdo->query("
            SELECT t.id, t.name, count(at2.article_id) AS n
            FROM tags t
            JOIN article_tags at2 ON at2.tag_id = t.id
            JOIN articles a ON a.id = at2.article_id AND a.estado = 'publicado'
            GROUP BY t.id, t.name
            ORDER BY n DESC, t.name ASC
        ")->fetchAll();

        header('Content-Type: text/html; charset=utf-8');
        foreach ($etiquetas as $t) {
            echo '<a href="/articulos/index.php?etiqueta_id=' . (int) $t['id'] . '" class="tag-pill">#'
                . e($t['name']) . ' <span class="opacity-60">(' . (int) $t['n'] . ')</span></a>';
        }
    }
}
