<?php

session_start();
if ($_SESSION['login'])
{

//Includes de conexion a la BD y a las funciones
include "conexion.php";
include("./lib/clsFunciones.php");

//Variables Globales
$id_grupo=$_SESSION["grupo"];
$id_componente=$_SESSION["componente"];
$nombre=$_SESSION["nombre_usuario"];



//Variables GET
$id_eliminar=$_REQUEST['eliminar'];
$caso=$_REQUEST['caso'];


//Casos para rutear los deletes de acuerdo a su origen
//caso 1: Deshabilitar Tema
//caso 2: Deshabilitar Subtema
//caso 3: Deshabilitar pregunta
//caso 4: Deshabilitar acta
//caso 5: Deshabilitar usuario
//caso 6: Deshabilitar Radicado Reservado
//caso 7: Deshabilitar acta Proveedor



switch ($caso) {
case "1":

	$clsFunciones = new clsFunciones;
	$delete = $clsFunciones->deletetema($id_eliminar);
	$regresar="configtemas.php?";

	break;

case "2":

	$clsFunciones = new clsFunciones;
	$delete = $clsFunciones->deletesubtema($id_eliminar);
	$regresar="configsubtemas.php?";

	break;

case "3":

	$clsFunciones = new clsFunciones;
	$delete = $clsFunciones->deletepreguntaxmodalidad($id_eliminar);
	$regresar="configpreguntas.php?";

	break;

case "4":

	$clsFunciones = new clsFunciones;
	$delete = $clsFunciones->deleteacta($id_eliminar);
	$regresar="imprimiractas.php?id_acta=".$id_eliminar;

	break;

case "5":

	$clsFunciones = new clsFunciones;
	$delete = $clsFunciones->deleteusuario($id_eliminar);
	$regresar="creacionusuarios.php?id=0";

	break;

case "6":

	$clsFunciones = new clsFunciones;
	$delete = $clsFunciones->deleteradicadoreservado($id_eliminar);
	$regresar="reservaradicado.php?";

	break;

case "7":

	$clsFunciones = new clsFunciones;
	$delete = $clsFunciones->deleteactaproveedor($id_eliminar);
	$regresar="imprimiractasproveedor.php?id_acta=".$id_eliminar;

	break;

case "22":

	$clsFunciones = new clsFunciones;
	$delete = $clsFunciones->deleteactadescuentos($id_eliminar);
	$regresar="descontaractas.php?id_acta=".$acta;

	break;

  case "23":
		$id_descuento = $_POST['id_descuento'];
		$clsFunciones = new clsFunciones;
		$delete = $clsFunciones->deletedescuento($id_descuento);
		$regresar="descuentosValoracion.php";
		header('Location: '.$regresar);
		return true;
	break;

} //End Case

		if ($delete){
			header('Location: '.$regresar.'&msg=1');
		}
		else{
			header('Location: '.$regresar.'&msg=2');
		}




} //End If login

else {
	header('Location: index.php');

}

?>
