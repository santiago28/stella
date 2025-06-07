<?php

session_start();
if (true) //if ($_SESSION['login'])
{

	include "conexion.php";

	//Variables Globales
	$id_grupo=$_SESSION["grupo"];
	$nombre=$_SESSION["nombre_usuario"];
	$fotoperfil = $_SESSION["fotoperfil"];
	//Variables recibidas via GET para la consulta
	$id_contrato=$_GET["id_contrato"];


	//Seleccionar el id_contrato
	$lista_desplegable= mysql_query(("
	SELECT
	contrato_x_sede.id_contrato,
	modalidad.abr_modalidad,
	prestador.nombre_prestador

	FROM
	contrato_x_sede,modalidad,prestador
	where
	contrato_x_sede.id_modalidad=modalidad.id_modalidad and
	contrato_x_sede.id_prestador=prestador.id_prestador /*and
	contrato_x_sede.estado='1'*/
	group by id_contrato
	order by id_contrato
	"),$conexion);


	if($id_contrato!=0){

		//Variables recibidas via GET para la consulta
		$fcorte=$_GET["fcorte"];

		//Consulta registros de la tabla acta
		$actas_creadas= mysql_query(("
		SELECT
		id_sede,
		nombre_sede,
		numero_visita
		FROM
		acta
		where
		id_contrato='$id_contrato' and
		fecha_evaluacion<='$fcorte'
		group by id_sede,numero_visita
		order by id_sede,numero_visita,nombre_sede

		"),$conexion);

		while($row=mysql_fetch_assoc($actas_creadas)){
			$id_sede[]=$row['id_sede'];
			$nombre_sede[]=$row['nombre_sede'];
			$numero_visita[]=$row['numero_visita'];
		}


		//Consulta registros de la tabla acta
		$info_contrato= mysql_query(("
		SELECT
		contrato_x_sede.id_contrato,
		contrato_x_sede.id_modalidad,
		modalidad.nombre_modalidad,
		prestador.nombre_prestador

		FROM
		contrato_x_sede,modalidad,prestador
		where
		contrato_x_sede.id_modalidad=modalidad.id_modalidad and
		contrato_x_sede.id_prestador=prestador.id_prestador and
		contrato_x_sede.id_contrato='$id_contrato' /*and
		contrato_x_sede.estado='1'*/
		"),$conexion);

		$row=mysql_fetch_assoc($info_contrato);
		$id_modalidad=$row['id_modalidad'];
		$nombre_modalidad=$row['nombre_modalidad'];
		$nombre_prestador=$row['nombre_prestador'];





		//Consulta Porcentaje de los componentes
		$query_porc_componentes= mysql_query(("
		SELECT
		id_tema,
		porc_componente_x_modalidad
		FROM
		componente_x_modalidad
		WHERE
		id_modalidad='$id_modalidad' and
		estado='1'
		"),$conexion);

		while($row=mysql_fetch_assoc($query_porc_componentes)){
			$id_tema[]=$row['id_tema'];
			$porc_componente_x_modalidad[]=$row['porc_componente_x_modalidad'];
		}






		//Consulta promedio cumplimiento
		// $promedio_cumplimiento= mysql_query(("
		// SELECT
		// sum(operacion.porc_componente_x_modalidad) suma_porc_comp_x_mod,
		// sum(operacion.porc_componente_x_final) suma_porc_comp_x_final,
		// round(sum(operacion.porc_componente_x_final)/sum(operacion.porc_componente_x_modalidad),4) suma_total
		// FROM
		// (
		// 	SELECT
		// 	porc_final,
		// 	porc_componente_x_modalidad,
		// 	porc_final*porc_componente_x_modalidad porc_componente_x_final
		// 	FROM
		// 	acta
		// 	WHERE
		// 	id_contrato='$id_contrato' and
		// 	fecha_evaluacion<='$fcorte'
		// 	) as operacion
		// 	"),$conexion);
		// 	$row=mysql_fetch_assoc($promedio_cumplimiento);
		// 	$promedio_final=$row['suma_total']*100;

		$timestamp = new DateTime();
		$tabla_temp = "m".$timestamp->getTimestamp();

		$create_temp = mysql_query(("CREATE TEMPORARY TABLE $tabla_temp (id_contrato bigint, nombre_prestador varchar(100), nombre_modalidad varchar(100), id_componente int, nombre_componente varchar(100), promedio_componente_inicial float, promedio_componente_final float, porc_componente_x_modalidad float)  CHARACTER SET utf8 COLLATE utf8_bin"),$conexion);
		$porcentajes = mysql_query(("
		INSERT INTO  $tabla_temp (id_contrato, nombre_prestador, nombre_modalidad, id_componente, nombre_componente, promedio_componente_inicial, promedio_componente_final, porc_componente_x_modalidad)
		SELECT
		operacion1.id_contrato,
		operacion1.nombre_prestador,
		operacion1.nombre_modalidad,
		operacion1.id_componente,
		operacion1.nombre_componente,
		avg(operacion1.promedio_componente_inicial/operacion1.porcentaje_referencia) promedio_componente_inicial,
		avg(operacion1.promedio_componente_final/operacion1.porcentaje_referencia) promedio_componente_final,
		componente_x_modalidad.porc_componente_x_modalidad
		FROM
		(
			SELECT
			sum(porcentaje_inicial) promedio_componente_inicial,
			sum(porcentaje_final) promedio_componente_final,
			sum(porcentaje_referencia) porcentaje_referencia,
			operacion.id_tema,
			operacion.id_modalidad as id_modalidad,
			operacion.id_contrato,
			operacion.nombre_prestador,
			operacion.nombre_modalidad,
			operacion.id_componente,
			operacion.nombre_componente
			FROM
			(
				SELECT
				sum(porc_inicial) porcentaje_inicial,
				sum(porc_final) porcentaje_final,
				sum(porc_referencia) porcentaje_referencia,
				tema.id_tema,
				evaluacion.id_modalidad,
				evaluacion.id_contrato,
				prestador.nombre_prestador,
				modalidad.nombre_modalidad,
				componente.id_componente,
				componente.nombre_componente
				FROM
				evaluacion, tema, prestador, modalidad, componente
				WHERE
				evaluacion.id_tema = tema.id_tema and
				evaluacion.id_prestador= prestador.id_prestador and evaluacion.id_modalidad=modalidad.id_modalidad and
				evaluacion.id_componente = componente.id_componente and
				evaluacion.fecha_evaluacion <= '$fcorte' and id_contrato='$id_contrato' and
				evaluacion.estado='1'
				group by id_subtema, id_acta
				) as operacion
				group by id_tema
				)as operacion1, componente_x_modalidad
				where componente_x_modalidad.id_modalidad = operacion1.id_modalidad
				and componente_x_modalidad.id_tema = operacion1.id_tema
				group by operacion1.id_tema"), $conexion);

				$porcentaje_cobertura = mysql_query(("
				INSERT INTO  $tabla_temp (id_contrato, nombre_prestador, nombre_modalidad, id_componente, nombre_componente, promedio_componente_inicial, promedio_componente_final, porc_componente_x_modalidad)
				SELECT
				operacion.id_contrato,
				operacion.nombre_prestador,
				operacion.nombre_modalidad,
				operacion.id_componente,
				operacion.nombre_componente,
				avg(porcentaje_inicial) promedio_componente_inicial,
				avg(porcentaje_final) promedio_componente_final,
				operacion.porc_componente_x_modalidad
				FROM
				(
					SELECT
					sum(porc_inicial)/sum(porc_referencia) porcentaje_inicial,
					sum(porc_final)/sum(porc_referencia) porcentaje_final,
					acta.id_contrato,
					acta.id_modalidad,
					prestador.nombre_prestador,
					modalidad.nombre_modalidad,
					componente.id_componente,
					componente.nombre_componente,
					componente_x_modalidad.porc_componente_x_modalidad
					FROM
					acta, prestador, modalidad, componente, componente_x_modalidad
					WHERE
					acta.id_prestador = prestador.id_prestador and
					acta.id_modalidad = modalidad.id_modalidad and
					acta.id_componente = componente.id_componente and
					acta.id_componente = componente_x_modalidad.id_componente and
					acta.id_modalidad = componente_x_modalidad.id_modalidad and
					acta.id_contrato='$id_contrato' and
					acta.fecha_evaluacion <= '$fcorte' and
					componente.id_componente='3' and
					acta.estado='0'
					group by acta.id_acta
					) as operacion"), $conexion);

					$porcentajes_nutricion = mysql_query(("
					SELECT id_contrato, nombre_componente, id_componente, promedio_componente_inicial, promedio_componente_final
					FROM $tabla_temp
					WHERE id_componente = 7 and id_contrato='$id_contrato'"),$conexion);

					while ($row = mysql_fetch_array($porcentajes_nutricion)) {
						$contrato = $row["id_contrato"];
						$porc_inicial = $row["promedio_componente_inicial"];
						$porc_final = $row["promedio_componente_final"];
					}

					$descuentos_calculo_calidad = mysql_query(("
					SELECT
					detalle_tipo_descuento.tipo_descuento,
					descuentos_x_valoracion.estado,
					detalle_tipo_descuento.descuento
					FROM descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
					WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador and
					descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
					detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
					tipo_descuento.id = detalle_tipo_descuento.tipo_descuento and
					descuentos_x_valoracion.id_contrato = '$id_contrato' and
					descuentos_x_valoracion.fecha <= '$fcorte'
					ORDER BY prestador.nombre_prestador"), $conexion);

					$descuentos_calculo_cumplimiento = mysql_query(("
					SELECT
					detalle_tipo_descuento.tipo_descuento,
					descuentos_x_valoracion.estado,
					detalle_tipo_descuento.descuento
					FROM descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
					WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador and
					descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
					detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
					tipo_descuento.id = detalle_tipo_descuento.tipo_descuento and
					descuentos_x_valoracion.id_contrato = '$id_contrato' and
					descuentos_x_valoracion.fecha <= '$fcorte'
					ORDER BY prestador.nombre_prestador"), $conexion);

					//desceuentos de calidad
					$descuento = 0;
					$descuento_no_patogeno = 0;
					while ($row1=mysql_fetch_assoc($descuentos_calculo_calidad)) {
						if ($row1['tipo_descuento'] == 3) {
							$descuento_no_patogeno = null;
							$descuento_no_patogeno = $row1["descuento"];
						}else {
							$descuento = $descuento + $row1["descuento"];
						}
					}
					$descuento = $descuento + $descuento_no_patogeno;
					$descuento = round($descuento,4)/100;
					$porc_inicial = $porc_inicial - $descuento;

					//descuentos cumplimiento
					$descuento1 = 0;
					$descuento_no_patogeno1 = 0;
					while ($row1=mysql_fetch_assoc($descuentos_calculo_cumplimiento)) {
						if ($row1['estado'] != 0) {
							if ($row1['tipo_descuento'] == 3) {
								$descuento_no_patogeno1 = null;
								$descuento_no_patogeno1 = $row1["descuento"];
							}else {
								$descuento1 = $descuento1 + $row1["descuento"];
							}
						}
					}
					$descuento1 = $descuento1 + $descuento_no_patogeno1;
					$descuento1 = round($descuento1,4)/100;
					$porc_final = $porc_final + $descuento1;
					if ($porc_final > 1) {
						$porc_final = 1;
					}

					$update_temp = mysql_query(("
					UPDATE $tabla_temp SET
					promedio_componente_inicial = '$porc_inicial',
					promedio_componente_final = '$porc_final'
					WHERE id_componente = 7 and id_contrato='$id_contrato'"), $conexion);

					$consulta_datos_temp = mysql_query(("
					SELECT
					round(sum(operacion.promedio_inicial) / sum(operacion.porc_componente_x_modalidad),4) as promedio_componente_inicial,
					round(sum(operacion.promedio_final) / sum(operacion.porc_componente_x_modalidad),4) as promedio_componente_final,
					operacion.id_contrato,
					operacion.nombre_prestador,
					operacion.nombre_modalidad
					FROM
					(SELECT
					id_contrato,
					nombre_prestador,
					nombre_modalidad,
					id_componente,
					nombre_componente,
					promedio_componente_inicial*porc_componente_x_modalidad as promedio_inicial,
					promedio_componente_final*porc_componente_x_modalidad as promedio_final,
					porc_componente_x_modalidad
					FROM $tabla_temp) as operacion"), $conexion);

					$row=mysql_fetch_assoc($consulta_datos_temp);
					$promedio_final=$row['promedio_componente_final']*100;
					$delete_temp = mysql_query(("DROP TABLE $tabla_temp"), $conexion);
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
					<h2>Valoraciones por Contrato detallado por Sede</h2>
					<h5>Verifique el cumplimiento y la calidad para cada uno de los contratos</h5>



					<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="GET" action="reportes.php">


						<select data-parsley-min="1" class="form-control" name="id_contrato">
							<option value="0" required>Seleccione el número de Contrato...</option>
							<?php  	while($row=mysql_fetch_assoc($lista_desplegable)){ ?>
								<option  value="<?php  echo  $row['id_contrato']; ?>"><?php echo  $row['id_contrato'].": ".$row['abr_modalidad']." - ".$row['nombre_prestador']; ?></option>	<?php 	}	?>
							</select>


							<input data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/" class="form-control input datepicker" name="fcorte" type="text" value="<?php date('Y-m-d'); ?>" placeholder="  Fecha de Corte: (Ej: aaaa-mm-dd)" required>


							<button  class="btn btn-pascual" type="submit">Consultar</button>
							<br>
							<br>
						</form>


					</div> <!-- /jumbotron -->

					<?php
					//SECCION VALORACIONES

					if($id_contrato!=0){ //1er if

						$numrows= mysql_num_rows($actas_creadas);
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



							?>

							<div class="alert alert-default" role="alert">
								<center> <p> <img src="images/pascualbravo.jpg" width="200" height="60"> </p>
									<h2><center>Valoración Porcentual del Cumplimiento del Contrato</strong></h2>
										<h4><center>Interventoría Buen Comienzo</center></h4>

										<div class="footer">
										</div>

									</div>

									<div class="bs-docs-section">
										<h3 id="tables-example">Información del Contrato </h3>
									</div>
									<div class="footer"></div>

									<div class="row">

										<div class="col-sm-4">
											<div class="panel panel-primary">
												<div class="panel-heading"><h3 class="panel-title">CONTRATO</h3></div>
												<div class="panel-body"><?php echo $id_contrato;  ?></div>
											</div>
										</div><!-- /.col-sm-4 -->

										<div class="col-sm-8">
											<div class="panel panel-primary">
												<div class="panel-heading"><h3 class="panel-title">PRESTADOR</h3></div>
												<div class="panel-body"><?php echo $nombre_prestador;  ?></div>
											</div>
										</div><!-- /.col-sm-4 -->

										<div class="col-sm-4">
											<div class="panel panel-primary">
												<div class="panel-heading"><h3 class="panel-title">MODALIDAD</h3></div>
												<div class="panel-body"><?php echo $nombre_modalidad;  ?></div>
											</div>
										</div><!-- /.col-sm-4 -->

										<div class="col-sm-4">
											<div class="panel panel-primary">
												<div class="panel-heading"><h3 class="panel-title">PROMEDIO CUMPLIMIENTO</h3></div>
												<center><div class="panel-body"><?php echo $promedio_final;  ?>%</div></center>
											</div>
										</div><!-- /.col-sm-4 -->

										<div class="col-sm-4">
											<div class="panel panel-primary">
												<div class="panel-heading"><h3 class="panel-title">CLASIFICACIÓN Y CONCEPTO</h3></div>
												<?php

												if($promedio_final>=95){
													?><div class="panel-body alert-success" align="center"><strong>A</strong>- Cumple</div><?php
												}
												else{
													if($promedio_final>=80){
														?><div class="panel-body alert-info" align="center"><strong>B</strong>- Cumple Parcialmente</div><?php
													}
													elseif($promedio_final>=60){
														?><div class="panel-body alert-warning" align="center"><strong>C</strong>- Cumple Parcialmente</div><?php
													}
													else{
														?><div class="panel-body alert-danger" align="center"><strong>D</strong>- No Cumple</div><?php
													}
												}


												?>
											</div>
										</div><!-- /.col-sm-4 -->

									</div><!-- row -->

									<div class="alert alert-warning" role="alert">
										<center>Tenga en cuenta las siguientes convenciones:</center>
										<center>%I=Porcentaje Inicial de la visita, %F=Porcentaje Final de la visita, %C=Porcentaje de Cumplimiento relativo al componente</center>
									</div>

								</div> <!-- /container -->



								<table align="center" class="table table-bordered table-hover" id='table' style="width: 95%">
									<thead>
										<tr>
											<th rowspan="2" class="info"># Sede</th>
											<th rowspan="2" class="info">Nombre_de_la_Sede</th>
											<th rowspan="2" class="info">Visita</th>
											<th colspan="3" class="info">Gestión Institucional - <?php $k = array_search('401', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Gestión Estratégica - <?php $k = array_search('402', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Cobertura - <?php $k = array_search('301', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Salud - <?php $k = array_search('101', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Seguridad - <?php $k = array_search('102', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Dotación - <?php $k = array_search('201', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Infraestructura - <?php $k = array_search('501', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Nutrición - <?php $k = array_search('701', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Procesos Pedagógicos - <?php $k = array_search('801', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Valoración del Desarrollo - <?php $k = array_search('802', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Participación - <?php $k = array_search('901', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Protección - <?php $k = array_search('902', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th colspan="3" class="info">Interacción con Familias - <?php $k = array_search('903', $id_tema); echo $porc_componente_x_modalidad[$k]*100; ?>%</th>
											<th rowspan="2" class="info">%C Total</th>

										</tr>
										<tr>

											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>
											<th class="warning">%I</th>
											<th class="warning">%F</th>
											<th class="danger">%C</th>

										</tr>
									</thead>
									<tbody>

										<?php for ($i=0; $i < count($id_sede);$i++) { ?>

											<tr>
												<td class="active"><?php echo $id_sede[$i]; ?></td>
												<td class="active"><?php echo $nombre_sede[$i]; ?></td>
												<td class="active"><?php echo $numero_visita[$i]; ?></td>

												<?php
												/* Modelo Consultando en la tabla Acta
												//Consulta GESTION INSTITUCIONAL
												$gi= mysql_query(("
												SELECT
												porc_inicial,
												porc_final,
												porc_componente_x_final
												FROM acta
												WHERE
												id_contrato='$id_contrato' and
												fecha_evaluacion<='$fcorte' and
												id_sede='$id_sede[$i]' and
												nombre_sede='$nombre_sede[$i]' and
												numero_visita='$numero_visita[$i]' and
												id_componente='4'
												"),$conexion);

												$numrows= mysql_num_rows($gi);
												if ($numrows != 0){

												while($row=mysql_fetch_assoc($gi)){


												?>
												<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
												<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
												<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
												<?php }//end While

											}else{
											?>
											<td class="active"></td>
											<td class="active"></td>
											<td class="active"></td>
											<?php
										} //End Else
										*/
										?>



										<?php
										//GI
										$gi= mysql_query(("
										SELECT
										round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
										round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
										round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
										round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
										FROM

										(

											SELECT
											e.id_contrato,
											e.id_sede,
											s.nombre_sede,
											e.id_tema,
											t.nombre_tema,
											e.id_acta,
											month(e.fecha_evaluacion) mes,
											sum(e.porc_inicial) porcentaje_inicial,
											sum(e.porc_final) porcentaje_final,
											sum(e.porc_referencia) porcentaje_referencia,
											sum(e.porc_componente_x_final) porc_componente_x_final
											FROM
											acta a, evaluacion e, tema t, sede s
											WHERE
											a.id_acta = e.id_acta and
											e.fecha_evaluacion<='$fcorte' and
											e.estado='1' AND e.id_contrato = '$id_contrato'
											and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
											e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '4'
											and e.id_tema = '401'
											group by id_acta,id_subtema
											) as operacion
											group by operacion.id_contrato,operacion.id_tema, operacion.id_sede


											"),$conexion);

											$numrows= mysql_num_rows($gi);
											if ($numrows != 0){

												while($row=mysql_fetch_assoc($gi)){
													if($row['porcentaje_referencia']!=""){

														?>
														<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
														<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
														<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
													<?php }

													else{
														?>
														<td class="active"></td>
														<td class="active"></td>
														<td class="active"></td>
														<?php
													} //End Else
												} //End While
											} //End if numrows
											else{
												?>
												<td class="active"></td>
												<td class="active"></td>
												<td class="active"></td>
												<?php
											} //End Else numrows
											?>


											<?php
											//GE
											$ge= mysql_query(("
											SELECT
											round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
											round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
											round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
											round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
											FROM

											(

												SELECT
												e.id_contrato,
												e.id_sede,
												s.nombre_sede,
												e.id_tema,
												t.nombre_tema,
												e.id_acta,
												month(e.fecha_evaluacion) mes,
												sum(e.porc_inicial) porcentaje_inicial,
												sum(e.porc_final) porcentaje_final,
												sum(e.porc_referencia) porcentaje_referencia,
												sum(e.porc_componente_x_final) porc_componente_x_final
												FROM
												acta a, evaluacion e, tema t, sede s
												WHERE
												a.id_acta = e.id_acta and
												e.fecha_evaluacion<='$fcorte' and
												e.estado='1' AND e.id_contrato = '$id_contrato'
												and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
												e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '4'
												and e.id_tema = '402'
												group by id_acta,id_subtema
												) as operacion
												group by operacion.id_contrato,operacion.id_tema, operacion.id_sede

												"),$conexion);

												$numrows= mysql_num_rows($ge);
												if ($numrows != 0){

													while($row=mysql_fetch_assoc($ge)){
														if($row['porcentaje_referencia']!=""){

															?>
															<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
															<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
															<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
														<?php }

														else{
															?>
															<td class="active"></td>
															<td class="active"></td>
															<td class="active"></td>
															<?php
														} //End Else
													} //End While
												} //End if numrows
												else{
													?>
													<td class="active"></td>
													<td class="active"></td>
													<td class="active"></td>
													<?php
												} //End Else numrows
												?>

												<?php
												//CO
												$co= mysql_query(("
												SELECT
												sum(porc_referencia) porcentaje_referencia,
												round(sum(porc_inicial)/sum(porc_referencia),4) porc_inicial,
												round(sum(porc_final)/sum(porc_referencia),4) porc_final,
												round(sum(porc_componente_x_final)/sum(porc_referencia),4) porc_componente_x_final
												FROM acta
												WHERE
												id_contrato='$id_contrato' and
												fecha_evaluacion<='$fcorte' and
												id_sede='$id_sede[$i]' and
												numero_visita='$numero_visita[$i]' and
												id_componente='3'
												GROUP BY id_contrato, id_sede, id_componente, numero_visita
												"),$conexion);

												$numrows= mysql_num_rows($co);
												if ($numrows != 0){

													while($row=mysql_fetch_assoc($co)){
														if($row['porcentaje_referencia']!=""){

															?>
															<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
															<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
															<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
														<?php }

														else{
															?>
															<td class="active"></td>
															<td class="active"></td>
															<td class="active"></td>
															<?php
														} //End Else
													} //End While
												} //End if numrows
												else{
													?>
													<td class="active"></td>
													<td class="active"></td>
													<td class="active"></td>
													<?php
												} //End Else numrows
												?>

												<?php
												//SA
												$sa= mysql_query(("
												SELECT
												round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
												round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
												round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
												round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
												FROM

												(

													SELECT
													e.id_contrato,
													e.id_sede,
													s.nombre_sede,
													e.id_tema,
													t.nombre_tema,
													e.id_acta,
													month(e.fecha_evaluacion) mes,
													sum(e.porc_inicial) porcentaje_inicial,
													sum(e.porc_final) porcentaje_final,
													sum(e.porc_referencia) porcentaje_referencia,
													sum(e.porc_componente_x_final) porc_componente_x_final
													FROM
													acta a, evaluacion e, tema t, sede s
													WHERE
													a.id_acta = e.id_acta and
													e.fecha_evaluacion<='$fcorte' and
													e.estado='1' AND e.id_contrato = '$id_contrato'
													and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
													e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '1'
													and e.id_tema = '101'
													group by id_acta,id_subtema
													) as operacion
													group by operacion.id_contrato,operacion.id_tema, operacion.id_sede

													"),$conexion);

													$numrows= mysql_num_rows($sa);
													if ($numrows != 0){

														while($row=mysql_fetch_assoc($sa)){
															if($row['porcentaje_referencia']!=""){

																?>
																<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
															<?php }

															else{
																?>
																<td class="active"></td>
																<td class="active"></td>
																<td class="active"></td>
																<?php
															} //End Else
														} //End While
													} //End if numrows
													else{
														?>
														<td class="active"></td>
														<td class="active"></td>
														<td class="active"></td>
														<?php
													} //End Else numrows
													?>

													<?php
													//SE
													$se= mysql_query(("
													SELECT
													round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
													round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
													round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
													round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
													FROM

													(

														SELECT
														e.id_contrato,
														e.id_sede,
														s.nombre_sede,
														e.id_tema,
														t.nombre_tema,
														e.id_acta,
														month(e.fecha_evaluacion) mes,
														sum(e.porc_inicial) porcentaje_inicial,
														sum(e.porc_final) porcentaje_final,
														sum(e.porc_referencia) porcentaje_referencia,
														sum(e.porc_componente_x_final) porc_componente_x_final
														FROM
														acta a, evaluacion e, tema t, sede s
														WHERE
														a.id_acta = e.id_acta and
														e.fecha_evaluacion<='$fcorte' and
														e.estado='1' AND e.id_contrato = '$id_contrato'
														and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
														e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '1'
														and e.id_tema = '102'
														group by id_acta,id_subtema
														) as operacion
														group by operacion.id_contrato,operacion.id_tema, operacion.id_sede
														"),$conexion);

														$numrows= mysql_num_rows($se);
														if ($numrows != 0){

															while($row=mysql_fetch_assoc($se)){
																if($row['porcentaje_referencia']!=""){

																	?>
																	<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																	<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																	<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
																<?php }

																else{
																	?>
																	<td class="active"></td>
																	<td class="active"></td>
																	<td class="active"></td>
																	<?php
																} //End Else
															} //End While
														} //End if numrows
														else{
															?>
															<td class="active"></td>
															<td class="active"></td>
															<td class="active"></td>
															<?php
														} //End Else numrows
														?>

														<?php
														//VD
														$vd= mysql_query(("
														SELECT
														round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
														round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
														round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
														round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
														FROM

														(

															SELECT
															e.id_contrato,
															e.id_sede,
															s.nombre_sede,
															e.id_tema,
															t.nombre_tema,
															e.id_acta,
															month(e.fecha_evaluacion) mes,
															sum(e.porc_inicial) porcentaje_inicial,
															sum(e.porc_final) porcentaje_final,
															sum(e.porc_referencia) porcentaje_referencia,
															sum(e.porc_componente_x_final) porc_componente_x_final
															FROM
															acta a, evaluacion e, tema t, sede s
															WHERE
															a.id_acta = e.id_acta and
															e.fecha_evaluacion<='$fcorte' and
															e.estado='1' AND e.id_contrato = '$id_contrato'
															and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
															e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '2'
															and e.id_tema = '201'
															group by id_acta,id_subtema
															) as operacion
															group by operacion.id_contrato,operacion.id_tema, operacion.id_sede

															"),$conexion);

															$numrows= mysql_num_rows($vd);
															if ($numrows != 0){

																while($row=mysql_fetch_assoc($vd)){
																	if($row['porcentaje_referencia']!=""){

																		?>
																		<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																		<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																		<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
																	<?php }

																	else{
																		?>
																		<td class="active"></td>
																		<td class="active"></td>
																		<td class="active"></td>
																		<?php
																	} //End Else
																} //End While
															} //End if numrows
															else{
																?>
																<td class="active"></td>
																<td class="active"></td>
																<td class="active"></td>
																<?php
															} //End Else numrows
															?>


															<?php
															//IN
															$in= mysql_query(("
															SELECT
															round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
															round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
															round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
															round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
															FROM

															(

																SELECT
																e.id_contrato,
																e.id_sede,
																s.nombre_sede,
																e.id_tema,
																t.nombre_tema,
																e.id_acta,
																month(e.fecha_evaluacion) mes,
																sum(e.porc_inicial) porcentaje_inicial,
																sum(e.porc_final) porcentaje_final,
																sum(e.porc_referencia) porcentaje_referencia,
																sum(e.porc_componente_x_final) porc_componente_x_final
																FROM
																acta a, evaluacion e, tema t, sede s
																WHERE
																a.id_acta = e.id_acta and
																e.fecha_evaluacion<='$fcorte' and
																e.estado='1' AND e.id_contrato = '$id_contrato'
																and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
																e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '5'
																and e.id_tema = '501'
																group by id_acta,id_subtema
																) as operacion
																group by operacion.id_contrato,operacion.id_tema, operacion.id_sede

																"),$conexion);

																$numrows= mysql_num_rows($in);
																if ($numrows != 0){

																	while($row=mysql_fetch_assoc($in)){
																		if($row['porcentaje_referencia']!=""){

																			?>
																			<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																			<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																			<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
																		<?php }

																		else{
																			?>
																			<td class="active"></td>
																			<td class="active"></td>
																			<td class="active"></td>
																			<?php
																		} //End Else
																	} //End While
																} //End if numrows
																else{
																	?>
																	<td class="active"></td>
																	<td class="active"></td>
																	<td class="active"></td>
																	<?php
																} //End Else numrows
																?>

																<?php
																//NT
																$nt= mysql_query(("
																SELECT
																round(avg(operacion1.porcentaje_inicial/operacion1.porcentaje_referencia),4) porc_inicial,
																round(avg(operacion1.porcentaje_final/operacion1.porcentaje_referencia),4) porc_final,
																round(operacion1.porcentaje_referencia,4) porcentaje_referencia,
																round(sum(operacion1.porc_componente_x_final)/ sum(operacion1.porcentaje_referencia),4) porc_componente_x_final
																FROM (
																	SELECT
																	sum(operacion.porcentaje_referencia) porcentaje_referencia,
																	sum(operacion.porcentaje_inicial) porcentaje_inicial,
																	sum(operacion.porcentaje_final) porcentaje_final,
																	sum(porc_componente_x_final) porc_componente_x_final,
																	operacion.id_tema
																	FROM
																	(

																		SELECT
																		e.id_contrato,
																		e.id_sede,
																		s.nombre_sede,
																		e.id_tema,
																		t.nombre_tema,
																		e.id_acta,
																		month(e.fecha_evaluacion) mes,
																		sum(e.porc_inicial) porcentaje_inicial,
																		sum(e.porc_final) porcentaje_final,
																		sum(e.porc_referencia) porcentaje_referencia,
																		sum(e.porc_componente_x_final) porc_componente_x_final
																		FROM
																		acta a, evaluacion e, tema t, sede s
																		WHERE
																		a.id_acta = e.id_acta and
																		e.fecha_evaluacion<='$fcorte' and
																		e.estado='1' AND e.id_contrato = '$id_contrato'
																		and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
																		e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '7'
																		and e.id_tema = '701'
																		group by id_acta,id_subtema
																		) as operacion
																		group by operacion.id_tema
																		) AS operacion1
																		group by operacion1.id_tema
																		"),$conexion);

																		$numrows= mysql_num_rows($nt);
																		if ($numrows != 0){

																			while($row=mysql_fetch_assoc($nt)){
																				if($row['porcentaje_referencia']!=""){

																					?>
																					<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																					<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																					<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
																				<?php }

																				else{
																					?>
																					<td class="active"></td>
																					<td class="active"></td>
																					<td class="active"></td>
																					<?php
																				} //End Else
																			} //End While
																		} //End if numrows
																		else{
																			?>
																			<td class="active"></td>
																			<td class="active"></td>
																			<td class="active"></td>
																			<?php
																		} //End Else numrows
																		?>


																		<?php
																		//PP
																		$pp= mysql_query(("
																		SELECT
																		round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
																		round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
																		round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
																		round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
																		FROM

																		(

																			SELECT
																			e.id_contrato,
																			e.id_sede,
																			s.nombre_sede,
																			e.id_tema,
																			t.nombre_tema,
																			e.id_acta,
																			month(e.fecha_evaluacion) mes,
																			sum(e.porc_inicial) porcentaje_inicial,
																			sum(e.porc_final) porcentaje_final,
																			sum(e.porc_referencia) porcentaje_referencia,
																			sum(e.porc_componente_x_final) porc_componente_x_final
																			FROM
																			acta a, evaluacion e, tema t, sede s
																			WHERE
																			a.id_acta = e.id_acta and
																			e.fecha_evaluacion<='$fcorte' and
																			e.estado='1' AND e.id_contrato = '$id_contrato'
																			and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
																			e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '8'
																			and e.id_tema = '801'
																			group by id_acta,id_subtema
																			) as operacion
																			group by operacion.id_contrato,operacion.id_tema, operacion.id_sede

																			"),$conexion);

																			$numrows= mysql_num_rows($pp);
																			if ($numrows != 0){

																				while($row=mysql_fetch_assoc($pp)){
																					if($row['porcentaje_referencia']!=""){

																						?>
																						<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																						<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																						<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
																					<?php }

																					else{
																						?>
																						<td class="active"></td>
																						<td class="active"></td>
																						<td class="active"></td>
																						<?php
																					} //End Else
																				} //End While
																			} //End if numrows
																			else{
																				?>
																				<td class="active"></td>
																				<td class="active"></td>
																				<td class="active"></td>
																				<?php
																			} //End Else numrows
																			?>

																			<?php
																			//VA
																			$va= mysql_query(("
																			SELECT
																			round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
																			round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
																			round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
																			round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
																			FROM

																			(

																				SELECT
																				e.id_contrato,
																				e.id_sede,
																				s.nombre_sede,
																				e.id_tema,
																				t.nombre_tema,
																				e.id_acta,
																				month(e.fecha_evaluacion) mes,
																				sum(e.porc_inicial) porcentaje_inicial,
																				sum(e.porc_final) porcentaje_final,
																				sum(e.porc_referencia) porcentaje_referencia,
																				sum(e.porc_componente_x_final) porc_componente_x_final
																				FROM
																				acta a, evaluacion e, tema t, sede s
																				WHERE
																				a.id_acta = e.id_acta and
																				e.fecha_evaluacion<='$fcorte' and
																				e.estado='1' AND e.id_contrato = '$id_contrato'
																				and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
																				e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '8'
																				and e.id_tema = '802'
																				group by id_acta,id_subtema
																				) as operacion
																				group by operacion.id_contrato,operacion.id_tema, operacion.id_sede

																				"),$conexion);

																				$numrows= mysql_num_rows($va);
																				if ($numrows != 0){

																					while($row=mysql_fetch_assoc($va)){
																						if($row['porcentaje_referencia']!=""){

																							?>
																							<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																							<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																							<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
																						<?php }

																						else{
																							?>
																							<td class="active"></td>
																							<td class="active"></td>
																							<td class="active"></td>
																							<?php
																						} //End Else
																					} //End While
																				} //End if numrows
																				else{
																					?>
																					<td class="active"></td>
																					<td class="active"></td>
																					<td class="active"></td>
																					<?php
																				} //End Else numrows
																				?>

																				<?php
																				//PA
																				$pa= mysql_query(("
																				SELECT
																				round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
																				round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
																				round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
																				round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
																				FROM

																				(

																					SELECT
																					e.id_contrato,
																					e.id_sede,
																					s.nombre_sede,
																					e.id_tema,
																					t.nombre_tema,
																					e.id_acta,
																					month(e.fecha_evaluacion) mes,
																					sum(e.porc_inicial) porcentaje_inicial,
																					sum(e.porc_final) porcentaje_final,
																					sum(e.porc_referencia) porcentaje_referencia,
																					sum(e.porc_componente_x_final) porc_componente_x_final
																					FROM
																					acta a, evaluacion e, tema t, sede s
																					WHERE
																					a.id_acta = e.id_acta and
																					e.fecha_evaluacion<='$fcorte' and
																					e.estado='1' AND e.id_contrato = '$id_contrato'
																					and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
																					e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '9'
																					and e.id_tema = '901'
																					group by id_acta,id_subtema
																					) as operacion
																					group by operacion.id_contrato,operacion.id_tema, operacion.id_sede

																					"),$conexion);

																					$numrows= mysql_num_rows($pa);
																					if ($numrows != 0){

																						while($row=mysql_fetch_assoc($pa)){
																							if($row['porcentaje_referencia']!=""){

																								?>
																								<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																								<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																								<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
																							<?php }

																							else{
																								?>
																								<td class="active"></td>
																								<td class="active"></td>
																								<td class="active"></td>
																								<?php
																							} //End Else
																						} //End While
																					} //End if numrows
																					else{
																						?>
																						<td class="active"></td>
																						<td class="active"></td>
																						<td class="active"></td>
																						<?php
																					} //End Else numrows
																					?>

																					<?php
																					//PR
																					$pr= mysql_query(("
																					SELECT
																					round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
																					round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
																					round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
																					round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
																					FROM

																					(

																						SELECT
																						e.id_contrato,
																						e.id_sede,
																						s.nombre_sede,
																						e.id_tema,
																						t.nombre_tema,
																						e.id_acta,
																						month(e.fecha_evaluacion) mes,
																						sum(e.porc_inicial) porcentaje_inicial,
																						sum(e.porc_final) porcentaje_final,
																						sum(e.porc_referencia) porcentaje_referencia,
																						sum(e.porc_componente_x_final) porc_componente_x_final
																						FROM
																						acta a, evaluacion e, tema t, sede s
																						WHERE
																						a.id_acta = e.id_acta and
																						e.fecha_evaluacion<='$fcorte' and
																						e.estado='1' AND e.id_contrato = '$id_contrato'
																						and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
																						e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '9'
																						and e.id_tema = '902'
																						group by id_acta,id_subtema
																						) as operacion
																						group by operacion.id_contrato,operacion.id_tema, operacion.id_sede

																						"),$conexion);

																						$numrows= mysql_num_rows($pr);
																						if ($numrows != 0){

																							while($row=mysql_fetch_assoc($pr)){
																								if($row['porcentaje_referencia']!=""){

																									?>
																									<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																									<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																									<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
																								<?php }

																								else{
																									?>
																									<td class="active"></td>
																									<td class="active"></td>
																									<td class="active"></td>
																									<?php
																								} //End Else
																							} //End While
																						} //End if numrows
																						else{
																							?>
																							<td class="active"></td>
																							<td class="active"></td>
																							<td class="active"></td>
																							<?php
																						} //End Else numrows
																						?>

																						<?php
																						//IF
																						$if= mysql_query(("
																						SELECT
																						round(sum(operacion.porcentaje_referencia),4) porcentaje_referencia,
																						round(sum(operacion.porcentaje_inicial)/ sum(porcentaje_referencia),4) porc_inicial,
																						round(sum(operacion.porcentaje_final)/ sum(porcentaje_referencia),4) porc_final,
																						round(sum(porc_componente_x_final)/ sum(porcentaje_referencia),4) porc_componente_x_final
																						FROM

																						(

																							SELECT
																							e.id_contrato,
																							e.id_sede,
																							s.nombre_sede,
																							e.id_tema,
																							t.nombre_tema,
																							e.id_acta,
																							month(e.fecha_evaluacion) mes,
																							sum(e.porc_inicial) porcentaje_inicial,
																							sum(e.porc_final) porcentaje_final,
																							sum(e.porc_referencia) porcentaje_referencia,
																							sum(e.porc_componente_x_final) porc_componente_x_final
																							FROM
																							acta a, evaluacion e, tema t, sede s
																							WHERE
																							a.id_acta = e.id_acta and
																							e.fecha_evaluacion<='$fcorte' and
																							e.estado='1' AND e.id_contrato = '$id_contrato'
																							and e.id_sede=s.id_sede and e.id_tema=t.id_tema and
																							e.numero_visita = '$numero_visita[$i]' and e.id_sede = '$id_sede[$i]' and e.id_componente = '9'
																							and e.id_tema = '903'
																							group by id_acta,id_subtema
																							) as operacion
																							group by operacion.id_contrato,operacion.id_tema, operacion.id_sede

																							"),$conexion);

																							$numrows= mysql_num_rows($if);
																							if ($numrows != 0){

																								while($row=mysql_fetch_assoc($if)){
																									if($row['porcentaje_referencia']!=""){

																										?>
																										<td class="active"><?php echo $row['porc_inicial']*100; ?>%</td>
																										<td class="active"><?php echo $row['porc_final']*100; ?>%</td>
																										<td class="active"><?php echo $row['porc_componente_x_final']*100; ?>%</td>
																									<?php }

																									else{
																										?>
																										<td class="active"></td>
																										<td class="active"></td>
																										<td class="active"></td>
																										<?php
																									} //End Else
																								} //End While
																							} //End if numrows
																							else{
																								?>
																								<td class="active"></td>
																								<td class="active"></td>
																								<td class="active"></td>
																								<?php
																							} //End Else numrows
																							?>



																							<?php
																							//Consulta SUMATORIA COMPONENTES
																							$sumatoria= mysql_query(("

																							SELECT
																							sum(operacion.porc_componente_x_modalidad) suma_porc_comp_x_mod,
																							sum(operacion.porc_componente_x_final) suma_porc_comp_x_final,
																							round(sum(operacion.porc_componente_x_final)/sum(operacion.porc_componente_x_modalidad),4) suma_total
																							FROM
																							(
																								SELECT
																								porc_final,
																								porc_componente_x_modalidad,
																								porc_final*porc_componente_x_modalidad porc_componente_x_final
																								FROM
																								acta
																								WHERE
																								id_contrato='$id_contrato' and
																								fecha_evaluacion<='$fcorte' and
																								id_sede='$id_sede[$i]' and
																								nombre_sede='$nombre_sede[$i]' and
																								numero_visita='$numero_visita[$i]'
																								) as operacion

																								"),$conexion);

																								$numrows= mysql_num_rows($sumatoria);
																								if ($numrows != 0){

																									while($row=mysql_fetch_assoc($sumatoria)){


																										?>
																										<td class="active"><?php echo $row['suma_total']*100; ?>%</td>
																									<?php }//end While

																								}else{
																									?>
																									<td class="active"></td>
																									<?php
																								} //End Else
																								?>


																							</tr>
																						<?php } ?>
																					</tbody>
																				</table>



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

																				<!-- Cerrar el boton emergente-->
																				$('.close').click(function() {
																					$(this).parent().parent().fadeOut();
																				});

																				<!-- Fecha Datepicker-->
																				$('.datepicker').datepicker({
																					format: 'yyyy-mm-dd'
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
