<?php

namespace TLMK\Library;
use TLMK\DB\Database as Database;
session_start();


class NumberList
{
    public function listClassification()
    {
        $db = new Database();
        $sql = <<< SQL
SELECT DISTINCT  LITERAL, NumReg FROM  CNAE ORDER BY LITERAL ASC
SQL;
        $classes = $db->getRows($sql);
        $db->disconnect();
        return $classes;
    }

    public function listNumberByClass($class, $limit = 10)
    {
        $db = new Database();
        $sql = <<< SQL
SELECT prospectos.telefono, CNAE.LITERAL, prospectos.`_id`
FROM prospectos
INNER JOIN CNAE ON prospectos.clase= CNAE.NumReg
WHERE prospectos.clase=? AND prospectos.selected=0
LIMIT $limit
SQL;

        $result = $db->getRows($sql, [$class]);
        return $result;
    }

    public function insertTasks($id_user, $id_client)
    {
        $db = new Database();
        $sql = <<< SQL
INSERT INTO tareas(id_user,id_firm) VALUES (?, ?)
SQL;
        $response = $db->insertRow($sql, [$id_user, $id_client]);
        $update = <<< SQL
UPDATE prospectos SET selected=1 WHERE `_id`=?
SQL;
        $db->updateRow($update, [$id_client]);
        $db->disconnect();
        if ($response) {
            return "<i class='material-icons teal-text'>done</i>";
        } else {
            return "No se ha poido argegar la tarea";
        }
    }
}
