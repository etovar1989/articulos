<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista del listado de categorias. Recibe los datos ya resueltos por
 * App\Controllers\Admin\CategoryController::index().
 *
 * @var string $titulo
 * @var array $categorias
 */
require __DIR__ . '/../../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-6">Categorías <span class="text-gray-400 text-base font-normal">(<?= count($categorias) ?>)</span></h1>

<div class="<?= e(tw_card()) ?> p-4 mb-6">
    <h2 class="text-sm font-semibold text-gray-600 mb-2">Nueva categoría</h2>
    <form method="post" action="/admin/categorias/guardar.php" class="flex flex-wrap gap-2">
        <?= csrf_field() ?>
        <input type="text" name="name" class="<?= e(tw_input()) ?> md:w-56" placeholder="Nombre" required>
        <input type="text" name="description" class="<?= e(tw_input()) ?> md:flex-1" placeholder="Descripción (opcional)">
        <button type="submit" class="<?= e(tw_btn('primario')) ?>">Crear</button>
    </form>
</div>

<div class="<?= e(tw_card()) ?>">
    <div class="hidden md:flex gap-2 px-4 pt-3 pb-2 text-xs text-gray-400 border-b border-gray-100">
        <div class="w-1/3">Nombre</div>
        <div class="flex-1">Descripción</div>
        <div class="w-20">Artículos</div>
        <div class="w-28"></div>
    </div>
    <?php foreach ($categorias as $c): ?>
    <form method="post" action="/admin/categorias/guardar.php" class="flex flex-wrap items-center gap-2 px-4 py-2 border-b border-gray-100 last:border-0">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
        <input type="text" name="name" value="<?= e($c['name']) ?>" class="<?= e(tw_input()) ?> md:w-1/3">
        <input type="text" name="description" value="<?= e($c['description'] ?? '') ?>" class="<?= e(tw_input()) ?> md:flex-1">
        <a href="/admin/articulos/index.php?categoria_id=<?= (int) $c['id'] ?>" class="w-20 text-marca-azul text-sm"><?= (int) $c['n_articulos'] ?></a>
        <button type="submit" class="<?= e(tw_btn('outline-sm')) ?>">Guardar</button>
    </form>
    <?php endforeach; ?>
</div>

<p class="text-gray-400 text-xs mt-4">Para eliminar una categoría, primero reasigna sus artículos a otra (edítalos desde el listado de artículos); una categoría con artículos asignados no se puede borrar.</p>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
