let DTIncidencias;
$(document).ready(function () {
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })
    document.getElementById("listadoregistros").hidden = false;
    document.getElementById("formularioregistros").hidden = false;
    mostrarform(false);
    listar();
});

//Función limpiar
function limpiar() {
    $("#ubicacion").val("");
    $("#fecha_registro").val("");
    $("#observacion").val("");
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnGuardar").show();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnGuardar").hide();
    }
}

//Función Listar
function listar() {
    $.ajax({
        url: "../controller/incidencia.php?op=getIncidencias"
        , type: "get"
        , beforeSend: function (request) {
            $.blockUI({ message: '<div class="spinner-border text-primary" role="status"></div><h3>Un momento por favor...</h3>' });
        }
        , success: function (jreresult, textStatus, jQxhr) {
            $.unblockUI();
            var jresult = JSON.parse(jreresult);
            if (jresult.lError) {
                Swal.fire({ icon: 'info', title: 'Aviso', text: jresult.sError });
                return;
            }

            DTIncidencias = $('table#tbl-Incidencias').dataTable({
                "language": { "sUrl": "./js/dataTables_Espanol.json" },
                "processing": true,//Activamos el procesamiento del datatables
                "serverSide": false,//Paginación y filtrado realizados por el servidor
                dom: 'Bfrtip',//Definimos los elementos del control de tabla
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-solid fa-file-excel"></i> Excel',
                        title: 'Incidencias'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-solid fa-file-pdf"></i> DESCARGAR PDF',
                        title: 'Incidencias'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-solid fa-print"></i> IMPRIMIR',
                        title: 'Incidencias'
                    }
                ],
                "bDestroy": true,
                "pageLength": 10,//Paginación
                "responsive": true,
                "order": [[0, "desc"]],//Ordenar (columna,orden)
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                "columnDefs": [
                    {
                        "name": "sAcciones", "data": "sAcciones", "className": "", "targets": 0,
                        render: function (data, type, row, meta) {
                            return type === 'display' ?
                                `<button class="btn btn-warning" ${(row.latendida == 1)?"disabled":""} onclick='mostrar(${JSON.stringify(row)})'><i class="fas fa-edit"></i></button>` : data;
                        }
                    }
                    , { "name": "idincidencia", "data": "idincidencia", "className": "text-center", "targets": 1, "visible": false }
                    , { "name": "tipo_incidencia", "data": "tipo_incidencia", "className": "text-center", "targets": 2 }
                    , { "name": "num_usuario", "data": "num_usuario", "className": "text-center", "targets": 3 }
                    , {
                        "name": "des_peticion", "data": "des_peticion", "className": "text-center", "targets": 4,
                        render: function (data, type, row, meta) {
                            return "<textarea readonly class='text-input form-control' style='min-width: 250px;' rows='4'>" + data + "</textarea>";
                        }
                    }
                    , {
                        "name": "observacion", "data": "observacion", "className": "", "targets": 5,
                        render: function (data, type, row, meta) {
                            return "<textarea readonly class='text-input form-control' style='min-width: 250px;' rows='4'>" + data + "</textarea>";
                        }
                    }
                    , { "name": "fecha_incidencia", "data": "fecha_incidencia", "className": "text-center", "targets": 6 }
                    , { "name": "fecha_atendida", "data": "fecha_atendida", "className": "text-center", "targets": 7 }
                    , { "name": "latendida", "data": "latendida", "className": "text-center", "targets": 8, "visible": false }
                    , { "name": "idhorario", "data": "idhorario", "className": "text-center", "targets": 9, "visible": false }
                ],
            }).DataTable();
            DTIncidencias.clear().draw();
            $.each(jresult.data, function (_index, _oData) {
                DTIncidencias.row.add({
                    "sAcciones": ""
                    , "idincidencia": _oData.idincidencia
                    , "tipo_incidencia": _oData.tipo_incidencia
                    , "num_usuario": _oData.num_usuario
                    , "des_peticion": _oData.des_peticion
                    , "observacion": _oData.observacion
                    , "fecha_incidencia": _oData.fecha_incidencia
                    , "fecha_atendida": _oData.fecha_atendida
                    , "latendida": _oData.latendida
                    , "idhorario": ""
                });
            });
            DTIncidencias.draw();
            DTIncidencias.columns.adjust().draw();
        },
        error: function (xhrCS, statusCS) {
            $.unblockUI();
            console.log(e.responseText);
        }
    });
}

function mostrar(data) {
    mostrarform(true);
    $("#idincidencia").val(data.idincidencia);
    $("#idhorario").val(data.idhorario)
    $("#num_usuario").val(data.num_usuario);
    $("#observacion").val(data.observacion);
    document.getElementById("fecha_incidencia").value = data.fecha_incidencia;
}

function cancelarform() {
    limpiar();
    mostrarform(false);
}

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../controller/incidencia.php?op=editar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (jreresult) {
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
            if (jresult.data ==  1) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Se registraron los cambios',
                    showConfirmButton: false,
                    timer: 1500
                });
                mostrarform(false);
                listar();
            } else if (jresult.data == 2) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'No se pudo realizar el cambio',
                    showConfirmButton: false,
                    timer: 1500
                });
                $("#btnGuardar").prop("disabled", false);
            }else{
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Ocurrio un error en la operación',
                    showConfirmButton: false,
                    timer: 1500
                });
                $("#btnGuardar").prop("disabled", false);
            }
        },
        error: function (xhrCS, statusCS) {
            $.unblockUI();
            console.log(e.responseText);
        }
    });
    limpiar();
}