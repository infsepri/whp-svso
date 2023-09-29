<?php

/**
 *
 * @package   Employee
 * @package    Models
 * @copyright  2020 SEPRI
 *
 */ 

require_once('models/Entity.php');

class Employee extends Entity {
	private $idemployee;
	
	private $language;
  	private $language2;

  


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

	public static function search($search='%', $limit=1, $schoolDefault){
		$sqlExtra = "";
		if(isset($schoolDefault)&&!empty($schoolDefault)){
		  $sqlExtra .= " AND entity.`idschool`=".$schoolDefault;
		}
		$sql = "SELECT *,employee.identity as id,entity.name as select_show FROM `employee` inner join entity on entity.identity=employee.identity WHERE entity.employee=1  AND (UPPER(entity.name) LIKE UPPER(:search))".$sqlExtra." ORDER BY idemployee DESC LIMIT :limit";
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

	public static function findbyentity($id){
   		return parent::findbyid($id);
	}


	public static function findbyemployee($id){
		$sql = "SELECT *,entity.name as name, entity.identity from entity 
		LEFT join employee on employee.identity=entity.identity 

		where entity.identity=:entity";
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

	public static function findbyIDemployeeparent($id){
		$sql = "SELECT * from entity inner join employee on employee.idemployeeparent=entity.identity where entity.identity=:entity";
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

	public static function findbyemployeeParent($id){
		$sql = "SELECT *, employee.identity as idemployee from entity
		inner join employee on employee.idemployeeparent=entity.identity
		inner join employeeparent on employeeparent.identity=entity.identity
		where employee.idemployeeparent=:entity";
		$retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}
		$retval->bindParam(':entity', $id, PDO::PARAM_INT);
		$retval->execute();
		if ($retval->rowCount() > 0) {
			$values = $retval->fetchAll(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
	}

	public static function findbyidemployeeentity($id){
		$sql = "SELECT * from employee inner join entity on entity.identity=employee.identity where employee.idemployee=:employee";
		$retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}
		$retval->bindParam(':employee', $id, PDO::PARAM_INT);
		$retval->execute();
		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			return $values;
		}

		$retval->closeCursor();
		return null;
	}
	public static function findEmployeeAll(){
		$sql = "SELECT `idemployee`, `entity`.`name` as nameentity,
											`entity`.`email` as email,
											`entity`.`mobilephone` as mobilephone,
											`currentschool`, `otherschools`,
											`gradenumber`, `numberfail`,
											`employee`.`identity`,
											`idemployeeparent`, `employee`.`createdby` , `entity`.`updatedby` as updatedbyentity ,
											 `entity`.`updatedat`as updatedatentity,
											 entityEmployeeparent.name as employeeparentName,
											 entityEmployeeparent.mobilephone as employeeparentMobilephone
											 FROM `employee`
											 INNER JOIN `entity` ON `entity`.identity = `employee`.identity
											 WHERE `entity`.`statelogin`=1";
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
	public static function findAlltable($limitstart, $perpage, $order, $keyorder, $search,$idschoolyear=null,$idlevelteaching=null,$iddiscipline=null,$idteacher=null){
		$limitstart = intval($limitstart);
		$perpage = intval($perpage);

		$sqlExtra = ""; $joinExtraregistrationemployee = ""; $joinExtrablock = ""; $joinExtralevel = ""; $joinExtrateacher = ""; $joinExtradiscipline = ""; $joinExtraschoolyear = "";


		if($idschoolyear!="" && $idschoolyear !=0){
			$idschoolyear = (int)$idschoolyear;
			$joinExtraregistrationemployee = "LEFT JOIN `registrationemployee`  ON   registrationemployee.idemployee = `employee`.`identity`";
			$joinExtrablock 							= "LEFT JOIN `block`  ON   block.idblock = `registrationemployee`.`idblock`";
			$joinExtralevel 							= "LEFT JOIN `leveldiscipline`  ON   leveldiscipline.idlevel = `employee`.`idlevel`";
			$joinExtrateacher 						= "LEFT JOIN `entity` as entityteacher ON entityteacher.identity = `block`.`idteacher`";
			$joinExtradiscipline 					= "LEFT JOIN `discipline`  ON   discipline.iddiscipline = `block`.`iddiscipline`";
			$joinExtraschoolyear 					= "LEFT JOIN `schoolyear`  ON   schoolyear.idschoolyear = `block`.`idschoolyear`";

			$sqlExtra .= " AND  schoolyear.idschoolyear=".$idschoolyear;

		}
		if($idlevelteaching!="" && $idlevelteaching !=0){
	    $idlevelteaching = (int)$idlevelteaching;

			$joinExtraregistrationemployee = "LEFT JOIN `registrationemployee`  ON   registrationemployee.idemployee = `employee`.`identity`";
			$joinExtrablock 							= "LEFT JOIN `block`  ON   block.idblock = `registrationemployee`.`idblock`";
			$joinExtralevel 							= "LEFT JOIN `leveldiscipline`  ON   leveldiscipline.idlevel = `employee`.`idlevel`";
			$joinExtrateacher 						= "LEFT JOIN `entity` as entityteacher ON entityteacher.identity = `block`.`idteacher`";
			$joinExtradiscipline 					= "LEFT JOIN `discipline`  ON   discipline.iddiscipline = `block`.`iddiscipline`";
			$joinExtraschoolyear 					= "LEFT JOIN `schoolyear`  ON   schoolyear.idschoolyear = `block`.`idschoolyear`";

			$sqlExtra .= " AND  employee.idlevel=".$idlevelteaching; }
		if($iddiscipline!="" && $iddiscipline !=0){
			 $iddiscipline = (int)$iddiscipline;
			$joinExtraregistrationemployee = "LEFT JOIN `registrationemployee`  ON   registrationemployee.idemployee = `employee`.`identity`";
			$joinExtrablock 							= "LEFT JOIN `block`  ON   block.idblock = `registrationemployee`.`idblock`";
			$joinExtralevel 							= "LEFT JOIN `leveldiscipline`  ON   leveldiscipline.idlevel = `employee`.`idlevel`";
			$joinExtrateacher 						= "LEFT JOIN `entity` as entityteacher ON entityteacher.identity = `block`.`idteacher`";
			$joinExtradiscipline 					= "LEFT JOIN `discipline`  ON   discipline.iddiscipline = `block`.`iddiscipline`";
			$joinExtraschoolyear 					= "LEFT JOIN `schoolyear`  ON   schoolyear.idschoolyear = `block`.`idschoolyear`";

			$sqlExtra .= " AND  block.iddiscipline=".$iddiscipline; }
		if($idteacher!="" && $idteacher !=0){

			$idteacher = (int)$idteacher;
			$joinExtraregistrationemployee = "LEFT JOIN `registrationemployee`  ON   registrationemployee.idemployee = `employee`.`identity`";
			$joinExtrablock 							= "LEFT JOIN `block`  ON   block.idblock = `registrationemployee`.`idblock`";
			$joinExtralevel 							= "LEFT JOIN `leveldiscipline`  ON   leveldiscipline.idlevel = `employee`.`idlevel`";
			$joinExtrateacher 						= "LEFT JOIN `entity` as entityteacher ON entityteacher.identity = `block`.`idteacher`";
			$joinExtradiscipline 					= "LEFT JOIN `discipline`  ON   discipline.iddiscipline = `block`.`iddiscipline`";
			$joinExtraschoolyear 					= "LEFT JOIN `schoolyear`  ON   schoolyear.idschoolyear = `block`.`idschoolyear`";

			$sqlExtra .= " AND  block.idteacher=".$idteacher; }


		$sql = "SELECT `employee`.`idemployee`, `entity`.`name` as nameentity,
											`entity`.`email` as email,
											`entity`.`photo` as photo,
											`entity`.`mobilephone` as mobilephone,
											`currentschool`, `otherschools`,
											`gradenumber`, `numberfail`,
											`employee`.`identity`,
											`employee`.`idemployeeparent`, `employee`.`createdby` , `entity`.`updatedby` as updatedbyentity ,
											`entity`.`updatedat`as updatedatentity, entityEmployeeparent.namemother as employeeparentName,
											 entityEmployeeparent.namefather as employeeparentNamef,
											entityEmployeeparent.workphonemother as employeeparentMobilephone,
											entityEmployeeparent.workphonefather as employeeparentMobilephonef,
											entityEmployeeparent.greeparent as greeparent ,entityEmployeeparent.email as greeparentemail
											 FROM `employee`
											LEFT JOIN `entity` ON `entity`.identity = `employee`.identity
											LEFT JOIN `employeeparent` as entityEmployeeparent ON entityEmployeeparent.idemployeeparent = `employee`.`idemployeeparent`
  									  ".$joinExtraregistrationemployee."
											".$joinExtrablock."
											".$joinExtralevel."
											".$joinExtrateacher."
											".$joinExtradiscipline."
											".$joinExtraschoolyear."

											WHERE `entity`.`statelogin`=1 AND
											(UPPER(`entity`.`name`) LIKE UPPER(:search) OR UPPER(`entity`.`mobilephone`) LIKE UPPER(:search) OR UPPER(`entity`.`email`) LIKE UPPER(:search))

										 ".$sqlExtra." GROUP BY $keyorder $order, `entity`.`identity` DESC LIMIT :start, :limit";

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

	public static function countall($search,$idschoolyear=null,$idlevelteaching=null,$iddiscipline=null,$idteacher=null){
		$sqlExtra = ""; $joinExtraregistrationemployee = ""; $joinExtrablock = ""; $joinExtralevel = ""; $joinExtrateacher = ""; $joinExtradiscipline = ""; $joinExtraschoolyear = "";

		if($idschoolyear!="" && $idschoolyear !=0){
			$idschoolyear = (int)$idschoolyear;
			$joinExtraregistrationemployee = "LEFT JOIN `registrationemployee`  ON   registrationemployee.idemployee = `employee`.`identity`";
			$joinExtrablock 							= "LEFT JOIN `block`  ON   block.idblock = `registrationemployee`.`idblock`";
			$joinExtralevel 							= "LEFT JOIN `leveldiscipline`  ON   leveldiscipline.idlevel = `employee`.`idlevel`";
			$joinExtrateacher 						= "LEFT JOIN `entity` as entityteacher ON entityteacher.identity = `block`.`idteacher`";
			$joinExtradiscipline 					= "LEFT JOIN `discipline`  ON   discipline.iddiscipline = `block`.`iddiscipline`";
			$joinExtraschoolyear 					= "LEFT JOIN `schoolyear`  ON   schoolyear.idschoolyear = `block`.`idschoolyear`";

			$sqlExtra .= " AND  schoolyear.idschoolyear=".$idschoolyear;

		}
		if($idlevelteaching!="" && $idlevelteaching !=0){
			$idlevelteaching = (int)$idlevelteaching;

			$joinExtraregistrationemployee = "LEFT JOIN `registrationemployee`  ON   registrationemployee.idemployee = `employee`.`identity`";
			$joinExtrablock 							= "LEFT JOIN `block`  ON   block.idblock = `registrationemployee`.`idblock`";
			$joinExtralevel 							= "LEFT JOIN `leveldiscipline`  ON   leveldiscipline.idlevel = `employee`.`idlevel`";
			$joinExtrateacher 						= "LEFT JOIN `entity` as entityteacher ON entityteacher.identity = `block`.`idteacher`";
			$joinExtradiscipline 					= "LEFT JOIN `discipline`  ON   discipline.iddiscipline = `block`.`iddiscipline`";
			$joinExtraschoolyear 					= "LEFT JOIN `schoolyear`  ON   schoolyear.idschoolyear = `block`.`idschoolyear`";

			$sqlExtra .= " AND  employee.idlevelteaching=".$idlevelteaching; }
		if($iddiscipline!="" && $iddiscipline !=0){
			 $iddiscipline = (int)$iddiscipline;
			$joinExtraregistrationemployee = "LEFT JOIN `registrationemployee`  ON   registrationemployee.idemployee = `employee`.`identity`";
			$joinExtrablock 							= "LEFT JOIN `block`  ON   block.idblock = `registrationemployee`.`idblock`";
			$joinExtralevel 							= "LEFT JOIN `leveldiscipline`  ON   leveldiscipline.idlevel = `employee`.`idlevel`";
			$joinExtrateacher 						= "LEFT JOIN `entity` as entityteacher ON entityteacher.identity = `block`.`idteacher`";
			$joinExtradiscipline 					= "LEFT JOIN `discipline`  ON   discipline.iddiscipline = `block`.`iddiscipline`";
			$joinExtraschoolyear 					= "LEFT JOIN `schoolyear`  ON   schoolyear.idschoolyear = `block`.`idschoolyear`";

			$sqlExtra .= " AND  block.iddiscipline=".$iddiscipline; }
		if($idteacher!="" && $idteacher !=0){

			$idteacher = (int)$idteacher;
			$joinExtraregistrationemployee = "LEFT JOIN `registrationemployee`  ON   registrationemployee.idemployee = `employee`.`identity`";
			$joinExtrablock 							= "LEFT JOIN `block`  ON   block.idblock = `registrationemployee`.`idblock`";
			$joinExtralevel 							= "LEFT JOIN `leveldiscipline`  ON   leveldiscipline.idlevel = `employee`.`idlevel`";
			$joinExtrateacher 						= "LEFT JOIN `entity` as entityteacher ON entityteacher.identity = `block`.`idteacher`";
			$joinExtradiscipline 					= "LEFT JOIN `discipline`  ON   discipline.iddiscipline = `block`.`iddiscipline`";
			$joinExtraschoolyear 					= "LEFT JOIN `schoolyear`  ON   schoolyear.idschoolyear = `block`.`idschoolyear`";

			$sqlExtra .= " AND  block.idteacher=".$idteacher; }

		$sql= "SELECT count(`employee`.idemployee) as total FROM `employee`
		LEFT JOIN `entity` ON `entity`.identity = `employee`.identity
		LEFT JOIN `employeeparent` as entityEmployeeparent ON entityEmployeeparent.idemployeeparent = `employee`.`idemployeeparent`

		".$joinExtraregistrationemployee."
		".$joinExtrablock."
		".$joinExtralevel."
		".$joinExtrateacher."
		".$joinExtradiscipline."
		".$joinExtraschoolyear."

		 WHERE (UPPER(currentschool) LIKE UPPER(:search)  OR UPPER(otherschools) LIKE UPPER(:search) OR UPPER(gradenumber) LIKE UPPER(:search)) ".$sqlExtra;

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

	public function save2() {
		if($this->identity==null){
			$aux=parent::save();
			if($aux==False){
				return false;
			}
		}else{
			$aux=$this->identity;
		}

		$sql = "INSERT INTO `employee` (`currentschool`,  `otherschools`, `gradenumber`, `numberfail`, `identity`,`createdby` , `updatedby`, `createdat`, `updatedat`) VALUES
		(:currentschool,  :otherschools, :gradenumber, :numberfail, :identity,:createdby , :updatedby, now(), now())";
		$retval =  self::$con->prepare($sql);
		if(!$retval){
			file_put_contents("EMPLOYEE_SAVE_011.txt". print_r($retval->error, true));
			return false;
		}

		$retval->bindParam(':currentschool', $this->currentschool, PDO::PARAM_STR);
		$retval->bindParam(':otherschools', $this->otherschools, PDO::PARAM_STR);
		$retval->bindParam(':gradenumber', $this->gradenumber, PDO::PARAM_STR);
	
		$retval->bindParam(':numberfail', $this->numberfail, PDO::PARAM_INT);
	
		$retval->bindParam(':identity', $aux, PDO::PARAM_INT);
 
    $retval->bindParam(':createdby', $this->createdby, PDO::PARAM_INT);
    $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);

		if($retval->execute()) {
			$id = self::$con->lastInsertId();
			$retval->closeCursor();
			return $aux;
		}

		$retval->closeCursor();
		file_put_contents("EMPLOYEE_SAVE_02.txt", print_r($retval->error, true));
		return false;
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

		$sql = "INSERT INTO `employee` (`identity`,`createdby` , `updatedby`, `createdat`, `updatedat`) VALUES
		(:identity,:createdby , :updatedby, now(), now())";
		$retval =  self::$con->prepare($sql);
		if(!$retval){
			file_put_contents("EMPLOYEE_SAVE_011.txt". print_r($retval->error, true));
			return false;
		}

	
		$retval->bindParam(':identity', $aux, PDO::PARAM_INT);
		$retval->bindParam(':createdby', $this->createdby, PDO::PARAM_INT);
		$retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);

		if($retval->execute()) {
			$id = self::$con->lastInsertId();
			$retval->closeCursor();
			return $aux;
		}

		$retval->closeCursor();
		file_put_contents("EMPLOYEE_SAVE_02.txt", print_r($retval->error, true));
		return false;
	}




	public function update() {


			$aux=parent::update();
			if($aux==false){
				return false;
			}


		$sql = "UPDATE `employee` SET `updatedby`=:updatedby, `updatedat`=now() WHERE identity=:identity";
		$retval =  self::$con->prepare($sql);
		if(!$retval){
			return false;
		}


		$retval->bindParam(':identity', $this->identity, PDO::PARAM_INT);
		$retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);

		if($retval->execute()) {
			$retval->closeCursor();
			return true;
		}

		$retval->closeCursor();
		return false;
	}


	public function updatestate() {
		$sql = "UPDATE `employee` SET state=:state,`updatedat`=now() WHERE idemployee=:idemployee";
		$retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}

		$retval->bindParam(':state', $this->state, PDO::PARAM_INT);
		$retval->bindParam(':idemployee', $this->idemployee, PDO::PARAM_INT);

		if($retval->execute()) {
			$retval->closeCursor();
			return true;
		}

		$retval->closeCursor();
		return false;
	}

	public static function getNextID() {
		$sql = "SELECT MAX(idemployee) as cod FROM `employee` LIMIT 1";
		$retval =  self::$con->prepare($sql);
		if(!$retval){
			return null;
		}
		$retval->execute();

		if ($retval->rowCount() > 0) {
			$values = $retval->fetch(PDO::FETCH_OBJ);
			$retval->closeCursor();
			$res = $values->cod + 1;
			return $res;
		}

		$retval->closeCursor();
		return 1;
	}

	public static function findemployeeby($id){
				$sql = "SELECT *,entity.name from employee
				inner join entity on employee.identity=entity.identity where employee.idemployee=:idemployee";
				$retval =  self::$con->prepare($sql);
				if(!$retval){
					return null;
				}
				$retval->bindParam(':idemployee', $id, PDO::PARAM_INT);
				$retval->execute();
				if ($retval->rowCount() > 0) {
					$values = $retval->fetch(PDO::FETCH_OBJ);
					$retval->closeCursor();
					return $values;
				}

				$retval->closeCursor();
				return null;
			}

			/*public static function findbyemployeeParentsby($id){
						$sql = "SELECT
										`entity`.`name` as nameentity,
										`entity`.`email` as email,
										`entity`.`mobilephone` as mobilephone,
                    `entity`.`photo` as photo,
										`currentschool`,
										`otherschools`,
										 `gradenumber`,
										 `numberfail`,
										 `employee`.`identity`,
										 `idemployeeparent`,
										 `employee`.`createdby` ,
										 `entity`.`updatedby` as updatedbyentity ,
										 `entity`.`updatedat`as updatedatentity,
										  entityUpdate.name as userUpd
											FROM `employee`
											INNER JOIN `entity` ON `entity`.identity = `employee`.identity

											INNER JOIN `entity` as entityUpdate ON entityUpdate.identity = `employee`.`updatedby`
                                            where employee.idemployeeparent=:idemployeeparent";


						$retval =  self::$con->prepare($sql);
						if(!$retval){
							return null;
						}
						$retval->bindParam(':idemployeeparent', $id, PDO::PARAM_INT);
						$retval->execute();
						if ($retval->rowCount() > 0) {
							$values = $retval->fetchAll(PDO::FETCH_OBJ);
							$retval->closeCursor();
							return $values;
						}

						$retval->closeCursor();
						return null;
					}*/


					public static function findbiograficdataEmployeeAlltable($id){

					$sql = "SELECT *, entity.name as nameemployee,
	 					entity.photo as photo,
	   				entity.identitycard as identitycard,
	    			entity.identitycardvalidity as identitycardvalidity,
	     			entity.nif as nif,
	      		entity.socialsecurity as socialsecurity,
			      entity.iban as iban,
						entity.placeofbirth as placeofbirth,
			      entity.address as address,
			      entity.postalcode as postalcode,
			      entity.locality as locality,
			      entity.mobilephone as mobilephone,
			      entity.email as email,
						entityParent.name as nameEmployeeParent,
            entityParent.nif  as nifEmployeeParent,
            entityParent.identitycard as identitycardEmployeeParent,
            entityParent.identitycardvalidity as identitycardvalidityEmployeeParent,
            entityParent.address as addressEmployeeParent,
            entityParent.postalcode as postalcodeEmployeeParent,
            entityParent.email as emailEmployeeParent,
            entityParent.mobilephone as mobilephoneEmployeeParent,
            entityParent.iban as ibanEmployeeParent,
            entityParent.bic as bicEmployeeParent,
            entityParent.locality as localityEmployeeParent,
            grade.name as namegradeEmployeeParent,
            company.logo as logo,

            (SELECT idservicefood FROM servicefood WHERE idemployee=:identity LIMIT 1) as servFood,
            (SELECT idservicetransport FROM servicetransport WHERE idemployee=:identity LIMIT 1) as servtransport
						from employee
								INNER JOIN entity on entity.identity=employee.identity
								LEFT JOIN employeeparent on employeeparent.identity=employee.idemployeeparent
						    LEFT JOIN `entity` as entityParent ON entityParent.identity = `employeeparent`.`identity`
						    LEFT JOIN grade on grade.idgrade= employeeparent.idgrade
                LEFT JOIN company on company.idcompany=entity.idcompany

								where entity.identity=:identity";
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
    
			$sql = "DELETE FROM `employee` WHERE identity=:identity";
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
