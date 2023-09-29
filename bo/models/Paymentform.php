<?php
/**
 *
 * @package   Paymentform
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */ 
require_once('conf/connection.php');

class Paymentform{
  private $idpaymentform;
  private $description;
  private $numberdays;
  private $idcompany;
  private $createdat;
  private $updatedat;
  private $created_by;
  private $updatedby;
  private static $con;
  private $paymentform;
  private static $class;


  public function __construct(){
    self::$con = new Connection();
    $this->valuespaymentform();
    self::$class = $this;
  }

  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }


  public function getattribute() {
    return  get_object_vars($this);
  }




  public static function search($search, $idcompany, $limit=1){
      $sql = "SELECT idpaymentform as id, description as select_show, numberdays, idcompany, createdat, updatedat  FROM paymentform where idcompany=:idcompany and (description like :search or numberdays like :search) limit :limit";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
          return null;
      }
        $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
      $retval->bindParam(':search', $search, PDO::PARAM_STR);
      $retval->bindParam(':limit', $limit, PDO::PARAM_INT);
      $retval->execute();

      if ($retval->rowCount() > 0) {
          $values = $retval->fetchAll(PDO::FETCH_OBJ);
          $retval->closeCursor();
          return $values;
      }

      $retval->closeCursor();
      return null;
  }


  public  function savepayments($idcompany){
    $payment = self::findAll($idcompany);
    $countpayment = $payment!=null ? count($payment) : 0;
    if($payment==null || $countpayment==0){
      foreach ($this->paymentform as &$value) {
        $this->description = $value[1];
        $this->numberdays = $value[2];
        $this->idcompany = $idcompany;
        $this->save();
      }
    }
    unset($payment);
  }

  private function valuespaymentform(){
    $this->paymentform = array (
      1 => array(1=>'Pronto Pagamento', 2=>'0'),
      2 => array(1=>'15 dias', 2=>'15'),
      3 => array(1=>'30 dias', 2=>'30'),
      4 => array(1=>'45 dias', 2=>'45'),
      5 => array(1=>'60 dias', 2=>'60'),
      6 => array(1=>'90 dias', 2=>'90')
    );
  }

  public static function findAll($idcompany){
    $paymentform=array();
    $sql= "SELECT idpaymentform, description, numberdays, idcompany, createdat, updatedat  FROM paymentform where idcompany=:idcompany";
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




  public static function countall($idcompany){

    $sql= "SELECT count(idpaymentform) as total  FROM paymentform where idcompany=:idcompany";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return 0;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
        $values = $retval->fetch(PDO::FETCH_OBJ);
        $retval->closeCursor();
        return $values->total;
    }

    $retval->closeCursor();
    return 0;
  }



  public static function findbynumberdays($idcompany, $numberdays){

    $sql= "SELECT idpaymentform, description, numberdays  FROM paymentform where idcompany=:idcompany and numberdays=:numberdays limit 1";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
        return null;
      }

      $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
      $retval->bindParam(':numberdays', $numberdays, PDO::PARAM_INT);
      $retval->execute();

      if ($retval->rowCount() > 0) {
        $values = $retval->fetch(PDO::FETCH_OBJ);
        $retval->closeCursor();
        return $values;
      }

      $retval->closeCursor();
      return null;
  }


  public static function findAPI($idcompany){

    $sql= "SELECT idpaymentform, description, numberdays  FROM paymentform where idcompany=:idcompany";
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





  public static function finddescbylang($id, $lang){

    $sql= "SELECT description  FROM paymentform_lang where idpaymentform=:idpaymentform and language like :lang limit 1";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idpaymentform', $id, PDO::PARAM_INT);
    $retval->bindParam(':lang', $lang, PDO::PARAM_STR);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


  public static function findperpage($limitstart, $perpage, $idcompany, $order, $keyorder, $search){
    $limitstart = intval($limitstart);
    $perpage = intval($perpage);
    $sql= "SELECT `idpaymentform`, `description`, `numberdays`, `idcompany`, `createdat`, `updatedat` FROM `paymentform`
     where  idcompany=:idcompany and UPPER(description) LIKE UPPER(:search)  order by $keyorder $order, description DESC limit :start, :limit ";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
          return null;
      }

      $retval->bindParam(':search', $search, PDO::PARAM_STR);
      $retval->bindParam(':start',  $limitstart, PDO::PARAM_INT);
      $retval->bindParam(':limit',  $perpage, PDO::PARAM_INT);
      $retval->bindParam(':idcompany',  $idcompany, PDO::PARAM_INT);
      $retval->execute();

      if ($retval->rowCount() > 0) {
          $values = $retval->fetchAll(PDO::FETCH_OBJ);
          $retval->closeCursor();
          return $values;
      }

      $retval->closeCursor();
      return null;
  }

  public function save(){
    $sql = "INSERT INTO paymentform (description,
                        numberdays,
                        idcompany,
                        createdat,
                        updatedat,
                        createdby,
                        updatedby) VALUES (:description,:numberdays,:idcompany,now(),now(),:updatedby,:createdby)";
    $retval =  self::$con->prepare($sql);
  if(!$retval){
      return false;
  }

  $retval->bindParam(':description',             $this->description, PDO::PARAM_STR);
  $retval->bindParam(':numberdays',            $this->numberdays, PDO::PARAM_INT);
  $retval->bindParam(':idcompany',             $this->idcompany, PDO::PARAM_INT);
  $retval->bindParam(':createdby',             $this->created_by, PDO::PARAM_INT);
  $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);


  if($retval->execute()) {
      $id =  self::$con->lastInsertId();
      $retval->closeCursor();
      return $id;
  }

  $retval->closeCursor();
  return false;
  }

  public static function findbyid($id, $idcompany){

    $sql= "SELECT idpaymentform, description, numberdays, idcompany, createdat, updatedat FROM paymentform WHERE idpaymentform=:idpaymentform and idcompany=:idcompany";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':idpaymentform', $id, PDO::PARAM_INT);
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
  }


  public function update(){
    $sql = "UPDATE `paymentform` SET `description`=:description,`numberdays`=:numberdays,`updatedat`=now(),updatedby=:updatedby WHERE `idpaymentform`=:idpaymentform";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return null;
    }
    $retval->bindParam(':idpaymentform', $this->idpaymentform, PDO::PARAM_INT);
    $retval->bindParam(':description', $this->description, PDO::PARAM_STR);
    $retval->bindParam(':numberdays', $this->numberdays, PDO::PARAM_INT);
    $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);

    if($retval->execute()) {
        $retval->closeCursor();
        return true;
    }

    $retval->closeCursor();
    return false;

  }




}

?>
