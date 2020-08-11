<?php
include("class.mysql.php");
include("class.combos.php");
$menu1 = new selects();
$menu1->code = $_GET["code"];
$option1 = $menu1->lista1();
foreach($option1 as $key=>$value)
{
		echo "<option value=\"$key\">$value</option>";
}
?>