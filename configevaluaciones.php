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

	//Consultas de acuerdo al perfil
	if($id_grupo==1 OR $id_grupo==3){
		//Grupo Administrador
		$lista_desplegable4= mysql_query(("SELECT * FROM componente where estado='1'"),$conexion);
		$lista_radicados= mysql_query(("
		SELECT id_acta,
		descripcion_reserva
		FROM reserva_radicado
		where
		estado='1'
		order by fecha_reserva asc
		"),$conexion); //Radicados
	}
	else{
		//Grupo Members
		$lista_desplegable4= mysql_query(("SELECT * FROM componente where id_componente='$id_componente' and estado='1'"),$conexion);
		$lista_radicados= mysql_query(("
		SELECT id_acta,
		descripcion_reserva
		FROM reserva_radicado
		where
		id_interventor='$id_interventor' and
		estado='1'
		order by fecha_reserva asc
		"),$conexion); //Radicados
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
		<!-- Estilos menú principal -->
		<link rel="stylesheet" href="css/estilos.css">

		<!-- Material Icons -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="css/jumbotron-narrow.css" rel="stylesheet">

		<!-- JavaScript para los filtros de las tablas -->

		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/jquery.tablesorter.widgets.js"></script>
		<script src="js/jquery.tablesorter.js"></script>
		<link href="css/theme.default.css" rel="stylesheet">

		<!-- Para validacion de campos -->
		<script src="js/parsley.js"></script>




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
			<h5>Seleccione los filtros para realizar la interventoría</h5>


			<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="evaluar.php">



				<select data-parsley-min="1" class="form-control" name="id_prestador" id="select1">
					<option value="0" required>Seleccione el Prestador...</option>
				</select>

				<select data-parsley-min="1" class="form-control" name="id_modalidad" id="select2">
					<option value="0" required>Seleccione la Modalidad...</option>
				</select>

				<select data-parsley-min="1" class="form-control" name="id_sede" id="select3">
					<option value="0" required>Seleccione la Sede...</option>
				</select>

				<select data-parsley-min="1" class="form-control" name="id_componente" id="select4">
					<option id="option4_js" value="0" required>Seleccione el Componente...</option>
					<?php  	while($row=mysql_fetch_assoc($lista_desplegable4)){ ?>
						<option  value="<?php  echo  $row['id_componente']; ?>"><?php echo  $row['nombre_componente']; ?></option>	<?php 	}	?>
					</select>

					<select  class="form-control" name="tipo_acta" id="select_4">
						<option value="0" >Seleccione tipo de acta...</option>
						<option value="1">Familiar-AAVN </option>
						<option value="2">Familiar-administrativas</option>
						<option value="3">Familiar-sede</option>
					</select>

					<div id="select5">
						<select data-parsley-required class="form-control" id="select50" name="acta_reservada">
							<option  value="" >Seleccione una opción...</option>
							<option  value="NO" >GENERAR NÚMERO DE ACTA AL FINALIZAR LA EVALUACIÓN</option>
							<option  value="SI" >TENGO RESERVADO UN NÚMERO DE ACTA</option>
						</select>
					</div>

					<div id="select6">
						<label class="col-sm-12 control-label" >---Seleccione un número de acta---</label>
						<select data-parsley-required class="form-control" name="id_acta" id="select60">
							<option  value="N/A" id="select600">Seleccione el radicado...</option>
							<?php  	while($row=mysql_fetch_assoc($lista_radicados)){ ?>
								<option  value="<?php  echo  $row['id_acta']; ?>"><?php echo  $row['id_acta']." : ".$row['descripcion_reserva']; ?></option>	<?php 	}	?>
							</select>
						</div>



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


					<!-->Ocultar campos
					$('#select6').fadeOut();

					$("select#select50").change(function(){
						var estado_select = $("select#select50").val();
						if(estado_select == "SI"){
							$('#select6').fadeIn();
							$('#select60').val( "" );
							$('#select600').fadeOut( );
						}
						else {
							$('#select6').fadeOut();
							$('#select60').val( "N/A" );

						}
					});



					<!-->Filtros en los encabezados de la tabla
					$("#tcreados").tablesorter({sortList: [[0,0]], headers: { }});


					<!-->Menus desplegables
					$(document).ready(function(){
						cargar_menu1();
						$("#select1").change(function(){cargar_menu2();});
						$("#select2").change(function(){cargar_menu3();});
						$("#select2").attr("disabled",true);
						$("#select3").attr("disabled",true);
						$("#select_4").attr("disabled",true);
						$("#select_4 option[value="+ 0 +"]").attr("selected",true)
					});

					function cargar_menu1()
					{
						$.get("lib/combo-configevaluaciones/option-select1.php", function(resultado){
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
							$.get("lib/combo-configevaluaciones/option-select2.php", { code: code },
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
							$.get("lib/combo-configevaluaciones/option-select3.php?", { code: code, code2: code2 },
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



					$('#select4').on('change', function(){
						debugger;
						var value_componente = $("#select4").val();
						var value_modalidad = $("#select2").val();
						if (value_componente == 7 && value_modalidad == 5) {
							$("#select_4").attr("disabled",false);	
							$('#select_4').attr('data-parsley-min', 1);
						}else{
							$("#select_4").attr("disabled",true);
							$("#select_4 option[value="+ 0 +"]").attr("selected",true)
							$('#select_4').attr('data-parsley-min', 0);
						}
					});




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
