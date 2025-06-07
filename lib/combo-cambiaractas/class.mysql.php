<?php
class MySQL
{
  var $conexion;
  function MySQL()
  {
  	if(!isset($this->conexion))
	{

		$dbhost="localhost";  	// host del MySQL (generalmente localhost)
		$dbusuario="root"; 		// aqui debes ingresar el nombre de usuario para acceder a la base
		$dbpassword=""; 	// password de acceso para el usuario de la linea anterior
		$db="bdevaluacion_2024"; 	// Seleccionamos la base con la cual trabajar

		$this->conexion = (mysql_connect($dbhost, $dbusuario, $dbpassword)) or die(mysql_error());
		mysql_query("SET character_set_results=utf8", $this->conexion);
		mb_language('uni');
		mb_internal_encoding('UTF-8');
		mysql_select_db($db, $this->conexion)or die(mysql_error());
		mysql_query("set names 'utf8'",$this->conexion);


  	}
  }

 function consulta($consulta)
 {
	$resultado = mysql_query($consulta,$this->conexion);
  	if(!$resultado)
	{
  		echo 'MySQL Error: ' . mysql_error();
	    exit;
	}
  	return $resultado;
  }

 function fetch_array($consulta)
 {
  	return mysql_fetch_array($consulta);
 }

 function num_rows($consulta)
 {
 	 return mysql_num_rows($consulta);
 }

 function fetch_row($consulta)
 {
 	 return mysql_fetch_row($consulta);
 }
 function fetch_assoc($consulta)
 {
 	 return mysql_fetch_assoc($consulta);
 }

}

?>
