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
        case 'obtenerasistencias':
        $aRespuesta     = [];
        $aRegistros     = [];
        $rspta          = [];
        $usuario        = [];
        $dateIni        = "";
        $datefinal      = "";
        $lError         = false;
        $sError         = "";
        $fecha_actual = date("d-m-Y");
        try {
            date_default_timezone_set('America/Mexico_City');
            if (!isset($_POST["personId"]) || $_POST["personId"] == ""){
                $lError = true;
                $sError = "falta el parametro personId";
            }
            if($sError == ""){
                if (!isset($_POST["startTime"]) || $_POST["startTime"] == ""){
                    $dateIni    =  date('Y-m-d',strtotime($fecha_actual."- 7 days")) . ' 23:59:59';
                    $datefinal  = date('Y-m-d') . ' 23:59:59';
                }
                else if (!isset($_POST["endTime"]) || $_POST["endTime"] == ""){
                    $dateIni = (new DateTime($_POST["startTime"] . " 00:00:00"))->format('Y-m-d H:i:s');
                    $datefinal = (new DateTime($_POST["startTime"] . " 23:59:59"))->format('Y-m-d H:i:s');
                }//fin: else if 
                else {
                    $dateIni    = (new DateTime($_POST["startTime"] . " 00:00:00"))->format('Y-m-d H:i:s');
                    $datefinal  = (new DateTime($_POST["endTime"] . " 23:59:59"))->format('Y-m-d H:i:s');
                }//fin:else

                $usuario = $horario->consultarUsuario($_POST["personId"])->fetch_object();
                $rspta = $horario->consultar($dateIni,$datefinal,$_POST["personId"]);

                $data= Array();

                while ($reg=$rspta->fetch_object()){
                        $obj = new stdClass();
                        $obj->lIncidencia = false;
                        if($reg->tipo_registro == "e" && strtotime(substr($reg->fecha_registro,11,8)) > strtotime($usuario->hora_entrada."+ 15 minutes")){
                                $obj->lIncidencia = true;
                        }else if($reg->tipo_registro  == "s" && strtotime(substr($reg->fecha_registro,11,8)) < strtotime($usuario->hora_salida)){
                                $obj->lIncidencia = true;
                        }
                        $obj->tipo_registro = $reg->tipo_registro;
                        $obj->fecha_registro = $reg->fecha_registro;
                        $aRegistros[]=$obj;
                }
                
                $aRespuesta["registros"] = $aRegistros;
                $aRespuesta["folio"] = $usuario->num_usuario;
                $aRespuesta["hora_entrada"] = $usuario->hora_entrada;
                $aRespuesta["hora_salida"] = $usuario->hora_salida;
            }
        }//fin:try
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