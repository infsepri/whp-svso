<?php
/**
 *
 * @package   District
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once('conf/connection.php');

class District{
    private $iddistrict;
    private $code;
    private $description;
    private $idcountry;
    private $state;


    private $createdby;
    private $createdat;
    private $updatedat;
    private $updatedby;
    private static $class;
    private static $con;



    public function __construct(){
        District::$con = new Connection();
      self::$class = $this;
    }


    public function save(){
        $sql = "INSERT INTO `district` (code,description,idcountry,state,createdat, updatedat,createdby, updatedby) VALUES (:code,:description,:idcountry,:state,now(),now(), :createdby,:updatedby)";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
        return false;
        }

        $retval->bindParam(':code',             $this->code, PDO::PARAM_STR);
        $retval->bindParam(':description',              $this->description, PDO::PARAM_STR);
        $retval->bindParam(':idcountry',             $this->idcountry, PDO::PARAM_INT);
        $retval->bindParam(':state',             $this->state, PDO::PARAM_INT);

        $retval->bindParam(':createdby',            $this->createdby, PDO::PARAM_INT);
        $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);

        if($retval->execute()) {
        $id =  self::$con->lastInsertId();
        $retval->closeCursor();
        return $id;
        }

        $retval->closeCursor();
        return false;
    }
    public function update(){
        $sql = "UPDATE `district` SET code=:code,description=:description,idcountry=:idcountry,enddate=:enddate,state=:state,`updatedby`=:updatedby,`updatedat`=now() WHERE iddistrict=:iddistrict";
        $retval =  self::$con->prepare($sql);
            if(!$retval){
                return null;
            }
            $retval->bindParam(':iddistrict', $this->iddistrict, PDO::PARAM_STR);
            $retval->bindParam(':code', $this->code, PDO::PARAM_INT);
            $retval->bindParam(':description',              $this->description, PDO::PARAM_STR);
            $retval->bindParam(':idcountry',             $this->idcountry, PDO::PARAM_INT);
            $retval->bindParam(':state',             $this->state, PDO::PARAM_INT);


            $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);


            if($retval->execute()) {
                $retval->closeCursor();
                return true;
            }

            $retval->closeCursor();
            return false;
    }



    public static function findAll(){

        $sql= "SELECT `iddistrict`, `code`, `description`,`idcountry`, state  FROM district";
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
        $sql= "SELECT count(iddistrict) as total FROM `district` WHERE (UPPER(code) LIKE UPPER(:search) OR
                                                                            UPPER(description) LIKE UPPER(:search) OR
                                                                            UPPER(idcountry) LIKE UPPER(:search))";

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

    public static function search($search='%', $limit=1){

    $sql = "SELECT *, iddistrict as id, description as select_show FROM `district` WHERE (UPPER(description) LIKE UPPER(:search)) AND state=1 ORDER BY iddistrict DESC LIMIT :limit";
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
        $sql = "SELECT `iddistrict`, `description`, `state`, `createdat`, `updatedat` FROM `district` WHERE iddistrict=:iddistrict";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
          return false;
        }

          $retval->bindParam(':iddistrict', $id, PDO::PARAM_INT);
          $retval->execute();

          if ($retval->rowCount() > 0) {
            $values = $retval->fetch(PDO::FETCH_OBJ);
            $retval->closeCursor();
            return $values;
          }

    }



    public static function findbydesc($desc){
        $sql = "SELECT `iddistrict`, `description`, `state`, `createdat`, `updatedat` FROM `district` WHERE description like :descr";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
          return false;
        }

          $retval->bindParam(':descr', $desc, PDO::PARAM_STR);
          $retval->execute();

          if ($retval->rowCount() > 0) {
            $values = $retval->fetch(PDO::FETCH_OBJ);
            $retval->closeCursor();
            return $values;
          }

    }


}

?>
