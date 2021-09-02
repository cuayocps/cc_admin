<?php
//incluir la conexion de base de datos
require "../admin/config/Conexion.php";
class Asistencia
{
    //implementamos nuestro constructor
    public function __construct()
    {
    }

    public function verificarcodigo_persona($codigo_persona)
    {
        $sql = "SELECT * FROM usuarios WHERE codigo_persona='$codigo_persona'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function seleccionarcodigo_persona($codigo_persona)
    {
        $sql = "SELECT * FROM asistencia WHERE codigo_persona = '$codigo_persona'";
        return ejecutarConsulta($sql);
    }

    public function registrar($codigo_persona, $tipo)
    {
        $fecha = date("Y-m-d");
        $sql = "INSERT INTO asistencia (codigo_persona, tipo, fecha) VALUES ('$codigo_persona', '$tipo', '$fecha')";
        $id = ejecutarConsulta_retornarID($sql);
        $sql = "SELECT * FROM asistencia WHERE idasistencia='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //listar registros
    public function listar()
    {
        $sql = "SELECT * FROM asistencia";
        return ejecutarConsulta($sql);
    }
}
