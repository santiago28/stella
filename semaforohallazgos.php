<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";
	//Variables recibidas via POST
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$nombre=$_SESSION["nombre_usuario"];
	$fotoperfil = $_SESSION["fotoperfil"];


	//Consultas de acuerdo al perfil
	if($id_grupo==1){
		//Grupo Administrador
		$lista_desplegable1= mysql_query(("SELECT * FROM componente where estado='1'"),$conexion); //componente
		$lista_desplegable2= mysql_query(("SELECT username FROM users where active='1' order by username"),$conexion); //id_interventor

	}
	else{
		//Grupo Members
		$lista_desplegable1= mysql_query(("SELECT * FROM componente where id_componente='$id_componente' and estado='1'"),$conexion); //componente
		$lista_desplegable2= mysql_query(("SELECT username FROM users where active='1' and id_componente='$id_componente' order by username"),$conexion); //id_interventor

	}



	//Variables recibidas via GET
	$componente_selected=$_GET["componente_selected"];

	if($componente_selected!=0){
		$id_interventor=$_GET["id_interventor"];
		$etapa=$_GET["etapa"];

		$actas_creadas= mysql_query(("
		SELECT
		subsanacion.id_acta,
		subsanacion.id_pregunta,
		evaluacion.valor_calificacion_final,
		componente.nombre_componente,
		subsanacion.id_interventor,
		prestador.nombre_prestador,
		subsanacion.id_contrato,
		modalidad.abr_modalidad,
		acta.nombre_sede,
		acta.fecha_evaluacion,
		subsanacion.fecha_subsanacion_final,
		subsanacion.fecha_solicitud_aclaracion,
		subsanacion.fecha_requerimiento,
		datediff(subsanacion.fecha_subsanacion_final,CURDATE()) vencimiento_ac,
		datediff(subsanacion.fecha_solicitud_aclaracion,CURDATE()) vencimiento_sa,
		datediff(subsanacion.fecha_requerimiento,CURDATE()) vencimiento_req,
		subsanacion.etapa,
		acta.estado


		FROM
		subsanacion,acta,evaluacion,componente,prestador,modalidad
		WHERE
		subsanacion.id_acta=acta.id_acta and
		subsanacion.id_acta=evaluacion.id_acta and
		subsanacion.id_pregunta=evaluacion.id_pregunta and
		subsanacion.id_componente=componente.id_componente and
		subsanacion.id_prestador=prestador.id_prestador and
		subsanacion.id_modalidad=modalidad.id_modalidad and

		subsanacion.id_componente='$componente_selected' and
		subsanacion.id_interventor LIKE '$id_interventor' and
		subsanacion.etapa LIKE '$etapa' and

		subsanacion.estado='1'

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

		<title>Semáforo de Hallazgos</title>

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
		<h2>Filtros para realizar la Búsqueda</h2>
		<h5>Diligencie los siguientes campos para que la búsqueda sea mas eficiente</h5>



		<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="GET" action="semaforohallazgos.php">


			<select data-parsley-min="1" class="form-control" name="componente_selected" id="select1">
				<option id="option1_js" value="0" required>Seleccione el Componente...</option>
				<?php  	while($row=mysql_fetch_assoc($lista_desplegable1)){ ?>
					<option  value="<?php  echo  $row['id_componente']; ?>"><?php echo  $row['nombre_componente']; ?></option>	<?php 	}	?>
				</select>

				<select data-parsley-required class="form-control" name="id_interventor" id="select2">
					<option value="" required>Seleccione el interventor...</option>
					<option value="%" required>TODOS LOS INTERVENTORES</option>
					<?php  	while($row=mysql_fetch_assoc($lista_desplegable2)){ ?>
						<option  value="%<?php  echo  $row['username']; ?>%"><?php echo  $row['username']; ?></option>	<?php 	}	?>
					</select>

					<select data-parsley-required class="form-control" name="etapa" id="select3" >
						<option  value="" >Seleccione la Etapa...</option>
						<option  value="%" >TODAS LAS ETAPAS</option>
						<option  value="OK" >OK: Solucionada</option>
						<option  value="AC" >AC: Acción Correctiva</option>
						<option  value="SA" >SA: Solicitud de Aclaración</option>
						<option  value="REQ" >REQ: Requerimiento</option>
					</select>



					<br>


					<button  class="btn btn-pascual" type="submit">Consultar</button>
					<br>
					<br>
				</form>


			</div> <!-- /jumbotron -->



			<?php
			//SECCION ACTAS CREADAS
			if($componente_selected!=0){
				if(mysql_num_rows($actas_creadas) > 0){ ?>

					<div class="bs-docs-section" align="center">
						<h2 id="tables-example">Seguimiento al Debido Proceso</h2>
						<h5 id="tables-example">Realice y Consulte el seguimiento al Debido Proceso.</h5>
					</div>
					<div class="footer">
					</div>

				</div> <!-- /container -->


				<table align="center" class="table table-bordered table-hover" id='table' style="width: 95%">
					<thead>
						<tr>
							<th class="info" style="width:100px">Acta</th>
							<th class="info">Fecha</th>
							<th class="info">Preg.</th>
							<th class="info">Componente</th>
							<th class="info">Interv.</th>
							<th class="info">Prestador</th>
							<th class="info">Contrato</th>
							<th class="info">Mod.</th>
							<th class="info" style="width:150px">Sede</th>
							<th class="info">Calif.</th>
							<th class="danger">Etapa</th>
							<th class="danger">Plazo Envío Evidencias</th>
							<th class="danger">Vence en X días</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while($row = mysql_fetch_assoc($actas_creadas)){ ?>
							<tr>
								<td class="warning"><a  href='imprimiractas.php?id_acta=<?php echo $row['id_acta'] ?>&msg=0'><?php echo $row['id_acta'] ?></a></td>
								<td class="active"><?php echo $row['fecha_evaluacion'];  ?></td>
								<td class="active"><?php echo $row['id_pregunta'];  ?></td>
								<td class="active"><?php echo $row['nombre_componente']; ?></td>
								<td class="active"><?php echo $row['id_interventor'];  ?></td>
								<td class="active"><?php echo $row['nombre_prestador'];  ?></td>
								<td class="active"><?php echo $row['id_contrato'];  ?></td>
								<td class="active"><?php echo $row['abr_modalidad'];  ?></td>
								<td class="active"><?php echo $row['nombre_sede'];  ?></td>
								<td class="active"><?php echo $row['valor_calificacion_final'];  ?></td>
								<td class="active" align="center"><?php echo $row['etapa'];  ?></td>

								<td class="active"><?php
								if($row['etapa']=="AC")
								{
									echo $row['fecha_subsanacion_final'];
									$operacion_fecha=$row['vencimiento_ac'];
								}
								if($row['etapa']=="SA")
								{
									echo $row['fecha_solicitud_aclaracion'];
									$operacion_fecha=$row['vencimiento_sa'];
								}
								if($row['etapa']=="REQ")
								{
									echo $row['fecha_requerimiento'];
									$operacion_fecha=$row['vencimiento_req'];
								}
								if($row['etapa']=="OK")
								{
									echo "";
									$operacion_fecha="";
								}
								?>
							</td>

							<td>
								<?php if($operacion_fecha==""){
									?>
									<div class="active"></div>
									<?php

								} else{

									if($operacion_fecha<5){
										?>
										<div class="alert alert-danger"><center><?php echo $operacion_fecha;  ?></center></div>
										<?php
									} else{
										?>
										<div class="alert alert-success"><center><?php echo $operacion_fecha;  ?></center></div>
										<?php
									}
								}
								?>
							</td>


						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php }

		else {

			?>
			<div class="page-header">

				<div class="alert alert-warning" role="alert">
					<strong>¡Advertencia!</strong> No hay registros que mostrar.
				</div>
			</div>

			<?php
		}
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
