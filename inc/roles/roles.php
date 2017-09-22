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
   <a class="addusers addplusicon active" href="<?php echo home_base_url(); ?>roles/roles.php" title="Roles" style="float: none !important; margin-right: 15px;">Roles</a>
 <!-- <h3 class="margin0">Users</h3> <br>&nbsp;<br>-->
<a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>perm/permissions.php" title="Add New Role" style="float: none ! important; margin-right: 15px;"> Permissions</a>

 <!-- <h3 class="margin0">Users</h3> --><br>&nbsp;<br>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 padding0 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>roles/add_role.php" title="Add New Role"><i class="fa fa-plus" aria-hidden="true"></i> Add Role</a></div>
</div>
<div class="userslistbox clearfix">	

<div class="overflo">

<table class="table tabledata">
    <thead>
      <tr>
      	<th class="usertablecol_1">Role ID</th>
        <th class="usertablecol_2">Role Name</th>
        <th class="usertablecol_7">Status</th>
        <th class="usertablecol_8"></th>
      </tr>
    </thead>
    <tbody>
    
<?php $roles = $role->getroleslist(); foreach($roles as $role){ ?>  
      <tr>
        <td class="usertablecol_1"><?php echo $role['role_id']; ?></td>
        <td class="usertablecol_2"><?php echo $role['role_name']; ?></td>
        <td class="usertablecol_7"><?php echo $role['status']; ?></td>
        <td class="text-right usertablecol_8"><a href="<?php echo home_base_url(); ?>roles/edit_role.php?id=<?php echo $role['role_id']; ?>&action=edit" class="edit actionicon"><i class="fa fa-pencil" aria-hidden="true"></i></a><a href="<?php echo home_base_url(); ?>roles/delete_role.php?id=<?php echo $role['role_id']; ?>&action=delete" class="delete actionicon"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
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