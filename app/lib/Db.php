<?php

declare(strict_types=1);

namespace App\Lib;

use Base;
use PDO;

// Factory PDO unico, reemplaza los dos db() casi identicos que vivian en
// public/articulos/lib/db.php y public/admin/lib/db.php. Lee las credenciales del
// hive de F3 (ya centralizadas en app/config.local.ini) en vez de un config.php
// propio bajo public/.
final class Db
{
    private static ?PDO $pdo = null;

    public static function pdo(Base $f3): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                (string) $f3->get('DB_DSN'),
                (string) $f3->get('DB_USER'),
                (string) $f3->get('DB_PASS'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            self::$pdo->exec("SET client_encoding TO 'UTF8'");
        }
        return self::$pdo;
    }
}
