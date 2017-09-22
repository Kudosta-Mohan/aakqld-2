<?php
	require_once 'dbconfig.php';
	
	if($user->is_loggedin()=="")
	{
	$user->redirect('index.php');
	}
	
	
	include ('header.php'); ?>
<link href="<?php echo home_base_url(); ?>css/jquery.bxslider.css" rel="stylesheet" />
<?php
	$date = isset($_GET['date']) ? $_GET['date'] : date('d/m/Y'); 
	$date = str_replace('/', '-', $date);
	
	$prev_date = date('d/m/Y', strtotime($date .' -1 day'));
	$next_date = date('d/m/Y', strtotime($date .' +1 day')); 
	$current_date = date('d/m/Y', strtotime($date));
	$current_day = date('l - F jS Y', strtotime($date));
	
	/*$equipments = $equipment->getequipmentlist();
	$equi_count = count($equipments);
	$columnwidth = (100/$equi_count);
	*/
	
	/*$date1 = '25/05/2016 7:00 AM';
	echo $date1;
	$date1 = str_replace('/', '-', $date1);
	echo strtotime($date1);
	echo '<br>';
	$date2 = '25/05/2016 7:01 AM';
	echo $date2;
	$date2 = str_replace('/', '-', $date2);
	echo strtotime($date2);
	*/?>

<div id="container"> 
  
  <!-- Page Content -->
  <div class="container-fluid">
    <div class="row marginbottom20">
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 yesterday"> <a class="btn btn-default prev_day" href="<?php echo home_base_url(); ?>home.php?date=<?php echo $prev_date; ?>">&laquo; Yesterday</a> </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center"> <span class="currentday currentdiv"><?php echo $current_day; ?></span> <span class="currentdate currentdiv"><a href="javascript:;" id="fullcalendershow"><?php echo $current_date; ?></a> <br>
        <small><i><a href="<?php echo home_base_url(); ?>mapdashboard.php">Month View</a></i></small>
        <input type="hidden" id="locationurl" name="locationurl" value="<?php echo home_base_url(); ?>home.php?date="  />
        <div id="datepicker" class="fullcalenderdisplay" style="display:none"> <a href="javascript:;" id="fullcalenderhide" class="closebutton"><i class="glyphicon glyphicon-remove"></i></a>
          <input type="hidden" id="selecteddate" name="date" class="form-control" value="<?php echo $current_date; ?>" />
        </div>
        </span> </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right tomorrow"> <a class="btn btn-default next_day" href="<?php echo home_base_url(); ?>home.php?date=<?php echo $next_date; ?>">Tomorrow &raquo;</a> </div>
    </div>
    <div id="weather"></div>
    <div class="row marginbottom20 customwidthouter">
      <?php
	$totaljob = 0;
	$i = 0;
	$eu = 0;
	
	$equipments1 = $equipment->getequipmentlist();
	$loginuserf_name = $userdata['first_name'];
	$loginuserl_name = $userdata['last_name'];
    foreach($equipments1 as $equipment2)
	{
		$eqlistarr[] = strtolower($equipment2['equi_name']);
		$eqlistvaluess[strtolower($equipment2['equi_name'])] = $equipment2['equi_id'] ;
	}
	if(in_array(strtolower($loginuserf_name),$eqlistarr)){
    $eqlistvaluess[strtolower($loginuserf_name)];
	$jobsbycranetypes =  $job->getjobbycranetypelist($eqlistvaluess[strtolower($loginuserf_name)],$current_date); 
	$jobsdata = count($jobsbycranetypes);
	if(is_array($jobsbycranetypes))
	{ 
	if($eu%6==0){ echo '<div class="clearfix"></div>'; } /*if($eu%12==0){ echo '<div class="clearfix"></div>'; }*/ $eu++
	?>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 marginbottom20">
        <?php if($jobsdata > 0){ ?>
        <div class="equinamesection text-center"><?php echo $loginuserf_name; ?></div>
        <?php }
	
	foreach($jobsbycranetypes as $jobdetail)
	{
		$jobsequi_size1 = $equipment->jobsequisize($jobdetail['job_equi_size']);
		if($jobsequi_size1[0]['equipments_size'] == $loginuserl_name)
		{
			$customers = $customer->customerdata($jobdetail['job_clie_id']);
			$editby = $jobdetail['job_completedby'];
			$sequence_id = $jobdetail['sequence_id'];
			$jobstatus_billed = $jobdetail['job_status_billed'];
			$jobstatus_processed = $jobdetail['job_status_processed'];
			$contactdata = $customer->gecontactdata($jobdetail['job_cont_name']);
	?>
        <div class="jobcontentbox clearfix  <?php if($jobstatus == 'Billed' || $jobstatus_billed == '1' || $jobstatus_processed == '1'){ echo "billeddiv"; }?>">
          <?php if($sequence_id != 0){ ?>
          <a class="sequencelink" href="<?php echo home_base_url(); ?>sequence_job.php?sequencejobid=<?php echo $sequence_id; ?>" target="_blank"> <img src="<?php echo home_base_url(); ?>images/link-5.png" alt="ELS" class="img-responsive"> </a>
          <?php } ?>
          <?php if($jobstatus_billed == '1'){ ?>
          <div class="billledleftbox">BILLED</div>
          <?php }  
	
	if($jobstatus_processed == '1'){
	?>
          <div class="processedleftbox process">PROCESSED</div>
          <?php } 
		  
		  $attachment = json_decode($jobdetail['job_attachments']);
		  //print_r($attachment);
	?>
          <div class="jobcontentbox_inner as-<?php echo $jobdetail['id']; ?>">
            <div><b>Job id:</b> <?php echo $jobdetail['id']; ?></div>
            <div><b>Client:</b> <?php echo $customers['cust_name']; ?></div>
            <div><b>Time:</b> <?php echo $jobdetail['job_time']; ?></div>
            <div><b>Address:</b> <?php echo substr($jobdetail['job_address'], 0,18); ?>...</div>
            <?php $jobsequi_size = $equipment->jobsequisize($jobdetail['job_equi_size']);
				foreach($jobsequi_size as $jobsequi_sizes){
				?>
            <div><b>Crane Type:</b> <?php echo $jobsequi_sizes['equipments_size']; ?></div>
            <?php } ?>
            <div><b>Contact:</b> <?php echo $contactdata['cont_name']; ?> - <?php echo $contactdata['cont_phone']; ?></div>
            <div><b>Details:</b> <?php echo substr($jobdetail['job_detail'], 0, 18); ?>...</div>
            <div><b>Completed By: </b> <span>
              <?php $completedby_processed = $user->loginuserdata($jobdetail['job_processed_user_name']);
			$completedby_billed = $user->loginuserdata($jobdetail['job_billed_user_name']);
			?>
              <strong>
              <?php if($jobstatus_processed == 1){ echo $completedby_processed['first_name'].' '.$completedby_processed['last_name'];  ?>
              </strong> mark as processed, <strong>
              <?php } if($jobstatus_billed == 1){ echo $completedby_processed['first_name'].' '.$completedby_processed['last_name']; ?>
              </strong> mark as billed
              <?php } ?>
              </span></div>
          </div>
          <div class="text-right jobmenubottom clearfix">
            <div class="viewjobbox pull-left hideprint"><a title="<?php $opuserids = json_decode($jobdetail['job_oper_id']);
	if(is_array($opuserids)){ foreach($opuserids as $opuserid){
	$operators = $user->loginuserdata($opuserid);?>
	<?php echo 'Operator: ' . $operators['user_name']; ?><?php } } ?><?php $userids = json_decode($jobdetail['job_dogm_id']);
	if(is_array($userids)){ foreach($userids as $userid){
	$dogmans = $user->loginuserdata($userid);?><?php echo ' Dogman: ' . $dogmans['user_name']; ?>,
	<?php } } ?>
	
	" href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">View</a></div>
            <?php 
	if($jobstatus_billed == '1'){ 
	$usereditby = $user->loginuserdata($editby);
	$fletter = substr($usereditby['first_name'], 0, 1);
	$lletter = substr($usereditby['last_name'], 0, 1);
	?>
            <div class="ahbox"><?php echo $fletter.$lletter; ?></div>
            <?php } ?>
            <?php  
			$j = 0;
			foreach($attachment as $attahments_image)
		  		{  
		  			$a = $attahments_image->imagename;
					$info = new SplFileInfo($attahments_image->imagename);
$extension = $info->getExtension();
//echo $extension;
		 		if (strpos($a, 'SWMS') !== false && $j ==0 && $extension == 'pdf' || $extension == 'PDF'){
		  		?>
            <div class="swms">SWMS</div>
            <?php $j++;} } ?>
            <div class="menujoblist">=</div>
            <div class="dropmenuouter">
              <?php if($jobstatus_processed == '1')
				{ ?>
              <div class="Un-MarkasProcessed"><a href="#unmarkProcessed" class="markprocessedbtn" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Un-Mark as Processed</a></div>
              <?php 
				} 
			  else 
				{ ?>
              <div class=""><a href="#markProcessed" class="markprocessedbtn" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Mark as Processed</a></div>
              <?php }
			    if($jobstatus_billed == '1')
					{ ?>
              <div class=""><a href="#unmarkBilled" class="markprocessedbtnbilled" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-invoice="<?php echo $jobdetail['job_invoice']?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Un-Mark as Billed</a></div>
              <?php 
					 }
			  	else
					{ ?>
              <div class=""><a href="#markBilled" class="markprocessedbtnbilled" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-invoice="<?php echo $jobdetail['job_invoice']?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Mark as Billed</a></div>
              <?php }  ?>
              <div class=""><a href="<?php echo home_base_url(); ?>job/extend_job.php?id=<?php echo $jobdetail['id']; ?>&action=edit" class="markprocessedbtnbilled" >Extend Job</a></div>
              <div class="hideprint"><a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">Print Job</a></div>
            </div>
          </div>
        </div>
        <?php $i++; } } ?>
      </div>
      <?php
	$totaljob = $i;} 
    /////////////// end dddddddd ////////////////
	} 
	else 
	{
    ////////////////////  start //////////////////////
	foreach($equipments1 as $equipment2)
	{
		$jobsbycranetypes =  $job->getjobbycranetypelist($equipment2['equi_id'],$current_date); 
		if(is_array($jobsbycranetypes))
		{ 
		if($eu%6==0){ echo '<div class="clearfix"></div>'; } /*if($eu%12==0){ echo '<div class="clearfix"></div>'; }*/ $eu++
	?>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 marginbottom20">
        <div class="equinamesection text-center"><?php echo $equipment2['equi_name']; ?></div>
        <?php
	
		foreach($jobsbycranetypes as $jobdetail)
		{
			$customers = $customer->customerdata($jobdetail['job_clie_id']);
			$editby = $jobdetail['job_completedby'];
			$sequence_id = $jobdetail['sequence_id'];
			$jobstatus_billed = $jobdetail['job_status_billed'];
			$jobstatus_processed = $jobdetail['job_status_processed'];
			$contactdata = $customer->gecontactdata($jobdetail['job_cont_name']);
	
	?>
        <div class="jobcontentbox clearfix  <?php if($jobstatus == 'Billed' || $jobstatus_billed == '1' || $jobstatus_processed == '1'){ echo "billeddiv"; }?>">
          <?php if($sequence_id != 0){ ?>
          <a class="sequencelink" href="<?php echo home_base_url(); ?>sequence_job.php?sequencejobid=<?php echo $sequence_id; ?>" target="_blank"> <img src="<?php echo home_base_url(); ?>images/link-5.png" alt="ELS" class="img-responsive"> </a>
          <?php } ?>
          <?php if($jobstatus_billed == '1'){ ?>
          <div class="billledleftbox">BILLED</div>
          <?php }  
	
	if($jobstatus_processed == '1'){
	?>
          <div class="processedleftbox process">PROCESSED</div>
          <?php } 
		  
		  $attachment = json_decode($jobdetail['job_attachments']);
		  //print_r($attachment);
	?>
          <div class="jobcontentbox_inner as-<?php echo $jobdetail['id']; ?>">
            <div><b>Job id:</b> <?php echo $jobdetail['id']; ?></div>
            <div><b>Client:</b> <?php echo $customers['cust_name']; ?></div>
            <div><b>Time:</b> <?php echo $jobdetail['job_time']; ?></div>
            <div><b>Address:</b> <?php echo substr($jobdetail['job_address'], 0,18); ?>...</div>
            <?php $jobsequi_size = $equipment->jobsequisize($jobdetail['job_equi_size']);
				foreach($jobsequi_size as $jobsequi_sizes){
				?>
            <div><b>Crane Type:</b> <?php echo $jobsequi_sizes['equipments_size']; ?></div>
            <?php } ?>
            <div><b>Contact:</b> <?php echo $contactdata['cont_name']; ?> - <?php echo $contactdata['cont_phone']; ?></div>
            <div><b>Details:</b> <?php echo substr($jobdetail['job_detail'], 0, 18); ?>...</div>
            <div><b>Completed By: </b> <span>
              <?php $completedby_processed = $user->loginuserdata($jobdetail['job_processed_user_name']);
			$completedby_billed = $user->loginuserdata($jobdetail['job_billed_user_name']);
			?>
              <strong>
              <?php if($jobstatus_processed == 1){ echo $completedby_processed['first_name'].' '.$completedby_processed['last_name'];  ?>
              </strong> mark as processed, <strong>
              <?php } if($jobstatus_billed == 1){ echo $completedby_processed['first_name'].' '.$completedby_processed['last_name']; ?>
              </strong> mark as billed
              <?php } ?>
              </span></div>
          </div>
          <div class="text-right jobmenubottom clearfix">
            <div class="viewjobbox pull-left hideprint"><a title="<?php $opuserids = json_decode($jobdetail['job_oper_id']);
	if(is_array($opuserids)){ foreach($opuserids as $opuserid){
	$operators = $user->loginuserdata($opuserid);?>
	<?php echo 'Operator: ' . $operators['user_name']; ?><?php } } ?><?php $userids = json_decode($jobdetail['job_dogm_id']);
	if(is_array($userids)){ foreach($userids as $userid){
	$dogmans = $user->loginuserdata($userid);?><?php echo ' Dogman: ' . $dogmans['user_name']; ?>,
	<?php } } ?>
	
	" href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">View</a></div>
            <?php 
	if($jobstatus_billed == '1'){ 
	$usereditby = $user->loginuserdata($editby);
	$fletter = substr($usereditby['first_name'], 0, 1);
	$lletter = substr($usereditby['last_name'], 0, 1);
	?>
            <div class="ahbox"><?php echo $fletter.$lletter; ?></div>
            <?php } ?>
            <?php  
			$j = 0;
			foreach($attachment as $attahments_image)
		  		{  
		  			$a = $attahments_image->imagename;
					$info = new SplFileInfo($attahments_image->imagename);
$extension = $info->getExtension();
//echo $extension;
		 		if (strpos($a, 'SWMS') !== false && $j ==0 && $extension == 'pdf' || $extension == 'PDF'){
		  		?>
            <div class="swms">SWMS</div>
            <?php $j++;} } ?>
            <div class="menujoblist">=</div>
            <div class="dropmenuouter">
              <?php if($jobstatus_processed == '1')
				{ ?>
              <div class="Un-MarkasProcessed"><a href="#unmarkProcessed" class="markprocessedbtn" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Un-Mark as Processed</a></div>
              <?php 
				} 
			  else 
				{ ?>
              <div class=""><a href="#markProcessed" class="markprocessedbtn" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Mark as Processed</a></div>
              <?php }
			    if($jobstatus_billed == '1')
					{ ?>
              <div class=""><a href="#unmarkBilled" class="markprocessedbtnbilled" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-invoice="<?php echo $jobdetail['job_invoice']?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Un-Mark as Billed</a></div>
              <?php 
					 }
			  	else
					{ ?>
              <div class=""><a href="#markBilled" class="markprocessedbtnbilled" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-invoice="<?php echo $jobdetail['job_invoice']?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Mark as Billed</a></div>
              <?php }  ?>
              <div class=""><a href="<?php echo home_base_url(); ?>job/extend_job.php?id=<?php echo $jobdetail['id']; ?>&action=edit" class="markprocessedbtnbilled" >Extend Job</a></div>
              <div class="hideprint"><a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">Print Job</a></div>
            </div>
          </div>
        </div>
        <?php $i++; } ?>
      </div>
      <?php 
	$totaljob = $i;} 

   }  $totaljob = $i;  ?>
    </div>
    <div class="pull-right">Total # of Jobs : <?php echo $totaljob; ?></div>
    <div class="row marginbottom20 customwidthouter">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 marginbottom20 cross_outer">
        <div class="equinamesection text-center">Cross Hire</div>
        <?php 
	$jobsbycranetypes =  $job->getjobbycranetypelist2($current_date);
	$val=0;
	foreach($jobsbycranetypes as $jobdetailvalue)
	{
	$value= $jobdetailvalue['job_cross_hire'];
	$val += $value;
	}
	if(!empty($val))
	{
	?>
        <ul class="bxslider">
          <?php
	foreach($jobsbycranetypes as $jobdetail)
	{
		if($jobdetail['job_cross_hire'] == 1)
		{
			$customers1 = $customer->customerdata($jobdetail['job_clie_id']);
			$editby = $jobdetail['job_completedby'];
			$sequence_id = $jobdetail['sequence_id'];
			$jobstatus_billed = $jobdetail['job_status_billed'];
			$jobstatus_processed = $jobdetail['job_status_processed'];
			$contactdata = $customer->gecontactdata($jobdetail['job_cont_name']);
	
	?>
          <li>
            <div class="jobcontentbox clearfix  <?php if($jobstatus == 'Billed' || $jobstatus_billed == '1' || $jobstatus_processed == '1'){ echo "billeddiv"; }?>">
              <?php if($sequence_id != 0){ ?>
              <a class="sequencelink" href="<?php echo home_base_url(); ?>sequence_job.php?sequencejobid=<?php echo $sequence_id; ?>" target="_blank"> <img src="<?php echo home_base_url(); ?>images/link-5.png" alt="ELS" class="img-responsive"> </a>
              <?php } ?>
              <?php if($jobstatus_billed == '1'){ ?>
              <div class="billledleftbox">BILLED</div>
              <?php }  
	
	if($jobstatus_processed == '1'){
	?>
              <div class="processedleftbox process">PROCESSED</div>
              <?php } 
	?>
              <div><b>Job id:</b> <?php echo $jobdetail['id']; ?></div>
              <div><b>Client:</b> <?php echo $customers1['cust_name']; ?></div>
              <div><b>Time:</b> <?php echo $jobdetail['job_time']; ?></div>
              <div><b>Address:</b> <?php echo substr($jobdetail['job_address'], 0,18); ?>...</div>
              <?php $jobsequi_size = $equipment->jobsequisize($jobdetail['job_equi_size']);
				foreach($jobsequi_size as $jobsequi_sizes){
				?>
              <div><b>Crane Type:</b> <?php echo $jobsequi_sizes['equipments_size']; ?></div>
              <?php } ?>
              <div><b>Contact:</b> <?php echo $contactdata['cont_name']; ?> - <?php echo $contactdata['cont_phone']; ?></div>
              <div><b>Details:</b> <?php echo substr($jobdetail['job_detail'], 0, 18); ?>...</div>
              <div><b>Completed By: </b> <span>
                <?php $completedby_processed = $user->loginuserdata($jobdetail['job_processed_user_name']);
			$completedby_billed = $user->loginuserdata($jobdetail['job_billed_user_name']);
			?>
                <strong>
                <?php if($jobstatus_processed == 1){ echo $completedby_processed['first_name'].' '.$completedby_processed['last_name'];  ?>
                </strong> mark as processed, <strong>
                <?php } if($jobstatus_billed == 1){ echo $completedby_processed['first_name'].' '.$completedby_processed['last_name']; ?>
                </strong> mark as billed
                <?php } ?>
                </span></div>
              <div class="text-right jobmenubottom clearfix">
                <div class="viewjobbox pull-left hideprint"><a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">View</a></div>
                <?php 
	if($jobstatus_billed == '1'){ 
	$usereditby = $user->loginuserdata($editby);
	$fletter = substr($usereditby['first_name'], 0, 1);
	$lletter = substr($usereditby['last_name'], 0, 1);
	?>
                <div class="ahbox"><?php echo $fletter.$lletter; ?></div>
                <?php } ?>
                <div class="menujoblist">=</div>
                <div class="dropmenuouter">
                  <?php if($jobstatus_processed == '1')
				{ ?>
                  <div class=""><a href="#unmarkProcessed" class="markprocessedbtn" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Un-Mark as Processed</a></div>
                  <?php 
				} 
			  else 
				{ ?>
                  <div class=""><a href="#markProcessed" class="markprocessedbtn" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Mark as Processed</a></div>
                  <?php }
			    if($jobstatus_billed == '1')
					{ ?>
                  <div class=""><a href="#unmarkBilled" class="markprocessedbtnbilled" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-invoice="<?php echo $jobdetail['job_invoice']?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Un-Mark as Billed</a></div>
                  <?php 
					 }
			  	else
					{ ?>
                  <div class=""><a href="#markBilled" class="markprocessedbtnbilled" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>" data-invoice="<?php echo $jobdetail['job_invoice']?>" data-sequence="<?php echo $jobdetail['sequence_id']?>">Mark as Billed</a></div>
                  <?php }  ?>
                  <div class=""><a href="<?php echo home_base_url(); ?>job/extend_job.php?id=<?php echo $jobdetail['id']; ?>&action=edit" class="markprocessedbtnbilled" >Extend Job</a></div>
                  <div class="hideprint"><a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">Print Job</a></div>
                </div>
              </div>
            </div>
          </li>
          <?php  }  } ?>
        </ul>
        <?php
	} else { ?>
        <div class="jobcontentbox clearfix">
          <div>No Data Available</div>
        </div>
        <?php  }?>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 marginbottom20 meeting_outer">
        <div class="equinamesection text-center">Meetings</div>
        <?php 
	$jobsbycranetypes =  $job->getjobbycranetypelist2($current_date); 
	
	$val=0;
	foreach($jobsbycranetypes as $jobdetailvalue){
	$value= $jobdetailvalue['job_meetings'];
	$val += $value;
	}
	
	if(!empty($val)){?>
        <ul class="bxslider">
   <?php foreach($jobsbycranetypes as $jobdetail)
   		{
			if($jobdetail['job_meetings'] == 1)
			{
				$customers3 = $customer->customerdata($jobdetail['job_clie_id']);
				$editby = $jobdetail['job_completedby'];
				$jobstatus_billed = $jobdetail['job_status_billed'];
				$jobstatus_processed = $jobdetail['job_status_processed'];
				$contactdata = $customer->gecontactdata($jobdetail['job_cont_name']);
	?>
          <li>
            <div class="jobcontentbox clearfix  <?php if($jobstatus == 'Billed' || $jobstatus_billed == '1' || $jobstatus_processed == '1'){ echo "billeddiv"; }?>">
              <?php if($jobstatus_billed == '1'){ ?>
              <div class="billledleftbox">BILLED</div>
              <?php }  
	
	if($jobstatus_processed == '1'){
	?>
              <div class="processedleftbox process">PROCESSED</div>
              <?php } 
	?>
              <div><b>Job id:</b> <?php echo $jobdetail['id']; ?></div>
              <div><b>Client:</b> <?php echo $customers3['cust_name']; ?></div>
              <div><b>Time:</b> <?php echo $jobdetail['job_time']; ?></div>
              <div><b>Address:</b> <?php echo substr($jobdetail['job_address'], 0,18); ?>...</div>
              <?php $jobsequi_size = $equipment->jobsequisize($jobdetail['job_equi_size']);
				foreach($jobsequi_size as $jobsequi_sizes){
				?>
              <div><b>Crane Type:</b> <?php echo $jobsequi_sizes['equipments_size']; ?></div>
              <?php } ?>
              <div><b>Contact:</b> <?php echo $contactdata['cont_name']; ?> - <?php echo $contactdata['cont_phone']; ?></div>
              <div><b>Details:</b> <?php echo substr($jobdetail['job_detail'], 0, 18); ?>...</div>
              <div><b>Completed By: </b> <span>
                <?php $completedby_processed = $user->loginuserdata($jobdetail['job_processed_user_name']);
			$completedby_billed = $user->loginuserdata($jobdetail['job_billed_user_name']);

			?>
                <strong>
                <?php if($jobstatus_processed == 1){ echo $completedby_processed['first_name'].' '.$completedby_processed['last_name'];  ?>
                </strong> mark as processed, <strong>
                <?php } if($jobstatus_billed == 1){ echo $completedby_processed['first_name'].' '.$completedby_processed['last_name']; ?>
                </strong> mark as billed
                <?php } ?>
                </span></div>
              <div class="text-right jobmenubottom clearfix">
                <div class="viewjobbox pull-left hideprint"><a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">View</a></div>
                <?php 
	if($jobstatus_billed == '1'){ 
	$usereditby = $user->loginuserdata($editby);
	$fletter = substr($usereditby['first_name'], 0, 1);
	$lletter = substr($usereditby['last_name'], 0, 1);
	?>
                <div class="ahbox"><?php echo $fletter.$lletter; ?></div>
                <?php } ?>
                <div class="menujoblist">=</div>
                <div class="dropmenuouter">
                  <div class=""><a href="#markProcessed" class="markprocessedbtn" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>">Mark as Processed</a></div>
                  <?php if($jobstatus != 'Billed'){ ?>
                  <div class=""><a href="#markBilled" class="markprocessedbtnbilled" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>">Mark as Billed</a></div>
                  <?php }?>
                  <div class=""><a href="<?php echo home_base_url(); ?>job/extend_job.php?id=<?php echo $jobdetail['id']; ?>&action=edit" class="markprocessedbtnbilled" >Extend Job</a></div>
                  <div class="hideprint"><a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">Print Job</a></div>
                </div>
              </div>
            </div>
          </li>
          <?php }
	} ?>
        </ul>
        <?php } else { ?>
        <div class="jobcontentbox clearfix">
          <div>No Data Available</div>
        </div>
        <?php } ?>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 marginbottom20 tbc_outer"> 
        <!--tbc search-->
        <div class="equinamesection text-center">
          <div class="tbc_heading col-sm-1">TBC</div>
          <div class="searchform tbc_search pull-right col-sm-11">
            <form class="navbar-form" role="search" action="" method="get">
              <div class="input-group">
                <input type="text" class="form-control tbc_txt" placeholder="Search" name="tbc_serach_box" value="">
                <div class="input-group-btn">
                  <button class="btn tbc_search_btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                </div>
              </div>
            </form>
          </div>
          <div class="clearall"></div>
        </div>
        <!--tbc search-->
        
        <?php 
	$jobsbycranetypes =  $job->getjobbycranetypelist1(); 
	$val=0;
	foreach($jobsbycranetypes as $jobdetailvalue){
	$value= $jobdetailvalue['job_tbc'];
	$val += $value;
	}
	
	if(!empty($val)){
	?>
        <div id="tbc_result_show">
          <ul class="bxslider_tbc">
            <?php
	foreach($jobsbycranetypes as $jobdetail){
	if($jobdetail['job_tbc'] == 1){
	
	$customers2 = $customer->customerdata($jobdetail['job_clie_id']);
	
	$editby = $jobdetail['job_completedby'];
	
	$jobstatus_billed = $jobdetail['job_status_billed'];
	$jobstatus_processed = $jobdetail['job_status_processed'];
	$contactdata = $customer->gecontactdata($jobdetail['job_cont_name']);
	
	?>
            <li>
              <div class="jobcontentbox clearfix  <?php if($jobstatus == 'Billed'){ echo "billeddiv"; }?>">
                <?php if($jobstatus_billed == '1'){ ?>
                <div class="billledleftbox">BILLED</div>
                <?php }  
	
	if($jobstatus_processed == '1'){
	?>
                <div class="processedleftbox process">PROCESSED</div>
                <?php } 
	?>
                <div><b>Job id:</b> <?php echo $jobdetail['id']; ?></div>
                <div><b>Client:</b> <?php echo $customers2['cust_name']; ?></div>
                <div><b>Date:</b> <?php echo $jobdetail['job_date']; ?></div>
                <div><b>Time:</b> <?php echo $jobdetail['job_time']; ?></div>
                <div><b>Address:</b> <?php echo substr($jobdetail['job_address'], 0,18); ?>...</div>
                <?php $jobsequi_size = $equipment->jobsequisize($jobdetail['job_equi_size']);
				foreach($jobsequi_size as $jobsequi_sizes){
				?>
                <div><b>Crane Type:</b> <?php echo $jobsequi_sizes['equipments_size']; ?></div>
                <?php } ?>
                <div><b>Contact:</b> <?php echo $contactdata['cont_name']; ?> - <?php echo $contactdata['cont_phone']; ?></div>
                <div><b>Details:</b> <?php echo substr($jobdetail['job_detail'], 0, 18); ?>...</div>
                <div class="text-right jobmenubottom clearfix">
                  <div class="viewjobbox pull-left hideprint"><a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">View</a></div>
                  <?php 
	if($jobstatus_billed == '1'){ 
	$usereditby = $user->loginuserdata($editby);
	$fletter = substr($usereditby['first_name'], 0, 1);
	$lletter = substr($usereditby['last_name'], 0, 1);
	?>
                  <div class="ahbox"><?php echo $fletter.$lletter; ?></div>
                  <?php } ?>
                  <div class="menujoblist">=</div>
                  <div class="dropmenuouter">
                    <div class=""><a href="#markProcessed" class="markprocessedbtn" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>">Mark as Processed</a></div>
                    <?php if($jobstatus != 'Billed'){ ?>
                    <div class=""><a href="#markBilled" class="markprocessedbtnbilled" data-toggle="modal" data-jobid="<?php echo $jobdetail['id']; ?>">Mark as Billed</a></div>
                    <?php }?>
                    <div class=""><a href="<?php echo home_base_url(); ?>job/extend_job.php?id=<?php echo $jobdetail['id']; ?>&action=edit" class="markprocessedbtnbilled" >Extend Job</a></div>
                    <div class="hideprint"><a href="<?php echo home_base_url(); ?>job/view_job.php?id=<?php echo $jobdetail['id']; ?>">Print Job</a></div>
                  </div>
                </div>
              </div>
            </li>
            <?php }  } ?>
          </ul>
        </div>
        <?php
	}  else { ?>
        <div class="jobcontentbox clearfix">
          <div>No Data Available</div>
        </div>
        <?php } ?>
      </div>
    </div> <?php } ?>
    <div class="row marginbottom20">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center notprintthisarea">
        <button class="btn btn-default footerprint" onclick="fullpageprint()">Print Full Page</button>
      </div>
    </div>
    <div class="bs-example">
      <div id="markProcessed" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
              <p>Job ID <span class="jobidbox"></span> Mark as Processed</p>
              <!--<div class="checkbox">
	<label><input type="checkbox" name="markprocess" class="markprocess" id="markprocess" value="Yes">Yes</label>
	</div>--> 
            </div>
            <div class="modal-footer">
              <input type="hidden" name="jobmarkprocess" class="jobmarkprocess" id="jobmarkprocess" value="">
              <input type="hidden" name="jobidsequence" class="jobidsequence" id="jobidsequence" value="">
              <input type="hidden" name="currentuserid" class="currentuserid" id="currentuserid" value="<?php echo $userdata['user_id']; ?>">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="jobprocess">Save changes</button>
            </div>
          </div>
        </div>
      </div>
      <div id="markBilled" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
              <p>Job ID <span class="jobidboxbilled"></span> Mark as Billed</p>
              <p>Invoice Number</p>
              <input type="text" name="jobinvoice" class="form-control jobinvoice" id="jobinvoice" value="<?php echo $jobsmeetings['job_invoice']; ?>" placeholder="Enter Invoice Number"/>
              <!--<div class="checkbox">
	<label><input type="checkbox" name="markprocess" class="markprocess" id="markprocess" value="Yes">Yes</label>
	</div>--> 
            </div>
            <div class="modal-footer">
              <input type="hidden" name="jobmarkbilled" class="jobmarkbilled" id="jobmarkbilled" value="">
              <input type="hidden" name="jobidsequencebilled" class="jobidsequencebilled" id="jobidsequencebilled" value="">
              <input type="hidden" name="currentuserid" class="currentuserid" id="currentuserid" value="<?php echo $userdata['user_id']; ?>">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="jobbilled">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bs-example">
      <div id="unmarkProcessed" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
              <p>Job ID <span class="jobidbox"></span> Un-Mark as Processed</p>
              <!--<div class="checkbox">
	<label><input type="checkbox" name="markprocess" class="markprocess" id="markprocess" value="Yes">Yes</label>
	</div>--> 
            </div>
            <div class="modal-footer">
              <input type="hidden" name="jobmarkprocess" class="jobmarkprocess" id="jobmarkprocess" value="">
              <input type="hidden" name="jobidsequence" class="jobidsequence" id="jobidsequence" value="">
              <input type="hidden" name="currentuserid" class="currentuserid" id="currentuserid" value="<?php echo $userdata['user_id']; ?>">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="jobunprocess">Save changes</button>
            </div>
          </div>
        </div>
      </div>
      <div id="unmarkBilled" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
              <p>Job ID <span class="jobidboxbilled"></span> Un-Mark as Billed</p>
              <p>Invoice Number</p>
              <input type="text" name="jobinvoice" class="form-control jobinvoice" id="jobinvoice" value="<?php echo $jobsmeetings['job_invoice']; ?>" placeholder="Enter Invoice Number"/>
              <!--<div class="checkbox">
	<label><input type="checkbox" name="markprocess" class="markprocess" id="markprocess" value="Yes">Yes</label>
	</div>--> 
            </div>
            <div class="modal-footer">
              <input type="hidden" name="jobmarkbilled" class="jobmarkbilled" id="jobmarkbilled" value="">
              <input type="hidden" name="jobidsequencebilled" class="jobidsequencebilled" id="jobidsequencebilled" value="">
              <input type="hidden" name="currentuserid" class="currentuserid" id="currentuserid" value="<?php echo $userdata['user_id']; ?>">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="jobunbilled">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container --> 
  
</div>
<?php include ('footer.php');  ?>
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/jquery.bxslider.min.js"></script> 
<script type="text/javascript">
	
	// v3.1.0
	// SIMPLEWEATHER BELOW HERE
	$(document).ready(function() {
	$.simpleWeather({
	location: 'BNE',
	woeid: '',
	unit: 'c',
	success: function(weather) {
	html = '<h2><a href="http://www.bom.gov.au/products/IDR663.loop.shtml" title="Click for BOM" alt="Click for BOM" target="_blank"><i class="icon-'+weather.code+'"></i> '+weather.temp+'&deg;'+weather.units.temp+' '+weather.currently+' '+weather.wind.direction+' '+weather.wind.speed+' '+weather.units.speed+'</a></h2>';
	
	$("#weather").html(html);
	},
	error: function(error) {
	$("#weather").html('<p>'+error+'</p>');
	}
	});
	});
	//SIMPLEWEATHER ABOVE HERE
	
	function fullpageprint() {
	window.print();
	}
	
	jQuery(document).ready(function($) {
	
	/*var billedwidth = ($('.billeddiv').width()+20);
	var billedheight = ($('.billeddiv').height()+20);
	//alert(billedwidth);
	//alert(billedheight);
	var digonalwidth = Math.sqrt([(billedwidth*billedwidth) + (billedheight*billedheight)]);
	//alert(digonalwidth);
	$('.billeddiv').find('.billledleftbox').css('width', digonalwidth+'px');*/
	
	
	// tbc search btn
	
	$(".tbc_txt").keyup(function(event){
	if(event.keyCode == 13){
	$(".tbc_search_btn").click();
	
	}
	}); 
	$('.tbc_search_btn').click(function(){
	
	var tbcstex = $('.tbc_txt').val();
	if(tbcstex != '') {
	$( ".tbc_txt" ).prop( "disabled", true ); 
	$( ".tbc_search_btn" ).prop( "disabled", true );
	data = 'action=tbc_search&tbc_stxt='+tbcstex;
	// alert(data);	
	$.ajax({
	type: 'POST',
	cache: false,
	url: '<?php echo home_base_url(); ?>class/class.ajax.php',
	data: data, 
	success: function(html) {
	//alert(html);
	$( ".tbc_txt" ).prop( "disabled", false );
	$( ".tbc_search_btn" ).prop( "disabled", false );
	$("#tbc_result_show").empty();
	document.getElementById("tbc_result_show").innerHTML=html;
	setTimeout(function(){
	$('.bxslider_tbc').bxSlider({
	
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 240,
	slideMargin: 15,
	pager: false,
	moveSlides: 1,
	infiniteLoop: false,
	hideControlOnEnd: true,
	
	
	});
	},100);
	$('.menujoblist').click(function(){
	$(this).parent().find('.dropmenuouter').toggleClass('showdiv');	
	});
	$('.markprocessedbtn').click(function(){
	var jobidtext = $(this).data('jobid');
	$('.jobidbox').html(jobidtext);
	$('.jobmarkprocess').val(jobidtext);
	}); 
	
	$('.markprocessedbtnbilled').click(function(){
	var jobidtext = $(this).data('jobid');
	$('.jobidboxbilled').html(jobidtext);
	$('.jobmarkbilled').val(jobidtext);
	}); 
	}
	});	
	
	}
	return false;
	
	
	});
	
	// end of tbc search
	jQuery(document).on('click', '.menujoblist', function($)
	{
	//$('.menujoblist').click(function(){
	jQuery(this).parent().find('.dropmenuouter').toggleClass('showdiv');	
	});
	
	$('.markprocessedbtn').click(function(){
	var jobidtext = $(this).data('jobid');;
	var jobidsequence = $(this).data('sequence');
	$('.jobidbox').html(jobidtext);
	$('.jobmarkprocess').val(jobidtext);
	$('.jobidsequence').val(jobidsequence);
	}); 
	
	$('.markprocessedbtnbilled').click(function(){
	var jobidtext = $(this).data('jobid');
	var jobidinvoice = $(this).data('invoice');
	var jobidsequence = $(this).data('sequence');
	$('.jobidboxbilled').html(jobidtext);
	$('.jobmarkbilled').val(jobidtext);
	$('.jobinvoice').val(jobidinvoice);
	$('.jobidsequencebilled').val(jobidsequence);
	
	}); 
	
	$('#jobprocess').click(function(){ 
	var jobid = $('.jobmarkprocess').val();
	var editby = $('.currentuserid').val();
	var jobidsequence = $('.jobidsequence').val();
	// alert(jobid);	
	
	data = 'action=changejobprocess&jobid='+jobid+'&editby='+editby+'&jobidsequence='+jobidsequence;
	//alert(data);	
	$.ajax({
	type: 'POST',
	cache: false,
	url: '<?php echo home_base_url(); ?>class/class.ajax.php',
	data: data, 
	success: function(html) {
		//alert(html);
	setTimeout( function() {
	var notification = new NotificationFx({
	message : '<p> Job marked as Processed Successfully </p>',
	layout : 'growl',
	effect : 'slide',
	type : 'notice', // notice, warning or error
	});
	// show the notification
	notification.show();
	}, 800 );
	setInterval(      
	function() {
	
	//location.reload();
	$('#markProcessed').modal('hide');
	
	location.reload();
	},2000);
	//$('.dropmenuouter').removeClass('showdiv');
	//$('.as-'+jobid+'').append('<div class="processedleftbox">PROCESSED</div>');
	}
	});	
	
	
	});	
	
	$('#jobbilled').click(function(){ 
	var jobid = $('.jobmarkbilled').val();
	var editby = $('.currentuserid').val();
	var jobinvoice = $('#jobinvoice').val();
	var jobidsequencebilled = $('.jobidsequencebilled').val();
	//alert(jobinvoice);
	// alert(jobid);	
	if(jobinvoice != ""){	
	data = 'action=changejobbilled&jobid='+jobid+'&editby='+editby+'&jobinvoice='+jobinvoice+'&jobidsequencebilled='+jobidsequencebilled;
	//alert(data);	
	$.ajax({
	type: 'POST',
	cache: false,
	url: '<?php echo home_base_url(); ?>class/class.ajax.php',
	data: data, 
	success: function(html) {
	//alert(html);
	setTimeout( function() {
	var notification = new NotificationFx({
	message : '<p> Job marked as Billed Successfully </p>',
	layout : 'growl',
	effect : 'slide',
	type : 'notice', // notice, warning or error
	});
	// show the notification
	notification.show();
	}, 800 );
	setInterval(      
	function() {
	
	//location.reload();
	$('#markBilled').modal('hide');
	
	location.reload();
	},2000);
	//$('.dropmenuouter').removeClass('showdiv');
	//$('.as-'+jobid+'').append('<div class="billledleftbox">BILLED</div>');
	
	
	}
	});	
	}
	else
	{
	alert("Enter Invoice Number");	
	}
	
	
	});	
	
	
	
	$('#jobunprocess').click(function(){ 
	var jobid = $('.jobmarkprocess').val();
	var editby = $('.currentuserid').val();
	var jobidsequence = $('.jobidsequence').val();
	// alert(jobid);	
	
	data = 'action=changejobunprocess&jobid='+jobid+'&editby='+editby+'&jobidsequence='+jobidsequence;
	//alert(data);	
	$.ajax({
	type: 'POST',
	cache: false,
	url: '<?php echo home_base_url(); ?>class/class.ajax.php',
	data: data, 
	success: function(html) {
		//alert(html);
	setTimeout( function() {
	var notification = new NotificationFx({
	message : '<p> Job Un-marked as Processed Successfully </p>',
	layout : 'growl',
	effect : 'slide',
	type : 'notice', // notice, warning or error
	});
	// show the notification
	notification.show();
	}, 800 );
	setInterval(      
	function() {
	
	//location.reload();
	$('#unmarkProcessed').modal('hide');
	location.reload();
	
	},2000);
	 
	}
	});	
	
	
	});
	$('#jobunbilled').click(function(){ 
		var jobid = $('.jobmarkbilled').val();
		var editby = $('.currentuserid').val();
		var jobinvoice = $('#jobinvoice').val();
		var jobidsequencebilled = $('.jobidsequencebilled').val();	
		if(jobinvoice != "")
		{	
			data = 'action=changejobunbilled&jobid='+jobid+'&editby='+editby+'&jobinvoice='+jobinvoice+'&jobidsequencebilled='+jobidsequencebilled;
			//alert(data);	
			$.ajax({
				type: 'POST',
				cache: false,
				url: '<?php echo home_base_url(); ?>class/class.ajax.php',
				data: data, 
				success: function(html) {
				//alert(html);
				setTimeout( function() {
				var notification = new NotificationFx({
				message : '<p> Job Un-marked as Billed Successfully </p>',
				layout : 'growl',
				effect : 'slide',
				type : 'notice', // notice, warning or error
				});
				// show the notification
				notification.show();
				}, 800 );
				setInterval(      
				function() {
				
				//location.reload();
				$('#unmarkBilled').modal('hide');
				$('.billledleftbox').hide();
				location.reload();
				},2000); 
				}
			});	
		}
		else
		{
			alert("Enter Invoice Number");	
		}
	});
	
	$('.bxslider').bxSlider({
		minSlides: 1,
		maxSlides: 6,
		slideWidth: 240,
		slideMargin: 15,
		pager: false,
		moveSlides: 1,
		adaptiveHeight: true,
		infiniteLoop: false,                 // true, false - display first slide after last
	});		
	
	$('.bxslider_tbc').bxSlider({
		minSlides: 1,
		maxSlides: 6,
		slideWidth: 240,
		slideMargin: 15,
		pager: false,
		moveSlides: 1,
		infiniteLoop: false,                 // true, false - display first slide after last
	});				
	});
	</script>
<?php include ('footer-bottom.php');  ?>
