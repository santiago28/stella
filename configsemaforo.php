<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$nombre=$_SESSION["nombre_usuario"];
	$id_interventor=$_SESSION["login"];
	$fotoperfil = $_SESSION["fotoperfil"];


	//Menu desplegables
	$lista_desplegable= mysql_query(("
	SELECT
	semaforo.id_contrato,
	modalidad.abr_modalidad,
	prestador.nombre_prestador

	FROM
	semaforo,modalidad,prestador
	where
	semaforo.id_modalidad=modalidad.id_modalidad and
	semaforo.id_prestador=prestador.id_prestador and
	semaforo.estado='1'
	group by id_contrato
	order by nombre_prestador ASC, id_contrato DESC
	"),$conexion); //Contrato

	if($id_grupo==1){
		//Grupo Administrador
		$lista_desplegable2= mysql_query(("SELECT * FROM tema where estado='1'"),$conexion);	//Componente
	}
	else{
		//Grupo Members
		$lista_desplegable2= mysql_query(("SELECT * FROM tema where id_componente='$id_componente' and estado='1'"),$conexion); //Componente
	}

	?>


	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">

		<title>Configuraciones</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="css/jumbotron-narrow.css" rel="stylesheet">

		<!-- JavaScript para los filtros de las tablas -->

		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/jquery.tablesorter.widgets.js"></script>
		<script src="js/jquery.tablesorter.js"></script>
		<link href="css/theme.default.css" rel="stylesheet">

		<!-- Para validacion de campos -->
		<script src="js/parsley.js"></script>
		<!-- Estilos menú principal -->
		<link rel="stylesheet" href="css/estilos.css">

		<!-- Material Icons -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	</head>

	<body>
		<div class="barra-menu">
			<div class="col-md-12">
				<!-- <div class="form-group col-md-6">
				<div style="margin-left: 50%">
				<img src="images/logo_medellin.png" width="140" height="60">
			</div>
		</div> -->
		<div style="margin-top: 1%; float: right;">
			<a class="glyphicon glyphicon-home" href="homeadmin.php" style="font-size:35px; color:#ffffff; text-decoration:none;" role="button"></a>

		</div>
	</div>
</div>
<?php include("menu.php"); ?>
<div class="container">
	<div class="jumbotron">
		<h2>Seleccione el Contrato a Evaluar</h2>
		<h5>Seleccione los filtros para ingresar el análisis mensual del contrato</h5>


		<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="GET" action="evaluarsemaforo.php">
			<input type="hidden" name="msg" value="0">

			<select data-parsley-min="1" class="form-control" name="id_mes" id="select1">
				<option value="0" required>Seleccione el Mes...</option>
			</select>

			<select data-parsley-min="1" class="form-control" name="id_tema" id="select2">
				<option value="0" required>Seleccione el Componente...</option>
			</select>

			<select data-parsley-min="1" class="form-control" name="id_contrato" id="select3">
				<option value="0" required>Seleccione el Contrato...</option>
			</select>



			<button  class="btn btn-pascual" type="submit">Continuar</button>
			<br>
			<br>
		</form>


		<?php





		include "cerrarconexion.php"; ?>
	</div>



	<div class="footer">
		<center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

		</div>

	</div> <!-- /container -->


	<!-- Bootstrap core JavaScript-->
	<script>
	$(function() {

		<!-->Menus desplegables
		$(document).ready(function(){
			cargar_menu1();
			$("#select1").change(function(){cargar_menu2();});
			$("#select2").change(function(){cargar_menu3();});
			$("#select2").attr("disabled",true);
			$("#select3").attr("disabled",true);
		});

		function cargar_menu1()
		{
			$.get("lib/combo-configsemaforo/option-select1.php", function(resultado){
				if(resultado == false)
				{
					alert("Error");
				}
				else
				{
					$('#select1').append(resultado);
				}
			});
		}
		function cargar_menu2()
		{
			var code = $("#select1").val();
			var id_componente = "<?php echo $id_componente; ?>";
			var id_grupo = "<?php echo $id_grupo; ?>";
			$.get("lib/combo-configsemaforo/option-select2.php", { code: code, id_componente: id_componente, id_grupo: id_grupo },
			function(resultado)
			{
				if(resultado == false)
				{
					alert("Error");
				}
				else
				{
					$("#select2").attr("disabled",false);
					document.getElementById("select2").options.length=1;
					$('#select2').append(resultado);
				}
			}

		);
	}

	function cargar_menu3()
	{
		var code = $("#select1").val();
		var code2 = $("#select2").val();
		$.get("lib/combo-configsemaforo/option-select3.php?", { code: code, code2: code2 },
		function(resultado)
		{
			if(resultado == false)
			{
				alert("Error");
			}
			else
			{
				$("#select3").attr("disabled",false);
				document.getElementById("select3").options.length=1;
				$('#select3').append(resultado);
			}
		}
	);
}








});
</script>


<!-- Placed at the end of the document so the pages load faster -->
</body>
<script>

$(".icono-menu").click(function () {

	$(".sobre-menu-principal").fadeIn();
	$(".menu-principal").animate({
		left: "0"
	}, 500);
});

$(".sobre-menu-principal").click(function () {

	$(".sobre-menu-principal").fadeOut();
	$(".menu-principal").animate({
		left: "-1000px"
	}, 500);
});

</script>
</html>

<?php

}

else
{
	?>
	<script>
	window.location='index.php';
	</script>
	<?php
}

?>
