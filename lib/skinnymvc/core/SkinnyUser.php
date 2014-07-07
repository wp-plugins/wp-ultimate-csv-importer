<?php
/******************************
 * filename:    SkinnyUser.php
 * description: Holds session stuff
 *              This version requires working session persistency!
 */

class SkinnyUser {

   private $authenticated = false;

   private $timeout = 1800;

   private $last_accessed = 0;

   private $attributes = array();

   private function SkinnyUser() {
   }

  /**
   * Gets the existing User or creates a new one
   */
   public static function getUser() {
      if (!SkinnySettings::$CONFIG['session persistency']) {
        throw new SkinnyException("Session persistency not enabled");
      }
      $sess = null;
      session_start();

      if (file_exists("tmp/".session_id().".session")) {
         $sess = unserialize(@file_get_contents("tmp/".session_id().".session"));
         $session_inactive = time() - $sess->last_accessed;
         if ($session_inactive > $sess->timeout) {
             $sess->last_accessed = time();
             $sess->setAuthenticated(false);
             $sess->save();
         } else {
             $sess->last_accessed = time();
             $sess->save();
         }
      } else {
         $sess = new SkinnyUser();
         $sess->timeout = SkinnySettings::$CONFIG['session timeout'];
         $sess->last_accessed = time();
         $sess->save();
      }
      return $sess;
   }

  /**
   * @return boolean Is the user authenticated?
   */
   public function isAuthenticated() {
     return $this->authenticated;
   }

  /**
   * Sets the user to authenticated or unauthenticated
   * @param boolean $authenticated 
   */
   public function setAuthenticated($authenticated) {
     if (!is_bool($authenticated)) {
        throw new SkinnyException("Authentication value must be boolean");
     }

     $this->authenticated = $authenticated;
     $this->save();     
   }

  /**
   * Returns the value of a saved attribute
   * @param string $name Name of the attribute
   * @return mixed Value of the attribute or null if the attribute was not found
   */
   public function getAttribute($name) {
     if(isset($this->attributes[$name])) {
        return $this->attributes[$name];
     } else {
        return null;
     }
   }

  /**
   * Saves an attribut in the session
   * @param string $name Name of the attribute
   * @param mixed $value Value of the attribute
   */
   public function setAttribute($name, $value) {
      $this->attributes[$name] = $value;
      $this->save();
   }

  /**
   * Deletes an attibute that was stored in the session
   * @param string $name Name of the attribute
   */
   public function deleteAttribute($name) {
      if(isset($this->attributes[$name])) {
        unset($this->attributes[$name]);
      }
      $this->save();
   }

  /**
   * Gets the current session timeout value
   * @return int Timeout in seconds
   */ 
   public function getTimeout() {
      return $this->timeout;
   }

  /**
   * Make user data persistent
   */
   public function save() {
      $data = serialize($this);
      return file_put_contents("tmp/".session_id().".session", $data);
   }

  /**
   * Destroys the session - removes user file
   */
   public function destroy() {
      return @unlink("tmp/".session_id().".session");
   }

   //clean up the tmp dir
   public static function cleanup() {
      if ($handle = opendir("tmp")) {
         while (false !== ($file = readdir($handle))) {
           $diff = time() - filemtime("tmp/".$file);
           if ($diff>SkinnySettings::$CONFIG["session timeout"]){
              @unlink("tmp/$file");
           }
         }
      }
   }
}
    
