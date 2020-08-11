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
	$msg=$_GET['msg'];


	//Consultas de acuerdo al perfil
	if($id_grupo==1){
		$lista_desplegable= mysql_query(("SELECT * FROM componente where estado='1'"),$conexion);
		$radicados_reservados= mysql_query(("
		SELECT
		reserva_radicado.id_reserva_radicado,
		reserva_radicado.id_acta,
		reserva_radicado.descripcion_reserva,
		reserva_radicado.fecha_reserva,
		reserva_radicado.id_interventor,
		componente.nombre_componente
		FROM
		reserva_radicado,componente
		WHERE
		reserva_radicado.id_componente=componente.id_componente  and
		reserva_radicado.estado='1'
		"),$conexion);
	}
	else{
		$lista_desplegable= mysql_query(("SELECT * FROM componente where id_componente='$id_componente' and estado='1'"),$conexion);
		$radicados_reservados= mysql_query(("
		SELECT
		reserva_radicado.id_reserva_radicado,
		reserva_radicado.id_acta,
		reserva_radicado.descripcion_reserva,
		reserva_radicado.fecha_reserva,
		reserva_radicado.id_interventor,
		componente.nombre_componente
		FROM
		reserva_radicado,componente
		WHERE
		reserva_radicado.id_componente=componente.id_componente  and
		reserva_radicado.id_componente='$id_componente' and
		reserva_radicado.estado='1'
		"),$conexion);
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
		<link href="css/theme.default.css" rel="stylesheet">

		<!-- bootstrap widget theme -->
		<link href="css/theme.bootstrap.css" rel="stylesheet" >

		<!-- Estilos menú principal -->
		<link rel="stylesheet" href="css/estilos.css">

		<!-- Material Icons -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

		<!-- JavaScript para los filtros de las tablas -->
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/jquery.tablesorter.js"></script>
		<script src="js/jquery.tablesorter.widgets.js"></script>

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

	<?php
	if($msg!=0){
		if($msg==1){


			?>

			<div id="mensaje" class="alert alert-success" role="alert">
				<h5 align="center"><strong>¡Felicitaciones!</strong>
					El registro ha sido Insertado/Actualizado exitosamente.<button type="button" class="close" aria-hidden="true">x</button></h5>
				</div>
				<?php
			}
			else{
				?>

				<div id="mensaje" class="alert alert-danger" role="alert">
					<h5 align="center"><strong>¡Advertencia!</strong>
						Acaba de Eliminar un registro o se generaron inconvenientes al realizar la transacción.<button type="button" class="close" aria-hidden="true">x</button></h5>
					</div>
					<?php
				} // End else msg=2
			} //End msg=0
			?>




			<div class="jumbotron">
				<h2>Reservar números de Acta</h2>
				<h5>Haga la reserva de radicados cuando no diligencie en línea la visita de interventoría</h5>




				<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">
					<input type="hidden" name="caso" value="13">
					<input type="hidden" name="fecha_reserva" value="<?php echo date("Y-m-d H:i:s"); ?>">
					<input type="hidden" name="id_interventor" value="<?php echo $id_interventor; ?>">


					<input data-parsley-required type="text" name="descripcion_reserva" class="form-control" placeholder="Descripción de la reserva" autofocus>
					<br>

					<select data-parsley-min="1" class="form-control" name="id_componente">
						<option value="0" required>Seleccione el Componente...</option>
						<?php  	while($row=mysql_fetch_assoc($lista_desplegable)){ ?>
							<option  value="<?php  echo  $row['id_componente']; ?>"><?php echo  $row['nombre_componente']; ?></option>	<?php 	}	?>
						</select>

						<br>
						<button  class="btn btn-pascual" type="submit">Reservar Nuevo</button>
						<br>
						<br>
					</form>

				</div> <!-- /jumbotron -->



				<?php
				//SECCION TEMAS CREADOS

				if(mysql_num_rows($radicados_reservados) > 0){ ?>

					<div class="bs-docs-section" align="center">
						<h2 id="tables-example">Números de acta reservados para futuras visitas</h2>
					</div>
					<div class="footer">
					</div>

				</div> <!-- /container -->

				<table align="center" class="table table-bordered table-hover" id='table' style="width: 80%">
					<thead>
						<tr>
							<th class="info">Número Acta</th>
							<th class="info">Descripción Reserva</th>
							<th class="info">Fecha Reserva</th>
							<th class="info">Interventor</th>
							<th class="info">Componente</th>
							<?php if ($id_grupo==1){ ?>
								<th class="info">Eliminar</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						while($row = mysql_fetch_assoc($radicados_reservados)){ ?>
							<tr>
								<td class="active"><?php echo $row['id_acta'];  ?></td>
								<td class="warning"><?php echo $row['descripcion_reserva'];  ?></td>
								<td class="active"><?php echo $row['fecha_reserva'];  ?></td>
								<td class="active"><?php echo $row['id_interventor'];  ?></td>
								<td class="active"><?php echo $row['nombre_componente'];  ?></td>
								<?php if ($id_grupo==1){ ?>
									<td class="danger"><a  href='deletes.php?eliminar=<?php echo $row['id_reserva_radicado'] ?>&caso=6'><center><IMG src='images/eliminar.png' border='0'></center></a></td>
									<?php } ?>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php }

				else {

					?>
					<div class="page-header">

						<div align="center" class="alert alert-warning" role="alert">
							<strong>¡Advertencia!</strong> No hay registros que coincidan con los filtros seleccionados.
						</div>
					</div>

					<?php
				}


				include "cerrarconexion.php"; ?>


				<div class="container">

					<div class="footer">
						<center> <p> &copy; 2020 Sistema de Información de la interventoría Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

						</div>

					</div> <!-- /container -->


					<!-- Bootstrap core JavaScript-->
					<script>
					$(function() {


						<!-- Cerrar el boton emergente-->
						$('.close').click(function() {
							$(this).parent().parent().fadeOut();
						});

						<!-- Filtros Tablas-->
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
