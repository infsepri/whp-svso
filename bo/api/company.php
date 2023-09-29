<?php
 
require_once('models/Entity.php');
 
class CompanyAPI{
  private $homecontroller;


  public function __construct(){
    header("Access-Control-Allow-Orgin: *");
    header("Access-Control-Allow-Methods: *");
  }

 
  public function getcompany(){
    header("Content-Type: application/json");
    require_once("models/Company.php");
    new Company();  
    $idcompany = isset($_GET['id']) ? $_GET['id'] : 0 ;
    $company = Company::findOne($idcompany);
    if($company==null){
      $company = "";
    }
    http_response_code(200);
    print json_encode(array( 'code'=>200 , 'company'=>$company)) ;die();
  }

  public function getcompanynif(){
    header("Content-Type: application/json");
    require_once("models/Company.php");
    new Company();     

    $this->checkaccess();
    $idcompany = isset($_GET['nif']) ? $_GET['nif'] : 0 ;
    $company = Company::findbynif($idcompany);
    if($company==null){
      http_response_code(201);
      $company = "";
      print json_encode(array( 'code'=>201 , 'company'=>$company)) ;die();
    }
    

    http_response_code(200);
    print json_encode(array( 'code'=>200 , 'company'=>$company)) ;die();
  }

  public function getbycompany(){
    header("Content-Type: application/json");
    require_once("models/Company.php");
    new Company();     

    $this->checkaccess();
    $idcompany = isset($_GET['id']) ? $_GET['id'] : 0 ;
    $users = Company::findAllbycompany($idcompany);

    if($users==null){
      http_response_code(201);
      $users = "";
      print json_encode(array( 'code'=>201 , 'users'=>$users)) ;die();
    }
    

    http_response_code(200);
    print json_encode(array( 'code'=>200 , 'users'=>$users)) ;die();
  }

  private function checkaccess(){
    $md5 = md5('*whp2022#'. date('Y-m-d'));
    $pass = base64_encode($md5);
    if($pass != $_GET['API_KEY']){
      print json_encode(array('id'=>0, 'code'=>203 , 'msg'=>'Non-Authoritative_Information')) ;die();
    }
  }


}


?>
