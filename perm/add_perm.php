<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);

if(isset($_POST['btn-signup']))
{ 

    $perm_name = $_POST['perm_name'];
    $perm_desc = $_POST['perm_desc'];
    $perm_url = $_POST['perm_url'];
    $perm_order = $_POST['perm_order'];
	$perm_category = $_POST['perm_category'];
   
   if($perm_name=="") {
      $error[] = "Enter permission page name !"; 
   }
   if($perm_url=="") {
      $error[] = "Enter permission page url !"; 
   }
   if($perm_category=="") {
      $error[] = "Please select permission category !"; 
   }
   else
   {
      try
      {

		 $stmt = $DB_con->prepare("SELECT perm_name,perm_url FROM permissions WHERE perm_name=:perm_name OR perm_url=:perm_url");
         $stmt->execute(array(':perm_name'=>$perm_name, ':perm_url'=>$perm_url));
         $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
         if($row['perm_name']==$perm_name) {
            $error[] = "sorry permission page already taken !";
         }
         else if($row['perm_url']==$perm_url) {
            $error[] = "sorry permission page url already taken !";
         }
         else
         {
            if($perm->insert($perm_name,$perm_desc,$perm_url,$perm_order,$perm_category)) 
            {
                $perm->redirect('add_perm.php?joined');
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
	$perm_name = $_POST['perm_name'];
    $perm_desc = $_POST['perm_desc'];
    $perm_url = $_POST['perm_url'];
    $perm_order = $_POST['perm_order'];
} else {
	$perm_name = '';
    $perm_desc = '';
    $perm_url = '';
    $perm_order = '';
	}
	
	
?>
<?php include ('../header.php');  ?>

<div id="container">
    
  <!-- Page Content -->
    <div class="container">
    
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
<div class="titlesection row">   
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="margin0">Add New Permission</h1></div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>perm/permissions.php" title="View Permission"><i class="fa fa-users" aria-hidden="true"></i> View Permission</a></div>
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
            
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="username">Permission Page Name:</label>
              <input type="text" class="form-control" id="username" name="perm_name"  value="<?php echo $perm_name; ?>">
            </div>
            
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="username">Permission Page Description:</label>
              <input type="text" class="form-control" id="username" name="perm_desc"  value="<?php echo $perm_desc; ?>">
            </div>
            
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="username">Permission Page Url:</label>
              <input type="text" class="form-control" id="username" name="perm_url"  value="<?php echo $perm_url; ?>">
            </div>
            
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="username">Permission Order:</label>
              <input type="text" class="form-control" id="username" name="perm_order"  value="<?php echo $perm_order; ?>">
            </div>
           
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="perm_category">Category:</label>
             <select class="form-control" id="perm_category" name="perm_category" >
              <option value="">Select Category</option>
              <?php $addeporting = $perm->permissioncategory();
			  
			  foreach($addeporting as $addeportings){
			   ?>
              
                <option value="<?php echo $addeportings['id']; ?>" ><?php echo $addeportings['name']; ?></option>
                <?php } ?>
                
              </select>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button type="submit" class="btn btn-default btn-success" name="btn-signup">Add Permission Page</button>
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
							message : '<p>Permission Page Added Successfully</p>',
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