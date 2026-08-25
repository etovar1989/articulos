<?php
declare(strict_types=1);
require __DIR__ . '/articulos/lib/helpers.php';
require __DIR__ . '/articulos/lib/db.php';

$pdo = db();

$totalArticulos = (int) $pdo->query("SELECT count(*) FROM articles WHERE estado = 'publicado'")->fetchColumn();

// Destacados: articulos reales con portada ya generada (el lote de portadas corre en
// segundo plano, asi que no todos los articulos recientes la tienen todavia).
$candidatos = $pdo->query("
    SELECT a.id, a.title, a.summary, left(a.body, 400) AS extracto, c.name AS categoria
    FROM articles a
    LEFT JOIN categories c ON c.id = a.category_id
    WHERE a.estado = 'publicado'
    ORDER BY a.article_date DESC NULLS LAST, a.id DESC
    LIMIT 80
")->fetchAll();

$destacados = [];
foreach ($candidatos as $c) {
    if (is_file(__DIR__ . '/articulos/img/portadas/' . $c['id'] . '.jpg')) {
        $destacados[] = $c;
    }
    if (count($destacados) >= 4) {
        break;
    }
}

function resumen_home(?string $resumen, string $extracto, int $largo = 110): string
{
    $texto = $resumen ?: preg_replace('/[#>*`_~\[\]()!]/', '', $extracto);
    $texto = trim(preg_replace('/\s+/', ' ', $texto));
    return mb_substr($texto, 0, $largo) . (mb_strlen($texto) > $largo ? '…' : '');
}

$titulo = 'Inicio';
$descripcion = 'Eduteka: recursos, artículos y herramientas con IA para docentes de Hispanoamérica. Centro de Innovación Educativa y TIC de la Universidad Icesi.';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> · Eduteka</title>
    <meta name="description" content="<?= e($descripcion) ?>">
    <meta name="theme-color" content="#5454E9">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
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
</head>
<body class="font-sans bg-white text-gray-900">

<!-- NAV -->
<header class="sticky top-0 z-40 bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 h-[76px] flex items-center justify-between gap-6">
        <a href="/" class="flex items-center gap-2 shrink-0">
            <img src="/img/logo-eduteka.png" alt="Eduteka" class="h-8 w-auto">
            <span class="hidden sm:block text-xs text-gray-500 border-l border-gray-200 pl-2">Llega más lejos</span>
        </a>
        <nav class="hidden md:flex items-center gap-7 text-sm font-semibold">
            <a href="#contenido" class="hover:text-marca-azul">Contenido</a>
            <a href="#herramientas" class="hover:text-marca-azul">Herramientas</a>
            <a href="#comunidad" class="hover:text-marca-azul">Comunidad</a>
            <a href="#icesi-eduteka" class="hover:text-marca-azul">Sobre Eduteka</a>
            <a href="#aliados" class="hover:text-marca-azul">Aliados</a>
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

<!-- HERO -->
<section class="relative overflow-hidden" style="background: linear-gradient(135deg, #14143A 0%, #1E1E5C 45%, #5454E9 100%);">
    <div class="absolute inset-0 opacity-50" style="background-image: radial-gradient(circle at 15% 20%, rgba(255,255,255,.12) 0, transparent 40%), radial-gradient(circle at 85% 75%, rgba(255,255,255,.10) 0, transparent 45%);"></div>
    <div class="max-w-6xl mx-auto px-4 relative py-24">
        <div class="flex items-center gap-4 mb-9 flex-wrap">
            <div class="rounded-full px-4 py-2" style="background-color: rgba(15,15,26,.65); backdrop-filter: blur(12px); border:1px solid rgba(255,255,255,.15);">
                <span class="text-white text-sm font-semibold">25 años acompañando la educación con TIC</span>
            </div>
            <div class="flex gap-1.5">
                <div class="w-7 h-2 bg-marca-naranja"></div>
                <div class="w-7 h-2 bg-marca-verde"></div>
                <div class="w-7 h-2 bg-marca-amarillo"></div>
            </div>
        </div>

        <h1 class="text-5xl md:text-7xl leading-[1.02] text-white max-w-3xl font-extrabold tracking-tight">
            <strong>Compartir</strong> recursos + <strong>Conectar</strong> docentes = <span class="font-normal">Transformar aulas</span>
        </h1>

        <p class="max-w-xl text-marca-grisClaro text-lg mt-6">
            Más de <?= number_format($totalArticulos, 0, ',', '.') ?> artículos y recursos gratuitos, proyectos y
            herramientas con IA para docentes, directivos y estudiantes de Hispanoamérica.
        </p>

        <form action="/articulos/index.php" method="get" class="mt-10 max-w-xl">
            <div class="rounded-2xl p-2 flex items-center gap-2" style="background-color: rgba(15,15,26,.65); backdrop-filter: blur(12px); border:1px solid rgba(255,255,255,.15);">
                <i class="fa-solid fa-wand-magic-sparkles text-white ml-3"></i>
                <input type="text" name="q" placeholder="¿Qué dice Eduteka sobre pensamiento computacional?" class="bg-transparent outline-none text-white placeholder-white/60 text-sm flex-1">
                <button type="submit" class="<?= e(tw_btn('primario')) ?>">Buscar con IA</button>
            </div>
            <p class="text-white/50 text-xs mt-2 pl-2">Búsqueda semántica: entiende el significado de lo que preguntas, no solo palabras exactas.</p>
        </form>

        <div class="flex gap-3 mt-9 flex-wrap">
            <a href="/articulos/index.php" class="inline-flex items-center rounded-lg px-7 py-4 font-semibold bg-marca-azul text-white hover:bg-marca-azulHover">Explorar Artículos</a>
            <a href="#herramientas" class="inline-flex items-center rounded-lg px-7 py-4 font-semibold bg-marca-amarillo" style="color:#2B2B7A;">Conocer EdutekaLab</a>
            <a href="#aliados" class="inline-flex items-center rounded-lg px-7 py-4 font-semibold border border-white/60 text-white">Vincularme como Aliado</a>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="py-14 bg-gray-50 border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div>
            <span class="block text-4xl font-extrabold text-marca-azul">25+</span>
            <span class="block text-sm font-bold mt-1">Años</span>
            <span class="block text-xs text-gray-500">De trayectoria en innovación educativa</span>
        </div>
        <div>
            <span class="block text-4xl font-extrabold text-marca-azul">4K+</span>
            <span class="block text-sm font-bold mt-1">Recursos</span>
            <span class="block text-xs text-gray-500">Artículos, módulos y proyectos gratuitos</span>
        </div>
        <div>
            <span class="block text-4xl font-extrabold text-marca-azul"><?= number_format($totalArticulos, 0, ',', '.') ?></span>
            <span class="block text-sm font-bold mt-1">Artículos</span>
            <span class="block text-xs text-gray-500">Indexados en el buscador con IA</span>
        </div>
        <div>
            <span class="block text-4xl font-extrabold text-marca-azul">100%</span>
            <span class="block text-sm font-bold mt-1">Gratuito</span>
            <span class="block text-xs text-gray-500">Sin registro ni costo para docentes</span>
        </div>
    </div>
</section>

<!-- CONTENIDO -->
<section id="contenido" class="py-24">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-10 flex-wrap gap-4">
            <div>
                <span class="text-marca-azul text-xs font-bold tracking-widest uppercase">Contenido</span>
                <h2 class="text-3xl font-extrabold mt-2">Novedades del portal</h2>
            </div>
            <a href="/articulos/index.php" class="font-bold text-sm flex items-center gap-1 hover:text-marca-azul">
                Ver todos los artículos <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <?php if ($destacados): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($destacados as $d): ?>
            <a href="/articulos/ver.php?id=<?= (int) $d['id'] ?>" class="<?= e(tw_card()) ?> overflow-hidden hover:border-marca-azul transition flex flex-col">
                <div class="h-36 bg-marca-grisClaro/20 overflow-hidden">
                    <img src="/articulos/img/portadas/<?= (int) $d['id'] ?>.jpg" loading="lazy" alt="" class="w-full h-full object-cover">
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <?php if ($d['categoria']): ?>
                    <span class="inline-block bg-marca-grisClaro/40 text-marca-morado text-xs px-2 py-0.5 rounded mb-2 self-start"><?= e($d['categoria']) ?></span>
                    <?php endif; ?>
                    <div class="font-bold mb-1 text-sm leading-snug"><?= e($d['title']) ?></div>
                    <p class="text-xs text-gray-500 flex-1"><?= e(resumen_home($d['summary'], $d['extracto'])) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- HERRAMIENTAS -->
<section id="herramientas" class="py-24 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <span class="text-marca-azul text-xs font-bold tracking-widest uppercase">Herramientas</span>
        <h2 class="text-3xl font-extrabold mt-2 max-w-xl">EdutekaLab: <strong>herramientas con IA</strong> para tu práctica docente</h2>
        <p class="max-w-xl text-gray-500 mt-3 text-sm">Seis asistentes gratuitos que automatizan tareas del día a día, para que dediques más tiempo a enseñar.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="<?= e(tw_card()) ?> p-7">
                <div class="w-12 h-12 flex items-center justify-center bg-marca-morado text-white mb-4"><i class="fa-solid fa-plus fa-lg"></i></div>
                <span class="block text-2xl font-extrabold text-marca-grisClaro mb-3">01</span>
                <h3 class="font-bold mb-2">IDEA</h3>
                <p class="text-sm text-gray-500">Genera planes de clase completos a partir de un tema y un grado.</p>
            </div>
            <div class="<?= e(tw_card()) ?> p-7">
                <div class="w-12 h-12 flex items-center justify-center bg-marca-verde text-white mb-4"><i class="fa-regular fa-clipboard fa-lg"></i></div>
                <span class="block text-2xl font-extrabold text-marca-grisClaro mb-3">02</span>
                <h3 class="font-bold mb-2">RubriK</h3>
                <p class="text-sm text-gray-500">Crea rúbricas de evaluación claras y alineadas a tus objetivos.</p>
            </div>
            <div class="<?= e(tw_card()) ?> p-7">
                <div class="w-12 h-12 flex items-center justify-center bg-marca-naranja text-white mb-4"><i class="fa-solid fa-diagram-project fa-lg"></i></div>
                <span class="block text-2xl font-extrabold text-marca-grisClaro mb-3">03</span>
                <h3 class="font-bold mb-2">Planeo</h3>
                <p class="text-sm text-gray-500">Diseña cursos completos con secuencia didáctica sugerida.</p>
            </div>
            <div class="<?= e(tw_card()) ?> p-7">
                <div class="w-12 h-12 flex items-center justify-center bg-marca-amarillo mb-4" style="color:#2B2B7A;"><i class="fa-solid fa-gamepad fa-lg"></i></div>
                <span class="block text-2xl font-extrabold text-marca-grisClaro mb-3">04</span>
                <h3 class="font-bold mb-2">Gamificación IA</h3>
                <p class="text-sm text-gray-500">Convierte cualquier actividad en una experiencia con retos y niveles.</p>
            </div>
            <div class="<?= e(tw_card()) ?> p-7">
                <div class="w-12 h-12 flex items-center justify-center bg-marca-azul text-white mb-4"><i class="fa-solid fa-chart-simple fa-lg"></i></div>
                <span class="block text-2xl font-extrabold text-marca-grisClaro mb-3">05</span>
                <h3 class="font-bold mb-2">MÁTICA</h3>
                <p class="text-sm text-gray-500">Diagnostica el nivel de competencias TIC de tu institución.</p>
            </div>
            <div class="rounded-lg bg-marca-azul p-7">
                <div class="w-12 h-12 flex items-center justify-center bg-white/20 text-white mb-4"><i class="fa-solid fa-comment-dots fa-lg"></i></div>
                <span class="block text-2xl font-extrabold text-white/30 mb-3">06</span>
                <h3 class="font-bold mb-2 text-white">SEE</h3>
                <p class="text-sm text-white/75">Sistema Experto: resuelve dudas pedagógicas al instante, con IA.</p>
            </div>
        </div>
    </div>
</section>

<!-- COMUNIDAD -->
<section id="comunidad" class="py-24">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
        <div>
            <span class="text-marca-azul text-xs font-bold tracking-widest uppercase">Comunidad</span>
            <h2 class="text-3xl font-extrabold mt-2"><strong>Pregúntale</strong> a Eduteka</h2>
            <p class="text-gray-500 text-sm mt-4 max-w-md">
                Un asistente con IA que busca en los <?= number_format($totalArticulos, 0, ',', '.') ?> artículos del
                portal y responde citando siempre sus fuentes — para que nunca te quedes con una duda sin resolver.
            </p>
            <div class="mt-7 flex gap-4 flex-wrap items-center">
                <a href="/articulos/index.php" class="<?= e(tw_btn('primario')) ?>">Iniciar conversación</a>
                <a href="#" class="font-bold text-sm hover:text-marca-azul">Ver eventos Eduteka →</a>
            </div>
        </div>

        <div class="<?= e(tw_card()) ?> p-6 bg-gray-50">
            <div class="flex items-center gap-2.5 bg-marca-azul rounded-t-lg px-4 py-3 -m-6 mb-5">
                <div class="w-8 h-8 bg-white/20 flex items-center justify-center rounded-full"><i class="fa-solid fa-graduation-cap text-white text-sm"></i></div>
                <span class="text-white font-bold text-sm">Pregúntale a Eduteka</span>
            </div>
            <div class="flex flex-col gap-3">
                <div class="self-end max-w-[80%] bg-marca-azul text-white px-3.5 py-2.5 rounded-2xl rounded-br-sm text-sm">
                    ¿Qué dice Eduteka sobre el pensamiento computacional?
                </div>
                <div class="self-start max-w-[85%] bg-white border border-gray-200 px-3.5 py-2.5 rounded-2xl rounded-bl-sm text-sm">
                    Eduteka lo define como una habilidad clave de la era digital, más allá de programar
                    <span class="bg-marca-azul/10 text-marca-azul font-bold px-1.5 py-0.5 rounded text-xs">[1]</span>...
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ICESI-EDUTEKA -->
<section id="icesi-eduteka" class="py-24 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
        <div class="h-96 rounded-lg relative overflow-hidden">
            <img src="/img/torre-icesi.jpg" alt="Torre de la Universidad Icesi" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0" style="background: linear-gradient(0deg, rgba(10,10,25,.85) 0%, rgba(10,10,25,.35) 45%, rgba(10,10,25,0) 70%);"></div>
            <div class="absolute bottom-6 left-6 right-6">
                <span class="inline-block bg-marca-amarillo text-xs font-bold px-3 py-1 rounded mb-3" style="color:#2B2B7A;">Sede oficial</span>
                <p class="text-2xl font-extrabold text-white leading-tight">Universidad Icesi</p>
                <p class="text-sm text-white/80 mt-1">Calle 18 No. 122–135, Pance · Cali, Colombia</p>
            </div>
        </div>
        <div>
            <span class="text-marca-azul text-xs font-bold tracking-widest uppercase">Icesi + Eduteka</span>
            <h2 class="text-2xl md:text-3xl font-extrabold mt-2">Un centro de la Universidad Icesi, al servicio de toda Hispanoamérica</h2>
            <p class="text-gray-500 text-sm mt-4 max-w-md">
                Eduteka es el Centro de Innovación Educativa y TIC de la Universidad Icesi. Desde 2001
                investiga y difunde cómo integrar la tecnología al aula de forma significativa — sin
                costo, sin publicidad, sin registro.
            </p>
            <div class="grid grid-cols-2 gap-5 mt-8">
                <div class="flex gap-3">
                    <div class="w-10 h-10 bg-marca-grisClaro flex items-center justify-center shrink-0"><i class="fa-solid fa-landmark text-marca-azul text-sm"></i></div>
                    <div><p class="font-bold text-sm">Fundación</p><p class="text-xs text-gray-500">Gabriel Piedrahita Uribe</p></div>
                </div>
                <div class="flex gap-3">
                    <div class="w-10 h-10 bg-marca-grisClaro flex items-center justify-center shrink-0"><i class="fa-solid fa-flask text-marca-azul text-sm"></i></div>
                    <div><p class="font-bold text-sm">Investigación</p><p class="text-xs text-gray-500">Aplicada al aula real</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ALIADOS -->
<section id="aliados" class="py-24">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-marca-azul text-xs font-bold tracking-widest uppercase">Aliados</span>
            <h2 class="text-3xl font-extrabold mt-2">Instituciones que hacen posible Eduteka</h2>
        </div>
        <?php
        $aliados = [
            ['file' => 'ascofade.png', 'name' => 'Ascofade'],
            ['file' => 'comision-vallecaucana.jpg', 'name' => 'Comisión Vallecaucana'],
            ['file' => 'fanalca.png', 'name' => 'Fanalca'],
            ['file' => 'fundacion-mayaguez.png', 'name' => 'Fundación Mayagüez'],
            ['file' => 'florecer.png', 'name' => 'Florecer'],
            ['file' => 'tq.png', 'name' => 'TQ'],
            ['file' => 'scarpetta-gnecco.png', 'name' => 'Scarpetta Gnecco'],
            ['file' => 'senacyt.jpg', 'name' => 'Senacyt'],
        ];
        ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 items-center">
            <?php foreach ($aliados as $a): ?>
            <div class="h-20 bg-gray-50 rounded flex items-center justify-center p-4">
                <img src="/img/aliados/<?= e($a['file']) ?>" alt="<?= e($a['name']) ?>" class="max-h-full max-w-full object-contain" loading="lazy">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- REDES -->
<section class="bg-marca-azul py-12">
    <div class="max-w-6xl mx-auto px-4 flex items-center justify-between flex-wrap gap-8">
        <div>
            <span class="text-white/75 text-xs font-bold tracking-widest uppercase">Redes</span>
            <h2 class="text-2xl font-extrabold mt-2 text-white max-w-md">Síguenos, no te pierdas los nuevos recursos</h2>
        </div>
        <div class="flex gap-3">
            <a href="#" class="w-11 h-11 rounded-full bg-white/15 flex items-center justify-center text-white"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="w-11 h-11 rounded-full bg-white/15 flex items-center justify-center text-white"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="w-11 h-11 rounded-full bg-white/15 flex items-center justify-center text-white"><i class="fa-brands fa-linkedin-in"></i></a>
            <a href="#" class="w-11 h-11 rounded-full bg-white/15 flex items-center justify-center text-white"><i class="fa-brands fa-x-twitter"></i></a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer style="background:#0F0F1A;" class="text-white/85 pt-16">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-10">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <span class="text-xl font-extrabold text-white">EDUTEKA</span>
                <span class="w-px h-6 bg-white/20"></span>
                <span class="text-sm text-white/55">Universidad Icesi</span>
            </div>
            <p class="text-sm text-white/55 max-w-xs mb-6">
                Plataforma educativa de la Universidad Icesi que provee materiales de calidad para
                docentes, directivos escolares y formadores de maestros de manera
                <strong class="text-white">completamente gratuita.</strong>
            </p>
            <div class="flex gap-3.5">
                <div class="bg-marca-morado px-4 py-3.5"><span class="block text-xl font-extrabold text-white">20+</span><span class="text-xs text-white/85">Años</span></div>
                <div style="background:#1B1B2E;" class="px-4 py-3.5"><span class="block text-xl font-extrabold text-white">4K+</span><span class="text-xs text-white/60">Recursos</span></div>
            </div>
        </div>
        <div>
            <h4 class="text-xs font-bold text-white uppercase tracking-wide mb-4 pb-2 border-b border-white/15">Secciones</h4>
            <div class="flex flex-col gap-2.5 text-sm">
                <a href="/articulos/index.php" class="text-white/70 hover:text-white">Artículos</a>
                <a href="#" class="text-white/70 hover:text-white">Módulos</a>
                <a href="#" class="text-white/70 hover:text-white">Proyectos</a>
                <a href="#" class="text-white/70 hover:text-white">Herramientas TIC</a>
                <a href="#" class="text-white/70 hover:text-white">Matemática Interactiva</a>
                <a href="#" class="text-white/70 hover:text-white">Recursos Digitales</a>
            </div>
        </div>
        <div>
            <h4 class="text-xs font-bold text-white uppercase tracking-wide mb-4 pb-2 border-b border-white/15">EdutekaLab</h4>
            <div class="flex flex-col gap-2.5 text-sm">
                <a href="#" class="text-white/70 hover:text-white">IDEA</a>
                <a href="#" class="text-white/70 hover:text-white">RubriK</a>
                <a href="#" class="text-white/70 hover:text-white">Planeo</a>
                <a href="#" class="text-white/70 hover:text-white">Gamificación IA</a>
                <a href="#" class="text-white/70 hover:text-white">MÁTICA</a>
                <a href="#" class="text-white/70 hover:text-white">Eventos Eduteka</a>
            </div>
        </div>
        <div>
            <h4 class="text-xs font-bold text-white uppercase tracking-wide mb-4 pb-2 border-b border-white/15">Información</h4>
            <div class="flex flex-col gap-2.5 text-sm mb-6">
                <a href="#" class="text-white/70 hover:text-white">Quiénes Somos</a>
                <a href="#" class="text-white/70 hover:text-white">Políticas de Uso</a>
                <a href="#" class="text-white/70 hover:text-white">Protección de Datos</a>
                <a href="#" class="text-white/70 hover:text-white">Universidad ICESI</a>
            </div>
            <div class="flex gap-2.5">
                <a href="#" class="w-8 h-8 bg-white/10 flex items-center justify-center text-xs"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="w-8 h-8 bg-white/10 flex items-center justify-center text-xs"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="w-8 h-8 bg-white/10 flex items-center justify-center text-xs"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#" class="w-8 h-8 bg-white/10 flex items-center justify-center text-xs"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
    <div class="mt-14 border-t border-white/10 py-5">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between flex-wrap gap-3">
            <span class="text-xs text-white/45">© Copyright Eduteka 2001–2026 · Universidad Icesi</span>
            <div class="flex gap-5 text-xs">
                <a href="#" class="text-white/45 hover:text-white">Términos de Servicio</a>
                <a href="#" class="text-white/45 hover:text-white">Privacidad</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
