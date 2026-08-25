<?php
declare(strict_types=1);
require __DIR__ . '/lib/helpers.php';
require __DIR__ . '/lib/db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}

$config = require __DIR__ . '/config/config.php';
$pdo = db();
$ip = ip_cliente();

// --- Gobernanza: rate limit por IP antes de gastar en la API ---
// (rag-baseline principio 9: permiso/rate-limit/cuota antes de cualquier llamada)
$LIMITE = 20;
$VENTANA_MIN = 10;
$check = $pdo->prepare("
    SELECT count(*) FROM ai_usage
    WHERE ip = :ip AND kind = 'chat_articulo' AND created_at > now() - make_interval(mins => :ventana)
");
$check->execute(['ip' => $ip, 'ventana' => $VENTANA_MIN]);
if ((int) $check->fetchColumn() >= $LIMITE) {
    http_response_code(429);
    echo json_encode(['error' => 'Demasiadas preguntas seguidas. Espera unos minutos e intenta de nuevo.']);
    exit;
}

$entrada = json_decode(file_get_contents('php://input'), true);
if (!is_array($entrada)) {
    http_response_code(400);
    echo json_encode(['error' => 'Solicitud inválida.']);
    exit;
}

$articuloId = (int) ($entrada['article_id'] ?? 0);
$mensaje = trim((string) ($entrada['message'] ?? ''));
$historialCrudo = is_array($entrada['history'] ?? null) ? $entrada['history'] : [];

if ($mensaje === '' || mb_strlen($mensaje) > 2000) {
    http_response_code(400);
    echo json_encode(['error' => 'Pregunta vacía o demasiado larga.']);
    exit;
}

$stmt = $pdo->prepare("SELECT title, body FROM articles WHERE id = :id AND estado = 'publicado'");
$stmt->execute(['id' => $articuloId]);
$articulo = $stmt->fetch();
if (!$articulo) {
    http_response_code(404);
    echo json_encode(['error' => 'Artículo no encontrado.']);
    exit;
}

// Roles saneados a user/assistant, últimos 6 turnos — un cliente malicioso
// no puede inyectar un mensaje "system" (rag-baseline §6.4).
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
    error_log('chat.php OpenAI error: ' . $errorCurl . ' HTTP ' . $httpCode . ' body=' . $respuestaCruda);
    exit;
}

$datos = json_decode($respuestaCruda, true);
$respuesta = $datos['choices'][0]['message']['content'] ?? null;

if (!$respuesta) {
    http_response_code(502);
    echo json_encode(['error' => 'No se pudo generar una respuesta.']);
    exit;
}

// Registrar consumo y la pregunta/respuesta (envuelto en try/catch: el logging
// jamás tumba la respuesta principal). ai_usage sirve para el costo agregado;
// chat_log guarda el texto real — es la base para perfilar qué le interesa
// preguntar a la gente sobre cada artículo, no solo cuánto cuesta.
try {
    $tokensIn = $datos['usage']['prompt_tokens'] ?? null;
    $tokensOut = $datos['usage']['completion_tokens'] ?? null;

    $pdo->prepare('
        INSERT INTO ai_usage (origen, kind, tokens_in, tokens_out, ip, article_id)
        VALUES (\'chat_publico\', \'chat_articulo\', :tin, :tout, :ip, :aid)
    ')->execute(['tin' => $tokensIn, 'tout' => $tokensOut, 'ip' => $ip, 'aid' => $articuloId]);

    $pdo->prepare('
        INSERT INTO chat_log (article_id, pregunta, respuesta, tokens_in, tokens_out, ip)
        VALUES (:aid, :pregunta, :respuesta, :tin, :tout, :ip)
    ')->execute([
        'aid' => $articuloId, 'pregunta' => $mensaje, 'respuesta' => $respuesta,
        'tin' => $tokensIn, 'tout' => $tokensOut, 'ip' => $ip,
    ]);
} catch (Throwable $e) {
    error_log('chat.php error registrando métricas: ' . $e->getMessage());
}

echo json_encode(['reply' => $respuesta]);
