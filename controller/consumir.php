<?php
session_start(); 
require_once "../model/Horario.php";
require_once "../model/Incidencia.php";
require_once "../model/Usuario.php";
$horario=new Horario();
$incidencia = new Incidencia();
$usuario = new Usuario();
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
                    $obj->id_horario = $reg->idhorario;
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
    case "insertarincidencia":
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
    case "consultarincidencias":
        $lError         = false;
        $sError         = "";
        $aRespuesta     = [];
        $numusuario     = "";

        try {
            if (!isset($_POST["numusuario"]) || $_POST["numusuario"] == ""){
                $lError = true;
                $sError = "falta el parametro numusuario";
            }
            if($sError == ""){
                $numusuario = $_POST["numusuario"];
            }
            $rspta = $incidencia->consultarIncidencias($numusuario);
            while ($reg=$rspta->fetch_object()){
                $aRespuesta[]=$reg;
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
    case "iniciarsesion":
        $lError = false;
        $sError = "";
        $aRespuesta = [];
        try {
            $logina=$_POST['logina'];
            $clavea=$_POST['clavea'];
            //Hash SHA256 en la contraseña
            $clavehash = hash("SHA256",$clavea);
    
            $rspta=$usuario->verificar($logina, $clavehash);
            // num_usuario
            $fetch=$rspta->fetch_object();
            if (isset($fetch)){
                $aRespuesta["nombre"] = $fetch->nombre;
                $aRespuesta["num_usuario"] = $fetch->num_usuario;
                $aRespuesta["lValido"] = true;
            }else{
                $aRespuesta["nombre"] = "";
                $aRespuesta["num_usuario"] = "";
                $aRespuesta["lValido"] = false;
            }
        }catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = true;
        }
        $response = [ "lError" => $lError ,"sError" => $sError, "data" => $aRespuesta];
        header('Content-type: application/json');
        echo json_encode($response);
    break;
}
?>