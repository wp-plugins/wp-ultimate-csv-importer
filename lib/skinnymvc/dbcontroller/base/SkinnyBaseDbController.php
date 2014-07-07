<?php
/**
 * filename:    SkinnyBaseDbController.php
 * description: Database controller
 */

class SkinnyBaseDbController extends PDO {

   protected static $connections = array();

   protected function SkinnyBaseDbController($dbKey, $mode, $dsn, $username=null, $password=null, $driver_options=null ) {
     parent::__construct($dsn, $username, $password, $driver_options);
     self::$connections[$dbKey][$mode] = $this;
   }

  /**
   * Gets the existing DB Connection or creates a new one
   * @param string $dbKey
   * @return SkinnyDbController
   */
   public static function getConnection($dbKey = '', $mode='r+') { // empty $dbKey must be '' and NOT null!!!

     if (!isset(self::$connections[$dbKey][$mode]) || empty(self::$connections[$dbKey][$mode])) {

       if (  array_key_exists("dbs", SkinnySettings::$CONFIG) && is_array(SkinnySettings::$CONFIG["dbs"]) && array_key_exists($dbKey, SkinnySettings::$CONFIG["dbs"])  ) {
         $db_config = SkinnySettings::$CONFIG["dbs"][$dbKey];
         $dbName = null;
       } else {
         $db_config = SkinnySettings::$CONFIG;
         $dbName = $dbKey;
       }
       if (empty($dbName)) {
         $dbName = $db_config["dbname"];
       }

       if ($db_config["dbhost"] == "127.0.0.1") {
         $dsn = $db_config["dbdriver"].":dbname=".$dbName;
       } else {
         $dsn = $db_config["dbdriver"].":dbname=".$dbName.";host=".$db_config["dbhost"];
       }

       $dsn = $db_config["dbdriver"].":dbname=".$dbName.";host=".$db_config["dbhost"];
       try {
         return new SkinnyDbController($dbKey, $mode, $dsn, $db_config["dbuser"], $db_config["dbpassword"]);
       } catch (PDOException $e) {
         throw new SkinnyDbException($e->getMessage(), $e->getCode());
       }
     } else {
       return self::$connections[$dbKey][$mode];
     }
   }

    public static function getReadConnection($dbKey = '')
    {
        // TODO after PHP 5.3 becomes more common: return static::getConnection($dbKey, "r");
        return self::getConnection($dbKey, "r");
    }

    public static function getWriteConnection($dbKey = '')
    {
        // TODO after PHP 5.3 becomes more common: return static::getConnection($dbKey, "w");
        return self::getConnection($dbKey, "w");
    }
}
    
