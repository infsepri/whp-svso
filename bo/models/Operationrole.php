
<?php
/**
*
* @package  Operationrole
* @package    Models
* @copyright  2020 SEPRI
*
*/
require_once('conf/connection.php');

class Operationrole{
    private $idrole;
    private $idoperation;
    private $type;
    private static $class;
    private static $con;

    public function __construct(){
      Operationrole::$con = new Connection();
      self::$class = $this;
    }
    public function set($attribute, $content){
        $this->$attribute = $content;
    }
    public function get($attribute){
        return $this->$attribute;
    }


    public function save() {
        $sql = "INSERT INTO `operation_role` (`idrole`, `idoperation`,`type`,createdat, updatedat,createdby, updatedby) VALUES (:idrole, :idoperation,:type,now(),now(), :createdby,:updatedby)";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
            return false;
        }

        $retval->bindParam(':idrole', $this->idrole, PDO::PARAM_INT);
        $retval->bindParam(':idoperation', $this->idoperation, PDO::PARAM_INT);
        $retval->bindParam(':type', $this->type, PDO::PARAM_INT);
        $retval->bindParam(':createdby',              $this->createdby, PDO::PARAM_INT);
        $retval->bindParam(':updatedby',              $this->updatedby, PDO::PARAM_INT);


        if($retval->execute()) {
            $retval->closeCursor();
            return true;
        }

        $retval->closeCursor();
        return false;
    }

    public static function findbyrole($idrole,$type){
        $arr=array();
        $sql= "SELECT idoperation FROM `operation_role` WHERE `idrole`=:idrole AND `type`=:type";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
            return null;
        }

        $retval->bindParam(':idrole', $idrole, PDO::PARAM_INT);
        $retval->bindParam(':type', $type, PDO::PARAM_INT);
        $retval->execute();

        if ($retval->rowCount() > 0) {
            $values = $retval->fetchAll(PDO::FETCH_COLUMN);
            $retval->closeCursor();
            return $values;
        }

        $retval->closeCursor();
        return null;
    }

    public function delete() {
        $sql = "DELETE FROM `operation_role` WHERE idrole=:idrole";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
            return false;
        }

        $retval->bindParam(':idrole', $this->idrole, PDO::PARAM_INT);

        if($retval->execute()) {
              $retval =  self::$con->prepare($sql);
            $retval->closeCursor();
            return true;
        }
        $retval->closeCursor();
        return false;
    }

}

?>
