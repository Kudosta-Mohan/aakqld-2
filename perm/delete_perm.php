<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);

if(isset($_POST['btn-signup']) && isset($_POST['action']) && isset($_GET['id']) && $_POST['action'] == 'delete')
{ 

   $perm_id = $_POST['perm_id'];
 
      try
      {

        if($perm->deleted($perm_id)) 
          {
                $perm->redirect('delete_perm.php?deleted');
         }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }

}
else if(!isset($_POST['btn-signup']) && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']))
{
	$permdata = $perm->getperm($_GET['id']);
	
} 


?>
<?php include ('../header.php');  ?>

<div id="container">
    
  <!-- Page Content -->
    <div class="container">
    
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
<div class="titlesection row">   
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="margin0">Delete Permission Page</h1></div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>perm/permissions.php" title="View Permisssion"><i class="fa fa-users" aria-hidden="true"></i> View Permission</a></div>
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
                    <h1> Are you sure you want to delete <?php echo $permdata['perm_name']; ?>?</h1>
                   </div>
                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center want">
					<input type="hidden" class="form-control" id="action" name="action"  value="delete">
                    <input type="hidden" class="form-control" id="perm_id" name="perm_id"  value="<?php echo $permdata['perm_id'];?>">
                    
                    <a class="btn btn-default" href="<?php echo home_base_url(); ?>perm/permissions.php" >Cancel</a>
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
							message : '<p>Permission Page Deleted Successfully</p>',
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