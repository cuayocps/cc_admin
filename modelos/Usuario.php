<?php

require_once dirname(__DIR__) . '/admin/config/Conexion.php';

class Usuario
{

  protected $table = 'usuarios';

  public function codigo($idusuario)
  {
    $sql = "SELECT codigo_persona FROM {$this->table} WHERE idusuario = '$idusuario'";
    $usuario = ejecutarConsultaSimpleFila($sql);
    return empty($usuario) ? null : $usuario['codigo_persona'];
  }

  public function departamentoId($idusuario)
  {
    $sql = "SELECT iddepartamento AS id FROM {$this->table} WHERE idusuario = '$idusuario'";
    $departamento = ejecutarConsultaSimpleFila($sql);
    return empty($departamento) ? null : $departamento['id'];
  }

  public function id($codigoPersona, $correo = null)
  {
    $sql = "SELECT idusuario FROM {$this->table} WHERE codigo_persona = '$codigoPersona'";
    if (!empty($correo)) {
      $sql .= " AND email = '$correo'";
    }
    $usuario = ejecutarConsultaSimpleFila($sql);
    return empty($usuario) ? null : $usuario['idusuario'];
  }

  public function info($id_usuario)
  {
    $sql = "SELECT u.nombre, CONCAT_WS(' ', u.nombre, u.apellidos) AS nombre_completo, u.codigo_persona, d.nombre AS departamento
      FROM {$this->table} AS u
      LEFT JOIN departamento AS d using (iddepartamento)
      WHERE u.idusuario = '$id_usuario'";
    return ejecutarConsultaSimpleFila($sql);
  }

}
