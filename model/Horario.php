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
}
?>