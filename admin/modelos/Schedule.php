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
  const DAYS_OF_WEEK_NAMES = [
    2 => 'Lunes',
    3 => 'Martes',
    4 => 'Miércoles',
    5 => 'Jueves',
    6 => 'Viernes',
    7 => 'Sábado',
    1 => 'Domingo'
  ];


  protected $table = 'schedules';

  //implementamos nuestro constructor
  public function __construct()
  {
  }

  public function id($id_departamento, $dia)
  {
    $sql = "SELECT id FROM {$this->table} WHERE id_departamento='$id_departamento' AND dia='$dia'";
    $schedule = ejecutarConsultaSimpleFila($sql);
    return empty($schedule) ? null : $schedule['id'];
  }

  //metodo insertar regiustro
  public function insertar($id_departamento, $dia, $hora_inicio, $hora_final, $tolerancia)
  {
    $fecha_creacion = date('Y-m-d H:i:s');
    $sql = "INSERT INTO {$this->table}
      SET id_departamento = '$id_departamento',
        dia = '$dia',
        hora_inicio = '$hora_inicio',
        hora_final = '$hora_final',
        fecha_creacion = '$fecha_creacion',
        tolerancia = '$tolerancia'";
    return ejecutarConsulta($sql);
  }

  public function editar($id, $id_departamento, $dia, $hora_inicio, $hora_final, $tolerancia)
  {
    $fecha_creacion = date('Y-m-d H:i:s');
    $sql = "UPDATE {$this->table}
      SET id_departamento = '$id_departamento',
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
  public function listar($id_departamento)
  {
    $sql = "SELECT * FROM {$this->table} where id_departamento = '$id_departamento'";
    return consultaEnArray(ejecutarConsulta($sql));
  }
}
