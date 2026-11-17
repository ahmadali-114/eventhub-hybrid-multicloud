<?php
require_once 'config.php';
if (!empty($_SESSION['user_id'])) redirect(($_SESSION['role']??'')==='admin'?'admin/dashboard.php':'dashboard.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf(); $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??'';
 if(filter_var($email,FILTER_VALIDATE_EMAIL) && $password!==''){
  $stmt=$conn->prepare('SELECT id,name,password,role FROM users WHERE email=? LIMIT 1'); $stmt->bind_param('s',$email); $stmt->execute(); $user=$stmt->get_result()->fetch_assoc();
  if($user && password_verify($password,$user['password'])){ session_regenerate_id(true); $_SESSION['user_id']=(int)$user['id']; $_SESSION['user_name']=$user['name']; $_SESSION['role']=$user['role']; redirect($user['role']==='admin'?'admin/dashboard.php':'dashboard.php'); }
 }
 $error='Invalid email or password.';
}
$page_title='Login'; include 'includes/header.php'; ?>
<main class="auth-page"><div class="auth-card"><div class="auth-intro"><p class="eyebrow">WELCOME BACK</p><h1>Sign in to EventHub</h1><p>Access registrations, event details, and your dashboard.</p></div><?php if($error): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?><form method="post" class="form"><?= csrf_field() ?><label>Email Address<input type="email" name="email" autocomplete="username" required></label><label>Password<input type="password" name="password" autocomplete="current-password" required></label><button class="primary-btn full" type="submit">Sign In</button></form><p class="form-foot">New to EventHub? <a href="register.php">Create an account</a></p></div></main><?php include 'includes/footer.php'; ?>
