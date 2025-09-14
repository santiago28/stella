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
	$tipo_acta=$_POST['tipo_acta'];

	//Query para hallar las variables faltantes
	$data= mysql_query(("
	SELECT
	max(contrato_x_sede.id_contrato) id_contrato,
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



	//Query para traer las preguntas x componente
	// if ($id_componente == 5) {
		$preguntas= mysql_query(("	SELECT
			pregunta_x_modalidad.id_tema,
			pregunta_x_modalidad.id_subtema,
			pregunta_x_modalidad.id_pregunta,
			tema.nombre_tema,
			subtema.nombre_subtema,
			pregunta.descripcion_pregunta,
			pregunta.descripcion_observacion,
			pregunta.descripcion_accion_correctiva
			FROM
			pregunta_x_modalidad,tema,subtema,pregunta
			WHERE
			pregunta_x_modalidad.id_pregunta=pregunta.id_pregunta and
			pregunta_x_modalidad.id_tema=tema.id_tema and
			pregunta_x_modalidad.id_subtema=subtema.id_subtema and
			pregunta_x_modalidad.id_componente='$id_componente' and
			pregunta_x_modalidad.id_modalidad='$id_modalidad' and
			pregunta_x_modalidad.tipo_acta='$tipo_acta' and
			pregunta_x_modalidad.estado='1'
			order by
			pregunta_x_modalidad.id_tema,
			-- pregunta_x_modalidad.id_subtema,
			pregunta_x_modalidad.id_pregunta
			"),$conexion); //pregunta_x_modalidad.id_subtema,
		// }else{
		// 	$preguntas= mysql_query(("	SELECT
		// 		pregunta_x_modalidad.id_tema,
		// 		pregunta_x_modalidad.id_subtema,
		// 		pregunta_x_modalidad.id_pregunta,
		// 		tema.nombre_tema,
		// 		subtema.nombre_subtema,
		// 		pregunta.descripcion_pregunta,
		// 		pregunta.descripcion_observacion,
		// 		pregunta.descripcion_accion_correctiva
		// 		FROM
		// 		pregunta_x_modalidad,tema,subtema,pregunta
		// 		WHERE
		// 		pregunta_x_modalidad.id_pregunta=pregunta.id_pregunta and
		// 		pregunta_x_modalidad.id_tema=tema.id_tema and
		// 		pregunta_x_modalidad.id_subtema=subtema.id_subtema and
		// 		pregunta_x_modalidad.id_componente='$id_componente' and
		// 		pregunta_x_modalidad.id_modalidad='$id_modalidad' and
		// 		pregunta_x_modalidad.estado='1'
		// 		order by
		// 		pregunta_x_modalidad.id_tema,
		// 		/*pregunta_x_modalidad.id_subtema,*/
		// 		pregunta_x_modalidad.id_pregunta
		// 		"),$conexion); //pregunta_x_modalidad.id_subtema,
		// 	}


			//Obtener el numero de visita
			$queryvisita= mysql_query(("
			SELECT
			max(numero_visita) numero_visita
			FROM
			evaluacion
			WHERE
			id_contrato='$id_contrato' and
			id_prestador='$id_prestador' and
			id_modalidad='$id_modalidad' and
			id_sede='$id_sede' and
			id_componente='$id_componente'
			"),$conexion);

			while($row=mysql_fetch_assoc($queryvisita)){
				$numero_visita=$row['numero_visita']+1;
			}


			//Porcentaje Componente x Modalidad
			$comp_x_mod= mysql_query(("
			SELECT
			sum(porc_componente_x_modalidad) porc_componente_x_modalidad
			FROM
			componente_x_modalidad
			WHERE
			id_componente='$id_componente' and
			id_modalidad='$id_modalidad'
			"),$conexion);

			while($row=mysql_fetch_assoc($comp_x_mod)){
				$porc_componente_x_modalidad=$row['porc_componente_x_modalidad'];
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
				<strong>¡Señor(a) profesional de área!</strong> Si los datos relacionados con el contrato presentan alguna inconsistencia, no diligencie la evaluación y póngase en contacto con el administrador del sistema
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
				<strong>¡Señor(a) profesional de área!</strong> Verifique los datos para esta evaluación. Si hay alguna modificación en la información de la sede, por favor realice el cambio.
			</div>



			<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">


				<div class="row">

					<div class="col-sm-4">
						<div class="panel panel-success">
							<div class="panel-heading"><h3 class="panel-title">Sede</h3></div>
							<div class="panel-body"><textarea name="nombre_sede"  style="width:100%" readonly><?php echo $nombre_sede;  ?></textarea></div>
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
					<strong>¡Señor(a) profesional de área!</strong> Diligencie los siguientes datos para continuar. No deben quedar casillas en blanco
				</div>

				<center>
					<div class="form-group col-sm-12">
						<div class="form-group col-sm-6">
							<div class="input-group">
								<div class="input-group-addon">Fecha de Evaluación</div>
								<input data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/" class="form-control input datepicker" name="fecha_evaluacion" type="text" value="<?php date('Y-m-d'); ?>" placeholder="Formato: aaaa-mm-dd" required>
							</div>
						</div>

						<div class="form-group col-sm-6">
							<div class="input-group">
								<div class="input-group-addon">Fecha de Subsanación</div>
								<input data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/" class="form-control input datepicker" name="fecha_subsanacion" type="text" value="<?php date('Y-m-d'); ?>" placeholder="Formato: aaaa-mm-dd" required>
							</div>
						</div>
					</div>

					<div class="form-group col-sm-12">
						<div class="form-group col-sm-4">
							<div class="input-group">
								<div class="input-group-addon">Número de Visita</div>
								<input type="number" class="form-control input" name="numero_visita" value="<?php echo $numero_visita; ?>" readonly>
							</div>
						</div>

						<div class="form-group col-sm-4">
							<div class="input-group">
								<div class="input-group-addon">Hora Ini</div>
								<input data-parsley-pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" name="hora_inicio" type="text" class="form-control input" id="exampleInputEmail2" placeholder="Ejemplo: 08:00" required >
							</div>
						</div>

						<div class="form-group col-sm-4">
							<div class="input-group">
								<div class="input-group-addon">Hora Fin</div>
								<input data-parsley-pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" name="hora_fin" type="text" class="form-control input" id="exampleInputEmail2" placeholder="Ejemplo: 10:00" required >
							</div>
						</div>
					</div>

					<div class="form-group col-sm-12">
						<div class="form-group col-sm-12">
							<div class="input-group">
								<div class="input-group-addon">Asistentes</div>
								<textarea class="form-control" name="nombre_asistentes" rows="3" style="width:720px" placeholder="Digite los Participantes (Nombres y teléfonos contacto)"required ></textarea>
							</div>
						</div>
					</div>

					<div class="form-group col-sm-12">
						<div class="form-group col-sm-12">
							<div class="input-group">
								<div class="input-group-addon">Información Complementaria</div>
								<textarea class="form-control" name="informacion_complementaria" rows="3" style="width:600px" placeholder="Utilice este espacio para ingresar la información complementaria del acta fisica"required ></textarea>
							</div>
						</div>
					</div>

					<?php if ($id_componente == 7 && $id_modalidad == 5){ ?>
						<div class="">
							<div class="form-group col-sm-12">
								<div class="form-group col-sm-12">
									<div class="input-group">
										<div class="input-group-addon">Tema Encuentro Educativo</div>
										<textarea class="form-control" name="tema_encuentro" rows="3" style="width:600px" placeholder="Utilice este espacio para ingresar el tema del encuentro educativo"></textarea>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</center>

				<!-- </center> -->


				<div class="bs-docs-section">
					<h2 id="tables-example">Evaluación</h2>
				</div>
				<div class="footer"></div>

				<div class="alert alert-danger" role="alert">


				<?php if($tipo_acta == 4){ ?>
				<strong>¡Señor profesional de área!</strong> Para diligenciar esta evaluación tenga en cuenta las siguientes convenciones:
					1.AC		= Aceptable,
					2.AR	= Aceptable con requerimiento,
					<!-- 4.NS 	= No Subsanable, -->
					3.I	= Inaceptable.

				<?php } else { ?>
					<strong>¡Señor profesional de área!</strong> Para diligenciar esta evaluación tenga en cuenta las siguientes convenciones:
					1.C		= Cumple,
					3.NC	= No Cumple,
					<!-- 4.NS 	= No Subsanable, -->
					5.NA	= No Aplica.

				<?php } ?>

				
				</div>


				<?php
				//SECCION PREGUNTAS A EVALUAR

				if(mysql_num_rows($preguntas) > 0){ ?>


					<div class="footer"></div>




					<input type="hidden" name="caso" value="6">
					<input type="hidden" name="id_componente" value="<?php echo $id_componente; ?>">
					<input type="hidden" name="id_codigoacta" value="<?php echo $id_codigoacta; ?>">
					<input type="hidden" name="id_contrato" value="<?php echo $id_contrato; ?>">
					<input type="hidden" name="id_sede" value="<?php echo $id_sede; ?>">
					<input type="hidden" name="id_prestador" value="<?php echo $id_prestador; ?>">
					<input type="hidden" name="id_modalidad" value="<?php echo $id_modalidad; ?>">
					<input type="hidden" name="id_interventor" value="<?php echo $id_interventor; ?>">
					<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
					<input type="hidden" name="acta_reservada" value="<?php echo $acta_reservada; ?>">
					<input type="hidden" name="porc_componente_x_modalidad" value="<?php echo $porc_componente_x_modalidad; ?>">





					<div class="panel-group" id="accordion"> <!-- Inicio acordeón -->
						<?php
						$id_tema = 0; //Hace la comparación por los id_tema, cuando cambia de id_tema empaqueta en un nuevo acordeon
						$i = 0;
						while($row = mysql_fetch_assoc($preguntas)){

							?>
							<input type="hidden" name="id_tema[]" value="<?php echo $row['id_tema']; ?>">
							<input type="hidden" name="id_subtema[]" value="<?php echo $row['id_subtema']; ?>">
							<input type="hidden" name="id_pregunta[]" value="<?php echo $row['id_pregunta']; ?>">
							<input type="hidden" name="descripcion_pregunta[]" value="<?php echo $row['descripcion_pregunta']; ?>">
							<input type="hidden" name="descripcion_observacion[]" value="<?php echo $row['descripcion_observacion']; ?>">
							<input type="hidden" name="descripcion_accion_correctiva[]" value="<?php echo $row['descripcion_accion_correctiva']; ?>">
							<?php

							if($i == 0){ ?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>">
												<?php echo $row['nombre_tema']; ?>
											</a>
										</h4>
									</div>
									<div id="collapse<?php echo $i; ?>" class="panel-collapse collapse in">
										<div class="panel-body">
											<table class="table table-bordered table-hover" id='tcreados'>
												<thead>
													<tr>
														<th class="info">#</th>
														<th class="info">Criterio de calidad</th>
														<th class="info">Descripción de la pregunta</th>
														<th class="info">*Calificación*</th>
														<th class="info">Observación</th>
													</tr>
												</thead>
												<tbody>
												<?php } else if($id_tema !== $row['id_tema'] && $id_tema !== 0){ ?>
												</tbody>
											</table>
										</div> <!-- fin panel-body -->
									</div> <!-- fin colapse one -->
								</div> <!-- fin panel-default -->
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>">
												<?php echo $row['nombre_tema']; ?>
											</a>
										</h4>
									</div>
									<div id="collapse<?php echo $i; ?>" class="panel-collapse collapse">
										<div class="panel-body">
											<table class="table table-bordered table-hover" id='tcreados'>
												<thead>
													<tr>
														<th class="info">#</th>
														<th class="info">Estándar</th>
														<th class="info">Descripción de la Pregunta</th>
														<th class="info">*Calificación*</th>
													</tr>
												</thead>
												<tbody>
												<?php } ?>
												<tr>
													<td class="active"><?php echo $row['id_pregunta'];  ?></td>
													<td class="active"><?php echo $row['nombre_subtema'];  ?></td>
													<td class="active"><?php echo $row['descripcion_pregunta'];  ?></td>
													<td class="active">
													
													<?php
													
													if($tipo_acta == 4){ ?>

														<select data-parsley-min="1" class="form-control" id="select1" name="valor_calificacion[]" required>
															<option value=""	selected	>Seleccione</option>
															<option value="1"	>1.A</option>
															<option value="2" 			>2.AR</option>
															<!-- <option value="4" 			>4.NS</option> -->
															<option value="3" 			>3.I</option>
														</select>
														<?php } else { ?>
														<select data-parsley-min="1" class="form-control" id="select1" name="valor_calificacion[]" required>
														<option value=""	selected	>Seleccione</option>	
														<option value="1"	>1.C</option>
															<option value="3" 			>3.NC</option>
															<!-- <option value="4" 			>4.NS</option> -->
															<option value="5" 			>5.NA</option>
														</select>
														<?php } ?>
													</td>
													<td class="active">
														<textarea name="observacion[]"  style="width:100%"></textarea>
													</td>
												</tr>
												<?php $i++; $id_tema = $row['id_tema']; } ?>
											</tbody>
										</table>
									</div> <!-- fin panel-body -->
								</div> <!-- fin colapse one -->
							</div> <!-- fin panel-default -->
						</div> <!-- Fin acordeón -->






						<br>
						<center><button  class="btn btn-lg btn-pascual" type="submit">Guardar y Continuar</button></center>
						<br>
						<br>
					</form>




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

				<div class="footer">
					<center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

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
			$(".transporte_interventoria").change(function(){
				if ($(this).val() == "SI") {
					$(".valor").val(0);
					$(".valor").attr('readonly', true);
				}else {
					$(".valor").val(6000);
					$(".valor").attr('readonly', false);
				}
			});

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
