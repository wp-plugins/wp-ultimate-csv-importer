<?php
class Settings{

	public $activePlugins = array();

	function __construct(){
		$this->activePlugins = get_option('active_plugins');
	}

	public function chkCustomPostPlugin(){
		$customPostType = $this->chkCustomTypePost();
		$cctm = $this->chkCCTM();
		if( ($cctm) || ($customPostType))
			return true;
		else
			return false;
	}

	public function chkCCTM(){
		if(in_array('custom-content-type-manager/index.php',$this->activePlugins))
			return true;
		else
			return false;
	}

	public function chkCustomTypePost(){
		if(in_array('custom-post-type-ui/custom-post-type-ui.php',$this->activePlugins))
			return true;
		else
			return false;
	}

	public function isPluginPresent($plugin){
		$plugins = get_plugins();
		$pluginName = array();
		foreach($plugins as $plug => $key)
			$pluginName[] = $plug;
		if(in_array($plugin,$pluginName))
			return true;
		else
			return false;
	}
	public function isPluginActive($plugin){
		if(in_array($plugin,$this->activePlugins))
			return true;
		else
			return false;
	}
}
