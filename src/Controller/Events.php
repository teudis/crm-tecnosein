<?php


namespace TLMK\Controller;
require "../../vendor/autoload.php";
use TLMK\Library\Events as Events;
session_start();


if (isset($_POST['action'])) {

	
	$eventAction =  new Events();
	if ($_POST['action'] == 'add_event')
	{ 
	  // clean input data
      $titulo =  filter_input(INPUT_POST, 'titulo');
      $descripcion = filter_input(INPUT_POST, 'descripcion');
	  $fecha = filter_input(INPUT_POST, 'fecha');
	  $level = $_SESSION['level'];
	  $id_user = $_SESSION['_id'];	 
	  $resultado = $eventAction->addEvent($titulo, $descripcion, $fecha, $level, $id_user);
	  echo $resultado;
	  	
	}
	else
		if ($_POST['action']== "all_event") {
			# code...
			$level = $_SESSION['level'];
			$results;
			if ($level==1) {
				# code...
				$results = $eventAction->getEventLevel($level);
			}
			
			else
			{
				$id = $_SESSION['_id'];
				$results = $eventAction->getEventUser($id);
			}
			//$results  = $eventAction->listEvents();
			
            $events = array();
			for ($i=0; $i < count($results) ; $i++) { 
				# code...
				$e = array();
				$e['id'] = $results[$i]['_id_event'];
				$e['title'] = $results[$i]['titulo'];
				$e['description'] = $results[$i]['descripcion'];
				$e['start'] = $results[$i]['fecha'];
				array_push($events, $e);	

			}
			echo json_encode($events);
		}

		else
			if ($_POST['action']== "get_event") {
				# code...

				$id = filter_input(INPUT_POST,'id_event');

				$result = $eventAction->getEvent($id);
				$event = array();				
				$e = array();
				$e['id'] = $result['_id_event'];
				$e['title'] = $result['titulo'];
				$e['description'] = $result['descripcion'];
				$e['start'] = $result['fecha'];				
				echo json_encode($e);
				 
			}

		else
			if($_POST['action']== "update_event")
			{
				$titulo =  filter_input(INPUT_POST, 'titulo');
      			$descripcion = filter_input(INPUT_POST, 'descripcion');
	  			$fecha = filter_input(INPUT_POST, 'fecha');
	  			$id = filter_input(INPUT_POST,'id_event');
	  			$eventAction->updateEvent($id,$titulo,$descripcion,$fecha);

			}

	
	
 }

