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
        $sql="INSERT INTO horario (fecha_registro,imagen,ubicacion)
        VALUES ('$fecha_registro','$imagen','$ubicacion')";
        //return ejecutarConsulta($sql);
        $idhorarioNew=ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        if ($idhorarioNew > 0)
        {
            $sql_detalle = "INSERT INTO usuario_horario(num_usuario, idhorario) VALUES('$numusuario', '$idhorarioNew')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }

        return $sw;
    }

    public function consultar($fecha_ini,$fecha_fin,$id_usuario){
        $sql = "select h.idhorario,h.fecha_registro,h.ubicacion,uh.num_usuario from usuario_horario uh 
        join horario h
        on h.idhorario = uh.idhorario
        where (h.fecha_registro between '$fecha_ini' and '$fecha_fin') ";

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