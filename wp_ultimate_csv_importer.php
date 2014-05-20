<?php
/**
 * Activate the CSV importer free plugin
 */
$get_active_plugins = get_option('active_plugins');
$pluginArr = array();
foreach($get_active_plugins as $plugin){
	if($plugin == 'wp-ultimate-csv-importer/wp_ultimate_csv_importer.php'){
		$pluginArr[] = 'wp-ultimate-csv-importer/index.php';
	} else {
		$pluginArr[] = $plugin;
	}
}
update_option('active_plugins', $pluginArr);
?>
