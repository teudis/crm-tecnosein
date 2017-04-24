<?php

namespace TLMK\Library;
use TLMK\DB\Database as Database;


class Events
{

    public function addEvent($titulo, $descripcion, $fecha, $level, $id_user)
    { 
            $db = new Database();
            $sql = <<< SQL
            INSERT INTO eventos(titulo, descripcion,fecha ,level,  id_users)
            VALUES (?,?,?,?,?)
SQL;

        $addUser = $db->insertRow($sql, [$titulo, $descripcion, $fecha, $level, $id_user]);
        $db->disconnect();
        if ($addUser == 1) {
            return 'Evento registrado';
        } else {
            return -1;
        }

    }
   

    public function updateEvent($id_event, $titulo, $descripcion,$fecha)
    {
     
		$db = new Database();
        $update = <<< SQL
UPDATE eventos SET titulo=?, descripcion=?, fecha=? WHERE `_id_event`=?
SQL;
        $db->updateRow($update, [$titulo, $descripcion, $fecha, $id_event]);
        $db->disconnect();
    }

    public function deleteEvent($id_event)
    {
         $sql = <<< SQL
DELETE FROM eventos WHERE `_id_event`=?
SQL;
        $db = new Database();
        $db->deleteRow($sql, [$id_event]);
       
    }

    public function listEvents()
    {
      
        $db = new Database();
        $sql = <<< SQL
SELECT * FROM eventos
SQL;
        return $db->getRows($sql);       
    }

    public function getEvent($userId)
    {
        $db = new Database();
        $sql= <<< SQL
        SELECT * FROM eventos WHERE `_id_event`=?
SQL;
        $event = $db->getRow($sql,[$userId]);
        $db->disconnect();
        return $event;


    }

    public function getEventLevel($level)
    {
         $db = new Database();
        $sql= <<< SQL
        SELECT * FROM eventos WHERE `level`=?
SQL;
        $events = $db->getRows($sql,[$level]);
        $db->disconnect();
        return $events;

    }

    public function getEventUser($userId){
      
        $db = new Database();
        $sql= <<< SQL
SELECT * FROM eventos WHERE id_users=?
SQL;
        $userlevel = $db->getRows($sql,[$userId]);
        $db->disconnect();
        return $userlevel;        
    }

}