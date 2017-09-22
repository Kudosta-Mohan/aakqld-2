<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);

$perm_id = $_GET['id'];
if(!$perm_id){
	$perm->redirect('permissions.php');
	}

if(isset($_POST['btn-signup']) && isset($_POST['action']) && isset($_GET['id']) && $_POST['action'] == 'edit')
{ 


   $perm_id = $_POST['perm_id'];
   $perm_name = $_POST['perm_name'];
   $perm_desc = $_POST['perm_desc'];
   $perm_url = $_POST['perm_url'];
   $perm_status = $_POST['perm_status'];
   $perm_order = $_POST['perm_order'];
   $perm_category = $_POST['perm_category'];
   
      try
      {
            if($perm->updated($perm_id,$perm_name,$perm_desc,$perm_url,$perm_status,$perm_order,$perm_category)) 
            {
                $perm->redirect('edit_perm.php?id='.$perm_id.'&action=edit&updated=1');
            }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }
	 
  } 

elseif(!isset($_POST['btn-signup']) && isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']))
{
  $permdata = $perm->getperm($perm_id);

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
            <h1 class="margin0">Edit Permission</h1>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>perm/permissions.php" title="View Permissions"><i class="fa fa-users" aria-hidden="true"></i> View Permissions</a></div>
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
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="username">Permission Page Name:</label>
              <input type="text" class="form-control" id="username" name="perm_name"  value="<?php echo $permdata['perm_name']; ?>">
              <input type="hidden" name="perm_id" value="<?php echo $permdata['perm_id'];?>"  />
            </div>
            
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="username">Permission Page Description:</label>
              <input type="text" class="form-control" id="username" name="perm_desc"  value="<?php echo $permdata['perm_desc']; ?>">
            </div>
            
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="username">Permission Page Url:</label>
              <input type="text" class="form-control" id="username" name="perm_url"  value="<?php echo $permdata['perm_url']; ?>">
            </div>
            
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="status">Status:</label>
              <select class="form-control" id="perm_status" name="perm_status" >
                <option value="Active" <?php if($permdata['status'] == 'Active'){ echo 'selected="selected"'; } ?> >Active</option>
                <option value="Inactive" <?php if($permdata['status'] == 'Inactive'){ echo 'selected="selected"'; } ?> >Inactive</option>
              </select>
            </div>
            
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="username">Permission Order:</label>
              <input type="text" class="form-control" id="username" name="perm_order"  value="<?php echo $permdata['perm_order']; ?>">
            </div>
            
           <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="perm_category">Category:</label>
              <select class="form-control" id="perm_category" name="perm_category" >
              <option>Select Category</option>
              <?php $eporting = $perm->permissioncategory();
			  
			  foreach($eporting as $eportings){
			   ?>
              
                <option value="<?php echo $eportings['id']; ?>" <?php if(in_array($permdata['perm_category'],$eportings)){ echo 'selected="selected"'; } ?> ><?php echo $eportings['name']; ?></option>
                <?php } ?>
                
              </select>
            </div>
            
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <input type="hidden" class="form-control" id="action" name="action"  value="edit">
              <button type="submit" class="btn btn-default btn-success" name="btn-signup">Update Permission</button>
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
							message : '<p>Permission Edited Successfully</p>',
							layout : 'growl',
							effect : 'slide',
							type : 'notice', // notice, warning or error
						});
						// show the notification
						notification.show();
					}, 1200 );
			<?php } ?>
				
	
			});
</script>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>
