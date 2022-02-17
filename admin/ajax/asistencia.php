<?php

require_once '../modelos/Asistencia.php';
require_once '../modelos/Usuario.php';
require_once '../modelos/Departamento.php';

if (strlen(session_id()) < 1) {
  session_start();
}

class AsistenciaController
{

  public function __construct()
  {
    $this->asistencia = new Asistencia();
  }


  public function guardaryeditar()
  {
    $codigo_persona = isset($_POST['codigo_persona']) ? limpiarCadena($_POST['codigo_persona']) : '';

    $result = $this->asistencia->verificarcodigo_persona($codigo_persona);

    if ($result > 0) {
      date_default_timezone_set(TIMEZONE);
      $fecha = date("Y-m-d");
      $hora = date("H:i:s");

      $result2 = $this->asistencia->seleccionarcodigo_persona($codigo_persona);

      $par = abs($result2 % 2);

      if ($par == 0) {

        $tipo = "Entrada";
        $rspta = $this->asistencia->registrar_entrada($codigo_persona, $tipo);
        //$movimiento = 0;
        echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-success"> Ingreso registrado ' . $hora . '</div>' : 'No se pudo registrar el ingreso';
      } else {
        $tipo = "Salida";
        $rspta = $this->asistencia->registrar_salida($codigo_persona, $tipo);
        //$movimiento = 1;
        echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-danger"> Salida registrada ' . $hora . '</div>' : 'No se pudo registrar la salida';
      }
    } else {
      echo '<div class="alert alert-danger">
                <i class="icon fa fa-warning"></i> No hay empleado registrado con esa código...!
            </div>';
    }
  }

  public function mostrar()
  {
    return $this->asistencia->mostrar($idasistencia);
  }


  public function listar()
  {
    $rspta = $this->asistencia->listar();
    //declaramos un array
    $data = array();


    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => '<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>',
        "1" => $reg->codigo_persona,
        "2" => $reg->nombre,
        "3" => $reg->departamento,
        "4" => $reg->fecha_hora,
        "5" => $reg->tipo,
        "6" => $reg->fecha
      );
    }

    return array(
      "sEcho" => 1, //info para datatables
      "iTotalRecords" => count($data), //enviamos el total de registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
  }

  public function listaru()
  {
    $idusuario = $_SESSION["idusuario"];
    $rspta = $this->asistencia->listaru($idusuario);
    $data = array();

    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => '<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>',
        "1" => $reg->codigo_persona,
        "2" => $reg->nombre,
        "3" => $reg->departamento,
        "4" => $reg->fecha_hora,
        "5" => $reg->tipo,
        "6" => $reg->fecha
      );
    }

    return  array(
      "sEcho" => 1, //info para datatables
      "iTotalRecords" => count($data), //enviamos el total de registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
  }

  public function listar_asistencia()
  {
    $fecha_inicio = $_REQUEST["fecha_inicio"];
    $fecha_fin = $_REQUEST["fecha_fin"];
    $codigo_persona = isset($_REQUEST['idcliente']) ? limpiarCadena($_REQUEST['idcliente']) : '';

    $iddepartamento = $_REQUEST["iddepartamento"];
    $rspta = $this->asistencia->listar_asistencia($fecha_inicio, $fecha_fin, $codigo_persona, $iddepartamento);

    $data = array();

    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->fecha,
        "1" => "{$reg->nombre} {$reg->apellidos}",
        "2" => $reg->tipo,
        "3" => $reg->fecha_hora,
        "4" => $reg->codigo_persona,
        "5" => "<a href=\"https://www.google.com/maps/search/?api=1&query={$reg->latitud},{$reg->longitud}\">Mapa</a>"
      );
    }

    return  array(
      "sEcho" => 1, //info para datatables
      "iTotalRecords" => count($data), //enviamos el total de registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
  }

  public function listar_asistenciau()
  {
    $fecha_inicio = $_REQUEST["fecha_inicio"];
    $fecha_fin = $_REQUEST["fecha_fin"];
    $codigo_persona = $_SESSION["codigo_persona"];
    $rspta = $this->asistencia->listar_asistencia($fecha_inicio, $fecha_fin, $codigo_persona);
    //declaramos un array
    $data = array();


    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->fecha,
        "1" => $reg->nombre,
        "2" => $reg->tipo,
        "3" => $reg->fecha_hora,
        "4" => $reg->codigo_persona
      );
    }

    return array(
      "sEcho" => 1, //info para datatables
      "iTotalRecords" => count($data), //enviamos el total de registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
  }

  public function selectPersona()
  {
    $usuario = new Usuario();
    $filters = [];
    if (!empty($_POST['iddepartamento'])) {
      $filters['iddepartamento'] = $_POST['iddepartamento'];
    }
    $rspta = $usuario->listar($filters);

    echo '<option></option>';
    while ($reg = $rspta->fetch_object()) {
      echo "<option value=\"{$reg->codigo_persona}\">{$reg->nombre} {$reg->apellidos}</option>";
    }
  }

  public function selectDepartamento()
  {
    $departamento = new Departamento();

    $rspta = $departamento->listar();

    echo '<option></option>';
    while ($reg = $rspta->fetch_object()) {
      echo "<option value=\"{$reg->iddepartamento}\">{$reg->nombre}</option>";
    }
  }
}

$method = $_GET['op'] ?: 'e404';
$result = (new AsistenciaController)->$method();
if (!is_null($result)) {
  echo json_encode($result, JSON_PRETTY_PRINT);
}
