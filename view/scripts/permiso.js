let tabla;

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();
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
	tabla = $('#tbllistado').dataTable(
		{
			"language": { "sUrl": "./js/dataTables_Espanol.json" },
			"aProcessing": true,//Activamos el procesamiento del datatables
			"aServerSide": true,//Paginación y filtrado realizados por el servidor
			dom: 'Bfrtip',//Definimos los elementos del control de tabla
			buttons: [
				'excelHtml5',
				'pdf',
				'print'
			],
			"ajax":
			{
				url: '../controller/permiso.php?op=listar',
				type: "get",
				dataType: "json",
				error: function (e) {
					console.log(e.responseText);
				}
			},
			"bDestroy": true,
			"iDisplayLength": 20,//Paginación
			"order": [[0, "desc"]],//Ordenar (columna,orden)
		}).DataTable();
}
init();