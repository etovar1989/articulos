<?php

namespace App\Controllers;

use Base;

class Home
{
    public function index(Base $f3): void
    {
        require $_SERVER['DOCUMENT_ROOT'] . '/inicio.php';
    }
}
