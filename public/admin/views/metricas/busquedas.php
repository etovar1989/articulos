<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista del log de busquedas. Recibe los datos ya resueltos por
 * App\Controllers\Admin\MetricsController::busquedas().
 *
 * @var string $titulo
 * @var string $q
 * @var bool $soloSinResultados
 * @var int $pagina
 * @var array $filas
 * @var int $totalFilas
 * @var int $totalPaginas
 * @var string $baseUrl
 */
require __DIR__ . '/../../templates/header.php';
?>
<div class="flex items-center justify-between mb-2 flex-wrap gap-2">
    <h1 class="text-2xl font-bold">
        Búsquedas del buscador <span class="text-gray-400 text-base font-normal">(<?= $totalFilas ?>)</span>
    </h1>
    <a href="/admin/metricas/index.php" class="<?= e(tw_btn('outline')) ?>">&larr; Volver a métricas</a>
</div>

<form method="get" class="flex flex-wrap items-center gap-2 mb-4">
    <input type="text" name="q" value="<?= e($q) ?>" class="<?= e(tw_input()) ?> md:w-80" placeholder="Buscar en las consultas...">
    <label class="flex items-center gap-2 text-sm text-gray-600">
        <input type="checkbox" name="sin_resultados" value="1" <?= $soloSinResultados ? 'checked' : '' ?>>
        Solo sin resultados
    </label>
    <button type="submit" class="<?= e(tw_btn('outline')) ?>">Buscar</button>
</form>

<div class="overflow-x-auto <?= e(tw_card()) ?>">
<table class="w-full text-sm">
    <thead class="bg-marca-grisClaro/20 text-left text-gray-600">
        <tr>
            <th class="px-3 py-2">Consulta</th>
            <th class="px-3 py-2 text-right">Resultados</th>
            <th class="px-3 py-2 text-center">Resumen IA</th>
            <th class="px-3 py-2">Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filas as $f): ?>
        <tr class="border-t border-gray-100">
            <td class="px-3 py-2"><?= e($f['consulta']) ?></td>
            <td class="px-3 py-2 text-right">
                <?php if ((int) $f['n_resultados'] === 0): ?>
                    <span class="text-marca-naranja font-semibold">0</span>
                <?php else: ?>
                    <?= (int) $f['n_resultados'] ?>
                <?php endif; ?>
            </td>
            <td class="px-3 py-2 text-center">
                <?php if ($f['con_sintesis']): ?>
                    <i class="fa-solid fa-check text-marca-verde"></i>
                <?php else: ?>
                    <span class="text-gray-300">&mdash;</span>
                <?php endif; ?>
            </td>
            <td class="px-3 py-2"><?= e(substr((string) $f['created_at'], 0, 16)) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$filas): ?>
        <tr><td colspan="4" class="text-center text-gray-400 py-6">No se encontraron búsquedas.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<div class="mt-4">
<?= paginacion($pagina, $totalPaginas, $baseUrl) ?>
</div>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
