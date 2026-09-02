<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Lib\Db;
use App\Lib\View;
use App\Models\MetricsModel;
use Base;

final class MetricsController extends AdminController
{
    private function precios(Base $f3): array
    {
        return [
            (float) $f3->get('PRECIO_ENTRADA_POR_M'),
            (float) $f3->get('PRECIO_SALIDA_POR_M'),
        ];
    }

    public function index(Base $f3): void
    {
        $pdo = Db::pdo($f3);
        [$precioEntrada, $precioSalida] = $this->precios($f3);

        $totales = MetricsModel::totalesChat($pdo);
        $costoTotal = ($totales['tokens_in'] / 1000000 * $precioEntrada)
            + ($totales['tokens_out'] / 1000000 * $precioSalida);

        $totalesBusqueda = MetricsModel::totalesBusqueda($pdo);
        $costoBusquedaTokens = MetricsModel::costoBusquedaTokens($pdo);
        $costoBusquedaTotal = ($costoBusquedaTokens['tokens_in'] / 1000000 * $precioEntrada)
            + ($costoBusquedaTokens['tokens_out'] / 1000000 * $precioSalida);

        $busquedasFrecuentes = MetricsModel::busquedasFrecuentes($pdo, 15);

        $q = trim((string) ($_GET['q'] ?? ''));
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
        $porPagina = 25;
        $resultado = MetricsModel::articulosConChat($pdo, $q, $pagina, $porPagina);

        View::render('admin/views/metricas/index.php', [
            'titulo' => 'Métricas',
            'totales' => $totales,
            'costoTotal' => $costoTotal,
            'totalesBusqueda' => $totalesBusqueda,
            'costoBusquedaTotal' => $costoBusquedaTotal,
            'busquedasFrecuentes' => $busquedasFrecuentes,
            'q' => $q,
            'pagina' => $pagina,
            'filas' => $resultado['items'],
            'totalFilas' => $resultado['total'],
            'totalPaginas' => $resultado['totalPaginas'],
        ]);
    }

    public function busquedas(Base $f3): void
    {
        $pdo = Db::pdo($f3);

        $q = trim((string) ($_GET['q'] ?? ''));
        $soloSinResultados = isset($_GET['sin_resultados']);
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
        $porPagina = 30;

        $resultado = MetricsModel::busquedasLog($pdo, $q, $soloSinResultados, $pagina, $porPagina);
        $baseUrl = '/admin/metricas/busquedas.php?q=' . urlencode($q) . ($soloSinResultados ? '&sin_resultados=1' : '');

        View::render('admin/views/metricas/busquedas.php', [
            'titulo' => 'Búsquedas del buscador semántico',
            'q' => $q,
            'soloSinResultados' => $soloSinResultados,
            'pagina' => $pagina,
            'filas' => $resultado['items'],
            'totalFilas' => $resultado['total'],
            'totalPaginas' => $resultado['totalPaginas'],
            'baseUrl' => $baseUrl,
        ]);
    }

    public function preguntas(Base $f3): void
    {
        $pdo = Db::pdo($f3);

        $articuloId = trim((string) ($_GET['articulo_id'] ?? ''));
        $q = trim((string) ($_GET['q'] ?? ''));
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
        $porPagina = 30;

        $articuloActual = $articuloId !== '' ? MetricsModel::articuloPorId($pdo, (int) $articuloId) : null;
        $resultado = MetricsModel::chatLog($pdo, $articuloId !== '' ? (int) $articuloId : null, $q, $pagina, $porPagina);

        View::render('admin/views/metricas/preguntas.php', [
            'titulo' => 'Preguntas del chat',
            'articuloId' => $articuloId,
            'q' => $q,
            'pagina' => $pagina,
            'articuloActual' => $articuloActual,
            'filas' => $resultado['items'],
            'totalFilas' => $resultado['total'],
            'totalPaginas' => $resultado['totalPaginas'],
        ]);
    }
}
