<?php 
require_once 'dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$userdata = $user->loginuserdata($_SESSION['user_session']);

if(isset($_POST['btn-signup']))
{ 

   $messagebody = $_POST['messagebody'];
   $mail_id = $_POST['mail_id'];
   
   $attachmenturl = $_POST['mailattachmentpath'];
   $attachmentname = $_POST['mailattachmentname'];
   
    if( $mail_id ){
	  $mailid = $mail_id; 
	   } else{
	  $mailid = 1; 	   
	}
   
   
   if($messagebody=="") {
      $error[] = "Enter Mail Message Body Content !"; 
   }
   else
   {
      try
      {
        
            if($mailbody->updated($mailid,$messagebody,$attachmenturl,$attachmentname)) 
            {
                $mailbody->redirect('email.php?id=1&joined');
            }
        
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }
  } 
}

if(isset($error)){ 
   $messagebody = $messagebody;

} else {
	
    $mailbody = $mailbody->mailbodydata($_REQUEST['id']);
	$messagebody = $mailbody['mail_body'];
    $pdfattachments = json_decode($mailbody['mail_pdflink']);
	
	}

?>
<?php include('header.php');  ?>

<div id="container">
    
  <!-- Page Content -->
    <div class="container">
    
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
<div class="titlesection row">   
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="margin0">Email Template Body</h1></div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users"></div>
</div>

<div class="addnewuserform">

 <form role="form" method="post" id="add_customers" class="add_customers">
 
 
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
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully Insert.
                 </div>
            <?php 
				} 
				?>
                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label for="messagebody">Message Body:</label>
                      <textarea  class="form-control" id="messagebody" name="messagebody" placeholder="Enter Mail Message Body Content Here" ><?php echo $messagebody; ?></textarea>
                   </div>
                   
                  <?php /*?> <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label for="pdflink">Upload brochures Attachment Link:</label>
                      <input type="text" class="form-control" id="pdflink" name="pdflink" placeholder="Enter PDF link Here" value="<?php echo $pdflink; ?>" />
                      <div class="fileuploader" id="fileuploader">Upload</div>
                   </div><?php */?>
                   
                   
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label for="pdflink">Upload brochures Attachment Link:</label>
              <div class="clearfix">
                <ul class="list-group">
                  <?php 
					  	if(is_array($pdfattachments)){ foreach($pdfattachments as $pdfattachment){ 
						
$info = new SplFileInfo($pdfattachment->imagepath);
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
	$imagepath = home_base_url().$pdfattachment->imagepath;
	}
						
					  ?>
                  <li class="list-group-item"> <a href="<?php echo home_base_url().$pdfattachment->imagepath; ?>" target="_blank"><img width="75" height="75" src="<?php echo $imagepath; ?>" alt="<?php echo $pdfattachment->imagename; ?>"  /></a> <span class="imagenametext"><?php echo substr($pdfattachment->imagename, 0 , 30); ?></span> <a href="javascript:;" class="deleteattachment pull-right"><i class="fa fa-close"></i></a>
                    <input type="hidden" class="form-control" id="picedit" name="mailattachmentpath[]"  value="<?php echo $pdfattachment->imagepath; ?>">
                    <input type="hidden" class="form-control" id="picedit" name="mailattachmentname[]"  value="<?php echo $pdfattachment->imagename; ?>">
                  </li>
                  <?php } } ?>
                </ul>
              </div>
              <div class="mailattachmentuploader" id="mailattachmentuploader">Upload</div>
            </div>
                   
                  
                   
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" name="mail_id" value="<?php echo $_REQUEST['id']; ?>"  />
                      <button type="submit" class="btn btn-default" name="btn-signup">Save Message Body</button>
                   </div>
                   
                   
                </form>

</div>	

  </div>
    </div>

        

    </div>
    <!-- /.container -->    
    
</div>

<?php include('footer.php');  ?>
<script type="text/javascript">
$(document).ready(function(){

tinymce.init({
  selector: '#messagebody',
  height: 500,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code'
  ],
  toolbar: ' styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
    '//www.tinymce.com/css/codepen.min.css'
  ]
});

	});
</script>
<?php include('footer-bottom.php');  ?>