let DTImages;
let sfecha = "";
//Función que se ejecuta al inicio
function init(){
    document.getElementById("Images").hidden = true;
	mostrarform(false);
	// listar();
}

//Función mostrar formulario
function mostrarform(flag)
{
	//limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").hide();
	}
}

//Función Listar
function listar()
{
    sfecha = document.getElementById("fechaInicial").value;
    $.ajax({
        url: "../controller/facial.php?op=getMarcajes"
        , type: "post"
        , data: { "startTime": sfecha }
        , beforeSend: function (request) {
            // $.blockUI({ message: '<h3><img src="/assets/images/ajax-loader.gif" width="45" height="40" />Un momento por favor...</h3>' });
        }
        , success: function (jreresult, textStatus, jQxhr) {
            // $.unblockUI();
            let jresult = JSON.parse(jreresult);
            if (jresult.lError) {
                Swal.fire({
                    icon: 'info',
                    title: 'Aviso',
                    text: jresult.serror
                });
                document.getElementById("Images").hidden = true;
                return;
            }

            DTImages = $('table#tbl-Images').dataTable({
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
                "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
                language: {
                    zeroRecords: 'No hay registros para mostrar.',
                    search: 'BUSCAR',
                    emptyTable: 'La tabla está vacia.',
                    "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Ultimo",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                    }
                },
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }, 
                "columnDefs": [
                    { "name": "name", "data": "name", "className": "text-center", "targets": 0 }
                    , { "name": "dtFecha", "data": "dtFecha", "className": "text-center", "targets": 1 }
                    , { "name": "iIp", "data": "iIp", "className": "text-center", "targets": 2 }
                    , { "name": "path", "data": "path", "className": "", "targets": 3, "visible": false }
                    , {
                        "name": "sAcciones", "data": "sAcciones", "className": "", "targets": 4,
                        render: function (data, type, row, meta) {
                            return type === 'display' ?
                                `<button onclick="fnDescargarImagen('${row.path}','${row.name}')" class="btn update btn-primary select-item"><i class="mdi mdi-file-image"></i> Imagen</button>` : data;
                        }
                    }
                ],
            }).DataTable();
            document.getElementById("Images").hidden = false;
            DTImages.clear().draw();
            $.each(jresult.data, function (_index, _oData) {
                DTImages.row.add({
                    "name": _oData.name
                    , "dtFecha": _oData.date
                    , "iIp": _oData.ip
                    , "path": _oData.path
                    , "sAcciones": ""
                });
            });
            DTImages.draw();
            DTImages.columns.adjust().draw();
        },
        error: function (xhrCS, statusCS) {
            console.log(e.responseText);
        }
    });	
}

init();