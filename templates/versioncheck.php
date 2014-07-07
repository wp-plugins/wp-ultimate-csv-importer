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

global $wpdb;
$all_arr = array();
$all_arr = $_REQUEST['postdata'];
$all_arr = $all_arr[0];
if ($all_arr['action'] == 'file_exist_check') {
	$file_with_version = $all_arr['filename'];

	$temp_arr = array();
	$temp_arr = explode("(", $file_with_version);
	$file_name = $temp_arr[0] . '.csv';
	$all_csv_names = $wpdb->get_results("select csv_name from smack_dashboard_manager");
	$all_names = array();
	foreach ($all_csv_names as $key1 => $value1) {

		foreach ($value1 as $key2 => $value2) {
			$all_names[] = $value2;
		}

	}

	if (in_array($file_name, $all_names)) {
		print('exist');
	} else {
		print('not_exist');
	}
} elseif ($all_arr['action'] == 'move_file') {
	$plugin_dir = wp_upload_dir();
	$file_name = $all_arr['filename'];
	$source = $plugin_dir['basedir'] . '/ultimate_importer/temp_importer/' . $file_name;
	$destination = $plugin_dir['basedir'] . '/ultimate_importer/' . $file_name;
	copy($source, $destination);
}
