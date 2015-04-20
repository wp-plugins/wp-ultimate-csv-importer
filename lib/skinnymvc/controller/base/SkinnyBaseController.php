<?php
/******************************
 * filename:    SkinnyBaseController.php
 * description: The main application controller. Every request goes through here.
 */

class SkinnyBaseControllerWPCsvFree {

    protected static $layout = 'layout'; /* name of the layout file to use - no extension */
    protected $app = null;
    protected $module = null;
    protected $action = null;
    protected $param = null;
    protected $skinnyUser = null;

    protected $allowModulesAsFiles = false;
    protected $allowActionsAsFiles = false;
    protected $fixMisspellings     = true;

    public function __construct()
    {
        // Nothing here.
    }

  /**
   * The main controller script, running with every request.
   */
    public function main()
    {
        //
        // Get the Module and Action from the CGI parameters.
        //
            if (isset($_GET['__action']) && !empty($_GET['__action'])) {
                $action = $_GET['__action'];
            } else {
                $action = 'index';
            }

            if (isset($_GET['__module']) && !empty($_GET['__module'])) {
                $module = $_GET['__module'];
            } else {
                $module = 'default';
                $action = 'index';
            }


        //
        // Set up $param.
        //
            $paramGET = $_GET;
            unset($paramGET['__module']);
            unset($paramGET['__action']);

            $param = array('GET'=>$paramGET, 'POST'=>$_POST, 'FILES'=>$_FILES);


        //
        // Set up variable that are used by the run() method.
        //
            $this->module = $module;
            $this->action = $action;
            $this->param  = $param;


        //
        // Handle the missing slashes if there are any.
        //

           // Slash after the module missing?
            $hasMissingSlash = '' == @$_GET['__action']
                            && '/' == substr($_SERVER['REQUEST_URI'],0,1) 
                            && 1 < strlen($_SERVER['REQUEST_URI']) 
                            && FALSE == strpos($_SERVER['REQUEST_URI'],'/',1)
                             ;
            if (  $hasMissingSlash  ) {

                if (  $this->allowModulesAsFiles   ) {

                    // Nothing here.

                } else if (  $this->fixMisspellings  ) {

                    if (  '' != $this->module && '' == @$_GET['__action']  ) {
                        $href = '/' . $this->module . '/';
                        header('Location: '.$href);
                        exit();
                    }

                } else {
                    //Error: Action does not exist
                    header("HTTP/1.1 404 Not Found");
                    echo file_get_contents(WP_CSVIMP_PLUGIN_BASE."templates/404.php");
                    exit;
                }
            }

           // Slash after the action missing?
           $hasMissingSlash = '' != @$_GET['__action']
                            && '/' == substr($_SERVER['REQUEST_URI'],0,1) 
                            && 1 < strlen($_SERVER['REQUEST_URI']) 
                            && FALSE !== strpos($_SERVER['REQUEST_URI'],'/',1)
                            && FALSE ==  strpos($_SERVER['REQUEST_URI'],'/', strpos($_SERVER['REQUEST_URI'],'/',1))
                             ;
            if (  $hasMissingSlash  ) {

                if (  $this->allowActionsAsFiles   ) {

                    // Nothing here.

                } else if (  $this->fixMisspellings  ) {

                    if (  '' != $this->module && '' != @$_GET['__action']  ) {
                        $href = '/' . $this->module . '/'. $this->action .'/';
                        header('Location: '.$href);
                        exit();
                    }

                } else {
                    //Error: Action does not exist
                    header("HTTP/1.1 404 Not Found");
                    echo file_get_contents(WP_CSVIMP_PLUGIN_BASE."templates/404.php");
                    exit;
                }
            

            }


        //Get the core classes
        $this->require_once_many(WP_CSVIMP_PLUGIN_BASE."lib/skinnymvc/core/base/*.php");
        $this->require_once_many(WP_CSVIMP_PLUGIN_BASE."lib/skinnymvc/core/*.php");

        // Get the db controller classes
        $this->require_once_many(WP_CSVIMP_PLUGIN_BASE."lib/skinnymvc/dbcontroller/base/*.php");
        $this->require_once_many(WP_CSVIMP_PLUGIN_BASE."lib/skinnymvc/dbcontroller/*.php");

        // Get the controller classes
        $this->require_once_many(WP_CSVIMP_PLUGIN_BASE."lib/skinnymvc/controller/base/*.php");
        $this->require_once_many(WP_CSVIMP_PLUGIN_BASE."lib/skinnymvc/controller/*.php");

        //Get all Model classes
        if (SkinnySettings::$CONFIG['preload model']) {
            $this->require_once_many(WP_CSVIMP_PLUGIN_BASE."lib/skinnymvc/model/*.php");
            $this->require_once_many(WP_CSVIMP_PLUGIN_BASE."lib/skinnymvc/model/base/*.php");
        }

        //Initialize session
        if (SkinnySettings::$CONFIG['session persistency']) {
            $this->skinnyUser = SkinnyUser::getUser();
        }

        //Get all plugins
        $this->require_once_many(WP_CSVIMP_PLUGIN_BASE."plugins/skinnyPlugin*.php");


        //
        // Call the run() method.
        //
            $this->run();

    }


    public function run()
    {
        $this->executeModuleAction($this->module, $this->action, $this->param);
    }


    protected function executeModuleAction($module, $action, $param)
    {

        if (!file_exists(WP_CSVIMP_PLUGIN_BASE."modules/$module/actions/actions.php")) {
            //Error: Action does not exist
            header("HTTP/1.1 404 Not Found");
            echo file_get_contents(WP_CSVIMP_PLUGIN_BASE."templates/404.php");
            exit;
        }

        require_once(WP_CSVIMP_PLUGIN_BASE."modules/$module/actions/actions.php");

        $moduleClass = self::camelize($module) . 'Actions';

        $actionMethod = 'execute'.self::camelize($action);

        $moduleObj = new $moduleClass();

        $skinnyUser = $this->skinnyUser;

        if (!empty($skinnyUser)) {
            $this->checkAuthentication($moduleObj, $skinnyUser, $param);
        }

        if (empty($moduleObj)) {
            //Error: Module does not exist
            header("HTTP/1.1 404 Not Found");
            echo file_get_contents(WP_CSVIMP_PLUGIN_BASE."templates/404.php");
            exit;
        }

        // The action should return an array of all values that will be needed in the template
        if ( $moduleObj->allowUndefinedActions()) {

            $data = array();
            if (  is_callable(array($moduleObj, $actionMethod))  ) {
                $data = call_user_func_array(array($moduleObj, $actionMethod), array($param));
            } else {
                if (!file_exists(WP_CSVIMP_PLUGIN_BASE."modules/$module/templates/$action.php")) {
                    //Error: Action does not exist
                    header("HTTP/1.1 404 Not Found");
                    echo file_get_contents(WP_CSVIMP_PLUGIN_BASE."templates/404.php");
                    exit;
                }
            }
            if (SkinnySettings::$CONFIG['debug']) {
                global $__DEBUG;
                array_push($__DEBUG['data'], $data);
            }
        } else if (is_callable(array($moduleObj, $actionMethod))) {
            $data = call_user_func_array(array($moduleObj, $actionMethod), array($param));
            if (SkinnySettings::$CONFIG['debug']) {
                global $__DEBUG;
                array_push($__DEBUG['data'], $data);
            }
        } else {
            //Error: Action $action does not exist
            header("HTTP/1.1 404 Not Found");
            echo file_get_contents(WP_CSVIMP_PLUGIN_BASE."templates/404.php");
            exit;
        }

        //Process the templates
        if (!file_exists(WP_CSVIMP_PLUGIN_BASE."modules/$module/templates/$action.php")) {
            //Error
            throw new SkinnyException("Template for module $module, action $action does not exist.");
            exit;
        }

        $actionTemplateSource = file_get_contents(WP_CSVIMP_PLUGIN_BASE."modules/$module/templates/$action.php");

        ob_start();
        $this->processTemplate($data, $skinnyUser, $actionTemplateSource);

        $skinny_content = ob_get_clean();

        //Run the layout;
        $this->processLayout($skinny_content, $data, $skinnyUser, $module, $action, self::$layout);

	#TODO: Revisit the flush
        //flush();
        //ob_flush();

        //clean up old sessions
        $rand = rand(0, 99);
        if ($rand == 1) {
            #SkinnyUser::cleanup();
        }
    }

    protected function checkAuthentication($moduleObj, $skinnyUser, $param) {
        $moduleObj->setSkinnyUser($skinnyUser);
        if ($moduleObj->authenticatedOnly()) {
            if (!$skinnyUser->isAuthenticated()) {
                //Not authenticated!
                if (isset(SkinnySettings::$CONFIG['unauthenticated default module'])) {
                    if (isset(SkinnySettings::$CONFIG['unauthenticated default action'])) {
                        $moduleObj->redirect(SkinnySettings::$CONFIG['unauthenticated default module'], SkinnySettings::$CONFIG['unauthenticated default action'], $param);
                    } else {
                        $moduleObj->redirect(SkinnySettings::$CONFIG['unauthenticated default module'], "index", $param);
                    }
                } else {
                    $moduleObj->redirect("default", "index", $param);
                }
            }
        }
    }


  /**
   * Turns "foo_bar" into "FooBar"
   * @param string $str
   * @return string Camelized $str
   */
   public static function camelize($str)
   {
     $str = str_replace("_", " ", $str);
     $str = ucwords($str);
     $str = str_replace(" ", "", $str);
     return $str;
   }

   private function processTemplate($skinnyData, $skinnyUser, $skinnyTemplateSourceData) {
      eval('?>'.$skinnyTemplateSourceData."\n");
   }


   private function processLayout(&$skinny_content, $layoutData, $skinnyUser, $module, $action, $layout) {
      include_once WP_CSVIMP_PLUGIN_BASE.'templates/'. $layout .'.php';
   }


   private function require_once_many($pattern)
   {
      foreach(glob($pattern) as $class_filename) {
         require_once($class_filename);
      }
   }

    protected function moduleExists($moduleName)
    {
        return file_exists(WP_CSVIMP_PLUGIN_BASE.'modules/'. $moduleName .'/actions/actions.php');
    }

    protected function actionExists($moduleName, $actionName)
    {
        return file_exists(WP_CSVIMP_PLUGIN_BASE.'modules/'. $moduleName .'/templates/'. $actionName .'.php');
    }

    
   /**
    * static setter for the layout used to render action data
    *
    * @access public
    * @static
    * @param string $layout
    * @return void
    */
   public static function setLayout($layout)
   {
     self::$layout = $layout;
   }


} // class SkinnyBaseController
