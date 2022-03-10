<?php

require_once dirname(__DIR__) . '/config/Conexion.php';

class Grupos
{

  protected $table = 'grupos';

    public function insertar($nombre)
    {
        $sql = "INSERT INTO {$this->table} SET nombre = '$nombre'";
        return ejecutarConsulta_retornarID($sql);
    }

    public function listar()
    {
      $sql = "SELECT id, nombre FROM {$this->table}";
      $result = consultaEnArray(ejecutarConsulta($sql));
      return array_column($result, 'nombre', 'id');
    }

    public function listarIds(array $ids)
    {
      $idsGrupos = implode("','", $ids);
      $sql = "SELECT id FROM {$this->table} where id in ('{$idsGrupos}')";
      $result = consultaEnArray(ejecutarConsulta($sql));
      return array_column($result, 'id');
    }

    /**
     * Agrega los grupos de la lista que no existen
     * @param array $grupos lista de grupos ["id" => "name"]
     * @return array lista de IDs existente y nuevos
     */
    static public function crearSiNoExiste(array $grupos)
    {
      $self = new static();
      $ids = $self->listarIds(array_keys($grupos));
      $diff = array_filter($grupos, function ($key) use ($ids) {
        return !in_array($key, $ids);
      }, ARRAY_FILTER_USE_KEY);
      foreach ($diff as $key => $nombre) {
        $ids[] = $self->insertar($nombre);
      }
      return $ids;
    }

}
