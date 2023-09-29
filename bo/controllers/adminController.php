<?php

/**
 *
 * @package    Admin
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


require_once("models/Doctor.php");
require_once("models/Tsecurity.php");
require_once("models/Nurse.php");
require_once("models/Employee.php");
require_once("models/Physiotherapist.php");


class adminController
{

	public function __construct()
	{
		$this->admin = new Admin();
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

		$this->doctor      = new Doctor();
		$this->nurse      = new Nurse();
		$this->tsecurity      = new Tsecurity();
		
		$this->employee      = new Employee();
		$this->physiotherapist      = new Physiotherapist();
	}




	public function index()
	{
		Accesscontrol::beforeActionSession();
		$identity     = $_SESSION['entity']['identity'];
		$checkusersepri = Entity::findbysepri($identity);

		$breadcrumbs = array(
			array("icon" => "clip-users", "msg" => "MENU_MANAGEMENT"),
			array("active" => 1, "url" => "?controller=admin&action=index", "msg" => "MENU_MANAGEMENT_ADMIN")

		);


		$doctor= Doctor::countdata();
		$nurse= Nurse::countdata();
		$physiotherapist= physiotherapist::countdata();
		$tsecurity= Tsecurity::countdata();
		


		$view = 'views/admin/index.php';
		require_once("views/layout/_layout.php");
	}

	public function getadmin()
	{
		Accesscontrol::beforeActionSession();
		//_______________________________________________________________
		$identity     = $_SESSION['entity']['identity'];

		$usertype     = $_SESSION['entity']['userT'];
		$idcompany    = $_SESSION['entity']['idcompany'];
		//_______________________________________________________________

		$search = isset($_GET['q']) ? "%" . $_GET['q'] . "%" : "%";
		session_write_close();
		$admin = Admin::search($search, 50, $idcompany);
		if ($admin == null) {
			print json_encode(array());
			die();
		}
		print json_encode($admin);
	}



	public function getadmin1()
	{
		Accesscontrol::beforeActionSession();

		$search = isset($_GET['q']) ? "%" . $_GET['q'] . "%" : "%";
		session_write_close();
		$admin = Admin::search1($search, 50);
		if ($admin == null) {
			print json_encode(array());
			die();
		}
		print json_encode($admin);
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

		$arr = Admin::findAlltable($limitstart, $perpage, $order, $keyorder, $search, $idcompany);
		$arrcountsend=array();
		if (isset($arr)&&!empty($arr)) {
			foreach ($arr as $key => $value) {
				$value->workcity = 8;
				$value->workload =4;
				$value->workenvironment = 11;
	
			
				/*$workdata = Common::getworkerbyuser($value->identity);
				array_push($arrcountsend,$workdata);	
		if (!empty($workdata->body)) {
			$countworkcity=0;
			$countworkload=0;
			$countworkenvironment=0;
					
					foreach ($workdata->body as $key1 => $value2) {
						
					}
				} */
				
				
			}
		}
	//	file_put_contents('POST_Nome.txt',var_export($arrcountsend ,TRUE));die;

		$total = Admin::countalltable($search, $idcompany);
		$totalReg = $total;
		if ($total == 0) {
			$total = 1;
		}

		$showing = sizeof($arr);
		$start = $limitstart;
		$total = ceil($total / $perpage);

		require_once('views/admin/table.php');
	}

	public function newad()
	{
		Accesscontrol::beforeActionSession();
		$identity     = $_SESSION['entity']['identity'];
		$checkusersepri = Entity::findbysepri($identity);

		$fieldblock = false;
		$fieldblockem = false;
		$role = Role::searchex();
		$role = ($role == null) ? array() : $role;

		$breadcrumbs = array(
			array("icon" => "clip-users", "msg" => "MENU_MANAGEMENT"),
			array("url" => "?controller=admin&action=index", "msg" => "MENU_MANAGEMENT_ADMIN"),
			array("active" => 1, "msg" => "MENU_NEWF", "url" => "?controller=admin&action=newad")
		);



		$view = "views/admin/create.php";
		require_once("views/layout/_layout.php");
	}

	public function create()
	{
		Accesscontrol::beforeActionSession();
		$company = new Company();
		$identity = $_SESSION['entity']['identity'];
		$idcompany = $_SESSION['entity']['idcompany'];
		$service = false;
		if (isset($_POST['service'])) {
			$service = true;
		}

		$company = Company::findOne($idcompany);
		$entity = Entity::findbyid($identity);
		if ($company == null && ($entity == null)) {
			Common::response($service, "?controller=admin&action=index&alert-danger=NOT_PERMIT", array('id' => 0, 'code' => 201, 'msg' => 'NOT_PERMIT'));
			die();
		}


		$adminPost = isset($_POST['admin']) ? $_POST['admin'] : array();
		$filePost = isset($_FILES['entitylogo']) ? $_FILES['entitylogo'] : array();



		session_write_close();
		if (empty($adminPost)) {
			return	Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_DATA_PARAMS", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_DATA_PARAMS'));
		}
		$validdateB = Common::afterTodayDate($adminPost['placeofbirth']);
		if ($validdateB != 0) {
			Common::response($service, "?controller=admin&action=newad&alert-danger=date_invalid", array('id' => 0, 'code' => 201, 'msg' => 'date_invalid'));
			die();
		}



		$adminPost['type'] = 1;
		$arrIds = $this->save($adminPost, $identity, $service, $idcompany, $filePost, $company, $entity);
		if (!empty($arrIds)) {
			$status = true;
		}
		$identitytype = $adminPost['identitytype'];
		if (isset($identitytype) && $identitytype == 1) {
			$idnewadmin = $arrIds[1];
			//$idnewentity = $arrIds[0];


			if (isset($adminPost['idrole']) && !empty($adminPost['idrole'])) {
				$this->adminrole->set("identity", $arrIds);
				$this->adminrole->set("createdby", $identity);
				$this->adminrole->set("updatedby", $identity);
				foreach ($adminPost['idrole'] as $key => $value) {


					$this->adminrole->set("idrole", $value);

					$status = $this->adminrole->save();

					if ($status == false) {
						$GLOBALS['statusdb'] = 0;
						break;
					}
				}
			}


			if ($GLOBALS['statusdb'] == 0) {
				$GLOBALS['statusdb'] = 0;
				return	Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_USER'));
			}
		}


		if ($adminPost['identitytype'] == 2) {
			return	Common::response($service, "?controller=admin&action=index&alert-success=SUCCESS_SAVE_DATA&tab=panel_overview1", array('code' => 200, 'newUrl' => "1"));
		} elseif ($adminPost['identitytype'] == 3) {
			return	Common::response($service, "?controller=admin&action=index&alert-success=SUCCESS_SAVE_DATA&tab=panel_overview2", array('code' => 200, 'newUrl' => "1"));
		} elseif ($adminPost['identitytype'] == 5) {
			return	Common::response($service, "?controller=admin&action=index&alert-success=SUCCESS_SAVE_DATA&tab=panel_overview3", array('code' => 200, 'newUrl' => "1"));
		} elseif ($adminPost['identitytype'] == 6) {
			return	Common::response($service, "?controller=admin&action=index&alert-success=SUCCESS_SAVE_DATA&tab=panel_overview4", array('code' => 200, 'newUrl' => "1"));
		} else {
			return	Common::response($service, "?controller=admin&action=index&alert-success=SUCCESS_SAVE_DATA&tab=panel_overview", array('code' => 200, 'newUrl' => "1"));
		}
	}

	public function info()
	{

		Accesscontrol::beforeActionSession();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = false;
		$fieldblock = true;
		$admin = admin::findbyadmin($id);
		//file_put_contents('POST6.txt', print_r($admin, TRUE));


		if ($admin == null) {
			return Common::response($service, "?controller=admin&action=index&alert-danger=REG_NOT_FOUND", array('id' => 0, 'code' => 201, 'msg' => 'REG_NOT_FOUND'));
		}

		$entitytype = Entitytype::findbyid($admin->identitytype);

		$country = Country::findbyid($admin->idcountry);
		$district = District::findbyid($admin->iddistrict);

		if ($admin == null) {
			return	Common::response($service, "?controller=admin&action=index&alert-danger=REG_NOT_FOUND", array('id' => 0, 'code' => 201, 'msg' => 'REG_NOT_FOUND'));
		}

		$adminrole = Adminrole::findbyadmin($id);

		$role = array();
		if ($adminrole != null) {
			foreach ($adminrole as $k => $value) {
				//$role[$value] = Role::findbyid($value);

				array_push($role, $value);
			}
		}




		$breadcrumbs = array(
			array("icon" => "clip-users", "msg" => "MENU_MANAGEMENT"),

			array("active" => 1, "url" => "?controller=admin&action=index", "msg" => "MENU_MANAGEMENT_ADMIN"),
			array("active" => 1, "msg" => "MENU_EDIT", "text" => $admin->name)
		);

		$view = 'views/admin/info.php';
		require_once('views/layout/_layout.php');
	}

	public function edit()
	{
		Accesscontrol::beforeActionSession();
		$usertype = $_SESSION['entity']['userT'];
		$identity = $_SESSION['entity']['identity'];
		$idcompany = $_SESSION['entity']['idcompany'];
	    $id_hash = isset($_GET['id']) ? $_GET['id'] : null;
		$aux = isset($_GET['a']) ? $_GET['a'] : null;
		$identitytype_hash = isset($_GET['t']) ? $_GET['t'] : null;
		//_______________________________________________________________
		$aux1 = "6Le12lkUAAAAALb37HIbWkbnjFIpt3yeD3GEbUij";
		$aux2 = "6Le12lkUAAAAALb37HIbWkbnjFIpt3yeD3GEbUij";
		$id = Common::encrypt_decrypt("decrypt", $id_hash, $aux1, $aux2);
		$identitytype = Common::encrypt_decrypt("decrypt", $identitytype_hash, $aux1, $aux2);
		//_______________________________________________________________
	
		if ($usertype[$_SESSION['defaultprofile']] != 1) {
		
				$service = false;
				if ($identitytype == 2) {
					return Common::response($service, "?controller=doctor&action=index&alert-danger=not_permit_not_have_permission", array('id' => 0, 'code' => 201, 'msg' => 'not_permit_not_have_permission'));die();
				} elseif ($identitytype == 3) {
					return Common::response($service, "?controller=physiotherapist&action=index&alert-danger=not_permit_not_have_permission", array('id' => 0, 'code' => 201, 'msg' => 'not_permit_not_have_permission'));die();
				} elseif ($identitytype == 5) {
					return Common::response($service, "?controller=nurse&action=index&alert-danger=not_permit_not_have_permission", array('id' => 0, 'code' => 201, 'msg' => 'not_permit_not_have_permission'));die();
				} elseif ($identitytype == 6) {
					return Common::response($service, "?controller=tsecurity&action=index&alert-danger=not_permit_not_have_permission", array('id' => 0, 'code' => 201, 'msg' => 'not_permit_not_have_permission'));die();
				}else{
					return Common::response($service, "?controller=employee&action=index&alert-danger=not_permit_not_have_permission", array('id' => 0, 'code' => 201, 'msg' => 'not_permit_not_have_permission'));die();
				}
			
		}


		if ($aux == 1) {
			$fieldblock = false;
			$fieldblockem = true;
		} else {
			$fieldblock = true;
			$fieldblockem = true;
		}
		$service = false;

	
		if (isset($identitytype)) {
			if ($identitytype == 1) {
				$admin = admin::findbyadmin($id);
			} elseif ($identitytype == 2) {
				$admin = Doctor::findbydoctor($id);
			} elseif ($identitytype == 3) {
				$admin = Physiotherapist::findbyphysiotherapist($id);

			} elseif ($identitytype == 5) {
				$admin = Nurse::findbynurse($id);
			} elseif ($identitytype == 6) {
				$admin = Tsecurity::findbytsecurity($id);
				
			} else {
				$admin = Employee::findbyemployee($id);
			}
		}


		if ($admin == null) {
			return Common::response($service, "?controller=admin&action=index&alert-danger=REG_NOT_FOUND", array('id' => 0, 'code' => 201, 'msg' => 'REG_NOT_FOUND'));
		}

		$country = Country::findbyid($admin->idcountry);

		$entitytype = Entitytype::findbyid($admin->identitytype);

		$district = District::findbyid($admin->iddistrict);
		$entitytype = Entitytype::findbyid($admin->identitytype);
		$adminrole = Adminrole::findbyadmin($id);
		$roleuser = Adminrole::findbyidentitylist($id);

		$role = Role::searchex();
		$role = ($role == null) ? array() : $role;
		/*$role = array();
		if($adminrole!=null){
			 foreach($adminrole as $k=>$value) {
					 //$role[$value] = Role::findbyid($value);

					 array_push($role,$value );
			 }
		 }*/

		if ($admin == null) {
			return	Common::response($service, "?controller=admin&action=index&alert-danger=REG_NOT_FOUND", array('id' => 0, 'code' => 201, 'msg' => 'REG_NOT_FOUND'));
		}




		$breadcrumbs = array(
			array("type" => 1, "msg" => "MENU_MANAGEMENT"),
			array("type" => 2, "msg" => "MENU_MANAGEMENT_ADMIN",  "url" => "?controller=admin&action=index"),
			array("type" => 0, "url" => "", "text" => "&nbsp" . $admin->name, "bold" => 1, "color" => 1)
		);

		$view = 'views/admin/edit.php';
		require_once('views/layout/_layout.php');
	}

	public function update()
	{
		Accesscontrol::beforeActionSession();

		$identity = $_SESSION['entity']['identity'];
		$idcompany = $_SESSION['entity']['idcompany'];
		$checkusersepri = Entity::findbysepri($identity);
		$service = false;
		if (isset($_POST['servicesession'])) {
			$service = true;
		}
		$adminPost = isset($_POST['admin']) ? $_POST['admin'] : array();
		$filePost = isset($_FILES['entitylogo']) ? $_FILES['entitylogo'] : array();



		session_write_close();
		if (empty($adminPost) || empty($adminPost['identity'])) {
			return Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_DATA_PARAMS", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_DATA_PARAMS'));
		}

		$company = Company::findOne($idcompany);
		$entity = Entity::findbyid($identity);
		$entityupd = Entity::findbyid($adminPost['identity']);
		$adminPost['type'] = 2;
		$verify_email = Entity::findbyemail($entityupd->email);

		if (!empty($entityupd->email)) {

			if ($verify_email != null && $verify_email->identity != $adminPost['identity']) {
				return	Common::response($service, "?controller=admin&action=index&alert-danger=msgusernameexist", array('id' => 0, 'code' => 201, 'msg' => 'msgusernameexist'));
			}
		}

		if (isset($entityupd) && !empty($entityupd)) {




			if ($adminPost['identitytype'] == 1) {

				//new Admin();
				if ($entityupd->identitytype != $adminPost['identitytype']) {
					$this->employee->set('identity', $adminPost['identity']);
					$this->employee->delete();
					$adminPost['type'] = 1;
					$adminPost['email'] = $entityupd->email;
				}
			}
			if ($adminPost['identitytype'] == 4) {
				//new Employee();
				if ($entityupd->identitytype != $adminPost['identitytype']) {
					$adminPost['type'] = 1;
					$this->admin->set('identity', $adminPost['identity']);
					$this->admin->delete();
					$adminPost['email'] = $entityupd->email;
				}
			}
			if ($adminPost['identitytype'] == 2) {
				$adminPost['email'] = $entityupd->email;
			}
			if ($adminPost['identitytype'] == 3) {
				$adminPost['email'] = $entityupd->email;
			}
			if ($adminPost['identitytype'] == 5) {
				$adminPost['email'] = $entityupd->email;
			}

			if ($adminPost['identitytype'] == 6) {
				$adminPost['email'] = $entityupd->email;
			}
		}




		$identitytype = $adminPost['identitytype'];
		$st = $this->save($adminPost, $identity, $service, $idcompany, $filePost, $company, $entity);



		if (isset($identitytype) && $identitytype == 1) {

			//$this->save($teacherPost,$teacherparent, $identity, $service);

			$this->adminrole->set('identity', $adminPost['identity']);

			$this->adminrole->delete();
			if (isset($adminPost['idrole']) && !empty($adminPost['idrole'])) {
				$this->adminrole->set("identity", $st);
				$this->adminrole->set("createdby", $identity);
				$this->adminrole->set("updatedby", $identity);
				foreach ($adminPost['idrole'] as $key => $value) {


					$this->adminrole->set("idrole", $value);

					$status = $this->adminrole->save();

					if ($status == false) {
						$GLOBALS['statusdb'] = 0;
						break;
					}
				}
			}


			if ($GLOBALS['statusdb'] == 0) {
				$GLOBALS['statusdb'] = 0;
				return	Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_USER'));
			}
		}



		if (isset($checkusersepri) && !empty($checkusersepri)) {
			if ($adminPost['identitytype'] == 1) {
				return	Common::response($service, "?controller=admin&action=index&tab=panel_overview&alert-success=SUCCESS_SAVE_DATA", array('code' => 200, 'newUrl' => "1"));
			} elseif ($adminPost['identitytype'] == 2) {
				return	Common::response($service, "?controller=admin&action=index&tab=panel_overview1&alert-success=SUCCESS_SAVE_DATA", array('code' => 200, 'newUrl' => "1"));
			} elseif ($adminPost['identitytype'] == 3) {
				return	Common::response($service, "?controller=admin&action=index&tab=panel_overview2&alert-success=SUCCESS_SAVE_DATA", array('code' => 200, 'newUrl' => "1"));
			} elseif ($adminPost['identitytype'] == 5) {
				return	Common::response($service, "?controller=admin&action=index&tab=panel_overview3&alert-success=SUCCESS_SAVE_DATA", array('code' => 200, 'newUrl' => "1"));
			} elseif ($adminPost['identitytype'] == 6) {
				return	Common::response($service, "?controller=admin&action=index&tab=panel_overview4&alert-success=SUCCESS_SAVE_DATA", array('code' => 200, 'newUrl' => "1"));
			} else {
				return	Common::response($service, "?controller=admin&action=index&tab=panel_overview&alert-success=SUCCESS_SAVE_DATA", array('code' => 200, 'newUrl' => "1"));
			}
		} else {
			return	Common::response($service, "?controller=admin&action=index&alert-success=SUCCESS_SAVE_DATA", array('code' => 200, 'newUrl' => "1"));
		}
	}


	public function removephoto()
	{
		Accesscontrol::beforeActionSession();
		$admin = Admin::findbyid($_GET['id']);
		$identity = $_SESSION['entity']['identity'];

		if ($admin != null && $admin->photo != null) {
			$nameoldlogo =   Common::target_dir . $admin->photo;
			if (file_exists($nameoldlogo)) {
				unlink($nameoldlogo);
			}
			$explode = explode("/", $admin->photo);
			if (isset($explode[1])) {
				end($explode);
				$key = key($explode);
				$explode[$key] = "80x80" . $explode[$key];
			}
			$explode = implode("/", $explode);
			$nameoldlogo =   Common::target_dir . $explode;
			if (file_exists($nameoldlogo)) {
				unlink($nameoldlogo);
			}
		}

		$this->admin->set('photo', null);
		$this->admin->set('identity', $_GET['id']);
		$this->admin->set('updatedby', $identity);
		$this->admin->updatephoto();

		print json_encode(array());
	}

	public function changestate()
	{
		Accesscontrol::beforeActionSession();
		$identity = $_SESSION['entity']['identity'];
		$checkusersepri = Entity::findbysepri($identity);
		$id = $_GET['id'];
		$tabd = isset($_GET['tab']) && $_GET['tab'] == 1 ? 1 : null;
		$tabf = isset($_GET['tab']) && $_GET['tab'] == 2 ? 2 : null;



		$service = false;
		$admin = Admin::findbyentity($id);


		if ($admin == null) {
			if (isset($checkusersepri) && !empty($checkusersepri)) {

				if ($tabd == 1) {
					return Common::response($service, "?controller=admin&action=index&tab=panel_overview1&alert-danger=REG_NOT_FOUND", array('id' => 0, 'code' => 201, 'msg' => 'REG_NOT_FOUND'));
				}

				if ($tabf == 2) {
					return Common::response($service, "?controller=admin&action=index&tab=panel_overview2&alert-danger=REG_NOT_FOUND", array('id' => 0, 'code' => 201, 'msg' => 'REG_NOT_FOUND'));
				}
			} else {
				return Common::response($service, "?controller=admin&action=index&alert-danger=REG_NOT_FOUND", array('id' => 0, 'code' => 201, 'msg' => 'REG_NOT_FOUND'));
			}
		}


		$this->entity->set("state", 0);
		if ($admin->state == 0) {
			$this->entity->set("state", 1);
		}

		$this->entity->set('identity', $admin->identity);
		$state = $this->entity->updatestate();
		if ($state) {

			if (isset($checkusersepri) && !empty($checkusersepri)) {

				if ($tabd == 1) {
					return Common::response($service, "?controller=admin&action=index&tab=panel_overview1&alert-success=STATE_CHANGE_SUCCESS", array('id' => 0, 'code' => 200, 'msg' => 'STATE_CHANGE_SUCCESS'));
				} elseif ($tabf == 2) {
					return Common::response($service, "?controller=admin&action=index&tab=panel_overview2&alert-success=STATE_CHANGE_SUCCESS", array('id' => 0, 'code' => 200, 'msg' => 'STATE_CHANGE_SUCCESS'));
				} else {
					return Common::response($service, "?controller=admin&action=index&alert-success=STATE_CHANGE_SUCCESS", array('id' => 0, 'code' => 200, 'msg' => 'STATE_CHANGE_SUCCESS'));
				}
			} else {
				return Common::response($service, "?controller=admin&action=index&alert-success=STATE_CHANGE_SUCCESS", array('id' => 0, 'code' => 200, 'msg' => 'STATE_CHANGE_SUCCESS'));
			}
		}

		return Common::response($service, "?controller=admin&action=index&alert-danger=STATE_CHANGE_ERROR", array('id' => 0, 'code' => 201, 'msg' => 'STATE_CHANGE_ERROR'));
	}



	public function save($adminPost, $identity, $service, $idcompany, $file, $company, $entity)
	{


		$verify_email = Entity::findbyemail($adminPost['email']);



		if (isset($adminPost['identitytype'])) {
			$identitytype = $adminPost['identitytype'];
			if ($identitytype == 1) { # code...Admin---------------------------------------------------
				$admin_logo = null;
				$oldphoto = null;
				if ($adminPost['type'] == 2) {
					$admin = Admin::findbyid($adminPost['identity']);
					if ($admin != null) {
						$this->admin->set("photo", $admin->photo);
						$oldphoto = $admin->photo;
					}
				}

				if ($adminPost['identity'] != null && $adminPost['type'] != 2) {

					$admin = Admin::findbyid($adminPost['identity']);
					if ($admin != null) {
						$this->admin->set("photo", $admin->photo);
						$oldphoto = $admin->photo;
					}
				}


				if ($file != Null && isset($file['name']['adminlogo'])) {
					$admin_logo = array("name" => $file['name']['adminlogo'], "type" => $file['type']['adminlogo'], "tmp_name" => $file['tmp_name']['adminlogo'], "error" => $file['error']['adminlogo'], "size" => $file['size']['adminlogo']);
					$admin_logo = Common::savefile($admin_logo, $service, $idcompany, $oldphoto, "entityphoto");
				}


				$t = microtime(true);
				$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
				$d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
				$api = md5($adminPost['name'] . $d->format("Y-m-d H:i:s.u"));
				$hash = md5($adminPost['name'] . $api);


				$this->admin->set("api", $api);
				$this->admin->set("hash", $hash);



				$this->admin->set("statelogin", 1);



				$this->admin->set("name", isset($adminPost['name']) ? $adminPost['name'] : "");
				$this->admin->set("nif", isset($adminPost['nif']) ? $adminPost['nif'] : "");

				$this->admin->set("identitycard", (isset($adminPost['identitycard']) && !empty($adminPost['identitycard'])) ? $adminPost['identitycard'] : null);

				$this->admin->set("numberhelth", isset($adminPost['numberhelth']) ? $adminPost['numberhelth'] : null);
				$this->admin->set("socialsecurity", isset($adminPost['socialsecurity']) ? $adminPost['socialsecurity'] : "");

				if ($admin_logo != null) {
					$this->admin->set("photo", $admin_logo);
				}
				$this->admin->set("genre", isset($adminPost['genre']) ? $adminPost['genre'] : 2);
				$this->admin->set("address", isset($adminPost['address']) ? $adminPost['address'] : "");
				$this->admin->set("postalcode", isset($adminPost['postalcode']) ? $adminPost['postalcode'] : "");
				$this->admin->set("locality", isset($adminPost['locality']) ? $adminPost['locality'] : "");
				$this->admin->set("idcountry", isset($adminPost['idcountry']) ? $adminPost['idcountry'] : "");
				$this->admin->set("iddistrict", (isset($adminPost['iddistrict']) && !empty($adminPost['iddistrict'])) ? $adminPost['iddistrict'] : null);
				$this->admin->set("placeofbirth", isset($adminPost['placeofbirth']) ? $adminPost['placeofbirth'] : "");
				$this->admin->set("idcompany", $idcompany);
				$this->admin->set("telephone", isset($adminPost['telephone']) ? $adminPost['telephone'] : "");
				$this->admin->set("mobilephone", isset($adminPost['mobilephone']) ? $adminPost['mobilephone'] : "");
				$this->admin->set("obs", isset($adminPost['obs'])  ? $adminPost['obs'] : "");
				$this->admin->set("type", (isset($adminPost['typ_']) && !empty($adminPost['typ_'])) ? $adminPost['typ_'] : 1);
				$this->admin->set("identitytype", $identitytype);
				if ($adminPost['type'] == 1) {



					if (!empty($adminPost['email'])) {
						if ($verify_email == null) {

							$adminPost['identity'] = $verify_email->identity;
							$this->admin->set('identity', $verify_email->identity);
							$this->admin->set('state', $verify_email->state);
							$this->admin->set("createdby", $identity);
							$this->admin->set("updatedby", $identity);
							$t = $this->admin->save();
							if ($t == false) {
								$GLOBALS['statusdb'] = 0;
								Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_USER&-1", array('id' => 0, 'code' => 201, "subcode" => -1, 'msg' => 'ERROR_SAVE_USER'));
								die();
							}
							$t = $this->admin->updatestates();
							if ($t == false) {
								$GLOBALS['statusdb'] = 0;
								Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_USER&-10", array('id' => 0, 'code' => 201, "subcode" => -10, 'msg' => 'ERROR_SAVE_USER'));
								die();
							}
						} else {
							$adminchec = Admin::find($adminPost['identity']);
							if (!empty($adminchec->idadmin)) {
								$adminPost['type'] = 2;
								$t = $this->admin->updatestates();
							} else {
								$adminPost['type'] = 2;
								$adminPost['photo'] = $verify_email->photo;

								$adminPost['identity'] = $verify_email->identity;
								$this->admin->set('identity', $verify_email->identity);
								$this->admin->set('state', $verify_email->state);
								$this->admin->set("createdby", $identity);
								$this->admin->set("updatedby", $identity);
								$t = $this->admin->save();
							}
						}
					}
				}
				if ($adminPost['type'] == 1) {
					$this->admin->set("email", isset($adminPost['email']) ? $adminPost['email'] : "");
					$this->admin->set('state', 1);
					$this->admin->set("createdby", $identity);
					$this->admin->set("updatedby", $identity);
					$status = $this->admin->save();
					$idst = $status;
					if ($idst) {
						if ($t == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&0", array('id' => 0, 'code' => 201, "subcode" => 0, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}
						if ($verify_email == null) {
							$status = Common::insertuser($this->admin, $company->get('nif'), $entity->email);
						}
						if ($status[0] == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&1", array('id' => 0, 'code' => 201, "subcode" => 1, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}

						$mail = configEmail("Admin");
						require_once("conf/templateEmail/accountcreate.php");
						$email = $adminPost['email'];
						$entity = array($company->get('name'), $company->get('email'), $company->get('address'), $company->get('postalcode'), $company->get('locality'), $company->get('logo'), $company->get('mobilephone'), $company->get('website'), "nameentity" => $adminPost['name']);
						$token = $status[1];
						$status = true;

						if ($token != "0") {
							if (mailer($email, $mail, $token, $entity, 1)) {
							} else {
								Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&2", array('id' => 0, 'code' => 201, "subcode" => 2,   'msg' => 'ERROR_SAVE_USER'));
								die();
							}
						}
					}
				} elseif ($adminPost['type'] == 2) {
					$this->admin->set("identity", $adminPost['identity']);
					$this->admin->set("updatedby", $identity);
					$status = $this->admin->update();
					if ($status == false) {
						$GLOBALS['statusdb'] = 0;
						Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_DATA&1", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_DATA', 'subcode' => 1));
						die();
					}

					$idst = $adminPost['identity'];
				}
			} elseif ($identitytype == 2) { # code...Medico --------------------------------------------
				$doctor_logo = null;
				$oldphoto = null;
				if ($adminPost['type'] == 2) {
					$doctor = Doctor::findbyid($adminPost['identity']);
					if ($doctor != null) {
						$this->doctor->set("photo", $doctor->photo);
						$oldphoto = $doctor->photo;
					}
				}

				if ($file != Null && isset($file['name']['adminlogo'])) {
					$doctor_logo = array("name" => $file['name']['adminlogo'], "type" => $file['type']['adminlogo'], "tmp_name" => $file['tmp_name']['adminlogo'], "error" => $file['error']['adminlogo'], "size" => $file['size']['adminlogo']);
					$doctor_logo = Common::savefile($doctor_logo, $service, $idcompany, $oldphoto, "entityphoto");
				}


				$t = microtime(true);
				$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
				$d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
				$api = md5($adminPost['name'] . $d->format("Y-m-d H:i:s.u"));
				$hash = md5($adminPost['name'] . $api);


				$this->doctor->set("api", $api);
				$this->doctor->set("hash", $hash);



				$this->doctor->set("statelogin", 1);



				$this->doctor->set("name", isset($adminPost['name']) ? $adminPost['name'] : "");
				$this->doctor->set("nif", isset($adminPost['nif']) ? $adminPost['nif'] : "");

				$this->doctor->set("identitycard", (isset($adminPost['identitycard']) && !empty($adminPost['identitycard'])) ? $adminPost['identitycard'] : null);

				$this->doctor->set("numberhelth", isset($adminPost['numberhelth']) ? $adminPost['numberhelth'] : null);

				if ($doctor_logo != null) {
					$this->doctor->set("photo", $doctor_logo);
				}
				$this->doctor->set("genre", isset($adminPost['genre']) ? $adminPost['genre'] : 2);
				$this->doctor->set("address", isset($adminPost['address']) ? $adminPost['address'] : "");
				$this->doctor->set("postalcode", isset($adminPost['postalcode']) ? $adminPost['postalcode'] : "");
				$this->doctor->set("locality", isset($adminPost['locality']) ? $adminPost['locality'] : "");
				$this->doctor->set("idcountry", isset($adminPost['idcountry']) ? $adminPost['idcountry'] : "");
				$this->doctor->set("iddistrict", (isset($adminPost['iddistrict']) && !empty($adminPost['iddistrict'])) ? $adminPost['iddistrict'] : null);
				$this->doctor->set("placeofbirth", isset($adminPost['placeofbirth']) ? $adminPost['placeofbirth'] : "");
				$this->doctor->set("idcompany", $idcompany);
				$this->doctor->set("telephone", isset($adminPost['telephone']) ? $adminPost['telephone'] : "");
				$this->doctor->set("mobilephone", isset($adminPost['mobilephone']) ? $adminPost['mobilephone'] : "");
				$this->doctor->set("obs", isset($adminPost['obs'])  ? $adminPost['obs'] : "");
				$this->doctor->set("type", (isset($adminPost['typ_']) && !empty($adminPost['typ_'])) ? $adminPost['typ_'] : 1);
				$this->doctor->set("identitytype", $identitytype);
				if ($adminPost['type'] == 1) {
					if (!empty($adminPost['email'])) {


						if ($verify_email == null) {
							$adminPost['type'] = 1;
						} else {
							$doctorchec = Doctor::find($adminPost['identity']);

							if (!empty($doctorchec->iddoctor)) {
								$adminPost['type'] = 2;
								$t = $this->doctor->updatestates();
							} else {
								$adminPost['type'] = 2;
								$adminPost['identity'] = $verify_email->identity;
								$this->doctor->set('identity', $verify_email->identity);
								$this->doctor->set('state', $verify_email->state);
								$this->doctor->set("createdby", $identity);
								$this->doctor->set("updatedby", $identity);
								$t = $this->doctor->save();
							}
						}
					}
				}
				
				
				if ($adminPost['type'] == 1) {
					$this->doctor->set("email", isset($adminPost['email']) ? $adminPost['email'] : "");
					$this->doctor->set('state', 1);
					$this->doctor->set("createdby", $identity);
					$this->doctor->set("updatedby", $identity);
					$status = $this->doctor->save();
					$idst = $status;
					if ($idst) {
						if ($t == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&0", array('id' => 0, 'code' => 201, "subcode" => 0, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}
						if ($verify_email == null) {
							$status = Common::insertuser($this->doctor, $company->get('nif'), $entity->email);
						}


						if ($status[0] == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&1", array('id' => 0, 'code' => 201, "subcode" => 1, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}

						$mail = configEmail("Admin");
						require_once("conf/templateEmail/accountcreate.php");
						$email = $adminPost['email'];
						$entity = array($company->get('name'), $company->get('email'), $company->get('address'), $company->get('postalcode'), $company->get('locality'), $company->get('logo'), $company->get('mobilephone'), $company->get('website'), "nameentity" => $adminPost['name']);
						$token = $status[1];
						$status = true;

						if ($token != "0") {
							if (mailer($email, $mail, $token, $entity, 1)) {
							} else {
								Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&2", array('id' => 0, 'code' => 201, "subcode" => 2,   'msg' => 'ERROR_SAVE_USER'));
								die();
							}
						}
					}
				} elseif ($adminPost['type'] == 2) {


					$this->doctor->set("identity", $adminPost['identity']);
					$this->doctor->set("updatedby", $identity);
					$status = $this->doctor->update();
					if ($status == false) {
						$GLOBALS['statusdb'] = 0;
						Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_DATA&1", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_DATA', 'subcode' => 1));
						die();
					}

					$idst = $adminPost['identity'];
				}

			



			} elseif ($identitytype == 3) { # code...Fisio  --------------------------------------------
				$physiotherapist_logo = null;
				$oldphoto = null;
				if ($adminPost['type'] == 2) {
					$physiotherapist = Physiotherapist::findbyid($adminPost['identity']);
					if ($physiotherapist != null) {
						$this->physiotherapist->set("photo", $physiotherapist->photo);
						$oldphoto = $physiotherapist->photo;
					}
				}

				if ($file != Null && isset($file['name']['adminlogo'])) {
					$physiotherapist_logo = array("name" => $file['name']['adminlogo'], "type" => $file['type']['adminlogo'], "tmp_name" => $file['tmp_name']['adminlogo'], "error" => $file['error']['adminlogo'], "size" => $file['size']['adminlogo']);
					$physiotherapist_logo = Common::savefile($physiotherapist_logo, $service, $idcompany, $oldphoto, "entityphoto");
				}


				$t = microtime(true);
				$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
				$d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
				$api = md5($adminPost['name'] . $d->format("Y-m-d H:i:s.u"));
				$hash = md5($adminPost['name'] . $api);


				$this->physiotherapist->set("api", $api);
				$this->physiotherapist->set("hash", $hash);



				$this->physiotherapist->set("statelogin", 1);



				$this->physiotherapist->set("name", isset($adminPost['name']) ? $adminPost['name'] : "");
				$this->physiotherapist->set("nif", isset($adminPost['nif']) ? $adminPost['nif'] : "");

				$this->physiotherapist->set("identitycard", (isset($adminPost['identitycard']) && !empty($adminPost['identitycard'])) ? $adminPost['identitycard'] : null);

				$this->physiotherapist->set("numberhelth", isset($adminPost['numberhelth']) ? $adminPost['numberhelth'] : null);
				$this->physiotherapist->set("socialsecurity", isset($adminPost['socialsecurity']) ? $adminPost['socialsecurity'] : "");

				if ($physiotherapist_logo != null) {
					$this->physiotherapist->set("photo", $physiotherapist_logo);
				}
				$this->physiotherapist->set("genre", isset($adminPost['genre']) ? $adminPost['genre'] : 2);
				$this->physiotherapist->set("address", isset($adminPost['address']) ? $adminPost['address'] : "");
				$this->physiotherapist->set("postalcode", isset($adminPost['postalcode']) ? $adminPost['postalcode'] : "");
				$this->physiotherapist->set("locality", isset($adminPost['locality']) ? $adminPost['locality'] : "");
				$this->physiotherapist->set("idcountry", isset($adminPost['idcountry']) ? $adminPost['idcountry'] : "");
				$this->physiotherapist->set("iddistrict", (isset($adminPost['iddistrict']) && !empty($adminPost['iddistrict'])) ? $adminPost['iddistrict'] : null);
				$this->physiotherapist->set("placeofbirth", isset($adminPost['placeofbirth']) ? $adminPost['placeofbirth'] : "");
				$this->physiotherapist->set("idcompany", $idcompany);
				$this->physiotherapist->set("telephone", isset($adminPost['telephone']) ? $adminPost['telephone'] : "");
				$this->physiotherapist->set("mobilephone", isset($adminPost['mobilephone']) ? $adminPost['mobilephone'] : "");
				$this->physiotherapist->set("obs", isset($adminPost['obs'])  ? $adminPost['obs'] : "");
				$this->physiotherapist->set("type", (isset($adminPost['typ_']) && !empty($adminPost['typ_'])) ? $adminPost['typ_'] : 1);
				$this->physiotherapist->set("identitytype", $identitytype);



				if ($adminPost['type'] == 1) {
					if (!empty($adminPost['email'])) {


						if ($verify_email == null) {
							$adminPost['type'] = 1;
						} else {
							$physiotherapistchec = Physiotherapist::find($adminPost['identity']);

							if (!empty($physiotherapistchec->idphysiotherapist)) {
								$adminPost['type'] = 2;
								$t = $this->physiotherapist->updatestates();
							} else {
								$adminPost['type'] = 2;
								$adminPost['identity'] = $verify_email->identity;
								$this->physiotherapist->set('identity', $verify_email->identity);
								$this->physiotherapist->set('state', $verify_email->state);
								$this->physiotherapist->set("createdby", $identity);
								$this->physiotherapist->set("updatedby", $identity);
								$t = $this->physiotherapist->save();
							}
						}
					}
				}



				if ($adminPost['type'] == 1) {
					$this->physiotherapist->set("email", isset($adminPost['email']) ? $adminPost['email'] : "");
					$this->physiotherapist->set('state', 1);
					$this->physiotherapist->set("createdby", $identity);
					$this->physiotherapist->set("updatedby", $identity);
					$status = $this->physiotherapist->save();
					$idst = $status;
					if ($idst) {
						if ($t == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&0", array('id' => 0, 'code' => 201, "subcode" => 0, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}

						if ($verify_email == null) {
							$status = Common::insertuser($this->physiotherapist, $company->get('nif'), $entity->email);
						}

						if ($status[0] == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&1", array('id' => 0, 'code' => 201, "subcode" => 1, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}

						$mail = configEmail("Admin");
						require_once("conf/templateEmail/accountcreate.php");
						$email = $adminPost['email'];
						$entity = array($company->get('name'), $company->get('email'), $company->get('address'), $company->get('postalcode'), $company->get('locality'), $company->get('logo'), $company->get('mobilephone'), $company->get('website'), "nameentity" => $adminPost['name']);
						$token = $status[1];
						$status = true;

						if ($token != "0") {
							if (mailer($email, $mail, $token, $entity, 1)) {
							} else {
								Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&2", array('id' => 0, 'code' => 201, "subcode" => 2,   'msg' => 'ERROR_SAVE_USER'));
								die();
							}
						}
					}
				} elseif ($adminPost['type'] == 2) {
					$this->physiotherapist->set("identity", $adminPost['identity']);
					$this->physiotherapist->set("updatedby", $identity);
					$status = $this->physiotherapist->update();
					if ($status == false) {
						$GLOBALS['statusdb'] = 0;
						Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_DATA&1", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_DATA', 'subcode' => 1));
						die();
					}

					$idst = $adminPost['identity'];
				}

			} elseif ($identitytype == 5) { # code...Enf  --------------------------------------------









				$nurse_logo = null;
				$oldphoto = null;
				if ($adminPost['type'] == 2) {
					$nurse = Nurse::findbyid($adminPost['identity']);
					if ($nurse != null) {
						$this->nurse->set("photo", $nurse->photo);
						$oldphoto = $nurse->photo;
					}
				}

				if ($file != Null && isset($file['name']['adminlogo'])) {
					$nurse_logo = array("name" => $file['name']['adminlogo'], "type" => $file['type']['adminlogo'], "tmp_name" => $file['tmp_name']['adminlogo'], "error" => $file['error']['adminlogo'], "size" => $file['size']['adminlogo']);
					$nurse_logo = Common::savefile($nurse_logo, $service, $idcompany, $oldphoto, "entityphoto");
				}


				$t = microtime(true);
				$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
				$d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
				$api = md5($adminPost['name'] . $d->format("Y-m-d H:i:s.u"));
				$hash = md5($adminPost['name'] . $api);


				$this->nurse->set("api", $api);
				$this->nurse->set("hash", $hash);



				$this->nurse->set("statelogin", 1);



				$this->nurse->set("name", isset($adminPost['name']) ? $adminPost['name'] : "");
				$this->nurse->set("nif", isset($adminPost['nif']) ? $adminPost['nif'] : "");

				$this->nurse->set("identitycard", (isset($adminPost['identitycard']) && !empty($adminPost['identitycard'])) ? $adminPost['identitycard'] : null);

				$this->nurse->set("numberhelth", isset($adminPost['numberhelth']) ? $adminPost['numberhelth'] : null);

				if ($nurse_logo != null) {
					$this->nurse->set("photo", $nurse_logo);
				}
				$this->nurse->set("genre", isset($adminPost['genre']) ? $adminPost['genre'] : 2);
				$this->nurse->set("address", isset($adminPost['address']) ? $adminPost['address'] : "");
				$this->nurse->set("postalcode", isset($adminPost['postalcode']) ? $adminPost['postalcode'] : "");
				$this->nurse->set("locality", isset($adminPost['locality']) ? $adminPost['locality'] : "");
				$this->nurse->set("idcountry", isset($adminPost['idcountry']) ? $adminPost['idcountry'] : "");
				$this->nurse->set("iddistrict", (isset($adminPost['iddistrict']) && !empty($adminPost['iddistrict'])) ? $adminPost['iddistrict'] : null);
				$this->nurse->set("placeofbirth", isset($adminPost['placeofbirth']) ? $adminPost['placeofbirth'] : "");
				$this->nurse->set("idcompany", $idcompany);
				$this->nurse->set("telephone", isset($adminPost['telephone']) ? $adminPost['telephone'] : "");
				$this->nurse->set("mobilephone", isset($adminPost['mobilephone']) ? $adminPost['mobilephone'] : "");
				$this->nurse->set("obs", isset($adminPost['obs'])  ? $adminPost['obs'] : "");
				$this->nurse->set("type", (isset($adminPost['typ_']) && !empty($adminPost['typ_'])) ? $adminPost['typ_'] : 1);
				$this->nurse->set("identitytype", $identitytype);
				if ($adminPost['type'] == 1) {
					if (!empty($adminPost['email'])) {


						if ($verify_email == null) {
							$adminPost['type'] = 1;
						} else {
							$nursechec = Nurse::find($adminPost['identity']);

							if (!empty($nursechec->idnurse)) {
								$adminPost['type'] = 2;
								$t = $this->nurse->updatestates();
							} else {
								$adminPost['type'] = 2;
								$adminPost['identity'] = $verify_email->identity;
								$this->nurse->set('identity', $verify_email->identity);
								$this->nurse->set('state', $verify_email->state);
								$this->nurse->set("createdby", $identity);
								$this->nurse->set("updatedby", $identity);
								$t = $this->nurse->save();
							}
						}
					}
				}
				
				
				if ($adminPost['type'] == 1) {
					$this->nurse->set("email", isset($adminPost['email']) ? $adminPost['email'] : "");
					$this->nurse->set('state', 1);
					$this->nurse->set("createdby", $identity);
					$this->nurse->set("updatedby", $identity);
					$status = $this->nurse->save();
					$idst = $status;
					if ($idst) {
						if ($t == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&0", array('id' => 0, 'code' => 201, "subcode" => 0, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}
						if ($verify_email == null) {
					
							$status = Common::insertuser($this->nurse, $company->get('nif'), $entity->email);
						}


						if ($status[0] == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&1", array('id' => 0, 'code' => 201, "subcode" => 1, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}

						$mail = configEmail("Admin");
						require_once("conf/templateEmail/accountcreate.php");
						$email = $adminPost['email'];
						$entity = array($company->get('name'), $company->get('email'), $company->get('address'), $company->get('postalcode'), $company->get('locality'), $company->get('logo'), $company->get('mobilephone'), $company->get('website'), "nameentity" => $adminPost['name']);
						$token = $status[1];
						$status = true;

						if ($token != "0") {
							if (mailer($email, $mail, $token, $entity, 1)) {
							} else {
								Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&2", array('id' => 0, 'code' => 201, "subcode" => 2,   'msg' => 'ERROR_SAVE_USER'));
								die();
							}
						}
					}
				} elseif ($adminPost['type'] == 2) {


					$this->nurse->set("identity", $adminPost['identity']);
					$this->nurse->set("updatedby", $identity);
					$status = $this->nurse->update();
					if ($status == false) {
						$GLOBALS['statusdb'] = 0;
						Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_DATA&1", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_DATA', 'subcode' => 1));
						die();
					}

					$idst = $adminPost['identity'];
				}

			


			} elseif ($identitytype == 6) { # code...Tércnico Segunça  --------------------------------------------







				$tsecurity_logo = null;
				$oldphoto = null;
				if ($adminPost['type'] == 2) {
					$tsecurity = Tsecurity::findbyid($adminPost['identity']);
					if ($tsecurity != null) {
						$this->tsecurity->set("photo", $tsecurity->photo);
						$oldphoto = $tsecurity->photo;
					}
				}

				if ($file != Null && isset($file['name']['adminlogo'])) {
					$tsecurity_logo = array("name" => $file['name']['adminlogo'], "type" => $file['type']['adminlogo'], "tmp_name" => $file['tmp_name']['adminlogo'], "error" => $file['error']['adminlogo'], "size" => $file['size']['adminlogo']);
					$tsecurity_logo = Common::savefile($tsecurity_logo, $service, $idcompany, $oldphoto, "entityphoto");
				}


				$t = microtime(true);
				$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
				$d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
				$api = md5($adminPost['name'] . $d->format("Y-m-d H:i:s.u"));
				$hash = md5($adminPost['name'] . $api);


				$this->tsecurity->set("api", $api);
				$this->tsecurity->set("hash", $hash);



				$this->tsecurity->set("statelogin", 1);



				$this->tsecurity->set("name", isset($adminPost['name']) ? $adminPost['name'] : "");
				$this->tsecurity->set("nif", isset($adminPost['nif']) ? $adminPost['nif'] : "");

				$this->tsecurity->set("identitycard", (isset($adminPost['identitycard']) && !empty($adminPost['identitycard'])) ? $adminPost['identitycard'] : null);

				$this->tsecurity->set("numberhelth", isset($adminPost['numberhelth']) ? $adminPost['numberhelth'] : null);

				if ($tsecurity_logo != null) {
					$this->tsecurity->set("photo", $tsecurity_logo);
				}
				$this->tsecurity->set("genre", isset($adminPost['genre']) ? $adminPost['genre'] : 2);
				$this->tsecurity->set("address", isset($adminPost['address']) ? $adminPost['address'] : "");
				$this->tsecurity->set("postalcode", isset($adminPost['postalcode']) ? $adminPost['postalcode'] : "");
				$this->tsecurity->set("locality", isset($adminPost['locality']) ? $adminPost['locality'] : "");
				$this->tsecurity->set("idcountry", isset($adminPost['idcountry']) ? $adminPost['idcountry'] : "");
				$this->tsecurity->set("iddistrict", (isset($adminPost['iddistrict']) && !empty($adminPost['iddistrict'])) ? $adminPost['iddistrict'] : null);
				$this->tsecurity->set("placeofbirth", isset($adminPost['placeofbirth']) ? $adminPost['placeofbirth'] : "");
				$this->tsecurity->set("idcompany", $idcompany);
				$this->tsecurity->set("telephone", isset($adminPost['telephone']) ? $adminPost['telephone'] : "");
				$this->tsecurity->set("mobilephone", isset($adminPost['mobilephone']) ? $adminPost['mobilephone'] : "");
				$this->tsecurity->set("obs", isset($adminPost['obs'])  ? $adminPost['obs'] : "");
				$this->tsecurity->set("type", (isset($adminPost['typ_']) && !empty($adminPost['typ_'])) ? $adminPost['typ_'] : 1);
				$this->tsecurity->set("identitytype", $identitytype);
				if ($adminPost['type'] == 1) {
					if (!empty($adminPost['email'])) {


						if ($verify_email == null) {
							$adminPost['type'] = 1;
						} else {
							$tsecuritychec = Tsecurity::find($adminPost['identity']);

							if (!empty($tsecuritychec->idtsecurity)) {
								$adminPost['type'] = 2;
								$t = $this->tsecurity->updatestates();
							} else {
								$adminPost['type'] = 2;
								$adminPost['identity'] = $verify_email->identity;
								$this->tsecurity->set('identity', $verify_email->identity);
								$this->tsecurity->set('state', $verify_email->state);
								$this->tsecurity->set("createdby", $identity);
								$this->tsecurity->set("updatedby", $identity);
								$t = $this->tsecurity->save();
							}
						}
					}
				}
				
				
				if ($adminPost['type'] == 1) {
					$this->tsecurity->set("email", isset($adminPost['email']) ? $adminPost['email'] : "");
					$this->tsecurity->set('state', 1);
					$this->tsecurity->set("createdby", $identity);
					$this->tsecurity->set("updatedby", $identity);
					$status = $this->tsecurity->save();
					$idst = $status;
					if ($idst) {
						if ($t == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&0", array('id' => 0, 'code' => 201, "subcode" => 0, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}
						if ($verify_email == null) {
					
							$status = Common::insertuser($this->tsecurity, $company->get('nif'), $entity->email);
						}


						if ($status[0] == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&1", array('id' => 0, 'code' => 201, "subcode" => 1, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}

						$mail = configEmail("Admin");
						require_once("conf/templateEmail/accountcreate.php");
						$email = $adminPost['email'];
						$entity = array($company->get('name'), $company->get('email'), $company->get('address'), $company->get('postalcode'), $company->get('locality'), $company->get('logo'), $company->get('mobilephone'), $company->get('website'), "nameentity" => $adminPost['name']);
						$token = $status[1];
						$status = true;

						if ($token != "0") {
							if (mailer($email, $mail, $token, $entity, 1)) {
							} else {
								Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&2", array('id' => 0, 'code' => 201, "subcode" => 2,   'msg' => 'ERROR_SAVE_USER'));
								die();
							}
						}
					}
				} elseif ($adminPost['type'] == 2) {


					$this->tsecurity->set("identity", $adminPost['identity']);
					$this->tsecurity->set("updatedby", $identity);
					$status = $this->tsecurity->update();
					if ($status == false) {
						$GLOBALS['statusdb'] = 0;
						Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_DATA&1", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_DATA', 'subcode' => 1));
						die();
					}

					$idst = $adminPost['identity'];
				}



























			} else { # code...Colab ------------------------------------------------------------------------



				$employee_logo = null;
				$oldphoto = null;
				if ($adminPost['type'] == 2) {
					$employee = Employee::findbyid($adminPost['identity']);
					if ($employee != null) {
						$this->employee->set("photo", $employee->photo);
						$oldphoto = $employee->photo;
					}
				}


				if ($adminPost['identity'] != null && $adminPost['type'] != 2) {

					$employee = Employee::findbyid($adminPost['identity']);
					if ($employee != null) {
						$this->employee->set("photo", $employee->photo);
						$oldphoto = $employee->photo;
					}
				}

				if ($file != Null && isset($file['name']['adminlogo'])) {
					$employee_logo = array("name" => $file['name']['adminlogo'], "type" => $file['type']['adminlogo'], "tmp_name" => $file['tmp_name']['adminlogo'], "error" => $file['error']['adminlogo'], "size" => $file['size']['adminlogo']);
					$employee_logo = Common::savefile($employee_logo, $service, $idcompany, $oldphoto, "entityphoto");
				}


				$t = microtime(true);
				$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
				$d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
				$api = md5($adminPost['name'] . $d->format("Y-m-d H:i:s.u"));
				$hash = md5($adminPost['name'] . $api);


				$this->employee->set("api", $api);
				$this->employee->set("hash", $hash);



				$this->employee->set("statelogin", 1);



				$this->employee->set("name", isset($adminPost['name']) ? $adminPost['name'] : "");
				$this->employee->set("nif", isset($adminPost['nif']) ? $adminPost['nif'] : "");

				$this->employee->set("identitycard", (isset($adminPost['identitycard']) && !empty($adminPost['identitycard'])) ? $adminPost['identitycard'] : null);

				$this->employee->set("numberhelth", isset($adminPost['numberhelth']) ? $adminPost['numberhelth'] : null);
				$this->employee->set("socialsecurity", isset($adminPost['socialsecurity']) ? $adminPost['socialsecurity'] : "");

				if ($employee_logo != null) {
					$this->employee->set("photo", $employee_logo);
				}
				$this->employee->set("genre", isset($adminPost['genre']) ? $adminPost['genre'] : 2);
				$this->employee->set("address", isset($adminPost['address']) ? $adminPost['address'] : "");
				$this->employee->set("postalcode", isset($adminPost['postalcode']) ? $adminPost['postalcode'] : "");
				$this->employee->set("locality", isset($adminPost['locality']) ? $adminPost['locality'] : "");
				$this->employee->set("idcountry", isset($adminPost['idcountry']) ? $adminPost['idcountry'] : "");
				$this->employee->set("iddistrict", (isset($adminPost['iddistrict']) && !empty($adminPost['iddistrict'])) ? $adminPost['iddistrict'] : null);
				$this->employee->set("placeofbirth", isset($adminPost['placeofbirth']) ? $adminPost['placeofbirth'] : "");
				$this->employee->set("idcompany", $idcompany);
				$this->employee->set("telephone", isset($adminPost['telephone']) ? $adminPost['telephone'] : "");
				$this->employee->set("mobilephone", isset($adminPost['mobilephone']) ? $adminPost['mobilephone'] : "");
				$this->employee->set("obs", isset($adminPost['obs'])  ? $adminPost['obs'] : "");
				$this->employee->set("type", (isset($adminPost['typ_']) && !empty($adminPost['typ_'])) ? $adminPost['typ_'] : 1);
				$this->employee->set("identitytype", $identitytype);
				if ($adminPost['type'] == 1) {
					if (!empty($adminPost['email'])) {

						if ($verify_email == null) {


							$adminPost['identity'] = $verify_email->identity;
							$this->employee->set('identity', $verify_email->identity);
							$this->employee->set('state', $verify_email->state);
							$this->employee->set("createdby", $identity);
							$this->employee->set("updatedby", $identity);
							$t = $this->employee->save();
							if ($t == false) {
								$GLOBALS['statusdb'] = 0;
								Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_USER&-1", array('id' => 0, 'code' => 201, "subcode" => -1, 'msg' => 'ERROR_SAVE_USER'));
								die();
							}
							$t = $this->employee->updatestates();
							if ($t == false) {
								$GLOBALS['statusdb'] = 0;
								Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_USER&-10", array('id' => 0, 'code' => 201, "subcode" => -10, 'msg' => 'ERROR_SAVE_USER'));
								die();
							}
						} else {
							$employeechec = Employee::find($adminPost['identity']);

							if (!empty($employeechec->idemployee)) {
								$adminPost['type'] = 2;
								$t = $this->employee->updatestates();
							} else {
								$adminPost['type'] = 2;
								$adminPost['identity'] = $verify_email->identity;
								$this->employee->set('identity', $verify_email->identity);
								$this->employee->set('state', $verify_email->state);
								$this->employee->set("createdby", $identity);
								$this->employee->set("updatedby", $identity);
								$t = $this->employee->save();
							}
						}
					}
				}


				if ($adminPost['type'] == 1) {
					$this->employee->set("email", isset($adminPost['email']) ? $adminPost['email'] : "");
					$this->employee->set('state', 1);
					$this->employee->set("createdby", $identity);
					$this->employee->set("updatedby", $identity);
					$status = $this->employee->save();
					$idst = $status;
					if ($idst) {
						if ($t == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&0", array('id' => 0, 'code' => 201, "subcode" => 0, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}



						if ($verify_email == null) {
							$status = Common::insertuser($this->employee, $company->get('nif'), $entity->email);
						}

						if ($status[0] == false) {
							$GLOBALS['statusdb'] = 0;
							Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&1", array('id' => 0, 'code' => 201, "subcode" => 1, 'msg' => 'ERROR_SAVE_USER'));
							die();
						}

						$mail = configEmail("Admin");
						require_once("conf/templateEmail/accountcreate.php");
						$email = $adminPost['email'];
						$entity = array($company->get('name'), $company->get('email'), $company->get('address'), $company->get('postalcode'), $company->get('locality'), $company->get('logo'), $company->get('mobilephone'), $company->get('website'), "nameentity" => $adminPost['name']);
						$token = $status[1];
						$status = true;

						if ($token != "0") {
							if (mailer($email, $mail, $token, $entity, 1)) {
							} else {
								Common::response($service, "?controller=admin&action=index&alert-danger=ERROR_SAVE_USER&2", array('id' => 0, 'code' => 201, "subcode" => 2,   'msg' => 'ERROR_SAVE_USER'));
								die();
							}
						}
					}
				} elseif ($adminPost['type'] == 2) {

					$this->employee->set("identity", $adminPost['identity']);
					$this->employee->set("updatedby", $identity);
					$status = $this->employee->update();
					if ($status == false) {
						$GLOBALS['statusdb'] = 0;
						Common::response($service, "?controller=admin&action=newad&tab=panel_overview&alert-danger=ERROR_SAVE_DATA&1", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_DATA', 'subcode' => 1));
						die();
					}

					$idst = $adminPost['identity'];
				} //-----------------------------------------------------------------------------------------------------
			}
		}


		return $idst;
	}
}
