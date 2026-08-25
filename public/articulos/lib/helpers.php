<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

// El scrape original de muchos artículos dejó líneas "en blanco" que en realidad
// tienen un espacio suelto; Parsedown las trata como párrafos con contenido y cada
// una reserva su propio margen — en artículos con cientos de estas líneas (hasta
// ~76% del archivo en algunos casos) esto infla el PDF a decenas de páginas vacías.
// Se limpia antes de convertir, igual que recomienda rag-baseline §4.4.
function limpiar_markdown(string $md): string
{
    // \x{00A0} = espacio de no separación (U+00A0): así quedaron guardadas muchas
    // líneas "vacías" del scrape original — sin el modificador /u, [ \t] no lo detecta.
    $md = preg_replace('/^[ \t\x{00A0}]+$/mu', '', $md);
    $md = preg_replace('/\n{3,}/', "\n\n", $md);
    return trim($md);
}

// Muchos artículos del scrape traen imágenes con ruta relativa ("../imgbd/...")
// que nunca van a resolver desde este dominio — quedan como ícono de "imagen rota".
// Se quitan solo para el PDF (no cambia el contenido guardado); las que sí son URL
// absoluta se conservan porque Dompdf las puede descargar e incrustar.
function quitar_imagenes_rotas(string $md): string
{
    return preg_replace('/!\[[^\]]*\]\((?!https?:\/\/)[^)]*\)/', '', $md);
}

function markdown_render(string $md): string
{
    static $parsedown = null;
    if ($parsedown === null) {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
    }
    return $parsedown->text(limpiar_markdown($md));
}

// Recorte por caracteres (aprox. 4 caracteres/token) para acotar el contexto
// que se manda al chat — no hay tiktoken disponible en PHP, esto es suficiente
// para no pasarnos de forma peligrosa con los artículos más largos (~117 KB).
function truncar_para_ia(string $texto, int $maxCaracteres = 40000): string
{
    if (mb_strlen($texto) <= $maxCaracteres) {
        return $texto;
    }
    return mb_substr($texto, 0, $maxCaracteres) . "\n\n[... contenido recortado ...]";
}

// PDO/pgsql no convierte una columna `vector` a array de PHP: llega como el
// literal de texto de pgvector '[0.12,-0.34,...]' (corchetes, a diferencia del
// '{...}' de un array nativo de Postgres). Solo hace falta para leer de vuelta
// la caché de query_embeddings — la similitud entre artículos ahora se calcula
// en SQL con el operador <=> sobre el índice HNSW, no en PHP.
function parse_pg_vector(string $literal): array
{
    $recortado = trim($literal, '[]');
    if ($recortado === '') {
        return [];
    }
    return array_map('floatval', explode(',', $recortado));
}

// Resalta las citas [n] de un texto de IA (síntesis de búsqueda, chat) como
// pequeñas insignias, igual que en el inspector del RAG del panel admin —
// misma trazabilidad visual para el usuario final.
function resaltar_citas(string $texto): string
{
    $html = e($texto);
    return preg_replace(
        '/\[(\d+)\]/',
        '<span class="inline-flex items-center justify-center rounded bg-marca-azul/10 text-marca-azul text-xs font-bold px-1.5 py-0.5 mx-0.5">[$1]</span>',
        $html
    );
}

function ip_cliente(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function tw_btn(string $variante = 'primario'): string
{
    return match ($variante) {
        'primario' => 'inline-flex items-center justify-center gap-2 rounded bg-marca-azul px-4 py-2 text-sm font-semibold text-white hover:bg-marca-azulHover transition',
        'outline' => 'inline-flex items-center justify-center gap-2 rounded border border-marca-azul px-4 py-2 text-sm font-semibold text-marca-azul hover:bg-marca-azul hover:text-white transition',
        default => '',
    };
}

function tw_card(): string
{
    return 'bg-white border border-marca-grisClaro/40 rounded-lg shadow-sm';
}

function tw_input(): string
{
    return 'w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-marca-azul focus:border-marca-azul';
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
