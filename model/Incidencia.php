<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Incidencia
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
    public function consultar($opcion){
        $sql = "select * from incidencias ";

        if($opcion != "-1"){
            if(!isset($opcion) || $opcion == ""){
                $sql .= "where latendida = '0'";
            }else{
                $sql .= "where laceptada = '$opcion'";
            }
        }
        $sql .= "order by fecha_creacion desc";
        $respuesta = ejecutarConsulta($sql);
        return $respuesta;
    }

    public function consultarIncidencias($incidencia)
    {
        $sql = "select * from incidencias where num_usuario = '$incidencia' order by fecha_creacion desc";
        $respuesta = ejecutarConsulta($sql);
        return $respuesta;
    }
    public function editarincidencia($idincidencia,$observacion,$idhorario,$num_usuario,$ubicacion,$fecha_registro,$laceptada){
        $sqlupdate = "";
        $respuesta = 0;
        if($laceptada == 1){
            if($idhorario == "" || $idhorario == "0"){
                $sql="INSERT INTO horario (fecha_registro,ubicacion,num_usuario,imagen,fecha_creacion,fecha_modificacion,lactivo)
                VALUES ('$fecha_registro','$ubicacion','$num_usuario','',NOW(),null,1);";
                $respuesta = ejecutarConsulta_retornarID($sql);
                $sqlupdate = ", idhorario = '$respuesta'";
            }else{
                $sql = "update horario set fecha_registro = '$fecha_registro', ubicacion = '$ubicacion', fecha_modificacion = NOW(), latendida = 1 
                where idhorario = '$idhorario'";
                $respuesta = ejecutarConsulta($sql);
            }
        }

        if((is_numeric($respuesta) && $respuesta > 0) || $laceptada != 1){
            $sql = "update incidencias set observacion = '$observacion', fecha_atendida = NOW(), fecha_modificacion = NOW(), latendida = 1, laceptada = '$laceptada'"; 
            $sql .= $sqlupdate ." where idincidencia = '$idincidencia'";
            $respuesta = ejecutarConsulta($sql);
        }else{
            $respuesta = false;
        }

        return $respuesta;
    }

    public function insertar($idhorario, $num_usuario, $tipo_incidencia, $des_peticion, $fecha_incidencia){
        $sql = " INSERT INTO `incidencias` (`idhorario`, `num_usuario`,`tipo_incidencia` , `observacion`, `des_peticion`, `fecha_incidencia`, `fecha_atendida`, `fecha_creacion`, `fecha_modificacion`, `lactivo`, `latendida`, `laceptada`) VALUES
        ('$idhorario','$num_usuario','$tipo_incidencia','','$des_peticion','$fecha_incidencia',null,Now(),null,1,0,null)";
        $respuesta = ejecutarConsulta_retornarID($sql);

        return $respuesta;
    }
}
?>