<?php
declare(strict_types=1);
require __DIR__ . '/../lib/auth.php';
require __DIR__ . '/../lib/helpers.php';
require __DIR__ . '/../lib/db.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/categorias/index.php');
}
csrf_check();

$pdo = db();
$id = $_POST['id'] !== '' ? (int) $_POST['id'] : null;
$name = trim((string) ($_POST['name'] ?? ''));
$description = trim((string) ($_POST['description'] ?? '')) ?: null;

if ($name === '') {
    flash('danger', 'El nombre de la categoría no puede estar vacío.');
    redirect('/admin/categorias/index.php');
}

try {
    if ($id) {
        $stmt = $pdo->prepare('UPDATE categories SET name = :name, description = :description WHERE id = :id');
        $stmt->execute(['name' => $name, 'description' => $description, 'id' => $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
        $stmt->execute(['name' => $name, 'description' => $description]);
    }
    flash('success', 'Categoría guardada.');
} catch (PDOException $e) {
    flash('danger', 'Ya existe una categoría con ese nombre.');
}

redirect('/admin/categorias/index.php');
