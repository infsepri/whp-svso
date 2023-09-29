<?php

/**
 *
 * @package   Unit
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once('conf/connection.php');


class Unit{
  private $idunit;
  private $name;
  private $idcompany;
  private $typeitem;
  private $state;
  private $createdat;
  private $updatedat;
  private $createdby;
  private $updatedby;
  private static $con;
  private static $class;

  public function __construct(){
    self::$con = new Connection();
    self::$class = $this;
  }

  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }


public function savedefault($idcompany){
  $this->typeitem = 0;
  $this->idcompany = $idcompany;
  $this->name = 'Un.';
  $this->save();
  $this->typeitem = 1;
  $this->idcompany = $idcompany;
  $this->name = 'Un.';
  $this->save();
  $this->typeitem = 2;
  $this->idcompany = $idcompany;
  $this->name = 'Outro';
  $this->save();
}


  public function update(){
    $sql = "UPDATE `unit` SET typeitem=:typeitem,`updatedby`=:updatedby, `name`=:name,`updatedat`=now() WHERE idunit=:idunit";
    $retval =  self::$con->prepare($sql);
          if(!$retval){
              return null;
          }
        $retval->bindParam(':idunit', $this->idunit, PDO::PARAM_INT);
        $retval->bindParam(':name', $this->name, PDO::PARAM_STR);
        $retval->bindParam(':typeitem', $this->typeitem, PDO::PARAM_INT);
        $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);


        if($retval->execute()) {
            $retval->closeCursor();
            return true;
        }

        $retval->closeCursor();
        return false;

  }



  public static function findbycompany($idcompany){

    $sql= "SELECT `idunit`, typeitem, `name` FROM `unit` WHERE idcompany=:idcompany order by typeitem asc";
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





  public static function findbyname($idcompany, $desc){

    $sql= "SELECT `idunit`, typeitem, `name` FROM `unit` WHERE idcompany=:idcompany and name like :desc order by typeitem asc";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':desc', $desc, PDO::PARAM_STR);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }



  public static function findbyid($idcompany, $id){
    $sql= "SELECT `idunit`, typeitem, `name`, `name` as description FROM `unit` WHERE idcompany=:idcompany and idunit=:idunit order by typeitem asc";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':idunit', $id, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }




  public static function findbystate($idcompany, $state, $type=-1){
    if($type==-1){
      $type=null;
    }
    $sql= "SELECT `idunit` as id, typeitem, `name` as select_show, `name` as description FROM `unit` WHERE idcompany=:idcompany and state=:state and typeitem=IFNULL(:typeitem,typeitem) order by typeitem asc";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':state', $state, PDO::PARAM_INT);
    $retval->bindParam(':typeitem', $type, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetchAll(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


  public static function findbycompanyandtypeitem($idcompany, $type){
    $sql= "SELECT `idunit`, typeitem, `name` FROM `unit` WHERE idcompany=:idcompany and typeitem=:typeitem order by typeitem asc";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':typeitem', $type, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


  public static function findAll($idcompany, $limitstart, $perpage, $keyorder, $order, $search){
    $values=array();
    $sql= "SELECT `idunit`,  `name`, `typeitem` FROM unit     where idcompany=:idcompany and UPPER(name) LIKE UPPER(:search) order by $keyorder $order, idunit DESC limit :start, :limit";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return null;
    }

    $retval->bindParam(':search', $search, PDO::PARAM_STR);
    $retval->bindParam(':start',  $limitstart, PDO::PARAM_INT);
    $retval->bindParam(':limit',  $perpage, PDO::PARAM_INT);
    $retval->bindParam(':idcompany',$idcompany, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
        $values = $retval->fetchAll(PDO::FETCH_OBJ);
        $retval->closeCursor();
        return $values;
    }

    $retval->closeCursor();
    return null;
  }




  public static function countall( $idcompany, $search){
    $values=array();
    $sql= "SELECT count(idunit) as total FROM unit
     where idcompany=:idcompany and UPPER(name) LIKE UPPER(:search) ";
   $retval =  self::$con->prepare($sql);
   if(!$retval){
   return 0;
   }

   $retval->bindParam(':search', $search, PDO::PARAM_STR);
   $retval->bindParam(':idcompany',$idcompany, PDO::PARAM_INT);
   $retval->execute();

   if ($retval->rowCount() > 0) {
   $values = $retval->fetch(PDO::FETCH_OBJ);
   $retval->closeCursor();
   return $values->total;
   }

   $retval->closeCursor();
   return 0;
  }



  public function save(){
    $sql = "INSERT INTO `unit`(typeitem, `idcompany`, `name`,`state`,`createdat`, `updatedat`,`createdby`, `updatedby`) VALUES (:typeitem, :idcompany, :name,:state, now(), now(),:createdby,:updatedby)";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
    return false;
    }


    $retval->bindParam(':typeitem',             $this->typeitem, PDO::PARAM_INT);
    $retval->bindParam(':idcompany',            $this->idcompany, PDO::PARAM_INT);
    $retval->bindParam(':name',             $this->name, PDO::PARAM_STR);
    $retval->bindParam(':state',            $this->state, PDO::PARAM_INT);
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





}
?>
