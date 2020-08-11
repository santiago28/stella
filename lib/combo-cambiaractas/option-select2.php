<?php
include("class.mysql.php");
include("class.combos.php");
$menu2 = new selects();
$menu2->code = $_GET["code"];
$menu2->code1 = $_GET["code1"];
$menu2->code2 = $_GET["code2"];
$option2 = $menu2->lista2();
foreach($option2 as $key=>$value)
{
	$limite=100;
	$texto = trim($value);
	$texto = strip_tags($texto);
	$tamano = strlen($texto);
	$resultado = '';
	if($tamano <= $limite){
		$resultado = $texto;
	}else{
		$texto = substr($texto, 0, $limite);
		$palabras = explode(' ', $texto);
		$resultado = implode(' ', $palabras);
		$resultado .= '...';
	}
	echo "<option value=\"$key\" title=\"$value\">$resultado</option>";
}
?>
