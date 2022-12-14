<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Usuario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos,$codigousuario,$horainicio,$horafin)
	{
		$sql = " select * from usuario u 
		where nombre = '$nombre' || login = '$login'";
		$oUsuario = ejecutarConsulta($sql);
		if($oUsuario->fetch_object() != null) return false;
		$sql="INSERT INTO usuario (nombre,telefono,email,cargo,login,clave,imagen,condicion,num_usuario,hora_entrada,hora_salida)
		VALUES ('$nombre','$telefono','$email','$cargo','$login','$clave','$imagen','1','$codigousuario','$horainicio','$horafin')";
		//return ejecutarConsulta($sql);
		$idusuarionew=ejecutarConsulta_retornarID($sql);

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($permisos))
		{
			$sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuarionew', '$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
		}

		return $sw;
	}

	//Implementamos un método para editar registros
	public function editar($idusuario,$nombre,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos,$codigousuario,$horainicio,$horafin)
	{
		$sql = " select * from usuario u 
		where nombre = '$nombre' || login = '$login'";
		$oUsuario = ejecutarConsulta($sql)->fetch_object();
		if($oUsuario != null && $oUsuario->idusuario != $idusuario) return false;

		$sql="UPDATE usuario SET nombre='$nombre',telefono='$telefono',email='$email',cargo='$cargo',login='$login',clave='$clave',imagen='$imagen',num_usuario='$codigousuario',hora_entrada='$horainicio',hora_salida='$horafin' WHERE idusuario='$idusuario'";
		ejecutarConsulta($sql);

		//Eliminamos todos los permisos asignados para volverlos a registrar
		$sqldel="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
		ejecutarConsulta($sqldel);

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($permisos))
		{
			$sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuario', '$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
		}

		return $sw;
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idusuario)
	{
		$sql="UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idusuario)
	{
		$sql="UPDATE usuario SET condicion='1' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idusuario)
	{
		$sql="SELECT * FROM usuario WHERE idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM usuario";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los permisos marcados
	public function listarmarcados($idusuario)
	{
		$sql="SELECT up.*,(p.nombre) FROM usuario_permiso up INNER JOIN permiso p ON up.idpermiso = p.idpermiso WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Función para verificar el acceso al sistema
	public function verificar($login,$clave)
    {
    	$sql="SELECT u.num_usuario, u.idusuario,u.nombre,u.telefono,u.email,u.cargo,u.imagen,u.login, u.clave FROM usuario u 
		WHERE u.login='$login' AND u.clave='$clave' AND u.condicion='1'"; 
    	return ejecutarConsulta($sql);  
    }

	public function select()
	{
		$sql="SELECT idusuario, concat(nombre,' ',cargo) as nombres  FROM usuario where condicion=1;";
		return ejecutarConsulta($sql);		
	}

	public function selectHorario(){
		$sql="SELECT idusuario, concat(nombre,' ',cargo) as nombres  FROM usuario where condicion=1;";
		return ejecutarConsulta($sql);	
	}
}
?>