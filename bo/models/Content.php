<?php
require_once('conf/connection.php');

class Content{
    private $idcontent;
    private $number_class;
    private $idblock;
    private $idteacher;
    private $date_;
    private $hour_start;
    private $hour_end;
    private $obs;
    private $createdby;
    private $createdat;
    private $updatedat;
    private $updatedby;
    private static $class;
    private static $con;



    public function __construct(){
        Content::$con = new Connection();
      self::$class = $this;
    }
    public function set($attribute, $content){
		$this->$attribute = $content;
	}
	public function get($attribute){
		return $this->$attribute;
	}


	public static function search($search='%', $limit=1,$schoolDefault){
    $sqlExtra = "";
    if(isset($schoolDefault)&&!empty($schoolDefault)){
      $sqlExtra .= " AND content.`idschool`=".$schoolDefault;
    }
		$sql = "SELECT *, idcontent as id, number_class as select_show FROM `content` WHERE (UPPER(number_class) LIKE UPPER(:search)) ".$sqlExtra."  ORDER BY idcontent DESC LIMIT :limit";
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
        $sql = "SELECT `idcontent`, `number_class`,`idblock`,`idteacher`,`date_`,`hour_start`,`hour_end`,`content`,`createdat`, `updatedat` FROM `content` WHERE idcontent=:idcontent";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
        return false;
        }

		$retval->bindParam(':idcontent', $id, PDO::PARAM_INT);
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
    }
    public static function findAlltable($limitstart, $perpage, $order, $keyorder, $search,$schoolDefault){
		$limitstart = intval($limitstart);
		$perpage = intval($perpage);
    $sqlExtra = "";
    if(isset($schoolDefault)&&!empty($schoolDefault)){
      $sqlExtra .= " AND content.`idschool`=".$schoolDefault;
    }
		$sql = "SELECT `idcontent`,
                            `block`.`number_class` as block,
                            `content`.`number_class`,
                            `content`.`hour_start`,
                            `content`.`hour_end`,
                            `content`.`idblock`,
                            `content`.`idteacher`,
                            `content`.`date_`,
                            `content`.`createdat`,
                            `content`.`updatedby`,
                            `content`. `updatedat`,
                             entityUpdate.name as userUpd,
                              entity.name as teacher FROM `content`
                              INNER JOIN `block` ON `block`.idblock = `content`.idblock
                              LEFT JOIN `teacher` ON `teacher`.idteacher = `content`.idteacher
                              LEFT JOIN `entity`  ON   entity.identity = `teacher`.`identity`
                              INNER JOIN `entity` as entityUpdate ON entityUpdate.identity = `content`.`updatedby`
                              WHERE (UPPER(`content`.`number_class`) LIKE UPPER(:search) OR UPPER(`block`.`name`) LIKE UPPER(:search) OR UPPER(idcontent) LIKE UPPER(:search)) ".$sqlExtra."  ORDER BY $keyorder $order, idcontent DESC LIMIT :start, :limit";
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




    public static function countall($search,$schoolDefault){
      $sqlExtra = "";
      if(isset($schoolDefault)&&!empty($schoolDefault)){
        $sqlExtra .= " AND content.`idschool`=".$schoolDefault;
      }
		$sql= "SELECT count(idcontent) as total FROM `content` WHERE (UPPER(number_class) LIKE UPPER(:search) OR UPPER(idblock) LIKE UPPER(:search) OR  UPPER(idcontent) LIKE UPPER(:search)) ".$sqlExtra." ";

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




      public static function findContentAlltable($limitstart, $perpage, $order, $keyorder, $search,$id,$schoolDefault){
      $limitstart = intval($limitstart);
      $perpage = intval($perpage);
   $sqlExtra = "";
    if(isset($schoolDefault)&&!empty($schoolDefault)){
      $sqlExtra .= " AND content.`idschool`=".$schoolDefault;
    }
      $sql = "SELECT `idcontent`,
                      `content`.`date_`,
                        `content`.`content`,
                      `content`.`number_class`,
                      `content`.`idblock`,
                        `content`.`hour_start`,
                          `content`.`hour_end`,
                         `content`.`idteacher`,
                          `content`.`createdat`,
                          `content`.`updatedat`,
                           entity.name as namet,
                           entityUpdate.name as userUpd
                           FROM `content`
                           INNER JOIN `teacher` ON `teacher`.identity = `content`.idteacher
                           INNER JOIN `entity` ON entity.identity = `teacher`.`identity`
                           INNER JOIN `entity` as entityUpdate ON entityUpdate.identity = `content`.`updatedby`
                           WHERE  `content`.`idblock`=:idblock AND (UPPER(idcontent) LIKE UPPER(:search)) ".$sqlExtra."  ORDER BY $keyorder $order, idcontent DESC LIMIT :start, :limit";
       $retval =  self::$con->prepare($sql);
      if(!$retval){
        return null;
      }

      $retval->bindParam(':search', $search, PDO::PARAM_STR);
      $retval->bindParam(':start', $limitstart, PDO::PARAM_INT);
      $retval->bindParam(':limit', $perpage, PDO::PARAM_INT);
      $retval->bindParam(':idblock', $id, PDO::PARAM_INT);
      $retval->execute();

      if ($retval->rowCount() > 0) {
        $values = $retval->fetchAll(PDO::FETCH_OBJ);
        $retval->closeCursor();
        return $values;
      }

      $retval->closeCursor();
      return null;
    }

    public static function countallContent($search,$id,$schoolDefault){
      $sqlExtra = "";
      if(isset($schoolDefault)&&!empty($schoolDefault)){
        $sqlExtra .= " AND content.`idschool`=".$schoolDefault;
      }
    $sql= "SELECT count(idcontent) as total FROM `content` WHERE  idblock=:idblock AND (UPPER(date_) LIKE UPPER(:search)  OR  UPPER(idcontent) LIKE UPPER(:search)) ".$sqlExtra." ";

        $retval =  self::$con->prepare($sql);
    if(!$retval){
      return 0;
    }

    $retval->bindParam(':search', $search, PDO::PARAM_STR);
      $retval->bindParam(':idblock', $id, PDO::PARAM_INT);
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
        $sql = "INSERT INTO `content` (number_class,idschool,idblock,idteacher,date_,hour_start,hour_end,content,createdat, updatedat,createdby, updatedby) VALUES (:number_class,:idschool,:idblock,:idteacher,:date_,:hour_start,:hour_end,:content,now(),now(), :createdby,:updatedby)";
        $retval =  self::$con->prepare($sql);
        if(!$retval){
        return false;
        }

        $retval->bindParam(':number_class',                  $this->number_class, PDO::PARAM_INT);
        $retval->bindParam(':idblock',          $this->idblock, PDO::PARAM_INT);
        $retval->bindParam(':idteacher',             $this->idteacher, PDO::PARAM_INT);
        $retval->bindParam(':idschool',             $this->idschool, PDO::PARAM_INT);
        $retval->bindParam(':date_',             $this->date_, PDO::PARAM_STR);
        $retval->bindParam(':hour_start',             $this->hour_start, PDO::PARAM_STR);
        $retval->bindParam(':hour_end',               $this->hour_end, PDO::PARAM_STR);
        $retval->bindParam(':content',               $this->obs, PDO::PARAM_STR);
        $retval->bindParam(':createdby',              $this->createdby, PDO::PARAM_INT);
        $retval->bindParam(':updatedby',              $this->updatedby, PDO::PARAM_INT);


        if($retval->execute()) {
        $id =  self::$con->lastInsertId();
        $retval->closeCursor();
        return $id;
        }

        $retval->closeCursor();
        return false;
    }
    public function update(){
        $sql = "UPDATE `content` SET date_=:date_,idschool=:idschool,hour_start=:hour_start,hour_end=:hour_end,content=:content,`updatedby`=:updatedby,`updatedat`=now() WHERE idcontent=:idcontent";
        $retval =  self::$con->prepare($sql);
            if(!$retval){
                return null;
            }
            $retval->bindParam(':idcontent', $this->idcontent, PDO::PARAM_INT);
            $retval->bindParam(':idschool',             $this->idschool, PDO::PARAM_INT);
            $retval->bindParam(':date_', $this->date_, PDO::PARAM_STR);
            $retval->bindParam(':hour_start',                   $this->hour_start, PDO::PARAM_STR);
            $retval->bindParam(':hour_end',                   $this->hour_end, PDO::PARAM_STR);
              $retval->bindParam(':content',                   $this->obs, PDO::PARAM_STR);
            $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);


            if($retval->execute()) {
                $retval->closeCursor();
                return true;
            }

            $retval->closeCursor();
            return false;
    }

    public static function countallbyteacher($identity){
    $sql= "SELECT count(idcontent) as total FROM content
    INNER JOIN block ON `block`.`idblock`=`content`.`idblock`
    INNER JOIN teacher ON `teacher`.`identity` = `content`.`idteacher` WHERE `teacher`.`identity`=:identity";/*GROUP BY registrationstudent.idstudent*/

        $retval =  self::$con->prepare($sql);
    if(!$retval){
      return 0;
    }

    $retval->bindParam(':identity',$identity, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values->total;
    }

    $retval->closeCursor();
    return 0;
    }



public static function findContentbyBlockid($id){
$sql = "SELECT *,block.hour_start,block.hour_end FROM `content` INNER JOIN `block` ON block.idblock = `content`.`idblock` WHERE `content`.`idblock`=:idblock";


  $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
  }
  $retval->bindParam(':idblock', $id, PDO::PARAM_INT);
  $retval->execute();

  if ($retval->rowCount() > 0) {
      $values = $retval->fetchAll(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
  }

  $retval->closeCursor();
            return null;
}


public static function findattendencebyBlockid($id){
$sql = "SELECT *, GROUP_CONCAT(`entity`.`name`) as namesAtend FROM `attendance`
  INNER JOIN `entity` ON entity.identity = `attendance`.`idstudent`

WHERE `attendance`.`idcontent`=:idcontent group by `attendance`.`idcontent`";


  $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
  }
  $retval->bindParam(':idcontent', $id, PDO::PARAM_INT);
  $retval->execute();

  if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
  }

  $retval->closeCursor();
            return null;
}

public static function findatmaxcontbyBlockid($id){
$sql = "SELECT MAX(number_class) as numberclass  FROM content WHERE idblock=:idblock";


  $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
  }
  $retval->bindParam(':idblock', $id, PDO::PARAM_INT);
  $retval->execute();

  if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return  $values->numberclass;
  }

  $retval->closeCursor();
return null;
}





}

?>
