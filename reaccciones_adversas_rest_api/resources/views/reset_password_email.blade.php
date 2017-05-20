<!DOCTYPE html>
<html>
    <head>
        <title>Se ha cambiado su contraseña</title>
    </head>
    <body>
        <h1>Se ha cambiado su contraseña {{ $usuario->name }}</h1>
        <h4> Si este no fue usted puede ingresar a {{ $url }} para cambiarla de nuevo </h4>
        <h4> Si este fue usted haga caso omiso de este correo </h4>
    </body>
</html>