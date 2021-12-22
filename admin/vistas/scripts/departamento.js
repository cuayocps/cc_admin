var tabla;

//funcion que se ejecuta al inicio
function init() {
  listar();
  $("#formularioregistros").on("submit", "form", function (e) {
    guardaryeditar(e);
  })
}

//funcion cancelarform
function cancelarform() {
  $("#listadoregistros").show();
  $("#btnagregar").show();
  $("#formularioregistros").empty();
}

//funcion mostrar formulario
function mostrarform(html) {
  $("#listadoregistros").hide();
  $("#btnagregar").hide();
  console.log(html);
  $("#formularioregistros").html(html);
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
            url: '../ajax/departamento.php?op=listar',
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

//funcion para guardaryeditar
function guardaryeditar(e) {
    e.preventDefault();//no se activara la accion predeterminada
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/departamento.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos);
            tabla.ajax.reload();
            cancelarform();
        }
    });

    cancelarform();
}

function agregar() {
    var departamento = {
      iddepartamento: null,
      nombre: '',
      descripcion:''
    };
    const editar = Template.load('departamento/edit.tpl');
    const html = editar(departamento);
    mostrarform(html);
}

function mostrar(iddepartamento) {
    $.post("../ajax/departamento.php?op=mostrar", { iddepartamento: iddepartamento },
      function (departamento) {
        const editar = Template.load('departamento/edit.tpl');
        const html = editar(departamento);
        mostrarform(html);
      }, 'json')
}


//funcion para desactivar
function desactivar(iddepartamento) {
    bootbox.confirm("¿Esta seguro de desactivar este dato?", function (result) {
        if (result) {
            $.post("../ajax/departamento.php?op=desactivar", { iddepartamento: iddepartamento }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

function activar(iddepartamento) {
    bootbox.confirm("¿Esta seguro de activar este dato?", function (result) {
        if (result) {
            $.post("../ajax/departamento.php?op=activar", { iddepartamento: iddepartamento }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

init();
