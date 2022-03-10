<?php
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
  exit;
}

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
                            <h1 class="box-title">Usuarios</h1>
                            <div class="box-tools pull-right">
                                <button class="btn btn-default" onclick="mostrarNominaForm(true)" id="btnSubirNomina"><i class="fa fa-cloud-upload"></i> Cargar Nómina</button>
                                <button class="btn btn-success" onclick="mostrarNuevo()" id="btnagregar"><i class="fa fa-plus-circle"></i> Agregar</button>
                            </div>
                        </div>
                        <!--box-header-->
                        <!--centro-->
                        <div class="panel-body table-responsive" id="listadoregistros">
                            <div class="form-group row">
                                <label class="col-xs-4 col-sm-2 control-label">Departamento</label>
                                <div class="col-xs-8 col-sm-6 col-md-4 col-xl-3">
                                    <select name="iddepartamento" id="Filtro__iddepartamento" class="form-control selectpicker" data-live-search="true"></select>
                                </div>
                                <div class="col-xs-12 col-sm-2">
                                    <button class="btn btn-info" onclick="listar();">Filtrar</button>
                                </div>
                            </div>
                            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Login</th>
                                    <th>Email</th>
                                    <th>Foto</th>
                                    <th>Fecha/Registro</th>
                                    <th>Estado</th>
                                    <th>Departamento</th>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Login</th>
                                    <th>Email</th>
                                    <th>Foto</th>
                                    <th>Fecha/Registro</th>
                                    <th>Estado</th>
                                    <th>Departamento</th>
                                </tfoot>
                            </table>
                        </div>
                        <div class="panel-body" id="formularioregistros">
                            <form action="" name="formulario" id="formulario" method="POST">
                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Tipo usuario(*):</label>
                                    <select name="idtipousuario" id="idtipousuario" class="form-control select-picker" required>

                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Departamento(*):</label>
                                    <select name="iddepartamento" id="iddepartamento" class="form-control select-picker" required>

                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Nombre(*):</label>
                                    <input class="form-control" type="hidden" name="idusuario" id="idusuario">
                                    <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Apellidos(*):</label>
                                    <input class="form-control" type="text" name="apellidos" id="apellidos" maxlength="100" placeholder="Apellidos" required>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Email: </label>
                                    <input class="form-control" type="email" name="email" id="email" maxlength="70" placeholder="email">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Login(*):</label>
                                    <input class="form-control" type="text" name="login" id="login" maxlength="20" placeholder="nombre de usuario" required>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-xs-12" id="claves">
                                    <label for="">Clave de ingreso(*):</label>
                                    <input class="form-control" type="password" name="clave" id="clave" maxlength="64" placeholder="Clave">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-xs-12" id="claves">
                                  <label for="codigo_persona">Clave de asistencia(*):</label>
                                  <div class="input-group">
                                    <input class="form-control" type="text" name="codigo_persona" id="codigo_persona" maxlength="64" placeholder="Clave">
                                    <span class="input-group-btn">
                                      <button class="btn btn-info" type="button" onclick="generar(6);">Generar</button>
                                    </span>
                                  </div>
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-xs-8">
                                    <label for="">Imagen:</label>
                                    <input class="form-control filestyle" data-buttonText="Seleccionar foto" type="file" name="imagen" id="imagen">
                                    <input type="hidden" name="imagenactual" id="imagenactual">
                                </div>
                                <div class="form-group col-lg-2 col-md-2 col-xs-4">
                                  <img src="" alt="" id="imagenmuestra" style="max-width: 100%; min-height: 32px;" class="img-thumbnail pull-right">
                                </div>
                                <div class="row">
                                  <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                    <label for="">Grupos de correo:</label>
                                    <input class="form-control" type="hidden" name="grupos" id="grupos">
                                  </div>
                                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                                    <button class="btn btn-danger" onclick="cancelarform()" type="reset"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                  </div>
                                </div>
                            </form>
                        </div>

                        <!--inicio de modal editar contraseña--->
                        <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">Cambio de contraseña</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" name="formularioc" id="formularioc" method="POST">
                                            <div class="form-group">
                                                <label for="recipient-name" class="col-form-label">Password:</label>
                                                <input class="form-control" type="hidden" name="idusuarioc" id="idusuarioc">
                                                <input class="form-control" type="password" name="clavec" id="clavec" maxlength="64" placeholder="Clave" required>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button class="btn btn-primary" type="submit" id="btnGuardar_clave"><i class="fa fa-save"></i> Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--fin de modal editar contraseña--->
                        </div>

                        <div class="modal fade" id="modalSubirNomina" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">Cargar Nómina</h4>
                                    </div>
                                    <form action="../ajax/nomina/cargar.php" name="formularioNomina" id="formularioNomina" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div id="grupoNomina" class="form-group">
                                                <label for="nomina" class="col-form-label">Nómina XML:</label>
                                                <input class="form-control" type="file" name="nomina" id="nomina" required />
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <dl id="resumenNomina" class="row">
                                                    </dl>
                                                </div>
                                            </div>
                                            <h2 id="cargandoNomina" class="text-center">
                                                <i class="fa fa-circle-o-notch fa-spin"></i>
                                                cargando nómina...
                                            </h2>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="row">
                                                <div class="col-md-6 text-left">
                                                    <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
                                                </div>
                                                <div id="botonesNomina" class="col-md-6 text-right">
                                                    <a class="btn btn-default" href="../ajax/nomina/planilla.php"><i class="fa fa-cloud-download"></i> Descargar planilla</a>
                                                    <button class="btn btn-primary" type="submit" id="btnCargarNomina"><i class="fa fa-cloud-upload"></i> Cargar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.box -->

        </section>
        <!-- /.content -->
    </div>
    <?php

    require 'footer.php';
    ?>
    <script src="scripts/usuario.js"></script>
    <script src="scripts/groups-selector.js"></script>
