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
  <h3 class="margin0">Fruits / Vegetables</h3> <br>&nbsp;<br>
</div>

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 padding0 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>fruit/add_fruit.php" title="Add New Fruit/Vegetable"><i class="fa fa-plus" aria-hidden="true"></i> Add Fruits/Vegetables</a></div>

</div>
<div class="userslistbox clearfix">	

<div class="overflo">

<table class="table tabledata">
    <thead>
      <tr>
      	<th class="usertablecol_1">Name</th>
        <th class="usertablecol_2">Image</th>
         <th class="usertablecol_7">Status</th>
        <th class="usertablecol_8"></th>
      </tr>
    </thead>
    <tbody>
    
<?php $fruitfields = $fruit->getfruitslist(); foreach($fruitfields as $fruitfield){ ?>  
      <tr>
        <td class="usertablecol_1"><?php echo $fruitfield['fruit_name']; ?></td>
        <td class="usertablecol_2">
          <img src="<?php echo home_base_url(); ?><?php echo $fruitfield['fruit_image_path']; ?>" alt="<?php echo $fruitfield['fruit_image_name']; ?>" style="max-width: 35px; max-height: 35px;" /></td>
        <td class="usertablecol_7"><?php echo $fruitfield['status']; ?></td>
        <td class="text-right usertablecol_8"><a href="<?php echo home_base_url(); ?>fruit/edit_fruit.php?id=<?php echo $fruitfield['fruit_id']; ?>&action=edit" class="edit actionicon"><i class="fa fa-pencil" aria-hidden="true"></i></a><a href="<?php echo home_base_url(); ?>fruit/delete_fruit.php?id=<?php echo $fruitfield['fruit_id']; ?>&action=delete" class="delete actionicon"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
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