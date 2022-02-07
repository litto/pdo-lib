<?php

 class PDO
 {
  /*
 * Author : Litto chacko
 * Email:littochackomp@gmail.com
*/
  public $dbUser;
  public $dbPass;
  public $dbName;
  public $dbHost;
  private $dbConnection;
  private $errorString;
  private $filter;  
  private $util;
  public static $instance;
  public $query;
  public $newCon;

function __construct(){
  
   $dbConnection = null;
   $this->filter  = true; 
   $this->newCon   = false;
   
  }
  
  
  function setNew(){
   $this->newCon = true;
  }
  function noFilter()
  {
   $this->filter = false;
  }
  /*
   * Setting Error Message on Db Operation
   * Input String Message
   * Called upon db operation
  */
  
  function setError($string)
  {
   $this->errorString = $string;
   //echo "MYSQL ERROR - ".$this->errorString;
  }
  
  /*
   * get Error Message after a db operation
   * Retrieves the current error Status
  */
  
  function getError()
  {
   return $this->errorString;
  }
  
  function connect()
  {
   if(is_null($dbConnection)){

                require_once(CONST_BASEDIR.'/config.php') ;    
    $this->dbUser  = $config["databaseUser"];
     $this->dbPass  = $config["databasePass"];
     $this->dbName  = $config["databaseName"];
    $this->dbHost  = $config["databaseHost"];
   try {
 
$dbConnection= new PDO('mysql:host='.$this->dbHost.';dbname='.$this->dbName.';charset=utf8', $this->dbUser, $this->dbPass,array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
return $dbConnection;
} catch(PDOException $ex) {
    print "Error!: " . $ex->getMessage() . "<br/>";
    //some_logging_function($ex->getMessage());
}
}
}

function getInstance(){
   return $dbConnection;
  }
  
  /*
   * Close the Mysql Connection   
  */
  function addFilter($string){
   return addslashes($string); 
     
  }
  
  /*
   * Remove added special chars on STring 
  */
  
  function removeFilter($string){   
    return stripslashes($string);   
  } 
  
  
  function escapeHtml($text){
   return strip_tags($text);
  }
  


  function close()
  {
   if($dbConnection){
    $dbConnection = null;
   }else{
    $dbConnection = null;
   }
  } 
  
function fetchAll($query)
  {

   include(CONST_BASEDIR.'/config.php') ;    
    $dbUser  = $config["databaseUser"];
     $dbPass  = $config["databasePass"];
     $dbName  = $config["databaseName"];
    $dbHost  = $config["databaseHost"];
   try {

$db= new PDO('mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8', $dbUser, $dbPass,array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
   $fileds  = array();
        $resultSet = array();
   $stmt = $db->query($query);
   $result= $stmt->fetchAll(PDO::FETCH_ASSOC);
   return $result;
  } catch(PDOException $ex) {
    print "Error!: " . $ex->getMessage() . "<br/>";
    //some_logging_function($ex->getMessage());
}
   


}


function insert($options,$table)
  {
   $queryString = "";
   $p    = count($options);
   $start   = 0;
   $fieldString = null;
   $valueString = array();
  include(CONST_BASEDIR.'/config.php') ;    
    $dbUser  = $config["databaseUser"];
     $dbPass  = $config["databasePass"];
     $dbName  = $config["databaseName"];
    $dbHost  = $config["databaseHost"];
    try {

   $db= new PDO('mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8', $dbUser, $dbPass,array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
 foreach($options as $key=>$val){
    $fieldString.=" `{$key}`";
    $valueString[":".$key.""]="{$val}";
    $vs.=":".$key;
    if($start<$p-1){
     $fieldString.=",";
     $vs.=",";
    }
    $start++;
   }

//$dc=$valueString;
$queryString = "INSERT INTO `{$table}` ({$fieldString}) VALUES ({$vs}) ";
$stmt = $db->prepare($queryString);
$stmt->execute($valueString);
 //$affected_rows = $stmt->rowCount();

 } catch(PDOException $ex) {
    print "Error!: " . $ex->getMessage() . "<br/>";
    //some_logging_function($ex->getMessage());
}


  }
  


function update($options,$table,$condition)
  {
   $queryString = "";
   $fieldString = ""; 
      $valueString = array();  
   $p    = count($options);
   $start   = 0;
   
  include(CONST_BASEDIR.'/config.php') ;    
    $dbUser  = $config["databaseUser"];
     $dbPass  = $config["databasePass"];
     $dbName  = $config["databaseName"];
    $dbHost  = $config["databaseHost"];
      try {

$db= new PDO('mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8', $dbUser, $dbPass,array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
foreach($options as $key=>$val){
     $vs=":".$key;
$fieldString.=" `{$key}`={$vs}";
$valueString[":".$key.""]="{$val}";

    if($start<$p-1){
     $fieldString.=",";
    }
    $start++;
   }   
$queryString = "UPDATE `{$table}` SET {$fieldString} ";
   if(!empty($condition)){
    echo $queryString.=" WHERE {$condition} ";
   }

$stmt = $db->prepare($queryString);
$stmt->execute($valueString);
} catch(PDOException $ex) {
    print "Error!: " . $ex->getMessage() . "<br/>";
    //some_logging_function($ex->getMessage());
}


  }


function delete($table,$condition)
  {
  
include(CONST_BASEDIR.'/config.php') ;    
    $dbUser  = $config["databaseUser"];
     $dbPass  = $config["databasePass"];
     $dbName  = $config["databaseName"];
    $dbHost  = $config["databaseHost"];
  try {

  $db= new PDO('mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8', $dbUser, $dbPass,array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

$queryString = "DELETE FROM `{$table}` ";
   if(!empty($condition)){
    $queryString.=" WHERE {$condition} ";
   }
$result = $db->exec($queryString);
} catch(PDOException $ex) {
    print "Error!: " . $ex->getMessage() . "<br/>";
    //some_logging_function($ex->getMessage());
}


  function execute($query){
       include(CONST_BASEDIR.'/config.php') ;    
    $dbUser  = $config["databaseUser"];
     $dbPass  = $config["databasePass"];
     $dbName  = $config["databaseName"];
    $dbHost  = $config["databaseHost"];
try {

$db= new PDO('mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8', $dbUser, $dbPass,array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

   $result = $db->exec($query);
} catch(PDOException $ex) {
    print "Error!: " . $ex->getMessage() . "<br/>";
    //some_logging_function($ex->getMessage());
}
}
}
} 
?>
