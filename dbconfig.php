<?php
session_start();
//error_reporting(0);

$DB_host = "localhost";
$DB_user = "root";
//$DB_pass = "";
//$DB_name = "job_db";

/** live server detail **/
// $DB_pass = "Z%%37UBER1337";
$DB_pass = "";
$DB_name = "aakqld";

try
{
     $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}
include_once 'class/class.roles.php';
include_once 'class/class.perm.php';
include_once 'class/class.user.php';
include_once 'class/class.customers.php';
include_once 'class/class.equipment.php';
include_once 'class/class.jobs.php';
include_once 'class/class.phpmailer.php';
include_once 'inc/functions.php';
include_once 'class/class.auxequipment.php';
include_once 'class/class.mailbody.php';
include_once 'class/class.farms.php';
include_once 'class/class.fruit.php';
include_once 'class/class.setting.php';

$role = new ROLES($DB_con);
$perm = new PERMISSION($DB_con);
$user = new USER($DB_con);
$customer = new Customer($DB_con);
$equipment = new Equipment($DB_con);
$job = new Jobs($DB_con);
$auxequipment = new Auxequipment($DB_con);
$mailbody = new Mailbody($DB_con);
$mail = new PHPMailer(); // defaults to using php "mail()"

$farms = new FARMS($DB_con); 
$fruit = new FRUIT($DB_con); 
$setting = new SETTING($DB_con); 

if(isset($_SESSION['user_session'])){
$userdata = $user->loginuserdata($_SESSION['user_session']);
}
if(isset($_SESSION['user_type'])){
$currentusertype = $_SESSION['user_type'];
}

$notaccesstype = array('General','Accounts','Crane Operator','Dogman Rigger','Truck Driver');
$jobtimechangetype = array('SuperAdmin','Dispatch','Admin');
$jobsecureaccess = array('SuperAdmin','Admin','Dispatch','Accounts');
$menucondition = array('35','2'); /* ABL IT, Jason */
$menucondition2 = array('35','2', '1','7','48'); /* ABLIT, Jason, Admin, Accounts, Admin2 */
$menucondition3 = array('35','2', '1','34','37','14','7','6','42,'); /* ABLIT, Jason, Admin, Dispatch, Jeff, Wayne, Accounts, Graeme, Jeff (Safety) */
$json = array('2');
$equipment_users = array('1','2','5','34','13','35','37','14','7','6','42');
 /*
1 	Admin
2	Jason
7 Accounts
13 	Adrian
34	Dispatch
35	ABL IT
42 Jeff W (safety)
*/
$operator = array('Crane Operator','Dogman Rigger');
