<?php require_once 'dbconfig.php';

include ('header.php');

if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}

?>

<?php $farms_value= $farms->getfarmslist();
?>
 <div class="container">

      <h2 class="text-center">Dashboard</h2>
      <div class="kd_block">
    	<ul class="list-inline text-center">
            <li><img class="img-responsive" src="img/crop_input.png" width="163" height="123"></li>
            <li><img class="img-responsive" src="img/quality_assuarnce.png" width="163" height="123"></li>
            <li><img class="img-responsive" src="img/packing.png" width="163" height="123"> </li>
            <li><img class="img-responsive" src="img/sales.png" width="163" height="123"></li>
    	</ul>
        </div>
        <div class="clearfix"></div>
        
		<div class="cst_table">
		<table class="points_table tabledata table" width="100%">
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
                    	<p><?php echo $farms_val['name'];?></p>
                        
                       	<div class="map-pro_img"><span class="map_img-t"><img src="img/map_img.png" /></span><span class="block_text"><?php echo $farms_val['blocks'];?> Blocks</span><span><img src="img/pro_01.png"> <img src="img/pro_02.png"> <img src="img/pro_03.png">  <img src="img/pro_04.png"></span></div>
                    
                    </td>
					<td width="35%"><?php if($farms_val['price']<0){$colorclass="red_color";}else{$colorclass="green_color";}?>
                    	<div class="right-section-table">
                            <span class="price <?php echo $colorclass;?>">$<?php echo $farms_val['price'];?></span><span class="sign-s"><img src="img/up-arrow.png"></span><span class="sign-s"><img src="img/dwon_arrow.png"></span> 
                            <span class="go-btn"><a href="<?php echo home_base_url(); ?>settings/view_farm.php?id=<?php echo $farms_val['id']; ?>">Go</a></span>
                        </div>
                    </td>
				</tr>
<?php }?>
			

			</tbody>
		</table>
	</div>
    
    	</div>
<?php  
include ('footer.php');  ?>
<?php include ('footer-bottom.php');  ?>



