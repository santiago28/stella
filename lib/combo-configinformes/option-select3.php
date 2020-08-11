<?php
include("class.mysql.php");
include("class.combos.php");
$menu3 = new selects();
$menu3->code3 = $_GET["code2"];
$option3 = $menu3->lista3();
foreach($option3 as $key=>$value)
{
		echo "<option value=\"$key\">$value</option>";
}
?>