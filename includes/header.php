<?php if (!isset($page_title)) $page_title = 'Event Management System'; $asset_prefix = $asset_prefix ?? ''; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="description" content="EventHub college fest event registration platform"><title><?= h($page_title) ?> | EventHub</title><link rel="stylesheet" href="<?= h($asset_prefix) ?>assets/css/style.css"></head><body>
<header class="topbar"><a class="brand" href="<?= h($asset_prefix) ?>index.php"><span>EH</span> EventHub</a><nav>
<a href="<?= h($asset_prefix) ?>index.php">Home</a>
<?php if (!empty($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'user'): ?>
<a href="<?= h($asset_prefix) ?>dashboard.php">Dashboard</a><a href="<?= h($asset_prefix) ?>my_registrations.php">My Events</a>
<form class="nav-inline" method="post" action="<?= h($asset_prefix) ?>logout.php"><?= csrf_field() ?><button class="nav-button" type="submit">Logout</button></form>
<?php elseif (!empty($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin'): ?>
<a class="nav-button" href="<?= h($asset_prefix) ?>admin/dashboard.php">Admin Panel</a><form class="nav-inline" method="post" action="<?= h($asset_prefix) ?>logout.php"><?= csrf_field() ?><button class="nav-button" type="submit">Logout</button></form>
<?php else: ?><a href="<?= h($asset_prefix) ?>login.php">Login</a><a class="nav-button" href="<?= h($asset_prefix) ?>register.php">Register</a><?php endif; ?>
</nav></header>
<?php if (!empty($_SESSION['_flash'])): ?><div class="page-shell"><?php display_flash(); ?></div><?php endif; ?>
