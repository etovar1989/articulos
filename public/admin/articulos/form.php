<?php
declare(strict_types=1);
require __DIR__ . '/../lib/auth.php';
require __DIR__ . '/../lib/helpers.php';
require __DIR__ . '/../lib/db.php';
requiere_login();

$pdo = db();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$articulo = [
    'id' => null, 'title' => '', 'slug' => '', 'summary' => '', 'body' => '',
    'author' => '', 'category_id' => '', 'article_date' => '', 'estado' => 'borrador',
];
$etiquetasTexto = '';

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $fila = $stmt->fetch();
    if (!$fila) {
        flash('danger', 'Artículo no encontrado.');
        redirect('/admin/articulos/index.php');
    }
    $articulo = $fila;

    $tagsStmt = $pdo->prepare('
        SELECT t.name FROM tags t
        JOIN article_tags at2 ON at2.tag_id = t.id
        WHERE at2.article_id = :id
        ORDER BY t.name
    ');
    $tagsStmt->execute(['id' => $id]);
    $etiquetasTexto = implode(', ', array_column($tagsStmt->fetchAll(), 'name'));
}

$categorias = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();

$titulo = $id ? 'Editar artículo' : 'Nuevo artículo';
require __DIR__ . '/../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-6"><?= e($titulo) ?></h1>

<form method="post" action="/admin/articulos/guardar.php" class="<?= e(tw_card()) ?> p-5">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= e((string) $articulo['id']) ?>">

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-8">
            <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
            <input type="text" name="title" class="<?= e(tw_input()) ?>" required
                   value="<?= e($articulo['title']) ?>">
        </div>
        <div class="md:col-span-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
            <input type="text" name="slug" class="<?= e(tw_input()) ?>"
                   value="<?= e($articulo['slug']) ?>" placeholder="se genera del título si se deja vacío">
        </div>

        <div class="md:col-span-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
            <input type="text" name="author" class="<?= e(tw_input()) ?>" value="<?= e($articulo['author'] ?? '') ?>">
        </div>
        <div class="md:col-span-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
            <select name="category_id" class="<?= e(tw_input()) ?>">
                <option value="">Sin categoría</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= (string) $articulo['category_id'] === (string) $c['id'] ? 'selected' : '' ?>>
                        <?= e($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
            <input type="date" name="article_date" class="<?= e(tw_input()) ?>" value="<?= e((string) $articulo['article_date']) ?>">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select name="estado" class="<?= e(tw_input()) ?>">
                <option value="borrador" <?= $articulo['estado'] === 'borrador' ? 'selected' : '' ?>>Borrador</option>
                <option value="publicado" <?= $articulo['estado'] === 'publicado' ? 'selected' : '' ?>>Publicado</option>
                <option value="archivado" <?= $articulo['estado'] === 'archivado' ? 'selected' : '' ?>>Archivado</option>
            </select>
        </div>

        <div class="md:col-span-12">
            <label class="block text-sm font-medium text-gray-700 mb-1">Resumen</label>
            <textarea name="summary" class="<?= e(tw_input()) ?>" rows="2"><?= e($articulo['summary'] ?? '') ?></textarea>
        </div>

        <div class="md:col-span-12">
            <label class="block text-sm font-medium text-gray-700 mb-1">Contenido (Markdown)</label>
            <textarea name="body" class="<?= e(tw_input()) ?> font-mono" rows="16" required><?= e($articulo['body']) ?></textarea>
        </div>

        <div class="md:col-span-12">
            <label class="block text-sm font-medium text-gray-700 mb-1">Etiquetas (separadas por coma)</label>
            <input type="text" name="etiquetas" class="<?= e(tw_input()) ?>"
                   value="<?= e($etiquetasTexto) ?>" placeholder="ej: robotica, pensamiento computacional">
        </div>
    </div>

    <div class="mt-6 flex gap-2">
        <button type="submit" class="<?= e(tw_btn('primario')) ?>">Guardar</button>
        <a href="/admin/articulos/index.php" class="<?= e(tw_btn('outline')) ?>">Cancelar</a>
    </div>
</form>

<?php if ($id): ?>
<form method="post" action="/admin/articulos/eliminar.php" class="mt-3"
      onsubmit="return confirm('¿Archivar este artículo? No se borra de la base, solo se saca de circulación.');">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) $id ?>">
    <button type="submit" class="<?= e(tw_btn('outline-danger')) ?>">Archivar</button>
</form>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>
