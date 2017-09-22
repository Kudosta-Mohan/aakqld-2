<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);

if(isset($_POST['btn-signup']))
{ 

   $uname = trim($_POST['username']);
   $umail = trim($_POST['email']);
   $upass = trim($_POST['password']);
   $fname = trim($_POST['firstname']);
   $lname = trim($_POST['lastname']);
   $uphone = trim($_POST['phone']);
   $cust_contactlist = $_POST['equipmentsize'];
  // $userdatelicenses = trim($_POST['userdatelicenses']);
    $userattachmenturl = '';
    if(isset($_POST['picedit']))
    {
      $userattachmenturl = $_POST['picedit'];
    }
    $userattachmentname = '';
    if(isset($_POST['piceditname']))
    {
      $userattachmentname = $_POST['piceditname'];
    }
   $em_contact = $_POST['em_contact'];
   $utype = isset( $_POST['usertype']) ?  $_POST['usertype'] : ''; 
 $utype = isset( $_POST['usertype']) ?  $_POST['usertype'] : '';
   if($uname=="") {
      $error[] = "Enter username !"; 
   }
   
   else if($upass=="") {
      $error[] = "Enter password !";
   }
   else if(strlen($upass) < 6){
      $error[] = "Password must be atleast 6 characters"; 
   }
   else
   {
      try
      {
         $stmt = $DB_con->prepare("SELECT user_name,user_email FROM users WHERE user_name=:uname OR user_email=:umail");
         $stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
         $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
         if($row['user_name']==$uname) {
            $error[] = "sorry username already taken !";
         }
         
         else
         {
            if($user->register($fname,$lname,$uname,$umail,$upass,$uphone,$utype,$userattachmenturl,$userattachmentname,$cust_contactlist,$em_contact)) 
            {
                $user->redirect('add_users.php?joined');
            }
         }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }
  } 
}

if(isset($error)){ 
	$username = $uname;
	$password = $upass;
	$firstname = $fname;
	$lastname = $lname;
	$email = $umail;
	$phone = $uphone;
	$utypearrays = $utype;
	$cust_contactlist = $cust_contactlist;
} else {
	$username = '';
	$password = '';
	$firstname = '';
	$lastname = '';
	$email = '';
	$phone = '';
	$utypearrays = '';
	$cust_contactlist  = '';
	}

?>
<?php include ('../header.php');  ?>

<div id="container">
    
  <!-- Page Content -->
    <div class="container">
    
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
<div class="titlesection row">   
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="margin0">Add New User</h1></div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>settings/users.php" title="View Users"><i class="fa fa-users" aria-hidden="true"></i> View Users</a></div>
</div>

<div class="addnewuserform">

 <form role="form" method="post" id="add_users" class="add_users">
 
 
  		 <?php
            if(isset($error))
            {
               foreach($error as $error)
               {
                  ?>
                  <div class="alert alert-danger">
                      <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                  </div>
                  <?php
               }
            }
            else if(isset($_GET['joined']))
            {
                 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered 
                 </div>
            <?php 
				} 
				?>

                   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="username">Username:</label>
                      <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username Here" value="<?php echo $username; ?>">
                   </div>
                   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="email">Email:</label>
                      <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email ID Here" value="<?php echo $email; ?>">
                   </div>
                   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="password">Password:</label>
                      <input type="text" class="form-control" id="password" name="password" placeholder="Enter Password Here" value="<?php echo $password; ?>">
                   </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="phone">Phone:</label>
                      <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number Here" value="<?php echo $phone; ?>">
                   </div>
                   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="firstname">First Name:</label>
                      <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter Firstname Here" value="<?php echo $firstname; ?>">
                   </div>
                   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="lastname">Last Name:</label>
                      <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Lastname Here" value="<?php echo $lastname; ?>">
                   </div>
                   
                   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="em_contact">Emergency Contact 1: Name</label>
                      <input type="text" class="form-control" name="em_contact[name][]" placeholder="Enter Emergency Contact Number Here" value="<?php if(isset($emgerncy_name[0]->name)) echo $emgerncy_name[0]->name; ?>">
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="em_contact">Emergency Contact 1: Phone</label>
                      <input type="text" class="form-control" name="em_contact[phone][]" placeholder="Enter Emergency Contact Number Here" value="<?php if(isset($emgerncy_name[0]->phone)) echo $emgerncy_phone[0]->phone; ?>">
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="em_contact">Emergency Contact 2: Name</label>
                      <input type="text" class="form-control" name="em_contact[name][]" placeholder="Enter Emergency Contact Number Here" value="<?php if(isset($emgerncy_name[1]->name)) echo $emgerncy_name[1]->name; ?>">
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="em_contact">Emergency Contact 2: Phone</label>
                      <input type="text" class="form-control" name="em_contact[phone][]" placeholder="Enter Emergency Contact Number Here" value="<?php if(isset($emgerncy_name[1]->phone)) echo $emgerncy_phone[1]->phone; ?>">
            </div> 
                   
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="myown">
            <br />
            <?php $i = 1; ?>
            <label class="listno">Staff Certificates  </label>
                      
                      <div class="newentry input-group newsieses<?php echo $i; ?>">
            
             <div class="pull-left input-group list_nosss">
             
              <input class="form-control" name="equipmentsize[name][]" type="text" placeholder="Name of Certificate"  />
              <input class="form-control" name="equipmentsize[date][]" type="text" id="dateequi" placeholder="Expires on"/>
             </div> 
              <div class="form-group pull-left appendcertificatesequipment list_nosss">
             
              <div class="fileuploaderssequisize" data-upid='upid'>Upload</div>
            </div>
            
            <span class="pull-right col-lg-1 col-md-1 col-sm-1 col-xs-1 input-group-btn">
                  <button class="btn btn-success addequipmentsizes addnewbtn adduserdata" type="button"> 
                    <span class="glyphicon glyphicon-plus"></span> 
                  </button>
            </span> 
            <div class="clearfix"></div>
        </div>
                    </div>
              
            		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="usertype">User Type:</label>
              <?php if (in_array($_SESSION['user_type'], $notaccesstype)){ ?>
              <select name="usertype[]" id="usertype" class="form-control multiselect" multiple="multiple">
                <?php $user_types = $role->getroleslist();
			  		foreach($user_types as $user_type){
					  if (in_array($user_type['role_name'], $notaccesstype)){ ?>
                <option value="<?php echo $user_type['role_id']; ?>"> <?php echo $user_type['role_name']; ?> </option>
                <?php } } ?>
              </select>
              <?php } else { ?>
              <select name="usertype[]" id="usertype" class="form-control multiselect" multiple="multiple">
                <?php $user_types = $role->getroleslist();
			  		foreach($user_types as $user_type){ ?>
                <option value="<?php echo $user_type['role_id']; ?>"> <?php echo $user_type['role_name']; ?> </option>
                <?php  } ?>
              </select>
              <?php } ?>
            </div>
                   
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <button type="submit" class="btn btn-default" name="btn-signup">Save User</button>
                   </div>
                   
                   
                </form>

</div>	

  </div>
    </div>

        

    </div>
    <!-- /.container -->    
    
</div>

<script type="text/javascript">

var c = <?php echo $i;?>; 
jQuery(document).on('click', '.addequipmentsizes', function($)
{
	jQuery(this).removeClass('addequipmentsizes').addClass('deleteequipmentsizes').removeClass('btn-success').addClass('btn-danger')
	.html('<span class="glyphicon glyphicon-minus"><input class="dltid" type="hidden" value="'+ c +'" /></span>');
	
	c += 1;
	jQuery('#myown').append('<div class="newentry input-group newsieses'+ c +'">  <div class="pull-left input-group list_nosss"> <input class="form-control" name="equipmentsize[name][]" type="text" placeholder="Name of Certificate"  />  <input id="dateequi'+ c +'" class="form-control" name="equipmentsize[date][]" type="text" placeholder="Expires on"/>  </div>   <div class="form-group pull-left appendcertificatesequipment list_nosss"> <div class="fileuploaderssequisize'+ c +'" data-upid="upid">Upload</div> </div>  <span class="pull-right col-lg-1 col-md-1 col-sm-1 col-xs-1 input-group-btn">   <button class="btn btn-success addequipmentsizes addnewbtn  adduserdata" type="button">    <span class="glyphicon glyphicon-plus"></span>  </button>  </span>    <div class="clearfix"></div>  </div>');
	
	for (i = 0; i <= c; i++) { 
	jQuery(".fileuploaderssequisize"+i).uploadFile({
		
    url:"<?php echo home_base_url(); ?>/class/class.ajax.php?action=uploadimages",
    fileName:"myfile",
    showDelete: true,
    onSuccess: function (files, response, xhr, pd) {
    pd.statusbar.append(response);
    }
	});
	
	jQuery('#dateequi'+i).datepicker({
		//startDate: new Date(),
		format: 'dd/mm/yyyy',
		});
		
}
	
});


		jQuery(document).ready(function($) {
			<?php 
			 if(isset($_REQUEST['joined'])) {
			?>
					
					setTimeout( function() {
						var notification = new NotificationFx({
							message : '<p>User Added Successfully</p>',
							layout : 'growl',
							effect : 'slide',
							type : 'notice', // notice, warning or error
						});
						// show the notification
						notification.show();
					}, 1200 );
			<?php } ?>
			
			setTimeout( function() {
				//alert();
				jQuery('.userdatelicenses').datepicker({
	
//startDate: new Date(),
format: 'yyyy/mm/dd/',
});	
			},500);
			
	
	 
	 $(".fileuploaderssequisize").uploadFile({
		
    url:"<?php echo home_base_url(); ?>class/class.ajax.php?action=uploadimages",
    fileName:"myfile",
    showDelete: true,
    onSuccess: function (files, response, xhr, pd) {
		//alert(response);
    pd.statusbar.append(response);
    }
    });	
	
	jQuery(document).on('click', '.deleteequipmentsizes', function($)
	{
	var id = jQuery(this).data('id');
	var valuess = jQuery(this).find('.dltid').val();
	jQuery('.newsieses'+valuess).remove();
	//jQuery(this).parent().parent().remove();
	//alert(valuess);		
	var data ='action=deleteuserattachment&userid='+id;
	jQuery.ajax({
    type: "POST",
    url: '<?php echo home_base_url(); ?>class/class.ajax.php',
    
    data: data,
    cache: false,
    success: function(result){ 
	//alert(result);
	//window.location.href = '<?php echo home_base_url(); ?>equipment/editsize_equipment.php?equi_id=1&equisize_id=8&updated';
	
	}
    });
	});
	jQuery('#dateequi').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});	
	
		});
</script>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>