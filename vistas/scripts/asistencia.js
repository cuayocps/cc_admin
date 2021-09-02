var video;

//funcion que se ejecuta al inicio
function init() {
    $("#formulario").on("submit", function (e) {
        e.preventDefault();
        $("#btnGuardar").prop("disabled", true);
        mostrarCamara();
    });
    $('#camara').on('shown.bs.modal', function () {
        setTimeout(function () {
            tomarFoto(video);
        }, 3000)
    })
    $('#camara').on('hidden.bs.modal', function () {
        $('#camaraBody').empty();
    })
    $("#codigo_persona").focus();
    geolocation()
}

function limpiar() {
    $("#codigo_persona").val('');
    $("#foto_base64").val('');
    setTimeout('document.location.reload()', 2000);
}

function mostrarCamara() {
    if (navigator.mediaDevices) {
        video = document.createElement('video');
        video.width = 320;
        video.height = 240;
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
    } else {
        errorjs.innerHTML = 'Tu navegador no soporta la Camara en HTML5';
        enviarFormulario();
    }
}

function tomarFoto(video) {
    var canvas = document.createElement('canvas');
    canvas.width = 320;
    canvas.height = 240;
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

function geolocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (objPosicion) {
            $('#latitud').val(objPosicion.coords.longitude);
            $('#longitud').val(objPosicion.coords.latitude);
        }, function (objError) {
            switch (objError.code) {
                case objError.POSITION_UNAVAILABLE:
                    error('La informaci&oacute;n de tu posici&oacute;n no es posible');
                    break;
                case objError.TIMEOUT:
                    error("Tiempo de espera agotado");
                    break;
                case objError.PERMISSION_DENIED:
                    error('Necesitas permitir tu localizaci&oacute;n');
                    break;
                case objError.UNKNOWN_ERROR:
                    error('Error desconocido');
                    break;
            }
        });
    } else {
        error('Tu navegador no soporta la Geolocalizaci&oacute;n en HTML5');
    }
}

function error(msg) {
    var errorjs = document.getElementById('errorjs');
    errorjs.innerHTML = msg
}
init();



