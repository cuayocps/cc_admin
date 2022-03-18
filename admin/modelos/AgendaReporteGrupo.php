<?php

require_once dirname(__DIR__) . '/config/Conexion.php';

class AgendaReporteGrupo
{
  protected $table = 'agenda_reporte_grupos';

  public function guardar($id, $grupos)
  {
    $this->eliminarSiNoExiste($id, $grupos);
    $this->agregar($id, $grupos);
  }

  public function agregar($id, array $grupos)
  {
    foreach ($grupos as $idGrupo) {
      $sql = "INSERT IGNORE INTO {$this->table}
        SET
          id_agenda_reporte = $id,
          id_grupo = $idGrupo";
      ejecutarConsulta($sql);
    }
  }

  public function eliminarSiNoExiste($id, array $grupos)
  {
    $and = '';
    if (!empty($grupos)) {
      $listaGrupos = implode(',', $grupos);
      $and = "AND id_grupo NOT IN ($listaGrupos)";
    }
    $sql = "DELETE FROM {$this->table}
      WHERE id_agenda_reporte = $id
        $and";
    ejecutarConsulta($sql);
  }

  public static function destinatarios($id)
  {
    $sql = "SELECT u.email FROM agenda_reporte_grupos arg
      INNER JOIN usuario_grupos ug ON ug.id_grupo = arg.id_grupo
      INNER JOIN usuarios u ON u.idusuario = ug.id_usuario
      WHERE arg.id_agenda_reporte = $id";
    $resultado = consultaEnArray(ejecutarConsulta($sql));

    return array_column($resultado, 'email');

  }

}
