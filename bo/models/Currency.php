<?php
/**
 *
 * @package   Currency
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once('conf/connection.php');

class Currency{
  private $idcurrency;
  private $currency;
  private $symbol;
  private $code;
  private $state;
  private $createdat;
  private $updatedat;
  private static $con;
  private static $class;

  const obsolete = 0;
  const circulation = 1;


  public function __construct(){
    Currency::$con = new Connection();
    self::$class = $this;
  }

  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }


  public static function getobsolete()
  {
    return self::obsolete;
  }

  public static function getcirculation()
  {
    return self::circulation;
  }


  public function getattribute() {
    return  get_object_vars($this);
  }


  public function checkcurrency(){
    $currency = self::findAll();
    if($currency == null){
      $this->currency = "Euro";
      $this->symbol = "€";
      $this->code = "EUR";
      $this->state = self::getcirculation();
      $this->save();
    }
  }

  public static function findAll(){

    $sql= "SELECT idcurrency, currency, symbol, code, state, createdat, updatedat FROM currency where state=:state ";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }
    
    $retval->bindParam(':state', 1, PDO::PARAM_INT);
    $retval->execute();
    
    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }
    
    $retval->closeCursor();
    return null;
  }



  public static function findative(){

    $sql= "SELECT idcurrency, currency, symbol, code FROM currency where state=:state ";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }
    
    $retval->bindParam(':state', 1, PDO::PARAM_INT);
    $retval->execute();
    
    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }
    
    $retval->closeCursor();
    return null;
  }



  public static function findbycode($code){

    $sql= "SELECT idcurrency, currency, symbol, code FROM currency where code like :code ";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }
    
    $retval->bindParam(':code', $code, PDO::PARAM_STR);
    $retval->execute();
    
    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }
    
    $retval->closeCursor();
    return null;
  }



  public function save(){
    $sql = "INSERT INTO currency ( currency, symbol, code, state, createdat, updatedat) VALUES (:currency,:symbol,:code,:state,now(),now())";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
    return false;
    }

    $retval->bindParam(':currency',             $this->currency, PDO::PARAM_STR);
    $retval->bindParam(':symbol',            $this->symbol, PDO::PARAM_STR);
    $retval->bindParam(':code',             $this->code, PDO::PARAM_STR);
    $retval->bindParam(':state',             $this->state, PDO::PARAM_STR);

    if($retval->execute()) {
    $id =  self::$con->lastInsertId();
    $retval->closeCursor();
    return $id;
    }

    $retval->closeCursor();
    return false;
  }


  public static function findbyid($id){

    $sql= "SELECT idcurrency, currency, symbol, code, state, createdat, updatedat FROM currency WHERE idcurrency=:idcurrency ";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }
    
    $retval->bindParam(':idcurrency', $idcurrency, PDO::PARAM_INT);
    $retval->execute();
    
    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }
    
    $retval->closeCursor();
    return null;
  }



  public static function findbyidcompany($idcompany){
    $arr=array();
    $sql= "SELECT currency.idcurrency, currency.currency, currency.symbol, currency.code, currency.state, currency.createdat, currency.updatedat FROM currency inner join company on company.idcurrency=currency.idcurrency WHERE company.idcompany=:idcompany ";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }
    
    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->execute();
    
    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }
    
    $retval->closeCursor();
    return null;
  }


}

?>
