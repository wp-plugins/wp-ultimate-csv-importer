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

$pluginURL = plugins_url();
require_once(WP_CONST_ULTIMATE_CSV_IMP_DIRECTORY . 'lib/skinnymvc/core/base/SkinnyBaseActions.php');
require_once(WP_CONST_ULTIMATE_CSV_IMP_DIRECTORY . 'lib/skinnymvc/core/SkinnyActions.php');
require_once('actions.php');

$dashObj = new DashboardActions(); 
$maincontent = array();
$totalfilescount = '';
$content = $_REQUEST['postdata'];
foreach ($content as $key => $value) {
	$maincontent = $value;
}

if ($maincontent['action'] == 'download') {
	echo($dashObj->downloadCSVFile($maincontent['file']));
} elseif ($maincontent['action'] == 'downloadall') {
	echo($dashObj->downloadAllCSVFiles($maincontent['file']));
} elseif ($maincontent['action'] == 'deletefiles') {
	$csvfile = $maincontent['csvname'];
	$managerID = $maincontent['managerid'];
	$importedas = $maincontent['importedas'];
	echo($dashObj->deleteCSVFiles($csvfile, $managerID, $importedas));

} elseif ($maincontent['action'] == 'deletefilesandrecords' || $maincontent['action'] == 'deleteall') {
	$csvfile = $maincontent['csvname'];
	$managerID = $maincontent['managerid'];
	$importedas = $maincontent['importedas'];
	if (isset($maincontent['totalcount'])) {
		$totalfilescount = $maincontent['totalcount'];
	}
	if ($maincontent['action'] == 'deletefilesandrecords') {
		$action = 'deleteFilesRecords';
	} elseif ($maincontent['action'] == 'deleteall') {
		$action = 'deleteall';
	}
	echo($dashObj->deleteFilesRecords($csvfile, $managerID, $importedas, $totalfilescount, $action));
} elseif ($maincontent['action'] == 'trashall') {
	//echo 'trashall';
	$managerID = $maincontent['managerid'];
	$importedas = $maincontent['importedas'];
	$perform = $maincontent['perform'];
	echo($dashObj->restoreTrashAllRecords($managerID, $importedas, $perform));
}
