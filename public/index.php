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
