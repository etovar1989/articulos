<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requiere_login(): void
{
    if (empty($_SESSION['admin_autenticado'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

function usuario_autenticado(): bool
{
    return !empty($_SESSION['admin_autenticado']);
}
