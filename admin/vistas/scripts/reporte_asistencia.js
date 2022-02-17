function init() {
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

function reporte_asistencia(format) {
  $('#reporte-asistencia-form').attr('action', '../ajax/reporte_asistencia.php?op=' + format);
  $('#reporte-asistencia-form').submit();
}

init();
