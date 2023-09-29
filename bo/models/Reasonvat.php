<?php

/**
 *
 * @package   Reasonvat
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */  
require_once('conf/connection.php');

class Reasonvat{
  private $idreasonvat;
  private $reason;
  private $createdby;
  private $createdat;
  private $updatedat;
  private static $con;
  private $updatedby;
  private static $class;
  private $state;



  public function __construct(){
    Reasonvat::$con = new Connection();
    $this->valuesreasons();
    self::$class = $this;
  }

  public function set($attribute, $content){
    $this->$attribute = $content;
  }

  public function get($attribute){
    return $this->$attribute;
  }

  public function savereasonvat(){

   $reasons = Reasonvat::findAll();
    if(isset($reasons)){
      $countreason = count($reasons);
    }else{
      $countreason = 0;
    }

    if($reasons==null || $countreason==0){
      foreach ($this->reasons as $key => $value) {
        $this->idreasonvat = $key;
        $this->code = $this->codes[$key];
        $this->reason = $value;
        $this->save();
      }
    }
  }

  private function valuesreasons(){

    $this->reasons = array (
          1 =>  'Sem isenção',
          2 =>  'Isento Artigo 14º do RITI.',
          3 =>  'Regime de margem de lucro - Obj. de coleção e antiguidades',
          4 =>  'Regime de margem de lucro - Objectos de arte.',
          5 =>  'Regime de margem de lucro - Bens em segunda mão.',
          6 =>  'Regime de margem de lucro - Agências de viagens',
          7 =>  'Regime particular do tabaco.',
          8 =>  'IVA - Regime de isenção.',
          9 =>  'Não sujeito; não tributado (ou similar).',
          10 =>  'IVA - não confere direito a dedução.',
          11 =>  'IVA - autoliquidação.',
          12 =>  'Isenção Artigo 9º do CIVA.',
          13 =>  'Isenção Artigo 15º do CIVA.',
          14 =>  'Isenção Artigo 14º do CIVA.',
          15 =>  'Isenção Artigo 13º do CIVA.',
          16 =>  'Exigibilidade de caixa.',
          17 =>  'Artigo 6º do Decreto-Lei nº 198/90, de 19 de Junho.',
          18 =>  'Artigo 16º nº6 do CIVA.',
          19 =>  'IVA - Regime forfetário'
    );

    $this->codes = array (
          1 => '000',
          2 =>  'M16',
          3 =>  'M15',
          4 =>  'M14',
          5 =>  'M13',
          6 =>  'M12',
          7 =>  'M11',
          8 =>  'M10',
          9 =>  'M99',
          10 =>  'M09',
          11 =>  'M08',
          12 =>  'M07',
          13 =>  'M06',
          14 =>  'M05',
          15 =>  'M04',
          16 =>  'M03',
          17 =>  'M02',
          18 =>  'M01',
          19 =>  'M20'
    );
  }

  public static function findAll(){

    $sql= "SELECT idreasonvat, code, reason, createdat, updatedat, createdby, updatedby FROM reasonvat ";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

    $retval->execute();

    if ($retval->rowCount() > 0) {
      $values = $retval->fetch(PDO::FETCH_OBJ);
      $retval->closeCursor();
      return $values;
    }

    $retval->closeCursor();
    return null;

  }


  public static function findAPI(){

    $sql= "SELECT idreasonvat, code, reason FROM reasonvat ";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
      return null;
    }

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
    $sql = "INSERT INTO reasonvat (code,reason, state,createdat, updatedat,createdby, updatedby) VALUES (:code,:reason,:state,now(),now(), :createdby,:updatedby)";
    $retval =  self::$con->prepare($sql);
    if(!$retval){
    return false;
    }
    $retval->bindParam(':code',             $this->code, PDO::PARAM_STR);
    $retval->bindParam(':reason',             $this->reason, PDO::PARAM_STR);
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



  public static function findbyreason($reason){

    $sql= "SELECT idreasonvat, reason,  code FROM reasonvat WHERE reason=:reason order by idreasonvat ASC";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':reason', $reason, PDO::PARAM_STR);
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
  }



  public static function findbyid($id){

    $sql= "SELECT idreasonvat, reason,  code FROM reasonvat WHERE idreasonvat=:idreasonvat ";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':idreasonvat', $id, PDO::PARAM_INT);
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
  }



  public static function findbyVat($idvat){

    $sql= "SELECT idreasonvat as id, reason as select_show, code FROM reasonvat WHERE      IF((select vat from vat where vat.idvat=:idvat limit 1)=0, code!='000', code='000')";
    $retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':idvat', $idvat, PDO::PARAM_INT);
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
