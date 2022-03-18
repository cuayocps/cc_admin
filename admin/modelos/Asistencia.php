<?php
//incluir la conexion de base de datos
require dirname(__DIR__) . '/config/Conexion.php';
class Asistencia
{

    protected $table = 'asistencia';

    //implementamos nuestro constructor
    public function __construct()
    {
    }


    //listar registros
    public function listar()
    {
        $sql = "SELECT a.idasistencia,a.codigo_persona,a.fecha_hora,a.tipo,a.fecha,u.nombre,u.apellidos,d.nombre as departamento FROM asistencia a INNER JOIN usuarios u INNER JOIN departamento d ON u.iddepartamento=d.iddepartamento WHERE a.codigo_persona=u.codigo_persona";
        return ejecutarConsulta($sql);
    }

    public function listaru($idusuario)
    {
        $sql = "SELECT a.idasistencia,a.codigo_persona,a.fecha_hora,a.tipo,a.fecha,u.nombre,u.apellidos,d.nombre as departamento FROM asistencia a INNER JOIN usuarios u INNER JOIN departamento d ON u.iddepartamento=d.iddepartamento WHERE a.codigo_persona=u.codigo_persona AND u.idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    public function listar_asistencia($fecha_inicio, $fecha_fin, $codigo_persona = null, $iddepartamento = null)
    {
        $options = '';
        if ($codigo_persona) {
            $options .= " AND a.codigo_persona='$codigo_persona'";
        }
        if ($iddepartamento) {
            $options .= " AND u.iddepartamento='$iddepartamento'";
        }
        $sql = "SELECT a.idasistencia,
                    a.codigo_persona,
                    a.fecha_hora,
                    a.tipo,
                    a.fecha,
                    a.latitud,
                    a.longitud,
                    u.nombre,
                    u.apellidos
                FROM asistencia a
                INNER JOIN usuarios u
                    ON  a.codigo_persona = u.codigo_persona
                WHERE DATE(a.fecha) >= '$fecha_inicio'
                    AND DATE(a.fecha) <= '$fecha_fin' $options";
        return ejecutarConsulta($sql);
    }

    public function reporte($person_code, $fecha_inicio, $fecha_fin)
    {
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
          AND entrada.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
        ORDER BY entrada.idasistencia";
      return consultaEnArray(ejecutarConsulta($sql));
    }
}
