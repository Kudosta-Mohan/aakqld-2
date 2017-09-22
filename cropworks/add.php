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
          <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="margin0">Add New Crop Work</h1></div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>settings/worktypes.php" title="View Work Type"><i class="fa fa-eye" aria-hidden="true"></i> View Crop Work</a></div>
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
              <label for="fvname">Select Farm:</label>
              <select name="farm_name" class="form-control">
                <option value="">Select...</option>
                <?php $farms_value= $farms->getfarmslist(); foreach($farms_value as $farm){?>
                <option value="<?php echo $farm['id']; ?>"><?php echo $farm['name']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label for="fvname">Select Block:</label>
              <select name="farm_block" id="farm_block" class="form-control">
              </select>
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
<script type="text/javascript">
$('select[name="farm_name"]').on('change', function(){    
  var fid = $(this).val();
  jQuery.ajax({
    type: "POST",
    url: '<?php echo home_base_url(); ?>class/class.ajax.php?action=getFarmBlocks',
    data: {'fid': fid},
    cache: false,
    dataType: 'json',
    success: function(result)
    { 
      var newhm = '<option value="">Select...</option>';
      var i = 1;
      $.each(result, function(key, val){
        newhm += '<option value="'+val.id+'">Block '+i+'</option>';
        i++;
      }); 
      $('#farm_block').html(newhm);
    }
  });
});
</script>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>