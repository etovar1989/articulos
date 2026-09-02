<?php
declare(strict_types=1);
if (!defined('EDUTEKA_APP')) { http_response_code(404); exit; }
/**
 * Vista de login. Recibe $titulo y $error ya resueltos por
 * App\Controllers\Admin\AuthController::login().
 *
 * @var string $titulo
 * @var string|null $error
 */
require __DIR__ . '/../templates/header.php';
?>
<div class="flex justify-center">
    <div class="w-full max-w-sm">
        <div class="<?= e(tw_card()) ?> mt-16 p-6">
            <h1 class="text-lg font-bold mb-4">eduteka · admin</h1>
            <?php if ($error): ?>
                <div class="border rounded px-4 py-2 mb-4 text-sm <?= e(clases_alerta('danger')) ?>"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" class="<?= e(tw_input()) ?>" required autofocus>
                </div>
                <button type="submit" class="<?= e(tw_btn('primario')) ?> w-full">Entrar</button>
            </form>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../templates/footer.php'; ?>
