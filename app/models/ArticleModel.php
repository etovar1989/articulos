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
}
