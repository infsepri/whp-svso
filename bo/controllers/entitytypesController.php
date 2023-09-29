<?php
/**
*
* @package    Entitytypes
* @package    Controller
* @copyright  2020 SEPRI
*
*/
require_once("conf/globalconf.php");
require_once("conf/configEmail.php");
require_once("conf/accesscontrol.php");
require_once("libraries/other/common.php");
require_once("models/Entity.php");
require_once("models/Entitytype.php");
class entitytypesController{

	public function __construct() {
		$this->entitytypes = new Entitytype();
        $this->entity = new Entity();
    }

	public function getidentifytypes(){
        Accesscontrol::beforeActionSession();
        $identity     = $_SESSION['entity']['identity'];
        $checkusersepri = Entity::findbysepri($identity);
       $search = isset($_GET['q']) ? "%".$_GET['q']."%" : "%";
       session_write_close();

       if (isset($checkusersepri) && !empty($checkusersepri)) {
       $entitytypes = Entitytype::search($search, 50);
       }else{
        $entitytypes = Entitytype::search1($search, 50);
       }

       if($entitytypes==null){
           print json_encode(array());die();
       }
       print json_encode($entitytypes);
   }




}
