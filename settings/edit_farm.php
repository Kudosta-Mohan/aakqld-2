<?php require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
$fruitfields= $fruit->getfruitslist();

if(isset($_POST['savefarm']) && $_POST['savefarm'] == 'save')
{ 
  
  $fid = $_POST['farm_id'];
  $fname = trim(str_replace(',', '-',$_POST['address']));
  $flat = $_POST['lat'];
  $flong = $_POST['long'];
  $blockArray = $_POST['blocks'];
  $error = array();
  if($fname=="") {
    $error[] = "Enter farm address!"; 
  }
  if($flat == '' && $flong == '')
  {
      $error[] = "Please select address properly by map!"; 
  }
  for($i=0; $i< count($blockArray['update']);$i++)
  {
    if($blockArray['update'][$i]['fruitName']  == '') {
      $error[] = "Select fruit!"; 
    }
    if($blockArray['update'][$i]['blockName']  == ''){
        $error[] = "Select block size!"; 
    }
    
  }

  for($j=count($blockArray['update']); $j< count($blockArray['insert']); $j++)
  {
    if($blockArray['insert'][$j]['fruitName']  == '') {
      $error[] = "Select fruit!"; 
    }
    if($blockArray['insert'][$j]['blockName']  == ''){
        $error[] = "Select block size!"; 
    }
  }
  
  if(count($error) == 0)
  { 
   
    try
    {
      $totalCount = count($blockArray['update']) + count($blockArray['insert']);
      if($farms->update($fname,$totalCount, $flat,$flong, $fid)) 
      {
        $farms->updateBlocks($blockArray['update'], $fid);
        $farms->insertBlocks($blockArray['insert'], $fid);
        $farms->redirect('edit_farm.php?id='.$fid.'&joined');
      }
       
   }
   catch(PDOException $e)
   {
      echo $e->getMessage();
   }
  }
}
$id = $_GET['id'];
$farms_detail = $farms->getfarmsdetail($id);
$blocks_details = $farms->getAllBlocks($id);

include ('../header.php');
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=drawing"></script>
<div class="container">
	<div class="row">
   		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      	<div class="titlesection row">   
  				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 equipment"><h1 class="equipment_head">Edit Farm</h1></div>
  				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users-eye"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>settings/farmsetup.php" title="View Users"><i class="fa fa-eye" aria-hidden="true"></i> View Farms</a></div>
  			</div>
		    <div class="addnewuserform row">
          <div class="add_block_outer">
            <form role="form" method="post"  class="block_form" enctype="multipart/form-data">
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
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully updated 
                 </div>
              <?php 
              } 
              ?>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="username">Farm Address:</label>
                <div class="maosection">
                  <input id="pac-input" class="controls" type="text" placeholder="Search Box" style="margin-bottom:150px" name="address" value="<?php echo str_replace('-', ',', $farms_detail['address']); ?>">
                  <div id="map" style="width:100%;height:400px; margin-top:150px"></div>
                </div> 
              </div>
              <input type="hidden" id="lat" name="lat" value="<?php echo $farms_detail['lat']; ?>">
              <input type="hidden" id="long" name="long" value="<?php echo $farms_detail['lng']; ?>">
              <input type="hidden" name="farm_id" value="<?php echo $farms_detail['fid']; ?>"> 
              <div class="total_book_list col-sm-12 col-xs-12">
                <div class="add_block">
                  <label for="username">Blocks:</label>
                  <div class="row block_edit_farm_section">
                    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 add-new-blocks">
                      <?php $i = 0; foreach($blocks_details as $bdetail){ ?>
                      <div id="block-<?php echo $bdetail['id']; ?>" class="product_edit_block" <?php if($i>0){ ?>style="margin-top:10px;"<?php } ?>>
                        <?php if($i>0){ ?>
                        <a class="remove-farm" href="javascript:void(0);" onclick="removeFarmBlock('<?php echo $bdetail['id']; ?>', '<?php echo $_GET["id"]; ?>')"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php } ?>
                        <h3 class="text-center">Block <?php echo $j =$i+1; ?></h3>
                        <div class="clearfix"></div>
                        <ul class="edit_details_sec">
                          <li>
                            <div class="editfarm_left_sec">Select Fruit:</div>
                            <div class="editfarm_right_sec">
                              <select name="blocks[update][<?php echo $i; ?>][fruitName]">
                                <option value="">Select...</option>
                                <?php foreach($fruitfields as $fruitfield){ ?>
                                <option value="<?php echo $fruitfield['fruit_id']; ?>" style="background:url(../img/<?php echo $fruitfield['fruit_image_name']; ?>) no-repeat; height:25px; width:25px;" <?php if($bdetail['fruit_id'] == $fruitfield['fruit_id']) echo 'selected="selected"'; ?>><?php echo $fruitfield['fruit_name']; ?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </li>
                          <li>
                            <div class="editfarm_left_sec">Block Size:</div>
                            <div class="editfarm_right_sec"><input type="text" class="form-control" name="blocks[update][<?php echo $i; ?>][blockName]" placeholder="Enter block size" value="<?php echo $bdetail['block_size'] ?>" class="blockName"></div>
                          </li>
                        </ul>
                      </div>
                      <input type="hidden" name="blocks[update][<?php echo $i; ?>][blockId]" value="<?php echo $bdetail['id']; ?>">
                      <?php $i++; } ?>
                    </div>
                  </div>
                  
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="btn-section-add-farm">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 btn-add-new-block text-left">
                  <button class="btn btn-success btn-add addnewbtn" type="button"> Add New Block</button> 
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 btn-savefram text-right">
                      <button type="submit" class="btn btn-success" name="savefarm" value="save">Save Farm</button>
               </div>
             </div>
              </form> 
          </div>
        </div>
		</div>
	</div>
</div>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>
<script type="text/javascript">

jQuery(document).ready(function(){
  var count = '<?php echo $i+1; ?>';
  var incCount = '<?php echo $i; ?>';
  jQuery(document).on('click','.addnewbtn',function(){ 
    jQuery('.add-new-blocks').append('<div class="product_edit_block" style="margin-top:10px;"><span class="remove "><i class="fa fa-times" aria-hidden="true"></i></span><h3 class="text-center">Block '+(count)+'</h3><div class="clearfix"></div><ul class="edit_details_sec"><li><div class="editfarm_left_sec">Select Fruit:</div><div class="editfarm_right_sec"><select name="blocks[insert]['+(incCount)+'][fruitName]"><option value="">Select...</option><?php foreach($fruitfields as $fruitfield){ ?><option value="<?php echo $fruitfield["fruit_id"]; ?>" style="background:url(../img/<?php echo $fruitfield["fruit_image_name"]; ?>) no-repeat; height:25px; widht:25px;"><?php echo $fruitfield["fruit_name"]; ?></option><?php } ?></select></div></li><li><div class="editfarm_left_sec">Block Size:</div><div class="editfarm_right_sec"><input class="form-control" name="blocks[insert]['+(incCount)+'][blockName]" placeholder="Enter block size" value="" type="text"></div></li></ul></div>');
      count++;
      incCount++; 
  });

  $('.add-new-blocks').on('click','.remove',function() {
    $(this).parent().remove();
    count--;
    incCount--;
  });
});

function removeFarmBlock(blId, fId)
{
  var con = confirm("Are you sure to remove this Block!");
  if(con == true)
  {
    jQuery.ajax({
        type: "POST",
        url: '<?php echo home_base_url(); ?>class/class.ajax.php?action=removefarmblock',
        data: {'blId': blId, 'fId': fId},
        cache: false,
        success: function(result){ 
        $('#block-'+blId).remove();
      }
    }); 
  }
}

// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
var flat = '<?php echo $farms_detail["lat"]; ?>';
var flng = '<?php echo $farms_detail["lng"]; ?>';
function initAutocomplete() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: new google.maps.LatLng(flat, flng),
    zoom: 17,
    mapTypeId: 'satellite'
  });

/* var drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.MARKER,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: ['marker', 'circle', 'polygon', 'polyline', 'rectangle']
    },
    markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},
    circleOptions: {
      fillColor: '#ffff00',
      fillOpacity: 1,
      strokeWeight: 5,
      clickable: false,
      editable: true,
      zIndex: 1
    }
  });
  drawingManager.setMap(map);*/






  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {

    
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    
    places.forEach(function(place) {
      if (!place.geometry) {
        console.log("Returned place contains no geometry");
        return;
      }
      $('#lat').val(place.geometry.location.lat());
      $('#long').val(place.geometry.location.lng());
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtSC1AKuhbEyTNhhFnovCH38CJB93r1Ik&libraries=drawing,places&callback=initAutocomplete"async defer></script>
