<?php
require_once 'config.php';
if (!empty($_SESSION['user_id'])) redirect(($_SESSION['role'] ?? '') === 'admin' ? 'admin/dashboard.php' : 'dashboard.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf(); $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??'';
 if ($name==='' || mb_strlen($name)>100 || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<10) $error='Enter a valid name and email. Password must contain at least 10 characters.';
 else {
  try { $hash=password_hash($password,PASSWORD_DEFAULT); $stmt=$conn->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'user')"); $stmt->bind_param('sss',$name,$email,$hash); $stmt->execute(); set_flash('success','Account created. You can now sign in.'); redirect('login.php'); }
  catch(mysqli_sql_exception $e) { if ($e->getCode()===1062) $error='An account with this email already exists.'; else { error_log($e->getMessage()); $error='Registration could not be completed. Please try again.'; } }
 }
}
$page_title='Create Account'; include 'includes/header.php'; ?>
<main class="auth-page"><div class="auth-card"><div class="auth-intro"><p class="eyebrow">JOIN EVENTHUB</p><h1>Create your account</h1><p>Register once and manage your college fest participation.</p></div><?php if($error): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>
<form method="post" class="form" autocomplete="on"><?= csrf_field() ?><label>Full Name<input type="text" name="name" maxlength="100" autocomplete="name" required></label><label>Email Address<input type="email" name="email" maxlength="150" autocomplete="email" required></label><label>Password<input type="password" name="password" minlength="10" autocomplete="new-password" required><small>Use at least 10 characters.</small></label><button class="primary-btn full" type="submit">Create Account</button></form><p class="form-foot">Already registered? <a href="login.php">Sign in</a></p></div></main><?php include 'includes/footer.php'; ?>
