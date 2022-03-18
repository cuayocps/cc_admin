$.fn.selectpicker.defaults = {
  iconBase: 'fa',
  tickIcon: 'fa-check-circle-o'
};

function range (from, to, merge) {
  var range = {};
  var x = from;
  while(x<=to) {
    range[x] = x;
    ++x;
  }
  if (merge) {
    _.extend(range, merge);
  }
  return range;
}

const _week_days_ = {
  MONDAY: 'Lunes',
  TUESDAY: 'Martes',
  WEDNESDAY: 'Miercoles',
  THURSDAY: 'Jueves',
  FRIDAY: 'Viernes',
  SATURDAY: 'Sábado',
  SUNDAY: 'Domingo'
};

const date = {
  _def_: {
    CURR_MONTH: {
      text: 'Mes Actual',
      prep: ' del ',
      days: range(1, 28, { LAST_DAY: 'Ultimo día', TODAY: 'Día de ejecución' })
    },
    LAST_MONTH: {
      text: 'Mes Anterior',
      prep: ' del ',
      days: range(1, 28, { LAST_DAY: 'Ultimo día' })
    },
    CURR_WEEK: {
      text: 'Semana Actual',
      prep: ' de la ',
      days: _week_days_
    },
    LAST_WEEK: {
      text: 'Semana Anterior',
      prep: ' de la ',
      days: _week_days_
    }
  },
  __: function (key, format) {
    var text = '';
    var sm;
    if (key.toString().includes(',')) {
      var parts = key.split(',');
      sm = this._def_[parts[0]];
      text = text.concat(sm.prep).concat(sm.text).toLowerCase();
      key = parts[1];
    } else {
       sm = this._def_.CURR_MONTH.days.hasOwnProperty(key) ? this._def_.CURR_MONTH : this._def_.CURR_WEEK.days.hasOwnProperty(key) ? this._def_.CURR_WEEK : null;
    }
    if (!sm || !sm.days.hasOwnProperty(key)) {
      return key;
    }
    text = sm.days[key].toString().toLowerCase().concat(text);
    if (!format) {
      return text;
    }

    return format.replace('%', text);
  },

  selectOptions: function () {
    var options = [];
    _.each(Object.keys(date._def_), function (e) {
      options.push({
        value: e,
        text: date._def_[e].text
      });
    });
    return options;
  },

  daysSelectOptions: function (from, except) {
    var options = [];
    _.each(date._def_[from].days, function (text, value) {
      options.push({
        value: value,
        text: text
      });
    });
    if (except) {
      return _.filter(options, function (o) {
        return !except.includes(o.value);
      })
    }
    return options;
  }
}

function init() {
  $.post("../ajax/agenda_reporte_asistencia.php?op=selectGrupos", function (r) {
    $("#grupos").html(r);
    $('#grupos').selectpicker('refresh');
  });

  $.post("../ajax/asistencia.php?op=selectDepartamento", function (r) {
    $("#id_departamento").html(r);
    $('#id_departamento').selectpicker('refresh');
  });

  $('#id_departamento').on('changed.bs.select', function () {
    var id_departamento = $(this).val();
    $.post("../ajax/asistencia.php?op=selectPersona", { iddepartamento: id_departamento }, function (r) {
      $("#id_usuario").html(r);
      $('#id_usuario').selectpicker('refresh');
    });
  }).trigger('changed.bs.select');

  Html.updateOptions('#dia', _.concat(date.daysSelectOptions('CURR_MONTH', ['TODAY']), date.daysSelectOptions('CURR_WEEK')));

  Html.updateOptions('#desde_sm', date.selectOptions());
  $('#desde_sm').on('change', function () {
    var sm = $(this).val();
    Html.updateOptions('#desde_dia', date.daysSelectOptions(sm, ['TODAY']), $('#desde_dia').val());
  }).trigger('change');

  Html.updateOptions('#hasta_sm', date.selectOptions());
  $('#hasta_sm').on('change', function () {
    var sm = $(this).val();
    Html.updateOptions('#hasta_dia', date.daysSelectOptions(sm), $('#hasta_dia').val());
  }).trigger('change');

  listar_asistencia();
}

function agenda_reporte_asistencia() {
  $('#reporte-asistencia-form').attr('action', '../ajax/agenda_reporte_asistencia.php?op=guardar');
  $('#reporte-asistencia-form').submit();
}

function listar_asistencia() {
  tabla = $('#reportes_agendados').dataTable({
    aProcessing: true,
    aServerSide: true,
    dom: 'Brtip',
    buttons: [],
    ajax: {
      url: '../ajax/agenda_reporte_asistencia.php?op=listar',
      type: "get",
      dataType: "json",
      error: function (e) {
        console.error(e.responseText);
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
          return date.__(dia, 'cada %');
        }
      },
      {
        data: function (row) {
          var desde = date.__(row.desde, 'del %');
          var hasta = date.__(row.hasta, ' hasta %');
          return desde.concat(hasta);
        }
      },
      { data: 'departamento' },
      { data: 'usuario' }
    ]
  }).DataTable();
}

init();
