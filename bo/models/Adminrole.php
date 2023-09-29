
<?php
/**
*
* @package    Adminrole
* @package    Models
* @copyright  2020 SEPRI
*
*/
require_once('conf/connection.php');

class Adminrole{
    private $idrole;
    private $identity;
    private $createdby;
    private $createdat;
    private $updatedat;
    private $updatedby;
    private static $class;
    private static $con;

    public function __construct(){
      Adminrole::$con = new Connection();
      self::$class = $this;
    }
    public function set($attribute, $content){
        $this->$attribute = $content;
    }
    public function get($attribute){
        return $this->$attribute;
    }


    public function save() {
        $sql = "INSERT INTO `admin_role` (`idrole`, `identity`,createdat, updatedat,createdby, updatedby) VALUES (:idrole, :identity,now(),now(), :createdby,:updatedby)";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
            return false;
        }

        $retval->bindParam(':idrole', $this->idrole, PDO::PARAM_INT);
        $retval->bindParam(':identity', $this->identity, PDO::PARAM_INT);
        $retval->bindParam(':createdby',              $this->createdby, PDO::PARAM_INT);
        $retval->bindParam(':updatedby',              $this->updatedby, PDO::PARAM_INT);


        if($retval->execute()) {
            $retval->closeCursor();
            return true;
        }

        $retval->closeCursor();
        return false;
    }

    public static function findbyadmin($identity){
        $arr=array();
        $sql= "SELECT idrole FROM `admin_role` WHERE `admin_role`.`identity`=:identity";
        //$sql= "SELECT admin_role.idrole FROM `admin_role` INNER JOIN `admin` ON admin.idadmin = `admin_role`.`identity` WHERE admin_role.identity=:identity ";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
            return null;
        }

        $retval->bindParam(':identity', $identity, PDO::PARAM_INT);
        $retval->execute();

        if ($retval->rowCount() > 0) {
            $values = $retval->fetchAll(PDO::FETCH_COLUMN);
            $retval->closeCursor();
            return $values;
        }

        $retval->closeCursor();
        return null;
    }

    public static function findbyidentitylist($identity){
    
        $values=array();
  
        $sql= "SELECT  entity.email,admin_role.`identity`,role.name FROM `admin_role`
         inner join role on role.idrole = admin_role.idrole
        inner join entity on entity.identity = admin_role.identity WHERE admin_role.identity=:identity";
        $retval = self::$con->prepare($sql);
        if(!$retval)
        {
          return -1;
        }else{
          $retval->bindParam(':identity', $identity, PDO::PARAM_INT);
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


    public function delete() {
    
        $sql = "DELETE FROM `admin_role` WHERE identity=:identity";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
            return false;
        }

        $retval->bindParam(':identity', $this->identity, PDO::PARAM_INT);

        if($retval->execute()) {
              $retval =  self::$con->prepare($sql);
            $retval->closeCursor();
            return true;
        }
        $retval->closeCursor();
        return false;
    }


    public static function findbyadminid($identity){
    
        $values=array();

        $sql= "SELECT  admin_role.`identity`, admin_role.`idrole`, admin_role.`createdat`, admin_role.`updatedat`, role.name FROM `admin_role` inner join role on role.idrole = admin_role.idrole WHERE admin_role.identity=:identity";
        $retval = self::$con->prepare($sql);
        if(!$retval)
        {
          return -1;
        }else{
          $retval->bindParam(':identity', $identity, PDO::PARAM_INT);
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


}

?>
