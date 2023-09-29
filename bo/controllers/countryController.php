
<?php
/**
*
* @package    Country
* @package    Controller
* @copyright  2020 SEPRI
*
*/
require_once("conf/globalconf.php");
require_once("conf/configEmail.php");
require_once("conf/accesscontrol.php");
require_once("libraries/other/common.php");

require_once("models/Country.php");

class countryController{

    public function __construct() {
        $this->country = new Country();
    }
public function getcountry(){
    Accesscontrol::beforeActionSession();


    $search = isset($_GET['q']) ? "%".$_GET['q']."%" : "%";
    session_write_close();

    $country = Country::search($search, 50);


    if($country==null){
    print json_encode(array());die();
    }
    print json_encode($country);
    }
}
