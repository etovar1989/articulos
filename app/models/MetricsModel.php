<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

// Consultas de reporteria del panel admin (chat por articulo, buscador, logs).
// Puerto 1:1 de las queries que antes vivian inline en admin/metricas/*.php.
final class MetricsModel
{
    public static function totalesChat(PDO $pdo): array
    {
        return $pdo->query('
            SELECT count(*) AS n_preguntas,
                   coalesce(sum(tokens_in), 0) AS tokens_in,
                   coalesce(sum(tokens_out), 0) AS tokens_out
            FROM chat_log
        ')->fetch();
    }

    public static function totalesBusqueda(PDO $pdo): array
    {
        return $pdo->query('
            SELECT count(*) AS n_busquedas,
                   count(*) FILTER (WHERE n_resultados = 0) AS n_sin_resultados,
                   count(*) FILTER (WHERE con_sintesis) AS n_con_sintesis
            FROM busqueda_log
        ')->fetch();
    }

    public static function costoBusquedaTokens(PDO $pdo): array
    {
        return $pdo->query("
            SELECT coalesce(sum(tokens_in), 0) AS tokens_in, coalesce(sum(tokens_out), 0) AS tokens_out
            FROM ai_usage WHERE kind = 'busqueda_sintesis'
        ")->fetch();
    }

    public static function busquedasFrecuentes(PDO $pdo, int $limite = 15): array
    {
        return $pdo->query("
            SELECT (array_agg(consulta ORDER BY created_at DESC))[1] AS consulta_ejemplo,
                   count(*) AS veces,
                   round(avg(n_resultados)) AS promedio_resultados,
                   count(*) FILTER (WHERE n_resultados = 0) AS veces_sin_resultados,
                   max(created_at) AS ultima_vez
            FROM busqueda_log
            GROUP BY lower(trim(consulta))
            ORDER BY veces DESC, ultima_vez DESC
            LIMIT $limite
        ")->fetchAll();
    }

    public static function articulosConChat(PDO $pdo, string $q, int $pagina, int $porPagina): array
    {
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

        return ['items' => $stmt->fetchAll(), 'total' => $totalFilas, 'totalPaginas' => $totalPaginas];
    }

    public static function busquedasLog(PDO $pdo, string $q, bool $soloSinResultados, int $pagina, int $porPagina): array
    {
        $condiciones = [];
        $params = [];
        if ($q !== '') {
            $condiciones[] = 'consulta ILIKE :q';
            $params['q'] = '%' . $q . '%';
        }
        if ($soloSinResultados) {
            $condiciones[] = 'n_resultados = 0';
        }
        $where = $condiciones ? ('WHERE ' . implode(' AND ', $condiciones)) : '';

        $total = $pdo->prepare("SELECT count(*) FROM busqueda_log $where");
        $total->execute($params);
        $totalFilas = (int) $total->fetchColumn();
        $totalPaginas = (int) max(1, ceil($totalFilas / $porPagina));
        $offset = ($pagina - 1) * $porPagina;

        $stmt = $pdo->prepare("
            SELECT id, consulta, n_resultados, con_sintesis, created_at
            FROM busqueda_log
            $where
            ORDER BY created_at DESC
            LIMIT :limite OFFSET :offset
        ");
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue('limite', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return ['items' => $stmt->fetchAll(), 'total' => $totalFilas, 'totalPaginas' => $totalPaginas];
    }

    public static function chatLog(PDO $pdo, ?int $articuloId, string $q, int $pagina, int $porPagina): array
    {
        $condiciones = [];
        $params = [];
        if ($articuloId !== null) {
            $condiciones[] = 'cl.article_id = :aid';
            $params['aid'] = $articuloId;
        }
        if ($q !== '') {
            $condiciones[] = '(cl.pregunta ILIKE :q OR cl.respuesta ILIKE :q)';
            $params['q'] = '%' . $q . '%';
        }
        $where = $condiciones ? ('WHERE ' . implode(' AND ', $condiciones)) : '';

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

        return ['items' => $stmt->fetchAll(), 'total' => $totalFilas, 'totalPaginas' => $totalPaginas];
    }

    public static function articuloPorId(PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare('SELECT id, title FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }
}
