<?php
/*
*Plugin Name: WP Ultimate CSV Importer
*Plugin URI: http://www.smackcoders.com/blog/how-to-guide-for-free-wordpress-ultimate-csv-importer-plugin.html
*Description: A plugin that helps to import the data's from a CSV file.
*Version: 3.2.0
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
error_reporting(0);
ini_set ( 'max_execution_time', 600 );
ini_set ( 'memory_limit', '128M' );
require_once (dirname ( __FILE__ ) . '/../../../wp-load.php');

require_once ("SmackImpCE.php");
$impCE = new SmackImpCE ();

require_once ("languages/" . $impCE->user_language () . ".php");

// Activate the plugin
function wp_ultimate_csv_importer_activate(){
        include 'plugin_config.php';
}

// Deactivate the plugin
function wp_ultimate_csv_importer_deactivate(){
        include 'plugin_deactivate.php';
}

// Admin menu settings
function wp_ultimate_csv_importer() {
	add_menu_page ( 'CSV importer settings', 'WP Ultimate CSV Importer', 'manage_options', 'upload_csv_file', 'upload_csv_file', WP_CONTENT_URL . "/plugins/wp-ultimate-csv-importer/images/icon.png" );
}
function LoadWpScript() {
	if($_REQUEST['page'] == 'upload_csv_file'){
                wp_register_script ( 'wp_ultimate_scripts', WP_CONTENT_URL . "/plugins/wp-ultimate-csv-importer/wp_ultimate_csv_importer.js", array ("jquery" ) );
	}
	wp_enqueue_script ( 'wp_ultimate_scripts' );

}
add_action ( 'admin_enqueue_scripts', 'LoadWpScript' );
if($_REQUEST['page'] == 'upload_csv_file'){
wp_enqueue_style('importer_styles', WP_CONTENT_URL .'/plugins/wp-ultimate-csv-importer/css/custom-style.css');
}

add_action ( "admin_menu", "wp_ultimate_csv_importer" );

register_activation_hook( __FILE__ , 'wp_ultimate_csv_importer_activate');
register_deactivation_hook( __FILE__ , 'wp_ultimate_csv_importer_deactivate');

/**
 * Home page layout and importer
 */
function upload_csv_file() {
	global $impCE;
	global $custom_array;
	global $wpdb;

	if(!$_REQUEST['action']){ ?>
		<script>
			window.location.href="<?php echo WP_PLUGIN_URL;?>/../../wp-admin/admin.php?page=upload_csv_file&action=post";
		</script>
	<?php }
	$importdir = $impCE->getUploadDirectory ();
	if(!$_REQUEST['action']||$_POST['post_csv'])
	echo "<input type = 'hidden' value ='dashboard' id='requestaction'>";
	else
	echo "<input type='hidden' value='".$_REQUEST['action']."' id ='requestaction'>";
	echo '<input type="hidden" value="'.WP_CONTENT_URL.'" id="contenturl">';
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
	if (isset ( $_POST ['Import'] )) { ?>
		<input type="hidden" name="versionedname" id="versionedname" value="" />
		<input type="hidden" name="fileversion" id="fileversion" value="" />
<?php
		$data_rows = $impCE->csv_file_data ( $_FILES ['csv_import'] ['tmp_name'], $impCE->delim );
		require_once ("SmackImpCE.php");
		$impObj = new SmackImpCE();
		$impObj->move_file();
		?>
<div class="smack-wrap" id="smack-content">
<?php
		echo renderMenu(); 
		if ( count($impCE->headers)>=1 &&  count($data_rows)>=1 ){?>

<form class="add:the-list: validate" name="secondform" method="post"onsubmit="return import_csv();" class="secondform">
<div style="float: left; min-width: 45%">
<h3>Import Data Configuration</h3>
<?php $cnt =count($impCE->defCols) +2;  
$cnt1 = count($impCE->headers); ?>
<input type="hidden" id="h1" name="h1" value="<?php echo $cnt; ?>" />
<input type="hidden" id="h2" name="h2" value="<?php echo $cnt1; ?>" />
<input type="hidden" name="renamestatus" value = "<?php echo $_POST['filenameupdate']; ?>" />
<input type="hidden" name="updatewithpostid" value = "<?php echo $_POST['updatewithpostid']; ?>" />
<input type="hidden" name="updatewithposttitle" value = "<?php echo $_POST['updatewithposttitle']; ?>" />
<input type="hidden" name="filerenamevalue" value = "<?php echo $_POST['renameFile']; ?>" />

<?php 
	// second form starts here
	if(($_REQUEST['action'] == 'post')||($_REQUEST['action'] == 'custompost')||($_REQUEST['action'] == 'page')){ 

//set custom fields value
                         $taxo = get_taxonomies();
                         foreach($taxo as $taxokey => $taxovalue){
                                if($taxokey !='category' && $taxokey !='link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format' && $taxokey !='product_tag' && $taxokey !='wpsc_product_category' && $taxokey !='wpsc-variation'){
                                        $custo_taxo.= $taxokey.',';
                                }
                         }
                         $custo_taxo = substr($custo_taxo, 0, -1);

?>
		<input type='hidden' name='cust_taxo' id='cust_taxo' value='<?php echo $custo_taxo; ?>' />
		<div id="posttypecss"style="margin-top: 30px;">
			<table><tr><td>
			<input name="_csv_importer_import_as_draft" type="hidden"value="publish" /> 
			<?php if($_REQUEST['action'] == 'custompost'){ ?>
				<label> Select Post Type<span class="mandatory"> *</span> </label></td><td> 
			<select	name='csv_importer_cat' id ='csv_importer_cat'>
					<option>-- Select --</option>
			<?php
			foreach ( get_post_types () as $key => $value ) {
				if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page')) {
					?>
					<option id="<?php echo($value);?>"> <?php echo($value);?> </option>
					<?php
				}
			}
			?>
				</select>
			<?php } else if($_REQUEST['action'] == 'post'||$_REQUEST['action'] == 'page'){ ?>
			<input name = 'csv_importer_cat' type = 'hidden' id = 'csv_importer_cat' value=<?php echo $_REQUEST['action']; ?>>
			<?php } ?>
			</td></tr><tr><td>
	<?php $cnt1 = count($impCE->headers);?>
	
	<label>Import with post status<span class="mandatory"> *</span></label></td> <td><select
				name='importallwithps' id='importallwithps'
				onchange='importAllPostStatus(this.value, "<?php echo $cnt1?>")'>
				<option value='0'>Status as in CSV</option>
				<option value='1'>Publish</option>
				<option value='2'>Sticky</option>
				<option value='4'>Private</option>
				<option value='3'>Protected</option>
				<option value='5'>Draft</option>
				<option value='6'>Pending</option>
			</select></td></tr><tr><td>
		<label id='passwordlabel' style="display:none;">Password</label></td><td>
		<input type='text' id='postsPassword' name='postsPassword' value = 'admin' style="display:none;">
			<span style="display: none; color: red; margin-left: 5px;" id='passwordhint' style="display:none;">Replace the default value</span>
</td></tr>
</table>
</div>
	<h3>Map Fields</h3>
		<div id='display_area'>
		<?php $cnt =count($impCE->defCols) +2;  ?>
		<input type="hidden" id="h1" name="h1" value="<?php echo $cnt; ?>" />
		<input type="hidden" id="h2" name="h2" value="<?php echo $cnt1; ?>" />
		<?php if(isset($_POST['titleduplicatecheck'])) { ?>
			<input type="hidden" id="titleduplicatecheck"
				name="titleduplicatecheck"
				value="<?php echo $_POST['titleduplicatecheck'] ?>" /> 
<?php } ?>
			<?php if(isset($_POST['contentduplicatecheck'])){ ?>
			<input type="hidden" id="contentduplicatecheck"	name="contentduplicatecheck"value="<?php echo $_POST['contentduplicatecheck'] ?>" /> 
<?php } ?><input
				type="hidden" id="delim" name="delim"
				value="<?php echo $_POST['delim']; ?>" /> <input type="hidden"
				id="header_array" name="header_array"
				value="<?php print_r($impCE->headers);?>" /> 
			<table style="font-size: 12px;">
				 <?php
			$count = 0;
			if($_REQUEST['action'] == 'page'){
				unset($impCE->defCols['post_category']);
				unset($impCE->defCols['post_tag']);
			}
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
					if($key1 == 'post_name')
						$key1 = 'post_slug';
					$strip_CF = strpos($key1,'CF: '); //Auto mapping
					if($strip_CF === 0){
                                        	$custom_key = substr($key1, 4);
					}
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
					
					</select> <!-- added to solve issue id 1072-->
					 <input class = "customfieldtext" type="text" id="textbox<?php print($count); ?>"
						name="textbox<?php print($count); ?>" style="display: none;"
						value="<?php echo $value ?>" /> <span
						style="display: none; color: red; margin-left: 5px;"
						id="customspan<?php echo $count?>">Replace the default value</span>
					</td>
				</tr>
				 <?php
				$count ++;
			}
			?>
				</table>
		</div>
		<br /> 
<?php }
elseif($_REQUEST['action'] == 'dashboard'){
echo renderDashboard();
}
elseif($_REQUEST['action'] == 'help'){
//die('help');
}


//second form exits ?>
<input type='hidden' name='filename' id='filename' value="<?php echo($_FILES['csv_import']['name']);?>" />
<?php 
 		$explodeCsv = explode('.csv',$_FILES['csv_import']['name']);
                $exactFIlename = $explodeCsv[0].'-'.$_REQUEST['action'].'.csv';
?>
<input type='hidden' name='realfilename' id='realfilename' value="<?php echo($_FILES['csv_import']['name']);?>" />
<input type="hidden" name="version" id="version" value="" />
<input type='hidden' name='selectedImporter' id = 'selectedImporter' value = "<?php echo $_REQUEST['action']; ?>" />
<input type='hidden' name='delim' id='delim' value="<?php echo($_POST['delim']) ?>" />
<button type='submit' class="action" name= 'post_csv' id='post_csv' value='Import' >Import</button>

</form>
</div>
</div>
<div style="min-width: 45%;">
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
	} else if ((isset ( $_POST ['post_csv'] ))&&(($_REQUEST['action'] == 'post')||($_REQUEST['action'] == 'page') ||($_REQUEST['action'] == 'custompost') )) {
//third form starts here
		$impCE->processDataInWP();
	        echo renderMenu(); 	
		if (( $impCE->insPostCount != 0) || ($impCE->dupPostCount != 0) || ($impCE->updatedPostCount != 0) ) {
			?>
		<div>
		
 <?php 
	echo setDashboardAction();
	$messageString = $impCE->insPostCount." records are successfully Imported.";
	if($_POST['titleduplicatecheck'] == 1 || $_POST['contentduplicatecheck'] == 1 )
		$messageString .= $impCE->dupPostCount." duplicate records found.";
	if(($impCE->noPostAuthCount != 0)&&(in_array('post_author',$_POST)))
		$messageString .= '<br>'.$impCE->noPostAuthCount." posts with no valid UserID/Name are assigned admin as author.";
	if($impCE->updatedPostCount != 0)
		$messageString .= '<br>'.$impCE->updatedPostCount.' posts has been updated.';
		echo showMessage('success',$messageString); ?>
		</div>
		<?php 
		}else if(($impCE->insPostCount == 0) && ($impCE->dupPostCount == 0) && ($impCE->updatedPostCount == 0)){ ?>
		<div>
			<?php echo showMessage('error',"Check your CSV file and format."); ?>
		</div>
		<?php } ?>
		<?php
		$_REQUEST['action'] = 'dashboard';		
		echo renderDashboard();
	}
	else {
		?>
<!-- first form gets displayed from here -->
<?php
if(!$_REQUEST['action']){
	$_REQUEST['action'] = 'post';
}
elseif($_REQUEST['action'] == 'dashboard'){
	echo renderMenu();
	echo renderDashboard();
}
elseif($_REQUEST['action'] == 'settings'){
echo renderMenu();
if($pluginActive != null)
echo showMessage('error',$pluginActive);
echo renderSettings();
}
elseif($_REQUEST['action'] == 'custompost'){
require_once('class.settings.php');
?>
<div class="wrap" id="smack-content">
<?php
echo renderMenu();
$sett = new Settings();
        if($sett->chkCustomPostPlugin()){
?>
        <div class="smack-postform">
                <?php echo renderPostPage(); ?>
        </div>
        <div class="module-desc">
                <?php print(renderDesc()); ?>
        </div>
</div>
<?php
	}
	else
	echo showMessage('error','Custom Post Plugin Not Found.');
}
else{
?>
<div class="wrap" id="smack-content">
<?php echo renderMenu(); ?>
	<div class="smack-postform">
		<?php echo renderPostPage(); ?>
	</div>
	<div class="module-desc">
		<?php print(renderDesc()); ?>
	</div>
</div>
<!-- end wrap -->
<?php
	}
	}
}

/**
 * Function to render the dashboard
 */
function renderDashboard() {
	require_once('stats.php');
}

/*
 *return state of plugins - absent,present and active
*/
function getPluginState($plugin){
$state = 'pluginAbsent';
require_once('class.settings.php');
$settobj = new Settings();
if($settobj->isPluginPresent($plugin))
$state = 'pluginPresent';
if($settobj->isPluginActive($plugin))
$state = 'pluginActive';
return $state;
}

/*
 *return state of plugins - absent,present and active
*/
function getPluginStateImg($plugin){
$state = '<img src ="'.WP_CONTENT_URL.'/plugins/wp-ultimate-csv-importer/images/notdetected.png" class = "settingsicon">';
require_once('class.settings.php');
$settobj = new Settings();
if($settobj->isPluginPresent($plugin))
$state ='<img src ="'.WP_CONTENT_URL.'/plugins/wp-ultimate-csv-importer/images/notactive.png" class = "settingsicon">';
if($settobj->isPluginActive($plugin))
$state = '<img src ="'.WP_CONTENT_URL.'/plugins/wp-ultimate-csv-importer/images/installed.png" class = "settingsicon">';
return $state;
}


/**
 * Function to display the plugin home description
 */
function renderSettings(){
	$impCESett = new SmackImpCE();
	$sett = getSettings();
	foreach($sett as $key)
		$$key = 'checked';

	$cctmtd = getPluginState('custom-content-type-manager/index.php');
	$cptutd = getPluginState('custom-post-type-ui/custom-post-type-ui.php');
	$eshoptd = getPluginState('eshop/eshop.php');
	$wpcomtd = getPluginState('wp-e-commerce/wp-shopping-cart.php');
	$aioseotd = getPluginState('all-in-one-seo-pack/all_in_one_seo_pack.php');
	$yoasttd = getPluginState('wordpress-seo/wp-seo.php');
	$cateicontd = getPluginState('category-icons/category_icons.php');

        $cctmtdi = getPluginStateImg('custom-content-type-manager/index.php');
        $cptutdi = getPluginStateImg('custom-post-type-ui/custom-post-type-ui.php');
        $eshoptdi = getPluginStateImg('eshop/eshop.php');
        $wpcomtdi = getPluginStateImg('wp-e-commerce/wp-shopping-cart.php');
        $aioseotdi = getPluginStateImg('all-in-one-seo-pack/all_in_one_seo_pack.php');
        $yoasttdi = getPluginStateImg('wordpress-seo/wp-seo.php');
        $cateicontdi = getPluginStateImg('category-icons/category_icons.php');

	if(!$ecommerce)
	$ecommercedisabled = 'disabled';

	$setString = "<div class = 'settingscontainer'><form class=\"add:the-list: validate\" method=\"post\" enctype=\"multipart/form-data\">";
	$setString .= "<div class='upgradetopro' id='upgradetopro' style='display:none;'>This feature is only available in Pro Version, Please <a href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html' target='_blank' >UPGRADE TO PRO</a></div>";
	$setString .= "<table>";

        $setString .= '<tr><td><a href="javascript:slideonlyone(\'featuresBox\',\''.WP_CONTENT_URL.'\');" id="myHead2" class="smackhelpswitcher_anchor">
<div class="smackhelpswitcher">'.$impCESett->t('FEATURE').'<img src="'.WP_CONTENT_URL.'/plugins/wp-ultimate-csv-importer/images/arrow_up.gif" id="featuresBox_img" class="smackhelpswitcher_img"></div></a></td></tr><tr><td>';
	$setString .= "<tr><td><div id='featuresBox' class = 'switchercontent newboxes2'><table>";
	$setString .= "<tr><td><label class=$automapping><input type='checkbox' name='automapping' value='automapping' onclick='savePluginSettings()' ".$automapping.">".$impCESett->t('ENABLEAUTOMAPPING')."</label></td>";
	$setString .= "<td><label class=$utfsupport><input type='checkbox' name='rutfsupport' value='utfsupport' onclick='savePluginSettings()' ".$utfsupport.">".$impCESett->t('ENABLEUTFSUPPORT')."</label></td></tr></table></div></td></tr>";


	$setString .= '<tr><td><a href="javascript:slideonlyone(\'moduleBox\',\''.WP_CONTENT_URL.'\');" id="myHead2" class="smackhelpswitcher_anchor">
<div class="smackhelpswitcher">Modules<img src="'.WP_CONTENT_URL.'/plugins/wp-ultimate-csv-importer/images/arrow_up.gif" id="featuresBox_img" class="smackhelpswitcher_img"></div></a></td></tr><tr><td>';
	$setString .= "<div id = 'moduleBox' class = 'switchercontent newboxes2'><table><tr><td><ul>";
	$setString .= "<li><label class='checked'><input type='checkbox' name='post' value='post' onclick='savePluginSettings()' disabled checked>".$impCESett->t('POST')."</label>";
	$setString .= "<label class='checked'><input type='checkbox' name='custompost' value='custompost' onclick='savePluginSettings()' disabled checked>".$impCESett->t('CUSTOMPOST')."</label>";
	$setString .= "<label class='checked'><input type='checkbox' name='page' value='page' onclick='savePluginSettings()' disabled checked>".$impCESett->t('PAGE').'</label>';
	$setString .= "<label class=\"$comments\"><input type='checkbox' name='comments' value='comments' onclick='savePluginSettings()' ".$comments.">".$impCESett->t('COMMENTS')."</label></li>";
	$setString .= "<li><label class=$categories><input type='checkbox' name='categories' value='categories' onclick='savePluginSettings()' ".$categories." >Categories/Tags</label>";
	$setString .= "<label class=$customtaxonomy><input type='checkbox' name='customtaxonomy' value='customtaxonomy' onclick='savePluginSettings()' ".$customtaxonomy." >Custom Taxonomy</label>";
	$setString .= "<label class=$users><input type='checkbox' name='users' value='users' onclick='savePluginSettings()' ".$users.">Users/Roles</label></li>";
	$setString .= "</ul></td></tr></table></div></td></tr>";


	$setString .= '<tr><td><a href="javascript:slideonlyone(\'thirdPartyBox\',\''.WP_CONTENT_URL.'\');" id="myHead2" class="smackhelpswitcher_anchor">
<div class="smackhelpswitcher">'.$impCESett->t('THIRDPARTY').'<img src="'.WP_CONTENT_URL.'/plugins/wp-ultimate-csv-importer/images/arrow_up.gif" id="featuresBox_img" class="smackhelpswitcher_img"></div></a></td></tr><tr><td>';
        $setString .= "<tr><td><div id='thirdPartyBox' class = 'switchercontent newboxes2'><table>";
	$setString .= "<tr><td><b>Ecommerce</b></td></tr>";
	$setString .= "<tr><td><label class=$nonerecommerce><input type = 'radio' name ='recommerce' value='nonerecommerce' onclick='savePluginSettings()' ".$nonerecommerce." class='ecommerce'>".$impCESett->t('NONE')."</label></td>";
	$setString .= "<td><label class=\"$eshoptd $eshop\"><input type='radio' name='recommerce' value='eshop' onclick='savePluginSettings()' ".$eshop."  "." class='ecommerce'>Eshop</label></td><td><label class=\"$wpcomtd $wpcommerce\"><input type='radio' name='recommerce' value='wpcommerce' onclick='savePluginSettings()' ".$wpcommerce."  class = 'ecommerce'>WP e-Commerce</label></td></tr>";
	$setString .= "<tr><td><b>".$impCESett->t('CUSTOMPOST')."</b></td></tr>";
	$setString .= "<tr><td><label class=$nonercustompost><input type = 'radio' name ='rcustompost' value='nonercustompost' onclick='savePluginSettings()' ".$nonercustompost." class='ecommerce'>".$impCESett->t('NONE')."</label></td>";
	$setString .= "<td><label class=\"$cptutd $custompostuitype\"><input type ='radio' name = 'rcustompost' value='custompostuitype' onclick='savePluginSettings()' ".$custompostuitype.">".$impCESett->t('CUSTOMPOSTTYPE')."</label></td>";
	$setString .= "<td><label class=\"$cctmtd $cctm\"><input type ='radio' name = 'rcustompost' value='cctm' onclick='savePluginSettings()' ".$cctm.">".$impCESett->t('CCTM')."</label>"."</td></tr>";
        $setString .= "<tr><td><b>".$impCESett->t('SEO_OPTIONS')."</b></td></tr>";
        $setString .= "<tr><td><label class=$nonerseooption><input type = 'radio' name ='rseooption' value='nonerseooption' onclick='savePluginSettings()' ".$nonerseooption." class='ecommerce'>".$impCESett->t('NONE')."</label></td>";
        $setString .= "<td><label class=\"$aioseotd $aioseo\"><input type ='radio' name = 'rseooption' value='aioseo' onclick='savePluginSettings()' ".$aioseo.">".$impCESett->t('ALLINONESEO')."</label></td>";
        $setString .= "<td><label class=\"$yoasttd $yoastseo\"><input type ='radio' name = 'rseooption' value='yoastseo' onclick='savePluginSettings()' ".$yoastseo.">".$impCESett->t('YOASTSEO')."</label></td></tr>";
        $setString .= "<tr><td><b>".$impCESett->t('CATEGORY_ICONS')."</b></td></tr>";
	$setString .= "<tr><td>".$impCESett->t('PLUGINCHECK')."</td></tr>";
        $setString .= "<tr><td><label class=$enable><input type = 'radio' name ='rcateicons' value='enable' onclick='savePluginSettings()' ".$enable." class='ecommerce'>".$impCESett->t('ENABLE_CATEGOTY_ICONS')."</label></td>";
        $setString .= "<td><label><input type ='radio' name = 'rcateicons' value='disable' onclick='savePluginSettings()' ".$disable.">".$impCESett->t('DISABLE_CATEGORY_ICONS')."</label></td></tr>";
        $setString .= "</table></div></td></tr>";
        $setString .= "<tr></tr></table><input type='button' class='action' name='savesettings' value='Save' style='float:left;' onclick='savePluginSettings()' /></form></div>";
	return $setString;
}

/*
 * Render description for each modules
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

function renderMenu(){
                $settings = getSettings();
                $impCEM = new SmackImpCE();
                foreach($settings as $key)
                	$$key = true;
		if($_POST['post_csv'] == 'Import')
			$dashboard = 'selected';
		else{
                $action = $_REQUEST['action'];
                $$action = 'selected';
		}

		if(!$_REQUEST['action'])
			$dashboard = 'selected';
                $menuHTML = "<div class='csv-top-navigation-wrapper' id='header' name='mainNavigation'><ul id='topNavigation'>";
                $menuHTML .="<li class=\"navigationMenu $post\" style='margin-left:0px;'><a href = 'admin.php?page=upload_csv_file&action=post' class = 'navigationMenu-link' id='module1'>".$impCEM->t('POST')."</a></li>";
		$menuHTML .= "<li class=\"navigationMenu $page\"><a href = 'admin.php?page=upload_csv_file&action=page' class = 'navigationMenu-link' id='module1'>".$impCEM->t("PAGE")."</a></li>";
                $menuHTML .= "<li class=\"navigationMenu $custompost\"><a href = 'admin.php?page=upload_csv_file&action=custompost' class = 'navigationMenu-link' id = 'module2'>".$impCEM->t('CUSTOMPOST')."</a></li>";
                $menuHTML .= "<li class=\"navigationMenu $settings\"><a href = 'admin.php?page=upload_csv_file&action=settings' class='navigationMenu-link' id='module9'>".$impCEM->t('SETTINGS')."</a></li>";
                $menuHTML .= "<li class=\"navigationMenu $dashboard\"><a href = 'admin.php?page=upload_csv_file&action=dashboard' class='navigationMenu-link' id='module0'>".$impCEM->t('DASHBOARD')."</a></li>";
                $menuHTML .= "</ul></div> <div style='margin-top:-55px;float:right;margin-right:300px'><a href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html' target='_blank'><input type='button' class='button-primary' name='Upgradetopro' id='Upgradetopro' value='Upgrade To PRO' /></a></div> <div class='msg' id = 'showMsg' style = 'display:none;'></div>";
                return $menuHTML;

	}

function renderPostPage(){
	$impCE = new SmackImpCE ();
	$postForm = ' <div style="float: left; margin-top: 11px; margin-right: 5px;"><img src = "'.WP_CONTENT_URL.'/plugins/wp-ultimate-csv-importer/images/Importicon_24.png"></div><div style="float:left;"><h2>'. $impCE->t('IMPORT_CSV_FILE').'</h2><form class="add:the-list: validate" method="post"enctype="multipart/form-data" onsubmit="return file_exist();"></div><table class="importform">
                                <tr><td><label for="csv_import" class="uploadlabel" >'.$impCE->t('UPLOAD_FILE').'<span class="mandatory"> *</span></label>
				<input type="hidden" value="'.WP_CONTENT_URL.'" id="contenturl" />
                                <input name="csv_import" id="csv_import" class="btn" type="file" value="" />

                        </td></tr>';
			
		if(($_REQUEST['action'] == 'post')||($_REQUEST['action'] == 'custompost')||($_REQUEST['action'] == 'page')){

		      $postForm .='<tr style="display:block;" id="detect"><td><label class="detectDup"><input type="checkbox" name="titleduplicatecheck" value=1> '.$impCE->t("ENABLE_DUPLICATION_POST_TITLE") .'</label></td><td><label class="detectDup"><input type="checkbox" name="contentduplicatecheck" value=1>  '.$impCE->t("ENABLE_DUPLICATION_POST_CONTENT").'</label></td></tr>';

		}
	      $postForm .='<tr><td><label class="uploadlabel">Delimiter</label><select name="delim" id="delim">
                                        <option value=",">,</option>
                                        <option value=";">;</option>
                                </select></td></tr>
			</table>
                        <p>
                                <button type="submit" class="action addmarginright" name="Import" value="Import" align="right" onclick = "return validateFirstForm();"> Import</button>
                        </p>
                </form></br></br>';
return $postForm;
}

/*
 * Render mapping
*/
function renderMapping($impCE){
	$mappingString = '<table>';
	$count = 0;
	foreach($impCE->headers as $key => $value){
		$mappingString .= "<tr><td><label> $value </label></td><td><select name=\"mapping$count\" id=\"mapping$count\" class ='uiButton' onchange=\"addcustomfield(this.value,$count)\" ><option id=\"select\" name=\"select\">-- Select --</option>";
		$maparray = getMappingoptions();
	        foreach($maparray as $key1=>$value1){
			$mappingString .= "<option value = \"$key1\" >$key1</option> ";
		}
		$mappingString .= "</select></td></tr>";
		$mappingString .= "<input type=\"text\" id=\"textbox$count\" name=\"textbox$count\" style=\"display:none; \"/>";
		$count++;
	}
	$mappingString .= '</table>';
	return $mappingString;
}

/*
 * returns Mapping Options for plugins
*/
function getMappingoptions(){
	$maparray = array();
	return $maparray;
}

/*
 * Shows status message
*/
function showMessage($status,$message){
	return "<div class = \"$status msg\"> $message</div>";
}

/*
 * Get Saved Settings
*/
function getSettings(){
	return get_option('wpcsvprosettings');
}

/*
 * Function renders Eshop Form
*/
function renderEshopForm(){
//	$eshopForm = "<table><tr><td><input name='csv_importer_import_as_draft' type='checkbox' value='draft'> Import as drafts</td><td><input name='featured_product' type='checkbox' value='featured'> Featured Product</td><td><input name='product_in_sale' type='checkbox' value='sale'>Product in sale</td><td> <input name='stock_available' type='checkbox' value='stock_available'> Stock Available </td></tr>";

//	$eshopForm .= '<tr><td><label><b>Cart Option</b></label></td></tr><tr><td><input type="radio" name="cart-option" value="dropdown-select" checked>Dropdown Select </td><td><input type="radio" name="cart-option" value="radio-buttons">Radio Buttons</td></tr>';

//	$eshopForm .= '</table>';
//	return $eshopForm;
}

/*
* Render Sample CSV link
*/
function renderSampleCSV(){
return 'This is footer of plugin';
}
function setDashboardAction(){
return '<div id = "requestaction" type = "hidden" value = "dashboard">';
}
?>
