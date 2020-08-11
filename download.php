<?php

session_start();
if ($_SESSION['login'])
{

	?>
	<script src="js/alasql.min.js"></script>
	<script src="js/xlsx.core.min.js"></script>

	<script type="text/javascript">
	var Export= [];

	</script>
	<?php
	//Variables recibidas via GET
	include "conexion.php";

	$id_componente=$_GET['id_componente'];
	$caso=$_GET['caso'];
	$fecha_inicial=$_GET['fecha_inicial'];
	$fecha_final=$_GET['fecha_final'];
	$id_prestador=$_GET["id_prestador"];


	//CASOS PARA GENERAR REPORTE
	//caso 1: Visitas realizadas
	//caso 2: Valoraciones x estandar
	//caso 3: Seguimiento debido proceso
	//caso 4: Calificaci�n de variables
	//caso 5: Proveedores - Valoracion x Estandar



	switch ($caso) {
		case "1":

		//Consulta  Visitas realizadas
		$registros=mysql_query("
		SELECT
		acta.id,
		acta.id_acta,
		acta.fecha_evaluacion,
		componente.nombre_componente,
		acta.id_contrato,
		prestador.nombre_prestador,
		modalidad.nombre_modalidad,
		acta.id_sede,
		acta.nombre_sede,
		acta.numero_visita,
		acta.id_interventor,
		sede.direccion_sede,
		sede.barrio_sede,
		acta.estado
		FROM
		acta,componente,prestador,modalidad,sede
		WHERE
		acta.id_componente=componente.id_componente and
		acta.id_prestador=prestador.id_prestador and
		acta.id_modalidad=modalidad.id_modalidad and
		acta.id_sede=sede.id_sede and
		acta.id_componente='$id_componente' and
		acta.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final'
		UNION
		SELECT
		acta_fallida.id,
		acta_fallida.id_acta,
		acta_fallida.fecha_evaluacion,
		componente.nombre_componente,
		acta_fallida.id_contrato,
		prestador.nombre_prestador,
		modalidad.nombre_modalidad,
		acta_fallida.id_sede,
		acta_fallida.nombre_sede,
		20 as numero_visita,
		acta_fallida.id_interventor,
		sede.direccion_sede,
		sede.barrio_sede,
		acta_fallida.estado
		FROM
		acta_fallida,componente,prestador,modalidad,sede
		WHERE
		acta_fallida.id_componente=componente.id_componente and
		acta_fallida.id_prestador=prestador.id_prestador and
		acta_fallida.id_modalidad=modalidad.id_modalidad and
		acta_fallida.id_sede=sede.id_sede and
		acta_fallida.id_componente='$id_componente' and
		acta_fallida.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final'
		UNION
		SELECT
		acta_proveedor.id,
		acta_proveedor.id_acta,
		acta_proveedor.fecha_evaluacion,
		componente.nombre_componente,
		0 as id_contrato,
		proveedor.nombre_proveedor,
		modalidad.nombre_modalidad,
		123456789 as id_sede,
		'sede proveedor' as nombre_sede,
		30 as numero_visita,
		acta_proveedor.id_interventor,
		acta_proveedor.direccion_proveedor,
		'medellin' as barrio_sede,
		acta_proveedor.estado
		FROM
		acta_proveedor,componente,proveedor,modalidad
		WHERE
		acta_proveedor.id_componente=componente.id_componente and
		acta_proveedor.id_proveedor=proveedor.id_proveedor and
		acta_proveedor.id_modalidad=modalidad.id_modalidad and
		acta_proveedor.id_componente='$id_componente' and
		acta_proveedor.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final'

		",$conexion)
		//
		//Construcci�n tabla en Excel
		or die("Problemas en el select:".mysql_error());
		?>

		<?php
		while ($reg=mysql_fetch_array($registros))
		{
			?>
			<script type="text/javascript">
			Export.push({
				"#": "<?php echo $reg['id']; ?>",
				"ACTA": "<?php echo $reg['id_acta']; ?>",
				"FECHA": "<?php echo date("d/m/Y", strtotime($reg['fecha_evaluacion'])); ?>",
				"COMPONENTE": "<?php echo $reg['nombre_componente']; ?>",
				"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
				"PRESTADOR": "<?php echo $reg['nombre_prestador']; ?>",
				"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
				"ID-SEDE": "<?php echo $reg['id_sede']; ?>",
				"NOMBRE SEDE": "<?php echo $reg['nombre_sede']; ?>",
				"VISITA": "<?php echo $reg['numero_visita']; ?>",
				"INTERVENTOR": "<?php echo $reg['id_interventor']; ?>",
				"DIRECCIÓN": "<?php echo $reg['direccion_sede']; ?>",
				"BARRIO": "<?php echo $reg['barrio_sede']; ?>",
				"ESTADO": "<?php if($reg['estado'] == 1){ echo 'abierta'; }else{ echo 'cerrada'; } ?>"
			});

			</script>
		<?php } ?>
		<script type="text/javascript">
		alasql('SELECT * INTO XLSX("actas.xlsx",{headers:true}) FROM ?', [Export]);
		window.location="exportar.php";
		</script>
		<?php
		break;



		case "2":

		//Consulta  Valoracion Estandares
		$registros=mysql_query("
		SELECT
		evaluacion.id_acta,
		componente.nombre_componente,
		evaluacion.id_contrato,
		prestador.nombre_prestador,
		modalidad.nombre_modalidad,
		evaluacion.id_sede,
		sede.nombre_sede,
		evaluacion.id_interventor,
		evaluacion.fecha_evaluacion,
		evaluacion.numero_visita,
		tema.nombre_tema,
		evaluacion.id_subtema,
		subtema.nombre_subtema,
		round(sum(porc_inicial)/sum(porc_referencia),4) porcentaje_estandar_inicial,
		round(sum(porc_final)/sum(porc_referencia),4) porcentaje_estandar_final,
		round(sum(porc_inicial),4) porcentaje_inicial_estandar,
		round(sum(porc_final),4) porcentaje_final_estandar
		FROM
		evaluacion,componente,prestador,modalidad,sede,tema,subtema
		WHERE
		evaluacion.id_componente=componente.id_componente and
		evaluacion.id_prestador=prestador.id_prestador and
		evaluacion.id_modalidad=modalidad.id_modalidad and
		evaluacion.id_sede=sede.id_sede and
		evaluacion.id_tema=tema.id_tema and
		evaluacion.id_subtema=subtema.id_subtema and
		evaluacion.id_componente='$id_componente' and
		evaluacion.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final'
		GROUP BY
		evaluacion.id_acta,evaluacion.id_subtema
		",$conexion)

		//Construcci�n tabla en Excel
		or die("Problemas en el select:".mysql_error());
		?>
		<?php
		while ($reg=mysql_fetch_array($registros))
		{
			?>
			<script type="text/javascript">
			Export.push({
				"ACTA": "<?php echo $reg['id_acta']; ?>",
				"COMPONENTE": "<?php echo $reg['nombre_componente']; ?>",
				"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
				"PRESTADOR": "<?php echo $reg['nombre_prestador']; ?>",
				"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
				"ID-SEDE": "<?php echo $reg['id_sede']; ?>",
				"NOMBRE-SEDE": "<?php echo $reg['nombre_sede']; ?>",
				"INTERVENTOR": "<?php echo $reg['id_interventor']; ?>",
				"FECHA EVALUACION": "<?php echo date("d/m/Y", strtotime($reg['fecha_evaluacion'])); ?>",
				"VISITA": "<?php echo $reg['numero_visita']; ?>",
				"CATEGORIA": "<?php echo $reg['nombre_tema']; ?>",
				"ID-ESTANDAR": "<?php echo $reg['id_subtema']; ?>",
				"NOMBRE ESTANDAR": "<?php echo $reg['nombre_subtema']; ?>",
				"% INICIAL": "<?php echo $reg['porcentaje_estandar_inicial']*100; ?>",
				"% FINAL": "<?php echo $reg['porcentaje_estandar_final']*100; ?>",
				"% INICIAL Prueba": "<?php echo $reg['porcentaje_inicial_estandar']*100; ?>",
				"% Final Prueba": "<?php echo $reg['porcentaje_final_estandar']*100; ?>",
			});
			</script>

		<?php } ?>
		<script type="text/javascript">
		alasql('SELECT * INTO XLSX("valoracion-estandares.xlsx",{headers:true}) FROM ?', [Export]);
		window.location="exportar.php";
		</script>
		<?php
		break;


		case "3":

		//Consulta  Seguimiento Debido Proceso
		$registros=mysql_query("
		SELECT
		subsanacion.id_acta,
		subsanacion.id_pregunta,
		pregunta.descripcion_pregunta,
		evaluacion.valor_calificacion_final,
		componente.nombre_componente,
		subsanacion.id_interventor,
		prestador.nombre_prestador,
		subsanacion.id_contrato,
		modalidad.abr_modalidad,
		acta.nombre_sede,
		acta.fecha_evaluacion,
		subsanacion.fecha_subsanacion_final,
		subsanacion.fecha_solicitud_aclaracion,
		subsanacion.fecha_requerimiento,
		datediff(subsanacion.fecha_subsanacion_final,CURDATE()) vencimiento_ac,
		datediff(subsanacion.fecha_solicitud_aclaracion,CURDATE()) vencimiento_sa,
		datediff(subsanacion.fecha_requerimiento,CURDATE()) vencimiento_req,
		subsanacion.historico,
		subsanacion.etapa,
		acta.estado

		FROM
		subsanacion,acta,evaluacion,componente,prestador,modalidad,pregunta
		WHERE
		subsanacion.id_acta=acta.id_acta and
		subsanacion.id_acta=evaluacion.id_acta and
		subsanacion.id_pregunta=evaluacion.id_pregunta and
		subsanacion.id_pregunta=pregunta.id_pregunta and
		subsanacion.id_componente=componente.id_componente and
		subsanacion.id_prestador=prestador.id_prestador and
		subsanacion.id_modalidad=modalidad.id_modalidad and

		acta.id_componente='$id_componente' and
		subsanacion.estado='1' and
		acta.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final'
		",$conexion)

		//Construcci�n tabla en Excel
		or die("Problemas en el select:".mysql_error());
		?>

		<?php
		while ($reg=mysql_fetch_array($registros))
		{
			?>
			<script type="text/javascript">
			Export.push({
				"ACTA": "<?php echo $reg['id_acta']; ?>",
				"FECHA": "<?php echo date("d/m/Y", strtotime($reg['fecha_evaluacion'])); ?>",
				"PREGUNTA": "<?php echo $reg['id_pregunta']; ?>",
				"DESCRIPCION PREGUNTA": "<?php echo $reg['descripcion_pregunta']; ?>",
				"COMPONENTE": "<?php echo $reg['nombre_componente']; ?>",
				"INTERVENTOR": "<?php echo $reg['id_interventor']; ?>",
				"PRESTADOR": "<?php echo $reg['nombre_prestador']; ?>",
				"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
				"MODALIDAD": "<?php echo $reg['abr_modalidad']; ?>",
				"SEDE": "<?php echo $reg['nombre_sede']; ?>",
				"CALIFICACION": "<?php echo $reg['valor_calificacion_final']; ?>",
				"ULTIMA ETAPA": "<?php echo $reg['historico']; ?>",
				"ESTADO": "<?php echo $reg['etapa']; ?>",
			})
			</script>
		<?php } ?>
		<script type="text/javascript">
		alasql('SELECT * INTO XLSX("debido-proceso.xlsx",{headers:true}) FROM ?', [Export]);
		window.location="exportar.php";
		</script>
		<?php
		break;

		case "4":

		//Consulta  Calificaci�n de Variables
		mysql_query("SET NAMES utf8");
		$registros=mysql_query("

		SELECT
		evaluacion.id_contrato,
		evaluacion.id_acta,
		evaluacion.fecha_evaluacion,
		tema.nombre_tema,
		modalidad.nombre_modalidad,
		sede.nombre_sede,
		prestador.nombre_prestador,
		subtema.nombre_subtema,
		REPLACE(REPLACE(pregunta.descripcion_pregunta,CHAR(10),''),CHAR(13),'') as descripcion_pregunta,
		evaluacion.valor_calificacion,
		evaluacion.valor_calificacion_final
		FROM
		evaluacion,
		tema,
		prestador,
		modalidad,
		sede,
		subtema,
		pregunta
		where
		evaluacion.id_tema=tema.id_tema and
		evaluacion.id_prestador=prestador.id_prestador and
		evaluacion.id_modalidad=modalidad.id_modalidad and
		evaluacion.id_sede=sede.id_sede and
		evaluacion.id_subtema=subtema.id_subtema and
		evaluacion.id_pregunta=pregunta.id_pregunta and
		evaluacion.id_componente='$id_componente' and
		evaluacion.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final'
		",$conexion)

		//Construcci�n tabla en Excel
		or die("Problemas en el select:".mysql_error());
		?>

		<?php
		while ($reg=mysql_fetch_array($registros))
		{
			?>
			<script type="text/javascript">
			Export.push({
				"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
				"ACTA": "<?php echo $reg['id_acta']; ?>",
				"FECHA EVALUACION": "<?php echo date("d/m/Y", strtotime($reg['fecha_evaluacion'])); ?>",
				"COMPONENTE": "<?php echo $reg['nombre_tema']; ?>",
				"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
				"NOMBRE SEDE": "<?php echo $reg['nombre_sede']; ?>",
				"NOMBRE PRESTADOR": "<?php echo $reg['nombre_prestador']; ?>",
				"ESTANDAR": "<?php echo $reg['nombre_subtema']; ?>",
				"DESCRIPCION PREGUNTA": "<?php echo	str_replace('"', " ", $reg['descripcion_pregunta']);?>",
				"CALIFICACION INICIA": "<?php echo $reg['valor_calificacion']; ?>",
				"CALIFICACION FINAL": "<?php echo $reg['valor_calificacion_final']; ?>"
			});
			</script>
		<?php } ?>
		<script type="text/javascript">
		alasql('SELECT * INTO XLSX("calificacionvariables.xlsx",{headers:true}) FROM ?', [Export]);
		window.location="exportar.php";
		</script>
		<?php
		break;


		case "5":

		//Consulta  Proveedores - Valoracion Estandares
		$registros=mysql_query("

		SELECT
		evaluacion_proveedor.id_acta,
		componente.nombre_componente,
		0 as id_contrato,
		proveedor.nombre_proveedor,
		modalidad.nombre_modalidad,
		0 as id_sede,
		'VISITA A PROVEEDOR' as nombre_sede,
		evaluacion_proveedor.id_interventor,
		evaluacion_proveedor.fecha_evaluacion,
		evaluacion_proveedor.numero_visita,
		tema.nombre_tema,
		evaluacion_proveedor.id_subtema,
		subtema.nombre_subtema,
		round(sum(porc_inicial)/sum(porc_referencia),4) porcentaje_estandar_inicial,
		round(sum(porc_final)/sum(porc_referencia),4) porcentaje_estandar_final

		FROM
		evaluacion_proveedor,componente,proveedor,modalidad,sede,tema,subtema


		WHERE
		evaluacion_proveedor.id_componente=componente.id_componente and
		evaluacion_proveedor.id_proveedor=proveedor.id_proveedor and
		evaluacion_proveedor.id_modalidad=modalidad.id_modalidad and
		evaluacion_proveedor.id_tema=tema.id_tema and
		evaluacion_proveedor.id_subtema=subtema.id_subtema and
		evaluacion_proveedor.id_componente='$id_componente' and
		evaluacion_proveedor.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final'

		GROUP BY
		evaluacion_proveedor.id_acta,evaluacion_proveedor.id_subtema


		",$conexion)

		//Construcci�n tabla en Excel
		or die("Problemas en el select:".mysql_error());

		?>

		<?php
		while ($reg=mysql_fetch_array($registros))
		{
			?>
			<script type="text/javascript">
			Export.push({
				"ACTA": "<?php echo $reg['id_acta']; ?>",
				"COMPONENTE": "<?php echo $reg['nombre_componente']; ?>",
				"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
				"PROVEEDOR": "<?php echo $reg['nombre_proveedor']; ?>",
				"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
				"ID-SEDE": "<?php echo $reg['id_sede']; ?>",
				"NOMBRE-SEDE": "<?php echo $reg['nombre_sede']; ?>",
				"INTERVENTOR": "<?php echo $reg['id_interventor']; ?>",
				"FECHA EVALUACION": "<?php echo date("d/m/Y", strtotime($reg['fecha_evaluacion'])); ?>",
				"VISITA": "<?php echo $reg['numero_visita']; ?>",
				"CATEGORIA": "<?php echo $reg['nombre_tema']; ?>",
				"ID-ESTANDAR": "<?php echo $reg['id_subtema']; ?>",
				"NOMBRE ESTANDAR": "<?php echo $reg['nombre_subtema']; ?>",
				"% CALIFICACION": "<?php echo $reg['porcentaje_estandar_inicial']*100; ?>"
			});
			</script>
		<?php } ?>
		<script type="text/javascript">
		alasql('SELECT * INTO XLSX("proveedores-valoracion-estandares.xlsx",{headers:true}) FROM ?', [Export]);
		window.location="exportar.php";
		</script>
		<?php
		break;

		case "6":

		//Consulta  Visitas realizadas Itinerante
		$registros=mysql_query("
		SELECT a.id,
		a.id_acta,
		a.fecha_evaluacion,
		c.nombre_componente,
		a.id_contrato,
		p.nombre_prestador,
		m.nombre_modalidad,
		cs.id_sede,
		s.nombre_sede,
		a.numero_visita,
		a.id_interventor,
		a.porc_inicial,
		a.porc_final,
		if(a.estado=1,'Abierta','Cerrada') estado
		FROM contrato_x_sede cs
		LEFT JOIN sede s ON (s.id_sede=cs.id_sede)
		LEFT JOIN acta a ON (a.id_sede=s.id_sede AND a.id_componente='$id_componente' AND a.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final' )
		LEFT JOIN modalidad m ON (cs.id_modalidad=m.id_modalidad)
		LEFT JOIN componente c ON (a.id_componente=c.id_componente)
		LEFT JOIN prestador p ON (cs.id_prestador=p.id_prestador)
		WHERE cs.id_modalidad = 12
		",$conexion)

		//Construcci�n tabla en Excel
		or die("Problemas en el select:".mysql_error());
		?>

		<?php
		while ($reg=mysql_fetch_array($registros))
		{
			?>
			<script type="text/javascript">
			Export.push({
				"#": "<?php echo $reg['id']; ?>",
				"ACTA": "<?php echo $reg['id_acta']; ?>",
				"FECHA": "<?php echo date("d/m/Y", strtotime($reg['fecha_evaluacion'])); ?>",
				"COMPONENTE": "<?php echo $reg['nombre_componente']; ?>",
				"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
				"PRESTADOR": "<?php echo $reg['nombre_prestador']; ?>",
				"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
				"ID-SEDE": "<?php echo $reg['id_sede']; ?>",
				"NOMBRE SEDE": "<?php echo $reg['nombre_sede']; ?>",
				"VISITA": "<?php echo $reg['numero_visita']; ?>",
				"INTERVENTOR": "<?php echo $reg['id_interventor']; ?>",
				"%INICIAL": "<?php echo $reg['porc_inicial']*100; ?>",
				"%FINAL": "<?php echo $reg['porc_final']*100; ?>",
				"ESTADO": "<?php echo $reg['estado']; ?>",
			});
			</script>
		<?php } ?>
		<script type="text/javascript">
		alasql('SELECT * INTO XLSX("actas.xlsx",{headers:true}) FROM ?', [Export]);
		window.location="exportar.php";
		</script>
		<?php
		break;
		// DESCUENTOS
		case "7":
		$registros=mysql_query('
		SELECT a.fecha_evaluacion,	a.id_acta,	a.id_contrato,	p.nombre_prestador,	m.abr_modalidad,	s.nombre_sede, a.id_interventor,	a.numero_visita,	d.id,	d.id_acta,	d.fecha_acta,	d.interventor,	d.matriculados,	d.asistentes,	d.alimentacion,
		CASE d.descripcion
		WHEN 1
		THEN "Producto en mal estado"
		WHEN 2
		THEN "Producto vencido"
		WHEN 3
		THEN "Cantidad insuficiente del producto"
		WHEN 4
		THEN "Inasistencia de ni�os" END as descripcion1,
		CASE d.unidad
		WHEN 1
		THEN "Gramos"
		WHEN 2
		THEN "Centimetros C�bicos"
		WHEN 3
		THEN "Unidad"
		WHEN 4
		THEN "Ni�os faltantes" END as unidad1,
		d.alimento,	d.detallealimento,	d.faltante,	d.grupo,	d.descontar,	d.observaciones,	d.estado
		FROM descuentos d
		LEFT JOIN acta a ON (a.id_acta=d.id_acta)
		LEFT JOIN prestador p ON (p.id_prestador=a.id_prestador)
		LEFT JOIN modalidad m ON (m.id_modalidad=a.id_modalidad)
		left JOIN sede s ON (s.id_sede=a.id_sede)
		WHERE d.estado = 3 AND (fecha_evaluacion >="'.$fecha_inicial.'" AND fecha_evaluacion <= "'.$fecha_final.'");
		', $conexion)
		//Construcci�n tabla en Excel
		or die("Problemas en el select:".mysql_error());
		?>

		<?php $i=0;
		while ($reg=mysql_fetch_array($registros))
		{
			?>
			<script type="text/javascript">
			Export.push({
				"#": "<?php echo $i++; ?>",
				"fecha_evaluacion": "<?php echo date("d/m/Y", strtotime($reg['fecha_evaluacion'])); ?>",
				"id_acta": "<?php echo $reg['id_acta']; ?>",
				"id_contrato": "<?php echo $reg['id_contrato']; ?>",
				"nombre_prestador": "<?php echo $reg['nombre_prestador']; ?>",
				"abr_modalidad": "<?php echo $reg['abr_modalidad']; ?>",
				"nombre_sede": "<?php echo $reg['nombre_sede']; ?>",
				"id_interventor": "<?php echo $reg['id_interventor']; ?>",
				"numero_visita": "<?php echo $reg['numero_visita']; ?>",
				"id": "<?php echo $reg['id']; ?>",
				"id_acta": "<?php echo $reg['id_acta']; ?>",
				"fecha_acta": "<?php echo date("d/m/Y", strtotime($reg['fecha_acta'])); ?>",
				"interventor": "<?php echo $reg['interventor']; ?>",
				"matriculados": "<?php echo $reg['matriculados']; ?>",
				"asistentes": "<?php echo $reg['asistentes']; ?>",
				"alimentacion": "<?php echo $reg['alimentacion']; ?>",
				"descripcion": "<?php echo $reg['descripcion1']; ?>",
				"unidad": "<?php echo $reg['unidad1']; ?>",
				"alimento": "<?php echo $reg['alimento']; ?>",
				"detallealimento": "<?php echo $reg['detallealimento']; ?>",
				"faltante": "<?php echo $reg['faltante']; ?>",
				"grupo": "<?php echo $reg['grupo']; ?>",
				"descontar": "<?php echo $reg['descontar']; ?>",
				"observaciones": "<?php echo $reg['observaciones']; ?>",
				"estado": "<?php echo $reg['estado']; ?>"
			});
			</script>
		<?php } ?>
		<script type="text/javascript">
		alasql('SELECT * INTO XLSX("descuentos-nutricion.xlsx",{headers:true}) FROM ?', [Export]);
		window.location="exportar.php";
		</script>
		<?php

		break;

		//GENERAR CONSULTA VALORACION CONSOLIDADA POR CONTRATO

		case "8":

		$contratos= mysql_query(("
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

		while($row=mysql_fetch_assoc($contratos)){
			$id_contrato[]=$row['id_contrato'];
		}


		for ($i=0; $i < count($id_contrato);$i++) {


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
					evaluacion.fecha_evaluacion <= '$fecha_final' and id_contrato='$id_contrato[$i]' and
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
						acta.id_contrato='$id_contrato[$i]' and
						acta.fecha_evaluacion <= '$fecha_final' and
						componente.id_componente='3' and
						acta.estado='0'
						group by acta.id_acta
						) as operacion"), $conexion);

						$porcentajes_nutricion = mysql_query(("
						SELECT id_contrato, nombre_componente, id_componente, promedio_componente_inicial, promedio_componente_final
						FROM $tabla_temp
						WHERE id_componente = 7 and id_contrato='$id_contrato[$i]'"),$conexion);

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
						descuentos_x_valoracion.id_contrato = '$id_contrato[$i]' and
						descuentos_x_valoracion.fecha <= '$fecha_final'
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
						descuentos_x_valoracion.id_contrato = '$id_contrato[$i]' and
						descuentos_x_valoracion.fecha <= '$fecha_final'
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
						WHERE id_componente = 7 and id_contrato='$id_contrato[$i]'"), $conexion);

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

						while ($reg = mysql_fetch_assoc($consulta_datos_temp)) {
							?>
							<script type="text/javascript">
							Export.push({

								"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
								"NOMBRE OFERENTE": "<?php echo $reg['nombre_prestador']; ?>",
								"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
								"FECHA DE CORTE": "<?php echo date("d/m/Y", strtotime($fecha_final)); ?>",
								"% CALIDAD": "<?php echo $reg['promedio_componente_inicial']*100; ?>",
								"% CUMPLIMIENTO": "<?php echo $reg['promedio_componente_final']*100; ?>"
							});
							</script>
							<?php
						}

						$delete_temp = mysql_query(("DROP TABLE $tabla_temp"), $conexion);
					}

					?>
					<script type="text/javascript">
					alasql('SELECT * INTO XLSX("valoracion.xlsx",{headers:true}) FROM ?', [Export]);
					window.location="exportar.php";
					</script>


					<?php

					break;

					// GENERAR SEM�FORO
					case "9":
					$separador=explode("-", $fecha_final);
					$id_mes=$separador[1];

					//Consulta  Semaforo
					$registros=mysql_query("
					SELECT
					semaforo.id_semaforo,
					tema.nombre_tema,
					semaforo.porc_calidad,
					semaforo.porc_deb_proc,
					semaforo.porc_descuento,
					semaforo.id_contrato,
					prestador.nombre_prestador,
					modalidad.nombre_modalidad,
					semaforo.descripcion_incumplimiento_grave,
					REPLACE(REPLACE(semaforo.logros_prestador,CHAR(10),''),CHAR(13),'') as logros,
					REPLACE(REPLACE(semaforo.dificultades_prestador,CHAR(10),''),CHAR(13),'') as dificultades,
					REPLACE(REPLACE(semaforo.debido_proceso,CHAR(10),''),CHAR(13),'') as debido_proceso1,
					REPLACE(REPLACE(semaforo.asistencia_tecnica,CHAR(10),''),CHAR(13),'') as asistencia_tecnica1,
					REPLACE(REPLACE(semaforo.otras_observaciones,CHAR(10),''),CHAR(13),'') as otras_observaciones1,
					semaforo.id_interventor
					FROM
					semaforo, prestador, modalidad, tema
					WHERE
					semaforo.id_prestador=prestador.id_prestador and
					semaforo.id_modalidad=modalidad.id_modalidad and
					semaforo.id_componente='$id_componente' and
					semaforo.id_tema=tema.id_tema and
					semaforo.id_mes='$id_mes'

					ORDER BY
					semaforo.id_contrato
					",$conexion)

					//Construcci�n tabla en Excel
					or die("Problemas en el select:".mysql_error());
					?>

					<?php
					while ($reg=mysql_fetch_array($registros))
					{
						?>
						<script type="text/javascript">
						Export.push({
							"#": "<?php echo $reg['id_semaforo']; ?>",
							"TEMA": "<?php echo $reg['nombre_tema']; ?>",
							"% CUMPLIMIENTO": "<?php echo $reg['porc_calidad']; ?>",
							"% DEBIDO PROCESO": "<?php echo $reg['porc_deb_proc']; ?>",
							"% DESCUENTO": "<?php echo $reg['porc_descuento']; ?>",
							"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
							"PRESTADOR": "<?php echo $reg['nombre_prestador']; ?>",
							"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
							"INCUMPLIMIENTO GRAVE": "<?php echo $reg['descripcion_incumplimiento_grave']; ?>",
							"LOGROS": "<?php echo $reg['logros']; ?>",
							"DIFICULTADES": "<?php echo $reg['dificultades']; ?>",
							"DEBIDO PROCESO": "<?php echo $reg['debido_proceso1']; ?>",
							"ASISTENCIA TECNICA": "<?php echo $reg['asistencia_tecnica1']; ?>",
							"OTRAS OBSERVACIONES": "<?php echo $reg['otras_observaciones1']; ?>",
							"INTERVENTOR": "<?php echo $reg['id_interventor']; ?>"
						});
						</script>
					<?php } ?>
					<script type="text/javascript">
					alasql('SELECT * INTO XLSX("semaforo.xlsx",{headers:true}) FROM ?', [Export]);
					window.location="exportar.php";
					</script>
					<?php
					break;

					// GENERAR HALLAZGOS
					case "10":
					$separador=explode("-", $fecha_final);
					$id_mes=$separador[1];

					//Consulta  Semaforo
					$registros=mysql_query("
					SELECT
					REPLACE(REPLACE(s.id_acta,CHAR(10),''),CHAR(13),'') as id_acta,
					e.fecha_evaluacion,
					e.id_tema,
					tema.nombre_tema,
					e.id_subtema,
					subtema.nombre_subtema,
					REPLACE(REPLACE(s.descripcion_pregunta,CHAR(10),''),CHAR(13),'') as descripcion_pregunta,
					REPLACE(REPLACE(s.descripcion_observacion,CHAR(10),''),CHAR(13),'') as descripcion_observacion,
					REPLACE(REPLACE(s.descripcion_accion_correctiva,CHAR(10),''),CHAR(13),'') as descripcion_accion_correctiva,
					componente.nombre_componente,
					s.id_contrato,
					prestador.nombre_prestador,
					modalidad.nombre_modalidad,
					sede.nombre_sede,
					s.fecha_envio_evidencia,
					s.etapa
					FROM
					subsanacion s,
					evaluacion e,
					tema,
					componente,
					subtema,
					modalidad,
					sede,
					prestador
					where
					s.id_componente='$id_componente' and
					s.id_acta=e.id_acta and
					s.id_componente=componente.id_componente and
					s.id_prestador=prestador.id_prestador and
					s.id_modalidad=modalidad.id_modalidad and
					s.id_sede=sede.id_sede and
					s.id_pregunta=e.id_pregunta and
					e.id_tema=tema.id_tema and
					e.id_subtema=subtema.id_subtema and
					e.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final'
					",$conexion)

					//Construcci�n tabla en Excel
					or die("Problemas en el select:".mysql_error());
					?>

					<?php
					while ($reg=mysql_fetch_array($registros))
					{
						?>
						<script type="text/javascript">
						Export.push({
							"ACTA": "<?php echo $reg['id_acta']; ?>",
							"FECHA EVALUACION": "<?php echo $reg['fecha_evaluacion']; ?>",
							"TEMA": "<?php echo $reg['id_tema']; ?>",
							"NOMBRE TEMA": "<?php echo $reg['nombre_tema']; ?>",
							"ID ESTANDAR": "<?php echo $reg['id_subtema']; ?>",
							"NOMBRE ESTANDAR": "<?php echo $reg['nombre_subtema']; ?>",
							"PREGUNTA": "<?php echo $reg['descripcion_pregunta']; ?>",
							"HALLAZGO": "<?php echo	str_replace('"', " ", $reg['descripcion_observacion']);?>",
							"ACCIÓN CORRECTIVA": "<?php echo	str_replace('"', " ", $reg['descripcion_accion_correctiva']);?>",
							"COMPONENTE": "<?php echo $reg['nombre_componente']; ?>",
							"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
							"PRESTADOR": "<?php echo $reg['nombre_prestador']; ?>",
							"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
							"SEDE": "<?php echo $reg['nombre_sede']; ?>",
							"FECHA ENVÍO EVIDENCIAS": "<?php echo $reg['fecha_envio_evidencia']; ?>",
							"ETAPA": "<?php echo $reg['etapa']; ?>"
						});
						</script>
					<?php } ?>
					<script type="text/javascript">
					alasql('SELECT * INTO XLSX("Hallazgos.xlsx",{headers:true}) FROM ?', [Export]);
					window.location="exportar.php";
					</script>
					<?php
					break;

					//DESCUENTOS DE NUTRICIÓN
					case "11":

					if ($id_componente == 7) {
						$registros = mysql_query("
						select
						promedio.id_contrato,
						round(promedio.promedio_calidad / promedio.porc_referencia,4) as promedio_calidad,
						round(promedio.promedio_cumplimiento / promedio.porc_referencia,4) as promedio_cumplimiento,
						promedio.fecha_de_corte,
						promedio.nombre_prestador,
						promedio.nombre_componente,
						promedio.id_tema
						FROM(
							SELECT
							operacion.id_contrato,
							operacion.fecha_de_corte,
							operacion.nombre_prestador,
							operacion.nombre_componente,
							sum(porcentaje_inicial) promedio_calidad,
							sum(porcentaje_final) promedio_cumplimiento,
							sum(porc_referencia) porc_referencia,
							operacion.id_tema
							FROM
							(
								SELECT
								acta.id_contrato,
								sum(evaluacion.porc_inicial)porcentaje_inicial,
								sum(evaluacion.porc_final) porcentaje_final,
								sum(evaluacion.porc_referencia) porc_referencia,
								'$fecha_final' as fecha_de_corte,
								prestador.nombre_prestador,
								componente.nombre_componente,
								evaluacion.id_tema
								FROM
								acta, evaluacion, prestador,tema, componente
								WHERE
								acta.id_prestador = prestador.id_prestador and
								evaluacion.id_componente='$id_componente' and
								evaluacion.id_tema = '701' and
								evaluacion.id_tema= tema.id_tema and
								evaluacion.estado='1' and
								evaluacion.fecha_evaluacion<='$fecha_final' and
								acta.id_acta = evaluacion.id_acta and
								evaluacion.id_componente = componente.id_componente
								group by evaluacion.id_subtema, evaluacion.id_acta
								) as operacion
								group by operacion.id_contrato
								order by operacion.id_contrato) as promedio", $conexion);

								while ($reg=mysql_fetch_array($registros)) {
									if ($id_componente == 7) {
										$id_contrato =  $reg['id_contrato'];
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
										descuentos_x_valoracion.fecha <='$fecha_final'
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
										descuentos_x_valoracion.id_contrato = '$id_contrato'
										ORDER BY prestador.nombre_prestador"), $conexion);

										//Cálculo calidad
										$reg['promedio_calidad'] = $reg['promedio_calidad'] * 100;
										$reg['promedio_cumplimiento'] = $reg['promedio_cumplimiento'] * 100;
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
										$descuento = round($descuento,4);
										$reg['promedio_calidad'] = $reg['promedio_calidad'] - $descuento;

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
										$descuento1 = round($descuento1,4);
										$reg['promedio_cumplimiento'] = $reg['promedio_cumplimiento'] + $descuento1;
										if ($reg['promedio_cumplimiento'] > 100) {
											$reg['promedio_cumplimiento'] = 100;
										}
									}

									?>
									<script type="text/javascript">
									Export.push({
										"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
										"COMPONENTE": "<?php echo $reg['nombre_componente']; ?>",
										"NOMBRE OFERENTE": "<?php echo $reg['nombre_prestador']; ?>",
										"FECHA CORTE": "<?php echo date("d/m/Y", strtotime($reg['fecha_de_corte'])); ?>",
										"% CALIDAD": "<?php echo $reg['promedio_calidad']; ?>",
										"% CUMPLIMIENTO": "<?php echo $reg['promedio_cumplimiento']; ?>"
									});
									</script>
								<?php } ?>
								<script type="text/javascript">
								alasql('SELECT * INTO XLSX("Valoración x Componente.xlsx",{headers:true}) FROM ?', [Export]);
								window.location="exportar.php";
								</script>
								<?php
							}else {
								$tema_x_componente = mysql_query(("
								SELECT
								id_tema,
								nombre_tema
								FROM
								tema
								WHERE id_componente = '$id_componente'"), $conexion);

								$id_contrato1 = []; $promedio_calidad1 = []; $promedio_cumplimiento1 = []; $fecha_de_corte1 = []; $nombre_prestador1 = []; $nombre_componente1 = [];
								$id_contrato2 = []; $promedio_calidad2 = []; $promedio_cumplimiento2 = []; $fecha_de_corte2 = []; $nombre_prestador2 = []; $nombre_componente2 = [];
								$id_contrato3 = []; $promedio_calidad3 = []; $promedio_cumplimiento3 = []; $fecha_de_corte3 = []; $nombre_prestador3 = []; $nombre_componente3 = [];
								$contador = 1;
								$contador2 = 0;
								$nombre_tema = [];

								while ($reg_temas=mysql_fetch_array($tema_x_componente)) {
									$id_tema = $reg_temas["id_tema"];
									$nombre_tema[] = $reg_temas["nombre_tema"];
									$registros = mysql_query("
									select
									promedio.id_contrato,
									round(promedio.promedio_calidad / promedio.porc_referencia,4) as promedio_calidad,
									round(promedio.promedio_cumplimiento / promedio.porc_referencia,4) as promedio_cumplimiento,
									promedio.fecha_de_corte,
									promedio.nombre_prestador,
									promedio.nombre_componente,
									promedio.id_tema
									FROM(
										SELECT
										operacion.id_contrato,
										operacion.fecha_de_corte,
										operacion.nombre_prestador,
										operacion.nombre_componente,
										sum(porcentaje_inicial) promedio_calidad,
										sum(porcentaje_final) promedio_cumplimiento,
										sum(porc_referencia) porc_referencia,
										operacion.id_tema
										FROM
										(
											SELECT
											acta.id_contrato,
											sum(evaluacion.porc_inicial)porcentaje_inicial,
											sum(evaluacion.porc_final) porcentaje_final,
											sum(evaluacion.porc_referencia) porc_referencia,
											'$fecha_final' as fecha_de_corte,
											prestador.nombre_prestador,
											componente.nombre_componente,
											evaluacion.id_tema
											FROM
											acta, evaluacion, prestador,tema, componente
											WHERE
											acta.id_prestador = prestador.id_prestador and
											evaluacion.id_componente='$id_componente' and
											evaluacion.id_tema = '$id_tema' and
											evaluacion.id_tema= tema.id_tema and
											evaluacion.estado='1' and
											evaluacion.fecha_evaluacion<='$fecha_final' and
											acta.id_acta = evaluacion.id_acta and
											evaluacion.id_componente = componente.id_componente
											group by evaluacion.id_subtema, evaluacion.id_acta
											) as operacion
											group by operacion.id_contrato
											order by operacion.id_contrato) as promedio", $conexion);

											while ($reg=mysql_fetch_array($registros)) {
												if ($contador == 1) {
													$id_contrato1[] = $reg["id_contrato"];
													$promedio_calidad1[] = $reg["promedio_calidad"];
													$promedio_cumplimiento1[] = $reg["promedio_cumplimiento"];
													$fecha_de_corte1[] = $reg["fecha_de_corte"] ;
													$nombre_prestador1[] = $reg["nombre_prestador"];
													$nombre_componente1[] = $reg["nombre_componente"];
												}elseif ($contador == 2) {
													$id_contrato2[] = $reg["id_contrato"];
													$promedio_calidad2[] = $reg["promedio_calidad"];
													$promedio_cumplimiento2[] = $reg["promedio_cumplimiento"];
													$fecha_de_corte2[] = $reg["fecha_de_corte"] ;
													$nombre_prestador2[] = $reg["nombre_prestador"];
													$nombre_componente2[] = $reg["nombre_componente"];
												}elseif ($contador == 3) {
													$id_contrato3[] = $reg["id_contrato"];
													$promedio_calidad3[] = $reg["promedio_calidad"];
													$promedio_cumplimiento3[] = $reg["promedio_cumplimiento"];
													$fecha_de_corte3[] = $reg["fecha_de_corte"] ;
													$nombre_prestador3[] = $reg["nombre_prestador"];
													$nombre_componente3[] = $reg["nombre_componente"];
												}
											}

											$contador++;
											$contador2++;
										}

										for ($i=0; $i < count($id_contrato1);$i++) {
											$id_contrato = $id_contrato1[$i];
											$nombre_prestador = $nombre_prestador1[$i];
											$nombre_componente = $nombre_componente1[$i];
											$fecha_de_corte = $fecha_de_corte1[0];

											if ($contador2 == 1) {
												$calidad1 = $promedio_calidad1[$i];
												$cumplimiento1 = $promedio_cumplimiento1[$i];
												?>
												<script type="text/javascript">
												Export.push({
													"CONTRATO": "<?php echo $id_contrato; ?>",
													"COMPONENTE": "<?php echo $nombre_componente; ?>",
													"NOMBRE OFERENTE": "<?php echo $nombre_prestador; ?>",
													"FECHA CORTE": "<?php echo date("d/m/Y", strtotime($fecha_de_corte)); ?>",
													"% CALIDAD <?php echo $nombre_tema[0]; ?>": "<?php echo $calidad1*100; ?>",
													"% CUMPLIMIENTO <?php echo $nombre_tema[0]; ?>": "<?php echo $cumplimiento1*100; ?>"
												});
												</script>
												<?php
											}elseif ($contador2 == 2) {
												$calidad1 = $promedio_calidad1[$i];
												$cumplimiento1 = $promedio_cumplimiento1[$i];
												$calidad2 = $promedio_calidad2[$i];
												$cumplimiento2 = $promedio_cumplimiento2[$i];
												?>
												<script type="text/javascript">
												Export.push({
													"CONTRATO": "<?php echo $id_contrato; ?>",
													"COMPONENTE": "<?php echo $nombre_componente; ?>",
													"NOMBRE OFERENTE": "<?php echo $nombre_prestador; ?>",
													"FECHA CORTE": "<?php echo date("d/m/Y", strtotime($fecha_de_corte)); ?>",
													"% CALIDAD <?php echo $nombre_tema[0]; ?>": "<?php echo $calidad1*100; ?>",
													"% CUMPLIMIENTO <?php echo $nombre_tema[0]; ?>": "<?php echo $cumplimiento1*100; ?>",
													"% CALIDAD <?php echo $nombre_tema[1]; ?>": "<?php echo $calidad2*100; ?>",
													"% CUMPLIMIENTO <?php echo $nombre_tema[1]; ?>": "<?php echo $cumplimiento2*100; ?>",
												});
												</script>
												<?php
											}elseif ($contador2 == 3) {
												$calidad1 = $promedio_calidad1[$i];
												$cumplimiento1 = $promedio_cumplimiento1[$i];
												$calidad2 = $promedio_calidad2[$i];
												$cumplimiento2 = $promedio_cumplimiento2[$i];
												$calidad3 = $promedio_calidad3[$i];
												$cumplimiento3 = $promedio_cumplimiento3[$i];
												?>
												<script type="text/javascript">
												Export.push({
													"CONTRATO": "<?php echo $id_contrato; ?>",
													"COMPONENTE": "<?php echo $nombre_componente; ?>",
													"NOMBRE OFERENTE": "<?php echo $nombre_prestador; ?>",
													"FECHA CORTE": "<?php echo date("d/m/Y", strtotime($fecha_de_corte)); ?>",
													"% CALIDAD <?php echo $nombre_tema[0]; ?>": "<?php echo $calidad1*100; ?>",
													"% CUMPLIMIENTO <?php echo $nombre_tema[0]; ?>": "<?php echo $cumplimiento1*100; ?>",
													"% CALIDAD <?php echo $nombre_tema[1]; ?>": "<?php echo $calidad2*100; ?>",
													"% CUMPLIMIENTO <?php echo $nombre_tema[1]; ?>": "<?php echo $cumplimiento2*100; ?>",
													"% CALIDAD <?php echo $nombre_tema[2]; ?>": "<?php echo $calidad3*100; ?>",
													"% CUMPLIMIENTO <?php echo $nombre_tema[2]; ?>": "<?php echo $cumplimiento3*100; ?>",
												});
												</script>
												<?php
											}

										}

										?>
										<script type="text/javascript">
										alasql('SELECT * INTO XLSX("Valoración x Componente.xlsx",{headers:true}) FROM ?', [Export]);
										window.location="exportar.php";
										</script>
										<?php
									}


									break;

									case "12":

									include("clsInforme.php");
									$registros = mysql_query("
									SELECT id_contrato
									FROM descuentos_x_valoracion
									WHERE fecha BETWEEN '$fecha_inicial' and '$fecha_final'
									GROUP BY id_contrato", $conexion);
									while ($reg=mysql_fetch_array($registros)) {

										$id_contrato = $reg["id_contrato"];
										$registros1 = mysql_query("
										SELECT descuentos_x_valoracion.id_contrato, tipo_descuento.id as id_tipodescuento, tipo_descuento.tipo_descuento, prestador.nombre_prestador
										FROM descuentos_x_valoracion, prestador,tipo_descuento, detalle_tipo_descuento
										WHERE descuentos_x_valoracion.tipo_descuento = detalle_tipo_descuento.id and
										detalle_tipo_descuento.tipo_descuento = tipo_descuento.id and
										descuentos_x_valoracion.id_prestador = prestador.id_prestador and
										fecha BETWEEN '$fecha_inicial' and '$fecha_final' and
										descuentos_x_valoracion.id_contrato = '$id_contrato'
										group by tipo_descuento.id", $conexion);

										if (mysql_num_rows($registros1) > 0) {
											$tipo_descuento=[];
											$contrato=[] ;
											$nombre_prestador=[];
											while ($reg1=mysql_fetch_array($registros1)) {
												$contrato[] = $reg1["id_contrato"];
												$tipo_descuento[] = $reg1["id_tipodescuento"];
												$nombre_prestador[] = $reg1["nombre_prestador"];
											}


											$infocontrato = new InfoContrato;
											for ($i=0; $i < count($tipo_descuento);$i++) {

												$infocontrato->id_contrato = $contrato[$i];
												$infocontrato->nombre_prestador = $nombre_prestador[$i];

												if ($tipo_descuento[$i] == 1) {
													$infocontrato->ic1 = "x";
												}
												if ($tipo_descuento[$i] == 2) {
													$infocontrato->ic2 = "x";
												}
												if ($tipo_descuento[$i] == 3) {
													$infocontrato->rnp = "x";
												}
												if ($tipo_descuento[$i] == 4) {
													$infocontrato->rp = "x";
												}
												if ($tipo_descuento[$i] == 5) {
													$infocontrato->eta = "x";
												}
												if ($tipo_descuento[$i] == 6) {
													$infocontrato->iv1 = "x";
												}
												if ($tipo_descuento[$i] == 7) {
													$infocontrato->iv2 = "x";
												}
												if ($tipo_descuento[$i] == 8) {
													$infocontrato->soc1 = "x";
												}
												if ($tipo_descuento[$i] == 9) {
													$infocontrato->soc2 = "x";
												}
												if ($tipo_descuento[$i] == 10) {
													$infocontrato->pt1 = "x";
												}
												if ($tipo_descuento[$i] == 11) {
													$infocontrato->pt2 = "x";
												}
												if ($tipo_descuento[$i] == 12) {
													$infocontrato->pt3 = "x";
												}
												if ($tipo_descuento[$i] == 13) {
													$infocontrato->pt4 = "x";
												}
											}
										}


										// while ($reg1=mysql_fetch_array($registros1)) {
										// 	$ic1 = " "; $ic2 = " "; $rnp = " "; $rp = " "; $eta = " "; $iv1 = " ";
										// 	$iv2 = " "; $soc1 = " "; $soc2 = " "; $pt1 = " "; $pt2 = " "; $pt3 = " "; $pt4 = " ";
										// 	if ($reg1["id_tipodescuento"] == 1) {
										// 		$ic1 = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 2) {
										// 		$ic2 = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 3) {
										// 		$rnp = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 4) {
										// 		$rp = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 5) {
										// 		$eta = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 6) {
										// 		$iv1 = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 7) {
										// 		$iv2 = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 8) {
										// 		$soc1 = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 9) {
										// 		$soc2 = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 10) {
										// 		$pt1 = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 11) {
										// 		$pt2 = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 12) {
										// 		$pt3 = "x";
										// 	}
										// 	if ($reg1["id_tipodescuento"] == 13) {
										// 		$pt4 = "x";
										// 	}
										?>
										<script type="text/javascript">
										var id_contrato = <?php echo $id_contrato; ?>;
										var contrato = Export.filter(function (item) {
											return item.CONTRATO == id_contrato;
										});

										Export.push({
											"CONTRATO": "<?php echo $infocontrato->id_contrato; ?>",
											"PRESTADOR": "<?php echo $infocontrato->nombre_prestador; ?>",
											"1 INFORME DE CONSUMO": "<?php echo $infocontrato->ic1; ?>",
											"2 INFORME DE CONSUMO": "<?php echo $infocontrato->ic2; ?>",
											"RASTREO NO PATOGENO": "<?php echo $infocontrato->rnp; ?>",
											"RASTREO PATOGENO": "<?php echo $infocontrato->rp; ?>",
											"ETA": "<?php echo $infocontrato->eta; ?>",
											"1 INFORME DE VIGILANCIA": "<?php echo $infocontrato->iv1; ?>",
											"2 INFORME DE VIGILANCIA": "<?php echo $infocontrato->iv2; ?>",
											"1 SOCIALIZACION": "<?php echo $infocontrato->soc1; ?>",
											"2 SOCIALIZACION": "<?php echo $infocontrato->soc2; ?>",
											"1 PESO Y TALLA": "<?php echo $infocontrato->pt1; ?>",
											"2 PESO Y TALLA": "<?php echo $infocontrato->pt2; ?>",
											"3 PESO Y TALLA": "<?php echo $infocontrato->pt3; ?>",
											"4 PESO Y TALLA": "<?php echo $infocontrato->pt4; ?>",
										});
										</script>
										<?php //}
									}?>
									<script type="text/javascript">
									alasql('SELECT * INTO XLSX("descuentos_x_valoracion.xlsx",{headers:true}) FROM ?', [Export]);
									//window.location="descuentosValoracion.php";
									window.close();
									</script>
									<?php
									break;


									case "13":
									$registros = mysql_query(("
									SELECT users.documento as documento,  users.first_name, users.last_name, componente.nombre_componente, gastos_desplazamiento.id_interventor as id_interventor,gastos_desplazamiento.id_acta,gastos_desplazamiento.fecha_evaluacion,  gastos_desplazamiento.pago_desplazamiento
									FROM gastos_desplazamiento, users, componente
									WHERE  gastos_desplazamiento.id_interventor =  users.username
									and gastos_desplazamiento.fecha_evaluacion BETWEEN '$fecha_inicial' and '$fecha_final'
									AND componente.id_componente = users.id_componente"), $conexion);

									while ($reg=mysql_fetch_array($registros)) {
										$id_acta = $reg["id_acta"];
										$barrios_visita = mysql_query(("
										SELECT sede.id_sede, sede.nombre_sede, sede.barrio_sede, sede.direccion_sede, sede.comuna
										FROM acta, sede
										WHERE acta.id_sede = sede.id_sede
										and acta.id_acta = '$id_acta'"), $conexion);
										$barrio_sede="";
										while ($row=mysql_fetch_array($barrios_visita)) {
											if ($row["barrio_sede"] != "") {
												$id_sede = $row["id_sede"];
												$nombre_sede = $row["nombre_sede"];
												$barrio_sede = $row["barrio_sede"];
												$direccion_sede = $row["direccion_sede"];
												$comuna = $row["comuna"];
											}
										}

										$consulta_contrato = mysql_query(("
										SELECT id_contrato
										FROM acta
										WHERE id_acta = '$id_acta'"),$conexion);
										$datos = mysql_fetch_array($consulta_contrato);
										$id_contrato = $datos["id_contrato"];

										$consulta_prestador = mysql_query(("
										SELECT prestador.nombre_prestador
										FROM acta, prestador
										WHERE
										acta.id_prestador = prestador.id_prestador
										and acta.id_acta = '$id_acta'"), $conexion);
										$datos_prestador = mysql_fetch_array($consulta_prestador);
										$nombre_prestador = $datos_prestador["nombre_prestador"];

										$consulta_modalidad = mysql_query(("
										SELECT modalidad.nombre_modalidad
										FROM acta, modalidad
										WHERE acta.id_modalidad = modalidad.id_modalidad
										and acta.id_acta = '$id_acta'"), $conexion);

										$datos_modalidad = mysql_fetch_array($consulta_modalidad);
										$nombre_modalidad = $datos_modalidad["nombre_modalidad"];

										?>
										<script type="text/javascript">
										Export.push({
											"Acta": "<?php echo $reg['id_acta']; ?>",
											"Fecha Evaluación": "<?php echo $reg['fecha_evaluacion']; ?>",
											"Nombre Componente": "<?php echo $reg['nombre_componente']; ?>",
											"id contrato": "<?php echo $id_contrato; ?>",
											"Nombre Prestador": "<?php echo $nombre_prestador; ?>",
											"Nombre Modalidad": "<?php echo $nombre_modalidad; ?>",
											"Id Sede": "<?php echo $id_sede; ?>",
											"Nombre Sede": "<?php echo $nombre_sede; ?>",
											"Número Visita": "1",
											"Id Interventor": "<?php echo $reg['id_interventor']; ?>",
											"Dirección Sede": "<?php echo $direccion_sede; ?>",
											"Barrio Sede": "<?php echo $barrio_sede; ?>",
											"Comuna": "<?php echo $comuna; ?>",
											"Cédula Contratista": "<?php echo $reg['documento']; ?>",
											"Componente": "<?php echo $reg['nombre_componente']; ?>",
											"Valor Visita": "<?php echo $reg['pago_desplazamiento']; ?>",
											"Valor Acumulado": "<?php echo $reg['pago_desplazamiento']; ?>",
											"Interventor": "<?php echo $reg['first_name'].' '.$reg['last_name']; ?>"

										});
										</script>
									<?php }?>

									<script type="text/javascript">
									alasql('SELECT * INTO XLSX("gastos desplazamiento.xlsx",{headers:true}) FROM ?', [Export]);
									window.location="homeadmin.php";
									</script>


									<?php
									break;

									// GENERAR HALLAZGOS FAMILIAR PARA NUTRICIÓN
									case "14":

									$registros1 = mysql_query(("
									SELECT
									evaluacion.id_acta,
									evaluacion.valor_calificacion,
									evaluacion.id_interventor,
									prestador.nombre_prestador,
									evaluacion.fecha_evaluacion,
									sede.nombre_sede,
									REPLACE(REPLACE(acta.nombre_asistentes,CHAR(10),''),CHAR(13),'') as nombre_asistentes,
									REPLACE(REPLACE(acta.informacion_complementaria,CHAR(10),''),CHAR(13),'') as informacion_complementaria,
									REPLACE(REPLACE(acta.tema_encuentro,CHAR(10),''),CHAR(13),'') as tema_encuentro
									FROM
									evaluacion, prestador, sede, acta
									WHERE
									evaluacion.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final' and
									evaluacion.id_componente = '$id_componente' and
									/*evaluacion.valor_calificacion= 4 and*/
									evaluacion.id_modalidad= 5 and
									prestador.id_prestador = evaluacion.id_prestador and
									evaluacion.id_sede = sede.id_sede and
									evaluacion.id_acta = acta.id_acta
									GROUP BY id_acta
									"),$conexion)
									or die("Problemas en el select:".mysql_error());

									while($row = mysql_fetch_array($registros1)){

										$id_acta = $row['id_acta'];
										$registros_interventor = mysql_query(("
										SELECT
										acta.fecha_evaluacion,
										acta.nombre_sede,
										acta.nombre_asistentes,
										acta.informacion_complementaria,
										acta.tema_encuentro,
										REPLACE(REPLACE(observacion_evaluador.descripcion_observacion_evaluador,CHAR(10),''),CHAR(13),'') as descripcion_observacion_evaluador
										FROM
										acta,  observacion_evaluador
										WHERE
										acta.id_acta=observacion_evaluador.id_acta and
										acta.id_acta='$id_acta'
										"),$conexion)
										or die("probelmas en query:".mysql_error());


										$observacion_evaluador = "";
										while($row3=mysql_fetch_array($registros_interventor)){
											$observacion_evaluador .= $row3["descripcion_observacion_evaluador"]."-";
										}

										$registros_prestador = mysql_query(("
										SELECT
										acta.fecha_evaluacion,
										acta.nombre_sede,
										acta.nombre_asistentes,
										acta.informacion_complementaria,
										acta.tema_encuentro,
										REPLACE(REPLACE(observacion_usuario.descripcion_observacion_usuario,CHAR(10),''),CHAR(13),'') as descripcion_observacion_usuario
										FROM
										acta, observacion_usuario
										WHERE
										acta.id_acta=observacion_usuario.id_acta and
										acta.id_acta='$id_acta'
										"),$conexion)
										or die("probelmas en query:".mysql_error());

										$observacion_usuario = "";
										while($row4=mysql_fetch_array($registros_prestador)){
											$observacion_usuario .= $row4["descripcion_observacion_usuario"]." -";
										}

										$registros2 = mysql_query(("
										SELECT
										REPLACE(REPLACE(descripcion_pregunta,CHAR(10),''),CHAR(13),'') as descripcion_pregunta,
										REPLACE(REPLACE(descripcion_observacion,CHAR(10),''),CHAR(13),'') as descripcion_observacion
										FROM
										subsanacion, evaluacion
										WHERE
										subsanacion.id_acta = evaluacion.id_acta and
										subsanacion.id_pregunta = evaluacion.id_pregunta AND
										/*evaluacion.valor_calificacion = 4 AND*/
										evaluacion.id_acta='$id_acta'
										"),$conexion)
										or die("probelmas en el select:".mysql_error());

										$descripcion_pregunta = "";
										$descripcion_observacion = "";

										while($row2=mysql_fetch_array($registros2)){
											$descripcion_pregunta .= $row2['descripcion_pregunta']."  -";
											$descripcion_observacion .= $row2['descripcion_observacion']."  -";
										}
										?>
										<script type="text/javascript">
										Export.push({
											"ACTA": "<?php echo $row['id_acta']; ?>",
											"PRESTADOR": "<?php echo $row['nombre_prestador']; ?>",
											"INTERVENTOR":"<?php echo $row['id_interventor'];?>",
											"FECHA VISITA": "<?php echo $row['fecha_evaluacion']; ?>",
											"SEDE": "<?php echo $row['nombre_sede']; ?>",
											"VARIABLE NS": "<?php echo	$descripcion_pregunta;?>",
											"OBSERVACION": "<?php echo	$descripcion_observacion;?>",
											"NUTRICIONISTA": "<?php echo $row['nombre_asistentes']; ?>",
											"INFORMACION DE PARTICIPANTES(NUIP, NOMBRES Y APELLIDOS, PESO 1, PESO 2, PESO PROMEDIO, TALLA 1, TALLA 2, TALLA PROMEDIO)": "<?php echo $row['informacion_complementaria']; ?>",
											"TEMA ENCUENTRO EDUCATIVO": "<?php echo	str_replace('"', " ", $row['tema_encuentro']);?>",
											"OBSERVACION INTERVENTOR": "<?php echo $observacion_evaluador; ?>",
											"OBSERVACION PRESTADOR": "<?php echo $observacion_usuario; ?>"
										});
										</script>
										<?php


									}

									/*$registros= mysql_query(("
									SELECT
									acta.id_acta,
									acta.fecha_evaluacion,
									prestador.nombre_prestador,
									acta.id_interventor,
									acta.nombre_sede,
									subsanacion.descripcion_pregunta,
									subsanacion.descripcion_observacion,
									acta.nombre_asistentes,
									acta.informacion_complementaria,
									REPLACE(REPLACE(observacion_usuario.descripcion_observacion_usuario,CHAR(10),''),CHAR(13),'') as descripcion_observacion_usuario,
									REPLACE(REPLACE(observacion_evaluador.descripcion_observacion_evaluador,CHAR(10),''),CHAR(13),'') as descripcion_observacion_evaluador,
									acta.tema_encuentro
									FROM
									subsanacion, evaluacion, acta, prestador, observacion_usuario, observacion_evaluador
									WHERE
									subsanacion.id_acta = acta.id_acta and
									subsanacion.id_modalidad= acta.id_modalidad and
									subsanacion.id_componente = '$id_componente'and
									evaluacion.id_acta = acta.id_acta and
									observacion_usuario.id_acta = acta.id_acta and
									observacion_evaluador.id_acta=acta.id_acta and
									acta.id_componente = '$id_componente' and
									acta.id_modalidad =  5 and
									acta.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final' and
									evaluacion.valor_calificacion = 4 and
									acta.id_prestador = prestador.id_prestador
									"),$conexion)

									//Construcción tabla en Excel
									or die("Problemas en el select:".mysql_error());*/



									?>
									<script type="text/javascript">
									alasql('SELECT * INTO XLSX("Hallazgos_Familiar.xlsx",{headers:true}) FROM ?', [Export]);
									window.location="exportar.php";
									</script>
									<?php
									break;

									case "15":

									$contratos = mysql_query(("
									SELECT contrato_x_sede.id_contrato,prestador.nombre_prestador
									FROM contrato_x_sede, prestador
									WHERE contrato_x_sede.id_prestador = prestador.id_prestador
									and contrato_x_sede.estado = 1 AND contrato_x_sede.id_contrato != 4600069314
									GROUP by contrato_x_sede.id_contrato"),$conexion);

									while ($reg=mysql_fetch_array($contratos)) {
										$id_contrato = $reg["id_contrato"];
										$sedes = mysql_query(("
										SELECT sede.id_sede, sede.nombre_sede
										FROM sede, contrato_x_sede
										WHERE sede.id_sede = contrato_x_sede.id_sede
										AND contrato_x_sede.estado AND contrato_x_sede.id_contrato = $id_contrato
										ORDER BY sede.id_sede"), $conexion);

										while ($reg=mysql_fetch_array($sedes)) {
											$id_sede = $reg["id_sede"];
											$nombre_sede = $reg["nombre_sede"];
											$registros_acta = mysql_query(("
											SELECT id_acta, numero_visita
											FROM acta
											WHERE id_sede = $id_sede and id_componente = $id_componente"), $conexion);
											$visita1 = "";
											$visita2 = "";
											$visita3 = "";
											$visita4 = "";
											while ($datos_acta=mysql_fetch_array($registros_acta)) {
												$numero_visita = $datos_acta["numero_visita"];
												if ($numero_visita == 1) {
													$visita1 = "x";
												}elseif ($numero_visita == 2) {
													$visita2 = "x";
												}elseif ($numero_visita == 3) {
													$visita3 = "x";
												}elseif ($numero_visita == 4) {
													$visita4 = "x";
												}
											}
											if (isset($registros_acta)) {
												?>
												<script type="text/javascript">
												Export.push({
													"CONTRATO": "<?php echo $id_contrato; ?>",
													"ID SEDE": "<?php echo $id_sede; ?>",
													"NOMBRE SEDE": "<?php echo $nombre_sede; ?>",
													"VISITA 1": "<?php echo $visita1; ?>",
													"VISITA 2": "<?php echo $visita2; ?>",
													"VISITA 3": "<?php echo $visita3; ?>",
													"VISITA 4": "<?php echo $visita4; ?>",
												});
												</script>
												<?php
											}
										}
									}
									?>
									<script type="text/javascript">
									alasql('SELECT * INTO XLSX("Visitas Sede.xlsx",{headers:true}) FROM ?', [Export]);
									window.location="exportar.php";
									</script>
									<?php
									break;

									case "16":
									$registros = mysql_query(("
									SELECT descuentos_x_valoracion.id_prestador,
									descuentos_x_valoracion.id_contrato,
									prestador.nombre_prestador,
									descuentos_x_valoracion.id_sede,
									tipo_descuento.tipo_descuento,
									descuentos_x_valoracion.id_radicado_osa,
									descuentos_x_valoracion.id_radicado_orq,
									descuentos_x_valoracion.fecha,
									modalidad.nombre_modalidad
									FROM descuentos_x_valoracion, tipo_descuento, detalle_tipo_descuento, prestador, modalidad
									WHERE descuentos_x_valoracion.tipo_descuento = detalle_tipo_descuento.id and
									detalle_tipo_descuento.tipo_descuento = tipo_descuento.id and
									descuentos_x_valoracion.id_prestador = prestador.id_prestador and
									descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
									fecha BETWEEN '$fecha_inicial' and '$fecha_final'
									ORDER BY fecha"), $conexion);

									while ($reg=mysql_fetch_array($registros))
									{
										$nombre_prestador = $reg['nombre_prestador'];
										?>
										<script type="text/javascript">
										Export.push({
											"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
											"PRESTADOR": "<?php echo $reg['nombre_prestador']; ?>",
											"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
											"NOMBRE SEDE": "<?php if($reg['id_sede'] == "0"){ echo "";}else{ echo $reg['id_sede'];} ?>",
											"TIPO DESCUENTO": "<?php echo $reg['tipo_descuento']; ?>",
											"RADICADO OSA": "<?php if($reg['id_radicado_osa'] == "0"){ echo "";}else{ echo $reg['id_radicado_osa'];} ?>",
											"RADICADO REG": "<?php if($reg['id_radicado_orq'] == "0"){ echo "";}else{ echo $reg['id_radicado_orq'];} ?>",
											"FECHA DESCUENTO": "<?php echo $reg['fecha']; ?>"
										});
										</script>
									<?php } ?>
									<script type="text/javascript">
									alasql('SELECT * INTO XLSX("descuentos_sedes.xlsx",{headers:true}) FROM ?', [Export]);
									window.location="descuentosValoracion.php";
									</script>
									<?php
									break;

									case "17":

									$actas_visita = mysql_query(("SELECT * FROM acta WHERE fecha_evaluacion BETWEEN '$fecha_inicial' and '$fecha_final'"),$conexion);


									break;


								} //End Case


							} //End If login

							else {
								header('Location: index.php');

							}

							?>
