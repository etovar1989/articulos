<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Lib\Db;
use App\Lib\View;
use App\Models\ArticleModel;
use App\Models\TagModel;
use Base;

final class ArticleController extends AdminController
{
    public function index(Base $f3): void
    {
        $pdo = Db::pdo($f3);

        $q = trim((string) ($_GET['q'] ?? ''));
        $categoriaId = (string) ($_GET['categoria_id'] ?? '');
        $etiquetaId = (string) ($_GET['etiqueta_id'] ?? '');
        $estado = (string) ($_GET['estado'] ?? '');
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
        $porPagina = 20;

        $resultado = ArticleModel::adminListar($pdo, $q, $categoriaId, $etiquetaId, $estado, $pagina, $porPagina);

        $etiquetaNombre = null;
        if ($etiquetaId !== '') {
            $etiquetaNombre = TagModel::nombrePorId($pdo, (int) $etiquetaId);
        }

        $categorias = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();

        View::render('admin/views/articulos/index.php', [
            'titulo' => 'Artículos',
            'q' => $q,
            'categoriaId' => $categoriaId,
            'etiquetaId' => $etiquetaId,
            'estado' => $estado,
            'pagina' => $pagina,
            'articulos' => $resultado['items'],
            'totalFilas' => $resultado['total'],
            'totalPaginas' => $resultado['totalPaginas'],
            'etiquetaNombre' => $etiquetaNombre,
            'categorias' => $categorias,
        ]);
    }

    public function form(Base $f3): void
    {
        $pdo = Db::pdo($f3);
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $articulo = [
            'id' => null, 'title' => '', 'slug' => '', 'summary' => '', 'body' => '',
            'author' => '', 'category_id' => '', 'article_date' => '', 'estado' => 'borrador',
        ];
        $etiquetasTexto = '';

        if ($id) {
            $fila = ArticleModel::adminBuscarPorId($pdo, $id);
            if (!$fila) {
                flash('danger', 'Artículo no encontrado.');
                redirect('/admin/articulos/index.php');
            }
            $articulo = $fila;
            $etiquetasTexto = implode(', ', array_column(ArticleModel::tagsDeArticulo($pdo, $id), 'name'));
        }

        $categorias = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();

        View::render('admin/views/articulos/form.php', [
            'titulo' => $id ? 'Editar artículo' : 'Nuevo artículo',
            'id' => $id,
            'articulo' => $articulo,
            'etiquetasTexto' => $etiquetasTexto,
            'categorias' => $categorias,
        ]);
    }

    public function guardar(Base $f3): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/articulos/index.php');
        }
        csrf_check();

        $pdo = Db::pdo($f3);

        $id = ($_POST['id'] ?? '') !== '' ? (int) $_POST['id'] : null;
        $title = trim((string) ($_POST['title'] ?? ''));
        $body = (string) ($_POST['body'] ?? '');
        $campos = [
            'title' => $title,
            'body' => $body,
            'summary' => trim((string) ($_POST['summary'] ?? '')) ?: null,
            'author' => trim((string) ($_POST['author'] ?? '')) ?: null,
            'category_id' => $_POST['category_id'] !== '' ? (int) $_POST['category_id'] : null,
            'article_date' => trim((string) ($_POST['article_date'] ?? '')) ?: null,
            'estado' => in_array($_POST['estado'] ?? '', ['borrador', 'publicado', 'archivado'], true)
                ? $_POST['estado'] : 'borrador',
            'slug' => trim((string) ($_POST['slug'] ?? '')),
            'etiquetas' => array_filter(array_map('trim', explode(',', (string) ($_POST['etiquetas'] ?? '')))),
        ];

        if ($title === '' || $body === '') {
            flash('danger', 'Título y contenido son obligatorios.');
            redirect($id ? "/admin/articulos/form.php?id=$id" : '/admin/articulos/form.php');
        }

        $configIA = [
            'openai_api_key' => (string) $f3->get('OPENAI_API_KEY'),
            'chat_model' => (string) $f3->get('OPENAI_CHAT_MODEL'),
        ];

        $resultado = ArticleModel::guardarDesdeAdmin($pdo, $configIA, $id, $campos);

        if ($resultado['avisoReindex']) {
            flash('warning', $resultado['avisoReindex']);
        }
        flash('success', 'Artículo guardado correctamente.');
        redirect('/admin/articulos/form.php?id=' . $resultado['id']);
    }

    public function eliminar(Base $f3): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/articulos/index.php');
        }
        csrf_check();

        $pdo = Db::pdo($f3);
        $id = (int) ($_POST['id'] ?? 0);
        ArticleModel::archivar($pdo, $id);

        flash('success', 'Artículo archivado.');
        redirect('/admin/articulos/index.php');
    }
}
