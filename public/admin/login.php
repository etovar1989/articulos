<?php
declare(strict_types=1);
require __DIR__ . '/lib/auth.php';
require __DIR__ . '/lib/helpers.php';

$config = require __DIR__ . '/config/config.php';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $clave = (string) ($_POST['password'] ?? '');
    if (password_verify($clave, $config['admin_password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_autenticado'] = true;
        redirect('/admin/index.php');
    }
    $error = 'Contraseña incorrecta.';
}

if (usuario_autenticado()) {
    redirect('/admin/index.php');
}

$titulo = 'Iniciar sesión';
require __DIR__ . '/templates/header.php';
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
<?php require __DIR__ . '/templates/footer.php'; ?>
