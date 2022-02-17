<?php

require_once '../modelos/Asistencia.php';
require_once '../modelos/AsistenciaResumen.php';
require_once '../modelos/Usuario.php';
require_once '../modelos/Departamento.php';
require_once '../services/reporte_asistencia_pdf.php';
require_once '../services/reporte_asistencia_xlsx.php';

use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;

if (strlen(session_id()) < 1) {
  session_start();
}

class ReporteController
{

  public function __construct()
  {
    $this->asistencia = new Asistencia();
    $this->asistenciaResumen = new AsistenciaResumen();
    $this->usuario = new Usuario();
    $this->departamento = new Departamento();
  }

  public function pdf()
  {
    $fecha_inicio = Carbon::parse(limpiarCadena($_POST['fecha_inicio']));
    $fecha_fin = Carbon::parse(limpiarCadena($_POST['fecha_fin']));
    $idDepartamento = limpiarCadena($_POST['iddepartamento']);
    $codigoPersona = limpiarCadena($_POST['idcliente']);

    $data = $this->getReportData($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $nombre = $this->nombreReporte($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $subtitle = "del {$fecha_inicio->format('d-m-Y')} al {$fecha_fin->format('d-m-Y')}";
    $pdf = new ReporteAsistenciaPdf();
    $pdf->download($nombre, $data, $subtitle);
    exit;
  }

  public function xlsx()
  {
    $fecha_inicio = Carbon::parse(limpiarCadena($_POST['fecha_inicio']));
    $fecha_fin = Carbon::parse(limpiarCadena($_POST['fecha_fin']));
    $idDepartamento = limpiarCadena($_POST['iddepartamento']);
    $codigoPersona = limpiarCadena($_POST['idcliente']);

    $data = $this->getReportData($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $nombre = $this->nombreReporte($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);

    $xlsx = new ReporteAsistenciaXlsx();
    $this->dataForTable($data);
    $xlsx->download($nombre, $data);
    exit;
  }

  protected function enviar($to, $subject, $message, $file = null)
  {
    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->From = 'noreply@prove.cl';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->Username = 'cuayocps@gmail.com';
    $mail->Password = 'xbmzzzegnrbczfaf';

    $mail->AddAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message;

    if (!empty($file)) {
      $mail->addAttachment($file, basename($file));
    }

    $mail->Send();
  }

  protected function nombreReporte($idDepartamento, $codigoPersona, Carbon $fecha_inicio, Carbon $fecha_fin)
  {
    $nombre = 'reporte-asistencia';
    if (!empty($codigoPersona)) {
      $usuario = $this->usuario->info($codigoPersona, ["CONCAT_WS(' ', nombre, apellidos) AS nombre_completo"]);
      $nombre .= '-' . slugify($usuario['nombre_completo']);
    } else if (!empty($idDepartamento)) {
      $nombre .= '-' . slugify($this->departamento->nombre($idDepartamento));
    }
    $nombre .= '-' . $fecha_inicio->format('dmY') . '-' . $fecha_fin->format('dmY');
    return $nombre;
  }

  protected function getReportData($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin)
  {
    if (!empty($codigoPersona)) {
      $usuario = $this->usuario->info($codigoPersona);
      $usuarios = [$usuario];
    } else if (!empty($idDepartamento)) {
      $usuarios = $this->usuario->allInfo($idDepartamento);
    } else {
      $usuarios = $this->usuario->allInfo();
    }
    $data = $this->groupUsersByAppartement($usuarios);
    $data = $this->addAppartementName($data);
    $data = $this->addHoursToUsers($data, $fecha_inicio, $fecha_fin);
    return array_filter($data, function ($reg) {
      return !empty($reg['usuarios']);
    });
  }

  protected function groupUsersByAppartement($usuarios)
  {
    $departamentos = [];
    foreach ($usuarios as $usuario) {
      $id = $usuario['iddepartamento'];
      if (!isset($departamentos[$id])) {
        $departamentos[] = [
          'nombre' => '',
          'usuarios' => []
        ];
      }
      $departamentos[$id]['usuarios'][] = $usuario;
    }
    return $departamentos;
  }

  protected function addAppartementName($data)
  {
    $ids = array_keys($data);
    $nombres = $this->departamento->nombres($ids);
    foreach ($nombres as $id => $nombre) {
      $data[$id]['nombre'] = $nombre;
    }
    return $data;
  }

  protected function addHoursToUsers($data, $fecha_inicio, $fecha_fin)
  {
    foreach ($data as $departamento => $datos) {
      foreach ($datos['usuarios'] as $i => $usuario) {
        $data[$departamento]['usuarios'][$i]['horas'] = $this->horasUsuario($usuario, $fecha_inicio, $fecha_fin);
      }
    }
    return $data;
  }

  protected function horasUsuario($usuario, $fecha_inicio, $fecha_fin)
  {
    $horas = $this->asistencia->reporte($usuario['codigo_persona'], $fecha_inicio, $fecha_fin);
    foreach ($horas as &$hora) {
      $resumen = $this->asistenciaResumen->buscar($usuario['idusuario'], $hora['fecha'], ['fecha', 'normal', 'extra']);
      $total = 0;
      $extra = 0;
      $fecha = Carbon::parse($hora['fecha']);
      if (!empty($resumen)) {
        $total = $resumen['normal'];
        $extra = $resumen['extra'];
      }
      $hora['fecha'] = $fecha;
      $hora['total'] = $total;
      $hora['extra'] = $extra;
    }
    return $horas;
  }

  protected function dataForTable(&$data)
  {
    foreach ($data as $i => $departamento) {
      $tablaHoras = [];
      foreach ($departamento['usuarios'] as $u => $usuario) {
        $horas = $usuario['horas'];
        unset($usuario['horas']);
        foreach ($horas as $hora) {
          $tablaHoras[] = array_merge($usuario, $hora);
        }
      }
      unset($data[$i]['usuarios']);
      usort($tablaHoras, function ($a, $b) {
        return $a['fecha']->format('Ymd') > $b['fecha']->format('Ymd');
      });
      $data[$i]['horas'] = $tablaHoras;
    }
  }
}

$method = $_GET['op'] ?: 'e404';
$result = (new ReporteController)->$method();
echo json_encode($result, JSON_PRETTY_PRINT);
