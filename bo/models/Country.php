<?php
/**
 *
 * @package   Country
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once(realpath( dirname( __FILE__ ) ).'/../conf/connection.php');

class Country{
  private $idcountry;
  private $description;
  private $abbreviation;
  private $idcontinent;
  private $createdat;
  private $updatedat;
  private $type;
  private static $con;
  private static $class;


  public function __construct(){
    self::$con = new Connection();
    self::$class = $this;
  }


  public function getattribute() {
    return  get_object_vars($this);
  }


  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }




  public static function findAllwithregion(){

    $sql= "SELECT IF(region.idregion is not null, CONCAT(country.`idcountry`,'-',region.idregion) , country.`idcountry`) as idcountry, IF(region.abbreviation is not null , region.abbreviation ,country.`abbreviation`) as abbreviation, IF(region.description is not null, region.description,country.`description`) as description, country.idcontinent FROM country left join region on region.idcountry=country.idcountry order by description ASC";
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


  public static function findAll(){

    $sql= "SELECT `idcountry`, `abbreviation`, `description`, idcontinent  FROM country";
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


  public static function countall($search){
    $sql= "SELECT count(idcountry) as total FROM `country` WHERE (UPPER(abbreviation) LIKE UPPER(:search) OR
                                                                        UPPER(description) LIKE UPPER(:search) OR
                                                                        UPPER(idcontinent) LIKE UPPER(:search))";

$retval =  self::$con->prepare($sql);
    if(!$retval){
        return 0;
    }

    $retval->bindParam(':search', $search, PDO::PARAM_STR);
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
    $sql = "INSERT INTO country ( abbreviation, `description`,callingcode,paypalabbr, `type`,`state`,createdat, updatedat, idcontinent) VALUES (:abbreviation,:description,:paypalabbr,:callingcode,type,:state,now(),now(),:idcontinent)";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return false;
    }

    $retval->bindParam(':abbreviation', $this->abbreviation, PDO::PARAM_STR);
    $retval->bindParam(':description', $this->description, PDO::PARAM_STR);
    $retval->bindParam(':callingcode', $this->callingcode, PDO::PARAM_STR);
    $retval->bindParam(':paypalabbr', $this->paypalabbr, PDO::PARAM_STR);
    $retval->bindParam(':idcontinent', $this->idcontinent, PDO::PARAM_INT);
    $retval->bindParam(':type', $this->type, PDO::PARAM_INT);
    $retval->bindParam(':state', $this->state, PDO::PARAM_INT);

    if($retval->execute()) {
        $id =  self::$con->lastInsertId();
        $retval->closeCursor();
        return $id;
    }

    $retval->closeCursor();
    return false;
  }



  public static function findbyabbreviation($abbreviation){

    $sql= "SELECT `idcountry`, `abbreviation`, `description`, idcontinent, `type`  FROM country WHERE  abbreviation like :abbreviation limit 1";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':abbreviation', $abbreviation, PDO::PARAM_STR);
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
  }


  public static function findbycontinent($idcontinent){

    $sql= "SELECT `idcountry`, `abbreviation`, `description`, idcontinent  FROM country WHERE idcontinent=:idcontinent order by idcountry ASC";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':idcontinent', $idcontinent, PDO::PARAM_INT);
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
  }



  public static function getregionbycontinent($idcontinent){
    $sql= " SELECT IF(region.idregion is not null, CONCAT(country.`idcountry`,'-',region.idregion) , country.`idcountry`) as idcountry, IF(region.abbreviation is not null , region.abbreviation ,country.`abbreviation`) as abbreviation, IF(region.description is not null, region.description,country.`description`) as description, country.idcontinent  FROM country left join region on region.idcountry=country.idcountry WHERE idcontinent=:idcontinent  order by description ASC";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':idcontinent', $idcontinent, PDO::PARAM_INT);
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
  }

  public static function search($search='%', $limit=1){

  $sql = "SELECT *, idcountry as id, description as select_show FROM `country` WHERE (UPPER(description) LIKE UPPER(:search))  ORDER BY idcountry DESC LIMIT :limit";
$retval =  self::$con->prepare($sql);
  if(!$retval){
      return null;
  }

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

public static function findbyid($id){
    $sql = "SELECT `idcountry`, `description`, `state`, `createdat`, `updatedat` FROM `country` WHERE idcountry=:idcountry";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
    return false;
    }

$retval->bindParam(':idcountry', $id, PDO::PARAM_INT);
$retval->execute();

if ($retval->rowCount() > 0) {
  $values = $retval->fetch(PDO::FETCH_OBJ);
  $retval->closeCursor();
  return $values;
}

}

}

?>
