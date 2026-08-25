<?php

namespace App\Controllers;

use Base;

class Home
{
    public function index(Base $f3): void
    {
        $f3->reroute('/articulos/index.php');
    }
}
