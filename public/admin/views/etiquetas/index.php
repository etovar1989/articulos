<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista del listado de etiquetas. Recibe los datos ya resueltos por
 * App\Controllers\Admin\TagController::index().
 *
 * @var string $titulo
 * @var string $q
 * @var int $pagina
 * @var array $etiquetas
 * @var int $totalFilas
 * @var int $totalPaginas
 */
require __DIR__ . '/../../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-6">Etiquetas <span class="text-gray-400 text-base font-normal">(<?= $totalFilas ?>)</span></h1>

<div class="<?= e(tw_card()) ?> p-4 mb-4">
    <h2 class="text-sm font-semibold text-gray-600 mb-1">Fusionar etiquetas duplicadas</h2>
    <p class="text-xs text-gray-400 mb-2">Mueve todos los artículos de la etiqueta "origen" hacia la "destino" y borra la de origen. Útil para juntar variantes como "tic" y "tics".</p>
    <form method="post" action="/admin/etiquetas/fusionar.php" class="flex flex-wrap gap-2"
          onsubmit="return confirm('¿Fusionar estas dos etiquetas? La de origen se elimina.');">
        <?= csrf_field() ?>
        <input type="text" name="origen" class="<?= e(tw_input()) ?> md:w-56" placeholder="Etiqueta origen (se borra)" required>
        <input type="text" name="destino" class="<?= e(tw_input()) ?> md:w-56" placeholder="Etiqueta destino (queda)" required>
        <button type="submit" class="<?= e(tw_btn('outline')) ?>">Fusionar</button>
    </form>
</div>

<form method="get" class="flex flex-wrap gap-2 mb-4">
    <input type="text" name="q" value="<?= e($q) ?>" class="<?= e(tw_input()) ?> md:w-64" placeholder="Buscar etiqueta...">
    <button type="submit" class="<?= e(tw_btn('outline')) ?>">Buscar</button>
</form>

<div class="<?= e(tw_card()) ?>">
    <?php foreach ($etiquetas as $t): ?>
    <form method="post" action="/admin/etiquetas/guardar.php" class="flex flex-wrap items-center gap-2 px-4 py-2 border-b border-gray-100 last:border-0">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
        <input type="text" name="name" value="<?= e($t['name']) ?>" class="<?= e(tw_input()) ?> md:w-1/2">
        <a href="/admin/articulos/index.php?etiqueta_id=<?= (int) $t['id'] ?>"
           class="inline-block bg-marca-grisClaro/40 text-marca-azul text-xs px-2 py-1 rounded w-28 text-center hover:bg-marca-grisClaro/70">
            <?= (int) $t['n_articulos'] ?> artículos
        </a>
        <div class="flex gap-2 ml-auto">
            <button type="submit" class="<?= e(tw_btn('outline-sm')) ?>">Renombrar</button>
            <?php if ((int) $t['n_articulos'] === 0): ?>
                <button type="submit" formaction="/admin/etiquetas/eliminar.php" class="<?= e(tw_btn('outline-danger-sm')) ?>"
                        onclick="return confirm('¿Eliminar esta etiqueta sin uso?');">
                    Eliminar
                </button>
            <?php endif; ?>
        </div>
    </form>
    <?php endforeach; ?>
    <?php if (!$etiquetas): ?>
        <p class="text-center text-gray-400 py-6">No se encontraron etiquetas.</p>
    <?php endif; ?>
</div>

<div class="mt-4">
<?= paginacion($pagina, $totalPaginas, '/admin/etiquetas/index.php?q=' . urlencode($q)) ?>
</div>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
