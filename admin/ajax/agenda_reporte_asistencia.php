<?php

require_once '../modelos/AgendaReporte.php';
require_once '../modelos/AgendaReporteGrupo.php';
require_once '../modelos/Grupos.php';

if (strlen(session_id()) < 1) {
  session_start();
}

class AgendaReporteAsistenciaController
{

  public function __construct()
  {
    $this->agendaReporte = new AgendaReporte;
    $this->agendaReporteGrupo = new AgendaReporteGrupo;
    $this->grupos = new Grupos;
  }

  public function listar()
  {
    $data = $this->agendaReporte->listar();
    $total = count($data);
    return [
      'length' => $total,
      'recordsTotal' => $total,
      'recordsFiltered' => $total,
      'data' => $data
    ];
  }

  public function guardar()
  {
    $grupos = [];
    if (!empty($_POST['grupos'])) {
      $grupos = $_POST['grupos'];
      unset($_POST['grupos']);
    }

    $_POST['hora'] = str_pad($_POST['hora'], 2, 0, STR_PAD_LEFT);
    $_POST['minuto'] = str_pad($_POST['minuto'], 2, 0, STR_PAD_LEFT);
    $_POST['desde'] = "{$_POST['desde']['sm']},{$_POST['desde']['dia']}";
    $_POST['hasta'] = "{$_POST['hasta']['sm']},{$_POST['hasta']['dia']}";
    $agenda = $this->agendaReporte->guardar($_POST);
    $this->agendaReporteGrupo->guardar($agenda['id'], $grupos);
    $agenda['grupos'] = implode(',', $grupos);
    return $agenda;
  }

  public function selectGrupos()
  {
    $grupos = $this->grupos->listar();
    foreach ($grupos as $id => $grupo) {
      echo "<option value=\"{$id}\">{$grupo}</option>";
    }
  }
}

$method = $_GET['op'] ?: 'e404';
$result = (new AgendaReporteAsistenciaController)->$method();
if (!is_null($result)) {
  echo json_encode($result, JSON_PRETTY_PRINT);
}
