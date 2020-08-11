<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$nombre=$_SESSION["nombre_usuario"];
	$fotoperfil = $_SESSION["fotoperfil"];

	$msg=$_GET['msg'];


	//Consultas de acuerdo al perfil
	if($id_grupo==1){
		//Grupo Administrador
		$lista_desplegable= mysql_query(("SELECT * FROM componente where estado='1'"),$conexion);
		$lista_desplegable2= mysql_query(("SELECT * FROM tema where estado='1'"),$conexion);
		$lista_desplegable3= mysql_query(("SELECT * FROM subtema where estado='1'"),$conexion);
		$lista_desplegable4= mysql_query(("SELECT * FROM modalidad where estado='1'"),$conexion);
		$preguntas_creadas= mysql_query(("	SELECT
			pregunta_x_modalidad.id_pregunta_x_modalidad,
			pregunta_x_modalidad.id_pregunta,
			componente.nombre_componente,
			modalidad.abr_modalidad,
			tema.nombre_tema,
			subtema.nombre_subtema,
			pregunta.descripcion_pregunta,
			pregunta_x_modalidad.estado
			FROM
			pregunta_x_modalidad,componente,modalidad,tema,subtema,pregunta
			WHERE
			pregunta_x_modalidad.id_pregunta=pregunta.id_pregunta and
			pregunta_x_modalidad.id_componente=componente.id_componente and
			pregunta_x_modalidad.id_modalidad=modalidad.id_modalidad and
			pregunta_x_modalidad.id_tema=tema.id_tema and
			pregunta_x_modalidad.id_subtema=subtema.id_subtema and
			pregunta_x_modalidad.id_pregunta=pregunta.id_pregunta and
			pregunta_x_modalidad.estado='1'
			ORDER BY
			pregunta_x_modalidad.id_componente,pregunta_x_modalidad.id_modalidad
			"),$conexion);
		}
		else{
			//Grupo Members
			$lista_desplegable= mysql_query(("SELECT * FROM componente where id_componente='$id_componente' and estado='1'"),$conexion);
			$lista_desplegable2= mysql_query(("SELECT * FROM tema where id_componente='$id_componente' and estado='1'"),$conexion);
			$lista_desplegable3= mysql_query(("SELECT * FROM subtema where id_componente='$id_componente' and estado='1'"),$conexion);
			$lista_desplegable4= mysql_query(("SELECT * FROM modalidad where estado='1'"),$conexion);
			$preguntas_creadas= mysql_query(("SELECT
				pregunta_x_modalidad.id_pregunta_x_modalidad,
				pregunta_x_modalidad.id_pregunta,
				componente.nombre_componente,
				modalidad.abr_modalidad,
				tema.nombre_tema,
				subtema.nombre_subtema,
				pregunta.descripcion_pregunta,
				pregunta_x_modalidad.estado
				FROM
				pregunta_x_modalidad,componente,modalidad,tema,subtema,pregunta
				WHERE
				pregunta_x_modalidad.id_pregunta=pregunta.id_pregunta and
				pregunta_x_modalidad.id_componente=componente.id_componente and
				pregunta_x_modalidad.id_modalidad=modalidad.id_modalidad and
				pregunta_x_modalidad.id_tema=tema.id_tema and
				pregunta_x_modalidad.id_subtema=subtema.id_subtema and
				pregunta_x_modalidad.id_pregunta=pregunta.id_pregunta and
				pregunta_x_modalidad.id_componente='$id_componente' and
				pregunta_x_modalidad.estado='1'
				ORDER BY
				pregunta_x_modalidad.id_componente,pregunta_x_modalidad.id_modalidad

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

				<!-- JavaScript para los filtros de las tablas -->
				<script src="js/jquery-1.11.1.min.js"></script>
				<script src="js/jquery.tablesorter.js"></script>
				<script src="js/jquery.tablesorter.widgets.js"></script>

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
						<h2>Creación de Preguntas por Modalidad</h2>
						<h5>Cree y asigne preguntas por Modalidad</h5>




						<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">
							<input type="hidden" name="caso" value="3">

							<select data-parsley-min="1" class="form-control" name="id_componente" id="select1">
								<option value="0" required>Seleccione el Componente...</option>
							</select>

							<select data-parsley-min="1" class="form-control" name="id_tema" id="select2">
								<option value="0" required>Seleccione el Componente Técnico...</option>
							</select>

							<select data-parsley-min="1" class="form-control" name="id_subtema" id="select3">
								<option value="0" required>Seleccione el Estandar...</option>
							</select>



							<input type="text" name="descripcion_pregunta" class="form-control" placeholder="Describa la variable a ser evaluada" required>


							<select data-parsley-required class="form-control" name="id_modalidad[]" id="select4"  size="10" multiple>
								<option id="option4_js"  value="" required>Seleccione la o las modalidades...</option>
								<?php  	while($row=mysql_fetch_assoc($lista_desplegable4)){ ?>
									<option  id="<?php  echo  $row['id_modalidad']; ?>" value="<?php  echo  $row['id_modalidad']; ?>"><?php echo  $row['nombre_modalidad']; ?></option>	<?php 	}	?>
								</select>

								<br>

								<button  class="btn btn-pascual" type="submit">Ingresar Nuevo</button>
								<br>
								<br>
							</form>


						</div> <!-- /jumbotron -->

						<?php
						//SECCION PREGUNTAS CREADAS

						if(mysql_num_rows($preguntas_creadas) > 0){ ?>

							<div align="center" class="bs-docs-section">
								<h2 id="tables-example">Tabla de Preguntas por Modalidad</h2>
							</div>
							<div class="footer">
							</div>


						</div> <!-- /container -->


						<table align="center" class="table table-bordered table-hover" id='table' style="width: 80%">
							<thead>
								<tr>
									<th class="info">id</th>
									<th class="info">Mod.</th>
									<th class="info">Componente Técnico</th>
									<th class="info">Estándar</th>
									<th class="info">Pregunta</th>
									<th class="info">Eliminar</th>
								</tr>
							</thead>
							<tbody>
								<?php
								while($row = mysql_fetch_assoc($preguntas_creadas)){ ?>

									<tr>
										<td class="active"><?php echo $row['id_pregunta'] ?></td>
										<td class="active"><?php echo $row['abr_modalidad'];  ?></td>
										<td class="active"><?php echo $row['nombre_tema'];  ?></td>
										<td class="active"><?php echo $row['nombre_subtema'];  ?></td>
										<td class="active"><?php echo $row['descripcion_pregunta'];  ?></td>
										<td class="danger"><a  href='deletes.php?eliminar=<?php echo $row['id_pregunta_x_modalidad'] ?>&caso=3'><center><IMG src='images/eliminar.png' border='0'></center></a></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>

							<br>


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
					</div>

					<div class="container">

						<div class="footer">
							<center> <p> &copy; 2020 Sistema de Información de la interventoría Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

							</div>

						</div> <!-- /container -->


						<!-- Bootstrap core JavaScript-->
						<script>
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
								var id_componente = "<?php echo $id_componente; ?>";
								var id_grupo = "<?php echo $id_grupo; ?>";
								$.get("lib/combo-configpreguntas/option-select1.php?", { id_componente: id_componente, id_grupo: id_grupo },
								function(resultado){
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
								$.get("lib/combo-configpreguntas/option-select2.php", { code: code },
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
							$.get("lib/combo-configpreguntas/option-select3.php?", { code: code, code2: code2 },
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
