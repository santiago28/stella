<?php
include("class.mysql.php");
include("class.combos.php");
$menu2 = new selects();
$menu2->code = $_GET["code"];
$option2 = $menu2->lista2();
foreach($option2 as $key=>$value)
{
		echo "<option value=\"$key\">$value</option>";
}
?>