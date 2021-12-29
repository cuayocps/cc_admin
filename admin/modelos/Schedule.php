<?php

require_once dirname(__DIR__) . '/config/Conexion.php';
date_default_timezone_set(TIMEZONE);

class Schedule
{

  const DAYS_OF_WEEK = [2, 3, 4, 5, 6, 7, 1];
  const DAYS = [
    'Lunes',
    'Martes',
    'Miércoles',
    'Jueves',
    'Viernes',
    'Sábado',
    'Domingo'
  ];

  protected $table = 'schedules';

  //implementamos nuestro constructor
  public function __construct()
  {
  }

  public function id($departamento_id, $dia)
  {
    $sql = "SELECT id FROM {$this->table} WHERE departamento_id='$departamento_id' AND dia='$dia'";
    $schedule = ejecutarConsultaSimpleFila($sql);
    return empty($schedule) ? null : $schedule['id'];
  }

  //metodo insertar regiustro
  public function insertar($departamento_id, $dia, $hora_inicio, $hora_final, $tolerancia)
  {
    $fecha_creacion = date('Y-m-d H:i:s');
    $sql = "INSERT INTO {$this->table}
      SET departamento_id = '$departamento_id',
        dia = '$dia',
        hora_inicio = '$hora_inicio',
        hora_final = '$hora_final',
        fecha_creacion = '$fecha_creacion',
        tolerancia = '$tolerancia'";
    return ejecutarConsulta($sql);
  }

  public function editar($id, $departamento_id, $dia, $hora_inicio, $hora_final, $tolerancia)
  {
    $fecha_creacion = date('Y-m-d H:i:s');
    $sql = "UPDATE {$this->table}
      SET departamento_id = '$departamento_id',
        dia = '$dia',
        hora_inicio = '$hora_inicio',
        hora_final = '$hora_final',
        fecha_creacion = '$fecha_creacion',
        tolerancia = '$tolerancia'
      WHERE id='$id'";
    return ejecutarConsulta($sql);
  }
  public function eliminar($id)
  {
    $sql = "DELETE FROM {$this->table} WHERE id='$id'";
    return ejecutarConsulta($sql);
  }

  //metodo para mostrar registros
  public function mostrar($id)
  {
    $sql = "SELECT * FROM {$this->table} WHERE id='$id'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //listar registros
  public function listar($departamento_id)
  {
    $sql = "SELECT * FROM {$this->table} where departamento_id = '$departamento_id'";
    return consultaEnArray(ejecutarConsulta($sql));
  }

}
