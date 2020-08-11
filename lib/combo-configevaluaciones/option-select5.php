<?php
include("class.mysql.php");
include("class.combos.php");
$menu4 = new selects();
$menu4->code = $_GET['code'];
$option4 = $menu4->lista5();
foreach($option4 as $key=>$value)
{
		echo "<option value=\"$key\" class=\"$value\">$value</option>";
}
 ?>
