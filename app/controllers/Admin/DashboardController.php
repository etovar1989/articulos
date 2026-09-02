<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Lib\Db;
use App\Lib\View;
use App\Models\ArticleModel;
use App\Models\CategoryModel;
use App\Models\TagModel;
use Base;

final class DashboardController extends AdminController
{
    public function index(Base $f3): void
    {
        $pdo = Db::pdo($f3);

        View::render('admin/views/index.php', [
            'titulo' => 'Panel',
            'totales' => ArticleModel::totalesPorEstadoYRag($pdo),
            'nCategorias' => CategoryModel::contar($pdo),
            'nEtiquetas' => TagModel::contar($pdo),
        ]);
    }
}
