<?php
$GLOBALS['statusdb'] = 1;
$GLOBALS['autoclosetransaction']=0;
date_default_timezone_set('Europe/Lisbon');

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
       
          $host = $_SERVER['HTTP_HOST'];
          $host = str_replace("\\", '', $host);
          header("Content-Type: application/json");
          file_put_contents(' host.txt', var_export($host , TRUE));

          http_response_code(200);
          print json_encode(array( 'code'=>500 , 'msg'=>'ERROR_INTERN_DATABASE', 'url'=>"https://".$host)) ;die();

         exit(1);
      }
  });

require_once("libraries/other/common.php");
require_once("libraries/php-restclient/restclient.php");
  $st = Common::checkaccessmanager($_GET['API_KEY'], 3);
  if($st==null){
    http_response_code(203);
        print json_encode(array('id'=>0, 'code'=>203 , 'msg'=>'Non-Authoritative_Information')) ;die();
  }

  if($st->database_name==1){
    $GLOBALS['database_name'] = $_GET['db'];
  }else{

    if($st->state==0){
      http_response_code(203);
          print json_encode(array('id'=>0, 'code'=>203 , 'msg'=>'ACCOUNT_INACTIVE_CONTACT_US')) ;die();
    }

    $GLOBALS['integrate'] = $st->integration_client_master;
    $GLOBALS['integrate_op'] = $st->integration_master;

    $GLOBALS['updateinfo_cron'] = $st->updateinfo_cron;
    $GLOBALS['updateinfo_urlcron'] = $st->updateinfo_urlcron;

    $arrfunc = array();
    if($st->functionality!=null){
      foreach ($st->functionality as $key => $value) {
        array_push($arrfunc, $value->name);
      }
    }
    $database_name = $st->database_name;
    
    $GLOBALS['arrfunc'] = $arrfunc;
  }


  $controllers = array(
    
    'manager' => ['useractive', 'userblock', 'createcompany', 'carriers', 'deliverycharges', 'shippingcompany', 'advancemoney'],
    'company' => ['getcompany', 'getcompanynif','getbycompany']
);

if(array_key_exists($controller, $controllers)){
  if(in_array($action, $controllers[$controller])){

    call($controller, $action);

  }else{
    header("Content-Type: application/json");
    http_response_code(404);
    print json_encode(array( 'code'=>404 , 'msg'=>'pagenotfound')) ;die();
  }
}else{
  header("Content-Type: application/json");
  http_response_code(404);
  print json_encode(array( 'code'=>404 , 'msg'=>'pagenotfound')) ;die();
}

}else{
  $controller = '';
  $action = '';
  header("Content-Type: application/json");
  http_response_code(404);
    print json_encode(array( 'code'=>404 , 'msg'=>'pagenotfound')) ;die();
}





function call($controller, $action){
  require_once('api/'.$controller.'.php');
  if(substr($_GET['action'],3)=="get" || $_GET['action']=="separate" || substr($_GET['action'],6)=="search" || substr($_GET['action'],8)=="separate" || substr($_GET['action'],4)=="show"){$GLOBALS['autoclosetransaction']=1;}
  switch ($controller) {
    
    case 'manager':
      $controlador = new Manager();
    break;
    case 'company':
    $controlador = new  CompanyAPI();
  break;
    
    default:
    http_response_code(404);
      print json_encode(array( 'code'=>404 , 'msg'=>'pagenotfound')) ;die();
  }

  $controlador->$action();
}
?>
