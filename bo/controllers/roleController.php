<?php
/**
*
* @package    Role
* @package    Controller
* @copyright  2020 SEPRI
*
*/
require_once("conf/globalconf.php");
require_once("conf/configEmail.php");
require_once("conf/accesscontrol.php");
require_once("libraries/other/common.php");
require_once("models/Operationrole.php");
require_once("models/Operation.php");
require_once("models/Role.php");
class roleController{

	public function __construct() {
		$this->role = new Role();
		$this->operationrole = new Operationrole();
		$this->operation = new Operation();
    }

	public function getrole(){
        Accesscontrol::beforeActionSession();
        $identity = $_SESSION['entity']['identity'];
        $super=0;
          if(isset($identity)&&!empty($identity)&&$identity==2){
            $super=1;
          }
       $search = isset($_GET['q']) ? "%".$_GET['q']."%" : "%";
       session_write_close();
       $role = Role::search($search, 50, $super);
       if($role==null){
           print json_encode(array());die();
       }
       print json_encode($role);
   }


   public function index() {
    Accesscontrol::beforeActionSession("management_role", 1,[1]);
   
    //_______________________________________________________________
         $identity = $_SESSION['entity']['identity'];
         $usertype= $_SESSION['entity']['userT'];//---START Verificação
         $typuser=$usertype[$_SESSION['defaultprofile']];
         if(isset($typuser)&&  $typuser!=1){
           if($typuser==2){$service=false;
             $localization= Common::get_client_ip();
             $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $line = array("Data"=>date('Y-m-d H:i:s'),"Localização"=>$localization, "Nº Utilizador"=>$identity, "Diretorio"=>$actual_link);
             Common::notifyError($line);
             Common::response($service, "?controller=student&action=dashboard&alert-danger=not_permit_not_have_permission", array('id'=>0, 'code'=>201 , 'msg'=>'not_permit_not_have_permission'));die();
     
         }else{ $service=false;
             $localization= Common::get_client_ip();
             $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $line = array("Data"=>date('Y-m-d H:i:s'),"Localização"=>$localization, "Nº Utilizador"=>$identity, "Diretorio"=>$actual_link);
             Common::notifyError($line);
             Common::response($service, "?controller=home&action=dashboard&alert-danger=not_permit_not_have_permission", array('id'=>0, 'code'=>201 , 'msg'=>'not_permit_not_have_permission'));die();
           }
         }//---END Verificação
    //_______________________________________________________________
            $breadcrumbs = array(
                array("icon"=>"clip-cog-2", "msg"=>"MENU_MANAGEMENT"),
                array("active"=>1, "url"=>"?controller=role&action=index", "msg"=>"MENU_ROLE")
            );

       $view = 'views/role/index.php';
       require_once("views/layout/_layout.php");
   }

   public function getall() {
            Accesscontrol::beforeActionSession();

        $identity= $_SESSION['entity']['identity'];
        $idrole=null;
        if(isset($identity) && $identity!=1){
          $idrole=1;
        }
        $super=0;
          if(isset($identity)&&!empty($identity)&&$identity==2){
            $super=1;
          }
        session_write_close();

        $search = "%"; $page = 1;
        $perpage = $_POST['numberelements'];
        if (isset($_POST['page']) && isset($_POST['order']) && isset($_POST['keyorder'])  && isset($_POST['search']) ){
            $page = $_POST['page'];
            $search =  "%".$_POST['search']."%";
            $order = $_POST['order'];
            $keyorder = $_POST['keyorder'];
        }
        $order = (empty($order)) ? "asc" : $order;
        $keyorder = (empty($keyorder)) ? "role.name" : $keyorder;

        $limitstart = intval($page) * intval($perpage) - intval($perpage);

        $arr = Role::findAlltable($limitstart, $perpage, $order, $keyorder, $search,$idrole, $super);

        $total = Role::countall($search,$idrole, $super);
    $totalReg = $total;
        if($total==0){
            $total = 1;
        }
    
        $showing = empty($arr) ? 0 : sizeof($arr);
        $start = $limitstart;
        $total = ceil($total / $perpage);

        require_once('views/role/table.php');
    }


    public function newr() {
      Accesscontrol::beforeActionSession("management_role", 2,[1]);
      Accesscontrol::beforeActionSession();
    //_______________________________________________________________
         $identity = $_SESSION['entity']['identity'];
         $usertype= $_SESSION['entity']['userT'];//---START Verificação
         $typuser=$usertype[$_SESSION['defaultprofile']];
         if(isset($typuser)&&  $typuser!=1){
           if($typuser==2){$service=false;
             $localization= Common::get_client_ip();
             $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $line = array("Data"=>date('Y-m-d H:i:s'),"Localização"=>$localization, "Nº Utilizador"=>$identity, "Diretorio"=>$actual_link);
             Common::notifyError($line);
             Common::response($service, "?controller=student&action=dashboard&alert-danger=not_permit_not_have_permission", array('id'=>0, 'code'=>201 , 'msg'=>'not_permit_not_have_permission'));die();
     
         }else{ $service=false;
             $localization= Common::get_client_ip();
             $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $line = array("Data"=>date('Y-m-d H:i:s'),"Localização"=>$localization, "Nº Utilizador"=>$identity, "Diretorio"=>$actual_link);
             Common::notifyError($line);
             Common::response($service, "?controller=home&action=dashboard&alert-danger=not_permit_not_have_permission", array('id'=>0, 'code'=>201 , 'msg'=>'not_permit_not_have_permission'));die();
           }
         }//---END Verificação
    //_______________________________________________________________

                 $breadcrumbs = array(
                    array("icon"=>"clip-cog-2", "msg"=>"MENU_MANAGEMENT"),
                    array("url"=>"?controller=role&action=index", "msg"=>"MENU_ROLE"),
                    array("active"=>1, "msg"=>"MENU_NEWF", "url"=>"?controller=role&action=newr")
                );



				 $operation = Operation::findAllGroup();
       $view = "views/role/create.php";
       require_once("views/layout/_layout.php");
   }

public function create() {
    Accesscontrol::beforeActionSession();
    //_______________________________________________________________
         $identity = $_SESSION['entity']['identity'];
         $usertype= $_SESSION['entity']['userT'];//---START Verificação
         $typuser=$usertype[$_SESSION['defaultprofile']];
         if(isset($typuser)&&  $typuser!=1){
           if($typuser==2){$service=false;
             $localization= Common::get_client_ip();
             $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $line = array("Data"=>date('Y-m-d H:i:s'),"Localização"=>$localization, "Nº Utilizador"=>$identity, "Diretorio"=>$actual_link);
             Common::notifyError($line);
             Common::response($service, "?controller=student&action=dashboard&alert-danger=not_permit_not_have_permission", array('id'=>0, 'code'=>201 , 'msg'=>'not_permit_not_have_permission'));die();
     
         }else{ $service=false;
             $localization= Common::get_client_ip();
             $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $line = array("Data"=>date('Y-m-d H:i:s'),"Localização"=>$localization, "Nº Utilizador"=>$identity, "Diretorio"=>$actual_link);
             Common::notifyError($line);
             Common::response($service, "?controller=home&action=dashboard&alert-danger=not_permit_not_have_permission", array('id'=>0, 'code'=>201 , 'msg'=>'not_permit_not_have_permission'));die();
           }
         }//---END Verificação
   //_______________________________________________________________
   $service=false;
   if(isset($_POST['service'])){$service=true;}

   $identity= $_SESSION['entity']['identity'];

   $rolePost = isset($_POST['role']) ? $_POST['role'] : array();
   $operation = isset($_POST['operation']) ? $_POST['operation'] : array();


	 if(!empty($rolePost['name'])){

		 $name=$rolePost['name'];

		 $aux=Role::findbyname(trim($name));
		 if($aux!=null){
			   Common::response($service, "?controller=role&action=newr&alert-danger=REGIST_ROLE_EXISTS", array('id'=>0, 'code'=>201 , 'msg'=>'REGIST_ROLE_EXISTS'));die();
		 }

     }



   session_write_close();
   if(empty($rolePost)){
       Common::response($service, "?controller=role&action=index&alert-danger=ERROR_DATA_PARAMS", array('id'=>0, 'code'=>201 , 'msg'=>'ERROR_DATA_PARAMS'));die();
   }
   $rolePost['type']=1;
   $this->save($rolePost,$identity, $service, $operation);
}

public function edit() {
  Accesscontrol::beforeActionSession("management_role", 2,[1]);
 
    //_______________________________________________________________
         $identity = $_SESSION['entity']['identity'];
         $usertype= $_SESSION['entity']['userT'];//---START Verificação
         $typuser=$usertype[$_SESSION['defaultprofile']];
         if(isset($typuser)&&  $typuser!=1){
           if($typuser==2){$service=false;
             $localization= Common::get_client_ip();
             $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $line = array("Data"=>date('Y-m-d H:i:s'),"Localização"=>$localization, "Nº Utilizador"=>$identity, "Diretorio"=>$actual_link);
             Common::notifyError($line);
             Common::response($service, "?controller=student&action=dashboard&alert-danger=not_permit_not_have_permission", array('id'=>0, 'code'=>201 , 'msg'=>'not_permit_not_have_permission'));die();
     
         }else{ $service=false;
             $localization= Common::get_client_ip();
             $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $line = array("Data"=>date('Y-m-d H:i:s'),"Localização"=>$localization, "Nº Utilizador"=>$identity, "Diretorio"=>$actual_link);
             Common::notifyError($line);
             Common::response($service, "?controller=home&action=dashboard&alert-danger=not_permit_not_have_permission", array('id'=>0, 'code'=>201 , 'msg'=>'not_permit_not_have_permission'));die();
           }
         }//---END Verificação
    //_______________________________________________________________
   $id = isset($_GET['id']) ? $_GET['id'] : null;
   $service=false;

   $role = Role::findbyid($id);
   if($role==null){
       Common::response($service, "?controller=role&action=index&alert-danger=REG_NOT_FOUND", array('id'=>0, 'code'=>201 , 'msg'=>'REG_NOT_FOUND'));die();
   }

   $operation = Operation::findAllGroup1();
  $operationprofile = Operationrole::findbyrole($id,1);
   $operation2profile = Operationrole::findbyrole($id,2);



 
 $breadcrumbs = array(
    array("icon"=>"clip-cog-2", "msg"=>"MENU_MANAGEMENT"),

    array("active"=>1, "url"=>"?controller=role&action=index", "msg"=>"MENU_ROLE"),
    array("active"=>1, "msg"=>"MENU_EDIT", "text"=>$role->name)
);

   $view = 'views/role/edit.php';
   require_once('views/layout/_layout.php');

}


public function update() {
    Accesscontrol::beforeActionSession();
   $identity= $_SESSION['entity']['identity'];

   $service = false;
   if( isset($_POST['servicesession']) ){$service = true;}
   $rolePost = isset($_POST['role']) ? $_POST['role'] : array();
   $operation = isset($_POST['operation']) ? $_POST['operation'] : array();

   session_write_close();
   if(empty($rolePost) || empty($rolePost['idrole'])){
     
       Common::response($service, "?controller=role&action=index&alert-danger=ERROR_DATA_PARAMS", array('id'=>0, 'code'=>201 , 'msg'=>'ERROR_DATA_PARAMS'));die();
   }

   if(!empty($rolePost['name']) && !empty($rolePost['idrole'])){

    $name=$rolePost['name'];
    $idrole=$rolePost['idrole'];

    $aux=Role::findbynameid(trim($name),$idrole);


    if($aux!=null){
          Common::response($service, "?controller=role&action=newr&alert-danger=REGIST_ROLE_EXISTS", array('id'=>0, 'code'=>201 , 'msg'=>'REGIST_ROLE_EXISTS'));die();
    }

}

   $profile = Role::findbyid($rolePost['idrole']);


   $rolePost['type']=2;
   $this->save($rolePost, $identity, $service,$operation);
}


public function save($rolePost, $identity, $service, $operation) {

    $this->role->set("name", $rolePost['name']);
    $this->role->set("state",1);
    $status=false;
    if($rolePost['type']==1){

        $this->role->set('createdby', $identity);
        $this->role->set('updatedby', $identity);
        $st  = $this->role->save();
        $status = true;
        if($status =true){
            if(!empty($operation)){

                $this->operationrole->set('idrole', $st);
								$this->operationrole->set('createdby', $identity);
								$this->operationrole->set('updatedby', $identity);
								foreach ($operation as $key => $value) {

										$this->operationrole->set('type', $key);
									foreach($value as $idoperation) {
												$this->operationrole->set('idoperation', $idoperation);
												$k = $this->operationrole->save();
												if($k==false){
													$status=false;
												}
									}

								}
            }
        }


    }
    elseif($rolePost['type']==2){


        $this->role->set('updatedby', $identity);
        $this->role->set("idrole", $rolePost['idrole']);
        $status = $this->role->update();


        $this->operationrole->set('idrole', $rolePost['idrole']);
      $this->operationrole->delete();
     $status = true;
     if(!empty($operation)){
        $this->operationrole->set('createdby', $identity);
        $this->operationrole->set('updatedby', $identity);
       foreach ($operation as $key => $value) {
         foreach ($value as $k  => $v ) {
           $this->operationrole->set('idoperation', $v);
           $this->operationrole->set('type', $key);
           $k = $this->operationrole->save();
           if($k==false){
             $status=false;
           }
         }
       }

    }

    }

    if($status==false){
        $GLOBALS['statusdb'] = 0;
        Common::response($service, "?controller=role&action=index&alert-danger=ERROR_SAVE_DATA", array('id'=>0, 'code'=>201 , 'msg'=>'ERROR_SAVE_DATA'));die();
    }

    Common::response($service, "?controller=role&action=index&alert-success=SUCCESS_SAVE_DATA", array('code'=>200 , 'newUrl'=>"1"));die();
 }
}
