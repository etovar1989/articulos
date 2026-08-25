<?php
declare(strict_types=1);
require __DIR__ . '/../lib/auth.php';
require __DIR__ . '/../lib/helpers.php';
require __DIR__ . '/../lib/db.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/etiquetas/index.php');
}
csrf_check();

$pdo = db();
$origenNombre = mb_strtolower(trim((string) ($_POST['origen'] ?? '')));
$destinoNombre = mb_strtolower(trim((string) ($_POST['destino'] ?? '')));

if ($origenNombre === '' || $destinoNombre === '' || $origenNombre === $destinoNombre) {
    flash('danger', 'Indica dos etiquetas distintas para fusionar.');
    redirect('/admin/etiquetas/index.php');
}

$buscar = $pdo->prepare('SELECT id FROM tags WHERE name = :n');
$buscar->execute(['n' => $origenNombre]);
$origenId = $buscar->fetchColumn();

$buscar->execute(['n' => $destinoNombre]);
$destinoId = $buscar->fetchColumn();

if (!$origenId || !$destinoId) {
    flash('danger', 'No se encontró alguna de las dos etiquetas.');
    redirect('/admin/etiquetas/index.php');
}

$pdo->beginTransaction();
try {
    // Mover asociaciones que no generen duplicado, descartar las que ya existan en destino
    $pdo->prepare('
        UPDATE article_tags SET tag_id = :destino
        WHERE tag_id = :origen
          AND article_id NOT IN (SELECT article_id FROM article_tags WHERE tag_id = :destino)
    ')->execute(['destino' => $destinoId, 'origen' => $origenId]);

    $pdo->prepare('DELETE FROM article_tags WHERE tag_id = :origen')->execute(['origen' => $origenId]);
    $pdo->prepare('DELETE FROM tags WHERE id = :origen')->execute(['origen' => $origenId]);

    $pdo->commit();
    flash('success', "Etiqueta \"$origenNombre\" fusionada dentro de \"$destinoNombre\".");
} catch (Throwable $e) {
    $pdo->rollBack();
    flash('danger', 'Error al fusionar: ' . $e->getMessage());
}

redirect('/admin/etiquetas/index.php');
