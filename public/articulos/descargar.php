<?php
declare(strict_types=1);
require __DIR__ . '/lib/helpers.php';
require __DIR__ . '/lib/db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare("
    SELECT a.title, a.slug, a.body, a.author, a.article_date, c.name AS categoria_nombre
    FROM articles a
    LEFT JOIN categories c ON c.id = a.category_id
    WHERE a.id = :id AND a.estado = 'publicado'
");
$stmt->execute(['id' => $id]);
$articulo = $stmt->fetch();

if (!$articulo) {
    http_response_code(404);
    exit('Artículo no encontrado.');
}

$contenidoHtml = markdown_render(quitar_imagenes_rotas($articulo['body']));
$metaPartes = [];
if ($articulo['author']) {
    $metaPartes[] = 'Por ' . e($articulo['author']);
}
if ($articulo['article_date']) {
    $metaPartes[] = e((string) $articulo['article_date']);
}
$meta = implode(' · ', $metaPartes);

$html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
    @page { margin: 2.2cm 2cm; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; color: #1a1a1a; line-height: 1.5; }
    h1 { color: #5454E9; font-size: 20pt; margin-bottom: 4px; }
    h2 { color: #5454E9; font-size: 15pt; margin-top: 22px; }
    h3 { font-size: 12.5pt; margin-top: 16px; }
    .categoria { display: inline-block; background: #CECFF4; color: #4a2d8a; font-size: 9pt;
                 padding: 3px 10px; border-radius: 4px; margin-bottom: 10px; }
    .meta { color: #88898C; font-size: 9.5pt; margin-bottom: 18px; }
    .regla { border: none; border-top: 1px solid #CECFF4; margin: 4px 0 20px; }
    table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
    table, th, td { border: 1px solid #ccc; }
    th, td { padding: 5px 7px; text-align: left; }
    blockquote { border-left: 3px solid #5454E9; margin: 10px 0; padding: 4px 14px; color: #444; font-style: italic; }
    img { max-width: 100%; }
    a { color: #5454E9; }
    .pie { margin-top: 28px; padding-top: 10px; border-top: 1px solid #eee; color: #999; font-size: 8.5pt; }
</style></head><body>';

if ($articulo['categoria_nombre']) {
    $html .= '<div class="categoria">' . e($articulo['categoria_nombre']) . '</div>';
}
$html .= '<h1>' . e($articulo['title']) . '</h1>';
if ($meta !== '') {
    $html .= '<div class="meta">' . $meta . '</div>';
}
$html .= '<hr class="regla">';
$html .= $contenidoHtml;
$html .= '<div class="pie">Descargado desde eduteka.co · edukatic.co/articulos/ver.php?id=' . $id . '</div>';
$html .= '</body></html>';

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $articulo['slug'] . '.pdf"');
echo $dompdf->output();
