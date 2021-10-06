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
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">Consulta de Asistencias por Fecha</h1>
                    </div>
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <div class="form-group row">
                            <label class="col-xs-4 col-sm-2 control-label">Fecha Inicio</label>
                            <div class="col-xs-8 col-sm-4 col-md-3">
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                            <label class="col-xs-4 col-sm-2 col-md-offset-1 control-label">Fecha Fin</label>
                            <div class="col-xs-8 col-sm-4 col-md-3">
                                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xs-4 col-sm-2 control-label">Empleado</label>
                            <div class="col-xs-8 col-sm-4">
                                <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true"></select>
                            </div>
                            <label class="col-xs-4 col-sm-2 control-label">Departamento</label>
                            <div class="col-xs-8 col-sm-4">
                                <select name="iddepartamento" id="iddepartamento" class="form-control selectpicker" data-live-search="true"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-12 text-right">
                                <button class="btn btn-info" onclick="listar_asistencia();">Mostrar</button>
                            </div>
                        </div>
                        <table id="tbllistado_asistencia" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>Asistencia</th>
                                <th>Fecha/Hora</th>
                                <th>Código</th>
                                <th>Ubicación</th>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>Asistencia</th>
                                <th>Fecha/Hora</th>
                                <th>Ubicación</th>
                                <th>Código</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
require 'footer.php';
?>
<script src="scripts/asistencia.js"></script>
