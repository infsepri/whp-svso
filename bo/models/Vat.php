<?php

/**
 *
 * @package   Vat
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once('conf/connection.php');

class Vat{
  private $idvat;
  private $name;
  private $idcompany;
  private $vat;
  private $taxzone;
  private $vatlevel;
  private $bydefault;
  private $state;
  private $createdat;
  private $updatedat;
  private $createdby;
  private $updatedby;
  private static $con;
  private static $class;

  const bydefaultyes = 1;
  const bydefaultno = 0;

  const stateactive = 1;
  const statedelete = 0;

  public function __construct(){
    Vat::$con = new Connection();
    self::$class = $this;
  }

  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }

  public static function getbydefaultyes()
  {
    return self::bydefaultyes;
  }

  public static function getbydefaultno()
  {
    return self::bydefaultno;
  }


  public static function getstateactive()
  {
    return self::stateactive;
  }

  public static function getstatedelete()
  {
    return self::statedelete;
  }

  public function getattribute() {
    return  get_object_vars($this);
  }

  public function savevatdefault($idcompany){
    $vat = self::findAll($idcompany);
    if(isset($vat) ){
      $countvat = count($vat);
    }else{
      $countvat=0;
    }



    if($vat==null || $countvat==0){
      //vat 23
      $this->name      = 'IVA23';
      $this->idcompany = $idcompany;
      $this->vat       = '23.00';
      $this->taxzone   = 'PT';
      $this->vatlevel  = 'NOR';
      $this->bydefault = 1;
      $this->state     = self::getstateactive();
      $this->save();
      //vat 0
      $this->name      = 'Isento';
      $this->idcompany = $idcompany;
      $this->vat       = '0.00';
      $this->taxzone   = 'PT';
      $this->vatlevel  = 'ISE';
      $this->bydefault = 0;
      $this->state     = self::getstateactive();
      $this->save();
      //vat 6
      $this->name      = 'IVA6';
      $this->idcompany = $idcompany;
      $this->vat       = '6.00';
      $this->taxzone   = 'PT';
      $this->vatlevel  = 'RED';
      $this->bydefault = 0;
      $this->state     = self::getstateactive();
      $this->save();
      //vat 13
      $this->name      = 'IVA13';
      $this->idcompany = $idcompany;
      $this->vat       = '13.00';
      $this->taxzone   = 'PT';
      $this->vatlevel  = 'INT';
      $this->bydefault = 0;
      $this->state     = self::getstateactive();
      $this->save();
    }
  }

  public static  function findAll($idcompany){

    $sql= "SELECT idvat, name, vat, bydefault, createdat, updatedat, idcompany, taxzone, vatlevel, state  FROM vat where idcompany=:idcompany and state=1";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


  public static function checkidvatinitem($idvat, $idcompany){
    $values=array();
    $sql= "SELECT count(*) as numberitem  FROM item where idvat=:idvat and idcompany=:idcompany";
    $retval = self::$con->prepare($sql);
    if(!$retval )
    {
      return null;
    }
      $retval->bindParam(':idvat', $idvat, PDO::PARAM_INT);
      $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
      $retval->execute();
     if ($retval->rowCount() > 0) {
       $values = $retval->fetch(PDO::FETCH_OBJ);
       $retval->closeCursor();
       return $values->numberitem;
     }

     $retval->closeCursor();
     return null;
  }



  public static function countall($idcompany){
    $sql = "SELECT count(idvat) as total  FROM vat where idcompany=:idcompany and state=1";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values->total;
    }

    $retval->closeCursor();
    return null;
  }



  public static  function findperpage($idcompany ,$limitstart, $limit, $keyorder, $order, $search){

    $sql= "SELECT idvat, name, vat, bydefault, createdat, updatedat, idcompany, taxzone, vatlevel, state  FROM vat where idcompany=:idcompany and UPPER(name) like UPPER(:search) and state=1 order by $keyorder $order limit  :start, :limit ";
    $retval =  self::$con->prepare($sql);
    if(!$retval){

        return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':search', $search, PDO::PARAM_STR);
    $retval->bindParam(':start',  $limitstart, PDO::PARAM_INT);
    $retval->bindParam(':limit',  $limit, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
        $values = $retval->fetchAll(PDO::FETCH_CLASS, "Vat");
        $retval->closeCursor();
        return $values;
    }

    $retval->closeCursor();
    return null;
  }



  public function save(){
    $sql = "INSERT INTO vat ( taxzone,
                              vatlevel,
                              name,
                              vat,
                              bydefault,
                              idcompany,
                              createdat,
                              updatedat,
                              createdby,
                              updatedby,
                              state) VALUES (:taxzone,
                                            :vatlevel,
                                            :name,
                                            :vat,
                                            :bydefault,
                                            :idcompany
                                            ,now(),now(),
                                            :createdby,
                                            :updatedby,
                                            :state)";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return false;
    }

    $retval->bindParam(':taxzone',             $this->taxzone, PDO::PARAM_STR);
    $retval->bindParam(':vatlevel',            $this->vatlevel, PDO::PARAM_STR);
    $retval->bindParam(':name',             $this->name, PDO::PARAM_STR);
    $retval->bindParam(':vat',             $this->vat, PDO::PARAM_STR);
    $retval->bindParam(':bydefault',            $this->bydefault, PDO::PARAM_INT);

    $retval->bindParam(':idcompany',             $this->idcompany,  PDO::PARAM_INT);
    $retval->bindParam(':createdby',            $this->createdby, PDO::PARAM_INT);
    $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);
    $retval->bindParam(':state',             $this->state, PDO::PARAM_INT);

    if($retval->execute()) {
        $id =  self::$con->lastInsertId();
        $retval->closeCursor();
        return $id;
    }

    $retval->closeCursor();
    return false;

  }

  public function update(){
    $sql = "UPDATE vat SET taxzone=:taxzone, vatlevel=:vatlevel, name=:name, vat=:vat, bydefault=:bydefault, idcompany=:idcompany,updatedby=:updatedby,updatedat=now(), state=1 where idvat=:idvat";
    $retval = self::$con->prepare($sql);
    if(!$retval){
      return false;
    }

    $retval->bindParam(':taxzone',             $this->taxzone, PDO::PARAM_STR);
    $retval->bindParam(':vatlevel',            $this->vatlevel, PDO::PARAM_STR);
    $retval->bindParam(':name',             $this->name, PDO::PARAM_STR);
    $retval->bindParam(':vat',             $this->vat, PDO::PARAM_STR);
    $retval->bindParam(':bydefault',            $this->bydefault, PDO::PARAM_INT);
    $retval->bindParam(':idcompany',             $this->idcompany,  PDO::PARAM_INT);
    $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);
    $retval->bindParam(':idvat',             $this->idvat, PDO::PARAM_INT);


    if($retval->execute()) {
      $retval->closeCursor();
      return true;
    }

    $retval->closeCursor();
    return false;
  }




  public function updatestate(){
    $sql = "UPDATE vat SET  updatedby=:updatedby,updatedat=now(), state=:state where idvat=:idvat";
    $retval = self::$con->prepare($sql);
    if(!$retval){
      return false;
    }


    $retval->bindParam(':state',             $this->state,  PDO::PARAM_INT);
    $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);
    $retval->bindParam(':idvat',             $this->idvat, PDO::PARAM_INT);


    if($retval->execute()) {
      $retval->closeCursor();
      return true;
    }

    $retval->closeCursor();
    return false;
  }



  public static function updateBydefault($newdefault, $olddefault, $idcompany){
    $sql = "UPDATE vat SET  bydefault=:newdefault,  updatedat=now() where bydefault=:olddefault and idcompany=:idcompany";

    $retval = self::$con->prepare($sql);
    if(!$retval){
      return false;
    }
    $retval->bindParam(':newdefault',$newdefault, PDO::PARAM_INT);
    $retval->bindParam(':olddefault', $olddefault, PDO::PARAM_INT);
    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    if($retval->execute()) {
      $retval->closeCursor();
      return true;
    }

    $retval->closeCursor();
    return false;
  }



  public static function findbyid($id, $idcompany){

    $sql= "SELECT idvat, name, vat, bydefault, createdat, updatedat, idcompany, taxzone, vatlevel, state FROM vat WHERE idvat=:idvat and idcompany=:idcompany";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':idvat', $id, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $retval->setFetchMode(PDO::FETCH_CLASS, 'Vat');
      $values = $retval->fetch( );
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }




  public static function findbystate($state, $idcompany){

    $sql= "SELECT idvat as id, name as select_show, vat, bydefault, createdat, updatedat, idcompany, taxzone, vatlevel, state FROM vat WHERE state=:state and idcompany=:idcompany";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':state', $state, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $retval->setFetchMode(PDO::FETCH_OBJ);
      $values = $retval->fetchAll( );
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }



  public static function findbyvat($vatp, $idcompany){

    $sql= "SELECT idvat, name, vat, bydefault, createdat, updatedat, idcompany, taxzone, vatlevel, state FROM vat WHERE vat=:vat and idcompany=:idcompany";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':vat', $vat, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


}

?>
