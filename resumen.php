<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

if (strlen(session_id()) < 1) {
  session_start();
}

require_once 'modelos/Asistencia.php';

$asistencia = new Asistencia;
$registros = $asistencia->listarSinResumen();
foreach ($registros as $salida) {
  try {
    $resumen = $asistencia->registrarResumen($salida);
    print_r(compact('salida', 'resumen'));
    echo "\n";
  } catch (Exception $e) {
    echo $e->getMessage() . "\n";
  }
}
