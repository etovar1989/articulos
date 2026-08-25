<?php

namespace App\Controllers;

use Base;

class Home
{
    public function index(Base $f3): void
    {
        require $f3->get('ROOT_DIR') . '/public/inicio.php';
    }
}
