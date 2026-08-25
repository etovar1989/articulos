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
<header class="no-imprimir sticky top-0 z-40 bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 h-[76px] flex items-center justify-between gap-6">
        <a href="/" class="flex items-center shrink-0">
            <img src="/img/logo-eduteka.png" alt="Eduteka" class="h-8 w-auto">
        </a>
        <nav class="hidden md:flex items-center gap-2 text-sm font-semibold">
            <a href="/articulos/index.php" class="bg-marca-azul/10 text-marca-azul px-3 py-1.5 rounded-full" aria-current="page">Contenido</a>
            <a href="/#herramientas" class="px-3 py-1.5 rounded-full hover:text-marca-azul hover:bg-gray-50">Herramientas</a>
            <a href="/#comunidad" class="px-3 py-1.5 rounded-full hover:text-marca-azul hover:bg-gray-50">Comunidad</a>
            <a href="/#icesi-eduteka" class="px-3 py-1.5 rounded-full hover:text-marca-azul hover:bg-gray-50">Sobre Eduteka</a>
            <a href="/#aliados" class="px-3 py-1.5 rounded-full hover:text-marca-azul hover:bg-gray-50">Aliados</a>
        </nav>
        <div class="flex items-center gap-3">
            <form action="/articulos/index.php" method="get" class="hidden md:flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-full px-4 py-2 w-60">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                <input type="text" name="q" placeholder="Buscar artículos, temas..." class="bg-transparent outline-none text-sm w-full">
            </form>
            <a href="/articulos/index.php" class="<?= e(tw_btn('primario')) ?>">Pregúntale a Eduteka</a>
        </div>
    </div>
</header>
<main class="max-w-4xl mx-auto px-4 py-8">
