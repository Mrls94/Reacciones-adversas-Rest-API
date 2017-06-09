<!DOCTYPE html>
<html>
    <head>
        <title>Bienvenido a MED Notify - Sistema de Reacciones Adversas</title>
    </head>
    <body>
        <!--<img class="ui small image logo" src="../images/ma-logo.png">-->
        <h1>Bienvenido, {{ $usuario->name }}</h1>
        <h4> Accede al siguiente enlace para establecer tu contraseña: </h4>
        <h4> {{ $registerurl }} </h4>
    </body>
</html>