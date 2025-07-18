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
	if($id_grupo==1){
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


	//Consultas de acuerdo al perfil
	if($id_grupo==1){
		$actas_creadas= mysql_query(("
		SELECT
		acta_fallida.id_acta,
		acta_fallida.fecha_evaluacion,
		componente.nombre_componente,
		acta_fallida.id_contrato,
		prestador.nombre_prestador,
		modalidad.abr_modalidad,
		acta_fallida.nombre_sede,
		acta_fallida.id_interventor,
		acta_fallida.observacion_interventor
		FROM
		acta_fallida,prestador,modalidad,componente
		WHERE
		acta_fallida.id_prestador=prestador.id_prestador and
		acta_fallida.id_modalidad=modalidad.id_modalidad and
		acta_fallida.id_componente=componente.id_componente
		order by acta_fallida.fecha_evaluacion desc
		"),$conexion);
	}
	else{
		$actas_creadas= mysql_query(("
		SELECT
		acta_fallida.id_acta,
		acta_fallida.fecha_evaluacion,
		componente.nombre_componente,
		acta_fallida.id_contrato,
		prestador.nombre_prestador,
		modalidad.abr_modalidad,
		acta_fallida.nombre_sede,
		acta_fallida.id_interventor,
		acta_fallida.observacion_interventor
		FROM
		acta_fallida,prestador,modalidad,componente
		WHERE
		acta_fallida.id_prestador=prestador.id_prestador and
		acta_fallida.id_modalidad=modalidad.id_modalidad and
		acta_fallida.id_componente=componente.id_componente and
		acta_fallida.id_componente='$id_componente'
		order by acta_fallida.fecha_evaluacion desc
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
		<link href="css/datepicker.css" rel="stylesheet">

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
		<script src="js/bootstrap-datepicker.js"></script>

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
		<h5>Seleccione los filtros para realizar la Visita Fallida</h5>


		<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="evaluarfallida.php">



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

			</div>

			<?php
			//SECCION ACTAS CREADAS

			if(mysql_num_rows($actas_creadas) > 0){ ?>

				<div class="bs-docs-section" align="center">
					<h2 id="tables-example">Consultar Actas Fallidas</h2>
					<h5 id="tables-example">Solo se permite la lectura del acta fallida.</h5>
				</div>
				<div class="footer">
				</div>

			</div> <!-- /container -->


			<table align="center" class="table table-bordered table-hover" id='table' style="width: 95%">
				<thead>
					<tr>
						<th class="info">Acta</th>
						<th class="info">Fecha</th>
						<th class="info">Componente</th>
						<th class="info">Contrato</th>
						<th class="info">Prestador</th>
						<th class="info">Mod.</th>
						<th class="info">Sede</th>
						<th class="info">Interv.</th>
						<th class="info">Observación Interventor</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while($row = mysql_fetch_assoc($actas_creadas)){ ?>
						<tr>
							<td class="active"><?php echo $row['id_acta'] ?></td>
							<td class="active"><?php echo $row['fecha_evaluacion'];  ?></td>
							<td class="active"><?php echo $row['nombre_componente'];  ?></td>
							<td class="active"><?php echo $row['id_contrato'];  ?></td>
							<td class="active"><?php echo $row['nombre_prestador'];  ?></td>
							<td class="active"><?php echo $row['abr_modalidad'];  ?></td>
							<td class="active"><?php echo $row['nombre_sede'];  ?></td>
							<td class="active"><?php echo $row['id_interventor'];  ?></td>
							<td class="active"><?php echo $row['observacion_interventor'];  ?></td>

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
			(function($) {

				<!-- Fecha Datepicker-->
				$('.datepicker').datepicker({
					format: 'yyyy-mm-dd'
				});

				<!-- Cerrar el boton emergente-->
				$('.close').click(function() {
					$(this).parent().parent().fadeOut();
				});

			})(jQuery);



			(function($) {
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




			})(jQuery);


			(function($) {
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


	})(jQuery);


	(function($) {

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


	})(jQuery);
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
