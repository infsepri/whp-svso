<?php
/**
*
* @package    Route
* @Route
* @copyright  2020 SEPRI
*
*/
$GLOBALS['statusdb'] = 1;
$GLOBALS['autoclosetransaction']=0;
date_default_timezone_set("Europe/Lisbon");
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
if(isset($_GET['controller']) && isset($_GET['action'])){
  $controller = $_GET['controller'];
  $action = $_GET['action'];

  ini_set("display_errors", 0);
  ini_set("log_errors", 1);
  ini_set("error_log", "error.log");


  register_shutdown_function(function(){
    $last_error = error_get_last();

    if (!empty($last_error) && $last_error['type'] === E_ERROR) {
        $GLOBALS['statusdb']=0;
    }


    if($GLOBALS['statusdb']==1){
      if(isset($GLOBALS['connectiondb'])){
        $GLOBALS['connectiondb']->commit();
      }
    }else{
      if(isset($GLOBALS['connectiondb'])){
        $GLOBALS['connectiondb']->rollback();
      }
    }
    if(isset($GLOBALS['connectiondb'])){
      $GLOBALS['connectiondb'] = null;
      }


      if ( !empty($last_error) &&
           $last_error['type'] & (E_ERROR | E_COMPILE_ERROR | E_PARSE | E_CORE_ERROR | E_USER_ERROR)
         )
      {
        file_put_contents("error_log.txt", print_r($last_error, true)."\n", FILE_APPEND);
        if(isset($_POST['servicesession'])){
          $host = $_SERVER['HTTP_HOST'];
          $host = str_replace("\\", '', $host);
          header("Content-Type: application/json");
          http_response_code(200);
           print json_encode(array( 'code'=>500 , 'msg'=>'ERROR_INTERN_DATABASE', 'url'=>"https://".$host)) ;die();
        }else{
          require_once('views/error500.php');
        }

         exit(1);
      }

  });

  $controllers = array(
    //----Manager-----
    'home'            =>['checknumberhelth','checkuseremail','runcron','index','createaccount','accesscheck','checksession','validateLogin','destroy','getpostalcode','checkuser','checkusername','sendEmailReset','sendEmailContact','sendEmailUsername','changepassword','resetpassword',"messagesession","createregister","creatcontactinfo","getcompany","checkstudent","modifyversion"],
    'distritct'			  =>["getdistritct"],
    'country'			    =>["getcountry"],
    'entity'			    =>["getentity", "getdatapersonal", 'updatepersonaldata'],
    'entitytype'			=>["getentitytype","getsubtype"],
    'role'			  =>["index","newr", "create", "getall", "edit", "update","getrole"],
    "entitytypes" =>["getidentifytypes"],
    'distritct'			  =>["getdistritct"],
    'country'			    =>["getcountry"],
    "admin"           =>["index","newad", "create", "getall", "changestate", "edit", "update","getadmin","removephoto","info","getadmin1"],
    "schoolyear" =>["index","newsy", "create", "getall", "changestate", "edit", "update", "getschoolyear","changestatenow","getschoolyearAll"],
    "discipline" =>["index","newdi", "create", "getall", "changestate", "edit", "update", "getdiscipline","getavailableschool"],
    "classroom" =>["index","newcr", "create", "getall", "changestate", "edit", "update", "getclassroom"],
    "levelteaching" =>["index","newlt", "create", "getall", "changestate", "edit", "update", "getlevelteaching"],
    "activitie" =>["index","newac", "create", "getall", "changestate", "edit", "update","getactivitie", "removephoto"],
    'personaldata'    => ['index', 'getdata', 'create', 'update', 'getdetail', 'changepass', 'updatepass', 'getdatapersonal', 'updatepersonaldata'],
    "doctor" =>["index","newte", "create", "getall", "changestate", "edit", "update", "getteacher","getavailableteacher","dashboard","personaldata","currentaccount","message","activitie","removephoto","getPDF","info","deletall"],
    "nurse" =>["index","newte", "create", "getall", "changestate", "edit", "update", "getteacher","getavailableteacher","dashboard","personaldata","currentaccount","message","activitie","removephoto","getPDF","info","deletall"],
    "tsecurity" =>["index","newte", "create", "getall", "changestate", "edit", "update", "getteacher","getavailableteacher","dashboard","personaldata","currentaccount","message","activitie","removephoto","getPDF","info","deletall"],
    'notify'			  =>["index","newr", "create", "getall", "edit", "update","getrole"],
   
    "physiotherapist" =>["index","newte", "create", "getall", "changestate", "edit", "update", "getteacher","getavailableteacher","dashboard","personaldata","teacherclass","currentaccount","message","activitie","removephoto","getPDF","info","deletall"],
    "employee" =>["index","newte", "create", "getall", "changestate", "edit", "update", "getteacher","getavailableteacher","dashboard","personaldata","teacherclass","currentaccount","message","activitie","removephoto","getPDF","info","deletall"],
    "block" =>["index","newbl", "create", "getall", "changestate", "edit", "update", "getblock"],
    "student" =>["index","info", "newst", "create", "getall", "changestate", "edit", "update", "getstudent","getPDF","dashboard","personaldata","explanation","activitie","currentaccount","message","notification","report","setsession","getbiograficdataPD","removephoto","creatpdff","getinfostudenttransf"],
    'worksituation'			    =>["getworksituation"],
    'areastudy'			    =>["getareastudy"],
    'grade'			    =>["getgrade"],
    'studentparent'			    =>["getstudentparent","getparent"],
    "registrationstudent" =>["index","newregs", "create","delete",'getPDF', "getall", "changestate", "edit", "update","newregisterstudent", "getregistrationstudent","getregistrationstudentAll","createmessage","getallbyidstudent","registrationstudentblock","getallbyidteacher","getregistrationstudentblock"],
    "content" =>["index","newco", "create", "getall", "changestate", "edit", "update", "getcontentAll","getcontentstudentAll", "getPDF"],
    "attendance" =>["index","newat", "create", "getall", "changestate", "edit", "update", "getattendance"],
    "didacticmaterial" =>["index","newdi", "create", "getall", "changestate", "edit", "update", "getdidacticmaterialAll","delete"],
    "message" =>["index","newme", "create", "getall", "changestate", "edit", "update", "getmessageAll","delete","getmessage","getallbystudent","getallbyblock","notification","getallbyid","getallbyidreceive","getallbyblockid","getregisterstudent","getteacherbyidreceive","findAllmessagebyid","getmessageblock","getcontentblock","getContent","getContentB"],
    "report" =>["index","newr", "create", "getall", "changestate", "edit", "update","delete","getallbyid","getPDF","getPDFbyid","getreportbyid"],
    'school'		=>['index', 'newser', 'create', 'getall', 'changestate', 'edit', 'update','getschool',"removephoto",'getschool','getavailableschool'],
    "activitiestudent" =>["index","newregs", "create","delete",'getPDF', "getall", "changestate", "edit", "update", "getactivitystudent","getactivitystudentAll","createmessage","getallbyidstudent","activitystudentactivity","getallbyidteacher","info","newregisterstudent"],
    
  );

  if(array_key_exists($controller, $controllers)){
    if(in_array($action, $controllers[$controller])){

      call($controller, $action);

    }else{
      if(isset($_POST['servicesession'])){
        $host = $_SERVER['HTTP_HOST'];
        $host = str_replace("\\", '', $host);
        header("Content-Type: application/json");
        http_response_code(200);
        print json_encode(array( 'code'=>404 ,'type'=>1, 'msg'=>"https://".$host)) ;die();
      }else{
        require_once('views/error404.php');die();
      }

    }
  }else{
    if(isset($_POST['servicesession'])){
      $host = $_SERVER['HTTP_HOST'];
      $host = str_replace("\\", '', $host);
      header("Content-Type: application/json");
      http_response_code(200);
      print json_encode(array( 'code'=>404 ,'type'=>2, 'msg'=>"https://".$host)) ;die();
    }else{
      require_once('views/error404.php');die();
    }
  }


}else{
    $controller = '';
    $action = '';
    $host = $_SERVER['HTTP_HOST'];
    $host = str_replace("\\", '', $host);
    $subdirectory = dirname($_SERVER['PHP_SELF']);
    $subdirectory = str_replace("\\", '', $subdirectory);
    $location = ''.$host.$subdirectory.'/';

    if(isset($_POST['servicesession'])){
      header("Content-Type: application/json");
      http_response_code(200);
      $host = $_SERVER['HTTP_HOST'];
      $host = str_replace("\\", '', $host);
      print json_encode(array( 'code'=>403 , 'msg'=>"https://".$host)) ;die();
    }else{

      header("Location: https://".$host);die();
    }
}

function call($controller, $action){
    if(file_exists('controllers/'.$controller.'Controller.php')){
      require_once('controllers/'.$controller.'Controller.php');
    } else{
      if(isset($_POST['servicesession'])){
        $host = $_SERVER['HTTP_HOST'];
        $host = str_replace("\\", '', $host);
        header("Content-Type: application/json");
        http_response_code(200);
        print json_encode(array( 'code'=>404 , 'type'=>3 , 'msg'=>"https://".$host)) ;die();
      }else{
        require_once('views/error404.php');die();
      }
    }
  if(substr($_GET['action'],3)=="get" || substr($_GET['action'],3)=="pdf" || substr($_GET['action'],4)=="show"){$GLOBALS['autoclosetransaction']=1;}
    switch ($controller) {
        //----Manager-----
        case 'home' :
            $controlador = new homeController();
        break;

        case 'distritct' :
            $controlador = new distritctController();
        break;
        case 'country' :
            $controlador = new countryController();
        break;
        case 'entity' :
              $controlador = new entityController();
        break;
        case 'entitytype' :
          $controlador = new entitytypeController();
        break;
        case 'role' :
          $controlador = new roleController();
        break;
        case 'admin' :
          $controlador = new adminController();
      break;
        case 'entitytypes' :
          $controlador = new entitytypesController();
        break;
        case 'distritct' :
          $controlador = new distritctController();
        break;
        case 'country' :
          $controlador = new countryController();
        break;
        case 'schoolyear' :
          $controlador = new schoolyearController();
        break;

        case 'discipline' :
          $controlador = new disciplineController();
        break;

        case 'classroom' :
          $controlador = new classroomController();
        break;
        case 'levelteaching' :
          $controlador = new levelteachingController();
        break;
        case 'activitie' :
          $controlador = new activitieController();
        break;

        case 'doctor' :
          $controlador = new doctorController();
        break;
        case 'nurse' :
          $controlador = new nurseController();
        break;
        case 'tsecurity' :
          $controlador = new tsecurityController();
        break;

        case 'physiotherapist' :
          $controlador = new physiotherapistController();
        break;
        case 'notify' :
          $controlador = new notifyController();
        break;

        
        case 'employee' :
          $controlador = new employeeController();
        break;

    case 'personaldata':
      $controlador = new personaldataController();
      break;

        case 'block' :
          $controlador = new blockController();
        break;

        case 'student' :
          $controlador = new studentController();
        break;

        case 'worksituation' :
          $controlador = new worksituationController();
        break;


        case 'areastudy' :
          $controlador = new areastudyController();
        break;

        case 'grade' :
          $controlador = new gradeController();
        break;

        case 'studentparent' :
          $controlador = new studentparentController();
        break;
        case 'registrationstudent' :
          $controlador = new registrationstudentController();
        break;
        case 'content' :
          $controlador = new contentController();
      break;
      case 'attendance' :
          $controlador = new attendanceController();
      break;
      case 'didacticmaterial' :
          $controlador = new didacticmaterialController();
      break;

      case 'message' :
      $controlador = new messageController();
      break;

      case 'report' :
      $controlador = new reportController();
      break;
      case 'school' :
        $controlador = new schoolController();
        break;

        case 'activitiestudent' :
          $controlador = new activitiestudentController();
          break;

        
      
        default:
        if(isset($_POST['servicesession'])){
          $host = $_SERVER['HTTP_HOST'];
          $host = str_replace("\\", '', $host);
          header("Content-Type: application/json");
          http_response_code(200);
          print json_encode(array( 'code'=>404 ,'type'=>4, 'msg'=>"https://".$host)) ;die();
        }else{
          require_once('views/error404.php');die();
        }
    }

    $controlador->$action();
  }

  ?>
