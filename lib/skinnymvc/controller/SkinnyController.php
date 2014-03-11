<?php
/******************************
 * filename:    SkinnyController.php
 * description: The main application controller. Every request goes through here.
 */

require_once('base/SkinnyBaseController.php');

class SkinnyControllerWPCsvFree extends SkinnyBaseControllerWPCsvFree 
{

	public function __construct()
	{
		// Nothing here.
		//$this->getKeyVals();
	}

	public function run()
	{
		// Put code here to rewrite the routing rules, or whatever.
		//
		// To make this happen, set the following fields to change the routing (and then call parent::run() )...
		//
		//     $this->module
		//     $this->action
		//     $this->param
		//
		//
		// For example, to make it so URLs like...
		//
		//     http://example.com/book/1234
		//     http://example.com/book/51238
		//     http://example.com/book/7
		//
		// ... work as if they were the URLs...
		//
		//     http://example.com/knowledgebase/item?ID=1234
		//     http://example.com/knowledgebase/item?ID=51238
		//     http://example.com/knowledgebase/item?ID=7
		//
		// ... we use the following code...
		//
		//     if (  'book' == $module  ) {
		//
		//         $ID = $this->action;
		//
		//         $this->param['GET']['ID'] = $ID;
		//         $this->module = 'knowledgebase';
		//         $this->action = 'item';
		//     }
		//
		//
		// Or for a more complex example, to make it so URLs like...
		//
		//     http://example.com/joe
		//     http://example.com/john
		//     http://example.com/jen
		//
		// ... work as if they were the URLs...
		//
		//     http://example.com/user/defaul?username=joe
		//     http://example.com/user/defaul?username=john
		//     http://example.com/user/defaul?username=jen
		//
		// ... EXCEPT in cases where there is actually a module for that, like...
		//
		//     http://example.com/settings
		//     http://example.com/about
		//     http://example.com/contact
		//
		// ... we use code like...
		//
		//     if (  ! $this->moduleExists($this->module)  ) {
		//         $this->module = 'user';
		//         $this->action = 'default';
		//         $this->param['GET']['username'] = $this->module;
		//     }
		//



		// This MUST stay here!
		parent::run();
	}	

} // class SkinnyController

class CallSkinnyObj extends SkinnyControllerWPCsvFree
{
private static $_instance = null;
public static function getInstance()
{
if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
self::$_instance = new SkinnyControllerWPCsvFree();
return self::$_instance;
}
}// CallSkinnyObj Class Ends

