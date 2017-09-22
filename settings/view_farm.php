<?php require_once '../dbconfig.php';

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
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
  				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 equipment"><h1 class="equipment_head"><a class="btn btn-primary" href="<?php echo home_base_url(); ?>dashboard.php"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back</a></h1></div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 equipment_address-h"><h2><?php echo str_replace('-', ',', $farms_detail['address']); ?></h2></div>
  				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 users-eye"><a class="addusers addplusicon pull-right" href="<?php echo home_base_url(); ?>settings/edit_farm.php?id=<?php echo $_GET['id']; ?>" title="View Users"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Farm</a></div>
  			</div>
		    <div class="addnewuserform row">
          <div class="add_block_outer">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="maosection">
                 
                  <div id="map" style="width:100%;height:800px; margin-top:150px"></div>
                </div> 
              </div>
              <div class="total_book_list col-sm-12 col-xs-12">
                <div class="add_block">
                  

                     <table class="points_table points_table_views points_table_farm-details  table" width="100%">
                        <thead>
                            <tr>
                                <th width="65%">Name
                                  
                                </th>
                                <th width="35%" class="text-right">
                                Price
                                </th>
                            </tr>
                        </thead>
                        <tbody class="points_table_scrollbar">
                        <?php $i = 1; foreach($blocks_details as $bdetail){ ?>
                          <tr>
                              <td width="65%">
                                  <div class="map-pro_img block_str_b"> <strong>Block <?php echo $i; ?></strong><span><img src="<?php echo home_base_url(). $bdetail['fruit_image_path']; ?>" style="margin-left:50px;"></span></div>
                              </td>
                              <td width="35%">
                                  <div class="right-section-table">
                                      <span class="sign-s"><img src="../img/up-arrow.png"></span><span class="sign-s"><img src="../img/dwon_arrow.png"></span><span class="price">25%</span> 
                                  </div>
                              </td>
                          </tr>
                        <?php $i++; } ?>   
                        </tbody>
                    </table>
                  
                </div>
              </div>
              <div class="clearfix"></div>
              
         
          </div>
        </div>
		</div>
	</div>
</div>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>
<script type="text/javascript">
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

}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtSC1AKuhbEyTNhhFnovCH38CJB93r1Ik&libraries=drawing,places&callback=initAutocomplete"async defer></script>
