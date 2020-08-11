<?php
include("class.mysql.php");
include("class.combos.php");

$menu5 = new selects();
$menu5->code = $_GET['code'];
$option5 = $menu5->lista6();
foreach ($option5 as $key => $value)
{
  echo "<option value=\"$value\">$value</option>";
}


?>
