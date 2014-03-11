<?php
$post = $page = $custompost = $categories = $users = $customtaxonomy = $comments = $eshop = $wpcommerce = $woocommerce = $settings = $support = $dashboard = $export = $mappingtemplate = $filemanager = $schedulemapping = '';
$impCEM = CallWPImporterObj::getInstance();
$settings = $impCEM->getSettings();
$get_pluginData = get_plugin_data(plugin_dir_path( __FILE__ ).'../index.php'); 
$mod = isset($_REQUEST['__module']) ? $_REQUEST['__module'] : '';
foreach($settings as $key){
	$$key = true;
}
if(isset($_POST['post_csv']) && $_POST['post_csv'] == 'Import')
{
	$dashboard = 'selected';
}
else if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	$$action = 'selected';
}
else if(isset($mod) && !empty($mod))
{
	$$mod = 'selected';
}
else if(!isset($_REQUEST['action']))
{
	$dashboard = 'selected';
}
$menuHTML = "<div class='csv-top-navigation-wrapper' id='header' name='mainNavigation'><ul id='topNavigation'>";
if($post)
	$menuHTML .="<li class=\"navigationMenu $post\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=post&step=uploadfile' class = 'navigationMenu-link' id='module4'>Post</a></li>";
if($page)
	$menuHTML .="<li class=\"navigationMenu $page\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=page&step=uploadfile' class = 'navigationMenu-link' id='module5'>Page</a></li>";
if($custompost)
	$menuHTML .="<li class=\"navigationMenu $custompost\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=custompost&step=uploadfile' class = 'navigationMenu-link' id='module6'>Custompost</a></li>";
if($categories)
	$menuHTML .= "<li class=\"navigationMenu $categories\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=categories' class = 'navigationMenu-link' id = 'module7'>Categories/Tags</a></li>";
if($users)
	$menuHTML .= "<li class=\"navigationMenu $users \"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=users&step=uploadfile' class = 'navigationMenu-link' id = 'module8'>Users/Roles</a></li>";
if($customtaxonomy)
	$menuHTML .= "<li class=\"navigationMenu $customtaxonomy\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=customtaxonomy' class = 'navigationMenu-link' id = 'module9'>Custom Taxonomy</a></li>";
if($comments)
	$menuHTML .= "<li class=\"navigationMenu $comments\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=comments&step=uploadfile' class = 'navigationMenu-link' id = 'module10'>Comments</a></li>";
if($eshop)
	$menuHTML .= "<li class=\"navigationMenu $eshop\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=eshop' class = 'navigationMenu-link' id = 'module11'>Eshop</a></li>";
if($wpcommerce)
	$menuHTML .= "<li class=\"navigationMenu $wpcommerce\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=wpcommerce' class = 'navigationMenu-link' id = 'module12'>WP e-Commerce</a></li>";
if($woocommerce)
	$menuHTML .= "<li class=\"navigationMenu $woocommerce\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=woocommerce' class = 'navigationMenu-link' id = 'module13'>WooCommerce</a></li>";
$menuHTML .= "<li class=\"navigationMenu $settings\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=settings' class='navigationMenu-link' id='module15'>Settings</a></li>";
$menuHTML .= "<li class=\"navigationMenu $support\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=support' class='navigationMenu-link' id='module16'>Support</a></li>";
//$menuHTML .= "<li class=\"navigationMenu $dashboard\" style='margin-left:0px;'><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=dashboard' class='navigationMenu-link' id='module0'>Dashboard</a></li>";
/*$menuHTML .="<li class=\"navigationMenu $filemanager\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=filemanager' class = 'navigationMenu-link' id='module1'>File Manager</a></li>";
$menuHTML .= "<li class=\"navigationMenu $mappingtemplate\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=mappingtemplate' class='navigationMenu-link' id='module2'>Templates</a></li>";
$menuHTML .="<li class=\"navigationMenu $schedulemapping\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=schedulemapping' class = 'navigationMenu-link' id='module3'>Smart Schedule</a></li>";
*/
$tabcount = count(get_option('wpcsvfreesettings')); 
if(intval($tabcount) < 3)
{
$menuHTML .= "<li class=\"moreMenuList $dashboard\" style='margin-left:0px;'><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=dashboard' class='navigationMenu-link' id='module0'>Dashboard</a></li>";
//$menuHTML .="<li class=\"navigationMenu $filemanager\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=filemanager' class = 'navigationMenu-link' id='module1'>File Manager</a></li>";
$menuHTML .= "<li class=\"navigationMenu $mappingtemplate\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=mappingtemplate' class='navigationMenu-link' id='module2'>Templates</a></li>";
$menuHTML .="<li class=\"navigationMenu $schedulemapping\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=schedulemapping' class = 'navigationMenu-link' id='module3'>Smart Schedule</a></li>";
$menuHTML .= "<li class=\"navigationMenu $export\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=export' class='navigationMenu-link' id='module14'>Export</a></li>";
}
else
{
$menuHTML .="<li class=\"moreMenu\"><a href = '#' class='navigationMenu-link' id='module17'>Pro-modules</a><ul><li class=\"moreMenuList $dashboard\" style='margin-left:0px;'><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=dashboard' class='navigationMenu-link' id='module0'>Dashboard</a></li><!--<li class=\"moreMenuList  $filemanager\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=filemanager' class = 'navigationMenu-link' id='module1'>File Manager</a></li>--><li class=\"moreMenuList $mappingtemplate\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=mappingtemplate' class='navigationMenu-link' id='module2'>Templates</a></li> <li class=\"moreMenuList $schedulemapping\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=schedulemapping' class = 'navigationMenu-link' id='module3'>Smart Schedule</a></li><li class=\"moreMenuList  $export\"><a href = 'admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=export' class='navigationMenu-link' id='module14'>Export</a></li></ul></li>";
}
$menuHTML .= "</ul>";
$menuHTML .= "<div style='margin-right:10px;width: 250px;float: right;'>";
$menuHTML .= "<span class='prolinks'><a class='label label-info' href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html' target='_blank'>GO PRO NOW</a></span>";
$menuHTML .= "<span class='prolinks'><a class='label label-info' href='http://demo.smackcoders.com/demowpthree/wp-admin/admin.php?page=wp-ultimate-csv-importer-pro/index.php&__module=dashboard' target='_blank'>TRY PRO LIVE DEMO NOW</a></span>";
$menuHTML .= "</div>";
$menuHTML .= "</div>";
$menuHTML .= "<div style='width:100%;padding-bottom:30px;'>";
$menuHTML .= '<div class="">
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer" target="_blank">WIKI</a></label>
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer_FAQ" target="_blank">FAQ</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/" target="_blank">TUTORIALS</a></label>
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer_Videos" target="_blank">VIDEOS</a></label>
<label class="plugintags"><a href="http://forum.smackcoders.com/" target="_blank">FORUM</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/wordpress-ultimate-csv-importer-csv-sample-files-and-updates.html" target="_blank">SAMPLE FILES</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/how-to-make-one-click-easy-csv-import-in-wordpress-free-cheat-sheet-downloads.html" target="_blank">CHEAT SHEETS</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/" target="_blank">RELATED DOWNLOADS</a></label>
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer_Change_Log" target="_blank">CHANGE LOG</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/" target="_blank">CURRENT VERSION NEWS</a></label>
</div>';
if(isset ($_REQUEST['__module']) && $_REQUEST['__module'] != 'settings')
	$menuHTML .= "<div style='float:left;'><a class='label label-info' href='".get_admin_url()."admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=settings'>Click here to Enable any disabled module</a></div>";

if(isset ($_REQUEST['__module']) && $_REQUEST['__module'] == 'settings') {
        $menuHTML .= "<div style='float:left;margin-right:15px;'><a class='label label-info' href='".get_admin_url()."admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=support'>Click here to Get some useful links</a></div>"; 
	$menuHTML .= "<div style='float:right;margin-right:15px;'>Current Version: ".$get_pluginData['Version']." <a class='label label-info' href='http://wordpress.org/plugins/wp-ultimate-csv-importer/developers/'>Get Old Versions</a></div>";
}
if(isset ($_REQUEST['__module']) && $_REQUEST['__module'] != 'support' && $_REQUEST['__module'] != 'settings') {
        $menuHTML .= "<div style='float:right;margin-right:15px;'><a class='label label-info' href='".get_admin_url()."admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=support'>Click here to Get some useful links</a></div>"; 
        $menuHTML .= "<div style='float:right;margin-right:15px;'>Current Version: ".$get_pluginData['Version']." <a class='label label-info' href='http://wordpress.org/plugins/wp-ultimate-csv-importer/developers/'>Get Old Versions</a></div>";
}
if(isset ($_REQUEST['__module']) && $_REQUEST['__module'] == 'support'){
        $menuHTML .= "<div style='float:right;margin-right:15px;'>Current Version: ".$get_pluginData['Version']." <a class='label label-info' href='http://wordpress.org/plugins/wp-ultimate-csv-importer/developers/'>Get Old Versions</a></div>";
}
$menuHTML .= "</div>";
$menuHTML .= "<div class='msg' id = 'showMsg' style = 'display:none;'></div>";
$menuHTML .= "<input type='hidden' id='current_url' name='current_url' value='".get_admin_url()."admin.php?page=".WP_CONST_ULTIMATE_CSV_IMP_SLUG."/index.php&__module=".$_REQUEST['__module']."&step=uploadfile'/>";
echo $menuHTML;
?>
