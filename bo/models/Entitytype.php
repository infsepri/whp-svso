<?php

/**
 *
 * @package   Entitytype
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once('conf/connection.php');

class Entitytype{
    private $identitytype;
    private $name;
    private $state;
    private $createdby;
    private $createdat;
    private $updatedat;
    private $updatedby;
    private static $class;
    private static $con;



    public function __construct(){
        Entitytype::$con = new Connection();
      self::$class = $this;
    }
    public function set($attribute, $content){
		$this->$attribute = $content;
	}
	public function get($attribute){
		return $this->$attribute;
	}


	public static function search($search='%', $limit=1){
		$sql = "SELECT *, identitytype as id, name as select_show FROM `entitytypes` WHERE (UPPER(name) LIKE UPPER(:search)) ORDER BY identitytype DESC LIMIT :limit";
        $retval =  self::$con->prepare($sql);
		if(!$retval){
            return -1;
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

  public static function search1($search='%', $limit=1){
		$sql = "SELECT *, identitytype as id, name as select_show FROM `entitytypes` WHERE identitytype!=2 AND identitytype!=3 AND identitytype!=5 AND identitytype!=6 AND  (UPPER(name) LIKE UPPER(:search)) ORDER BY identitytype DESC LIMIT :limit";
        $retval =  self::$con->prepare($sql);
		if(!$retval){
            return -1;
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

  public static function searchT($search='%', $limit=1){
		$sql = "SELECT *, identitytype as id, name as select_show FROM `entitytypes` WHERE  identitytype=1 OR identitytype=4  AND (UPPER(name) LIKE UPPER(:search)) ORDER BY identitytype DESC LIMIT :limit";
        $retval =  self::$con->prepare($sql);
		if(!$retval){
            return -1;
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

  public static function searchS($search='%', $limit=1){
    $sql = "SELECT *, identitytype as id, name as select_show FROM `entitytypes` WHERE identitytype=1 or identitytype=2 AND (UPPER(name) LIKE UPPER(:search)) ORDER BY identitytype DESC LIMIT :limit";
        $retval =  self::$con->prepare($sql);
    if(!$retval){
            return -1;
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
        $sql = "SELECT * FROM `entitytypes`
        WHERE identitytype=:identitytype";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
        return false;
        }

		$retval->bindParam(':identitytype', $id, PDO::PARAM_INT);
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
    }


    public static function allentitytypes(){

      		$sql = "SELECT `identitytype`,
                                  `entitytypes`.`name`,
                                  `entitytypes`.`state`,
                                  `entitytypes`.`createdat`,
                                  `entitytypes`.`updatedby`,
                                  `entitytypes`. `updatedat`
                                   FROM `entitytypes`";
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

  }
