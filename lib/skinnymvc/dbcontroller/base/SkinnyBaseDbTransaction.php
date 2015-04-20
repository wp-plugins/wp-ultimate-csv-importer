<?php
/**
 * filename:    SkinnyBaseDbTransaction.php
 * description: Database Transaction class
 */

class SkinnyBaseDbTransaction {

    protected static $dbTransactions = array();
    protected $currentDbKey = null;
    protected $magicNumber = null;
    protected $transactionActive = false;


    protected function __construct() {
        $this->magicNumber = md5(uniqid(mt_rand(), true)); //TODO
    }


    public static function create($p=array()) {
      $t = new SkinnyDbTransaction();
      if (!empty($p)) {
         $t->addManyORMs($p);
      }
      return $t;
    }


    public static function transactionActive($dbKey) {
       if (isset(self::$dbTransactions[$dbKey])) {
          return self::$dbTransactions[$dbKey]['is_active'];
       }
       return false;
    }


    public static function transactionMagicNumber($dbKey) {
       if (isset(self::$dbTransactions[$dbKey])) {
          return self::$dbTransactions[$dbKey]['magic_number'];
       }
       return false;
    }

    public static function transactionExists($dbKey) {
       return isset(self::$dbTransactions[$dbKey]);
    }


    public static function fetchDbTransaction($dbKey, $magicNumber) {
       if (isset(self::$dbTransactions[$dbKey]) && self::$dbTransactions[$dbKey]['magic_number'] == $magicNumber) {
          return self::$dbTransactions[$dbKey]['db_transaction'];
       }
       return null;
    }


    public function getCurrentDbKey() {
        return $this->currentDbKey;
    }


    //begin transaction; at least one object must be added before calling this method.
    public function begin() {
       if(empty($this->currentDbKey)) {
          throw new SkinnyDbException('No DB connection.');
       }

       $this->transactionActive = true;
       self::$dbTransactions[$this->currentDbKey]['is_active'] = $this->transactionActive; 

       //begin the actual transaction
       $con = SkinnyDbController::getWriteConnection($this->currentDbKey);
       $con->beginTransaction();
    }


    public function addORM($ormObj) {
       if (!is_object($ormObj)) {
          throw new SkinnyDbException('Non-object sent to addORM().');
       }

       // Set the DB Key
       $dbKey = $ormObj->databaseKey();
       if (is_null($this->currentDbKey)) {
          if (isset(self::$dbTransactions[$dbKey])) {
             throw new SkinnyDbException('An open transaction already exists for this DB key.');
          }
          $this->currentDbKey = $dbKey;
          self::$dbTransactions[$dbKey]['is_active'] = $this->transactionActive;
          self::$dbTransactions[$dbKey]['magic_number'] = $this->magicNumber;
          self::$dbTransactions[$dbKey]['db_transaction'] = $this;
       } else {
          if($this->currentDbKey != $dbKey) {
             throw new SkinnyDbException('DbKey mismatch.');
          }
       }

       if ($ormObj->isInAnyTransaction()) {
          throw new SkinnyDbException('Object is already in a transaction');
       }

       //Flag the ORM object
       $ormObj->updateMagicTransactionNumber($this->magicNumber);

       //TODO: add updateMagicTransactionNumber and isInTransaction to ORM objects
       //TODO: check for existing transaction in save and delete, 
       //      so that objects that are not in an existing transaction are locked out of saving
       //      If no transaction exists for the particular database, objects are saved with autocommit
    }


    public function addManyORMs($p) {
       if (is_array($p)) {
          foreach($p as $pObj) {
             $this->addORM($pObj);
          }
       } else if(is_object($p)) {
          $this->addORM($p);
       }
    }


    public function removeORM($ormObj) {
       if (!is_object($ormObj)) {
          throw new SkinnyDbException('Non-object sent to addORM().');
       }
       $ormObj->updateMagicTransactionNumber(null);
    }

    public function commit() {
       if(empty($this->currentDbKey)) {
          throw new SkinnyDbException('No DB connection.');
       }

       if (!$this->transactionActive) {
          throw new SkinnyDbException('Transaction not active');
       }

       //Do the actual commit
       $con = SkinnyDbController::getWriteConnection($this->currentDbKey);
       $con->commit();

       $this->clearKey();
    }


    public function rollBack() {
       if(empty($this->currentDbKey)) {
          throw new SkinnyDbException('No DB connection.');
       }

       if (!$this->transactionActive) {
          throw new SkinnyDbException('Transaction not active');
       }

       //Do the actual rollBack
       $con = SkinnyDbController::getWriteConnection($this->currentDbKey);
       $con->rollBack();

       $this->clearKey();
    }


    private function clearKey() {
       $this->transactionActive = false;
       unset(self::$dbTransactions[$this->currentDbKey]['db_transaction']);
       unset(self::$dbTransactions[$this->currentDbKey]);
       $this->currentDbKey = null;
    }
}
