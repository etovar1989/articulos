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
}
