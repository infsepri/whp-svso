<?php

/**
 *
 * @package   Doctor
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */ 

  require_once('models/Entity.php');

class Doctor  extends Entity{
    private $iddoctor;
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

    public static function findbydoctor($id){
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



  	public static function findbydoctorblock($id){
  		$sql = "SELECT *, doctor.identity as doctorId from entity inner join doctor on doctor.identity=entity.identity where entity.identity=:entity";
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
  		WHERE 	 entity.`statelogin`!=0 AND entity.identitytype!=1 AND entity.identitytype!=4 AND entity.identitytype!=3 AND entity.identitytype!=5 AND entity.identitytype!=6 AND   `entity`.email!='mt@sepri.pt'	AND	(UPPER( `entity`.`identity`) LIKE UPPER(:search) OR UPPER(`entity`.`email`) LIKE UPPER(:search) OR  UPPER(`entity`.name) LIKE UPPER(:search) OR  UPPER(`entity`.numberhelth) LIKE UPPER(:search)  OR UPPER(`entity`.mobilephone) LIKE UPPER(:search)) ".$sqlExtra." ORDER BY $keyorder $order, entity.identity DESC LIMIT :start, :limit";
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
        WHERE entity.`statelogin`!=0 AND entity.identitytype!=1   AND entity.identitytype!=3 AND entity.identitytype!=4  AND entity.identitytype!=5 AND entity.identitytype!=6 ".$sqlExtra." AND `entity`.email!='mt@sepri.pt' AND (UPPER(`entity`.identity) LIKE UPPER(:search) OR  UPPER(`entity`.name) LIKE UPPER(:search) OR  UPPER(`entity`.numberhelth) LIKE UPPER(:search)  OR UPPER(`entity`.mobilephone) LIKE UPPER(:search))";

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

    public static function findAlltablepdf($state){


      $sql = "SELECT `doctor`.`iddoctor`,`entity`.`name` as nameentity,
                      `entity`.`photo` as photo,
                      `entity`.`email` as email,
                      `entity`.`state` as state,
                      `entity`.`mobilephone` as mobilephone,
                      `doctor`.`identity`,
                      `doctor`.`createdby` ,
                      `entity`.`updatedby` as updatedbyentity ,
                      `entity`.`updatedat`as updatedatentity ,
                      entityUpdate.name as userUpd  FROM `doctor`
                                                                  INNER JOIN `entity` ON `entity`.identity = `doctor`.identity
                                                                  INNER JOIN `entity` as entityUpdate ON entityUpdate.identity = `doctor`.`updatedby`
                                                                  WHERE `entity`.`state`=:state ORDER BY `entity`.`name` ASC";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
        return null;
      }

      $retval->bindParam(':state', $state, PDO::PARAM_INT);
      $retval->execute();

      if ($retval->rowCount() > 0) {
        $values = $retval->fetchAll(PDO::FETCH_OBJ);
        $retval->closeCursor();
        return $values;
      }

      $retval->closeCursor();
      return null;
    }

    public static function countall1($search, $state=null,$schoolDefault){
      $sqlExtra = "";
      if(isset($schoolDefault)&&!empty($schoolDefault)){
        $sqlExtra .= " AND entity.`idschool`=".$schoolDefault;
      }
      $sql= "SELECT count(iddoctor) as total FROM `doctor`
      INNER JOIN `entity` ON `entity`.identity = `doctor`.identity
      INNER JOIN `entity` as entityUpdate ON entityUpdate.identity = `doctor`.`updatedby`
       WHERE (UPPER(iddoctor) LIKE UPPER(:search)) AND entity.state=IFNULL(:state, entity.state) ".$sqlExtra."";

      $retval =  self::$con->prepare($sql);
      if(!$retval){
        return 0;
      }

      $retval->bindParam(':search', $search, PDO::PARAM_STR);
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

    public function save() {
      if($this->identity==null){
        $aux=parent::save();
        if($aux==False){
          return false;
        }
      }else{
        $aux=$this->identity;
      }

      $sql = "INSERT INTO `doctor` (`identity`,`createdby` , `updatedby`, `createdat`, `updatedat`) VALUES
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
      $sql = "SELECT *, `entity`.`identity` as id, `entity`.`name` as select_show FROM `doctor`
      INNER JOIN `entity` ON `entity`.identity = `doctor`.identity
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


    public static function findbydoctorid($id){
      		$sql = "SELECT *,entity.name from doctor
          inner join entity on doctor.identity=entity.identity where doctor.iddoctor=:iddoctor";
      		$retval =  self::$con->prepare($sql);
      		if(!$retval){
      			return null;
      		}
      		$retval->bindParam(':iddoctor', $id, PDO::PARAM_INT);
      		$retval->execute();
      		if ($retval->rowCount() > 0) {
      			$values = $retval->fetch(PDO::FETCH_OBJ);
      			$retval->closeCursor();
      			return $values;
      		}

      		$retval->closeCursor();
      		return null;
      	}

        public static function findbydoctorbyid($id){
              $sql = "SELECT *,entity.name,`entity`.`identitycard`,`entity`.`idschool` from doctor
              inner join entity on doctor.identity=entity.identity where doctor.identity=:identity";
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


        public static function searchdoctor($hourstart=null, $hourend=null, $iddiscipline=null, $iddayweek=null, $search='%', $limit=1, $schoolDefault){

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
            $joinExtra .= " INNER JOIN availability_dayweek ON availability_dayweek.iddoctoravailability = doctoravailability.iddoctoravailability";
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

          $sql = "SELECT doctor.*, doctor.identity as id, `entity`.`name` as select_show, doctoravailability.iddoctoravailability FROM `doctor`
          INNER JOIN `entity` ON `entity`.identity = `doctor`.identity
          INNER JOIN doctoravailability ON doctor.identity = doctoravailability.iddoctor
          ".$joinExtra."
          WHERE  entity.state=1 AND (UPPER(`entity`.`name`) LIKE UPPER(:search))  ".$sqlExtra." ".$sqlExtra1." ORDER BY doctor.identity DESC LIMIT :limit";
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
                $sqlDay = "SELECT * FROM availability_dayweek WHERE availability_dayweek.iddoctoravailability=:iddoctoravailability AND availability_dayweek.iddayweek=:iddayweek";
                $retvalDay =  self::$con->prepare($sqlDay);
                $retvalDay->bindParam(':iddoctoravailability', $row->iddoctoravailability, PDO::PARAM_INT);
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

        public static function countalldoctor($state=null, $schoolDefault){
         $sqlExtra1 = ""; 
          if(isset($schoolDefault)&&!empty($schoolDefault)){
            $sqlExtra1 .= " AND entity.`idschool`=".$schoolDefault;
          }
          $sql= "SELECT count(iddoctor) as total FROM `doctor`
          INNER JOIN entity ON entity.identity = doctor.identity
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
    
        $sql = "DELETE FROM `doctor` WHERE identity=:identity";
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
        
         $sql= "SELECT count(iddoctor) as total FROM `doctor`";

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
