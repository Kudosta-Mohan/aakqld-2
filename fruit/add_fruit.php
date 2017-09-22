<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);

if(isset($_POST['btn-signup']))
{ 
  
   $fvname = trim($_POST['fvname']);
   $fruitimgpath = (isset($_POST['fruitimgpath'])) ? $_POST['fruitimgpath'] : '';
   $fruitimgname = (isset($_POST['fruitimgname'])) ? $_POST['fruitimgname'] : ''; 
 
   if($fvname=="") {
      $error[] = "Enter Fruit or Vegetable name !"; 
   }
   if($fruitimgpath == "" ) {
      $error[] = "Enter Fruit or Vegetable Image !"; 
   }
   else
   {  
      try
      {
        if($fruit->insert($fvname,$fruitimgpath,$fruitimgname)) 
        {
            $fruit->redirect('add_fruit.php?joined');
        }
         
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }
  } 
}

if(isset($error)){ 
	$fvname = $fvname;
} else {
	$fvname = '';
}

?>
<?php include ('../header.php');  ?>

<div id="container">
    
  <!-- Page Content -->
    <div class="container">
    
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
<div class="titlesection row">   
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="margin0">Add New Fruit / Vegetable</h1></div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>fruit/fruit.php" title="View fruits / vegetables"><i class="fa fa-tree" aria-hidden="true"></i> View fruits / vegetables</a></div>
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
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully inserted 
                 </div>
            <?php 
				} 
				?>

                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <input type="hidden" class="form-control" id="fruitimgpath" name="fruitimgpath" value="">
                      <input type="hidden" class="form-control" id="fruitimgname" name="fruitimgname" value="">

                      <label for="fvname">Fruit / Vegetable Name:</label>
                      <input type="text" class="form-control" id="fvname" name="fvname" placeholder="Enter Fruit / Vegetable Name Here" value="<?php echo $fvname; ?>">
                   </div>
                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="fileuploaderssequisize" data-upid='upid'>Upload</div>
                   </div>
                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <button type="submit" class="btn btn-default" name="btn-signup">Save</button>
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
							message : '<p>Fruit / Vegetable Added Successfully</p>',
							layout : 'growl',
							effect : 'slide',
							type : 'notice', // notice, warning or error
						});
						// show the notification
						notification.show();
					}, 1200 );
			<?php } ?>
			
	
	 
	 $(".fileuploaderssequisize").uploadFile({
		
    url:"<?php echo home_base_url(); ?>class/class.ajax.php?action=uploadfruitimages",
    fileName:"myfile",
    showDelete: true,
    onSuccess: function (files, response, xhr, pd) {
		//alert(response);
    pd.statusbar.append(response);
    }
    });	
	

	
});
</script>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>