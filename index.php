<?php
require_once 'dbconfig.php';

if($user->is_loggedin()!="")
{
 $user->redirect('dashboard.php');
}

/*if(!isset($_SESSION['user_session']))
{
	  $tbc_notification = 1;
  	$date = isset($_GET['date']) ? $_GET['date'] : date('d/m/Y'); 
  	$date = str_replace('/', '-', $date);
  	$todaydate = date("Y-m-d");	

   	$atodaydate = strtotime($todaydate);
  	//$tbc_date = date('d/m/Y', strtotime($date .' +3 day'));
  	$tbc_jobnotificati = $job->tbcnotification($atodaydate,$tbc_notification);
  		foreach($tbc_jobnotificati as $tbc_jobnotificatiss){  
    		if($tbc_jobnotificatiss['job_date'] <= $tbc_date){
    		$jobids = $tbc_jobnotificatiss['id'];
             $tbcnotification = $job->sessionvalue($jobids);
    		}
  		}
}*/


if(isset($_POST['btn-login']))
{
$uname = $_POST['email'];	
$umail = $_POST['email'];
$upass = $_POST['password'];

 if($user->login($uname,$umail,$upass))
 {
	 
	$user_type = explode(",",$_SESSION['user_type']);
	if(in_array('Crane Operator',$user_type) || in_array('Dogman Rigger',$user_type)){
		$user->redirect('crane_dogman.php');
	} 
	else {
    $user->redirect('dashboard.php');
	}
 }
 else
 {
  $error = "Wrong Details !";
 } 

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AAKQLD</title>

<!-- css include here -->
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
</head>
<body>

    <div class="container"> 
    
    <div class="loginbox_logo">
    	<a href="<?php echo home_base_url(); ?>"><h2>AAKQLD</h2></a>
    </div>
    
      
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Sign In</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >
                    
                    
<?php if(isset($error)) {  ?>
                  <div class="alert alert-danger">
                      <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
                  </div>
                  <?php }  ?>

                            
                        <form id="loginform" class="form-horizontal" role="form" method="post">
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-email" type="text" class="form-control" name="email" value="" placeholder="Username or E mail ID" required >                                        
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login-password" type="password" class="form-control" name="password" placeholder="Password" required >
                                        <span class="input-group-addon showpassword"><i class="glyphicon glyphicon-eye-open"></i></span>
                                    </div>
                                    
                                    
							<div class="form-group">
                                    <!-- Button -->

                                    <div class="col-sm-6 controls">
                                      <button id="btn-login" name="btn-login" class="btn btn-success">Login  </button>
                                    </div>
                                    <div class="col-sm-6 controls checkbox text-right">
                                     <label>
                                          <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                                        </label>
                                    </div>
                                    
                                </div>
                                


                            </form>     



                        </div>                     
                    </div>  
        </div>
    </div>
    
<!-- jquery include here -->
<script src="js/jquery-1.12.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
	jQuery(document).ready(function() {
		
		

 
                if (localStorage.chkbx && localStorage.chkbx != '') {
                    $('#login-remember').attr('checked', 'checked');
                    $('#login-email').val(localStorage.usrname);
                    $('#login-password').val(localStorage.pass);
                } else {
                    $('#login-remember').removeAttr('checked');
                    $('#login-email').val('');
                    $('#login-password').val('');
                }
 
                $('#login-remember').click(function() {
 
                    if ($('#login-remember').is(':checked')) {
                        // save username and password
                        localStorage.usrname = $('#login-email').val();
                        localStorage.pass = $('#login-password').val();
                        localStorage.chkbx = $('#login-remember').val();
                    } else {
                        localStorage.usrname = '';
                        localStorage.pass = '';
                        localStorage.chkbx = '';
                    }
                });
	
		
jQuery.fn.toggleAttr = function(attr, attr1, attr2) {
  return this.each(function() {
    var self = jQuery(this);
    if (self.attr(attr) == attr1)
      self.attr(attr, attr2);
    else
      self.attr(attr, attr1);
  });
};
		
		
jQuery('.showpassword').click(function(){ 
	jQuery(this).parent().find('input').toggleAttr('type', 'password', 'text');
});

});
</script>

</body>
</html>