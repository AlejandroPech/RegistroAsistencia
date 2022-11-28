<?php
session_start(); 
require_once "../model/Incidencia.php";

$incidencia=new Incidencia();
switch ($_GET["op"]){
    case 'getIncidencias':
        $opcion = $_POST["opcion"];
        $aRespuesta = [];
        $sError = "";
        $lError = false;
        try{
            $rspta=$incidencia->consultar($opcion);

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
    case 'insertar':
        $aRespuesta = 0;
        $sError = "";
        $lError = false;
        try{
            if (!isset($_POST["idhorario"]) || !isset($_POST["num_usuario"]) || !isset($_POST["tipo_incidencia"]) || !isset($_POST["des_peticion"]) || !isset($_POST["fecha_incidencia"])){
                $lError = true;
                $sError = "faltan parametros";
            }

            if($sError == ""){
                $idhorario = $_POST["idhorario"];
                $num_usuario = $_POST["num_usuario"];
                $tipo_incidencia = $_POST["tipo_incidencia"];
                $des_peticion = $_POST["des_peticion"];
                $fecha_incidencia = $_POST["fecha_incidencia"];
                $aRespuesta=$incidencia->insertar($idhorario, $num_usuario, $tipo_incidencia, $des_peticion, $fecha_incidencia);
            }
        }
        catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = true;
        }
        $response = [ "lError" => $lError ,"sError" => $sError, "data" => $aRespuesta];
        header('Content-type: application/json');
        echo json_encode($response);
    break;
}
?>