<?php
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

//ip de la pc servidor base de datos
define('DB_HOST', '');

// nombre de la base de datos
define('DB_NAME', '');


//nombre de usuario de base de datos
define('DB_USERNAME', '');

//conraseña del usuario de base de datos
define('DB_PASSWORD', '');

//codificacion de caracteres
define('DB_ENCODE', 'utf8');

define('TMP_DIR', '/tmp');

define('TIMEZONE', 'America/Santiago');

date_default_timezone_set(TIMEZONE);
