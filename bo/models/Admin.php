<?php
/**
 *
 * @package   Admin
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
  require_once('models/Entity.php');

class Admin  extends Entity{
    private $idadmin;
    private static $class;
    private static $con;

    public function __construct(){
      self::$class = $this;
      $com=Entity::getcon();
      if($com==null){
        Entity::setcon();
        $com=Entity::getcon();
      }
      self::$con = $com;
    }
    public function set($attribute, $content){
      $this->$attribute = $content;
    }
    public function get($attribute){
      return $this->$attribute;
    }


    public static function findbyentity($id){
     		return parent::findbyentity($id);
    }
    

  
    public static function findbyadmin($id){
      $sql = "SELECT *,entity.name as name, entity.identity from entity 
      
      LEFT join admin on admin.identity=entity.identity where entity.identity=:entity";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
        return null;
      }
      $retval->bindParam(':entity', $id, PDO::PARAM_INT);
      $retval->execute();
      if ($retval->rowCount() > 0) {
        $values = $retval->fetch(PDO::FETCH_OBJ);
        $retval->closeCursor();
        return $values;
      }

      $retval->closeCursor();
      return null;
    }

    public static function findAlltable($limitstart, $perpage, $order, $keyorder, $search,$idcompany){
  		$limitstart = intval($limitstart);
  		$perpage = intval($perpage);
      $sqlExtra = "";
      if(isset($idcompany)&&empty($idcompany)){
        $identity=2;
        $sqlExtra .= " AND entity.`idcompany`=".$idcompany;
      }
  		$sql = "SELECT `entity`.`identitytype`,
  										`entity`.`name` as nameentity,
                      `entity`.`state` as state,
                      `entity`.`statelogin` as statelogin,
  										`entity`.`email` as email,
                      `entity`.`photo` as photo,
                      `entity`.`numberhelth` as numberhelth,
  										`entity`.`mobilephone` as mobilephone,
  										 `entity`.`identity`,
  										 `admin`.`createdby` ,
  										 `entity`.`updatedby` as updatedbyentity ,
  										 `entity`.`updatedat`as updatedatentity,
                       entityUpdate.name as userUpd
  											FROM `entity`
  											LEFT JOIN `admin` ON `admin`.identity = `entity`.identity
                        LEFT JOIN `physiotherapist` ON `physiotherapist`.identity = `entity`.identity
                        LEFT JOIN `doctor` ON `doctor`.identity = `entity`.identity
                        LEFT JOIN `employee` ON `employee`.identity = `entity`.identity
                        LEFT JOIN `entity` as entityUpdate ON entityUpdate.identity = `entity`.`updatedby`
  		WHERE 	 entity.`statelogin`!=0 AND entity.identitytype!=2 AND entity.identitytype!=3 AND entity.identitytype!=5 AND entity.identitytype!=6 AND  `entity`.email!='mt@sepri.pt'	AND	(UPPER( `entity`.`identity`) LIKE UPPER(:search) OR UPPER(`entity`.`email`) LIKE UPPER(:search) OR  UPPER(`entity`.name) LIKE UPPER(:search) OR  UPPER(`entity`.numberhelth) LIKE UPPER(:search)  OR UPPER(`entity`.mobilephone) LIKE UPPER(:search)) ".$sqlExtra." ORDER BY $keyorder $order, entity.identity DESC LIMIT :start, :limit";
  		$retval =  self::$con->prepare($sql);
  		if(!$retval){
  			return null;
  		}

  		$retval->bindParam(':search', $search, PDO::PARAM_STR);
  		$retval->bindParam(':start', $limitstart, PDO::PARAM_INT);
  		$retval->bindParam(':limit', $perpage, PDO::PARAM_INT);
  		$retval->execute();

  		if ($retval->rowCount() > 0) {
  			$values = $retval->fetchAll(PDO::FETCH_OBJ);
  			$retval->closeCursor();
  			return $values;
  		}

  		$retval->closeCursor();
  		return null;
  	}


    public static function countalltable($search,$idcompany){

      $sqlExtra = "";
      if(isset($idcompany)&&empty($idcompany)){
        $identity=2;
        $sqlExtra .= " AND entity.`idcompany`=".$idcompany;
      }
      $sql= "SELECT count(`entity`.identity) as total 
										FROM `entity`
                    LEFT JOIN `admin` ON `admin`.identity = `entity`.identity
                    LEFT JOIN `physiotherapist` ON `physiotherapist`.identity = `entity`.identity
                    LEFT JOIN `doctor` ON `doctor`.identity = `entity`.identity
                    LEFT JOIN `employee` ON `employee`.identity = `entity`.identity
                    LEFT JOIN `entity` as entityUpdate ON entityUpdate.identity = `entity`.`updatedby`	
        WHERE entity.`statelogin`!=0 AND entity.identitytype!=2 AND entity.identitytype!=3 AND entity.identitytype!=5 AND entity.identitytype!=6  ".$sqlExtra." AND `entity`.email!='mt@sepri.pt' AND (UPPER(`entity`.identity) LIKE UPPER(:search) OR  UPPER(`entity`.name) LIKE UPPER(:search) OR  UPPER(`entity`.numberhelth) LIKE UPPER(:search)  OR UPPER(`entity`.mobilephone) LIKE UPPER(:search))";

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

    public function save() {
      if($this->identity==null){
        $aux=parent::save();
        if($aux==False){
          return false;
        }
      }else{
        $aux=$this->identity;
      }

      $sql = "INSERT INTO `admin` (`identity`,`type`,`createdby` , `updatedby`, `createdat`, `updatedat`) VALUES
      (:identity,:type,:createdby , :updatedby, now(), now())";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
        return false;
      }


      $retval->bindParam(':identity', $aux, PDO::PARAM_INT);
      $retval->bindParam(':type', $this->type, PDO::PARAM_INT);
      $retval->bindParam(':createdby', $this->createdby, PDO::PARAM_INT);
      $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);

      if($retval->execute()) {
        $id = self::$con->lastInsertId();
        $retval->closeCursor();
        //return array($aux, $id);
          return $aux;
      }

      $retval->closeCursor();
      return false;
    }

    public static function search($search='%', $limit=1, $idcompany){
      $sqlExtra = "";
      if(isset($idcompany)&&!empty($idcompany)){
        $sqlExtra .= " AND entity.`idcompany`=".$idcompany;
      }
      $sql = "SELECT *, idadmin as id, `entity`.`name` as select_show FROM `admin`
      INNER JOIN `entity` ON `entity`.identity = `admin`.identity
      WHERE  state=1 AND (UPPER(`entity`.`name`) LIKE UPPER(:search)) ".$sqlExtra." ORDER BY idadmin DESC LIMIT :limit";
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
      $sql = "SELECT *, `admin`.identity as id, `entity`.`name` as select_show FROM `admin`
      INNER JOIN `entity` ON `entity`.identity = `admin`.identity
      WHERE  state=1 AND type=2 AND (UPPER(`entity`.`name`) LIKE UPPER(:search)) ORDER BY idadmin DESC LIMIT :limit";
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



    public static function findbyadminid($id){
          $sql = "SELECT *,entity.name from admin
          inner join entity on admin.identity=entity.identity where admin.idadmin=:idadmin";
          $retval =  self::$con->prepare($sql);
          if(!$retval){
            return null;
          }
          $retval->bindParam(':idadmin', $id, PDO::PARAM_INT);
          $retval->execute();
          if ($retval->rowCount() > 0) {
            $values = $retval->fetch(PDO::FETCH_OBJ);
            $retval->closeCursor();
            return $values;
          }

          $retval->closeCursor();
          return null;
        }

        public function updatephoto(){
          $sql = "UPDATE `entity` SET  photo=:photo, `updatedby`=:updatedby,`updatedat`=now() WHERE identity=:identity";
          $retval =  self::$con->prepare($sql);
              if(!$retval){
                  file_put_contents("ENTITY_UPDATE_01.txt". print_r($retval->error, true));
                  return null;
              }
              $retval->bindParam(':identity', $this->identity, PDO::PARAM_INT);
              $retval->bindParam(':photo',      $this->photo, PDO::PARAM_STR);
              $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);
              if($retval->execute()) {
                  $retval->closeCursor();
                  return true;
              }
              $retval->closeCursor();
              file_put_contents("ENTITY_UPDATE_01.txt". print_r($retval->error, true));
              return false;
      }


      public function update() {


        $aux=parent::update();
        if($aux==false){
          return false;
        }
  
  
      $sql = "UPDATE `admin` SET `type`=:type, `updatedby`=:updatedby, `updatedat`=now() WHERE identity=:identity";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
        return false;
      }
  
      $retval->bindParam(':type', $this->type, PDO::PARAM_STR);
    
      $retval->bindParam(':identity', $this->identity, PDO::PARAM_INT);
      $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);
      if($retval->execute()) {
        $retval->closeCursor();
        return true;
      }
  
      $retval->closeCursor();
      return false;
    }

    public function delete() {
    
      $sql = "DELETE FROM `admin` WHERE identity=:identity";
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


}
