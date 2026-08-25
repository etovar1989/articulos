<?php
declare(strict_types=1);
require __DIR__ . '/../lib/auth.php';
require __DIR__ . '/../lib/helpers.php';
require __DIR__ . '/../lib/db.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/articulos/index.php');
}
csrf_check();

$pdo = db();

$id = $_POST['id'] !== '' ? (int) $_POST['id'] : null;
$title = trim((string) ($_POST['title'] ?? ''));
$body = (string) ($_POST['body'] ?? '');
$summary = trim((string) ($_POST['summary'] ?? '')) ?: null;
$author = trim((string) ($_POST['author'] ?? '')) ?: null;
$categoryId = $_POST['category_id'] !== '' ? (int) $_POST['category_id'] : null;
$articleDate = trim((string) ($_POST['article_date'] ?? '')) ?: null;
$estado = in_array($_POST['estado'] ?? '', ['borrador', 'publicado', 'archivado'], true)
    ? $_POST['estado'] : 'borrador';
$slug = trim((string) ($_POST['slug'] ?? '')) ?: slugify($title);
$etiquetas = array_filter(array_map('trim', explode(',', (string) ($_POST['etiquetas'] ?? ''))));

if ($title === '' || $body === '') {
    flash('danger', 'Título y contenido son obligatorios.');
    redirect($id ? "/admin/articulos/form.php?id=$id" : '/admin/articulos/form.php');
}

// Garantizar slug único (excluyendo el propio artículo si se está editando)
$slugBase = $slug;
$sufijo = 2;
while (true) {
    $chequeo = $pdo->prepare('SELECT id FROM articles WHERE slug = :slug AND id IS DISTINCT FROM :id');
    $chequeo->execute(['slug' => $slug, 'id' => $id]);
    if (!$chequeo->fetch()) {
        break;
    }
    $slug = $slugBase . '-' . $sufijo++;
}

if ($id) {
    // El trigger marcar_pendiente_reindex() se encarga de rag_status/content_hash/updated_at
    // cuando title o body cambian.
    $stmt = $pdo->prepare('
        UPDATE articles SET
            title = :title, slug = :slug, summary = :summary, body = :body,
            author = :author, category_id = :category_id, article_date = :article_date,
            estado = :estado
        WHERE id = :id
    ');
    $stmt->execute([
        'title' => $title, 'slug' => $slug, 'summary' => $summary, 'body' => $body,
        'author' => $author, 'category_id' => $categoryId, 'article_date' => $articleDate,
        'estado' => $estado, 'id' => $id,
    ]);
} else {
    // articles.id no es autoincremental (viene del corpus original) y file_path es NOT NULL:
    // se asigna el siguiente id libre y un file_path sintético para artículos creados desde el admin.
    $nuevoId = (int) $pdo->query('SELECT COALESCE(MAX(id), 0) + 1 FROM articles')->fetchColumn();
    $contentHash = hash('sha256', $title . $body);
    $stmt = $pdo->prepare('
        INSERT INTO articles
            (id, slug, title, file_path, body, summary, author, category_id,
             article_date, estado, rag_status, content_hash)
        VALUES
            (:id, :slug, :title, :file_path, :body, :summary, :author, :category_id,
             :article_date, :estado, \'pending\', :content_hash)
    ');
    $stmt->execute([
        'id' => $nuevoId, 'slug' => $slug, 'title' => $title,
        'file_path' => 'admin://' . $slug, 'body' => $body, 'summary' => $summary,
        'author' => $author, 'category_id' => $categoryId, 'article_date' => $articleDate,
        'estado' => $estado, 'content_hash' => $contentHash,
    ]);
    $id = $nuevoId;
}

// El trigger marcar_pendiente_reindex() ya marcó rag_status='pending' si title/body
// cambiaron (o el INSERT de arriba lo puso en 'pending' para un artículo nuevo) — pero
// hasta ahora nada consumía esa señal, así que el embedding viejo seguía sirviéndose
// indefinidamente tras una edición. Solo se reindexa si va a quedar publicado (lo
// único que de verdad se recupera en la búsqueda/chat).
if ($estado === 'publicado') {
    $estadoRag = $pdo->prepare('SELECT rag_status FROM articles WHERE id = :id');
    $estadoRag->execute(['id' => $id]);
    if ($estadoRag->fetchColumn() === 'pending') {
        require_once __DIR__ . '/../../articulos/lib/busqueda.php';
        $configIA = require __DIR__ . '/../../articulos/config/config.php';
        if (!reindexar_embedding_articulo($pdo, $configIA, $id, $title, $body)) {
            flash('warning', 'Artículo guardado, pero no se pudo actualizar su embedding para el buscador/chat. Se reintentará más tarde.');
        }
    }
}

// Sincronizar etiquetas: reemplazo total de la relación para este artículo
$pdo->prepare('DELETE FROM article_tags WHERE article_id = :id')->execute(['id' => $id]);
foreach ($etiquetas as $nombre) {
    $nombre = mb_strtolower($nombre);
    if ($nombre === '') {
        continue;
    }
    $pdo->prepare('INSERT INTO tags (name) VALUES (:n) ON CONFLICT (name) DO NOTHING')
        ->execute(['n' => $nombre]);
    $tagId = $pdo->prepare('SELECT id FROM tags WHERE name = :n');
    $tagId->execute(['n' => $nombre]);
    $tagIdValor = $tagId->fetchColumn();
    $pdo->prepare('INSERT INTO article_tags (article_id, tag_id) VALUES (:a, :t) ON CONFLICT DO NOTHING')
        ->execute(['a' => $id, 't' => $tagIdValor]);
}

flash('success', 'Artículo guardado correctamente.');
redirect("/admin/articulos/form.php?id=$id");
