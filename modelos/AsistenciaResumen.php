<?php

require_once dirname(__DIR__) . '/admin/config/Conexion.php';

require_once dirname(__FILE__) . '/Schedule.php';
require_once dirname(__FILE__) . '/Usuario.php';

class AsistenciaResumen
{

  protected $table = 'asistencia_resumen';

  static public function guardar($datos)
  {
    $resumen = new static;
    $id = $resumen->id($datos['id_usuario'], $datos['fecha']);
    if (empty($id)) {
      $registro = $resumen->agregar($datos);
    } else {
      $registro = $resumen->actualizar($id, $datos);
    }
    return $registro;
  }

  public function obtener($id, array $campos = null)
  {
    $campos = is_null($campos) ? '*' : implode(', ', $campos);
    $sql = "SELECT {$campos} FROM {$this->table} WHERE id='$id'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function id($id_usuario, $fecha)
  {
    $sql = "SELECT id FROM {$this->table} WHERE id_usuario='{$id_usuario}' AND fecha='{$fecha}'";
    $resumen = ejecutarConsultaSimpleFila($sql);
    return empty($resumen) ? null : $resumen['id'];
  }

  public function buscar($id_usuario, $fecha, $campos = null)
  {
    $campos = is_null($campos) ? '*' : implode(', ', $campos);
    $sql = "SELECT {$campos} FROM {$this->table} WHERE id_usuario='{$id_usuario}' AND fecha='{$fecha}'";
    $resumen = ejecutarConsultaSimpleFila($sql);
    return empty($resumen) ? null : $resumen;
  }

  public function agregar(array $datos)
  {
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');
    $sql = "INSERT INTO {$this->table}
      SET id_usuario = '{$datos['id_usuario']}',
        fecha = '{$datos['fecha']}',
        expected = '{$datos['expected']}',
        normal = '{$datos['normal']}',
        extra = '{$datos['extra']}',
        fecha_creacion = '{$created_at}',
        fecha_actualizacion = '{$updated_at}'";
    $id = ejecutarConsulta_retornarID($sql);
    return $this->obtener($id);
  }

  public function actualizar($id, array $datos)
  {
    $updated_at = date('Y-m-d H:i:s');
    $sql = "UPDATE {$this->table}
      SET id_usuario = '{$datos['id_usuario']}',
        fecha = '{$datos['fecha']}',
        expected = '{$datos['expected']}',
        normal = '{$datos['normal']}',
        extra = '{$datos['extra']}',
        fecha_actualizacion = '{$updated_at}'
      WHERE id='$id'";
    ejecutarConsulta($sql);
    return $this->obtener($id);
  }

}
