<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class TagModel
{
    public static function nombrePorId(PDO $pdo, int $id): ?string
    {
        $stmt = $pdo->prepare('SELECT name FROM tags WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $nombre = $stmt->fetchColumn();
        return $nombre !== false ? $nombre : null;
    }

    public static function contar(PDO $pdo): int
    {
        return (int) $pdo->query('SELECT count(*) FROM tags')->fetchColumn();
    }

    // Admin: listado paginado con conteo de articulos, filtrable por nombre.
    public static function adminListarConConteo(PDO $pdo, string $q, int $pagina, int $porPagina): array
    {
        $where = '';
        $params = [];
        if ($q !== '') {
            $where = 'WHERE t.name ILIKE :q';
            $params['q'] = '%' . $q . '%';
        }

        $total = $pdo->prepare("SELECT count(*) FROM tags t $where");
        $total->execute($params);
        $totalFilas = (int) $total->fetchColumn();
        $totalPaginas = (int) max(1, ceil($totalFilas / $porPagina));
        $offset = (max(1, $pagina) - 1) * $porPagina;

        $stmt = $pdo->prepare("
            SELECT t.id, t.name, count(at2.article_id) AS n_articulos
            FROM tags t
            LEFT JOIN article_tags at2 ON at2.tag_id = t.id
            $where
            GROUP BY t.id
            ORDER BY n_articulos DESC, t.name
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

    // Crea o renombra segun $id. Deja que PDOException (choque de unicidad en name)
    // se propague — el controller decide el mensaje de error.
    public static function guardar(PDO $pdo, ?int $id, string $name): void
    {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE tags SET name = :name WHERE id = :id');
            $stmt->execute(['name' => $name, 'id' => $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO tags (name) VALUES (:name)');
            $stmt->execute(['name' => $name]);
        }
    }

    public static function estaEnUso(PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare('SELECT count(*) FROM article_tags WHERE tag_id = :id');
        $stmt->execute(['id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // Devuelve true si borro una fila real (mismo criterio que rowCount() > 0 del
    // original). No valida "en uso" aqui — eso lo hace el controller antes de llamar.
    public static function eliminar(PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare('DELETE FROM tags WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Fusiona dos etiquetas por nombre: mueve las asociaciones de "origen" hacia
    // "destino" (descartando duplicados) y borra "origen". Transaccion completa,
    // devuelve ['ok'=>bool, 'mensaje'=>string].
    public static function fusionar(PDO $pdo, string $origenNombre, string $destinoNombre): array
    {
        if ($origenNombre === '' || $destinoNombre === '' || $origenNombre === $destinoNombre) {
            return ['ok' => false, 'mensaje' => 'Indica dos etiquetas distintas para fusionar.'];
        }

        $buscar = $pdo->prepare('SELECT id FROM tags WHERE name = :n');
        $buscar->execute(['n' => $origenNombre]);
        $origenId = $buscar->fetchColumn();

        $buscar->execute(['n' => $destinoNombre]);
        $destinoId = $buscar->fetchColumn();

        if (!$origenId || !$destinoId) {
            return ['ok' => false, 'mensaje' => 'No se encontró alguna de las dos etiquetas.'];
        }

        $pdo->beginTransaction();
        try {
            // Mover asociaciones que no generen duplicado, descartar las que ya existan en destino
            $pdo->prepare('
                UPDATE article_tags SET tag_id = :destino
                WHERE tag_id = :origen
                  AND article_id NOT IN (SELECT article_id FROM article_tags WHERE tag_id = :destino)
            ')->execute(['destino' => $destinoId, 'origen' => $origenId]);

            $pdo->prepare('DELETE FROM article_tags WHERE tag_id = :origen')->execute(['origen' => $origenId]);
            $pdo->prepare('DELETE FROM tags WHERE id = :origen')->execute(['origen' => $origenId]);

            $pdo->commit();
            return ['ok' => true, 'mensaje' => "Etiqueta \"$origenNombre\" fusionada dentro de \"$destinoNombre\"."];
        } catch (\Throwable $e) {
            $pdo->rollBack();
            return ['ok' => false, 'mensaje' => 'Error al fusionar: ' . $e->getMessage()];
        }
    }
}
