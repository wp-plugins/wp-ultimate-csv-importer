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

class SettingsActions extends SkinnyActions
{
	public $activePlugins = array();
	public function __construct()
	{
		$this->activePlugins = get_option('active_plugins');
	}

	/**
	 * The actions index method
	 * @param array $request
	 * @return array
	 */
	public function executeIndex($request)
	{
		// return an array of name value pairs to send data to the template
		$data = array();
		$data['savesettings'] = 'notdone';
		if(isset($_POST['savesettings'])){ 
			update_option('wpcsvfreesettings',$_POST);
			$data['savesettings'] = 'done';
		}
		$setingsArr = array('post', 'page', 'custompost', 'comments', 'categories', 'customtaxonomy', 'users', 'eshop', 'wpcommerce', 'woocommerce', 'custompostuitype', 'cctm', 'acf', 'aioseo', 'yoastseo', 'enable', 'disable', 'nonerseooption', 'nonercustompost', 'nonerecommerce', 'recommerce','enable_plugin_access_for_author', 'send_log_email', 'enable_debug', 'disable_debug', 'debug_mode');
		foreach($setingsArr as $option)
			$data[$option] = "";

		$skinnycontroller = new WPImporter_includes_helper();
		$settings = $skinnycontroller->getSettings(); 
		foreach($settings as $settings_key)
			$data[$settings_key] = 'checked';

//Settings action
//SEO option
                $tableseo = get_option('wpcsvfreesettings');
                $seooption = $tableseo['rseooption'];
                if ( $seooption == 'aioseo' ) {
                        $data['aioseo'] = 'checked enablesetting';
                        $data['yoastseo'] = 'disablesetting';
                        $data['nonerseooption'] = 'disablesetting';
                        $data['aioseo_status'] = 'Enabled';
                        $data['yoastseo_status'] = 'Disabled';
                        $data['none_status']= 'Disabled';
                }
                else if ( $seooption == 'yoastseo' ) {
                        $data['yoastseo'] = 'checked enablesetting';
                        $data['aioseo'] = 'disablesetting';
                        $data['nonerseooption'] = 'disablesetting';
                        $data['aioseo_status'] = 'Disabled';
                        $data['yoastseo_status'] = 'Enabled';
                        $data['none_status'] = 'Disabled';
                }
                else {
                        $data['nonerseooption'] = 'checked enablesetting';
                        $data['aioseo'] = 'disablesetting';
                        $data['yoastseo'] = 'disablesetting';
                        $data['aioseo_status'] = 'Disabled';
                        $data['yoastseo_status'] = 'Disabled';
                        $data['none_status'] = 'Enabled';
                }

                $data['wpcustomfields'] = '';
                if(isset($tableseo['wpcustomfields']) && $tableseo['wpcustomfields'] == 'on') {
                        $data['wpcustomfields'] = 'checked';
                }
//Security and Performance
                if(isset($tableseo['enable_plugin_access_for_author'])) {
                        $importoption = $tableseo['enable_plugin_access_for_author'];
                } else {
                        $importoption = '';
                };
                if ( $importoption == 'enable_plugin_access_for_author' ) {
                        $data['authorimport'] = 'checked enablesetting';
                        $data['noauthorimport'] = 'disablesetting';
                }
                else {
                        $data['noauthorimport'] = 'checked enablesetting';
                        $data['authorimport'] = 'disablesetting';
                }

//General Settings
                if(isset($tableseo['post'])) {
                        $importoption = $tableseo['post'];
                } else {
                        $importoption = '';
                };
                if ( $importoption == 'post' ) {
                        $data['post'] = 'checked enablesetting';
                        $data['nopost'] = 'disablesetting';
                }
                else {
                        $data['nopost'] = 'checked enablesetting';
                        $data['post'] = 'disablesetting';
                }

                if(isset($tableseo['page'])) {
                        $importoption = $tableseo['page'];
                } else {
                        $importoption = '';
                };
                if ( $importoption == 'page' ) {
                        $data['page'] = 'checked enablesetting';
                        $data['nopage'] = 'disablesetting';
		}
                else {
                        $data['nopage'] = 'checked enablesetting';
                        $data['page'] = 'disablesetting';
                }

                if(isset($tableseo['users'])) {
                        $importoption = $tableseo['users'];
                } else {
                        $importoption = '';
                };
                if ( $importoption == 'users' ) {
                        $data['users'] = 'checked enablesetting';
                        $data['nousers'] = 'disablesetting';
                }
                else {
                        $data['nousers'] = 'checked enablesetting';
                        $data['users'] = 'disablesetting';
                }

                if(isset($tableseo['comments'])) {
                        $importoption = $tableseo['comments'];
                } else {
                        $importoption = '';
                };
                if ( $importoption == 'comments' ) {
                        $data['comments'] = 'checked enablesetting';
                        $data['nocomments'] = 'disablesetting';
                }
                else {
                        $data['nocomments'] = 'checked enablesetting';
                        $data['comments'] = 'disablesetting';
                }

                if(isset($tableseo['custompost'])) {
                        $importoption = $tableseo['custompost'];
                } else {
			$importoption = '';
                };
                if ( $importoption == 'custompost' ) {
                        $data['custompost'] = 'checked enablesetting';
                        $data['nocustompost'] = 'disablesetting';
                }
                else {
                        $data['nocustompost'] = 'checked enablesetting';
                        $data['custompost'] = 'disablesetting';
                }

                if(isset($tableseo['customtaxonomy'])) {
                        $importoption = $tableseo['customtaxonomy'];
                } else {
                        $importoption = '';
                };
                if ( $importoption == 'customtaxonomy' ) {
                        $data['customtaxonomy'] = 'checked enablesetting';
                        $data['nocustomtaxonomy'] = 'disablesetting';
                }
                else {
                        $data['nocustomtaxonomy'] = 'checked enablesetting';
                        $data['customtaxonomy'] = 'disablesetting';
                }

                if(isset($tableseo['categories'])) {
                        $importoption = $tableseo['categories'];
                } else {
                        $importoption = '';
                };
                if ( $importoption == 'categories' ) {
                        $data['categories'] = 'checked enablesetting';
                        $data['nocategories'] = 'disablesetting';
                }
                else {
                        $data['nocategories'] = 'checked enablesetting';
			$data['categories'] = 'disablesetting';
                }

                if(isset($tableseo['rcustomerreviews'])) {
                        $importoption = $tableseo['rcustomerreviews'];
                } else {
                        $importoption = '';
                };
                if ( $importoption == 'customerreviews' ) {
                        $data['customerreviews'] = 'checked enablesetting';
                        $data['nocustomerreviews'] = 'disablesetting';
                }
                else {
                        $data['nocustomerreviews'] = 'checked enablesetting';
                        $data['customerreviews'] = 'disablesetting';
                }

		// Debug mode enable / disable
		if(isset($tableseo['debug_mode'])) {
			$debug_mode = $tableseo['debug_mode'];
		} else {
			$debug_mode = '';
		}
		if($debug_mode == 'enable_debug') {
			$data['debugmode_enable'] = 'checked enablesetting';
			$data['debugmode_disable'] = 'disablesetting';
		} else if($debug_mode == 'disable_debug') {
                        $data['debugmode_enable'] = 'disablesetting'; 
                        $data['debugmode_disable'] = 'checked enablesetting';
		}

//Custom Fields
                #$wpmemberoption = $tableseo['rwpmembers'];
                if (isset($tableseo['rwpmembers']) && $tableseo['rwpmembers'] == 'wpmembers' ) {
                        $data['checkuser'] = 'checked enablesetting';
                        $data['uncheckuser'] = 'disablesetting';
                }
                else {
                        $data['uncheckuser'] = 'checked enablesetting';
                        $data['checkuser'] = 'disablesetting';
                }
                #$customfieldoption = $tableseo['rcustomfield'];
                if ( isset($tableseo['rcustomfield']) && $tableseo['rcustomfield'] == 'acf' ) {
                        $data['acf'] = 'checked enablesetting';
                        $data['cctmcustfields'] = 'disablesetting';
                        $data['wptypescustfields'] = 'disablesetting';
                        $data['podscustomfields'] = 'disablesetting';
			$data['acf_status'] = 'Enabled';
                        $data['cctmfield_status'] = 'Disabled';
                        $data['typesfield_status'] = 'Disabled';
                        $data['podsfield_status'] = 'Disabled';
                }
                else if ( isset($tableseo['rcustomfield']) && $tableseo['rcustomfield'] == 'cctmcustfields' ) {
                        $data['cctmcustfields'] = 'checked enablesetting';
                        $data['acf'] = 'disablesetting';
                        $data['wptypescustfields'] = 'disablesetting';
                        $data['podscustomfields'] = 'disablesetting';
                        $data['acf_status'] = 'Disabled';
                        $data['cctmfield_status'] = 'Enabled';
                        $data['typesfield_status'] = 'Disabled';
                        $data['podsfield_status'] = 'Disabled';
                }
                else if ( isset($tableseo['rcustomfield']) && $tableseo['rcustomfield'] == 'wptypescustfields' ) {
                        $data['wptypescustfields'] = 'checked enablesetting';
                        $data['acf'] = 'disablesetting';
                        $data['cctmcustfields'] = 'disablesetting';
                        $data['podscustomfields'] = 'disablesetting';
                        $data['acf_status'] = 'Disabled';
                        $data['cctmfield_status'] = 'Disabled';
                        $data['typesfield_status'] = 'Enabled';
                        $data['podsfield_status'] = 'Disabled';
                }
                else if ( isset($tableseo['rcustomfield']) && $tableseo['rcustomfield'] == 'podscustomfields' ) {
                        $data['podscustomfields'] = 'checked enablesetting';
                        $data['acf'] = 'disablesetting';
                        $data['cctmcustfields'] = 'disablesetting';
                        $data['wptypescustfields'] = 'disablesetting';
                        $data['acf_status'] = 'Disabled';
                        $data['cctmfield_status'] = 'Disabled';
                        $data['typesfield_status'] = 'Disabled';
                        $data['podsfield_status'] = 'Enabled';
                }
                else  {
                        $data['podscustomfields'] = 'disablesetting';
			$data['acf'] = 'disablesetting';
                        $data['cctmcustfields'] = 'disablesetting';
                        $data['wptypescustfields'] = 'disablesetting';
                        $data['acf_status'] = 'Disabled';
                        $data['cctmfield_status'] = 'Disabled';
                        $data['typesfield_status'] = 'Disabled';
                        $data['podsfield_status'] = 'Disabled';
                }

//Custom post
                $tablecustompost = get_option('wpcsvfreesettings');
                $customoption = $tablecustompost['rcustompost'];
                if ( $customoption == 'custompostuitype' ) {
                        $data['custompostuitype'] = 'checked enablesetting';
                        $data['wptypes'] = 'disablesetting';
                        $data['cctm'] = 'disablesetting';
                        $data['podspost'] = 'disablesetting';
                        $data['nonercustompost'] = 'disablesetting';
                        $data['default_status'] = 'Disabled';
                        $data['cptui_status'] = 'Enabled';
                        $data['wptypes_status'] = 'Disabled';
                        $data['cctm_status'] = 'Disabled';
                        $data['podspost_status'] = 'Disabled';
                }
                else if ( $customoption == 'wptypes' ) {
                        $data['wptypes'] = 'checked enablesetting';
                        $data['custompostuitype'] = 'disablesetting';
                        $data['cctm'] = 'disablesetting';
                        $data['podspost'] = 'disablesetting';
                        $data['nonercustompost'] = 'disablesetting';
                        $data['default_status'] = 'Disabled';
                        $data['cptui_status'] = 'Disabled';
                        $data['wptypes_status'] = 'Enabled';
                        $data['cctm_status'] = 'Disabled';
                        $data['podspost_status'] = 'Disabled';
                }
                else if ( $customoption == 'cctm' ) {
			$data['cctm'] = 'checked enablesetting';
                        $data['wptypes'] = 'disablesetting';
                        $data['custompostuitype'] = 'disablesetting';
                        $data['podspost'] = 'disablesetting';
                        $data['nonercustompost'] = 'disablesetting';
                        $data['default_status'] = 'Disabled';
                        $data['cptui_status'] = 'Disabled';
                        $data['wptypes_status'] = 'Disabled';
                        $data['cctm_status'] = 'Enabled';
                        $data['podspost_status'] = 'Disabled';
                }
                else if ( $customoption == 'podspost' ) {
                        $data['podspost'] = 'checked enablesetting';
                        $data['cctm'] = 'disablesetting';
                        $data['wptypes'] = 'disablesetting';
                        $data['custompostuitype'] = 'disablesetting';
                        $data['nonercustompost'] = 'disablesetting';
                        $data['default_status'] = 'Disabled';
                        $data['cctm_status'] = 'Disabled';
                        $data['cptui_status'] = 'Disabled';
                        $data['wptypes_status'] = 'Disabled';
                        $data['podspost_status'] = 'Enabled';
                }
                else {
                        $data['nonercustompost'] = 'checked enablesetting';
                        $data['cctm'] = 'disablesetting';
                        $data['wptypes'] = 'disablesetting';
                        $data['podspost'] = 'disablesetting';
                        $data['custompostuitype'] = 'disablesetting';
                        $data['default_status'] = 'Enabled';
                        $data['cptui_status'] = 'Disabled';
                        $data['wptypes_status'] = 'Disabled';
                        $data['cctm_status'] = 'Disabled';
                        $data['podspost_status'] = 'Disabled';
               }
//Additional Settings
                $scheduleoption = $tableseo['send_log_email'];
                if ( $scheduleoption == 'send_log_email' ) {
                        $data['schedulelog'] = 'checked enablesetting';
                        $data['schedulenolog'] = 'disablesetting';
                }
                else {
                        $data['schedulenolog'] = 'checked enablesetting';
                        $data['schedulelog'] = 'disablesetting';
                }

                $categoryoption = $tableseo['rcateicons'];
                if ( $categoryoption == 'enable' ) {
                        $data['catyenable'] = 'checked enablesetting';
                        $data['catydisable'] = 'disablesetting';
                        $data['catyenablestatus'] = 'checked';
                        $data['catydisablestatus'] = '';
                }
                else {
                        $data['catydisable'] = 'checked enablesetting';
                        $data['catyenable'] = 'disablesetting';
                        $data['catyenablestatus'] = '';
                        $data['catydisablestatus'] = 'checked';
                }

                $dropoption = $tableseo['drop_table'];
                if ( $dropoption == 'on' ) {
                        $data['drop_on'] = 'checked enablesetting';
                        $data['drop_off'] = 'disablesetting';
                        $data['dropon_status'] = 'checked';
                        $data['dropoff_status'] = '';
                }
                else {
                        $data['drop_off'] = 'checked enablesetting';
                        $data['drop_on'] = 'disablesetting';
                        $data['dropon_status'] = '';
                        $data['dropoff_status'] = 'checked';
                }
//Eccommerce option
        $ecommerceoption = $tableseo['recommerce'];
                if ( $ecommerceoption == 'eshop' ) {
                        $data['eshop'] = 'checked enablesetting';
                        $data['marketpress'] = 'disablesetting';
                        $data['woocommerce'] = 'disablesetting';
                        $data['wpcommerce'] = 'disablesetting';
                        $data['nonerecommerce'] = 'disablesetting';

                        $data['eshop_status'] = 'Enabled';
                        $data['marketpress_status'] = 'Disabled';
                        $data['woocommerce_status'] = 'Disabled';
                        $data['wpcommerce_status'] = 'Disabled';
                        $data['ecomnone_status'] = 'Disabled';
                }
                else if ( $ecommerceoption == 'marketpress' ) {
                        $data['marketpress'] = 'checked enablesetting';
                        $data['eshop'] = 'disablesetting';
                        $data['woocommerce'] = 'disablesetting';
                        $data['wpcommerce'] = 'disablesetting';
                        $data['nonerecommerce'] = 'disablesetting';

                        $data['eshop_status'] = 'Disabled';
                        $data['marketpress_status'] = 'Enabled';
                        $data['woocommerce_status'] = 'Disabled';
                        $data['wpcommerce_status'] = 'Disabled';
                        $data['ecomnone_status'] = 'Disabled';
                }
                else if ( $ecommerceoption == 'woocommerce' ) {
                        $data['woocommerce'] = 'checked enablesetting';
                        $data['marketpress'] = 'disablesetting';
                        $data['eshop'] = 'disablesetting';
                        $data['wpcommerce'] = 'disablesetting';
                        $data['nonerecommerce'] = 'disablesetting';

                        $data['eshop_status'] = 'Disabled';
                        $data['marketpress_status'] = 'Disabled';
                        $data['woocommerce_status'] = 'Enabled';
			$data['wpcommerce_status'] = 'Disabled';
                        $data['ecomnone_status'] = 'Disabled';
                }
                else if ( $ecommerceoption == 'wpcommerce' ) {
                        $data['wpcommerce'] = 'checked enablesetting';
                        $data['marketpress'] = 'disablesetting';
                        $data['woocommerce'] = 'disablesetting';
                        $data['eshop'] = 'disablesetting';
                        $data['nonerecommerce'] = 'disablesetting';

                        $data['eshop_status'] = 'Disabled';
                        $data['marketpress_status'] = 'Disabled';
                        $data['woocommerce_status'] = 'Disabled';
                        $data['wpcommerce_status'] = 'Enabled';
                        $data['ecomnone_status'] = 'Disabled';
                }
                else {
                        $data['nonerecommerce'] = 'checked enablesetting';
                        $data['wpcommerce'] = 'disablesetting';
                        $data['marketpress'] = 'disablesetting';
                        $data['woocommerce'] = 'disablesetting';
                        $data['eshop'] = 'disablesetting';

                        $data['eshop_status'] = 'Disabled';
                        $data['marketpress_status'] = 'Disabled';
			$data['woocommerce_status'] = 'Disabled';
                        $data['wpcommerce_status'] = 'Disabled';
                        $data['ecomnone_status'] = 'Enabled';
                }


		$data['cctmtd'] = $this->getpluginstate('custom-content-type-manager/index.php');
		$data['cptutd'] = $this->getpluginstate('custom-post-type-ui/custom-post-type-ui.php');
		$data['eshoptd'] = $this->getpluginstate('eshop/eshop.php');
		$data['wpcomtd'] = $this->getpluginstate('wp-e-commerce/wp-shopping-cart.php');
		$data['woocomtd'] = $this->getpluginstate('woocommerce/woocommerce.php');
		$data['aioseotd'] = $this->getpluginstate('all-in-one-seo-pack/all_in_one_seo_pack.php');
		$data['yoasttd'] = $this->getpluginstate('wordpress-seo/wp-seo.php');
		$data['cateicontd'] = $this->getpluginstate('category-icons/category_icons.php');
		$data['wecftd'] = $this->getpluginstate('wp-e-commerce-custom-fields/custom-fields.php');
		$data['acftd'] = $this->getpluginstate('advanced-custom-fields/acf.php');
	
		$data['cctmtdi'] = $this->getpluginstate('custom-content-type-manager/index.php');
		$data['cptutdi'] = $this->getpluginstate('custom-post-type-ui/custom-post-type-ui.php');
		$data['eshoptdi'] = $this->getpluginstate('eshop/eshop.php');
		$data['wpcomtdi'] = $this->getpluginstate('wp-e-commerce/wp-shopping-cart.php');
		$data['woocomtdi'] = $this->getpluginstate('woocommerce/woocommerce.php');
		$data['aioseotdi'] = $this->getpluginstate('all-in-one-seo-pack/all_in_one_seo_pack.php');
		$data['yoasttdi'] = $this->getpluginstate('wordpress-seo/wp-seo.php');
		$data['cateicontdi'] = $this->getpluginstate('category-icons/category_icons.php');
		$data['acftdi'] = $this->getpluginstate('advanced-custom-fields/acf.php');

		$data['plugStatus']=$this->allPluginStatus($data);
		return $data;
	}

	/**
	 * @param string $plugin
	 * @return string $state ** absent,present and active **
	 **/
	public function getpluginstate($plugin)
	{
		$state = 'pluginAbsent';
		if($this->isPluginPresent($plugin))
			$state = 'pluginPresent';
		if($this->isPluginActive($plugin))
			$state = 'pluginActive';
		return $state;
	}

	/**
	 * return whether plugin is present
	 * @param string plugin
	 * @return boolean
	 **/
	public function isPluginPresent($plugin)
	{
		$pluginName = array();
		$plugins    = get_plugins();
		$custompostui=get_option('cpt_custom_post_types');
		$custtax=get_option('cpt_custom_tax_types');
		foreach($plugins as $plug => $key)
			$pluginName[] = $plug;

		if(in_array($plugin,$pluginName))
			return true;
		else
			return false;
	}

	/**
	 *  return where plugin is active or not
	 *  @param string $plugin
	 *  @return boolean
	 **/
	public function isPluginActive($plugin)
	{
		if(in_array($plugin,$this->activePlugins))
			return true;
		else
			return false;
	}
	
	public function allPluginStatus($skinnyData)
	{
		$pluginStatus='';
		if($skinnyData['eshop']=='checked')
		{
			if($skinnyData['eshoptdi']=='pluginPresent')
				$pluginStatus='Eshop Plugin is not Activate. ';
			elseif($skinnyData['eshoptdi']=='pluginAbsent')
				$pluginStatus='Eshop Plugin is missing. ';
		}
		elseif($skinnyData['wpcommerce']=='checked')
		{
			if($skinnyData['wpcomtdi']=='pluginPresent')
				$pluginStatus='WPCommerce Plugin is not Activate. ';
			elseif($skinnyData['wpcomtdi']=='pluginAbsent')
				$pluginStatus='WPCommerce Plugin is missing. ';

		}
		elseif($skinnyData['woocommerce']=='checked')
		{
			if($skinnyData['woocomtdi']=='pluginPresent')
				$pluginStatus='Woocommerce Plugin is not Activate. ';
			elseif($skinnyData['woocomtdi']=='pluginAbsent')
				$pluginStatus='Woocommerce Plugin is missing. ';

		}

		if($skinnyData['custompostuitype']=='checked')
		{
			if($skinnyData['cptutdi']=='pluginPresent')
				$pluginStatus.='Custom Post Type UI Plugin is not Activate. ';
			elseif($skinnyData['cptutdi']=='pluginAbsent')
				$pluginStatus.='Custom Post Type UI Plugin is missing. ';
		}
		elseif($skinnyData['cctm']=='checked')
		{
			if($skinnyData['cctmtdi']=='pluginPresent')
				$pluginStatus.='Custom Content Type Manager Plugin is not Activate. ';
			elseif($skinnyData['cctmtdi']=='pluginAbsent')
				$pluginStatus.='Custom Content Type Manager Plugin is missing. ';
		}

		if($skinnyData['acf']=='checked')
		{
			if($skinnyData['acftdi']=='pluginPresent')
				$pluginStatus.='Advance Custom Field Plugin is not Activate. ';
			elseif($skinnyData['acftdi']=='pluginAbsent')
				$pluginStatus.='Advance Custom Field Plugin is missing. ';

		}

		if($skinnyData['aioseo']=='checked')
		{
			if($skinnyData['aioseotdi']=='pluginPresent')
				$pluginStatus.='All in One SEO Plugin is not Activate. ';
			elseif($skinnyData['aioseotdi']=='pluginAbsent')
				$pluginStatus.='All in One SEO Plugin is missing. ';
		}
		elseif($skinnyData['yoastseo']=='checked')
		{
			if($skinnyData['yoasttdi']=='pluginPresent')
				$pluginStatus.='Yoast SEO Plugin is not Activate. ';
			elseif($skinnyData['yoasttdi']=='pluginAbsent')
				$pluginStatus.='Yoast SEO Manager Plugin is missing. ';
		}
		return $pluginStatus;
	}
}

