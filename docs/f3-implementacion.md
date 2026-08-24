# Implementación de F3 (Fat-Free Framework) en Eduteka

Documento de referencia de la migración de PHP plano a F3. Describe el estado del
proyecto, la arquitectura implementada y cómo levantarlo localmente.

> **Cambio de rumbo (2026-08-24):** el proyecto arrancó pensado para correr en Docker
> (Apache+PHP y Postgres en contenedores). Se abandonó ese camino porque Docker Desktop
> no está instalado en la máquina de desarrollo y no se usaba en el día a día — todo el
> stack ahora corre **nativo en Windows**: PHP vía XAMPP, PostgreSQL instalado aparte,
> sin contenedores. `Dockerfile`, `docker-compose.yml` y `docker/` se eliminaron del
> repo, igual que los ~30 scripts de troubleshooting de Docker/WSL/pgAdmin que había en
> la raíz.

## 1. Estado actual del proyecto

- **Stack**: PHP 8.2.12 (XAMPP, `C:\xampp\php\php.exe`, no está en el `PATH` del
  sistema), PostgreSQL 15 (a instalar aparte, XAMPP no lo trae — solo trae MySQL).
- **Sin Apache por ahora**: se sirve con el servidor embebido de PHP
  (`php -S`) apuntando a `public/` como docroot. Se puede migrar a Apache/XAMPP más
  adelante si hace falta.
- **Datos**: 1577 artículos en Markdown en [articulos/doc/](../articulos/doc/), con
  frontmatter (`id`, `title`, `date`, `tags`, `category`, `author`).
- **Pipeline de carga** ([tools/](../tools/)): `load_articles.py` → `build_embeddings.py`
  → `build_categories.py` (clustering + GPT) → `build_tags.py` (GPT). Los scripts ya
  tenían `host="127.0.0.1"` hardcodeado en el dict `DB` (nunca leían `DB_HOST` del
  entorno), así que corren tal cual contra el Postgres nativo, sin cambios.
- **Schema** ([config/schema_articles.sql](../config/schema_articles.sql)):
  `articles(id, slug, title, file_path, body, original_category, original_tags, article_date, category_id)`,
  `categories(id, name, description)`, `tags(id, name)`, `article_tags(article_id, tag_id)`.
  Ni `categories` ni `tags` tienen columna `slug` todavía.
- **Conexión a BD**: [config/database.php](../config/database.php) expone
  `getDbConnection(): PDO` y las constantes `DB_HOST`/`DB_PORT`/`DB_NAME`/`DB_USER`/`DB_PASS`
  (con fallback hardcodeado si no hay variables de entorno — ver sección 5). La usa
  `apply_schema.php` y ahora también `public/index.php`.

## 2. Por qué F3 encaja aquí

- Es un único paquete Composer sin dependencias pesadas — coherente con un proyecto
  que era PHP plano.
- Trae router, motor de plantillas y un Mapper para SQL (incluye PostgreSQL) en una
  sola pieza, evitando ensamblar router + ORM + templates por separado.
- No depende de Apache: el servidor embebido de PHP (`php -S`) es suficiente para
  desarrollo local, así que no hace falta configurar un vhost.

## 3. Arquitectura implementada

```
eduteka_new/
├── app/
│   ├── Controllers/
│   │   ├── ArticleController.php
│   │   ├── CategoryController.php
│   │   └── TagController.php
│   ├── Models/                  # F3 DB\SQL\Mapper
│   │   ├── Article.php
│   │   ├── Category.php
│   │   └── Tag.php
│   └── views/
│       ├── layout.htm
│       ├── articles/{list,show}.htm
│       ├── categories/show.htm
│       └── tags/show.htm
├── public/                      # docroot del servidor embebido de PHP
│   ├── index.php                # front controller único
│   └── .htaccess                # solo relevante si algún día se sirve con Apache
├── config/
│   ├── f3.ini                   # config de F3 (DEBUG, ruta de vistas)
│   ├── database.php             # constantes DB_* + getDbConnection()
│   └── schema_articles.sql
├── articulos/doc/                # sin cambios — sigue siendo la fuente de datos
├── tools/                        # sin cambios — pipeline Python
├── vendor/                       # generado por Composer (no versionado)
└── composer.json
```

## 4. Instalación (entorno nativo Windows)

### 4.1 PHP

Ya está disponible vía XAMPP en `C:\xampp\php\php.exe`. Se habilitaron las extensiones
`pdo_pgsql` y `pgsql` en `C:\xampp\php\php.ini` (venían con el DLL presente pero
comentadas). Para no escribir la ruta completa cada vez, conviene agregar
`C:\xampp\php` al `PATH` del usuario.

### 4.2 Composer

No estaba instalado. Instalar desde <https://getcomposer.org/Composer-Setup.exe>
(detecta el PHP de XAMPP automáticamente), o vía:

```powershell
winget install ComposerPHP.Composer
```

### 4.3 PostgreSQL

No estaba instalado (XAMPP no lo incluye). Instalar la versión 15 (misma que se usaba
en el contenedor) vía instalador oficial <https://www.postgresql.org/download/windows/>
o:

```powershell
winget install PostgreSQL.PostgreSQL.15
```

Durante la instalación hay que fijar la contraseña del superusuario `postgres`. Luego,
crear la base y el usuario de la app (con `psql` o pgAdmin de escritorio) para que
coincidan con `.env`:

```sql
CREATE USER edtk_root_eduteka WITH PASSWORD '5up3rS4j123@!';
CREATE DATABASE edtk_eduteka OWNER edtk_root_eduteka;
```

### 4.4 Instalar F3 y aplicar el schema

```powershell
cd D:\workSpace\eduteka_new
composer install
php apply_schema.php   # o abrir http://localhost:8080/apply_schema.php una vez levantado el server
```

## 5. Variables de entorno

`.env` documenta los valores, pero **no hay un loader de `.env` en PHP** (no se agregó
`phpdotenv` para no meter una dependencia extra sin necesidad) — `getenv()` no lee el
archivo `.env`, solo variables de entorno reales del proceso. Por eso
[config/database.php](../config/database.php) tiene fallbacks hardcodeados que ya
coinciden con los valores de `.env` (host `127.0.0.1`, puerto `5432`, etc.), así que
funciona sin configuración adicional. Si `.env` cambia, hay que reflejar el cambio
también en `config/database.php` (o, más adelante, agregar `phpdotenv` para que sea la
única fuente de verdad).

## 6. Bootstrap (`public/index.php`)

```php
<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../config/database.php';

$f3 = \Base::instance();
$f3->config(__DIR__.'/../config/f3.ini');

$f3->set('DB', new \DB\SQL(
    sprintf('pgsql:host=%s;port=%s;dbname=%s', DB_HOST, DB_PORT, DB_NAME),
    DB_USER,
    DB_PASS
));

$f3->route('GET /', 'App\Controllers\ArticleController->index');
$f3->route('GET /articulo/@slug', 'App\Controllers\ArticleController->show');
$f3->route('GET /categoria/@slug', 'App\Controllers\CategoryController->show');
$f3->route('GET /etiqueta/@slug', 'App\Controllers\TagController->show');

$f3->run();
```

`config/f3.ini`:

```ini
[globals]
DEBUG=3
UI=../app/views/
```

## 7. Modelos (Mappers)

```php
// app/Models/Article.php
namespace App\Models;

class Article extends \DB\SQL\Mapper {
    function __construct(\DB\SQL $db) {
        parent::__construct($db, 'articles');
    }
}
```

Igual para `Category` (tabla `categories`) y `Tag` (tabla `tags`). El Mapper cubre
`find()`, `load()`, paginación (`paginate()`) y filtros — no hace falta escribir SQL a
mano para las consultas simples que ya soporta el schema actual.

## 8. Controladores y vistas

`ArticleController` resuelve `/` (listado paginado) y `/articulo/@slug` (detalle).
`CategoryController` y `TagController` cargan por `id`, luego listan artículos
relacionados (`category_id` para categorías, un `JOIN` sobre `article_tags` para
etiquetas — ver [app/Controllers/TagController.php](../app/Controllers/TagController.php)).
Las vistas están en `app/views/` usando el motor de plantillas nativo de F3
(`<repeat>`, `<check>`, `{{ @var }}`).

Pendiente conocido: `{{ @article['body'] }}` imprime el Markdown crudo escapado, no
HTML renderizado — falta agregar un parser (`league/commonmark` vía Composer) para
convertirlo antes de mostrarlo.

## 9. Ajuste de schema pendiente: slugs para categorías y etiquetas

Hecho: `categories` y `tags` ahora tienen columna `slug` (agregada tanto en
`config/schema_articles.sql` como en la BD viva con `ALTER TABLE ... ADD COLUMN`).
`build_categories.py` y `build_tags.py` generan el slug (quitar tildes, minúsculas,
espacios → guiones) al insertar cada categoría/etiqueta. Las tablas siguen vacías hoy
(el pipeline de IA no ha corrido), así que no hizo falta backfill — cuando corra por
primera vez con `OPENAI_API_KEY`, cada fila nueva ya sale con su slug.

## 10. Estado de las fases

| Fase | Contenido | Estado |
|---|---|---|
| 0 | Entorno nativo: PHP (XAMPP), extensiones pgsql habilitadas, limpieza de archivos Docker/WSL | Hecho |
| 1 | `composer.json`, `public/index.php` con rutas, Composer instalado (`C:\composer\composer.phar`) | Hecho |
| 2 | Conexión a BD vía F3 reusando `config/database.php` | Hecho |
| 3 | Modelos `Article`, `Category`, `Tag` | Hecho |
| 4 | Controladores + rutas (`/`, `/articulo/@slug`, `/categoria/@slug`, `/etiqueta/@slug`) | Hecho |
| 5 | Vistas (`layout.htm`, listado, detalle, categoría, etiqueta) | Hecho |
| 6 | PostgreSQL 15 instalado (servicio `postgresql-x64-15`), BD `edtk_eduteka` + usuario `edtk_root_eduteka` creados, schema aplicado, 1577 artículos cargados con `tools/load_articles.py` | **Hecho y verificado** — `GET /` y `GET /articulo/@slug` responden 200 con datos reales; 404 correcto en artículo inexistente |
| 7 | Slugs en `categories`/`tags` (`ALTER TABLE ... ADD COLUMN slug`, ya aplicado a la BD viva), `build_categories.py`/`build_tags.py` generan el slug al insertar, rutas `/categoria/@slug` y `/etiqueta/@slug` | **Hecho y verificado** |
| 8 | Markdown→HTML para `body` vía `league/commonmark`, filtro `{{ @article['body'] \| raw }}` en la vista (sintaxis confirmada leyendo `vendor/bcosca/fatfree-core/base.php`) | **Hecho y verificado** |
| 9 | Limpieza: borrar `apply_schema.php` y el `index.php` placeholder de `articulos/` una vez confirmado que todo funciona | Pendiente |
| 10 (opcional) | Paginación en UI, búsqueda, caché de F3, enlaces de artículo → categoría/etiqueta (hoy `original_category` es solo texto, sin link) | Pendiente |

**Pipeline de IA corrido (2026-08-24):** con `OPENAI_API_KEY` en `.env`, se corrieron
`build_embeddings.py` (1577 embeddings, `text-embedding-3-large`) →
`build_categories.py` (KMeans k=28 + GPT-4.1-mini, 28 categorías, los 1577 artículos
categorizados) → `build_tags.py` (GPT-4.1-mini por artículo, 3367 etiquetas únicas,
1577 artículos etiquetados, 0 errores tras el fix de abajo). `/categoria/@slug` y
`/etiqueta/@slug` ya devuelven contenido real y verificado.

**Bug encontrado y corregido en `build_tags.py`:** el `INSERT ... ON CONFLICT (name) DO
NOTHING` solo cubría choques en `name`, no en `slug` — cuando dos nombres de etiqueta
*distintos* normalizaban al mismo slug (p. ej. dos variantes de "ISTE"), Postgres
lanzaba una violación de unicidad no capturada y esos artículos se quedaban sin
etiquetas (pasó con 4 de 1577 en la primera corrida). Se cambió a
`ON CONFLICT DO NOTHING` (sin columna objetivo, cubre cualquier constraint único de la
tabla) más un `SELECT ... WHERE name = %s OR slug = %s` para recuperar el id en ambos
casos — ver [tools/build_tags.py](../tools/build_tags.py).

## 11. Cómo levantar el proyecto localmente

Ya está corriendo (servidor embebido de PHP, proceso en segundo plano) en
`http://localhost:8080/`. Para volver a levantarlo en una sesión nueva:

```powershell
cd D:\workSpace\eduteka_new
composer install          # solo si vendor/ no existe o composer.json cambió
C:\xampp\php\php.exe -S localhost:8080 -t public
```

(`php` y `composer` ya quedaron en el `PATH` de usuario — en una terminal nueva basta
con `php -S localhost:8080 -t public` y `composer install`.)

Para detener el servidor de prueba actual: `Stop-Process -Id 3228` (PID del proceso
lanzado durante esta implementación).
