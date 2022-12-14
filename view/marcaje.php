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
if (isset($_SESSION["marcaje"]) &&  $_SESSION['marcaje'] == 1)
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
        <div id="div-filtros"><h5 style="margin-bottom: 20px;">Marcajes</h5>
            <div id="div-inputs" class="text-center row col-12 ">
                <div style="margin-bottom: 5px;" class="col-md-4 col-sm-12">
                    <input class="col-md-6" rows="1" value="Folio" style="background-color: #ffffff;"readonly="readonly" disabled="disabled" />
                    <input class="col-md-6" type="text" id="personId" data-id="personId" value="" />
                </div>
                <div style="margin-bottom: 5px;" class="col-md-4 col-sm-12">
                    <input class="col-md-6" rows="1" value="Fecha Inicio" style="background-color: #ffffff;"readonly="readonly" disabled="disabled" />
                    <input class="col-md-6" type="date" id="startTime" data-id="startTime" value="" />
                </div>
                <div style="margin-bottom: 5px;" class="col-md-4 col-sm-12">
                    <input class="col-md-6" rows="1" value="Fecha Fin" style="background-color: #ffffff;"readonly="readonly" disabled="disabled" />
                    <input class="col-md-6" type="date" id="endTime" data-id="endTime" value="" />
                </div>
            </div>
        </div>
        </br>
        <div class="d-flex justify-content-center">
            <button onclick="listar();" class="btn btn-primary">Consultar</button>
        </div>
        <div id="Marcajes" hidden>
            <div id="" class="mt-3">
                <div class="table-responsive" data-topline="1">
                    <table style="background: transparent;" width='100%' id='tbl-Marcaje' class='table dt-responsive responsive hover nowrap'>
                        <thead>
                            <tr style="color:#fffff; background: transparent;" id="">
                                <th>idhorario</th>
                                <th>Folio</th>
                                <th>Fecha de Registro</th>
                                <th>Ubicaci??n</th>
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
<script type="text/javascript" src="scripts/marcaje.js"></script>
<?php 
}
ob_end_flush();
?>