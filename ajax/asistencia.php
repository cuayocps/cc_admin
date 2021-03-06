<?php
require_once dirname(__DIR__) . '/modelos/Asistencia.php';

use Carbon\Carbon;

$asistencia = new Asistencia();

$codigo_persona = isset($_POST["codigo_persona"]) ? limpiarCadena($_POST["codigo_persona"]) : "";
$latitud = isset($_POST["latitud"]) ? limpiarCadena($_POST["latitud"]) : "";
$longitud = isset($_POST["longitud"]) ? limpiarCadena($_POST["longitud"]) : "";

switch ($_GET['op']) {
    case 'registrar_asistencia':
        $result = $asistencia->verificarcodigo_persona($codigo_persona);
        $error = 'No hay empleado registrado con ese código.';
        if (!empty($result)) {
            $result2 = $asistencia->seleccionarcodigo_persona($codigo_persona);
            $count2 = mysqli_num_rows($result2);
            $par = abs($count2 % 2);
            $error = '';
            if ($par == 0) {
                $tipo = 'Entrada';
                $errorTipo = 'el ingreso';
            } else {
                $tipo = 'Salida';
                $errorTipo = 'la salida';
            }
            $registro = $asistencia->registrar($codigo_persona, $tipo, $latitud, $longitud);
            if (!$registro) {
                $error = "No se pudo registrar {$errorTipo}";
            }
            if (!$error && !empty($_POST['foto_base64'])) {
                $foto64 = str_replace('data:image/jpeg;base64,', '', $_POST['foto_base64']);
                $foto = base64_decode($foto64);
                $fecha = Carbon::parse($registro['fecha_hora']);
                $month = $fecha->format('m');
                $folder = dirname(__DIR__) . "/files/registro_asistencia/{$codigo_persona}/{$fecha->year}/{$month}";
                mkdir($folder, 0777, true);
                $dayTime = $fecha->format('d-His');
                $path = "$folder/{$dayTime}.jpg";
                file_put_contents($path, $foto);
            }
            $nombre = $result['nombre'];
            $apellidos = $result['apellidos'];
            $hora = date('H:i:s');
        }
        echo json_encode(compact('par', 'tipo', 'nombre', 'apellidos', 'hora', 'error'));
        break;
}
