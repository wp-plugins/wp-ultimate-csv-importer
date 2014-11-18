<?php
/*********************************************************************************
 * WP Ultimate CSV Importer is a Tool for importing CSV for the Wordpress
 * plugin developed by Smackcoder. Copyright (C) 2014 Smackcoders.
 *
 * WP Ultimate CSV Importer is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License version 3
 * as published by the Free Software Foundation with the addition of the
 * following permission added to Section 15 as permitted in Section 7(a): FOR
 * ANY PART OF THE COVERED WORK IN WHICH THE COPYRIGHT IS OWNED BY WP Ultimate
 * CSV Importer, WP Ultimate CSV Importer DISCLAIMS THE WARRANTY OF NON
 * INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * WP Ultimate CSV Importer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public
 * License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program; if not, see http://www.gnu.org/licenses or write
 * to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA 02110-1301 USA.
 *
 * You can contact Smackcoders at email address info@smackcoders.com.
 *
 * The interactive user interfaces in original and modified versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License
 * version 3, these Appropriate Legal Notices must retain the display of the
 * WP Ultimate CSV Importer copyright notice. If the display of the logo is
 * not reasonably feasible for technical reasons, the Appropriate Legal
 * Notices must display the words
 * "Copyright Smackcoders. 2014. All rights reserved".
 ********************************************************************************/

$impCE = new WPImporter_includes_helper();
?>
	<div style="width:100%;">
	<div id="accordion">
	<table class="table-importer">
	<tr>
	<td>
	<h3>CSV Import Options</h3>
	<div id='sec-one' <?php if($_REQUEST['step']!= 'uploadfile') {?> style='display:none;' <?php } ?>>
	<?php if(is_dir($impCE->getUploadDirectory('default'))){ ?>
		<input type='hidden' id='is_uploadfound' name='is_uploadfound' value='found' />
	<?php } else { ?>
		<input type='hidden' id='is_uploadfound' name='is_uploadfound' value='notfound' />
	<?php } ?>
         <div class="warning" id="warning" name="warning" style="display:none;margin: 4% 0 4% 22%;"></div>
	<form action='<?php echo admin_url().'admin.php?page='.WP_CONST_ULTIMATE_CSV_IMP_SLUG.'/index.php&__module='.$_REQUEST['__module'].'&step=mapping_settings'?>' id='browsefile' enctype="multipart/form-data" method='post' name='browsefile'>
	<div class="importfile" align='center'>
	<div id='filenamedisplay'></div>
	<div class="container">
          <?php echo $impCE->smack_csv_import_method(); ?>
	<input type ='hidden' id="pluginurl"value="<?php echo WP_CONTENT_URL;?>">
	<?php $uploadDir = wp_upload_dir(); ?>
	<input type="hidden" id="uploaddir" value="<?php if(isset($uploadDir)) { echo $uploadDir['basedir']; }  ?>">
	<input type="hidden" id="uploadFileName" name="uploadfilename" value="">
        <input type = 'hidden' id = 'uploadedfilename' name = 'uploadedfilename' value = ''>
        <input type = 'hidden' id = 'upload_csv_realname' name = 'upload_csv_realname' value =''>
        <input type = 'hidden' id = 'current_file_version' name = 'current_file_version' value = ''>
        <input type = 'hidden' id = 'current_module' name = 'current_module' value = '<?php if(isset($_REQUEST['__module'])) { echo $_REQUEST['__module']; }  ?>' >
	</span>
	<!-- The global progress bar -->
         <div class="form-group" style="padding-bottom:20px;">
                                <table>
                                <tr>
                                   <div id='showmappingtemplate' style='float:left;padding-left:10px;'>  
                                  <select disabled>
                               <option value ='select template' > select template </option>
                                   </select>
				<img src="<?php echo WP_CONTENT_URL; ?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG; ?>/images/pro_icon.gif" title="PRO Feature" />
                                   </div>

                                </div>
                                <div style="float:right;">
                                <input type='button' name='clearform' id='clearform' value='<?php echo __("Clear"); ?>' onclick="Reload();"
                                class='btn btn-warning' style="margin-right:15px" />
                                <input type='submit' name='importfile' id='importfile' value='<?php echo __("Next >>");?>' disabled
                                class='btn btn-primary'style="margin-right:15px"/>
                                </div>
                                </tr>
                                </table>
                                <div class="warning" id="warning" name="warning" style="display:none"></div>
                                <!-- The container for the uploaded files -->
                                <div id="files" class="files"></div>
                                   <br>
                                </div>
	<script>
	var check_upload_dir = document.getElementById('is_uploadfound').value;  
	if(check_upload_dir == 'notfound'){
                document.getElementById('browsefile').style.display = 'none';
		jQuery('#defaultpanel').css('visibility','hidden');
		jQuery('<p/>').text("").appendTo('#warning');
		jQuery( "#warning" ).empty();
		jQuery('#warning').css('display','inline');
		jQuery('<p/>').text("Warning:   Sorry. There is no uploads directory Please create it with write permission.").appendTo('#warning');
		jQuery('#warning').css('color','red');
		jQuery('#warning').css('font-weight','bold');
		jQuery('#progress .progress-bar').css('visibility','hidden');
	}
	else{
	jQuery(function () {
		'use strict';
		var uploadPath = document.getElementById('uploaddir').value;
		var url = (document.getElementById('pluginurl').value+'/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/lib/jquery-plugins/uploader.php')+'?uploadPath='+uploadPath+'&curr_action=<?php echo $_REQUEST['__module']; ?>';
		jQuery('#fileupload').fileupload({
		url: url,
		dataType: 'json',
		done: function (e, data) {
		jQuery.each(data.result.files, function (index, file) {
		document.getElementById('uploadFileName').value=file.name;
                var filewithmodule = file.uploadedname.split(".csv");
                file.uploadedname = filewithmodule[0]+"-<?php echo $_REQUEST['__module']; ?>"+".csv";
                document.getElementById('upload_csv_realname').value = file.uploadedname; 
                var get_version1 = file.name.split("-<?php echo $_REQUEST['__module']; ?>"); 
                var get_version2 = get_version1[1].split(".csv");
                var get_version3 = get_version2[0].split("-");
                document.getElementById('current_file_version').value = get_version3[1];
                jQuery('#uploadedfilename').val(file.uploadedname);
		jQuery( "#filenamedisplay").empty();
		if(file.size>1024 && file.size<(1024*1024))
		{
			var fileSize =(file.size/1024).toFixed(2)+' kb';
		}
		else if(file.size>(1024*1024))
		{
			var fileSize =(file.size/(1024*1024)).toFixed(2)+' mb';
		}
		else
		{
			var fileSize= (file.size)+' byte';
		}
		jQuery('<p/>').text((file.name)+' - '+fileSize).appendTo('#filenamedisplay');
		jQuery('#importfile').attr('disabled', false);
		});
		},progressall: function (e, data) {
		var progress = parseInt(data.loaded / data.total * 100, 10);
		jQuery('#progress .progress-bar').css('width', progress + '%' );
		}
		}).prop('disabled', !jQuery.support.fileInput)
		.parent().addClass(jQuery.support.fileInput ? undefined : 'disabled');
		});
	}
	</script>
	<input type = 'hidden' name = 'importid' id = 'importid' >
<!--	<div class='section-one' align='center'>
	<input type = 'button' name='clearform' id='clearform' value='Clear' onclick="Reload();" class = 'btn btn-warning' /> 
	<input type = 'submit' name='importfile' id='importfile' value='Next>>' disabled class = 'btn btn-primary' /> 
	<input type = 'hidden' name = 'importid' id = 'importid' >
	</div> -->
		</form>
	</div>
	</div>
	</td>
	</tr>
	<tr>
	<td>
	<form name='mappingConfig' action="<?php echo admin_url(); ?>admin.php?page=<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/index.php&__module=<?php echo $_REQUEST['__module']?>&step=importoptions"  method="post" onsubmit="return import_csv();" >
	<div class='msg' id = 'showMsg' style = 'display:none;'></div>
	<?php $_SESSION['SMACK_MAPPING_SETTINGS_VALUES'] = $_POST;
	if (isset($_POST['mydelimeter'])) {	
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['delim'] = $_POST['mydelimeter']; 
	}
	$wpcsvsettings=array();
        $custom_key = array();
	$wpcsvsettings=get_option('wpcsvfreesettings');
	?>
	<h3>Map CSV to WP fields/attributes</h3>
         <?php if(isset($_REQUEST['step']) && $_REQUEST['step'] == 'mapping_settings' ) { ?>
	<div id='sec-two' <?php if($_REQUEST['step']!= 'mapping_settings'){ ?> style='display:none;' <?php } ?> >
	<div class='mappingsection'>
	<h2><div class="secondformheader">Import Data Configuration</div></h2>
        <?php  
        if(isset($_FILES['inlineimages'])) {
                $uploaded_compressedFile = $_FILES['inlineimages']['tmp_name'];
                $get_basename_zipfile = explode('.', $_FILES['inlineimages']['name']);
                $basename_zipfile = $get_basename_zipfile[0];
                $location_to_extract = $uploadDir['basedir'] . '/smack_inline_images/' . $basename_zipfile;
                $extracted_image_location = $uploadDir['baseurl'] . '/smack_inline_images/' . $basename_zipfile;
                $zip = new ZipArchive;
                if ($zip->open($uploaded_compressedFile) === TRUE) {
                        $zip->extractTo($location_to_extract);
                        $zip->close();
                        $extracted_status = 1;
                } else {
                        $extracted_status = 0;
                }
        }
        ?>
	<?php if(isset($_REQUEST['__module']) && $_REQUEST['__module']=='custompost'){ ?>
		<div class='importstatus' style='display:true;'>
			<input type="hidden" id="customposts" name="customposts" value="">
			<div style = 'float:left'> <label id="importalign"> Select Post Type </label> <span class="mandatory"> * </span> </div>
			<div style = 'float:left;margin-right:10px'> 
			<select name='custompostlist' id='custompostlist'>
			<option value='select'>---Select---</option>
			<?php
			$cust_post_list_count=0;
			foreach (get_post_types() as $key => $value) {
                                $cctm_post_type = array();
                                $cctm_post_type = get_option('cctm_data');
                                if(!empty($cctm_post_type)){
                                        foreach($cctm_post_type['post_type_defs'] as $cctmptkey => $cctmptval) {
                                                if($cctmptkey == $value){
                                                        $value = 'createdByCCTM';
                                                }
                                        }
                                }
                                $types_post_types = array();
                                $types_post_types = get_option('wpcf-custom-types');
                                if(!empty($types_post_types)) {
                                        foreach($types_post_types as $tptKey => $tptVal){
                                                if($tptKey == $value) {
                                                        $value = 'createdByTypes';
                                                }
                                        }
                                }
				if($wpcsvsettings['rcustompost']=='custompostuitype'){	
					if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group') && ($value != 'product') && ($value != 'product_variation') && ($value != 'shop_order') && ($value != 'shop_coupon') && ($value != 'acf') && ($value != 'createdByCCTM') && ($value != 'createdByTypes')) {?>
					<option id="<?php echo($value); ?>"> <?php echo($value);?> </option>
						<?php	
						$cust_post_list_count++;
					}
				}
				else
				{
					$cust_post_ui = array();
					$cust_post_ui = get_option('cpt_custom_post_types');
					$cctm_post_type = array();
					$cctm_post_type = get_option('cctm_data');
					if(!empty($cctm_post_type)){
						foreach($cctm_post_type['post_type_defs'] as $cctmptkey => $cctmptval) {
							if($cctmptkey == $value){
								$value = 'createdByCCTM';
							}
						}
					}
					foreach($cust_post_ui as $cust_list){
						if($cust_list['name']==$value)
						{
							$value='createdByCustomPostUI';
						}
					}
					$types_post_types = array();
					$types_post_types = get_option('wpcf-custom-types');
					if(!empty($types_post_types)) {
						foreach($types_post_types as $tptKey => $tptVal){
							if($tptKey == $value) {
								$value = 'createdByTypes';
							}
						}
					}
					if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group') && ($value != 'product') && ($value != 'product_variation') && ($value != 'shop_order') && ($value != 'shop_coupon') && ($value != 'acf') && ($value != 'createdByCustomPostUI') && ($value != 'createdByCCTM') && ($value != 'createdByTypes')) {?>
                                        	<option id="<?php echo($value); ?>"> <?php echo($value);?> </option>
                                                <?php                 
							$cust_post_list_count++;
					}
				}
			} ?>
			</select>
			<input type = 'hidden' id = 'cust_post_list_count' name = 'cust_post_list_count' value = '<?php echo $cust_post_list_count; ?>'>
			</div>
			<div style = 'float:left'> 
			<a href="#" class="tooltip">
			<img src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>images/help.png" />
			<span class="tooltipCustompost">
			<img class="callout" src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>images/callout.gif" />
			<strong>Select your custompost type</strong>
			<img src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>images/help.png" style="margin-top: 6px;float:right;" />
			</span>
			</a><label id='cust_post_empty' style='display:none'>CUSTOMPOST LIST IS EMPTY</label>
			</div>
			</div>
			<?php } ?>
			<?php echo $impCE->getImportDataConfiguration(); ?>
			</div>
			<div id='mappingheader' class='mappingheader' >
			<?php  
			//   $impCE = CallSkinnyObj::getInstance();
			$allcustomposts='';
			$mFieldsArr='';
			$delimeter='';
			$filename='';
                        $records = '';
			if(isset($_POST['uploadfilename']) && $_POST['uploadfilename'] != ''){
                                $file_name = $_POST['uploadfilename'];
                                $filename = $impCE->convert_string2hash_key($file_name);
			}
			if (isset($_POST['mydelimeter'])) {
				$delimeter= $_POST['mydelimeter'];
			}
                        if(isset($_POST['upload_csv_realname']) && $_POST['upload_csv_realname'] != '') {
                                $uploaded_csv_name = $_POST['upload_csv_realname'];
                        }
			$getrecords = $impCE->csv_file_data($filename); 
			$getcustomposts=get_post_types();
			foreach($getcustomposts as $keys => $value)
			{
				if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group')) {
					$allcustomposts.=$value.',';
				}
			}
			?>	
			<table style="font-size: 12px;" class = "table table-striped"> 
			<tr>
			<td colspan='4'>
			<div align='center' style='float:right;'>
			<?php $cnt = count($impCE->defCols) + 2;
			$cnt1 = count($impCE->headers);
                        $records = count($getrecords);
			$imploaded_array = implode(',', $impCE->headers); ?>
			<input type = 'hidden' id = 'imploded_header' name = 'imploded_array' value = '<?php if(isset($imploaded_array)) { echo $imploaded_array; }  ?>'>
			<input type='hidden' id='h1' name='h1' value="<?php if(isset($cnt)) { echo $cnt; }  ?>"/>
			<input type='hidden' id='h2' name='h2' value="<?php if(isset($cnt1)) { echo $cnt1; }  ?>"/>
			<input type='hidden' name='selectedImporter' id='selectedImporter' value="<?php if(isset($_REQUEST['__module'])) { echo $_REQUEST['__module']; }  ?>"/>
			<input type="hidden" id="prevoptionindex" name="prevoptionindex" value=""/>
			<input type="hidden" id="prevoptionvalue" name="prevoptionvalue" value=""/>
			<input type='hidden' id='current_record' name='current_record' value='0' />
			<input type='hidden' id='totRecords' name='totRecords' value='<?php if(isset($records)) { echo $records; }  ?>' />
			<input type='hidden' id='tmpLoc' name='tmpLoc' value='<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>' />
			<input type='hidden' id='uploadedFile' name='uploadedFile' value="<?php if(isset($filename)) { echo  $filename; }  ?>" />
                        <!-- real uploaded filename -->
                        <input type='hidden' id='uploaded_csv_name' name='uploaded_csv_name' value="<?php if(isset($uploaded_csv_name)) { echo $uploaded_csv_name; }  ?>" />
			<input type='hidden' id='select_delimeter' name='select_delimeter' value="<?php if(isset($delimeter)) { echo  $delimeter; }  ?>" />
			<input type='hidden' id='stepstatus' name='stepstatus' value='<?php if(isset($_REQUEST['step'])) { echo $_REQUEST['step']; }  ?>' />
			<input type='hidden' id='mappingArr' name='mappingArr' value='' />
			<input type='hidden' id='inline_image_location' name='inline_image_location' value='<?php echo $extracted_image_location; ?>' />
			<input type='button' id='prev_record' name='prev_record' class="btn btn-primary" value='<<' onclick='gotoelement(this.id);' />
			 <label style="padding-right:10px;" id='preview_of_row'>Showing preview of row #  1</label>
                        <input type='button' id='next_record' name='next_record' class="btn btn-primary" value='>>' onclick='gotoelement(this.id);' />
			<label id="importalign" style="margin-right:8px;"> Go To Row #</label><input type='text' id='goto_element' name='goto_element' />
			<input type='button' id='apply_element' name='apply_element' class="btn btn-success" value='Show' onclick='gotoelement(this.id);' style="margin-right:10px;margin-left:5px"/>
			</div>
			</td>
			</tr> 
			<?php
			$count = 0;
			if (isset($_REQUEST['__module']) && $_REQUEST['__module']== 'page') {
				unset($impCE->defCols['post_category']);
				unset($impCE->defCols['post_tag']);
                                unset($impCE->defCols['post_format']);
			}
                        if (isset($_REQUEST['__module']) && $_REQUEST['__module'] != 'page') {
                                unset($impCE->defCols['menu_order']);
                                unset($impCE->defCols['wp_page_template']);
                        }
                        ?>
                        <tr><td class="left_align columnheader"> <b>CSV HEADER</b> </td><td class="columnheader"> <b>WP FIELDS</b> </td><td class="columnheader"> <b>CSV ROW</b> </td><td></td></tr>
                        <?php
			$mappingFields_arr = array();
			foreach ($impCE->headers as $key => $value) 
			{ ?>
				<tr>
					<td class="left_align csvheader"> <label> <?php print($value);?> </label> </td>
					<td class="left_align"> <select name="mapping<?php print($count); ?>" id="mapping<?php print($count); ?>" class="uiButton" onchange="addcustomfield(this.value,<?php echo $count; ?>);">
					<option id = "select"> -- Select -- </option>
					<?php
					foreach ($impCE->defCols as $key1 => $value1) 
					{
						if ($key1 == 'post_name')
							$key1 = 'post_slug';
						if ($value == 'post_name')
							$value = 'post_slug';

					?> 
								<option value = "<?php print($key1); ?>">  <?php

						if ($key1 != 'post_name')
						{
							print ($key1);
							$mappingFields_arr[$key1] = $key1;
						}
						else
						{
							print 'post_slug';
							$mappingFields_arr['post_slug'] = 'post_slug';
						}
						?>
							</option>
							<?php
					}

					foreach (get_taxonomies() as $taxokey => $taxovalue) 
					{
						if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') 
						{ ?>
							<option value="<?php print($taxokey); ?>"> <?php print($taxovalue);?> </option>
								<?php $mappingFields_arr[$taxovalue] = $taxovalue;
						}
					}
					?>
					<option value="add_custom<?php print($count); ?>">Add Custom Field</option>
					</select>
                                        <script type="text/javascript">
                                        jQuery("select#mapping<?php print($count); ?>").find('option').each(function() {
                                                        if(jQuery(this).val() == "<?php print($value);?>") {
                                                        jQuery(this).prop('selected', true);
                                                        }
                                        });
                                        </script>
 
					</td>
					<td class="left_align csvcolumnvalue">
					<?php
					$getrecords[0][$value] = htmlspecialchars($getrecords[0][$value], ENT_COMPAT, "UTF-8"); 
					if(strlen($getrecords[0][$value])>32)
					{
						$getrecords[0][$value] = substr($getrecords[0][$value], 0, 28).'...';
					} ?>
					<span id='elementVal_<?php echo $key; ?>' > <?php  echo $getrecords[0][$value]; ?> </span>
					</td>
					<td width = "180px;">
					<input class="customfieldtext" type="text" id="textbox<?php print($count); ?>" name="textbox<?php print($count); ?>" TITLE="Replace the default value" style="display: none;float:left;width:160px;" value="<?php echo $value ?>"/>
					<span style="display: none;float:left" id="customspan<?php echo $count ?>">
					<a href="#" class="tooltip">
					<img src="../wp-content/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/help.png" />
					<span class="tooltipPostStatus">
					<img class="callout" src="../wp-content/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/callout.gif" />
					<strong>Give a name for your new custom field</strong>
					<img src="../wp-content/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/help.png" style="margin-top: 6px;float:right;" />
					</span>
					</a> 
					</span>
					<span style="display: none; color: red; margin-left: 5px;" id="customspan<?php echo $count ?>">Replace the custom value</span>
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
		<input type="hidden" id="mapping_fields_array" name="mapping_fields_array" value="<?php if(isset($mFieldsArr)) {  print_r($mFieldsArr); } ?>"/>
		<div>
	                <div class="goto_import_options" align=center>
                <div class="mappingactions"  style="margin-top:26px;">
                <input type='button' id='clear_mapping' class='clear_mapping btn btn-warning' name='clear_mapping' value='Clear Mapping' onclick='clearMapping();' style = 'float:left'/>
                <span style = ''>
                <a href="#" class="tooltip tooltip_smack"  style = ''>
                <img src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>images/help.png" />
                <span class="tooltipClearMapping">
                <img class="callout" src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>images/callout.gif" />
                <strong>Refresh to re-map fields</strong>
                <img src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>images/help.png" style="margin-top: 6px;float:right;" />
                </span>
                </a>
                </span>
                </div>
                <div class="mappingactions" >
                <input type='submit' id='goto_importer_setting' class='goto_importer_setting btn btn-info' name='goto_importer_setting' value='Next >>' />
                </div>
                </div> 
		</div>
		</div>
                <?php } ?>
		</div>
		</form>
		</td>
		</tr>
		<tr>
		<td>
		<h3>Settings and Performance</h3>
             <?php if(isset($_REQUEST['step']) && $_REQUEST['step']  == 'importoptions') { ?>
		<div id='sec-three' <?php if($_REQUEST['step']!= 'importoptions'){ ?> style='display:none;' <?php } ?> >
		<?php //$prevoptionindex='';?>
		<input type="hidden" id="prevoptionindex" name="prevoptionindex" value="<?php 
		if (isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['prevoptionindex']))
		{
			echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['prevoptionindex']; 
		}
		?>"/>
          <?php if(isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES'])) { ?>
		<input type="hidden" id="prevoptionvalue" name="prevoptionvalue" value="<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['prevoptionvalue']; ?>"/>
		<input type='hidden' id='current_record' name='current_record' value='<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['current_record']; ?>' />
		<input type='hidden' id='tot_records' name='tot_records' value='<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['totRecords']; ?>' />
		<input type='hidden' id='checktotal' name='checktotal' value='<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['totRecords']; ?>' />
		<input type='hidden' id='stepstatus' name='stepstatus' value='<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['stepstatus']; ?>' />
		<input type='hidden' id='selectedImporter' name='selectedImporter' value='<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['selectedImporter']; ?>' />
            <?php } ?>                
            <?php if(isset($_POST)) { ?>
 		<input type='hidden' id='tmpLoc' name='tmpLoc' value='<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>' />
		<input type='hidden' id='checkfile' name='checkfile' value='<?php echo $_POST['uploadedFile']; ?>' />
		<input type='hidden' id='select_delim' name='select_delim' value='<?php echo $_POST['select_delimeter']; ?>' />
		<input type='hidden' id='uploadedFile' name='uploadedFile' value='<?php echo $_POST['uploadedFile']; ?>' />
		<input type='hidden' id='inline_image_location' name='location_inlineimages' value='<?php echo $_POST['inline_image_location']; ?>' />
                  <?php } ?>
    		<input type='hidden' id='mappingArr' name='mappingArr' value='' />
		<!-- Import settings options -->
		<div class="postbox" id="options" style=" margin-bottom:0px;">
		<!--        <h4 class="hndle">Search settings</h4>-->
		<div class="inside">
                <label id="importalign"><input type ='radio' id='importNow' name='importMode' value='' onclick='choose_import_mode(this.id);' checked/> <?php echo __("Import right away"); ?> </label> 
                                        <label id="importalign"><input type ='radio' id='scheduleNow' name='importMode' value='' onclick='choose_import_mode(this.id);' disabled/> <?php echo __("Schedule now"); ?> </label>
                  <div id='schedule' style='display:none'>
                                 <input type ='hidden' id='select_templatename' name='#select_templatename' value = '<?php if(isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['templateid'])) { echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['templateid'] ; } ?>'>
                                <?php //echo WPImporter_includes_schedulehelper::generatescheduleHTML(); ?>
                                    </div>
 <div id='importrightaway' style='display:block'>
		<form method="POST">
		<ul id="settings">
		<li>
		<!--Get all posts with an <strong>content-similarity</strong> of more than:                        <strong><span id="similarity_amount">80</span>%</strong>
		<div id="similarity" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 60%;"></a></div>
		</li>
		<input type="hidden" value="80" name="similarity">
		<li id="statuses">Include these <strong>statuses</strong>: <br>
		<input name="status[]" type="checkbox" value="draft"> Draft<br><input name="status[]" type="checkbox" value="pending"> Pending Review<br><input name="status[]" type="checkbox" value="private"> Private<br><input name="status[]" type="checkbox" value="publish" checked=""> Published<br>                    </li>
		<li id="dates">Limit by <strong>post date</strong>:<br>
		from <input id="datefrom" name="datefrom" class="datepicker hasDatepicker" type="text" value="" readonly="readonly"><img class="ui-datepicker-trigger" src="images/date-button.gif" alt="..." title="..."> until                        <input id="dateto" name="dateto" class="datepicker hasDatepicker" type="text" value="" readonly="readonly"><img class="ui-datepicker-trigger" src="images/date-button.gif" alt="..." title="...">
		</li>
		<li>
		Compare <select name="search_field" id="search_field">
		<option value="0" selected="selected">
		content (post_content)                            </option>
		<option value="1">
		title (post_title)                            </option>
		<option value="2">
		content and title                            </option>
		</select><br>
		<input name="filterhtml" id="filterhtml" type="checkbox" value="1"> Filter out HTML-Tags while comparing <br>
		<input name="filterhtmlentities" id="filterhtmlentities" type="checkbox" value="1"> Decode HTML-Entities before comparing <br>-->
		<label id="importalign"><input name='duplicatecontent' id='duplicatecontent' type="checkbox" value=""> Detect duplicate post content</label> <br>
		<label id="importalign"><input name='duplicatetitle' id='duplicatetitle' type="checkbox" value="" > Detect duplicate post title</label> <br>
		 <label id="importalign">No. of posts/rows per server request</label> <span class="mandatory" style="margin-left:-13px;margin-right:10px">*</span> <input name="importlimit" id="importlimit" type="text" value="1" placeholder="10" onblur="check_allnumeric(this.value);"></label> <?php echo $impCE->helpnotes(); ?><br>
			<span class='msg' id='server_request_warning' style="display:none;color:red;margin-left:-10px;">You can set upto <?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['totRecords']; ?> per request.</span>
                <input type="hidden" id="currentlimit" name="currentlimit" value="0"/>
		<input type="hidden" id="tmpcount" name="tmpcount" value="0" />
		<input type="hidden" id="terminateaction" name="terminateaction" value="continue" />
                <label id="innertitle">Inline image options</label><br />
                <label id='importalign'> <input type ='checkbox' id='multiimage' name='multiimage' value = '' onclick="enableinlineimageoption();"> Insert Inline Images </label><br>
                <div id='inlineimageoption' style="display:none;" >
                <label id="importalign"><input type="radio" name="inlineimage_location" id="imagewithextension" value="imagewithextension" onclick="inline_image_option(this.value);" /> Image name with extension </label>
                <label id="importalign"><input type="radio" name="inlineimage_location" id="inlineimage_location" value="inlineimage_location" onclick="inline_image_option(this.value);" /> <input type="text" name="imagelocation" id="imagelocation" placeholder="Inline Image Location" value="" onblur="customimagelocation(this.value);" style="margin-top:5px;margin-left:10px"/></label>
                </div>
                <input type='hidden' id='inlineimagevalue' name='inlineimagevalue' value='none' />
		</li>
		<!--<li>
		Ignore these words while comparing <input name="filterwords" id="filterwords" type="text" value="">
		</li>-->
		</ul>
		<input id="startbutton" class="btn btn-primary" type="button" value="Import Now" style="color: #ffffff;background:#2E9AFE;" onclick="importRecordsbySettings();" >
		<input id="terminatenow" class="btn btn-danger btn-sm" type="button" value="Terminate Now" style="display:none;" onclick="terminateProcess();" />
		<input class="btn btn-warning" type="button" value="Reload" id="importagain" style="display:none" onclick="import_again();" />
		<!--<input id="continuebutton" class="button" type="button" value="Continue old search" style="color: #ffffff;background:#2E9AFE;">-->
		<div id="ajaxloader" style="display:none"><img src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>images/ajax-loader.gif"> Processing...</div>
		<div class="clear"></div>
		</form>
                </div>
		<div class="clear"></div>
		<br>
	<!--	Compared <span id="done">0</span> of <span id="count">6</span> posts<br>Found <span id="found">0</span> duplicates            <br><input id="deletebutton" style="display: none" class="button" type="button" value="Move selected posts to trash">-->
		</div>
		</div>
                 <?php } ?>
		<!-- Code Ends Here-->
		</div>
		</td>
		</tr>
		</table>
		</div>
                   <div style="width:100%;">
                                               <div id="accordion">
                                               <table class="table-importer">
                                               <tr>
                                               <td>
                                               <h3><?php echo __("Summary"); ?></h3>
                                                <div id='reportLog' class='postbox'  style='display:none;'>
                                                <input type='hidden' name = 'csv_version' id = 'csv_version' value = "<?php if(isset($_POST['uploaded_csv_name'])) { echo $_POST['uploaded_csv_name']; } ?>">
                                                <div id="logtabs" class="logcontainer">
                                                <div id="log" class='log'>
                                                </div>
                                                </div>
                                                </div>
                                                </td>
                                                </tr>
                                                </table>
                                                </div>
                                                </div>
                <!-- Promotion footer for other useful plugins -->
                <div class= "promobox" id="pluginpromo" style="width:98%;">
                <div class="accordion-group" >
                <div class="accordion-body in collapse">
                <div>
                <?php //$impCE->common_footer_for_other_plugin_promotions(); ?>
		<?php $impCE->common_footer(); ?>
                </div>
                </div>
                </div>
                </div> 
	</div>
