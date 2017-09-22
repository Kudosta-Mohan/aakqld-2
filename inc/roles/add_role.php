<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);

if(isset($_POST['btn-signup']))
{ 

   $role_name = $_POST['role_name'];
   $permissions = $_POST['permissions'];
   
   if($role_name=="") {
      $error[] = "Enter Role !"; 
   }
   else
   {
      try
      {
         $stmt = $DB_con->prepare("SELECT role_name FROM roles WHERE role_name=:role_name");
         $stmt->execute(array(':role_name'=>$role_name));
         $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
         if($row['role_name']==$role_name) {
            $error[] = "sorry role already taken !";
         }
         else
         {
            if($role->insert($role_name,$permissions)) 
            {
                $role->redirect('add_role.php?joined');
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
	$role_name = $role_name;
	$currentpermisions = $permissions;
} else {
	$role_name = '';
	$currentpermisions = '';
	}
	
	
	

?>
<?php include ('../header.php');  ?>

<div id="container">
    
  <!-- Page Content -->
    <div class="container">
    
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
<div class="titlesection row">   
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="margin0">Add New Role</h1></div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>roles/roles.php" title="View Roles"><i class="fa fa-users" aria-hidden="true"></i> View Roles</a></div>
</div>

<div class="addnewuserform">

<form role="form" method="post" id="jobadd" class="">
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
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully Added 
                 </div>
            <?php 
				} 
				?>
            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <label for="username">Role:</label>
              <input type="text" class="form-control" id="username" name="role_name"  value="<?php echo $role_name; ?>">
            </div>
            
            
            
            <div class="form-group col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="checkbox col-lg-3 col-md-3 col-sm-3 col-xs-12 margintop10">
             		<label><input type="checkbox" id="checkAll" value="" name="checkall"><strong>Check all</strong></label>
                </div>    
             </div>
             
             <?php $permcategory = $perm->permissioncategory();
			  
			  foreach($permcategory as $permcategories){ ?>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margintop10">
                <label> <strong><?php echo $permcategories['name']; ?></strong></label>
                </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php $permisions = $role->getpermisionlistcategory($permcategories['id']);
			if(is_array($permisions)){
				foreach($permisions as $permision){ ?>
                
				<div class="checkbox col-lg-3 col-md-3 col-sm-3 col-xs-12 margintop10">
                  <label class="selectallcheckbox"><input type="checkbox" value="<?php echo $permision['perm_id']?>" name="permissions[]" <?php if (is_array($currentpermisions) && in_array($permision['perm_id'], $currentpermisions)) { echo 'checked="checked"'; } ?>><?php echo $permision['perm_name']; ?></label>
                </div>
				<?php 	}
				}  
			   ?>
                </div>
               <?php } ?>
            </div>
           
            
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button type="submit" class="btn btn-default btn-success" name="btn-signup">Add Role</button>
            </div>
          </form>

</div>	

  </div>
    </div>

        

    </div>
    <!-- /.container -->    
    
</div>
<script type="text/javascript">
		jQuery(document).ready(function($) {
			<?php 
			 if(isset($_REQUEST['joined'])) {
			?>
					
					setTimeout( function() {
						var notification = new NotificationFx({
							message : '<p>Role Added Successfully</p>',
							layout : 'growl',
							effect : 'slide',
							type : 'notice', // notice, warning or error
						});
						// show the notification
						notification.show();
					}, 1200 );
			<?php } ?>
			
			
$("#checkAll").change(function () { 
    $('.selectallcheckbox').find("input:checkbox").prop('checked', $(this).prop("checked"));
});
			
			
		});
</script>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>