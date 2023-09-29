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

class notifyController{

	public function __construct() {

        $this->entity = new Entity();
        $this->types  = array(1=>"workseat" ,2=>"Worload" ,3=>"invironment");
        $this->reslts  = array(0=>"unfavourable" ,1=>"favourable" ,2=>"overload" ,3=>"optimal",4=>"underload");

    }

	public function getall(){
        Accesscontrol::beforeActionSession();
        $identity     = $_SESSION['entity']['identity'];
        $id_hash= isset($_GET['id']) ? $_GET['id'] : null;
        //_______________________________________________________________
		$aux1 = "6Le12lkUAAAAALb37HIbWkbnjFIpt3yeD3GEbUij";
		$aux2 = "6Le12lkUAAAAALb37HIbWkbnjFIpt3yeD3GEbUij";
		$id = Common::encrypt_decrypt("decrypt", $id_hash, $aux1, $aux2);

           
        $arr = Common::getworkerbyuser($id);

               
        $breadcrumbs = array(
            array("icon" => "clip-stats", "msg" => "MENU_NOTIFICATION"),
            array("active" => 1, "url" => "?controller=admin&action=index", "msg" => "MENU_NOTIFICATION")

        );



        $view = 'views/notify/index.php';
        require_once("views/layout/_layout.php");

      
   }




}
