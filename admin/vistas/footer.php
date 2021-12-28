  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 0.6.2
    </div>
    <strong>Copyright &copy; 2019-2021 <a target="_blank" href="http://especialistasti.cl">Especialistas TI</a></strong>
    Todo los derechos reservados.
  </footer>

  <!-- jQuery 3 -->

  <script src="../public/js/jquery-3.1.1.min.js"></script>
  <!-- Bootstrap 3.3.5 -->
  <script src="../public/js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../public/js/app.min.js"></script>

  <!-- DATATABLES -->
  <script src="../public/datatables/jquery.dataTables.min.js"></script>
  <script src="../public/datatables/dataTables.buttons.min.js"></script>
  <script src="../public/datatables/buttons.html5.min.js"></script>
  <script src="../public/datatables/buttons.colVis.min.js"></script>
  <script src="../public/datatables/jszip.min.js"></script>
  <script src="../public/datatables/pdfmake.min.js"></script>
  <script src="../public/datatables/vfs_fonts.js"></script>

  <script src="../public/js/bootbox.min.js"></script>
  <script src="../public/js/bootstrap-select.min.js"></script>
  <script src="../public/js/bootstrap-timepicker.min.js"></script>
  <script src="../public/js/filestyle.min.js"> </script>
  <script src="../public/js/lodash.min.js"></script>
  <script src="../public/js/helpers/template.js"></script>
  <script src="../public/js/helpers/html.js"></script>
  <?php
  $adminDir = preg_replace("/^\\{$_SERVER['DOCUMENT_ROOT']}/", '', dirname(__DIR__));
  ?>

  <script type="text/javascript">
    Template.setLocation('<?= $adminDir ?>/public/templates');
    Template.cache = false;
    var timepickerConfig = {
      minuteStep: 5,
      showInputs: false,
      showMeridian: false,
      disableFocus: false,
      defaultTime: false
    };
  </script>


  </body>

  </html>
