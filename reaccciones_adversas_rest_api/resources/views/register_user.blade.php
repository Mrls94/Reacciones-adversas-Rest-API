<!DOCTYPE html>
<html>
    <head>
        <title>MED Notify - Cambio de Contraseña</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.11.8/semantic.min.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.11.8/semantic.min.js"></script>
        <link rel="stylesheet" href="../css/index.css" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <script type="text/javascript" src="../js/main.js"></script>
    </head>
    <body>
        
        @if ($usuario)
        <div class="ui middle aligned center aligned grid">
            <div class="column" style="margin:10%;">
                <h1 class="ui image my-blue header">
                    <div class="content">
                        Bienvenido, {{ $usuario->name }}            
                    </div>
                </h1>
                <h2 class="ui my-blue image header">
                  <div class="content">
                    Establezca una nueva contraseña
                    <h4>
                        Debe contener mínimo 6 caracteres, un dígito y una mayúscula
                    </h4>
                  </div>
                </h2>
                <form class="ui large form" method="post">
                    <input hidden id="user_token" value= {{ $usuario->token }} />
                    <div class ="ui stacked segment">
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="lock icon"></i>
                                <input type="password" name="password" placeholder="Contraseña" id="password"/>
                             </div>
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="lock icon"></i>
                                <input type="password" name="confirmpassword" placeholder="Confirmar Contraseña" id="confirmpassword">
                             </div>
                        </div>
                        <button id="register-button" class="ui fluid large submit button">Aceptar</button>
                    </div>
                    
                    <div class="ui error message transition">
                        <i class="close icon"></i>
                        <div class="header"></div>
                        <p></p>
                    </div>
                    <div class="ui success message transition">
                        <i class="close icon"></i>
                        <div class="header"></div>
                        <p></p>
                        <a href="https://ra-front-end-luisllach.c9users.io" id="acceder" class="ui button">Acceder</a>
                    </div>
                </form>  
            </div>
        </div>
        @else
        <h1>Se ha caducado el enlace</h1>
        @endif
    </body>
</html>