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
				<div class="item-menu-titulo">
					<span>Configuración inicial</span>
				</div>
				<!-- <div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="creacionusuarios.php?id=0">Creación/Edición Usuarios</a></span>
				</div> -->
				<div class="item-menu">
					<i class="material-icons">&#xE886;</i>
					<span><a href="configtemas.php?msg=0">Crear Componentes Técnico</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE8F9;</i>
					<span><a href="configsubtemas.php?msg=0">Crear Estándares</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE8AF;</i>
					<span><a href="configpreguntas.php?msg=0">Crear Preguntas por Modalidad</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE150;</i>
					<span><a href="configobservaciones.php?componente_selected=0&id_tema=0&msg=0">Editar Hallazgos y Acciones Correctivas</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE5CA;</i>
					<span><a href="reservaradicado.php?msg=0">Reservar números de Acta</a></span>
				</div>
				<div class="item-menu-titulo">
					<span>Interventorias</span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="consultaevaluaciones.php?msg=0">Interventorías</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE25C;</i>
					<span><a href="descuentoVer.php?msg=0">Descuentos Alimentos</a></span>
				</div>
				<?php if ($id_grupo == 1 || ($id_grupo == 3 && $id_componente == 7)){ ?>
					<div class="item-menu" id="descuentos_valoracion">
						<i class="material-icons">&#xE25C;</i>
						<span><a href="descuentosValoracion.php">Descuentos Valoracion</a></span>
					</div>
				<?php } ?>
				<div class="item-menu">
					<i class="material-icons">&#xE558;</i>
					<span><a href="consultaevaluacionesproveedores.php?msg=0">Visitas a Proveedores</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE002;</i>
					<span><a href="semaforohallazgos.php?componente_selected=0">Debido Proceso</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE14C;</i>
					<span><a href="visitasfallidas.php">Visitas No Valoradas</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE14F;</i>
					<span><a href="obligacionescontrato.php">Obligaciones x Contrato</a></span>
				</div>
				<div class="item-menu-titulo">
					<span>Informes</span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="configsemaforo.php">Informe Gestión Mensual x Contrato</a></span>
				</div>

				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="reportes.php?id_contrato=0">Valoraciones Detalladas x Sede</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="reportes2.php">Valoraciones Consolidadas Cumplimiento</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="reportes4.php">Valoraciones Consolidadas Calidad</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="reportes3.php?msg=0&componente_selected=0&id_tema=0&id_mes=0">Semáforo Consolidado</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="informesFinal.php?msg=0">Informes Finales</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="informesLiquidacion.php?msg=0">Informes Liquidación</a></span>
				</div>
				<?php if ($id_grupo==1) {
					echo '<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="informesMensuales.php">Informes Mensuales</a></span>
					</div>';
				}?>
				<div class="item-menu-titulo">
					<span>Descargas</span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE5DB;</i>
					<form class="" name="exportar" action="exportar.php" method="post">
						<input type="hidden" name="msg"  value="0">
						<span><a onclick="document.forms.exportar.submit()">&nbsp;&nbsp;Exportar a Excel</a></span>
					</form>
				</div>
				<?php if ($id_grupo == 1){ ?>
					<div class="item-menu">
						<i class="material-icons">&#xE5DB;</i>
						<span><a href="ExportarInformes.php?msg=0">Exportar Informes</a></span>
					</div>
				<?php } ?>
				<?php
			}
			elseif ($id_grupo==2) {
				//Grupo Members ?>
				<div class="item-menu-titulo">
					<span>Configuración inicial</span>
				</div>
				<!-- <div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="creacionusuarios.php?id=0">Creación/Edición Usuarios</a></span>
				</div> -->
				<div class="item-menu">
					<i class="material-icons">&#xE5CA;</i>
					<span><a href="reservaradicado.php?msg=0">Reservar números de Acta</a></span>
				</div>
				<div class="item-menu-titulo">
					<span>Interventorias</span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="consultaevaluaciones.php?msg=0">Interventorías</a></span>
				</div>
				<?php if($id_componente==7){ ?>
					<div class="item-menu">
						<i class="material-icons">&#xE558;</i>
						<span><a href="consultaevaluacionesproveedores.php?msg=0">Visitas a Proveedores</a></span>
					</div>
					<div class="item-menu">
						<i class="material-icons">&#xE25C;</i>
						<span><a href="descuentoVer.php?msg=0">Descuentos Alimentos</a></span>
					</div>
					<div class="item-menu" id="descuentos_valoracion">
						<i class="material-icons">&#xE25C;</i>
						<span><a href="descuentosValoracion.php">Descuentos Valoracion</a></span>
					</div>
				<?php } ?>
				<div class="item-menu">
					<i class="material-icons">&#xE002;</i>
					<span><a href="semaforohallazgos.php?componente_selected=0">Debido Proceso</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE14C;</i>
					<span><a href="visitasfallidas.php">Visitas No Valoradas</a></span>
				</div>
				<div class="item-menu-titulo">
					<span>Informes</span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="configsemaforo.php">Informe Gestión Mensual x Contrato</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="reportes.php?id_contrato=0">Valoraciones Detalladas x Sede</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="reportes2.php">Valoraciones Consolidadas Cumplimiento</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="reportes4.php">Valoraciones Consolidadas Calidad</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="reportes3.php?msg=0&componente_selected=0&id_tema=0&id_mes=0">Semáforo Consolidado</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="informesFinal.php?msg=0">Informes Finales</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE6E1;</i>
					<span><a href="informesLiquidacion.php?msg=0">Informes Liquidación</a></span>
				</div>
				<div class="item-menu-titulo">
					<span>Descargas</span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE5DB;</i>
					<form class="" name="exportar" action="exportar.php" method="post">
						<input type="hidden" name="msg"  value="0">
						<span><a onclick="document.forms.exportar.submit()">&nbsp;&nbsp;Exportar a Excel</a></span>
					</form>
				</div>
				<?php
			}
			elseif ($id_grupo == 5) {

				?>
			<?php }
			else { //Grupo Secretaria Educacion
				?>
				<h5>Este portal le permitirá consultar información relevante de la prestación del servicio del programa Buen Comienzo. De clic en la opción para continuar</h5>
				<div class="item-menu not-admin not-carga">
					<i class="material-icons">&#xE87C;</i>
					<span><a href="consultaevaluaciones.php?msg=0">Interventorías</a></span>
				</div>
				<div class="item-menu">
					<i class="material-icons">&#xE5DB;</i>
					<form class="" name="exportar" action="exportar.php" method="post">
						<input type="hidden" name="msg"  value="0">
						<span><a onclick="document.forms.exportar.submit()">&nbsp;&nbsp;Exportar a Excel</a></span>
					</form>
				</div>
				<?php
			} //<p><a class="btn btn-lg btn-block btn-danger" href="reportes3.php?msg=0&componente_selected=0&id_tema=0&id_mes=0" role="button">Semáforo Consolidado</a></p>
			?>
		</div>
		<div class="footer-menu-principal">
			<!-- <div><i class="material-icons">&#xE88E;</i></div> -->
			<div><a class="material-icons" href="logout.php">&#xE8AC;</a></div>
			<?php if ($id_grupo == 4){ ?>
				<div><a class="material-icons" >&#xE869;</a></div>
			<?php }else{?>
				<div><a class="material-icons" href="creacionusuarios.php?id=0">&#xE869;</a></div>
			<?php } ?>

		</div>
	</div>
</div>
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
