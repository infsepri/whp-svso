<?php

/**
 *
 * @package   Language
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once('conf/connection.php');

class Language{
  private $idlanguage;
  private $name;

  private $createdat;
  private $updatedat;
  private static $con;
  private static $class;


	public function __construct(){
		Language::$con = new Connection();
		self::$class = $this;
	}
	public function set($attribute, $content){
		$this->$attribute = $content;
	}
	public function get($attribute){
		return $this->$attribute;
	}
	public function getattribute() {
		return (object)get_object_vars($this);
	}

public function checklanguage(){
  if(self::findAll()==null){
    $this->name = "Português";
    $this->save();
  }
  return 0;
}

public function save(){
  $sql = "INSERT INTO language ( name,   state,createdat, updatedat) VALUES (:name, :state,now(),now())";
  $retval =  self::$con->prepare($sql);
  if(!$retval){
  return false;
  }

  $retval->bindParam(':name',             $this->name, PDO::PARAM_STR);
  $retval->bindParam(':state',             $this->state, PDO::PARAM_INT);

  if($retval->execute()) {
  $id =  self::$con->lastInsertId();
  $retval->closeCursor();
  return $id;
  }

  $retval->closeCursor();
  return false;
}

public static function findbydescription($description){
  $languages=array();
  $sql= "SELECT `idlanguage`, `name`, `createdat`, `updatedat`  FROM language where name=:name";

  $retval =  self::$con->prepare($sql);
  if(!$retval){
    return null;
  }

  $retval->bindParam(':name', $description, PDO::PARAM_STR);
  $retval->execute();

  if ($retval->rowCount() > 0) {
    $values = $retval->fetch(PDO::FETCH_OBJ);
    $retval->closeCursor();
    return $values;
  }

  $retval->closeCursor();
  return null;
}

  public static function findbycompany($idcompany){

    $sql= "SELECT language.idlanguage, language.name,   language.createdat, language.updatedat  FROM language inner  join company on language.idlanguage=company.idlanguage where company.idcompany=:idcompany LIMIT 1";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $retval->setFetchMode(PDO::FETCH_CLASS, 'Language');
      $values = $retval->fetch();
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


  public static function findAll(){

    $sql= "SELECT `idlanguage`, `name`,  `createdat`, `updatedat`  FROM language";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }

  public static function findAllOrderby($id){

    $sql= "SELECT `idlanguage`, `name`,   `createdat`, `updatedat`  FROM language order by idlanguage =:idlanguage DESC, idlanguage ASC";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idlanguage',$id, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }

  public static function findAllobj(){
    $sql= "SELECT `idlanguage`, `name`,  `createdat`, `updatedat`  FROM language";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetchAll(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


  public static function findbyname($name){

      $sql= "SELECT `idlanguage`, `name`,  `createdat`, `updatedat`  FROM language where name=:name";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
        return null;
      }

      $retval->bindParam(':name',$name, PDO::PARAM_STR);
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
