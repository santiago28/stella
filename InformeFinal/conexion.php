<?php

$dbhost="localhost";  	// host del MySQL (generalmente localhost)
$dbusuario="root"; 		// aqui debes ingresar el nombre de usuario para acceder a la base
$dbpassword=""; 	// password de acceso para el usuario de la linea anterior
$db="bdevaluacion_2020"; 	// Seleccionamos la base con la cual trabajar




$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);
    mysql_query("SET character_set_results=utf8", $conexion);
    mb_language('uni');
    mb_internal_encoding('UTF-8');
    mysql_select_db($db, $conexion);
    mysql_query("set names 'utf8'",$conexion);


$mysqli = new mysqli($dbhost, $dbusuario, $dbpassword, $db);
if ($mysqli -> connect_errno) {
die( "Fallo la conexi�n a MySQL: (" . $mysqli -> mysqli_connect_errno()
. ") " . $mysqli -> mysqli_connect_error());
}
else {

}

?>
