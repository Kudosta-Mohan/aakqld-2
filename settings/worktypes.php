<?php
require_once '../dbconfig.php';
if($user->is_loggedin()=="")
{
  $user->redirect('index.php');
}
include ('../header.php');
$userdata = $user->loginuserdata($_SESSION['user_session']);
?>
<div id="container">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="titlesection clearfix">
          <div class="tabbing-section">
            <ul>
                <li><a href="<?php echo home_base_url(); ?>/settings/general.php">Fruits / Vegetables</a></li>
                <li><a href="<?php echo home_base_url(); ?>/settings/worktypes.php" class="active">Work Types</a></li>
                <li><a href="<?php echo home_base_url(); ?>/settings/cropworks.php">Crop Work</a></li>
            </ul>
          </div>   
          <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 padding0 equpiment">
            <h3 class="margin0">Work Types</h3> <br>&nbsp;<br>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 padding0 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>worktype/add.php" title="Add Type"><i class="fa fa-plus" aria-hidden="true"></i> Add Type</a></div>
        </div>
        <div class="alert alert-info alert-remove" style="display:none;">
            <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully removed 
          </div>
        <div class="userslistbox clearfix">	
          <div class="overflo">
            <table class="table tabledata">
              <thead>
                <tr>
                	<th class="usertablecol_1">Name</th>
                  <th class="usertablecol_8"></th>
                </tr>
              </thead>
              <tbody>
              <?php $worktypefields = $setting->getWorkTypeList(); foreach($worktypefields as $work){ ?>  
                <tr id="work-<?php echo $work['id']; ?>">
                  <td class="usertablecol_1"><?php echo $work['type_name']; ?></td>
                  <td class="text-right usertablecol_8"><a href="<?php echo home_base_url(); ?>worktype/edit.php?id=<?php echo $work['id']; ?>&action=edit" class="edit actionicon"><i class="fa fa-pencil" aria-hidden="true"></i></a><a href="javascript:void(0)" class="delete actionicon" onclick="deleteWrokType('<?php echo $work['id']; ?>');"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                </tr>
              <?php } ?>      
              </tbody>
            </table>  
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function deleteWrokType(id)
{
  var con = confirm('Are you sure to Delete this Type!');
  if(con == true)
  {
    jQuery.ajax({
      type: "POST",
      url: '<?php echo home_base_url(); ?>class/class.ajax.php?action=removeWorkType',
      data: {'id': id},
      cache: false,
      success: function(result){ 
        $('#work-'+id).remove();
        $('.alert-remove').show();
      }
    });
  }
}
</script>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>