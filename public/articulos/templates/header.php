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
<header class="bg-marca-azul no-imprimir">
    <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="/articulos/index.php" class="text-white font-extrabold tracking-tight">eduteka</a>
        <span class="text-marca-grisClaro text-sm hidden sm:block">Llega más lejos</span>
    </div>
</header>
<main class="max-w-4xl mx-auto px-4 py-8">
