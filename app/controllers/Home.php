<?php

namespace App\Controllers;

use App\Lib\Db;
use App\Lib\View;
use App\Models\ArticleModel;
use Base;

class Home
{
    public function index(Base $f3): void
    {
        $pdo = Db::pdo($f3);

        View::render('views/inicio.php', [
            'totalArticulos' => ArticleModel::totalPublicados($pdo),
            'destacados' => ArticleModel::destacadosConPortada($pdo, $_SERVER['DOCUMENT_ROOT'] . '/articulos/img/portadas', 4),
            'titulo' => 'Inicio',
            'descripcion' => 'Eduteka: recursos, artículos y herramientas con IA para docentes de Hispanoamérica. Centro de Innovación Educativa y TIC de la Universidad Icesi.',
        ]);
    }
}
