<?php
declare(strict_types=1);
/** @var string $titulo */
/** @var string|null $descripcion */
$titulo = $titulo ?? 'Eduteka';
$descripcion = $descripcion ?? 'Recursos educativos de Eduteka';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> · Eduteka</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <meta name="description" content="<?= e($descripcion) ?>">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?= e($titulo) ?>">
    <meta property="og:description" content="<?= e($descripcion) ?>">
    <?php if (!empty($urlCanonica)): ?>
    <meta property="og:url" content="<?= e($urlCanonica) ?>">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <style media="print">
        .no-imprimir { display: none !important; }
        body { color: #000; }
    </style>
</head>
<body class="font-sans bg-white text-gray-900 text-left">
<header class="no-imprimir fixed top-0 w-full z-40" style="background-color: rgba(20,20,30,.65); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,.15);">
    <div class="max-w-6xl mx-auto px-4 h-20 flex items-center justify-between gap-6">
        <a href="/" class="flex items-center shrink-0">
            <img src="/img/logo-eduteka.png" alt="Eduteka" class="h-8 w-auto brightness-0 invert">
        </a>
        <nav class="hidden md:flex items-center gap-2 text-sm font-semibold">
            <a href="/articulos/index.php" class="bg-black/30 text-white px-4 py-2 rounded-full" aria-current="page">Contenido</a>
            <a href="/#herramientas" class="text-white/90 hover:text-white hover:bg-black/20 px-4 py-2 rounded-full">Herramientas</a>
            <a href="/#comunidad" class="text-white/90 hover:text-white hover:bg-black/20 px-4 py-2 rounded-full">Comunidad</a>
        </nav>
        <div class="flex items-center gap-3">
            <form action="/articulos/index.php" method="get" class="hidden md:flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 w-60">
                <i class="fa-solid fa-magnifying-glass text-white/60 text-sm"></i>
                <input type="text" name="q" placeholder="Buscar artículos, temas..." class="bg-transparent outline-none text-sm w-full text-white placeholder-white/50">
            </form>
        </div>
    </div>
</header>
<main class="max-w-4xl mx-auto px-4 pt-28 pb-8">
