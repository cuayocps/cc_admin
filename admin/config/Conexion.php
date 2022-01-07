<?php
require_once "global.php";

$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

mysqli_query($conexion, 'SET NAMES "' . DB_ENCODE . '"');

//muestra posible error en la conexion
if (mysqli_connect_errno()) {
    printf("Ups parece que falló en la conexion con la base de datos: %s\n", mysqli_connect_error());
    exit();
}

if (!function_exists('ejecutarConsulta')) {
    function ejecutarConsulta($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
        if (mysqli_error($conexion)) {
            throw new exception($conexion->error, $conexion->errno);
        }
        return $query;
    }

    function ejecutarConsultaSimpleFila($sql)
    {
        global $conexion;

        $query = $conexion->query($sql);
        $row = $query ? $query->fetch_assoc() : null;
        return $row;
    }
    function ejecutarConsulta_retornarID($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
        return $conexion->insert_id;
    }

    function limpiarCadena($str)
    {
        global $conexion;
        $str = mysqli_real_escape_string($conexion, trim($str));
        return htmlspecialchars($str);
    }

    function pr($v) {
        echo '<pre>' . print_r($v, true) . '</pre>';
    }

    function dd() {
      $args = func_get_args();
      foreach ($args as $arg) {
        pr($arg);
      }
      exit;
    }

    function consultaEnArray(mysqli_result $query)
    {
      $data = [];
      while($row = $query->fetch_assoc()) {
        $data[] = $row;
      }
      return $data;
    }

    function mes($mes) {
      $meses = [
        '01' => 'Enero',
        '02' => 'Febrero',
        '03' => 'Marzo',
        '04' => 'Abril',
        '05' => 'Mayo',
        '06' => 'Junio',
        '07' => 'Julio',
        '08' => 'Agosto',
        '09' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre'
      ];
      return $meses[$mes];
    }
}
