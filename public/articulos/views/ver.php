<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista de detalle de un articulo. Recibe todos los datos ya resueltos por
 * App\Controllers\ArticleController::show() via App\Lib\View::render().
 *
 * @var array|null $articulo null cuando no se encontro (404)
 * @var int $id
 * @var array $etiquetas
 * @var array $relacionados
 * @var string $titulo
 * @var string|null $descripcion
 * @var string|null $urlCanonica
 * @var string|null $contenidoHtml
 * @var array|null $sugerenciasChat
 */

if ($articulo === null) {
    require __DIR__ . '/../templates/header.php';
    echo '<p class="text-gray-500">No encontramos ese artículo, o no está publicado.</p>';
    require __DIR__ . '/../templates/footer.php';
    return;
}

$iconosSugerencias = ['fa-solid fa-lightbulb', 'fa-solid fa-people-group', 'fa-regular fa-clipboard', 'fa-solid fa-book-open'];

require __DIR__ . '/../templates/header.php';
?>

<article>
    <div class="mb-4">
        <?php if ($articulo['categoria_nombre']): ?>
            <a href="/articulos/index.php?categoria_id=<?= (int) $articulo['category_id'] ?>"
               class="inline-block bg-marca-grisClaro/40 text-marca-morado text-xs font-semibold px-2 py-1 rounded mb-3">
                <?= e($articulo['categoria_nombre']) ?>
            </a>
        <?php endif; ?>
        <h1 class="text-3xl font-extrabold leading-tight mb-2"><?= e($articulo['title']) ?></h1>
        <div class="text-sm text-gray-500 flex flex-wrap gap-x-3 gap-y-1">
            <?php if ($articulo['author']): ?><span>Por <?= e($articulo['author']) ?></span><?php endif; ?>
            <?php if ($articulo['article_date']): ?><span><?= e((string) $articulo['article_date']) ?></span><?php endif; ?>
        </div>
    </div>

    <!-- Barra de acciones: imprimir, compartir, descargar, escuchar -->
    <div class="no-imprimir flex flex-wrap items-center gap-2 mb-8 pb-4 border-b border-gray-100">
        <button onclick="window.print()" class="<?= e(tw_btn('outline')) ?>">
            <i class="fa-solid fa-print"></i> Imprimir
        </button>

        <button id="btn-compartir" onclick="compartirArticulo()" class="<?= e(tw_btn('outline')) ?>">
            <i class="fa-solid fa-share-nodes"></i> Compartir
        </button>

        <a href="/articulos/descargar.php?id=<?= (int) $id ?>" id="btn-descargar" class="<?= e(tw_btn('outline')) ?>"><i class="fa-solid fa-file-pdf"></i> Descargar PDF</a>

        <button id="btn-escuchar" onclick="alternarLectura()" class="<?= e(tw_btn('outline')) ?>">
            <i class="fa-solid fa-volume-high"></i> Escuchar
        </button>
    </div>

    <!-- Reproductor de lectura en voz alta: aparece al darle a "Escuchar" -->
    <div id="barra-audio" class="no-imprimir hidden items-center gap-3 bg-marca-grisClaro/15 border border-marca-grisClaro/60 rounded-full px-4 py-2 mb-8 -mt-4">
        <button onclick="alternarLectura()" class="text-marca-azul text-lg w-5 shrink-0" title="Pausar/Reanudar">
            <i id="icono-play-barra" class="fa-solid fa-pause"></i>
        </button>
        <span id="tiempo-transcurrido" class="text-xs text-gray-500 tabular-nums w-10 shrink-0 text-right">0:00</span>
        <input type="range" id="slider-audio" min="0" max="1000" value="0"
               class="flex-1 h-1.5 rounded-full cursor-pointer accent-marca-azul">
        <span id="tiempo-total" class="text-xs text-gray-500 tabular-nums w-10 shrink-0">0:00</span>
        <button onclick="cambiarVelocidad()" class="text-xs font-semibold text-marca-azul border border-marca-azul rounded-full px-2 py-0.5 w-10 shrink-0 text-center" title="Velocidad de lectura">
            <span id="texto-velocidad-barra">1x</span>
        </button>
        <button onclick="adelantarLectura()" class="text-marca-azul text-lg shrink-0" title="Adelantar">
            <i class="fa-solid fa-forward-step"></i>
        </button>
        <button onclick="detenerLecturaManual()" class="text-gray-400 hover:text-red-500 text-lg shrink-0" title="Detener">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div id="contenido-articulo" class="prose prose-slate max-w-none prose-headings:font-bold prose-a:text-marca-azul">
        <?= $contenidoHtml ?>
    </div>

    <?php if ($etiquetas): ?>
    <div class="no-imprimir mt-8 flex flex-wrap gap-2">
        <?php foreach ($etiquetas as $t): ?>
            <a href="/articulos/index.php?etiqueta_id=<?= (int) $t['id'] ?>"
               class="text-xs bg-marca-grisClaro/30 text-marca-azul px-2 py-1 rounded hover:bg-marca-grisClaro/60">
                #<?= e($t['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</article>

<?php if ($relacionados): ?>
<section class="no-imprimir mt-12">
    <h2 class="text-xl font-bold mb-4">Artículos relacionados</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <?php foreach ($relacionados as $r): ?>
            <a href="/articulos/ver.php?id=<?= $r['id'] ?>" class="<?= e(tw_card()) ?> p-4 hover:border-marca-azul transition">
                <div class="font-semibold text-sm"><?= e($r['title']) ?></div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Chatbot flotante: chatear con este artículo -->
<div class="no-imprimir fixed bottom-6 right-6 z-40">
    <button id="btn-chat-flotante" onclick="alternarChat()" type="button"
            class="group relative w-14 h-14 rounded-full bg-marca-azul text-white shadow-lg hover:bg-marca-azulHover transition flex items-center justify-center text-xl">
        <i id="icono-chat-flotante" class="fa-solid fa-comment-dots"></i>
        <span class="pointer-events-none absolute right-full mr-3 top-1/2 -translate-y-1/2 whitespace-nowrap rounded bg-gray-900 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100">
            Chatea sobre este artículo
        </span>
    </button>
</div>

<div id="ventana-chat" class="no-imprimir hidden fixed bottom-24 right-6 z-40 w-[380px] max-w-[calc(100vw-2rem)] max-h-[32rem] flex-col overflow-hidden rounded-xl border border-gray-100 bg-white shadow-2xl">
    <div class="flex items-center gap-3 bg-marca-azul px-4 py-3 text-white shrink-0">
        <div class="flex w-9 h-9 items-center justify-center rounded-full bg-white/15">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div class="flex-1 leading-tight">
            <div class="text-sm font-semibold">Asistente Pedagógico</div>
            <div class="text-xs text-marca-grisClaro">Conectado</div>
        </div>
        <button onclick="alternarChat()" type="button" class="px-1 text-lg leading-none text-white/80 hover:text-white">&minus;</button>
    </div>

    <div id="chat-bienvenida" class="overflow-y-auto px-5 py-6 text-center">
        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-marca-grisClaro/40 text-2xl text-marca-azul">
            <i class="fa-solid fa-comment-dots"></i>
        </div>
        <h3 class="mb-1 font-bold">¡Hola! Soy tu asistente pedagógico</h3>
        <p class="mb-4 text-sm text-gray-500">Conozco el contenido de este artículo y puedo ayudarte con:</p>
        <div class="space-y-2 text-left">
            <?php foreach ($sugerenciasChat as $n => $s): ?>
                <button type="button" class="sugerencia-chat flex w-full items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm transition hover:border-marca-azul hover:bg-marca-azul/5"
                        data-pregunta="<?= e($s['pregunta']) ?>">
                    <i class="<?= e($iconosSugerencias[$n % count($iconosSugerencias)]) ?> w-4 text-marca-azul"></i> <?= e($s['etiqueta']) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="chat-mensajes" class="hidden flex-1 space-y-3 overflow-y-auto px-4 py-3"></div>

    <form id="form-chat" class="flex shrink-0 gap-2 border-t border-gray-100 p-3">
        <input id="chat-input" type="text" required placeholder="Escribe tu pregunta..."
               class="flex-1 rounded-full border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-marca-azul">
        <button type="submit" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-marca-azul text-white transition hover:bg-marca-azulHover">
            <i class="fa-solid fa-paper-plane text-sm"></i>
        </button>
    </form>
</div>

<script>
const ARTICULO_ID = <?= (int) $id ?>;
const URL_ARTICULO = <?= json_encode($urlCanonica) ?>;
const TITULO_ARTICULO = <?= json_encode($articulo['title']) ?>;

// --- Aviso rápido (toast) ---
function avisar(icono, titulo) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icono,
        title: titulo,
        showConfirmButton: false,
        timer: 2200,
        timerProgressBar: true,
    });
}

// --- Compartir ---
// Antes intentaba primero el panel nativo del sistema operativo (navigator.share())
// y solo caía al modal propio si fallaba. Se quita esa vía: en algunos navegadores
// el panel nativo se abre y se cierra solo a los pocos segundos sin que el usuario
// haga nada (comportamiento del propio SO, fuera de nuestro control), lo que parecía
// "el botón no funciona". Ahora siempre se usa el modal de SweetAlert, consistente
// en cualquier navegador.
function compartirArticulo() {
    abrirModalCompartir();
}

function abrirModalCompartir() {
    Swal.fire({
        title: 'Compartir artículo',
        width: 380,
        showConfirmButton: false,
        showCloseButton: true,
        html: `
            <div class="flex flex-col gap-2 text-left">
                <button id="sw-copiar" type="button" class="flex w-full items-center gap-3 rounded-lg border border-gray-200 px-4 py-3 text-sm transition hover:border-marca-azul hover:bg-marca-azul/5">
                    <i class="fa-regular fa-copy w-5 text-marca-azul"></i> Copiar enlace
                </button>
                <a id="sw-whatsapp" target="_blank" rel="noopener" class="flex w-full items-center gap-3 rounded-lg border border-gray-200 px-4 py-3 text-sm transition hover:border-marca-azul hover:bg-marca-azul/5">
                    <i class="fa-brands fa-whatsapp w-5 text-marca-azul"></i> WhatsApp
                </a>
                <a id="sw-x" target="_blank" rel="noopener" class="flex w-full items-center gap-3 rounded-lg border border-gray-200 px-4 py-3 text-sm transition hover:border-marca-azul hover:bg-marca-azul/5">
                    <i class="fa-brands fa-x-twitter w-5 text-marca-azul"></i> X (Twitter)
                </a>
                <a id="sw-correo" class="flex w-full items-center gap-3 rounded-lg border border-gray-200 px-4 py-3 text-sm transition hover:border-marca-azul hover:bg-marca-azul/5">
                    <i class="fa-regular fa-envelope w-5 text-marca-azul"></i> Correo
                </a>
            </div>
        `,
        didOpen: () => {
            document.getElementById('sw-whatsapp').href =
                'https://wa.me/?text=' + encodeURIComponent(TITULO_ARTICULO + ' ' + URL_ARTICULO);
            document.getElementById('sw-x').href =
                'https://twitter.com/intent/tweet?url=' + encodeURIComponent(URL_ARTICULO) + '&text=' + encodeURIComponent(TITULO_ARTICULO);
            document.getElementById('sw-correo').href =
                'mailto:?subject=' + encodeURIComponent(TITULO_ARTICULO) + '&body=' + encodeURIComponent(URL_ARTICULO);
            document.getElementById('sw-copiar').addEventListener('click', () => {
                navigator.clipboard.writeText(URL_ARTICULO).then(() => {
                    Swal.close();
                    avisar('success', 'Enlace copiado al portapapeles');
                }).catch(() => {
                    avisar('error', 'No se pudo copiar el enlace');
                });
            });
        },
    });
}

document.getElementById('btn-descargar').addEventListener('click', () => {
    avisar('success', 'Descargando el artículo en PDF…');
});

// --- Escuchar (Web Speech API, voz del navegador) ---
// Chrome tiene un bug conocido: una utterance larga (varios miles de caracteres,
// como un artículo completo) se detiene sola y en silencio a los ~15s y nunca
// se reanuda. La solución estándar es trocear el texto en fragmentos cortos y
// encolarlos uno tras otro (speechSynthesis.pause/resume sí funciona bien sobre
// una cola de varias utterances). También hay que esperar a que el navegador
// cargue la lista de voces antes de hablar (getVoices() puede llegar vacía la
// primera vez, sobre todo en Chrome).
let leyendo = false;
let pausado = false;
let colaLectura = [];
let offsetsLectura = [0]; // caracteres acumulados antes de cada fragmento (para el slider)
let indiceLectura = 0;
let vozEspanol = null;
let saltandoIntencionalmente = false;

const VELOCIDADES = [0.75, 1, 1.25, 1.5, 2];
let indiceVelocidad = 1; // arranca en 1x

// La Web Speech API no expone la posición real de reproducción (no hay
// "currentTime" como en <audio>), así que el progreso y el tiempo son una
// ESTIMACIÓN a partir de caracteres leídos y una velocidad de lectura
// aproximada. Se resincroniza en cada cambio de fragmento para no acumular
// deriva en artículos largos.
const CARACTERES_POR_SEGUNDO = 15;
let segundosDentroFragmento = 0;
let ultimoTick = 0;
let intervaloReloj = null;
let arrastrandoSlider = false;

function trocearTexto(texto, maxCaracteres = 180) {
    const oraciones = texto.match(/[^.!?\n]+[.!?\n]*/g) || [texto];
    const trozos = [];
    let actual = '';
    for (const oracion of oraciones) {
        if (actual && (actual.length + oracion.length) > maxCaracteres) {
            trozos.push(actual.trim());
            actual = oracion;
        } else {
            actual += oracion;
        }
    }
    if (actual.trim()) {
        trozos.push(actual.trim());
    }
    return trozos.filter(t => t.length > 0);
}

function calcularOffsets(trozos) {
    const offsets = [0];
    let acumulado = 0;
    for (const t of trozos) {
        acumulado += t.length + 1;
        offsets.push(acumulado);
    }
    return offsets;
}

function formatoTiempo(segundosTotales) {
    segundosTotales = Math.max(0, Math.round(segundosTotales));
    const horas = Math.floor(segundosTotales / 3600);
    const min = Math.floor((segundosTotales % 3600) / 60);
    const seg = segundosTotales % 60;
    const segStr = String(seg).padStart(2, '0');
    if (horas > 0) {
        return horas + ':' + String(min).padStart(2, '0') + ':' + segStr;
    }
    return min + ':' + segStr;
}

function actualizarIconoPlay(iconoClase) {
    document.getElementById('icono-play-barra').className = 'fa-solid ' + iconoClase;
}

function mostrarBarraAudio(mostrar) {
    document.getElementById('btn-escuchar').classList.toggle('hidden', mostrar);
    const barra = document.getElementById('barra-audio');
    barra.classList.toggle('hidden', !mostrar);
    barra.classList.toggle('flex', mostrar);
}

function detenerLectura() {
    leyendo = false;
    pausado = false;
    if (intervaloReloj) {
        clearInterval(intervaloReloj);
        intervaloReloj = null;
    }
    mostrarBarraAudio(false);
}

function detenerLecturaManual() {
    saltandoIntencionalmente = true;
    window.speechSynthesis.cancel();
    detenerLectura();
}

// Crea la utterance del fragmento actual (indiceLectura), sin avanzar el índice —
// la usan tanto la reproducción normal como "cambiar velocidad" (que reinicia el
// fragmento en curso), "adelantar" y el slider (que primero mueven el índice y
// luego llaman esto).
function hablarFragmentoActual() {
    if (indiceLectura >= colaLectura.length) {
        detenerLectura();
        return;
    }
    segundosDentroFragmento = 0;
    ultimoTick = Date.now();

    const utterance = new SpeechSynthesisUtterance(colaLectura[indiceLectura]);
    utterance.lang = 'es-ES';
    utterance.rate = VELOCIDADES[indiceVelocidad];
    if (vozEspanol) {
        utterance.voice = vozEspanol;
    }
    utterance.onend = () => {
        if (saltandoIntencionalmente) {
            saltandoIntencionalmente = false;
            return;
        }
        indiceLectura++;
        hablarFragmentoActual();
    };
    utterance.onerror = (ev) => {
        if (saltandoIntencionalmente) {
            saltandoIntencionalmente = false;
            return;
        }
        console.error('Error de síntesis de voz:', ev.error);
        avisar('error', 'Ocurrió un error al leer el artículo en voz alta');
        detenerLectura();
    };
    window.speechSynthesis.speak(utterance);
}

function iniciarLectura() {
    const contenido = document.getElementById('contenido-articulo').innerText.trim();
    if (!contenido) {
        avisar('error', 'No hay contenido para leer');
        return;
    }
    const voces = window.speechSynthesis.getVoices();
    vozEspanol = voces.find(v => v.lang && v.lang.toLowerCase().startsWith('es')) || null;
    if (!vozEspanol && voces.length === 0) {
        avisar('warning', 'Tu navegador no tiene voces instaladas para leer en voz alta');
        return;
    }

    window.speechSynthesis.cancel();
    colaLectura = trocearTexto(contenido);
    offsetsLectura = calcularOffsets(colaLectura);
    indiceLectura = 0;
    leyendo = true;
    pausado = false;
    mostrarBarraAudio(true);
    actualizarIconoPlay('fa-pause');
    if (intervaloReloj) {
        clearInterval(intervaloReloj);
    }
    intervaloReloj = setInterval(tickReloj, 250);
    hablarFragmentoActual();
}

function alternarLectura() {
    if (!('speechSynthesis' in window)) {
        avisar('error', 'Tu navegador no soporta lectura en voz alta');
        return;
    }
    if (!leyendo) {
        // getVoices() puede llegar vacía la primera vez; se espera el evento
        // voiceschanged, con un margen de espera por si el navegador no lo dispara.
        if (window.speechSynthesis.getVoices().length === 0) {
            let yaInicio = false;
            const iniciarUnaVez = () => {
                if (yaInicio) return;
                yaInicio = true;
                iniciarLectura();
            };
            window.speechSynthesis.addEventListener('voiceschanged', iniciarUnaVez, { once: true });
            setTimeout(iniciarUnaVez, 400);
        } else {
            iniciarLectura();
        }
    } else if (!pausado) {
        window.speechSynthesis.pause();
        pausado = true;
        actualizarIconoPlay('fa-play');
    } else {
        window.speechSynthesis.resume();
        pausado = false;
        actualizarIconoPlay('fa-pause');
    }
}

// Adelanta al siguiente fragmento (equivalente a "saltar" ~una oración/frase,
// que es la granularidad con la que se trocea la lectura).
function adelantarLectura() {
    if (!leyendo || indiceLectura >= colaLectura.length - 1) {
        return;
    }
    saltandoIntencionalmente = true;
    window.speechSynthesis.cancel();
    indiceLectura++;
    pausado = false;
    actualizarIconoPlay('fa-pause');
    hablarFragmentoActual();
    actualizarBarraProgreso(); // refresco inmediato: no esperar al próximo tick del intervalo
}

// La Web Speech API no permite cambiar la velocidad de una utterance que ya está
// sonando, así que se reinicia el fragmento actual (sin avanzar el índice) con
// la nueva velocidad aplicada.
function cambiarVelocidad() {
    indiceVelocidad = (indiceVelocidad + 1) % VELOCIDADES.length;
    const nuevaVelocidad = VELOCIDADES[indiceVelocidad];
    document.getElementById('texto-velocidad-barra').textContent = nuevaVelocidad + 'x';
    avisar('info', 'Velocidad de lectura: ' + nuevaVelocidad + 'x');
    if (leyendo && !pausado) {
        saltandoIntencionalmente = true;
        window.speechSynthesis.cancel();
        hablarFragmentoActual();
    }
    if (leyendo) {
        actualizarBarraProgreso(); // refresco inmediato (el total cambia con la velocidad)
    }
}

function tickReloj() {
    if (leyendo && !pausado) {
        const ahora = Date.now();
        segundosDentroFragmento += (ahora - ultimoTick) / 1000;
        ultimoTick = ahora;
    } else {
        ultimoTick = Date.now();
    }
    actualizarBarraProgreso();
}

function actualizarBarraProgreso() {
    const velocidad = VELOCIDADES[indiceVelocidad];
    const totalCaracteres = offsetsLectura[offsetsLectura.length - 1] || 1;
    const totalSeg = totalCaracteres / (CARACTERES_POR_SEGUNDO * velocidad);

    let caracteresConsumidos = (offsetsLectura[indiceLectura] || 0)
        + segundosDentroFragmento * CARACTERES_POR_SEGUNDO * velocidad;
    caracteresConsumidos = Math.min(caracteresConsumidos, totalCaracteres);
    const elapsedSeg = caracteresConsumidos / (CARACTERES_POR_SEGUNDO * velocidad);

    document.getElementById('tiempo-transcurrido').textContent = formatoTiempo(elapsedSeg);
    document.getElementById('tiempo-total').textContent = formatoTiempo(totalSeg);

    if (!arrastrandoSlider) {
        document.getElementById('slider-audio').value = Math.round((caracteresConsumidos / totalCaracteres) * 1000) || 0;
    }
}

const sliderAudio = document.getElementById('slider-audio');

sliderAudio.addEventListener('input', () => {
    // Mientras se arrastra: solo previsualizar el tiempo, sin saltar todavía
    // (saltar en cada pixel arrastrado cancelaría/reiniciaría el audio sin parar).
    arrastrandoSlider = true;
    const velocidad = VELOCIDADES[indiceVelocidad];
    const totalCaracteres = offsetsLectura[offsetsLectura.length - 1] || 1;
    const previewSeg = (sliderAudio.value / 1000) * totalCaracteres / (CARACTERES_POR_SEGUNDO * velocidad);
    document.getElementById('tiempo-transcurrido').textContent = formatoTiempo(previewSeg);
});

sliderAudio.addEventListener('change', () => {
    arrastrandoSlider = false;
    if (!leyendo || colaLectura.length === 0) {
        return;
    }
    const totalCaracteres = offsetsLectura[offsetsLectura.length - 1] || 1;
    const caracteresObjetivo = (sliderAudio.value / 1000) * totalCaracteres;

    let nuevoIndice = 0;
    for (let i = 0; i < offsetsLectura.length - 1; i++) {
        if (caracteresObjetivo >= offsetsLectura[i]) {
            nuevoIndice = i;
        }
    }
    nuevoIndice = Math.min(nuevoIndice, colaLectura.length - 1);

    saltandoIntencionalmente = true;
    window.speechSynthesis.cancel();
    indiceLectura = nuevoIndice;
    pausado = false;
    actualizarIconoPlay('fa-pause');
    hablarFragmentoActual();
    actualizarBarraProgreso(); // refresco inmediato: no esperar al próximo tick del intervalo
});

window.addEventListener('beforeunload', () => {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
    }
});

// --- Chat con el artículo (widget flotante) ---
const historial = [];
const listaMensajes = document.getElementById('chat-mensajes');
const formChat = document.getElementById('form-chat');
const inputChat = document.getElementById('chat-input');
const ventanaChat = document.getElementById('ventana-chat');
const iconoChatFlotante = document.getElementById('icono-chat-flotante');
const chatBienvenida = document.getElementById('chat-bienvenida');

function alternarChat() {
    const abierta = ventanaChat.classList.contains('flex');
    if (abierta) {
        ventanaChat.classList.remove('flex');
        ventanaChat.classList.add('hidden');
        iconoChatFlotante.className = 'fa-solid fa-comment-dots';
    } else {
        ventanaChat.classList.remove('hidden');
        ventanaChat.classList.add('flex');
        iconoChatFlotante.className = 'fa-solid fa-xmark';
        inputChat.focus();
    }
}

function mostrarConversacion() {
    if (!chatBienvenida.classList.contains('hidden')) {
        chatBienvenida.classList.add('hidden');
        listaMensajes.classList.remove('hidden');
    }
}

document.querySelectorAll('.sugerencia-chat').forEach((boton) => {
    boton.addEventListener('click', () => {
        inputChat.value = boton.dataset.pregunta;
        formChat.requestSubmit();
    });
});

function agregarMensaje(rol, texto) {
    const div = document.createElement('div');
    const esUsuario = rol === 'user';
    div.className = esUsuario
        ? 'text-right'
        : 'text-left';
    div.innerHTML = '<span class="inline-block rounded px-3 py-2 text-sm max-w-[85%] '
        + (esUsuario ? 'bg-marca-azul text-white' : 'bg-gray-100 text-gray-800')
        + '">' + texto.replace(/</g, '&lt;') + '</span>';
    listaMensajes.appendChild(div);
    listaMensajes.scrollTop = listaMensajes.scrollHeight;
    return div;
}

formChat.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    const mensaje = inputChat.value.trim();
    if (!mensaje) return;
    mostrarConversacion();
    agregarMensaje('user', mensaje);
    inputChat.value = '';
    inputChat.disabled = true;
    const indicador = agregarMensaje('assistant', 'Escribiendo…');

    try {
        const resp = await fetch('/articulos/chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ article_id: ARTICULO_ID, message: mensaje, history: historial }),
        });
        const datos = await resp.json();
        if (!resp.ok) {
            indicador.querySelector('span').textContent = datos.error || 'Ocurrió un error, intenta de nuevo.';
            if (resp.status === 429) {
                avisar('warning', 'Demasiadas preguntas seguidas, espera unos minutos');
            } else {
                avisar('error', 'No se pudo responder tu pregunta');
            }
        } else {
            indicador.querySelector('span').textContent = datos.reply;
            historial.push({ role: 'user', content: mensaje });
            historial.push({ role: 'assistant', content: datos.reply });
        }
    } catch (e) {
        indicador.querySelector('span').textContent = 'No se pudo conectar. Intenta de nuevo.';
        avisar('error', 'No se pudo conectar con el chat');
    } finally {
        inputChat.disabled = false;
        inputChat.focus();
    }
});
</script>

<?php require __DIR__ . '/../templates/footer.php'; ?>
