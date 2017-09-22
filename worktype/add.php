<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);
if(isset($_POST['btn-signup']))
{ 
  
  $wtname = trim($_POST['wtname']);
  if($wtname=="") {
      $error[] = "Enter Work Type Name !"; 
  }
  else
  {  
    try
    {
      if($setting->insertWorkType($wtname)) 
      {
          $setting->redirect('add.php?joined');
      }
         
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }
  } 
}
?>
<?php include ('../header.php');  ?>

<div id="container">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="titlesection row">   
          <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="margin0">Add New Work Type</h1></div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>settings/worktypes.php" title="View Work Type"><i class="fa fa-eye" aria-hidden="true"></i> View Work Type</a></div>
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
				    }?>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label for="fvname">Work Type Name:</label>
              <input type="text" class="form-control" id="wtname" name="wtname" placeholder="Enter Work Type" value="<?php // echo $fvname; ?>">
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button type="submit" class="btn btn-default" name="btn-signup">Save</button>
            </div>
          </form>
        </div>	
      </div>
    </div>
  </div>
</div>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>