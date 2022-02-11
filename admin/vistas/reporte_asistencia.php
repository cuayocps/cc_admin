<?php

session_start();
if (!isset($_SESSION['nombre'])) {
  return header("Location: login.html");
}

require 'header.php';
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <div class="box">
          <div class="box-header with-border">
            <h1 class="box-title">Reporte de Asistencias</h1>
            <div class="panel-body">
              <form id="reporte-asistencia-form" method="POST">
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Fecha Inicio</label>
                  <div class="col-xs-8">
                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?= date("Y-m"); ?>-01">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Fecha Fin</label>
                  <div class="col-xs-8">
                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?= date("Y-m-d"); ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Departamento</label>
                  <div class="col-xs-8">
                    <select name="iddepartamento" id="iddepartamento" class="form-control selectpicker" data-live-search="true"></select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xs-4 control-label">Empleado</label>
                  <div class="col-xs-8">
                    <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true"></select>
                  </div>
                </div>
                <!-- <div class="form-group row">
                  <label class="col-xs-4 control-label"></label>
                  <div class="col-xs-8">
                    <input type="hidden" name="empty" value="0">
                    <label class="control-label">
                      <input type="checkbox" name="empty" value="1">
                      Incluir departamentos y usuarios sin horas.
                    </label>
                  </div>
                </div> -->
                <div class="form-group row">
                  <div class="col-xs-12 text-right">
                    <button class="btn btn-info" type="button" onclick="reporte_asistencia_pdf();">Descargar PDF</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
require 'footer.php';
?>
<script src="scripts/reporte_asistencia.js"></script>
