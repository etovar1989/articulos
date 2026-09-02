<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista del listado de articulos (admin). Recibe los datos ya resueltos por
 * App\Controllers\Admin\ArticleController::index().
 *
 * @var string $titulo
 * @var string $q
 * @var string $categoriaId
 * @var string $etiquetaId
 * @var string $estado
 * @var int $pagina
 * @var array $articulos
 * @var int $totalFilas
 * @var int $totalPaginas
 * @var string|null $etiquetaNombre
 * @var array $categorias
 */
require __DIR__ . '/../../templates/header.php';
?>
<div class="flex items-center justify-between mb-4 flex-wrap gap-2">
    <h1 class="text-2xl font-bold">Artículos <span class="text-gray-400 text-base font-normal">(<?= $totalFilas ?>)</span></h1>
    <a href="/admin/articulos/form.php" class="<?= e(tw_btn('primario')) ?>">+ Nuevo artículo</a>
</div>

<?php if ($etiquetaNombre !== null): ?>
<div class="flex items-center gap-2 mb-4">
    <span class="text-sm text-gray-500">Filtrando por etiqueta:</span>
    <span class="inline-flex items-center gap-2 bg-marca-grisClaro/40 text-marca-azul text-sm px-3 py-1 rounded">
        <?= e($etiquetaNombre) ?>
        <a href="/admin/articulos/index.php?q=<?= urlencode($q) ?>&categoria_id=<?= urlencode((string) $categoriaId) ?>&estado=<?= urlencode($estado) ?>" class="font-bold">&times;</a>
    </span>
</div>
<?php endif; ?>

<form method="get" class="flex flex-wrap gap-2 mb-4">
    <input type="hidden" name="etiqueta_id" value="<?= e((string) $etiquetaId) ?>">
    <input type="text" name="q" value="<?= e($q) ?>" class="<?= e(tw_input()) ?> md:w-64" placeholder="Buscar por título...">
    <select name="categoria_id" class="<?= e(tw_input()) ?> md:w-56">
        <option value="">Todas las categorías</option>
        <?php foreach ($categorias as $c): ?>
            <option value="<?= (int) $c['id'] ?>" <?= (string) $categoriaId === (string) $c['id'] ? 'selected' : '' ?>>
                <?= e($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="estado" class="<?= e(tw_input()) ?> md:w-48">
        <option value="">Todos los estados</option>
        <option value="publicado" <?= $estado === 'publicado' ? 'selected' : '' ?>>Publicado</option>
        <option value="borrador" <?= $estado === 'borrador' ? 'selected' : '' ?>>Borrador</option>
        <option value="archivado" <?= $estado === 'archivado' ? 'selected' : '' ?>>Archivado</option>
    </select>
    <button type="submit" class="<?= e(tw_btn('outline')) ?>">Filtrar</button>
</form>

<div class="overflow-x-auto <?= e(tw_card()) ?>">
<table class="w-full text-sm">
    <thead class="bg-marca-grisClaro/20 text-left text-gray-600">
        <tr>
            <th class="px-3 py-2">ID</th>
            <th class="px-3 py-2">Título</th>
            <th class="px-3 py-2">Categoría</th>
            <th class="px-3 py-2">Fecha</th>
            <th class="px-3 py-2">Estado</th>
            <th class="px-3 py-2">RAG</th>
            <th class="px-3 py-2"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articulos as $a): ?>
        <tr class="border-t border-gray-100">
            <td class="px-3 py-2"><?= (int) $a['id'] ?></td>
            <td class="px-3 py-2"><?= e($a['title']) ?></td>
            <td class="px-3 py-2"><?php if ($a['categoria_nombre']): ?>
                <span class="inline-block bg-marca-grisClaro/50 text-marca-morado text-xs px-2 py-0.5 rounded"><?= e($a['categoria_nombre']) ?></span>
            <?php endif; ?></td>
            <td class="px-3 py-2"><?= e($a['article_date']) ?></td>
            <td class="px-3 py-2"><span class="font-semibold <?= e(clase_estado($a['estado'])) ?>"><?= e($a['estado']) ?></span></td>
            <td class="px-3 py-2"><span class="<?= e(clase_rag($a['rag_status'])) ?>"><?= e($a['rag_status']) ?></span></td>
            <td class="px-3 py-2 text-right">
                <a href="/admin/articulos/form.php?id=<?= (int) $a['id'] ?>" class="<?= e(tw_btn('outline-sm')) ?>">Editar</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$articulos): ?>
        <tr><td colspan="7" class="text-center text-gray-400 py-6">No se encontraron artículos.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<div class="mt-4">
<?= paginacion($pagina, $totalPaginas, '/admin/articulos/index.php?q=' . urlencode($q) . '&categoria_id=' . urlencode((string) $categoriaId) . '&etiqueta_id=' . urlencode((string) $etiquetaId) . '&estado=' . urlencode($estado)) ?>
</div>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
