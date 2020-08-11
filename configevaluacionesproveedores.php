f<?php

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
	if($id_grupo==1){
		//Grupo Administrador
		$lista_desplegable4= mysql_query(("SELECT * FROM componente where estado='1'"),$conexion);
	} else{
		//Grupo Members
		$lista_desplegable4= mysql_query(("SELECT * FROM componente where id_componente='$id_componente' and estado='1'"),$conexion);
	}

	$lista_desplegable5= mysql_query(("SELECT * FROM proveedor where estado='1'"),$conexion);
	$lista_radicados= mysql_query(("
	SELECT
	id_radicado,
	nombre_otro,
	descripcion_asunto
	FROM
	radicado
	WHERE
	id_radicado LIKE '%AVP%' AND
	estado='1'
	ORDER BY
	fecha_radicado asc
	"),$conexion); //Radicados





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
		<!-- Estilos menú principal -->
		<link rel="stylesheet" href="css/estilos.css">

		<!-- Material Icons -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

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
		<h2>Seleccione el Proveedor a Evaluar</h2>
		<h5>Seleccione los filtros para realizar la interventoría</h5>


		<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="evaluarproveedor.php">

			<select data-parsley-min="1" class="form-control" name="id_proveedor" id="select1">
				<option id="option1_js" value="0" required>Seleccione el Proveedor...</option>
				<?php  	while($row=mysql_fetch_assoc($lista_desplegable5)){ ?>
					<option  value="<?php  echo  $row['id_proveedor']; ?>"><?php echo  $row['nombre_proveedor']; ?></option>	<?php 	}	?>
				</select>

				<select data-parsley-min="1" class="form-control" name="id_componente" id="select4">
					<option id="option4_js" value="0" required>Seleccione el Componente...</option>
					<?php  	while($row=mysql_fetch_assoc($lista_desplegable4)){ ?>
						<option  value="<?php  echo  $row['id_componente']; ?>"><?php echo  $row['nombre_componente']; ?></option>	<?php 	}	?>
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
								<option  value="<?php  echo  $row['id_radicado']; ?>"><?php echo  $row['id_radicado']." : ".$row['nombre_otro']." - ".$row['descripcion_asunto']; ?></option>	<?php 	}	?>
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
					<center> <p> &copy; <?=date('Y')?> Sistema de Información de la interventoría Buen Comienzo | <img src="images/pascualbravo.jpg" width="200" height="60"> </p>

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
