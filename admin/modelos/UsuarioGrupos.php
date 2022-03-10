<?php

require_once dirname(__DIR__) . '/config/Conexion.php';
require_once __DIR__ . '/Grupos.php';

class UsuarioGrupos
{

  protected $table = 'usuario_grupos';

    //implementamos nuestro constructor
    public function __construct()
    {
    }

    //metodo insertar regiustro
    public function insertar($id_usuario, $id_grupo)
    {
        $fechacreado = date('Y-m-d H:i:s');
        $sql = "INSERT INTO {$this->table} SET id_usuario = $id_usuario, id_grupo = $id_grupo";
        return ejecutarConsulta($sql);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = $id";
        return ejecutarConsulta($sql);
    }

    public function listarIdsGrupos($id_usuario)
    {
      $sql = "SELECT id_grupo FROM {$this->table} where id_usuario = {$id_usuario}";
      $result = consultaEnArray(ejecutarConsulta($sql));
      return array_column($result, 'id_grupo');
    }

    public function set($id_usuario, array $grupos)
    {
      $gruposIds = Grupos::crearSiNoExiste($grupos);
      $gruposActuales = $this->listarIdsGrupos($id_usuario);
      $diff = array_filter($gruposIds, function ($key) use ($gruposActuales) {
        return !in_array($key, $gruposActuales);
      });
      foreach ($diff as $id_grupo) {
        $this->insertar($id_usuario, $id_grupo);
      }
    }

}
