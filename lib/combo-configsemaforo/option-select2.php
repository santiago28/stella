<?php
include("class.mysql.php");
include("class.combos.php");
$menu2 = new selects();
$menu2->code = $_GET["code"];
$menu2->id_componente = $_GET["id_componente"];
$menu2->id_grupo = $_GET["id_grupo"];
$option2 = $menu2->lista2();
foreach($option2 as $key=>$value)
{
		echo "<option value=\"$key\">$value</option>";
}
?>