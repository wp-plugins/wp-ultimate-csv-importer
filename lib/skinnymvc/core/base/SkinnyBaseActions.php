<?php
/******************************
 * filename:    SkinnyBaseActions.php
 * description: main Actions class
 */

class SkinnyBaseActions {

   protected $skinnyUser = null;

   protected $authenticatedOnly = false;

   protected $allowUndefinedActions = false;

  /**
   * Attaches the session-user to this action
   * @param SkinnyUser $skinnyUser
   */
   public function setSkinnyUser($skinnyUser) {
      $this->skinnyUser = $skinnyUser;
   }

  /**
   * Gets the current SkinnyUser - session
   * @return SkinnyUser
   */
   public function getSkinnyUser() {
      return $this->skinnyUser;
   }

  /**
   * Is this module restricted?
   * @return boolean
   */
   public function authenticatedOnly() {
      return $this->authenticatedOnly;
   }

  /**
   * Does module allow undefined actions?
   * @return boolean
   */
   public function allowUndefinedActions() {
      return $this->allowUndefinedActions;
   }

  /**
   * Redirects the browser to a new page (modue and action)
   * @param string $module
   * @param string $action
   * @param array $request
   */
   public function redirect($module='default', $action='index', $request='') {
      $param = self::getRelativeRoot()."$module/$action/";
      if (is_array($request)) {
        $loop = 0;
        if(isset($request['GET'])) {
           foreach ($request['GET'] As $key=>$value) {
              if ($loop == 0) {
                $param .= "?";
              } else {
                $param .= "&";
              }
              $param .= "$key=$value";
              $loop++;
           }
        }
        if(isset($request['POST'])) {
           foreach ($request['POST'] As $key=>$value) {
               if ($loop == 0) {
                $param .= "?";
              } else {
                $param .= "&";
              }
              $param .= "$key=$value";
              $loop++;
           }
        }
      } else { //not array
        $param .= $request;
      }

      if (SkinnySettings::$CONFIG['debug']) {
         header( "Location: /dev.php".$param );
      } else {
         header( "Location: ".$param );
      }
      exit;
   }

  /**
   * Makes a call to the specified module+action and returns back to the caller
   * @param string $module
   * @param string $action
   * @param array $request
   * @return array
   */
   public function call($module='default', $action='index', $request=array('GET'=>array(), 'POST'=>array())) {
      $moduleClass = SkinnyControllerWPCsvFree::camelize($module) . 'Actions';

      $actionMethod = 'execute'.SkinnyControllerWPCsvFree::camelize($action);

      $moduleObj = new $moduleClass();

      if ($moduleObj->authenticatedOnly()) {
        if (!$this->skinnyUser->isAuthenticated()) {
            //Not authenticated!
            return null;
        }
      }

      if (is_callable(array($moduleObj, $actionMethod))) {
        $data = call_user_func_array(array($moduleObj, $actionMethod), array($request));
        if (SkinnySettings::$CONFIG['debug']) {
           global $__DEBUG;
           array_push($__DEBUG['data'], $data);
        }
      }
      return $data;
   }


    public function sendHttpResponse204($param=array())
    {
        //
        // Deal with parameters.
        //
            if (  !isset($param) || !is_array($param)  ) {
                $param = array();
            }

        //
        // Send the HTTP 204 Not Found response.
        //
            header('HTTP/1.1 204 No Content');
            if (  isset($param['headers']) && is_array($param['headers']) && !empty($param['headers'])  ) {
                foreach (  $param['headers'] AS $header  ) {
                    header($header);
                } // foreach
            }

        //
        // Exit.
        //
    /////// EXIT
            exit(0);
    }

    public function sendHttpResponse404($param=array())
    {
        $default_s = '<html><head><title>Not Found</title></head><body><h1>Not Found</h1></body></html>';

        //
        // Deal with parameters.
        //
            if (  is_string($param)  ) {
                $param = array(  'layoutData' => $param  );
            }
            if (  !isset($param) || !is_array($param)  ) {
                $param = array();
            }
            if (  !isset($param['layoutData'])  ) {
                $param['layoutData'] = array();
            }

        //
        // Send the HTTP 404 Not Found response.
        //
            header('HTTP/1.1 404 Not Found');
            if (  isset($param['headers']) && is_array($param['headers']) && !empty($param['headers'])  ) {
                foreach (  $param['headers'] AS $header  ) {
                    header($header);
                } // foreach
            }


            $s = file_get_contents("../templates/404.php");
            if (  !isset($s) || !is_string($s) ) {
                $s = $default_s;
            }

            $code = ' ?'.'>'. $s .'<'.'?'.'php ';

            $proc = create_function('$layoutData', $code);
            if (  !isset($proc) || FALSE === $proc  ) {
                print($default_s);
    /////////// EXIT
                exit(1);
            }

            $proc($param['layoutData']);

        //
        // Exit.
        //
    /////// EXIT
            exit(1);
    }

    public function sendHttpResponse405($param=array())
    {
        $default_s = '<html><head><title>Method Not Allowed</title></head><body><h1>Method Not Allowed</h1></body></html>';

        //
        // Deal with parameters.
        //
            if (  is_string($param)  ) {
                $param = array(  'layoutData' => $param  );
            }
            if (  !isset($param) || !is_array($param)  ) {
                $param = array();
            }
            if (  !isset($param['layoutData'])  ) {
                $param['layoutData'] = array();
            }

        //
        // Send the HTTP 405 Not Found response.
        //
            header('HTTP/1.1 405 Method Not Allowed');
            if (  isset($param['headers']) && is_array($param['headers']) && !empty($param['headers'])  ) {
                foreach (  $param['headers'] AS $header  ) {
                    header($header);
                } // foreach
            }


            $s = file_get_contents("../templates/405.php");
            if (  !isset($s) || !is_string($s) ) {
                $s = $default_s;
            }

            $code = ' ?'.'>'. $s .'<'.'?'.'php ';

            $proc = create_function('$layoutData', $code);
            if (  !isset($proc) || FALSE === $proc  ) {
                print($default_s);
    /////////// EXIT
                exit(1);
            }

            $proc($param['layoutData']);

        //
        // Exit.
        //
    /////// EXIT
            exit(1);
    }

    public function sendHttpResponse500($param=array())
    {
        $default_s = '<html><head><title>Internal Server Error</title></head><body><h1>Internal Server Error</h1></body></html>';

        //
        // Deal with parameters.
        //
            if (  is_string($param)  ) {
                $param = array(  'layoutData' => $param  );
            }
            if (  !isset($param) || !is_array($param)  ) {
                $param = array();
            }
            if (  !isset($param['layoutData'])  ) {
                $param['layoutData'] = array();
            }

        //
        // Send the HTTP 500 Not Found response.
        //
            header('HTTP/1.1 500 Internal Server Error');
            if (  isset($param['headers']) && is_array($param['headers']) && !empty($param['headers'])  ) {
                foreach (  $param['headers'] AS $header  ) {
                    header($header);
                } // foreach
            }


            $s = file_get_contents("../templates/500.php");
            if (  !isset($s) || !is_string($s) ) {
                $s = $default_s;
            }

            $code = ' ?'.'>'. $s .'<'.'?'.'php ';

            $proc = create_function('$layoutData', $code);
            if (  !isset($proc) || FALSE === $proc  ) {
                print($default_s);
    /////////// EXIT
                exit(1);
            }

            $proc($param['layoutData']);

        //
        // Exit.
        //
    /////// EXIT
            exit(1);
    }



  /**
   * Gets the relative root directory of the project - useful, if installed in a subdir.
   * @return string
   */
   public static function getRelativeRoot() {
      $rel_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
      if ($rel_path == "index.php"){
        $rel_path="/";
      } else if ($rel_path == "dev.php") {
        $rel_path="/dev.php/";
      } else {
        $rel_path = substr($rel_path, 0, strrpos($rel_path, "/")+1);
      }
      return $rel_path;
   }

   
   /**
    * proxy method providing ability to set the layout used for a given action
    *
    * @param String $layout
    * @return void
    */
   public function setLayout($layout)
   {
     SkinnyBaseControllerWPCsvFree::setLayout($layout);
   }

}

