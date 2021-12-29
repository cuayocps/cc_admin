var tabla, schedules;

//funcion que se ejecuta al inicio
function init() {
  listar();
  $("#formularioregistros").on("submit", "#Departamento__form", function (e) {
    guardaryeditar(e);
  })
}

//funcion cancelarform
function cancelarform() {
  $("#listadoregistros").show();
  $("#btnagregar").show();
  $("#formularioregistros").empty();
  $('#formularioregistros').hide()
}

//funcion mostrar formulario
function mostrarform(html) {
  $("#listadoregistros").hide();
  $("#btnagregar").hide();
  $("#formularioregistros").html(html);
  $('#Schedule__time_from').timepicker(timepickerConfig);
  $('#Schedule__time_to').timepicker(timepickerConfig);
  $('#Schedule__form').on('submit', function(e) {
    e.preventDefault();
    addSchedule();
  })
  initScheduleTable();
  $('#formularioregistros').show()
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

function initScheduleTable() {
  schedules = $('#schedules').dataTable({
    aProcessing: true,
    aServerSide: true,
    dom: 'frt',
    ajax: {
      url: '../ajax/schedule.php?op=listar&iddepartamento=' + $('#iddepartamento').val(),
      type: 'get',
      dataType: 'json',
      error: function (e) {
          console.log(e.responseText);
      }
    },
    bDestroy: true,
    iDisplayLength: 10,
    columns: [
      {
        data: function (row) {
          return '<button class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>'
        }
      },
      {
        data: 'dia',
        render: function (dia) {
          return days[dia];
        }
      },
      { data: 'hora_inicio' },
      { data: 'hora_final' },
      {
        data: 'tolerancia',
        render: function (tolerancia) {
          return tolerancia + ' minuto' + (tolerancia == 1 ? '' : 's')
        }
      },
      { data: 'fecha_creacion' },
    ]
  }).DataTable();
  $('#schedules').on('click', '.btn.delete', function () {
    var $tr = $(this).closest('tr');
    var row = schedules.row($tr).data();
    deleteSchedule(row)
  })
}

function addSchedule() {
  $('#Schedule__add').prop('disabled', true);
  var scheduleData = $("#Schedule__form").serialize();
  $.post('../ajax/schedule.php?op=agregar', scheduleData, function (r) {
    schedules.ajax.reload();
    $('#Schedule__add').prop('disabled', false);
  }, 'json')
}

function deleteSchedule(row) {
  console.log(row)
  var scheduleData = {id: row.id};
  $.post('../ajax/schedule.php?op=eliminar', scheduleData, function (r) {
    schedules.ajax.reload();
  }, 'json')
}

init();
