<?php

use Carbon\Carbon;

require_once dirname(dirname(__DIR__)) . '/modelos/Schedule.php';

$hoy = Carbon::now();

?>

<div class="user">Colaborador: <?= $nombre_completo ?></div>

<table class="list" style="margin-top: 1rem;">
  <tr>
    <th colspan="2">Día</th>
    <th>Entrada</th>
    <th>Salida</th>
    <th>Horas Normales</th>
    <th>Horas Extra</th>
  </tr>
  <?php foreach ($horas as $hora) { ?>
    <tr>
      <td align="right"><?= $hora['fecha']->format('d-m-Y') ?></td>
      <td><?= Schedule::DAYS_OF_WEEK_NAMES[$hora['fecha']->dayOfWeek] ?></td>
      <td align="right"><?= Carbon::parse($hora['hora_entrada'])->format('H:i') ?></td>
      <td align="right"><?= Carbon::parse($hora['hora_salida'])->format('H:i') ?></td>
      <td align="right"><?= round($hora['total'], 2) ?></td>
      <td align="right"><?= round($hora['extra'], 2) ?></td>
    </tr>
  <?php } ?>
</table>
