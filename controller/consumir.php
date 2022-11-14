<?php
session_start(); 
require_once "../model/Horario.php";

$horario=new Horario();
switch ($_GET["op"]){
	case 'insertarhorario':
        $fecharegistro = $_POST["fecharegistro"];
        $ubicacion = $_POST["ubicacion"];
        $imagen = $_POST["imagen"];
        $numusuario = $_POST["numusuario"];
        $rspta = false;

        $rspta=$horario->insertar($fecharegistro,$imagen,$ubicacion,$numusuario);
        echo $rspta;
	break;
}
?>