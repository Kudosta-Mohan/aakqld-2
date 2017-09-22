
<div class="footer_outer">
  <div class="container"> 
    <!-- Footer -->
    <footer>
      <div class="row">
        <div class="col-lg-12 text-center copy">
          <p>Intellectual Property of ABL IT Business IT Specialists <br/> Copyright 2017 </p><p>Not to be reused without express permission of ABL IT and it's directors</p>
        </div>
      </div>
    </footer>
  </div>
</div>

<!-- jquery include here --> 
<script src="<?php echo home_base_url(); ?>js/jquery.highlight-5.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/moment-with-locales.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/less.min.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/bootstrap-datepicker.min.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/bootstrap-timepicker.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/bootstrap-multiselect.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/dataTables.bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/jquery.uploadfile.min.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/raphael-min.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/morris.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/tinymce/tinymce.min.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/jquery.simpleWeather.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/classie.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/custom-script.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/modernizr.custom.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/notificationFx.js" charset="utf-8"></script> 
<script type="text/javascript">
    
jQuery(document).ready(function($)
  {
    $('table.datasd').DataTable();
    $(".notificationLink").click(function()
	  {
		$(".notificationContainer").fadeToggle();
		$(".equinotificationContainer").hide();
		setInterval(      
		function() {
		$(".notification_count").hide();
		},5000);
		var curusrid = $(this).data('currentuserid');
		var countnoti = $(this).data('countnoti');
		data = 'action=notificationupdate&userid='+curusrid;
		$.ajax({
			type: 'POST',
			cache: false,
			url: '<?php echo home_base_url(); ?>class/class.ajax.php',
			data: data, 
			success: function(html) {
			}
		});	
		return false;
	  });
    
    //Document Click
  $(document).click(function()
	  {
		$(".notificationContainer").hide();
		$(".equinotificationContainer").hide();
	  });
    
  $(".equinotificationLink").click(function()
    {
		$(".equinotificationContainer").fadeToggle();
		$(".notificationContainer").hide();
		//$(".notification_count").fadeOut("slow");
		
		setInterval(      
		function() {
		$(".equinotification_count").hide();
		},5000);
		
		var curusrid = $(this).data('currentuserid');
		var countnoti = $(this).data('countnoti');
		data = 'action=equinotificationupdate&userid='+curusrid;
		$.ajax({
			type: 'POST',
			cache: false,
			url: '<?php echo home_base_url(); ?>class/class.ajax.php',
			data: data, 
			success: function(html) {
			}
		});	
		return false;
    });
	
 $(".fileuploaderss").uploadFile({
		url:"<?php echo home_base_url(); ?>class/class.ajax.php?action=uploadimages",
		fileName:"myfile",
		showDelete: true,
		onSuccess: function (files, response, xhr, pd) {
		pd.statusbar.append(response);
		}
    });
    
 $(".fileuploadersecuress").uploadFile({
		url:"<?php echo home_base_url(); ?>class/class.ajax.php?action=uploadimagessecure",
		fileName:"myfilesecure",
		showDelete: true,
		onSuccess: function (files, response, xhr, pd) {
		pd.statusbar.append(response);
		}
    });
    
 $('.addcuster').click(function(){ 
		var clientval = $('#clientes').val();
		$('#resultaddas').hide();
		$('#clientes').prop( "disabled", true ).hide();
		$('#client_ids').prop( "disabled", true ).hide();
		$('#contactus').prop( "disabled", true ).hide();
		$('#addclientes').val(clientval).prop( "disabled", false ).show();
		$('#addcontactus').prop( "disabled", false ).show();
	 });	
    
 $('.addcontactus').click(function(){
		var contactval = $('#searchcontactus').val();
		$('#resultaddcontactus').hide();
		$('#searchcontactus').prop( "disabled", true ).hide();
		$('#contact_ids').prop( "disabled", true ).hide();
		//$('#contact').prop( "disabled", true ).hide();
		$('#contactus').val(contactval).prop( "disabled", false ).show();
		//$('#addcontactus').prop( "disabled", false ).show();
    });	
    
    /** client filter jquery */	  
 $("#clientes").keyup(function(){  
		var searchid = $(this).val();
		var dataString = 'action=getcustomerlist&search='+ searchid;
		if(searchid!=' ')
		{
		$.ajax({
			type: "POST",
			url: "<?php echo home_base_url(); ?>class/class.ajax.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
			if(html == 2){	
			$("#resultas").hide();	
			$('#resultaddas').show();		
			}else{
			$("#resultas").html(html).show();
			$('#resultaddas').hide();
			}
			
			$('.getclientid').click(function(){
			var custid = $(this).attr('data-id');
			var custname = $(this).attr('data-name');
			var data_flag = $(this).attr('data_flag');
			var data_reorder = $(this).attr('data_reorder');
			
			$("#clientes").val(custname);
			$("#client_ids").val(custid);
			$("#resultas").hide();
			
			var selectval = custid;
			
			if(data_flag == 'Yes'){
			$('.badpayer').show();
			} else {
			$('.badpayer').hide();
			}
			
			if(data_reorder == 'Yes'){
			$('.rporderbox').show().find('#rporders').prop( "disabled", false );
			} else {
			$('.rporderbox').hide().find('#rporders').prop( "disabled", true );
			}
			
			});
			}
		});
		}
		return false;    
	});
    
 $("#searchcontactus").keyup(function(){ 
		var clientid = $("#client_ids").val();
		var searchid = $(this).val(); 
		var dataString = 'action=getcustomercontactlist&clientid='+clientid+'&search='+ searchid;
		if(searchid!=' ')
		{
		$.ajax({
			type: "POST",
			url: "<?php echo home_base_url(); ?>class/class.ajax.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
			//alert(html);
			if(html == 2){	
			$("#resultcontactus").hide();	
			$('#resultaddcontactus').show();		
			}else{
			$("#resultcontactus").html(html).show();
			$('#resultaddcontactus').hide();
			}
			
			$('.getcontactid').click(function(){
			var custid = $(this).attr('data-id');
			var custname = $(this).attr('data-name');
			
			$("#searchcontactus").val(custname);
			$("#contact_ids").val(custid);
			$("#resultcontactus").hide();
			
			
			});
			}
		});
		}
	return false;    
    });
    
    /**--------------extended job-----------*/
 $("#extend_sequencesuccess").hide();
 $("#extend_savesequence").click(function(){
		$( "#extend_savesequence" ).prop( "disabled", true );	
		var datas = $("#extend_sequencejobform").serialize()+'&'+$("#extend_add_customers").serialize();
		var extend_id= '<?php // echo $_REQUEST['id']; ?>';
		$.ajax({
			type: "POST",
			url: '<?php echo home_base_url(); ?>class/class.ajax.php/?extend_id='+extend_id,
			data: datas,
			cache: false,
			success: function(result){ 
			if(result == ''){
			$('#extend_add_customers')[0].reset();
			$('#extend_sequencejobform')[0].reset();
			$("#extend_sequencesuccess").show();
			setTimeout( function() {
			var notification = new NotificationFx({
			message : '<p> Sequence Added Successfully </p>',
			layout : 'growl',
			effect : 'slide',
			type : 'notice', // notice, warning or error
			});
			// show the notification
			notification.show();
			}, 1200 );
			setInterval(      
			function() {
			window.location.href = '<?php echo home_base_url(); ?>';
			$('#addsequence').modal('hide');
			
			},
			4000);
			}
			}
		});
		return false;
    });
    
    $("#extend_sequencesuccess").hide();
    /*** crane type jquery  **/
 $('select#craneus').on('change', function() {
		var selectval = $(this).val();
		$('#cranesizeusss').empty();
		$('#cranesizeus option[value!=" "]').remove();
		data = 'action=onchangecranetype&equi_id='+selectval;
		//alert(data);
		$.ajax({
			type: 'POST',
			cache: false,
			dataType: 'json',
			url: '<?php echo home_base_url(); ?>class/class.ajax.php',
			data: data, 
			success: function(html) {
			//alert(html);
			jQuery.unique(html);
			var arr = [];
			var htmlStr = '';
			$.each(html, function(k, v){
			if(v.type == 'Group'){
			if ($.inArray(v.size, arr) == -1) {
			arr.push(v.size);
			}
			}
			else{
			$('#cranesizeusss').hide();
			}
			});
			$('#cranesizeusss').show().append(arr);
			}
		});	
    });
    
 $('.selectme input:checkbox').click(function() {
    $('.selectme input:checkbox').not(this).prop('checked', false);
   }); 
    
 $("#submitsform").click(function(){
		$( "#submitsform" ).prop( "disabled", true );	
		var client = $("#clientes").val();
		var date = $("#dates").val();
		var address = $("#addressss").val();
		var datas = $("#add_jobs").serialize();
		var addclient = $("#addclientes").val();
		if(date == '' || address == ''){
		$("#errormsg").show();
		$( "#submitsform" ).prop( "disabled", false );
		}
		else{
		$.ajax({
			type: "POST",
			url: '<?php echo home_base_url(); ?>class/class.ajax.php',
			
			data: datas,
			cache: false,
			success: function(result){ //alert(result);
			if(result == ''){
			$('#add_jobs')[0].reset();
			
			$("#insersuccess").show();
			setTimeout( function() {
			var notification = new NotificationFx({
			message : '<p> Job Added Successfully </p>',
			layout : 'growl',
			effect : 'slide',
			type : 'notice', // notice, warning or error
			});
			// show the notification
			notification.show();
			}, 1200 );
			setInterval(      
			function() {
			
			window.location.href = '<?php echo home_base_url(); ?>';
			$('#addjobss').modal('hide');
			
			},
			4000);
			}
			
			}
		});
		$("#errormsg").hide();
		}
    return false;
  });
	
    $("#insersuccess").hide();
    $("#errormsg").hide();
    
 $("#searchaddress").keyup(function(){ 
		var clientid = $("#client_ids").val(); 
		var contact_id = $("#contact_ids").val(); 
		var searchid = $(this).val(); 
		var dataString = 'action=getaddresslist&clientid='+clientid+'&search='+ searchid+'&contact_id='+ contact_id;
		if(searchid!=' ')
		{
		$.ajax({
			type: "POST",
			url: "<?php echo home_base_url(); ?>class/class.ajax.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
			if(html == 2){	
			$("#resultaddress").hide();	
			$('#resultaddaddress').show();		
			}else{
			$("#resultaddress").html(html).show();
			$('#resultaddaddress').hide();
			}
			$('.getaddress').click(function(){
			var custid = $(this).attr('data-id');
			var custname = $(this).attr('data-name');
			$("#searchaddress").val(custname);
			$("#contact_iddsd").val(custid);
			$("#resultaddress").hide();
			});
			}
		});
		}
	return false;    
    });
    
    
 $('.addaddress').click(function(){
		var contactval = $('#searchaddress').val();
		$('#resultaddaddress').hide();
		$('#searchaddress').prop( "disabled", true ).hide();
		$('#contact_iddsd').prop( "disabled", true ).hide();
		//$('#contact').prop( "disabled", true ).hide();
		$('#address').val(contactval).prop( "disabled", false ).show();
		//$('#addcontact').prop( "disabled", false ).show();
    });	
    
 $("#savesequence").click(function(){
		$( "#savesequence" ).prop( "disabled", true );	
		var datas = $("#sequencejobform").serialize()+'&'+$("#add_jobs").serialize();
		$.ajax({
			type: "POST",
			url: '<?php echo home_base_url(); ?>class/class.ajax.php',
			data: datas,
			cache: false,
			success: function(result){ 
			if(result == ''){
			$('#add_jobs')[0].reset();
			$('#sequencejobform')[0].reset();
			$("#sequencesuccess").show();
			setTimeout( function() {
			var notification = new NotificationFx({
			message : '<p> Job Added Successfully </p>',
			layout : 'growl',
			effect : 'slide',
			type : 'notice', // notice, warning or error
			});
			// show the notification
			notification.show();
			}, 1200 );
			setInterval(      
			function() {
			
			window.location.href = '<?php echo home_base_url(); ?>';
			$('#addsequence').modal('hide');
			
			},
			4000);
			}
			}
		});
    
    return false;
    });
    
    
 $("#sequencesuccess").hide();
    
 $("#searchingtext").keyup(function(event){
		if(event.keyCode == 13){
		$("#searchingg").click();
		}
  });	
  
 $('ul.pagination li a').click(function(){
     highlightfunction();
 });
	
 $('#submitsform,#savesequence,#sizeupdate,#addedjobs,#savesequencejobs,#extend_savesequence,#updatejobss,#deleteobsss').click(function(){
		var curusrid = $('.notificationLink').data('currentuserid');
		dataval = 'action=notiautoupdate&userid='+curusrid;
		$.ajax({
		type: 'POST',
		cache: false,
		url: '<?php echo home_base_url(); ?>class/class.ajax.php',
		data: dataval, 
		success: function(html) {
		var json = $.parseJSON(html);
		notificationViaNode(json);
		 }
		});	
	});
 
 $('#addequipments,#updateequipments,#addsizeequipmentss,#editsizeequipmentss,#deleteequipmentss').click(function(){
	 		
			notificationpmsViaNode();	
				
	}); 
	
 $('#addauxequipments,#updateauxequipments,#addsizeauxequipmentss,#editsizeauxequipmentss,#deleteauxequipmentss').click(function(){
	 		
			notificationpmsViaNode();	
				
	});
 });
	
 $( window ).load( function() {highlightfunction(); });
 function highlightfunction(){
    var $context = $("#searchjobsss");
    var $form = $("form");
    var $input = $form.find("input[name='q']");
    var searchTerm = $input.val();
    if(searchTerm){
    $context.highlight(searchTerm);
    }
 }
    </script> 
