<!DOCTYPE html>
<html>
    <head>
        <title>Se ha cambiado su contraseña</title>
    </head>
    <body>
        <h1>Se ha cambiado su contraseña {{ $usuario->name }}</h1>
        <h4> Tu clave es: {{ $password }} </h4>
        <h4> Ingresa con tu correo {{ $usuario->email }}</h4>
    </body>
</html>