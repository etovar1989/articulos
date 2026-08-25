<?php
declare(strict_types=1);
require __DIR__ . '/lib/helpers.php';
require __DIR__ . '/lib/db.php';
require __DIR__ . '/lib/busqueda.php';
require __DIR__ . '/lib/chat_general.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}

$config = require __DIR__ . '/config/config.php';
$pdo = db();
$ip = ip_cliente();

// Gobernanza: rate limit por IP antes de gastar en la API (mismo patrón que
// el chat por artículo y la búsqueda).
$LIMITE = 20;
$VENTANA_MIN = 10;
$check = $pdo->prepare("
    SELECT count(*) FROM ai_usage
    WHERE ip = :ip AND kind = 'chat_general' AND created_at > now() - make_interval(mins => :ventana)
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

$mensaje = trim((string) ($entrada['message'] ?? ''));
$historialCrudo = is_array($entrada['history'] ?? null) ? $entrada['history'] : [];

if ($mensaje === '' || mb_strlen($mensaje) > 2000) {
    http_response_code(400);
    echo json_encode(['error' => 'Pregunta vacía o demasiado larga.']);
    exit;
}

// Roles saneados a user/assistant, últimos 6 turnos — un cliente malicioso no
// puede inyectar un mensaje "system" (rag-baseline §6.4).
$historial = [];
foreach (array_slice($historialCrudo, -12) as $turno) {
    $rol = ($turno['role'] ?? '') === 'assistant' ? 'assistant' : 'user';
    $contenido = trim((string) ($turno['content'] ?? ''));
    if ($contenido !== '') {
        $historial[] = ['role' => $rol, 'content' => mb_substr($contenido, 0, 2000)];
    }
}

$resultado = generar_respuesta_rag_general($pdo, $config, $mensaje, $historial);

echo json_encode([
    'reply' => $resultado['respuesta'],
    'grounding' => $resultado['grounding'],
    'citados' => $resultado['citados'],
]);
