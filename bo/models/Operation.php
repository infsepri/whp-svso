<?php

/**
 *
 * @package   Operation
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */

require_once('conf/connection.php');

class Operation{
  private $idoperation;
  private $description;
  private $abbreviation;
  private $orderfather;
  private $orderchild;
  private $orderchild2;
  private $level;
  private $functionality;
  private $url;
  private $menuclass;
  private $menuative;
  private $createdat;
  private $updatedat;
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



public static function findAllGroup(){
    $arr=array();
    $sql= "SELECT `idoperation`, `description`, `createdat`, `updatedat`, `abbreviation`, `orderfather`, `orderchild`, `level`,`type`, functionality, menuative FROM `operation` order by orderfather ASC, orderchild ASC, orderchild2 ASC, `level` ASC";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return 0;
    }
    $retval->execute();
    if ($retval->rowCount() > 0) {
        while($values = $retval->fetch(PDO::FETCH_OBJ)) {
            array_push($arr, $values);
        }
        $retval->closeCursor();
        return $arr;
    }

    $retval->closeCursor();
    return null;

  }

  public static function findAllGroup1(){
    $arr=array();
    $sql= "SELECT `idoperation`, `description`, `createdat`, `updatedat`, `abbreviation`, `orderfather`, `orderchild`, `level`,`type`, functionality, menuative FROM  `operation` where type=1 order by orderfather ASC, orderchild ASC, orderchild2 ASC, `level` ASC";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return 0;
    }
    $retval->execute();
    if ($retval->rowCount() > 0) {
        while($values = $retval->fetch(PDO::FETCH_OBJ)) {
            array_push($arr, $values);
        }
        $retval->closeCursor();
        return $arr;
    }

    $retval->closeCursor();
    return null;

  }

  public static function findbymaintenanceon($maintenance){
    $arr=array();
    $sql= "SELECT operation.url FROM `operation`
    WHERE maintenance=:maintenance";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return null;
    }
  
    $retval->bindParam(':maintenance', $maintenance, PDO::PARAM_INT);
    $retval->execute();
  
    if ($retval->rowCount() > 0) {
        $values = $retval->fetchAll(PDO::FETCH_COLUMN);
        $retval->closeCursor();
        return $values;
    }
  
    $retval->closeCursor();
    return null;
  }


  public static function searchall($search, $limit=1){


   
 
    $sql = "SELECT menuative, maintenance,operation.url,idoperation as id, description as select_show FROM `operation` WHERE operation.url IS NOT NULL AND  (UPPER(operation.description) LIKE UPPER(:search)) ORDER BY operation.maintenance DESC LIMIT :limit";
   
  
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
  public function updatemaintenance() {
    $sql = "UPDATE `operation` SET maintenance=:maintenance,`updatedat`=now() WHERE idoperation=:idoperation";
      $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }
    
    $retval->bindParam(':maintenance', $this->maintenance, PDO::PARAM_INT);
    $retval->bindParam(':idoperation', $this->idoperation, PDO::PARAM_INT);
    
    if($retval->execute()) {
      $retval->closeCursor();
      return true;
    }
    
    $retval->closeCursor();
    return false;
  }


  public static function deletemaintenance() {
    $sql = "UPDATE `operation` SET maintenance=0,`updatedat`=now() WHERE maintenance=1";
     $retval =  self::$con->prepare($sql);
    if(!$retval){
        return false;
    }
    if($retval->execute()) {
        $id =  self::$con->lastInsertId();
        $retval->closeCursor();
        return $id;
    }

    $retval->closeCursor();
    return false;
}

  public static function findbymaintenance($maintenance){
    $arr=array();
    $sql= "SELECT idoperation FROM `operation`
    WHERE maintenance=:maintenance";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return null;
    }

    $retval->bindParam(':maintenance', $maintenance, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
        $values = $retval->fetchAll(PDO::FETCH_COLUMN);
        $retval->closeCursor();
        return $values;
    }

    $retval->closeCursor();
    return null;
}


  


public static function findAbrevation($type){
  $arr=array();
  $sql= "SELECT `abbreviation` FROM `operation` WHERE type=:type  ORDER BY orderfather ASC, orderchild ASC";
  $retval =  self::$con->prepare($sql);
  if(!$retval){
      return $arr;
  }

  $retval->bindParam(':type', $type, PDO::PARAM_INT);
  $retval->execute();

  if ($retval->rowCount() > 0) {
    $arr=$retval->fetchAll(PDO::FETCH_COLUMN);

      $retval->closeCursor();
      return $arr;
  }

  $retval->closeCursor();
  return $arr;


  }


  public static function findAll($level,$type){
    $arr=array();
    $sql= "SELECT * FROM `operation` WHERE level=:level AND type=:type  ORDER BY orderfather ASC, orderchild ASC, `level` ASC";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return 0;
    }
    $retval->bindParam(':level', $level, PDO::PARAM_INT);
    $retval->bindParam(':type', $type, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
        while($values = $retval->fetch(PDO::FETCH_OBJ)) {
            $menu_level2 = self::findbylevel(2,$values->orderfather);
            if($menu_level2!=null){
                foreach ($menu_level2 as $key => $value) {
                    $menu_level3 = self::findbylevel(3, $value->orderfather, $value->orderchild);
                    if($menu_level3!=null){
                        $menu_level2[$key]->submenu = $menu_level3;
                    }
                }
                $values->submenu = $menu_level2;
            }
            array_push($arr, $values);
        }

        $retval->closeCursor();
        return $arr;
    }

    $retval->closeCursor();
    return null;
  }



  public static function findAllAdm($level,$type,$identity){
    $arr=array();
    $sql= "SELECT operation.*,`operation_role`.`type` as permission FROM `operation`
        inner join operation_role on operation_role.idoperation=operation.idoperation
        inner join admin_role on admin_role.idrole=operation_role.idrole
        inner join entity on entity.identity=admin_role.identity
        WHERE level=:level AND operation.type=:type AND  entity.identity=:identity GROUP BY orderfather ASC, orderchild ASC, `level` ASC";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return 0;
    }
    $retval->bindParam(':level', $level, PDO::PARAM_INT);
    $retval->bindParam(':type', $type, PDO::PARAM_INT);
    $retval->bindParam(':identity', $identity, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
        while($values = $retval->fetch(PDO::FETCH_OBJ)) {
            $menu_level2 = self::findbylevel(2,$values->orderfather);
            if($menu_level2!=null){
                foreach ($menu_level2 as $key => $value) {
                    $menu_level3 = self::findbylevel(3, $value->orderfather, $value->orderchild);
                    if($menu_level3!=null){
                        $menu_level2[$key]->submenu = $menu_level3;
                    }
                }
                $values->submenu = $menu_level2;
            }
            array_push($arr, $values);
        }

        $retval->closeCursor();
        return $arr;
    }

    $retval->closeCursor();
    return null;
  }


  public static function findbyadminpermission($identity){
    $arr=array();
    $sql= "SELECT operation_role.type, operation.abbreviation FROM `admin_role` inner join `role` on `role`.idrole=admin_role.idrole inner join operation_role on operation_role.idrole=`admin_role`.idrole inner join operation on operation.idoperation = operation_role.idoperation WHERE identity=:identity group by operation.abbreviation, operation_role.type";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return null;
    }

    $retval->bindParam(':identity', $identity, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
        $values = $retval->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN );
        $retval->closeCursor();
        return $values;
    }

    $retval->closeCursor();
    return null;
}


  public static function findbylevel($level, $groupfather, $groupchild=-1){
    $arr=array();
    $sql= "SELECT * FROM `operation` WHERE level=:level  AND orderfather=:groupfather ORDER BY orderfather ASC, orderchild ASC, `level` ASC";
    if($groupchild!=-1){
        $sql= "SELECT * FROM `operation` WHERE level=:level  AND orderfather=:groupfather AND orderchild=:groupchild ORDER BY orderfather ASC, orderchild ASC, orderchild2 ASC, `level` ASC";
    }
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return 0;
    }

    $retval->bindParam(':level', $level, PDO::PARAM_INT);
    $retval->bindParam(':groupfather', $groupfather, PDO::PARAM_INT);
    if($groupchild!=-1){
        $retval->bindParam(':groupchild', $groupchild, PDO::PARAM_INT);
    }

    $retval->execute();
    if ($retval->rowCount() > 0) {
        while($values = $retval->fetch(PDO::FETCH_OBJ)) {
            array_push($arr, $values);
        }
        $retval->closeCursor();
        return $arr;
    }

    $retval->closeCursor();
    return null;
}


  public static function findbyid($id){
    $arr=array();
    $sql= "SELECT `idoperation`, `description`, `created_at`, `updated_at`, `abbreviation`, `orderfather`, `orderchild`, `level`, functionality, menuclass, color_active FROM `operation` WHERE idoperation=:idoperation";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return 0;
    }
    $retval->bindParam(':idoperation',$id, PDO::PARAM_INT);

    $retval->execute();
    if ($retval->rowCount() > 0) {
        while($values = $retval->fetch(PDO::FETCH_OBJ)) {
            array_push($arr, $values);
        }
        $retval->closeCursor();
        return $arr;
    }

    $retval->closeCursor();
    return null;

  }







}

?>
