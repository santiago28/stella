<?php
require_once 'class.mysql.php';
require_once 'index1.php';

$exportar = new Exportar();
$exportar->mes = $_GET["mes"];
$lista = $exportar->ConsultarContratos();

foreach($lista as $key=>$value)
{
		echo $value.",";
}
?>
