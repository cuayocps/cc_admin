<?php

require_once '../modelos/Grupos.php';

if (strlen(session_id()) < 1) {
  session_start();
}

class GrupoController
{

  public function __construct()
  {
    $this->grupos = new Grupos();
  }

  public function listar()
  {
    return $this->grupos->listar() ?: new stdClass;
  }

}

$method = $_GET['op'] ?: 'e404';
$result = (new GrupoController)->$method();
if (!is_null($result)) {
  echo json_encode($result, JSON_PRETTY_PRINT);
}
