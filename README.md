# Reacciones-adversas-Rest-API

## Instalación


1. $ composer global require "laravel/installer" 
2. $ export PATH="$PATH:~/.composer/vendor/bin"
3. $ mysql -u root
..* Si sale error : ```ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (111)```
..* sudo service mysql start
4. ```> CREATE DATABASE reacciones_adversas;```
..* confirmar
..* ```> show databases;```
..* confirmar creación de base de datos