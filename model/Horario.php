<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Horario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function insertar($fecha_registro,$imagen,$ubicacion,$numusuario)
    {
        $sql="INSERT INTO horario (fecha_registro,imagen,ubicacion,num_usuario,fecha_creacion,fecha_modificacion,lactivo)
        VALUES ('$fecha_registro','$imagen','$ubicacion','$numusuario',NOW(),null,1)";
        //return ejecutarConsulta($sql);
        $sw=ejecutarConsulta($sql);

        return $sw;
    }

    public function consultar($fecha_ini,$fecha_fin,$id_usuario){
        $sql = "select idhorario,fecha_registro,ubicacion,num_usuario from horario
        where (fecha_registro between '$fecha_ini' and '$fecha_fin') ";

        if($id_usuario > 0){
            $sql .= "and num_usuario = '$id_usuario'";
        }

        $respuesta = ejecutarConsulta($sql);
        return $respuesta;
    }

    public function verImagen($idhorario){
        $sql = " select imagen from horario
        where idhorario = '$idhorario'";
        $respuesta = ejecutarConsulta($sql);
        return $respuesta;
    }
}
?>