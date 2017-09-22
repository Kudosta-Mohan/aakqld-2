<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);

$role_id = $_GET['id'];
if(!$role_id){
	$role->redirect('roles.php');
	}

if(isset($_POST['btn-signup']) && isset($_POST['action']) && isset($_GET['id']) && $_POST['action'] == 'edit')
{ 


   $role_id = trim($_POST['role_id']);
   $permissions = $_POST['permissions'];
   
      try
      {
            if($role->updated($role_id,$permissions)) 
            {
                $role->redirect('edit_role.php?id='.$role_id.'&action=edit&updated=1');
            }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }
	 
  } 

elseif(!isset($_POST['btn-signup']) && isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']))
{


$rolexds = $role->getrole($role_id);
$permisions = $role->getpermisionlist();

$rolepermision = $rolexds['capability'];
$currentpermisions = json_decode($rolepermision);
   
	}


?>
<?php include ('../header.php');  ?>

<div id="container"> 
  
  <!-- Page Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="titlesection row">
          <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment">
            <h1 class="margin0">Edit Role Permision</h1>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>roles/roles.php" title="View Roles"><i class="fa fa-users" aria-hidden="true"></i> View Roles</a></div>
        </div>
        <div class="addnewuserform clearfix">
          <form role="form" method="post" id="jobadd" class="">
            <?php
            if(isset($_GET['updated'])&& ($_GET['updated'] == 1))
            {
                 ?>
            <div class="alert alert-info"> <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully Updated </div>
            <?php
            }
            ?>
            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <label for="username">Role:</label>
              <input type="text" class="form-control" id="username" name="role_name" readonly="readonly" value="<?php echo $rolexds['role_name']; ?>">
              <input type="hidden" name="role_id" value="<?php echo $rolexds['role_id'];?>"  />
            </div>
            <div class="form-group col-lg-9 col-md-9 col-sm-9 col-xs-12">
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="checkbox col-lg-3 col-md-3 col-sm-3 col-xs-12 margintop10">
             		<label><input type="checkbox" id="checkAll" value="" name="checkall"><strong>Check all</strong></label>
                </div>    
             </div>
             <?php $permcategoryedit = $perm->permissioncategory();
			  
			  foreach($permcategoryedit as $permcategoryedits){ ?>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margintop10">
               <label> <strong><?php echo $permcategoryedits['name']; ?></strong></label>
                </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php $permisionsedit = $role->getpermisionlistcategory($permcategoryedits['id']);
			if(is_array($permisionsedit)){
				foreach($permisionsedit as $permisionsedits){ ?>
                
				<div class="checkbox col-lg-3 col-md-3 col-sm-3 col-xs-12 margintop10">
                  <label class="selectallcheckbox"><input type="checkbox" value="<?php echo $permisionsedits['perm_id']?>" name="permissions[]" <?php if (is_array($currentpermisions) && in_array($permisionsedits['perm_id'], $currentpermisions)) { echo 'checked="checked"'; } ?>><?php echo $permisionsedits['perm_name']; ?></label>
                </div>
				<?php 	}
				}  
			   ?>
                </div>
               <?php } ?>
            </div>
           
            
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <input type="hidden" class="form-control" id="action" name="action"  value="edit">
              <button type="submit" class="btn btn-default btn-success" name="btn-signup">Update Role</button>
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
			 if(isset($_REQUEST['updated'])) {
			?>
					
					setTimeout( function() {
						var notification = new NotificationFx({
							message : '<p>Role Edited Successfully</p>',
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
