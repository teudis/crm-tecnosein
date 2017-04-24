<?php


namespace TLMK\Library;
use TLMK\DB\Database as Database;
class TaskEditor
{
    public function getUnfinished($id_user)
    {
        $db = new Database();
        $sql = <<< SQL
SELECT prospectos.telefono, prospectos.nombre, tareas.`_id`, prospectos.clase, CNAE.LITERAL AS nombreClase,
tareas.attempts
FROM prospectos
INNER JOIN tareas ON prospectos.`_id`=tareas.id_firm
INNER JOIN CNAE ON prospectos.clase = CNAE.NumReg
WHERE tareas.called = 0 AND tareas.id_user=?
SQL;
        $taskList = $db->getRows($sql, [$id_user]);
        $db->disconnect();
        return $taskList;
    }

    public function saveCall($idTask, $web, $email, $clientName, $clientLastName, $firmName,
                             $altPhone, $idResults, $appointment, $notes)
    {
        $db = new Database();
        $sql = <<< SQL
INSERT INTO user_results(id_task, alt_phone, web, email, client_name, client_last_name, firm_name, id_result,
appointment, notes)
 VALUES (?,?,?,?,?,?,?,?,?,?)
SQL;
        if ($idResults == 1) {
            $result = $db->insertRow($sql, [$idTask, $altPhone, $web, $email, $clientName, $clientLastName, $firmName,
                $idResults, $appointment, $notes]);
            $updateTaskSQL = <<< SQL
UPDATE tareas SET called=1 WHERE `_id`=?
SQL;
            $db->updateRow($updateTaskSQL, [$idTask]);
        }
        if ($idResults == 2) {
            $result = 'recordatorio añadido';
            $dontAnswer = <<< SQL
UPDATE tareas SET attempts=? WHERE `_id`=?
SQL;
            $db->updateRow($dontAnswer, [$_POST['attempts'], $idTask]);
        } else {
            $result = 'numero eliminado';
            $updateTaskSQL = <<< SQL
UPDATE tareas SET called=1 WHERE `_id`=?
SQL;
            $db->updateRow($updateTaskSQL, [$idTask]);
        }
        $db->disconnect();
        return $result;
    }

    public function callResults()
    {
        $db = new Database();
        $sql = <<< SQL
SELECT `_id`,resultado FROM call_results
SQL;
        $results = $db->getRows($sql);
        $db->disconnect();
        return $results;
    }

    public function getNextCalls($callType, $userID)
    {
        $db = new Database();
        $sql = <<<SQL
SELECT user_results.`_id`, user_results.client_name AS clientName, prospectos.telefono
FROM user_results
INNER JOIN tareas ON tareas.`_id`=user_results.id_task
INNER JOIN prospectos ON prospectos.`_id` = tareas.id_firm
WHERE user_results.call_type = ? AND tareas.id_user = ?
SQL;
        $results = $db->getRows($sql, [$callType, $userID]);
        $db->disconnect();
        return $results;
    }

    public function getProspectDetails($idTask)
    {
        $db = new Database();
        $sql = <<<SQL
SELECT * FROM user_results WHERE `_id`=?
SQL;
        $results = $db->getRow($sql, [$idTask]);
        $db->disconnect();
        return $results;
    }

}

