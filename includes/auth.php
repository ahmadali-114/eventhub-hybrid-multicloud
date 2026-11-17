<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
function require_login(): void {
    if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'user') { set_flash('error','Please sign in with a student account.'); redirect('/login.php'); }
}
function require_admin(): void {
    if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { set_flash('error','Administrator access is required.'); redirect('/login.php'); }
}
