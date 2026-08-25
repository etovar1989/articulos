<?php
// Aplica las migraciones pendientes de db/migrations/, una transaccion por archivo,
// en orden por nombre (NNNN_descripcion.sql). --status solo lista, no aplica nada.

declare(strict_types=1);

$f3 = require __DIR__ . '/cli_bootstrap.php';

$pdo = new PDO($f3->get('DB_DSN'), $f3->get('DB_USER'), $f3->get('DB_PASS'), [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec('
    CREATE TABLE IF NOT EXISTS schema_migrations (
        version    text PRIMARY KEY,
        applied_at timestamptz NOT NULL DEFAULT now()
    )
');

$aplicadas = $pdo->query('SELECT version FROM schema_migrations')->fetchAll(PDO::FETCH_COLUMN);

$dir = dirname(__DIR__) . '/db/migrations';
$archivos = glob($dir . '/*.sql');
sort($archivos, SORT_STRING);

$pendientes = [];
foreach ($archivos as $archivo) {
    $version = basename($archivo, '.sql');
    if (!in_array($version, $aplicadas, true)) {
        $pendientes[] = ['version' => $version, 'archivo' => $archivo];
    }
}

$soloEstado = in_array('--status', $argv, true);

if (!$pendientes) {
    echo "No hay migraciones pendientes.\n";
    exit(0);
}

echo count($pendientes) . " migracion(es) pendiente(s):\n";
foreach ($pendientes as $p) {
    echo "  - {$p['version']}\n";
}

if ($soloEstado) {
    exit(0);
}

foreach ($pendientes as $p) {
    echo "Aplicando {$p['version']}...\n";
    $sql = file_get_contents($p['archivo']);
    $pdo->beginTransaction();
    try {
        $pdo->exec($sql);
        $pdo->prepare('INSERT INTO schema_migrations (version) VALUES (:v)')
            ->execute(['v' => $p['version']]);
        $pdo->commit();
        echo "  OK\n";
    } catch (Throwable $e) {
        $pdo->rollBack();
        fwrite(STDERR, "  ERROR en {$p['version']}: {$e->getMessage()}\n");
        exit(1);
    }
}

echo "Listo.\n";
