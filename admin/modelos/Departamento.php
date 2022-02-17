<?php

require_once dirname(__DIR__) . '/config/Conexion.php';

class Departamento
{


    //implementamos nuestro constructor
    public function __construct()
    {
    }

    //metodo insertar regiustro
    public function insertar($nombre, $descripcion, $idusuario)
    {
        date_default_timezone_set(TIMEZONE);
        $fechacreada = date('Y-m-d H:i:s');
        $sql = "INSERT INTO departamento (nombre,descripcion,fechacreada,idusuario) VALUES ('$nombre','$descripcion','$fechacreada','$idusuario')";
        return ejecutarConsulta($sql);
    }

    public function editar($iddepartamento, $nombre, $descripcion, $idusuario)
    {
        $sql = "UPDATE departamento SET nombre='$nombre',descripcion='$descripcion',idusuario='$idusuario'
          WHERE iddepartamento='$iddepartamento'";
        return ejecutarConsulta($sql);
    }
    public function desactivar($iddepartamento)
    {
        $sql = "UPDATE departamento SET fechacreada='0' WHERE iddepartamento='$iddepartamento'";
        return ejecutarConsulta($sql);
    }
    public function activar($iddepartamento)
    {
        $sql = "UPDATE departamento SET fechacreada='1' WHERE iddepartamento='$iddepartamento'";
        return ejecutarConsulta($sql);
    }

    //metodo para mostrar registros
    public function mostrar($iddepartamento, array $fields = null)
    {
      $campos = is_null($fields) ? '*' : implode(', ', $fields);
      $sql = "SELECT {$campos} FROM departamento WHERE iddepartamento='$iddepartamento'";
      return ejecutarConsultaSimpleFila($sql);
    }

    //listar registros
    public function listar()
    {
        $sql = "SELECT * FROM departamento";
        return ejecutarConsulta($sql);
    }
    //listar y mostrar en selct
    public function select()
    {
        $sql = "SELECT * FROM departamento";
        return ejecutarConsulta($sql);
    }

    public function regresaRolDepartamento($departamento)
    {
        $sql = "SELECT nombre FROM departamento where iddepartamento='$departamento'";
        return ejecutarConsulta($sql);
    }

    public function nombre($id)
    {
      $sql = "SELECT nombre FROM departamento WHERE iddepartamento = $id";
      $departamento = ejecutarConsultaSimpleFila($sql);
      return $departamento['nombre'];
    }

    public function nombres($ids)
    {
      $listaIds = implode(',', $ids);
      $sql = "SELECT iddepartamento, nombre FROM departamento WHERE iddepartamento IN ($listaIds)";
      $datos = consultaEnArray(ejecutarConsulta($sql));
      $ret = [];
      foreach ($datos as $departamento) {
        $id = $departamento['iddepartamento'];
        $ret[$id] = $departamento['nombre'];
      }
      return $ret;
    }
}
