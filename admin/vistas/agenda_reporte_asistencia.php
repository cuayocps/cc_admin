<?php

session_start();
if (!isset($_SESSION['nombre'])) {
  return header("Location: login.html");
}
require_once '../modelos/AgendaReporte.php';
require 'header.php';
?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <h1 class="box-title">Agenda Reporte de Asistencias</h1>
        <div class="panel-body">
          <form id="reporte-asistencia-form" method="POST">
            <input type="hidden" name="id" id="id" />
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Día envío</label>
                  <div class="col-xs-8">
                    <select name="dia" id="dia" class="form-control"></select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Hora envío</label>
                  <div class="col-xs-4">
                    <select name="hora" id="hora" class="form-control">
                      <?php foreach (range(0, 23) as $d) : ?>
                        <option value="<?= $d ?>"><?= str_pad($d, 2, '0', STR_PAD_LEFT) ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="col-xs-4">
                    <select name="minuto" id="minuto" class="form-control">
                      <?php foreach (range(0, 55, 5) as $d) : ?>
                        <option value="<?= $d ?>"><?= str_pad($d, 2, '0', STR_PAD_LEFT) ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Desde</label>
                  <div class="col-xs-4">
                    <select name="desde[sm]" id="desde_sm" class="form-control"></select>
                  </div>
                  <div class="col-xs-4">
                    <select name="desde[dia]" id="desde_dia" class="form-control"></select>
                  </div>
                </div>
                <div class="form-group row mb-0">
                  <label class="col-xs-4 control-label">Hasta</label>
                  <div class="col-xs-4">
                    <select name="hasta[sm]" id="hasta_sm" class="form-control"></select>
                  </div>
                  <div class="col-xs-4">
                    <select name="hasta[dia]" id="hasta_dia" class="form-control"></select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Departamento</label>
                  <div class="col-xs-8">
                    <select name="id_departamento" id="id_departamento" class="form-control selectpicker" data-live-search="true"></select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Empleado</label>
                  <div class="col-xs-8">
                    <select name="id_usuario" id="id_usuario" class="form-control selectpicker" data-live-search="true"></select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Grupos destinatarios</label>
                  <div class="col-xs-8">
                    <select name="grupos[]" id="grupos" class="form-control selectpicker" multiple></select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Correos destinatarios</label>
                  <div class="col-xs-8">
                    <input type="text" name="correos" id="correos" class="form-control" />
                    <small class="muted">Correos separados por coma.</small>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="panel-footer bg-white">
          <div class="text-right">
            <button class="btn btn-danger" type="button" onclick="cancelar_reporte_asistencia()" id="reporte-asistencia-cancelar" style="display: none">
              <i class="fa fa-ban" aria-hidden="true"></i>
              Cancelar
            </button>
            <button class="btn btn-info" type="button" onclick="agendar_reporte_asistencia()">
              <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
              Agendar
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="box">
      <div class="box-header with-border">
        <h1 class="box-title">Reportes Agendados</h1>
        <div class="panel-body">
          <table class="table" id="reportes_agendados">
            <thead>
              <tr>
                <th></th>
                <th>Día</th>
                <th>Periodo</th>
                <th>Departamento</th>
                <th>Empleado</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>


<?php
require 'footer.php';
?>
<script src="scripts/agenda_reporte_asistencia.js"></script>
