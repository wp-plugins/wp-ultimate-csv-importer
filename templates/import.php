<?php
require_once(WP_CONST_ULTIMATE_CSV_IMP_DIRECTORY.'lib/skinnymvc/core/base/SkinnyBaseActions.php');
require_once(WP_CONST_ULTIMATE_CSV_IMP_DIRECTORY.'lib/skinnymvc/core/SkinnyActions.php');
$skinnyObj = new CallWPImporterObj();
$curr_action = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['selectedImporter'];

if($curr_action!='post' && $curr_action!= 'page' && $curr_action!='custompost'){
	require_once(plugin_dir_path(__FILE__) . '../modules/'.$curr_action.'/actions/actions.php');
}
if($curr_action=='post' || $curr_action== 'page' || $curr_action=='custompost'){
	$importObj = new WPImporter_includes_helper();
}elseif($curr_action=='eshop'){
	$importObj = new EshopActions();
}elseif($curr_action=='wpcommerce'){
	$importObj = new WpcommerceActions();
	}
	elseif($curr_action=='woocommerce'){
        $importObj = new WooCommerceActions();
	}
elseif($curr_action=='users'){
	$importObj = new UsersActions();
}
elseif($curr_action=='categories'){
        $importObj = new CategoriesActions();
}
elseif($curr_action=='customtaxonomy'){
        $importObj = new CustomTaxonomyActions();
}
elseif($curr_action=='comments'){
        $importObj = new CommentsActions();
}


$limit = $_POST['postdata']['limit'];
$totRecords = $_POST['postdata']['totRecords'];
$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importlimit'] = $_POST['postdata']['importlimit'];
$count = $_POST['postdata']['importlimit'];
$requested_limit = $_POST['postdata']['importlimit'];
$tmpCnt = $_POST['postdata']['tmpcount'];

if($count < $totRecords){
	$count = $tmpCnt+$count;
	if($count > $totRecords){
		$count = $totRecords;
	}
}else{
	$count = $totRecords;
}
$resultArr = array();
$filename = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['uploadedFile'];
$delim = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['select_delimeter'];
$resultArr = $skinnyObj->csv_file_data($filename,$delim);
if($_POST['postdata']['dupTitle']){
	$importObj->titleDupCheck = $_POST['postdata']['dupTitle'];
}
if($_POST['postdata']['dupContent']){
	$importObj->conDupCheck = $_POST['postdata']['dupContent'];
}
$csv_rec_count = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['h2'];
for($i=$limit;$i<$count;$i++){
	$colCount = count($resultArr[$i]);
	$_SESSION['SMACK_SKIPPED_RECORDS'] = $i;
	$importObj->processDataInWP($resultArr[$i],$_SESSION['SMACK_MAPPING_SETTINGS_VALUES'],$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']);
	$limit++;
}
if($limit >= $totRecords){
	$dir = $skinnyObj->getUploadDirectory();
	$skinnyObj->deletefileafterprocesscomplete($dir);
}
if($importObj->insPostCount != 0 || $importObj->dupPostCount != 0 || $importObj->updatedPostCount != 0){
	if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPostCount']))
	        $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPostCount']=0;
	if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPostCount']))
	        $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPostCount']=0;
	if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']))
	        $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']=0;
	if(!isset($importObj->capturedId))
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['captureId']=0;
$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPostCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPostCount']+$importObj->insPostCount;
$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPostCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPostCount']+$importObj->dupPostCount;
$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']+$importObj->updatedPostCount;
if(isset($importObj->capturedId)){
	$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['captureId']=$importObj->capturedId;
}
}
if($curr_action=='users'){
        echo "<div style='margin-left:7px;'>";
        if(($limit == $requested_limit) && ($limit <= $count))
                echo "<div style='margin-left:3px;'>Chosen server request is " . $count . " .</div><br>";
	echo "[". date('h:m:s') ."] - No of user(s) Skipped - " . $importObj->dupPostCount . ".<br>";
	echo "[". date('h:m:s') ."] - No of user(s) Inserted - " . $importObj->insPostCount . '.<br>';
        if($limit == $totRecords)
                echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
        echo "</div>";
}
elseif($curr_action=='comments')
{
        echo "<div style='margin-left:7px;'>";
        if(($limit == $requested_limit) && ($limit <= $count))
                echo "<div style='margin-left:3px;'>Chosen server request is " . $count . " .</div><br>";
	echo "[". date('h:m:s') ."] - No of comment(s) Skipped - " . $importObj->dupPostCount . '.<br>';
	echo "[". date('h:m:s') ."] - No of comment(s) Inserted - " . $importObj->insPostCount . ".<br>";
        if($limit == $totRecords)
                echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
        echo "</div>";
}
elseif($curr_action=='customtaxonomy')
{
        echo "<div style='margin-left:7px;'>";
        if(($limit == $requested_limit) && ($limit <= $count))
                echo "<div style='margin-left:3px;'>Chosen server request is " . $count . " .</div><br>";
        echo "[". date('h:m:s') ."] - No of customtaxonomies Skipped - " . $importObj->dupPostCount . ".<br>";
        echo "[". date('h:m:s') ."] - No of customtaxonomies Inserted - " . $importObj->insPostCount . ".<br>";
        if($limit == $totRecords)
                echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
        echo "</div>";
}

elseif($curr_action=='categories'){
	echo "<div style='margin-left:7px;'>";
        if(($limit == $requested_limit) && ($limit <= $count))
                echo "<div style='margin-left:3px;'>Chosen server request is " . $count . " .</div><br>";
        echo "[". date('h:m:s') ."] - No of categories Skipped - " . $importObj->dupPostCount . '.<br>';
        echo "[". date('h:m:s') ."] - No of categories Inserted - " . $importObj->insPostCount . '.<br>';
        if($limit == $totRecords)
                echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
        echo "</div>";
}
elseif($curr_action=='post'){
	echo "<div style='margin-left:7px;'>";
	if(($limit == $requested_limit) && ($limit <= $count))
		echo "<div style='margin-left:3px;'>Chosen server request is " . $count . " .</div><br>";
	echo "[". date('h:m:s') ."] - No of post(s) Skipped - " . $importObj->dupPostCount . '.<br>';
	echo "[". date('h:m:s') ."] - No of post(s) Inserted - " . $importObj->insPostCount . '.<br>';
	echo "[". date('h:m:s') ."] - No of post(s) are assigned as admin - " . $importObj->noPostAuthCount . ".<br>";
	if($limit == $totRecords)
		echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
	echo "</div>";
}
elseif($curr_action=='page'){
        echo "<div style='margin-left:7px;'>";
        if(($limit == $requested_limit) && ($limit <= $count))
                echo "<div style='margin-left:3px;'>Chosen server request is " . $count . " .</div><br>";
        echo "[". date('h:m:s') ."] - No of page(s) Skipped - " . $importObj->dupPostCount . '.<br>';
        echo "[". date('h:m:s') ."] - No of page(s) Inserted - " . $importObj->insPostCount . '.<br>';
        echo "[". date('h:m:s') ."] - No of page(s) are assigned as admin - " . $importObj->noPostAuthCount . '.<br>';
        if($limit == $totRecords)
                echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
        echo "</div>";
}
elseif($curr_action=='custompost'){
	$customposttype = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['custompostlist'];
        echo "<div style='margin-left:7px;'>";
        if(($limit == $requested_limit) && ($limit <= $count))
                echo "<div style='margin-left:3px;'>Chosen server request is " . $count . " .</div><br>";
        echo "[". date('h:m:s') ."] - No of " . $customposttype . " Skipped - " . $importObj->dupPostCount . '.<br>';
        echo "[". date('h:m:s') ."] - No of " . $customposttype . " Inserted - " . $importObj->insPostCount . '.<br>';
        echo "[". date('h:m:s') ."] - No of " . $customposttype . " are assigned as admin - " . $importObj->noPostAuthCount . ".<br>";
        if($limit == $totRecords)
                echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
        echo "</div>";
}
foreach($_SESSION['SMACK_MAPPING_SETTINGS_VALUES'] as $key => $value){
	for($j=0;$j<$csv_rec_count;$j++){
		if($key == 'mapping'.$j){
			$mapArr[$j] = $value;
		}
	}
}
?>
