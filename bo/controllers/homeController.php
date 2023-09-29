<?php

/**
 *
 * @package    Home
 * @package    Controller
 * @copyright  2020 SEPRI
 *
 */
require_once("conf/globalconf.php");
require_once("conf/configEmail.php");
require_once("conf/accesscontrol.php");
require_once("libraries/other/common.php");
require_once("libraries/php-restclient/restclient.php");
require_once("models/Company.php");
require_once("models/Language.php");
require_once("models/Entity.php");
require_once("models/Currency.php");
require_once("models/Employee.php");
require_once("models/Paymentform.php");
require_once("models/Country.php");
require_once("models/Reasonvat.php");
require_once("models/Vat.php");
require_once("models/Serie.php");
require_once("models/Unit.php");
require_once("models/Admin.php");
require_once("models/Communicatemethod.php");
require_once("models/Operation.php");
require_once("models/District.php");
require_once("models/Role.php");
require_once("models/Adminrole.php");
require_once("models/Operationrole.php");




class HomeController
{

  private $company;
  private $language;
  public function __construct()
  {
  }

  public function index()
  {
    Accesscontrol::beforeActionSession();


    //_______________________________________________________________
    $maintenancelink = Common::linknow();
    $maint = $_SESSION['entity']['maintenance'];
    if (isset($maintenancelink) && is_array($maint) && in_array($maintenancelink, $maint)) { //---START Manutenção
      $breadcrumbs = array(
        array("icon" => "fa fa-cogs", "msg" => "maintenance")
      );
      $view = 'views/manager/home/maintenance.php';
      require_once("views/layout/_layout.php");
    }
    $maintenance = false;
    if (isset($maintenancelink) && is_array($maint) && in_array($maintenancelink, $maint)) {
      $maintenance = true;
    }
    //--------------------------------------------------------------
    $version = $_SESSION['entity']['version'];
    $view = 'views/home/index.php';
    require_once("views/layout/_layout.php");
  }

  public function createaccount()
  {
    Accesscontrol::beforeAction();

    $host = $_SERVER['HTTP_HOST'];

    $host = str_replace("\\", '', $host);
    $subdirectory = dirname($_SERVER['PHP_SELF']);
    $strpos = strpos($subdirectory, "/bo");
    $subdirectory = substr($subdirectory, 0, $strpos);

    $subdirectory = str_replace("\\", '', $subdirectory);
    $location = '' . $host . $subdirectory . '/';
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    $_POST['url_host'] = $protocol . $location;

    $service = false;
    $company = isset($_POST['company']) ? $_POST['company'] : null;
    if ($company == null) {


      $current = file_get_contents("accessinvalid.log");

      file_put_contents("accessinvalid.log", $current);
    }



    if (isset($_POST['company'])) {
      $isValid = $this->validatecreateaccount($_POST['company']);
      if (!$isValid) {
        $GLOBALS['statusdb'] = 0;
        
        header("Location: ../index.php?alert-danger=ERROR_DATA_PARAMS&11");
        die();
      }

      $company = $_POST['company'];
      $plan = "3";
      $this->accountsignup($service, $company, $plan);
    } else {
      $GLOBALS['statusdb'] = 0;
   
      header("Location: ../index.php?alert-danger=ERROR_DATA_PARAMS&12");
      die();
    }
  }


  public function validatecreateaccount($company)
  {
    $companyFields = array("name", "email", "nif", "address", "locality", "idcountry", "phone");
    foreach ($companyFields as $k) {
      if (!isset($company[$k]) || empty($company[$k])) {
        return false;
      }
    }
    return true;
  }



  public function validateindex()
  {
    if (isset($_SESSION['entity'])) {
      if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 64800, '/');
      }
      session_destroy();
    }
    Accesscontrol::beforeAction();

    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if ($username == null || empty($password)) {
      header("Location: ../index.php?alert-danger=LOGIN_ERROR_ACCESS&1");
      die();
    }
    $st = Common::checkaccessmanagerforLogin($username, $password);

    if ($st == null) {
      header("Location: ../index.php?alert-danger=LOGIN_ERROR_ACCESS&2");
      die();
    }


    if ($st->code == 202) {
      header("Location: ../index.php?alert-danger=LOGIN_ERROR_ACCESS&4");
      die();
    }


    $st = $st->user;

    if (!isset($st->clients) || empty($st->clients)) {
      header("Location: ../index.php?alert-danger=LOGIN_ERROR_ACCESS&10");
      die();
    }

    $_SESSION['defaultdatabaseKey'] = 0;


    $_SESSION['databaseinfo'] = $st->clients;

    $_SESSION['defaultprofile'] = 0;
    $this->validateAccessUser($st->clients[0], $username, $st->api);
  }



  public function modifycompany()
  {
    $key = isset($_POST['key']) ? $_POST['key'] : -1;
    $_SESSION['defaultprofile'] = 0;
    if (!isset($_SESSION['databaseinfo'][$key])) {
      $userT = $_SESSION['entity']['userT'];
      if ($userT[$_SESSION['defaultprofile']] == 1) {
        header("Location: ?controller=class&action=index");
        die();
      } elseif ($userT[$_SESSION['defaultprofile']] == 2) {
        header("Location: ?controller=teacher&action=index");
        die();
      } elseif ($userT[$_SESSION['defaultprofile']] == 3) {
        header("Location: ?controller=employee&action=index");
        die();
      } else {
        header("Location: ?controller=employee&action=index");
        die();
      }
    }
    $_SESSION['defaultdatabaseKey'] = $key;

    $this->validateAccessUser($_SESSION['databaseinfo'][$key], $_SESSION['entity']['email'], $_SESSION['entity']['api']);
  }






  public function modifyprofile()
  {
    $key = isset($_POST['key']) ? $_POST['key'] : -1;
    $_SESSION['defaultprofile'] = 0;
    if (!isset($_SESSION['entity']['userT'][$key])) {
      $userT = $_SESSION['entity']['userT'];
      if ($userT[$_SESSION['defaultprofile']] == 1) {
        header("Location: ?controller=class&action=index");
        die();
      } elseif ($userT[$_SESSION['defaultprofile']] == 2) {
        header("Location: ?controller=teacher&action=index");
        die();
      } elseif ($userT[$_SESSION['defaultprofile']] == 3) {
        header("Location: ?controller=employee&action=index");
        die();
      } else {
        header("Location: ?controller=employee&action=index");
        die();
      }
    }
    $_SESSION['defaultprofile'] = $key;

    $this->validateAccessUser($_SESSION['databaseinfo'][$_SESSION['defaultdatabaseKey']], $_SESSION['entity']['email'], $_SESSION['entity']['api']);
  }


  public function modifyversion()
  { //Alterarn version
    
    $key = $_SESSION['entity']['version'];

    $service = false;

  
    if (isset($key) && !empty($key)) {

      $userT = $_SESSION['entity']['version'];
      if ($key == 1) {

        $_SESSION['entity']['version'] = 2;
  
        return  Common::response($service, "?controller=home&action=index&alert-success=SUCCESS_DEV_MOD", array('code' => 200, 'newUrl' => "1"));
      } else {
        $_SESSION['entity']['version'] = 1;

      
        return  Common::response($service, "?controller=home&action=index&alert-success=SUCCESS_PROD_MOD", array('code' => 200, 'newUrl' => "1"));
      }
    }
  }


  private function validateAccessUser($cl_def, $email, $api_key)
  {

    $_SESSION['name_database_session'] = $cl_def->database_name;
    $_SESSION['userplan'] = $cl_def->idplan;

    $arrfunc = array();
    if ($cl_def->functionality != null) {
      foreach ($cl_def->functionality as $key => $value) {
        array_push($arrfunc, $value->name);
      }
    }
    $_SESSION['functionality'] = $arrfunc;

    $this->company = new Company();
    $this->country = new Country();
    $this->entity = new Entity();
    $this->language  = new Language();
    $this->operation = new Operation();
    $this->employee = new Employee();
    //$this->adminrole = new Adminrole();


 
    $this->entity->set("email", $email);
    $this->entity->set("api", $api_key);
    //$this->entity->updateemailbyapi();
    $this->entity->updateemailbyapi_login();
    

    $entity = Entity::findbyemail($email);


    if ($entity == null) {
      header("Location: ../index.php?alert-danger=LOGIN_ERROR_ACCESS&4");
      die();
    }
    if ($entity->statelogin != 1) {
      header("Location: ../index.php?alert-warning=LOGIN_WAIT_ACCESS&5");
      die();
    }

    $this->company = Company::findOne($entity->idcompany);



    if ($this->company == null) {
      header("Location: ../index.php?alert-warning=LOGIN_WAIT_ACCESS");
      die();
    }


    //$this->message      = new Message();
    $auxee = array();
    $userT = array();

    if ($entity->identitytype == 1) {

      array_push($userT, 1);
    }
    if ($entity->identitytype == 2) {

      array_push($userT, 2);
    }

    if ($entity->identitytype == 3) {

      array_push($userT, 3);
    }


    if ($entity->identitytype == 4) {

      array_push($userT, 4);
    }

    if ($entity->identitytype == 5) {

      array_push($userT, 5);
    }

    if ($entity->identitytype == 6) {

      array_push($userT, 6);
    }
    if ($entity->idcompany != null) {

      $companyname = $this->company->get('name');
    } else {

      $companyname = "";
    }




    $level = 1;
    $type = 1;
    $admin = $entity->identity;
    $permission = null;

    if ($userT[$_SESSION['defaultprofile']] == 1) {
      $menu = Operation::findAllAdm($level, $type, $admin);
      $permission = Operation::findbyadminpermission($admin);
     

    } else {
      $menu = Operation::findAll($level, $userT[$_SESSION['defaultprofile']]);
    }

    $menuaux = Operation::findAbrevation($userT[$_SESSION['defaultprofile']]);
    $maintenance = Operation::findbymaintenanceon(1);

    if ($permission == null) {
      $permission = array(1 => array(), 2 => array());
    }

    if ((!isset($permission['1'])  || empty($permission['1']))) {
      $permission['1'] = array("withoutdata");
    }

    if ((!isset($permission['2']) || empty($permission['2']))) {
      $permission['2'] = array("withoutdata");
    }

    Common::finishdatabase();


    if ($entity->identity == true) {
      $alluserdev = Entity::findAllnotify();
    }



    $entitySession = array("api" => $api_key, "companyname" => $companyname, "identity" => $entity->identity, "operation" => $menuaux, "permission" => $permission, "photo" => $entity->photo, "email" => $entity->email, "name" => $entity->name, "idcompany" => $entity->idcompany,  "numberresults" => $this->company->get('numberresults'), 'username' => $entity->name, 'menu' => $menu, 'functionality' => array(), 'userT' => $userT, "version" => 1, 'alluserdev' => $alluserdev, 'maintenance' => $maintenance);
   
    $_SESSION['entity'] = $entitySession;


    if ($userT[$_SESSION['defaultprofile']] == 1) {
      header("Location: ?controller=home&action=index");
      die();
    } elseif ($userT[$_SESSION['defaultprofile']] == 2) {
      header("Location: ?controller=doctor&action=index");
      die();
    } elseif ($userT[$_SESSION['defaultprofile']] == 3) {
      header("Location: ?controller=physiotherapist&action=index");
      die();
    } elseif ($userT[$_SESSION['defaultprofile']] == 5) {
      header("Location: ?controller=nurse&action=index");
      die();
    } elseif ($userT[$_SESSION['defaultprofile']] == 6) {
      header("Location: ?controller=tsecurity&action=index");
      die();
    } else {
      header("Location: ?controller=employee&action=index");
      die();
    }
  }

  public function modifyuser()
  { //Alteraruser
    $key = isset($_POST['identity']) ? $_POST['identity'] : -1;



    if (isset($key) && !empty($key) && $key != -1) {
      $_SESSION['defaultprofile'] = 0;
      $infuser = Entity::findbyid($key);
      $this->validateAccessUser($infuser->email, $infuser->api);
    }
  }


  public function createcompany($service, $user, $company)
  {

    $this->company = new Company();
    $this->role = new Role();
    $this->adminrole = new Adminrole();
    $this->operationrole = new Operationrole();

    $this->company = new Company();
    $this->language = new Language();
    $this->country = new Country();
    $this->entity = new Entity();
    $this->currency = new Currency();
    $this->employee = new Employee();
    $this->paymentform = new Paymentform();
    $this->reasonvat = new Reasonvat();
    $this->vat = new Vat();
    $this->serie = new Serie();
    $admin = new Admin();
    $this->unit = new Unit();
    $communicatemethod = new Communicatemethod();

    if (!array_key_exists('language', $company)) {
      $language = Language::findbydescription('Português');

      if ($language != null) {
        $language = $language->idlanguage;
      }
      $company['language'] = isset($company['language']) ? $company['language'] : $language;
    }

    if (!array_key_exists('idcurrency', $company)) {
      $idcurrency = Currency::findbycode("EUR");
      if ($idcurrency != null) {

        $idcurrency = $idcurrency->idcurrency;
      }
      $company['idcurrency'] = isset($company['idcurrency']) ? $company['idcurrency'] : $idcurrency;
    }


    $this->createcompanyparam($company);
    if ($this->company->save()) {


      foreach ($user as $l => $value) {
        $hash = md5($value['name'] . "" . $value['api']);
        $this->entity->set("name", $value['name']);
        $this->entity->set("allowance", 0);
        $this->entity->set("allowancenumber", 0);
        $this->entity->set("genre", 2);
        $this->entity->set("email", $value['email']);
        $this->entity->set("api", $value['api']);
        $this->entity->set("hash", $hash);
        $this->entity->set("state", 1);


        $this->entity->set("statelogin", 1);
        $this->entity->set("identitytype", 1);
        $this->entity->set("idcompany", $this->company->get('idcompany'));
        $identity =  $this->entity->save();

        if ($identity == false) {
          $GLOBALS['statusdb'] = 0;
          print json_encode(array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_ENTITY_USER'));
          die();
        }

        if (isset($value['resetPasswordToken']) && !empty($value['resetPasswordToken'])) {
          $aux = $value['resetPasswordToken'];
        }


        $admin->set("identity", $identity);
        $admin->set("updatedby", $identity);
        $admin->set("createdby", $identity);

        $st = $admin->save();

        if ($st == false) {
          $GLOBALS['statusdb'] = 0;
          print json_encode(array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_ENTITY_USER'));
          die();
        }
      }


      $t = microtime(true);
      $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
      $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
      $api = md5("Consumidor Final" . $d->format("Y-m-d H:i:s.u"));
      $hash = md5("Consumidor Final" . $api);

      $this->employee->set("name", "Consumidor Final");

      $this->employee->set("nif", "999999990");
      $this->employee->set("allowance", 0);
      $this->employee->set("allowancenumber", 0);
      $this->employee->set("genre", 2);
      $this->employee->set("email", "");
      $this->employee->set("api", $api);
      $this->employee->set("hash", $hash);
      $this->employee->set("state", 1);


      $this->employee->set("statelogin", 0);
      $this->employee->set("idcompany", $this->company->get('idcompany'));
      $this->employee->set("identitytype", 4);




      $this->employee->set("updatedby", $identity);
      $this->employee->set("createdby", $identity);
      $st = $this->employee->save();
      if ($st == false) {
        $GLOBALS['statusdb'] = 0;
        print json_encode(array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_ENTITY'));
        die();
      }



      $communicatemethod->set('idcompany', $this->company->get('idcompany'));

      $communicatemethod->set('typedoc', 0);
      $communicatemethod->set('typecommunication', Communicatemethod::getSAFT());

      if ($communicatemethod->save()) {
        $communicatemethod->set('typedoc', 1);
        $communicatemethod->save();
        $this->paymentform->savepayments($this->company->get('idcompany'));
        $this->reasonvat->set("updatedby", $identity);
        $this->reasonvat->set("createdby", $identity);
        $this->reasonvat->set("state", 1);
        $this->reasonvat->savereasonvat();
        $this->vat->set("updatedby", $identity);
        $this->vat->set("createdby", $identity);
        $this->vat->savevatdefault($this->company->get('idcompany'));
        $this->serie->checkserie($this->company->get('idcompany'));
        $this->unit->set("updatedby", $identity);
        $this->unit->set("createdby", $identity);
        $this->unit->set("state", 1);
        $this->unit->savedefault($this->company->get('idcompany'));
        http_response_code(200);
        Common::createdirectory($this->company->get('nif'));

        //Envio
        $mail = configEmail("Admin");
        $email = $this->company->get('email');
        $nameCompany = $this->company->get('name');
        $token = "";
        if (isset($aux)) {
          $token = $aux;
        }
        require_once("conf/templateEmail/accountcreatebyuser.php");

        if (mailer($email, $mail, $token, $nameCompany)) {
        
          Common::response($service, "Location: https://" . $_SERVER['HTTP_HOST'] . "index.php?alert-success=SUCCESS_ENTITY_CREATE", array('id' => $this->company->get('idcompany'), 'code' => 200, 'msg' => 'SUCCESS_ENTITY_CREATE'));
          die();
        } else {
     
          $GLOBALS['statusdb'] = 0;
          http_response_code(201);
          Common::response($service, "Location: https://" . $_SERVER['HTTP_HOST'] . "index.php?alert-danger=ERROR_CREATE_ENTITY", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_CREATE_ENTITY'));
          die();
        }
      } else {
        $GLOBALS['statusdb'] = 0;
        http_response_code(201);
        Common::response($service, "Location: https://" . $_SERVER['HTTP_HOST'] . "index.php?alert-danger=ERROR_CREATE_ENTITY", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_CREATE_ENTITY'));
        die();
      }
    } else {
      $GLOBALS['statusdb'] = 0;
      http_response_code(201);
      Common::response($service, "Location: https://" . $_SERVER['HTTP_HOST'] . "index.php?alert-danger=ERROR_SAVE_ENTITY", array('id' => 0, 'code' => 201, 'msg' => 'ERROR_SAVE_ENTITY'));
      die();
    }
  }
  private function createcompanyparam($companyPost)
  {
    $this->company->set('nif', $companyPost['nif']);
    $this->company->set('name', $companyPost['name']);
    $this->company->set('address', $companyPost['address']);
    $this->company->set('postalcode', $companyPost['postalcode']);
    $this->company->set('locality', $companyPost['locality']);
    $this->company->set('email', $companyPost['email']);
    $this->company->set('mobilephone', $companyPost['phone']);
    $this->company->set('conservatory', $companyPost['conservatory']);
    $this->company->set('registernumber', $companyPost['registernumber']);
    $this->company->set('idcountry', $companyPost['idcountry']);
    $this->company->set('vatregimebox', 0);
    $this->company->set('typevat', 1);
    $this->company->set('numberresults', 10);
    $this->company->set('state', 1);
    $this->company->set('idlanguage', $companyPost['language']);
    $this->company->set('idcurrency', $companyPost['idcurrency']);
    return $this->company;
  }

  public function accountsignup($service, $company, $plan)
  {

    $vatvalide =  $this->validatetin($company['nif'], false);
    if (!$vatvalide) {
      header('Location: ' . $_POST['url_host'] . "?alert-danger=TIN_NOT_VALID");
      die();
    }
    if (isset($company['conservatory']) && isset($company['registernumber'])) {
      $spaceconservatory = strpos($company['conservatory'], " ");
      $spaceregisternumber = strpos($company['registernumber'], " ");
      if (($spaceconservatory !== false) || ($spaceregisternumber !== false)) {
        header('Location: ' . $_POST['url_host'] . "?alert-danger=ERROR_CONSERVATORY_REGISTER_NUMBER");
        die();
      }
    }




    $this->apicompany($service, $company, $plan);

    header("Location: ../index.php?alert-success=SUCCESS_SAVE_DATA&1");
    die();
  }




  public function checkusername($return = false)
  {

    print json_encode(array("code" => 201));
    die();
  }


  public function checkuseremail($return = false)
  {

    $this->entity = new Entity();

    if (isset($_POST['admin'])) {
      $admin = isset($_POST['admin']) ? $_POST['admin'] : array();
      $email = $admin['email'];
    }

    $aux = Entity::findbyemail((trim($email)));


    if (isset($aux) && !empty($aux)) {
      print json_encode(array("code" => 200));
      die();
    } else {
      print json_encode(array("code" => 201));
      die();
    }
  }

  public function checknumberhelth($return = false)
  {

    $this->entity = new Entity();

    if (isset($_POST['admin'])) {
      $admin = isset($_POST['admin']) ? $_POST['admin'] : array();
      $numberhelth = $admin['numberhelth'];
    }

    $aux = Entity::findbynumberhelth((trim($numberhelth)));


    if (isset($aux) && !empty($aux)) {
      print json_encode(array("code" => 200));
      die();
    } else {
      print json_encode(array("code" => 201));
      die();
    }
  }


  private function validatetin($vat, $ignoreFirst = true)
  {
    $vat = trim($vat);
    $vatSplit = str_split($vat);
    if (
      in_array($vatSplit[0], array(1, 2, 3, 5, 6, 7, 8, 9))
      ||
      $ignoreFirst
    ) {
      $checkDigit = 0;
      for ($i = 0; $i < 8; $i++) {
        $checkDigit += $vatSplit[$i] * (10 - $i - 1);
      }
      $checkDigit = 11 - ($checkDigit % 11);

      if ($checkDigit >= 10) $checkDigit = 0;

      if ($checkDigit == $vatSplit[8]) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  private function apicompany($service, $company, $plan)
  {
    $md5 = md5('*whp2022#' . date('Y-m-d'));
    $pass = base64_encode($md5);
    $api = new RestClient([
      'base_url' => $GLOBALS['url_manager'],
    ]);
    $host = $_SERVER['HTTP_HOST'];
    $host = str_replace("\\", '', $host);
    $subdirectory = dirname($_SERVER['PHP_SELF']);
    $subdirectory = str_replace("\\", '', $subdirectory);
    $location = 'http://' . $host . $subdirectory . '/';
    $db = require('conf/database.inc.php');

    $auth_key = bin2hex(openssl_random_pseudo_bytes(16));

    $company['auth_key'] = $auth_key;


    $result = $api->post("api.php?controller=company&action=create&API_KEY=" . $pass, (['company' => json_encode($company), 'plan' => $plan, 'host' => $location, 'user_database' => $db['db']['development']['user']]));

    if ($result->info->http_code != 200) {
      //header('Location: '.$_POST['url_host']."?alert-danger=ERROR_SAVE_COMPANY");die();
      header("Location: ../index.php?alert-danger=ERROR_SAVE_COMPANY&11");
      die();
    }
  }
  public function checksession()
  {
    Accesscontrol::beforeActionSession();
    header("Content-Type: application/json");

    http_response_code(200);
    print json_encode(array('code' => 200));
    die();
  }

  public function accesscheck()
  {
    Accesscontrol::beforeAction();
    print json_encode(array('id' => 0, 'code' => 200, 'msg' => ''));
    die();
  }

  public function destroy()
  {

    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time() - 64800, '/');
    }

    session_destroy();
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/dev_whp");
  }

  public function getpostalcode()
  {

    $postalcode = isset($_GET['postalcode']) ? $_GET['postalcode'] : 0;
    $url = "https://www.ctt.pt/pdcp/xml_pdcp?incodpos=" . $postalcode;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
      $response = '';
    } else {
      curl_close($ch);
    }

    $response = json_decode(json_encode(simplexml_load_string($response)), true);

    if (isset($response['Localidade']) && isset($response['Localidade']['Designacao'])) {

      $district = 0;
      if (!empty($response['Localidade']['Distrito']) && !isset($_GET['distictignored'])) {
        new District();
        $dis = District::findbydesc($response['Localidade']['Distrito']);
        if ($dis != null) {
          $district = $dis->iddistrict;
        }
      }

      print json_encode(array("code" => 200, "iddistrict" => $district, "district" => $response['Localidade']['Distrito'], "county" => $response['Localidade']['Concelho'], "parish" => $response['Localidade']['Freguesia'], "designation" => $response['Localidade']['Designacao']));
    } else {
      print json_encode(array("code" => 201));
    }
  }



  public function sendEmailReset()
  {
    Accesscontrol::beforeAction();
    $username = isset($_POST['username']) ? $_POST['username'] : null;


    if ($username == null) {
      if (!file_exists("accessinvalid.log")) {
        $fp = fopen("accessinvalid.log", "wb");
        fwrite($fp, "");
        fclose($fp);
      }
      $ip = Common::get_client_ip();
      $current = file_get_contents("accessinvalid.log");
      $current .= $ip . " - " . date('Y-m-d H:i:s') . " - sendEmailReset \n";
      file_put_contents("accessinvalid.log", $current);
    }
    $st = Common::checkaccessmanager($username, 1);

    if ($st == null) {
      header("Location: ../index.php?alert-danger=RESET_ERROR_USERNAME_INCORRECT");
      die();
    }


    require_once("conf/templateEmail/resetPassword.php");

    $token = Common::generateResetToken($st->email);

    if (empty($token)) {
      header("Location: ../index.php?alert-danger=RESET_ERROR_TOKEN");
      die();
    }

    $mail = configEmail("Admin");
    $email = $st->email;


    if (mailer($email, $mail, $token)) {
      header("Location: ../index.php?alert-success=EMAIL_SEND_SUCESS");
      die();
    } else {
      header("Location: ../index.php?alert-danger=RESET_ERROR_EMAIL");
      die();
    }
  }

  public function sendEmailUsername()
  {
    Accesscontrol::beforeAction();
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    if ($email == null) {

      $ip = Common::get_client_ip();
      $current = file_get_contents("accessinvalid.log");
      $current .= $ip . " - " . date('Y-m-d H:i:s') . " - sendEmailUser \n";
      file_put_contents("accessinvalid.log", $current);
    }

    $st = Common::sendEmailbyusername($email);

    if ($st != null) {
    
      header("Location: ../index.php?alert-success=LOGIN_SUCCESS_EMAIL");
      die();
    } else {
  
      header("Location: ../index.php?alert-danger=RESET_ERROR_EMAIL");
      die();
    }
  }

  private function checkpassword($pass, $confpass)
  {
    if ($pass == $confpass) {
      return true;
    } else {
      return false;
    }
  }

  public function changepassword()
  {
    Accesscontrol::beforeAction();

    $host = $_SERVER['HTTP_HOST'];
    $host = str_replace("\\", '', $host);
    if (isset($_POST['password']) && isset($_POST['confirm']) && isset($_POST['token'])) {
      $password = $_POST['password'];
      $password2 = $password;
      $passconfirm = $_POST['confirm'];
      $token = $_POST['token'];


      if ($this->checkpassword($password, $passconfirm)) {
        $st = Common::checkaccessmanager($token, 2);

        if ($st == null) {
         
          header("Location: ../index.php?alert-danger=USER_NOT_FOUND");
          die();
        }




        $status = Common::updateuserpassword($st, $password2, null);

        if ($status == false) {
         
          header("Location: ../index.php?alert-danger=RESET_ERROR_TOKEN");
          die();
        }

 
        header("Location: ../index.php?alert-success=USER_PASSWORD_UPDATE_SUCCESS");
        die();
      } else {
        header("Location: https://" . $host . "bo/?controller=home&action=resetpassword&password_reset={$token}&alert-danger=PASS_NOT_EQUALS");
      }
    } else {
      header("Location: https://" . $host);
    }
  }

  public function resetpassword()
  {
    Accesscontrol::beforeAction();
    if (isset($_GET['password_reset'])) {
      $token = $_GET['password_reset'];

      $st = Common::checkaccessmanager($token, 2);

      if ($st == null) {
        header("Location: ../index.php?alert-danger=USER_NOT_FOUND");
        die();
      }
      require_once('views/home/resetpassword.php');
    } else {
      header("Location: ../index.php?alert-danger=RESET_ERROR_TOKEN404");
      die();
    }
  }


  public function validateLogin()
  {
    if (isset($_SESSION['entity'])) {
      if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 64800, '/');
      }
      session_destroy();
    }
    Accesscontrol::beforeAction();

    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if ($username == null || empty($password)) {
      header("Location: ../index.php?alert-danger=LOGIN_ERROR_ACCESS&1");
      die();
    }
    $st = Common::checkaccessmanagerforLogin($username, $password);



    if ($st == null) {
      header("Location: ../index.php?alert-danger=LOGIN_ERROR_ACCESS&2");
      die();
    }


    if ($st->code == 202) {
      header("Location: ../index.php?alert-danger=LOGIN_ERROR_ACCESS&4");
      die();
    }


    $st = $st->user;

    if (!isset($st->clients) || empty($st->clients)) {
      header("Location: ../index.php?alert-danger=LOGIN_ERROR_ACCESS&10");
      die();
    }

    $_SESSION['defaultdatabaseKey'] = 0;


    $_SESSION['databaseinfo'] = $st->clients;

    $_SESSION['defaultprofile'] = 0;
    $this->validateAccessUser($st->clients[0], $username, $st->api);
  }

  public function messagesession()
  {
    Accesscontrol::beforeActionSession();
    $id =  $_SESSION['entity']['identity'];
    $this->message      = new Message();
    $message = Message::countallbyentity($id);

    $_SESSION['entity']['message'] = $message;

    return json_encode(array("msg" => $message));
  }

  public function getcompany()
  {
    Accesscontrol::beforeActionSession();
    $search = isset($_POST['scholl']) ? $_POST['scholl'] : " ";


    $company = Common::findcompany(1, $search);

    session_write_close();
    if ($company == null) {
      print json_encode(array());
      die();
    }
    print json_encode($company);
  }

  public function checkemployee()
  {
    Accesscontrol::beforeActionSession();

    $companyselect = isset($_POST['companyselect']) ? $_POST['companyselect'] : null;

    if (isset($companyselect) && !empty($companyselect)) {
      $company = Common::findcompany(1, $companyselect);
      foreach ($company as $key => $value) {
        if ($value->id == $companyselect) {
        }
      }
    }



    session_write_close();
    if ($companyselect == null) {
      print json_encode(array());
      die();
    }
    print json_encode($companyselect);
  }






  public function runcron()
  {



    //_______________________________________________________________
    if (isset($_SESSION['entity'])) {
      Accesscontrol::beforeActionSession();
      $identity = $_SESSION['entity']['identity'];
      $usertype = $_SESSION['entity']['userT']; //---START Verificação
      $typuser = $usertype[$_SESSION['defaultprofile']];
    } else {
      Accesscontrol::beforeAction();
    }
    //_______________________________________________________________ 
    $service = false;
    if (isset($_POST['servicesession'])) {
      $service = true;
    }
    $id = isset($_GET['option']) ? $_GET['option'] : 0;



    if (isset($id) && !empty($id)) {

      switch ($id) {
          //----Manager-----
          //______________________________________________________________________________________________________________________________________________________________________________________________________
        case '1': //Atualizar MENUS
          $this->role = new Role();
          $this->adminrole = new Adminrole();
          $this->operationrole = new Operationrole();

          if (isset($identity) && !empty($identity)) {
            //verificar role


            $role = Role::findbyname('Administrador');

            $this->adminrole->set('identity', $identity);
            $this->adminrole->delete();

            $this->operationrole->set('idrole', $role->idrole);
            $this->operationrole->delete();
            if (isset($role) && !empty($role)) { //add se existe 
              $this->adminrole->set("identity", $identity);
              $this->adminrole->set("createdby", $identity);
              $this->adminrole->set("updatedby", $identity);
              $this->adminrole->set("idrole", $role->idrole);
              $status = $this->adminrole->save();
              if ($status == false) {
                $GLOBALS['statusdb'] = 0;
              }

              for ($j = 1; $j <= 4; $j++) { //4 menus
                for ($i = 1; $i <= 2; $i++) { //4 menus
                  $this->operationrole->set("idrole", $role->idrole);
                  $this->operationrole->set("idoperation", $j);
                  $this->operationrole->set("type", $i);
                  $this->operationrole->set("createdby", $identity);
                  $this->operationrole->set("updatedby", $identity);
                  $status = $this->operationrole->save();
                }
              }
            }
          }
          $this->destroy();
          break;
          //______________________________________________________________________________________________________________________________________________________________________________________________________
        case '2': //Apagar duplicado

          break;
          //______________________________________________________________________________________________________________________________________________________________________________________________________
        case '3': //apagar contacto lixo

          break;
          //______________________________________________________________________________________________________________________________________________________________________________________________________

          //______________________________________________________________________________________________________________________________________________________________________________________________________
        case '4': //Envio de notificação Equipamento HST

          break;
          //______________________________________________________________________________________________________________________________________________________________________________________________________

          //______________________________________________________________________________________________________________________________________________________________________________________________________
        case '5': //Envio de 

          break;
          //______________________________________________________________________________________________________________________________________________________________________________________________________
      }
    }
  }
}
