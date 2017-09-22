<?php
require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);
$newarray = array();
if(isset($_POST['btn-signup']) && isset($_POST['action']) && isset($_GET['id']) && $_POST['action'] == 'editpassword')
{ 

   $uid = trim($_POST['user_id']);
   $upass = trim($_POST['password']);
      try
      {

        if($user->updatedpassword($uid,$upass)) 
          {
                $user->redirect('edit_users.php?id='.$uid.'&action=edit&updated=2');
         }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }

}

if(isset($_POST['btn-signup']) && isset($_POST['action']) && isset($_GET['id']) && $_POST['action'] == 'edit')
{ 
   $rows_ids = $_POST['equipment_license_ids'];
   $uid = trim($_POST['user_id']);
   $uname = trim($_POST['username']);
   $umail = trim($_POST['email']);
   //$upass = trim($_POST['password']);
   $fname = trim($_POST['firstname']);
   $lname = trim($_POST['lastname']);
   $uphone = trim($_POST['phone']);
   $status = trim($_POST['status']); 
   $utype = $_POST['usertype'];
   $usersattachmenturl = $_POST['picedit'];
   $usersattachmentname = $_POST['piceditname'];
   //$userslicense = trim($_POST['userdatelicenses']);
   $cust_contactlist = $_POST['equipmentsize'];
   $em_contact = $_POST['em_contact'];
   $picedit_id = $_POST['picedit_id'];
   $username = $uname;
	$em_contact = $em_contact;
	$firstname = $fname;
	$picedit_id = $picedit_id;
	$lastname = $lname;
	$email = $umail;
	$phone = $uphone;
	$utypearrays = $utype;
 //echo "<pre>"; print_r($rows_ids); print_r($usersattachmenturl); echo "</pre>";die('m here');
      try
      {

        if($user->updated($rows_ids,$uid,$fname,$lname,$uname,$umail,$uphone,$status,$utype,$usersattachmenturl,$usersattachmentname,$cust_contactlist,$em_contact,$picedit_id)) 
          {
                $user->redirect('edit_users.php?id='.$uid.'&action=edit&updated=1');
         }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }

}
else if(!isset($_POST['btn-signup']) && isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']))
{
	$userfield = $user->loginuserdata($_GET['id']);

	$userid = $userfield['user_id'];
	$user_names = $userfield['user_name'];
	//$password = $userfield['user_pass'];
	$firstname = $userfield['first_name'];
	$lastname = $userfield['last_name'];
	$email = $userfield['user_email'];
	$phone = $userfield['user_phone'];
	$status = $userfield['status'];
	$ustype = $userfield['type'];
  if(isset($userfield['user_licenses']))
  {
  	$userlicenses = $userfield['user_licenses'];
  	$userlicensess = str_replace('-','/',$userlicenses);
  }
	$user_role_array = json_decode($userfield['user_role']);
	$utypearrays = explode(",",$ustype);
	$emgerncy_phone = json_decode($userfield['emerengecy_contact']);
	$emgerncy_name = json_decode($userfield['emerengecy_name']);
	//echo $_GET['id'].'     &nbsp;'.$username.'<pre>'; print_r($userfield);
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
            <h1 class="margin0">Edit User</h1>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>settings/users.php" title="View Users"><i class="fa fa-users" aria-hidden="true"></i> View Users</a></div>
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
              <label for="username">Username:</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username Here" value="<?php echo $user_names; ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="email">Email:</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email ID Here" value="<?php echo $email; ?>">
            </div>
            <?php /*?><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label for="password">Password:</label>
                      <input type="text" class="form-control" id="password" name="password" placeholder="Enter Password Here" value="<?php echo $password; ?>">
                   </div><?php */?>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="firstname">First Name:</label>
              <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter Firstname Here" value="<?php echo $firstname; ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="lastname">Last Name:</label>
              <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Lastname Here" value="<?php echo $lastname; ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="phone">Phone:</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number Here" value="<?php echo $phone; ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="status">Status:</label>
              <select class="form-control" id="status" name="status" >
                <option value="Active" <?php if($status == 'Active'){ echo 'selected="selected"'; } ?> >Active</option>
                <option value="Inactive" <?php if($status == 'Inactive'){ echo 'selected="selected"'; } ?> >Inactive</option>
              </select>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="em_contact">Emergency Contact 1: Name</label>
              <input type="text" class="form-control" name="em_contact[name][]" placeholder="Enter Emergency Contact Number Here" value="<?php echo $emgerncy_name[0]->name; ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="em_contact">Emergency Contact 1: Phone</label>
              <input type="text" class="form-control" name="em_contact[phone][]" placeholder="Enter Emergency Contact Number Here" value="<?php echo $emgerncy_phone[0]->phone; ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="em_contact">Emergency Contact 2: Name</label>
              <input type="text" class="form-control" name="em_contact[name][]" placeholder="Enter Emergency Contact Number Here" value="<?php echo $emgerncy_name[1]->name; ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="em_contact">Emergency Contact 2: Phone</label>
              <input type="text" class="form-control" name="em_contact[phone][]" placeholder="Enter Emergency Contact Number Here" value="<?php echo $emgerncy_phone[1]->phone; ?>">
            </div>
            <?php $userfiel = $user->getloginusercertificatedata($_GET['id']);
					//echo "<pre>"; print_r($userfiel);	echo "</pre>";
			?>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="myown"> <br />
              <label class="listno">Staff Certificates </label>
              <?php 
		 $i = 1; 
		 if(is_array($userfiel))
   			{
			
			foreach($userfiel as $userfie2) { 
			$current_date = date("d-m-Y");
					  $next_date = str_replace('/','-',$userfie2['expire_date']);
					  $today_time = strtotime($current_date);
					  $expire_time = strtotime($next_date);
					  $datess = date_create($current_date);
					  $datess1 = date_create($next_date);
					  $diff = $datess->diff($datess1);
			 ?>
              <div class="newentry input-group newsieses<?php echo $i;?>">
                <div class="pull-left input-group list_nosss">
                  <input name="equipment_license_ids[]" type="hidden" value="<?php echo $userfie2['id'];?>"/>
                  <input class="form-control" name="equipmentsize[name][]" type="text" placeholder="Name of Certificate"  value="<?php echo $userfie2['licenses_name'];?>"/>
                  <input class="form-control" name="equipmentsize[date][]" type="text" id="dateequi<?php echo $i;?>" placeholder="Expires on" value="<?php echo $userfie2['expire_date'];?>" style=" <?php if($diff->days <= 10 && $expire_time >= $today_time)
						{
							 echo "background:#FF0000;color:#fff";
							 } if($diff->days > 10 && $diff->days <= 30 && $expire_time > $today_time)
							 {
								  echo "background:#FFFF00";
								  }  ?>"  />
                </div>
                <div class="form-group pull-left appendcertificatesequipment list_nosss user_attachment<?php echo $userfie2['id'];?>">
                  <?php if($userfie2['attachment']){ 
          $info = new SplFileInfo($userfie2['attachment']);
$extension = $info->getExtension();
if($extension == 'msg'){
  $imagepath = home_base_url().'images/msg.png';
}elseif($extension == 'pdf' || $extension == 'PDF'){
  $imagepath = home_base_url().'images/pdf.png';
}elseif($extension == 'zip'){
  $imagepath = home_base_url().'images/zip.png';
}elseif($extension == 'docx' || $extension == 'doc'){
  $imagepath = home_base_url().'images/doc.png';
}elseif($extension == 'mov' || $extension == 'MOV'){
  $imagepath = home_base_url().'images/mov.png';
}elseif($extension == 'xls' || $extension =='xlsx'){
  $imagepath = home_base_url().'images/excel.png';
}else{
	$imagepath = home_base_url().$userfie2['attachment'];
	}
	?>
                  <ul>
                    <?php  $asd = explode('/',$userfie2['attachment']);
			 
			 $last_name = end($asd);
			 $first_name = explode('-',$last_name);
			 array_splice($first_name, 0, 1);
			 $image_name = implode('-',$first_name);
			//print_r($value);
			  ?>
                    <li class="list-group-item"> <a href="<?php echo home_base_url().$userfie2['attachment'] ?>" target="_blank"> <img width="60" height="60" src="<?php echo $imagepath; ?>" alt="<?php echo $image_name; ?>"  /> <span class="imagenametext"> <?php echo substr($image_name, 0 , 100); ?></span></a><a href="javascript:;" class="deleteattachmentuser pull-right" data-id="<?php echo $userfie2['id'];?>"><i class="fa fa-close"></i></a>
                      <input type="hidden" class="form-control" id="picedit" name="equipmentsize[<?php echo $userfie2['id'];?>][]"  value="<?php echo $userfie2['attachment']; ?>">
                      <input type="hidden" class="form-control" id="picedit" name="equipmentsize[<?php echo $userfie2['id'];?>][]"  value="<?php echo $userfie2['attachment']; ?>">
                    </li>
                  </ul>
                  <?php } else {?>
                  <div class="fileuploaderssequisize<?php echo $i;?>" data-upid="<?php echo $userfie2['id'];?>">Upload</div>
                  
                  <?php }?>
                </div>
                <span class="pull-right col-lg-1 col-md-1 col-sm-1 col-xs-1 input-group-btn">
                <button type="button" class="btn addnewbtn adduserdata deleteequipmentsizes btn-danger" data-id="<?php echo $userfie2['id'];?>">
                <span class="glyphicon glyphicon-minus">
                <input type="hidden" value="<?php echo $i;?>" class="dltid">
                </span>
                </button>
                </span>
                <div class="clearfix"></div>
              </div>
              <?php  $i++; } } $data = $i; ?>
              <div class="newentry input-group newsieses<?php echo $i;?>">
                <div class="pull-left input-group list_nosss">
                  <input class="form-control" name="equipmentsize[name][]" type="text" placeholder="Name of Certificate"  />
                  <input class="form-control" name="equipmentsize[date][]" type="text" id="dateequi<?php echo $i;?>" placeholder="Expires on"/>
                </div>
                <div class="form-group pull-left appendcertificatesequipment list_nosss">
                  <div class="fileuploaderssequisize" data-upid='upid'>Upload</div>
                </div>
                <span class="pull-right col-lg-1 col-md-1 col-sm-1 col-xs-1 input-group-btn">
                <button class="btn btn-success addequipmentsizes addnewbtn adduserdata" type="button"> <span class="glyphicon glyphicon-plus"></span> </button>
                </span>
                <div class="clearfix"></div>
              </div>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <?php 
				 ?>
              <label for="usertype">User Type:</label>
              <select name="usertype[]" id="usertype" class="form-control multiselect" multiple="multiple">
                <?php 
						$getrole = array();
						for($i=0;$i<count($user_role_array);$i++)
						{
						$user_role = $role->getrole($user_role_array[$i]);
						$getrole[] = $user_role['role_name'];
						}
						
						$user_type = $role->getroleslist();
			 
			  			foreach($user_type as $user_types){
				  
			  			 ?>
                <option value="<?php echo $user_types['role_id']; ?>" <?php 
					
						if (in_array($user_types['role_name'],$getrole) ) {
						echo 'selected="selected"';}  ?>> <?php echo $user_types['role_name']; ?> </option>
                <?php 
				}?>
              </select>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <input type="hidden" class="form-control" id="action" name="action"  value="edit">
              <input type="hidden" class="form-control" id="user_id" name="user_id"  value="<?php echo $userid;?>">
              <button type="submit" class="btn btn-default btn-success" name="btn-signup">Update User</button>
            </div>
          </form>
        </div>
        <hr class="divider">
        <?php 
	$userids = 60;
  if(is_array($newarray) && in_array($userids,$newarray)){ ?>
        <div class="changeuserpassword clearfix">
          <h2>Change Password</h2>
          <form role="form" method="post" id="jobadd" class="">
            <?php
            if(isset($_GET['updated']) && ($_GET['updated'] == 2) )
            {
                 ?>
            <div class="alert alert-info"> <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully Updated Password </div>
            <?php
            }
            ?>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="password">Password:</label>
              <input type="text" class="form-control" id="password" name="password" placeholder="Enter Password Here" value="">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <input type="hidden" class="form-control" id="action" name="action"  value="editpassword">
              <input type="hidden" class="form-control" id="user_id" name="user_id"  value="<?php echo $userid;?>">
              <button type="submit" class="btn btn-default btn-success" name="btn-signup">Update Password</button>
            </div>
          </form>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <!-- /.container --> 
  
</div>
<script type="text/javascript">
// this is  start for add new row 

var c = <?php echo $data;?>; 

jQuery(document).on('click', '.addequipmentsizes', function($)
{
	
	
	jQuery(this).removeClass('addequipmentsizes').addClass('deleteequipmentsizes').removeClass('btn-success').addClass('btn-danger')
	.html('<span class="glyphicon glyphicon-minus"><input class="dltid" type="hidden" value="'+ c +'" /></span>');
	
	c += 1;
	
	jQuery('#myown').append('<div class="newentry input-group newsieses'+ c +'">  <div class="pull-left input-group list_nosss"> <input class="form-control" name="equipmentsize[name][]" type="text" placeholder="Name of Certificate"  />  <input id="dateequi'+ c +'" class="form-control" name="equipmentsize[date][]" type="text" placeholder="Expires on"/>  </div>   <div class="form-group pull-left appendcertificatesequipment list_nosss"> <div class="fileuploaderssequisize'+ c +'" data-upid="upid">Upload</div> </div>  <span class="pull-right col-lg-1 col-md-1 col-sm-1 col-xs-1 input-group-btn">   <button class="btn btn-success addequipmentsizes addnewbtn  adduserdata" type="button">    <span class="glyphicon glyphicon-plus"></span>  </button>  </span>    <div class="clearfix"></div>  </div>');
	
	for (i = 0; i <= c; i++) { 
	jQuery(".fileuploaderssequisize"+i).uploadFile({
		
    url:"http://ablworks.com.au/testelsjobs/class/class.ajax.php?action=equiuploadimages",
    fileName:"myfile",
    showDelete: true,
    onSuccess: function (files, response, xhr, pd) {
    pd.statusbar.append(response);
    }
	});
	
	jQuery('#dateequi'+i).datepicker({
		//startDate: new Date(),
		format: 'dd/mm/yyyy',
		});
		
}
	
});
// this is  ends for add new row 


jQuery(document).ready(function($) {
	
	
	
	var d = <?php echo $data;?>;	
	d += 1;
	for (i = 0; i <= d; i++) { 
		jQuery(".fileuploaderssequisize"+i).uploadFile({
		
    url:"<?php echo home_base_url(); ?>class/class.ajax.php?action=equiuploadimages&eid="+jQuery(".fileuploaderssequisize"+i).attr('data-upid'),
    fileName:"myfile",
    showDelete: true,
    onSuccess: function (files, response, xhr, pd) {
    pd.statusbar.append(response);
    }
	});
	
	jQuery('#dateequi'+i).datepicker({
		//startDate: new Date(),
		format: 'dd/mm/yyyy',
		});
		
}
			<?php 
			 if(isset($_REQUEST['updated'])) {
			?>
					
					setTimeout( function() {
						var notification = new NotificationFx({
							message : '<p>User Edited Successfully</p>',
							layout : 'growl',
							effect : 'slide',
							type : 'notice', // notice, warning or error
						});
						// show the notification
						notification.show();
					}, 1200 );
			<?php } ?>

jQuery('.deleteattachmentuser').click(function(){
		jQuery(this).parent().remove();
		var iduser = jQuery(this).data('id');
		 
		jQuery('.user_attachment'+iduser).prepend('<div class="userattachment'+iduser+'" data-upid="upid">Upload</div>');
	
	jQuery('.userattachment'+iduser).uploadFile({
	url:"<?php echo home_base_url(); ?>class/class.ajax.php?action=equiuploadimages",
	fileName:"myfile",
	showDelete: true,
	onSuccess: function (files, response, xhr, pd) {
	//alert(response);
	pd.statusbar.append(response);
	}
    });
		});	
			
		
	$(".fileuploaderssequisize").uploadFile({
		
    url:"<?php echo home_base_url(); ?>class/class.ajax.php?action=equiuploadimages",
    fileName:"myfile",
    showDelete: true,
    onSuccess: function (files, response, xhr, pd) {
		//alert(response);
    pd.statusbar.append(response);
    }
    });
	
	
jQuery(document).on('click', '.deleteequipmentsizes', function($)
	{
	var id = jQuery(this).data('id');
	var value = jQuery(this).find('.dltid').val();
	jQuery('.newsieses'+value).remove();
	//jQuery(this).parent().parent().remove();
	//alert(value);		
	var data ='action=deleteuserattachment&userattachid='+id;
	jQuery.ajax({
    type: "POST",
    url: '<?php echo home_base_url(); ?>class/class.ajax.php',
    
    data: data,
    cache: false,
    success: function(result){ 
	//alert(result);
	//window.location.href = '<?php echo home_base_url(); ?>equipment/editsize_equipment.php?equi_id=1&equisize_id=8&updated';
	
	}
    });
	});
	jQuery('#dateequi').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});

			});
</script>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>
