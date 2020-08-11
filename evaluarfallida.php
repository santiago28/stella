<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";

	//Variables Globales declaradas
	$id_grupo=$_SESSION["grupo"];
	$nombre=$_SESSION["nombre_usuario"];
	$id_interventor=$_SESSION["login"];
	$fotoperfil = $_SESSION["fotoperfil"];
	//Variables recibidas del _POST
	$id_prestador=$_POST['id_prestador'];
	$id_modalidad=$_POST['id_modalidad'];;
	$id_sede=$_POST['id_sede'];
	$id_componente=$_POST['id_componente'];
	$acta_reservada=$_POST['acta_reservada'];
	$id_acta=$_POST['id_acta'];

	//Query para hallar las variables faltantes
	$data= mysql_query(("
	SELECT
	contrato_x_sede.id_contrato,
	prestador.nombre_prestador,
	modalidad.nombre_modalidad,
	sede.nombre_sede,
	sede.direccion_sede,
	sede.barrio_sede,
	sede.telefono_sede
	FROM
	contrato_x_sede,prestador,modalidad,sede
	WHERE
	contrato_x_sede.id_prestador=prestador.id_prestador and
	contrato_x_sede.id_modalidad=modalidad.id_modalidad and
	contrato_x_sede.id_sede=sede.id_sede and
	contrato_x_sede.id_prestador='$id_prestador' and
	contrato_x_sede.id_modalidad='$id_modalidad' and
	contrato_x_sede.id_sede='$id_sede'
	"),$conexion);

	while($row=mysql_fetch_assoc($data)){
		$id_contrato=$row['id_contrato'];
		$nombre_prestador=$row['nombre_prestador'];
		$nombre_modalidad=$row['nombre_modalidad'];
		$nombre_sede=$row['nombre_sede'];
		$direccion_sede=$row['direccion_sede'];
		$barrio_sede=$row['barrio_sede'];
		$telefono_sede=$row['telefono_sede'];
	}

	//Para pasar variables del componente al Insert
	$data2= mysql_query(("
	SELECT
	id_codigoacta,
	nombre_componente
	FROM
	componente
	WHERE
	id_componente='$id_componente'
	"),$conexion);

	while($row=mysql_fetch_assoc($data2)){
		$id_codigoacta=$row['id_codigoacta'];
		$nombre_componente=$row['nombre_componente'];
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

		<title>Diligenciar Evaluaciones</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/datepicker.css" rel="stylesheet">

		<!-- Para validacion de campos -->
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/parsley.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		<!-- Estilos menú principal -->
		<link rel="stylesheet" href="css/estilos.css">

		<!-- Material Icons -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


		<!-- Custom styles for this template -->
		<link href="css/jumbotron-narrow.css" rel="stylesheet">


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
	<?php if($acta_reservada=="SI"){ ?>
		<div class="bs-docs-section">
			<h1 id="tables-example">ACTA # <strong><?php echo $id_acta; ?></strong></h1>
		</div>
	<?php } else{ ?>

		<div class="bs-docs-section">
			<h1 id="tables-example"><strong>EL NÚMERO DE ACTA SE GENERARÁ AL FINALIZAR LA EVALUACIÓN</strong></h1>
		</div>
	<?php } ?>

	<div class="bs-docs-section">
		<h3 id="tables-example">Información General del Contrato: <strong><?php echo $id_contrato; ?></strong></h3>
	</div>

	<div class="footer"></div>

	<div class="alert alert-info" role="alert">
		<strong>¡Señor(a) interventor(a)!</strong> Si los datos relacionados con el contrato presentan alguna inconsistencia, no diligencie la evaluación y póngase en contacto con el administrador del sistema
	</div>





	<div class="row">

		<div class="col-sm-4">
			<div class="panel panel-primary">
				<div class="panel-heading"><h3 class="panel-title">Componente</h3></div>
				<div class="panel-body"><?php echo $nombre_componente;  ?></div>
			</div>
		</div><!-- /.col-sm-4 -->

		<div class="col-sm-4">
			<div class="panel panel-primary">
				<div class="panel-heading"><h3 class="panel-title">Prestador</h3></div>
				<div class="panel-body"><?php echo $nombre_prestador;  ?></div>
			</div>
		</div><!-- /.col-sm-4 -->

		<div class="col-sm-4">
			<div class="panel panel-primary">
				<div class="panel-heading"><h3 class="panel-title">Modalidad</h3></div>
				<div class="panel-body"><?php echo $nombre_modalidad;  ?></div>
			</div>
		</div><!-- /.col-sm-4 -->

	</div><!-- row -->

	<div class="bs-docs-section">
		<h3 id="tables-example">Información de la Sede </h3>
	</div>
	<div class="footer"></div>

	<div class="alert alert-success" role="alert">
		<strong>¡Señor(a) interventor(a)!</strong> Verifique los datos para esta evaluación. Si hay alguna modificación en la información de la sede, por favor realice el cambio.
	</div>



	<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">


		<div class="row">

			<div class="col-sm-4">
				<div class="panel panel-success">
					<div class="panel-heading"><h3 class="panel-title">Sede</h3></div>
					<div class="panel-body"><textarea name="nombre_sede"  style="width:100%"><?php echo $nombre_sede;  ?></textarea></div>
				</div>
			</div><!-- /.col-sm-4 -->

			<div class="col-sm-4">
				<div class="panel panel-success">
					<div class="panel-heading"><h3 class="panel-title">Teléfono Sede</h3></div>
					<div class="panel-body"><textarea name="telefono_sede"  style="width:100%"><?php echo $telefono_sede;  ?></textarea></div>
				</div>
			</div><!-- /.col-sm-4 -->

			<div class="col-sm-4">
				<div class="panel panel-success">
					<div class="panel-heading"><h3 class="panel-title">Dirección Sede</h3></div>
					<div class="panel-body"><textarea name="direccion_sede"  style="width:100%"><?php echo $direccion_sede." - ".$barrio_sede;  ?></textarea></div>
				</div>
			</div><!-- /.col-sm-4 -->

		</div><!-- row -->


		<div class="bs-docs-section">
			<h3 id="tables-example">Datos de la Evaluación </h3>
		</div>
		<div class="footer"></div>

		<div class="alert alert-warning" role="alert">
			<strong>¡Señor(a) interventor(a)!</strong> Diligencie los siguientes datos para continuar. No deben quedar casillas en blanco
		</div>


		<center>
			<div class="form-inline">

				<div class="form-group">
					<input data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/" class="form-control input-lg datepicker" name="fecha_evaluacion" type="text" value="<?php date('Y-m-d'); ?>" placeholder="Fecha (Ej: aaaa-mm-dd)" required>
				</div>

				<br></br>

				<div class="form-group">
					<input data-parsley-pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" name="hora_inicio" type="text" class="form-control input-lg" id="exampleInputEmail2" placeholder="Hora Inicio (Ej: 00:00)" required >
				</div>

				<div class="form-group">
					<input data-parsley-pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" name="hora_fin" type="text"  class="form-control input-lg" placeholder="Hora Fin (Ej: 24:00)" required >
				</div>

				<br></br>

				<div class="form-group">
					<textarea class="form-control" name="observacion_interventor" rows="3" style="width:470px" placeholder="Observaciones Generales del Interventor"required ></textarea>
				</div>


				<br></br>

			</div>
		</center>

		<input type="hidden" name="caso" value="14">
		<input type="hidden" name="id_componente" value="<?php echo $id_componente; ?>">
		<input type="hidden" name="id_codigoacta" value="<?php echo $id_codigoacta; ?>">
		<input type="hidden" name="id_contrato" value="<?php echo $id_contrato; ?>">
		<input type="hidden" name="id_sede" value="<?php echo $id_sede; ?>">
		<input type="hidden" name="id_prestador" value="<?php echo $id_prestador; ?>">
		<input type="hidden" name="id_modalidad" value="<?php echo $id_modalidad; ?>">
		<input type="hidden" name="id_interventor" value="<?php echo $id_interventor; ?>">
		<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
		<input type="hidden" name="acta_reservada" value="<?php echo $acta_reservada; ?>">


		<br>
		<center><button  class="btn btn-lg btn-pascual" type="submit">Guardar</button></center>
		<br>
		<br>
	</form>




	<?php
	include "cerrarconexion.php";
	?>

	<div class="footer">
		<center> <p> &copy; 2020 Sistema de Información de la interventoría Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>
		</div>

	</div> <!-- /container -->


	<!-- Bootstrap core JavaScript
	================================================== -->
	<script>
	$(function() {
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd'
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
