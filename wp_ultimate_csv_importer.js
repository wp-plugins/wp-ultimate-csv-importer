/*********************************************************************************
 * WordPress ultimate CSV Importer is a Tool for importing CSV for the Wordpress
 * plugin developed by Smackcoder. Copyright (C) 2013 Smackcoders.
 *
 * WordPress ultimate CSV Importer is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License version 3 as
 * published by the Free Software Foundation with the addition of the following
 * permission added to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE
 * COVERED WORK IN WHICH THE COPYRIGHT IS OWNED BY WordPress ultimate CSV Importer,
 * WordPress ultimate CSV Importer DISCLAIMS THE WARRANTY OF NON INFRINGEMENT OF THIRD
 * PARTY RIGHTS.
 *
 * WordPress ultimate CSV Importer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for
 * more details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact Smackcoders at email address info@smackcoders.com.
 *
 * The interactive user interfaces in original and modified versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the WordPress ultimate
 * CSV Importer copyright notice. If the display of the logo is not reasonably feasible
 * for technical reasons, the Appropriate Legal Notices must display the words
 * "Copyright Smackcoders. 2013. All rights reserved".
 ********************************************************************************/

function importAllPostStatus(selectedId, headerCount) {
    var select;
    var options;
    if (selectedId != 0) {
        for (var u = 0; u < headerCount; u++) {
            select = document.getElementById('mapping' + u);
            options = select.options;
            for (var o = 0; o < options.length; o++) {
                if (options[o].value == 'post_status') {
                    select.remove(o);
                }
            }
        }
    }
    else {
        for (var v = 0; v < headerCount; v++) {
            select = document.getElementById('mapping' + v);
            options = select.options;
            poststatus = 0;
            for (var o = 0; o < options.length; o++) {
                if (options[o].value == 'post_status')
                    poststatus = 1;
            }
            if (poststatus == 0) {
                var option = document.createElement("option");
                option.text = "post_status";
                select.add(option);
            }

        }
    }//exits post_status show hide
    if (selectedId == 3) {
        document.getElementById('postsPassword').style.display = "";
        document.getElementById('passwordlabel').style.display = "";
        document.getElementById('passwordhint').style.display = "";
    }
    else {
        document.getElementById('postsPassword').style.display = "none";
        document.getElementById('passwordlabel').style.display = "none";
        document.getElementById('passwordhint').style.display = "none";
    }
}

function loadSelectedPost(selectedCateg, contenturl) {
    jQuery.ajax({
        url: contenturl + '/plugins/wp-ultimate-csv-importer/commentspost.php?categid=' + selectedCateg,
        type: 'post',
        data: selectedCateg,
        success: function (response) {
            jQuery("#showPosts").html(response);
        }
    });
}
function showRadioSett(id) {
    var inputs = document.getElementsByClassName(id);
    if (document.getElementById(id).checked) {
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = false;
        }
    }
    else {
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = true;
        }
    }
}
// Function for add customfield

function addcustomfield(myval, selected_id) {
    var a = document.getElementById('h1').value;
    var importer = document.getElementById('selectedImporter').value;
    var aa = document.getElementById('h2').value;
    var selected_value;
    if (importer == 'custompost' || importer == 'post' || importer == 'page') {
        for (var i = 0; i < aa; i++) {
            var b = document.getElementById('mapping' + i).value;
            if (b == 'add_custom' + i) {
                document.getElementById('textbox' + i).style.display = "";
                document.getElementById('customspan' + i).style.display = "";
            }
            else {
                document.getElementById('textbox' + i).style.display = "none";
                document.getElementById('customspan' + i).style.display = "none";
            }
        }
    }
    var header_count = document.getElementById('h2').value;
    for (var j = 0; j < header_count; j++) {
        var selected_value = document.getElementById('mapping' + j);
        var value1 = selected_value.options[selected_value.selectedIndex].value;
        if (j != selected_id) {
            if (myval == value1 && myval != '-- Select --') {
                var selected_dropdown = document.getElementById('mapping' + selected_id);
                selected_dropdown.selectedIndex = '-- Select --';
                showMapMessages('error', myval + ' is already selected!');
            }
        }
    }
}

// Function for check file exist

function file_exist() {
    var requestaction = document.getElementById('requestaction').value;
    if (requestaction == 'post' || requestaction == 'custompost' || requestaction == 'page') {
        if (document.getElementById('filenameupdate').checked) {
            if ((!document.getElementById('updatewithpostid').checked) && (!document.getElementById('updatewithposttitle').checked)) {
                showMessages('error', 'Select Update Based On.');
                return false;
            }

        }
    }

    if (document.getElementById('csv_import').value == '') {
        showMessages('error', "Please attach your CSV.");
        return false;
    }
    else {
        return true;
    }
}

//function show messages
function showMessages(alerttype, msg) {
    document.getElementById('showMsg').innerHTML = msg;
    document.getElementById('showMsg').className += ' ' + alerttype;
    document.getElementById('showMsg').style.display = '';
}

function showMapMessages(alerttype, msg) {
    jQuery("#showMsg").addClass("maperror");
    document.getElementById('showMsg').innerHTML = msg;
    document.getElementById('showMsg').className += ' ' + alerttype;
    document.getElementById('showMsg').style.display = '';
    jQuery("#showMsg").fadeOut(10000);
}

// Function for import csv

function import_csv() {
    var importer = document.getElementById('selectedImporter').value;
    var header_count = document.getElementById('h2').value;
    var array = new Array();
    var val1, val2, val3, val4, val5, val6, val7, error_msg;
    val1 = val2 = val3 = val4 = val5 = val6 = val7 = post_status_msg = 'Off';
    for (var i = 0; i < header_count; i++) {
        var e = document.getElementById("mapping" + i);
        var value = e.options[e.selectedIndex].value;
        array[i] = value;
    }
    if (importer == 'post' || importer == 'page' || importer == 'custompost') {
        var getSelectedIndex = document.getElementById('csv_importer_cat');
        var SelectedIndex = getSelectedIndex.value;
        var chk_status_in_csv;
        var post_status_msg;
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
        if (val1 == 'On' && val2 == 'On' && SelectedIndex != '-- Select --' && post_status_msg == 'On') {
            return true;
        }
        else {
            error_msg = '';
            if (val1 == 'Off')
                error_msg += " post_title,";
            if (val2 == 'Off')
                error_msg += " post_content,";
            if (SelectedIndex == '-- Select --')
                error_msg += " post type";
            if (post_status_msg == 'Off')
                error_msg += " post_status";
            showMapMessages('error', 'Error: ' + error_msg + ' - Mandatory fields. Please map the fields to proceed.');
            return false;
        }
    }
}

// Select the Mapper for Post/Page
function selectType(id, adminurl) {
    var headercount = document.getElementById('h2').value;
    var getdropdownvalue = document.getElementById(id);
    var selectedvalue = getdropdownvalue.options[getdropdownvalue.selectedIndex].text;
    for (var i = 0; i < headercount; i++) {
        var x = document.getElementById("mapping" + i);
        var myOpts = x.options;
        var postCatePresent = false;
        var postTagPresent = false;
        if (selectedvalue == 'post') {
            for (var j = 0; j < myOpts.length; j++) {
                if (myOpts[j].value == 'post_category')
                    postCatePresent = true;
                if (myOpts[j].value == 'post_tag')
                    postTagPresent = true;
            }
            if (!postCatePresent) {
                var option = document.createElement("option");
                option.value = "post_category";
                option.text = "post_category";
                x.add(option);
            }
            if (!postTagPresent) {
                var option1 = document.createElement("option");
                option1.value = 'post_tag';
                option1.text = 'post_tag';
                x.add(option1);
            }
        }
        else {
            jQuery("#mapping" + i + " option[value='post_category']").remove();
            jQuery("#mapping" + i + " option[value='post_tag']").remove();
        }
    }
}

function slideonlyone(thechosenone, content_url) {
    jQuery('.newboxes2').each(function (index) {
        if (jQuery(this).attr("id") == thechosenone) {
            jQuery(this).slideDown(200);
            var id = jQuery(this).attr('id');
            jQuery("#" + id + "_img").attr('src', content_url + "/plugins/wp-ultimate-csv-importer/images/arrow_down.gif");
        }
        else {
            jQuery(this).slideUp(600);
            var id = jQuery(this).attr('id');
            jQuery("#" + id + "_img").attr('src', content_url + "/plugins/wp-ultimate-csv-importer/images/arrow_up.gif");

        }
    });
}

// Function to save plugin settings
function savePluginSettings() {
    window.setTimeout("showSuccessMessage()", 100);
    window.setTimeout("hideSuccessMessage()", 15000);
}

function showSuccessMessage() {
    document.getElementById('upgradetopro').style.display = "";
}

function hideSuccessMessage() {
    document.getElementById('upgradetopro').style.display = "none";
}

