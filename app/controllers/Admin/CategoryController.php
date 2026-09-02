<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Lib\Db;
use App\Lib\View;
use App\Models\CategoryModel;
use Base;
use PDOException;

final class CategoryController extends AdminController
{
    public function index(Base $f3): void
    {
        $pdo = Db::pdo($f3);

        View::render('admin/views/categorias/index.php', [
            'titulo' => 'Categorías',
            'categorias' => CategoryModel::adminListarConConteo($pdo),
        ]);
    }

    public function guardar(Base $f3): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/categorias/index.php');
        }
        csrf_check();

        $pdo = Db::pdo($f3);
        $id = ($_POST['id'] ?? '') !== '' ? (int) $_POST['id'] : null;
        $name = trim((string) ($_POST['name'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? '')) ?: null;

        if ($name === '') {
            flash('danger', 'El nombre de la categoría no puede estar vacío.');
            redirect('/admin/categorias/index.php');
        }

        try {
            CategoryModel::guardar($pdo, $id, $name, $description);
            flash('success', 'Categoría guardada.');
        } catch (PDOException $e) {
            flash('danger', 'Ya existe una categoría con ese nombre.');
        }

        redirect('/admin/categorias/index.php');
    }
}
