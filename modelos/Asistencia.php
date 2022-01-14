<?php

require_once dirname(__DIR__) . '/admin/config/Conexion.php';

require_once dirname(__FILE__) . '/AsistenciaResumen.php';
require_once dirname(__FILE__) . '/Schedule.php';
require_once dirname(__FILE__) . '/Usuario.php';

use Carbon\Carbon;

class Asistencia
{

  protected $table = 'asistencia';

  public function verificarcodigo_persona($codigo_persona)
  {
    $sql = "SELECT * FROM usuarios WHERE codigo_persona='$codigo_persona'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function seleccionarcodigo_persona($codigo_persona)
  {
    $sql = "SELECT * FROM {$this->table} WHERE codigo_persona = '$codigo_persona'";
    return ejecutarConsulta($sql);
  }

  public function registrar($codigo_persona, $tipo, $latitud, $longitud)
  {
    $fecha = date("Y-m-d");
    $sql = "INSERT INTO {$this->table} SET codigo_persona = '$codigo_persona', tipo = '$tipo', fecha = '$fecha', latitud = '$latitud', longitud = '$longitud'";
    $id = ejecutarConsulta_retornarID($sql);
    $sql = "SELECT * FROM {$this->table} WHERE idasistencia='$id'";
    $asistencia = ejecutarConsultaSimpleFila($sql);
    if ($tipo == 'Salida') {
      $this->registrarResumen($asistencia);
    }
    return $asistencia;
  }

  public function listar()
  {
    $sql = "SELECT * FROM {$this->table}";
    return ejecutarConsulta($sql);
  }

  public function reporte($person_code, $year, $month, $day = null)
  {
    $day_from = "{$year}-{$month}-" . (empty($day) ? '01' : str_pad($day, 2, '0', STR_PAD_LEFT));
    $day_to = "{$year}-{$month}-" . (empty($day) ? '31' : str_pad($day, 2, '0', STR_PAD_LEFT));
    $sql = "SELECT
        entrada.fecha,
        DAY(entrada.fecha) AS dia,
        DAYOFWEEK(entrada.fecha) AS dia_nombre,
        entrada.idasistencia AS id_entrada,
        entrada.fecha_hora AS hora_entrada,
        (SELECT salida.fecha_hora
          FROM {$this->table} AS salida
          WHERE salida.idasistencia > entrada.idasistencia
            AND salida.tipo = 'Salida'
            AND salida.codigo_persona = entrada.codigo_persona
            AND salida.fecha = entrada.fecha
          LIMIT 1
        ) AS hora_salida
      FROM {$this->table} AS entrada
      WHERE entrada.tipo = 'Entrada'
        AND entrada.codigo_persona = '$person_code'
        AND entrada.fecha BETWEEN '$day_from' AND '$day_to'
      ORDER BY entrada.idasistencia";
    return consultaEnArray(ejecutarConsulta($sql));
  }

  public function entrada($codigo_persona, $salida)
  {
    $sql = "SELECT * FROM {$this->table}
      WHERE codigo_persona = '$codigo_persona'
        AND tipo = 'Entrada'
        AND fecha_hora < '{$salida['fecha_hora']}'
      ORDER BY idasistencia DESC";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function registrarResumen($salida)
  {
    $usuario = new Usuario;
    $codigo_persona = $salida['codigo_persona'];
    $id_usuario = $usuario->id($codigo_persona);
    $id_departamento = $usuario->departamentoId($id_usuario);
    $schedule = (new Schedule)->find($id_departamento, $salida['fecha_hora']);
    $horaSalida = Carbon::parse($salida['fecha_hora']);
    $fecha = $salida['fecha'];
    $zero = Carbon::parse($fecha);
    $horarioEntrada = Carbon::parse("{$fecha} {$schedule['hora_inicio']}");
    $horarioSalida = Carbon::parse("{$fecha} {$schedule['hora_final']}");
    $times = $this->reporte($codigo_persona, $zero->year, $zero->month, $zero->day);

    $counter = count($times);
    $total = 0;
    foreach ($times as $i => $time) {
      if (empty($time['hora_entrada']) || empty($time['hora_salida'])) {
        continue;
      }
      $in = Carbon::parse($time['hora_entrada'])->diffInMinutes($zero);
      $out = Carbon::parse($time['hora_salida'])->diffInMinutes($zero);
      $scheduleIn = $horarioEntrada->diffInMinutes($zero);
      $scheduleOut = $horarioSalida->diffInMinutes($zero);
      $diffIn = $in - $scheduleIn;
      if ($diffIn < 0 && $i == 0) {
        $in = $scheduleIn;
      }
      $diffOut = $out - $scheduleOut;

      if ($diffOut > 0 && $counter == 1) {
        $out -= 60;
      }
      $total += $out - $in;
    }
    $expected = $scheduleOut - $scheduleIn - 60;
    $extra = 0;
    $normal = $total;
    $expectedTolerance = ($expected + $schedule['tolerancia']);
    if ($total > $expected) {
      $normal = $expected;
    }
    if ($total > $expected + $schedule['tolerancia']) {
      $extra = ($total - $expected) / 60;
    }
    $expected = $expected / 60;
    $normal = $normal / 60;
    return AsistenciaResumen::guardar(compact('id_usuario', 'fecha', 'normal', 'expected', 'extra'));
  }

}
