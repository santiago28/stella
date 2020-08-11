<?php
include("class.mysql.php");
include("class.combos.php");
$menu1 = new selects();
$menu1->id_componente = $_GET["id_componente"];
$menu1->id_grupo = $_GET["id_grupo"];
$option1 = $menu1->lista1();
foreach($option1 as $key=>$value)
{
		echo "<option value=\"$key\">$value</option>";
}
?>