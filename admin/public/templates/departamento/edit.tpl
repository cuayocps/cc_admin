<div class="content clearfix">
  <form action="" name="Departamento__form" method="POST">
    <div class="row">
      <div class="form-group col-lg-6 col-md-6 col-xs-12">
        <label for="">Nombre</label>
        <input class="form-control" type="hidden" name="iddepartamento" id="iddepartamento" value="<%= iddepartamento %>">
        <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50" placeholder="Nombre" required value="<%= nombre %>">
      </div>
      <div class="form-group col-lg-6 col-md-6 col-xs-12">
        <label for="">Descripcion</label>
        <input class="form-control" type="text" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripcion" value="<%= descripcion %>">
      </div>
      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
        <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
      </div>
  </form>
</div>

<hr>

<h4>Horario</h4>
<form id="Schedule__form" class="form-inline">
  <input type="hidden" name="iddepartamento" value="<%= iddepartamento %>">
  <%= Html.select('date_from', days, null, {
      id: 'Schedule__date_from'
    }) %>
  <%= Html.select('date_to', days, null, {
      id: 'Schedule__date_to',
      empty: true
    }) %>
  <div class="input-group bootstrap-timepicker timepicker">
    <input class="form-control" type="text" name="time_from" id="Schedule__time_from" placeholder="00:00">
  </div>
  <div class="input-group bootstrap-timepicker timepicker">
    <input class="form-control" type="text" name="time_to" id="Schedule__time_to" placeholder="00:00">
  </div>
  <div class="input-group">
    <input type="text" class="form-control" name="tolerance" id="Schedule__tolerance" placeholder="0" aria-describedby="basic-addon2" size="3" maxlength="2">
    <label for="Schedule__tolerance" class="input-group-addon" id="basic-addon2" style="background-color: #e5e5e5;">minuto(s)</label>
  </div>
  <button class="btn btn-success btn-md" id="Schedule__add"><i class="fa fa-clock-o"></i> Agregar</button>
</form>
<hr>
<div class="row">
  <div class="col-md-12">
    <table id="schedules" class="table table-striped table-bordered table-condensed table-hover">
      <thead>
        <th></th>
        <th>Día</th>
        <th>Hora Inicio</th>
        <th>Hora Final</th>
        <th>Tolerancia</th>
        <th>Fecha/registro</th>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <th></th>
        <th>Día</th>
        <th>Hora Inicio</th>
        <th>Hora Final</th>
        <th>Tolerancia</th>
        <th>Fecha/registro</th>
      </tfoot>
    </table>
  </div>
</div>
</div>
