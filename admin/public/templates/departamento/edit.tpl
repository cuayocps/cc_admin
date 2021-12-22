<form action="" name="formulario" method="POST">
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
