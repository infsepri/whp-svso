
        <?php
        /**
        *
        * @package   Role
        * @package    Models
        * @copyright  2020 SEPRI
        *
        */
        require_once('conf/connection.php');

        class Role{
            private $idrole;
            private $name;

            private $createdby;
            private $createdat;
            private $updatedat;
            private $updatedby;
            private static $class;
            private static $con;



            public function __construct(){
                Role::$con = new Connection();
              self::$class = $this;
            }
            public function set($attribute, $content){
        		$this->$attribute = $content;
        	}
        	public function get($attribute){
        		return $this->$attribute;
        	}


        	public static function search($search='%', $limit=1, $super){
            $sqlExtra = "";
            if(isset($super)&&empty($super)){
              $idrole=1;
              $sqlExtra .= " AND role.`idrole`!=".$idrole;
            }
        		$sql = "SELECT *, idrole as id, name as select_show FROM `role` WHERE  (UPPER(name) LIKE UPPER(:search))  ".$sqlExtra." ORDER BY idrole DESC LIMIT :limit";
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
            public static function searchex(){
              $sql = "SELECT * FROM `role` WHERE  state=1 ";
            $retval =  self::$con->prepare($sql);
            if(!$retval){
                    return -1;
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
        

            public static function findbyid($id){

                $sql = "SELECT `idrole`, `name`, `createdat`, `updatedat` FROM `role` WHERE idrole=:idrole";
                $retval =  self::$con->prepare($sql);
                if(!$retval){
                return false;
                }

        		$retval->bindParam(':idrole', $id, PDO::PARAM_INT);
        		$retval->execute();

        		if ($retval->rowCount() > 0) {
        			$values = $retval->fetch(PDO::FETCH_OBJ);
        			$retval->closeCursor();
        			return $values;
        		}

        		$retval->closeCursor();
        		return null;
            }

            //public static function findAll($id){}

            public static function findbyname($name){

                $sql = "SELECT idrole,`name` FROM `role` WHERE (`role`.`name` LIKE :name)";
                $retval =  self::$con->prepare($sql);
                if(!$retval){
                return false;
                }


            $retval->bindParam(':name', $name, PDO::PARAM_STR);
            $retval->execute();

            if ($retval->rowCount() > 0) {
              $values = $retval->fetch(PDO::FETCH_OBJ);
              $retval->closeCursor();
              return $values;
            }

            $retval->closeCursor();
            return null;
            }

            public static function findbynameid($name,$idrole){

                $sql = "SELECT `name` FROM `role` WHERE (`role`.`name` LIKE :name) and idrole!=:idrole";
                $retval =  self::$con->prepare($sql);
                if(!$retval){
                return false;
                }


            $retval->bindParam(':name', $name, PDO::PARAM_STR);
            $retval->bindParam(':idrole', $idrole, PDO::PARAM_INT);
            $retval->execute();

            if ($retval->rowCount() > 0) {
              $values = $retval->fetch(PDO::FETCH_OBJ);
              $retval->closeCursor();
              return $values;
            }

            $retval->closeCursor();
            return null;
            }


            public static function findAlltable($limitstart, $perpage, $order, $keyorder, $search,$idrole=null,$super){
        		$limitstart = intval($limitstart);
        		$perpage = intval($perpage);
            $sqlExtra = "";
            $sqlExtra1 = "";
            if(isset($super)&&empty($super)){
              $idrole=1;
              $sqlExtra1 .= " AND role.`idrole`!=".$idrole;
            }

            if(!empty($idrole)) {
              $idrole = ($idrole);
              $sqlExtra .= " AND idrole !=".$idrole; 
            }
        		$sql = "SELECT `role`.`idrole`,`entity`.`name` as userUpd,`role`.`name` , `role`.`createdat`,`role`.`updatedby`, `role`.`updatedat`
            FROM `role`
            LEFT JOIN `entity` ON `entity`.identity = `role`.updatedby
            WHERE (UPPER(idrole) LIKE UPPER(:search)OR UPPER(`role`.`name`) LIKE UPPER(:search)OR UPPER(`role`.`updatedby`) LIKE UPPER(:search) OR UPPER(`role`.`updatedat`) LIKE UPPER(:search) ".$sqlExtra.")  ".$sqlExtra1." ORDER BY $keyorder $order, idrole DESC LIMIT :start, :limit";
        	//	(UPPER(`role`.`name`) LIKE UPPER(:search)) GROUP BY $keyorder $order, `role`.name DESC LIMIT :start, :limit";
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

            public static function countall($search,$idrole=null,$super){
              $sqlExtra = "";
              $sqlExtra1 = "";
              if(!empty($idrole)) {
                $idrole = ($idrole);
                $sqlExtra .= " AND idrole !=".$idrole; 
              }

              if(isset($super)&&empty($super)){
                $idrole=1;
                $sqlExtra1 .= " AND role.`idrole`!=".$idrole;
              }
        		$sql= "SELECT count(idrole) as total FROM `role`  
            
            LEFT JOIN `entity` ON `entity`.identity = `role`.updatedby WHERE  (UPPER(`role`.`name`) LIKE UPPER(:search) OR UPPER(idrole) LIKE UPPER(:search))".$sqlExtra." ".$sqlExtra1."";

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

            public function save(){
                $sql = "INSERT INTO `role` (name,createdat, updatedat,createdby, updatedby) VALUES (:name,now(),now(), :createdby,:updatedby)";
                $retval =  self::$con->prepare($sql);
                if(!$retval){
                return false;
                }

                $retval->bindParam(':name',                   $this->name, PDO::PARAM_STR);
                $retval->bindParam(':createdby',            $this->createdby, PDO::PARAM_INT);
                $retval->bindParam(':updatedby',             $this->updatedby, PDO::PARAM_INT);

                if($retval->execute()) {
                $id =  self::$con->lastInsertId();
                $retval->closeCursor();
                return $id;
                }

                $retval->closeCursor();
                return false;
            }
            public function update(){
                $sql = "UPDATE `role` SET name=:name,`updatedby`=:updatedby,`updatedat`=now() WHERE idrole=:idrole";
                $retval =  self::$con->prepare($sql);
                    if(!$retval){
                        return null;
                    }
                    $retval->bindParam(':idrole', $this->idrole, PDO::PARAM_INT);
                    $retval->bindParam(':name', $this->name, PDO::PARAM_STR);
                    $retval->bindParam(':updatedby', $this->updatedby, PDO::PARAM_INT);


                    if($retval->execute()) {
                        $retval->closeCursor();
                        return true;
                    }

                    $retval->closeCursor();
                    return false;
            }

          public static function findAll(){
            $sql = "SELECT * FROM `role`";
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

        public static function countallrole(){
          $sql="SELECT count(idrole) as total FROM `role`";

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
