<?php
//incluir la conexion de base de datos
require_once dirname(__DIR__) . '/config/Conexion.php';

class Usuario
{

  protected $table = 'usuarios';

    //implementamos nuestro constructor
    public function __construct()
    {
    }

    //metodo insertar regiustro
    public function insertar($nombre, $apellidos, $login, $iddepartamento, $idtipousuario, $email, $clavehash, $imagen, $usuariocreado, $codigo_persona)
    {
        $fechacreado = date('Y-m-d H:i:s');
        $sql = "INSERT INTO usuarios (nombre,apellidos,login,iddepartamento,idtipousuario,email,password,imagen,fechacreado,usuariocreado,codigo_persona,estado,idmensaje) VALUES ('$nombre','$apellidos','$login','$iddepartamento','$idtipousuario','$email','$clavehash','$imagen','$fechacreado','$usuariocreado','$codigo_persona','1','0')";
        return ejecutarConsulta_retornarID($sql);
    }

    public function editar($idusuario, $nombre, $apellidos, $login, $iddepartamento, $idtipousuario, $email, $imagen, $usuariocreado, $codigo_persona)
    {
        $sql = "UPDATE usuarios SET nombre='$nombre',apellidos='$apellidos',login='$login',iddepartamento='$iddepartamento',idtipousuario='$idtipousuario',email='$email',imagen='$imagen' ,usuariocreado='$usuariocreado',codigo_persona='$codigo_persona' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }
    public function editar_clave($idusuario, $clavehash)
    {
        $sql = "UPDATE usuarios SET password='$clavehash' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }
    public function mostrar_clave($idusuario)
    {
        $sql = "SELECT idusuario, password FROM usuarios WHERE idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }
    public function desactivar($idusuario)
    {
        $sql = "UPDATE usuarios SET estado='0' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }
    public function activar($idusuario)
    {
        $sql = "UPDATE usuarios SET estado='1' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    //metodo para mostrar registros
    public function mostrar($idusuario, array $campos = null)
    {
      $campos = is_null($campos) ? '*' : implode(', ', $campos);
      $sql = "SELECT $campos FROM usuarios WHERE idusuario='$idusuario'";
      return ejecutarConsultaSimpleFila($sql);
    }

    //listar registros
    public function listar($filters)
    {
        $where = '';
        if (!empty(array_filter($filters))) {
            $and = [];
            foreach ($filters as $field => $value) {
                $and[] = "usuarios.{$field} = '$value'";
            }
            $where = 'WHERE ' . implode(' AND ', $and);
        }
        $sql = "SELECT usuarios.*, departamento.nombre as departamento FROM usuarios INNER JOIN departamento ON departamento.iddepartamento = usuarios.iddepartamento {$where}";
        return ejecutarConsulta($sql);
    }

    public function cantidad_usuario()
    {
        $sql = "SELECT count(*) nombre FROM usuarios";
        return ejecutarConsulta($sql);
    }

    //Función para verificar el acceso al sistema
    public function verificar($login, $clave)
    {
        $sql = "SELECT u.codigo_persona,u.idusuario,u.nombre,u.apellidos,u.login,u.idtipousuario,u.iddepartamento,u.email,u.imagen,u.login, tu.nombre as tipousuario FROM usuarios u INNER JOIN tipousuario tu ON u.idtipousuario=tu.idtipousuario WHERE login='$login' AND password='$clave' AND estado='1'";
        return ejecutarConsulta($sql);
    }

    public function buscar($filtros)
    {
        global $conexion;
        $filtros = array_map(function ($value) use ($conexion) {
            return mysqli_real_escape_string($conexion, $value);
        }, $filtros);
        $where = [];
        foreach ($filtros as $key => $value) {
            $where[] = "$key = '$value'";
        }
        $sql = 'SELECT * FROM usuarios WHERE ' . implode(' AND ', $where);

        return ejecutarConsultaSimpleFila($sql);
    }

    public function id($codigoPersona)
    {
      $sql = "SELECT idusuario FROM {$this->table} WHERE codigo_persona = '$codigoPersona'";
      $usuario = ejecutarConsultaSimpleFila($sql);
      return empty($usuario) ? null : $usuario['idusuario'];
    }

    public function codigo($id_usuario)
    {
      $usuario = $this->mostrar($id_usuario, ['codigo_persona']);
      return $usuario ? $usuario['codigo_persona'] : null;
    }

    public function info($codigo_persona, array $campos = null)
    {
      $campos = is_null($campos) ? "idusuario, codigo_persona, iddepartamento, nombre, CONCAT_WS(' ', nombre, apellidos) AS nombre_completo" : implode(', ', $campos);
      $sql = "SELECT $campos
        FROM {$this->table}
        WHERE codigo_persona = '$codigo_persona'";
      return ejecutarConsultaSimpleFila($sql);
    }

    public function allInfo($iddepartamento = null)
    {
      $where = '';
      if (!empty($iddepartamento)) {
        $where = "WHERE iddepartamento = '$iddepartamento'";
      }
      $sql = "SELECT idusuario, codigo_persona, iddepartamento, nombre, CONCAT_WS(' ', nombre, apellidos) AS nombre_completo
        FROM {$this->table}
        $where";
      return consultaEnArray(ejecutarConsulta($sql));
    }

}
