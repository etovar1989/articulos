<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista del dashboard. Recibe los datos ya resueltos por
 * App\Controllers\Admin\DashboardController::index().
 *
 * @var string $titulo
 * @var array $totales
 * @var int $nCategorias
 * @var int $nEtiquetas
 */
require __DIR__ . '/../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-6">Panel de administración</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Artículos</div>
        <div class="text-3xl font-bold"><?= (int) $totales['total'] ?></div>
        <div class="text-xs mt-1 space-x-1">
            <span class="text-marca-verde font-semibold"><?= (int) $totales['publicados'] ?> publicados</span> ·
            <span class="text-marca-naranja font-semibold"><?= (int) $totales['borradores'] ?> borrador</span> ·
            <span class="text-marca-gris font-semibold"><?= (int) $totales['archivados'] ?> archivados</span>
        </div>
    </div>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Categorías</div>
        <div class="text-3xl font-bold"><?= $nCategorias ?></div>
        <a href="/admin/categorias/index.php" class="text-xs text-marca-azul font-semibold">Administrar →</a>
    </div>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Etiquetas</div>
        <div class="text-3xl font-bold"><?= $nEtiquetas ?></div>
        <a href="/admin/etiquetas/index.php" class="text-xs text-marca-azul font-semibold">Administrar →</a>
    </div>
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="text-gray-500 text-sm">Estado RAG</div>
        <div class="text-xs mt-2 leading-relaxed">
            <span class="text-marca-verde">● <?= (int) $totales['rag_listos'] ?> listos</span><br>
            <span class="text-marca-naranja">● <?= (int) $totales['rag_pendientes'] ?> pendientes</span><br>
            <span class="text-red-600">● <?= (int) $totales['rag_errores'] ?> con error</span>
        </div>
    </div>
</div>

<?php if ($totales['rag_listos'] == 0): ?>
<div class="border rounded px-4 py-3 mb-6 text-sm <?= e(clases_alerta('warning')) ?>">
    La indexación RAG (chunks + pgvector) todavía no está activa en este servidor — falta
    instalar la extensión <code>pgvector</code> con un usuario superusuario de Postgres.
    El CRUD de artículos, categorías y etiquetas funciona igual mientras tanto.
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <a href="/admin/articulos/index.php" class="<?= e(tw_btn('primario')) ?> py-4">Gestionar artículos</a>
    <a href="/admin/categorias/index.php" class="<?= e(tw_btn('outline')) ?> py-4">Gestionar categorías</a>
    <a href="/admin/etiquetas/index.php" class="<?= e(tw_btn('outline')) ?> py-4">Gestionar etiquetas</a>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>
