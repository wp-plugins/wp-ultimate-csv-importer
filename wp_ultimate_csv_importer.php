<?php
/**
 *Plugin Name: WP Ultimate CSV Importer
 *Plugin URI: http://www.smackcoders.com/blog/how-to-guide-for-free-wordpress-ultimate-csv-importer-plugin.html
 *Description: A plugin that helps to import the data's from a CSV file.
 *Version: 3.2.3
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

ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require_once (ABSPATH . 'wp-load.php');

require_once "SmackImpCE.php";
require_once "class.rendercsv.php";
require_once 'class.settings.php';

$impCE = new SmackImpCE ();
$impRen = new RenderCSVCE;
require_once "languages/" . $impCE->user_language() . ".php";

/**
 * Activate the CSV importer free plugin
 */
function wp_ultimate_csv_importer_activate()
{
    require_once 'plugin_config.php';
}

/**
 * Deactivate the CSV importer free plugin
 */
function wp_ultimate_csv_importer_deactivate()
{

}

/**
 * Admin menu settings
 */
function wp_ultimate_csv_importer()
{
    add_menu_page('CSV importer settings', 'WP Ultimate CSV Importer', 'manage_options', 'upload_csv_file', 'upload_csv_file', WP_CONTENT_URL . "/plugins/wp-ultimate-csv-importer/images/icon.png");
}

/**
 *Function to load script files
 */
function LoadWpScript()
{
    if (isset($_REQUEST['page'])) {
        if ($_REQUEST['page'] == 'upload_csv_file') {
            wp_register_script('wp_ultimate_scripts', WP_CONTENT_URL . "/plugins/wp-ultimate-csv-importer/wp_ultimate_csv_importer.js", array("jquery"));
        }
    }

    if (isset($_REQUEST['page'])) {
        if ($_REQUEST['page'] == 'upload_csv_file') {
            wp_enqueue_style('importer_styles', WP_CONTENT_URL . '/plugins/wp-ultimate-csv-importer/css/custom-style.css');
        }
    }

    wp_enqueue_script('wp_ultimate_scripts');

}

add_action('admin_enqueue_scripts', 'LoadWpScript');
add_action("admin_menu", "wp_ultimate_csv_importer");
add_action('after_plugin_row_wp-ultimate-csv-importer/wp_ultimate_csv_importer.php', array('SmackImpCE', 'plugin_row'));

register_activation_hook(__FILE__, 'wp_ultimate_csv_importer_activate');
register_deactivation_hook(__FILE__, 'wp_ultimate_csv_importer_deactivate');

/**
 * Home page layout and importer
 */
function upload_csv_file()
{
	global $impCE, $impRen, $pluginActive, $custo_taxo;
	global $custom_array;
	global $wpdb;
	$mFieldsArr = '';
	if (!isset($_REQUEST['action']) || !$_REQUEST['action']) {
		?>
			<script>
			window.location.href = "<?php echo WP_PLUGIN_URL;?>/../../wp-admin/admin.php?page=upload_csv_file&action=post";
		</script>
			<?php
	}
	$importdir = $impCE->getUploadDirectory();
	if (!$_REQUEST['action'] || (isset($_POST['post_csv']) && $_POST['post_csv']))
		echo "<input type = 'hidden' value ='dashboard' id='requestaction'>";
	else
		echo "<input type='hidden' value='" . $_REQUEST['action'] . "' id ='requestaction'>";
	echo '<input type="hidden" value="' . WP_CONTENT_URL . '" id="contenturl">';
	/*
	 * Get POST data
	 */
	if (isset ($_POST ['delim']) && in_array($_POST ['delim'], $impCE->delim_avail))
		$impCE->delim = $_POST ['delim'];
	if (isset ($_POST ['titleduplicatecheck']) && trim($_POST ['titleduplicatecheck'] != ""))
		$impCE->titleDupCheck = true;

	if (isset ($_POST ['contentduplicatecheck']) && trim($_POST ['contentduplicatecheck'] != ""))
		$impCE->conDupCheck = true;

	$custom_array = array();
	if (isset ($_POST ['Import'])) {
		?>
			<input type="hidden" name="versionedname" id="versionedname" value=""/>
			<input type="hidden" name="fileversion" id="fileversion" value=""/>
			<?php
			$data_rows = $impCE->csv_file_data($_FILES ['csv_import'] ['tmp_name'], $impCE->delim);
		require_once ("SmackImpCE.php");
		$impObj = new SmackImpCE();
		$impObj->move_file();
		?>
			<div class="smack-wrap" id="smack-content">
			<?php
			echo $impRen->renderMenu();
		if (count($impCE->headers) >= 1 && count($data_rows) >= 1) {
			?>
				<form class="add:the-list: validate" name="secondform" id="secondform" method="post" onsubmit="return import_csv();"
				class="secondform">
				<div style="float: left; min-width: 45%">
				<div style="float: left; margin-top: 11px; margin-right: 5px;"><img
				src="<?php echo WP_CONTENT_URL; ?>/plugins/wp-ultimate-csv-importer/images/Importicon_24.png">
				</div>
				<div style="float:left;">
				<h3>Import Data Configuration</h3>
				</div>
				</br>
				<?php $cnt = count($impCE->defCols) + 2;
				$cnt1 = count($impCE->headers); ?>
				<input type="hidden" id="h1" name="h1" value="<?php echo $cnt; ?>"/>
				<input type="hidden" id="h2" name="h2" value="<?php echo $cnt1; ?>"/>
				<input type="hidden" id="prevoptionindex" name="prevoptionindex" value=""/>
				<input type="hidden" id="prevoptionvalue" name="prevoptionvalue" value=""/>
				<?php
				// second form starts here
				if (($_REQUEST['action'] == 'post') || ($_REQUEST['action'] == 'custompost') || ($_REQUEST['action'] == 'page')) {

					//set custom fields value
					$taxo = get_taxonomies();
					foreach ($taxo as $taxokey => $taxovalue) {
						if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format' && $taxokey != 'product_tag' && $taxokey != 'wpsc_product_category' && $taxokey != 'wpsc-variation') {
							$custo_taxo .= $taxokey . ',';
						}
					}
					$custo_taxo = substr($custo_taxo, 0, -1);

					?>
						<input type='hidden' name='cust_taxo' id='cust_taxo' value='<?php echo $custo_taxo; ?>'/>
						<div id="posttypecss" style="margin-top: 30px;">
						<table>
						<tr>
						<td>
						<input name="_csv_importer_import_as_draft" type="hidden" value="publish"/>
						<?php if($_REQUEST['action'] == 'custompost'){ ?>
							<label> Select Post Type<span class="mandatory"> *</span> </label></td>
								<td>
								<select name='csv_importer_cat' id='csv_importer_cat'>
								<option>-- Select --</option>
								<?php
								foreach (get_post_types() as $key => $value) {
									if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group')) {
										?>
											<option id="<?php echo($value); ?>"> <?php echo($value);?> </option>
											<?php
									}
								}
							?>
								</select>
								<a href="#" class="tooltip">
								<img src="../wp-content/plugins/wp-ultimate-csv-importer/images/help.png" />
								<span class="tooltipOne">
								<img class="callout" src="../wp-content/plugins/wp-ultimate-csv-importer/images/callout.gif" />
								<strong>Select a custom post type to import</strong>
								<img src="../wp-content/plugins/wp-ultimate-csv-importer/images/help.png" style="margin-top: 6px;float:right;" />
								</span>
								</a>

								<?php } else if ($_REQUEST['action'] == 'post' || $_REQUEST['action'] == 'page') { ?>
									<input name='csv_importer_cat' type='hidden' id='csv_importer_cat'
										value=<?php echo $_REQUEST['action']; ?>>
										<?php } ?>
										</td>
										</tr>
										<tr>
										<td>
										<?php $cnt1 = count($impCE->headers);?>

										<label>Import with post status<span class="mandatory"> *</span></label></td>
										<td><select
										name='importallwithps' id='importallwithps'
										onchange='importAllPostStatus(this.value, "<?php echo $cnt1 ?>")'>
										<option value='0'>Status as in CSV</option>
										<option value='1'>Publish</option>
										<option value='2'>Sticky</option>
										<option value='4'>Private</option>
										<option value='3'>Protected</option>
										<option value='5'>Draft</option>
										<option value='6'>Pending</option>
										</select>
										<a href="#" class="tooltip">
										<img src="../wp-content/plugins/wp-ultimate-csv-importer/images/help.png" /> 									      <?php if($_REQUEST['action'] == 'custompost'){?>
										<span class="tooltipTwo">
										<?php } else { ?>
										<span class="tooltipFive">
										<?php } ?>
										<img class="callout" src="../wp-content/plugins/wp-ultimate-csv-importer/images/callout.gif" />
										<strong>Select a status for the post imported, if not defined within your csv. E.g. Publish</strong>
										<img src="../wp-content/plugins/wp-ultimate-csv-importer/images/help.png" style="margin-top: 6px;float:right;" />
										</span>
										</a>
										</td>
										</tr>
										<tr>
										<td>
										<label id='passwordlabel' style="display:none;">Password</label></td>
										<td>
										<input type='text' id='postsPassword' name='postsPassword' value='admin'
										style="display:none;">
										<span style="display: none; color: red; margin-left: 5px;" id='passwordhint'
										style="display:none;">Replace the default value</span>
										</td>
										</tr>
										</table>
										</div>
										<div style="width:50%;float:left;"><h3>Map Fields</h3></div><div style="width:50%;float:right;"><input type="button" name="remap" id="remap" value="Clear Mapping" onclick="clearmapping();" />
										<a href="#" class="tooltip">
										<img src="../wp-content/plugins/wp-ultimate-csv-importer/images/help.png" />
										<span class="tooltipThree">
										<img class="callout" src="../wp-content/plugins/wp-ultimate-csv-importer/images/callout.gif" />
										<strong>Refresh to re-map fields</strong>
										<img src="../wp-content/plugins/wp-ultimate-csv-importer/images/help.png" style="margin-top: 6px;float:right;" />
										</span>
										</a></div></br></br></br>
										<div id='display_area'>
										<?php $cnt = count($impCE->defCols) + 2;  ?>
										<input type="hidden" id="h1" name="h1" value="<?php echo $cnt; ?>"/>
										<input type="hidden" id="h2" name="h2" value="<?php echo $cnt1; ?>"/>
										<?php if (isset($_POST['titleduplicatecheck'])) { ?>
											<input type="hidden" id="titleduplicatecheck"
												name="titleduplicatecheck"
												value="<?php echo $_POST['titleduplicatecheck'] ?>"/>
												<?php } ?>
												<?php if (isset($_POST['contentduplicatecheck'])) { ?>
													<input type="hidden" id="contentduplicatecheck" name="contentduplicatecheck"
														value="<?php echo $_POST['contentduplicatecheck'] ?>"/>
														<?php }?>
														<input type="hidden" id="delim" name="delim" value="<?php echo $_POST['delim']; ?>"/> <input type="hidden" id="header_array" name="header_array" value="<?php print_r($impCE->headers); ?>"/>
														<table style="font-size: 12px;">
														<?php
														$count = 0;
							if ($_REQUEST['action'] == 'page') {
								unset($impCE->defCols['post_category']);
								unset($impCE->defCols['post_tag']);
							}
							foreach ($impCE->headers as $key => $value) {
								?>
									<tr>
									<td><label><?php print($value);?></label></td>
									<td><select name="mapping<?php print($count); ?>"
									id="mapping<?php print($count); ?>" class='uiButton'
									onchange="addcustomfield(this.value,<?php echo $count; ?>);">
									<option id="select">-- Select --</option>
									<?php
									foreach ($impCE->defCols as $key1 => $value1) {
										if ($key1 == 'post_name')
											$key1 = 'post_slug';
										$strip_CF = strpos($key1, 'CF: '); //Auto mapping
										if ($strip_CF === 0) {
											$custom_key = substr($key1, 4);
										}
										?>
											<option value="<?php print($key1); ?>">
											<?php
											if ($key1 != 'post_name'){
												print ($key1);
												$mappingFields_arr[$key1] = $key1;
											}else{
												print 'post_slug';
												$mappingFields_arr['post_slug'] = 'post_slug';
											}
										?>
											</option>
											<?php

									}
								foreach (get_taxonomies() as $taxokey => $taxovalue) {
									if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') {
										?>
											<option
											value="<?php print($taxokey); ?>"><?php print($taxovalue);?></option>
											<?php
											$mappingFields_arr[$taxovalue] = $taxovalue;

									}
								}
								?>
									<option value="add_custom<?php print($count); ?>">Add Custom Field
									</option>

									</select> <!-- added to solve issue id 1072-->
									<input class="customfieldtext" type="text"
									id="textbox<?php print($count); ?>"
									name="textbox<?php print($count); ?>" TITLE="Replace the default value" style="display: none;" value="<?php echo $value ?>"/>
<span style="display: none;" id="customspan<?php echo $count ?>">
<a href="#" class="tooltip">
    <img src="../wp-content/plugins/wp-ultimate-csv-importer/images/help.png" />
    <span class="tooltipFour">
        <img class="callout" src="../wp-content/plugins/wp-ultimate-csv-importer/images/callout.gif" />
        <strong>Give a name for your new custom field</strong>
        <img src="../wp-content/plugins/wp-ultimate-csv-importer/images/help.png" style="margin-top: 6px;float:right;" />
    </span>
</a> 
</span>
									<!--<span style="display: none; color: red; margin-left: 5px;"
									id="customspan<?php //echo $count ?>">Replace the default value</span>-->
									</td>
									</tr>
									<?php
									$count++;
							}
							foreach($mappingFields_arr as $mkey => $mval){
								$mFieldsArr .= $mkey.',';
							}
							$mFieldsArr = substr($mFieldsArr, 0, -1);
							?>
								</table>
								<input type="hidden" id="mapping_fields_array" name="mapping_fields_array" value="<?php print_r($mFieldsArr); ?>"/>
								</div>
								<br/>
								<?php
				} elseif ($_REQUEST['action'] == 'dashboard') {
					echo $impRen->renderDashboard();
				} elseif ($_REQUEST['action'] == 'help') {

				}


			//second form exits ?>
			<input type='hidden' name='filename' id='filename'
				value="<?php echo($_FILES['csv_import']['name']); ?>"/>
				<?php
				$explodeCsv = explode('.csv', $_FILES['csv_import']['name']);
			$exactFIlename = $explodeCsv[0] . '-' . $_REQUEST['action'] . '.csv';
			?>
				<input type='hidden' name='realfilename' id='realfilename'
				value="<?php echo($_FILES['csv_import']['name']); ?>"/>
				<input type="hidden" name="version" id="version" value=""/>
				<input type='hidden' name='selectedImporter' id='selectedImporter'
				value="<?php echo $_REQUEST['action']; ?>"/>
				<input type='hidden' name='delim' id='delim' value="<?php echo($_POST['delim']) ?>"/>
				<button type='submit' class="action" name='post_csv' id='post_csv' value='Import'>Import</button>

				</form>
				</div>
				<div style="min-width: 45%;float:right;margin-right:9px;">
				<div style="width:100%;">
				<div style="float:left;"><h3>CSV Mapping Headers Explained</h3></div>
				<div style="float:right;margin-top:15px;margin-right:25px;"><a
				href="http://www.smackcoders.com/blog/wordpress-ultimate-csv-importer-csv-sample-files-and-updates.html"
				target="_blank">Download Sample Files Here</a></div>
				</div>
				<a href="<?php echo WP_CONTENT_URL; ?>/plugins/wp-ultimate-csv-importer/images/HeadersExplained.jpeg"
				target="_blank" title="Headers Explained"><img
				src="<?php echo WP_CONTENT_URL; ?>/plugins/wp-ultimate-csv-importer/images/HeadersExplained.jpeg"
				width=600 style='border:1px solid;padding:2px;'/></a>
				</div>
				<?php
		} else {
			?>
				<div style="font-size: 16px; margin-left: 20px;"><?php echo $impCE->t("CHOOSE_ANOTHER_DELIMITER"); ?>
				</div>
				<br/>
				<div style="margin-left: 20px;">
				<form class="add:the-list: validate" method="post" action="">
				<input type="submit" class="button-primary" name="Import Again"
				value="Import Again"/>
				</form>
				</div>
				<div style="margin-left: 20px; margin-top: 30px;">
				<b><?php echo $impCE->t("NOTE"); ?> :-</b>

				<p>1. <?php echo $impCE->t("NOTE_CONTENT_1"); ?></p>

				<p>2. <?php echo $impCE->t("NOTE_CONTENT_2"); ?></p>
				</div>
				<?php
		}
	} else if ((isset ($_POST ['post_csv'])) && (($_REQUEST['action'] == 'post') || ($_REQUEST['action'] == 'page') || ($_REQUEST['action'] == 'custompost'))) {
		//third form starts here
		$impCE->processDataInWP();
		echo $impRen->renderMenu();
		if (($impCE->insPostCount != 0) || ($impCE->dupPostCount != 0)) {
			?>
				<div>

				<?php
				echo $impRen->setDashboardAction();
			$messageString = $impCE->insPostCount . " records are successfully Imported.";
			if ((isset($_POST['titleduplicatecheck']) && $_POST['titleduplicatecheck'] == 1) || (isset($_POST['contentduplicatecheck']) && $_POST['contentduplicatecheck'] == 1))
				$messageString .= $impCE->dupPostCount . " duplicate records found.";
			if (($impCE->noPostAuthCount != 0) && (in_array('post_author', $_POST)))
				$messageString .= '<br>' . $impCE->noPostAuthCount . " posts with no valid UserID/Name are assigned admin as author.";
			echo $impRen->showMessage('success', $messageString); ?>
				</div>
				<?php
		} else if (($impCE->insPostCount == 0) && ($impCE->dupPostCount == 0)) {
			?>
				<div>
				<?php echo $impRen->showMessage('error', "Check your CSV file and format."); ?>
				</div>
				<?php } ?>
				<?php
				$_REQUEST['action'] = 'dashboard';
		echo $impRen->renderDashboard();
	} else {
		?>
			<!-- first form gets displayed from here -->
			<?php
			if (!$_REQUEST['action']) {
				$_REQUEST['action'] = 'post';
			} elseif ($_REQUEST['action'] == 'dashboard') {
				echo $impRen->renderMenu();
				echo $impRen->renderDashboard();
			} elseif ($_REQUEST['action'] == 'settings') {
				echo $impRen->renderMenu();
				if ($pluginActive != null)
					echo $impRen->showMessage('error', $pluginActive);
				echo $impRen->renderSettings();
			} elseif ($_REQUEST['action'] == 'custompost') {
				?>
				<div class="wrap" id="smack-content">
				<?php
				echo $impRen->renderMenu();
				$sett = new IMPSettings();
					?>
						<div class="smack-postform">
						<?php echo $impRen->renderPostPage(); ?>
						</div>
						<div class="module-desc">
						<?php print($impRen->renderDesc()); ?>
						</div>
						</div>
						<?php
			} else {
				?>
					<div class="wrap" id="smack-content">
					<?php echo $impRen->renderMenu(); ?>
					<div class="smack-postform">
					<?php echo $impRen->renderPostPage(); ?>
					</div>
					<div class="module-desc">
					<?php print($impRen->renderDesc()); ?>
					</div>
					</div>
					<!-- end wrap -->
					<?php
			}
	}
}

?>
