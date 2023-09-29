
<?php
/**
*
* @package    personaldata
* @package    Controller
* @copyright  2022 Sepri
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

require_once("models/Adminrole.php");
  
class personaldataController{

	public function __construct() {
		    $this->admin = new Admin();
    $this->country       = new Country();
    $this->district      = new District();
 
    $this->adminrole      = new Adminrole();
    $this->entity = new Entity();
    $this->state = array(0=>"disable", 1=>"enable");
        $this->genre = array(0=>"M", 1=>"F");
        $this->allowance  = array(0=>"no", 1=>"yes");
				$this->maritalstatus  = array(1=>"single",2=>"married",3=>"partnership",4=>"divorced",5=>"widowed",6=>"late");
				$this->dayweek = array(1=>"sunday", 2=>"monday", 3=>"tuesday", 4=>"wednesday", 5=>"thursday", 6=>"friday", 7=>"saturday");
    }

				 public function index(){
                    Accesscontrol::beforeActionSession("management_personaldata", 1,[1]);
                        //_______________________________________________________________
                        $identity = $_SESSION['entity']['identity'];
                        $usertype= $_SESSION['entity']['userT'];//---START Verificação
                        $typuser=$usertype[$_SESSION['defaultprofile']];
                        if(isset($typuser)&&  $typuser!=1){
                        if($typuser==2){$service=false;
                            $localization= Common::get_client_ip();
                            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                            $line = array("Data"=>date('Y-m-d H:i:s'),"Localização"=>$localization, "Nº Utilizador"=>$identity, "Diretorio"=>$actual_link);
                            Common::notifyError($line);
                            Common::response($service, "?controller=colab&action=dashboard&alert-danger=not_permit_not_have_permission", array('id'=>0, 'code'=>201 , 'msg'=>'not_permit_not_have_permission'));die();
                    
                        }
                        }//---END Verificação
                    //_______________________________________________________________ 
                            $aux=isset($_GET['a']) ? $_GET['a'] : null;
                            if($aux==1){$fieldblock=false;}else{$fieldblock=true;}
						$service=false;

			     
						
				    $service=false;
                    $admin = admin::findbyadmin($identity);
               
                    
                    $country= Country::findbyid($admin->idcountry);
                    $district= District::findbyid($admin->iddistrict);
                    $identifytypes= Identifytypes::findbyid($admin->identitycard);
                    if(isset($typuser)&&  $typuser==1){
                    $adminrole = Adminrole::findbyadminid($identity);
                   
                    $role = array();
                    if($adminrole!=null){
                         foreach($adminrole as $k=>$value) {
                                            
                                 array_push($role,$value );
                         }
                     }
                    }
						if($admin==null){
				         header("Location: ?controller=home&action=index");die();
				        }
                        
                        
                               $breadcrumbs = array(
                                    array("icon"=>"clip-keyboard", "msg"=>"MENU_PERSONAL_DATA"),
                                    array("active"=>1, "msg"=>"MENU_EDIT", "text"=>$admin->name)
                                );
				     $view = 'views/manager/user/edit.php';
				    require_once('views/layout/_layout.php');
                 }
                 

    public function changepass() {
    Accesscontrol::beforeActionSession();
                    //_______________________________________________________________
    $identity = $_SESSION['entity']['identity'];
    $usertype= $_SESSION['entity']['userT'];//---START Verificação
    $typuser=$usertype[$_SESSION['defaultprofile']];
    if(isset($typuser)&&  $typuser!=1){
    if($typuser==2){$service=false;
        $userdata = entity::findbyid($identity);
        if($userdata==null){
            header("Location: ?controller=admin&action=dashboard");die();
        }
       $breadcrumbs = array(
            array("icon"=>"clip-settings", "msg"=>"MENU_PERSONAL_DATA"),
            array("active"=>1, "msg"=>"MENU_EDIT", "text"=>$userdata->name)
        );

    }if($typuser==3){ $service=false;
      $userdata = entity::findbyid($identity);
      if($userdata==null){
          header("Location: ?controller=colab&action=dashboard");die();
      }
       $breadcrumbs = array(
            array("icon"=>"clip-settings", "msg"=>"MENU_PERSONAL_DATA"),
            array("active"=>1, "msg"=>"MENU_EDIT", "text"=>$userdata->name)
        );
    }
    }else{
    
        $userdata = admin::findbyadmin($identity);
      if($userdata==null){
          header("Location: ?controller=home&action=index");die();
      }
       $breadcrumbs = array(
            array("icon"=>"clip-keyboard", "msg"=>"MENU_PERSONAL_DATA"),
            array("active"=>1, "msg"=>"MENU_EDIT", "text"=>$userdata->name)
        );
    }
    

    
    //---END Verificação
   //_______________________________________________________________ 
               

               
                $view = 'views/resetpassword/edit.php';
                require_once('views/layout/_layout.php');

                }


            public function update() {
         
                Accesscontrol::beforeActionSession();
            
                $identity = $_SESSION['entity']['identity'];
                $usertype= $_SESSION['entity']['userT'];//---START Verificação
                $typuser=$usertype[$_SESSION['defaultprofile']];
               
                    $service = false;
                    if( isset($_POST['servicesession']) ){$service = true;}


                    $entityPost = isset($_POST['entity']) ? $_POST['entity'] : array();
                    session_write_close();
                    if(empty($entityPost) || empty($entityPost['identity'])){
                        Common::response($service, "?controller=home&action=index&alert-danger=ERROR_DATA_PARAMS", array('id'=>0, 'code'=>201 , 'msg'=>'ERROR_DATA_PARAMS'));die();
                    }

                        $aux1="KSNbP6fA4LWWZuH1nUHBillKKJAl8uip";
                        $aux2="KSNbP6fA4LWWZuH1nUHBillKKJAl8uip";
                       $password2=$entityPost['password'];
                       $passwordold= isset($entityPost['passwordold'])? $entityPost['passwordold'] : null ;
                     
                        //$password_hash =Common::encrypt_decrypt("encrypt", $password2,$aux1,$aux2);
                        //$password_hash_old =Common::encrypt_decrypt("encrypt",$passwordold,$aux1,$aux2);
                        $user = Entity::findbyid($identity);
                        /*
                        $this->entity = new Entity();
                        $this->entity->set('identity', $identity);
                        $this->entity->set('password_hash', $password_hash);
                        $status = $this->entity->updatepassn();*/
                       // $status = Common::changepassword($user, $password_hash, $token);


                       $usercompany = Common::findusercompany($user->api); 


                       if(isset($typuser)&&  $typuser!=1){
                        $opt=0;
                       }else{
                        $opt=1;
                       }
                      

                       //$status = Common::changepassword($user, $password_hash);
                       $status = Common::changepassword($user, $password2,$passwordold,$opt);
                            if($status==false){ 
                                $GLOBALS['statusdb'] = 0;
                                //Common::response($service, "?controller=personaldata&action=changepass&alert-danger=PASSWORD_UPDATE_INSUCCESS", array('id'=>0, 'code'=>201 , 'msg'=>'ERROR_SAVE_DATA'));die();
                                Common::response($service, "?controller=personaldata&action=changepass&alert-danger=PASSWORD_UPDATE_INSUCCESS", array('id' => 0, 'code' => 201, 'msg' => 'PASSWORD_UPDATE_INSUCCESS'));
                            }else{
                     
                                //Common::response($service, "?controller=personaldata&action=changepass&alert-success=PASSWORD_UPDATE_SUCCESS", array('id'=>0, 'code'=>200, "newUrl"=>1 , 'msg'=>'PASSWORD_UPDATE_SUCCESS'));die();

                                //Common::response($service, "?controller=personaldata&action=changepass&alert-success=PASSWORD_UPDATE_SUCCESS", array('code'=>200 , 'newUrl'=>"1"));die();
                                Common::response($service, "?controller=personaldata&action=changepass&alert-success=PASSWORD_UPDATE_SUCCESS", array('id' => 0, 'code' => 200, 'msg' => 'PASSWORD_UPDATE_SUCCESS'));
                                die();
                           
                            }
                        



                            
            }

                 
}