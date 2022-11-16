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
if (isset($_SESSION["facial"]) &&  $_SESSION['facial'] == 1)
{
?>
<br>
<!--Contenido-->
<div class="modal fade" id="divmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center modal-titulo">Aviso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div><!-- fin:[div#ModalAlertas] -->
<div class="content-wrapper">
  <section class="content">
    <div class="col-12 row" style="display: flex; justify-content: space-around; align-items: center;"  >
        <div class="col-4"  >
            <div>
                <label class="text-center">Filtrar por fecha</label>
                <input type="date" name="fecha" id="fechaInicial" value="" class="form-control" rows="4" style="color:white;" required maxlength="55"></input>
            </div>
        </div>
        <div  >
            <button onclick="listar();" class="btn btn-primary">Consultar</button>
        </div>
    </div>
    <br />
    <div id="Images" hidden>
        <div class="table-responsive" data-topline="1">
                <table style="background: transparent;" width='100%' id='tbl-Images' class='table dt-responsive responsive'>
                    <thead>
                        <tr style="color:#fffff; background: transparent;" id="">
                            <th>idhorario</th>
                            <th>num_usuario</th>
                            <th>fecha_registro</th>
                            <th>ubicacion</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody style="color:#fffff; background: transparent;" id=""></tbody>
                </table>
            </div>
        </div>
    </div>
  </section>
</div>
<!--Fin-Contenido-->
<?php
}
else
{
  require 'noacceso.php';
}
require 'template/footer.php';
?>
<script type="text/javascript" src="scripts/facial.js"></script>
<?php 
}
ob_end_flush();
?>