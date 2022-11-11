<?php
$container      = json_decode(@file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/view/scripts/config.json"),true);
switch ($_GET["op"]) {
    case 'getFaciales':
        $oParsedBody    = $_POST["startTime"];

        $aRespuesta     = [];
        $dateIni        = "";
        $datefinal      = "";
        $personId       = -1;
        $lError         = false;
        $sError         = "";
        $iHttpEstatus   = 0;
        $sLinelog       = "";
        $HandlerCURL    = null;

        try {
            date_default_timezone_set('America/Mexico_City');
            $oListIp        = $container["oListIp"];
            $sPass          = $container["pass"];
            $iLengthPost    = $container["ilengthPost"];
            $iNumAddSeconds = $container["iAddSeconds"];
            $iMinIntervalo  = $container["iMinIntervaloFechas"];
            date_default_timezone_set('America/Mexico_City');
            $dateIni = (new DateTime($oParsedBody . " 00:00:00"));
            $datefinal = (new DateTime($oParsedBody . " 23:59:59"));

            if(!$lError){
                // $interval = new DateInterval('P2Y4DT6H8M');
                $intervalo = new DateInterval('PT'. $iMinIntervalo .'M');
                $iSegundos = new DateInterval('PT'. ($iMinIntervalo * 60 - 1) .'S');

                // Sacamos los periodos entre las horas
                $periodo   = new DatePeriod($dateIni, $intervalo, $datefinal);
                
                foreach ($oListIp as $sIp) {
                    foreach( $periodo as $hora ) {

                        // Guardamos las horas intervalos 
                        $dtFecha =  $hora->format('Y-m-d H:i:s');
                        $dtFechaFin =  $hora->add($iSegundos)->format('Y-m-d H:i:s');
                    $HandlerCURL = curl_init();    
                    curl_setopt_array($HandlerCURL, 
                        array(
                            CURLOPT_URL => 'http://'.$sIp["ip"].'/newFindRecords',
                            CURLOPT_RETURNTRANSFER  => true,
                            CURLOPT_ENCODING        => '',
                            CURLOPT_MAXREDIRS       => 10,
                            CURLOPT_TIMEOUT         => 0,
                            CURLOPT_FOLLOWLOCATION  => true,
                            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST   => 'GET',
                            CURLOPT_POSTFIELDS      => 'pass='.$sPass.'&personId='.$personId.'&startTime='.$dtFecha.'&endTime='.$dtFechaFin.'&length='.$iLengthPost.'&model=-1&order=1&index=0',
                            CURLOPT_HTTPHEADER      => array('Content-Type: application/x-www-form-urlencoded')
                        )
                    );    
                    $responseCurl = curl_exec($HandlerCURL);
                    $iHttpEstatus = curl_getinfo($HandlerCURL, CURLINFO_HTTP_CODE);
                    if($iHttpEstatus != 200 ) {
                        $sError = "El request a "."http://".$sIp."/newFindRecords retorno ". strval($iHttpEstatus);
                        $lError = true;
                        continue;
                    }//fin:if	
                    else{
                        $oJsonResponse = json_decode($responseCurl);
                        curl_close($HandlerCURL);
                        if($oJsonResponse->code == "LAN_SUS-0" && $oJsonResponse->success){
                            $records = $oJsonResponse->data->records;
                            foreach ($records as $record) {
                                $record->ip = $sIp["name"];
                                $record->date = date('Y-m-d H:i:s', substr($record->time,0,strlen($record->time)-3));
                                $record->dtFakeRegistro = (new DateTime($record->date))->add(new DateInterval('PT' . $iNumAddSeconds . 'S'))->format('Y-m-d H:i:s');
                                array_push($aRespuesta, $record);
                            }//fin:foreach ($records as $record)
                        }//fin:if($oJsonResponse->code == "LAN_SUS-0" && $oJsonResponse->success)
                        else{
                            $sError = "http://".$sIp."/newFindRecords presento un error:". $oJsonResponse->msg;
                            $lError = true;
                            //$sError = $oJsonResponse->msg;
                            continue;
                        }//fin:else
                    }//fin:else
                    }
                    
                }//fin:foreach ($oListIp as $sIp)
            }//fin:if(!$lError)
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
        $iHttpEstatus   = 0;
        $sLinelog       = "";
        $HandlerCURL    = null;

        try {
            date_default_timezone_set('America/Mexico_City');
            $oListIp        = $container["oListIp"];
            $sPass          = $container["pass"];
            $iLengthPost    = $container["ilengthPost"];
            if (!isset($_POST["personId"]) || $_POST["personId"] == ""){
                $lError = true;
                $sError = "falta el parametro personId";
            }
            if (!isset($_POST["startTime"]) || $_POST["startTime"] == ""){
                $dateIni    = "0";
                $datefinal  = "0";
            }
            else if (!isset($_POST["endTime"]) || $_POST["endTime"] == ""){
                $dateIni = (new DateTime($_POST["startTime"] . "00:00:00"))->format('Y-m-d H:i:s');
                $datefinal = (new DateTime($_POST["startTime"] . "23:59:59"))->format('Y-m-d H:i:s');
            }//fin: else if 
            else {
                $dateIni    = (new DateTime($_POST["startTime"] . "00:00:00"))->format('Y-m-d H:i:s');
                $datefinal  = (new DateTime($_POST["endTime"] . "23:59:59"))->format('Y-m-d H:i:s');
            }//fin:else

            if(!$lError){
                foreach ($oListIp as $sIp) {
                    $HandlerCURL = curl_init();    
                    curl_setopt_array($HandlerCURL, 
                        array(
                            CURLOPT_URL => 'http://'.$sIp["ip"].'/newFindRecords',
                            CURLOPT_RETURNTRANSFER  => true,
                            CURLOPT_ENCODING        => '',
                            CURLOPT_MAXREDIRS       => 10,
                            CURLOPT_TIMEOUT         => 0,
                            CURLOPT_FOLLOWLOCATION  => true,
                            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST   => 'GET',
                            CURLOPT_POSTFIELDS      => 'pass='.$sPass.'&personId='.$_POST["personId"].'&startTime='.$dateIni.'&endTime='.$datefinal.'&length='.$iLengthPost.'&model=-1&order=1&index=0',
                            CURLOPT_HTTPHEADER      => array('Content-Type: application/x-www-form-urlencoded')
                        )
                    );    
                    $responseCurl = curl_exec($HandlerCURL);
                    $iHttpEstatus = curl_getinfo($HandlerCURL, CURLINFO_HTTP_CODE);
                    if($iHttpEstatus != 200 ) {
                        //$cErrorMsg  = "Call to URL failed with status $iHttpEstatus, curl_error: " . 
                        //$cErrorMsg .= curl_error($HandlerCURL) . ", curl_errno: " . curl_errno($HandlerCURL);
                        //throw new Exception($cErrorMsg);
                        $sError = "El request a "."http://".$sIp."/newFindRecords retorno ". strval($iHttpEstatus);
                        $lError = true;
                        continue;
                    }//fin:if	
                    else{
                        $oJsonResponse = json_decode($responseCurl);
                        curl_close($HandlerCURL);
                        if($oJsonResponse->code == "LAN_SUS-0" && $oJsonResponse->success){
                            $records = $oJsonResponse->data->records;
                            foreach ($records as $record) {
                                $record->ip = $sIp["name"];
                                $record->date = date('Y-m-d H:i:s', substr($record->time,0,strlen($record->time)-3));
                                array_push($aRespuesta, $record);
                            }//fin:foreach ($records as $record)
                        }//fin:if($oJsonResponse->code == "LAN_SUS-0" && $oJsonResponse->success)
                    }//fin:else
                }//fin:foreach ($oListIp as $sIp)
                if(count($aRespuesta) <= 0){
                    $sError = "No se encontro ningun registro";
                    $lError = true;
                }
            }//fin:if(!$lError)
        }//fin:try
        catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = true;
        }
        $response = [ "lError" => $lError ,"sError" => $sError, "data" => $aRespuesta];
        echo json_encode($response);
    break;
    case "downloadImageFtp":
        $aRespuesta = [];
        $sBase64    = "";
        $lError     = false;
        $sError     = "";
        $sUrlFtp    = "";
        $data       = null;
        try {
            $sPass      = $container["pass"];
            $sUser      = $container["username"];
            $uriData = parse_url($_POST["path"]);
            if(count($uriData) == 4){
                $sUrlFtp     =  $uriData["scheme"]."://".$sUser.":".$sPass."@".$uriData["host"].":".$uriData["port"];
                $sUrlFtp    .= "/data/record".$uriData["path"];
                $data        = @file_get_contents($sUrlFtp);
                if($data === FALSE){
                    $lError = true;
                    $sError = "No fue posible obtener la imagen relacionado al Empleado.";
                }//fin:if($data === FALSE)
                else{
                    $sBase64    = base64_encode($data);
                    unset($data);
                }//fin:else
            }//fin:if
            else{
                $lError = true;
                $sError = "Path Invalido";
            }//fin:else
        }//fin:try
        catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = 99;
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