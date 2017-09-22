<?php
require_once 'dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$jobid = $_REQUEST['id'];
$userdata = $user->loginuserdata($_SESSION['user_session']);
if(isset($_REQUEST['q'])){
		
		$csearch = $_REQUEST['q'];
	
	$jobfields = $customer->searchjobslist($csearch); 
		
	$equipmentlist = $equipment->equipmentlist($csearch); 
	
	$userfields = $customer->searchjobslistuserattachment($csearch); 
		
	
	$attachmentss = $customer->searchjobslistattachment($csearch); 
		
	
	$custfields = $customer->searchcustomerslist($csearch); 
		}
	else{
	$custfields = $customer->getcustomerslist();	
	}
//print_r($custfields );
?>
<?php include ('header.php'); ?>
<div id="container"> 
  
  <!-- Page Content -->
  <div class="container" id="searchjobsss">
    <h3>Showing result that match your query:<b> >> <?php echo $_REQUEST['q'];?> <<</b></h3>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="userslistbox clearfix">
          <div class="overflo">
            <table class="table ">
              <thead class="customer">
                <tr>
                  <th class="custtablecol_1">Customer</th>
                  <th class="custtablecol_2">Email</th>
                  <th class="custtablecol_3">Address</th>
                  <th class="custtablecol_6">Contact</th>
                  <th class="custtablecol_4">View customer</th>
                  <th class="custtablecol_6"> View job history</th>
                </tr>
              </thead>
              <tbody>
                <?php  $count  = 0; if(is_array($custfields)){ foreach($custfields as $custfield){ 
				
				
				?>
                <tr>
                  <td class="custtablecol_1"><?php echo $custfield['cust_name']; ?></td>
                  <td class="custtablecol_2"><?php echo $custfield['cust_email']; ?></td>
                  <td class="custtablecol_3"><?php echo $custfield['cust_address']; ?></td>
                  <td class="custtablecol_5"><?php echo $custfield['cont_name']; ?></td>
                  <td class="text-right custtablecol_6 cust"><?php if(isset($_REQUEST['q'])){ ?>
                    <a href="<?php echo home_base_url(); ?>customers/edit_customer.php?id=<?php echo $custfield['cust_id']; ?>&action=edit" class="actionicon">View customer</a>
                    <?php } ?></td>
                  <td class="text-right custtablecol_6 cust"><?php if(isset($_REQUEST['q'])){ ?>
                    <a href="<?php echo home_base_url(); ?>customers/view_customer_job.php?id=<?php echo $custfield['cust_id']; ?>" class="actionicon">View job history</a>
                    <?php } ?></td>
                </tr>
                <?php $count++; } } 
				
				echo '<h3 class="customersearchresults"><u>'. $count .' results found that match customers</u></h3>';
				?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <br />
     <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="userslistbox clearfix">
          <div class="overflo">
            <table class="table ">
              <thead class="customer">
                <tr>
                  <th class="custtablecol_1">Equipment</th>
                  <th class="custtablecol_5">Equipment Type</th>
                  <th class="custtablecol_2">Equipment Size</th>
                   <th class="custtablecol_6"> View Equipment</th>
                </tr>
              </thead>
              <tbody>
                <?php  $count  = 0; if(is_array($equipmentlist)){ foreach($equipmentlist as $equipmentlists){ 
				
				
				?>
                <tr>
                  <td class="custtablecol_1"><?php echo $equipmentlists['equi_name']; ?></td>
                  <td class="custtablecol_5"><?php echo $equipmentlists['equi_type']; ?></td>
                  <?php $equimentsize = $equipment->equimentsize($equipmentlists['equi_id']); 
				  
				  ?>
                  <td class="custtablecol_2"><?php foreach($equimentsize as $equimentsizes){ ?> <a href="<?php echo home_base_url(); ?>equipment/editsize_equipment.php?equi_id=<?php echo $equimentsizes['equipment_id']; ?>&equisize_id=<?php echo $equimentsizes['id']; ?>&equisize=<?php echo $equimentsizes['equipments_size']; ?>" class="edit actionicon"> <?php echo $equimentsizes['equipments_size'].', ';} ?> </a></td>
                  
                  <td class="text-right custtablecol_6 cust"><?php if(isset($_REQUEST['q'])){ ?>
                    <a href="<?php echo home_base_url(); ?>equipment/edit_equipment.php?id=<?php echo $equipmentlists['equi_id']; ?>&action=edit" class="actionicon">View Equipment</a>
                    <?php } ?></td>
                  
                </tr>
                <?php $count++; } } 
				
				echo '<h3 class="customersearchresults"><u>'. $count .' results found that match equipment</u></h3>';
				?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <br />
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="userslistbox clearfix">
          <div class="overflo">
            <table class="table ">
              <thead class="jobss">
                <tr>
                  <th class="custtablecol_1">Client</th>
                  <th class="custtablecol_2">Job Address</th>
                  <th class="custtablecol_2">Job Detail</th>
                  <th class="custtablecol_4">Contact</th>
                   <th class="custtablecol_4">Equipment</th>
                  <th class="custtablecol_5">Invoice</th>
                  <th class="custtablecol_1">Job Date/Time</th>
                  <th class="custtablecol_5">View Job</th>
                </tr>
              </thead>
              <tbody>
                <?php 
				$count = 0;
				 if(is_array($jobfields)){ foreach($jobfields as $custfield){ ?>
                <tr>
                  <td class="custtablecol_5"><?php echo $custfield['cust_name']; ?></td>
                  <td class="custtablecol_2"><?php echo $custfield['job_address']; ?></td>
                  <td class="custtablecol_3"><?php echo $custfield['job_detail']; ?></td>
                  <td class="custtablecol_5"><?php echo $custfield['cont_name']; ?></td>
                  <td class="custtablecol_5"><?php echo $custfield['equi_name']; ?></td>
                  <td class="custtablecol_5"><?php echo $custfield['job_invoice']; ?></td>
                  <td class="custtablecol_5"><?php echo $custfield['job_date'].'&nbsp;'.$custfield['job_time']; ?></td>
                  <td class="text-right custtablecol_6 cust"><?php if(isset($_REQUEST['q'])){ ?>
                    <a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $custfield['id']; ?>" class="actionicon">View Job</a>
                    <?php } ?></td>
                </tr>
                <?php $count++; } } 
				
				echo '<h3 class="jobssearchresults"><u>'. $count .' results found that match jobs</u></h3>'
				?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    
    
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="userslistbox clearfix">
          <div class="overflo">
            <table class="table ">
              <thead class="jobss">
                <tr>
                  <th class="custtablecol_1">User Name</th>
                  <th class="custtablecol_2">First Name</th>
                  <th class="custtablecol_3">Last Name</th>
                  <th class="custtablecol_5">View User</th>
                </tr>
              </thead>
              <tbody>
                <?php 
				$count = 0;
				 if(is_array($userfields)){ foreach($userfields as $userfiel){ ?>
                <tr>
                  <td class="custtablecol_1"><?php echo $userfiel['user_name'];?></td>
                  <td class="custtablecol_2"><?php echo $userfiel['first_name']; ?></td>
                  <td class="custtablecol_3"><?php echo $userfiel['last_name']; ?></td>
                  <td class="text-right custtablecol_6 cust"><?php if(isset($_REQUEST['q'])){ ?>
                    <a href="<?php echo home_base_url(); ?>users/edit_users.php?id=<?php echo $userfiel['user_id']; ?>" class="actionicon">View Job</a>
                    <?php } ?></td>
                </tr>
                
                <?php $count++; } } 
				echo '<h2 class="jobssearchresults"><u> Other results</u></h2>';
				echo '<h4 class="jobssearchresults"><u>'. $count .' Users results</u></h4>'
				?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    
  <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="userslistbox clearfix">
          <div class="overflo">
            <table class="table ">
              <thead class="jobss">
                <tr>
                  <th class="custtablecol_1">Client</th>
                  <th class="custtablecol_2">Job Address</th>
                  <th class="custtablecol_5">Job Detail</th>
                  <th class="custtablecol_3">Job Attachments</th>
                  <th class="custtablecol_5">View Job</th>
                </tr>
              </thead>
              <tbody>
                <?php 
				$count = 0;
				 if(is_array($attachmentss)){ foreach($attachmentss as $attachme){ 
				  ?>
                     
                <tr>
                  <td class="custtablecol_1"><?php echo $attachme['cust_name']; ?></td>
                  <td class="custtablecol_2"><?php echo $attachme['job_address']; ?></td>
                  <td class="custtablecol_3"><?php echo $attachme['job_detail']; ?></td>
                  <td class="custtablecol_1">
                <?php $jobattachments = json_decode($attachme['job_attachments']);
						
					  	if(is_array($jobattachments)){ foreach($jobattachments as $jobattachment){
							
$info = new SplFileInfo($jobattachment->imagepath);
$extension = $info->getExtension();
if($extension == 'msg'){
	$imagepath = home_base_url().'images/msg.png';
}elseif($extension == 'pdf'){
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
	$imagepath = home_base_url().$jobattachment->imagepath;
	}
							
					  ?>
                  
                 <a class="attachmentimagfhgesbox" href="<?php echo home_base_url().$jobattachment->imagepath; ?>" target="_blank"><?php echo $jobattachment->imagename; ?></a><br /> 
                     <?php } } ?>
                     </td>
                  <td class="text-right custtablecol_6 cust"><?php if(isset($_REQUEST['q'])){ ?>
                    <a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $attachme['id']; ?>" class="actionicon">View Job</a>
                    <?php } ?></td>
                </tr>
                <?php $count++; } } 
				
				echo '<h4 class="jobssearchresults"><u>'. $count .' Attachment results</u></h4>'
				?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>  
    
  </div>
</div>
<!-- /.container -->
<?php  
include ('footer.php');  ?>
<?php include ('footer-bottom.php');  ?>
