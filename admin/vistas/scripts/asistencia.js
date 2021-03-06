var tabla;

//funcion que se ejecuta al inicio
function init() {
  listar();
  listaru();
  $("#formulario").on("submit", function (e) {
    guardaryeditar(e);
  })

  //cargamos los items al select cliente
  $.post("../ajax/asistencia.php?op=selectDepartamento", function (r) {
    $("#iddepartamento").html(r);
    $('#iddepartamento').selectpicker('refresh');
  });

  $('#iddepartamento').on('changed.bs.select', function () {
    var iddepartamento = $(this).val();
    $.post("../ajax/asistencia.php?op=selectPersona", {iddepartamento: iddepartamento}, function (r) {
      $("#idcliente").html(r);
      $('#idcliente').selectpicker('refresh');
    });
  }).trigger('changed.bs.select');
}

//funcion listar
function listar() {
  tabla = $('#tbllistado').dataTable({
    "aProcessing": true,//activamos el procedimiento del datatable
    "aServerSide": true,//paginacion y filrado realizados por el server
    dom: 'Bfrtip',//definimos los elementos del control de la tabla
    buttons: [
      'copyHtml5',
      'excelHtml5',
      'csvHtml5',
      'pdf'
    ],
    "ajax":
    {
      url: '../ajax/asistencia.php?op=listar',
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//paginacion
    "order": [[0, "desc"]]//ordenar (columna, orden)
  }).DataTable();
}

function listaru() {
  tabla = $('#tbllistadou').dataTable({
    "aProcessing": true,//activamos el procedimiento del datatable
    "aServerSide": true,//paginacion y filrado realizados por el server
    dom: 'Bfrtip',//definimos los elementos del control de la tabla
    buttons: [
      'copyHtml5',
      'excelHtml5',
      'csvHtml5',
      'pdf'
    ],
    "ajax":
    {
      url: '../ajax/asistencia.php?op=listaru',
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//paginacion
    "order": [[0, "desc"]]//ordenar (columna, orden)
  }).DataTable();
}

function listar_asistencia() {
  var fecha_inicio = $("#fecha_inicio").val();
  var fecha_fin = $("#fecha_fin").val();
  var idcliente = $("#idcliente").val();
  var iddepartamento = $("#iddepartamento").val();

  tabla = $('#tbllistado_asistencia').dataTable({
    "aProcessing": true,//activamos el procedimiento del datatable
    "aServerSide": true,//paginacion y filrado realizados por el server
    dom: 'Bfrtip',//definimos los elementos del control de la tabla
    buttons: [
      'copyHtml5',
      'excelHtml5',
      'csvHtml5',
      'pdf'
    ],
    "ajax":
    {
      url: '../ajax/asistencia.php?op=listar_asistencia',
      data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idcliente: idcliente, iddepartamento: iddepartamento },
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//paginacion
    "order": [[0, "desc"]]//ordenar (columna, orden)
  }).DataTable();
}

function listar_asistenciau() {
  var fecha_inicio = $("#fecha_inicio").val();
  var fecha_fin = $("#fecha_fin").val();

  tabla = $('#tbllistado_asistenciau').dataTable({
    "aProcessing": true,//activamos el procedimiento del datatable
    "aServerSide": true,//paginacion y filrado realizados por el server
    dom: 'Bfrtip',//definimos los elementos del control de la tabla
    buttons: [
      'copyHtml5',
      'excelHtml5',
      'csvHtml5',
      'pdf'
    ],
    "ajax":
    {
      url: '../ajax/asistencia.php?op=listar_asistenciau',
      data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//paginacion
    "order": [[0, "desc"]]//ordenar (columna, orden)
  }).DataTable();
}

init();
