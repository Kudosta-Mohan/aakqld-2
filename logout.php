<?php
require_once 'dbconfig.php';

$userdata = $user->logout();

if($user->is_loggedin()!="")
{
 $user->redirect('home.php');
} else {
 $user->redirect('index.php');	
}
?>