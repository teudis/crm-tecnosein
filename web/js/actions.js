/**
 * Created by angel on 11/10/15.
 */
var documento = $('document').ready();
documento.ready(function () {
    var mainMenu = $('#mainMenu');
    var numberAction = $('#taskItemList');
    var taskId = null;
    var loginBtn = $('#login_btn');
    var callResult = null;
    var mainContent = $('#mainContent');

    $('#password').keyup(function (e) {
        if (e.which == 13) {
            loginBtn.trigger('click');
        }
    });

    loginBtn.click(function login(e) {
        e.preventDefault();
       base_url = window.location.protocol + "//" + window.location.host + "/";

        var loginname = $('#username').val();
        var loginpass = $('#password').val();
        var container = $('#error_show');
        var token = $('#tkn').val();
        
        $.ajax({
            method: 'POST',
            url: 'src/Controller/Auth.php',
            data: {
                action: 'login', username: loginname, password: loginpass, token: token
            },

            context: document.body,
            success: function (response) {
                //console.log('Ajax called');
                //console.log("response: " + response);
                //location.reload();
               
                if(response==0)
                {

                    var respuesta = "Error datos insertados";
                    var html =  "<label for='email' data-error='wrong' data-success='right'>"+respuesta+"</label>";
                    container.empty();
                    container.append(html);
                    //alert(response);

                }
                else
                    if (response==2) 
                        {
                           var respuesta = "Usuario Bloqueado. Intente en 20 minutos";
                           var html =  "<label for='email' data-error='wrong' data-success='right'>"+respuesta+"</label>";
                           container.empty();
                           container.append(html);     

                        }
                    else
                        if(response==-1)
                    {

                        var respuesta = "Error al insertar datos";
                        var html =  "<label for='email' data-error='wrong' data-success='right'>"+respuesta+"</label>";
                        container.empty();
                        container.append(html);
                    }

                    else
                    {
                        //datos correctos;

                         // Para url http://192.168.1.29/tnaranjo o http://localhost/crm
                         var parte_url = window.location.pathname;
                         var url_base = window.location.protocol + "//" + window.location.host;
                         //location.reload()
                         var redirect_response = url_base+parte_url+ response;
                          // en caso que fuese un dominio ej ..tecnosein.com
                          //  var url_base = window.location.protocol + "//" + window.location.host;
                          //redirect_response = url_base+response;
                          
                          window.location.href = redirect_response;               

                    }
                


            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
    });


    // fin  de function login 

    mainMenu.on('click', 'li > a.destroySession', function destroy(e) {
        e.preventDefault();

        $.ajax({
            method: 'POST',
            url: '../../src/Controller/Auth.php',
            data: {
                action: 'destroy'
            },

            context: document.body,
            success: function () {
                location.reload();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
    });

    $('#sign_up_button').click(function addUser(e) {
        e.preventDefault();
        var username = $('#username_add').val();
        var name = $('#fullname').val();
        var pass = $('#pass_add').val();
        var phone = $('#phone_add').val();
        var email = $('#email').val();
        var container = $('#add_response');

        $.ajax({
            method: 'POST',
            url: 'Users.php',
            data: {
                action: 'insert', username: username, name: name, password: pass,
                email: email, tel: phone
            },

            context: document.body,
            success: function (response) {
                //console.log('Ajax called');
                //console.log("response: " + response);
                container.append(response);
                $('#signup_form').get(0).reset();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
    });

    mainMenu.on('click', 'li > a[ id=profile]', function (e) {
        e.preventDefault();
        $('#singleProfile').openModal();
    });

    mainMenu.on('click', 'li > a[id=userList]', function (e) {
        e.preventDefault();
        $('#userListModal').openModal();
        $('#userListTable').empty();
        $.ajax({
            method: 'POST',
            url: '../../src/Controller/Users.php',
            data: {
                action: 'userList'
            },

            context: document.body,
            success: function (response) {
                //console.log('Ajax called');
                //console.log("response: " + response);
                $('#userListTable').append(response);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
    });

    mainMenu.on('click', 'li > a[id=assignTask]', function (e) {
        e.preventDefault();
        $('#taskListModal').openModal();
        $('#listUser').empty();
        $('#listClass').empty();
        $.ajax({
            method: 'POST',
            url: '../../src/Library/NumberList.php',
            data: {
                action: 'listClass'
            },

            context: document.body,
            success: function (response) {
                //console.log('Ajax called');
                //console.log("response: " + response);
                $('#listClass').append(response);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
        $.ajax({
            method: 'POST',
            url: '../../src/Controller/Users.php',
            data: {
                action: 'getUsersOptionList'
            },

            context: document.body,
            success: function (response) {
                //console.log('Ajax called');
                //console.log("response: " + response);
                $('#listUser').append(response);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
    });
    $('#queryNumber').click(function (e) {
        e.preventDefault();
        var listcontainer = $('#listResults');
        listcontainer.empty();

        $.ajax({
            method: 'POST',
            url: '../../src/Controller/NumberList.php',
            data: {
                action: 'listNumbers', classes: $('#listClass').val(), limit: $('#numbLimit').val()
            },

            context: document.body,
            success: function (response) {
                //console.log('Ajax called');
                //console.log("response: " + response);
                listcontainer.append(response);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
    });

    $('tbody').on('click', 'a.addNumber', function (e) {
        e.preventDefault();

        $.ajax({
            method: 'POST',
            url: '../../src/Controller/NumberList.php',
            data: {
                action: 'addNumberTask', id_user: $('#listUser').val(), id_client: e.target.id
            },

            context: document.body,
            success: function (response) {

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
        $(this).parents('tr').remove();

    });
    mainMenu.on('click', 'li > a[id=addUser]', function (e) {
        e.preventDefault();
        $('#addUserModal').openModal();
    });

    // show modal calendar
     mainMenu.on('click', 'li > a[id=showcalendar]', function (e) {
        e.preventDefault();
        $('#calendarModal').openModal();
         $("#calendar").fullCalendar('render');

    });


    // Call SECTION ------------------------------------------------------------


    // Call activations
    numberAction.on('click', 'li > div.collapsible-header > a.numberListItem', function () {
        var endCallContainer = mainMenu.children('li[id=endCallContainer]');
        //alert('id: ' + e.target.id + ' número' + $(this).text());


    });

    numberAction.on('click', 'li > div.collapsible-header > a.secondary-content', function (e) {
        e.preventDefault();
        var container = $('#numberTaskActions');
        container.empty();
        taskId = $(this).attr('id');
        $.ajax({
            method: 'POST',
            url: '../../src/Controller/TaskEditor.php',
            data: {
                action: 'getCallResults'
            },
            context: document.body,
            success: function (response) {
                //console.log('Ajax called');
                //console.log("response: " + response);
                container.append(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
        container.css({'width': ''})
    });
    $('#numberTaskActions').on('click', 'li > a.callResultAction', function (e) {
        e.preventDefault();
        callResult = e.target.id;
        var container = $('#taskItemList');
        //alert('id: ' + callResult + ' acción: ' + $(this).text() + ' parent: '+taskId);
        if (callResult > 2) {
            $.ajax({
                method: 'POST',
                url: '../../src/Controller/TaskEditor.php',
                data: {
                    action: 'saveCall', idResults: callResult, id_task: taskId,
                    web: '', email: '', clientName: '', clientLastName: '', firmName: '', altPhone: '',
                    appointment: '', notes: ''
                },
                context: document.body,
                success: function (response) {
                    //console.log('Ajax called');
                    console.log("response: " + response);
                    //container.append(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error(s):' + textStatus, errorThrown);
                }
            });
            container.children('li[id=' + taskId + ']').remove();
        }
        if (callResult == 2) {
            //console.log('no contesta');
            var attempts = container.children('li[id=' + taskId + ']').attr('data-attempts');
            container.children('li[id=' + taskId + ']').children('div.collapsible-header').append(
                '<i class="material-icons teal-text">access_time</i>'
            );
            attempts = parseInt(attempts) + 1;
            $.ajax({
                method: 'POST',
                url: '../../src/Controller/TaskEditor.php',
                data: {
                    action: 'saveCall', idResults: callResult, id_task: taskId, attempts: attempts,
                    web: '', email: '', clientName: '', clientLastName: '', firmName: '', altPhone: '',
                    appointment: '', notes: ''
                },
                context: document.body,
                success: function (response) {
                    //console.log('Ajax called');
                    console.log("response: " + response);
                    //container.append(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error(s):' + textStatus, errorThrown);
                }
            });
        }
        if (callResult == 1) {
            var prospectForm = $('#taskContainer');
            var saveBtn = $('#saveProspect');
            saveBtn.removeClass('hide');
            container.children('li[id=' + taskId + ']').append(prospectForm);
            container.children('li[id=' + taskId + ']').trigger('click');
        }
    });

    $('#saveProspectData').click(function (e) {
        e.preventDefault();
        var prospectWeb = $('#prospectWeb').val();
        var prospectMail = $('#prospectMail').val();
        var prospectName = $('#prospectName').val();
        var prospectLastName = $('#prospectLastName').val();
        var prospectEmpresa = $('#prospectEmpresa').val();
        var prospectAltPhone = $('#prospectAltPhone').val();
        var prospectNotes = $('#prospectNotes').val();
        var appointment = $('#appointment').val() + $('#appointmentHour').val();

        $.ajax({
            method: 'POST',
            url: '../../src/Controller/TaskEditor.php',
            data: {
                action: 'saveCall', idResults: callResult, id_task: taskId,
                web: prospectWeb, email: prospectMail, clientName: prospectName, clientLastName: prospectLastName,
                firmName: prospectEmpresa, altPhone: prospectAltPhone, appointment: appointment, notes: prospectNotes
            },
            context: document.body,
            success: function (response) {
                console.log("response: " + response);
                //container.append(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
        $('#taskItemList').children('li[id=' + taskId + ']').remove();
        $('#saveProspect').addClass('hide');

    });

    $('#addUserBtn').click(function (e) {
        e.preventDefault();
        var container = $('#addUserResponseContainer');
        var usernameAdd = $('#username_add').val();
        var fullName = $('#fullname').val();
        var passAdd = $('#pass_add').val();
        var phoneAdd = $('#phone_add').val();
        var emailAdd = $('#email_add').val();

        container.empty();

        $.ajax({
            method: 'POST',
            url: '../../src/Controller/Users.php',
            data: {
                action: 'insert', username: usernameAdd, name: fullName,
                password: passAdd, tel: phoneAdd, email: emailAdd
            },
            context: document.body,
            success: function (response) {
                console.log("response: " + response);
                container.append(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        })
    });
    mainMenu.on('click', 'input[id=super_conference]', function () {
        var conferenceFrame = $('#conferenceFrame');
        if (this.checked) {
            conferenceFrame.attr('src', 'https://tecnosein.com:7443/ofmeet/?r=dora&novideo=true');
        } else {
            conferenceFrame.attr('src', '');
        }
    });

    mainMenu.on('click', 'a[id=fullScreenMode]', function (e) {
        e.preventDefault();
        $(document).toggleFullScreen();
        $(this).children('i').text(function (i, text) {
            return text === 'fullscreen' ? 'fullscreen_exit' : 'fullscreen'
        })
    });

    mainContent.on('click', 'ul > li > a[id=call2List]', function () {
        var container = $('#secondTaskItemList');
        var userID = $('#userGlobalId').text();
        container.empty();
        $.ajax({
            method: 'POST',
            url: '../../src/Controller/TaskEditor.php',
            data: {
                action: 'getNextCall', callType: 2, userID: userID
            },
            context: document.body,
            success: function (response) {
                //console.log("response: " + response);
                container.append(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        });
    });

    mainContent.on('click', 'ul[id=secondTaskItemList] > li', function () {
        var container = $(this);
        if ($(this).children('.collapsible-body').length < 1) {
            $.ajax({
                method: 'POST',
                url: '../../src/Controller/TaskEditor.php',
                data: {
                    action: 'getProspectDetails', id_task: $(this).attr('id')
                },
                context: document.body,
                success: function (response) {
                    //console.log("response: " + response);
                    container.append(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error(s):' + textStatus, errorThrown);
                }
            });
            $('.collapsible').collapsible();
        }


    });

    mainContent.on('click', 'ul > li > a[id=call3List]', function () {
        var container = $('#thirdTaskItemList');
        var userID = $('#userGlobalId').text();
        container.empty();
        $.ajax({
            method: 'POST',
            url: '../../src/Controller/TaskEditor.php',
            data: {
                action: 'getNextCall', callType: 3, userID: userID
            },
            context: document.body,
            success: function (response) {
                //console.log("response: " + response);
                container.append(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error(s):' + textStatus, errorThrown);
            }
        })
    });
});