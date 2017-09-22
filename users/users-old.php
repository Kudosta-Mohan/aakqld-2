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
    
  <!-- Page Content -->
    <div class="container">
    
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
<div class="titlesection clearfix">   
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 padding0 equpiment">
   <a class="addusers addplusicon pull-right active" href="<?php echo home_base_url(); ?>users/users.php" title="Users" style="float: none ! important; margin-right: 15px;">Users</a>
 
  <a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>roles/roles.php" title="Add New Role" style="float: none ! important; margin-right: 15px;"> Roles</a>

  <a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>perm/permissions.php" title="Add New Role" style="float: none ! important; margin-right: 15px;"> Permissions</a>
 <h3 class="margin0">Users</h3> <br>&nbsp;<br>
</div>

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 padding0 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>users/add_users.php" title="Add New User"><i class="fa fa-plus" aria-hidden="true"></i> Add Users</a></div>

</div>
<div class="userslistbox clearfix">	

<div class="overflo">

<table class="table tabledata">
    <thead>
      <tr>
      	<th class="usertablecol_1">Username</th>
        <th class="usertablecol_2">Email</th>
        <th class="usertablecol_3">Firstname</th>
        <th class="usertablecol_4">Lastname</th>
        <th class="usertablecol_5">Phone</th>
        <th class="usertablecol_6">User Type</th>
        <th class="usertablecol_7">Status</th>
        <th class="usertablecol_8"></th>
      </tr>
    </thead>
    <tbody>
    
<?php $userfields = $user->getuserslist(); foreach($userfields as $userfield){ ?>  
      <tr>
        <td class="usertablecol_1"><?php echo $userfield['user_name']; ?></td>
        <td class="usertablecol_2"><?php echo $userfield['user_email']; ?></td>
        <td class="usertablecol_3"><?php echo $userfield['first_name']; ?></td>
        <td class="usertablecol_4"><?php echo $userfield['last_name']; ?></td>
        <td class="usertablecol_5"><?php echo $userfield['user_phone']; ?></td>
        <td class="usertablecol_6"><?php echo $userfield['type']; ?></td>
        <td class="usertablecol_7"><?php echo $userfield['status']; ?></td>
        <td class="text-right usertablecol_8"><a href="<?php echo home_base_url(); ?>users/edit_users.php?id=<?php echo $userfield['user_id']; ?>&action=edit" class="edit actionicon"><i class="fa fa-pencil" aria-hidden="true"></i></a><a href="<?php echo home_base_url(); ?>users/delete_users.php?id=<?php echo $userfield['user_id']; ?>&action=delete" class="delete actionicon"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
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