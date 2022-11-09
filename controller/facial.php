<?php

switch ($_GET["op"]) {
    case 'getMarcajes':
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
            $container      = json_decode(@file_get_contents($_SERVER["DOCUMENT_ROOT"] . "./view/scripts/config.json"),true);
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

                        //$this->container->get('logger')->info("testewtewt");
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
                        $this->container->get('logger')->error("El request a "."http://".$sIp."/newFindRecords retorno ". strval($iHttpEstatus));
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
                            $sLinelog = "http://".$sIp."/newFindRecords presento un error:". $oJsonResponse->msg;
                            //$lError = true;
                            //$sError = $oJsonResponse->msg;
                            $this->container->get('logger')->error($sLinelog);
                            continue;
                        }//fin:else
                    }//fin:else
                    }
                    
                }//fin:foreach ($oListIp as $sIp)
            }//fin:if(!$lError)
        }//fin:try
        catch (Exception $Exc) {
            $sError = $Exc->getMessage();
            $lError = 99;
        }
        $response = [ "lError" => $lError ,"sError" => $sError, "data" => $aRespuesta];
        echo json_encode($response);
        break;
    default:
        # code...
        break;
}

?>