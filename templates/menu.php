<?php
/******************************************************************************************
 * Copyright (C) Smackcoders 2014 - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/
//require_once(WP_CONST_ULTIMATE_CSV_IMP_DIR.'includes/WPImporter_includes_helper.php');
$impObj = new WPImporter_includes_helper();
$nonceKey = $impObj->create_nonce_key();
if(! wp_verify_nonce($nonceKey, 'smack_nonce'))
die('You are not allowed to do this operation.Please contact your admin.');
$impCheckobj = CallWPImporterObj::checkSecurity();
if($impCheckobj != 'true')
die($impCheckobj);

$post = $page = $custompost = $categories = $users = $customtaxonomy = $comments = $eshop = $wpcommerce = $woocommerce = $settings = $support = $dashboard = $export = $mappingtemplate = $filemanager = $schedulemapping = $marketpress = $customerreviews = '';
$impCEM = CallWPImporterObj::getInstance();
$get_settings = array();
$get_settings = $impCEM->getSettings();
$mod = isset($_REQUEST['__module']) ? $_REQUEST['__module'] : '';
if( is_array($get_settings) && !empty($get_settings) ) {
        foreach ($get_settings as $key) {
                $$key = true;
        }
}
if (isset($_POST['post_csv']) && $_POST['post_csv'] == 'Import') {
	$dashboard = 'activate';
} else {
	if (isset($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
               
		$$action = 'activate';
	} else {
		if (isset($mod) && !empty($mod)) {
                       $module_array =array('post','page','custompost','users','custompost','customtaxonomy','customerreviews','comments','eshop','wpcommerce','woocommerce','marketpress','filemanager','schedulemapping','mappingtemplate' ,'dashboard');
                  foreach($module_array as $val) {
                       if($val = $mod) { 
			   $$mod = 'activate';
                             if( $mod!= 'filemanager' &&  $mod != 'schedulemapping' &&  $mod != 'mappingtemplate' && $mod != 'support' && $mod != 'export' && $mod != 'settings' && $mod != 'dashboard') {
                                $module = 'activate';
                                $manager = 'deactivate';
                                $dashboard = 'deactivate';
                                }
                             else if($mod != 'support' && $mod != 'export' && $mod != 'settings' && $mod != 'dashboard') {
                                $manager = 'activate';
                                $module = 'deactivate';
                                $dashboard = 'deactivate';
                                }
                             else if($mod == 'dashboard') {
                                $manager = 'deactivate';
                                $module = 'deactivate';
                                }
                        }                 
                  }
	        } else {
		      if (!isset($_REQUEST['action'])) {
				$dashboard = 'deactivate';
			}
		}
	}
}
$tab_inc = 1;

$menuHTML = "<nav class='navbar navbar-default' role='navigation'>
   <div>
      <ul class='nav navbar-nav'>
         <li  class = '{$dashboard}' ><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=dashboard'  >".__('Dashboard',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
         <li class='dropdown {$module} '>
            <a href='#'  data-toggle='dropdown'>
               ". __('Imports',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."
               <b class='caret'></b>
            </a>
            <ul class='dropdown-menu'>
               <li class= '{$post}'><a href= 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=post&step=uploadfile'>".__('Post',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
               <li class = '{$page}'><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=page&step=uploadfile'>". __('Page',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
               <li class = '{$custompost}'><a href= 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=custompost&step=uploadfile'>". __('Custom Post',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>";
if($comments) {
$menuHTML .= "<li class = '{$comments}'><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=comments&step=uploadfile'>". __('Comments',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>";
} 
if($users) {
$menuHTML .= "<li class = '{$users}'><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=users&step=uploadfile'>". __('Users',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>";
}
if($eshop) {
$menuHTML .= "<li class = '{$eshop}'><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=eshop&step=uploadfile'>". __('Eshop',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>";
}
$menuHTML .= "</ul>
         </li>
         <li class='dropdown {$manager}'>
            <a href='#'  data-toggle='dropdown'> Managers <b class='caret'></b>  </a>
            <ul class='dropdown-menu'>
               <li class = '{$filemanager}'><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=filemanager'>". __('File Manager',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
               <li class = '{$schedulemapping}'><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=schedulemapping'>". __('Smart Scheduler',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
               <li class = '{$mappingtemplate}'><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=mappingtemplate'>". __('Templates',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
            </ul>
         </li>";
         $menuHTML .= "<li class = '{$export}'><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=export'>". __('Export',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>";
         $menuHTML .= "<li class=  '{$settings}'><a href='admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=settings'  />". __('Settings',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
         <li class = '{$support}'><a href= 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=support'>". __('Support',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
         
         <li ><a href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html?utm_source=WpPlugin&utm_medium=Free&utm_campaign=SupportTraffic' target='_blank'>". __('Go Pro Now',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
         <li ><a href='http://demo.smackcoders.com/demowpthree/wp-admin/admin.php?page=wp-ultimate-csv-importer-pro/index.php&__module=dashboard' target='_blank'>" . __('Try Live Demo Now',WP_CONST_ULTIMATE_CSV_IMP_SLUG)."</a></li>
      </ul>";
    $plugin_version = get_option('ULTIMATE_CSV_IMP_VERSION');
$menuHTML .= "</div>";
$menuHTML .= "<div class='msg' id = 'showMsg' style = 'display:none;'></div>";
$menuHTML .= "<input type='hidden' id='current_url' name='current_url' value='" . get_admin_url() . "admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=" . $_REQUEST['__module'] . "&step=uploadfile'/>";
$menuHTML .= "<input type='hidden' name='checkmodule' id='checkmodule' value='" . $_REQUEST['__module'] . "' />";

$menuHTML .=  "
</nav>";

echo $menuHTML;

