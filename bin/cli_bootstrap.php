<?php
// Bootstrap para scripts de bin/: autoload + config, sin routing (sin Base::instance()->run()).

declare(strict_types=1);

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

$f3 = \Base::instance();
$f3->set('ROOT_DIR', $root);
$f3->config($root . '/app/config.ini');
if (is_file($root . '/app/config.local.ini')) {
    $f3->config($root . '/app/config.local.ini');
}

return $f3;
