<?php
date_default_timezone_set('America/Santiago');

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

// error_reporting(E_ALL);
//ip de la pc servidor base de datos
define("DB_HOST", "host.docker.internal");

// nombre de la base de datos
define("DB_NAME", "ccadmin");


//nombre de usuario de base de datos
define("DB_USERNAME", "root");
//define("DB_USERNAME", "u222417_admin");

//conraseña del usuario de base de datos
define("DB_PASSWORD", ".sweetpwd.");
//define("DB_PASSWORD", "Enero2020Admin");

//codificacion de caracteres
define("DB_ENCODE", "utf8");

//nombre del proyecto
define("PRO_NOMBRE", "_");
