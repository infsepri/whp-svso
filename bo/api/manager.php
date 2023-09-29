<?php

class Manager{

  public function __construct(){
    header("Access-Control-Allow-Orgin: *");
    header("Access-Control-Allow-Methods: *");

    $this->checkaccess();
  }


  public function useractive(){
    header("Content-Type: application/json");
    $hash = $_GET['hash']; 
      
    require_once('models/Entity.php');
    $entity = new Entity();
    $user = Entity::findbyidmange($hash);

    if($user==null){
      print json_encode(array( 'code'=>201 , 'msg'=>'USER_NOT_FOUND')) ;die();
    }
    $entity->set('api',$hash);
    $entity->set('statelogin', Entity::getentityAtive());
    $changestatus=$entity->changestatus();

    if($changestatus==true){
      require_once("conf/configEmail.php");
      require_once("conf/templateEmail/activeUser.php");
      $mail = configEmail("Admin");
      $email = $user->email;
      mailer($email,$mail);
      print json_encode(array( 'code'=>200 , 'msg'=>'SUCESS_ACTIVE_USER')) ;die();

    }else{
      print json_encode(array( 'code'=>201 , 'msg'=>'ERROR_ACtive_USER')) ;die();
    }
  }




  public function userblock(){
    header("Content-Type: application/json");

     
    $api = $_GET['hash'];
  
    
    require_once('models/Entity.php');
    $entity = new Entity();
    $user = Entity::findbyidmange($api);
    
    if($user==null){
      print json_encode(array( 'code'=>201 , 'msg'=>'USER_NOT_FOUND')) ;die();
    }
    $entity->set('api',$api);
    $entity->set('statelogin', Entity::getentityWaitActive());
    $changestatus=$entity->changestatus();
    
    if($changestatus==true){
      print json_encode(array( 'code'=>200 , 'msg'=>'SUCESS_ACTIVE_USER')) ;die();

    }else{
      print json_encode(array( 'code'=>201 , 'msg'=>'ERROR_ACtive_USER')) ;die();
    }
  }


  private function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

  public function createcompany(){

    header("Content-Type: application/json");
    require_once('controllers/homeController.php');
    $homeController = new HomeController();
    $service=true;
    $param = $_POST['param'];
    $param = json_decode($param);


    

    $pass = $this->generateRandomString(10);


$resetPasswordToken = null;
        if(isset($param->passwordResetToken)){
          $resetPasswordToken = $param->passwordResetToken;
        }

      if($param->createsepriaccount==1){

        $user_1 = array("name"=>$param->user_name,     "email"=>$param->user_email,   "api"=>$param->api, "resetPasswordToken"=>$resetPasswordToken);
        $user_2 = array("name"=>$param->namesepri,    "email"=>$param->emailsepri,   "api"=>$param->apisepri);

        $user = array($user_1, $user_2 );

      }else{
        $user = array(array("name"=>$param->user_name,     "email"=>$param->user_email,   "api"=>$param->api, "resetPasswordToken"=>$resetPasswordToken));

      }

    $company = array("name"=>$param->company_name, "email"=>$param->company_email, "nif"=>$param->company_nif, "conservatory"=>$param->company_conservatory, "registernumber"=>$param->company_registernumber, "address"=>$param->company_address, "postalcode"=>$param->company_postalcode, "locality"=>$param->company_locality, "idcountry"=>$param->company_idcountry, "phone"=>$param->company_phone);

    $homeController->createcompany($service, $user, $company);
  }



  private function checkaccess(){
    $md5 = md5('*whp2022#'.date('Y-m-d'));
    $pass = base64_encode($md5);
    if(isset($_GET['API_KEY'])){
      if($pass != $_GET['API_KEY']){
        print json_encode(array('id'=>0, 'code'=>203 , 'msg'=>'Non-Authoritative_Information')) ;die();
      }
    }else{
      print json_encode(array('id'=>0, 'code'=>203 , 'msg'=>'Non-Authoritative_Information')) ;die();
    }

  }


}


?>
