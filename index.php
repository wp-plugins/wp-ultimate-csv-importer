<?php
/******************************
Plugin Name: WP Ultimate CSV Importer
Description: A plugin that helps to import the data's from a CSV file.
Version: 3.5.2
Author: smackcoders.com
Plugin URI: http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html
Author URI: http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html
 * filename: index.php
 */
ob_start();
define('WP_CONST_ULTIMATE_CSV_IMP_URL', 'http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html');
define('WP_CONST_ULTIMATE_CSV_IMP_NAME', 'WP Ultimate CSV Importer');
define('WP_CONST_ULTIMATE_CSV_IMP_SLUG', 'wp-ultimate-csv-importer');
define('WP_CONST_ULTIMATE_CSV_IMP_SETTINGS', 'WP Ultimate CSV Importer');
define('WP_CONST_ULTIMATE_CSV_IMP_VERSION', '3.5.2');
define('WP_CONST_ULTIMATE_CSV_IMP_DIR', WP_PLUGIN_URL . '/' . WP_CONST_ULTIMATE_CSV_IMP_SLUG . '/');
define('WP_CONST_ULTIMATE_CSV_IMP_DIRECTORY', plugin_dir_path( __FILE__ ));
define('WP_CSVIMP_PLUGIN_BASE', WP_CONST_ULTIMATE_CSV_IMP_DIRECTORY);

//require_once('config/settings.php');

if(!class_exists('SkinnyControllerWPCsvFree'))
    require_once('lib/skinnymvc/controller/SkinnyController.php');

require_once('includes/WPImporter_includes_helper.php');

# Activation & Deactivation 
register_activation_hook(__FILE__, array('WPImporter_includes_helper', 'activate') );
register_deactivation_hook(__FILE__, array('WPImporter_includes_helper', 'deactivate') );

function action_csv_imp_admin_menu()
{
	add_menu_page(WP_CONST_ULTIMATE_CSV_IMP_SETTINGS, WP_CONST_ULTIMATE_CSV_IMP_NAME, 'manage_options',  __FILE__, array('WPImporter_includes_helper','output_fd_page'), WP_CONST_ULTIMATE_CSV_IMP_DIR . "/images/icon.png");
}
add_action ( "admin_menu", "action_csv_imp_admin_menu" );

function action_csv_imp_admin_init()
{
	if(isset($_REQUEST['page']) && ($_REQUEST['page'] == 'wp-ultimate-csv-importer/index.php' || $_REQUEST['page'] == 'page')) {
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-style', plugins_url('css/jquery-ui.css', __FILE__));
		wp_register_script('ultimate-importer-js', plugins_url('js/ultimate-importer-pro.js', __FILE__));
		wp_enqueue_script('ultimate-importer-js');
		wp_register_script('ultimate-importer-button', plugins_url('js/buttons.js', __FILE__));
		wp_enqueue_script('ultimate-importer-button');
		wp_enqueue_style('ultimate_importer_font_awesome', plugins_url('css/font-awesome.css', __FILE__));
		wp_register_script('jquery-min', plugins_url('js/jquery.js', __FILE__));
		wp_enqueue_script('jquery-min');
		wp_register_script('jquery-widget', plugins_url('js/jquery.ui.widget.js', __FILE__));
		wp_enqueue_script('jquery-widget');
		wp_register_script('jquery-fileupload', plugins_url('js/jquery.fileupload.js', __FILE__));
		wp_enqueue_script('jquery-fileupload');
		wp_register_script('bootstrap-collapse', plugins_url('js/bootstrap-collapse.js', __FILE__));
		wp_enqueue_script('bootstrap-collapse');
		wp_enqueue_style('style', plugins_url('css/style.css', __FILE__));
		wp_enqueue_style('jquery-fileupload', plugins_url('css/jquery.fileupload.css', __FILE__));
		wp_enqueue_style('bootstrap-css', plugins_url('css/bootstrap.css', __FILE__));
		wp_enqueue_style('ultimate-importer-css', plugins_url('css/main.css', __FILE__));
	}
}
add_action('admin_init', 'action_csv_imp_admin_init');

function importByRequest()
{
        require_once("templates/import.php");
        die;
}   
add_action('wp_ajax_importByRequest', 'importByRequest');

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}
?>
