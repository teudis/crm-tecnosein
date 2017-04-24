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
				 
				 console.log(titulo);
				  $.ajax({
		            method: 'POST',
		            url: '../../src/Controller/Events.php',
		            data: {
		                action: 'update_event', titulo: titulo, descripcion: descripcion, fecha: fecha , id_event: id
		            },

		            context: document.body,
		            success: function (msg) {
		                
						// console.log(msg);
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