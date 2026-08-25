<?php
declare(strict_types=1);
require __DIR__ . '/lib/auth.php';

$_SESSION = [];
session_destroy();
header('Location: /admin/login.php');
exit;
