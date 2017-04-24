<?php
require "vendor/autoload.php";
session_start();
$ramdom =  md5(uniqid(mt_rand(), true));
$opciones = [
    'cost' => 11,
    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
];
$token = password_hash($ramdom, PASSWORD_BCRYPT, $opciones);
$_SESSION['tkn_sg'] = $token;

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tecnosein Tecnologías - CRM</title>

    <!--    Scripts-->
    <script src=" web/js/jquery.js"></script>
    <script src="web/js/materialize.js"></script>
    <script src="web/js/jquery.fullscreen-min.js"></script>
    <script src="web/js/init.js"></script>
    <!--    <script src="js/jssip-0.7.8.js"></script>-->
    <!--    <script>JsSIP.debug.enable('*');</script>-->
    <script src="web/js/actions.js"></script>
    <!--    Styles-->
    <link rel="stylesheet" href="web/css/materialize.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="web/css/style.css">
</head>
<body>
<header>
    <div class="navbar-fixed">
        <nav>
            <div class="nav-wrapper indigo z-depth-2">
                <a href="#" class="brand-logo thin" style="margin-left: 20px">TecnoseinTLMK</a>
                <ul id="mainMenu" class="right hide-on-small-and-down">
                   <li class="right-align row hide-on-med-and-down">

</li>
                    
                </ul>
            </div>
        </nav>
    </div>
</header>
<main>
    <section id="splash">
       
            <div class="row">
<div class="col s12 m6 offset-m4 l6 offset-l4 ">
<h3 class="thin" style="padding-top: 150px">Tecnosein Tecnologías</h3>
<div class="row  col s12 offset-s1">
  <h5 class="condensed grey-text">Plataforma de llamadas</h5>
</div>

</div>

           <div id="login-page" class="row">
    <div class="col s3 offset-s4 card-panel">
      <form class="login-form">
        <div class="row">
          <div class="input-field col s12 offset-s6">           
            <i class="material-icons large">contact_phone</i>
           
          </div>
            <div class="col s12 center" > 
                <div id="error_show" class="red-text s12">
    
          </div>
                
            </div>
            
        </div>
        <div class="row margin">
          <div class="input-field col s12 offset-s2">
            <i class="material-icons prefix">account_circle</i>
            <input id="username" type="text" class="validate " style="padding-left: 10px; border-bottom: solid 1px white;">
            <label for="username" class="center-align">Username</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12  offset-s2">
            <i class="material-icons prefix">vpn_key</i>
            <input id="password" type="password" class="validate" style="padding-left: 10px; border-bottom: solid 1px white;">
            <label for="password">Password</label>
          </div>
        </div>
       
        <div class="row">
          <div class="input-field col s12 offset-s3">
            <a  id="login_btn" class="btn waves-effect waves-light col s12">Login</a>
           
          </div>
        </div>
        <div class="row">
         <input type="hidden" name="tkn" id="tkn" value="<?php echo $_SESSION['tkn_sg']; ?>" />
       <br> 
         
        </div>
          

      </form>
    </div>
  </div>   
                    
                         
      





</div>

       
    </section>
   
 
   
    
   
</main>
<footer>

</footer>
</body>
</html>
