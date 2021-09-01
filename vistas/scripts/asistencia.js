var tabla;

//funcion que se ejecuta al inicio
function init() {
    $("#formulario").on("submit", function (e) {
        e.preventDefault();
        $("#btnGuardar").prop("disabled", true);
        mostrarCamara();
    });
    $('#camara').on('hidden.bs.modal', function () {
        $('#camaraBody').empty()
    })
}

function limpiar() {
    $("#codigo_persona").val('');
    $("#foto_base64").val('');
    setTimeout('document.location.reload()', 2000);
}

function mostrarCamara() {
    var video = document.createElement('video');;
    video.width = 512;
    video.height = 384;
    video.autoplay = true;
    $('#camaraBody').append(video)
    $('#camara').modal('show')
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(function (stream) {
            video.srcObject = stream;
        })
        .catch(function (error) {
            alert(error);
            return;
        })
    setTimeout(function () {
        tomarFoto(video);
    }, 2000)
}

function tomarFoto(video) {
    var canvas = document.createElement('canvas');
    canvas.width = 512;
    canvas.height = 384;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    var image_data_url = canvas.toDataURL('image/jpeg');
    $('#fotoBase64').val(image_data_url);
    video.srcObject.getTracks().forEach(function (track) {
        track.stop();
    });
    $('#camara').modal('hide')
    enviarFormulario()
}

function enviarFormulario() {
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/asistencia.php?op=registrar_asistencia",
        type: "POST",
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (registro) {
            console.log(registro)
            if (registro.error) {
                var html = '<div class="alert alert-danger">' + registro.error + '</div >';
            } else {
                var alert = registro.par ? 'info' : 'success';
                var html = '<h3><strong>Nombres: </strong> ' + registro.nombre + ' ' + registro.apellidos + '</h3>' +
                    '<div class="alert alert-' + alert + '">' + registro.tipo + ' registrado ' + registro.hora + '</div>';
            }
            $("#movimientos").html(html);
            limpiar();
        },
        error: function (error) {
            var html = '<div class="alert alert-danger">' + error + '</div >';
            $("#movimientos").html(html);
            limpiar();
        }
    });
}

init();
