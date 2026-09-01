<?php
declare(strict_types=1);

// Sesion/seguridad del panel admin. Reemplaza public/admin/lib/auth.php (parte de
// sesion) y la parte de csrf/flash/redirect de public/admin/lib/helpers.php.

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

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_check(): void
{
    $enviado = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $enviado)) {
        http_response_code(400);
        exit('Token CSRF inválido. Vuelve atrás e intenta de nuevo.');
    }
}

function redirect(string $ruta): void
{
    header('Location: ' . $ruta);
    exit;
}

function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'][] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function flash_tomar(): array
{
    $mensajes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $mensajes;
}
