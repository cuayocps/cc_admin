<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
} else {
    require 'header.php';
?>
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h1 class="box-title">Departamento <button class="btn btn-success" id="btnagregar" onclick="agregar()"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
                            <div class="box-tools pull-right">

                            </div>
                        </div>
                        <!--box-header-->
                        <!--centro-->
                        <div class="panel-body table-responsive" id="listadoregistros">
                            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Fecha/registro</th>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Fecha/registro</th>
                                </tfoot>
                            </table>
                        </div>
                        <div class="panel-body" style="min-height: 400px; display:none" id="formularioregistros"></div>
                        <!--fin centro-->
                    </div>
                </div>
            </div>
            <!-- /.box -->

        </section>
        <!-- /.content -->
    </div>
    <?php


    require 'footer.php';
    require_once "../modelos/Schedule.php";

    ?>
    <script type="text/javascript">
      var days = <?= json_encode(Schedule::DAYS) ?>;
    </script>
    <script src="scripts/departamento.js"></script>
<?php
}

ob_end_flush();
?>
