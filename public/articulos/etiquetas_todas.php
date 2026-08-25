<?php
declare(strict_types=1);
require __DIR__ . '/lib/helpers.php';
require __DIR__ . '/lib/db.php';

// Fragmento liviano con TODAS las etiquetas, cargado de forma diferida (fetch)
// desde index.php. Renderizar las ~3.390 etiquetas junto con el resto de la
// página de inicio la inflaba a más de 1 MB de HTML y se sentía lenta —
// separado así, el resto de la página carga rápido y esto llega un instante
// después. Markup compacto (una sola clase por etiqueta) para que ni siquiera
// este fragmento pese de más.
$pdo = db();
$etiquetas = $pdo->query("
    SELECT t.id, t.name, count(at2.article_id) AS n
    FROM tags t
    JOIN article_tags at2 ON at2.tag_id = t.id
    JOIN articles a ON a.id = at2.article_id AND a.estado = 'publicado'
    GROUP BY t.id, t.name
    ORDER BY n DESC, t.name ASC
")->fetchAll();

header('Content-Type: text/html; charset=utf-8');
foreach ($etiquetas as $t) {
    echo '<a href="/articulos/index.php?etiqueta_id=' . (int) $t['id'] . '" class="tag-pill">#'
        . e($t['name']) . ' <span class="opacity-60">(' . (int) $t['n'] . ')</span></a>';
}
