<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: ./login.html");
}
else
{
require 'template/header.php';

if (isset($_SESSION['incidencias']) && $_SESSION['incidencias'] == 1)
{
?>
<br>
<!--Contenido-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h1 class="box-title">Incidencias</h1>
            <div class="box-tools pull-right">
            </div>
            <br>
          </div>
          <!-- /.box-header -->
          <!-- centro -->
          <div id="listadoregistros" data-topline="1" hidden>
            <table style="background: transparent;" width='100%' id='tbl-Incidencias' class='table dt-responsive responsive'>
              <thead>
                <th>Opciones</th>
                <th>Id Incidencia</th>
                <th>Folio empleado</th>
                <th>Tipo Incicencia</th>
                <th>Descripcion peticion</th>
                <th>Descripcion respuesta</th>
                <th>Fecha incidencia</th>
                <th>Fecha de ateción</th>
                <th>atendida</th>
                <th>idhorario</th>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div class="panel-body" id="formularioregistros" hidden>
            <form name="formulario" id="formulario" method="POST">
              <input hidden type="text" name="idincidencia" id="idincidencia">
              <input hidden type="text" name="idhorario" id="idhorario">
              <div class="container">
                <div class="row">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <label>Folio Empleado:</label>
                    <input type="hidden" name="idusuario" id="idusuario">
                    <input type="text" class="form-control" name="num_usuario" id="num_usuario" maxlength="100"
                      maxlength="11" placeholder="Folio Empleado" readonly>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Fecha Incidencia:</label>
                    <input type="datetime-local" class="form-control" name="fecha_incidencia" id="fecha_incidencia" readonly>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Ubicacion:</label>
                    <input type="text" class="form-control" name="ubicacion" id="ubicacion" maxlength="100"
                      placeholder="Ubicación">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Fecha de Registro:</label>
                    <input type="datetime-local" class="form-control" name="fecha_registro" id="fecha_registro"
                      required>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Respuesta:</label>
                    <textarea class="form-control" name="observacion" id="observacion" rows="4" value=""></textarea>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>
                      Guardar</button>
                    <button class="btn btn-danger" onclick="cancelarform()" type="button"><i
                        class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <!--Fin centro -->
        </div><!-- /.box -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!--Fin-Contenido-->
<?php
}
else
{
  require 'noacceso.php';
}

require 'template/footer.php';
?>
<script type="text/javascript" src="scripts/incidencias.js"></script>
<?php 
}
ob_end_flush();
?>

