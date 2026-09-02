<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class CategoryModel
{
    public static function nombrePorId(PDO $pdo, int $id): ?string
    {
        $stmt = $pdo->prepare('SELECT name FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $nombre = $stmt->fetchColumn();
        return $nombre !== false ? $nombre : null;
    }

    public static function topPorConteo(PDO $pdo, int $limite = 12): array
    {
        return $pdo->query("
            SELECT c.id, c.name, count(a.id) AS n
            FROM categories c
            JOIN articles a ON a.category_id = c.id AND a.estado = 'publicado'
            GROUP BY c.id, c.name
            ORDER BY n DESC
            LIMIT $limite
        ")->fetchAll();
    }
}
