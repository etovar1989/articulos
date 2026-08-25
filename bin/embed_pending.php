<?php
// Cola de indexacion RAG: procesa articulos con rag_status='pending', generando su
// embedding en embeddings_small via la MISMA funcion que usa el admin al guardar un
// articulo (reindexar_embedding_articulo, en articulos/lib/busqueda.php) — no se
// reimplementa la logica, se reutiliza tal cual para no divergir de produccion.
// Pensado para correr por cron cada 5 min (ver estandar de infraestructura); tambien
// sirve para el llenado inicial en desarrollo.

declare(strict_types=1);

$publicRoot = dirname(__DIR__) . '/public';
require $publicRoot . '/articulos/vendor/autoload.php';
require $publicRoot . '/articulos/lib/helpers.php';
require $publicRoot . '/articulos/lib/db.php';
require $publicRoot . '/articulos/lib/busqueda.php';

$config = require $publicRoot . '/articulos/config/config.php';
$pdo = db();

$quiet = in_array('--quiet', $argv, true);

$pendientes = $pdo->query("SELECT id, title, body FROM articles WHERE rag_status = 'pending' ORDER BY id")->fetchAll();

if (!$pendientes) {
    if (!$quiet) {
        echo "Sin articulos pendientes de indexar.\n";
    }
    exit(0);
}

echo count($pendientes) . " articulos pendientes de indexar.\n";

$ok = 0;
$fallidos = 0;
foreach ($pendientes as $a) {
    $exito = reindexar_embedding_articulo($pdo, $config, (int) $a['id'], $a['title'], $a['body']);
    if ($exito) {
        $ok++;
    } else {
        $fallidos++;
        fwrite(STDERR, "  ERROR indexando articulo {$a['id']}: {$a['title']}\n");
    }
    if (($ok + $fallidos) % 100 === 0) {
        echo "  ... " . ($ok + $fallidos) . "/" . count($pendientes) . "\n";
    }
}

echo "Listo. OK: $ok  Errores: $fallidos\n";
exit($fallidos > 0 ? 1 : 0);
