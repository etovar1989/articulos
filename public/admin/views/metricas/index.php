<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista de metricas (chat por articulo + buscador). Recibe los datos ya
 * resueltos por App\Controllers\Admin\MetricsController::index().
 *
 * @var string $titulo
 * @var array $totales
 * @var float $costoTotal
 * @var array $totalesBusqueda
 * @var float $costoBusquedaTotal
 * @var array $busquedasFrecuentes
 * @var string $q
 * @var int $pagina
 * @var array $filas
 * @var int $totalFilas
 * @var int $totalPaginas
 */
require __DIR__ . '/../../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-6">Métricas del chat por artículo</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Preguntas totales</div>
        <div class="text-3xl font-bold"><?= number_format((int) $totales['n_preguntas'], 0, ',', '.') ?></div>
    </div>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Tokens de entrada</div>
        <div class="text-3xl font-bold"><?= number_format((int) $totales['tokens_in'], 0, ',', '.') ?></div>
    </div>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Tokens de salida</div>
        <div class="text-3xl font-bold"><?= number_format((int) $totales['tokens_out'], 0, ',', '.') ?></div>
    </div>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Costo estimado (gpt-4.1-mini)</div>
        <div class="text-3xl font-bold">US$ <?= number_format($costoTotal, 2) ?></div>
    </div>
</div>

<div class="flex items-center justify-between mb-4 flex-wrap gap-2">
    <form method="get" class="flex flex-wrap gap-2">
        <input type="text" name="q" value="<?= e($q) ?>" class="<?= e(tw_input()) ?> md:w-64" placeholder="Buscar artículo...">
        <button type="submit" class="<?= e(tw_btn('outline')) ?>">Filtrar</button>
    </form>
    <a href="/admin/metricas/preguntas.php" class="<?= e(tw_btn('outline')) ?>">Ver todas las preguntas</a>
</div>

<div class="overflow-x-auto <?= e(tw_card()) ?>">
<table class="w-full text-sm">
    <thead class="bg-marca-grisClaro/20 text-left text-gray-600">
        <tr>
            <th class="px-3 py-2">Artículo</th>
            <th class="px-3 py-2">Categoría</th>
            <th class="px-3 py-2 text-right">Preguntas</th>
            <th class="px-3 py-2 text-right">Tokens entrada</th>
            <th class="px-3 py-2 text-right">Tokens salida</th>
            <th class="px-3 py-2 text-right">Total tokens</th>
            <th class="px-3 py-2">Última pregunta</th>
            <th class="px-3 py-2"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filas as $f): $totalTokens = (int) $f['tokens_in'] + (int) $f['tokens_out']; ?>
        <tr class="border-t border-gray-100">
            <td class="px-3 py-2"><?= e($f['title']) ?></td>
            <td class="px-3 py-2">
                <?php if ($f['categoria_nombre']): ?>
                    <span class="inline-block bg-marca-grisClaro/50 text-marca-morado text-xs px-2 py-0.5 rounded"><?= e($f['categoria_nombre']) ?></span>
                <?php endif; ?>
            </td>
            <td class="px-3 py-2 text-right"><?= number_format((int) $f['n_preguntas'], 0, ',', '.') ?></td>
            <td class="px-3 py-2 text-right"><?= number_format((int) $f['tokens_in'], 0, ',', '.') ?></td>
            <td class="px-3 py-2 text-right"><?= number_format((int) $f['tokens_out'], 0, ',', '.') ?></td>
            <td class="px-3 py-2 text-right font-semibold"><?= number_format($totalTokens, 0, ',', '.') ?></td>
            <td class="px-3 py-2"><?= e(substr((string) $f['ultima_pregunta'], 0, 16)) ?></td>
            <td class="px-3 py-2 text-right">
                <a href="/admin/metricas/preguntas.php?articulo_id=<?= (int) $f['id'] ?>" class="<?= e(tw_btn('outline-sm')) ?>">Ver preguntas</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$filas): ?>
        <tr><td colspan="8" class="text-center text-gray-400 py-6">Todavía no hay preguntas registradas.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<div class="mt-4">
<?= paginacion($pagina, $totalPaginas, '/admin/metricas/index.php?q=' . urlencode($q)) ?>
</div>

<h1 class="text-2xl font-bold mb-6 mt-12">Métricas del buscador semántico</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Búsquedas totales</div>
        <div class="text-3xl font-bold"><?= number_format((int) $totalesBusqueda['n_busquedas'], 0, ',', '.') ?></div>
    </div>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Sin resultados</div>
        <div class="text-3xl font-bold"><?= number_format((int) $totalesBusqueda['n_sin_resultados'], 0, ',', '.') ?></div>
    </div>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Con resumen de IA</div>
        <div class="text-3xl font-bold"><?= number_format((int) $totalesBusqueda['n_con_sintesis'], 0, ',', '.') ?></div>
    </div>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Costo estimado (resúmenes)</div>
        <div class="text-3xl font-bold">US$ <?= number_format($costoBusquedaTotal, 2) ?></div>
    </div>
</div>

<div class="flex items-center justify-between mb-4">
    <h2 class="font-bold text-sm text-gray-600">Búsquedas más frecuentes</h2>
    <a href="/admin/metricas/busquedas.php" class="<?= e(tw_btn('outline')) ?>">Ver todas las búsquedas</a>
</div>

<div class="overflow-x-auto <?= e(tw_card()) ?>">
<table class="w-full text-sm">
    <thead class="bg-marca-grisClaro/20 text-left text-gray-600">
        <tr>
            <th class="px-3 py-2">Consulta</th>
            <th class="px-3 py-2 text-right">Veces buscada</th>
            <th class="px-3 py-2 text-right">Promedio resultados</th>
            <th class="px-3 py-2 text-right">Veces sin resultados</th>
            <th class="px-3 py-2">Última vez</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($busquedasFrecuentes as $b): ?>
        <tr class="border-t border-gray-100">
            <td class="px-3 py-2"><?= e($b['consulta_ejemplo']) ?></td>
            <td class="px-3 py-2 text-right font-semibold"><?= number_format((int) $b['veces'], 0, ',', '.') ?></td>
            <td class="px-3 py-2 text-right"><?= number_format((float) $b['promedio_resultados'], 0, ',', '.') ?></td>
            <td class="px-3 py-2 text-right">
                <?php if ((int) $b['veces_sin_resultados'] > 0): ?>
                    <span class="text-marca-naranja font-semibold"><?= (int) $b['veces_sin_resultados'] ?></span>
                <?php else: ?>
                    <span class="text-gray-300">0</span>
                <?php endif; ?>
            </td>
            <td class="px-3 py-2"><?= e(substr((string) $b['ultima_vez'], 0, 16)) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$busquedasFrecuentes): ?>
        <tr><td colspan="5" class="text-center text-gray-400 py-6">Todavía no hay búsquedas registradas.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
