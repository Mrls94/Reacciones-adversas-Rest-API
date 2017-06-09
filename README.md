# Reacciones-adversas-Rest-API

## Instalación


1. $ composer global require "laravel/installer" 
2. $ export PATH="$PATH:~/.composer/vendor/bin"
3. $ mysql -u root
⋅⋅* Si sale error : ```ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (111)```
⋅⋅* sudo service mysql start
4. ```> CREATE DATABASE reacciones_adversas;```
⋅⋅* confirmar
⋅⋅* ```> show databases;```
⋅⋅* confirmar creación de base de datos
5. $php artisan migrate
6. $php artisan db:seed
7. $php artisan serve --port=3000& ``` Al agregarle el & al final de commando se corren en background el proceso ```

### Parar servicio
1. $lsof -i :3000 ``` Listar procesos escuchando en puerto 3000 ```
2. kill {numero de pid del proceso} ``` kill -9 {pid}  para forzarlo```