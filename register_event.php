<?php
require_once 'config.php'; require_once 'includes/auth.php'; require_login();
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('Method not allowed');} verify_csrf(); $event_id=(int)($_POST['event_id']??0);$user_id=current_user_id();
try{
 $conn->begin_transaction();
 $lock=$conn->prepare('SELECT id,capacity FROM events WHERE id=? FOR UPDATE');$lock->bind_param('i',$event_id);$lock->execute();$event=$lock->get_result()->fetch_assoc();
 if(!$event){$conn->rollback();set_flash('error','The selected event could not be found.');redirect('index.php');}
 $dup=$conn->prepare('SELECT id FROM registrations WHERE user_id=? AND event_id=? LIMIT 1');$dup->bind_param('ii',$user_id,$event_id);$dup->execute();
 if($dup->get_result()->num_rows){$conn->rollback();set_flash('error','You are already registered for this event.');redirect('my_registrations.php');}
 $count=$conn->prepare("SELECT COUNT(*) AS total FROM registrations WHERE event_id=? AND status='confirmed'");$count->bind_param('i',$event_id);$count->execute();$taken=(int)$count->get_result()->fetch_assoc()['total'];
 if($taken >= (int)$event['capacity']){$conn->rollback();set_flash('error','Sorry, this event has reached capacity.');redirect('event.php?id='.$event_id);}
 $insert=$conn->prepare("INSERT INTO registrations(user_id,event_id,status) VALUES(?,?,'confirmed')");$insert->bind_param('ii',$user_id,$event_id);$insert->execute();$conn->commit();set_flash('success','Your event registration is confirmed.');redirect('my_registrations.php');
}catch(Throwable $e){if($conn->errno===0){} try{$conn->rollback();}catch(Throwable $ignored){} error_log('Registration error: '.$e->getMessage());set_flash('error','Registration could not be completed. Please try again.');redirect('event.php?id='.$event_id);}
