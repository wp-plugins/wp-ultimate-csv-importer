<?php
require_once('../includes/WPImporter_includes_helper.php');
require_once('../../../../wp-load.php');
$impObj = CallWPImporterObj::getInstance(); //print_r($impObj);//die;
$filename=$_POST['file_name'];
$delimeter = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['delim'];
$result = $impObj->csv_file_data($filename, $delimeter);
print_r(json_encode($result[$_REQUEST['record_no']]));
?>
