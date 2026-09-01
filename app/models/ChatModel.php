<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Throwable;

// Gobernanza (rate-limit por IP antes de gastar en la API de OpenAI) y registro de
// uso/consumo. Mismo patron que antes vivia repetido en chat.php, chat_general.php
// e index.php (modo busqueda).
final class ChatModel
{
    public static function limiteExcedido(PDO $pdo, string $ip, string $kind, int $limite = 20, int $ventanaMin = 10): bool
    {
        $check = $pdo->prepare("
            SELECT count(*) FROM ai_usage
            WHERE ip = :ip AND kind = :kind AND created_at > now() - make_interval(mins => :ventana)
        ");
        $check->execute(['ip' => $ip, 'kind' => $kind, 'ventana' => $ventanaMin]);
        return (int) $check->fetchColumn() >= $limite;
    }

    // Registra el consumo (ai_usage, costo agregado) y la pregunta/respuesta real
    // (chat_log) del chat por articulo — envuelto en try/catch: el logging jamas
    // tumba la respuesta principal.
    public static function registrarChatArticulo(PDO $pdo, string $ip, int $articuloId, string $pregunta, string $respuesta, ?int $tokensIn, ?int $tokensOut): void
    {
        try {
            $pdo->prepare('
                INSERT INTO ai_usage (origen, kind, tokens_in, tokens_out, ip, article_id)
                VALUES (\'chat_publico\', \'chat_articulo\', :tin, :tout, :ip, :aid)
            ')->execute(['tin' => $tokensIn, 'tout' => $tokensOut, 'ip' => $ip, 'aid' => $articuloId]);

            $pdo->prepare('
                INSERT INTO chat_log (article_id, pregunta, respuesta, tokens_in, tokens_out, ip)
                VALUES (:aid, :pregunta, :respuesta, :tin, :tout, :ip)
            ')->execute([
                'aid' => $articuloId, 'pregunta' => $pregunta, 'respuesta' => $respuesta,
                'tin' => $tokensIn, 'tout' => $tokensOut, 'ip' => $ip,
            ]);
        } catch (Throwable $e) {
            error_log('registrarChatArticulo: error registrando métricas: ' . $e->getMessage());
        }
    }
}
