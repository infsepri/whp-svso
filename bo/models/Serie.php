<?php

/**
 *
 * @package   Serie
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */  
require_once('conf/connection.php');
//require_once('libraries/other/convert.php');

class Serie{
  private $idserie;
  private $numberseries;
  private $sequenceinvoice;
  private $sequenceinvoice_receipt;
  private $sequencewaybill;
  private $sequencedelivery_note;
  private $sequencereturn_guide;
  private $sequenceconsignment_guide;
  private $sequenceguide_move_assets_own;
  private $sequencecredit_note;
  private $sequencedebit_note;
  private $sequencereceipt;
  private $idcompany;
  private $defaultserie;
  private $uplimit;
  private $downlimit;
  private $logo;
  private $state;
  private $createdat;
  private $updatedat;
  private static $con;
  private static $class;

  const SerieActive = 1;
  const SerieInactive = 0;

  public function __construct(){
    Serie::$con = new Connection();
    self::$class = $this;
  }


  public function getattributeobj() {
		return (object)get_object_vars($this);
	}

  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }

    public static function getSerieActive()
  {
    return self::SerieActive;
  }

  public static function getSerieInactive()
  {
    return self::SerieInactive;
  }


  public  function checkserie($idcompany, $numberseries=null ){
    if($numberseries==null){
      $numberseries = date('Y');
    }
    $this->sequenceinvoice = 0;
     $this->sequenceinvoice_receipt= 0;
     $this->sequencesimplified_invoice= 0;
     $this->sequencewaybill= 0;
     $this->sequencedelivery_note= 0;
     $this->sequencereturn_guide= 0;
     $this->sequenceconsignment_guide= 0;
     $this->sequenceguide_move_assets_own= 0;
     $this->sequencecredit_note= 0;
     $this->sequencedebit_note= 0;
     $this->sequencereceipt= 0;
     $this->numberseries = $numberseries;

     $this->idcompany = $idcompany;
     $this->defaultserie = 1;
     $this->online = 0;
     $this->uplimit= 999999999;
     $this->downlimit= 0;
     $this->state = self::getSerieActive();
   return $this->save();
  }




  public function update($attribute){
    $sql = "UPDATE serie SET {$attribute}=:value,updatedat=now() WHERE idserie=:idserie";
    $retval = self::$con->prepare($sql);
    if(!$retval)
    {

      return false;
    }else{
      $attr = ($this->$attribute);
      $idserie = intval($this->idserie);
      $retval->bindParam(":idserie",  $idserie, PDO::PARAM_INT);
      $retval->bindParam(':value', $attr, PDO::PARAM_STR);
      if($retval->execute()) {
        $retval->closeCursor();
        return true;
      }

      $retval->closeCursor();
      return false;
    }
  }


  public function save(){
          $sql = "INSERT INTO `serie` (`numberseries`,
          `sequenceinvoice`,
          `sequenceinvoice_receipt`,
          `sequencewaybill`,
          `sequencedelivery_note`,
          `sequencereturn_guide`,
          `sequenceconsignment_guide`,
          `sequenceguide_move_assets_own`,
          `sequencecredit_note`,
          `sequencedebit_note`,
          `sequencereceipt`,
          `idcompany`,
          `defaultserie`,
          `uplimit`,
          `downlimit`,
          `logo`,
          `state`,
          `createdat`,
          `updatedat`) VALUES (    :numberseries,
                                    :sequenceinvoice,
                                    :sequenceinvoice_receipt,
                                    :sequencewaybill,
                                    :sequencedelivery_note,
                                    :sequencereturn_guide,
                                    :sequenceconsignment_guide,
                                    :sequenceguide_move_assets_own,
                                    :sequencecredit_note,
                                    :sequencedebit_note,
                                    :sequencereceipt,
                                    :idcompany,
                                    :defaultserie,
                                    :uplimit,
                                    :downlimit,
                                    :logo,
                                    :state,
                                    now(),
                                    now())";

      $retval =  self::$con->prepare($sql);
      if(!$retval){
      return false;
      }

      $retval->bindParam(':numberseries',             $this->numberseries, PDO::PARAM_STR);
      $retval->bindParam(':sequenceinvoice',            $this->sequenceinvoice, PDO::PARAM_INT);
      $retval->bindParam(':sequenceinvoice_receipt',             $this->sequenceinvoice_receipt, PDO::PARAM_INT);
      $retval->bindParam(':sequencewaybill',             $this->sequencewaybill, PDO::PARAM_INT);
      $retval->bindParam(':sequencedelivery_note',            $this->sequencedelivery_note, PDO::PARAM_INT);

      $retval->bindParam(':sequencereturn_guide',             $this->sequencereturn_guide,  PDO::PARAM_INT);
      $retval->bindParam(':sequenceconsignment_guide',             $this->sequenceconsignment_guide,  PDO::PARAM_INT);
      $retval->bindParam(':sequenceguide_move_assets_own',            $this->sequenceguide_move_assets_own, PDO::PARAM_INT);
      $retval->bindParam(':sequencecredit_note',             $this->sequencecredit_note, PDO::PARAM_INT);
      $retval->bindParam(':sequencedebit_note',             $this->sequencedebit_note, PDO::PARAM_INT);
      $retval->bindParam(':sequencereceipt',            $this->sequencereceipt, PDO::PARAM_INT);

      $retval->bindParam(':idcompany',             $this->idcompany, PDO::PARAM_INT);
      $retval->bindParam(':defaultserie',            $this->defaultserie, PDO::PARAM_INT);
      $retval->bindParam(':uplimit',             $this->uplimit, PDO::PARAM_INT);
      $retval->bindParam(':logo',             $this->logo, PDO::PARAM_STR);
      $retval->bindParam(':downlimit',            $this->downlimit, PDO::PARAM_INT);
      $retval->bindParam(':state',            $this->state, PDO::PARAM_INT);



      if($retval->execute()) {
      $id =  self::$con->lastInsertId();
      $retval->closeCursor();
      return $id;
      }

      $retval->closeCursor();
      return false;
  }

  public static function countall($idcompany, $search){
       $sql= "SELECT count(idserie) as total FROM serie WHERE idcompany=:idcompany and (UPPER(numberseries) LIKE UPPER(:search) )";
       $retval =  self::$con->prepare($sql);
       if(!$retval){
              return 0;
        }
        $retval->bindParam(':search', $search, PDO::PARAM_STR);
        $retval->bindParam(':idcompany',$idcompany, PDO::PARAM_INT);
        $retval->execute();

        if ($retval->rowCount() > 0) {
              $values = $retval->fetch(PDO::FETCH_OBJ);
              $retval->closeCursor();
              return $values->total;
        }

        $retval->closeCursor();
        return 0;
  }


  public static function findlogobyid($id, $idcompany){
        $sql = "SELECT `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat`        FROM serie WHERE idserie=:idserie and idcompany=:idcompany LIMIT 1";
        	$retval =  self::$con->prepare($sql);
          if(!$retval){
            return null;
          }

          $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
          $retval->bindParam(':idserie', $id, PDO::PARAM_INT);
          $retval->execute();

          if ($retval->rowCount() > 0) {
            $values = $retval->fetch(PDO::FETCH_OBJ);
            $retval->closeCursor();
            return $values;
          }

          $retval->closeCursor();
          return null;

  }


  public static function findnumberseries($numberseries, $idcompany){
      $sql = "SELECT `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat`       FROM serie WHERE UPPER(numberseries)=UPPER(:numberseries) and idcompany=:idcompany LIMIT 1";
          $retval =  self::$con->prepare($sql);
          if(!$retval){
            return null;
          }

          $retval->bindParam(':numberseries', $numberseries, PDO::PARAM_INT);
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



  public static function findbyDefaultSerie($defaultserie, $idcompany){

        $sql = "SELECT  `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat` FROM serie WHERE defaultserie=:defaultserie and idcompany=:idcompany";
           $retval =  self::$con->prepare($sql);
           if(!$retval){
             return null;
           }

           $retval->bindParam(':defaultserie', $defaultserie, PDO::PARAM_INT);
           $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
           $retval->execute();

           if ($retval->rowCount() > 0) {
             $retval->setFetchMode(PDO::FETCH_CLASS, 'Serie');
             $values = $retval->fetch( );
             $retval->closeCursor();
             return $values;
           }

           $retval->closeCursor();
           return null;
  }


  public static function findbynumberserie($numberserie, $idcompany){
    $sql = "SELECT  `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat`                    FROM serie WHERE UPPER(numberseries) like UPPER(:numberserie) and idcompany=:idcompany";


    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':numberserie', $numberserie, PDO::PARAM_INT);
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


  public static function findbyid($idserie, $idcompany){
    $sql = "SELECT `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat` FROM serie WHERE idserie=:idserie and state=1 and idcompany=:idcompany FOR UPDATE;";
      $retval =  self::$con->prepare($sql);
      if(!$retval){
        return null;
      }

      $retval->bindParam(':idserie', $idserie, PDO::PARAM_INT);
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

  public static function findbyidallstate($idserie){
    $sql = "SELECT  `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat` FROM serie WHERE idserie=:idserie ";

    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':idserie', $idserie, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }

  public static function findactiveserie( $idcompany){
      $sql = "SELECT `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat` FROM serie WHERE   state=1 and idcompany=:idcompany";
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

  public static function findonlineseries( $idcompany){
    $sql = "SELECT  `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat` FROM serie WHERE   state=1 and idcompany=:idcompany";
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

  public static function findbyidallstateobj($idserie){

    $sql = "SELECT `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat`                     FROM serie WHERE idserie=:idserie ";
   $retval =  self::$con->prepare($sql);
   if(!$retval){
     return null;
   }

   $retval->bindParam(':idserie', $idserie, PDO::PARAM_INT);
   $retval->execute();

   if ($retval->rowCount() > 0) {
     $values = $retval->fetch(PDO::FETCH_OBJ);
     $retval->closeCursor();
     return $values;
   }

   $retval->closeCursor();
   return null;
  }

  public static function existseriecompany($idcompany){

    $sql = "SELECT `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat`                     FROM serie  WHERE serie.idcompany=:idcompany and serie.state=1";
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

  public static function findbyidcompany($idcompany ,$limitstart, $limit, $keyorder, $order, $search){

    $sql = "SELECT `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat`                    FROM serie  WHERE idcompany=:idcompany and UPPER(numberseries) like UPPER(:search) order by $keyorder $order, defaultserie DESC limit :start, :limit ";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return null;
    }

    $retval->bindParam(':search', $search, PDO::PARAM_STR);
    $retval->bindParam(':start',  $limitstart, PDO::PARAM_INT);
    $retval->bindParam(':limit',  $limit, PDO::PARAM_INT);
    $retval->bindParam(':idcompany',$idcompany, PDO::PARAM_INT);
    $retval->execute();

    if ($retval->rowCount() > 0) {
        $values = $retval->fetchAll(PDO::FETCH_CLASS, "Serie");
        $retval->closeCursor();
        return $values;
    }

    $retval->closeCursor();
    return null;
  }

  public static function APIfindbycompany($idcompany){
    $sql = "SELECT idserie, numberseries, defaultserie, state
                     FROM serie  WHERE serie.idcompany=:idcompany and serie.state=1";
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

  public function updatedefaultserie(){
    $sql = "UPDATE serie SET defaultserie=:defaultserie,updatedat=now() WHERE idserie=:idserie";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
        return null;
    }

    $retval->bindParam(':defaultserie', $this->defaultserie, PDO::PARAM_INT);
    $retval->bindParam(':idserie', $this->idserie, PDO::PARAM_INT);

    if($retval->execute()) {
        $retval->closeCursor();
        return true;
    }

    $retval->closeCursor();
    return false;
  }


  public static function getoneserie($idcompany){

    $sql = "SELECT `idserie`, `numberseries`, `sequenceinvoice`, `sequenceinvoice_receipt`, `sequencewaybill`, `sequencedelivery_note`, `sequencereturn_guide`, `sequenceconsignment_guide`, `sequenceguide_move_assets_own`, `sequencecredit_note`, `sequencedebit_note`, `sequencereceipt`, `idcompany`, `defaultserie`, `uplimit`, `downlimit`, `logo`, `state`, `createdat`, `updatedat`                     FROM serie  WHERE serie.idcompany=:idcompany and serie.state=1 order by defaultserie DESC limit 1";
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

}
?>
