<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../lib/auth.php';

use App\Lib\View;
use Base;

final class AuthController
{
    public function login(Base $f3): void
    {
        $adminPasswordHash = (string) $f3->get('ADMIN_PASSWORD_HASH');
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $clave = (string) ($_POST['password'] ?? '');
            if (password_verify($clave, $adminPasswordHash)) {
                session_regenerate_id(true);
                $_SESSION['admin_autenticado'] = true;
                redirect('/admin/index.php');
            }
            $error = 'Contraseña incorrecta.';
        }

        if (usuario_autenticado()) {
            redirect('/admin/index.php');
        }

        View::render('admin/views/login.php', [
            'titulo' => 'Iniciar sesión',
            'error' => $error,
        ]);
    }

    public function logout(Base $f3): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /admin/login.php');
        exit;
    }
}
