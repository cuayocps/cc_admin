<?php

use Carbon\Carbon;
error_reporting(E_ALL);
require_once dirname(dirname(__DIR__)) . '/modelos/Schedule.php';

$hoy = Carbon::now();

?>

<style>
  * {
    font-family: Arial, Helvetica, sans-serif;
  }

  h2,
  h3 {
    margin: 1rem 0;
  }

  table {
    border-collapse: collapse;
  }

  table,
  th,
  td {
    border: none;
  }

  th,
  td {
    padding: 5px;
  }

  table.list {
    width: 100%;
  }

  table.list,
  table.list th,
  table.list td {
    border: 1px solid #cccccc;
  }
</style>


<h2>
  <center>Reporte de Asistencia</center>
</h2>
<h3>
  <center><?= $fecha['mes'] ?> de <?= $fecha['ano'] ?></center>
</h3>

<table width="100%">
  <tr>
    <td>Colaborador: <?= $usuario['nombre_completo'] ?></td>
    <td></td>
  </tr>
  <tr>
    <td>Departamento: <?= $usuario['departamento'] ?></td>
    <td align="right">
      <?= $hoy->format('d') ?> de
      <?= mes($hoy->format('m')) ?> de
      <?= $hoy->format('Y') ?>
    </td>
  </tr>
</table>

<table class="list" style="margin-top: 1rem;">
  <tr>
    <th colspan="2">Día</th>
    <th>Entrada</th>
    <th>Salida</th>
    <th>Total Horas</th>
  </tr>
  <?php foreach ($horas as $hora) { ?>
    <tr>
      <td align="right"><?= $hora['dia'] ?></td>
      <td><?= Schedule::DAYS_OF_WEEK_NAMES[$hora['dia_nombre']] ?></td>
      <td align="right"><?= Carbon::parse($hora['hora_entrada'])->format('H:i') ?></td>
      <td align="right"><?= Carbon::parse($hora['hora_salida'])->format('H:i') ?></td>
      <td align="right"><?= round($hora['total'], 2) ?></td>
    </tr>
  <?php } ?>
</table>
