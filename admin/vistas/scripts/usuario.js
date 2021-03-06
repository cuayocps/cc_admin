var tabla;

//funcion que se ejecuta al inicio
function init() {
    mostrarform(false);
    mostrarform_clave(false);
    listar();
    $("#formularioc").on("submit", function (c) {
        editar_clave(c);
    })
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })

    $("#modalSubirNomina").on('show.bs.modal', function () {
        $('#grupoNomina').show()
        $('#botonesNomina').show()
        $('#resumenNomina').hide();
        $('#cargandoNomina').hide();
        $('#formularioNomina').get(0).reset();
    });

    $("#imagenmuestra").hide();
    //mostramos los permisos
    $.post("../ajax/usuario.php?op=permisos&id=", function (r) {
        $("#permisos").html(r);
    });

    //cargamos los items al select departamento
    $.post("../ajax/departamento.php?op=selectDepartamento", function (r) {
        $("#iddepartamento").html(r);
        $('#iddepartamento').selectpicker('refresh');
        $("#Filtro__iddepartamento").html(r);
        $('#Filtro__iddepartamento').selectpicker('refresh');
    });

    //cargamos los items al select tipousuario
    $.post("../ajax/tipousuario.php?op=selectTipousuario", function (r) {
        $("#idtipousuario").html(r);
        $('#idtipousuario').selectpicker('refresh');
    });

    //cargamos los items al select grupo
    $.post("../ajax/grupo.php?op=listar", function (grupos) {
        window.listaGrupos = grupos;
    }, 'json');

    $("#formularioNomina").on('submit', function (event) {
        event.preventDefault();
        $('#cargandoNomina').show()
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'post',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                var html = '';
                var keys = Object.keys(response);
                keys.forEach(function (key) {
                    html += '<dt class="col-sm-4">' + key + '</dt>';
                    html += '<dd class="col-sm-8">' + response[key] + '</dd>';
                });
                $('#grupoNomina').hide()
                $('#botonesNomina').hide()
                $('#cargandoNomina').hide()
                $('#resumenNomina').html(html).show();
            },
        });
    });

}

//funcion limpiar
function limpiar() {
    $("#nombre").val("");
    $("#apellidos").val("");
    $("#direccion").val("");
    $("#iddepartamento").selectpicker('refresh');
    $("#idtipousuario").selectpicker('refresh');
    $("#email").val("");
    $("#login").val("");
    $("#clave").val("");
    $("#codigo_persona").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#idusuario").val("");
}

//funcion mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        $("#btnSubirNomina").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnSubirNomina").show();
    }
}

function mostrarform_clave(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formulario_clave").show();
        $("#btnGuardar_clave").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formulario_clave").hide();
        $("#btnagregar").show();
    }
}
//cancelar form
function cancelarform() {
    $("#claves").show();
    limpiar();
    mostrarform(false);
}
function cancelarform_clave() {
    limpiar();
    mostrarform_clave(false);

}
//funcion listar
function listar() {
    var iddepartamento = $("#Filtro__iddepartamento").val();
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
            url: '../ajax/usuario.php?op=listar',
            data: { iddepartamento: iddepartamento },
            type: "post",
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
//funcion para guardaryeditar
function guardaryeditar(e) {
    e.preventDefault();//no se activara la accion predeterminada
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        }
    });
    $("#claves").show();
    limpiar();
}

function editar_clave(c) {
    c.preventDefault();//no se activara la accion predeterminada
    $("#btnGuardar_clave").prop("disabled", true);
    var formData = new FormData($("#formularioc")[0]);

    $.ajax({
        url: "../ajax/usuario.php?op=editar_clave",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos);
            mostrarform_clave(false);
            tabla.ajax.reload();
        }
    });

    limpiar();
    $("#getCodeModal").modal('hide');
}

function mostrar(idusuario) {
    $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario },
        function (data, status) {
            data = JSON.parse(data);
            mostrarform(true);
            actualizaForm(data);
        });
    $.post("../ajax/usuario.php?op=permisos&id=" + idusuario, function (r) {
        $("#permisos").html(r);
    });
}

function mostrarNuevo(idusuario) {
  mostrarform(true);
  var data = {
    idusuario: '',
    nombre: '',
    iddepartamento: '',
    idtipousuario: '',
    apellidos: '',
    email: '',
    login: '',
    codigo_persona: '',
    imagen: 'default.jpg',
    grupos: []
  };
  actualizaForm(data);
}

function actualizaForm(data) {
  $("#idusuario").val(data.idusuario);
  if (data.idusuario.length == 0) {
    $("#claves").show();
  } else {
    $("#claves").hide();
  }
  $("#nombre").val(data.nombre);
  $("#iddepartamento").val(data.iddepartamento);
  $("#iddepartamento").selectpicker('refresh');
  $("#idtipousuario").val(data.idtipousuario);
  $("#idtipousuario").selectpicker('refresh');
  $("#apellidos").val(data.apellidos);
  $("#email").val(data.email);
  $("#login").val(data.login);
  $("#codigo_persona").val(data.codigo_persona);
  if (data.imagen) {
    $("#imagenmuestra").attr("src", "../files/usuarios/" + data.imagen);
    $("#imagenmuestra").show();
  }
  $("#imagenactual").val(data.imagen);
  $("#idusuario").val(data.idusuario);
  new GroupsSelector('#grupos', window.listaGrupos, data.grupos);
}

function mostrar_clave(idusuario) {
    $("#getCodeModal").modal('show');
    $("#idusuarioc").val(idusuario);
}

//funcion para desactivar
function desactivar(idusuario) {
    bootbox.confirm("??Esta seguro de desactivar este dato?", function (result) {
        if (result) {
            $.post("../ajax/usuario.php?op=desactivar", { idusuario: idusuario }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

function activar(idusuario) {
    bootbox.confirm("??Esta seguro de activar este dato?", function (result) {
        if (result) {
            $.post("../ajax/usuario.php?op=activar", { idusuario: idusuario }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

function generar(longitud) {
    long = parseInt(longitud);
    var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHIJKLMNPQRTUVWXYZ2346789";
    var contrase??a = "";
    for (i = 0; i < long; i++) contrase??a += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    $("#codigo_persona").val(contrase??a);
}

function mostrarNominaForm(idusuario) {
    $("#modalSubirNomina").modal('show');
}

init();
