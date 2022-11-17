<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Incidencia
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
    public function consultar(){
        $sql = "select * from incidencias order by fecha_creacion desc";
        $respuesta = ejecutarConsulta($sql);
        return $respuesta;
    }

    public function editarincidencia($idincidencia,$observacion,$idhorario,$num_usuario,$ubicacion,$fecha_registro){
        $sql = "update incidencias set observacion = '$observacion', fecha_atendida = NOW(), fecha_modificacion = NOW(), latendida = 1 
        where idincidencia = '$idincidencia'";
        $respuesta = ejecutarConsulta($sql);
        if($idhorario == "" || $idhorario == "0"){
            $sql="INSERT INTO horario (fecha_registro,ubicacion,num_usuario,fecha_creacion,fecha_modificacion,lactivo)
            VALUES ('$fecha_registro','$ubicacion','$num_usuario',NOW(),null,1)";
            $respuesta = ejecutarConsulta_retornarID($sql);

            $sql = "update incidencias set observacion = '$observacion', fecha_atendida = NOW(), fecha_modificacion = NOW(), latendida = 1 
            where idincidencia = '$idincidencia'";
            $respuesta = ejecutarConsulta($sql);
        }else{
            $sql = "update horario set fecha_registro = '$fecha_registro', ubicacion = '$ubicacion', fecha_modificacion = NOW(), latendida = 1 
            where idhorario = '$idhorario'";
            $respuesta = ejecutarConsulta($sql);
        }
        return $respuesta;
    }
}
?>