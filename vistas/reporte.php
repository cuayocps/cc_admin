<?php
require_once dirname(__DIR__) . '/admin/config/Conexion.php';
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CC | Asistencia</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../admin/public/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../admin/public/css/font-awesome.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="../admin/public/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../admin/public/css/blue.css">
  <link rel="shortcut icon" href="../admin/public/img/favicon.ico">

</head>

<body class="hold-transition lockscreen">

  <!-- Automatic element centering -->
  <div class="lockscreen-wrapper">
    <?php
    //include '../ajax/asistencia.php'
    ?>

    <div id="errorjs"></div>

    <div class="lockscreen-logo">
      <a href="#"><b>CC</b> ASISTENCIA</a>
    </div>
    <!-- User name -->
    <div class="lockscreen-name form-row">REPORTE DE ASISTENCIA</div>

    <form action="" name="formulario" id="formulario" method="POST">
      <div class="row form-row">
        <div class="col-sm-8 col-sm-offset-2">
          <input type="text" class="form-control" name="correo" id="correo" placeholder="Correo">
        </div>
      </div>
      <div class="row form-row">
        <div class="col-sm-8 col-sm-offset-2">
          <input type="password" class="form-control" name="codigo_persona" id="codigo_persona" placeholder="ID de asistencia">
        </div>
      </div>
      <div class="row form-row">
        <div class="col-sm-3 col-sm-offset-2">
          <select class="form-control" name="ano" id="ano" placeholder="Año del reporte">
            <option value="2021">2021</option>
            <option value="2022">2022</option>
          </select>
        </div>
        <div class="col-sm-5">
          <select class="form-control" name="mes" id="mes" placeholder="Mes del reporte">
            <option value="01"><?= mes('01') ?></option>
            <option value="02"><?= mes('02') ?></option>
            <option value="03"><?= mes('03') ?></option>
            <option value="04"><?= mes('04') ?></option>
            <option value="05"><?= mes('05') ?></option>
            <option value="06"><?= mes('06') ?></option>
            <option value="07"><?= mes('07') ?></option>
            <option value="08"><?= mes('08') ?></option>
            <option value="09"><?= mes('09') ?></option>
            <option value="10"><?= mes('10') ?></option>
            <option value="11"><?= mes('11') ?></option>
            <option value="12"><?= mes('12') ?></option>
          </select>
        </div>
      </div>
      <div class="row form-row">
        <div class="col-sm-4 col-sm-offset-2">
          <button type="button" class="btn btn-md btn-default" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
        </div>
        <div class="col-sm-4 text-right">
          <button type="submit" class="btn btn-md btn-primary" id="btnEnviar"><i class="fa fa-envelope"></i> Enviar</button>
        </div>
      </div>
    </form>
    <div class="help-block text-center">
      Ingresa tu Correo, ID de asistencia y mes
    </div>
  </div>

  <!-- jQuery -->
  <script src="../admin/public/js/jquery-3.1.1.min.js"></script>
  <!-- Bootstrap 3.3.5 -->
  <script src="../admin/public/js/bootstrap.min.js"></script>
  <!-- Bootbox -->
  <script src="../admin/public/js/bootbox.min.js"></script>

  <script type="text/javascript" src="scripts/reporte.js"></script>

</body>

</html>
