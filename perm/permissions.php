<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);
?>
<?php include ('../header.php');  ?>

<div id="container">
    
  <!-- Page Content -->
    <div class="container">
    
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
<div class="titlesection clearfix">   
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 padding0 equpiment">
<a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>settings/users.php" title="Users" style="float: none ! important; margin-right: 15px;">Users</a>
   
 <a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>roles/roles.php" title="Add New Role" style="float: none ! important; margin-right: 15px;"> Roles</a>
 <a class="addusers addplusicon active" href="<?php echo home_base_url(); ?>perm/permissions.php" title="Permissions" style="float: none !important; margin-right: 15px;">Permissions</a>
 
<br>&nbsp;<br>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 padding0 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>perm/add_perm.php" title="Add New Permission"><i class="fa fa-plus" aria-hidden="true"></i> Add Permission</a></div>
</div>
<div class="userslistbox clearfix">	

<div class="overflo">

<table class="table tabledata">
    <thead>
      <tr>
      	<th class="usertablecol_1">Permision ID</th>
        <th class="usertablecol_2">Permision Name</th>
        <th class="usertablecol_2">Permision Url</th>
        <th class="usertablecol_7">Status</th>
        <th class="usertablecol_8"></th>
      </tr>
    </thead>
    <tbody>
    
<?php $perms = $perm->getpermlist(); foreach($perms as $perm){ ?>  
      <tr>
        <td class="usertablecol_1"><?php echo $perm['perm_id']; ?></td>
        <td class="usertablecol_2"><?php echo $perm['perm_name']; ?></td>
        <td class="usertablecol_2"><?php echo $perm['perm_url']; ?></td>
        <td class="usertablecol_7"><?php echo $perm['status']; ?></td>
        <td class="text-right usertablecol_8"><a href="<?php echo home_base_url(); ?>perm/edit_perm.php?id=<?php echo $perm['perm_id']; ?>&action=edit" class="edit actionicon"><i class="fa fa-pencil" aria-hidden="true"></i></a><a href="<?php echo home_base_url(); ?>perm/delete_perm.php?id=<?php echo $perm['perm_id']; ?>&action=delete" class="delete actionicon"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
      </tr>
<?php } ?>      
    </tbody>
  </table>
  
</div> 
  
  </div>
  </div>
    </div>

        

    </div>
    <!-- /.container -->    
    
</div>

<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>