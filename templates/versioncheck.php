<?php
//require_once('../../../../../../wp-load.php');
global $wpdb;
//echo ($_REQUEST['postdata']);
$all_arr=array();
$all_arr=$_REQUEST['postdata'];
$all_arr=$all_arr[0];
//print_r($all_arr);
if($all_arr['action']=='file_exist_check')
{
$file_with_version=$all_arr['filename'];

$temp_arr=array();
$temp_arr=explode("(",$file_with_version);
$file_name=$temp_arr[0].'.csv';
$all_csv_names = $wpdb->get_results("select csv_name from smack_dashboard_manager"); 
$all_names=array();
//print($file_name);
foreach($all_csv_names as $key1 => $value1)
{

	foreach($value1 as  $key2 => $value2)
		{
			$all_names[]=$value2;
		}

}

	if(in_array($file_name,$all_names))
	{
		print('exist');
	}
	else
	{
		print('not_exist');
	}
//print_r($all_names);
}
elseif($all_arr['action']=='move_file')
{
$plugin_dir=wp_upload_dir();
$file_name=$all_arr['filename'];
$source=$plugin_dir['basedir'].'/ultimate_importer/temp_importer/'.$file_name;
$destination=$plugin_dir['basedir'].'/ultimate_importer/'.$file_name;
//print('sf:'.$source);
//print('df:'.$destination);
//print_r($plugin_dir);
copy($source,$destination);

}
?>
