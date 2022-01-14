<?php

require_once dirname(__DIR__) . '/admin/config/Conexion.php';

date_default_timezone_set(TIMEZONE);

class Schedule
{

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

  public function find($id_departamento, $fecha)
  {
    $sql = "SELECT id, dia, hora_inicio, hora_final, tolerancia FROM {$this->table} WHERE id_departamento='$id_departamento' AND dia = DAYOFWEEK('$fecha')";
    $schedule = ejecutarConsultaSimpleFila($sql);
    return empty($schedule) ? null : $schedule;
  }
}
