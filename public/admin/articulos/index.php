<?php
declare(strict_types=1);
require __DIR__ . '/../lib/auth.php';
require __DIR__ . '/../lib/helpers.php';
require __DIR__ . '/../lib/db.php';
requiere_login();

$pdo = db();

$q = trim((string) ($_GET['q'] ?? ''));
$categoriaId = $_GET['categoria_id'] ?? '';
$etiquetaId = $_GET['etiqueta_id'] ?? '';
$estado = $_GET['estado'] ?? '';
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$porPagina = 20;

$condiciones = [];
$params = [];

if ($q !== '') {
    $condiciones[] = 'a.title ILIKE :q';
    $params['q'] = '%' . $q . '%';
}
if ($categoriaId !== '') {
    $condiciones[] = 'a.category_id = :categoria_id';
    $params['categoria_id'] = (int) $categoriaId;
}
if ($etiquetaId !== '') {
    $condiciones[] = 'a.id IN (SELECT article_id FROM article_tags WHERE tag_id = :etiqueta_id)';
    $params['etiqueta_id'] = (int) $etiquetaId;
}
if ($estado !== '') {
    $condiciones[] = 'a.estado = :estado';
    $params['estado'] = $estado;
}

$where = $condiciones ? ('WHERE ' . implode(' AND ', $condiciones)) : '';

$total = $pdo->prepare("SELECT count(*) FROM articles a $where");
$total->execute($params);
$totalFilas = (int) $total->fetchColumn();
$totalPaginas = (int) max(1, ceil($totalFilas / $porPagina));

$etiquetaNombre = null;
if ($etiquetaId !== '') {
    $et = $pdo->prepare('SELECT name FROM tags WHERE id = :id');
    $et->execute(['id' => (int) $etiquetaId]);
    $etiquetaNombre = $et->fetchColumn() ?: null;
}

$offset = ($pagina - 1) * $porPagina;
$sql = "
    SELECT a.id, a.slug, a.title, a.estado, a.rag_status, a.article_date,
           c.name AS categoria_nombre
    FROM articles a
    LEFT JOIN categories c ON c.id = a.category_id
    $where
    ORDER BY a.id DESC
    LIMIT :limite OFFSET :offset
";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue('limite', $porPagina, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articulos = $stmt->fetchAll();

$categorias = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();

$titulo = 'Artículos';
require __DIR__ . '/../templates/header.php';
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

<?php require __DIR__ . '/../templates/footer.php'; ?>
