<?php
/**
 * filename:    modules/settings/actions/actions.php
 * description: settings module
 **/
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
		$setingsArr = array('post', 'page', 'custompost', 'comments', 'categories', 'customtaxonomy', 'users', 'eshop', 'wpcommerce', 'woocommerce', 'custompostuitype', 'cctm', 'acf', 'aioseo', 'yoastseo', 'enable', 'disable', 'nonerseooption', 'nonercustompost', 'nonerecommerce', 'recommerce');
		foreach($setingsArr as $option)
			$data[$option] = "";

		$skinnycontroller = new WPImporter_includes_helper();
		$settings = $skinnycontroller->getSettings(); 
		foreach($settings as $settings_key)
			$data[$settings_key] = 'checked';

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
