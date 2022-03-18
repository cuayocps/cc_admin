<?php

require_once __DIR__ . '/reporte_asistencia/pdf.php';
require_once __DIR__ . '/reporte_asistencia/xlsx.php';

require_once dirname(__DIR__) . '/modelos/Asistencia.php';
require_once dirname(__DIR__) . '/modelos/AsistenciaResumen.php';
require_once dirname(__DIR__) . '/modelos/Usuario.php';
require_once dirname(__DIR__) . '/modelos/Departamento.php';

use Carbon\Carbon;

class ReporteAsistencia
{

  public function __construct()
  {
    $this->asistencia = new Asistencia();
    $this->asistenciaResumen = new AsistenciaResumen();
    $this->usuario = new Usuario();
    $this->departamento = new Departamento();
  }

  public function downloadPdf($fecha_inicio, $fecha_fin, $idDepartamento, $codigoPersona)
  {
    $fecha_inicio = $fecha_inicio instanceof Carbon ? $fecha_inicio : Carbon::parse($fecha_inicio);
    $fecha_fin = $fecha_fin instanceof Carbon ? $fecha_fin : Carbon::parse($fecha_fin);
    $data = $this->getReportData($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $nombre = $this->nombreReporte($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $subtitle = "del {$fecha_inicio->format('d-m-Y')} al {$fecha_fin->format('d-m-Y')}";
    $pdf = new Pdf();
    $pdf->download($nombre, $data, $subtitle);
  }

  public function downloadXlsx($fecha_inicio, $fecha_fin, $idDepartamento, $codigoPersona)
  {
    $fecha_inicio = $fecha_inicio instanceof Carbon ? $fecha_inicio : Carbon::parse($fecha_inicio);
    $fecha_fin = $fecha_fin instanceof Carbon ? $fecha_fin : Carbon::parse($fecha_fin);
    $data = $this->getReportData($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $nombre = $this->nombreReporte($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $xlsx = new Xlsx();
    $this->dataForTable($data);
    $xlsx->download($nombre, $data);
  }

  public function getXlsx($fecha_inicio, $fecha_fin, $idDepartamento, $codigoPersona)
  {
    $fecha_inicio = $fecha_inicio instanceof Carbon ? $fecha_inicio : Carbon::parse($fecha_inicio);
    $fecha_fin = $fecha_fin instanceof Carbon ? $fecha_fin : Carbon::parse($fecha_fin);
    $data = $this->getReportData($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $nombre = $this->nombreReporte($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $xlsx = new Xlsx();
    $this->dataForTable($data);
    return $xlsx->getFile($nombre, $data);
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
