<?php
session_start(); 
require_once "../model/Incidencia.php";

$incidencia=new Incidencia();
switch ($_GET["op"]){
    case 'getIncidencias':
        $aRespuesta = [];
        $sError = "";
        $lError = false;
        try{
            $rspta=$incidencia->consultar();

            while ($reg=$rspta->fetch_object()){
                $aRespuesta[]=$reg;
            }
        }
        catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = true;
        }
        $response = [ "lError" => $lError ,"sError" => $sError, "data" => $aRespuesta];
        echo json_encode($response);
    break;
    case 'editar':
        $aRespuesta = [];
        $sError = "";
        $lError = false;
        $idincidencia = $_POST["idincidencia"];
        $observacion = $_POST["observacion"];
        $num_usuario = $_POST["num_usuario"];
        $laceptada = $_POST["laceptada"];
        
        $idhorario = isset($_POST["idhorario"])? limpiarCadena($_POST["idhorario"]):"";
        $ubicacion =  isset($_POST["ubicacion"])? limpiarCadena($_POST["ubicacion"]):"";
        $fecha_registro = isset($_POST["fecha_registro"])? limpiarCadena($_POST["fecha_registro"]):"";
        try{
            $rspta=$incidencia->editarincidencia($idincidencia,$observacion,$idhorario,$num_usuario,$ubicacion,$fecha_registro,$laceptada);

            $aRespuesta =  $rspta ? 1 : 2;
        }
        catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = true;
        }
        $response = [ "lError" => $lError ,"sError" => $sError, "data" => $aRespuesta];
        echo json_encode($response);
    break;
}
?>