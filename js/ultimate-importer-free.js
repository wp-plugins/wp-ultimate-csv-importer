jQuery( document ).ready(function() {
      jQuery('.dropdown-toggle').dropdown('toggle');
   var checkmodule = document.getElementById('checkmodule').value;
        if(checkmodule != 'dashboard' && checkmodule != 'filemanager' && checkmodule !='support' && checkmodule !='export') {
                var get_log = document.getElementById('log').innerHTML;
                   if (!jQuery.trim(jQuery('#log').html()).length) {      
                  document.getElementById('log').innerHTML = '<p style="margin:15px;color:red;">NO LOGS YET NOW.</p>';
                }
       
//pieStats();
//lineStats();


 }
 if (checkmodule == 'custompost') {
		    var step = jQuery('#stepstatus').val();
		    if (step == 'mapping_settings') {
			    var cust_post_list_count = jQuery('#cust_post_list_count').val();
			    if (cust_post_list_count == '0')
				    document.getElementById('cust_post_empty').style.display = '';
		    }
	    }
 if (checkmodule != 'filemanager' && checkmodule != 'settings' && checkmodule !='support' && checkmodule !='export') {
	 var checkfile = jQuery('#checkfile').val();
	 var uploadedFile = jQuery('#uploadedFile').val();
	 var select_delimeter = jQuery('#select_delim').val();
	 var select_delim = jQuery('#select_delim').val();
	 var get_log = jQuery('#log').val();
	 if (!jQuery.trim(jQuery('#log').html()).length) {
		 if(checkmodule != 'dashboard') 
			 document.getElementById('log').innerHTML = '<p style="margin:15px;color:red;">NO LOGS YET NOW.</p>';
	 }

	 if (checkfile != '') {
		 uploadedFile = checkfile;
	 }
	 if (select_delimeter != '') {
		 select_delim = select_delimeter;
	 }
	 if(uploadedFile != '' && select_delim != '') { 
		 var doaction = 'record_no=1&file_name=' + uploadedFile + '&selected_delimeter=' + select_delim;
		 var tmpLoc = jQuery('#tmpLoc').val();
		 if(tmpLoc != '' && tmpLoc != null) {
			jQuery.ajax({
				url: tmpLoc + 'templates/readfile.php',
				type: 'post',
				data: doaction,
				dataType: 'json',
				success: function (response) {
					if (response != null) {
						var totalLength = response.length;
						var setHeight = (parseInt(totalLength) * 30) + 250;
					}
				}
			});
		 }
	 }
 }
});
function goto_mapping(id){
if(id == 'importfile'){
var currentURL = document.URL; 
var go_to_url = currentURL.replace("uploadfile","mapping_settings");
window.location.assign(go_to_url);
document.getElementById('sec-one').style.display='none';
document.getElementById('sec-two').style.display='';
}
}

function gotoelement(id) {
    var gotoElement = document.getElementById('current_record').value;
    var no_of_records = document.getElementById('totRecords').value;
    var uploadedFile = document.getElementById('uploadedFile').value;
    var delim = document.getElementById('select_delimeter').value;
    if (id == 'prev_record') {
        gotoElement = parseInt(gotoElement) - 1;
    }
    if (id == 'next_record') {
        gotoElement = parseInt(gotoElement) + 1;
    }
    if (gotoElement <= 0) {
        gotoElement = 0;
    }
    if (gotoElement >= no_of_records) {
        gotoElement = parseInt(no_of_records) - 1;
    }
    if (id == 'apply_element') {
        gotoElement = parseInt(document.getElementById('goto_element').value);
        if (isNaN(gotoElement)) {
            showMapMessages('error', ' Please provide valid record number.');
        }
        if (gotoElement <= 0) {
            gotoElement = 0;
            showMapMessages('error', ' Please provide valid record number.');
        } else {
            gotoElement = gotoElement - 1;
        }
        if (gotoElement >= no_of_records) {
            gotoElement = parseInt(no_of_records) - 1;
            showMapMessages('error', 'CSV file have only ' + no_of_records + ' records.');
            return false;
        }
    }
    var doaction = 'record_no=' + gotoElement + '&file_name=' + uploadedFile + '&delim='+ delim;
    var tmpLoc = document.getElementById('tmpLoc').value;
    jQuery.ajax({
        url: tmpLoc + 'templates/readfile.php',
        type: 'post',
        data: doaction,
        dataType: 'json',
        success: function (response) {
            var totalLength = response.length;
            for (var i = 0; i < totalLength; i++) {
                if ((response[i].length) > 32) {
                    document.getElementById('elementVal_' + i).innerHTML = response[i].substring(0, 28) + '...';
                } else {
                    document.getElementById('elementVal_' + i).innerHTML = response[i];
                }
            }
        var displayRecCount = gotoElement + 1;
            document.getElementById('preview_of_row').innerHTML = "Showing preview of row #" + displayRecCount;
            document.getElementById('current_record').value = gotoElement;
        }
    });
}

function showtemplatediv_wpuci(checked, div)
{
	if(checked)
		jQuery('#'+div).show();
	else
		jQuery('#'+div).hide();
}

function showtemplatediv_edit(checked, value)
{
	if(value == 'saveas')
		jQuery('#showtemplate_edit_div').show();
	else
		jQuery('#showtemplate_edit_div').hide();
}


function selectpoststatus()
{
	var ps = document.getElementById("importallwithps");
	var selectedpsindex = ps.options[ps.selectedIndex].value;
	if(selectedpsindex == 3){
		document.getElementById('globalpassword_label').style.display = "block";
		document.getElementById('globalpassword_text').style.display = "block";
	}
	else{
                document.getElementById('globalpassword_label').style.display = "none";
                document.getElementById('globalpassword_text').style.display = "none";
	}
	var totdropdown= document.getElementById('h2').value;
	var total = parseInt(totdropdown);
	if(selectedpsindex=='0')
	{

		for(var i=0;i < total;i++)
		{

			dropdown = document.getElementById("mapping"+i);
			var option=document.createElement('option');
			option.text="post_status";
			dropdown.add(option);

		}

	}
	else {
		for(var i=0;i < total;i++)
		{

			dropdown = document.getElementById("mapping"+i);

			var totarr = dropdown.options.length;

			for(var j=0;j<totarr;j++)
			{

				if(dropdown.options[j].value=='post_status')
				{

					dropdown.options.remove(j);
					totarr--;
				}
			}

		}
	}
}



// Function for add customfield

function addcustomfield(myval, selected_id) { 
    var a = document.getElementById('h1').value;
    var importer = document.getElementById('selectedImporter').value;
    var aa = document.getElementById('h2').value;
    var selected_dropdown = document.getElementById('mapping' + selected_id);
    var selected_value = selected_dropdown.value; 
    var prevoptionindex = document.getElementById('prevoptionindex').value;
    var prevoptionvalue = document.getElementById('prevoptionvalue').value;
    var mappedID = 'mapping' + selected_id;
    var add_prev_option = false;
    if(mappedID == prevoptionindex){
	    add_prev_option = true;	
    }
    for (var i = 0; i < aa; i++) {
	    var b = document.getElementById('mapping' + i).value; 
	    var id = 'mapping' + i;
	    if(add_prev_option){
		    if(i != selected_id){	
			    jQuery('#'+id).append( new Option(prevoptionvalue,prevoptionvalue) );
		    }
	    }
	    if(i != selected_id){
		    var x=document.getElementById('mapping' + i);
		    jQuery('#'+id+' option[value="'+selected_value+'"]').remove();
	    }
	    if (b == 'add_custom' + i) {
		    document.getElementById('textbox' + i).style.display = "";
		    document.getElementById('customspan' + i).style.display = "";
	    }
	    else {
		    document.getElementById('textbox' + i).style.display = "none";
		    document.getElementById('customspan' + i).style.display = "none";
	    }
    }
    document.getElementById('prevoptionindex').value = 'mapping' + selected_id;
    var customField = selected_value.indexOf("add_custom");
    if(selected_value != '-- Select --' && customField != 0){
	    document.getElementById('prevoptionvalue').value = selected_value;
    }
}


function clearMapping()
{
	var total_mfields = document.getElementById('h2').value; 
	var mfields_arr = document.getElementById('mapping_fields_array').value;
	var n=mfields_arr.split(",");
	var options = '<option id="select">-- Select --</option>';
	for(var i=0;i<n.length;i++){
		options +="<option value='"+n[i]+"'>"+n[i]+"</option>";
	}
	for(var j=0;j<total_mfields;j++){
		document.getElementById('mapping'+j).innerHTML = options;
		document.getElementById('mapping'+j).innerHTML += "<option value='add_custom"+j+"'>Add Custom Field</option>";
		document.getElementById('textbox'+j).style.display = 'none';
		document.getElementById('customspan'+j).style.display = 'none';
	}	
}

function clearmapping()
{
        var total_mfields = document.getElementById('h2').value;
        var mfields_arr = document.getElementById('mapping_fields_array').value;
        var n=mfields_arr.split(",");
        var options = "<option id='select'>-- Select --</option>";
        for(var i=0;i<n.length;i++){
                options +="<option value='"+n[i]+"'>"+n[i]+"</option>";
        }
        for(var j=0;j<total_mfields;j++){
                document.getElementById('mapping'+j).innerHTML = options;
                //document.getElementById('mapping'+j).innerHTML += "<option value='add_custom"+j+"'>Add Custom Field</option>";
                document.getElementById('textbox'+j).style.display = 'none';
                document.getElementById('customspan'+j).style.display = 'none';
        }
}

function shownotification(msg, alerts)
{
        var newclass;
        var divid = "notification_wp_csv";

        if(alerts == 'success')
                newclass = "alert alert-success";
        else if(alerts == 'danger')
                newclass = "alert alert-danger";
        else if(alerts == 'warning')
                newclass = "alert alert-warning";
        else
                newclass = "alert alert-info";

        jQuery('#'+divid).removeClass()
        jQuery('#'+divid).html(msg);
        jQuery('#'+divid).addClass(newclass);
        // Scroll
        jQuery('html,body').animate({
                scrollTop: jQuery("#"+divid).offset().top},
        'slow'); 
}

function import_csv() 
{
	// code added by goku to check whether templatename
	var mapping_checked = jQuery('#mapping_templatename_checked').is(':checked');
	var mapping_tempname = jQuery('#mapping_templatename').val();
	var mapping_checked_radio = jQuery('input[name=tempaction]:radio:checked').val();
	if(mapping_checked || mapping_checked_radio == 'saveas')	
	{
		if(mapping_checked_radio == 'saveas')
			mapping_tempname = jQuery('#mapping_templatename_edit').val();

		if(jQuery.trim(mapping_tempname) == '')	
		{
			alert('Template name is empty');
			return false;
		}
		else
		{
			// check templatename already exists
			jQuery.ajax({
		                type: 'POST',
                		url: ajaxurl,
				async: false,
		                data: {
                		    'action'       : 'checktemplatename',
		                    'templatename' : mapping_tempname,
		                },
                		success:function(data) 
				{
					if(data != 0)
					{
						jQuery('#mapping_templatename').val('');
					}
        	        	},
		                error: function(errorThrown){
        	        	        console.log(errorThrown);
        		        }
		        });		
		}
	}
	var mapping_tempname = jQuery('#mapping_templatename').val();
	if(mapping_checked_radio == 'saveas')
        	//mapping_tempname = jQuery('#mapping_templatename_edit').val();

	if(mapping_tempname == '' && (mapping_checked || mapping_templatename_edit == 'saveas'))
	{
		alert('Template Name already exists');return false;
	}
	// code ends here on checking templatename

    var importer = document.getElementById('selectedImporter').value;
    var header_count = document.getElementById('h2').value;
    var array = new Array();
    var val1, val2, val3, val4, val5, val6, val7, error_msg, chk_status_in_csv, post_status_msg;
    val1 = val2 = val3 = val4 = val5 = val6 = val7 = post_status_msg = post_type = 'Off';
    for (var i = 0; i < header_count; i++) {
        var e = document.getElementById("mapping" + i);
        var value = e.options[e.selectedIndex].value;
        array[i] = value;
    }
//alert(array.length);
    if (importer == 'post' || importer == 'page' || importer == 'custompost') { 
	if(importer == 'custompost') {
        	var getSelectedIndex = document.getElementById('custompostlist');
	        var SelectedIndex = getSelectedIndex.value;
			//var t=getSelectedIndex.options[getSelectedIndex.selectedIndex];
			if( SelectedIndex != 'select')
			post_type='On';
			//alert(t+'---'+SelectedIndex);
	}

        chk_status_in_csv = document.getElementById('importallwithps').value;
        if (chk_status_in_csv != 0)
            post_status_msg = 'On';

        for (var j = 0; j < array.length; j++) {
            if (array[j] == 'post_title') {
                val1 = 'On';
            }
            if (array[j] == 'post_content') {
                val2 = 'On';
            }
            if (post_status_msg == 'Off') {
                if (array[j] == 'post_status')
                    post_status_msg = 'On';
            }
        }
        if (importer != 'custompost' && val1 == 'On' && val2 == 'On' && post_status_msg == 'On') {
		return true;
        }
	else if (importer == 'custompost' && val1 == 'On' && val2 == 'On' && post_status_msg == 'On' && post_type=='On') {
                return true;
        }
        else {
            error_msg = '';
            if (val1 == 'Off')
                error_msg += " post_title,";
            if (val2 == 'Off')
                error_msg += " post_content,";
	    if(importer == 'custompost') {
	            if (SelectedIndex == 'select')
        	        error_msg += " post_type,";
	    }
            if (post_status_msg == 'Off')
                error_msg += " post_status";
            showMapMessages('error', 'Error: ' + error_msg + ' - Mandatory fields. Please map the fields to proceed.');
            return false;
        }
    }

// validation starts
else if(importer == 'comments'){
        //var getSelectedIndex1 = document.getElementById('selectPosts');
        //var SelectedIndex1 = getSelectedIndex1.options[getSelectedIndex1.selectedIndex].text;
                for(var j=0;j<array.length;j++){
                        if(array[j] == 'comment_author'){
                                val1 = 'On';
                        }
                        if(array[j] == 'comment_author_email'){
                                val2 = 'On';
                        }
                        if(array[j] == 'comment_content'){
                                val3 = 'On';
                        }
			if(array[j] == 'comment_post_ID'){
                                val4 = 'On';
                        }


                }
                if(val1 == 'On' && val2 == 'On' && val3 == 'On' && val4 == 'On') {
                 return true;
                }
                else{
                 showMapMessages('error',' "Post Id", "Comment Author", "Comment Author Email" and "Comment Content" should be mapped.');
                 return false;
                }

	
		showMapMessages('error',header_count);return false;
	}
	else if(importer == 'users'){
		//var getSelectedIndex = document.getElementById('userrole');
		//var SelectedIndex = getSelectedIndex.options[getSelectedIndex.selectedIndex].text;
		for(var j=0;j<array.length;j++){
			if(array[j] == 'user_login'){
				val1 = 'On';	
			}
			if(array[j] == 'user_email'){
				val2 = 'On';
			}
			if(array[j] == 'role'){
				val3 = 'On';
			}
		}
		if(val1 == 'On' && val2 == 'On' && val3 == 'On') {
	   	 return true;
		}
		else{
		 showMapMessages('error','"role", "user_login" and "user_email" should be mapped.');
		 return false;
		}
	}
// validation ends
}


function showMapMessages(alerttype, msg) {
    jQuery("#showMsg").addClass("maperror");
    document.getElementById('showMsg').innerHTML = msg;
    document.getElementById('showMsg').className += ' ' + alerttype;
    document.getElementById('showMsg').style.display = '';
    jQuery("#showMsg").fadeOut(10000);
}

function filezipopen()
{
var advancemedia = document.getElementById('advance_media_handling').checked;
if(advancemedia == true)
        document.getElementById('filezipup').style.display = '';
else
        document.getElementById('filezipup').style.display = 'none';

}
//var allowedextension ={ '.zip' : 1 };
function checkextension(filename)
{
var allowedextension ={ '.zip' : 1 };
var match = /\..+$/;
          var ext = filename.match(match);
          if (allowedextension[ext])
          {
                return true;
          }
          else
          {
                alert("File must be .zip!");
                //will clear the file input box.
                location.reload();
                return false;
          }

}


function inline_image_option(id) {
	document.getElementById('startbutton').disabled = false;
	var selected_option = document.getElementById(id).value;
	document.getElementById('inlineimagevalue').value = selected_option;
	if(selected_option == 'inlineimage_location') {
		var image_location = document.getElementById('imagelocation').value;
		document.getElementById('inlineimagevalue').value = image_location;
	}
}

function customimagelocation(val) {
	document.getElementById('inlineimagevalue').value = val;
}

function enableinlineimageoption() {
        var importinlineimage = document.getElementById('multiimage').checked;
        if(importinlineimage == true) {
                document.getElementById('inlineimageoption').style.display = '';
		document.getElementById('startbutton').disabled = true;
        } else {
                document.getElementById('inlineimageoption').style.display = 'none';
		document.getElementById('startbutton').disabled = false;
	}
}

function importRecordsbySettings(siteurl)
{
        var importlimit = document.getElementById('importlimit').value; 
        var get_requested_count = importlimit; 
        var tot_no_of_records = document.getElementById('checktotal').value;
        var importas = document.getElementById('selectedImporter').value;
        var uploadedFile = document.getElementById('checkfile').value;
       // var no_of_columns = document.getElementById('h2').value;
        var step = document.getElementById('stepstatus').value;
        var mappingArr = document.getElementById('mappingArr').value;
	var dupContent = document.getElementById('duplicatecontent').checked;
	var dupTitle = document.getElementById('duplicatetitle').checked;
	var currentlimit = document.getElementById('currentlimit').value;
	var tmpCnt = document.getElementById('tmpcount').value;
	var no_of_tot_records = document.getElementById('tot_records').value;
	var importinlineimage = false;
	var imagehandling = false;
	var inline_image_location = false;
	var currentModule = document.getElementById('current_module').value;
	if(currentModule != 'users' && currentModule != 'comments') {
		importinlineimage = document.getElementById('multiimage').checked;
		imagehandling = document.getElementById('inlineimagevalue').value;
		inline_image_location = document.getElementById('inline_image_location').value;
	}
        var get_log = document.getElementById('log').innerHTML;
         document.getElementById('reportLog').style.display = '';
         document.getElementById('terminatenow').style.display = '';
        if(get_requested_count != '') {
                //return true;
        } else {
                 document.getElementById('showMsg').style.display = "";
                 document.getElementById('showMsg').innerHTML = '<p id="warning-msg" class="alert alert-warning">Fill all mandatory fields.</p>';			jQuery("#showMsg").fadeOut(10000);
                 return false;
        }
	if(parseInt(get_requested_count) <= parseInt(no_of_tot_records)) {
                document.getElementById('server_request_warning').style.display = 'none';
        } else {
                document.getElementById('server_request_warning').style.display = '';
                return false;
        }
	if(get_log == '<p style="margin:15px;color:red;">NO LOGS YET NOW.</p>'){
		document.getElementById('log').innerHTML = '<p style="margin:15px;color:red;">Your Import Is In Progress...</p>';
		document.getElementById('startbutton').disabled = true;
	}
	document.getElementById('ajaxloader').style.display="";
        var tempCount = parseInt(tmpCnt);
        var totalCount = parseInt(tot_no_of_records);
        if(tempCount>totalCount){
		document.getElementById('ajaxloader').style.display="none";
		document.getElementById('startbutton').style.display="none";
		document.getElementById('importagain').style.display="";
                document.getElementById('terminatenow').style.display = "none";
		return false;
	}

	var postdata = new Array();
	postdata = {'dupContent':dupContent,'dupTitle':dupTitle,'importlimit':importlimit,'limit':currentlimit,'totRecords':tot_no_of_records,'selectedImporter':importas,'uploadedFile':uploadedFile,'tmpcount':tmpCnt,'importinlineimage':importinlineimage,'inlineimagehandling':imagehandling,'inline_image_location':inline_image_location,}

        var tmpLoc = document.getElementById('tmpLoc').value;
	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
		    'action'   : 'importByRequest',
		    'postdata' : postdata,
		    'siteurl'  : siteurl,
		},
		success:function(data) {
                        if(parseInt(tmpCnt) == parseInt(tot_no_of_records)){
                                document.getElementById('terminatenow').style.display = "none";
                        } 
        	        if(parseInt(tmpCnt) < parseInt(tot_no_of_records)){
				var terminate_action = document.getElementById('terminateaction').value;
				if(terminate_action == 'continue'){
					currentlimit = parseInt(currentlimit)+parseInt(importlimit);
					document.getElementById('currentlimit').value = currentlimit;
					console.log('impLmt: '+importlimit+'totRecds: '+tot_no_of_records);
					document.getElementById('tmpcount').value = parseInt(tmpCnt)+parseInt(importlimit);
					setTimeout(function(){importRecordsbySettings()},0);
				} else {
					document.getElementById('log').innerHTML += "Import process has been terminated.</br>";
					return false;
				}
	                }else{
				document.getElementById('ajaxloader').style.display="none";
		                document.getElementById('startbutton').style.display="none";
		                document.getElementById('importagain').style.display="";
        	                return false;
	                }
			document.getElementById('log').innerHTML += data+'<br/>';
				
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	});
}

// Terminate import process
function terminateProcess(){
	document.getElementById('terminateaction').value = 'terminate';
}


// Enable/Disable WP-e-Commerce Custom Fields
function enablewpcustomfield(val){
	if(val == 'wpcustomfields'){
		document.getElementById('wpcustomfieldstr').style.display = '';
	}
	else{
		document.getElementById('wpcustomfields').checked = false;
		document.getElementById('wpcustomfieldstr').style.display = 'none';
	}
}

function saveSettings(){ //alert('dd');
//document.getElementById('ShowMsg').style.display = '';
jQuery(document).ready( function() {
        jQuery('#ShowMsg').delay(2000).fadeOut();
      });
}

function Reload(){
window.location.reload();
}

function  check_if_avail(val){
	var proModule = new Array();
	proModule[0] = 'categories';
	proModule[1] = 'customtaxonomy';
	proModule[2] = 'eshop';
	proModule[3] = 'marketpress';
	proModule[4] = 'woocommerce';
	proModule[5] = 'wpcommerce';
	proModule[6] = 'cctm';
	proModule[7] = 'types';
	proModule[8] = 'acf';
	proModule[9] = 'Customerreviews';
	proModule[10] = 'WP-Members';
	proModule[11] = 'yoastseo';
	proModule[12] = 'caticonenable';
	proModule[13] = 'custompostuitype';
	proModule[14] = 'wpcustomfields';
	proModule[15] = 'recommerce';
	proModule[16] = 'automapping';
	proModule[17] = 'utfsupport';
        proModule[18] = 'podspost';
	proModule[19] = 'podscustomfield';
	proModule[20]  = 'typescustomfield';  
	proModule[21]  = 'drop_table';
	proModule[22]  = 'database_optimization';
	proModule[23]  = 'scheduled';
	proModule[24] = 'cctmcustomfields';

	var warning_name = new Array();
	warning_name['categories'] = 'Categories/Tags';
	warning_name['customtaxonomy'] = 'Custom Taxonomy';
	warning_name['eshop'] = 'Eshop';
	warning_name['marketpress'] = 'Market Press Lite';
	warning_name['woocommerce'] = 'WooCommerce';
	warning_name['wpcommerce'] = 'WP e-Commerce';
	warning_name['cctm'] = 'CCTM Custom Post';
	warning_name['types'] = 'Types Custom Post';
	warning_name['acf'] = 'ACF';
	warning_name['Customerreviews'] = 'Customer Reviews';
	warning_name['WP-Members'] = 'WP-Members';
	warning_name['aioseo'] = 'All-in-SEO';
	warning_name['yoastseo'] = 'Yoast SEO';
	warning_name['caticonenable'] = 'Category Icons';
	warning_name['custompostuitype'] = 'Custom Post Type UI';
	warning_name['automapping'] = 'Auto Mapping';
	warning_name['utfsupport'] = 'UTF Support';
	warning_name['podspost'] = 'Pods Custom Post';                 
	warning_name['podscustomfield'] = 'Pods Custom Field';
	warning_name['typescustomfield'] = 'Types Custom Field';
	warning_name['drop_table'] = 'Drop Table';
	warning_name['database_optimization'] = 'Database Optimization';
	warning_name['scheduled'] = 'Scheduled log mails';
	warning_name['cctmcustomfields'] = 'CCTM Custom Fields';

	var result = inArray(val, proModule);
	if(result == true){

		if( val == 'wpcustomfields' ){
			if(val == 'wpcommerce' || val == 'wpcustomfields') {
				document.getElementById('wpcustomfieldstr').style.display = '';
			} else {
				document.getElementById('wpcustomfieldstr').style.display = '';
			}
			//			document.getElementById('wpcustomfieldstr').style.display = 'none';
			document.getElementById('nonerecommerce').checked = true;
		}
		if(val == 'cctm'){
			document.getElementById('cctm').checked = true;
		}
		if(val == 'types'){
			document.getElementById('types').checked = true;
                }
		if(val == 'acf'){
			document.getElementById('acf').checked = true;
		}
		if(val == 'cctmcustomfields'){
			document.getElementById('cctmcustomfields').checked = true;
		}
		if(val == 'podscustomfield'){
                        document.getElementById('podscustomfield').checked = true;
		}
		if(val == 'yoastseo'){
			document.getElementById('yoastseo').checked = true;
		}
		if(val == 'caticonenable'){
			document.getElementById('caticondisable').checked = true;
		}
		if(val == 'drop_table'){
			document.getElementById('drop_table').checked = true;
		}
		if(val == 'scheduled'){
			document.getElementById('scheduled').checked = true;
		}
		if(val == 'marketpress'){
			document.getElementById('marketpress').checked = true;
		}
		if(val == 'woocommerce'){
                        document.getElementById('woocommerce').checked = true;
		}
		if(val == 'wpcommerce'){
                        document.getElementById('wpcommerce').checked = true;
		}
		document.getElementById(val).checked = false;
		document.getElementById('ShowMsg').style.display = "";
		document.getElementById('warning-msg').innerHTML = warning_name[val]+' feature is available only for PRO!.';
		jQuery('#ShowMsg').delay(7000).fadeOut();
	}
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(typeof haystack[i] == 'object') {
            if(arrayCompare(haystack[i], needle)) {
		 return true;
	    }
        } else {
            if(haystack[i] == needle) {
		 return true;
	    }
        }
    }
    return false;
}

function import_again(){
	var get_current_url = document.getElementById('current_url').value;
	window.location.assign(get_current_url);
}
function sendemail2smackers(){
//	var useremail = document.getElementById('usermailid').value;
	var message_content = document.getElementById('message').value;
	var firstname = document.getElementById('firstname').value;
	var lastname = document.getElementById('lastname').value;
	if(message_content != '' && firstname != '' && lastname != '')
		return true;
	else
		document.getElementById('showMsg').style.display = '';
		document.getElementById('showMsg').innerHTML = '<p id="warning-msg" class="alert alert-warning">Fill all mandatory fields.</p>';
		jQuery("#showMsg").fadeOut(10000);
		return false;
}


function check_allnumeric(inputtxt)  
{  
	var numbers = /^[0-9]+$/;  
	if(inputtxt.match(numbers))  
	{  
		return true;
	}  
	else  
	{  
		if(inputtxt == '')
			alert('Fill all mandatory fields.');
		else
			alert('Please enter numeric characters only');  
		return false;  
	}  
}

function gotoback() {
	var currentURL = document.getElementById('current_url').value;
	var set_assigned_step = currentURL.replace("uploadfile","mapping_settings");
//	window.location.assign(set_assigned_step);
}
function export_module(){
        var get_selected_module = document.getElementsByName('export');
//      alert(get_selected_module);
        for (var i = 0, length = get_selected_module.length; i < length; i++) {
                if (get_selected_module[i].checked) {
                        // do whatever you want with the checked radio
                        //alert(get_selected_module[i].value);
                        // only one radio can be logically checked, don't check the rest
                        //break;
                        return true;
                }
        }
        showMapMessages('error', 'Please choose one module to export the records!');
        return false;
}
function export_check(value) {
	if( value == 'woocommerce' || value == 'wpcommerce' || value == 'marketpress' || value == 'users' || value == 'category' || value == 'tags' || value == 'customtaxonomy' || value == 'customerreviews') { 
		document.getElementById(value).checked = false;
		document.getElementById('ShowMsg').style.display = "";
		value = value.toUpperCase();
		document.getElementById('warning-msg').innerHTML = value+' Feature is available only for PRO!.';
		jQuery('#ShowMsg').delay(7000).fadeOut();
	}
}
function choose_import_method(id) {
        if(id == 'uploadfilefromcomputer') {
                document.getElementById('boxmethod1').style.border = "1px solid #ccc";
                document.getElementById('method1').style.display = '';
                document.getElementById('method1').style.height = '40px';
}
}
function choose_import_mode(id) {
        if(id == 'importNow') {
               document.getElementById('importrightaway').style.display='';
               document.getElementById('reportLog').style.display='';
               document.getElementById('schedule').style.display='none';
         }
        if(id == 'scheduleNow') {
               document.getElementById('schedule').style.display='';
               document.getElementById('importrightaway').style.display='none';
               document.getElementById('reportLog').style.display='none';
         }
}
function selectModules(id) {
  var param = id ;
 if(param == 'checkallModules') {
    document.getElementById('post').checked = true;
    document.getElementById('page').checked = true;
    document.getElementById('users').checked = true;
    document.getElementById('comments').checked = true;
    document.getElementById('custompost').checked = true;

    jQuery('#postlabel').removeClass("disablesetting");
    jQuery('#postlabel').addClass("enablesetting");
    jQuery('#nopostlabel').addClass("disablesetting");
    jQuery('#nopostlabel').removeClass("enablesetting");
    jQuery('#pagelabel').removeClass("disablesetting");
    jQuery('#pagelabel').addClass("enablesetting");
    jQuery('#nopagelabel').addClass("disablesetting");
    jQuery('#nopagelabel').removeClass("enablesetting");
    jQuery('#userlabel').removeClass("disablesetting");
    jQuery('#userlabel').addClass("enablesetting");
    jQuery('#nouserlabel').addClass("disablesetting");
    jQuery('#nouserlabel').removeClass("enablesetting");
    jQuery('#commentslabel').removeClass("disablesetting");
    jQuery('#commentslabel').addClass("enablesetting");
    jQuery('#nocommentslabel').addClass("disablesetting");
    jQuery('#nocommentslabel').removeClass("enablesetting");
    jQuery('#cplabel').removeClass("disablesetting");
    jQuery('#cplabel').addClass("enablesetting");
    jQuery('#nocplabel').addClass("disablesetting");
    jQuery('#nocplabel').removeClass("enablesetting");

 }
 else if(param == 'uncheckallModules') {

    document.getElementById('post').checked = false;
    document.getElementById('page').checked = false;
    document.getElementById('users').checked = false;
    document.getElementById('comments').checked = false;
    document.getElementById('custompost').checked = false;

    jQuery('#nopostlabel').removeClass("disablesetting");
    jQuery('#nopostlabel').addClass("enablesetting");
    jQuery('#postlabel').addClass("disablesetting");
    jQuery('#postlabel').removeClass("enablesetting");
    jQuery('#nopagelabel').removeClass("disablesetting");
    jQuery('#nopagelabel').addClass("enablesetting");
    jQuery('#pagelabel').addClass("disablesetting");
    jQuery('#pagelabel').removeClass("enablesetting");
    jQuery('#nouserlabel').removeClass("disablesetting");
    jQuery('#nouserlabel').addClass("enablesetting");
    jQuery('#userlabel').addClass("disablesetting");
    jQuery('#userlabel').removeClass("enablesetting");
    jQuery('#nocommentslabel').removeClass("disablesetting");
    jQuery('#nocommentslabel').addClass("enablesetting");
    jQuery('#commentslabel').addClass("disablesetting");
    jQuery('#commentslabel').removeClass("enablesetting");
    jQuery('#nocplabel').removeClass("disablesetting");
    jQuery('#nocplabel').addClass("enablesetting");
    jQuery('#cplabel').addClass("disablesetting");
    jQuery('#cplabel').removeClass("enablesetting");
    
  }
}

function addexportfilter(id) {
	if(document.getElementById(id).checked == true) {
		if(id == 'getdataforspecificperiod') {
			document.getElementById('specificperiodexport').style.display = '';
			document.getElementById('periodstartfrom').style.display = '';
			document.getElementById('postdatefrom').style.display = '';
			document.getElementById('periodendto').style.display = '';
			document.getElementById('postdateto').style.display = '';
		}
		else if(id == 'getdatawithspecificstatus') {
			document.getElementById('specificstatusexport').style.display = '';
			document.getElementById('status').style.display = '';
			document.getElementById('postwithstatus').style.display = '';
		}
		else if(id == 'getdatabyspecificauthors') {
			document.getElementById('specificauthorexport').style.display = '';
			document.getElementById('authors').style.display = '';
			document.getElementById('postauthor').style.display = '';
		}
	} else if (document.getElementById(id).checked == false) {
                if(id == 'getdataforspecificperiod') {
			document.getElementById('specificperiodexport').style.display = 'none';
			document.getElementById('periodstartfrom').style.display = 'none';
			document.getElementById('postdatefrom').style.display = 'none';
			document.getElementById('periodendto').style.display = 'none';
			document.getElementById('postdateto').style.display = 'none';
                }
                else if(id == 'getdatawithspecificstatus') {
			document.getElementById('specificstatusexport').style.display = 'none';
			document.getElementById('status').style.display = 'none';
			document.getElementById('postwithstatus').style.display = 'none';
                }
                else if(id == 'getdatabyspecificauthors') {
			document.getElementById('specificauthorexport').style.display = 'none';
                        document.getElementById('authors').style.display = 'none';
                        document.getElementById('postauthor').style.display = 'none';
                }
	}
}

//Settings js code
function showsettingsoption(id) {
        for(i=1;i<=9;i++) {
                if(parseInt(id) == parseInt(i)) {
                        if(parseInt(i) == 8) {
                                document.getElementById('sidebar').style.height = '1149px';
                                document.getElementById('contentbar').style.height = '1149px';
                                document.getElementById('settingsholder').style.height = '1150px';
                        } else if(parseInt(i) == 9) {
                                document.getElementById('sidebar').style.height = '665px';
                                document.getElementById('contentbar').style.height = '665px';
                                document.getElementById('settingsholder').style.height = '666px';
                       } else if(parseInt(i) == 4) {
                                document.getElementById('sidebar').style.height = '420px';
                                document.getElementById('contentbar').style.height = '420px';
                                document.getElementById('settingsholder').style.height = '421px';
                        } else if(parseInt(i) == 3) {
                                document.getElementById('sidebar').style.height = '586px';
                                document.getElementById('contentbar').style.height = '586px';
                                document.getElementById('settingsholder').style.height = '587px';
                        } else if(parseInt(i) == 2) {
                                document.getElementById('sidebar').style.height = '522px';
                                document.getElementById('contentbar').style.height = '522px';
                                document.getElementById('settingsholder').style.height = '523px';
                        } else if(parseInt(i) == 1) {
                                document.getElementById('sidebar').style.height = '965px';
                                document.getElementById('contentbar').style.height = '965px';
                                document.getElementById('settingsholder').style.height = '966px';
                        } else if(parseInt(i) == 6) {
                                document.getElementById('sidebar').style.height = '565px';
                                document.getElementById('contentbar').style.height = '565px';
                                document.getElementById('settingsholder').style.height = '566px';
                        } else {
                                document.getElementById('sidebar').style.height = 'auto';
                                document.getElementById('contentbar').style.height = 'auto';
                                document.getElementById('settingsholder').style.height = 'auto';
                        }

                        jQuery('#'+id).removeClass( "bg-sidebar" );
                        jQuery('#'+id).addClass( "selected" );
                        document.getElementById('section'+id).style.display="";
                    //  document.getElementById('arrow'+id).style.display="";
			document.getElementById('activemenu').innerHTML = document.getElementById('settingmenu'+id).innerHTML ;
                } else {
                        jQuery('#'+i).removeClass( "selected" );
                        jQuery('#'+i).addClass( "bg-sidebar" );
                        document.getElementById('section'+i).style.display="none";
                    //  document.getElementById('arrow'+i).style.display="none";
                }
        }
        document.getElementById('section'+id).style.display="";
}


//seo setting enable and disable
function seosetting(id) {
       if(id == 'nonerseooption') {
               jQuery('#seosetting1').removeClass("disablesetting");
               jQuery('#seosetting1').addClass("enablesetting");
               document.getElementById("seosetting1text").innerHTML="Enabled";
               document.getElementById("seosetting2text").innerHTML="Disabled";
               document.getElementById("seosetting3text").innerHTML="Disabled";
               jQuery('#seosetting2').addClass("disablesetting");
               jQuery('#seosetting2').removeClass("enablesetting");
               jQuery('#seosetting3').addClass("disablesetting");
               jQuery('#seosetting3').removeClass("enablesetting");
       }
        else if(id == 'aioseo') {
              jQuery('#seosetting2').removeClass("disablesetting");
               jQuery('#seosetting2').addClass("enablesetting");
               document.getElementById('seosetting2text').innerHTML="Enabled";
               document.getElementById("seosetting1text").innerHTML="Disabled";
               document.getElementById("seosetting3text").innerHTML="Disabled";
               jQuery('#seosetting1').addClass("disablesetting");
               jQuery('#seosetting1').removeClass("enablesetting");
               jQuery('#seosetting3').addClass("disablesetting");
               jQuery('#seosetting3').removeClass("enablesetting");
       }
       else if(id == 'yoastseo') {
               jQuery('#seosetting3').removeClass("disablesetting");
	       jQuery('#seosetting3').addClass("enablesetting");
               document.getElementById('seosetting3text').innerHTML="Enabled";
               document.getElementById("seosetting1text").innerHTML="Disabled";
               document.getElementById("seosetting2text").innerHTML="Disabled";
               jQuery('#seosetting1').addClass("disablesetting");
               jQuery('#seosetting1').removeClass("enablesetting");
               jQuery('#seosetting2').addClass("disablesetting");
               jQuery('#seosetting2').removeClass("enablesetting");
       }
}

//Custom post setting
function custompostsetting(id) {
       if(id == 'nonercustompost') {
               jQuery('#custompostsetting1').removeClass("disablesetting");
               jQuery('#custompostsetting1').addClass("enablesetting");
               document.getElementById("custompost1text").innerHTML="Enabled";
               document.getElementById("custompost2text").innerHTML="Disabled";
               jQuery('#custompostsetting2').addClass("disablesetting");
               jQuery('#custompostsetting2').removeClass("enablesetting");
       }
       else if(id == 'custompostuitype') {
               jQuery('#custompostsetting2').removeClass("disablesetting");
               jQuery('#custompostsetting2').addClass("enablesetting");
               document.getElementById("custompost2text").innerHTML="Enabled";
               document.getElementById("custompost1text").innerHTML="Disabled";
               jQuery('#custompostsetting1').addClass("disablesetting");
               jQuery('#custompostsetting1').removeClass("enablesetting");
       }
/*     else if(id == 'wptypespost') {
               jQuery('#custompostsetting3').removeClass("disablesetting");
               jQuery('#custompostsetting3').addClass("enablesetting");
               document.getElementById("custompost3text").innerHTML="Enabled";
               document.getElementById("custompost2text").innerHTML="Disabled";
               document.getElementById("custompost1text").innerHTML="Disabled";
               document.getElementById("custompost4text").innerHTML="Disabled";
               document.getElementById("custompost5text").innerHTML="Disabled";
               jQuery('#custompostsetting2').addClass("disablesetting");
               jQuery('#custompostsetting2').removeClass("enablesetting");
               jQuery('#custompostsetting1').addClass("disablesetting");
               jQuery('#custompostsetting1').removeClass("enablesetting");
               jQuery('#custompostsetting4').addClass("disablesetting");
               jQuery('#custompostsetting4').removeClass("enablesetting");
               jQuery('#custompostsetting5').addClass("disablesetting");
               jQuery('#custompostsetting5').removeClass("enablesetting");
       }
       else if(id == 'cctm') {
               jQuery('#custompostsetting4').removeClass("disablesetting");
               jQuery('#custompostsetting4').addClass("enablesetting");
               document.getElementById("custompost4text").innerHTML="Enabled";
               document.getElementById("custompost2text").innerHTML="Disabled";
               document.getElementById("custompost3text").innerHTML="Disabled";
               document.getElementById("custompost1text").innerHTML="Disabled";
               document.getElementById("custompost5text").innerHTML="Disabled";
               jQuery('#custompostsetting2').addClass("disablesetting");
               jQuery('#custompostsetting2').removeClass("enablesetting");
               jQuery('#custompostsetting3').addClass("disablesetting");
               jQuery('#custompostsetting3').removeClass("enablesetting");
	       jQuery('#custompostsetting1').addClass("disablesetting");
               jQuery('#custompostsetting1').removeClass("enablesetting");
               jQuery('#custompostsetting5').addClass("disablesetting");
              jQuery('#custompostsetting5').removeClass("enablesetting");
       }
       else if(id == 'podspost') {
               jQuery('#custompostsetting5').removeClass("disablesetting");
               jQuery('#custompostsetting5').addClass("enablesetting");
               document.getElementById("custompost5text").innerHTML="Enabled";
               document.getElementById("custompost1text").innerHTML="Disabled";
               document.getElementById("custompost2text").innerHTML="Disabled";
               document.getElementById("custompost3text").innerHTML="Disabled";
               document.getElementById("custompost4text").innerHTML="Disabled";
               jQuery('#custompostsetting1').addClass("disablesetting");
               jQuery('#custompostsetting1').removeClass("enablesetting");
               jQuery('#custompostsetting2').addClass("disablesetting");
               jQuery('#custompostsetting2').removeClass("enablesetting");
               jQuery('#custompostsetting3').addClass("disablesetting");
               jQuery('#custompostsetting3').removeClass("enablesetting");
               jQuery('#custompostsetting4').addClass("disablesetting");
               jQuery('#custompostsetting4').removeClass("enablesetting");
      }*/
}
//Security and performance
function authorimportsetting(id) {
         if(id == 'enableimport') {
                jQuery('#allowimport').removeClass("disablesetting");
                jQuery('#allowimport').addClass("enablesetting");
                jQuery('#donallowimport').addClass("disablesetting");
                jQuery('#donallowimport').removeClass("enablesetting");
         }
        else {
                jQuery('#donallowimport').removeClass("disablesetting");
                jQuery('#donallowimport').addClass("enablesetting");
                jQuery('#allowimport').addClass("disablesetting");
                jQuery('#allowimport').removeClass("enablesetting");
        }
}
//General Settings
function postsetting(id) {
         if(id == 'post') {
                jQuery('#postlabel').removeClass("disablesetting");
                jQuery('#postlabel').addClass("enablesetting");
                jQuery('#nopostlabel').addClass("disablesetting");
                jQuery('#nopostlabel').removeClass("enablesetting");
         }
        else {
                jQuery('#nopostlabel').removeClass("disablesetting");
                jQuery('#nopostlabel').addClass("enablesetting");
                jQuery('#postlabel').addClass("disablesetting");
                jQuery('#postlabel').removeClass("enablesetting");
        }
}

function pagesetting(id) {
         if(id == 'page') {
                jQuery('#pagelabel').removeClass("disablesetting");
                jQuery('#pagelabel').addClass("enablesetting");
                jQuery('#nopagelabel').addClass("disablesetting");
                jQuery('#nopagelabel').removeClass("enablesetting");
         }
        else {
                jQuery('#nopagelabel').removeClass("disablesetting");
                jQuery('#nopagelabel').addClass("enablesetting");
                jQuery('#pagelabel').addClass("disablesetting");
                jQuery('#pagelabel').removeClass("enablesetting");
        }
}

function usersetting(id) {
         if(id == 'users') {
                jQuery('#userlabel').removeClass("disablesetting");
                jQuery('#userlabel').addClass("enablesetting");
                jQuery('#nouserlabel').addClass("disablesetting");
                jQuery('#nouserlabel').removeClass("enablesetting");
         }
	 else {
                jQuery('#nouserlabel').removeClass("disablesetting");
                jQuery('#nouserlabel').addClass("enablesetting");
                jQuery('#userlabel').addClass("disablesetting");
                jQuery('#userlabel').removeClass("enablesetting");
        }
}

function commentsetting(id) {
         if(id == 'comments') {
                jQuery('#commentslabel').removeClass("disablesetting");
                jQuery('#commentslabel').addClass("enablesetting");
                jQuery('#nocommentslabel').addClass("disablesetting");
                jQuery('#nocommentslabel').removeClass("enablesetting");
         }
        else {
                jQuery('#nocommentslabel').removeClass("disablesetting");
                jQuery('#nocommentslabel').addClass("enablesetting");
                jQuery('#commentslabel').addClass("disablesetting");
                jQuery('#commentslabel').removeClass("enablesetting");
        }
}

function cpsetting(id) {
         if(id == 'custompost') {
                jQuery('#cplabel').removeClass("disablesetting");
                jQuery('#cplabel').addClass("enablesetting");
                jQuery('#nocplabel').addClass("disablesetting");
                jQuery('#nocplabel').removeClass("enablesetting");
         }
        else {
                jQuery('#nocplabel').removeClass("disablesetting");
                jQuery('#nocplabel').addClass("enablesetting");
                jQuery('#cplabel').addClass("disablesetting");
                jQuery('#cplabel').removeClass("enablesetting");
        }
}
function custaxsetting(id) {
         if(id == 'customtaxonomy') {
                jQuery('#custaxlabel').removeClass("disablesetting");
                jQuery('#custaxlabel').addClass("enablesetting");
                jQuery('#nocustaxlabel').addClass("disablesetting");
                jQuery('#nocustaxlabel').removeClass("enablesetting");
         }
        else {
                jQuery('#nocustaxlabel').removeClass("disablesetting");
                jQuery('#nocustaxlabel').addClass("enablesetting");
                jQuery('#custaxlabel').addClass("disablesetting");
                jQuery('#custaxlabel').removeClass("enablesetting");
        }
}

function catsetting(id) {
         if(id == 'categories') {
                jQuery('#catlabel').removeClass("disablesetting");
                jQuery('#catlabel').addClass("enablesetting");
                jQuery('#nocatlabel').addClass("disablesetting");
                jQuery('#nocatlabel').removeClass("enablesetting");
         }
        else {
                jQuery('#nocatlabel').removeClass("disablesetting");
                jQuery('#nocatlabel').addClass("enablesetting");
                jQuery('#catlabel').addClass("disablesetting");
                jQuery('#catlabel').removeClass("enablesetting");
        }
}

function cusrevsetting(id) {
         if(id == 'rcustomerreviews') {
                jQuery('#custrevlabel').removeClass("disablesetting");
                jQuery('#custrevlabel').addClass("enablesetting");
                jQuery('#nocustrevlabel').addClass("disablesetting");
                jQuery('#nocustrevlabel').removeClass("enablesetting");
         }
        else {
		jQuery('#nocustrevlabel').removeClass("disablesetting");
                jQuery('#nocustrevlabel').addClass("enablesetting");
                jQuery('#custrevlabel').addClass("disablesetting");
                jQuery('#custrevlabel').removeClass("enablesetting");
        }
}






//Custom field
function  resetOption(id) {
        if (id == 'resetopt') {
        document.getElementById('runcheckmember').checked = true;
        document.getElementById('wpcustomfields').checked = false;
        document.getElementById('cctmcustomfield').checked = false;
        document.getElementById('acfcustomfield').checked = false;
        document.getElementById('typescustomfield').checked = false;
        document.getElementById('podscustomfield').checked = false;

        document.getElementById('customfield1text').innerHTML = "Disabled";
        document.getElementById('customfield2text').innerHTML = "Disabled";
        document.getElementById('customfield3text').innerHTML = "Disabled";
        document.getElementById('customfield4text').innerHTML = "Disabled";

        jQuery('#wpuseruncheck').removeClass("disablesetting");
        jQuery('#wpuseruncheck').addClass("enablesetting");
        jQuery('#wpusercheck').addClass("disablesetting");
        jQuery('#wpusercheck').removeClass("enablesetting");
        jQuery('#customfieldsetting1').addClass("disablesetting");
        jQuery('#customfieldsetting1').removeClass("enablesetting");
        jQuery('#customfieldsetting2').addClass("disablesetting");
        jQuery('#customfieldsetting2').removeClass("enablesetting");
        jQuery('#customfieldsetting3').addClass("disablesetting");
        jQuery('#customfieldsetting3').removeClass("enablesetting");
        jQuery('#customfieldsetting4').addClass("disablesetting");
 	jQuery('#customfieldsetting4').removeClass("enablesetting");
        }
}
function wpmembersetting(id) {
        if(id == 'rwpmembers') {
                jQuery('#wpusercheck').removeClass("disablesetting");
                jQuery('#wpusercheck').addClass("enablesetting");
                jQuery('#wpuseruncheck').addClass("disablesetting");
                jQuery('#wpuseruncheck').removeClass("enablesetting");
         }
        else {
                jQuery('#wpuseruncheck').removeClass("disablesetting");
                jQuery('#wpuseruncheck').addClass("enablesetting");
                jQuery('#wpusercheck').addClass("disablesetting");
               jQuery('#wpusercheck').removeClass("enablesetting");
        }
}
function customfieldsetting(id) {
         if(id == 'acfcustomfield') {
               jQuery('#customfieldsetting1').removeClass("disablesetting");
               jQuery('#customfieldsetting1').addClass("enablesetting");
               document.getElementById("customfield1text").innerHTML="Enabled";
               document.getElementById("customfield2text").innerHTML="Disabled";
               document.getElementById("customfield3text").innerHTML="Disabled";
               document.getElementById("customfield4text").innerHTML="Disabled";
               jQuery('#customfieldsetting2').addClass("disablesetting");
               jQuery('#customfieldsetting2').removeClass("enablesetting");
               jQuery('#customfieldsetting3').addClass("disablesetting");
               jQuery('#customfieldsetting3').removeClass("enablesetting");
               jQuery('#customfieldsetting4').addClass("disablesetting");
               jQuery('#customfieldsetting4').removeClass("enablesetting");
       }
       else if(id == 'cctmcustomfield') {
               jQuery('#customfieldsetting2').removeClass("disablesetting");
               jQuery('#customfieldsetting2').addClass("enablesetting");
               document.getElementById('customfield2text').innerHTML="Enabled";
               document.getElementById("customfield1text").innerHTML="Disabled";
               document.getElementById("customfield3text").innerHTML="Disabled";
	       document.getElementById("customfield4text").innerHTML="Disabled";
               jQuery('#customfieldsetting1').addClass("disablesetting");
               jQuery('#customfieldsetting1').removeClass("enablesetting");
              jQuery('#customfieldsetting3').addClass("disablesetting");
               jQuery('#customfieldsetting3').removeClass("enablesetting");
               jQuery('#customfieldsetting4').addClass("disablesetting");
               jQuery('#customfieldsetting4').removeClass("enablesetting");
       }
       else if(id == 'typescustomfield') {
               jQuery('#customfieldsetting3').removeClass("disablesetting");
               jQuery('#customfieldsetting3').addClass("enablesetting");
               document.getElementById('customfield3text').innerHTML="Enabled";
               document.getElementById("customfield1text").innerHTML="Disabled";
               document.getElementById("customfield2text").innerHTML="Disabled";
               document.getElementById("customfield4text").innerHTML="Disabled";
               jQuery('#customfieldsetting1').addClass("disablesetting");
               jQuery('#customfieldsetting1').removeClass("enablesetting");
               jQuery('#customfieldsetting2').addClass("disablesetting");
               jQuery('#customfieldsetting2').removeClass("enablesetting");
               jQuery('#customfieldsetting4').addClass("disablesetting");
               jQuery('#customfieldsetting4').removeClass("enablesetting");
       }
       else if(id == 'podscustomfield') {
               jQuery('#customfieldsetting4').removeClass("disablesetting");
               jQuery('#customfieldsetting4').addClass("enablesetting");
               document.getElementById('customfield4text').innerHTML="Enabled";
               document.getElementById("customfield1text").innerHTML="Disabled";
               document.getElementById("customfield2text").innerHTML="Disabled";
               document.getElementById("customfield3text").innerHTML="Disabled";
               jQuery('#customfieldsetting1').addClass("disablesetting");
               jQuery('#customfieldsetting1').removeClass("enablesetting");
               jQuery('#customfieldsetting2').addClass("disablesetting");
               jQuery('#customfieldsetting2').removeClass("enablesetting");
               jQuery('#customfieldsetting3').addClass("disablesetting");
               jQuery('#customfieldsetting3').removeClass("enablesetting");
       }
}
//Additional Features
function schedulesetting(id) {
         if(id == 'scheduled') {
                jQuery('#schedulecheck').removeClass("disablesetting");
                jQuery('#schedulecheck').addClass("enablesetting");
                jQuery('#scheduleuncheck').addClass("disablesetting");
                jQuery('#scheduleuncheck').removeClass("enablesetting");
         }
        else {
                jQuery('#scheduleuncheck').removeClass("disablesetting");
                jQuery('#scheduleuncheck').addClass("enablesetting");
                jQuery('#schedulecheck').addClass("disablesetting");
                jQuery('#schedulecheck').removeClass("enablesetting");
        }
}

function categoryiconsetting(id) {
         if(id == 'caticonenable') {
                jQuery('#catenable').removeClass("disablesetting");
                jQuery('#catenable').addClass("enablesetting");
                jQuery('#catdisable').addClass("disablesetting");
                jQuery('#catdisable').removeClass("enablesetting");
         }
        else {
                jQuery('#catdisable').removeClass("disablesetting");
                jQuery('#catdisable').addClass("enablesetting");
                jQuery('#catenable').addClass("disablesetting");
                jQuery('#catenable').removeClass("enablesetting");
        }
}


function dropsetting(id) {
         if(id == 'drop_table') {
                jQuery('#dropon').removeClass("disablesetting");
                jQuery('#dropon').addClass("enablesetting");
                jQuery('#dropoff').addClass("disablesetting");
                jQuery('#dropoff').removeClass("enablesetting");
	}
        else {
                jQuery('#dropoff').removeClass("disablesetting");
                jQuery('#dropoff').addClass("enablesetting");
                jQuery('#dropon').addClass("disablesetting");
                jQuery('#dropon').removeClass("enablesetting");
        }
}

// Enable / Disable the debug mode 
function debugmode_check (id) {
	if(id == 'enabled') {
                jQuery('#debugmode_enable').removeClass("disablesetting");
                jQuery('#debugmode_enable').addClass("enablesetting");
                jQuery('#debugmode_disable').removeClass("enablesetting");
                jQuery('#debugmode_disable').addClass("disablesetting");
	} else {
                jQuery('#debugmode_disable').removeClass("disablesetting");
                jQuery('#debugmode_disable').addClass("enablesetting");
                jQuery('#debugmode_enable').removeClass("enablesetting");
		jQuery('#debugmode_enable').addClass("disablesetting");
	} 
}

//Ecommerce setting
function ecommercesetting(id) {
       if(id == 'nonerecommerce') {
                jQuery('#ecommercesetting1').removeClass("disablesetting");
                jQuery('#ecommercesetting1').addClass("enablesetting");
                document.getElementById("ecommerce1text").innerHTML="Enabled";
                document.getElementById("ecommerce2text").innerHTML="Disabled";
                document.getElementById("ecommerce3text").innerHTML="Disabled";
                document.getElementById("ecommerce4text").innerHTML="Disabled";
                document.getElementById("ecommerce5text").innerHTML="Disabled";

               jQuery('#ecommercesetting2').addClass("disablesetting");
               jQuery('#ecommercesetting2').removeClass("enablesetting");
               jQuery('#ecommercesetting3').addClass("disablesetting");
               jQuery('#ecommercesetting3').removeClass("enablesetting");
               jQuery('#ecommercesetting4').addClass("disablesetting");
               jQuery('#ecommercesetting4').removeClass("enablesetting");
               jQuery('#ecommercesetting5').addClass("disablesetting");
               jQuery('#ecommercesetting5').removeClass("enablesetting");
       }
       else if(id == 'eshop') {
               jQuery('#ecommercesetting2').removeClass("disablesetting");
               jQuery('#ecommercesetting2').addClass("enablesetting");
               document.getElementById("ecommerce2text").innerHTML="Enabled";
               document.getElementById("ecommerce1text").innerHTML="Disabled";
               document.getElementById("ecommerce3text").innerHTML="Disabled";
               document.getElementById("ecommerce4text").innerHTML="Disabled";
               document.getElementById("ecommerce5text").innerHTML="Disabled";
	       jQuery('#ecommercesetting1').addClass("disablesetting");
               jQuery('#ecommercesetting1').removeClass("enablesetting");
               jQuery('#ecommercesetting3').addClass("disablesetting");
               jQuery('#ecommercesetting3').removeClass("enablesetting");
               jQuery('#ecommercesetting4').addClass("disablesetting");
               jQuery('#ecommercesetting4').removeClass("enablesetting");
               jQuery('#ecommercesetting5').addClass("disablesetting");
               jQuery('#ecommercesetting5').removeClass("enablesetting");
       }
       else if(id == 'marketpress') {
               jQuery('#ecommercesetting3').removeClass("disablesetting");
               jQuery('#ecommercesetting3').addClass("enablesetting");
               document.getElementById("ecommerce3text").innerHTML="Enabled";
               document.getElementById("ecommerce2text").innerHTML="Disabled";
               document.getElementById("ecommerce1text").innerHTML="Disabled";
               document.getElementById("ecommerce4text").innerHTML="Disabled";
               document.getElementById("ecommerce5text").innerHTML="Disabled";
               jQuery('#ecommercesetting1').addClass("disablesetting");
               jQuery('#ecommercesetting1').removeClass("enablesetting");
               jQuery('#ecommercesetting2').addClass("disablesetting");
               jQuery('#ecommercesetting2').removeClass("enablesetting");
               jQuery('#ecommercesetting4').addClass("disablesetting");
               jQuery('#ecommercesetting4').removeClass("enablesetting");
               jQuery('#ecommercesetting5').addClass("disablesetting");
               jQuery('#ecommercesetting5').removeClass("enablesetting");
      }
        else if(id == 'woocommerce') {
              jQuery('#ecommercesetting4').removeClass("disablesetting");
               jQuery('#ecommercesetting4').addClass("enablesetting");
               document.getElementById("ecommerce4text").innerHTML="Enabled";
               document.getElementById("ecommerce2text").innerHTML="Disabled";
               document.getElementById("ecommerce3text").innerHTML="Disabled";
               document.getElementById("ecommerce1text").innerHTML="Disabled";
               document.getElementById("ecommerce5text").innerHTML="Disabled";
               jQuery('#ecommercesetting1').addClass("disablesetting");
               jQuery('#ecommercesetting1').removeClass("enablesetting");
               jQuery('#ecommercesetting2').addClass("disablesetting");
               jQuery('#ecommercesetting2').removeClass("enablesetting");	                                                                              jQuery('#ecommercesetting3').addClass("disablesetting");
               jQuery('#ecommercesetting3').removeClass("enablesetting");
               jQuery('#ecommercesetting5').addClass("disablesetting");
               jQuery('#ecommercesetting5').removeClass("enablesetting");
       }
        else if(id == 'wpcommerce') {
               jQuery('#ecommercesetting5').removeClass("disablesetting");
               jQuery('#ecommercesetting5').addClass("enablesetting");
               document.getElementById("ecommerce5text").innerHTML="Enabled";
               document.getElementById("ecommerce2text").innerHTML="Disabled";
               document.getElementById("ecommerce3text").innerHTML="Disabled";
               document.getElementById("ecommerce4text").innerHTML="Disabled";
              document.getElementById("ecommerce1text").innerHTML="Disabled";
               jQuery('#ecommercesetting1').addClass("disablesetting");
               jQuery('#ecommercesetting1').removeClass("enablesetting");
               jQuery('#ecommercesetting2').addClass("disablesetting");
               jQuery('#ecommercesetting2').removeClass("enablesetting");
               jQuery('#ecommercesetting4').addClass("disablesetting");
               jQuery('#ecommercesetting4').removeClass("enablesetting");
               jQuery('#ecommercesetting3').addClass("disablesetting");
               jQuery('#ecommercesetting3').removeClass("enablesetting");
       }
         else {
               jQuery('#ecommercesetting5').removeClass("enablesetting");
               jQuery('#ecommercesetting5').addClass("disablesetting");
               document.getElementById("ecommerce5text").innerHTML="Disabled";
               document.getElementById("ecommerce2text").innerHTML="Disabled";
               document.getElementById("ecommerce3text").innerHTML="Disabled";
               document.getElementById("ecommerce4text").innerHTML="Disabled";
               document.getElementById("ecommerce1text").innerHTML="Disabled";
               jQuery('#ecommercesetting1').addClass("disablesetting");
               jQuery('#ecommercesetting1').removeClass("enablesetting");
               jQuery('#ecommercesetting2').addClass("disablesetting");
               jQuery('#ecommercesetting2').removeClass("enablesetting");
               jQuery('#ecommercesetting4').addClass("disablesetting");
               jQuery('#ecommercesetting4').removeClass("enablesetting");
               jQuery('#ecommercesetting3').addClass("disablesetting");
               jQuery('#ecommercesetting3').removeClass("enablesetting");
	 }

}

//End of settings js

			

