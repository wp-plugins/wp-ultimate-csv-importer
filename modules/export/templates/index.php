<?php
/******************************************************************************************
 * Copyright (C) Smackcoders 2014 - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/
?>
<?php 
$siteurl = get_option('siteurl');

?>

 <div class="accordion" id="accordion2" style = 'width:98%;'>
                        <div class="accordion-group">

                           <div id="collapseTwo" class="accordion-body in collapse">
				<span style="margin: 4% 0px 4% 22%; color: red; font-weight: bold;" name="warning" id="warning" ><p><marquee onmouseover="this.stop();" onmouseout="this.start();"><span>Check your system configuration before proceeding the export. It may help to prevent from facing server configuration issues</span><span style='position:relative;left:4px;'><a href='<?php echo $siteurl?>/wp-admin/admin.php?page=wp-ultimate-csv-importer/index.php&__module=settings'>Click here</a> to refer your server configuration.</span></marquee></p></span>

                           <div class="accordion-inner">
<div>
        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/export.png" width="24" height="24" /> </span>
     
   <label> <h3 id="exporttitle">Export Data With Advanced Filters</h3></label>
</div>
<div style="margin-left:20px;">
	<form class="form-horizontal" method="post" name="exportmodule" action="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>modules/export/templates/export.php" onsubmit="return export_module();"> 	
<!--	<form class="form-horizontal" method="post" name="exportmodule" action="" onsubmit="return export_module();"> -->
	<div class="table-responsive">
	<table style='width:100%;' class='table exportmodule'>
	<th colspan='2'><label class='h-exportmodule'><h3 id="innertitle"> To export data based on the filters</h3></label></th>
	<tr>
	<td><label><input type='checkbox' name='getdataforspecificperiod' id='getdataforspecificperiod' value='getdataforspecificperiod' onclick='addexportfilter(this.id);' /><span id="align"> Export data for the specific period</span></label>
	<div id='specificperiodexport' style='padding:10px;display:none;'> 
	<label id='periodstartfrom'><b> Start From </b></label>
	<input type='text' class='form-control' name='postdatefrom' style='cursor:default;width:25%;' readonly id='postdatefrom' value='' />
        <label id='periodendto'><b> End To </b></label>
        <input type='text' class='form-control' name='postdateto' style='cursor:default;width:25%;' readonly id='postdateto' value='' />
	</div>
	</td>
        </tr>
	<tr>
	<td><label><input type='checkbox' name='getdatawithspecificstatus' id='getdatawithspecificstatus' value='getdatawithspecificstatus' onclick='addexportfilter(this.id);' /><span id="align"> Export data with the specific status</span></label>
	<div id='specificstatusexport' style='padding:10px;display:none;'>
	<label id='status'><b> Status </b></label>
	<select name='postwithstatus' id='postwithstatus'>
	<option>All</option>
        <option>Publish</option>
        <option>Sticky</option>
        <option>Private</option>
        <option>Protected</option>
        <option>Draft</option>
        <option>Pending</option>
	</select>
	</div>
	</td>
	</tr>
	<tr>
	<td><label><input type='checkbox' name='getdatabyspecificauthors' id='getdatabyspecificauthors' value='getdatabyspecificauthors' onclick='addexportfilter(this.id);' /><span id="align"> Export data by specific authors</span></label>
	<div id='specificauthorexport' style='padding:10px;display:none;'>
	<label id='authors'><b> Authors </b></label>
	<?php $blogusers = get_users( 'blog_id=1&orderby=nicename' ); ?>
	<select name='postauthor' id='postauthor' >
	<option value='0'>All</option>
	<?php foreach( $blogusers as $user ) { ?>
		<option value='<?php echo esc_html( $user->ID ); ?>'> <?php echo esc_html( $user->display_name ); ?> </option>	
	<?php } ?>
	</select>
	</div>
	</td>
	</tr>
	</table>
	<script type = 'text/javascript'> 
		jQuery(document).ready(function() {
			jQuery('#postdatefrom').datepicker({
				dateFormat : 'yy-mm-dd'
			});
			jQuery('#postdateto').datepicker({
                                dateFormat : 'yy-mm-dd'
                        });
		});
	</script>
	</div>
	<div class="table-responsive" id="exporttable">
	<table class='table exportmodule'>
	<th colspan='2'><label class='h-exportmodule'><h3 id="innertitle">Select your module to export the data </h3></label></th>
	<tr>
		<td class='exportdatatype'><label> <input type="radio" name="export" value="post"><span id="align"> Post</span> </label></td>
	        <td class='exportdatatype'><label> <input type="radio" name="export" value="eshop"><span id="align"> Eshop</span> </label></td>
	</tr>
	<tr>
		<td class='exportdatatype'><label> <input type="radio" name="export" value="page"><span id="align"> Page</span> </label></td>
	        <td class='exportdatatype'><label> <input type="radio" name="export" value="wpcommerce"><span id="align"> Wp-Commerce</span></label></td>
	</tr>
	<tr>
	<td class='exportdatatype'>
	<label> <input type="radio" name="export" value="custompost"><span id="align"> Custom Post</span></label>
		<select name="export_post_type" style="margin-left:10px">
		<option>--Select--</option>
		<?php
			foreach (get_post_types() as $key => $value) {
				if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group') && ($value != 'product') && ($value != 'product_variation') && ($value != 'shop_order') && ($value != 'shop_coupon') && ($value != 'acf')) {
				?>
					<option id="<?php echo($value); ?>"> <?php echo($value); ?> </option>
				<?php
				}
			}
		?>
	</select>
	</td>
	<td class='exportdatatype'><label> <input type="radio" name="export" value="woocommerce"><span id="align"> Woo-Commerce</span></label></td>
	</tr>
	<tr>
	<td class='exportdatatype'><label> <input type="radio" name="export" value="category"><span id="align"> Category</span></label></td>
        <td class='exportdatatype'><label> <input type="radio" name="export" value="marketpress"><span id="align"> Marketpress</span></label></td>
	</tr>
	<tr>
	<td class='exportdatatype'><label> <input type="radio" name="export" value="tags"><span id="align"> Tags </label></span></td>
        <td class='exportdatatype'><label> <input type="radio" name="export" value="customerreviews"><span id="align"> Customer Reviews</span></label></td>
	</tr>
	<tr>
	<td class='exportdatatype'>
	<label> <input type="radio" name="export" value="customtaxonomy"><span id="align"> Custom Taxonomy</span></label>
		<select name="export_taxo_type" style="margin-left:10px;">
		<option>--Select--</option>
		<?php
			foreach (get_taxonomies() as $key => $value) {
				if (($value != 'category') && ($value != 'post_tag') && ($value != 'nav_menu') && ($value != 'link_category') && ($value != 'post_format') && ($value != 'product_tag') && ($value != 'wpsc_product_category') && ($value != 'wpsc-variation')) {
				?>
					<option id="<?php echo($value); ?>"> <?php echo($value); ?> </option>
				<?php
				}
			}
		?>
	</select></td>
	<td class='exportdatatype'><label> <input type="radio" name="export" value="comments"><span id="align"> Comments</span></label></td>
	</tr>
	<tr>
	<td class='exportdatatype'><label> <input type="radio" name="export" value="users"><span id="align"> Users</span></label></td>
	<td class='exportdatatype'></td>
	</tr>
	</table>
	</div>
<?php /*
	<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="post"> Post </label>
			</div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="page"> Page </label>
			</div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="custompost"> Custom Post </label>
				<select name="export_post_type">
					<option>--Select--</option>
					<?php
					foreach (get_post_types() as $key => $value) {
						if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group') && ($value != 'product') && ($value != 'product_variation') && ($value != 'shop_order') && ($value != 'shop_coupon') && ($value != 'acf')) {
							?>
							<option id="<?php echo($value); ?>"> <?php echo($value); ?> </option>
						<?php
						}
					}
					?>
				</select></div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="users"> Users </label>
			</div>
		</div>
        <div class='form-group exportedas'><div class='col-sm-3 export_action'><label><input type='radio' name='export' value='comments' id='comments' onclick="export_check(this.value)">Comments </label> </div> </div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="category"> Category
				</label></div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="tags"> Tags </label>
			</div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="customtaxonomy"> Custom
					Taxonomy </label>
				<select name="export_taxo_type">
					<option>--Select--</option>
					<?php
					foreach (get_taxonomies() as $key => $value) {
						if (($value != 'category') && ($value != 'post_tag') && ($value != 'nav_menu') && ($value != 'link_category') && ($value != 'post_format') && ($value != 'product_tag') && ($value != 'wpsc_product_category') && ($value != 'wpsc-variation')) {
							?>
							<option id="<?php echo($value); ?>"> <?php echo($value); ?> </option>
						<?php
						}
					}
					?>
				</select></div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="eshop"> Eshop </label>
			</div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="wpcommerce">
					Wp-Commerce </label></div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="woocommerce">
					Woo-Commerce </label></div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="marketpress">
					Marketpress </label></div>
		</div>
		<div class='form-group exportedas'>
			<div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="customerreviews">
					Customer Reviews </label></div>
		</div>
*/ ?>
		<div class='form-group exportedas'>
			<label class='col-sm-2 control-label'><b> File Name: </b></label>

			<div class='col-sm-6'>
				<input class='form-control' type='text' name='export_filename' id='export_filename' value=''
					   placeholder="export_as_<?php echo(date("Y-m-d")); ?>" size="18">
			</div>
			<div class='col-sm-3'><input type="submit" name="exportbutton" value="Export" class='btn btn-primary'></div>

	</form>
</div>
 </div>
  </div>
    </div>
      </div>
