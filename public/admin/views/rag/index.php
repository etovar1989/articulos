<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista del inspector RAG. Recibe los datos ya resueltos por
 * App\Controllers\Admin\RagController::index() — el pipeline completo
 * (condensacion, embedding, recuperacion, generacion) vive en
 * App\Models\RagModel::ejecutarPipelineInspeccion(). e(), resaltar_citas() y
 * markdown_render() ya estan disponibles globalmente via app/lib/helpers.php,
 * sin necesidad de redeclararlas como antes.
 *
 * @var string $titulo
 * @var array|null $resultado
 * @var string|null $error
 * @var string $preguntaEnviada
 * @var string $historialTexto
 */

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

require __DIR__ . '/../../templates/header.php';
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

<?php require __DIR__ . '/../../templates/footer.php'; ?>
