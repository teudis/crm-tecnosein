<?php


namespace TLMK\Library;
use TLMK\DB\Database as Database;
use TLMK\Library\Users as Users;

/*

getTries($username)
login($username)
releaseUser($username)

*/

class Auth
{


		//intentos fallidos realizados
	public function getTries($username)
	{

        // Seguridad en funtion
        if (isset($_SESSION['tkn_sg'])) 
        {
		 $sql = <<< SQL
		 SELECT `_id_user`, cant_intentos,login,bloqueado,last_intento  FROM seguridad_login WHERE _id_user=?
SQL;
        $db = new Database();   
        //busca usuario    
        $user_security = $db->getRow($sql, [$username]);
        
        if (!$user_security['_id_user'])
    	{
    		// insertar datos tabla seguridad_login ya que no existe
    		 $sql_user = <<< SQL
		 INSERT INTO seguridad_login(_id_user, cant_intentos, login, bloqueado)
		VALUES (?,?,?,?)
SQL;
		 $usuario = $username;
		 $cant_intentos = 1;
		 $login = 0;
		 $bloqueado = 0;		 
		 			
		$addUser_security = $db->insertRow($sql_user, [$usuario, $cant_intentos, $login, $bloqueado]);
        $db->disconnect();
        if ($addUser_security == 1) {
            return 0;
        } else {

            return -1;
        }
    		
    	}
    	else
    	{

    		// aumentar intentos o bloquear ya que existe
    		if ($user_security['cant_intentos'] < 3) {
    			# code...
    			$usuario = $username;
    			$cant_intentos = $user_security['cant_intentos'] + 1;
		 		$login = 0;
		 		$bloqueado = 0;
		 		if ($cant_intentos >= 3) {
		 			# code...
		 			$bloqueado = 1;
		 		}
		 		
		 		// update data 
		 		$update = <<< SQL
			    UPDATE seguridad_login SET cant_intentos=?, login=?, bloqueado=? WHERE `_id_user`=?
SQL;
       		   $db->updateRow($update,[$cant_intentos, $login, $bloqueado,$usuario]);
        	   $db->disconnect();
        	   return 0;
    		}
    		else
    		{
    			$_SESSION['last_intento'] = $user_security['last_intento'];
    			return 2;

    		}


    	}

    }
    else
        {

            header('Location: ../');
                exit;

        }

	}




		//Desbloquear usuario bloqueado
	public function releaseUser($username)
	{

        // Seguridad en funtion
        if (isset($_SESSION['tkn_sg'])) 
        { 
                $db = new Database(); 
                $usuario = $username;
                $cant_intentos = 0;
                $login = 0;
                $bloqueado = 0;              
                
                // update data 
                $update = <<< SQL
                UPDATE seguridad_login SET cant_intentos=?, login=?, bloqueado=? WHERE `_id_user`=?
SQL;
               $db->updateRow($update,[$cant_intentos, $login, $bloqueado,$usuario]);
               $db->disconnect();
               return 0;
         }
         else
         {

            header('Location: ../');
            exit;
         }

	}
        
        
        // destruir sessiones de usuarios
        public function logout ()
        {        
          if( $_SESSION['navegador'] === $_SERVER['HTTP_USER_AGENT'] && isset($_SESSION['tkn_sg']))
            {
                # # Destroy The Session

                if( $_SESSION['ip_address'] === $_SERVER['REMOTE_ADDR'] ){

                     //Des
                     $_SESSION = null;
                    session_destroy(); # Destroy The Session
                    session_unset(); # Unset The Session
                }
                else{
                    

                     header("Location: ../index.php");
                     exit;                   

                }
            }
            else
            {
                
                  # Destroy The Session
                  $_SESSION = null;
                  session_destroy(); # Destroy The Session
                  session_unset(); # Unset The Session
                 header("Location: ../index.php");
                 exit; 
            }             
          
            
        }




}

