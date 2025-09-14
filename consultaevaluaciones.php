<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$nombre=$_SESSION["nombre_usuario"];
	$fotoperfil = $_SESSION["fotoperfil"];
	//Consultas de acuerdo al perfil
	if($id_grupo==1 or $id_grupo==4){
		$actas_creadas= mysql_query(("
		SELECT
		acta.id_acta,
		acta.fecha_evaluacion,
		componente.nombre_componente,
		acta.id_contrato,
		prestador.nombre_prestador,
		modalidad.abr_modalidad,
		acta.nombre_sede,
		acta.id_interventor,
		acta.numero_visita,
		acta.porc_inicial,
		acta.porc_final,
		acta.estado
		FROM
		acta,prestador,modalidad,componente
		WHERE
		acta.id_prestador=prestador.id_prestador and
		acta.id_modalidad=modalidad.id_modalidad and
		acta.id_componente=componente.id_componente
		order by acta.fecha_evaluacion desc
		"),$conexion);
	}
	else{
		$actas_creadas= mysql_query(("
		SELECT
		acta.id_acta,
		acta.fecha_evaluacion,
		componente.nombre_componente,
		acta.id_contrato,
		prestador.nombre_prestador,
		modalidad.abr_modalidad,
		acta.nombre_sede,
		acta.id_interventor,
		acta.numero_visita,
		acta.porc_inicial,
		acta.porc_final,
		acta.estado
		FROM
		acta,prestador,modalidad,componente
		WHERE
		acta.id_prestador=prestador.id_prestador and
		acta.id_modalidad=modalidad.id_modalidad and
		acta.id_componente=componente.id_componente and
		acta.id_componente='$id_componente'
		order by acta.fecha_evaluacion desc
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

		<title>Consulta de Actas</title>

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

	<div class="jumbotron">
		<h2>Creación de Actas</h2>
		<h5>Diligencie nuevas actas de visita</h5>

		<?php if ($id_grupo != 4) { ?>
			<p><a class="btn btn-lg btn-block btn-pascual" href="configevaluaciones.php" role="button">Realizar nueva visita</a></p>
			<p><a class="btn btn-lg btn-block btn-pascual" href="corregirActas.php?msg=0" role="button">Corregir visitas</a></p>
		<?php } ?>

	</div>	<!-- /jumbotron -->



	<?php
	//SECCION ACTAS CREADAS

	if(mysql_num_rows($actas_creadas) > 0){ ?>

		<div class="bs-docs-section" align="center">
			<h2 id="tables-example">Consultar Actas</h2>
			<h5 id="tables-example">Permite ingresar y editar actas creadas. Cuando el acta está cerrada solo permitirá su lectura.</h5>
		</div>
		<div class="footer">
		</div>

	</div> <!-- /container -->


	<table align="center" class="table table-bordered table-hover" id='table' style="width: 95%">
		<thead>
			<tr>
				<th class="info" style="width:100px">Acta</th>
				<th class="info" style="width:100px">Fecha</th>
				<th class="info">Componente</th>
				<th class="info">Contrato</th>
				<th class="info">Prestador</th>
				<th class="info">Mod.</th>
				<th class="info">Sede</th>
				<th class="info">Visita</th>
				<th class="info">Interv.</th>
				<th class="info">Porcentaje</th>
				<th class="info">Estado</th>
			</tr>
		</thead>
		<tbody>
			<?php
			while($row = mysql_fetch_assoc($actas_creadas)){ ?>
				<tr>
					<td class="active"><a  href='imprimiractas.php?id_acta=<?php echo $row['id_acta'] ?>&msg=0'><?php echo $row['id_acta'] ?></a></td>
					<td class="active"><?php echo $row['fecha_evaluacion'];  ?></td>
					<td class="active"><?php echo $row['nombre_componente'];  ?></td>
					<td class="active"><?php echo $row['id_contrato'];  ?></td>
					<td class="active"><?php echo $row['nombre_prestador'];  ?></td>
					<td class="active"><?php echo $row['abr_modalidad'];  ?></td>
					<td class="active"><?php echo $row['nombre_sede'];  ?></td>
					<td class="active"><?php echo $row['numero_visita'];  ?></td>
					<td class="active"><?php echo $row['id_interventor'];  ?></td>
					<td class="active"><?php echo $row['porc_inicial']*100;  ?>%</td>
					<td class="active"><?php if ($row['estado']==1) {echo "Abierta";} else{echo "Cerrada";}  ?></td>

				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php }

else {

	?>
	<div class="page-header">

		<div class="alert alert-warning" role="alert">
			<strong>¡Advertencia!</strong> No hay registros que coincidan con los filtros seleccionados.
		</div>
	</div>

	<?php
}
include "cerrarconexion.php";
?>


<div class="container">

	<div class="footer">
		<center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

		</div>

	</div> <!-- /container -->


	<!-- Bootstrap core JavaScript-->
	<script>
	$(function() {

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
