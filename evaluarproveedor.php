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
	$id_proveedor=$_POST['id_proveedor'];
	$id_componente=$_POST['id_componente'];
	$acta_reservada=$_POST['acta_reservada'];
	$id_acta=$_POST['id_acta'];
	$id_modalidad='0';

	//Query para hallar las variables faltantes
	$data= mysql_query(("
	SELECT
	*
	FROM
	proveedor
	WHERE
	id_proveedor='$id_proveedor'
	"),$conexion);

	while($row=mysql_fetch_assoc($data)){
		$nombre_proveedor=$row['nombre_proveedor'];
		$direccion_proveedor=$row['direccion_proveedor'];
		$barrio_proveedor=$row['barrio_proveedor'];
		$numero_telefono=$row['numero_telefono'];
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
		pregunta_x_modalidad.estado='1'
		order by
		pregunta_x_modalidad.id_tema,
		pregunta_x_modalidad.id_subtema,
		pregunta_x_modalidad.id_pregunta
		"),$conexion);


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

			<title>Diligenciar Evaluaciones Proveedores</title>

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
			<h3 id="tables-example">Información General del Proveedor</h3>
		</div>

		<div class="footer"></div>

		<div class="alert alert-info" role="alert">
			<strong>¡Señor(a) interventor(a)!</strong> Si los datos relacionados con el contrato presentan alguna inconsistencia, no diligencie la evaluación y póngase en contacto con el administrador del sistema
		</div>





		<div class="row">

			<div class="col-sm-4">
				<div class="panel panel-primary">
					<div class="panel-heading"><h3 class="panel-title">Componente</h3></div>
					<div class="panel-body"><?php echo $nombre_componente;  ?></div>
				</div>
			</div><!-- /.col-sm-4 -->

			<div class="col-sm-8">
				<div class="panel panel-primary">
					<div class="panel-heading"><h3 class="panel-title">Proveedor</h3></div>
					<div class="panel-body"><?php echo $nombre_proveedor;  ?></div>
				</div>
			</div><!-- /.col-sm-8 -->


		</div><!-- row -->

		<div class="bs-docs-section">
			<h3 id="tables-example">Información de la Sede del Proveedor </h3>
		</div>
		<div class="footer"></div>

		<div class="alert alert-success" role="alert">
			<strong>¡Señor(a) interventor(a)!</strong> Verifique los datos para esta evaluación. Si hay alguna modificación en la información de la sede, por favor realice el cambio.
		</div>



		<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">


			<div class="row">


				<div class="col-sm-6">
					<div class="panel panel-success">
						<div class="panel-heading"><h3 class="panel-title">Teléfono Sede</h3></div>
						<div class="panel-body"><textarea name="numero_telefono"  style="width:100%"><?php echo $numero_telefono;  ?></textarea></div>
					</div>
				</div><!-- /.col-sm-6 -->

				<div class="col-sm-6">
					<div class="panel panel-success">
						<div class="panel-heading"><h3 class="panel-title">Dirección Sede</h3></div>
						<div class="panel-body"><textarea name="direccion_proveedor"  style="width:100%"><?php echo $direccion_proveedor." - ".$barrio_proveedor;  ?></textarea></div>
					</div>
				</div><!-- /.col-sm-6 -->

			</div><!-- row -->


			<div class="bs-docs-section">
				<h3 id="tables-example">Datos de la Evaluación </h3>
			</div>
			<div class="footer"></div>

			<div class="alert alert-warning" role="alert">
				<strong>¡Señor(a) interventor(a)!</strong> Diligencie los siguientes datos para continuar. No deben quedar casillas en blanco
			</div>


			<center>
				<div class="form-inline">

					<div class="form-group">
						<input data-parsley-pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/" class="form-control input-lg datepicker" name="fecha_evaluacion" type="text" value="<?php date('Y-m-d'); ?>" placeholder="Fecha (Ej: aaaa-mm-dd)" required>
					</div>

					<div class="form-group">
						<input data-parsley-min="1" type="number" class="form-control input-lg" name="numero_visita" placeholder="Nímero de visita" required>
					</div>



					<br></br>

					<div class="form-group">
						<input data-parsley-pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" name="hora_inicio" type="text" class="form-control input-lg" id="exampleInputEmail2" placeholder="Hora Inicio (Ej: 00:00)" required >
					</div>


					<div class="form-group">
						<input data-parsley-pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" name="hora_fin" type="text"  class="form-control input-lg" placeholder="Hora Fin (Ej: 24:00)" required >
					</div>

					<br></br>

					<div class="form-group">
						<textarea class="form-control" name="nombre_prestadores" rows="5" style="width:800px" placeholder="Prestadores atendidos - Modalidad - Numero Contrato. (Ej: MUNDO MEJOR - I8H - 4600063445) "required ></textarea>
					</div>

					<div class="form-group">
						<textarea class="form-control" name="nombre_asistentes" rows="3" style="width:800px" placeholder="Participantes (Nombres y teléfonos contacto)"required ></textarea>
					</div>


					<br></br>

				</div>
			</center>




			<div class="bs-docs-section">
				<h2 id="tables-example">Evaluación</h2>
			</div>
			<div class="footer"></div>

			<div class="alert alert-danger" role="alert">
				<strong>¡Señor interventor!</strong> Para diligenciar esta evaluación tenga en cuenta las siguientes convenciones:
				1.C		= Cumple,
				3.NC	= No Cumple,
				4.NS = No Subsanable,
				5.NA	= No Aplica.

			</div>


			<?php
			//SECCION PREGUNTAS A EVALUAR

			if(mysql_num_rows($preguntas) > 0){ ?>


				<div class="footer"></div>




				<input type="hidden" name="caso" value="18">
				<input type="hidden" name="id_componente" value="<?php echo $id_componente; ?>">
				<input type="hidden" name="id_codigoacta" value="<?php echo $id_codigoacta; ?>">
				<input type="hidden" name="id_proveedor" value="<?php echo $id_proveedor; ?>">
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
													<th class="info">Estandar</th>
													<th class="info">Descripción de la pregunta</th>
													<th class="info">*Calificación*</th>
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
													<select data-parsley-min="1" class="form-control" id="select1" name="valor_calificacion[]">
														<option value="1" selected	>1.C</option>
														<option value="3" 			>3.NC</option>
														<option value="4" 			>4.NS</option>
														<option value="5" 			>5.NA</option>
													</select>
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
					<center><button  class="btn btn-lg btn-danger" type="submit">Guardar y Continuar</button></center>
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
