<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";

	//Variables Globales
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$nombre=$_SESSION["nombre_usuario"];
	$fotoperfil = $_SESSION["fotoperfil"];


	//Variables recibidas via GET para la consulta
	$componente_selected=$_GET['componente_selected'];
	$id_tema=$_GET['id_tema'];
	$id_mes=$_GET['id_mes'];
	$msg=$_GET['msg'];

	if($componente_selected==4){
		echo "<script>
		window.open('reportes3-1.php?componente_selected=$componente_selected&id_tema=$id_tema&id_tema=$id_tema&msg=$msg&id_mes=$id_mes','_blank');
		</script>";
	}


	if($componente_selected==0){} //Condicion para cuando el get sea 0
		else{

			$titulos= mysql_query(("		SELECT
				nombre_componente,
				nombre_tema
				FROM
				tema,componente
				WHERE
				tema.id_componente=componente.id_componente and
				tema.id_componente='$componente_selected' and
				tema.id_tema='$id_tema' and
				tema.estado='1'
				GROUP BY
				nombre_componente,nombre_tema
				"),$conexion);

				$observaciones_creadas= mysql_query(("
				SELECT
				semaforo.id_semaforo,
				semaforo.porc_calidad,
				semaforo.porc_deb_proc,
				semaforo.porc_descuento,
				semaforo.id_contrato,
				prestador.nombre_prestador,
				modalidad.abr_modalidad,
				semaforo.descripcion_incumplimiento_grave,
				semaforo.logros_prestador,
				semaforo.dificultades_prestador,
				semaforo.debido_proceso,
				semaforo.asistencia_tecnica,
				semaforo.otras_observaciones,
				semaforo.id_interventor,
				semaforo.incumplimiento_grave,
				semaforo.gi1,
				semaforo.gi2,
				semaforo.gi3,
				semaforo.gi4,
				semaforo.gi5,
				semaforo.gi6,
				semaforo.gi7,
				semaforo.estado
				FROM
				semaforo,prestador,modalidad
				WHERE
				semaforo.id_prestador=prestador.id_prestador and
				semaforo.id_modalidad=modalidad.id_modalidad and
				semaforo.id_componente='$componente_selected' and
				semaforo.id_tema='$id_tema' and
				semaforo.id_mes='$id_mes'

				ORDER BY
				semaforo.id_contrato

				"),$conexion);

			} //End Else



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

				<title>Semáforo</title>

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
						<h2>Semáforo de la prestación del Servicio</h2>
						<h5>Consulte/Edite el semáforo correspondiente al mes seleccionado </h5>



						<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="GET" action="reportes3.php">
							<input type="hidden" name="msg" value="0">




							<select data-parsley-min="1" class="form-control" name="componente_selected" id="select1">
								<option value="0" required>Seleccione el Componente...</option>
							</select>

							<select data-parsley-min="1" class="form-control" name="id_tema" id="select2">
								<option value="0" required>Seleccione el Componente Técnico...</option>
							</select>

							<select data-parsley-min="1" class="form-control" name="id_mes">
								<option value="0" required>Seleccione el Mes...</option>
								<option value="1" >ENERO</option>
								<option value="2" >FEBRERO</option>
								<option value="3" >MARZO</option>
								<option value="4" >ABRIL</option>
								<option value="5" >MAYO</option>
								<option value="6" >JUNIO</option>
								<option value="7" >JULIO</option>
								<option value="8" >AGOSTO</option>
								<option value="9" >SEPTIEMBRE</option>
								<option value="10">OCTUBRE</option>
								<option value="11">NOVIEMBRE</option>
								<option value="12">DICIEMBRE</option>
							</select>


							<br>


							<button  class="btn btn-pascual" type="submit">Consultar</button>
							<br>
							<br>
						</form>


					</div> <!-- /jumbotron -->

					<?php
					//SECCION OBSERVACIONES CREADAS

					if($componente_selected!=0){ //1er if

						$numrows= mysql_num_rows($observaciones_creadas);
						if ($numrows == 0){
							?>
							<div align="center" class="page-header">
								<div class="alert alert-warning" role="alert">
									<strong>¡Advertencia!</strong> No hay registros que coincidan con los filtros seleccionados.
								</div>
							</div>
							<?php //Cierro else interno
						} //2do if


						else{
							$row=mysql_fetch_assoc($titulos);


							?>

							<div align="center" class="bs-docs-section">
								<h2 id="tables-example">Semáforo del Estado de la Prestación del Servicio</h2>
								<h4 id="tables-example">Componente Técnico: <strong><?php echo $row['nombre_tema']  ?></strong></h4>
								<h3 id="tables-example">Mes: <strong><?php echo $id_mes;  ?></strong></h3>
							</div>
							<div class="footer">
							</div>

						</div> <!-- /container -->
						<form class="form-signin" role="form" name="formulario2" METHOD="post" action="inserts.php">
							<input type="hidden" name="caso" value="17">
							<input type="hidden" name="componente_selected" value="<?php echo $componente_selected; ?>">
							<input type="hidden" name="id_tema" value="<?php echo $id_tema; ?>">
							<input type="hidden" name="id_mes" value="<?php echo $id_mes; ?>">


							<table align="center" class="table table-bordered table-hover" id='table' style="width: 95%">
								<thead>
									<tr>
										<th class="info">#</th>
										<th class="info">Contrato</th>
										<th class="info">Prestador</th>
										<th class="info">Modalidad</th>
										<th class="info">Interventor</th>
										<th class="info">% Final Semáforo</th>
										<th class="info">Logros</th>
										<th class="info">Dificultades</th>
										<th class="info">% Calidad</th>
										<th class="info">Debido Proceso</th>
										<th class="info">% Debido Proceso</th>
										<th class="info">Sugerencia BC</th>
										<th class="info">Observaciones</th>
										<th class="info">OIG</th>
										<th class="info">Descripción del Incumplimiento Grave</th>
										<th class="info">% Descuento</th>
										<?php if ($componente_selected==4){ ?>
											<th class="info">Perfiles</th>
											<th class="info">Relación Técnica y Contratación</th>
											<th class="info">Pago de Nómina y Seguridad Social</th>
											<th class="info">Informes de Ejecución Financiera y Anexos</th>
											<th class="info">Dotación y Soportes</th>
											<th class="info">Peticiones, Quejas y Reclamos</th>
											<th class="info">Subsanación de Hallazgos y Requerimientos</th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php
									while($row=mysql_fetch_assoc($observaciones_creadas)){
										$estado[]=$row['estado'];
										?>

										<tr>

											<td class="active"><input type="hidden" name="id_semaforo[]" value="<?php echo $row['id_semaforo'] ?>"><?php echo $row['id_semaforo'];  ?></td>
											<td class="active"><?php echo $row['id_contrato'];  ?></td>
											<td class="active"><?php echo $row['nombre_prestador'];  ?></td>
											<td class="active"><?php echo $row['abr_modalidad'];  ?></td>
											<td class="active"><?php echo $row['id_interventor'];  ?></td>
											<td class="active">
												<?php

												if($componente_selected==7){ //% Total Nutricion
													if($row['porc_calidad']==0 && $row['porc_deb_proc']==0){
														$porc_total="NV";
													}else{
														$porc_total=round($row['porc_calidad']*0.8+$row['porc_deb_proc']*0.2+$row['porc_descuento'],4)*100;
													}
												}

												elseif($componente_selected==4){  //% Total Ginst
													$porc_total=$row['porc_descuento']*100;

												}

												else{ //% Total Demas componentes
													if($row['porc_calidad']==0 && $row['porc_deb_proc']==0){
														$porc_total="NV";
													}else{
														$porc_total=round($row['porc_calidad']+$row['porc_descuento'],4)*100;
													}
												}

												?>

												<?php if ($porc_total=="0"){ ?>
													<div class="alert alert-danger"><center><?php echo $porc_total;  ?>%</center></div>
												<?php } elseif ($porc_total=="NV"){ ?>
													<div class="alert alert-info"><center><?php echo $porc_total;  ?></center></div>
												<?php } elseif($porc_total>=95) {?>
													<div class="alert alert-success"><center><?php echo $porc_total;  ?>%</center></div>
												<?php } elseif($porc_total>=80) {?>
													<div class="alert alert-success"><center><?php echo $porc_total;  ?>%</center></div>
												<?php } elseif ($porc_total>=60){ ?>
													<div class="alert alert-warning"><center><?php echo $porc_total;  ?>%</center></div>
												<?php } else{ ?>
													<div class="alert alert-danger"><center><?php echo $porc_total;  ?>%</center></div>
												<?php } ?>


											</td>
											<td class="active"><textarea name="logros_prestador[]" rows="8" style="width:350px"><?php echo $row['logros_prestador'];?></textarea></td>
											<td class="active"><textarea name="dificultades_prestador[]" rows="8" style="width:350px"><?php echo $row['dificultades_prestador'];?></textarea></td>
											<td class="active">

												<?php if($row['porc_calidad']>=0.95) {?>
													<div class="alert alert-success"><center><?php echo $row['porc_calidad']*100;  ?>%</center></div>
												<?php } elseif($row['porc_calidad']>=0.80) {?>
													<div class="alert alert-success"><center><?php echo $row['porc_calidad']*100;  ?>%</center></div>
												<?php } elseif ($row['porc_calidad']>=0.60){ ?>
													<div class="alert alert-warning"><center><?php echo $row['porc_calidad']*100;  ?>%</center></div>
												<?php } else{ ?>
													<div class="alert alert-danger"><center><?php echo $row['porc_calidad']*100;  ?>%</center></div>
												<?php } ?>


											</td>
											<td class="active"><textarea name="debido_proceso[]" rows="8" style="width:350px"><?php echo $row['debido_proceso'];?></textarea></td>
											<td class="active">

												<?php if($row['porc_deb_proc']>=0.95) {?>
													<div class="alert alert-success"><center><?php echo $row['porc_deb_proc']*100;  ?>%</center></div>
												<?php } elseif ($row['porc_deb_proc']>=0.80){ ?>
													<div class="alert alert-success"><center><?php echo $row['porc_deb_proc']*100;  ?>%</center></div>
												<?php } elseif ($row['porc_deb_proc']>=0.60){ ?>
													<div class="alert alert-warning"><center><?php echo $row['porc_deb_proc']*100;  ?>%</center></div>
												<?php } else{ ?>
													<div class="alert alert-danger"><center><?php echo $row['porc_deb_proc']*100;  ?>%</center></div>
												<?php } ?>


											</td>
											<td class="active"><textarea name="asistencia_tecnica[]" rows="8" style="width:350px"><?php echo $row['asistencia_tecnica'];?></textarea></td>
											<td class="active"><textarea name="otras_observaciones[]" rows="8" style="width:350px"><?php echo $row['otras_observaciones'];?></textarea></td>
											<td class="active"><?php if ($row['incumplimiento_grave']==0) { echo "NO";} else { echo "SI";}  ?></td>
											<td class="active"><textarea name="descripcion_incumplimiento_grave[]" rows="8" style="width:350px"><?php echo $row['descripcion_incumplimiento_grave'];?></textarea></td>

											<td class="active">
												<div class="alert alert-danger"><center><?php echo $row['porc_descuento']*100;  ?>%</center></div>
											</td>

											<?php if ($componente_selected==4){ ?>
												<td class="active"><center><?php if ($row['gi1']==1){echo "CUMPLE";} else{ echo "NO CUMPLE";}  ?></center></td>
												<td class="active"><center><?php if ($row['gi2']==1){echo "CUMPLE";} else{ echo "NO CUMPLE";}  ?></center></td>
												<td class="active"><center><?php if ($row['gi3']==1){echo "CUMPLE";} else{ echo "NO CUMPLE";}  ?></center></td>
												<td class="active"><center><?php if ($row['gi4']==1){echo "CUMPLE";} else{ echo "NO CUMPLE";}  ?></center></td>
												<td class="active"><center><?php if ($row['gi5']==1){echo "CUMPLE";} else{ echo "NO CUMPLE";}  ?></center></td>
												<td class="active"><center><?php if ($row['gi6']==1){echo "CUMPLE";} else{ echo "NO CUMPLE";}  ?></center></td>
												<td class="active"><center><?php if ($row['gi7']==1){echo "CUMPLE";} else{ echo "NO CUMPLE";}  ?></center></td>
											<?php } ?>


										</tr>
									<?php } //Cierro While ?>
								</tbody>
							</table>

							<?php
							if($id_grupo==1 || $id_grupo==3 || $id_grupo == 2){
								if(array_sum($estado)>0){
									?>
									<center><button  class="btn btn-lg btn-pascual" type="submit">Modificar</button></center>
									<?php
								}
							}
							?>
						</form>
						<br>
						<br>


						<?php

					}//Cierro else


				}//Cierro 1er if

				else {

					?>
					<div align="center" class="page-header">

						<div class="alert alert-warning" role="alert">
							<strong>¡Advertencia!</strong> No hay registros que coincidan con los filtros seleccionados.
						</div>
					</div>

					<?php
				}


				include "cerrarconexion.php"; ?>


				<div class="container">

					<div class="footer">
						<center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

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
							$.get("lib/combo-configobservaciones/option-select1.php?", { id_componente: id_componente, id_grupo: id_grupo },
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
							$.get("lib/combo-configobservaciones/option-select2.php", { code: code },
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

					/*
					function cargar_menu3()
					{
					var code = $("#select1").val();
					var code2 = $("#select2").val();
					$.get("lib/combo-configobservaciones/option-select3.php?", { code: code, code2: code2 },
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
*/


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
