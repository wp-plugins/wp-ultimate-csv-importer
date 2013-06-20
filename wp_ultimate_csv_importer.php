<?php
/*
*Plugin Name: WP Ultimate CSV Importer
*Plugin URI: http://www.smackcoders.com/blog/how-to-guide-for-free-wordpress-ultimate-csv-importer-plugin.html
*Description: A plugin that helps to import the data's from a CSV file.
*Version: 3.1.0
*Author: smackcoders.com
*Author URI: http://www.smackcoders.com
*
* Copyright (C) 2013 Smackcoders (www.smackcoders.com)
*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @link http://www.smackcoders.com/blog/category/free-wordpress-plugins
***********************************************************************************************
*/

require_once (dirname ( __FILE__ ) . '/../../../wp-load.php');

require_once ("SmackImpCE.php");
$impCE = new SmackImpCE ();

require_once ("languages/" . $impCE->user_language () . ".php");

// Admin menu settings
function wp_ultimate_csv_importer() {
	add_menu_page ( 'CSV importer settings', 'WP Ultimate CSV Importer', 'manage_options', 'upload_csv_file', 'upload_csv_file', WP_CONTENT_URL . "/plugins/wp-ultimate-csv-importer/images/icon.png" );
}
function LoadWpScript() {
	wp_register_script ( 'wp_ultimate_scripts', WP_CONTENT_URL . "/plugins/wp-ultimate-csv-importer/wp_ultimate_csv_importer.js", array (
			"jquery" 
	) );
	wp_enqueue_script ( 'wp_ultimate_scripts' );
}

add_action ( 'admin_enqueue_scripts', 'LoadWpScript' );
add_action ( "admin_menu", "wp_ultimate_csv_importer" );

/**
 * Home page layout and importer
 */
function upload_csv_file() {
	global $impCE;
	global $custom_array;
	global $wpdb;
	
	$importdir = $impCE->getUploadDirectory ();
	
	/*
	 * Get POST data
	*/
	if (isset ( $_POST ['delim'] ) && in_array ( $_POST ['delim'], $impCE->delim_avail ))
		$impCE->delim = $_POST ['delim'];
	
	if (isset ( $_POST ['titleduplicatecheck'] ) && trim($_POST ['titleduplicatecheck'] != ""))
		$impCE->titleDupCheck = true;
	
	if (isset ( $_POST ['contentduplicatecheck'] ) && trim($_POST ['contentduplicatecheck'] != ""))
		$impCE->conDupCheck = true;
	
	$custom_array = array ();
	if (isset ( $_POST ['Import'] )) {
		$data_rows = $impCE->csv_file_data ( $_FILES ['csv_import'] ['tmp_name'], $impCE->delim );
		$impCE->move_file ();
		
		echo renderTop(); 
		 if ( count($impCE->headers)>=1 &&  count($data_rows)>=1 ){?>
<div style="float: left; min-width: 45%">
	<form class="add:the-list: validate" method="post"
		onsubmit="return import_csv();">
		<h3>Import Data Configuration</h3>
		<div style="margin-top: 30px;">
			<table><tr><td>
			<input name="_csv_importer_import_as_draft" type="hidden"
				value="publish" /> <label> Select Post Type </label></td><td> <select
				name='csv_importer_cat'>
					<?php
			foreach ( get_post_types () as $key => $value ) {
				if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item')) {
					?>
								<option id="<?php echo($value);?>"> <?php echo($value);?> </option>
					<?php
				
}
			}
			?>
				</select> </td></tr><tr><td>
	<?php $cnt1 = count($impCE->headers);?>
	
	<label>Import with post status</label></td> <td><select
				name='importallwithps' id='importallwithps'
				onchange='importAllPostStatus(this.value, "<?php echo $cnt1?>")'>
				<option value='0'>Status as in CSV</option>
				<option value='1'>Publish</option>
				<option value='2'>Sticky</option>
				<option value='4'>Private</option>
				<option value='3'>Protected</option>
				<option value='5'>Draft</option>
				<option value='6'>Pending</option>
			</select></td></tr></table>
		</div>
		<br />
		<label id='passwordlabel' style="display:none">Password</label>&nbsp;&nbsp;
		<input type='text' id='postsPassword' name='postsPassword' value = 'admin' style="display:none">
		<label id='passwordhint' style="display:none;color:red;"><?php echo $impCE->t("PASSWORD_HINT"); ?></label>
		<h3>Mapping the Fields</h3>
		<div id='display_area'>
				<?php $cnt =count($impCE->defCols) +2;  ?>
				<input type="hidden" id="h1" name="h1" value="<?php echo $cnt; ?>" />
			<input type="hidden" id="h2" name="h2" value="<?php echo $cnt1; ?>" />
			<input type="hidden" id="titleduplicatecheck"
				name="titleduplicatecheck"
				value="<?php echo $_POST['titleduplicatecheck'] ?>" /> <input
				type="hidden" id="contentduplicatecheck"
				name="contentduplicatecheck"
				value="<?php echo $_POST['contentduplicatecheck'] ?>" /> <input
				type="hidden" id="delim" name="delim"
				value="<?php echo $_POST['delim']; ?>" /> <input type="hidden"
				id="header_array" name="header_array"
				value="<?php print_r($impCE->headers);?>" />
			<table style="font-size: 12px;">
				 <?php
			$count = 0;
			foreach ( $impCE->headers as $key => $value ) {
				?>
				 <tr>
					<td><label><?php print($value);?></label></td>
					<td><select name="mapping<?php print($count);?>"
						id="mapping<?php print($count);?>" class='uiButton'
						onchange="addcustomfield(this.value,<?php echo $count; ?>);">
							<option id="select">-- Select --</option>
				    <?php
				foreach ( $impCE->defCols as $key1 => $value1 ) {
					?>
						<option value="<?php print($key1);?>">
					<?php
					
if ($key1 != 'post_name')
						print ($key1) ;
					else
						print 'post_slug';
					?>
						</option>
					    <?php
				
}
				foreach ( get_taxonomies () as $taxokey => $taxovalue ) {
					if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') {
						?>
						<option value="<?php print($taxokey);?>"><?php print($taxovalue);?></option>
				    <?php
					
}
				}
				?>
					<option value="add_custom<?php print($count);?>">Add Custom Field</option>
					</select> <!-- added to solve issue id 1072--> <input type="text"
						id="textbox<?php print($count); ?>"
						name="textbox<?php print($count); ?>" style="display: none;"
						value="<?php echo $value ?>" /> <span
						style="display: none; color: red; margin-left: 5px;"
						id="customspan<?php echo $count?>">Name this Field</span>
					</td>
				</tr>
				 <?php
				$count ++;
			}
			?>
				</table>
		</div>
		<br /> <input type='hidden' name='filename' id='filename'
			value="<?php echo($_FILES['csv_import']['name']);?>" /> <input
			type='submit' class='button-primary' name='post_csv' id='post_csv'
			value='Import' />
	</form>
</div>
<div style="min-width: 45%;">
				<?php print(renderDesc()); ?>
			</div>
<?php
		} else {
			?>
<div style="font-size: 16px; margin-left: 20px;"><?php echo $impCE->t("CHOOSE_ANOTHER_DELIMITER"); ?>
			</div>
<br />
<div style="margin-left: 20px;">
	<form class="add:the-list: validate" method="post" action="">
		<input type="submit" class="button-primary" name="Import Again"
			value="Import Again" />
	</form>
</div>
<div style="margin-left: 20px; margin-top: 30px;">
	<b><?php echo $impCE->t("NOTE"); ?> :-</b>
	<p>1. <?php echo $impCE->t("NOTE_CONTENT_1"); ?></p>
	<p>2. <?php echo $impCE->t("NOTE_CONTENT_2"); ?></p>
</div>
<?php
		
}
	} else if (isset ( $_POST ['post_csv'] )) {
		
		$impCE->processDataInWP();
		
		if (( $impCE->insPostCount != 0) || ($impCE->dupPostCount != 0)) {
			?>
		<div
			style="background-color: #FFFFE0; border-color: #E6DB55; border-radius: 3px 3px 3px 3px; border-style: solid; border-width: 1px; margin: 5px 15px 2px; padding: 5px; text-align: center">
			<b> <?php echo '('.$impCE->insPostCount.')'; ?> records are successfully Imported !<br> <?php echo '('.$impCE->dupPostCount.')'; ?> duplicate records found !
					<?php if($impCE->noPostAuthCount != 0){ ?><br>
					<?php echo $impCE->noPostAuthCount; ?> posts with no valid UserID/Name are assigned admin as author.
					<?php
						// ask and fill the error msg
					}
					?></b>
		</div>
		<?php }else if(($impCE->insPostCount == 0) && ($impCE->dupPostCount == 0)){ ?>
		<div
			style="background-color: #FFFFE0; border-color: #E6DB55; border-radius: 3px 3px 3px 3px; border-style: solid; border-width: 1px; margin: 5px 15px 2px; padding: 5px; text-align: center">
			<b> Check your CSV file and format. </b>
		</div>
		<?php } ?>
		<div style="margin-top: 30px; margin-left: 10px">
			<form class="add:the-list: validate" method="post"
				enctype="multipart/form-data">
				<input type="submit" class='button-primary' id="goto" name="goto"
					value="Continue" />
			</form>
		</div>
		<?php
		
	} else {
		?>
<div class="wrap">
			     <?php echo renderTop(); ?>

			     <div style="min-width: 45%; float: left; height: 500px;">
		<h2><?php echo $impCE->t('IMPORT_CSV_FILE'); ?></h2>
		<form class="add:the-list: validate" method="post"
			enctype="multipart/form-data" onsubmit="return file_exist();">

			<!-- File input -->
			<p>
				<label for="csv_import"><?php echo $impCE->t('UPLOAD_FILE'); ?>:</label><br />
				<input name="csv_import" id="csv_import" type="file" value=""
					required="required" />
			</p>
			<br />
			<p>
				<input type="checkbox" name="titleduplicatecheck" value=1>  <?php echo $impCE->t("ENABLE_DUPLICATION_POST_TITLE"); ?><br>
			
			
			<p>
				<input type="checkbox" name="contentduplicatecheck" value=1>  <?php echo $impCE->t("ENABLE_DUPLICATION_POST_CONTENT"); ?> <br>
				<!-- added above checkboxes for issueid 1057-->
			
			<p>
				<label id="duplicatehint" style="color:red;" ><?php echo $impCE->t("ENABLE_DUPLICATION_HINT");?></label>
				<br>

			<p>
				<label>Delimiter</label>&nbsp;&nbsp;&nbsp; <select name="delim"
					id="delim">
					<option value=",">,</option>
					<option value=";">;</option>
				</select>
			</p>

			<p class="submit">
				<input type="submit" class="button-primary" name="Import"
					value="Import" />
			</p>
		</form>
	</div>
	<div style="min-width: 45%;">
				<?php print(renderDesc()); ?>
			     </div>
</div>
<!-- end wrap -->
<?php
	}
}


/**
 * Function to display the plugin home description
 */
function renderDesc() {
	return "<p>WP Ultimate CSV Importer Plugin helps you to manage the post,page and </br> custom post data's from a CSV file.</p>
					<p>1. Admin can import the data's from any csv file.</p>
					<p>2. Can define the type of post and post status while importing.</p>
					<p>3. Provides header mapping feature to import the data's as your need.</p>
					<p>4. Users can map coloumn headers to existing fields or assign as custom fileds.</p>
					<p>5. Import unlimited datas as post.</p>
					<p>6. Make imported post as published or make it as draft.</p>
					<p>7. Added featured image import functionality.</p>
					<p><b> Important Note:- </b></p>
					<p><span style='color:red;'>1. Your csv should have the seperate column for post_date.
					<br/>2. It must be in the following format. ( yyyy-mm-dd hh:mm:ss ).</span></p>
					<p>Configuring our plugin is as simple as that. If you have any questions, issues and request on new features, plaese visit <a href='http://www.smackcoders.com/blog/category/free-wordpress-plugins' target='_blank'>Smackcoders.com blog </a></p>
					<div align='center' style='margin-top:40px;'> 'While the scripts on this site are free, donations are greatly appreciated. '<br/><br/><a href='http://www.smackcoders.com/donate.html' target='_blank'><img src='" . WP_CONTENT_URL . "/plugins/wp-ultimate-csv-importer/images/paypal_donate_button.png' /></a><br/><br/><a href='http://www.smackcoders.com/' target='_blank'><img src='http://www.smackcoders.com/wp-content/uploads/2012/09/Smack_poweredby_200.png'></a>
					</div><br/>";
}

/**
 * To render the top section
 */
function renderTop() {
	return "<marquee onmouseover=\"this.setAttribute('scrollamount', 0, 0);\" onmouseout=\"this.setAttribute('scrollamount', 6, 0);\" >Now the <a href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html' target='_blank'>Pro Version</a> is Available. For more details,please visit <a href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html' target='_blank'>here</a></marquee>
		<div style='background-color: #FFFFE0;border-color: #E6DB55;border-radius: 3px 3px 3px 3px;border-style: solid;border-width: 1px;margin: 5px 15px 2px; margin-top:15px;padding: 5px;text-align:center'> Please check out <a href='http://www.smackcoders.com/blog/category/free-wordpress-plugins' target='_blank'>www.smackcoders.com</a> for the latest news and details of other great plugins and tools. </div><br/>";
}

?>
