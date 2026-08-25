<?php
declare(strict_types=1);
require __DIR__ . '/../lib/auth.php';
require __DIR__ . '/../lib/helpers.php';
require __DIR__ . '/../lib/db.php';
requiere_login();

$pdo = db();

$articuloId = trim((string) ($_GET['articulo_id'] ?? ''));
$q = trim((string) ($_GET['q'] ?? ''));
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$porPagina = 30;

$condiciones = [];
$params = [];
if ($articuloId !== '') {
    $condiciones[] = 'cl.article_id = :aid';
    $params['aid'] = (int) $articuloId;
}
if ($q !== '') {
    $condiciones[] = '(cl.pregunta ILIKE :q OR cl.respuesta ILIKE :q)';
    $params['q'] = '%' . $q . '%';
}
$where = $condiciones ? ('WHERE ' . implode(' AND ', $condiciones)) : '';

$articuloActual = null;
if ($articuloId !== '') {
    $art = $pdo->prepare('SELECT id, title FROM articles WHERE id = :id');
    $art->execute(['id' => (int) $articuloId]);
    $articuloActual = $art->fetch();
}

$total = $pdo->prepare("SELECT count(*) FROM chat_log cl $where");
$total->execute($params);
$totalFilas = (int) $total->fetchColumn();
$totalPaginas = (int) max(1, ceil($totalFilas / $porPagina));
$offset = ($pagina - 1) * $porPagina;

$sql = "
    SELECT cl.id, cl.pregunta, cl.respuesta, cl.tokens_in, cl.tokens_out, cl.created_at,
           a.id AS articulo_id, a.title AS articulo_titulo
    FROM chat_log cl
    JOIN articles a ON a.id = cl.article_id
    $where
    ORDER BY cl.created_at DESC
    LIMIT :limite OFFSET :offset
";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue('limite', $porPagina, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$filas = $stmt->fetchAll();

$titulo = 'Preguntas del chat';
require __DIR__ . '/../templates/header.php';
?>
<div class="flex items-center justify-between mb-2 flex-wrap gap-2">
    <h1 class="text-2xl font-bold">
        Preguntas del chat <span class="text-gray-400 text-base font-normal">(<?= $totalFilas ?>)</span>
    </h1>
    <a href="/admin/metricas/index.php" class="<?= e(tw_btn('outline')) ?>">&larr; Volver a métricas</a>
</div>

<?php if ($articuloActual): ?>
<div class="flex items-center gap-2 mb-4">
    <span class="text-sm text-gray-500">Filtrando por artículo:</span>
    <span class="inline-flex items-center gap-2 bg-marca-grisClaro/40 text-marca-azul text-sm px-3 py-1 rounded">
        <?= e($articuloActual['title']) ?>
        <a href="/admin/metricas/preguntas.php?q=<?= urlencode($q) ?>" class="font-bold">&times;</a>
    </span>
</div>
<?php endif; ?>

<form method="get" class="flex flex-wrap gap-2 mb-4">
    <input type="hidden" name="articulo_id" value="<?= e($articuloId) ?>">
    <input type="text" name="q" value="<?= e($q) ?>" class="<?= e(tw_input()) ?> md:w-80" placeholder="Buscar en preguntas o respuestas...">
    <button type="submit" class="<?= e(tw_btn('outline')) ?>">Buscar</button>
</form>

<div class="space-y-3">
    <?php foreach ($filas as $f): ?>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="flex items-center justify-between gap-2 mb-2 text-xs text-gray-400">
            <a href="/admin/metricas/preguntas.php?articulo_id=<?= (int) $f['articulo_id'] ?>" class="text-marca-azul font-semibold">
                <?= e($f['articulo_titulo']) ?>
            </a>
            <span><?= e((string) $f['created_at']) ?> · <?= (int) $f['tokens_in'] + (int) $f['tokens_out'] ?> tokens</span>
        </div>
        <div class="text-sm font-semibold mb-1">P: <?= e($f['pregunta']) ?></div>
        <div class="text-sm text-gray-600">R: <?= e(mb_substr($f['respuesta'], 0, 400)) ?><?= mb_strlen($f['respuesta']) > 400 ? '…' : '' ?></div>
    </div>
    <?php endforeach; ?>
    <?php if (!$filas): ?>
        <p class="text-center text-gray-400 py-6">No se encontraron preguntas.</p>
    <?php endif; ?>
</div>

<div class="mt-4">
<?= paginacion($pagina, $totalPaginas, '/admin/metricas/preguntas.php?articulo_id=' . urlencode($articuloId) . '&q=' . urlencode($q)) ?>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>
