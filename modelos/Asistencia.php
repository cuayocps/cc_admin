<?php

require_once dirname(__DIR__) . '/admin/config/Conexion.php';

require_once dirname(__FILE__) . '/Schedule.php';
require_once dirname(__FILE__) . '/Usuario.php';

use Carbon\Carbon;

class Asistencia
{

  protected $table = 'asistencia';

  public function verificarcodigo_persona($codigo_persona)
  {
    $sql = "SELECT * FROM {$this->table} WHERE codigo_persona='$codigo_persona'";
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
    // if ($tipo == 'Salida') {
    //   $this->registrarExtra($asistencia);
    // }
    return $asistencia;
  }

  //listar registros
  public function listar()
  {
    $sql = "SELECT * FROM {$this->table}";
    return ejecutarConsulta($sql);
  }

  public function reporte($person_code, $year, $month)
  {
    $sql = "SELECT
        DAY(entrada.fecha) AS dia,
        DAYOFWEEK(entrada.fecha) AS dia_nombre,
        entrada.idasistencia AS id_entrada,
        entrada.fecha_hora AS hora_entrada,
        salida.idasistencia AS id_salida,
        salida.fecha_hora AS hora_salida,
        ((TIME_TO_SEC(TIME(salida.fecha_hora)) - TIME_TO_SEC(TIME(entrada.fecha_hora))) / 3600) - 1 AS total
      FROM {$this->table} AS entrada
      LEFT JOIN {$this->table} AS salida ON (
        salida.tipo = 'Salida'
        AND salida.codigo_persona = entrada.codigo_persona
        AND salida.fecha = entrada.fecha
      )
      WHERE entrada.tipo = 'Entrada'
        AND entrada.codigo_persona = '$person_code'
        AND entrada.fecha BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31'
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

  public function registrarExtra($salida)
  {
    // seleccionar entrada
    // seleccionar departamento del usuario
    // seleccionar horario del día
    // calcular
    // Al registrar el egreso pasado la tolerancia, según horario, calcular las horas y/o minutos extra
    // y registrarlo como horas extra.
    // tener en cuenta que si el intreso fue pasado la hora de entrada, los minutos pasados la hora de salida
    // son recuperación hasta cumplir las horas correspondientes al día.
    $usuario = new Usuario;
    $codigo_persona = $salida['codigo_persona'];
    $id_usuario = $usuario->id($codigo_persona);
    $entrada = $this->entrada($codigo_persona, $salida);
    $id_departamento = $usuario->departamentoId($id_usuario);
    $schedule = (new Schedule)->find($id_departamento, $entrada['fecha_hora']);
    $horaEntrada = Carbon::parse($entrada['fecha_hora']);
    $horaSalida = Carbon::parse($salida['fecha_hora']);
    $diferenciaEntrada = 0;




    // pr(compact('id_usuario', 'id_departamento', 'codigo_persona', 'entrada', 'salida', 'schedule'));
  }
}
