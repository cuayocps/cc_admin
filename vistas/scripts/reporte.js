var video;

//funcion que se ejecuta al inicio
function init() {
  $('#formulario').on('submit', function (e) {
    e.preventDefault();
    $('#btnEnviar').prop('disabled', true);
    enviarFormulario();
  });
  $('#btnCancelar').on('click', function (e) {
    e.preventDefault();
    window.location = './asistencia.php';
  });
}

function limpiar() {
  $('#formulario')[0].reset();
  $('#btnEnviar').prop('disabled', false);
}

function enviarFormulario() {
  var formData = new FormData($('#formulario')[0]);
  $.ajax({
    url: '../ajax/reporte.php?op=descargar',
    type: 'POST',
    data: formData,
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function (send) {
      if (send.success) {
        var html = '<div class="alert alert-success text-center">Hemos enviado el reporte a su correo.</div>';
        setTimeout(function () { $('#btnCancelar').click() }, 2000);
      } else {
        var html = '<div class="alert alert-danger text-center">No fue posible generar el reporte<br>Informelo a su jefatura.</div>';
      }
      $('#errorjs').html(html);
      limpiar();
    },
    error: function (error) {
      var html = '<div class="alert alert-danger">' + error + '</div >';
      $('#errorjs').html(html);
      limpiar();
    }
  });
}

init();
