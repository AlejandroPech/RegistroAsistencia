<?php 
//redireccionar a la vista de loginss
header('Access-Control-Allow-Origin: ' . (isset($_SERVER['HTTP_REFERER']) ? $REFERER['scheme'].'://'.$REFERER['host'] . (isset($REFERER['port']) ? (':' . $REFERER['port']) : '') : '*'));
$uriData = parse_url($_SERVER['REQUEST_URI']);
header ('Location: '.$uriData["scheme"].'/view/login.html');
?>
