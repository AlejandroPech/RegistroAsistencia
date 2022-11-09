let DTImages;
let sfecha = "";
//Función que se ejecuta al inicio
function init() {
    document.getElementById("Marcajes").hidden = true;
    mostrarform(false);
}

//Función mostrar formulario
function mostrarform(flag) {
    //limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    }
    else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").hide();
    }
}

//Función Listar
function listar() {
    let oJsonPost = {};
    let sParamName = null;
    $("div#div-inputs input[data-id]").each(function (index) {
        if (this.value == "") return;
        sParamName = this.id;
        oJsonPost[sParamName] = this.value
    });// fin:each()
    $.ajax({
        url: "../controller/facial.php?op=getMarcajes"
        , type: "post"
        , data: oJsonPost
        , beforeSend: function (request) {
            $.blockUI({ message: '<div class="spinner-border text-primary" role="status"></div><h3>Un momento por favor...</h3>' });
        }
        , success: function (jreresult, textStatus, jQxhr) {
            $.unblockUI();
            var jresult = JSON.parse(jreresult);
            if (jresult.lError) {
                Swal.fire({
                    icon: 'info',
                    title: 'Aviso',
                    text: jresult.sError
                });
                document.getElementById("Images").hidden = true;
                return;
            }

            DTMarcajes = $('table#tbl-Marcaje').dataTable({
                "processing": true,//Activamos el procesamiento del datatables
                "serverSide": false,//Paginación y filtrado realizados por el servidor
                dom: 'Bfrtip',//Definimos los elementos del control de tabla
                buttons: [
                    'excelHtml5',
                    'pdf',
                    'print'
                ],
                "bDestroy": true,
                "pageLength": 10,//Paginación
                "responsive": true,
                "order": [[0, "desc"]],//Ordenar (columna,orden)
                language: {
                    zeroRecords: 'No hay registros para mostrar.',
                    search: 'BUSCAR',
                    emptyTable: 'La tabla está vacia.',
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Ultimo",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                },
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                "columnDefs": [
                    { "name": "name", "data": "name", "className": "text-center", "targets": 0 }
                    , { "name": "date", "data": "date", "className": "text-center", "targets": 1 }
                    , { "name": "ip", "data": "ip", "className": "text-center", "targets": 2 }
                    , { "name": "path", "data": "path", "className": "text-center", "targets": 3, "visible": false }
                    , {
                        "name": "sAcciones", "data": "sAcciones", "className": "", "targets": 4,
                        render: function (data, type, row, meta) {
                            return type === 'display' ?
                                `<button onclick="fnDescargarImagen('${row.path}','${row.name}')" class="btn update btn-primary"><i class="mdi mdi-file-image"></i> Imagen</button>` : data;
                        }
                    }
                ],
            }).DataTable();
            document.getElementById("Marcajes").hidden = false;
            DTMarcajes.clear().draw();
            $.each(jresult.data, function (_index, _oData) {
                DTMarcajes.row.add({
                    "name": _oData.name
                    , "date": _oData.date
                    , "ip": _oData.ip
                    , "path": _oData.path
                    , "sAcciones": ""
                });
            });
            DTMarcajes.draw();
            DTMarcajes.columns.adjust().draw();
        },
        error: function (xhrCS, statusCS) {
            console.log(e.responseText);
        }
    });
}

function fnDescargarImagen(sPath, sEmpleado) {
    $.ajax({
        url: "../controller/facial.php?op=downloadImageFtp"
        , type: "post"
        , data: { "path": sPath }
        , beforeSend: function (request) {
            $.blockUI({ message: '<div class="spinner-border text-primary" role="status"></div><h3>Un momento por favor...</h3>' });
        }
        , success: function (jresult, textStatus, jQxhr) {
            $.unblockUI();
            var jresult = JSON.parse(jresult);
            if (jresult.lError) {
                Swal.fire({ icon: 'error', title: 'Mensaje', text: jresult.sError });
            }//fin:if(jresult.lError)
            else {
                sTemplate = `<img src="data:image/png;base64, ${jresult.data}" width="400" height="600" style="display: block;margin-left: auto;margin-right: auto; ">`;
                $('div#divmodal div.modal-body').html(sTemplate);
                $('div#divmodal h5.modal-titulo').html("Empleado: " + sEmpleado);
                $('div#divmodal').modal('show');
            }//fin:else            
        }
        , error: function (xhrCS, statusCS) {
            $.unblockUI();
            Swal.fire({ icon: 'error', title: 'Aviso', text: xhrCS.status });
        }
    });
}//fin:fnDescargarImagen
init();