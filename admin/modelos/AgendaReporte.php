<?php

require_once dirname(__DIR__) . '/config/Conexion.php';

class AgendaReporte
{
  const MONDAY = 'APUMA';
  const TUESDAY = 'LUN';
  const WEDNESDAY = 'MAR';
  const THURSDAY = 'MIE';
  const FRIDAY = 'JUE';
  const SATURDAY = 'VIE';
  const SUNDAY = 'SAB';
  const LAST_DAY = 'DOM';
  const TODAY = 'DOM';

  protected $table = 'agenda_reportes';


  public function guardar($datos)
  {
    if (empty($datos['id'])) {
      $agenda = $this->agregar($datos);
    } else {
      $id = $datos['id'];
      unset($datos['id']);
      $agenda = $this->actualizar($id, $datos);
    }
    return $agenda;
  }

  public function agregar(array $datos)
  {
    $created_at = date('Y-m-d H:i:s');
    $id_departamento = empty($datos['id_departamento']) ? 'NULL' : $datos['id_departamento'];
    $id_usuario = empty($datos['id_usuario']) ? 'NULL' : $datos['id_usuario'];
    $sql = "INSERT INTO {$this->table}
      SET
        dia = '{$datos['dia']}',
        hora = '{$datos['hora']}:{$datos['minuto']}',
        desde = '{$datos['desde']}',
        hasta = '{$datos['hasta']}',
        id_departamento = {$id_departamento},
        id_usuario = {$id_usuario},
        correos = '{$datos['correos']}',
        fecha_creacion = '{$created_at}'";
    $id = ejecutarConsulta_retornarID($sql);
    return $this->obtener($id);
  }

  public function actualizar($id, array $datos)
  {
    $updated_at = date('Y-m-d H:i:s');
    $id_departamento = empty($datos['id_departamento']) ? 'NULL' : $datos['id_departamento'];
    $id_usuario = empty($datos['id_usuario']) ? 'NULL' : $datos['id_usuario'];
    $sql = "UPDATE {$this->table}
      SET
        dia = '{$datos['dia']}',
        hora = '{$datos['hora']}:{$datos['minuto']}',
        desde = '{$datos['desde']}',
        hasta = '{$datos['hasta']}',
        id_departamento = {$id_departamento},
        id_usuario = {$id_usuario},
        correos = '{$datos['correos']}',
        fecha_actualizacion = '{$updated_at}'
      WHERE id='$id'";
    ejecutarConsulta($sql);
    return $this->obtener($id);
  }

  public function obtener($id, array $campos = null)
  {
    $campos = is_null($campos) ? '*' : implode(', ', $campos);
    $sql = "SELECT {$campos} FROM {$this->table} WHERE id='$id'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listar()
  {
    $sql = "SELECT
        ar.*,
        d.nombre as departamento,
        CONCAT(u.nombre, ' ', u.apellidos) as usuario,
        GROUP_CONCAT(agr.id_grupo) grupos
      FROM {$this->table} as ar
      LEFT JOIN departamento as d ON d.iddepartamento = ar.id_departamento
      LEFT JOIN usuarios as u ON u.idusuario = ar.id_usuario
      LEFT JOIN agenda_reporte_grupos agr ON agr.id_agenda_reporte = ar.id
      GROUP BY ar.id";
    return consultaEnArray(ejecutarConsulta($sql));
  }

}
