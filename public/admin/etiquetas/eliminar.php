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

$id = (int) ($_POST['id'] ?? 0);
$pdo = db();

$enUso = $pdo->prepare('SELECT count(*) FROM article_tags WHERE tag_id = :id');
$enUso->execute(['id' => $id]);

if ($id <= 0) {
    flash('danger', 'Etiqueta inválida.');
} elseif ((int) $enUso->fetchColumn() > 0) {
    flash('danger', 'No se puede eliminar: la etiqueta todavía tiene artículos asociados.');
} else {
    $borrado = $pdo->prepare('DELETE FROM tags WHERE id = :id');
    $borrado->execute(['id' => $id]);
    flash($borrado->rowCount() > 0 ? 'success' : 'danger',
        $borrado->rowCount() > 0 ? 'Etiqueta eliminada.' : 'La etiqueta no existe.');
}

redirect('/admin/etiquetas/index.php');
