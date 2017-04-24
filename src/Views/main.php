<?php

namespace TLMK\Views;
require "../../vendor/autoload.php";
use TLMK\Library\TaskEditor as TaskEditor;  
session_start();



// tiempo de inactividad
// Establecer tiempo de vida de la sesión en segundos 10 minutos es este caso
$inactividad = 600;
// Comprobar si $_SESSION["timeout"] está establecida
if(isset($_SESSION["timeout"])){
    // Calcular el tiempo de vida de la sesión (TTL = Time To Live)
    $sessionTTL = time() - $_SESSION["timeout"];
    if($sessionTTL > $inactividad){
        session_unset();
        session_destroy();
        header("Location: ../");
    }
}
// El siguiente key se crea cuando se inicia sesión
$_SESSION["timeout"] = time();

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tecnosein Tecnologías - CRM</title>

    <!--    Scripts-->
    <script src='../../web/js/moment.min.js'></script>
    <script src="../../web/js/jquery.js"></script>
    <script src="../../web/js/materialize.js"></script>
    <script src="../../web/js/jquery.fullscreen-min.js"></script>
    <script src="../../web/js/init.js"></script>
    <script src='../../web/js/fullcalendar.min.js'></script>    
    <script src="../../web/js/picker.js"></script>
    <script src="../../web/js/picker.time.js"></script>
    
    <!--    <script src="../js/jssip-0.7.8.js"></script>-->
    <!--    <script>JsSIP.debug.enable('*');</script>-->
    <script src="../../web/js/actions.js"></script>
    <script src="../../web/js/calendar.js"></script>
    <!--    Styles-->
    <link rel="stylesheet" href="../../web/css/materialize.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href='../../web/css/fullcalendar.css' rel='stylesheet' />
    <link href='../../web/css/fullcalendar.print.css' rel='stylesheet' media='print' />
    <link rel="stylesheet" href="../../web/css/style.css">
    <link rel="stylesheet" href="../../web/css/default.time.css">
</head>
<body>
<header>
    <div class="navbar-fixed">
        <nav>
            <div class="nav-wrapper indigo z-depth-2">
                <a href="#" class="brand-logo thin" style="margin-left: 20px">TecnoseinTLMK</a>
                <ul id="mainMenu" class="right hide-on-small-and-down">
                    <?php if (isset($_SESSION['username']) && isset($_SESSION['navegador']) &&  isset($_SESSION['tkn_sg']) && isset($_SESSION['level']) ) {
                        if ($_SESSION['level'] == 1 && isset($_SESSION['ip_address']) ) {
                            echo "<li id='endCallContainer' class='red accent-2 hide'><a id='hungDown'
class='btn-flat waves-effect waves-light white-text'
href='#'><i class='material-icons' style='margin-top: -16px'>call_end</i></a></li>";
                            echo "<li class='tooltipped' data-tooltip='Agregar usuario'>
<a id='addUser' class='btn-flat waves-effect waves-light white-text modal-trigger'
href='#addUserModal'><i class='material-icons' style='margin-top: -16px'>person_add</i></a></li>";
                            echo "<li class='tooltipped' data-tooltip='Lista de usuarios'>
<a id='userList' class='btn-flat waves-effect waves-light white-text modal-trigger'
href='#userListModal'><i class='material-icons' style='margin-top: -16px'>people</i></a></li>";
                            echo "<li class='tooltipped' data-tooltip='Asignar números'>
<a id='assignTask' class='btn-flat waves-effect waves-light white-text modal-trigger'
href='#taskListModal'><i class='material-icons' style='margin-top: -16px'>assignment_ind</i></a></li>";
                        }
                        echo "<li id='saveProspect' class='red accent-2 hide tooltipped' data-tooltip='Guardar'>
<a id='saveProspectData' class='btn-flat waves-effect waves-light white-text'
href='#'><i class='material-icons' style='margin-top: -16px'>save</i></a></li>";
                        echo "<li class='tooltipped' data-tooltip='Pantalla completa'>
<a id='fullScreenMode' class='btn-flat waves-effect waves-light white-text'>
<i class='material-icons' style='margin-top: -16px'>fullscreen</i>
</a>
</li>";
                        echo "<li class='tooltipped' data-tooltip='Conferencia con formador'>
    <div class=\"switch\">
    <label class='white-text'>
      <input id='super_conference' type=\"checkbox\">
      <span class=\"lever\"></span>
    </label></div></li>";

    echo "<li class='tooltipped' data-tooltip='Calendario'>
<a id='showcalendar' class='btn-flat waves-effect waves-light white-text modal-trigger'
href='#calendarModal'><i class='material-icons' style='margin-top: -16px'>assignment</i></a></li>";
                        echo "<li>
<a id='username_menu' class='dropdown-button btn-flat white-text' data-activates='usermenu'>{$_SESSION['username']}</a>
<ul id='usermenu' class='dropdown-content'>
<li><a id='profile' href='#singleProfile' class='modal-trigger'>Perfil</a></li>
<li class='divider'></li>
<li><a class='destroySession'>Salir</a></li>
</ul>
</li>";
                    } else {
                        
                         header('Location: ../../');
                        exit;
                    } ?>
                </ul>
            </div>
        </nav>
    </div>
</header>
<main>
    <section id="splash" <?php if (isset($_SESSION['username'])) {
        echo 'class="hide"';
    } ?>>
        <?php if (!isset($_SESSION['username']) && $_SESSION['tkn_sg']) {

            header('Location: ../../');
                    exit;
            
            
        }

        ?>
    </section>
    <section id="mainContent">
        <?php if (isset($_SESSION['_id']) && isset($_SESSION['navegador'])) {
            echo "<span id='userGlobalId' class='hide'>{$_SESSION['_id']}</span>";
            echo '
        <div class="row">
            <div class="col s12 m8 l8" style="padding-right: 0; padding-left: 0; min-height: 700px;">
                <iframe src="scripts/scripts.php" width="100%" height="100%" frameborder="0"
                        class="grey lighten-1" style="min-height: 700px"></iframe>
            </div>
            <div class="col hide-on-small-only m4 l4" style="padding-left: 5px; padding-right: 5px">
            <ul class="tabs">
            <li class="tab col s3"><a href="#call1" class="active">1</a></li>
            <li class="tab col s3"><a id="call2List" href="#call2">2</a></li>
            <li class="tab col s3"><a id="call3List" href="#call3">3</a></li>
            <li class="tab col s3"><a id="callSalesList" href="#callSales">ventas</a></li>
</ul>
<div id="call1" class="col s12">
        ';
        }
        ?>
        <ul id="taskItemList" class="collapsible" data-collapsible="accordion">
            <!--                Here goes task list-->
            <?php if (isset($_SESSION['_id']) && isset($_SESSION['navegador'])  ) {
                $taskEditor = new TaskEditor();
                $taskList = $taskEditor->getUnfinished($_SESSION['_id']);
                if (count($taskList) >= 1) {
                    foreach ($taskList as $item) {
                        $attempts = '';
                        if ($item['attempts'] > 0) {
                            for ($attempt = 1; $attempt <= $item['attempts']; $attempt++) {
                                $attempts .= '<i class="material-icons teal-text">access_time</i>';
                            }
                            echo "<li id='{$item['_id']}' data-attempts='{$item['attempts']}'>
<div class='collapsible-header'>{$attempts}
<a id='{$item['_id']}' class='collection-item numberListItem'>{$item['telefono']} {$item['nombreClase']}</a>
<a id='{$item['_id']}' class='secondary-content dropdown-button' data-activates='numberTaskActions'>
<i class='material-icons truncate'>arrow_drop_down_circle</i></a></div></li>";
                        } else {
                            echo "<li id='{$item['_id']}' data-attempts='{$item['attempts']}'>
<div class='collapsible-header'>
<a id='{$item['_id']}' class='collection-item numberListItem'>{$item['telefono']} {$item['nombreClase']}</a>
<a id='{$item['_id']}' class='secondary-content dropdown-button' data-activates='numberTaskActions'>
<i class='material-icons truncate'>arrow_drop_down_circle</i></a></div></li>";
                        }
                    }

                }
            }
            ?>
        </ul>
        <ul id='numberTaskActions' class='dropdown-content'></ul>
        </div>
        <?php if (isset($_SESSION['_id']) && isset($_SESSION['navegador']) ) {
            echo '<div id="call2" class="col s12">
<ul id="secondTaskItemList" class="collapsible" data-collapsible="accordion">
</ul>
</div>';
            echo '<div id="call3" class="col s12">
<ul id="thirdTaskItemList" class="collapsible" data-collapsible="accordion">
</ul></div>';
            echo '<div id="callSales" class="col s12">Listado proyectos vendidos</div>';
        } ?>
        </div>
    </section>
    <section>
        <audio id="remoteAudio" class="hide"></audio>
        <audio id="localAudio" class="hide"></audio>
        <iframe id="conferenceFrame" class="hide"></iframe>
    </section>
    <section id="taskFormSection" class="hide">
        <div id="taskContainer" class="collapsible-body">
            <form class="col s12">
                <div class="input-field col s12 m12 l12">
                    <i class="material-icons prefix indigo-text">language</i>
                    <input value="http://" id="prospectWeb" type="text" class="validate">
                    <label for="prospectWeb">Web</label>
                </div>
                <div class="input-field col s12 m12 l12">
                    <i class="material-icons prefix indigo-text">mail</i>
                    <input id="prospectMail" type="email" class="validate">
                    <label for="prospectMail">Email</label>
                </div>
                <div class="input-field col s12 m12 l12">
                    <i class="material-icons prefix indigo-text">person</i>
                    <input id="prospectName" type="text" class="validate">
                    <label for="prospectName">Nombre</label>
                </div>
                <div class="input-field col s12 m12 l12">
                    <i class="material-icons prefix indigo-text">person</i>
                    <input id="prospectLastName" type="text" class="validate">
                    <label for="prospectLastName">Apellido</label>
                </div>
                <div class="input-field col s12 m12 l12">
                    <i class="material-icons prefix indigo-text">domain</i>
                    <input id="prospectEmpresa" type="text" class="validate">
                    <label for="prospectEmpresa">Empresa</label>
                </div>
                <div class="input-field col s12 m12 l12">
                    <i class="material-icons prefix indigo-text">contact_phone</i>
                    <input id="prospectAltPhone" type="text" class="validate">
                    <label for="prospectAltPhone">Teléfono</label>
                </div>
                <div class="input-field col s12 m8 l8">
                    <i class="material-icons prefix indigo-text">event</i>
                    <input id="appointment" type="date" class="datepicker">
                </div>
                <div class="input-field col s12 m4 l4">
                    <input id="appointmentHour" type="time">
                </div>
                <div class="input-field col s12 m12 l12">
                    <i class="material-icons prefix indigo-text">description</i>
                    <textarea id="prospectNotes" class="materialize-textarea"></textarea>
                    <label for="prospectNotes">Notas</label>
                </div>
            </form>
        </div>
    </section>
    <section id="modalMenus">
        <!--        Profile Modal-->
        <div id="singleProfile" class="modal modal-fixed-footer">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="card-panel center-align indigo" style="padding-top: 10px;padding-bottom: 10px">
                        <span class="white-text" style="font-size: larger;">
                            <?php echo "{$_SESSION['name']}"; ?>
                        </span>
                        &nbsp;
                        <span style="font-size: large">Móvil: <b>626475051</b>
                            &nbsp;
                            &nbsp;
                        <span style="font-size: large" class="white-text">Correo:
                            hmartinez_freelance@tecnosein.com</span>
                    </div>
                </div>
            </div>
            <div class="row center">
                <span class="accent-1">Comisiones Noviembre 2015</span>
                <br> <span>(Comisiones de la base de datos de número Genéricos por sectores)</span>
            </div>
            <div class="row">
                <table id="profileCommission" class="striped centered">
                    <thead>
                    <tr class="indigo-text">
                        <th data-field="stage">Tramo</th>
                        <th data-field="perCommission">% Comisión</th>
                        <th data-field="price">Facturación (sin IVA)</th>
                        <th data-field="totalCommission">Comisión freelance</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>de 0 € a 1000 €</td>
                        <td>25%</td>
                        <td>0.00€</td>
                        <td>0.00€</td>
                    </tr>
                    <tr>
                        <td>de 1001 € a 2000 €</td>
                        <td>35%</td>
                        <td>0.00€</td>
                        <td>0.00€</td>
                    </tr>
                    <tr>
                        <td>de 2000 € en adelante</td>
                        <td>50%</td>
                        <td>0.00€</td>
                        <td>0.00€</td>
                    </tr>
                    </tbody>
                </table>
                <h5 class="right" style="padding-right: 10px">Total Comisiones: 0.00 EUR</h5>
            </div>
            <div class="row center">
                <hr>
                <span class="grey-text">Comisiones de clientes facilitados por Tecnosein (inactivo)</span>
            </div>
            <div class="row grey-text">
                <div class="col s12 m2 l2">
                    <blockquote>
                        No acumulable a los tramos de comisiones de la base genérica.
                    </blockquote>
                </div>
                <div class="col s12 m10 l10">
                    <table id="profileCommission" class="striped centered">
                        <thead>
                        <tr class="grey-text">
                            <th data-field="stageT">Tramo</th>
                            <th data-field="perCommissionT">% Comisión</th>
                            <th data-field="priceT">Facturación (sin IVA)</th>
                            <th data-field="totalCommissionT">Comisión</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>0 €-</td>
                            <td>%</td>
                            <td>0.00€</td>
                            <td>0.00€</td>
                        </tr>
                        <tr>
                            <td> - EUR</td>
                            <td>%</td>
                            <td>0.00€</td>
                            <td>0.00€</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- task Modal -->
        <div id="userListModal" class="modal">
            <div class="modal-content">
                <span class="black-text text-accent-4"><b>Usuarios</b></span>

                <div class="row">
                    <table class="striped responsive-table">
                        <thead class="indigo-text">
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Detalles</th>
                        </tr>
                        </thead>
                        <tbody id="userListTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="taskListModal" class="modal">
            <div class="modal-content">
                <span class="text-accent-4"><b>Tareas</b></span>

                <div class="row">
                    <form class="col s12 m12 l12" style="padding: 0">
                        <div class="row">
                            <div class="input-field col s8 m4 l4">
                                <select id="listUser" class="browser-default"></select>
                            </div>
                            <div class="input-field col s8 m4 l4">
                                <select id="listClass" class="browser-default"></select>
                            </div>
                            <div class="input-field col s3 m1 l1" style="padding: 0">
                                <input id="numbLimit" type="number" class="validate" value="10">
                                <label for="numbLimit">Límite</label>
                            </div>
                            <div class="col s3 m3 l3 center-align" style="margin-top: 15px;">
                                <a id="queryNumber" class="btn waves-effect waves-light red accent-2 white-text">
                                    <i class="material-icons">refresh</i></a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <table class="striped responsive-table">
                        <thead class="indigo-text">
                        <tr>
                            <th>Número</th>
                            <th>Clasificación</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="listResults"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="addUserModal" class="modal">
            <div class="modal-content">
                <span class=" black-text text-accent-4"><b>Agregar Usuario</b></span>

                <div class="row">
                    <form id="signup_form" class="col s12">
                        <div class="row">
                            <div class="input-field col s12 m6 l6">
                                <i class="material-icons prefix indigo-text">account_circle</i>
                                <input id="username_add" type="text" class="validate">
                                <label for="username_add">Nombre de Usuario</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <i class="material-icons prefix indigo-text">perm_identity</i>
                                <input id="fullname" type="text" class="validate">
                                <label for="fullname">Nombre Completo</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <i class="material-icons prefix indigo-text">vpn_key</i>
                                <input id="pass_add" type="password" class="validate">
                                <label for="pass_add">Contraseña</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <i class="material-icons prefix indigo-text">phone</i>
                                <input id="phone_add" type="tel" class="validate">
                                <label for="phone_add">Teléfono</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <i class="material-icons prefix indigo-text">mail</i>
                                <input id="email_add" type="email" class="validate">
                                <label for="email_add">Correo electrónico</label>
                            </div>
                            <div class="col s12 m6 l6 center-align">
                                <a id="addUserBtn"
                                   class="btn-flat waves-effect waves-light red accent-2 white-text"
                                   style="margin-top: 15px"><i class="material-icons large">save</i></a>
                            </div>
                        </div>
                    </form>
                    <div id="addUserResponseContainer" class="row"></div>
                </div>
            </div>
        </div>

        <!--modal showcalendar --!-->

         <div id="calendarModal" class="modal">
            <div class="modal-content">
                <span class=" black-text text-accent-4"><b>Calendario de Eventos</b></span>

                <div class="row">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>


        <!--Modal add event --!-->

<div id="new_event" class="modal">
            <div class="modal-content">
                <span class="text-accent-4"><b>Adicionar Evento</b></span>
                <br>
                <div class="row">
                    <form id="event_form" class="event_form" class="col s12">
                        <div class="row">
                            <div class="input-field col s12 m12 l12">
                                 <i class="material-icons prefix">info</i>
                                <input id="titulo_evento" name="titulo_evento" type="text" class="validate">
                                <label for="titulo">Nombre Evento</label>
                            </div>
                          <div class="input-field col s12 m12 l12">
                         
                          <i class="material-icons prefix">comment</i>
                        
                            <textarea id="descripcion"  name="descripcion" class="materialize-textarea"></textarea>
                            <label for="descripcion">Descripcion</label>
                          </div>                         
                            <div class="input-field col s12 m12 l12">   
                            <i class="material-icons prefix">assignment </i>    
                                <input id="fecha" name="fecha" type="text" class="validate">                                
        
                                
                            </div>                           
                           <div class="input-field col s12">
                            <a  id="addevent_btn" class="btn waves-effect waves-light col s12">Crear evento</a>                        
                          </div>
                        </div>
                    </form>
                  
                </div>
            </div>
        </div>

        <!--Modal edit event --!-->

        <div id="editevent" class="modal">
            <div class="modal-content">
                <span class="text-accent-4"><b>Datos Evento</b></span>
                <br>
                <div class="row">
                    <form id="edit_event_form" class="edit_event_form" class="col s12">
                        <div class="row">
                            <div class="input-field col s12 m12 l12">
                                 <i class="material-icons prefix">info</i>
                                <input id="edit_titulo_evento" name="titulo_evento" type="text" class="validate" value="">
                                
                            </div>
                          <div class="input-field col s12 m12 l12">
                         
                          <i class="material-icons prefix">comment</i>
                        
                            <textarea id="edit_descripcion"  name="edit_descripcion" class="materialize-textarea" value="" ></textarea>
                            
                          </div>                         
                            <div class="input-field col s12 m12 l12">   
                            <i class="material-icons prefix">assignment </i>    
                                <input id="edit_fecha" name="edit_fecha" type="text" class="validate">                                  
        
                                
                            </div>                           
                           <div class="input-field col s12">
                            <a  id="editevent_btn" class="btn waves-effect waves-light col s12">Update evento</a>                          
                          </div>
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