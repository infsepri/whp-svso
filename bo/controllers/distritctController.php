
<?php
/**
*
* @package    District
* @package    Controller
* @copyright  2020 SEPRI
*
*/
require_once("conf/globalconf.php");
require_once("conf/configEmail.php");
require_once("conf/accesscontrol.php");
require_once("libraries/other/common.php");

require_once("models/District.php");

class distritctController{

    public function __construct() {
        $this->district = new District();
    }
public function getdistritct(){
    Accesscontrol::beforeActionSession();


    $search = isset($_GET['q']) ? "%".$_GET['q']."%" : "%";
    session_write_close();

    $district = District::search($search, 50);

    if($district==null){
    print json_encode(array());die();
    }
    print json_encode($district);
    }
}
