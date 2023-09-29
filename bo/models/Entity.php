<?php
/**
 *
 * @package   Entity
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once('conf/connection.php');

class Entity{
  protected $identity;
  protected $name;
  protected $nif;
  protected $identitycard;
  protected $identitycardnumber;
  protected $identitycardvalidity;
  protected $identitycardemission;
  protected $identitycardarchive;
  protected $iban;
  protected $idschool;
  
  protected $bic;
  protected $socialsecurity;
  protected $allowance;
  protected $allowancenumber;
  protected $photo;
  protected $genre;
  protected $address;
  protected $postalcode;
  protected $locality;
  protected $idcountry;
  protected $iddistrict;
  protected $idcompany;
  protected $telephone;
  protected $mobilephone;
  protected $mobilephone2;
  protected $obs;
  protected $email;
  protected $api;
  protected $hash;


  protected $admin;
  protected $teacher;
  protected $studentparent;
  protected $student;
  protected $provider;
  protected $placeofbirth;
  protected $statelogin;
  protected $noretention;
  protected $isentvat;

  protected $state;
  protected $createdby;
  protected $createdat;
  protected $updatedby;
  protected $updatedat;

  protected $idpaymentform;

  private static $con;
  private static $class;

  const entityAtive = 1;
  const entityWaitActive = 2;
  const entityDelete = 0;

  const yesmaster = 1;
  const nomaster = 0;

  const userative = 1;

  public function __construct(){
    self::setcon();
    self::$class = $this;
  }
  public static function setcon(){
    self::$con = new Connection();
  }

  public static function getcon(){
    return self::$con;
  }

  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }

  public static function getentityAtive()
{
  return self::entityAtive;
}

public static function getentityDelete()
{
  return self::entityDelete;
}

public static function getentityWaitActive()
{
  return self::entityWaitActive;
}

public static function getMasterYes()
{
  return self::yesmaster;
}

public static function getMasterNo()
{
  return self::nomaster;
}

public static function getuserAtive()
{
  return self::userative;
}





public function updateemailbyapi(){
  $sql = "UPDATE entity SET `email`=:email,    updatedat=now() WHERE api like :api";
  $retval =  self::$con->prepare($sql);
  if(!$retval){
    return false;
  }
  $retval->bindParam(':email',            $this->email, PDO::PARAM_STR);
  $retval->bindParam(':api',            $this->api, PDO::PARAM_STR);
  if($retval->execute()) {
    $retval->closeCursor();
    return true;
  }
  $retval->closeCursor();
  return false;
}


public function updateemailbyapi_login(){
  $sql = "UPDATE entity SET updatedat=now() WHERE api like :api";
  $retval =  self::$con->prepare($sql);
  if(!$retval){
    return false;
  }
  //$retval->bindParam(':email',            $this->email, PDO::PARAM_STR);
  $retval->bindParam(':api',            $this->api, PDO::PARAM_STR);
  if($retval->execute()) {
    $retval->closeCursor();
    return true;
  }
  $retval->closeCursor();
  return false;
}



public function updatestates(){

  $sql = "UPDATE entity SET   updatedby=:updatedby,  updatedat=now() WHERE identity=:identity";
  $retval =  self::$con->prepare($sql);
  if(!$retval){
    return false;
  }

  $retval->bindParam(':updatedby',            $this->updatedby, PDO::PARAM_INT);
  $retval->bindParam(':identity',             $this->identity, PDO::PARAM_INT);

  if($retval->execute()) {
    $retval->closeCursor();
    return true;
  }

  $retval->closeCursor();
      file_put_contents("ENTITY_UPDATE_01.txt", print_r($retval->error, true));
  return false;
}


  public function update(){

    $sql = "UPDATE entity SET `name`=:name,
    nif=:nif,
    identitytype=:identitytype,
    numberhelth=:numberhelth,
    photo=:photo,
    genre=:genre,
    address=:address,
    postalcode=:postalcode,
    locality=:locality,
    idcountry=:idcountry,
    iddistrict=:iddistrict,
    mobilephone=:mobilephone,
    obs=:obs,
    placeofbirth=:placeofbirth,
    updatedby=:updatedby,
    updatedat=now() WHERE identity=:identity";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return false;
    }



    $retval->bindParam(':name',            $this->name, PDO::PARAM_STR);
    $retval->bindParam(':nif',             $this->nif, PDO::PARAM_STR);
    $retval->bindParam(':identitytype',             $this->identitytype, PDO::PARAM_INT);
    $retval->bindParam(':numberhelth',             $this->numberhelth,  PDO::PARAM_STR);
    $retval->bindParam(':photo',             $this->photo,  PDO::PARAM_STR);
    $retval->bindParam(':genre',             $this->genre,  PDO::PARAM_INT);
    $retval->bindParam(':address',             $this->address,  PDO::PARAM_STR);
    $retval->bindParam(':postalcode',             $this->postalcode,  PDO::PARAM_STR);
    $retval->bindParam(':locality',             $this->locality,  PDO::PARAM_STR);
    $retval->bindParam(':idcountry',             $this->idcountry,  PDO::PARAM_INT);
    $retval->bindParam(':iddistrict',             $this->iddistrict,  PDO::PARAM_INT);
    $retval->bindParam(':mobilephone',             $this->mobilephone,  PDO::PARAM_STR);
    $retval->bindParam(':obs',             $this->obs,  PDO::PARAM_STR);

    $retval->bindParam(':placeofbirth',             $this->placeofbirth,  PDO::PARAM_STR);
    $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);
    $retval->bindParam(':identity',             $this->identity, PDO::PARAM_INT);


    if($retval->execute()) {
      $retval->closeCursor();
      return true;
    }

    $retval->closeCursor();
        file_put_contents("ENTITY_UPDATE_01.txt", print_r($retval->error, true));
    return false;
  }



  public static function findUserbyemail($username, $state, $state2){
    $users=array();
  $sql= "SELECT hash, admin,teacher,studentparent,student, identity, name,   email, idcompany, statelogin, api, createdat, updatedat, nif, iban, bic FROM entity WHERE email=:email and statelogin IN ( 1, 1 ) LIMIT 1";
        $retval =  self::$con->prepare($sql);
		if(!$retval){
      return -1;
		}
    $retval->bindParam(':email', $username, PDO::PARAM_STR);

		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;

  }

/*
  public function changestatus(){
    $sql = "UPDATE entity SET statelogin=:statelogin WHERE identity=:identity";

    $retval->bindParam(':statelogin',             $this->statelogin,  PDO::PARAM_INT);
    $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);
    $retval->bindParam(':identity',             $this->identity, PDO::PARAM_INT);
    if($retval->execute()) {
      $retval->closeCursor();
      return true;
    }

    $retval->closeCursor();
    return false;
  }*/

  public function updatestate() {
		$sql = "UPDATE `entity` SET state=:state,`updatedat`=now() WHERE identity=:identity";
		$retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':state', $this->state, PDO::PARAM_INT);
		$retval->bindParam(':identity', $this->identity, PDO::PARAM_INT);

		if($retval->execute()) {
			$retval->closeCursor();
			return true;
		}

		$retval->closeCursor();
		return false;
	}




  public function changestatus(){
    $sql = "UPDATE entity SET `statelogin`=:statelogin,    updatedat=now() WHERE api like :api";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return false;
    }

    $retval->bindParam(':statelogin',            $this->statelogin, PDO::PARAM_STR);
    $retval->bindParam(':api',            $this->api, PDO::PARAM_STR);
    if($retval->execute()) {
      $retval->closeCursor();
      return true;
    }
    $retval->closeCursor();
    return false;
  }
  

  public function delete(){
    $sql = "DELETE FROM entity WHERE identity=:identity";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return false;
		}

		$retval->bindParam(':identity', $this->identity, PDO::PARAM_INT);

		if($retval->execute()) {
			$id =  self::$con->lastInsertId();
			$retval->closeCursor();
			return $id;
		}

		$retval->closeCursor();
		return false;
  }




  public static function findbyapikey($api){
    $entitys=array();
    $sql= "SELECT  hash, admin, identity, name,   email, idcompany, statelogin, api, createdat, updatedat, pin, nif, iban, bic FROM entity WHERE api like BINARY :api and statelogin=1 LIMIT 1";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':api', $api, PDO::PARAM_STR);

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }



  public static function findbysepri($identity){
    $entitys=array();
    $sql= "SELECT  *FROM entity WHERE name like 'SEPRI' and identity=:identity LIMIT 1";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':identity', $identity, PDO::PARAM_INT);

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }





  public static function findbyhash($hash, $status){
    $entitys=array();
    $sql= "SELECT  hash, admin, identity, name,   email, idcompany, statelogin, api, createdat, updatedat, pin, nif, iban, bic  FROM entity WHERE hash like BINARY :hash and statelogin=1 LIMIT 1";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':hash', $hash, PDO::PARAM_STR);

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


  
  public static function findbyidmange($api){

   
    $entitys=array();
    $sql= "SELECT  * FROM entity WHERE api like BINARY :api  LIMIT 1";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->bindParam(':api', $api, PDO::PARAM_STR);
   

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


  public static function findbyidverifyrole($id,$identity){
    $sqlExtra = "";
    if(!empty($id)) {
      if ($id==1) {
        $sqlExtra .= " AND admin=".$id;
      }elseif ($id==2) {
        $id=1;
        $sqlExtra .= " AND teacher=".$id;
      }elseif ($id==3) {
          $id=1;
        $sqlExtra .= " AND studentparent=".$id;
      }else{
          $id=1;
          $sqlExtra .= " AND student=".$id;
      }

    }


    $sql= "SELECT entity.identity, `entity`.`name` as usersend,`entity`.`photo` as photo,(SELECT message_content.date_ FROM message_content where (entity.identity=message_content.idsend or entity.identity=message_content.idreceiveingroup) AND (message_content.idreceiveingroup is not null) ORDER BY message_content.idmessagecontent DESC LIMIT 1) as datec 
     FROM entity WHERE  `entity`.identity!=:identity AND state=1 ".$sqlExtra."";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return 0;
    }
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















  public static function findbyid($id){

    $entitys=array();
    $sql= "SELECT * FROM entity 
    
    WHERE identity=:identity and state=1 LIMIT 1 ";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return 0;
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

  public static function findbyentitystudent($id){

    $sql= "SELECT  entity.identity, entity.name,  entity.photo,entity.idcompany,company.logo as logo,company.name as nameCompany,company.email as emailCompany,company.telephone as telephoneCompany,company.website as websiteCompany,company.mobilephone as mobilephoneCompany,student.gradenumber
            FROM entity
            LEFT JOIN company on company.idcompany=entity.idcompany
            LEFT JOIN student on student.identity=`entity`.`identity`
            WHERE entity.identity=:identity and entity.state=1 LIMIT 1";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return 0;
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

  public static function findbyentity($id){

    $sql= "SELECT  entity.identity,entity.state,entity.statelogin, entity.name,  entity.photo,entity.idcompany,company.logo as logo,company.name as nameCompany,company.email as emailCompany,company.telephone as telephoneCompany,company.website as websiteCompany,company.mobilephone as mobilephoneCompany
            FROM entity
            LEFT JOIN company on company.idcompany=entity.idcompany
         
            WHERE entity.identity=:identity  LIMIT 1";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return 0;
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


  public static function findbyentityteacher($id){

    $sql= "SELECT  entity.identity,
            company.name as nameCompany,
            company.telephone as telephoneCompany,
            company.email as emailCompany,
            company.website as websiteCompany,
            company.mobilephone as mobilephoneCompany,
            entity.name,
            entity.photo,
            entity.idcompany,
            company.logo as logo,
            discipline.name as discipline,
            schoolyear.name as schoolyear
            FROM entity
            LEFT JOIN company on company.idcompany=entity.idcompany
            LEFT JOIN teacher on teacher.identity=`entity`.`identity`
            LEFT JOIN block on block.idteacher=`entity`.`identity`
            LEFT JOIN content on content.idteacher=`entity`.`identity`
            LEFT JOIN attendance on attendance.idcontent=`content`.`idcontent`
            LEFT JOIN discipline on discipline.iddiscipline=`block`.`iddiscipline`
            LEFT JOIN `schoolyear` ON schoolyear.idschoolyear = `block`.`idblock`
            WHERE entity.identity=:identity and entity.state=1 LIMIT 1";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return 0;
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






  public static function find($id){
    $entitys=array();
    $sql= "SELECT iddoctor,idemployee,idphysiotherapist,idadmin FROM entity 
            LEFT JOIN employee on employee.identity=entity.identity
            LEFT JOIN admin on admin.identity=`entity`.`identity`
            LEFT JOIN physiotherapist on physiotherapist.identity=`entity`.`identity`
            LEFT JOIN doctor on doctor.identity=`entity`.`identity`
    WHERE entity.identity=:identity  LIMIT 1";
    $retval =  self::$con->prepare($sql);
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






  public static function findbyidcompany($idcompany, $identity){
    $entitys=array();
    $sql= "SELECT  idschool,hash, admin, identity, name,   email, idcompany, statelogin, api, createdat, updatedat, pin, nif, iban, bic FROM entity WHERE idcompany=:idcompany and identity!=:identity and statelogin=1";
    $retval =  self::$con->prepare($sql);
    $retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
    $retval->bindParam(':identity', $identity, PDO::PARAM_INT);

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;
  }


  public static function findAllnotify()
  {

    $sql = "SELECT entity.identity,entity.name,`email`,`statelogin`,entity.`state` FROM entity 

  where entity.state=1 AND entity.statelogin=1  AND (entity.identity !=2 AND entity.identity !=3 AND entity.identity !=1) ORDER BY entity.name ASC";
    $retval =  self::$con->prepare($sql);
    if (!$retval) {
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





  public static function findbyemail($email){
    $entitys=array();

    $sql= "SELECT  * FROM entity WHERE   email like :email";
    $retval =  self::$con->prepare($sql);
    $retval->bindParam(':email', $email, PDO::PARAM_STR);

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values ;
    }

    $retval->closeCursor();
    return null;
  }

  public static function findbynumberhelth($numberhelth){
    $entitys=array();

    $sql= "SELECT  * FROM entity WHERE   numberhelth like :numberhelth";
    $retval =  self::$con->prepare($sql);
    $retval->bindParam(':numberhelth', $numberhelth, PDO::PARAM_STR);

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values ;
    }

    $retval->closeCursor();
    return null;
  }
  



  public static function findbycompany($idcompany){
    $entitys=array();
    $sql= "SELECT identity, email, name, statelogin, hash, api  FROM entity WHERE  idcompany=:idcompany ";
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




  public function save(){
    $sql = "INSERT INTO `entity`(
                                `name`,
                                `nif`,
                                `identitycard`,
                                `identitycardnumber`,
                                `photo`,
                                `genre`,
                                numberhelth,
                                `address`,
                                `postalcode`,
                                `locality`,
                                `idcountry`,
                                `iddistrict`,
                                `idcompany`,
                                `telephone`,
                                `mobilephone`,
                                identitytype,
                                `obs`,
                                `email`,
                                `api`,
                                `placeofbirth`,
                                `statelogin`,
                                `state`,
                                `createdby`,
                                `updatedby`,
                                `createdat`,
                                `updatedat`) VALUES
                                (
                                :name,
                                :nif,
                                :identitycard,
                                :identitycardnumber,
                                :photo,
                                :genre,
                                :numberhelth,
                                :address,
                                :postalcode,
                                :locality,
                                :idcountry,
                                :iddistrict,
                                :idcompany,
                                :telephone,
                                :mobilephone,
                                :identitytype,
                                :obs,
                                :email,
                                :api,
                                :placeofbirth,
                                :statelogin,
                                :state,
                                :createdby,
                                :updatedby,
                                  now(),
                                  now())";

            $retval =  self::$con->prepare($sql);
              if(!$retval){
                file_put_contents("ENTITY_SAVE_011.txt", print_r($retval->error, true));
                  return false;
              }

              $retval->bindParam(':numberhelth',            $this->numberhelth, PDO::PARAM_STR);
            $retval->bindParam(':name',            $this->name, PDO::PARAM_STR);
            $retval->bindParam(':nif',             $this->nif, PDO::PARAM_STR);
            $retval->bindParam(':identitycard',             $this->identitycard, PDO::PARAM_STR);
            $retval->bindParam(':identitycardnumber',            $this->identitycardnumber, PDO::PARAM_STR);
            $retval->bindParam(':photo',             $this->photo,  PDO::PARAM_STR);
            $retval->bindParam(':genre',             $this->genre,  PDO::PARAM_INT);
            $retval->bindParam(':address',             $this->address,  PDO::PARAM_STR);
            $retval->bindParam(':postalcode',             $this->postalcode,  PDO::PARAM_STR);
            $retval->bindParam(':locality',             $this->locality,  PDO::PARAM_STR);
            $retval->bindParam(':idcountry',             $this->idcountry,  PDO::PARAM_INT);
            $retval->bindParam(':iddistrict',             $this->iddistrict,  PDO::PARAM_INT);
            $retval->bindParam(':idcompany',             $this->idcompany,  PDO::PARAM_INT);
            $retval->bindParam(':telephone',             $this->telephone,  PDO::PARAM_STR);
            $retval->bindParam(':mobilephone',             $this->mobilephone,  PDO::PARAM_STR);
            $retval->bindParam(':obs',             $this->obs,  PDO::PARAM_STR);
            $retval->bindParam(':email',             $this->email,  PDO::PARAM_STR);
            $retval->bindParam(':api',             $this->api,  PDO::PARAM_STR);
            $retval->bindParam(':placeofbirth',             $this->placeofbirth,  PDO::PARAM_STR);
            $retval->bindParam(':statelogin',             $this->statelogin,  PDO::PARAM_INT);
            $retval->bindParam(':createdby',            $this->createdby, PDO::PARAM_INT);
            $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);
            $retval->bindParam(':state',             $this->state, PDO::PARAM_INT);
            $retval->bindParam(':identitytype',             $this->identitytype, PDO::PARAM_INT);
            
            if($retval->execute()) {
                $id =  self::$con->lastInsertId();
                $retval->closeCursor();
                return $id;
            }

            $retval->closeCursor();
            file_put_contents("ENTITY_SAVE_022.txt", print_r($this->name, true));
        
            return false;

}






  public static function findAlll(){

    $sql= "SELECT `name`,
                  `nif`,
                  `identitycard`,
                  `identitycardnumber`,
                  `identitycardvalidity`,
                  `identitycardemission`,
                  `identitycardarchive`,
                  `iban`,
                  `bic`,
                  `socialsecurity`,
                  `allowance`,
                  `allowancenumber`,
                  `photo`,
                  `genre`,
                  `isentvat`,
                  `noretention`,
                  `address`,
                  `postalcode`,
                  `locality`,
                  `idcountry`,
                  `iddistrict`,
                  `idcompany`,
                  `telephone`,
                  `mobilephone`,
                  `mobilephone2`,
                  `obs`,
                  `email`,
                  `api`,
                  `hash`,
                  idschool,
                  `admin`,
                  `teacher`,
                  `studentparent`,
                  `student`,
                  `provider`,
                  `placeofbirth`,
                  `statelogin`,
                  `state`,
                  `createdby`,
                  `updatedby`,
                  `createdat`
                  FROM entity";
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
    $sql= "SELECT count(identity) as total FROM `entity` WHERE (UPPER(name) LIKE UPPER(:search) OR
                                                                        UPPER(nif) LIKE UPPER(:search) OR
                                                                        UPPER(iban) LIKE UPPER(:search) OR
                                                                        UPPER(bic) LIKE UPPER(:search) OR
                                                                        UPPER(socialsecurity) LIKE UPPER(:search) OR
                                                                        UPPER(allowance) LIKE UPPER(:search) OR
                                                                        UPPER(allowancenumber) LIKE UPPER(:search) OR
                                                                        UPPER(genre) LIKE UPPER(:search) OR
                                                                        UPPER(address) LIKE UPPER(:search) OR
                                                                        UPPER(postalcode) LIKE UPPER(:search) OR
                                                                        UPPER(locality) LIKE UPPER(:search) OR
                                                                        UPPER(telephone) LIKE UPPER(:search) OR
                                                                        UPPER(mobilephone) LIKE UPPER(:search) OR
                                                                        UPPER(mobilephone2) LIKE UPPER(:search) OR
                                                                        UPPER(obs) LIKE UPPER(:search) OR
                                                                        UPPER(admin) LIKE UPPER(:search) OR
                                                                        UPPER(teacher) LIKE UPPER(:search) OR
                                                                        UPPER(student) LIKE UPPER(:search) OR
                                                                        UPPER(studentparent) LIKE UPPER(:search) OR
                                                                        UPPER(student) LIKE UPPER(:search) OR
                                                                        UPPER(provider) LIKE UPPER(:search) OR
                                                                        UPPER(placeofbirth) LIKE UPPER(:search) OR
                                                                        UPPER(state) LIKE UPPER(:state) OR
                                                                        UPPER(statelogin) LIKE UPPER(:search))";

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


public static function searchentity($search='%', $limit=1){

  $sql = "SELECT *, identity as id, name as select_show FROM `entity` WHERE (`admin`=1 OR `teacher`=1) and   (UPPER(name) LIKE UPPER(:search))  ORDER BY identity DESC LIMIT :limit";
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

  public static function search($search='%', $limit=1, $schoolDefault){
    $sqlExtra = "";
    if(isset($schoolDefault)&&!empty($schoolDefault)){
      $sqlExtra .= " AND entity.`idschool`=".$schoolDefault;
    }

$sql = "SELECT *, identity as id, name as select_show FROM `entity` WHERE teacher= 1 and   (UPPER(name) LIKE UPPER(:search)) ".$sqlExtra." ORDER BY identity DESC LIMIT :limit";
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

public static function searchParent($search, $limit=1){

$sql = "SELECT *, identity as id, name as select_show FROM `entity` WHERE studentparent= 1 and   (UPPER(name) LIKE UPPER(:search) OR UPPER(nif) LIKE UPPER(:search))  ORDER BY identity DESC LIMIT :limit";
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

public static function findteacherbyid($id){
  $sql = "SELECT * FROM `entity` WHERE identity=:identity and teacher=1";
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






public static function searchstudentparnt($nif=null, $search='%', $limit=1){
  $sqlExtra = "";
  if(!empty($nif)) {
    $nif = (int)$nif;
    $sqlExtra .= " AND nif=".$nif;
  }


  $sql = "SELECT entity.*, entity.identity as id, `entity`.`name` as select_show FROM `entity`

  WHERE entity.state=1 AND (UPPER(`entity`.`name`) LIKE UPPER(:search))  ".$sqlExtra." ORDER BY entity.identity DESC LIMIT :limit";
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

public static function findParent($identity){

  $sql = "SELECT entity.*, entity.identity as id, `entity`.`name` as select_show,
`studentparent`.idgrade,`studentparent`.profession,
`studentparent`.workcompany,`studentparent`.workphone,
`studentparent`.greeparent,`studentparent`.idworksituation,
`studentparent`.maritalstatus,
`studentparent`.idgrade,
`district`.description,
`grade`.name as grade,
`country`.description as country
  FROM `entity`
  INNER JOIN `country` ON `country`.idcountry = `entity`.idcountry

  left JOIN `district` ON `district`.iddistrict = `entity`.iddistrict

  INNER JOIN `studentparent` ON `studentparent`.identity = `entity`.identity
    left JOIN `grade` ON `grade`.idgrade = `studentparent`.idgrade
  WHERE   entity.studentparent=1 AND entity.state=1 And `entity`.identity=:identity";



      $retval =  self::$con->prepare($sql);
  if(!$retval){
          return null;
  }


  $retval->bindParam(':identity', $identity, PDO::PARAM_INT);
  $retval->execute();

  if ($retval->rowCount() > 0) {
    $values = $retval->fetch(PDO::FETCH_OBJ);
    $retval->closeCursor();
    return $values;
  }

  $retval->closeCursor();
  return null;
}


public static function searchsubtype($idcategory,$search, $limit=1){


  if(!empty($idcategory)) {
    $idcategory = (int)$idcategory;
  }
if($idcategory==0){
  $sql = "SELECT entity.identity as id, entity.name as select_show FROM `entity` WHERE   (UPPER(entity.name) LIKE UPPER(:search))  ORDER BY entity.identity DESC LIMIT :limit";
}elseif($idcategory==1){
  $sql = "SELECT entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `admin`  on `admin`.identity=entity.identity WHERE   (UPPER(entity.name) LIKE UPPER(:search))  ORDER BY entity.identity DESC LIMIT :limit";
}elseif ($idcategory==2) {
  $sql = "SELECT entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `teacher`  on `teacher`.identity=entity.identity WHERE   (UPPER(entity.name) LIKE UPPER(:search))  ORDER BY entity.identity DESC LIMIT :limit";
}elseif  ($idcategory==3) {
  $sql = "SELECT entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `studentparent`  on `studentparent`.identity=entity.identity WHERE   (UPPER(entity.name) LIKE UPPER(:search))  ORDER BY entity.identity DESC LIMIT :limit";
}else {
$sql = "SELECT entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `student`  on `student`.identity=entity.identity WHERE   (UPPER(entity.name) LIKE UPPER(:search))  ORDER BY entity.identity DESC LIMIT :limit";
}

$retval =  self::$con->prepare($sql);
if(!$retval){
    return null;
}

$retval->bindParam(':search', $search, PDO::PARAM_STR);
$retval->bindParam(':limit', $limit, PDO::PARAM_INT);
$retval->execute();
file_put_contents('sql.txt', $sql);

if ($retval->rowCount() > 0) {
    $values = $retval->fetchAll(PDO::FETCH_OBJ);
    $retval->closeCursor();
    return $values;
}

$retval->closeCursor();
return null;
}


public static function searchsubtypeT($idcategory,$search, $limit=1,$identity=null){


  if(!empty($idcategory)) {
    $idcategory = (int)$idcategory;
  }
if($idcategory==1){
  $sql = "SELECT entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `admin`  on `admin`.identity=entity.identity WHERE  entity.identity=IFNULL(:identity, entity.identity) AND  (UPPER(entity.name) LIKE UPPER(:search))  ORDER BY entity.identity DESC LIMIT :limit";
}else {

$sql = "SELECT entity.identity as id, CONCAT(entity.name,' - ',block.name) as select_show FROM `entity`
                    inner JOIN `student`  on `student`.identity=entity.identity
                    inner JOIN `registrationstudent`  on `registrationstudent`.`idstudent`=student.identity
                    inner JOIN `block`  on registrationstudent.`idblock`= block.idblock
                    WHERE   (UPPER(entity.name) LIKE UPPER(:search)) and `block`.`idteacher`=:identity  ORDER BY entity.identity DESC LIMIT :limit";

}
$retval =  self::$con->prepare($sql);
if(!$retval){
    return null;
}

$retval->bindParam(':search', $search, PDO::PARAM_STR);
$retval->bindParam(':limit', $limit, PDO::PARAM_INT);
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

public static function searchsubtypeS($idcategory,$search, $limit=1,$identity=null){

  if(!empty($idcategory)) {
    $idcategory = (int)$idcategory;
  }
if($idcategory==1){
  $sql = "SELECT entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `admin`  on `admin`.identity=entity.identity WHERE  entity.identity=IFNULL(:identity, entity.identity) AND  (UPPER(entity.name) LIKE UPPER(:search))  ORDER BY entity.identity DESC LIMIT :limit";
}else {

$sql = "SELECT entity.identity as id, CONCAT(entity.name,' - ',block.name) as select_show FROM `entity`
                    inner JOIN `teacher`  on `teacher`.identity=entity.identity
                    inner JOIN `block`  on `block`.`idteacher`=entity.identity
                    inner JOIN `registrationstudent`  on registrationstudent.`idblock`= block.idblock
                    WHERE   (UPPER(entity.name) LIKE UPPER(:search)) and `registrationstudent`.`idstudent`=:identity  ORDER BY entity.identity DESC LIMIT :limit";

}
$retval =  self::$con->prepare($sql);
if(!$retval){
    return null;
}

$retval->bindParam(':search', $search, PDO::PARAM_STR);
$retval->bindParam(':limit', $limit, PDO::PARAM_INT);
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


public static function findAllmessage($id){

  $sql= "SELECT `name`,
                `nif`,
                `identity`,
                `statelogin`
                `identitycard`,
                `identitycardnumber`,
                `identitycardvalidity`,
                `identitycardemission`,
                `identitycardarchive`,
                `iban`,
                `bic`,
                `socialsecurity`,
                `allowance`,
                `allowancenumber`,
                `photo`,
                `genre`,
                `address`,
                `postalcode`,
                `locality`,
                `idcountry`,
                `iddistrict`,
                `idcompany`,
                `telephone`,
                `mobilephone`,
                `mobilephone2`,
                `obs`,
                `email`,
                `api`,
                `hash`,

                `admin`,
                `teacher`,
                `studentparent`,
                `student`,
                `provider`,
                `placeofbirth`,
                `statelogin`,
                `state`,
                `createdby`,
                `updatedby`,
                `createdat`
                FROM entity WHERE  statelogin=1 AND identity !=:identity";
  $retval =  self::$con->prepare($sql);
      if(!$retval){
          return null;
      }
      $retval->bindParam(':identity', $id, PDO::PARAM_INT);
      $retval->execute();

      if ($retval->rowCount() > 0) {
          $values = $retval->fetchAll(PDO::FETCH_OBJ);
          $retval->closeCursor();
          return $values;
      }

      $retval->closeCursor();
      return null;
}



public static function findAllspecifymessage($idcategory,$identity){


  if(!empty($idcategory)) {
    $idcategory = (int)$idcategory;
  }
if($idcategory==0){
  $sql = "SELECT entity.statelogin,entity.identity as id, entity.name as select_show FROM `entity` WHERE  statelogin=1 AND entity.identity!=:identity ";
}elseif($idcategory==1){
  $sql = "SELECT entity.statelogin,entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `admin`  on `admin`.identity=entity.identity WHERE  statelogin=1 AND entity.identity!=:identity";
}elseif ($idcategory==2) {
  $sql = "SELECT entity.statelogin,entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `teacher`  on `teacher`.identity=entity.identity WHERE  statelogin=1 AND   entity.identity!=:identity";
}elseif  ($idcategory==3) {
  $sql = "SELECT entity.statelogin,entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `studentparent`  on `studentparent`.identity=entity.identity WHERE  statelogin=1 AND  entity.identity!=:identity";
}else {
$sql = "SELECT entity.statelogin,entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `student`  on `student`.identity=entity.identity WHERE  statelogin=1 AND   entity.identity!=:identity";
}

$retval =  self::$con->prepare($sql);
if(!$retval){
    return null;
}

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
public static function team($idcategory,$identity){

if(!empty($idcategory)) {
    $idcategory = (int)$idcategory;
}
if($idcategory==1){
  //$sql = "SELECT entity.statelogin,entity.identity as id, entity.name as select_show FROM `entity` WHERE  statelogin=1 AND entity.identity!=:identity ";
  $sql = "select DISTINCT(entity.identity),entity.statelogin,
                entity.email,
                entity.photo,
                entity.admin,
                entity.teacher,

                entity.name as select_show from entity   where (teacher=1 or (admin=1  and identity=:identity ))  AND statelogin=1";
}elseif($idcategory==2){
  $sql = "select DISTINCT(entity.identity),entity.statelogin,
                entity.email,
                entity.photo,
                entity.admin,
                entity.teacher,

                entity.name as select_show from entity   where (admin=1 or (teacher=1  and identity=:identity ))  AND statelogin=1";
}elseif  ($idcategory==3) {
  $sql = "SELECT entity.statelogin,entity.identity as id, entity.name as select_show FROM `entity` inner JOIN `studentparent`  on `studentparent`.identity=entity.identity WHERE  statelogin=1 AND  entity.identity!=:identity";
}else {
$sql = "select DISTINCT(entity.identity),entity.statelogin,
                entity.email,
                entity.photo,
                entity.admin,
                entity.teacher,
                entity.name as select_show from entity left join block on block.idteacher=entity.identity left join registrationstudent rg on rg.idblock=block.idblock where (admin=1 or (teacher=1 and block.idblock is not null and rg.idstudent=:identity ))  AND statelogin=1";
}

$retval =  self::$con->prepare($sql);
if(!$retval){
    return null;
}

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
