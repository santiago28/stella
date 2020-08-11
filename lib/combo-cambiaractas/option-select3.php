<?php
include("class.mysql.php");
include("class.combos.php");
$menu3 = new selects();
$menu3->code = $_GET["code"];
$menu3->code1 = $_GET["code1"];
$menu3->code2 = $_GET["code2"];
$option3 = $menu3->lista3();
foreach($option3 as $key=>$value)
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
