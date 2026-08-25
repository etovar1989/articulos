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
$id = $_POST['id'] !== '' ? (int) $_POST['id'] : null;
$name = mb_strtolower(trim((string) ($_POST['name'] ?? '')));

if ($name === '') {
    flash('danger', 'El nombre de la etiqueta no puede estar vacío.');
    redirect('/admin/etiquetas/index.php');
}

try {
    if ($id) {
        $stmt = $pdo->prepare('UPDATE tags SET name = :name WHERE id = :id');
        $stmt->execute(['name' => $name, 'id' => $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO tags (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);
    }
    flash('success', 'Etiqueta guardada.');
} catch (PDOException $e) {
    flash('danger', 'Ya existe una etiqueta con ese nombre (usa "Fusionar" en vez de renombrar).');
}

redirect('/admin/etiquetas/index.php');
