<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Lib\Db;
use App\Lib\View;
use App\Models\RagModel;
use Base;

final class RagController extends AdminController
{
    public function index(Base $f3): void
    {
        $pdo = Db::pdo($f3);
        $configIA = [
            'openai_api_key' => (string) $f3->get('OPENAI_API_KEY'),
            'chat_model' => (string) $f3->get('OPENAI_CHAT_MODEL'),
        ];

        $resultado = null;
        $error = null;
        $preguntaEnviada = '';
        $historialTexto = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $preguntaEnviada = trim((string) ($_POST['pregunta'] ?? ''));
            $historialTexto = (string) ($_POST['historial'] ?? '');

            $historial = [];
            foreach (preg_split('/\r?\n/', $historialTexto) as $linea) {
                if (preg_match('/^\s*(Usuario|Asistente)\s*:\s*(.+)$/i', $linea, $m)) {
                    $historial[] = [
                        'role' => mb_strtolower($m[1]) === 'asistente' ? 'assistant' : 'user',
                        'content' => trim($m[2]),
                    ];
                }
            }

            if ($preguntaEnviada === '' || mb_strlen($preguntaEnviada) > 2000) {
                $error = 'Escribe una pregunta de prueba (máximo 2000 caracteres).';
            } else {
                $resultado = RagModel::ejecutarPipelineInspeccion(
                    $pdo,
                    $configIA,
                    $preguntaEnviada,
                    $historial,
                    (float) $f3->get('PRECIO_ENTRADA_POR_M'),
                    (float) $f3->get('PRECIO_SALIDA_POR_M')
                );
            }
        }

        View::render('admin/views/rag/index.php', [
            'titulo' => 'Inspector del RAG',
            'resultado' => $resultado,
            'error' => $error,
            'preguntaEnviada' => $preguntaEnviada,
            'historialTexto' => $historialTexto,
        ]);
    }
}
