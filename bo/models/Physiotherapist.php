<?php

/**
 *
 * @package   Physiotherapist
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */ 

  require_once('models/Entity.php');

class Physiotherapist  extends Entity{
    private $idphysiotherapist;
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
     		return parent::findbyid($id);
  	}

    public static function findbyphysiotherapist($id){
      $sql = "SELECT *,entity.name as name, entity.identity from entity 
      
      LEFT join employee on employee.identity=entity.identity where entity.identity=:entity";
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
  		WHERE 	 entity.`statelogin`!=0 AND entity.identitytype!=1 AND entity.identitytype!=4 AND entity.identitytype!=2 AND entity.identitytype!=5 AND entity.identitytype!=6 AND   `entity`.email!='mt@sepri.pt'	AND	(UPPER( `entity`.`identity`) LIKE UPPER(:search) OR UPPER(`entity`.`email`) LIKE UPPER(:search) OR  UPPER(`entity`.name) LIKE UPPER(:search) OR  UPPER(`entity`.numberhelth) LIKE UPPER(:search)  OR UPPER(`entity`.mobilephone) LIKE UPPER(:search)) ".$sqlExtra." ORDER BY $keyorder $order, entity.identity DESC LIMIT :start, :limit";
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
        WHERE entity.`statelogin`!=0 AND entity.identitytype!=1   AND entity.identitytype!=2 AND entity.identitytype!=4  AND entity.identitytype!=5 AND entity.identitytype!=6 ".$sqlExtra." AND `entity`.email!='mt@sepri.pt' AND (UPPER(`entity`.identity) LIKE UPPER(:search) OR  UPPER(`entity`.name) LIKE UPPER(:search) OR  UPPER(`entity`.numberhelth) LIKE UPPER(:search)  OR UPPER(`entity`.mobilephone) LIKE UPPER(:search))";

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

      $sql = "INSERT INTO `physiotherapist` (`identity`,`createdby` , `updatedby`, `createdat`, `updatedat`) VALUES
      (:identity,:createdby , :updatedby, now(), now())";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
        return false;
      }


      $retval->bindParam(':identity', $aux, PDO::PARAM_INT);
      $retval->bindParam(':createdby', $this->createdby, PDO::PARAM_INT);
      $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);

      if($retval->execute()) {
        $id = self::$con->lastInsertId();
        $retval->closeCursor();
        return array($aux, $id);
      }

      $retval->closeCursor();
      return false;
    }

    public static function search($search='%', $limit=1, $schoolDefault){
      $sqlExtra = "";
      if(isset($schoolDefault)&&!empty($schoolDefault)){
        $sqlExtra .= " AND entity.`idschool`=".$schoolDefault;
      }
      $sql = "SELECT *, `entity`.`identity` as id, `entity`.`name` as select_show FROM `physiotherapist`
      INNER JOIN `entity` ON `entity`.identity = `physiotherapist`.identity
      WHERE  state=1 AND (UPPER(`entity`.`name`) LIKE UPPER(:search))".$sqlExtra." ORDER BY `entity`.`identity` DESC LIMIT :limit";
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


    public static function findbyphysiotherapistid($id){
      		$sql = "SELECT *,entity.name from physiotherapist
          inner join entity on physiotherapist.identity=entity.identity where physiotherapist.idphysiotherapist=:idphysiotherapist";
      		$retval =  self::$con->prepare($sql);
      		if(!$retval){
      			return null;
      		}
      		$retval->bindParam(':idphysiotherapist', $id, PDO::PARAM_INT);
      		$retval->execute();
      		if ($retval->rowCount() > 0) {
      			$values = $retval->fetch(PDO::FETCH_OBJ);
      			$retval->closeCursor();
      			return $values;
      		}

      		$retval->closeCursor();
      		return null;
      	}

        public static function findbyphysiotherapistbyid($id){
              $sql = "SELECT *,entity.name,`entity`.`identitycard`,`entity`.`idschool` from physiotherapist
              inner join entity on physiotherapist.identity=entity.identity where physiotherapist.identity=:identity";
              $retval =  self::$con->prepare($sql);
              if(!$retval){
                return null;
              }
              $retval->bindParam(':identity', $id, PDO::PARAM_INT);
              $retval->execute();
              if ($retval->rowCount() > 0) {
                $values = $retval->fetch(PDO::FETCH_OBJ);
                $retval->closeCursor();
                return $values;
              }

              $retval->closeCursor();
              return null;
            }


        public static function searchphysiotherapist($hourstart=null, $hourend=null, $iddiscipline=null, $iddayweek=null, $search='%', $limit=1, $schoolDefault){

          $sqlExtra = ""; $sqlExtra1 = ""; $joinExtra = "";
          if(isset($schoolDefault)&&!empty($schoolDefault)){
            $sqlExtra1 .= " AND entity.`idschool`=".$schoolDefault;
          }
          if(!empty($iddiscipline)) {
            $iddiscipline = (int)$iddiscipline;
            $sqlExtra .= " AND iddiscipline=".$iddiscipline;
          }
        /*  if(!empty($iddayweek)) {
            $iddayweek = (int)$iddayweek;
            $joinExtra .= " INNER JOIN availability_dayweek ON availability_dayweek.idphysiotherapistavailability = physiotherapistavailability.idphysiotherapistavailability";
            $sqlExtra .= " AND availability_dayweek.iddayweek=".$iddayweek;
          }*/
          if(!empty($hourstart)) {
            $hourstart = ($hourstart);
            $sqlExtra .= " AND hour_start <= '".$hourstart."' AND hour_end > '".$hourstart."'";
          }
          if(!empty($hourend)) {
            $hourend = ($hourend);
            $sqlExtra .= " AND hour_end >= '".$hourend."'";
          }

          $sql = "SELECT physiotherapist.*, physiotherapist.identity as id, `entity`.`name` as select_show, physiotherapistavailability.idphysiotherapistavailability FROM `physiotherapist`
          INNER JOIN `entity` ON `entity`.identity = `physiotherapist`.identity
          INNER JOIN physiotherapistavailability ON physiotherapist.identity = physiotherapistavailability.idphysiotherapist
          ".$joinExtra."
          WHERE  entity.state=1 AND (UPPER(`entity`.`name`) LIKE UPPER(:search))  ".$sqlExtra." ".$sqlExtra1." ORDER BY physiotherapist.identity DESC LIMIT :limit";
              $retval =  self::$con->prepare($sql);
          if(!$retval){
                  return -1;
          }

          $retval->bindParam(':search', $search, PDO::PARAM_STR);
          $retval->bindParam(':limit', $limit, PDO::PARAM_INT);
          $retval->execute();

          if ($retval->rowCount() > 0) {
            $values = array();
            while($row = $retval->fetch(PDO::FETCH_OBJ)) {
              $isAvailable = true;
              foreach ($iddayweek as $id) {
                $sqlDay = "SELECT * FROM availability_dayweek WHERE availability_dayweek.idphysiotherapistavailability=:idphysiotherapistavailability AND availability_dayweek.iddayweek=:iddayweek";
                $retvalDay =  self::$con->prepare($sqlDay);
                $retvalDay->bindParam(':idphysiotherapistavailability', $row->idphysiotherapistavailability, PDO::PARAM_INT);
                $retvalDay->bindParam(':iddayweek', $id, PDO::PARAM_INT);
                $retvalDay->execute();
                if ($retvalDay->rowCount() <= 0) {
                    $retvalDay->closeCursor();
                    $isAvailable = false; break;
                }
                $retvalDay->closeCursor();
              }
              if($isAvailable) {
                $values[] = $row;
              }

            }
            $retval->closeCursor();
            return $values;
          }

          $retval->closeCursor();
          return null;
        }

        public static function countallphysiotherapist($state=null, $schoolDefault){
         $sqlExtra1 = ""; 
          if(isset($schoolDefault)&&!empty($schoolDefault)){
            $sqlExtra1 .= " AND entity.`idschool`=".$schoolDefault;
          }
          $sql= "SELECT count(idphysiotherapist) as total FROM `physiotherapist`
          INNER JOIN entity ON entity.identity = physiotherapist.identity
           WHERE entity.state=IFNULL(:state, entity.state)  ".$sqlExtra1."";

          $retval =  self::$con->prepare($sql);
          if(!$retval){
            return 0;
          }

          $retval->bindParam(':state', $state, PDO::PARAM_INT);
          $retval->execute();

          if ($retval->rowCount() > 0) {
            $values = $retval->fetch(PDO::FETCH_OBJ);
            $retval->closeCursor();
            return $values->total;
          }

          $retval->closeCursor();
          return 0;
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

      public function delete() {
    
        $sql = "DELETE FROM `physiotherapist` WHERE identity=:identity";
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


      
      public static function countdata(){
        $sql= "SELECT count(idphysiotherapist) as total FROM `physiotherapist`";

        $retval =  self::$con->prepare($sql);
        if(!$retval){
          return 0;
        }

        $retval->execute();

        if ($retval->rowCount() > 0) {
          $values = $retval->fetch(PDO::FETCH_OBJ);
          $retval->closeCursor();
          return $values->total;
        }
        $retval->closeCursor();
        return 0;
      }
}

?>
