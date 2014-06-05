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

$post = $page = $custompost = $categories = $users = $customtaxonomy = $comments = $eshop = $wpcommerce = $woocommerce = $settings = $support = $dashboard = $export = $mappingtemplate = $filemanager = $schedulemapping = '';
$impCEM = CallWPImporterObj::getInstance();
$get_settings = $impCEM->getSettings();
$get_pluginData = get_plugin_data(plugin_dir_path(__FILE__) . '../index.php');
$mod = isset($_REQUEST['__module']) ? $_REQUEST['__module'] : '';
foreach ($get_settings as $key) {
    $$key = true;
}
if (isset($_POST['post_csv']) && $_POST['post_csv'] == 'Import') {
    $dashboard = 'selected';
} else if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    $$action = 'selected';
} else if (isset($mod) && !empty($mod)) {
    $$mod = 'selected';
} else if (!isset($_REQUEST['action'])) {
    $dashboard = 'selected';
}
$menuHTML = "<div class='csv-top-navigation-wrapper' id='header' name='mainNavigation'><ul id='topNavigation'>";
if ($post)
    $menuHTML .= "<li class=\"navigationMenu $post\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=post&step=uploadfile' class = 'navigationMenu-link' id='module4'>Post</a></li>";
if ($page)
    $menuHTML .= "<li class=\"navigationMenu $page\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=page&step=uploadfile' class = 'navigationMenu-link' id='module5'>Page</a></li>";
if ($custompost)
    $menuHTML .= "<li class=\"navigationMenu $custompost\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=custompost&step=uploadfile' class = 'navigationMenu-link' id='module6'>Custompost</a></li>";
if ($categories)
    $menuHTML .= "<li class=\"navigationMenu $categories\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=categories' class = 'navigationMenu-link' id = 'module7'>Categories/Tags</a></li>";
if ($users)
    $menuHTML .= "<li class=\"navigationMenu $users \"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=users&step=uploadfile' class = 'navigationMenu-link' id = 'module8'>Users/Roles</a></li>";
if ($customtaxonomy)
    $menuHTML .= "<li class=\"navigationMenu $customtaxonomy\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=customtaxonomy' class = 'navigationMenu-link' id = 'module9'>Custom Taxonomy</a></li>";
if ($comments)
    $menuHTML .= "<li class=\"navigationMenu $comments\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=comments&step=uploadfile' class = 'navigationMenu-link' id = 'module10'>Comments</a></li>";
if ($eshop)
    $menuHTML .= "<li class=\"navigationMenu $eshop\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=eshop' class = 'navigationMenu-link' id = 'module11'>Eshop</a></li>";
if ($wpcommerce)
    $menuHTML .= "<li class=\"navigationMenu $wpcommerce\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=wpcommerce' class = 'navigationMenu-link' id = 'module12'>WP e-Commerce</a></li>";
if ($woocommerce)
    $menuHTML .= "<li class=\"navigationMenu $woocommerce\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=woocommerce' class = 'navigationMenu-link' id = 'module13'>WooCommerce</a></li>";
$menuHTML .= "<li class=\"navigationMenu $export\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=export' class='navigationMenu-link' id='module14'>Export</a><img src='" . WP_PLUGIN_URL . "/" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/images/new.gif' alt='New' width='35' height='12' border='0' class='newmoduleicon'></li>";
$menuHTML .= "<li class=\"navigationMenu $settings\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=settings' class='navigationMenu-link' id='module15'>Settings</a></li>";

$tabcount = count(get_option('wpcsvfreesettings'));

if (intval($tabcount) < 3) {
    $menuHTML .= "<li class=\"moreMenuList $dashboard\" style='margin-left:0px;'><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=dashboard' class='navigationMenu-link' id='module0'>Dashboard</a></li>";
    $menuHTML .= "<li class=\"navigationMenu $mappingtemplate\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=mappingtemplate' class='navigationMenu-link' id='module2'>Templates</a></li>";
    $menuHTML .= "<li class=\"navigationMenu $schedulemapping\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=schedulemapping' class = 'navigationMenu-link' id='module3'>Smart Schedule</a></li>";
} else {
    $menuHTML .= "<li class=\"moreMenu\"><a href = '#' class='navigationMenu-link' id='module17'>Power-Features</a><ul><li class=\"moreMenuList $dashboard\" style='margin-left:0px;'><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=dashboard' class='navigationMenu-link' id='module0'>Dashboard</a></li><!--<li class=\"moreMenuList  $filemanager\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=filemanager' class = 'navigationMenu-link' id='module1'>File Manager</a></li>--><li class=\"moreMenuList $mappingtemplate\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=mappingtemplate' class='navigationMenu-link' id='module2'>Templates</a></li> <li class=\"moreMenuList $schedulemapping\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=schedulemapping' class = 'navigationMenu-link' id='module3'>Smart Schedule</a></li></ul></li>";
}
$menuHTML .= "<li class=\"navigationMenu $support\"><a href = 'admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=support' class='navigationMenu-link' id='module16'>Support</a></li>";
$menuHTML .= "</ul>";
$menuHTML .= "<div style='margin-right:10px;width: 250px;float: right;'>";
$menuHTML .= "<span class='prolinks'><a class='label label-info' href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html' target='_blank'>GO PRO NOW</a></span>";
$menuHTML .= "<span class='prolinks'><a class='label label-info' href='http://demo.smackcoders.com/demowpthree/wp-admin/admin.php?page=wp-ultimate-csv-importer-pro/index.php&__module=dashboard' target='_blank'>TRY PRO LIVE DEMO NOW</a></span>";
$menuHTML .= "</div>";
$menuHTML .= "</div>";
$menuHTML .= "<div class='msg' id = 'showMsg' style = 'display:none;'></div>";
$menuHTML .= "<input type='hidden' id='current_url' name='current_url' value='" . get_admin_url() . "admin.php?page=" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/index.php&__module=" . $_REQUEST['__module'] . "&step=uploadfile'/>";
$menuHTML .= "<input type='hidden' name='checkmodule' id='checkmodule' value='" . $_REQUEST['__module'] . "' />";
echo $menuHTML;
