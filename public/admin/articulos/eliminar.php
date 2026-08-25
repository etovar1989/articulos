<?php
declare(strict_types=1);
require __DIR__ . '/../lib/auth.php';
require __DIR__ . '/../lib/helpers.php';
require __DIR__ . '/../lib/db.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/articulos/index.php');
}
csrf_check();

$id = (int) ($_POST['id'] ?? 0);

// Archivar, no borrar de verdad: se saca de la recuperación/listado público
// pero sus chunks e historial quedan intactos (mismo criterio que rag-baseline).
$stmt = db()->prepare("UPDATE articles SET estado = 'archivado' WHERE id = :id");
$stmt->execute(['id' => $id]);

flash('success', 'Artículo archivado.');
redirect('/admin/articulos/index.php');
