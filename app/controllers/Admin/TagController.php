<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Lib\Db;
use App\Lib\View;
use App\Models\TagModel;
use Base;
use PDOException;

final class TagController extends AdminController
{
    public function index(Base $f3): void
    {
        $pdo = Db::pdo($f3);

        $q = trim((string) ($_GET['q'] ?? ''));
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
        $porPagina = 40;

        $resultado = TagModel::adminListarConConteo($pdo, $q, $pagina, $porPagina);

        View::render('admin/views/etiquetas/index.php', [
            'titulo' => 'Etiquetas',
            'q' => $q,
            'pagina' => $pagina,
            'etiquetas' => $resultado['items'],
            'totalFilas' => $resultado['total'],
            'totalPaginas' => $resultado['totalPaginas'],
        ]);
    }

    public function guardar(Base $f3): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/etiquetas/index.php');
        }
        csrf_check();

        $pdo = Db::pdo($f3);
        $id = ($_POST['id'] ?? '') !== '' ? (int) $_POST['id'] : null;
        $name = mb_strtolower(trim((string) ($_POST['name'] ?? '')));

        if ($name === '') {
            flash('danger', 'El nombre de la etiqueta no puede estar vacío.');
            redirect('/admin/etiquetas/index.php');
        }

        try {
            TagModel::guardar($pdo, $id, $name);
            flash('success', 'Etiqueta guardada.');
        } catch (PDOException $e) {
            flash('danger', 'Ya existe una etiqueta con ese nombre (usa "Fusionar" en vez de renombrar).');
        }

        redirect('/admin/etiquetas/index.php');
    }

    public function eliminar(Base $f3): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/etiquetas/index.php');
        }
        csrf_check();

        $pdo = Db::pdo($f3);
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            flash('danger', 'Etiqueta inválida.');
        } elseif (TagModel::estaEnUso($pdo, $id)) {
            flash('danger', 'No se puede eliminar: la etiqueta todavía tiene artículos asociados.');
        } else {
            $borrado = TagModel::eliminar($pdo, $id);
            flash($borrado ? 'success' : 'danger', $borrado ? 'Etiqueta eliminada.' : 'La etiqueta no existe.');
        }

        redirect('/admin/etiquetas/index.php');
    }

    public function fusionar(Base $f3): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/etiquetas/index.php');
        }
        csrf_check();

        $pdo = Db::pdo($f3);
        $origenNombre = mb_strtolower(trim((string) ($_POST['origen'] ?? '')));
        $destinoNombre = mb_strtolower(trim((string) ($_POST['destino'] ?? '')));

        $resultado = TagModel::fusionar($pdo, $origenNombre, $destinoNombre);
        flash($resultado['ok'] ? 'success' : 'danger', $resultado['mensaje']);

        redirect('/admin/etiquetas/index.php');
    }
}
