<?php
/*********************************************************************************
 * WordPress ultimate CSV Importer is a Tool for importing CSV for the Wordpress
 * plugin developed by Smackcoder. Copyright (C) 2013 Smackcoders.
 *
 * WordPress ultimate CSV Importer is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License version 3 as
 * published by the Free Software Foundation with the addition of the following
 * permission added to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE
 * COVERED WORK IN WHICH THE COPYRIGHT IS OWNED BY WordPress ultimate CSV Importer,
 * WordPress ultimate CSV Importer DISCLAIMS THE WARRANTY OF NON INFRINGEMENT OF THIRD
 * PARTY RIGHTS.
 *
 * WordPress ultimate CSV Importer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for
 * more details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact Smackcoders at email address info@smackcoders.com.
 *
 * The interactive user interfaces in original and modified versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the WordPress ultimate
 * CSV Importer copyright notice. If the display of the logo is not reasonably feasible
 * for technical reasons, the Appropriate Legal Notices must display the words
 * "Copyright Smackcoders. 2013. All rights reserved".
 ********************************************************************************/

class RenderCSVCE
{

    /**
     * Render dashboard action
     */
    function setDashboardAction()
    {
        return '<div id = "requestaction" type = "hidden" value = "dashboard">';
    }

    /**
     * Shows status message
     */
    function showMessage($status, $message)
    {
        return "<div class = \"$status msg\"> $message</div>";
    }

    /**
     * Function to display the plugin home description
     */
    function renderSettings()
    {
        $selected_var = array('disable', 'enable', 'yoastseo', 'aioseo', 'nonerseooption', 'cctm', 'custompostuitype', 'wpcommerce', 'eshop', 'users', 'automapping', 'utfsupport', 'categories', 'customtaxonomy', 'comments', 'ecommerce', 'woocommerce', 'acf');
        foreach ($selected_var as $single_selected_var) {
            $$single_selected_var = "";
        }

        $impCESett = new SmackImpCE();
        $settobj = new IMPSettings();
        $sett = $settobj->getSettings();
        foreach ($sett as $key)
            $$key = 'checked';

        $cctmtd = $this->getPluginState('custom-content-type-manager/index.php');
        $cptutd = $this->getPluginState('custom-post-type-ui/custom-post-type-ui.php');
        $eshoptd = $this->getPluginState('eshop/eshop.php');
        $wpcomtd = $this->getPluginState('wp-e-commerce/wp-shopping-cart.php');
	$woocomtd = $this->getPluginState('woocommerce/woocommerce.php');
        $aioseotd = $this->getPluginState('all-in-one-seo-pack/all_in_one_seo_pack.php');
        $yoasttd = $this->getPluginState('wordpress-seo/wp-seo.php');
        $cateicontd = $this->getPluginState('category-icons/category_icons.php');
        $wecftd = $this->getPluginState('wp-e-commerce-custom-fields/custom-fields.php');
        $acftd = $this->getPluginState('advanced-custom-fields/acf.php');

        $cctmtdi = $this->getPluginStateImg('custom-content-type-manager/index.php');
        $cptutdi = $this->getPluginStateImg('custom-post-type-ui/custom-post-type-ui.php');
        $eshoptdi = $this->getPluginStateImg('eshop/eshop.php');
        $wpcomtdi = $this->getPluginStateImg('wp-e-commerce/wp-shopping-cart.php');
	$woocomtdi = $this->getPluginStateImg('woocommerce/woocommerce.php');
        $aioseotdi = $this->getPluginStateImg('all-in-one-seo-pack/all_in_one_seo_pack.php');
        $yoasttdi = $this->getPluginStateImg('wordpress-seo/wp-seo.php');
        $cateicontdi = $this->getPluginStateImg('category-icons/category_icons.php');
        $cateicontdi = $this->getPluginStateImg('category-icons/category_icons.php');
        $acftdi = $this->getPluginStateImg('advanced-custom-fields/acf.php');

        if (!$ecommerce)
            $ecommercedisabled = 'disabled';

        $setString = "<div class = 'settingscontainer'><form class=\"add:the-list: validate\" method=\"post\" enctype=\"multipart/form-data\">";
        $setString .= "<div class='upgradetopro' id='upgradetopro' style='display:none;'>This feature is only available in Pro Version, Please <a href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html' target='_blank' >UPGRADE TO PRO</a></div>";
        $setString .= "<table>";

        $setString .= '<tr><td><a href="javascript:slideonlyone(\'featuresBox\',\'' . WP_CONTENT_URL . '\');" id="myHead2" class="smackhelpswitcher_anchor">
                        <div class="smackhelpswitcher">' . $impCESett->t('FEATURE') . '<img src="' . WP_CONTENT_URL . '/plugins/wp-ultimate-csv-importer/images/arrow_up.gif" id="featuresBox_img" class="smackhelpswitcher_img"></div></a></td></tr><tr><td>';
        $setString .= "<tr><td><div id='featuresBox' class = 'switchercontent newboxes2'><table>";
        $setString .= "<tr><td><label class=$automapping><input type='checkbox' name='automapping' value='automapping' onclick='savePluginSettings()' " . $automapping . ">" . $impCESett->t('ENABLEAUTOMAPPING') . "</label></td>";
        $setString .= "<td><label class=$utfsupport><input type='checkbox' name='rutfsupport' value='utfsupport' onclick='savePluginSettings()' " . $utfsupport . ">" . $impCESett->t('ENABLEUTFSUPPORT') . "</label></td></tr></table></div></td></tr>";


        $setString .= '<tr><td><a href="javascript:slideonlyone(\'moduleBox\',\'' . WP_CONTENT_URL . '\');" id="myHead2" class="smackhelpswitcher_anchor">
                        <div class="smackhelpswitcher">Modules<img src="' . WP_CONTENT_URL . '/plugins/wp-ultimate-csv-importer/images/arrow_up.gif" id="featuresBox_img" class="smackhelpswitcher_img"></div></a></td></tr><tr><td>';
        $setString .= "<div id = 'moduleBox' class = 'switchercontent newboxes2'><table><tr><td><ul>";
        $setString .= "<li><label class='checked'><input type='checkbox' name='post' value='post' onclick='savePluginSettings()' disabled checked>" . $impCESett->t('POST') . "</label>";
        $setString .= "<label class='checked'><input type='checkbox' name='custompost' value='custompost' onclick='savePluginSettings()' disabled checked>" . $impCESett->t('CUSTOMPOST') . "</label>";
        $setString .= "<label class='checked'><input type='checkbox' name='page' value='page' onclick='savePluginSettings()' disabled checked>" . $impCESett->t('PAGE') . '</label>';
        $setString .= "<label class=\"$comments\"><input type='checkbox' name='comments' value='comments' onclick='savePluginSettings()' " . $comments . ">" . $impCESett->t('COMMENTS') . "</label></li>";
        $setString .= "<li><label class=$categories><input type='checkbox' name='categories' value='categories' onclick='savePluginSettings()' " . $categories . " >Categories/Tags</label>";
        $setString .= "<label class=$customtaxonomy><input type='checkbox' name='customtaxonomy' value='customtaxonomy' onclick='savePluginSettings()' " . $customtaxonomy . " >Custom Taxonomy</label>";
        $setString .= "<label class=$users><input type='checkbox' name='users' value='users' onclick='savePluginSettings()' " . $users . ">Users/Roles</label></li>";
        $setString .= "</ul></td></tr></table></div></td></tr>";


        $setString .= '<tr><td><a href="javascript:slideonlyone(\'thirdPartyBox\',\'' . WP_CONTENT_URL . '\');" id="myHead2" class="smackhelpswitcher_anchor">
                        <div class="smackhelpswitcher">' . $impCESett->t('THIRDPARTY') . '<img src="' . WP_CONTENT_URL . '/plugins/wp-ultimate-csv-importer/images/arrow_up.gif" id="featuresBox_img" class="smackhelpswitcher_img"></div></a></td></tr><tr><td>';
        $setString .= "<tr><td><div id='thirdPartyBox' class = 'switchercontent newboxes2'><table>";
        $setString .= "<tr><td><b>Ecommerce</b></td></tr>";
        $setString .= "<tr><td><label class=$nonerecommerce><input type = 'radio' name ='recommerce' value='nonerecommerce' onclick='savePluginSettings()' " . $nonerecommerce . " class='ecommerce'>" . $impCESett->t('NONE') . "</label></td>";
        $setString .= "<td><label class=\"$eshoptd $eshop\"><input type='radio' name='recommerce' value='eshop' onclick='enablewpcustomfield(\"none\")' " . $eshop . "  " . " class='ecommerce'>Eshop</label></td><td><label class=\"$wpcomtd $wpcommerce\"><input type='radio' name='recommerce' value='wpcommerce' onclick='enablewpcustomfield(\"wpcustomfields\")' " . $wpcommerce . "  class = 'ecommerce'>WP e-Commerce</label></td><td><label class=\"$woocomtd $woocommerce\"><input type='radio' name='recommerce' value='woocommerce' ".$woocommerce."  class = 'woocommerce' onclick='enablewpcustomfield(\"none\")'>WooCommerce</label></td></tr>";
	$chkwecfstate = get_option('wpcsvprosettings');
                if($chkwecfstate['recommerce'] == 'wpcommerce'){
                        $setString .= "<tr id='wpcustomfieldstr'><td></td><td></td><td><input type='checkbox' name='wpcustomfields' id='wpcustomfields' onclick='savePluginSettings()' checked />WP e-Commerce Custom Fields</td></tr>";
                }else{
                        $setString .= "<tr id='wpcustomfieldstr' style='display:none;'><td></td><td></td><td><input type='checkbox' name='wpcustomfields' id='wpcustomfields' onclick='savePluginSettings()' />WP e-Commerce Custom Fields</td></tr>";
                }
        $setString .= "<tr><td><b>" . $impCESett->t('CUSTOMPOST') . "</b></td></tr>";
        $setString .= "<tr><td><label class=$nonercustompost><input type = 'radio' name ='rcustompost' value='nonercustompost' onclick='savePluginSettings()' " . $nonercustompost . " class='ecommerce'>" . $impCESett->t('NONE') . "</label></td>";
        $setString .= "<td><label class=\"$cptutd $custompostuitype\"><input type ='radio' name = 'rcustompost' value='custompostuitype' onclick='savePluginSettings()' " . $custompostuitype . ">" . $impCESett->t('CUSTOMPOSTTYPE') . "</label></td>";
        $setString .= "<td><label class=\"$cctmtd $cctm\"><input type ='radio' name = 'rcustompost' value='cctm' onclick='savePluginSettings()' " . $cctm . ">" . $impCESett->t('CCTM') . "</label></td>";
	$setString .= "<td><label class=\"$acftd $acf\"><input type ='checkbox' name = 'rcustomfield' value='acf' ".$acf.">".$impCESett->t('ACF')."</label>"."</td></tr>";
        $setString .= "<tr><td><b>" . $impCESett->t('SEO_OPTIONS') . "</b></td></tr>";
        $setString .= "<tr><td><label class=$nonerseooption><input type = 'radio' name ='rseooption' value='nonerseooption' onclick='savePluginSettings()' " . $nonerseooption . " class='ecommerce'>" . $impCESett->t('NONE') . "</label></td>";
        $setString .= "<td><label class=\"$aioseotd $aioseo\"><input type ='radio' name = 'rseooption' value='aioseo' onclick='savePluginSettings()' " . $aioseo . ">" . $impCESett->t('ALLINONESEO') . "</label></td>";
        $setString .= "<td><label class=\"$yoasttd $yoastseo\"><input type ='radio' name = 'rseooption' value='yoastseo' onclick='savePluginSettings()' " . $yoastseo . ">" . $impCESett->t('YOASTSEO') . "</label></td></tr>";
        $setString .= "<tr><td><b>" . $impCESett->t('CATEGORY_ICONS') . "</b></td></tr>";
        $setString .= "<tr><td>" . $impCESett->t('PLUGINCHECK') . "</td></tr>";
        $setString .= "<tr><td><label class=$enable><input type = 'radio' name ='rcateicons' value='enable' onclick='savePluginSettings()' " . $enable . " class='ecommerce'>" . $impCESett->t('ENABLE_CATEGOTY_ICONS') . "</label></td>";
        $setString .= "<td><label><input type ='radio' name = 'rcateicons' value='disable' onclick='savePluginSettings()' " . $disable . ">" . $impCESett->t('DISABLE_CATEGORY_ICONS') . "</label></td></tr>";
        $setString .= "</table></div></td></tr>";
        $setString .= "<tr></tr></table><input type='button' class='action' name='savesettings' value='Save' style='float:left;' onclick='savePluginSettings()' /></form></div>";
        return $setString;
    }

    /**
     * Render description for each modules
     */
    function renderDesc()
    {
        return "<p>WP Ultimate CSV Importer Plugin helps you to manage the post,page and </br> custom post data's from a CSV file.</p>
		<p>1. Admin can import the data's from any csv file.</p>
		<p>2. Can define the type of post and post status while importing.</p>
		<p>3. Provides header mapping feature to import the data's as your need.</p>
		<p>4. Users can map coloumn headers to existing fields or assign as custom fileds.</p>
		<p>5. Import unlimited datas as post.</p>
		<p>6. Make imported post as published or make it as draft.</p>
		<p>7. Added featured image import functionality.</p>
		<p><b> Important Note:- </b></p>
		<p><span style='color:red;'>1. Your csv should have the seperate column for post_date.
		<br/>2. It must be in the following format. ( yyyy-mm-dd hh:mm:ss ).</span></p>
		<p>Configuring our plugin is as simple as that. If you have any questions, issues and request on new features, plaese visit <a href='http://www.smackcoders.com/blog/category/free-wordpress-plugins' target='_blank'>Smackcoders.com blog </a></p>
		<div align='center' style='margin-top:40px;'> 'While the scripts on this site are free, donations are greatly appreciated. '<br/><br/><a href='http://www.smackcoders.com/donate.html' target='_blank'><img src='" . WP_CONTENT_URL . "/plugins/wp-ultimate-csv-importer/images/paypal_donate_button.png' /></a><br/><br/><a href='http://www.smackcoders.com/' target='_blank'><img src='http://www.smackcoders.com/wp-content/uploads/2012/09/Smack_poweredby_200.png'></a>
		</div><br/>";
    }

    /**
     * Render menu for the plugin
     */
    function renderMenu()
    {
        $nav_var = array('post', 'page', 'custompost', 'settings', 'dashboard');
        foreach ($nav_var as $single_navvar) {
            $$single_navvar = "";
        }

        $impSet = new IMPSettings();
        $settings = $impSet->getSettings();
        $impCEM = new SmackImpCE();
        foreach ($settings as $key)
            $$key = true;
        if (isset($_POST['post_csv']) && $_POST['post_csv'] == 'Import')
            $dashboard = 'selected';
        else {
            $action = $_REQUEST['action'];
            $$action = 'selected';
        }

        if (!$_REQUEST['action'])
            $dashboard = 'selected';
        $menuHTML = "<div class='csv-top-navigation-wrapper' id='header' name='mainNavigation'><ul id='topNavigation'>";
        $menuHTML .= "<li class=\"navigationMenu $post\" style='margin-left:0px;'><a href = 'admin.php?page=upload_csv_file&action=post' class = 'navigationMenu-link' id='module1'>" . $impCEM->t('POST') . "</a></li>";
        $menuHTML .= "<li class=\"navigationMenu $page\"><a href = 'admin.php?page=upload_csv_file&action=page' class = 'navigationMenu-link' id='module1'>" . $impCEM->t("PAGE") . "</a></li>";
        $menuHTML .= "<li class=\"navigationMenu $custompost\"><a href = 'admin.php?page=upload_csv_file&action=custompost' class = 'navigationMenu-link' id = 'module2'>" . $impCEM->t('CUSTOMPOST') . "</a></li>";
        $menuHTML .= "<li class=\"navigationMenu $settings\"><a href = 'admin.php?page=upload_csv_file&action=settings' class='navigationMenu-link' id='module9'>" . $impCEM->t('SETTINGS') . "</a></li>";
        $menuHTML .= "<li class=\"navigationMenu $dashboard\"><a href = 'admin.php?page=upload_csv_file&action=dashboard' class='navigationMenu-link' id='module0'>" . $impCEM->t('DASHBOARD') . "</a></li>";
        $menuHTML .= "</ul></div> <div style='margin-top:-55px;float:right;margin-right:300px'><a href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html' target='_blank'><input type='button' class='button-primary' name='Upgradetopro' id='Upgradetopro' value='Upgrade To PRO' /></a></div> <div class='msg' id = 'showMsg' style = 'display:none;'></div>";
        return $menuHTML;

    }

    /**
     * Render post/page section
     */
    function renderPostPage()
    {
        $impCE = new SmackImpCE ();
        $postForm = ' <div style="float: left; margin-top: 11px; margin-right: 5px;"><img src = "' . WP_CONTENT_URL . '/plugins/wp-ultimate-csv-importer/images/Importicon_24.png"></div><div style="float:left;"><h2>' . $impCE->t('IMPORT_CSV_FILE') . '</h2></div></br></br><form class="add:the-list: validate" method="post"enctype="multipart/form-data" onsubmit="return file_exist();"><table class="importform">
                                <tr><td><label for="csv_import" class="uploadlabel" >' . $impCE->t('UPLOAD_FILE') . '<span class="mandatory"> *</span></label>
				<input type="hidden" value="' . WP_CONTENT_URL . '" id="contenturl" />
                                <input name="csv_import" id="csv_import" class="btn" type="file" value="" />

                        </td></tr>';

        if (($_REQUEST['action'] == 'post') || ($_REQUEST['action'] == 'custompost') || ($_REQUEST['action'] == 'page')) {

            $postForm .= '<tr style="display:block;" id="detect"><td><label class="detectDup"><input type="checkbox" name="titleduplicatecheck" value=1> ' . $impCE->t("ENABLE_DUPLICATION_POST_TITLE") . '</label></td><td><label class="detectDup"><input type="checkbox" name="contentduplicatecheck" value=1>  ' . $impCE->t("ENABLE_DUPLICATION_POST_CONTENT") . '</label></td></tr>';

        }
        $postForm .= '<tr><td><label class="uploadlabel">Delimiter</label><select name="delim" id="delim">
                                        <option value=",">,</option>
                                        <option value=";">;</option>
                                </select></td></tr>
			</table>
                        <p>
                                <button type="submit" class="action addmarginright" name="Import" value="Import" align="right" onclick = "return validateFirstForm();"> Import</button>
                        </p>
                </form></br></br>';
        return $postForm;
    }

    /**
     *return state of plugins - absent,present and active
     */
    function getPluginStateImg($plugin)
    {
        $state = '<img src ="' . WP_CONTENT_URL . '/plugins/wp-ultimate-csv-importer/images/notdetected.png" class = "settingsicon">';
        $settobj = new IMPSettings();
        if ($settobj->isPluginPresent($plugin))
            $state = '<img src ="' . WP_CONTENT_URL . '/plugins/wp-ultimate-csv-importer/images/notactive.png" class = "settingsicon">';
        if ($settobj->isPluginActive($plugin))
            $state = '<img src ="' . WP_CONTENT_URL . '/plugins/wp-ultimate-csv-importer/images/installed.png" class = "settingsicon">';
        return $state;
    }


    /**
     * Function to render the dashboard
     */
    function renderDashboard()
    {
        require_once('stats.php');
    }

    /**
     *return state of plugins - absent,present and active
     */
    function getPluginState($plugin)
    {
        $state = 'pluginAbsent';
        $settobj = new IMPSettings();
        if ($settobj->isPluginPresent($plugin))
            $state = 'pluginPresent';
        if ($settobj->isPluginActive($plugin))
            $state = 'pluginActive';
        return $state;
    }
}
?>
