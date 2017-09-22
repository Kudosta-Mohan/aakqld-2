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
	<div class="alert alert-info alert-remove" style="display:none;">
	  <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully removed 
	</div>
	<div class="cst_table">
	<div class="clearfix"></div>
		<div class="border-bottom">
			<div class="text-left">Farms</div>
			<div class="text-right"><a href="<?php echo home_base_url(); ?>settings/add_farm.php" class="btn btn-default btn-success">Add Farm</a>	</div>
		</div>
		<div class=""></div>
		<table class="points_table tabledata table" width="100%">
			<thead>
				<tr><th width="65%">Name</th>
					<th></th>
					<th width="35%" class="text-right">Price</th>
				</tr>
			</thead>
			<tbody class="points_table_scrollbar">
			<?php foreach($farms_value as $farms_val){?>
                <tr id="farm-<?php echo $farms_val['id']; ?>">
                	<td width="40%" class="kd-m-withST">
                    	<p><?php echo $farms_val['name'];?> </p>
                        
                       	<div class="map-pro_img"><span class="map_img-t map_img-t_forms"><img src="../img/map_img.png" /></span><span><img src="../img/pro_01.png"> <img src="../img/pro_02.png"> <img src="../img/pro_03.png">  <img src="../img/pro_04.png"></span></div>
                    </td>
                    <td width="15%" class="kd-m-withOF"><span class="block_text_o"><?php echo $farms_val['blocks'];?> Blocks</span></td>
					<td width="45%" class="kd-m-withFR"><?php if($farms_val['price']<0){$colorclass="red_color";}else{$colorclass="green_color";}?>
                    	<div class="right-section-table-setting">
                            <a href="<?php echo home_base_url(); ?>settings/edit_farm.php?id=<?php echo $farms_val['id']; ?>" class="edit_btn"><span class="hidden-xs-mobile">Edit Farm</span><i class="fa fa-pencil visible-xs-mobile" aria-hidden="true"></i></a>
                            <a href="javascript:void(0);" class="remove_btn" onclick="removeFarm('<?php echo $farms_val["id"]; ?>');"><span class="hidden-xs-mobile">Remove Farm</span><i class="fa fa-trash-o visible-xs-mobile" aria-hidden="true"></i></a>
                        </div>
                    </td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
function removeFarm(fid)
{
	var con = confirm('Are you sure to remove this Farm!');
	if(con == true)
	{
		jQuery.ajax({
		    type: "POST",
		    url: '<?php echo home_base_url(); ?>class/class.ajax.php?action=removefarm',
		    data: {'id': fid},
		    cache: false,
		    success: function(result){ 
				$('#farm-'+fid).remove();
				$('.alert-remove').show();
			}
	    });
	}
}
</script>
<?php include ('../footer.php');  ?>
<?php include ('../footer-bottom.php');  ?>