<?php


namespace TLMK\Controller;
require "../../vendor/autoload.php";
use TLMK\Library\Users as Users;
use TLMK\Library\Auth as Auth;
session_start();

if (isset($_POST['action'])) {

	$authAction =  new Auth();
	$userAction =  new Users();
	if ($_POST['action'] == 'login')
	{ 
             
            if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['token']) ) 
          {
            
            // clean input data
            $usuario =  filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');
            $token =    filter_input(INPUT_POST, 'token');
            //verificacion token
            $result = -1;
            
            $estado = $authAction->getTries($usuario);
            
            if($estado==2)
            {
                
                 $ultimo_acceso = $_SESSION['last_intento'];
                           $fecha_actual = date('Y-m-d G:i:s');
                           $diferencia_fecha =   strtotime($fecha_actual) - strtotime($ultimo_acceso);
                           $segundos = abs($diferencia_fecha); 
                           $segundos = floor($segundos);
                           $minutos = $segundos/60;
                           if($minutos<15)
                           {

                             //usuario bloqueado
                             echo 2;
                             exit;
                           }
                           else
                           {

                                //liberar usuario de bloququeado
                               $response =  $authAction->releaseUser($usuario);
                               // devuelvo 0 para mostrar que  es error de datos insertados(usuario liberado)
                               $estado = 0;
                               
                           }
            }
            else 
            if ($_SESSION['token_sg'] = $_POST['token'] && $estado!= 2)
            {
                // check login 
               $result = $userAction->login($usuario, $password);
                
            }
            
            //$response = $authAction->login($result);
            if ($result != -1) {
            	# code...
                // regeneramos id de session para que no pueda ser utilizado por otra persona
                session_regenerate_id(true);
                $authAction->releaseUser($usuario);
            	echo "src/Views/main.php";
            }
            else
                if($estado!= 2)
            {
            	//intento fallido
            	$bloqueado = $authAction->getTries($usuario);
            	if ($bloqueado==2) {
            				# code...
            		   $ultimo_acceso = $_SESSION['last_intento'];
                           $fecha_actual = date('Y-m-d G:i:s');
                           $diferencia_fecha =   strtotime($fecha_actual) - strtotime($ultimo_acceso);
                           $segundos = abs($diferencia_fecha); 
                           $segundos = floor($segundos);
                           $minutos = $segundos/60;

                           if($minutos<15)
                           {

                             //usuario bloqueado
                             echo 2;
                           }
                           else
                           {

                                //liberar usuario de bloququeado
                               $response =  $authAction->releaseUser($usuario);
                               // devuelvo 0 para mostrar que  es error de datos insertados(usuario liberado)
                               echo $response;

                           }


            			}
                        else
                            if($bloqueado==0)

                        {
                            //error de datos usuario y password
                            echo 0;


                        }
                        else
                        {

                            //return -1 si existio algun problema al insertar datos
                            return $bloqueado;
                        }
            	

            }            
            
        }
	}
        
        else
            if($_POST['action'] == 'destroy')
            {
                $authAction->logout();                
            }

}