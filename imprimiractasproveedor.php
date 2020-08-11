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
	$folder = "/var/www/2016/html/uploadstella/".$ruta_componente."/";

	//Ruta Lectura
	$folder_lectura="../uploadstella/".$ruta_componente."/";





	//Querys para traer todos los campos guardados del acta_proveedor
	$queryacta= mysql_query(("
	SELECT
	acta_proveedor.id_acta,
	acta_proveedor.fecha_evaluacion,
	acta_proveedor.hora_inicio,
	acta_proveedor.hora_fin,
	acta_proveedor.id_componente,
	componente.nombre_componente,
	proveedor.nombre_proveedor,
	modalidad.nombre_modalidad,
	acta_proveedor.numero_visita,
	acta_proveedor.direccion_proveedor,
	acta_proveedor.numero_telefono,
	acta_proveedor.nombre_asistentes,
	acta_proveedor.nombre_prestadores,
	acta_proveedor.id_interventor,
	acta_proveedor.estado
	FROM
	acta_proveedor,componente,proveedor,modalidad
	WHERE
	acta_proveedor.id_componente=componente.id_componente and
	acta_proveedor.id_proveedor=proveedor.id_proveedor and
	acta_proveedor.id_modalidad=modalidad.id_modalidad and
	id_acta='$id_acta'
	"),$conexion);

	while($row=mysql_fetch_assoc($queryacta)){
		$id_acta=$row['id_acta'];
		$fecha_evaluacion=$row['fecha_evaluacion'];
		$hora_inicio=$row['hora_inicio'];
		$hora_fin=$row['hora_fin'];
		$id_componente=$row['id_componente'];
		$nombre_componente=$row['nombre_componente'];
		$nombre_proveedor=$row['nombre_proveedor'];
		$nombre_modalidad=$row['nombre_modalidad'];
		$numero_visita=$row['numero_visita'];
		$direccion_proveedor=$row['direccion_proveedor'];
		$numero_telefono=$row['numero_telefono'];
		$nombre_asistentes=$row['nombre_asistentes'];
		$nombre_prestadores=$row['nombre_prestadores'];
		$id_interventor=$row['id_interventor'];
		$estado=$row['estado'];
	}

	//Query para traer todas las preguntas de la evaluacion correspondientes al acta
	$querypreguntas= mysql_query(("
	SELECT
	evaluacion_proveedor.id_evaluacion,
	evaluacion_proveedor.id_pregunta,
	tema.nombre_tema,
	subtema.nombre_subtema,
	pregunta.descripcion_pregunta,
	evaluacion_proveedor.valor_calificacion,
	evaluacion_proveedor.valor_calificacion_final
	FROM
	evaluacion_proveedor,tema,subtema,pregunta
	WHERE
	evaluacion_proveedor.id_tema=tema.id_tema and
	evaluacion_proveedor.id_subtema=subtema.id_subtema and
	evaluacion_proveedor.id_pregunta=pregunta.id_pregunta and
	id_acta='$id_acta'
	"),$conexion);

	//Query para traer todas las observaciones de la evaluacion correspondientes al acta
	$queryhallazgos= mysql_query(("
	SELECT
	subsanacion_proveedor.id_subsanacion,
	subsanacion_proveedor.id_acta,
	subsanacion_proveedor.id_pregunta,
	subsanacion_proveedor.descripcion_pregunta,
	subsanacion_proveedor.descripcion_observacion,
	subsanacion_proveedor.descripcion_accion_correctiva,
	subsanacion_proveedor.fecha_subsanacion,
	subsanacion_proveedor.fecha_subsanacion_final,
	subsanacion_proveedor.fecha_solicitud_aclaracion,
	subsanacion_proveedor.id_radicado_osa,
	subsanacion_proveedor.fecha_requerimiento,
	subsanacion_proveedor.id_radicado_orq,
	subsanacion_proveedor.fecha_envio_evidencia,
	subsanacion_proveedor.historico,
	subsanacion_proveedor.etapa,
	subsanacion_proveedor.estado,
	evaluacion_proveedor.id_evaluacion,
	evaluacion_proveedor.valor_calificacion,
	evaluacion_proveedor.valor_calificacion_final


	FROM
	subsanacion_proveedor,evaluacion_proveedor
	WHERE
	subsanacion_proveedor.id_acta=evaluacion_proveedor.id_acta and
	subsanacion_proveedor.id_pregunta=evaluacion_proveedor.id_pregunta and
	subsanacion_proveedor.id_acta='$id_acta' and
	subsanacion_proveedor.estado='1'
	"),$conexion);



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

		<title>Editar actas de proveedores creadas</title>



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
		<!-- <a class="btn btn-success btn-lg" role="button">Imprimir Acta</a> -->

		<?php
		if($estado==1){
			?>
			<a class="btn btn-pascual btn-lg" role="button" href='deletes.php?eliminar=<?php echo $id_acta; ?>&caso=7'>Bloquear Acta</a>
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
					<table class="table table-bordered table-hover">


						<tr>
							<th colspan="2"><div style="text-align:center">ACTA DE VISITA A LOS PROVEEDORES </div></th>
						</tr>

						<tbody>
							<tr>
								<td class="col-md-6">Acta: <strong><?php echo $id_acta; ?></strong></td>
								<td class="col-md-6">Visita: <strong><?php echo $numero_visita; ?></td>
								</tr>

								<tr>
									<td class="col-md-6">Componente: <strong><?php echo $nombre_componente; ?></td>
										<td class="col-md-6">Proveedor: <strong><?php echo $nombre_proveedor; ?></td>
										</tr>

										<tr>
											<td class="col-md-6">Teléfono: <strong><?php echo $numero_telefono; ?></td>
												<td class="col-md-6">Dirección: <strong><?php echo $direccion_proveedor; ?></td>
												</tr>

												<tr>
													<td class="col-md-6">Fecha: <strong><?php echo $fecha_evaluacion; ?></td>
														<td class="col-md-6">Interventor: <strong><?php echo $id_interventor; ?></td>
														</tr>

														<tr>
															<td class="col-md-6">Hora Inicio: <strong><?php echo $hora_inicio; ?></td>
																<td class="col-md-6">Hora Fin: <strong><?php echo $hora_fin; ?></td>

																</tr>

																<tr>
																	<td class="col-md-6">Asistentes: <strong><?php echo $nombre_asistentes; ?></td>
																		<td class="col-md-6">Presradores que atiende el proveedor: <strong><?php echo $nombre_prestadores; ?></td>
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
															5= No Aplica.

														</div>




														<table class="table table-bordered table-hover" id='temas_creados'>
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
																			<th>Calfificación</th>
																		</tr>
																		<?php $nombre_subtema = $row['nombre_subtema']; } ?>
																		<tr>
																			<td><?php echo $row['id_pregunta'];  ?></td>
																			<td><?php echo $row['descripcion_pregunta'];  ?></td>
																			<?php if($row['valor_calificacion']==1){
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




																		<form data-parsley-validate id="3" name="update" METHOD="post" action="inserts.php">
																			<input type="hidden" name="caso" value="19">
																			<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">




																			<?php if(mysql_num_rows($queryhallazgos) > 0){ ?>
																				<table align="center" class="table table-bordered " id='table' style="width: 95%">

																					<thead>

																						<tr>
																							<th>#</th>
																							<th>Descripcion_Pregunta</th>
																							<th>Calificación</th>
																							<th>Hallazgos_Encontrados</th>
																							<!-- <th>Acciones_Correctivas</th>
																							<th class="success">Plazo Acciones .Correctivas.</th>
																							<th class="success">Prorroga Acciones .Correctivas.</th>
																							<th class="warning">Plazo Solicitud .Aclaración.</th>
																							<th class="warning">Radicado Solicitud .Aclaración.</th>
																							<th class="danger">Plazo Requerimiento</th>
																							<th class="danger">Radicado Requerimiento</th>
																							<th class="info">Envío .Evidencias.</th>
																							<th class="info">Calf. Fin</th>
																							<th class="info">Etapa del seguimiento</th> -->
																						</tr>

																					</thead>

																					<tbody>
																						<?php
																						while($row = mysql_fetch_assoc($queryhallazgos)){
																							?>

																							<tr>
																								<td>
																									<input type="hidden" name="id_subsanacion[]" value="<?php echo $row['id_subsanacion'] ?>">
																									<input type="hidden" name="id_pregunta[]" value="<?php echo $row['id_pregunta'] ?>"><?php echo $row['id_pregunta'];  ?>
																									<!--
																									<input type="hidden" name="id_evaluacion[]" value="<?php echo $row['id_evaluacion'] ?>">
																									<input type="hidden" name="etapa_anterior[]" value="<?php echo $row['historico'] ?>">
																								-->
																							</td>

																							<td><?php echo $row['descripcion_pregunta'];  ?></td>

																							<td><?php echo $row['valor_calificacion'];  ?></td>

																							<td><textarea name="descripcion_observacion[]" rows="4" style="width:500px"><?php echo $row['descripcion_observacion'];  ?></textarea></td>

																							<!--
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

											<td><center><textarea data-parsley-min="1"  data-parsley-max="4" data-parsley-type="number" data-parsley-required name="valor_calificacion_final[]" rows="1" style="width:50px" placeholder="<?php echo $row['valor_calificacion_final'];  ?>" autofocus><?php echo $row['valor_calificacion_final'];  ?></textarea></center></td>


											<td>
											<select class="form-control" id="select1" name="etapa[]">
											<option value="<?php echo $row['etapa'];  ?>" selected><?php echo $row['etapa'];  ?></option>
											<option value="AC" >AC</option>
											<option value="SA" >SA</option>
											<option value="REQ">REQ</option>
											<option value="OK" >OK</option>
										</select>
									</td>
								-->




							</tr>
						<?php } ?>
					</tbody>
				</table>



				<?php
				if($estado==1){
					?>
					<center><button  class="btn btn-pascual" type="submit">Ingresar Hallazgos</button></center>
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
					En el momento de la realización de la visita, no fueron encontrados hallazgos por subsanar!
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
			<div class="bs-docs-section"><h3 id="tables-example">Observaciones Generales de la Interventoria</h3></div>
			<div class="footer"></div>


			<?php if(mysql_num_rows($queryobsinterventor) > 0){ ?>
				<table class="table table-bordered table-hover" id='tcreados'>
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
								<td><?php echo $row['id_interventor'];  ?></td>
							</tr>
						<?php } ?>
					</tbody>

				</table>

			<?php } ?>

			<form data-parsley-validate id="1"  role="form1" name="obsinterv" METHOD="post" action="inserts.php">
				<input type="hidden" name="caso" value="20">
				<input type="hidden" name="id_interventor" value="<?php echo $user; ?>">
				<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
				<input type="hidden" name="fecha_observacion_evaluador" value="<?php echo date("Y-m-d H:i:s");  ?>">

				<?php if($estado==1){ ?>
					<td class="active"><textarea data-parsley-required name="descripcion_observacion_evaluador" rows="4" style="width:100%" placeholder="Ingrese una nueva Observación" ></textarea></td>

					<br>
					<button  class="btn btn-pascual" type="submit">Ingresar Observación</button>
				<?php } ?>

				<br>
				<br>

			</form>


			<!-- ************************************************************************************** -->

			<br>
			<div class="bs-docs-section"><h3 id="tables-example">Observaciones Generales del Proveedor</h3>	</div>
			<div class="footer"></div>

			<?php if(mysql_num_rows($queryobsusuario) > 0){ ?>
				<table class="table table-bordered table-hover" id='tcreados'>
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Observación Proveedor</th>
							<th>Creada por</th>
						</tr>
					</thead>


					<tbody>
						<?php
						while($row = mysql_fetch_assoc($queryobsusuario)){ ?>
							<tr>
								<td><?php echo $row['fecha_observacion_usuario'];  ?></td>
								<td><?php echo $row['descripcion_observacion_usuario'];  ?></td>
								<td><?php echo $row['id_interventor'];  ?></td>
							</tr>
						<?php } ?>
					</tbody>

				</table>

			<?php } ?>

			<form data-parsley-validate id="2"  role="form2" name="obsusuario" METHOD="post" action="inserts.php">
				<input type="hidden" name="caso" value="21">
				<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
				<input type="hidden" name="id_interventor" value="<?php echo $user; ?>">
				<input type="hidden" name="fecha_observacion_usuario" value="<?php echo date("Y-m-d H:i:s");  ?>">

				<?php if($estado==1){ ?>
					<td class="active"><textarea data-parsley-required name="descripcion_observacion_usuario" rows="4" style="width:100%" placeholder="Ingrese una nueva Observación" ></textarea></td>
					<br>
					<button  class="btn btn-pascual" type="submit">Ingresar Observación</button>
				<?php } ?>

				<br>
				<br>
			</form>

			<!-- ************************************************************************************** -->
			<!--
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
<input data-parsley-required type="file" class="filestyle" name="archivo" id="archivo" value="" data-buttonName="btn-primary" data-buttonText=" Buscar...">
<br>
<button  class="btn btn-danger" type="submit">Subir Imagen</button>
<?php } ?>

<br>
<br>
</form>

-->

<!-- ************************************************************************************** -->



<div class="footer">
	<center> <p> &copy; 2020 Sistema de Información de la interventoría Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>
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
