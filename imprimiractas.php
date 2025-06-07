<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";




	//Variables Globales declaradas
	$id_grupo=$_SESSION["grupo"];
	$nombre=$_SESSION["nombre_usuario"];
	$user=$_SESSION["login"];
	$fotoperfil = $_SESSION["fotoperfil"];
	//Variables recibidas del _GET
	$id_acta=$_GET['id_acta'];
	$msg=$_GET['msg'];


	//Ruta donde se cargan las fotos
	$ruta_componente=substr($id_acta,4,2);

	//Ruta Escritura
	$folder = "/var/www/2017/html/uploadstella/".$ruta_componente."/";

	//Ruta Lectura
	$folder_lectura="../uploadstella/".$ruta_componente."/";





	//Querya traew todoq lo campos guardados del acta
	$queryacta= mysql_query(("
	SELECT
	acta.id_acta,
	acta.fecha_evaluacion,
	acta.hora_inicio,
	acta.hora_fin,
	acta.id_contrato,
	acta.id_componente,
	componente.nombre_componente,
	prestador.nombre_prestador,
	modalidad.nombre_modalidad,
	acta.id_sede,
	acta.numero_visita,
	acta.nombre_sede,
	acta.direccion_sede,
	acta.telefono_sede,
	acta.nombre_asistentes,
	acta.informacion_complementaria,
	acta.id_interventor,
	acta.estado,
	acta.tema_encuentro,
	modalidad.id_modalidad
	FROM
	acta,componente,prestador,modalidad
	WHERE
	acta.id_componente=componente.id_componente and
	acta.id_prestador=prestador.id_prestador and
	acta.id_modalidad=modalidad.id_modalidad and
	id_acta='$id_acta'
	"),$conexion);

	while($row=mysql_fetch_assoc($queryacta)){
		$id_acta=$row['id_acta'];
		$fecha_evaluacion=$row['fecha_evaluacion'];
		$hora_inicio=$row['hora_inicio'];
		$hora_fin=$row['hora_fin'];
		$id_componente=$row['id_componente'];
		$nombre_componente=$row['nombre_componente'];
		$id_contrato=$row['id_contrato'];
		$nombre_prestador=$row['nombre_prestador'];
		$nombre_modalidad=$row['nombre_modalidad'];
		$id_sede=$row['id_sede'];
		$numero_visita=$row['numero_visita'];
		$nombre_sede=$row['nombre_sede'];
		$direccion_sede=$row['direccion_sede'];
		$telefono_sede=$row['telefono_sede'];
		$nombre_asistentes=$row['nombre_asistentes'];
		$informacion_complementaria=$row['informacion_complementaria'];
		$id_interventor=$row['id_interventor'];
		$tema_encuentro=$row['tema_encuentro'];
		$id_modalidad=$row["id_modalidad"];
		if ($id_grupo == 4) {
			$estado=0;
		} else {
			$estado=$row['estado'];
		}
	}

	//Query para traer todas las preguntas de la evaluacion correspondientes al acta
	$querypreguntas= mysql_query(("
	SELECT
	evaluacion.id_evaluacion,
	evaluacion.id_pregunta,
	tema.nombre_tema,
	subtema.nombre_subtema,
	pregunta.descripcion_pregunta,
	evaluacion.valor_calificacion,
	evaluacion.valor_calificacion_final
	FROM
	evaluacion,tema,subtema,pregunta
	WHERE
	evaluacion.id_tema=tema.id_tema and
	evaluacion.id_subtema=subtema.id_subtema and
	evaluacion.id_pregunta=pregunta.id_pregunta and
	id_acta='$id_acta'
	"),$conexion);

	//Query para traer todas las observaciones de la evaluacion correspondientes al acta
	$queryhallazgos= mysql_query(("
	SELECT
	subsanacion.id_subsanacion,
	subsanacion.id_acta,
	subsanacion.id_pregunta,
	subsanacion.descripcion_pregunta,
	subsanacion.descripcion_observacion,
	subsanacion.descripcion_accion_correctiva,
	subsanacion.fecha_subsanacion,
	subsanacion.fecha_subsanacion_final,
	subsanacion.fecha_solicitud_aclaracion,
	subsanacion.id_radicado_osa,
	subsanacion.fecha_requerimiento,
	subsanacion.id_radicado_orq,
	subsanacion.fecha_envio_evidencia,
	subsanacion.historico,
	subsanacion.etapa,
	subsanacion.estado,
	evaluacion.id_evaluacion,
	evaluacion.valor_calificacion,
	evaluacion.valor_calificacion_final


	FROM
	subsanacion,evaluacion
	WHERE
	subsanacion.id_acta=evaluacion.id_acta and
	subsanacion.id_pregunta=evaluacion.id_pregunta and
	subsanacion.id_acta='$id_acta' and
	subsanacion.estado='1'
	"),$conexion);

	// query para traer el numero de folios que se han verificado correspondiente al acta -- Solo para Salud--.
	$queryfolios = mysql_query(("SELECT * FROM folios_salud WHERE id_acta = '$id_acta';"),$conexion);


	//Query para traer todas las observaciones del interventor al acta
	$queryobsinterventor= mysql_query(("
	SELECT
	*
	FROM
	observacion_evaluador
	WHERE
	id_acta='$id_acta' and
	estado='1'
	"),$conexion);

	//Query para traer todas las observaciones del usuario al acta
	$queryobsusuario= mysql_query(("
	SELECT
	*
	FROM
	observacion_usuario
	WHERE
	id_acta='$id_acta' and
	estado='1'
	"),$conexion);


	//Query para traer todas los archivos cargados al acta
	$queryarchivo= mysql_query(("
	SELECT
	*
	FROM
	archivo
	WHERE
	id_acta='$id_acta' and
	estado='1'
	"),$conexion);


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

		<title>Editar actas creadas</title>



		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/datepicker.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="css/jumbotron-narrow.css" rel="stylesheet">
		<link href="css/theme.default.css" rel="stylesheet">

		<!-- bootstrap widget theme -->
		<link href="css/theme.bootstrap.css" rel="stylesheet" >

		<!-- JavaScript para los filtros de las tablas -->
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/jquery.tablesorter.js"></script>
		<script src="js/jquery.tablesorter.widgets.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		<!-- Estilos menú principal -->
		<link rel="stylesheet" href="css/estilos.css">

		<!-- Material Icons -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

		<!-- Para validacion de campos -->
		<script src="js/parsley.js"></script>

		<!-- Upload Files -->
		<script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>



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
	<p>
		<a class="btn btn-success btn-lg" role="button">Imprimir Acta</a>

		<?php
		if($estado==1){
			?>
			<a class="btn btn-pascual btn-lg" role="button" href='deletes.php?eliminar=<?php echo $id_acta; ?>&caso=4'>Bloquear Acta</a>
		</p>
		<?php
	}
	?>


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
						<button type="button" class="close" aria-hidden="true">x</button></h5>
					</div>
					<?php
				} // End else msg=2
			} //End msg=0
			?>







			<div class="row">
				<div class="col-md-12">
					<table class="table table-bordered table-hover table1">


						<tr>
							<th colspan="3"><div style="text-align:center">ACTA DE SUPERVISIÓN A LA PRESTACIÓN DEL SERVICIO DE ATENCIÓN INTEGRAL A LA PRIMERA INFANCIA</div></th>
						</tr>


						<tbody>
						</tbody>

						<tbody>
							<tr>
								<td class="col-md-4">Acta: <strong><?php echo $id_acta; ?></strong></td>
								<td class="col-md-4">Contrato: <strong><?php echo $id_contrato; ?></td>
									<td class="col-md-4">Visita: <strong><?php echo $numero_visita; ?></td>
									</tr>

									<tr>
										<td class="col-md-4">Componente: <strong><?php echo $nombre_componente; ?></td>
											<td class="col-md-4">Prestador: <strong><?php echo $nombre_prestador; ?></td>
												<td class="col-md-4">Modalidad: <strong><?php echo $nombre_modalidad; ?></td>
												</tr>

												<tr>
													<?php if ($id_componente == 4){ ?>
														<td class="col-md-4">Sede: <strong><?php echo explode("-", $nombre_sede)[0]; ?></td>
														<?php }else{ ?>
															<td class="col-md-4">Sede: <strong><?php echo $nombre_sede; ?></td>
															<?php } ?>
																<td class="col-md-4">Teléfono: <strong><?php echo $telefono_sede; ?></td>
																	<td class="col-md-4">Dirección: <strong><?php echo $direccion_sede; ?></td>
																	</tr>

																	<tr>
																		<td class="col-md-4">Fecha: <strong><?php echo $fecha_evaluacion; ?></td>
																			<td class="col-md-4">Hora Inicio: <strong><?php echo $hora_inicio; ?></td>
																				<td class="col-md-4">Hora Fin: <strong><?php echo $hora_fin; ?></td>
																				</tr>

																				<tr>
																					<td class="col-md-4">Interventor: <strong><?php echo $id_interventor; ?></td>
																						<td colspan="2">Asistentes: <strong><?php echo $nombre_asistentes; ?></td>
																						</tr>

																						<tr>
																							<?php if ($id_componente == 7 & $id_modalidad == 5){ ?>
																								<td colspan="2">Información Complementaria: <strong><?php echo $informacion_complementaria; ?></td>
																									<td colspan="3">Tema Encuentro Educativo: <strong><?php echo $tema_encuentro; ?></td>
																									<?php }else{ ?>
																										<td colspan="3">Información Complementaria: <strong><?php echo $informacion_complementaria; ?></td>
																										<?php } ?>
																									</tr>

																								</tbody>
																							</table>
																						</div>

																					</div>








																					<div class="bs-docs-section">
																						<h3 id="tables-example">Preguntas Evaluadas</h3>
																					</div>
																					<div class="footer"></div>


																					<div class="alert alert-info" role="alert">
																						Para editar esta evaluación tenga en cuenta las siguientes convenciones:
																						1= Cumple,
																						3= No Cumple,
																						4= No Subsanable,
																						5= No Aplica.

																					</div>




																					<table class="table table-bordered table-hover table1" id='temas_creados'>
																						<tbody>
																							<?php
																							$nombre_subtema = 0;
																							while($row = mysql_fetch_assoc($querypreguntas)){
																								?><input type="hidden" name="id_pregunta[]" value="<?php echo $row['id_pregunta'] ?>">	<?php
																								if($nombre_subtema !== $row['nombre_subtema']) {
																									?>
																									<tr><th colspan="5"><?php echo $row['nombre_subtema'];  ?></th></tr>
																									<tr>
																										<th>#</th>
																										<th>Pregunta</th>
																										<th>Calf. inicial</th>
																										<th>Calf. Final</th>
																									</tr>
																									<?php $nombre_subtema = $row['nombre_subtema']; } ?>
																									<tr>
																										<td><?php echo $row['id_pregunta'];  ?></td>
																										<td><?php echo $row['descripcion_pregunta'];  ?></td>
																										<td><center><?php echo $row['valor_calificacion'];  ?><center></td>

																											<?php if($row['valor_calificacion_final']==1){
																												?>
																												<td class="alert alert-success"><center><?php echo $row['valor_calificacion_final'];  ?><center></td>

																													<?php
																												} else{
																													?>
																													<td class="alert alert-danger"><center><?php echo $row['valor_calificacion_final'];  ?><center></td>
																														<?php
																													}
																													?>
																												</tr>
																											<?php } ?>
																										</tbody>
																									</table>


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
																														<button type="button" class="close" aria-hidden="true">x</button></h5>
																													</div>
																													<?php
																												} // End else msg=2
																											} //End msg=0
																											?>





																											<br>
																											<div class="bs-docs-section">
																												<h3 id="tables-example">Debido Proceso de los Hallazgos Encontrados</h3>
																											</div>
																											<div class="footer"></div>

																										</div> <!-- /container -->




																										<form id="subsanacion" data-parsley-validate id="3" name="update" METHOD="post" action="inserts.php">
																											<input type="hidden" name="caso" value="7">
																											<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">




																											<?php if(mysql_num_rows($queryhallazgos) > 0){ ?>
																												<table align="center" class="table table-bordered table1 " id='table' style="width: 95%">

																													<thead>

																														<tr>
																															<th>#</th>
																															<th>Descripcion_Pregunta</th>
																															<th>Calf. Ini</th>
																															<th>Hallazgos_Encontrados</th>
																															<th>Acciones_Correctivas</th>
																															<th class="success">Plazo Acciones .Correctivas.</th>
																															<th class="success">Prorroga Acciones .Correctivas.</th>
																															<th class="warning">Plazo Solicitud .Aclaración.</th>
																															<th class="warning">Radicado Solicitud .Aclaración.</th>
																															<th class="danger">Plazo Requerimiento</th>
																															<th class="danger">Radicado Requerimiento</th>
																															<th class="info">Envío .Evidencias.</th>
																															<th class="info">Calf. Fin</th>
																															<th class="info">Etapa del seguimiento</th>
																														</tr>

																													</thead>

																													<tbody>
																														<?php
																														while($row = mysql_fetch_assoc($queryhallazgos)){
																															$fechaactual = date('Y-m-d');
																															if ($row['etapa'] == 'AC') {
																																if ($row['fecha_subsanacion_final'] == $fechaactual) {
																																	if ($row['fecha_solicitud_aclaracion'] == "0000-00-00") {
																																		$row['fecha_solicitud_aclaracion'] = date('Y-m-d',strtotime('+5 day', strtotime($fechaactual)));
																																	}
																																}
																															}
																															if ($row['etapa'] == 'SA') {
																																if ($row['fecha_subsanacion_final'] == $fechaactual) {
																																	if ($row['fecha_requerimiento'] == "0000-00-00") {
																																		$row['fecha_requerimiento'] = date('Y-m-d',strtotime('+5 day', strtotime($fechaactual)));
																																	}
																																}
																															}
																															if ($row['valor_calificacion_final'] == 4) {
																																$row['etapa'] = 'OK';
																															}
																															?>

																															<tr>
																																<td>
																																	<input type="hidden" name="id_pregunta[]" value="<?php echo $row['id_pregunta'] ?>"><?php echo $row['id_pregunta'];  ?>
																																	<input type="hidden" name="id_evaluacion[]" value="<?php echo $row['id_evaluacion'] ?>">
																																	<input type="hidden" name="id_subsanacion[]" value="<?php echo $row['id_subsanacion'] ?>">
																																	<input type="hidden" name="etapa_anterior[]" value="<?php echo $row['historico'] ?>">
																																</td>

																																<td><?php echo $row['descripcion_pregunta'];  ?></td>

																																<td><?php echo $row['valor_calificacion'];  ?></td>

																																<td><textarea name="descripcion_observacion[]" rows="4" style="width:250px"><?php echo $row['descripcion_observacion'];  ?></textarea></td>

																																<td><textarea name="descripcion_accion_correctiva[]" rows="4" style="width:250px"><?php echo $row['descripcion_accion_correctiva'];  ?></textarea></td>

																																<td>
																																	<center>
																																		<input
																																		data-parsley-required
																																		data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"
																																		class="form-control input datepicker"
																																		name="fecha_subsanacion[]"
																																		type="text"
																																		value="<?php echo $row['fecha_subsanacion']; ?>"
																																		placeholder="<?php echo $row['fecha_subsanacion']; ?>"
																																		>
																																	</center>
																																</td>

																																<td>
																																	<center>
																																		<input
																																		data-parsley-required
																																		data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"
																																		class="form-control input datepicker"
																																		name="fecha_subsanacion_final[]"
																																		type="text"
																																		value="<?php echo $row['fecha_subsanacion_final']; ?>"
																																		placeholder="<?php echo $row['fecha_subsanacion_final']; ?>"
																																		>
																																	</center>
																																</td>

																																<td>
																																	<center>
																																		<input
																																		data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"
																																		class="form-control input datepicker"
																																		name="fecha_solicitud_aclaracion[]"
																																		type="text"
																																		placeholder="<?php if($row['fecha_solicitud_aclaracion']=="0000-00-00"){echo "";} else{echo $row['fecha_solicitud_aclaracion'];} ?>"
																																		value="<?php if($row['fecha_solicitud_aclaracion']=="0000-00-00"){echo "";} else{echo $row['fecha_solicitud_aclaracion'];} ?>"
																																		>
																																	</center>
																																</td>


																																<td>
																																	<input type="text" name="id_radicado_osa[]"  class="form-control" value="<?php echo $row['id_radicado_osa'];  ?>" placeholder="<?php echo $row['id_radicado_osa'];  ?>">
																																</td>

																																<td>
																																	<center>
																																		<input
																																		data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"
																																		class="form-control input datepicker"
																																		name="fecha_requerimiento[]"
																																		type="text"
																																		placeholder="<?php if($row['fecha_requerimiento']=="0000-00-00"){echo "";} else{echo $row['fecha_requerimiento'];} ?>"
																																		value="<?php if($row['fecha_requerimiento']=="0000-00-00"){echo "";} else{echo $row['fecha_requerimiento'];} ?>"
																																		>
																																	</center>
																																</td>

																																<td>
																																	<input type="text" name="id_radicado_orq[]"  class="form-control" value="<?php echo $row['id_radicado_orq'];  ?>" placeholder="<?php echo $row['id_radicado_orq'];  ?>">
																																</td>


																																<td>
																																	<center>
																																		<input
																																		data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"
																																		class="form-control input datepicker"
																																		name="fecha_envio_evidencia[]"
																																		type="text"
																																		placeholder="<?php if($row['fecha_envio_evidencia']=="0000-00-00"){echo "";} else{echo $row['fecha_envio_evidencia'];} ?>"
																																		value="<?php if($row['fecha_envio_evidencia']=="0000-00-00"){echo "";} else{echo $row['fecha_envio_evidencia'];} ?>"
																																		>
																																	</center>
																																</td>
																																<?php if ($row['valor_calificacion_final'] == 4){ ?>
																																	<td><center><textarea readonly data-parsley-min="1"  data-parsley-max="4" data-parsley-type="number" data-parsley-required name="valor_calificacion_final[]" rows="1" style="width:50px" placeholder="<?php echo $row['valor_calificacion_final'];  ?>" autofocus><?php echo $row['valor_calificacion_final'];  ?></textarea></center></td>
																																<?php }else{ ?>
																																	<td><center><textarea data-parsley-min="1"  data-parsley-max="4" data-parsley-type="number" data-parsley-required name="valor_calificacion_final[]" rows="1" style="width:50px" placeholder="<?php echo $row['valor_calificacion_final'];  ?>" autofocus><?php echo $row['valor_calificacion_final'];  ?></textarea></center></td>
																																<?php } ?>
																																<!-- <?php if ($row['valor_calificacion_final'] == 4){ ?>
																																<td>
																																<span class="form-control"><?php echo 'OK' ?></span>
																															</td>
																														<?php }else{ ?>
																														<td>
																														<select class="form-control" id="select1" name="etapa[]">
																														<option value="<?php echo $row['etapa'];  ?>" selected><?php echo $row['etapa'];  ?></option>
																														<option value="AC" >AC</option>
																														<option value="SA" >SA</option>
																														<option value="REQ">REQ</option>
																														<option value="OK" >OK</option>
																													</select>
																												</td>

																											<?php } ?> -->
																											<td>
																												<select class="form-control" id="" name="etapa[]">
																													<option value="<?php echo $row['etapa'];  ?>"><?php echo $row['etapa']; ?></option>
																													<option value="AC" <?php if($row['valor_calificacion_final'] == 4){ echo "disabled";} ?>>AC</option>
																													<option value="SA"<?php if($row['valor_calificacion_final'] == 4){ echo "disabled";} ?>>SA</option>
																													<option value="REQ"<?php if($row['valor_calificacion_final'] == 4){ echo "disabled";} ?>>REQ</option>
																													<option value="OK" <?php if($row['valor_calificacion_final'] == 4){ echo "disabled";} ?>>OK</option>
																												</select>

																											</td>


																										</tr>
																									<?php } ?>
																								</tbody>
																							</table>



																							<?php
																							if($estado==1){
																								?>
																								<center><button class="btn btn-pascual" type="submit">Modificar Evaluación</button></center>
																								<?php
																							}
																							?>

																							<br>
																							<br>
																						</form>

																					<?php }
																					else{

																						?>

																						<div class="container">
																							<div class="alert alert-success" role="alert">
																								¡Felicitaciones Señor Operador: En el momento de la realización de la visita, no fueron encontrados hallazgos por subsanar!
																							</div>
																						</div>

																						<!-- Este codigo se pone para evitar un error no solucionado en el parsley de las observaciones interventor y Prestador -->
																						<form data-parsley-validate id="3" name="update" METHOD="post" action="inserts.php">
																						</form>

																						<?php
																					}

																					?>

																					<div class="container">


																						<!-- ************************************************************************************** -->

																						<br>
																						<?php if($id_componente == 1) { ?>
																							<div class="bs-docs-section"><h3 id="tables-example">Conteo de Folios de Salud</h3></div>
																							<?php if(mysql_num_rows($queryfolios) > 0 OR true){ ?>
																								<table class="table table-bordered table-hover" id='tcreados' data-sorter="false">
																									<thead>
																										<tr>
																											<th colspan="13" center> Folios Registrados:
																											</th>
																										</tr><tr>
																											<th rowspan="3">TOTAL</th>
																											<th colspan="4" style="display:none;">TAMIZAJE</th>
																											<th colspan="3">SGSSS</th>
																											<th colspan="3">Valoración Integral</th>
																											<th colspan="3" style="display:none;">VACUNAS</th>
																										</tr><tr>
																											<th>Documento Afiliación</th>
																											<th>Activación Ruta</th>
																											<th style="display:none;">OS</th>
																											<th>Sin Verif</th>
																											<th>Valoración Completa</th>
																											<th>Activación Rutas</th>
																											<th>Sin Verif</th>
																											<th style="display:none;">Docs</th>
																											<th style="display:none;">OS</th>
																											<th style="display:none;">SinVerif</th>
																											<th style="display:none;">Docs</th>
																											<th style="display:none;">OS</th>
																											<th style="display:none;">SinVerif</th>
																										</tr>
																									</thead>
																									<tbody>
																										<?php
																										while($row = mysql_fetch_assoc($queryfolios)){
																											$tv=$row['sisben_tv']; // tamizaje visual Daniel Gallo 24/03/2017
																											$sd=$row['sisben_doc'];
																											$sr=$row['sisben_ruta'];
																											$sv=$row['sisben_sinv'];
																											$fd=$row['fosyga_doc'];
																											$fr=$row['fosyga_ruta'];
																											$fv=$row['fosyga_sinv'];
																											$cd=$row['cyd_doc'];
																											$cr=$row['cyd_ruta'];
																											$cv=$row['cyd_sinv'];
																											$vd=$row['vacuna_doc'];
																											$vr=$row['vacuna_ruta'];
																											$vv=$row['vacuna_sinv'];
																											$total = $row['total'];
																										} ?>
																										<tr class="text-center">
																											<td class="active"><input type="text" id="folios" name="folios" value="<?php echo @$total; ?>" size="4" /></td>
																											<td style="display:none;" class="active"><input type="text" id="tv" name="tv" value="<?php echo @$tv; ?>" size="4" /></td>
																											<td style="display:none;" class="active"><input type="text" id="sd" name="sd" value="<?php echo @$sd; ?>" size="4" /></td>
																											<td style="display:none;" class="active"><input type="text" id="sr" name="sr" value="<?php echo @$sr; ?>" size="4" /></td>
																											<td style="display:none;" class="active"><input type="text" id="sv" name="sv" value="<?php echo @$sv; ?>" size="4" /></td>
																											<td class="active"><input type="text" id="fd" name="fd" value="<?php echo @$fd; ?>" size="4" /></td>
																											<td class="active"><input type="text" id="fr" name="fr" value="<?php echo @$fr; ?>" size="4" /></td>
																											<td class="active"><input type="text" id="fv" name="fv" value="<?php echo @$fv; ?>" size="4" /></td>
																											<td class="active"><input type="text" id="cd" name="cd" value="<?php echo @$cd; ?>" size="4" /></td>
																											<td class="active"><input type="text" id="cr" name="cr" value="<?php echo @$cr; ?>" size="4" /></td>

																											<td class="active"><input type="text" id="cv" name="cv" value="<?php echo @$cv; ?>" size="4" /></td>
																											<td style="display:none;" class="active"><input type="text" id="vr" name="vr" value="<?php echo @$vr; ?>" size="4" /></td>
																											<td style="display:none;" class="active"><input type="text" id="vd" name="vd" value="<?php echo @$vd; ?>" size="4" /></td>
																											<td style="display:none;" class="active"><input type="text" id="vv" name="vv" value="<?php echo @$vv; ?>" size="4" /></td>
																										</tr>

																									<?php } ?>

																								</tbody>
																							</table>
																							<?php if($estado==1){ ?>
																								<center><button  class="btn btn-pascual" type="button" onClick="grabaFolios();">Ingresar Folios</button></center>
																							<?php } ?>
																						<?php } ?>
																						<br>
																						<br>
																						<div class="bs-docs-section"><h3 id="tables-example">Observaciones Generales de la Supervisión</h3></div>
																						<div class="footer"></div>


																						<?php if(mysql_num_rows($queryobsinterventor) > 0){ ?>
																							<table class="table table-bordered table-hover table1" id='tcreados'>
																								<thead>
																									<tr>
																										<th>Fecha</th>
																										<th>Observación Interventor</th>
																										<th>Creada por</th>
																									</tr>
																								</thead>


																								<tbody>
																									<?php
																									while($row = mysql_fetch_assoc($queryobsinterventor)){ ?>
																										<tr>
																											<td><?php echo $row['fecha_observacion_evaluador'];  ?></td>
																											<td><?php echo $row['descripcion_observacion_evaluador'];  ?></td>
																											<td><?php echo $row['id_interventor'];  ?> <br>
																												<?php if($estado==1){ ?>
																													<img src="images/eliminar.png" onClick="borrarcomentario(<?php echo $row['id_observacion_evaluador'];  ?>,'e');">
																												<?php } ?>
																											</td>
																										</tr>
																									<?php } ?>
																								</tbody>

																							</table>

																						<?php } ?>

																						<form data-parsley-validate id="1"  role="form1" name="obsinterv" METHOD="post" action="inserts.php">
																							<input type="hidden" name="caso" value="8">
																							<input type="hidden" name="id_interventor" value="<?php echo $user; ?>">
																							<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
																							<input type="hidden" name="fecha_observacion_evaluador" value="<?php echo date("Y-m-d H:i:s");  ?>">

																							<?php if($estado==1){ ?>
																								<td class="active"><textarea data-parsley-required name="descripcion_observacion_evaluador" rows="4" style="width:100%" placeholder="Ingrese una nueva Observación" ></textarea></td>

																								<br>
																								<center><button  class="btn btn-pascual" type="submit">Ingresar Observación</button></center>
																							<?php } ?>

																							<br>
																							<br>

																						</form>


																						<!-- ************************************************************************************** -->

																						<br>
																						<div class="bs-docs-section"><h3 id="tables-example">Observaciones Generales del Prestador</h3>	</div>
																						<div class="footer"></div>

																						<?php if(mysql_num_rows($queryobsusuario) > 0){ ?>
																							<table class="table table-bordered table-hover table1" id='tcreados'>
																								<thead>
																									<tr>
																										<th>Fecha</th>
																										<th>Observación Prestador</th>
																										<th>Creada por</th>
																									</tr>
																								</thead>


																								<tbody>
																									<?php
																									while($row = mysql_fetch_assoc($queryobsusuario)){ ?>
																										<tr>
																											<td><?php echo $row['fecha_observacion_usuario'];  ?></td>
																											<td><?php echo $row['descripcion_observacion_usuario'];  ?></td>
																											<td><?php echo $row['id_interventor'];  ?><br>
																												<?php if($estado==1){ ?>
																													<img src="images/eliminar.png" onClick="borrarcomentario(<?php echo $row['id_observacion_usuario'];  ?>,'u');">
																												<?php } ?>
																											</td>
																										</tr>
																									<?php } ?>
																								</tbody>

																							</table>

																						<?php } ?>

																						<form data-parsley-validate id="2"  role="form2" name="obsusuario" METHOD="post" action="inserts.php">
																							<input type="hidden" name="caso" value="9">
																							<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
																							<input type="hidden" name="id_interventor" value="<?php echo $user; ?>">
																							<input type="hidden" name="fecha_observacion_usuario" value="<?php echo date("Y-m-d H:i:s");  ?>">

																							<?php if($estado==1){ ?>
																								<td class="active"><textarea data-parsley-required name="descripcion_observacion_usuario" rows="4" style="width:100%" placeholder="Ingrese una nueva Observación" ></textarea></td>
																								<br>
																								<center><button  class="btn btn-pascual" type="submit">Ingresar Observación</button></center>
																							<?php } ?>

																							<br>
																							<br>
																						</form>

																						<!-- ************************************************************************************** -->

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

																									$errores=$_GET['errores'];
																									?>

																									<div id="mensaje" class="alert alert-danger" role="alert">
																										<h5 align="center"><strong>¡Advertencia!</strong>
																											¡No se ha podido subir el archivo! <?php  echo $errores; ?>.<button type="button" class="close" aria-hidden="true">x</button></h5>
																										</div>
																										<?php
																									} // End else msg=2
																								} //End msg=0
																								?>


																								<br>
																								<div class="bs-docs-section"><h3 id="tables-example">Cargar Imágenes al Acta (Solo archivos pdf, jpg, jpeg, png)</h3>	</div>
																								<div class="footer"></div>

																								<?php if(mysql_num_rows($queryarchivo) > 0){ ?>
																									<table class="table table-bordered table-hover" id='tcreados'>
																										<thead>
																											<tr>
																												<th>Imagen</th>
																												<th>Fecha</th>
																												<th>Nombre del Archivo</th>
																												<th>Descripción del Archivo</th>
																												<th>Subida por</th>
																											</tr>
																										</thead>


																										<tbody>
																											<?php
																											while($row = mysql_fetch_assoc($queryarchivo)){ ?>
																												<tr>
																													<td>
																														<a href="<?php echo $folder_lectura.$row['nombre_archivo'];  ?>" class="thumbnail" target="_blank">
																															<?php if(substr($row['nombre_archivo'], -3)=="pdf" || substr($row['nombre_archivo'], -3)=="xls" || substr($row['nombre_archivo'], -4)=="xlsx") { ?>
																																<img src="<?php echo $folder_lectura."file.png";  ?>" alt="imagen"></a>
																															<?php } else { ?>
																																<img src="<?php echo $folder_lectura.$row['nombre_archivo'];  ?>" alt="imagen"></a>
																															<?php }  ?>
																														</td>
																														<td><?php echo $row['fecha_archivo'];  ?></td>
																														<td><?php echo $row['nombre_archivo'];  ?></td>
																														<td><?php echo $row['descripcion_archivo'];  ?></td>
																														<td><?php echo $row['id_interventor'];  ?></td>
																													</tr>
																												<?php } ?>
																											</tbody>

																										</table>

																									<?php } ?>




																									<form data-parsley-validate name="upload-file" id="upload-file" METHOD="post" enctype="multipart/form-data" action="inserts.php">
																										<input type="hidden" name="caso" value="16">
																										<input type="hidden" name="folder" value="<?php echo $folder; ?>">
																										<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
																										<input type="hidden" name="id_interventor" value="<?php echo $user; ?>">
																										<input type="hidden" name="fecha_archivo" value="<?php echo date("Y-m-d H:i:s");  ?>">

																										<?php if($estado==1){ ?>
																											<input type="text" data-parsley-required name="descripcion_archivo"  class="form-control" placeholder="Descripción del archivo">
																											<input data-parsley-required type="file" class="filestyle" name="archivo" id="archivo" value="" data-buttonName="btn-pascual" data-buttonText=" Buscar...">
																											<br>
																											<button  class="btn btn-pascual" type="submit">Subir Imagen</button>
																										<?php } ?>

																										<br>
																										<br>
																									</form>



																									<!-- ************************************************************************************** -->



																									<div class="footer">
																										<center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>
																										</div>

																									</div> <!-- /container -->


																									<!-- Bootstrap core JavaScript
																									================================================== -->

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
																										$(".table1").tablesorter({
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

																									function borrarcomentario(id,tabla) {
																										var contrato = "<?php echo $id_contrato; ?>";
																										var acta = "<?php echo $id_acta; ?>";
																										var interventor = "<?php echo $id_interventor; ?>";
																										var usuario = "<?php echo $user; ?>";
																										var revisa = {};
																										revisa.url = "lib/newfunctions.php";
																										revisa.method = "POST";
																										revisa.data = ({id:id, tabla:tabla, accion:"borrar", contrato:contrato, acta:acta, interventor:interventor, usuario:usuario});
																										$.ajax(revisa)
																										.done(function(data){
																											location.reload();
																										})
																										.fail(function(data) {
																											alert("Error al Borrar Observación.");
																										});
																									}

																									function grabaFolios() {
																										var acta = "<?php echo $id_acta; ?>";
																										var interventor = "<?php echo $id_interventor; ?>";
																										var total = document.getElementById('folios').value;
																										var tv = document.getElementById('tv').value;
																										var sd = document.getElementById('sd').value;
																										var sr = document.getElementById('sr').value;
																										var sv = document.getElementById('sv').value;
																										var fd = document.getElementById('fd').value;
																										var fr = document.getElementById('fr').value;
																										var fv = document.getElementById('fv').value;
																										var cd = document.getElementById('cd').value;
																										var cr = document.getElementById('cr').value;
																										var cv = document.getElementById('cv').value;
																										var vd = document.getElementById('vd').value;
																										var vr = document.getElementById('vr').value;
																										var vv = document.getElementById('vv').value;
																										var revisa = {};
																										revisa.url = "lib/newfunctions.php";
																										revisa.method = "POST";
																										revisa.data = ({ accion:"folios", acta:acta, interventor:interventor, total:total, tv:tv, sd:sd, sr:sr, sv:sv, fd:fd, fr:fr, fv:fv, cd:cd, cr:cr, cv:cv, vd:vd, vr:vr, vv:vv });
																										$.ajax(revisa)
																										.done(function(data){
																											location.reload();
																											//alert("Grabadó Folios con éxito.");
																										})
																										.fail(function(data) {
																											alert("Error al grabar Folios.");
																										});
																									}
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
