<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='../../web/css/fullcalendar.css' rel='stylesheet' />
<link href='../../web/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<link rel="stylesheet" href="../../web/css/materialize.css">
<link rel="stylesheet" href="../../web/css/style.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="../../web/css/default.time.css">
<script src='../../web/js/moment.min.js'></script>
<script src='../../web/js/jquery.js'></script>
<script src='../../web/js/fullcalendar.min.js'></script>
 <script src="../../web/js/materialize.js"></script>
 <script src="../../web/js/picker.js"></script>
 <script src="../../web/js/picker.time.js"></script>
<script>

	$(document).ready(function() {
		

		// global variable
		var currentDate;
		var currentEvent;
		var currentDay;

		// pickatime add event

	$('#fecha').pickatime({
  		clear: '' ,
  		// Formats
		format: 'HH:i A',
		// Time intervals
		interval: 30,

		// Time limits
		min: [9,00],
  		max: [17,0]

	});


	// pickatime edit 

	$('#edit_fecha').pickatime({
  		clear: '' ,
  		// Formats
		format: 'HH:i A',
		// Time intervals
		interval: 30,

		// Time limits
		min: [9,00],
  		max: [17,0]

	});


	
		// get all events form database

			$.ajax({
		url: '../../src/Controller/Events.php',
        type: 'POST', // Send post data
        data:  { action: 'all_event'},
        async: false,
        success: function(s){
        	json_events = s;

        }
	});


	
		$('#calendar').fullCalendar({
			header: {
				left: 'today',
				center: 'title',
				
			},
			defaultDate: '2015-12-12',
			defaultView: 'agendaWeek',
			weekends: false,
			selectable: true,
			selectHelper: true,		
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: JSON.parse(json_events),
		  dayClick: function(date, event, view) {
            currentDate = date.format();
            $('#new_event').openModal();
            },
             eventClick: function(calEvent, jsEvent, view) {

             	//click en evento existente
             	currentEvent = calEvent;
             	
             	//peticion ajax
             	  $.ajax({
            method: 'POST',
            url: '../../src/Controller/Events.php',
            data: {
                action: 'get_event', id_event: currentEvent.id
            },

            context: document.body,
            success: function (msg) {
                //location.reload();
                var datos = JSON.parse(msg);
				var titulo = datos.title;
				var descripcion = datos.description;
				var fecha = datos.start;
				var hora = fecha.split(' ')[1];
				currentDay = fecha.split(' ')[0];
				$("#edit_titulo_evento").val(titulo);
				$("#edit_descripcion").val(descripcion);
				$("#edit_fecha").val(hora);

				 // show modal
				$("#editevent").openModal();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
           }); 

             	  // end ajax
             	
             	//$("#editevent").openModal();

             }


		});
		
		
		  // Handle Click on Add Button
    $('.modal').on('click', '#addevent_btn',  function(e){
     
		var titulo = $("#titulo_evento").val();
		var descripcion = $("#descripcion").val();
		var dia  = currentDate.split('T')[0];
		var hora  = $("#fecha").val();
		hora = hora.split(' ')[0];
		var fecha = dia + ' '+ hora;
		 
		  $.ajax({
            method: 'POST',
            url: '../../src/Controller/Events.php',
            data: {
                action: 'add_event', titulo: titulo, descripcion: descripcion, fecha: fecha
            },

            context: document.body,
            success: function (msg) {
                //location.reload();
				 console.log(msg);
				 // hide modal
				 $('#new_event').closeModal();
				 // reset form
				 $('.event_form')[0].reset();				 
				 // reload page
				 location.reload();


            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
        
    });

    // end add new event

    // update event

    //editevent_btn


		$('.modal').on('click', '#editevent_btn',  function(e){
		     
				var titulo = $("#edit_titulo_evento").val();
				var descripcion = $("#edit_descripcion").val();
				var dia  = currentDay;
				var hora  = $("#edit_fecha").val();
				hora = hora.split(' ')[0];
				var fecha = dia + ' '+ hora;
				var id = currentEvent.id;
				 
				 alert(fecha);
				  $.ajax({
		            method: 'POST',
		            url: '../../src/Controller/Events.php',
		            data: {
		                action: 'update_event', titulo: titulo, descripcion: descripcion, fecha: fecha , id_event: id
		            },

		            context: document.body,
		            success: function (msg) {
		                //location.reload();
						 console.log(msg);
						 // hide modal
						 $('#editevent').closeModal();
						 // reset form
						 $('.edit_event_form')[0].reset();				 
						 // reload page
						 location.reload();


		            },
		            error: function (jqXHR, textStatus, errorThrown) {
		                console.log('error(s):' + textStatus, errorThrown);
		            }
		        });
		        
		    });



    //end edit event
		
	});

</script>
<style>

	body {
		margin: 40px 10px;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}

	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}

</style>
</head>
<body>

	<div id='calendar'></div>

</body>

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
							<i class="material-icons prefix">assignment	</i>	
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
							<i class="material-icons prefix">assignment	</i>	
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
</html>
