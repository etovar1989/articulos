<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../lib/auth.php';

use Base;

// Base de todos los controllers del admin salvo AuthController (login/logout no
// requieren sesion activa). F3 invoca beforeroute() automaticamente antes del
// metodo de ruta si la clase lo define — asi cada controller hereda la misma
// guardia de sesion sin repetirla.
abstract class AdminController
{
    public function beforeroute(Base $f3): void
    {
        requiere_login();
    }
}
