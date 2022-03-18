<?php

require_once '../services/reporte_asistencia.php';

if (strlen(session_id()) < 1) {
  session_start();
}

class ReporteController
{

  public function pdf()
  {
    $fecha_inicio = limpiarCadena($_POST['fecha_inicio']);
    $fecha_fin = limpiarCadena($_POST['fecha_fin']);
    $idDepartamento = limpiarCadena($_POST['iddepartamento']);
    $codigoPersona = limpiarCadena($_POST['idcliente']);
    $reporteAsistencia = new ReporteAsistencia;
    $reporteAsistencia->downloadPdf($fecha_inicio, $fecha_fin, $idDepartamento, $codigoPersona);
    exit;
  }

  public function xlsx()
  {
    $fecha_inicio = limpiarCadena($_POST['fecha_inicio']);
    $fecha_fin = limpiarCadena($_POST['fecha_fin']);
    $idDepartamento = limpiarCadena($_POST['iddepartamento']);
    $codigoPersona = limpiarCadena($_POST['idcliente']);
    $reporteAsistencia = new ReporteAsistencia;
    $reporteAsistencia->downloadXlsx($fecha_inicio, $fecha_fin, $idDepartamento, $codigoPersona);
    exit;
  }

}

$method = $_GET['op'] ?: 'e404';
$result = (new ReporteController)->$method();
echo json_encode($result, JSON_PRETTY_PRINT);
