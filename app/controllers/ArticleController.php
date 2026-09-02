<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Db;
use App\Lib\View;
use App\Models\ArticleModel;
use App\Models\CategoryModel;
use App\Models\ChatModel;
use App\Models\RagModel;
use App\Models\SearchModel;
use App\Models\TagModel;
use Base;
use Dompdf\Dompdf;
use Dompdf\Options;
use Throwable;

final class ArticleController
{
    private function config(Base $f3): array
    {
        return [
            'openai_api_key' => (string) $f3->get('OPENAI_API_KEY'),
            'chat_model' => (string) $f3->get('OPENAI_CHAT_MODEL'),
        ];
    }

    // GET /articulos/index.php — listado/catalogo. Cuatro modos segun query string:
    // busqueda (?q=), categoria (?categoria_id=), etiqueta (?etiqueta_id=), o el
    // catalogo completo paginado por defecto.
    public function index(Base $f3): void
    {
        $pdo = Db::pdo($f3);
        $config = $this->config($f3);

        $q = trim((string) ($_GET['q'] ?? ''));
        $categoriaId = (int) ($_GET['categoria_id'] ?? 0);
        $etiquetaId = (int) ($_GET['etiqueta_id'] ?? 0);

        $modo = 'inicio';
        if ($q !== '') {
            $modo = 'busqueda';
        } elseif ($categoriaId > 0) {
            $modo = 'categoria';
        } elseif ($etiquetaId > 0) {
            $modo = 'etiqueta';
        }

        $resultadosBusqueda = [];
        $errorBusqueda = null;
        $sintesis = null;
        $tagsBusqueda = [];

        $categoriaNombre = null;
        $etiquetaNombre = null;
        $listado = [];
        $tagsListado = [];
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
        $porPagina = 12;
        $totalPaginas = 1;

        $catalogo = $categorias = $deInteres = [];
        $tagsCatalogo = $tagsInteres = [];
        $totalCatalogo = 0;
        $totalPaginasCatalogo = 1;

        if ($modo === 'busqueda') {
            // Gobernanza: rate limit por IP antes de gastar en la API (igual que el chat).
            $ip = ip_cliente();
            if (ChatModel::limiteExcedido($pdo, $ip, 'busqueda_articulo')) {
                $errorBusqueda = 'Demasiadas búsquedas seguidas. Espera unos minutos e intenta de nuevo.';
            } else {
                $vecConsulta = SearchModel::embeberConsulta($pdo, $config, $q);
                if ($vecConsulta === null) {
                    $errorBusqueda = 'No se pudo completar la búsqueda en este momento. Intenta de nuevo.';
                } else {
                    $resultadosBusqueda = SearchModel::buscarArticulosSimilares($pdo, $vecConsulta, 12);
                    try {
                        $pdo->prepare("INSERT INTO ai_usage (origen, kind, ip) VALUES ('busqueda_publica', 'busqueda_articulo', :ip)")
                            ->execute(['ip' => $ip]);
                    } catch (Throwable $e) {
                        error_log('ArticleController::index error registrando ai_usage de búsqueda: ' . $e->getMessage());
                    }
                    // Fail-open: si la síntesis con IA falla, la lista de resultados
                    // igual se muestra normalmente.
                    $sintesis = SearchModel::obtenerSintesisBusqueda($pdo, $config, $q, $resultadosBusqueda);

                    try {
                        $pdo->prepare('
                            INSERT INTO busqueda_log (consulta, n_resultados, con_sintesis, ip)
                            VALUES (:c, :n, :s, :ip)
                        ')->execute([
                            'c' => $q,
                            'n' => count($resultadosBusqueda),
                            's' => $sintesis !== null && $sintesis['respuesta'] !== '',
                            'ip' => $ip,
                        ]);
                    } catch (Throwable $e) {
                        error_log('ArticleController::index error registrando busqueda_log: ' . $e->getMessage());
                    }
                }
            }
            $tagsBusqueda = ArticleModel::tagsPorArticulos($pdo, array_column($resultadosBusqueda, 'id'));
        } elseif ($modo === 'categoria') {
            $categoriaNombre = CategoryModel::nombrePorId($pdo, $categoriaId);
            $resultado = ArticleModel::porCategoria($pdo, $categoriaId, $pagina, $porPagina);
            $listado = $resultado['items'];
            $totalPaginas = $resultado['totalPaginas'];
            $tagsListado = ArticleModel::tagsPorArticulos($pdo, array_column($listado, 'id'));
        } elseif ($modo === 'etiqueta') {
            $etiquetaNombre = TagModel::nombrePorId($pdo, $etiquetaId);
            $resultado = ArticleModel::porEtiqueta($pdo, $etiquetaId, $pagina, $porPagina);
            $listado = $resultado['items'];
            $totalPaginas = $resultado['totalPaginas'];
            $tagsListado = ArticleModel::tagsPorArticulos($pdo, array_column($listado, 'id'));
        } else {
            // Catálogo completo, paginado; categorías top; "de interés" (los que
            // más preguntas generan en el chat, rellenado con aleatorios si hace falta).
            $resultado = ArticleModel::catalogo($pdo, $pagina, $porPagina);
            $catalogo = $resultado['items'];
            $totalCatalogo = $resultado['total'];
            $totalPaginasCatalogo = $resultado['totalPaginas'];
            $tagsCatalogo = ArticleModel::tagsPorArticulos($pdo, array_column($catalogo, 'id'));

            $categorias = CategoryModel::topPorConteo($pdo, 12);

            $deInteres = ArticleModel::deInteres($pdo, 6);
            $tagsInteres = ArticleModel::tagsPorArticulos($pdo, array_column($deInteres, 'id'));
        }

        View::render('articulos/views/index.php', [
            'q' => $q,
            'modo' => $modo,
            'categoriaId' => $categoriaId,
            'etiquetaId' => $etiquetaId,
            'resultadosBusqueda' => $resultadosBusqueda,
            'errorBusqueda' => $errorBusqueda,
            'sintesis' => $sintesis,
            'tagsBusqueda' => $tagsBusqueda,
            'categoriaNombre' => $categoriaNombre,
            'etiquetaNombre' => $etiquetaNombre,
            'listado' => $listado,
            'tagsListado' => $tagsListado,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'catalogo' => $catalogo,
            'categorias' => $categorias,
            'deInteres' => $deInteres,
            'tagsCatalogo' => $tagsCatalogo,
            'tagsInteres' => $tagsInteres,
            'totalCatalogo' => $totalCatalogo,
            'totalPaginasCatalogo' => $totalPaginasCatalogo,
            'titulo' => 'Artículos',
            'descripcion' => 'Explora los artículos de Eduteka por categoría, etiqueta o con el buscador semántico.',
        ]);
    }

    // GET /articulos/ver.php?id= — detalle de un articulo publicado.
    public function show(Base $f3): void
    {
        $pdo = Db::pdo($f3);

        $id = (int) ($_GET['id'] ?? 0);
        $articulo = ArticleModel::buscarPublicadoPorId($pdo, $id);

        if (!$articulo) {
            http_response_code(404);
            View::render('articulos/views/ver.php', ['articulo' => null, 'titulo' => 'Artículo no encontrado']);
            return;
        }

        $etiquetas = ArticleModel::tagsDeArticulo($pdo, $id);
        $relacionados = ArticleModel::relacionados($pdo, $id, $articulo['category_id'] ? (int) $articulo['category_id'] : null, 5);

        $titulo = $articulo['title'];
        $descripcion = $articulo['summary'] ?: mb_substr(strip_tags($articulo['body']), 0, 160);
        $urlCanonica = rtrim((string) $f3->get('SITE_URL'), '/') . '/articulos/ver.php?id=' . $id;
        $contenidoHtml = markdown_render($articulo['body']);

        // Sugerencias del chat: precalculadas por IA una sola vez y guardadas en
        // articles.chat_sugerencias. Si un articulo aun no las tiene, se usa un
        // respaldo generico para que el widget nunca se vea vacio.
        $sugerenciasChat = $articulo['chat_sugerencias'] ? json_decode($articulo['chat_sugerencias'], true) : null;
        if (!$sugerenciasChat || count($sugerenciasChat) < 4) {
            $sugerenciasChat = [
                ['etiqueta' => 'Ideas para el aula', 'pregunta' => 'Dame ideas para implementar esto en el aula'],
                ['etiqueta' => 'Adaptación y diferenciación', 'pregunta' => '¿Cómo puedo adaptar esto a distintos niveles o necesidades de mis estudiantes?'],
                ['etiqueta' => 'Estrategias de evaluación', 'pregunta' => '¿Qué estrategias de evaluación me recomiendas para este contenido?'],
                ['etiqueta' => 'Fundamentos pedagógicos', 'pregunta' => 'Resume los fundamentos pedagógicos de este artículo'],
            ];
        }

        View::render('articulos/views/ver.php', [
            'articulo' => $articulo,
            'id' => $id,
            'etiquetas' => $etiquetas,
            'relacionados' => $relacionados,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'urlCanonica' => $urlCanonica,
            'contenidoHtml' => $contenidoHtml,
            'sugerenciasChat' => $sugerenciasChat,
        ]);
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
