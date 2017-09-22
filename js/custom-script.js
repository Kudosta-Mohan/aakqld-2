// JavaScript Document
jQuery(document).ready(function() {

/*** full calender jquery */	

$('#fullcalenderhide').click(function(){
$('#datepicker').hide();
});

$("#datepicker").datepicker({format: 'dd/mm/yyyy',todayHighlight: true});
$('#fullcalendershow').click(function(){
$('#datepicker').toggle();
});

$("#datepicker").datepicker().on('changeDate', function (ev) {
var selecteddate = $('#selecteddate').val();
var locationurl = $('#locationurl').val();
//alert(locationurl+selecteddate);
// similar behavior as clicking on a link
window.location.href = locationurl+selecteddate;
});



<!-- common jquery start here -->
jQuery('#datesequencestart').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});
jQuery('#datesequencestartend').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});
jQuery('#extend_sequencejobstart').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});
jQuery('#extend_sequencejobend').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});	
jQuery('#sequencejobsstarts').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});
jQuery('#sequencejobsends').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});
jQuery('#userdatelicenses').datepicker({
//startDate: new Date(),
format: 'yyyy/dd/mm/',
});
jQuery('#sequencejobstarts').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});
jQuery('#sequencejobends').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});
jQuery('#jobdatepicker').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});
jQuery('#jobdatepickeras').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});

jQuery('#startdatepicker').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});

jQuery('#enddatepicker').datepicker({
//startDate: new Date(),
format: 'dd/mm/yyyy',
});


/*jQuery('#fullcalendershow').datepicker({
format: 'dd/mm/yyyy',
});	*/
jQuery('#sequencejobtimedate').timepicker();
jQuery('#extend_sequencejobtime').timepicker();
jQuery('#sequencejobstimes').timepicker();
jQuery('#sequencejobtime').timepicker();
jQuery('#jobtimepickers').timepicker();

jQuery('#jobtimepicker').timepicker();

jQuery('#jobfinishtimepicker').timepicker({defaultTime: false});
jQuery('#jobtimeleavingyardtimepicker').timepicker({defaultTime: false});
jQuery('#jobtimearriveyardtimepicker').timepicker({defaultTime: false});

/*jQuery('#jobtimeleavingyardtimepicker').timepicker();
jQuery('#jobtimearriveyardtimepicker').timepicker();

jQuery('#jobfinishtimepicker').timepicker({showMeridian: false});*/


jQuery('.multiselect').multiselect();

//jQuery('.table').DataTable();
jQuery('table.nothavetime').DataTable({
"ordering": false
// "aoColumns": [{ "bSortable": false },{ "bSortable": false }]
});
jQuery('.tabledata').DataTable();

jQuery('#table_dumped').DataTable({
"ordering": false
// "aoColumns": [{ "bSortable": false },{ "bSortable": false }]
});

jQuery('#notbilledjobstable').DataTable({
"ordering": false,
"searching": false

// "aoColumns": [{ "bSortable": false },{ "bSortable": false }]
});

jQuery(document).on('click', '.btn-add', function(e)
{
e.preventDefault();

var controlForm = jQuery('.contactlists'),
currentEntry = jQuery(this).parents('.entry:first'),
newEntry = jQuery(currentEntry.clone()).appendTo(controlForm);

newEntry.find('input').val('');
controlForm.find('.entry:not(:last) .btn-add')
.removeClass('btn-add').addClass('btn-remove')
.removeClass('btn-success').addClass('btn-danger')
.html('<span class="glyphicon glyphicon-minus"></span>');
}).on('click', '.btn-remove', function(e)
{
jQuery(this).parents('.entry:first').remove();

e.preventDefault();
return false;
});

var $i = 1;

jQuery(document).on('click', '.btn-addcontact', function(e)
{
e.preventDefault();



var controlForm = jQuery('.contactlists'),
currentEntry = jQuery(this).parents('.entry:first'),
newEntry = jQuery(currentEntry.clone()).appendTo(controlForm);

newEntry.find('input').val('');
//newEntry.find('label.listno').html('Add Contact List ' + $i);
controlForm.find('.entry:not(:last) .btn-addcontact')
.removeClass('btn-addcontact').addClass('btn-removecontact')
.removeClass('btn-success').addClass('btn-danger')
.html('<span class="glyphicon glyphicon-minus"></span>');

var totalele = $('.contactlists .entry').length;
for($i=0; $i < totalele; $i++ )	{	
$( "label.listno" ).each( function( $i, el ) {
$( el ).html('Add Contact List ' + ($i+1));
});	
}


}).on('click', '.btn-removecontact', function(e)
{

var contidval = jQuery(this).parents('.entry:first').find('.contid').val(); 
var appenddeletebox = '<input class="form-control" name="cust_contactlistdelete[cont_id][]" type="hidden"  value="'+contidval+'" />';

jQuery('.deletefield').append(appenddeletebox);

jQuery(this).parents('.entry:first').remove();

var totalele = $('.contactlists .entry').length;
for($i=0; $i < totalele; $i++ )	{	
$( "label.listno" ).each( function( $i, el ) {
$( el ).html('Add Contact List ' + ($i+1));
});	
}	


e.preventDefault();
return false;
});



});



<!-- common jquery end here -->	

