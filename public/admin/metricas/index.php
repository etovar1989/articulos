<?php
declare(strict_types=1);
require __DIR__ . '/../lib/auth.php';
require __DIR__ . '/../lib/helpers.php';
require __DIR__ . '/../lib/db.php';
requiere_login();

$pdo = db();

// Precio aproximado de gpt-4.1-mini (revisar si OpenAI cambia tarifas):
// USD 0.15 / 1M tokens de entrada, USD 0.60 / 1M tokens de salida.
const PRECIO_ENTRADA_POR_M = 0.15;
const PRECIO_SALIDA_POR_M = 0.60;

$totales = $pdo->query('
    SELECT count(*) AS n_preguntas,
           coalesce(sum(tokens_in), 0) AS tokens_in,
           coalesce(sum(tokens_out), 0) AS tokens_out
    FROM chat_log
')->fetch();
$costoTotal = ($totales['tokens_in'] / 1000000 * PRECIO_ENTRADA_POR_M)
            + ($totales['tokens_out'] / 1000000 * PRECIO_SALIDA_POR_M);

// ─── Buscador semántico ─────────────────────────────────────────────
$totalesBusqueda = $pdo->query('
    SELECT count(*) AS n_busquedas,
           count(*) FILTER (WHERE n_resultados = 0) AS n_sin_resultados,
           count(*) FILTER (WHERE con_sintesis) AS n_con_sintesis
    FROM busqueda_log
')->fetch();

$costoBusquedaTokens = $pdo->query("
    SELECT coalesce(sum(tokens_in), 0) AS tokens_in, coalesce(sum(tokens_out), 0) AS tokens_out
    FROM ai_usage WHERE kind = 'busqueda_sintesis'
")->fetch();
$costoBusquedaTotal = ($costoBusquedaTokens['tokens_in'] / 1000000 * PRECIO_ENTRADA_POR_M)
                    + ($costoBusquedaTokens['tokens_out'] / 1000000 * PRECIO_SALIDA_POR_M);

$busquedasFrecuentes = $pdo->query("
    SELECT (array_agg(consulta ORDER BY created_at DESC))[1] AS consulta_ejemplo,
           count(*) AS veces,
           round(avg(n_resultados)) AS promedio_resultados,
           count(*) FILTER (WHERE n_resultados = 0) AS veces_sin_resultados,
           max(created_at) AS ultima_vez
    FROM busqueda_log
    GROUP BY lower(trim(consulta))
    ORDER BY veces DESC, ultima_vez DESC
    LIMIT 15
")->fetchAll();

$q = trim((string) ($_GET['q'] ?? ''));
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$porPagina = 25;

$where = '';
$params = [];
if ($q !== '') {
    $where = 'WHERE a.title ILIKE :q';
    $params['q'] = '%' . $q . '%';
}

$total = $pdo->prepare("
    SELECT count(DISTINCT a.id)
    FROM articles a JOIN chat_log cl ON cl.article_id = a.id
    $where
");
$total->execute($params);
$totalFilas = (int) $total->fetchColumn();
$totalPaginas = (int) max(1, ceil($totalFilas / $porPagina));
$offset = ($pagina - 1) * $porPagina;

$stmt = $pdo->prepare("
    SELECT a.id, a.title, c.name AS categoria_nombre,
           count(cl.id) AS n_preguntas,
           coalesce(sum(cl.tokens_in), 0) AS tokens_in,
           coalesce(sum(cl.tokens_out), 0) AS tokens_out,
           max(cl.created_at) AS ultima_pregunta
    FROM articles a
    JOIN chat_log cl ON cl.article_id = a.id
    LEFT JOIN categories c ON c.id = a.category_id
    $where
    GROUP BY a.id, a.title, c.name
    ORDER BY (coalesce(sum(cl.tokens_in), 0) + coalesce(sum(cl.tokens_out), 0)) DESC
    LIMIT :limite OFFSET :offset
");
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue('limite', $porPagina, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$filas = $stmt->fetchAll();

$titulo = 'Métricas';
require __DIR__ . '/../templates/header.php';
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

<?php require __DIR__ . '/../templates/footer.php'; ?>
