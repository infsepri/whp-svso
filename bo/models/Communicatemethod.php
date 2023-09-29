<?php
/**
 *
 * @package   Communicatemethod
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once('conf/connection.php');
//require_once("libraries/other/AesCipher.php");

class Communicatemethod{
  private $idcommunicatemethod;
  private $idcompany;
  private $typecommunication;
  private $usercommunication;
  private $passcommunication;
  private $typedoc;
  private $createdat;
  private $updatedat;
  private $createdby;
  private $updatedby;
  private static $con;


  const saft = 0;
  const webservice = 1;

  const iv = "Pv8zL3hNojwoSQuy";
  const key = "0Jt4DTf1Tw6YaRwY";

  public function __construct(){
    self::$con = new Connection();
  }

  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }


  public static function getSAFT()
  {
    return self::saft;
  }

  public static function getWebService()
  {
    return self::webservice;
  }



  public function save(){
    $sql = "INSERT INTO `communicatemethod`(`typecommunication`, 
                                            `usercommunication`, 
                                            `passcommunication`, 
                                            `idcompany`,
                                            `createdby`, 
                                            `updatedby`, 
                                            `typedoc`, 
                                            `createdat`, 
                                            `updatedat`) 
    VALUES (:typecommunication,:usercommunication,:passcommunication,:idcompany,:createdby,:updatedby,:typedoc,now(),now())";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
    return false;
    }

    $retval->bindParam(':typecommunication',             $this->typecommunication, PDO::PARAM_INT);
    $retval->bindParam(':usercommunication',            $this->usercommunication, PDO::PARAM_STR);
    $retval->bindParam(':passcommunication',             $this->name, PDO::PARAM_STR);
    $retval->bindParam(':idcompany',                  $this->idcompany, PDO::PARAM_INT);
    $retval->bindParam(':typedoc',             $this->typedoc, PDO::PARAM_INT);
    $retval->bindParam(':createdby',            $this->createdby, PDO::PARAM_INT);
    $retval->bindParam(':updatedby',            $this->updatedby, PDO::PARAM_INT);



    if($retval->execute()) {
    $id =  self::$con->lastInsertId();
    $retval->closeCursor();
    return $id;
    }

    $retval->closeCursor();
    return false;
  }




  public function update(){
    $sql = "UPDATE `communicatemethod` SET `typecommunication`=:typecommunication,`usercommunication`=:usercommunication,`passcommunication`=:passcommunication,`updatedat`=now(), `updatedby`=:updatedby WHERE `idcommunicatemethod`=:idcommunicatemethod ";
    $retval = self::$con->stmt_init();
    if(!$retval){
        return false;
      }
  
      $retval->bindParam(':typecommunication',             $this->typecommunication, PDO::PARAM_INT);
      $retval->bindParam(':usercommunication',            $this->usercommunication, PDO::PARAM_STR);
      $retval->bindParam(':passcommunication',             $this->passcommunication, PDO::PARAM_STR);
      $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);
      $retval->bindParam(':idcommunicatemethod',             $this->idcommunicatemethod, PDO::PARAM_INT);
  
  
      if($retval->execute()) {
        $retval->closeCursor();
        return true;
      }
  
      $retval->closeCursor();
      return false;
  }



  public static function findbyidcompany($idcompany, $limit, $typedoc=0){
    $arr=array();
    $sql= "SELECT communicatemethod.`idcommunicatemethod`, communicatemethod.`idcompany`, communicatemethod.`typecommunication`, communicatemethod.`usercommunication`, communicatemethod.`passcommunication`, communicatemethod.`createdat`, communicatemethod.`updatedat`, `user`.username FROM `communicatemethod` where communicatemethod.idcompany=:idcompany and communicatemethod.typedoc=:typedoc  order by createdat DESC limit :limit";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }
    
    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':limit', $limit, PDO::PARAM_INT);
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
