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

    public static function contar(PDO $pdo): int
    {
        return (int) $pdo->query('SELECT count(*) FROM categories')->fetchColumn();
    }

    // Admin: listado completo con conteo de articulos por categoria (para el CRUD).
    public static function adminListarConConteo(PDO $pdo): array
    {
        return $pdo->query('
            SELECT c.id, c.name, c.description, count(a.id) AS n_articulos
            FROM categories c
            LEFT JOIN articles a ON a.category_id = c.id
            GROUP BY c.id
            ORDER BY c.name
        ')->fetchAll();
    }

    // Crea o actualiza segun $id. Deja que PDOException (choque de unicidad en name)
    // se propague — el controller decide el mensaje de error.
    public static function guardar(PDO $pdo, ?int $id, string $name, ?string $description): void
    {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE categories SET name = :name, description = :description WHERE id = :id');
            $stmt->execute(['name' => $name, 'description' => $description, 'id' => $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
            $stmt->execute(['name' => $name, 'description' => $description]);
        }
    }
}
