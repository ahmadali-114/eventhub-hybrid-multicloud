<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require_once __DIR__.'/../config.php';
$email=strtolower(trim(getenv('ADMIN_EMAIL')?:''));$password=getenv('ADMIN_PASSWORD')?:'';$name=getenv('ADMIN_NAME')?:'EventHub Administrator';
if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<14){fwrite(STDERR,"Set ADMIN_EMAIL and ADMIN_PASSWORD (minimum 14 characters) in your environment.\n");exit(1);}
$hash=password_hash($password,PASSWORD_DEFAULT);$stmt=$conn->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'admin') ON DUPLICATE KEY UPDATE name=VALUES(name),password=VALUES(password),role='admin'");$stmt->bind_param('sss',$name,$email,$hash);$stmt->execute();echo "Administrator account created/updated for {$email}.\n";
