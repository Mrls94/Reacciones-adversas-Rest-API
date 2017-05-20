$( document ).ready(function() {
    const ROOT_URL = "https://reacciones-adversas-rest-api-mrls94.c9users.io/Rest_API/reaccciones_adversas_rest_api/public";
    
    $("#register-button").on('click', function(event){
        event.preventDefault();
        event.stopPropagation();
        var password = $("#password").val();
        var confirmpassword = $("#confirmpassword").val();
        
        if (password.localeCompare(confirmpassword) != 0){
            $('.ui.error.message .header').html("Error");
            $('.ui.error.message p').html("Contraseñas no coinciden");
            
            $('.ui.error.message').transition('fade');   
        } else {
          
          var data = { "password": password };
          var token = $("#user_token").val();
          
          $.ajax({
            url: ROOT_URL + "/User/change_password",
            method: "POST",
            data: data,
            headers: { "token": token  }
          }).done(function(data){
            console.log("data", data);
          }).fail(function(data){
            console.log("data", data);
          });
          
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