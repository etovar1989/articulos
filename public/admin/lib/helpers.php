<?php
declare(strict_types=1);

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

function slugify(string $texto): string
{
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto) ?: $texto;
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto) ?? '';
    return trim($texto, '-');
}

function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

// Clases Tailwind reutilizadas en todas las vistas — un solo lugar para ajustar
// el look & feel (botones, campos, tarjetas) sin tocar cada plantilla.
function tw_input(): string
{
    return 'w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-marca-azul focus:border-marca-azul';
}

function tw_btn(string $variante = 'primario'): string
{
    return match ($variante) {
        'primario' => 'inline-flex items-center justify-center rounded bg-marca-azul px-4 py-2 text-sm font-semibold text-white hover:bg-marca-azulHover transition',
        'outline' => 'inline-flex items-center justify-center rounded border border-marca-azul px-4 py-2 text-sm font-semibold text-marca-azul hover:bg-marca-azul hover:text-white transition',
        'outline-danger' => 'inline-flex items-center justify-center rounded border border-red-400 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-500 hover:text-white transition',
        'outline-sm' => 'inline-flex items-center justify-center rounded border border-marca-azul px-3 py-1 text-xs font-semibold text-marca-azul hover:bg-marca-azul hover:text-white transition',
        'outline-danger-sm' => 'inline-flex items-center justify-center rounded border border-red-400 px-3 py-1 text-xs font-semibold text-red-600 hover:bg-red-500 hover:text-white transition',
        default => '',
    };
}

function tw_card(): string
{
    return 'bg-white border border-marca-grisClaro/40 rounded-lg shadow-sm';
}

function clase_estado(string $estado): string
{
    return match ($estado) {
        'publicado' => 'text-marca-verde',
        'borrador' => 'text-marca-naranja',
        'archivado' => 'text-marca-gris',
        default => 'text-gray-500',
    };
}

function clase_rag(string $estado): string
{
    return match ($estado) {
        'ready' => 'text-marca-verde',
        'pending' => 'text-marca-naranja',
        'error' => 'text-red-600',
        default => 'text-gray-500',
    };
}

function clases_alerta(string $tipo): string
{
    return match ($tipo) {
        'success' => 'bg-green-50 text-green-800 border-green-200',
        'danger' => 'bg-red-50 text-red-800 border-red-200',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
        default => 'bg-gray-50 text-gray-800 border-gray-200',
    };
}

function paginacion(int $pagina, int $totalPaginas, string $baseUrl): string
{
    if ($totalPaginas <= 1) {
        return '';
    }

    $sep = str_contains($baseUrl, '?') ? '&' : '?';
    $enlace = fn(int $p): string => e($baseUrl . $sep . 'pagina=' . $p);

    $base = 'inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2 rounded border text-sm';
    $normal = $base . ' border-gray-200 text-gray-700 hover:bg-marca-grisClaro/30';
    $activo = $base . ' border-marca-azul bg-marca-azul text-white font-semibold';
    $desactivado = $base . ' border-transparent text-gray-400';

    // Ventana alrededor de la página actual + primera/última, con "…" en los huecos.
    // Evita renderizar un elemento por cada página (con ~79 páginas se salía del ancho).
    $ventana = 2;
    $paginas = [1, $totalPaginas];
    for ($p = $pagina - $ventana; $p <= $pagina + $ventana; $p++) {
        if ($p > 1 && $p < $totalPaginas) {
            $paginas[] = $p;
        }
    }
    $paginas = array_unique($paginas);
    sort($paginas);

    $html = '<nav aria-label="Paginación" class="flex flex-wrap items-center gap-1">';

    if ($pagina > 1) {
        $html .= '<a class="' . $normal . '" href="' . $enlace($pagina - 1) . '">&lsaquo;</a>';
    }

    $anterior = null;
    foreach ($paginas as $p) {
        if ($anterior !== null && $p - $anterior > 1) {
            $html .= '<span class="' . $desactivado . '">&hellip;</span>';
        }
        $clase = $p === $pagina ? $activo : $normal;
        $html .= '<a class="' . $clase . '" href="' . $enlace($p) . '">' . $p . '</a>';
        $anterior = $p;
    }

    if ($pagina < $totalPaginas) {
        $html .= '<a class="' . $normal . '" href="' . $enlace($pagina + 1) . '">&rsaquo;</a>';
    }

    $html .= '</nav>';
    return $html;
}
