<?php
require_once "../model/Horario.php";
$horario=new Horario();
$container      = json_decode(@file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/view/scripts/config.json"),true);
switch ($_GET["op"]) {
    case 'getFaciales':
        $aRespuesta     = [];
        $dateIni        = "";
        $datefinal      = "";
        $lError         = false;
        $sError         = "";

        try {
            if (!isset($_POST["startTime"]) || $_POST["startTime"] == ""){
                $lError = true;
                $sError = "falta el parametro startTime";
            }
            $oParsedBody    = $_POST["startTime"];
            date_default_timezone_set('America/Mexico_City');
            $dateIni = (new DateTime($oParsedBody . " 00:00:00"));
            $datefinal = (new DateTime($oParsedBody . " 23:59:59"));

            $rspta = $horario->consultar($dateIni->format('Y-m-d H:i:s'),$datefinal->format('Y-m-d H:i:s'),0);

            $data= Array();

            while ($reg=$rspta->fetch_object()){
                $aRespuesta[]=$reg;
            }
        }//fin:try
        catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = true;
        }
        $response = [ "lError" => $lError ,"sError" => $sError, "data" => $aRespuesta];
        echo json_encode($response);
        break;
    case "getMarcajes":
        
        $aRespuesta     = [];
        $dateIni        = "";
        $datefinal      = "";
        $lError         = false;
        $sError         = "";

        try {
            date_default_timezone_set('America/Mexico_City');
            if (!isset($_POST["personId"]) || $_POST["personId"] == ""){
                $lError = true;
                $sError = "falta el parametro personId";
            }
            if($sError == ""){
                if (!isset($_POST["startTime"]) || $_POST["startTime"] == ""){
                    $dateIni    = date('Y-m-d') . ' 00:00:00';
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
    
                $rspta = $horario->consultar($dateIni,$datefinal,$_POST["personId"]);
    
                $data= Array();
    
                while ($reg=$rspta->fetch_object()){
                    $aRespuesta[]=$reg;
                }
            }
        }//fin:try
        catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = true;
        }
        $response = [ "lError" => $lError ,"sError" => $sError, "data" => $aRespuesta];
        echo json_encode($response);
    break;
    case "downloadImageFtp":
        $sBase64    = "";
        $lError     = false;
        $sError     = "";
        try {
            $idhorario = $_POST["idhorario"];

            $rspta = $horario->verImagen($idhorario);
            while ($reg=$rspta->fetch_object()){
                $sBase64 = $reg;
            }
            if(!is_string($sBase64->imagen) || !base64_decode($sBase64->imagen)){
                $sError = "No se pudo obtener la imagen";
                $lError = true;
            }
        }//fin:try
        catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = true;
        }
        $response = [ "lError" => $lError ,"sError" => $sError, "data" => $sBase64];
        echo json_encode($response);
    break;
    default:
        $response = [ "lError" => true ,"sError" => "opción no valida", "data" => null];
        echo json_encode($response);
    break;
}
?>