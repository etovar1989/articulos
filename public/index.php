<?php

declare(strict_types=1);

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
chdir($root);

$f3 = \Base::instance();
$f3->set('ROOT_DIR', $root);
$f3->config('app/config.ini');
if (is_file($root . '/app/config.local.ini')) {
    $f3->config('app/config.local.ini');
}
$f3->config('app/routes.ini');

$f3->set('DB', new \DB\SQL($f3->get('DB_DSN'), $f3->get('DB_USER'), $f3->get('DB_PASS')));

// JSON para /api/*, pagina amigable para el resto. El trace solo sale con DEBUG >= 2,
// para que produccion (DEBUG=0) nunca filtre rutas internas.
$f3->set('ONERROR', function (Base $f3) {
    $err = $f3->get('ERROR');
    $code = (int) ($err['code'] ?? 500);
    if (preg_match('~^/api/~', (string) $f3->get('PATH'))) {
        header('Content-Type: application/json; charset=utf-8', true, $code);
        echo json_encode([
            'error' => $err['text'] ?? 'Error interno',
            'code' => $code,
            'trace' => $f3->get('DEBUG') >= 2 ? ($err['trace'] ?? null) : null,
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code($code);
        echo "<h1>Error {$code}</h1><p>" . htmlspecialchars((string) ($err['text'] ?? '')) . '</p>';
    }
});

$f3->run();
