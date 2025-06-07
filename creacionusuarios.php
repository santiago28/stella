<?php

session_start();
if ($_SESSION['login'])
{

	include "conexion.php";
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$user=$_SESSION["login"];
	$nombre=$_SESSION["nombre_usuario"];
	$fotoperfil = $_SESSION["fotoperfil"];
	//Variables recibidas via GET para la actualización de usuarios
	$id=$_GET['id'];


	//Consultas de acuerdo al perfil
	if($id_grupo==1){ //Usuarios Admin
		$lista_desplegable= mysql_query(("SELECT * FROM componente where estado='1'"),$conexion);
		$lista_desplegable_2= mysql_query(("SELECT * FROM groups "),$conexion);
		$usuarios_creados= mysql_query(("	SELECT
			users.id,
			users.username,
			users.documento,
			users.first_name,
			users.last_name,
			users.email,
			users.phone,
			users.foto,
			componente.nombre_componente,
			groups.name,
			users.created_on
			FROM
			users,componente,groups
			where
			users.id_componente=componente.id_componente and
			users.id_group=groups.id and
			users.active='1'"),$conexion);


			if($id==!0){
				$usuarios_creados_2= mysql_query(("	SELECT
					users.id,
					users.username,
					users.documento,
					users.first_name,
					users.last_name,
					users.email,
					users.phone,
					users.foto,
					users.id_componente,
					componente.nombre_componente,
					users.id_group,
					groups.name,
					users.created_on
					FROM
					users,componente,groups
					where
					users.id_componente=componente.id_componente and
					users.id_group=groups.id and
					users.id='$id' and
					users.active='1'"),$conexion);
					while($row=mysql_fetch_assoc($usuarios_creados_2)){
						$id=$row['id'];
						$username=$row['username'];
						$documento=$row['documento'];
						$first_name=$row['first_name'];
						$last_name=$row['last_name'];
						$email=$row['email'];
						$phone=$row['phone'];
						$foto = $row['foto'];
						$id_componente=$row['id_componente'];
						$nombre_componente=$row['nombre_componente'];
						$id_group=$row['id_group'];
						$name=$row['name'];
					}
				}	//End if id=0

			} else{ //Usuarios Members

				$lista_desplegable= mysql_query(("SELECT * FROM componente where id_componente='$id_componente' and estado='1'"),$conexion);
				$lista_desplegable_2= mysql_query(("SELECT * FROM groups where id='$id_grupo' "),$conexion);
				$usuarios_creados= mysql_query(("	SELECT
					users.id,
					users.username,
					users.documento,
					users.first_name,
					users.last_name,
					users.email,
					users.phone,
					users.foto,
					componente.nombre_componente,
					groups.name,
					users.created_on
					FROM
					users,componente,groups
					where
					users.id_componente=componente.id_componente and
					users.id_group=groups.id and
					users.username='$user' and
					users.active='1'
					"),$conexion);


					if($id==!0){
						$usuarios_creados_2= mysql_query(("	SELECT
							users.id,
							users.username,
							users.documento,
							users.first_name,
							users.last_name,
							users.email,
							users.phone,
							users.foto,
							users.id_componente,
							componente.nombre_componente,
							users.id_group,
							groups.name,
							users.created_on
							FROM
							users,componente,groups
							where
							users.id_componente=componente.id_componente and
							users.id_group=groups.id and
							users.id='$id' and
							users.active='1'"),$conexion);
							while($row=mysql_fetch_assoc($usuarios_creados_2)){
								$id=$row['id'];
								$username=$row['username'];
								$documento=$row['documento'];
								$first_name=$row['first_name'];
								$last_name=$row['last_name'];
								$email=$row['email'];
								$phone=$row['phone'];
								$foto = $row['foto'];
								$id_componente=$row['id_componente'];
								$nombre_componente=$row['nombre_componente'];
								$id_group=$row['id_group'];
								$name=$row['name'];
							}
						}	//End if id=0

					} 	//End Members





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

						<title>Creación de Usuarios</title>

						<!-- Bootstrap core CSS -->
						<link href="css/bootstrap.min.css" rel="stylesheet">
						<!-- Estilos menú principal -->
						<link rel="stylesheet" href="css/estilos.css">

						<!-- Material Icons -->
						<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

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


					</head>

					<body>
						<div class="barra-menu">
							<div class="col-md-12">

								<div style="margin-top: 1%; float: right">

									<a class="glyphicon glyphicon-home" href="homeadmin.php" style="font-size:35px; color:#ffffff; text-decoration:none;" role="button"></a>

								</div>
							</div>
						</div>
						<?php include("menu.php"); ?>
						<div class="container">
							<div class="jumbotron">
								<h2>Creación/Edición de Usuarios</h2>
								<h5>Consulte los usuarios creados o ingrese nuevos si tiene los privilegios</h5>



								<?php

								if($id==0){		//Si el id llega vacio

									?>
									<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">
										<input type="hidden" name="caso" value="11">

										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon">Usuario</div>
												<input data-parsley-required class="form-control" type="text" name="username" placeholder="Ingrese el usuario">
											</div>
										</div>

										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon">Contraseña</div>
												<input data-parsley-minlength="8" data-parsley-required class="form-control" type="password" name="password" placeholder="Ingrese una contraseña alfanumérica de mínimo 8 caracteres">
											</div>
										</div>

										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon">Documento</div>
												<input data-parsley-required class="form-control" type="text" name="documento" placeholder="Ingrese el documento de identidad">
											</div>
										</div>

										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon">Nombres</div>
												<input data-parsley-required class="form-control" type="text" name="first_name" placeholder="Ingrese el/los nombre/s">
											</div>
										</div>

										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon">Apellidos</div>
												<input data-parsley-required class="form-control" type="text" name="last_name" placeholder="Ingrese el /los Apellido/s">
											</div>
										</div>

										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon">Email</div>
												<input class="form-control" data-parsley-required data-parsley-type="email" type="email" name="email" placeholder="Ingrese el correo (@interventoriabuencomienzo.org)">
											</div>
										</div>

										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon">Teléfono/Celular</div>
												<input data-parsley-required class="form-control" type="text" name="phone" placeholder="Ingrese el teléfono o celular">
											</div>
										</div>

										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon">Componente</div>
												<select data-parsley-min="1" class="form-control" name="id_componente">
													<option value="0" required>Seleccione el Componente...</option>
													<?php  	while($row=mysql_fetch_assoc($lista_desplegable)){ ?>
														<option  value="<?php  echo  $row['id_componente']; ?>"><?php echo  $row['nombre_componente']; ?></option>	<?php 	}	?>
													</select>
												</div>
											</div>

											<div class="form-group">
												<div class="input-group">
													<div class="input-group-addon">Grupo</div>
													<select data-parsley-min="1" class="form-control" name="id_group">
														<option value="0" required>Seleccione el Grupo...</option>
														<?php  	while($row=mysql_fetch_assoc($lista_desplegable_2)){ ?>
															<option  value="<?php  echo  $row['id']; ?>"><?php echo  $row['name']; ?></option>	<?php 	}	?>
														</select>
													</div>
												</div>

												<div class="form-group">
													<input id="fileUploadWebFoto" type="file" accept=".jpg,.png" style="display: none;" />
													<div class="input-group">
														<div class="input-group-addon">Foto&nbsp;<span class="glyphicon glyphicon-cloud-upload" id="subirFoto" style="cursor:pointer;"></span></div>
														<input class="form-control" type="text" name="foto" placeholder="Ingrese una foto de perfil" id="foto" readonly>
													</div>
												</div>

												<?php if ($id_grupo==1){?>
													<button  class="btn btn-pascual" type="submit">Ingresar Nuevo</button>
												<?php } ?>

												<br>
												<br>
											</form>

											<?php
										}	// End si llega vacio id



										else {

											?>

											<form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">
												<input type="hidden" name="caso" value="12">
												<input type="hidden" name="id" value="<?php echo  $id; ?>">
												<input type="hidden" name="username" value="<?php echo  $username; ?>">

												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Usuario</div>
														<input data-parsley-required class="form-control" type="text" name="username" value="<?php echo  $username; ?>" placeholder="Ingrese el usuario" disabled>
													</div>
												</div>

												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Contraseña</div>
														<input data-parsley-minlength="8" data-parsley-required class="form-control" type="password" name="password" placeholder="Ingrese una contraseña alfanumérica de mínimo 8 caracteres">
													</div>
												</div>

												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Documento</div>
														<input data-parsley-required class="form-control" type="text" name="documento" value="<?php echo $documento ?>" placeholder="Ingrese el documento de identidad">
													</div>
												</div>

												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Nombres</div>
														<input data-parsley-required class="form-control" type="text" name="first_name" value="<?php echo  $first_name; ?>" placeholder="Ingrese el/los nombre/s">
													</div>
												</div>

												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Apellidos</div>
														<input data-parsley-required class="form-control" type="text" name="last_name" value="<?php echo  $last_name; ?>" placeholder="Ingrese el /los Apellido/s">
													</div>
												</div>

												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Email</div>
														<input class="form-control" data-parsley-required data-parsley-type="email" type="email" name="email" value="<?php echo  $email; ?>" placeholder="Ingrese el correo (@interventoriabuencomienzo.org)">
													</div>
												</div>

												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Teléfono/Celular</div>
														<input data-parsley-required class="form-control" type="text" name="phone" value="<?php echo  $phone; ?>" placeholder="Ingrese el teléfono o celular">
													</div>
												</div>

												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Componente</div>
														<select data-parsley-min="1" class="form-control" name="id_componente">
															<option value="<?php echo  $id_componente; ?>" required><?php echo  $nombre_componente; ?></option>
															<?php  	while($row=mysql_fetch_assoc($lista_desplegable)){ ?>
																<option  value="<?php  echo  $row['id_componente']; ?>"><?php echo  $row['nombre_componente']; ?></option>	<?php 	}	?>
															</select>
														</div>
													</div>

													<div class="form-group">
														<div class="input-group">
															<div class="input-group-addon">Grupo</div>
															<select data-parsley-min="1" class="form-control" name="id_group">
																<option value="<?php echo  $id_group; ?>" required><?php echo  $name; ?></option>
																<?php  	while($row=mysql_fetch_assoc($lista_desplegable_2)){ ?>
																	<option  value="<?php  echo  $row['id']; ?>"><?php echo  $row['name']; ?></option>	<?php 	}	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<input id="fileUploadWebFoto" type="file" accept=".jpg,.png" style="display: none;" />
															<div class="input-group">
																<div class="input-group-addon">Foto&nbsp;<span class="glyphicon glyphicon-cloud-upload" id="subirFoto" style="cursor:pointer;"></span></div>
																<input class="form-control" type="text" name="foto" value="<?php echo  $foto; ?>" placeholder="Ingrese una foto de perfil" id="foto" readonly>
															</div>
														</div>

														<button  class="btn btn-pascual" type="submit">Modificar Usuario</button>
														<br>
														<br>
													</form>

												<?php } ?>



											</div> <!-- /jumbotron -->



											<?php
											//SECCION USUARIOS CREADOS

											if(mysql_num_rows($usuarios_creados) > 0){ ?>

												<div class="bs-docs-section" align="center">
													<h2 id="tables-example">Usuarios Creados</h2>
												</div>
												<div class="footer">
												</div>

											</div> <!-- /container -->

											<table align="center" class="table table-bordered table-hover" id='table' style="width: 80%">
												<thead>
													<tr>
														<th class="info">id</th>
														<th class="info">Usuario</th>
														<th class="info">Documento</th>
														<th class="info">Nombres</th>
														<th class="info">Apellidos</th>
														<th class="info">E-mail</th>
														<th class="info">Teléfono/Celular</th>
														<th class="info">Componente</th>
														<th class="info">Grupo</th>
														<th class="info">Fecha de Actualización</th>
														<th class="info">Editar</th>

														<?php if ($id_grupo==1){?>
															<th class="info">Eliminar</th>
														<?php } ?>
													</tr>
												</thead>
												<tbody>
													<?php
													while($row = mysql_fetch_assoc($usuarios_creados)){ ?>
														<tr>
															<td class="active"><?php echo $row['id'];  ?></td>
															<td class="active"><?php echo $row['username'];  ?></td>
															<td class="active"><?php echo $row['documento'] ?></td>
															<td class="active"><?php echo $row['first_name'];  ?></td>
															<td class="active"><?php echo $row['last_name'];  ?></td>
															<td class="active"><?php echo $row['email'];  ?></td>
															<td class="active"><?php echo $row['phone'];  ?></td>
															<td class="active"><?php echo $row['nombre_componente'];  ?></td>
															<td class="active"><?php echo $row['name'];  ?></td>
															<td class="active"><?php echo $row['created_on'];  ?></td>
															<td class="danger"><a  href='creacionusuarios.php?id=<?php echo $row['id'] ?>'><center><span class="glyphicon glyphicon-refresh"></span></center></a></td>
															<?php if ($id_grupo==1){?>
																<td class="danger"><a  href='deletes.php?eliminar=<?php echo $row['id'] ?>&caso=5'><center><span style="color:red" class="glyphicon glyphicon-trash"></span></center></a></td>
															<?php } ?>
														</tr>
													<?php } ?>
												</tbody>
											</table>
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


										<div class="container">

											<div class="footer">
												<center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

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

										$("#subirFoto").click(function(){
											$("#fileUploadWebFoto").trigger('click');
										});

										$("#fileUploadWebFoto").change(function(){
											dataweb = new FormData();
											var file = $("#fileUploadWebFoto").get(0).files;
											dataweb.append("UploadedImage", file[0]);

											var ajaxRequest = $.ajax({
												type: "POST",
												url: "lib/uploadfiles/uploadfiles.php",
												contentType: false,
												processData: false,
												data: dataweb
											}).done(function (responseData, textStatus) {
												$("#foto").val(responseData);
											}).fail(function () {
											});
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
