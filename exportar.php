<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$nombre=$_SESSION["nombre_usuario"];
	$fotoperfil = $_SESSION["fotoperfil"];
	$msg=$_POST['msg'];


	//Consultas de acuerdo al perfil
	if($id_grupo==1 or $id_grupo==4){
		//Grupo Administrador
		$lista_desplegable= mysql_query(("SELECT * FROM `componente` WHERE `id_componente` IN (1,2,4,5,7,8,9) and estado='1'"),$conexion);
		$lista_desplegable2= mysql_query(("SELECT * FROM tema where estado='1'"),$conexion);


	}
	else{
		//Grupo Members
		$lista_desplegable= mysql_query(("SELECT * FROM componente where id_componente='$id_componente' and estado='1'"),$conexion);
		$lista_desplegable2= mysql_query(("SELECT * FROM tema where id_componente='$id_componente' and estado='1'"),$conexion);
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

		<title>Exportar</title>

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

	<div class="jumbotron">
		<h2>Exportar</h2>
		<h5>Exporte a Excel el informe según los filtros personalizados</h5>




		<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="exportar.php">
			<input type="hidden" name="msg" value="1">

			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">Componente</div>

					<select data-parsley-min="1" class="form-control" name="id_componente" id="select1">
						<option  value="0" required>Seleccione el Componente...</option>
						<?php  	while($row=mysql_fetch_assoc($lista_desplegable)){ ?>
							<option  value="<?php  echo  $row['id_componente']; ?>"><?php echo  $row['nombre_componente']; ?></option>
						<?php 	}	?>
					</select>

				</div>
			</div>


			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">Tipo de Informe</div>



					<?php
					if($id_grupo==4){

						?>

						<select data-parsley-min="1" class="form-control" name="caso" id="select2">
							<option value="0" required	>¿Qué desea exportar?...</option>
							<!--<option value="1"			>Visitas Realizadas</option>-->
							<!--<option value="6"           >Visitas Realizadas Itinerante</option> -->
							<option value="2"			>Valoración x Estándar</option>
							<!--<option value="3"			>Seguimiento al Debido Proceso</option> -->
							<option value="4"			>Calificación de Variables</option>
							<!-- <option value="5"			>Proveedores - Valoración x Estándar</option> -->
							<!-- <option value="7"           >Nutrici&oacute;n - Descuentos</option> -->
							<option value="8"			>Valoración Consolidada x Contrato</option>
						</select>

						<?php
					}else{
						?>
						<select data-parsley-min="1" class="form-control" name="caso" id="select2">
							<option value="0" required	>¿Qué desea exportar?...</option>
							<option value="1"			>Visitas Realizadas</option>
							<!--<option value="6"           >Visitas Realizadas Itinerante</option> -->
							<option value="2"			>Valoración x Estándar</option>
							<option value="3"			>Seguimiento al Debido Proceso</option>
							<option value="4"			>Calificación de Variables</option>
							<option value="5"			>Proveedores - Valoración x Estándar</option>
							<option value="7"           >Nutrici&oacute;n - Descuentos</option>
							<option value="11"		>Valoración x Componente</option>
							<option value="8"			>Valoración Consolidada x Contrato</option>
							<option value="9"			>Información Consolidada x Contrato</option>
							<option value="10"			>Consolidado Hallazgos</option>
							<option value="16"			>Consolidado valoración por componente</option>
							<?php
								if($id_componente==7)
								{
							?>
								<option value="14">Notificación Hallazgos modalidad Familiar</option>
							<?php
								}
							?>

						</select>

						<?php
					}
					?>

				</div>
			</div>


			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">Fecha Inicial</div>

					<input data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/" class="form-control input datepicker" name="fecha_inicial" type="text" value="<?php date('Y-m-d'); ?>" placeholder="Fecha (Ej: aaaa-mm-dd)" required>

				</div>
			</div>

			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">Fecha Final</div>

					<input data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/" class="form-control input datepicker" name="fecha_final" type="text" value="<?php date('Y-m-d'); ?>" placeholder="Fecha (Ej: aaaa-mm-dd)" required>

				</div>
			</div>

			<br>


			<button  class="btn btn-primary" type="submit">Consultar</button>
			<br>
			<br>
		</form>

	</div> <!-- /jumbotron -->

	<?php
	if($msg!=0){

		//Variables recibidas via post
		$id_componente=$_POST['id_componente'];
		$caso=$_POST['caso'];
		$fecha_inicial=$_POST['fecha_inicial'];
		$fecha_final=$_POST['fecha_final'];

		?>
		<div id="mensaje" class="alert alert-success" role="alert">
			<h5 align="center"><strong>¡De click para descargar el reporte!</strong>
				<a href='download.php?
				id_componente=<?php echo  $id_componente  ?>&
				caso=<?php echo  $caso ?>&
				fecha_inicial=<?php echo  $fecha_inicial ?>&
				fecha_final=<?php echo  $fecha_final ?>
				'>
				<img src='images/save.png' border='0' alt='Clic para Exportar' title='Clic para Exportar'width='50' height='50'>
			</a>
			<button type="button" class="close" aria-hidden="true">x</button>
		</div>




		<?php
	} //End msg=0
	?>


	<?php include "cerrarconexion.php"; ?>


	<div class="container">

		<div class="footer">
			<center> <p> &copy; <?=date('Y')?> Sistema de Información de la interventoría Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

			</div>

		</div> <!-- /container -->

		<!-- Bootstrap core JavaScript-->
		<script>
		$(function() {


			$('.datepicker').datepicker({
				format: 'yyyy-mm-dd'
			});

			<!-- Cerrar el boton emergente-->
			$('.close').click(function() {
				$(this).parent().parent().fadeOut();
			});



			<!-- Filtros para las tablas-->
			$.extend($.tablesorter.themes.bootstrap, {
				// these classes are added to the table. To see other table classes available,
				// look here: http://twitter.github.com/bootstrap/base-css.html#tables
				table      : 'table table-bordered',
				caption    : 'caption',
				header     : 'bootstrap-header', // give the header a gradient background
				footerRow  : '',
				footerCells: '',
				icons      : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
				sortNone   : 'bootstrap-icon-unsorted',
				sortAsc    : 'icon-chevron-up glyphicon glyphicon-chevron-up',     // includes classes for Bootstrap v2 & v3
				sortDesc   : 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
				active     : '', // applied when column is sorted
				hover      : '', // use custom css here - bootstrap class may not override it
				filterRow  : '', // filter row class
				even       : '', // odd row zebra striping
				odd        : ''  // even row zebra striping
			});

			// call the tablesorter plugin and apply the uitheme widget
			$("table").tablesorter({
				// this will apply the bootstrap theme if "uitheme" widget is included
				// the widgetOptions.uitheme is no longer required to be set
				theme : "bootstrap",

				widthFixed: true,

				headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

				// widget code contained in the jquery.tablesorter.widgets.js file
				// use the zebra stripe widget if you plan on hiding any rows (filter widget)
				widgets : [ "uitheme", "filter", "zebra" ],

				widgetOptions : {
					// using the default zebra striping class name, so it actually isn't included in the theme variable above
					// this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
					zebra : ["even", "odd"],

					// reset filters button
					filter_reset : ".reset"

					// set the uitheme widget to use the bootstrap theme class names
					// this is no longer required, if theme is set
					// ,uitheme : "bootstrap"

				}
			})
			.tablesorterPager({

				// target the pager markup - see the HTML block below
				container: $(".ts-pager"),

				// target the pager page select dropdown - choose a page
				cssGoto  : ".pagenum",

				// remove rows from the table to speed up the sort of large tables.
				// setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
				removeRows: false,

				// output string - default is '{page}/{totalPages}';
				// possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
				output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

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
