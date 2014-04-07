<?php
/* Module : Comments
   Author : Fredrick, Mansoor
   Owner  : smackcoders.com
   Date   : Feb11,2014
 */
?>
<div style="width:100%;">
<div id="accordion">
<?php $impCE = new WPImporter_includes_helper(); 
?>
<table class="table-importer">
<tr>
<td>
  <h3>Import CSV File</h3>
  <div id='sec-one' <?php if($_REQUEST['step']!= 'uploadfile') {?> style='display:none;' <?php } ?>>
  <?php if(is_dir($impCE->getUploadDirectory('default'))){ ?>
        <input type='hidden' id='is_uploadfound' name='is_uploadfound' value='found' />
  <?php } else { ?>
        <input type='hidden' id='is_uploadfound' name='is_uploadfound' value='notfound' />
  <?php } ?>
  <form action='<?php echo admin_url().'admin.php?page='.WP_CONST_ULTIMATE_CSV_IMP_SLUG.'/index.php&__module=comments&step=mapping_settings'?>' id='browsefile' method='post' name='browsefile'>
  <div class="importfile" align='center'>
	<div id='filenamedisplay'><!--<span class='importer_icon' style="float:left;"><img src = "<?php //echo WP_CONST_ULTIMATE_CSV_IMP_DIR;?>/images/Importicon_24.png"></span><span style="float:left;"><h2>Import CSV File</h2></span>--></div><form class="add:the-list: validate" style="clear:both;" method="post" enctype="multipart/form-data" onsubmit="return file_exist();">
<div class="container">
    <!-- The fileinput-button span is used to style the file input field as button -->
    <span class="btn btn-success fileinput-button">
        <!--<i class="glyphicon glyphicon-plus"></i>-->
        <span>Browse</span>
        <!-- The file input field used as target for the file upload widget -->
<input type ='hidden' id="pluginurl"value="<?php echo WP_CONTENT_URL;?>">
<?php $uploadDir = wp_upload_dir(); ?>
<input type="hidden" id="uploaddir" value="<?php echo $uploadDir['basedir']; ?>">
<input type="hidden" id="uploadFileName" name="uploadfilename" value="">
        <input type = 'hidden' id = 'uploadedfilename' name = 'uploadedfilename' value = ''>
        <input type = 'hidden' id = 'upload_csv_realname' name = 'upload_csv_realname' value =''>
        <input type = 'hidden' id = 'current_file_version' name = 'current_file_version' value = ''>
        <input type = 'hidden' id = 'current_module' name = 'current_module' value = '<?php echo $_REQUEST['__module']; ?>' >
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
    <!-- The global progress bar -->
    <div id="progress" class="progress">
        <div class="progress-bar progress-bar-success"></div>
    </div>
    <div class = "form-group" style="margin-top: 10px;margin-left: 100px;">
    <div id="delimeter" class="delimeter"><span>Select your delimeter: <select name="mydelimeter">
<option value=",">,</option>
<option value=";">;</option>
</select></span></div>
        <input type = 'button' name='clearform' id='clearform' value='Clear' onclick="Reload();" class = 'btn btn-warning' />
        <input type = 'submit' name='importfile' id='importfile' value='Next>>' disabled class = 'btn btn-primary' />
        </div>
<div class="warning" id="warning" name="warning" style="display:none"></div>
    <!-- The container for the uploaded files -->
    <div id="files" class="files"></div>
	<div id="defaultpanel" align='center' style='width:100%;'> <p class='msgborder' style='color:green;'> You can also drag and drop files here</div>
<!--    <div class="panel panel-default" id="defaultpanel">
            <h4 class="panel-title">Notes</h4>
            <ul>
                <li>1. The maximum file size for uploads is unlimited.</li>
                <li>2. You can drag and drop files from your desktop on this webpage.</li>
            </ul>
    </div> -->
</div>
<script>
var check_upload_dir = document.getElementById('is_uploadfound').value; 
if(check_upload_dir == 'notfound'){
$('#defaultpanel').css('visibility','hidden');
$('<p/>').text("").appendTo('#warning');
$( "#warning" ).empty();
$('#warning').css('display','inline');
$('<p/>').text("Warning:   Sorry. There is no uploads directory Please create it with write permission.").appendTo('#warning');
$('#warning').css('color','red');
$('#warning').css('font-weight','bold');
$('#progress .progress-bar').css('visibility','hidden');
}
else{
$(function () {
    'use strict';
var uploadPath = document.getElementById('uploaddir').value;
var url = (document.getElementById('pluginurl').value+'/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/templates/uploader.php')+'?uploadPath='+uploadPath+'&curr_action=<?php echo $_REQUEST['__module']; ?>';
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
document.getElementById('uploadFileName').value=file.name;
                var filewithmodule = file.uploadedname.split(".csv");
                file.uploadedname = filewithmodule[0]+"-<?php echo $_REQUEST['__module']; ?>"+".csv";
                document.getElementById('upload_csv_realname').value = file.uploadedname; 
                var get_version1 = file.name.split("-<?php echo $_REQUEST['__module']; ?>"); 
                var get_version2 = get_version1[1].split(".csv");
                var get_version3 = get_version2[0].split("-");
                document.getElementById('current_file_version').value = get_version3[1];
                $('#uploadedfilename').val(file.uploadedname);
		    $( "#filenamedisplay" ).empty(); //alert(file.size);
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
		    $('<p/>').text((file.name)+' - '+fileSize).appendTo('#filenamedisplay');
$('#importfile').attr('disabled', false);

            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
}
</script>
  <input type = 'hidden' name = 'importid' id = 'importid' >
<!--  <div class='section-one' align='center'>
  <input type='button' name='clearform' id='clearform' value='Clear' class = 'btn btn-warning' onclick="Reload();"/> 
  <input type='submit' name='importfile' id='importfile' value='Next>>' class = 'btn btn-primary' disabled/>
  <input type = 'hidden' name = 'importid' id = 'importid' >
  </div> -->
  </form>
  </div>
  </div>
</td>
</tr>
<tr>
<td>
<form name='mappingConfig' action="<?php echo admin_url(); ?>admin.php?page=<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/index.php&__module=comments&step=importoptions"  method="post" onsubmit="return import_csv();" >
<div class='msg' id = 'showMsg' style = 'display:none;'></div>
<?php $_SESSION['SMACK_MAPPING_SETTINGS_VALUES'] = $_POST;
		if(isset($_POST['mydelimeter']))
      $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['delim'] = $_POST["mydelimeter"]; 
 $wpcsvsettings=array();
$wpcsvsettings=get_option('wpcsvfreesettings');
?>
  <h3>Mapping Configuration</h3>
  <div id='sec-two' <?php if($_REQUEST['step']!= 'mapping_settings'){ ?> style='display:none;' <?php } ?> >
  <div class='mappingsection'>
  <h2><div class="secondformheader">Import Data Configuration</div></h2>

  <div id="select_cust_taxonomy" class="select_cust_taxonomy" style="margin-top: 30px;">
  
  <div id='mappingheader' class='mappingheader' >
  <?php 
// $impCE = CallSkinnyObj::getInstance(); 
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
$getrecords = $impCE->csv_file_data($filename,$delimeter); //print('<pre>');print_r($getrecords); print_r($impCE->headers);
$getcustomposts=get_post_types();
$allcustomposts='';
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
   $cnt1 = count($impCE->headers); ?>
   <input type="hidden" id="h1" name="h1" value="<?php echo $cnt; ?>"/>
   <input type="hidden" id="h2" name="h2" value="<?php echo $cnt1; ?>"/>
   <input type='hidden' name='selectedImporter' id='selectedImporter' value="<?php echo $_REQUEST['__module']; ?>"/>
   <input type="hidden" id="prevoptionindex" name="prevoptionindex" value=""/>
   <input type="hidden" id="prevoptionvalue" name="prevoptionvalue" value=""/>
   <input type='hidden' id='current_record' name='current_record' value='0' />
   <input type='hidden' id='totRecords' name='totRecords' value='<?php echo count($getrecords); ?>' />
   <input type='hidden' id='tmpLoc' name='tmpLoc' value='<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>' />
   <input type='hidden' id='uploadedFile' name='uploadedFile' value="<?php echo  $filename; ?>" />
   <!-- real uploaded filename -->
   <input type='hidden' id='uploaded_csv_name' name='uploaded_csv_name' value="<?php echo $uploaded_csv_name; ?>" />
   <input type='hidden' id='select_delimeter' name='select_delimeter' value="<?php echo  $delimeter; ?>" />
   <input type='hidden' id='stepstatus' name='stepstatus' value='<?php echo $_REQUEST['step']; ?>' />
   <input type='hidden' id='mappingArr' name='mappingArr' value='' />
   <input type='button' id='prev_record' name='prev_record' class="btn btn-primary" value='<<' onclick='gotoelement(this.id);' />
   <label style="padding-right:10px;">Change the csv sample record value by rows</label>
   <input type='button' id='next_record' name='next_record' class="btn btn-primary" value='>>' onclick='gotoelement(this.id);' />
   Go To: <input type='text' id='goto_element' name='goto_element' />
   <input type='button' id='apply_element' name='apply_element' value='Get Record' class="btn btn-success" onclick='gotoelement(this.id);' />
   </div>
   </td>
   </tr> 
   <?php
   $count = 0;
   $cmdsObj = new CommentsActions(); 
   ?>
   <tr><td class="left_align"> <b>CSV HEADER</b> </td><td> <b>WP FIELDS</b> </td><td> <b>SAMPLE VALUE</b> </td><td></td></tr>
   <?php
   foreach ($impCE->headers as $key => $value) {
	   ?>
		   <tr>
		   <td class="left_align"><label><?php print($value);?></label></td>
		   <td><select name="mapping<?php print($count); ?>" id="mapping<?php print($count); ?>" class="uiButton" onchange="addcustomfield(this.value,<?php echo $count; ?>);">
		   <option id="select">-- Select --</option>
		   <?php
			foreach ($cmdsObj->defCols as $key1 => $value1) {
                           if ($key1 == 'post_name')
                                   $key1 = 'post_slug';
                        if ($value == 'post_name')
                                   $value = 'post_slug';

							
			                                ?>
                                                                <option value = "<?php print($key1); ?>">  <?php

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

		   ?>
		   </select> 
		   </td>
		   <td>
			<?php 
				if(strlen($getrecords[0][$key])>32)
					{
			 $getrecords[0][$key] = substr($getrecords[0][$key], 0, 28).'...';
			} ?>
		   <span id='elementVal_<?php echo $key; ?>' > <?php  echo $getrecords[0][$key]; ?> </span>
		   </td>
<td>
<input class="customfieldtext" type="text" id="textbox<?php print($count); ?>" name="textbox<?php print($count); ?>" TITLE="Replace the default value" style="display: none;" value="<?php echo $value ?>"/>
                   <span style="display: none;" id="customspan<?php echo $count ?>">
                   <a href="#" class="tooltip">
                   <img src="../wp-content/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/help.png" />
                   <span class="tooltipFour">
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
$mFieldsArr='';
foreach($mappingFields_arr as $mkey => $mval){
	$mFieldsArr .= $mkey.',';
}
$mFieldsArr = substr($mFieldsArr, 0, -1);
?>
</table></div>
<input type="hidden" id="mapping_fields_array" name="mapping_fields_array" value="<?php print_r($mFieldsArr); ?>"/>

<div>
		<div class="goto_import_options" align=center>
                <div class="mappingactions" >
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
</form>
</td>
</tr>
<tr>
<td>
  <h3>Import Option Settings</h3>
<!--<?php //echo $_POST['uploadedFile']; ?><?php //echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['totRecords']; die;?>-->
  <div id='sec-three' <?php if($_REQUEST['step']!= 'importoptions'){ ?> style='display:none;' <?php } ?> >
   <input type="hidden" id="prevoptionindex" name="prevoptionindex" value="<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['prevoptionindex']; ?>"/>
   <input type="hidden" id="prevoptionvalue" name="prevoptionvalue" value="<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['prevoptionvalue']; ?>"/>
   <input type='hidden' id='current_record' name='current_record' value='<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['current_record']; ?>' />
   <input type='hidden' id='tot_records' name='tot_records' value='<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['totRecords']; ?>' />
<input type='hidden' id='checktotal' name='checktotal' value='<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['totRecords']; ?>' />
   <input type='hidden' id='tmpLoc' name='tmpLoc' value='<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>' />
	<input type='hidden' id='checkfile' name='checkfile' value='<?php echo $_POST['uploadedFile']; ?>' />
	<input type='hidden' id='select_delim' name='select_delim' value='<?php echo $_POST['select_delimeter']; ?>' />
   <input type='hidden' id='uploadedFile1' name='uploadedFile1' value='<?php echo $_POST['uploadedFile']; ?>' />
   <input type='hidden' id='stepstatus' name='stepstatus' value='<?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['stepstatus']; ?>' />
   <input type='hidden' id='mappingArr' name='mappingArr' value='' />
<!-- Import settings options -->
<div class="postbox" id="options" style=" margin-bottom:0px;">
<!--        <h4 class="hndle">Search settings</h4>-->
        <div class="inside">
            <form method="POST">
                <ul id="settings">
                    <li>
			<!--Get all posts with an <strong>content-similarity</strong> of more than:                        <strong><span id="similarity_amount">80</span>%</strong>
                        <div id="similarity" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 60%;"></a></div>
                    </li>
                    <input type="hidden" value="80" name="similarity">
                    <li id="types"><label for="types">Compare this <strong>type</strong>:</label><br>
                        <input type="radio" value="post" name="types" checked=""> Posts<br> <input type="radio" value="page" name="types"> Pages<br> <input type="radio" value="attachment" name="types"> Media<br> <input type="radio" value="revision" name="types"> Revisions<br> <input type="radio" value="nav_menu_item" name="types"> Navigation Menu Items<br>                     </li>
                    <li id="statuses">Include these <strong>statuses</strong>:                        <br>
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
                        <input name="filterhtml" id="filterhtml" type="checkbox" value="1"> Filter out HTML-Tags while comparing                        <br>
                        <input name="filterhtmlentities" id="filterhtmlentities" type="checkbox" value="1"> Decode HTML-Entities before comparing                        <br>-->
			<label><input name='duplicatecontent' id='duplicatecontent' type="checkbox" value=""> Detect Duplicate Post Content</label> <br>
			<label><input name='duplicatetitle' id='duplicatetitle' type="checkbox" value="" > Detect Duplicate Post Title</label> <br>

                        How much comparisons per Server-Request? <span class="mandatory">*</span> <input name="importlimit" id="importlimit" type="text" value="" onblur="check_allnumeric(this.value);">
			<span class='msg' id='server_request_warning' style="display:none;color:red;margin-left:-10px;">You can set upto <?php echo $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['totRecords']; ?> per request.</span>
			<input type="hidden" id="currentlimit" name="currentlimit" value="0"/>
			<input type="hidden" id="tmpcount" name="tmpcount" value="0" />
			<input type="hidden" id="terminateaction" name="terminateaction" value="continue" />
                    </li>
<!--                    <li>
                        Ignore these words while comparing <input name="filterwords" id="filterwords" type="text" value="">
                    </li>-->
                </ul>
                <input id="startbutton" class="btn btn-primary" type="button" value="Import Now" style="color: #ffffff;background:#2E9AFE;" onclick="importRecordsbySettings();" />
		<input id="terminatenow" class="btn btn-danger btn-sm" type="button" value="Terminate Now" style="" onclick="terminateProcess();" />
		<input class="btn btn-warning" type="button" value="Import Again" id="importagain" style="display:none" onclick="import_again();" />
<!--                <input id="continuebutton" class="button" type="button" value="Continue old search" style="color: #ffffff;background:#2E9AFE;">-->
		<div id="ajaxloader" style="display:none"><img src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>images/ajax-loader.gif"> Processing...</div>
                <div class="clear"></div>
            </form>
            <div class="clear"></div>
            <br>
           <!-- Compared <span id="done">0</span> of <span id="count">6</span> posts<br>Found <span id="found">0</span> duplicates            <br><input id="deletebutton" style="display: none" class="button" type="button" value="Move selected posts to trash">-->
        </div>
    </div>
<!-- Code Ends Here-->
  </div>
</td>
</tr>
</table>
</div>
<div id='reportLog' class='reportLog'>
    <h3>Logs :</h3>
    <div id="logtabs" class="logcontainer">
       	<div id="log" class='log'>	
	</div>
    </div>
</div>
<!-- Promotion footer for other useful plugins -->
<!--<div class= "promobox" id="pluginpromo" style="width:99%;">
	<div class="accordion-group" >
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> OTHER USEFUL PLUGINS BY SMACKCODERS </a>
		</div>
		<div class="accordion-body in collapse">
		<div>
			<?php // $impCE->common_footer_for_other_plugin_promotions(); ?>
		</div>
		</div>
	</div>
</div> -->
</div>
