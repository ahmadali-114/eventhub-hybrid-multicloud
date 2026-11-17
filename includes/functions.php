<?php
declare(strict_types=1);

function h(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function redirect(string $path): never { header('Location: ' . $path); exit; }
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['_csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="_csrf" value="' . h(csrf_token()) . '">'; }
function verify_csrf(): void {
    $sent = $_POST['_csrf'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['_csrf'] ?? '', $sent)) {
        http_response_code(419); exit('Your session token expired. Go back, refresh the page, and try again.');
    }
}
function set_flash(string $type, string $message): void { $_SESSION['_flash'] = ['type'=>$type, 'message'=>$message]; }
function display_flash(): void {
    if (empty($_SESSION['_flash'])) return;
    $f = $_SESSION['_flash']; unset($_SESSION['_flash']);
    echo '<div class="alert ' . h($f['type']) . '">' . h($f['message']) . '</div>';
}
function current_user_id(): int { return (int)($_SESSION['user_id'] ?? 0); }
