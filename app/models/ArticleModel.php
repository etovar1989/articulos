<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

// Consultas de articulos usadas por el listado (articulos/index.php) y el detalle
// (articulos/ver.php). Puerto 1:1 de las queries que antes vivian inline en esos
// archivos.
final class ArticleModel
{
    public static function totalPublicados(PDO $pdo): int
    {
        return (int) $pdo->query("SELECT count(*) FROM articles WHERE estado = 'publicado'")->fetchColumn();
    }

    // Destacados de portada (home): articulos reales con portada ya generada (el
    // lote de portadas corre en segundo plano, asi que no todos los articulos
    // recientes la tienen todavia).
    public static function destacadosConPortada(PDO $pdo, string $dirPortadas, int $limite = 4): array
    {
        $candidatos = $pdo->query("
            SELECT a.id, a.title, a.summary, left(a.body, 400) AS extracto, c.name AS categoria
            FROM articles a
            LEFT JOIN categories c ON c.id = a.category_id
            WHERE a.estado = 'publicado'
            ORDER BY a.article_date DESC NULLS LAST, a.id DESC
            LIMIT 80
        ")->fetchAll();

        $destacados = [];
        foreach ($candidatos as $c) {
            if (is_file($dirPortadas . '/' . $c['id'] . '.jpg')) {
                $destacados[] = $c;
            }
            if (count($destacados) >= $limite) {
                break;
            }
        }
        return $destacados;
    }

    // Tags de un conjunto de articulos en una sola consulta (evita N+1 por tarjeta).
    public static function tagsPorArticulos(PDO $pdo, array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if (!$ids) {
            return [];
        }
        $marcadores = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("
            SELECT at2.article_id, t.id, t.name
            FROM article_tags at2
            JOIN tags t ON t.id = at2.tag_id
            WHERE at2.article_id IN ($marcadores)
            ORDER BY t.name
        ");
        $stmt->execute($ids);
        $porArticulo = [];
        foreach ($stmt->fetchAll() as $fila) {
            $porArticulo[$fila['article_id']][] = $fila;
        }
        return $porArticulo;
    }

    // Catalogo completo paginado (modo "inicio" del listado).
    public static function catalogo(PDO $pdo, int $pagina, int $porPagina): array
    {
        $total = self::totalPublicados($pdo);
        $totalPaginas = (int) max(1, ceil($total / $porPagina));
        $offset = ($pagina - 1) * $porPagina;

        $stmt = $pdo->prepare("
            SELECT a.id, a.title, a.slug, a.summary, left(a.body, 5000) AS extracto, a.article_date, c.name AS categoria_nombre
            FROM articles a
            LEFT JOIN categories c ON c.id = a.category_id
            WHERE a.estado = 'publicado'
            ORDER BY a.article_date DESC NULLS LAST, a.id DESC
            LIMIT :lim OFFSET :off
        ");
        $stmt->bindValue('lim', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return ['items' => $stmt->fetchAll(), 'total' => $total, 'totalPaginas' => $totalPaginas];
    }

    public static function porCategoria(PDO $pdo, int $categoriaId, int $pagina, int $porPagina): array
    {
        $total = $pdo->prepare("SELECT count(*) FROM articles WHERE category_id = :id AND estado = 'publicado'");
        $total->execute(['id' => $categoriaId]);
        $totalCount = (int) $total->fetchColumn();
        $totalPaginas = (int) max(1, ceil($totalCount / $porPagina));
        $offset = ($pagina - 1) * $porPagina;

        $stmt = $pdo->prepare("
            SELECT a.id, a.title, a.slug, a.summary, left(a.body, 5000) AS extracto, a.article_date
            FROM articles a
            WHERE a.category_id = :id AND a.estado = 'publicado'
            ORDER BY a.article_date DESC NULLS LAST, a.id DESC
            LIMIT :lim OFFSET :off
        ");
        $stmt->bindValue('id', $categoriaId, PDO::PARAM_INT);
        $stmt->bindValue('lim', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return ['items' => $stmt->fetchAll(), 'total' => $totalCount, 'totalPaginas' => $totalPaginas];
    }

    public static function porEtiqueta(PDO $pdo, int $etiquetaId, int $pagina, int $porPagina): array
    {
        $total = $pdo->prepare("
            SELECT count(*) FROM articles a
            JOIN article_tags at2 ON at2.article_id = a.id
            WHERE at2.tag_id = :id AND a.estado = 'publicado'
        ");
        $total->execute(['id' => $etiquetaId]);
        $totalCount = (int) $total->fetchColumn();
        $totalPaginas = (int) max(1, ceil($totalCount / $porPagina));
        $offset = ($pagina - 1) * $porPagina;

        $stmt = $pdo->prepare("
            SELECT a.id, a.title, a.slug, a.summary, left(a.body, 5000) AS extracto, a.article_date
            FROM articles a
            JOIN article_tags at2 ON at2.article_id = a.id
            WHERE at2.tag_id = :id AND a.estado = 'publicado'
            ORDER BY a.article_date DESC NULLS LAST, a.id DESC
            LIMIT :lim OFFSET :off
        ");
        $stmt->bindValue('id', $etiquetaId, PDO::PARAM_INT);
        $stmt->bindValue('lim', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return ['items' => $stmt->fetchAll(), 'total' => $totalCount, 'totalPaginas' => $totalPaginas];
    }

    // "De interes" = lo que mas preguntas genera en el chat de cada articulo. Si
    // todavia no hay suficiente actividad, se rellena con articulos al azar para
    // que la seccion nunca quede vacia.
    public static function deInteres(PDO $pdo, int $limite = 6): array
    {
        $deInteres = $pdo->query("
            SELECT a.id, a.title, a.slug, a.summary, left(a.body, 5000) AS extracto, c.name AS categoria_nombre, count(cl.id) AS n_preguntas
            FROM articles a
            JOIN chat_log cl ON cl.article_id = a.id
            LEFT JOIN categories c ON c.id = a.category_id
            WHERE a.estado = 'publicado'
            GROUP BY a.id, a.title, a.slug, a.summary, a.body, c.name
            ORDER BY n_preguntas DESC
            LIMIT $limite
        ")->fetchAll();

        if (count($deInteres) < $limite) {
            $idsExcluir = array_column($deInteres, 'id') ?: [0];
            $marcadores = implode(',', array_fill(0, count($idsExcluir), '?'));
            $faltantes = $limite - count($deInteres);
            $relleno = $pdo->prepare("
                SELECT a.id, a.title, a.slug, a.summary, left(a.body, 5000) AS extracto, c.name AS categoria_nombre, 0 AS n_preguntas
                FROM articles a
                LEFT JOIN categories c ON c.id = a.category_id
                WHERE a.estado = 'publicado' AND a.id NOT IN ($marcadores)
                ORDER BY random()
                LIMIT ?
            ");
            $relleno->execute([...$idsExcluir, $faltantes]);
            $deInteres = array_merge($deInteres, $relleno->fetchAll());
        }

        return $deInteres;
    }

    // Fila completa (todas las columnas) de un articulo publicado, para el detalle.
    public static function buscarPublicadoPorId(PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare("
            SELECT a.*, c.name AS categoria_nombre
            FROM articles a
            LEFT JOIN categories c ON c.id = a.category_id
            WHERE a.id = :id AND a.estado = 'publicado'
        ");
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public static function tagsDeArticulo(PDO $pdo, int $id): array
    {
        $stmt = $pdo->prepare('
            SELECT t.id, t.name FROM tags t
            JOIN article_tags at2 ON at2.tag_id = t.id
            WHERE at2.article_id = :id
            ORDER BY t.name
        ');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }

    // Articulos relacionados: KNN real con pgvector (indice HNSW de
    // embeddings_small), acotado a la misma categoria del articulo actual.
    public static function relacionados(PDO $pdo, int $id, ?int $categoriaId, int $limite = 5): array
    {
        if (!$categoriaId) {
            return [];
        }
        $stmt = $pdo->prepare("
            SELECT a.id, a.title, a.slug,
                   (es.embedding <=> (SELECT embedding FROM embeddings_small WHERE article_id = :id)) AS distancia
            FROM embeddings_small es
            JOIN articles a ON a.id = es.article_id
            WHERE a.category_id = :cat AND a.id != :id2 AND a.estado = 'publicado'
            ORDER BY distancia ASC
            LIMIT $limite
        ");
        $stmt->execute(['id' => $id, 'cat' => $categoriaId, 'id2' => $id]);
        return $stmt->fetchAll();
    }

    // --- Admin ---

    public static function totalesPorEstadoYRag(PDO $pdo): array
    {
        return $pdo->query("
            SELECT
                count(*) AS total,
                count(*) FILTER (WHERE estado = 'publicado') AS publicados,
                count(*) FILTER (WHERE estado = 'borrador') AS borradores,
                count(*) FILTER (WHERE estado = 'archivado') AS archivados,
                count(*) FILTER (WHERE rag_status = 'ready') AS rag_listos,
                count(*) FILTER (WHERE rag_status = 'pending') AS rag_pendientes,
                count(*) FILTER (WHERE rag_status = 'error') AS rag_errores
            FROM articles
        ")->fetch();
    }

    // Listado del CRUD: cualquier estado, filtros por titulo/categoria/etiqueta/estado.
    public static function adminListar(PDO $pdo, string $q, string $categoriaId, string $etiquetaId, string $estado, int $pagina, int $porPagina): array
    {
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

        return ['items' => $stmt->fetchAll(), 'total' => $totalFilas, 'totalPaginas' => $totalPaginas];
    }

    // Fila completa (cualquier estado) para el formulario de edicion.
    public static function adminBuscarPorId(PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    // Crea o actualiza un articulo desde el admin: garantiza slug unico, reindexa el
    // embedding si va a quedar publicado y el trigger lo dejo 'pending', y sincroniza
    // sus etiquetas (reemplazo total). Devuelve ['id'=>int, 'avisoReindex'=>?string]
    // — puerto 1:1 del flujo que antes vivia en admin/articulos/guardar.php.
    public static function guardarDesdeAdmin(PDO $pdo, array $configIA, ?int $id, array $campos): array
    {
        $title = $campos['title'];
        $body = $campos['body'];
        $slug = $campos['slug'] ?: slugify($title);

        // Garantizar slug unico (excluyendo el propio articulo si se esta editando)
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
                'title' => $title, 'slug' => $slug, 'summary' => $campos['summary'], 'body' => $body,
                'author' => $campos['author'], 'category_id' => $campos['category_id'], 'article_date' => $campos['article_date'],
                'estado' => $campos['estado'], 'id' => $id,
            ]);
        } else {
            // articles.id no es autoincremental (viene del corpus original) y file_path es NOT NULL:
            // se asigna el siguiente id libre y un file_path sintetico para articulos creados desde el admin.
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
                'file_path' => 'admin://' . $slug, 'body' => $body, 'summary' => $campos['summary'],
                'author' => $campos['author'], 'category_id' => $campos['category_id'], 'article_date' => $campos['article_date'],
                'estado' => $campos['estado'], 'content_hash' => $contentHash,
            ]);
            $id = $nuevoId;
        }

        // El trigger marcar_pendiente_reindex() ya marco rag_status='pending' si title/body
        // cambiaron (o el INSERT de arriba lo puso en 'pending' para un articulo nuevo).
        // Solo se reindexa si va a quedar publicado (lo unico que de verdad se recupera en
        // la busqueda/chat).
        $avisoReindex = null;
        if ($campos['estado'] === 'publicado') {
            $estadoRag = $pdo->prepare('SELECT rag_status FROM articles WHERE id = :id');
            $estadoRag->execute(['id' => $id]);
            if ($estadoRag->fetchColumn() === 'pending') {
                if (!SearchModel::reindexarEmbeddingArticulo($pdo, $configIA, $id, $title, $body)) {
                    $avisoReindex = 'Artículo guardado, pero no se pudo actualizar su embedding para el buscador/chat. Se reintentará más tarde.';
                }
            }
        }

        // Sincronizar etiquetas: reemplazo total de la relacion para este articulo
        $pdo->prepare('DELETE FROM article_tags WHERE article_id = :id')->execute(['id' => $id]);
        foreach ($campos['etiquetas'] as $nombre) {
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

        return ['id' => $id, 'avisoReindex' => $avisoReindex];
    }

    // Archivar, no borrar de verdad: se saca de la recuperacion/listado publico pero
    // sus chunks e historial quedan intactos.
    public static function archivar(PDO $pdo, int $id): void
    {
        $pdo->prepare("UPDATE articles SET estado = 'archivado' WHERE id = :id")->execute(['id' => $id]);
    }
}
