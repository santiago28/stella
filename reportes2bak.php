<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";

	//Variables Globales
	$id_grupo=$_SESSION["grupo"];
	$nombre=$_SESSION["nombre_usuario"];



	//Consulta registros de la tabla acta
	$actas_creadas= mysql_query(("
	SELECT
	acta.id_contrato,
	modalidad.abr_modalidad,
	prestador.nombre_prestador
	FROM
	acta,modalidad,prestador
	where
	acta.id_modalidad=modalidad.id_modalidad and
	acta.id_prestador=prestador.id_prestador
	group by id_contrato
	order by id_contrato

	"),$conexion);

	while($row=mysql_fetch_assoc($actas_creadas)){
		$id_contrato[]=$row['id_contrato'];
		$abr_modalidad[]=$row['abr_modalidad'];
		$nombre_prestador[]=$row['nombre_prestador'];
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

		<title>Valoraciones</title>

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
<div id="MenuPrincipal">
	<i class="material-icons icono-menu">&#xE5D2;</i>
	<div class="sobre-menu-principal"></div>
	<div class="menu-principal">
		<div class="header-menu-principal">
			<div>
				<img id="avatarprincipal" src="imagesprofile/<?php if ($fotoperfil!=null) { echo $fotoperfil; }else{echo 'icon-user.png'; } ?>" />
				<br />
				<b>Nombre:&ensp;</b><span id="username"><?php  echo $nombre ?></span>
			</div>
		</div>
		<div class="body-menu-principal">
			<?php
			if ($id_grupo==1 || $id_grupo==3 ) {

				// Grupo Administrador ?>
				<div class="item-menu">
					<i class="material-icons">&#xE558;</i>
					<span><a href="configtemas.php?msg=0">Crear Componentes Técnico</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE8F9;</i>
					<span><a href="configsubtemas.php?msg=0">Crear Estándares</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="configpreguntas.php?msg=0">Crear Preguntas por Modalidad</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE854;</i>
					<span><a href="configobservaciones.php?componente_selected=0&id_tema=0&msg=0">Editar Hallazgos y Acciones Correctivas</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE854;</i>
					<span><a href="reservaradicado.php?msg=0">Reservar números de Acta</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="consultaevaluaciones.php?msg=0">Interventorías</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="descuentoVer.php?msg=0">Descuentos Nutrici&oacute;n</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="descuentosValoracion.php">Descuentos Valoracion</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="consultaevaluacionesproveedores.php?msg=0">Visitas a Proveedores</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE558;</i>
					<span><a href="semaforohallazgos.php?componente_selected=0">Debido Proceso</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xEB3F;</i>
					<span><a href="visitasfallidas.php">Visitas No Valoradas</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE8ED;</i>
					<span><a href="configsemaforo.php">Informe Gestión Mensual x Contrato</a></span>
				</div>
				<?php if ($id_grupo==1) {
					echo '<div class="item-menu">
					<i class="material-icons">&#xE8ED;</i>
					<span><a href="informesFinal.php?msg=0">Informes Finales</a></span>
					</div>';
				}
			}
			elseif ($id_grupo==2) {
				//Grupo Members ?>
				<div class="item-menu">
					<i class="material-icons">&#xE854;</i>
					<span><a href="reservaradicado.php?msg=0">Reservar números de Acta</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="consultaevaluaciones.php?msg=0">Interventorías</a></span>
				</div>
				<?php if($id_componente==7){ ?>
					<div class="item-menu">
						<i class="material-icons">&#xE87C;</i>
						<span><a href="consultaevaluacionesproveedores.php?msg=0">Visitas a Proveedores</a></span>
					</div>
					<div class="item-menu">
						<i class="material-icons">&#xE87C;</i>
						<span><a href="descuentoVer.php?msg=0">Descuentos Nutrici&oacute;n</a></span>
					</div>
					<?php } ?>
					<div class="item-menu">
						<i class="material-icons">&#xE558;</i>
						<span><a href="semaforohallazgos.php?componente_selected=0">Debido Proceso</a></span>
					</div>
					<div class="item-menu">
						<i class="material-icons">&#xEB3F;</i>
						<span><a href="visitasfallidas.php">Visitas No Valoradas</a></span>
					</div>
					<div class="item-menu">
						<i class="material-icons">&#xE8ED;</i>
						<span><a href="configsemaforo.php">Informe Gestión Mensual x Contrato</a></span>
					</div>
					<?php
				}
				else{
					//Grupo Secretaria Educacion ?>
					<h5>Este portal le permitirá consultar información relevante de la prestación del servicio del programa Buen Comienzo. De clic en la opción para continuar</h5>
					<div class="item-menu not-admin not-carga">
						<i class="material-icons">&#xE87C;</i>
						<span><a href="consultaevaluaciones.php?msg=0">Interventorías</a></span>
					</div>
					<?php
				} //<p><a class="btn btn-lg btn-block btn-danger" href="reportes3.php?msg=0&componente_selected=0&id_tema=0&id_mes=0" role="button">Semáforo Consolidado</a></p>
				?>
			</div>
			<div class="footer-menu-principal">
				<div><i class="material-icons">&#xE88E;</i></div>
				<div><a class="material-icons" href="logout.php">&#xE8AC;</a></div>
				<div><i class="material-icons">&#xE869;</i></div>
			</div>
		</div>
	</div>
	<div class="container">

		<?php
		//SECCION CONTRATOS EVALUADOS

		$numrows= mysql_num_rows($actas_creadas);
		if ($numrows == 0){
			?>
			<div align="center" class="page-header">
				<div class="alert alert-warning" role="alert">
					<strong>¡Advertencia!</strong> No hay registros que coincidan con los filtros seleccionados.
				</div>
			</div>
			<?php //Cierro else interno
		} //end IF
		else{
			?>

			<div class="alert alert-default" role="alert">
				<center> <p> <img src="images/pascualbravo.jpg" width="200" height="60"> </p>
					<h2><center>Valoración Consolidada de Cumplimiento por Contrato</strong></h2>
						<h4><center>Interventoría Buen Comienzo</center></h4>

						<div class="footer">
						</div>

					</div>



					<div class="alert alert-warning" role="alert">
						<center>Tenga en cuenta que:</center>
						<center>El "%Calidad" hace referencia al porcentaje inicial de la visita, y el "%Cumplimniento" hace referencia al porcentaje final después de subsanar los hallazgos encontrados en la visita</center>
					</div>

				</div> <!-- /container -->



				<table align="center" class="table table-bordered table-hover" id='table' style="width: 95%">
					<thead>
						<tr>
							<th rowspan="2" class="info">Contrato</th>
							<th rowspan="2" class="info">Mod.</th>
							<th rowspan="2" class="info">Prestador</th>
							<th colspan="1" class="info">Gestión Institucional</th>
							<th colspan="1" class="info">Gestión Estratégica</th>
							<th colspan="1" class="info">Cobertura</th>
							<th colspan="1" class="info">Salud</th>
							<th colspan="1" class="info">Seguridad</th>
							<th colspan="1" class="info">Verificación de Dotación</th>
							<th colspan="1" class="info">Infraestructura</th>
							<th colspan="1" class="info">Nutrición</th>
							<th colspan="1" class="info">Procesos Pedagógicos</th>
							<th colspan="1" class="info">Valoración del Desarrollo</th>
							<th colspan="1" class="info">Participación</th>
							<th colspan="1" class="info">Protección</th>
							<th colspan="1" class="info">Interacción con Familias</th>
							<th rowspan="1" class="success">%Prom. Cumplimiento</th>

						</tr>
						<tr>


						</tr>
					</thead>
					<tbody>

						<?php for ($i=0; $i < count($id_contrato);$i++)
						{
							?>

							<tr>

								<td class="active"><a  href='reportes.php?id_contrato=<?php echo $id_contrato[$i]; ?>&fcorte=<?php echo date("Y-m-d"); ?>'><?php echo $id_contrato[$i]; ?></a></td>
								<td class="active"><?php echo $abr_modalidad[$i]; ?></td>
								<td class="active"><?php echo $nombre_prestador[$i]; ?></td>


								<?php
								//Consulta GI
								$gi= mysql_query(("
								SELECT
								avg(porcentaje_final) promedio_componente

								FROM
								(
									SELECT
									sum(porc_final)/sum(porc_referencia) porcentaje_final
									FROM
									evaluacion
									WHERE
									id_contrato='$id_contrato[$i]' and
									id_componente='4' and
									id_tema='401' and
									estado='1'
									group by id_acta
									) as operacion
									"),$conexion);

									while($row=mysql_fetch_assoc($gi)){

										if($row['promedio_componente']!=""){
											?>
											<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
											<?php
										}

										else{
											?>
											<td class="active"></td>
											<?php
										} //end else
									}//end While
									?>


									<?php
									//Consulta GE
									$ge= mysql_query(("
									SELECT
									avg(porcentaje_final) promedio_componente

									FROM
									(
										SELECT
										sum(porc_final)/sum(porc_referencia) porcentaje_final
										FROM
										evaluacion
										WHERE
										id_contrato='$id_contrato[$i]' and
										id_componente='4' and
										id_tema='402' and
										estado='1'
										group by id_acta
										) as operacion
										"),$conexion);

										while($row=mysql_fetch_assoc($ge)){

											if($row['promedio_componente']!=""){
												?>
												<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
												<?php
											}

											else{
												?>
												<td class="active"></td>
												<?php
											} //end else
										}//end While
										?>

										<?php
										//Consulta CO
										$co= mysql_query(("
										SELECT
										avg(porcentaje_final) promedio_componente
										FROM
										(SELECT
										sum(porc_final)/sum(porc_referencia) porcentaje_final
										FROM
										acta
										WHERE
										id_contrato='$id_contrato[$i]' and
										id_componente='3' and
										estado='0'
										group by id_acta
										) as operacion
										"),$conexion);

										while($row=mysql_fetch_assoc($co)){

											if($row['promedio_componente']!=""){
												?>
												<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
												<?php
											}

											else{
												?>
												<td class="active"></td>
												<?php
											} //end else
										}//end While
										?>

										<?php
										//Consulta SA
										$sa= mysql_query(("
										SELECT
										avg(porcentaje_final) promedio_componente

										FROM
										(
											SELECT
											sum(porc_final)/sum(porc_referencia) porcentaje_final
											FROM
											evaluacion
											WHERE
											id_contrato='$id_contrato[$i]' and
											id_componente='1' and
											id_tema='101' and
											estado='1'
											group by id_acta
											) as operacion
											"),$conexion);

											while($row=mysql_fetch_assoc($sa)){

												if($row['promedio_componente']!=""){
													?>
													<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
													<?php
												}

												else{
													?>
													<td class="active"></td>
													<?php
												} //end else
											}//end While
											?>

											<?php
											//Consulta SE
											$se= mysql_query(("
											SELECT
											avg(porcentaje_final) promedio_componente

											FROM
											(
												SELECT
												sum(porc_final)/sum(porc_referencia) porcentaje_final
												FROM
												evaluacion
												WHERE
												id_contrato='$id_contrato[$i]' and
												id_componente='1' and
												id_tema='102' and
												estado='1'
												group by id_acta
												) as operacion
												"),$conexion);

												while($row=mysql_fetch_assoc($se)){

													if($row['promedio_componente']!=""){
														?>
														<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
														<?php
													}

													else{
														?>
														<td class="active"></td>
														<?php
													} //end else
												}//end While
												?>

												<?php
												//Consulta VD
												$vd= mysql_query(("
												SELECT
												avg(porcentaje_final) promedio_componente

												FROM
												(
													SELECT
													sum(porc_final)/sum(porc_referencia) porcentaje_final
													FROM
													evaluacion
													WHERE
													id_contrato='$id_contrato[$i]' and
													id_componente='2' and
													id_tema='201' and
													estado='1'
													group by id_acta
													) as operacion
													"),$conexion);

													while($row=mysql_fetch_assoc($vd)){

														if($row['promedio_componente']!=""){
															?>
															<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
															<?php
														}

														else{
															?>
															<td class="active"></td>
															<?php
														} //end else
													}//end While
													?>

													<?php
													//Consulta IN
													$in= mysql_query(("
													SELECT
													avg(porcentaje_final) promedio_componente

													FROM
													(
														SELECT
														sum(porc_final)/sum(porc_referencia) porcentaje_final
														FROM
														evaluacion
														WHERE
														id_contrato='$id_contrato[$i]' and
														id_componente='5' and
														id_tema='501' and
														estado='1'
														group by id_acta
														) as operacion
														"),$conexion);

														while($row=mysql_fetch_assoc($in)){

															if($row['promedio_componente']!=""){
																?>
																<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
																<?php
															}

															else{
																?>
																<td class="active"></td>
																<?php
															} //end else
														}//end While
														?>

														<?php
														//Consulta NT
														$nt= mysql_query(("
														SELECT
														avg(porcentaje_final) promedio_componente

														FROM
														(
															SELECT
															sum(porc_final)/sum(porc_referencia) porcentaje_final
															FROM
															evaluacion
															WHERE
															id_contrato='$id_contrato[$i]' and
															id_componente='7' and
															id_tema='701' and
															estado='1'
															group by id_acta
															) as operacion
															"),$conexion);

															while($row=mysql_fetch_assoc($nt)){

																if($row['promedio_componente']!=""){
																	?>
																	<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
																	<?php
																}

																else{
																	?>
																	<td class="active"></td>
																	<?php
																} //end else
															}//end While
															?>


															<?php
															//Consulta PP
															$pp= mysql_query(("
															SELECT
															avg(porcentaje_final) promedio_componente

															FROM
															(
																SELECT
																sum(porc_final)/sum(porc_referencia) porcentaje_final
																FROM
																evaluacion
																WHERE
																id_contrato='$id_contrato[$i]' and
																id_componente='8' and
																id_tema='801' and
																estado='1'
																group by id_acta
																) as operacion
																"),$conexion);

																while($row=mysql_fetch_assoc($pp)){

																	if($row['promedio_componente']!=""){
																		?>
																		<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
																		<?php
																	}

																	else{
																		?>
																		<td class="active"></td>
																		<?php
																	} //end else
																}//end While
																?>

																<?php
																//Consulta VA
																$va= mysql_query(("
																SELECT
																avg(porcentaje_final) promedio_componente

																FROM
																(
																	SELECT
																	sum(porc_final)/sum(porc_referencia) porcentaje_final
																	FROM
																	evaluacion
																	WHERE
																	id_contrato='$id_contrato[$i]' and
																	id_componente='8' and
																	id_tema='802' and
																	estado='1'
																	group by id_acta
																	) as operacion
																	"),$conexion);

																	while($row=mysql_fetch_assoc($va)){

																		if($row['promedio_componente']!=""){
																			?>
																			<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
																			<?php
																		}

																		else{
																			?>
																			<td class="active"></td>
																			<?php
																		} //end else
																	}//end While
																	?>

																	<?php
																	//Consulta PA
																	$pa= mysql_query(("
																	SELECT
																	avg(porcentaje_final) promedio_componente

																	FROM
																	(
																		SELECT
																		sum(porc_final)/sum(porc_referencia) porcentaje_final
																		FROM
																		evaluacion
																		WHERE
																		id_contrato='$id_contrato[$i]' and
																		id_componente='9' and
																		id_tema='901' and
																		estado='1'
																		group by id_acta
																		) as operacion
																		"),$conexion);

																		while($row=mysql_fetch_assoc($pa)){

																			if($row['promedio_componente']!=""){
																				?>
																				<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
																				<?php
																			}

																			else{
																				?>
																				<td class="active"></td>
																				<?php
																			} //end else
																		}//end While
																		?>

																		<?php
																		//Consulta PR
																		$pr= mysql_query(("
																		SELECT
																		avg(porcentaje_final) promedio_componente

																		FROM
																		(
																			SELECT
																			sum(porc_final)/sum(porc_referencia) porcentaje_final
																			FROM
																			evaluacion
																			WHERE
																			id_contrato='$id_contrato[$i]' and
																			id_componente='9' and
																			id_tema='902' and
																			estado='1'
																			group by id_acta
																			) as operacion
																			"),$conexion);

																			while($row=mysql_fetch_assoc($pr)){

																				if($row['promedio_componente']!=""){
																					?>
																					<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
																					<?php
																				}

																				else{
																					?>
																					<td class="active"></td>
																					<?php
																				} //end else
																			}//end While
																			?>

																			<?php
																			//Consulta IF
																			$if= mysql_query(("
																			SELECT
																			avg(porcentaje_final) promedio_componente

																			FROM
																			(
																				SELECT
																				sum(porc_final)/sum(porc_referencia) porcentaje_final
																				FROM
																				evaluacion
																				WHERE
																				id_contrato='$id_contrato[$i]' and
																				id_componente='9' and
																				id_tema='903' and
																				estado='1'
																				group by id_acta
																				) as operacion
																				"),$conexion);

																				while($row=mysql_fetch_assoc($if)){

																					if($row['promedio_componente']!=""){
																						?>
																						<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
																						<?php
																					}

																					else{
																						?>
																						<td class="active"></td>
																						<?php
																					} //end else
																				}//end While
																				?>





																				<?php
																				/*
																				//Consulta GESTION INSTITUCIONAL (ejemplo consulta a la tabla acta
																				$gi= mysql_query(("
																				SELECT
																				avg(porc_final) promedio_componente
																				FROM acta
																				WHERE
																				id_contrato='$id_contrato[$i]' and
																				id_componente='4'
																				"),$conexion);

																				while($row=mysql_fetch_assoc($gi)){

																				if($row['promedio_componente']!=""){
																				?>
																				<td class="active" align="center"><?php echo round($row['promedio_componente'],4)*100; ?>%</td>
																				<?php
																			}

																			else{
																			?>
																			<td class="active"></td>
																			<?php
																		} //end else
																	}//end While
																	*/
																	?>



																	<?php
																	//Consulta TOTAL
																	$total= mysql_query(("
																	SELECT
																	sum(operacion.porc_componente_x_modalidad) suma_porc_comp_x_mod,
																	sum(operacion.porc_componente_x_inicial) suma_porc_comp_x_inicial,
																	sum(operacion.porc_componente_x_final) suma_porc_comp_x_final,
																	round(sum(operacion.porc_componente_x_inicial)/sum(operacion.porc_componente_x_modalidad),4) suma_total_inicial,
																	round(sum(operacion.porc_componente_x_final)/sum(operacion.porc_componente_x_modalidad),4) suma_total_final
																	FROM
																	(
																		SELECT
																		porc_inicial,
																		porc_final,
																		porc_componente_x_modalidad,
																		porc_inicial*porc_componente_x_modalidad porc_componente_x_inicial,
																		porc_final*porc_componente_x_modalidad porc_componente_x_final
																		FROM
																		acta
																		WHERE
																		id_contrato='$id_contrato[$i]'
																		) as operacion
																		"),$conexion);

																		while($row=mysql_fetch_assoc($total)){

																			if($row['suma_total_final']!=""){
																				?>
																				<td class="active" align="center"><?php echo $row['suma_total_final']*100; ?>%</td>
																				<?php
																			}

																			else{
																				?>
																				<td class="active"></td>
																				<?php
																			} //end else
																		}//end While
																		?>



																	</tr>
																	<?php
																} //End For
																?>
															</tbody>
														</table>



														<?php

													}//Cierro else

													include "cerrarconexion.php"; ?>


													<div class="container">

														<div class="footer">
															<center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

															</div>

														</div> <!-- /container -->


														<!-- Bootstrap core JavaScript-->
														<script>
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
