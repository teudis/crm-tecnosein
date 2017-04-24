<?php


namespace TLMK\Library;
require "../../vendor/autoload.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
//print_r($_POST);
if (isset($_POST['action'])) {
    $taskEditor = new TaskEditor();

    if ($_POST['action'] == 'unfinished') {
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
    if ($_POST['action'] == 'saveCall') {
        $appointment = date('Y-m-d h:i:s', strtotime($_POST['appointment']));
        $results = $taskEditor->saveCall($_POST['id_task'], $_POST['web'], $_POST['email'], $_POST['clientName'],
            $_POST['clientLastName'], $_POST['firmName'], $_POST['altPhone'], $_POST['idResults'],
            $appointment, $_POST['notes']);
        if ($results) {
            echo "ok";
        }
    }
    if ($_POST['action'] == 'getCallResults') {
        $results = $taskEditor->callResults();
        foreach ($results as $item) {
            echo "<li><a id='{$item['_id']}' class='callResultAction' style='width: auto'>{$item['resultado']}</a></li>";
        }

    }
    if ($_POST['action'] == 'loadTasks') {
        $taskList = $taskEditor->getUnfinished($_POST['id_User']);
        foreach ($taskList as $item) {
            echo "<li id='{$item['_id']}'><div class='collapsible-header'>
<a id='{$item['_id']}' class='collection-item numberListItem'>{$item['telefono']} {$item['nombreClase']}</a>
<a id='{$item['_id']}' class='secondary-content dropdown-button' data-activates='numberTaskActions'>
<i class='material-icons truncate tooltipped' data-tooltip='Acción'>arrow_drop_down_circle</i></a></div></li>";
        }

    }
    if ($_POST['action'] == 'getNextCall') {
        $callType = $_POST['callType'];
        $userID = $_POST['userID'];
        $taskList = $taskEditor->getNextCalls($callType, $userID);
        foreach ($taskList as $item) {
            echo "<li id='{$item['_id']}'><div class='collapsible-header'>
<a id='{$item['_id']}' class='collection-item numberListItem'>{$item['telefono']} {$item['clientName']}</a>
<a id='{$item['_id']}' class='secondary-content dropdown-button' data-activates='numberTaskActions'>
<i class='material-icons truncate tooltipped' data-tooltip='Acción'>arrow_drop_down_circle</i></a></div></li>";
        }
    }

    if ($_POST['action'] == 'getProspectDetails') {
        $prospectDetails = $taskEditor->getProspectDetails($_POST['id_task']);
        $appointment = explode(' ',$prospectDetails['appointment']);
        echo "<div id=\"taskContainer{$prospectDetails['_id']}\" class=\"collapsible-body\">
            <form class=\"col s12\">
                <div class=\"input-field col s12 m12 l12\">
                    <i class=\"material-icons prefix indigo-text\">language</i>
                    <input value=\"{$prospectDetails['web']}\" id=\"prospectWeb{$prospectDetails['_id']}\" type=\"text\" class=\"validate\">
                    <label for=\"prospectWeb\" class='active'>Web</label>
                </div>
                <div class=\"input-field col s12 m12 l12\">
                    <i class=\"material-icons prefix indigo-text\">mail</i>
                    <input value='{$prospectDetails['email']}' id=\"prospectMail{$prospectDetails['_id']}\" type=\"email\" class=\"validate\">
                    <label for=\"prospectMail\" class='active'>Email</label>
                </div>
                <div class=\"input-field col s12 m12 l12\">
                    <i class=\"material-icons prefix indigo-text\">person</i>
                    <input value='{$prospectDetails['client_name']}' id=\"prospectName{$prospectDetails['_id']}\" type=\"text\" class=\"validate\">
                    <label for=\"prospectName\" class='active'>Nombre</label>
                </div>
                <div class=\"input-field col s12 m12 l12\">
                    <i class=\"material-icons prefix indigo-text\">person</i>
                    <input value='{$prospectDetails['client_last_name']}' id=\"prospectLastName{$prospectDetails['client_last_name']}\" type=\"text\" class=\"validate\">
                    <label for=\"prospectLastName\" class='active'>Apellido</label>
                </div>
                <div class=\"input-field col s12 m12 l12\">
                    <i class=\"material-icons prefix indigo-text\">domain</i>
                    <input value='{$prospectDetails['firm_name']}' id=\"prospectEmpresa{$prospectDetails['_id']}\" type=\"text\" class=\"validate\">
                    <label for=\"prospectEmpresa\" class='active'>Empresa</label>
                </div>
                <div class=\"input-field col s12 m12 l12\">
                    <i class=\"material-icons prefix indigo-text\">contact_phone</i>
                    <input value='{$prospectDetails['alt_phone']}' id=\"prospectAltPhone{$prospectDetails['_id']}\" type=\"text\" class=\"validate\">
                    <label for=\"prospectAltPhone\" class='active'>Teléfono</label>
                </div>
                <div class=\"input-field col s12 m8 l8\">
                    <i class=\"material-icons prefix indigo-text\">event</i>
                    <input value='{$appointment[0]}' id=\"appointment{$prospectDetails['_id']}\" type=\"date\" class=\"datepicker\">
                </div>
                <div class=\"input-field col s12 m4 l4\">
                    <input value='{$appointment[1]}' id=\"appointmentHour{$prospectDetails['_id']}\" type=\"time\">
                </div>
                <div class=\"input-field col s12 m12 l12\">
                    <i class=\"material-icons prefix indigo-text\">description</i>
                    <textarea id=\"prospectNotes{$prospectDetails['_id']}\" class=\"materialize-textarea\">{$prospectDetails['notes']}</textarea>
                    <label for=\"prospectNotes\" class='active'>Notas</label>
                </div>
            </form>
        </div>";
    }
}