<?php
/**
 *
 * @package   Company
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */
require_once('conf/connection.php');

class Company{
private  $idcompany;
  private $nif;
  private $name;
  private $email;
  private $website;
  private $idlanguage;
  private $address;
  private $postalcode;
  private $locality;
  private $telephone;
  private $conservatory;
  private $registernumber;
  private $vatregimebox;
  private $idcountry;
  private $idcurrency;
  private $logo;
  private $iban;
  private $commentsdoc;
  private $typevat; //1=>quarterly, 2=>monthly
  private $numberresults;
  private $typepvp;
  private $mobilephone;
  private $sharecapital;
  private $state;
  private $createdat;
  private $updatedat;
  private $createdby;
  private $updatedby;
  private static $con;
  private static $class;


  public function __construct(){
    self::$con = new Connection();
    self::$class = $this;
  }

  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }



  public function getattribute() {
    return  get_object_vars($this);
  }



  public static function findOne($id){
    $sql = "SELECT * FROM `company` WHERE idcompany=:idcompany";
		$retval = self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':idcompany', $id, PDO::PARAM_INT);
		$retval->execute();

		if ($retval->rowCount() > 0) {
      $retval->setFetchMode(PDO::FETCH_CLASS, 'Company');
      $values = $retval->fetch();
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
  }


  public static function findbynif($nif){
    $sql = "SELECT * FROM `company` WHERE nif=:nif";
		$retval =self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':nif', $nif, PDO::PARAM_INT);
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
  }

  public static function findAllbycompany($idcompany){

    $sql= "SELECT * FROM entity where entity.email!='' and idcompany=:idcompany";

    $retval =  self::$con->prepare($sql);
        if(!$retval){
            return null;
        }
$retval->bindParam(':idcompany', $idcompany, PDO::PARAM_INT);
        $retval->execute();

        if ($retval->rowCount() > 0) {
            $values = $retval->fetchAll(PDO::FETCH_OBJ);
            $retval->closeCursor();
            return $values;
        }

        $retval->closeCursor();
        return null;
  }



  public function update(){
    $sql = "UPDATE `company` SET  `name`=:name,`website`=:website,`email`=:email,`idlanguage`=:idlanguage,`address`=:address,`postalcode`=:postalcode,`locality`=:locality,`telephone`=:telephone,`vatregimebox`=:vatregimebox,`idcountry`=:idcountry,`conservatory`=:conservatory,`registernumber`=:registernumber,`logo`=:logo,`commentsdoc`=:commentsdoc,`numberresults`=:numberresults,`typevat`=:typevat,`mobilephone`=:mobilephone,`sharecapital`=:sharecapital,`updatedby`=:updatedby,`updatedat`=now() WHERE idcompany=:idcompany";
    $retval =self::$con->prepare($sql);
    if(!$retval){
        return null;
    }
    $retval->bindParam(':name', $this->name, PDO::PARAM_STR);
    $retval->bindParam(':website', $this->website, PDO::PARAM_STR);
    $retval->bindParam(':email', $this->email, PDO::PARAM_STR);
    $retval->bindParam(':idlanguage', $this->idlanguage, PDO::PARAM_INT);
    $retval->bindParam(':address', $this->address, PDO::PARAM_STR);
    $retval->bindParam(':postalcode', $this->postalcode, PDO::PARAM_STR);
    $retval->bindParam(':locality', $this->locality, PDO::PARAM_STR);
    $retval->bindParam(':telephone', $this->telephone, PDO::PARAM_STR);
    $retval->bindParam(':vatregimebox', $this->vatregimebox, PDO::PARAM_INT);
    $retval->bindParam(':idcountry', $this->idcountry, PDO::PARAM_INT);
    $retval->bindParam(':conservatory', $this->conservatory, PDO::PARAM_STR);
    $retval->bindParam(':registernumber', $this->registernumber, PDO::PARAM_STR);
    $retval->bindParam(':logo', $this->logo, PDO::PARAM_STR);
    $retval->bindParam(':commentsdoc', $this->commentsdoc, PDO::PARAM_STR);
    $retval->bindParam(':numberresults', $this->numberresults, PDO::PARAM_INT);
    $retval->bindParam(':typevat', $this->typevat, PDO::PARAM_INT);
    $retval->bindParam(':mobilephone', $this->mobilephone, PDO::PARAM_STR);
    $retval->bindParam(':sharecapital', $this->sharecapital, PDO::PARAM_INT);
    $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);
    $retval->bindParam(':idcompany', $this->idcompany, PDO::PARAM_INT);

    if($retval->execute()) {
        $retval->closeCursor();
        return true;
    }

    $retval->closeCursor();
    return false;

  }

  public function updatelogo(){
    $sql = "UPDATE company SET logo=:logo WHERE idcompany=:idcompany";
    $retval =self::$con->prepare($sql);
    if(!$retval){
        return null;
    }
    $retval->bindParam(':idcompany', $this->idcompany, PDO::PARAM_INT);
    $retval->bindParam(':logo', $this->logo, PDO::PARAM_STR);
    if($retval->execute()) {
        $retval->closeCursor();
        return true;
    }

    $retval->closeCursor();
    return false;

  }

  public static function findAlltable($limitstart, $perpage, $order, $keyorder, $search){
    $limitstart = intval($limitstart);
    $perpage = intval($perpage);

    $sql = "SELECT `idcompany`,
                    `name`,
                    `website`,
                    `email`,
                    `nif`,
                    `idlanguage`,
                    `address`,
                    `postalcode`,
                    `locality`,
                    `telephone`,
                    `idcountry`,
                    `conservatory`,
                    `registernumber`,
                    `logo`,
                    `iban`,
                    `idcurrency`,
                    `typevat`,
                    `commentsdoc`,
                    `numberresults`,
                    `typepvp`,
                    `mobilephone`,
                    `sharecapital`,
                    `state`,
                    `createdat`,
                    `updatedat`
                    FROM `company` WHERE (UPPER(name)            LIKE UPPER(:search) OR
                                             UPPER(website)      LIKE UPPER(:search) OR
                                             UPPER(email)      LIKE UPPER(:search) OR
                                             UPPER(nif)  LIKE UPPER(:search) OR
                                             UPPER(idlanguage)  LIKE UPPER(:search) OR
                                             UPPER(address)  LIKE UPPER(:search) OR
                                             UPPER(postalcode)  LIKE UPPER(:search) OR
                                             UPPER(locality)  LIKE UPPER(:search) OR
                                             UPPER(telephone)  LIKE UPPER(:search) OR
                                             UPPER(idcountry)  LIKE UPPER(:search) OR
                                             UPPER(conservatory)  LIKE UPPER(:search) OR
                                             UPPER(registernumber)  LIKE UPPER(:search) OR
                                             UPPER(logo)  LIKE UPPER(:search) OR
                                             UPPER(iban)  LIKE UPPER(:search) OR
                                             UPPER(idcurrency)  LIKE UPPER(:search) OR
                                             UPPER(typevat)  LIKE UPPER(:search) OR
                                             UPPER(commentsdoc)  LIKE UPPER(:search) OR
                                             UPPER(numberresults)  LIKE UPPER(:search) OR
                                             UPPER(typepvp)  LIKE UPPER(:search) OR
                                             UPPER(mobilephone)  LIKE UPPER(:search) OR
                                             UPPER(sharecapital)  LIKE UPPER(:search)

                                             ) ORDER BY $keyorder $order, idcompany DESC LIMIT :start, :limit";

    $retval =self::$con->prepare($sql);
    if(!$retval){
        return null;
    }

    $retval->bindParam(':search', $search, PDO::PARAM_STR);
    $retval->bindParam(':start',  $limitstart, PDO::PARAM_INT);
    $retval->bindParam(':limit',  $perpage, PDO::PARAM_INT);
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
                            $sql= "SELECT count(idcompany) as total FROM `company` WHERE
                            (UPPER(name)            LIKE UPPER(:search) OR
                            UPPER(website)      LIKE UPPER(:search) OR
                            UPPER(email)      LIKE UPPER(:search) OR
                            UPPER(nif)  LIKE UPPER(:search) OR
                            UPPER(idlanguage)  LIKE UPPER(:search) OR
                            UPPER(address)  LIKE UPPER(:search) OR
                            UPPER(postalcode)  LIKE UPPER(:search) OR
                            UPPER(locality)  LIKE UPPER(:search) OR
                            UPPER(telephone)  LIKE UPPER(:search) OR
                            UPPER(idcountry)  LIKE UPPER(:search) OR
                            UPPER(conservatory)  LIKE UPPER(:search) OR
                            UPPER(registernumber)  LIKE UPPER(:search) OR
                            UPPER(logo)  LIKE UPPER(:search) OR
                            UPPER(iban)  LIKE UPPER(:search) OR
                            UPPER(idcurrency)  LIKE UPPER(:search) OR
                            UPPER(typevat)  LIKE UPPER(:search) OR
                            UPPER(commentsdoc)  LIKE UPPER(:search) OR
                            UPPER(numberresults)  LIKE UPPER(:search) OR
                            UPPER(typepvp)  LIKE UPPER(:search) OR
                            UPPER(mobilephone)  LIKE UPPER(:search) OR
                            UPPER(sharecapital)  LIKE UPPER(:search)";

  $retval =self::$con->prepare($sql);
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
  $sql = "INSERT INTO `company` (`name`,
                                `website`,
                                `email`,
                                `nif`,
                                `idlanguage`,
                                `address`,
                                `postalcode`,
                                `locality`,
                                `telephone`,
                                `vatregimebox`,
                                `idcountry`,
                                `conservatory`,
                                `registernumber`,
                                `logo`,
                                `iban`,
                                `idcurrency`,
                                `typevat`,
                                `commentsdoc`,
                                `numberresults`,
                                `typepvp`,
                                `mobilephone`,
                                `sharecapital`,
                                `state`,
                                `updatedby`,
                                `createdby`,
                                `createdat`,
                                `updatedat`) VALUES (    :name,
                                                          :website,
                                                          :email,
                                                          :nif,
                                                          :idlanguage,
                                                          :address,
                                                          :postalcode,
                                                          :locality,
                                                          :telephone,
                                                          :vatregimebox,
                                                          :idcountry,
                                                          :conservatory,
                                                          :registernumber,
                                                          :logo,
                                                          :iban,
                                                          :idcurrency,
                                                          :typevat,
                                                          :commentsdoc,
                                                          :numberresults,
                                                          :typepvp,
                                                          :mobilephone,
                                                          :sharecapital,
                                                          :state,
                                                          :updatedby,
                                                          :createdby,
                                                           now(), now())";

  $retval =self::$con->prepare($sql);
  if(!$retval){
      return false;
  }

  $retval->bindParam(':name',             $this->name, PDO::PARAM_STR);
  $retval->bindParam(':website',            $this->website, PDO::PARAM_STR);
  $retval->bindParam(':email',             $this->email, PDO::PARAM_STR);
  $retval->bindParam(':nif',             $this->nif, PDO::PARAM_STR);
  $retval->bindParam(':idlanguage',            $this->idlanguage, PDO::PARAM_INT);

  $retval->bindParam(':address',             $this->address,  PDO::PARAM_STR);
  $retval->bindParam(':postalcode',            $this->postalcode, PDO::PARAM_STR);
  $retval->bindParam(':locality',             $this->locality, PDO::PARAM_STR);
  $retval->bindParam(':telephone',             $this->telephone, PDO::PARAM_STR);
  $retval->bindParam(':idcountry',            $this->idcountry, PDO::PARAM_INT);

  $retval->bindParam(':conservatory',             $this->conservatory, PDO::PARAM_STR);
  $retval->bindParam(':registernumber',            $this->registernumber, PDO::PARAM_STR);
  $retval->bindParam(':vatregimebox',             $this->vatregimebox, PDO::PARAM_INT);
  $retval->bindParam(':logo',             $this->logo, PDO::PARAM_STR);
  $retval->bindParam(':iban',            $this->iban, PDO::PARAM_STR);

  $retval->bindParam(':idcurrency',             $this->idcurrency, PDO::PARAM_INT);
  $retval->bindParam(':typevat',            $this->typevat, PDO::PARAM_INT);
  $retval->bindParam(':commentsdoc',             $this->commentsdoc, PDO::PARAM_STR);
  $retval->bindParam(':numberresults',             $this->numberresults, PDO::PARAM_INT);
  $retval->bindParam(':typepvp',            $this->typepvp, PDO::PARAM_INT);

  $retval->bindParam(':mobilephone',             $this->mobilephone, PDO::PARAM_STR);
  $retval->bindParam(':sharecapital',            $this->sharecapital, PDO::PARAM_STR);
  $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);
  $retval->bindParam(':createdby',             $this->createdby, PDO::PARAM_INT);
  $retval->bindParam(':state',                 $this->state, PDO::PARAM_INT);



  if($retval->execute()) {
      $id =self::$con->lastInsertId();
      $this->idcompany = $id;
      $retval->closeCursor();
      return $id;
  }

  $retval->closeCursor();
  return false;
}

public function delete(){
  $idcompany = intval($this->idcompany);
  $dependencies = $this->deletedependencies();
  if(!$dependencies){return false;}
  $sql = "DELETE FROM company WHERE idcompany =:idcompany";

  $retval =self::$con->prepare($sql);
  if(!$retval){
      return null;
  }
  $retval->bindParam(':idcompany', $this->idcompany, PDO::PARAM_INT);

  if($retval->execute()) {
      $retval->closeCursor();
      return true;
  }

  $retval->closeCursor();
  return false;
}

private function deletedependencies(){
  $idcompany = intval($this->idcompany);
  $sql = "DELETE `serie`.*,vat.*, user.*, client.*, paymentform.* FROM `serie` join vat on vat.idcompany=serie.idcompany join user on user.idcompany=serie.idcompany join client on client.idcompany=serie.idcompany join paymentform on paymentform.idcompany=serie.idcompany  WHERE vat.idcompany=:idcompany";

  $retval =self::$con->prepare($sql);
  if(!$retval){
      return null;
  }
  $retval->bindParam(':idcompany', $this->idcompany, PDO::PARAM_INT);

  if($retval->execute()) {
      $retval->closeCursor();
      return true;
  }

  $retval->closeCursor();
  return false;
}
/////////////////////////////
public static function findAll(){

    $sql= "SELECT `idcompany`, `name`,
                   `website`,`email`,
                   `nif`,`idlanguage`,
                   `address`,`postalcode`,
                   `locality`,`telephone`,
                   `idcountry`,`conservatory`,
                   `registernumber`,`iban`,
                   `idcurrency`,`typevat`,
                   `commentsdoc`,`numberresults`,
                   `typepvp` ,`mobilephone`,
                   `sharecapital` FROM company";
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
}
?>
