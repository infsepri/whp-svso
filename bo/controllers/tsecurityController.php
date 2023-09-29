<?php
/**
*
* @package     tsecurity
* @package    Controller
* @copyright  2020 SEPRI
*
*/
require_once("conf/globalconf.php");
require_once("conf/configEmail.php");
require_once("conf/accesscontrol.php");
require_once("libraries/other/common.php");
require_once("models/Admin.php");
require_once("models/Entity.php");
require_once("models/Country.php");
require_once("models/District.php");
require_once("models/Company.php");
require_once("models/Adminrole.php");
require_once("models/Role.php");
require_once("models/Entitytype.php");


require_once("models/Tsecurity.php");
require_once("models/Employee.php");
require_once("models/Physiotherapist.php");


class tsecurityController{

	public function __construct() {

		$this->admin = new Admin();
		$this->tsecurity = new Tsecurity();
		$this->entity = new Entity();
		$this->entitytype = new Entitytype();

		$this->state = array(0 => "disable", 1 => "enable");
		$this->genre = array(0 => "M", 1 => "F");
		$this->allowance  = array(0 => "no", 1 => "yes");
		$this->typeuser  = array(1 => "colab", 2 => "director");
		$this->maritalstatus  = array(1 => "single", 2 => "married", 3 => "partnership", 4 => "divorced", 5 => "widowed", 6 => "late");
		$this->dayweek = array(1 => "sunday", 2 => "monday", 3 => "tuesday", 4 => "wednesday", 5 => "thursday", 6 => "friday", 7 => "saturday");

		$this->country       = new Country();
		$this->district      = new District();
		$this->company      = new Company();
		$this->adminrole      = new Adminrole();
		$this->role      = new Role();

		$this->tsecurity      = new Tsecurity();
		$this->employee      = new Employee();
		$this->physiotherapist      = new Physiotherapist();

	}




	public function index()
    {
        Accesscontrol::beforeActionSession();
        //_______________________________________________________________
		$identity     = $_SESSION['entity']['identity'];

		$usertype     = $_SESSION['entity']['userT'];
		$idcompany    = $_SESSION['entity']['idcompany'];
        //_______________________________________________________________ 



        $breadcrumbs = array(
            array("icon" => "clip-home", "msg" => "MENU_HOME_TSECURITY")
        );



        $view = 'views/tsecurity/index.php';
        require_once("views/layout/_layout.php");
    }


	public function getall()
	{
		Accesscontrol::beforeActionSession();

		//_______________________________________________________________
		$identity     = $_SESSION['entity']['identity'];

		$usertype     = $_SESSION['entity']['userT'];
		$idcompany    = $_SESSION['entity']['idcompany'];
		//_______________________________________________________________
		$super = 0;
		if (isset($identity) && !empty($identity) && $identity == 2) {
			$super = 1;
		}

		session_write_close();

		$search = "%";
		$page = 1;
		$perpage = $_POST['numberelements'];
		if (isset($_POST['page']) && isset($_POST['order']) && isset($_POST['keyorder'])  && isset($_POST['search'])) {
			$page = $_POST['page'];
			$search =  "%" . $_POST['search'] . "%";
			$order = $_POST['order'];
			$keyorder = $_POST['keyorder'];
		}
		$order = (empty($order)) ? "asc" : $order;
		$keyorder = (empty($keyorder)) ? "entity.identity" : $keyorder;

		$limitstart = intval($page) * intval($perpage) - intval($perpage);

		$arr = Tsecurity::findAlltable($limitstart, $perpage, $order, $keyorder, $search, $idcompany);
		$total = Tsecurity::countalltable($search, $idcompany);

		$totalReg = $total;
		if ($total == 0) {
			$total = 1;
		}
	
		$showing = empty($arr) ? 0 : sizeof($arr);
		$start = $limitstart;
		$total = ceil($total / $perpage);

		require_once('views/tsecurity/table.php');
	}



}
?>
