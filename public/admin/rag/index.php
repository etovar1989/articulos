<?php
declare(strict_types=1);
require __DIR__ . '/../lib/auth.php';
require __DIR__ . '/../lib/helpers.php';
require __DIR__ . '/../lib/db.php';
requiere_login();

// admin/lib/helpers.php ya define e()/tw_btn()/tw_card()/tw_input()/paginacion(); no se puede
// requerir también articulos/lib/helpers.php (redeclara esas mismas funciones). Las tres que sí
// necesitan articulos/lib/busqueda.php y articulos/lib/chat_general.php (markdown_render,
// ip_cliente, parse_pg_vector) se definen aquí antes de cargar esos archivos.
require_once __DIR__ . '/../../articulos/vendor/autoload.php';

function markdown_render(string $md): string
{
    static $parsedown = null;
    if ($parsedown === null) {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
    }
    $md = preg_replace('/^[ \t\x{00A0}]+$/mu', '', $md);
    $md = preg_replace('/\n{3,}/', "\n\n", $md);
    return $parsedown->text(trim($md));
}

function ip_cliente(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function parse_pg_vector(string $literal): array
{
    $recortado = trim($literal, '[]');
    if ($recortado === '') {
        return [];
    }
    return array_map('floatval', explode(',', $recortado));
}

require __DIR__ . '/../../articulos/lib/busqueda.php';
require __DIR__ . '/../../articulos/lib/chat_general.php';

$pdo = db();
$configIA = require __DIR__ . '/../../articulos/config/config.php';

const PRECIO_ENTRADA_POR_M = 0.15;
const PRECIO_SALIDA_POR_M = 0.60;
const TOP_EXTRA_VISUALIZACION = 5; // candidatos de más solo para ver "qué quedó justo fuera del top-K"

// Reproduce el mismo flujo de generar_respuesta_rag_general() pero exponiendo cada paso
// intermedio (candidatos antes de podar, contexto exacto, respuesta cruda) para el inspector.
// No usa esa función directamente: aquí no interesa solo el resultado final sino la trazabilidad
// completa, y tampoco debe escribir en chat_general_log (eso es el registro de preguntas reales
// de usuarios para perfilarlos, no de pruebas del admin).
function ejecutar_pipeline_rag(PDO $pdo, array $config, string $mensaje, array $historial): array
{
    $pasos = [];

    $t0 = microtime(true);
    $condensada = condensar_pregunta($config, $mensaje, $historial);
    $pasos['condensacion'] = [
        'aplico' => $condensada !== $mensaje,
        'original' => $mensaje,
        'condensada' => $condensada,
        'ms' => round((microtime(true) - $t0) * 1000),
    ];

    $hash = hash_consulta($condensada);
    $chk = $pdo->prepare('SELECT 1 FROM query_embeddings WHERE hash = :h');
    $chk->execute(['h' => $hash]);
    $eraCache = (bool) $chk->fetchColumn();

    $t0 = microtime(true);
    $vector = embeber_consulta($pdo, $config, $condensada);
    $pasos['embedding'] = [
        'fuente' => $eraCache ? 'cache' : 'api',
        'dimensiones' => $vector ? count($vector) : 0,
        'ms' => round((microtime(true) - $t0) * 1000),
    ];

    if ($vector === null) {
        $pasos['error'] = 'No se pudo generar el embedding de la consulta.';
        return $pasos;
    }

    $t0 = microtime(true);
    $candidatos = buscar_articulos_similares($pdo, $vector, TOP_K_CHAT_GENERAL + TOP_EXTRA_VISUALIZACION);
    $msRecuperacion = round((microtime(true) - $t0) * 1000);

    $dentroTopK = array_slice($candidatos, 0, TOP_K_CHAT_GENERAL);
    $fueraTopK = array_slice($candidatos, TOP_K_CHAT_GENERAL);
    $relevantes = array_values(array_filter($dentroTopK, fn($c) => $c['similitud'] >= UMBRAL_SIMILITUD_MINIMA));
    $descartadosPorUmbral = array_values(array_filter($dentroTopK, fn($c) => $c['similitud'] < UMBRAL_SIMILITUD_MINIMA));

    $pasos['recuperacion'] = [
        'ms' => $msRecuperacion,
        'top_k' => TOP_K_CHAT_GENERAL,
        'umbral' => UMBRAL_SIMILITUD_MINIMA,
        'dentro_top_k' => $dentroTopK,
        'fuera_top_k' => $fueraTopK,
        'relevantes' => $relevantes,
        'descartados_por_umbral' => $descartadosPorUmbral,
    ];

    if (!$relevantes) {
        $pasos['grounding'] = 'sin_resultados';
        $pasos['respuesta_final'] = 'No encontré artículos de Eduteka relacionados con tu pregunta. '
            . '¿Puedes reformularla o preguntar sobre otro tema educativo?';
        return $pasos;
    }

    $bloques = [];
    foreach ($relevantes as $i => $r) {
        $extracto = mb_substr(strip_tags(markdown_render($r['body'])), 0, 1200);
        $bloques[] = [
            'n' => $i + 1,
            'id' => $r['id'],
            'titulo' => $r['title'],
            'categoria' => $r['categoria_nombre'],
            'extracto' => $extracto,
        ];
    }
    $pasos['contexto'] = $bloques;

    $contextoTexto = '';
    foreach ($bloques as $b) {
        $etiquetaCategoria = $b['categoria'] ? ' (' . $b['categoria'] . ')' : '';
        $contextoTexto .= '[' . $b['n'] . '] "' . $b['titulo'] . '"' . $etiquetaCategoria . ":\n" . $b['extracto'] . "\n\n---\n\n";
    }

    $historialSaneado = [];
    foreach (array_slice($historial, -6) as $t) {
        $rol = ($t['role'] ?? '') === 'assistant' ? 'assistant' : 'user';
        $contenido = trim((string) ($t['content'] ?? ''));
        if ($contenido !== '') {
            $historialSaneado[] = ['role' => $rol, 'content' => mb_substr($contenido, 0, 2000)];
        }
    }

    // Mismo prompt exacto que articulos/lib/chat_general.php::generar_respuesta_rag_general().
    $systemPrompt = "Eres el asistente de Eduteka, un portal educativo. Respondes preguntas de "
        . "docentes y estudiantes basándote en los artículos de Eduteka que te doy como contexto.\n\n"
        . "Reglas:\n"
        . "- Básate siempre en los fragmentos numerados de abajo y cita [n] en cada afirmación que venga de ellos.\n"
        . "- Si los fragmentos no bastan para responder del todo, puedes complementar con tu conocimiento "
        . "general, pero avísalo explícitamente (\"Fuera de tus documentos de Eduteka...\").\n"
        . "- Nunca inventes citas, cifras, nombres o datos que no estén en el contexto.\n"
        . "- Si varios artículos tratan el tema, compáralos brevemente.\n"
        . "- Responde en español, clara y concisa (máximo 150 palabras).\n\n"
        . "--- ARTÍCULOS DE EDUTEKA ---\n" . $contextoTexto;

    $mensajes = [['role' => 'system', 'content' => $systemPrompt]];
    foreach ($historialSaneado as $t) {
        $mensajes[] = $t;
    }
    $mensajes[] = ['role' => 'user', 'content' =>
        $mensaje . "\n\n(Al final de tu respuesta agrega una última línea con SOLO una de estas "
        . 'tres opciones, elige exactamente una y escribe nada más que eso en esa línea: '
        . '"GROUNDING: rag" (si respondiste solo con los artículos), "GROUNDING: general" (si solo '
        . 'usaste conocimiento general) o "GROUNDING: integrado" (si combinaste ambos).)'];

    $payload = json_encode([
        'model' => $config['chat_model'],
        'messages' => $mensajes,
        'temperature' => 0.3,
        'max_tokens' => 500,
    ]);

    $t0 = microtime(true);
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $config['openai_api_key']],
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_TIMEOUT => 20,
    ]);
    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $msGeneracion = round((microtime(true) - $t0) * 1000);

    if ($resp === false || $http >= 400) {
        $pasos['error'] = 'El servicio de IA no respondió (HTTP ' . $http . ').';
        return $pasos;
    }

    $datos = json_decode($resp, true);
    $textoCompleto = trim((string) ($datos['choices'][0]['message']['content'] ?? ''));
    $respuestaCruda = $textoCompleto;

    $grounding = 'integrado';
    if (preg_match('/GROUNDING:\s*(rag|general|integrado)/i', $textoCompleto, $m)) {
        $grounding = mb_strtolower($m[1]);
    }
    $textoCompleto = trim((string) preg_replace('/\s*GROUNDING:.*/is', '', $textoCompleto));

    $citados = [];
    foreach ($relevantes as $i => $r) {
        if (preg_match('/\[' . ($i + 1) . '\]/', $textoCompleto)) {
            $citados[] = ['id' => (int) $r['id'], 'title' => $r['title'], 'n' => $i + 1];
        }
    }

    $tokensIn = $datos['usage']['prompt_tokens'] ?? null;
    $tokensOut = $datos['usage']['completion_tokens'] ?? null;

    $pasos['generacion'] = [
        'ms' => $msGeneracion,
        'respuesta_cruda' => $respuestaCruda,
        'tokens_in' => $tokensIn,
        'tokens_out' => $tokensOut,
        'costo' => $tokensIn !== null
            ? ($tokensIn / 1000000 * PRECIO_ENTRADA_POR_M) + ($tokensOut / 1000000 * PRECIO_SALIDA_POR_M)
            : null,
    ];
    $pasos['grounding'] = $grounding;
    $pasos['respuesta_final'] = $textoCompleto;
    $pasos['citados'] = $citados;

    try {
        $pdo->prepare("INSERT INTO ai_usage (origen, kind, tokens_in, tokens_out, ip) VALUES ('rag_inspector', 'rag_inspector', :tin, :tout, :ip)")
            ->execute(['tin' => $tokensIn, 'tout' => $tokensOut, 'ip' => ip_cliente()]);
    } catch (Throwable $e) {
        error_log('rag inspector: error registrando ai_usage: ' . $e->getMessage());
    }

    return $pasos;
}

$resultado = null;
$error = null;
$preguntaEnviada = '';
$historialTexto = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $preguntaEnviada = trim((string) ($_POST['pregunta'] ?? ''));
    $historialTexto = (string) ($_POST['historial'] ?? '');

    $historial = [];
    foreach (preg_split('/\r?\n/', $historialTexto) as $linea) {
        if (preg_match('/^\s*(Usuario|Asistente)\s*:\s*(.+)$/i', $linea, $m)) {
            $historial[] = [
                'role' => mb_strtolower($m[1]) === 'asistente' ? 'assistant' : 'user',
                'content' => trim($m[2]),
            ];
        }
    }

    if ($preguntaEnviada === '' || mb_strlen($preguntaEnviada) > 2000) {
        $error = 'Escribe una pregunta de prueba (máximo 2000 caracteres).';
    } else {
        $resultado = ejecutar_pipeline_rag($pdo, $configIA, $preguntaEnviada, $historial);
    }
}

function badge_grounding(string $g): string
{
    return match ($g) {
        'rag' => 'bg-green-50 text-green-700 border border-green-200',
        'general' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'integrado' => 'bg-marca-grisClaro/40 text-marca-azul border border-marca-azul/30',
        'sin_resultados' => 'bg-gray-100 text-gray-500 border border-gray-200',
        default => 'bg-red-50 text-red-700 border border-red-200',
    };
}

function resaltar_citas(string $texto): string
{
    $html = e($texto);
    return preg_replace('/\[(\d+)\]/', '<span class="inline-flex items-center justify-center rounded bg-marca-azul/10 text-marca-azul text-xs font-bold px-1.5 py-0.5 mx-0.5">[$1]</span>', $html);
}

$titulo = 'Inspector del RAG';
require __DIR__ . '/../templates/header.php';
?>
<h1 class="text-2xl font-bold mb-1">Inspector del RAG</h1>
<p class="text-sm text-gray-500 mb-6">
    Ejecuta el mismo pipeline que usa el chatbot general (condensación → embedding → recuperación KNN
    → poda por umbral → contexto → generación) para una pregunta de prueba, y observa cada paso.
    Estas pruebas <strong>no se guardan</strong> en el registro de preguntas de usuarios reales.
</p>

<form method="post" class="<?= e(tw_card()) ?> p-4 mb-6 space-y-3">
    <?= csrf_field() ?>
    <div>
        <label class="block text-sm font-semibold mb-1">Pregunta de prueba</label>
        <input type="text" name="pregunta" value="<?= e($preguntaEnviada) ?>" required
               class="<?= e(tw_input()) ?>" placeholder="Ej: ¿Qué dice Eduteka sobre el pensamiento computacional?">
    </div>
    <div>
        <label class="block text-sm font-semibold mb-1">Historial previo (opcional, para probar la condensación)</label>
        <textarea name="historial" rows="3" class="<?= e(tw_input()) ?> font-mono text-xs"
                  placeholder="Usuario: pregunta anterior&#10;Asistente: respuesta anterior"><?= e($historialTexto) ?></textarea>
        <p class="text-xs text-gray-400 mt-1">Una línea por turno, alternando "Usuario:" y "Asistente:".</p>
    </div>
    <button type="submit" class="<?= e(tw_btn('primario')) ?>">
        <i class="fa-solid fa-play mr-1"></i> Ejecutar pipeline
    </button>
</form>

<?php if ($error): ?>
<div class="border rounded px-4 py-3 mb-6 <?= e(clases_alerta('danger')) ?>"><?= e($error) ?></div>
<?php endif; ?>

<?php if ($resultado && !isset($resultado['error'])): ?>
<div class="space-y-4">

    <!-- Paso 1: condensación -->
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="flex items-center justify-between mb-2">
            <h2 class="font-bold text-sm text-marca-azul">1. Condensación de la pregunta</h2>
            <span class="text-xs text-gray-400"><?= (int) $resultado['condensacion']['ms'] ?> ms</span>
        </div>
        <?php if ($resultado['condensacion']['aplico']): ?>
            <p class="text-sm text-gray-500 mb-1">Original: <span class="text-gray-800"><?= e($resultado['condensacion']['original']) ?></span></p>
            <p class="text-sm text-gray-500">Reescrita de forma autocontenida: <span class="text-gray-900 font-semibold"><?= e($resultado['condensacion']['condensada']) ?></span></p>
        <?php else: ?>
            <p class="text-sm text-gray-500">No se condensó (mensaje ya autocontenido o sin historial previo). Se usa tal cual: <span class="text-gray-900 font-semibold"><?= e($resultado['condensacion']['original']) ?></span></p>
        <?php endif; ?>
    </div>

    <!-- Paso 2: embedding -->
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="flex items-center justify-between mb-2">
            <h2 class="font-bold text-sm text-marca-azul">2. Embedding de la consulta</h2>
            <span class="text-xs text-gray-400"><?= (int) $resultado['embedding']['ms'] ?> ms</span>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold <?= $resultado['embedding']['fuente'] === 'cache' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-amber-50 text-amber-700 border border-amber-200' ?>">
                <?= $resultado['embedding']['fuente'] === 'cache' ? 'Caché (query_embeddings)' : 'Llamada nueva a la API' ?>
            </span>
            <span class="text-gray-500"><?= (int) $resultado['embedding']['dimensiones'] ?> dimensiones (text-embedding-3-small)</span>
        </div>
    </div>

    <!-- Paso 3: recuperación -->
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-bold text-sm text-marca-azul">3. Recuperación (KNN sobre índice HNSW)</h2>
            <span class="text-xs text-gray-400"><?= (int) $resultado['recuperacion']['ms'] ?> ms</span>
        </div>
        <p class="text-xs text-gray-500 mb-3">
            Top-<?= (int) $resultado['recuperacion']['top_k'] ?> por similitud de coseno · umbral mínimo
            <?= number_format((float) $resultado['recuperacion']['umbral'], 2) ?>
        </p>
        <div class="space-y-1.5">
            <?php foreach ($resultado['recuperacion']['dentro_top_k'] as $i => $c):
                $pasaUmbral = $c['similitud'] >= $resultado['recuperacion']['umbral'];
                $ancho = max(0, min(1, (float) $c['similitud'])) * 100;
            ?>
            <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-400 w-5 text-right"><?= $i + 1 ?></span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <a href="/articulos/ver.php?id=<?= (int) $c['id'] ?>" target="_blank" class="truncate hover:text-marca-azul <?= $pasaUmbral ? 'text-gray-900 font-medium' : 'text-gray-400' ?>">
                            <?= e($c['title']) ?>
                        </a>
                        <span class="text-xs <?= $pasaUmbral ? 'text-gray-700 font-semibold' : 'text-gray-400' ?> shrink-0"><?= number_format((float) $c['similitud'], 3) ?></span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded overflow-hidden mt-0.5">
                        <div class="h-full <?= $pasaUmbral ? 'bg-marca-verde' : 'bg-gray-300' ?>" style="width: <?= $ancho ?>%"></div>
                    </div>
                </div>
                <span class="text-[10px] uppercase font-bold shrink-0 w-24 text-right <?= $pasaUmbral ? 'text-marca-verde' : 'text-gray-400' ?>">
                    <?= $pasaUmbral ? 'Incluido' : 'Descartado' ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if ($resultado['recuperacion']['fuera_top_k']): ?>
        <details class="mt-3">
            <summary class="text-xs text-gray-400 cursor-pointer hover:text-gray-600">
                Ver <?= count($resultado['recuperacion']['fuera_top_k']) ?> candidatos fuera del top-K (no llegan ni a evaluarse)
            </summary>
            <div class="space-y-1 mt-2">
                <?php foreach ($resultado['recuperacion']['fuera_top_k'] as $c): ?>
                <div class="flex items-center justify-between gap-2 text-xs text-gray-400">
                    <span class="truncate"><?= e($c['title']) ?></span>
                    <span><?= number_format((float) $c['similitud'], 3) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </details>
        <?php endif; ?>
    </div>

    <?php if ($resultado['grounding'] === 'sin_resultados'): ?>
    <div class="<?= e(tw_card()) ?> p-4 border-gray-200">
        <h2 class="font-bold text-sm text-gray-500 mb-2">Sin candidatos por encima del umbral</h2>
        <p class="text-sm text-gray-600">Ningún artículo superó el umbral de similitud; no se llama al modelo de generación. Respuesta mostrada al usuario:</p>
        <p class="text-sm text-gray-800 mt-2 italic"><?= e($resultado['respuesta_final']) ?></p>
    </div>
    <?php else: ?>

    <!-- Paso 4: contexto -->
    <div class="<?= e(tw_card()) ?> p-4">
        <h2 class="font-bold text-sm text-marca-azul mb-3">4. Contexto enviado al modelo (<?= count($resultado['contexto']) ?> fragmentos)</h2>
        <div class="space-y-2">
            <?php foreach ($resultado['contexto'] as $b): ?>
            <details class="border border-gray-100 rounded">
                <summary class="cursor-pointer px-3 py-2 text-sm font-semibold hover:bg-marca-grisClaro/10">
                    [<?= $b['n'] ?>] <?= e($b['titulo']) ?>
                    <?php if ($b['categoria']): ?><span class="text-xs font-normal text-gray-400">— <?= e($b['categoria']) ?></span><?php endif; ?>
                </summary>
                <p class="px-3 pb-3 text-xs text-gray-600 whitespace-pre-line"><?= e($b['extracto']) ?></p>
            </details>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Paso 5: generación -->
    <div class="<?= e(tw_card()) ?> p-4">
        <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
            <h2 class="font-bold text-sm text-marca-azul">5. Generación y respuesta final</h2>
            <div class="flex items-center gap-2">
                <span class="text-xs px-2 py-0.5 rounded font-semibold <?= badge_grounding($resultado['grounding']) ?>">
                    GROUNDING: <?= e($resultado['grounding']) ?>
                </span>
                <span class="text-xs text-gray-400"><?= (int) $resultado['generacion']['ms'] ?> ms</span>
            </div>
        </div>
        <p class="text-sm text-gray-800 leading-relaxed"><?= resaltar_citas($resultado['respuesta_final']) ?></p>

        <?php if ($resultado['citados']): ?>
        <div class="flex flex-wrap items-center gap-2 mt-3 text-xs">
            <span class="text-gray-400">Fuentes citadas:</span>
            <?php foreach ($resultado['citados'] as $c): ?>
            <a href="/articulos/ver.php?id=<?= (int) $c['id'] ?>" target="_blank" class="bg-marca-grisClaro/30 text-marca-azul px-2 py-1 rounded hover:bg-marca-grisClaro/60">
                [<?= $c['n'] ?>] <?= e($c['title']) ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500">
            <span><?= number_format((int) $resultado['generacion']['tokens_in'], 0, ',', '.') ?> tokens entrada</span>
            <span><?= number_format((int) $resultado['generacion']['tokens_out'], 0, ',', '.') ?> tokens salida</span>
            <?php if ($resultado['generacion']['costo'] !== null): ?>
            <span>US$ <?= number_format($resultado['generacion']['costo'], 5) ?></span>
            <?php endif; ?>
        </div>

        <details class="mt-3">
            <summary class="text-xs text-gray-400 cursor-pointer hover:text-gray-600">Ver respuesta cruda del modelo (antes de quitar la línea GROUNDING)</summary>
            <pre class="text-xs text-gray-500 bg-marca-grisClaro/10 rounded p-3 mt-2 whitespace-pre-wrap"><?= e($resultado['generacion']['respuesta_cruda']) ?></pre>
        </details>
    </div>
    <?php endif; ?>
</div>
<?php elseif ($resultado && isset($resultado['error'])): ?>
<div class="border rounded px-4 py-3 <?= e(clases_alerta('danger')) ?>"><?= e($resultado['error']) ?></div>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>
