<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);

if(isset($_POST['btn-signup']) && isset($_POST['action']) && isset($_GET['id']) && $_POST['action'] == 'delete')
{ 

   $role_id = $_POST['role_id'];
 
      try
      {

        if($role->deleted($role_id)) 
          {
                $role->redirect('delete_role.php?deleted');
         }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }

}
else if(!isset($_POST['btn-signup']) && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']))
{
	$rolexds = $role->getrole($_GET['id']);
	$role_name = $rolexds['role_name'];
	
} 


?>
<?php include ('../header.php');  ?>

<div id="container">
    
  <!-- Page Content -->
    <div class="container">
    
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
<div class="titlesection row">   
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="margin0">Delete Role</h1></div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>roles/roles.php" title="View Role"><i class="fa fa-users" aria-hidden="true"></i> View Role</a></div>
</div>

<div class="addnewuserform">

 <form role="form" method="post" id="jobadd" class="">
 
 
  		 <?php
            if(isset($_GET['deleted']))
            {
                 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully Deleted
                 </div>
                 <?php
            } else {
            ?>

                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center want">
                    <h1> Are you sure you want to delete <?php echo $role_name; ?>?</h1>
                   </div>
                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center want">
					<input type="hidden" class="form-control" id="action" name="action"  value="delete">
                    <input type="hidden" class="form-control" id="role_id" name="role_id"  value="<?php echo $rolexds['role_id'];?>">
                    
                    <a class="btn btn-default" href="<?php echo home_base_url(); ?>roles/roles.php" >Cancel</a>
                    <button type="submit" class="btn btn-default" name="btn-signup">Delete Role</button>
                   </div>
                   
                <?php } ?>   
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
			 if(isset($_REQUEST['deleted'])) {
			?>
					
					setTimeout( function() {
						var notification = new NotificationFx({
							message : '<p>Role Deleted Successfully</p>',
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