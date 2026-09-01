<?php

declare(strict_types=1);

namespace App\Lib;

// Renderiza una vista PHP plana bajo public/ pasandole datos ya preparados por el
// controlador. Generaliza el patron que app/controllers/Home.php ya usaba con exito
// (require directo con las variables en scope) en vez de depender de las
// particularidades internas de View::render()/sandbox() del propio F3.
final class View
{
    public static function render(string $rutaRelativaDesdePublic, array $datos = []): void
    {
        extract($datos);
        require $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($rutaRelativaDesdePublic, '/');
    }
}
