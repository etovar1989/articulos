# Arquitectura MVC sobre F3 (Fat-Free Framework) — Eduteka

Documento de referencia de la arquitectura real del proyecto. Reemplaza una versión
anterior de este documento que describía un prototipo (`app/Models` con `DB\SQL\Mapper`,
plantillas nativas `.htm` de F3, rutas `/articulo/@slug`) que nunca se llegó a construir.
Lo que sigue es lo que realmente corre en producción.

## 1. Resumen

El proyecto es una migración completa de PHP plano a un MVC real sobre F3 3.9.3, hecha en
fases incrementales sobre el sitio en producción (`https://edukatic.co/`), sin downtime y
preservando cada URL existente. El principio guía en todo momento: **las vistas viven en
`public/`, todo lo demás (Controllers, Models, librerías de negocio, config con
credenciales) vive fuera del docroot**, en `app/`.

## 2. Arquitectura

```
eduteka_new/
├── app/
│   ├── config.ini              # settings globales de F3 (sin secretos)
│   ├── config.local.ini        # credenciales reales (gitignorado): DB_DSN, DB_USER,
│   │                           #   DB_PASS, OPENAI_API_KEY, SITE_URL, ADMIN_PASSWORD_HASH
│   ├── routes.ini              # una linea por URL, path literal identico al legacy
│   ├── controllers/
│   │   ├── Home.php                     # GET /
│   │   ├── ArticleController.php        # sitio publico: listado, detalle, chat, PDF
│   │   └── Admin/
│   │       ├── AdminController.php       # base: beforeroute() = requiere_login()
│   │       ├── AuthController.php        # login/logout (no exige sesion)
│   │       ├── DashboardController.php
│   │       ├── ArticleController.php     # CRUD de articulos
│   │       ├── CategoryController.php    # CRUD de categorias
│   │       ├── TagController.php         # CRUD + fusion de etiquetas
│   │       ├── MetricsController.php     # reporteria (chat, buscador, costos)
│   │       └── RagController.php         # inspector del pipeline RAG
│   ├── models/                # PDO plano en clases (repository pattern, no Mapper de F3)
│   │   ├── ArticleModel.php    CategoryModel.php    TagModel.php
│   │   ├── SearchModel.php    (embeddings, busqueda semantica KNN sobre pgvector)
│   │   ├── RagModel.php       (chat general RAG + inspector del admin)
│   │   ├── ChatModel.php      (rate-limit + logging del chat por articulo)
│   │   └── MetricsModel.php   (reporteria del admin)
│   └── lib/
│       ├── Db.php             # App\Lib\Db::pdo($f3) — factory PDO unico
│       ├── View.php           # App\Lib\View::render($ruta, $datos) — extract + require
│       ├── helpers.php        # e(), tw_*, markdown_render(), paginacion()... (autoload
│       │                      #   global via composer "files", sin efectos secundarios)
│       └── auth.php           # requiere_login(), csrf_*, flash() — solo admin (abre
│                               #   sesion, se requiere explicitamente, no autoload global)
├── public/                     # docroot — SOLO vistas y assets estaticos
│   ├── index.php               # front controller F3 (unico bootstrap)
│   ├── .htaccess                # una regla: si el archivo no existe, cae a F3
│   ├── views/inicio.php         # home
│   ├── articulos/
│   │   ├── views/{index.php, ver.php}
│   │   ├── templates/{header.php, footer.php}
│   │   ├── img/, doc/            # assets estaticos (portadas, markdown fuente)
│   └── admin/
│       ├── views/{index.php, login.php, articulos/, categorias/, etiquetas/,
│       │          metricas/, rag/}
│       ├── templates/, assets/css/
├── bin/                         # scripts de linea de comandos (cron, migraciones)
│   ├── cli_bootstrap.php        # autoload + config de F3, sin routing
│   └── embed_pending.php        # cron cada 5 min: indexa articulos rag_status=pending
├── db/migrations/
├── vendor/                      # unico vendor (F3 + dompdf + parsedown), fuera de public/
└── composer.json
```

Toda vista lleva al inicio:
```php
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
```
La constante se define en `public/index.php` tras el `chdir()`. Esto evita que una vista
sea ejecutable pidiendo su URL directa (por ejemplo `/views/inicio.php`), aunque siga
viviendo físicamente bajo `public/`.

## 3. Routing

`public/.htaccess` tiene una sola regla:
```apache
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```
Si la ruta pedida coincide con un archivo real, Apache lo sirve directo. Si no, cae a
`public/index.php` (F3), que despacha según `app/routes.ini`. Cada ruta usa el **mismo
path literal** que tenía el archivo legacy que reemplaza (`GET /articulos/ver.php`, `POST
/admin/etiquetas/fusionar.php`, etc.) — ningún enlace, cita del chat, PDF generado o
bookmark del admin se rompió durante la migración.

## 4. Modelo de datos: PDO plano, no `DB\SQL\Mapper`

Los Models son clases con métodos estáticos que reciben un `PDO` (via
`App\Lib\Db::pdo($f3)`) y devuelven arrays. Se decidió explícitamente **no** usar el
Mapper de F3: hay patrones (operador pgvector `<=>`, `SET LOCAL hnsw.ef_search`,
transacciones explícitas multi-tabla, `fetchColumn()`) que no mapean limpiamente a un
ORM, y forzarlos habría sido puro riesgo sin beneficio.

## 5. Autenticación del admin

`App\Controllers\Admin\AdminController` define `beforeroute(Base $f3)`, que F3 invoca
automáticamente antes de cualquier método de ruta de una clase que herede de ella —
así cada controller del admin queda protegido por `requiere_login()` sin repetirla.
`AuthController` (login/logout) no hereda de esa base, igual que el código legacy nunca
exigía sesión para esas dos acciones.

## 6. Estado de las fases

| Fase | Contenido | Estado |
|---|---|---|
| 0 | Higiene de git: credencial de SFTP filtrada sacada del tracking, config muerta de un prototipo anterior eliminada | Hecho |
| A | Fundación compartida (`app/lib/Db`, `Helpers`, `Auth`, `View`), composer con dompdf/parsedown fusionados al vendor raíz | Hecho |
| B | Endpoints públicos aislados: chat por artículo, chat general RAG, descarga de PDF, nube de etiquetas. Cron `bin/embed_pending.php` repuntado | Hecho |
| C | Listado y detalle de artículos (las dos páginas de mayor tráfico) | Hecho |
| D | Home (`inicio.php`) a Controller/Model/View | Hecho |
| E | Panel admin completo: auth, dashboard, CRUD de artículos/categorías/etiquetas, métricas, inspector RAG | Hecho |
| F | Limpieza final: borrados `lib/`, `config/`, `vendor/` duplicados bajo `public/`; este documento reescrito | Hecho |

Cada fase se probó a fondo en local (incluyendo llamadas reales a OpenAI y operaciones
de escritura en la base de datos, limpiando los datos de prueba al terminar) antes de
desplegarse a producción vía SFTP, con verificación inmediata post-deploy y un commit
propio por fase.

## 7. Cómo levantar el proyecto localmente

```powershell
cd D:\workSpace\eduteka_new
composer install
php -S localhost:8080 -t public
```

El servidor embebido de PHP **no lee `.htaccess`**, así que para probar localmente
rutas que no correspondan a un archivo real (cualquier cosa que pase por F3, no solo
`/`) hace falta un script `router.php` que replique esa única regla y además fuerce
`SCRIPT_NAME=/index.php` antes de incluir el front controller — sin eso, F3 calcula mal
su `BASE` interno y todas las rutas devuelven 404 aunque el código esté bien. En
producción (Apache real) esto no hace falta: el `.htaccess` ya lo resuelve.
