
<?php
/**
*
* @package   Identifytypes
* @package    Models
* @copyright  2020 SEPRI
*
*/
require_once('conf/connection.php');

class Identifytypes{
    private $identitycard;
    private $name;
   
    private $state;
    private $createdat;
    private $updatedat;
    private static $class;
    private static $con;



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


	public static function search($search='%', $limit=1){
		$sql = "SELECT *, identitytype as id, name as select_show FROM `entitytypes` WHERE state=1 and (UPPER(name) LIKE UPPER(:search)) ORDER BY identitytype DESC LIMIT :limit";
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
        $sql = "SELECT * FROM `entitytypes` WHERE identitytype=:identitytype";
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
    
    }

}