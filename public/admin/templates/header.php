<?php
declare(strict_types=1);
/** @var string $titulo */
$titulo = $titulo ?? 'Panel Eduteka';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> · Panel Eduteka</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        marca: {
                            azul: '#5454E9',
                            azulHover: '#4444c9',
                            gris: '#88898C',
                            grisClaro: '#CECFF4',
                            amarillo: '#E4EB60',
                            verde: '#4CB979',
                            morado: '#865CF0',
                            naranja: '#E9683B',
                        },
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link href="/admin/assets/css/estilo.css" rel="stylesheet">
</head>
<body class="font-sans bg-white text-gray-900 text-left">
<?php if (usuario_autenticado()): ?>
<nav class="bg-marca-azul mb-8">
    <div class="max-w-6xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
        <a class="text-white font-extrabold tracking-tight" href="/admin/index.php">eduteka · admin</a>
        <div class="flex flex-wrap items-center gap-1">
            <a class="text-white font-semibold px-3 py-1.5 rounded hover:bg-marca-azulHover" href="/admin/articulos/index.php">Artículos</a>
            <a class="text-white font-semibold px-3 py-1.5 rounded hover:bg-marca-azulHover" href="/admin/categorias/index.php">Categorías</a>
            <a class="text-white font-semibold px-3 py-1.5 rounded hover:bg-marca-azulHover" href="/admin/etiquetas/index.php">Etiquetas</a>
            <a class="text-white font-semibold px-3 py-1.5 rounded hover:bg-marca-azulHover" href="/admin/metricas/index.php">Métricas</a>
            <a class="text-white font-semibold px-3 py-1.5 rounded hover:bg-marca-azulHover" href="/admin/rag/index.php">RAG</a>
            <a class="text-marca-grisClaro font-semibold px-3 py-1.5 rounded hover:bg-marca-azulHover hover:text-white" href="/admin/logout.php">Salir</a>
        </div>
    </div>
</nav>
<?php endif; ?>
<main class="max-w-6xl mx-auto px-4 pb-16">
    <?php foreach (flash_tomar() as $f): ?>
        <div class="border rounded px-4 py-3 mb-4 flex items-center justify-between gap-3 <?= e(clases_alerta($f['tipo'])) ?>">
            <span><?= e($f['mensaje']) ?></span>
            <button type="button" class="font-bold leading-none" onclick="this.closest('div').remove()">&times;</button>
        </div>
    <?php endforeach; ?>
