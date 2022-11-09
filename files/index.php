<?php 
//redireccionar a la vista de loginss
$uriData = parse_url($_SERVER['REQUEST_URI']);
header ('Location: '.$uriData["scheme"].'/view/login.html');
?>