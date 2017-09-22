<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);


if(isset($_POST['btn-signup']) && isset($_POST['action']) && isset($_GET['id']) && $_POST['action'] == 'edit')
{ 
  
    $fruit_id = $_POST['fruit_id'];
    $fvname = $_POST['fvname'];
    $fruitimgpath = $_POST['fruitimgpath'];
    $fruitimgname = $_POST['fruitimgname'];
    $status = $_POST['status'];
   
      try
      {

        if($fruit->updated($fruit_id,$fvname,$fruitimgpath,$fruitimgname,$status)) 
          {
                $user->redirect('edit_fruit.php?id='.$fruit_id.'&action=edit&updated=1');
         }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }

}
else if(!isset($_POST['btn-signup']) && isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']))
{
	$fruitfield = $fruit->getfruitbyid($_GET['id']);

 
	$fruitid = $fruitfield['fruit_id'];
	$fvname = $fruitfield['fruit_name'];
	$fimgpath = $fruitfield['fruit_image_path'];
	$fimgname = $fruitfield['fruit_image_name'];
	$status = $fruitfield['status'];
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
            <h1 class="margin0">Edit Fruit</h1>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>fruit/fruit.php" title="View fruits / vegetables"><i class="fa fa-tree" aria-hidden="true"></i> View fruits / vegetables</a></div>
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
              <label for="username">name:</label>
              <input type="text" class="form-control" id="fvname" name="fvname" placeholder="Enter Fruit / vegetable name Here" value="<?php echo $fvname; ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="status">Status:</label>
              <select class="form-control" id="status" name="status" >
                <option value="Active" <?php if($status == 'Active'){ echo 'selected="selected"'; } ?> >Active</option>
                <option value="Inactive" <?php if($status == 'Inactive'){ echo 'selected="selected"'; } ?> >Inactive</option>
              </select>
            </div>
            <?php if($fimgpath){?>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <img src="<?php echo home_base_url(); ?><?php echo $fimgpath; ?>" alt="<?php echo $fimgname; ?>" style="max-width: 35px; max-height: 35px;">
                  <input type="hidden" name="fruitimgpath" value="<?php echo $fimgpath; ?>">
                  <input type="hidden" name="fruitimgname" value="<?php echo $fimgname; ?>">
              </div>
              <div class="form-group col-lg-10 col-md-10 col-sm-10 col-xs-12">  
                <div class="fileuploaderssequisize" data-upid='upid'>Upload</div>
              </div>  
            </div>
            <?php } else { ?>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="fileuploaderssequisize" data-upid='upid'>Upload</div>
            </div>
            <?php } ?>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <input type="hidden" class="form-control" id="action" name="action"  value="edit">
              <input type="hidden" class="form-control" id="fruit_id" name="fruit_id"  value="<?php echo $fruitid;?>">
              <button type="submit" class="btn btn-default btn-success" name="btn-signup">Update</button>
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
							message : '<p>Fruit / Vegetable Edited Successfully</p>',
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
