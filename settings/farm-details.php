<?php require_once '../dbconfig.php';
include ('../header.php');
if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
?>
<?php $farms_value= $farms->getfarmslist();
?>
 <div class="container">
      <div class="kd_block">
      <div id="map"></div>
            <!--<img class="img-responsive" src="../img/block_image_map.png" />-->
        </div>
        <div class="clearfix"></div>
        <div class="cst_table">
        <table class="points_table points_table_farm-details tabledata table" width="100%">
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
                <?php foreach($farms_value as $farms_val){?>
                <tr>
                    <td width="65%">
                        <!--<p><?php// echo $farms_val['name'];?></p>-->
                        <div class="map-pro_img block_str_b"> <strong>Blocks</strong> <?php echo $farms_val['blocks'];?> <span><img src="../img/pro_01.png"> <img src="img/pro_02.png"> <img src="img/pro_03.png">  <img src="img/pro_04.png"></span></div>
                    </td>
                    <td width="35%"><?php if($farms_val['price']<0){$colorclass="red_color";}else{$colorclass="green_color";}?>
                        <div class="right-section-table">
                            <span class="price <?php echo $colorclass;?>">$<?php echo $farms_val['price'];?></span><span class="sign-s"><img src="../img/up-arrow.png"></span><span class="sign-s"><img src="../img/dwon_arrow.png"></span> 
                            <span class="go-btn">Go</span>
                        </div>
                    </td>
                </tr>
<?php }?>
            
            </tbody>
        </table>
    </div>
    
        </div>
<?php  
include ('../footer.php');  ?>
<?php include ('footer-bottom.php');  ?>
 <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
     <script>
      // This example requires the Drawing library. Include the libraries=drawing
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 8
        });

        var drawingManager = new google.maps.drawing.DrawingManager({
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
        drawingManager.setMap(map);
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtSC1AKuhbEyTNhhFnovCH38CJB93r1Ik&libraries=drawing&callback=initMap" async defer></script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtSC1AKuhbEyTNhhFnovCH38CJB93r1Ik&callback=initMap"
  type="text/javascript"></script>
<!--AIzaSyBtSC1AKuhbEyTNhhFnovCH38CJB93r1Ik->