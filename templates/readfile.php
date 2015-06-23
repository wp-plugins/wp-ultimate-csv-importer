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
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once($parse_uri[0]."wp-load.php");
$impCheckobj = CallWPImporterObj::checkSecurity();
if($impCheckobj != 'true')
die($impCheckobj);
$noncevar = isset($_REQUEST['wpnonce']) ? $_REQUEST['wpnonce'] : '';
if(! wp_verify_nonce($noncevar, 'smack_nonce'))
die('You are not allowed to do this operation.Please contact your admin.');

$requested_module = "";
if(isset($requested_module))
$requested_module = $_REQUEST['checkmodule'];
$post_url = admin_url() . 'admin.php?page=' . WP_CONST_ULTIMATE_CSV_IMP_SLUG . '/index.php&__module=' . $requested_module . '&step=mapping_settings';
 if($_SERVER['HTTP_REFERER'] != urldecode($_SERVER['HTTP_REFERER'])){
                if($post_url != urldecode($_SERVER['HTTP_REFERER']))
                die('Your requested url were wrong! Please contact your admin.');
        }
        else {
                if($post_url != $_SERVER['HTTP_REFERER'] )
                die('Your requested url were wrong! Please contact your admin.');
        }

$impObj = CallWPImporterObj::getInstance(); 
$filename = $_POST['file_name'];
$delimeter = '';
$result = $impObj->csv_file_readdata($filename, $impObj);
#$result = $impObj->csv_file_data($filename);
foreach($result[$_REQUEST['record_no']] as $key => $value) {
	$data[] = html_entity_decode($value);
}
print_r(json_encode($data));
