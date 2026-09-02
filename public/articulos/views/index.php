<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista del listado/catalogo de articulos. Recibe todos los datos ya resueltos
 * por App\Controllers\ArticleController::index() via App\Lib\View::render()
 * (extract de este array a variables locales) — sin queries ni logica de negocio
 * aqui, solo presentacion.
 *
 * @var string $q
 * @var string $modo
 * @var int $categoriaId
 * @var int $etiquetaId
 * @var array $resultadosBusqueda
 * @var string|null $errorBusqueda
 * @var array|null $sintesis
 * @var array $tagsBusqueda
 * @var string|null $categoriaNombre
 * @var string|null $etiquetaNombre
 * @var array $listado
 * @var array $tagsListado
 * @var int $pagina
 * @var int $totalPaginas
 * @var array $catalogo
 * @var array $categorias
 * @var array $deInteres
 * @var array $tagsCatalogo
 * @var array $tagsInteres
 * @var int $totalCatalogo
 * @var int $totalPaginasCatalogo
 * @var string $titulo
 * @var string $descripcion
 */

// Limpieza rápida de sintaxis Markdown para vistas previas (no usa Parsedown:
// una tarjeta de listado no necesita un parse completo a HTML solo para
// mostrar ~160 caracteres — con varias decenas de tarjetas por página, hacerlo
// con el parser completo se sentía notoriamente lento).
function texto_plano_desde_markdown(string $md): string
{
    $t = $md;
    $t = preg_replace('/^#{1,6}\s*/m', '', $t);
    $t = preg_replace('/^>\s?/m', '', $t);
    $t = preg_replace('/\*\*(.+?)\*\*/', '$1', $t);
    $t = preg_replace('/\*(.+?)\*/', '$1', $t);
    $t = preg_replace('/!\[[^\]]*\]\([^)]*\)/', '', $t);
    $t = preg_replace('/\[([^\]]*)\]\([^)]*\)/', '$1', $t);
    $t = preg_replace('/[`_~#]/', '', $t);
    return $t;
}

function resumen_corto(?string $resumen, string $cuerpo, int $largo = 160): string
{
    $texto = $resumen ?: texto_plano_desde_markdown($cuerpo);
    $texto = trim(preg_replace('/\s+/', ' ', $texto));
    return mb_substr($texto, 0, $largo) . (mb_strlen($texto) > $largo ? '…' : '');
}

// Tarjeta de artículo reutilizada en todas las secciones de la página: imagen
// (portada generada con IA por ID; si aún no existe —el lote corre en segundo
// plano—, cae al thumbnail del sitio original, y si tampoco existe, al
// placeholder de marca), categoría, título, resumen y sus etiquetas.
function tarjeta_articulo(array $a, array $tags = []): void
{
    $cuerpo = $a['extracto'] ?? $a['body'] ?? '';
    $id = (int) $a['id'];
    ?>
    <a href="/articulos/ver.php?id=<?= $id ?>" class="<?= e(tw_card()) ?> overflow-hidden hover:border-marca-azul transition flex flex-col">
        <div class="h-36 bg-marca-grisClaro/20 overflow-hidden">
            <img src="/articulos/img/portadas/<?= $id ?>.jpg" loading="lazy" alt="" class="w-full h-full object-cover"
                 onerror="imgFallbackPortada(this, <?= $id ?>)">
        </div>
        <div class="p-4 flex-1 flex flex-col">
            <?php if (!empty($a['categoria_nombre'])): ?>
                <span class="inline-block bg-marca-grisClaro/40 text-marca-morado text-xs px-2 py-0.5 rounded mb-2 self-start"><?= e($a['categoria_nombre']) ?></span>
            <?php endif; ?>
            <div class="font-bold mb-1"><?= e($a['title']) ?></div>
            <p class="text-sm text-gray-500 mb-2 flex-1"><?= e(resumen_corto($a['summary'] ?? null, $cuerpo)) ?></p>
            <?php if ($tags): ?>
                <div class="flex flex-wrap gap-1 pt-1 mt-auto">
                    <?php foreach (array_slice($tags, 0, 5) as $t): ?>
                        <span class="text-[11px] bg-marca-grisClaro/20 text-marca-azul px-1.5 py-0.5 rounded">#<?= e($t['name']) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </a>
    <?php
}

require __DIR__ . '/../templates/header.php';
?>

<!-- Buscador semántico -->
<section class="mb-10">
    <form method="get" id="form-busqueda-semantica" class="flex gap-2">
        <div class="relative flex-1">
            <i class="fa-solid fa-wand-magic-sparkles absolute left-3 top-1/2 -translate-y-1/2 text-marca-azul"></i>
            <input type="text" name="q" value="<?= e($q) ?>" placeholder="Busca por tema, no por palabra exacta: “cómo evaluar proyectos colaborativos”…"
                   class="w-full rounded-full border border-gray-300 pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-marca-azul">
        </div>
        <button type="submit" class="<?= e(tw_btn('primario')) ?> rounded-full px-6">Buscar</button>
    </form>
    <p class="text-xs text-gray-400 mt-1 ml-1">Búsqueda semántica con IA: entiende el significado de lo que escribes, no solo palabras exactas.</p>
</section>

<?php if ($modo === 'busqueda'): ?>

    <h1 class="text-2xl font-bold mb-1">Resultados para “<?= e($q) ?>”</h1>
    <p class="text-sm text-gray-500 mb-6"><a href="/articulos/index.php" class="text-marca-azul">&larr; Volver al inicio</a></p>

    <?php if ($errorBusqueda): ?>
        <div class="border border-amber-200 bg-amber-50 text-amber-800 rounded px-4 py-3 text-sm"><?= e($errorBusqueda) ?></div>
    <?php elseif (!$resultadosBusqueda): ?>
        <p class="text-gray-400">No encontramos artículos relacionados con esa búsqueda.</p>
    <?php else: ?>

        <?php if ($sintesis && $sintesis['respuesta'] !== ''): ?>
            <?php
            $articulosCitados = [];
            foreach ($sintesis['citados'] as $n) {
                if (isset($resultadosBusqueda[$n - 1])) {
                    $articulosCitados[] = $resultadosBusqueda[$n - 1];
                }
            }
            ?>
            <div class="<?= e(tw_card()) ?> p-5 mb-6 border-marca-azul/30">
                <div class="flex items-center gap-2 mb-2 text-marca-azul text-sm font-semibold">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Resumen con IA
                </div>
                <p class="text-gray-800 mb-3 leading-relaxed"><?= resaltar_citas($sintesis['respuesta']) ?></p>
                <?php if ($articulosCitados): ?>
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="text-gray-400">Basado en:</span>
                        <?php foreach ($articulosCitados as $ac): ?>
                            <a href="/articulos/ver.php?id=<?= (int) $ac['id'] ?>" class="bg-marca-grisClaro/30 text-marca-azul px-2 py-1 rounded hover:bg-marca-grisClaro/60">
                                <?= e($ac['title']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($resultadosBusqueda as $r): ?>
                <?php tarjeta_articulo($r, $tagsBusqueda[$r['id']] ?? []); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php elseif ($modo === 'categoria' || $modo === 'etiqueta'): ?>

    <div class="flex items-center gap-2 mb-6">
        <span class="text-sm text-gray-500"><?= $modo === 'categoria' ? 'Categoría:' : 'Etiqueta:' ?></span>
        <span class="inline-flex items-center gap-2 bg-marca-grisClaro/40 text-marca-azul text-sm px-3 py-1 rounded font-semibold">
            <?= e($categoriaNombre ?? $etiquetaNombre ?? '—') ?>
        </span>
        <a href="/articulos/index.php" class="text-sm text-marca-azul">&larr; Ver todo</a>
    </div>

    <?php if (!$listado): ?>
        <p class="text-gray-400">No hay artículos publicados en este filtro.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($listado as $a): ?>
                <?php tarjeta_articulo($a, $tagsListado[$a['id']] ?? []); ?>
            <?php endforeach; ?>
        </div>
        <div class="mt-6">
            <?= paginacion($pagina, $totalPaginas, '/articulos/index.php?' . ($modo === 'categoria' ? 'categoria_id=' . $categoriaId : 'etiqueta_id=' . $etiquetaId)) ?>
        </div>
    <?php endif; ?>

<?php else: /* inicio */ ?>

    <section class="mb-12">
        <h2 class="text-xl font-bold mb-4">Artículos de interés</h2>
        <p class="text-xs text-gray-400 -mt-3 mb-4">Los que más preguntas generan en el chat de cada artículo.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($deInteres as $a): ?>
                <?php tarjeta_articulo($a, $tagsInteres[$a['id']] ?? []); ?>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-xl font-bold mb-4">Categorías</h2>
        <div class="flex flex-wrap gap-2">
            <?php foreach ($categorias as $c): ?>
                <a href="/articulos/index.php?categoria_id=<?= (int) $c['id'] ?>"
                   class="inline-flex items-center gap-2 bg-marca-grisClaro/30 text-marca-morado text-sm px-3 py-1.5 rounded-full hover:bg-marca-grisClaro/60">
                    <?= e($c['name']) ?> <span class="text-xs opacity-70">(<?= (int) $c['n'] ?>)</span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-xl font-bold mb-1">Etiquetas</h2>
        <p class="text-xs text-gray-400 mb-4">Todas las etiquetas usadas en el portal.</p>
        <div id="nube-etiquetas" class="flex flex-wrap gap-2 max-h-64 overflow-y-auto <?= e(tw_card()) ?> p-4">
            <span class="text-sm text-gray-400">Cargando etiquetas…</span>
        </div>
    </section>
    <style>
        /* Una sola clase compartida para las ~3.390 etiquetas: mantiene el
           fragmento cargado por JS liviano (nada de repetir varias utilidades
           de Tailwind por cada una). */
        .tag-pill {
            font-size: 0.75rem;
            background: rgba(206, 207, 244, .2);
            color: #5454E9;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            height: fit-content;
        }
        .tag-pill:hover { background: rgba(206, 207, 244, .5); }
    </style>
    <script>
        fetch('/articulos/etiquetas_todas.php')
            .then(r => r.text())
            .then(html => { document.getElementById('nube-etiquetas').innerHTML = html; })
            .catch(() => {
                document.getElementById('nube-etiquetas').innerHTML =
                    '<span class="text-sm text-gray-400">No se pudieron cargar las etiquetas.</span>';
            });
    </script>

    <section class="mb-4">
        <h2 class="text-xl font-bold mb-4">
            Todos los artículos <span class="text-gray-400 text-base font-normal">(<?= number_format($totalCatalogo, 0, ',', '.') ?>)</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($catalogo as $a): ?>
                <?php tarjeta_articulo($a, $tagsCatalogo[$a['id']] ?? []); ?>
            <?php endforeach; ?>
        </div>
        <div class="mt-6">
            <?= paginacion($pagina, $totalPaginasCatalogo, '/articulos/index.php') ?>
        </div>
    </section>

<?php endif; ?>

<!-- Chatbot general: "Pregúntale a Eduteka" (RAG sobre los 1.577 artículos) -->
<div class="no-imprimir fixed bottom-6 right-6 z-40">
    <button id="btn-chat-general-flotante" onclick="alternarChatGeneral()" type="button"
            class="group relative w-14 h-14 rounded-full bg-marca-azul text-white shadow-lg hover:bg-marca-azulHover transition flex items-center justify-center text-xl">
        <i id="icono-chat-general-flotante" class="fa-solid fa-graduation-cap"></i>
        <span class="pointer-events-none absolute right-full mr-3 top-1/2 -translate-y-1/2 whitespace-nowrap rounded bg-gray-900 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100">
            Pregúntale a Eduteka
        </span>
    </button>
</div>

<div id="ventana-chat-general" class="no-imprimir hidden fixed bottom-24 right-6 z-40 w-[380px] max-w-[calc(100vw-2rem)] max-h-[32rem] flex-col overflow-hidden rounded-xl border border-gray-100 bg-white shadow-2xl">
    <div class="flex items-center gap-3 bg-marca-azul px-4 py-3 text-white shrink-0">
        <div class="flex w-9 h-9 items-center justify-center rounded-full bg-white/15">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div class="flex-1 leading-tight">
            <div class="text-sm font-semibold">Pregúntale a Eduteka</div>
            <div class="text-xs text-marca-grisClaro">Busca en los 1.577 artículos</div>
        </div>
        <button onclick="alternarChatGeneral()" type="button" class="px-1 text-lg leading-none text-white/80 hover:text-white">&minus;</button>
    </div>

    <div id="chat-general-bienvenida" class="overflow-y-auto px-5 py-6 text-center">
        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-marca-grisClaro/40 text-2xl text-marca-azul">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
        <h3 class="mb-1 font-bold">¡Hola! Soy el asistente de Eduteka</h3>
        <p class="mb-4 text-sm text-gray-500">Pregúntame lo que quieras: busco en todos los artículos del portal y te respondo citando las fuentes.</p>
        <div class="space-y-2 text-left">
            <button type="button" class="sugerencia-chat-general flex w-full items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm transition hover:border-marca-azul hover:bg-marca-azul/5"
                    data-pregunta="¿Qué es la Competencia para Manejar Información (CMI)?">
                <i class="fa-regular fa-lightbulb w-4 text-marca-azul"></i> ¿Qué es la CMI?
            </button>
            <button type="button" class="sugerencia-chat-general flex w-full items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm transition hover:border-marca-azul hover:bg-marca-azul/5"
                    data-pregunta="Dame ideas para usar Scratch con niños de primaria">
                <i class="fa-solid fa-code w-4 text-marca-azul"></i> Ideas con Scratch en primaria
            </button>
            <button type="button" class="sugerencia-chat-general flex w-full items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm transition hover:border-marca-azul hover:bg-marca-azul/5"
                    data-pregunta="¿Cómo diseño una rúbrica de evaluación?">
                <i class="fa-regular fa-clipboard w-4 text-marca-azul"></i> Diseñar una rúbrica
            </button>
            <button type="button" class="sugerencia-chat-general flex w-full items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm transition hover:border-marca-azul hover:bg-marca-azul/5"
                    data-pregunta="¿Qué dice Eduteka sobre el pensamiento computacional?">
                <i class="fa-solid fa-diagram-project w-4 text-marca-azul"></i> Pensamiento computacional
            </button>
        </div>
    </div>

    <div id="chat-general-mensajes" class="hidden flex-1 space-y-3 overflow-y-auto px-4 py-3"></div>

    <form id="form-chat-general" class="flex shrink-0 gap-2 border-t border-gray-100 p-3">
        <input id="chat-general-input" type="text" required placeholder="Escribe tu pregunta..."
               class="flex-1 rounded-full border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-marca-azul">
        <button type="submit" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-marca-azul text-white transition hover:bg-marca-azulHover">
            <i class="fa-solid fa-paper-plane text-sm"></i>
        </button>
    </form>
</div>

<script>
// Cadena de imagen de tarjeta: portada IA -> thumbnail del sitio original ->
// placeholder de marca. El lote de portadas corre en segundo plano, así que
// mientras no termine, algunos artículos todavía no tienen su .jpg en disco.
function imgFallbackPortada(img, id) {
    if (!img.dataset.intentoThumb) {
        img.dataset.intentoThumb = '1';
        img.src = 'https://eduteka.icesi.edu.co/thumb/m' + id + '.jpg';
        return;
    }
    const div = img.closest('div');
    div.classList.add('bg-gradient-to-br', 'from-marca-azul', 'to-marca-morado', 'flex', 'items-center', 'justify-center');
    img.outerHTML = '<i class="fa-solid fa-book-open text-white text-3xl opacity-80"></i>';
}

function avisarChatGeneral(icono, titulo) {
    Swal.fire({ toast: true, position: 'top-end', icon: icono, title: titulo, showConfirmButton: false, timer: 2200, timerProgressBar: true });
}

// El buscador es una recarga completa de página (no fetch), y el resumen con IA
// tarda unos segundos (embedding + generación) — sin esto la pantalla queda en
// blanco sin ningún indicador mientras carga.
const formBusquedaSemantica = document.getElementById('form-busqueda-semantica');
formBusquedaSemantica.addEventListener('submit', () => {
    const valor = formBusquedaSemantica.querySelector('input[name="q"]').value.trim();
    if (!valor) return;
    Swal.fire({
        title: 'Buscando la respuesta a tu pregunta…',
        html: 'Analizando los artículos de Eduteka con IA, esto puede tardar unos segundos.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading(),
    });
});

const ventanaChatGeneral = document.getElementById('ventana-chat-general');
const iconoChatGeneralFlotante = document.getElementById('icono-chat-general-flotante');
const chatGeneralBienvenida = document.getElementById('chat-general-bienvenida');
const listaMensajesGeneral = document.getElementById('chat-general-mensajes');
const formChatGeneral = document.getElementById('form-chat-general');
const inputChatGeneral = document.getElementById('chat-general-input');
const historialGeneral = [];

function alternarChatGeneral() {
    const abierta = ventanaChatGeneral.classList.contains('flex');
    if (abierta) {
        ventanaChatGeneral.classList.remove('flex');
        ventanaChatGeneral.classList.add('hidden');
        iconoChatGeneralFlotante.className = 'fa-solid fa-graduation-cap';
    } else {
        ventanaChatGeneral.classList.remove('hidden');
        ventanaChatGeneral.classList.add('flex');
        iconoChatGeneralFlotante.className = 'fa-solid fa-xmark';
        inputChatGeneral.focus();
    }
}

function mostrarConversacionGeneral() {
    if (!chatGeneralBienvenida.classList.contains('hidden')) {
        chatGeneralBienvenida.classList.add('hidden');
        listaMensajesGeneral.classList.remove('hidden');
    }
}

document.querySelectorAll('.sugerencia-chat-general').forEach((boton) => {
    boton.addEventListener('click', () => {
        inputChatGeneral.value = boton.dataset.pregunta;
        formChatGeneral.requestSubmit();
    });
});

function agregarMensajeGeneral(rol, texto) {
    const div = document.createElement('div');
    const esUsuario = rol === 'user';
    div.className = esUsuario ? 'text-right' : 'text-left';
    div.innerHTML = '<span class="inline-block rounded px-3 py-2 text-sm max-w-[90%] '
        + (esUsuario ? 'bg-marca-azul text-white' : 'bg-gray-100 text-gray-800')
        + '">' + texto.replace(/</g, '&lt;') + '</span>';
    listaMensajesGeneral.appendChild(div);
    listaMensajesGeneral.scrollTop = listaMensajesGeneral.scrollHeight;
    return div;
}

function agregarFuentesGeneral(citados) {
    if (!citados || !citados.length) return;
    const div = document.createElement('div');
    div.className = 'text-left';
    let html = '<div class="inline-flex flex-wrap gap-1 max-w-[90%]"><span class="text-[11px] text-gray-400 w-full">Fuentes:</span>';
    citados.forEach((c) => {
        html += '<a href="/articulos/ver.php?id=' + c.id + '" target="_blank" class="text-[11px] bg-marca-grisClaro/30 text-marca-azul px-2 py-0.5 rounded hover:bg-marca-grisClaro/60">'
            + c.title.replace(/</g, '&lt;') + '</a>';
    });
    html += '</div>';
    div.innerHTML = html;
    listaMensajesGeneral.appendChild(div);
    listaMensajesGeneral.scrollTop = listaMensajesGeneral.scrollHeight;
}

formChatGeneral.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    const mensaje = inputChatGeneral.value.trim();
    if (!mensaje) return;
    mostrarConversacionGeneral();
    agregarMensajeGeneral('user', mensaje);
    inputChatGeneral.value = '';
    inputChatGeneral.disabled = true;
    const indicador = agregarMensajeGeneral('assistant', 'Buscando en los artículos…');

    try {
        const resp = await fetch('/articulos/chat_general.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: mensaje, history: historialGeneral }),
        });
        const datos = await resp.json();
        if (!resp.ok) {
            indicador.querySelector('span').textContent = datos.error || 'Ocurrió un error, intenta de nuevo.';
            if (resp.status === 429) {
                avisarChatGeneral('warning', 'Demasiadas preguntas seguidas, espera unos minutos');
            } else {
                avisarChatGeneral('error', 'No se pudo responder tu pregunta');
            }
        } else {
            indicador.querySelector('span').textContent = datos.reply;
            agregarFuentesGeneral(datos.citados);
            historialGeneral.push({ role: 'user', content: mensaje });
            historialGeneral.push({ role: 'assistant', content: datos.reply });
        }
    } catch (e) {
        indicador.querySelector('span').textContent = 'No se pudo conectar. Intenta de nuevo.';
        avisarChatGeneral('error', 'No se pudo conectar con el chat');
    } finally {
        inputChatGeneral.disabled = false;
        inputChatGeneral.focus();
    }
});
</script>

<?php require __DIR__ . '/../templates/footer.php'; ?>
