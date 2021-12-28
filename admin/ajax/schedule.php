<?php
require_once '../modelos/Departamento.php';
require_once '../modelos/Schedule.php';

if (strlen(session_id()) < 1) {
  session_start();
}

switch ('a') {
  case 'guardaryeditar':
    if (empty($iddepartamento)) {
      $rspta = $departamento->insertar($nombre, $descripcion, $idusuario);
      echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
    } else {
      $rspta = $departamento->editar($iddepartamento, $nombre, $descripcion, $idusuario);
      echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
    }
    break;


  case 'desactivar':
    $rspta = $departamento->desactivar($iddepartamento);
    echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
    break;
  case 'activar':
    $rspta = $departamento->activar($iddepartamento);
    echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
    break;

  case 'mostrar':
    $rspta = $departamento->mostrar($iddepartamento);
    echo json_encode($rspta);
    break;

  case 'listar':
    $rspta = $departamento->listar();
    $data = array();

    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->iddepartamento . ')"><i class="fa fa-pencil"></i></button>' . ' '
          . '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->iddepartamento . ')"><i class="fa fa-close"></i></button>',
        "1" => $reg->nombre,
        "2" => $reg->descripcion,
        "3" => $reg->fechacreada
      );
    }
    $results = array(
      "sEcho" => 1, //info para datatables
      "iTotalRecords" => count($data), //enviamos el total de registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
    break;
  case 'selectDepartamento':
    $rspta = $departamento->select();
    echo '<option value="0">seleccione...</option>';
    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->iddepartamento . '>' . $reg->nombre . '</option>';
    }
    break;
}



class ScheduleController
{

  public function __construct()
  {
    $this->departamento = new Departamento();
    $this->schedule = new Schedule();
  }

  public function listar()
  {
    $data = $this->schedule->listar();
    $indiceDia = array_combine(Schedule::DAYS_OF_WEEK, array_keys(Schedule::DAYS_OF_WEEK));
    foreach ($data as &$row) {
      $row['dia'] = $indiceDia[$row['dia']];
    }
    $total = count($data);
    return [
      'length' => $total,
      'recordsTotal' => $total,
      'recordsFiltered' => $total,
      'data' => $data
    ];
  }

  public function agregar()
  {
    $departamento_id = !empty($_POST['iddepartamento']) ? limpiarCadena($_POST['iddepartamento']) : '';
    $dia_inicio = $_POST['date_from'] != '' ? limpiarCadena($_POST['date_from']) : '';
    $dia_fin = $_POST['date_to'] != '' ? limpiarCadena($_POST['date_to']) : limpiarCadena($_POST['date_from']);
    $hora_inicio = !empty($_POST['time_from']) ? limpiarCadena($_POST['time_from']) : '';
    $hora_final = !empty($_POST['time_to']) ? limpiarCadena($_POST['time_to']) : '';
    for ($d = $dia_inicio; $d <= $dia_fin; ++$d) {
      $dia = Schedule::DAYS_OF_WEEK[$d];
      if ($id = $this->schedule->id($departamento_id, $dia)) {
        $this->schedule->editar($id, $departamento_id, $dia, $hora_inicio, $hora_final);
      } else {
        $this->schedule->insertar($departamento_id, $dia, $hora_inicio, $hora_final);
      }
    }
    return ['success' => true];
  }

  public function eliminar()
  {
    $id = !empty($_POST['id']) ? limpiarCadena($_POST['id']) : '';
    $this->schedule->eliminar($id);
    return ['success' => true];
  }
}

$method = $_GET['op'] ?: 'e404';
$result = (new ScheduleController)->$method();
echo json_encode($result, JSON_PRETTY_PRINT);
