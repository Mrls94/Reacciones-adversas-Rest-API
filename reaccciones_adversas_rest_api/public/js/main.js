$( document ).ready(function() {
    const ROOT_URL = "https://reacciones-adversas-rest-api-mrls94.c9users.io/Rest_API/reaccciones_adversas_rest_api/public";
    const ROOT_URL_FRONT = "https://ra-front-end-luisllach.c9users.io/";
    
    $("#register-button").on('click', function(event){
        event.preventDefault();
        event.stopPropagation();
        var password = $("#password").val();
        var confirmpassword = $("#confirmpassword").val();
        var vis=$('.ui.success.message').hasClass('visible');
        var vie=$('.ui.error.message').hasClass('visible');
        if (password.localeCompare(confirmpassword) != 0){
            $('.ui.error.message .header').html("Error");
            $('.ui.error.message p').html("Contraseñas no coinciden");
            if(!vie){
              $('.ui.error.message').transition('fade');  
            }
            if(vis){
              $('.ui.success.message').transition('fade');
            }
        }else{
          if(password.length<6){
            $('.ui.error.message .header').html("Error");
            $('.ui.error.message p').html("La contraseña debe tener mínimo 6 caracteres");
            if(!vie){
              $('.ui.error.message').transition('fade');  
            }
            if(vis){
              $('.ui.success.message').transition('fade');
            }
          }else{
            re = /[0-9]/;
            if(!re.test(password)){
              $('.ui.error.message .header').html("Error");
              $('.ui.error.message p').html("La contraseña debe contener algún número");
              if(!vie){
                $('.ui.error.message').transition('fade');  
              }
              if(vis){
                $('.ui.success.message').transition('fade');
              }
            }else{
              re = /[A-Z]/;
              if(!re.test(password)){
                $('.ui.error.message .header').html("Error");
                $('.ui.error.message p').html("La contraseña debe contener mínimo una mayúscula");
                if(!vie){
                  $('.ui.error.message').transition('fade');  
                }
                if(vis){
                  $('.ui.success.message').transition('fade');
                }
              }else {
          
                var data = { "password": password };
                var token = $("#user_token").val();
                
                $.ajax({
                  url: ROOT_URL + "/User/change_password",
                  method: "POST",
                  data: data,
                  headers: { "token": token  }
                }).done(function(data){
                  console.log("data", data);
                  if(vie){
                    $('.ui.error.message').transition('fade');
                  }
                  $('.ui.success.message .header').html("Éxito");
                  $('.ui.success.message p').html("Contraseña registrada. Acceda a MED Notify - Reacciones Adversas");
                  
                  $('.ui.success.message').transition('fade');  
                }).fail(function(data){
                  console.log("data", data);
                });
                
              }
            }
          }
        }
        
    });
    
    
    $('.message .close')
      .on('click', function() {
        $(this)
          .closest('.message')
          .transition('fade')
        ;
      });
});