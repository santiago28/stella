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


//CASOS PARA GENERAR REPORTE
//caso 1: Visitas realizadas
//caso 2: Valoraciones x estandar
//caso 3: Seguimiento debido proceso
//caso 4: Calificación de variables
//caso 5: Proveedores - Valoracion x Estandar



switch ($caso) {
case "1":

//Consulta  Visitas realizadas
	$registros=mysql_query("
	SELECT
	a.id,
	a.id_acta,
	a.fecha_evaluacion,
	c.nombre_componente,
	cs.id_contrato,
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
	LEFT JOIN acta a ON (a.id_sede=s.id_sede AND a.id_contrato=cs.id_contrato and a.id_componente='$id_componente' AND a.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final' )
	LEFT JOIN modalidad m ON (cs.id_modalidad=m.id_modalidad)
	LEFT JOIN componente c ON (a.id_componente=c.id_componente)
    LEFT JOIN prestador p ON (cs.id_prestador=p.id_prestador)
	WHERE cs.id_modalidad IN (1,5,6,7,11)
	",$conexion)
//
//Construcción tabla en Excel
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
			"FECHA": "<?php echo $reg['fecha_evaluacion']; ?>",
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
			"ESTADO": "<?php echo $reg['estado']; ?>"
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
round(sum(porc_final)/sum(porc_referencia),4) porcentaje_estandar_final
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

//Construcción tabla en Excel
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
				"FECHA EVALUACION": "<?php echo $reg['fecha_evaluacion']; ?>",
				"VISITA": "<?php echo $reg['numero_visita']; ?>",
				"CATEGORIA": "<?php echo $reg['nombre_tema']; ?>",
				"ID-ESTANDAR": "<?php echo $reg['id_subtema']; ?>",
				"NOMBRE ESTANDAR": "<?php echo $reg['nombre_subtema']; ?>",
				"% INICIAL": "<?php echo $reg['porcentaje_estandar_inicial']*100; ?>",
				"% FINAL": "<?php echo $reg['porcentaje_estandar_final']*100; ?>",
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

//Construcción tabla en Excel
	or die("Problemas en el select:".mysql_error());
?>

<?php
while ($reg=mysql_fetch_array($registros))
{
?>
		<script type="text/javascript">
				Export.push({
					"ACTA": "<?php echo $reg['id_acta']; ?>",
					"FECHA": "<?php echo $reg['fecha_evaluacion']; ?>",
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

//Consulta  Calificación de Variables
	mysql_query("SET NAMES utf8");
	$registros=mysql_query("

	SELECT
	evaluacion.id_contrato,
	evaluacion.id_acta,
	acta.fecha_evaluacion,
	tema.nombre_tema,
	modalidad.nombre_modalidad,
	evaluacion.id_sede,
	sede.nombre_sede,
	subtema.nombre_subtema,
	evaluacion.id_pregunta,
	pregunta.descripcion_pregunta,
	evaluacion.valor_calificacion,
	evaluacion.valor_calificacion_final
	FROM
	evaluacion,sede,pregunta,modalidad,subtema,acta, tema
	WHERE
	evaluacion.id_modalidad=modalidad.id_modalidad AND
	evaluacion.id_sede=sede.id_sede and
	acta.id_acta=evaluacion.id_acta and
	evaluacion.id_subtema = subtema.id_subtema and
	evaluacion.id_tema=tema.id_tema and
	evaluacion.id_pregunta=pregunta.id_pregunta and
	evaluacion.id_componente='$id_componente' and
	evaluacion.fecha_evaluacion BETWEEN '$fecha_inicial' AND '$fecha_final'
	",$conexion)

//Construcción tabla en Excel
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
					"FECHA EVALUACION": "<?php echo $reg['fecha_evaluacion']; ?>",
					"COMPONENTE": "<?php echo $reg['nombre_tema']; ?>",
					"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
					"ID SEDE": "<?php echo $reg['id_sede']; ?>",
					"NOMBRE SEDE": "<?php echo $reg['nombre_sede']; ?>",
					"ESTANDAR": "<?php echo $reg['nombre_subtema']; ?>",
					"ID PREGUNTA": "<?php echo $reg['id_pregunta']; ?>",
					"DESCRIPCIÓN PREGUNTA": "<?php echo $reg['descripcion_pregunta']; ?>",
					"CALIFICACIÓN INICIA": "<?php echo $reg['valor_calificacion']; ?>",
					"CALIFICACIÓN FINAL": "<?php echo $reg['valor_calificacion_final']; ?>"
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

//Construcción tabla en Excel
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
					"FECHA EVALUACION": "<?php echo $reg['fecha_evaluacion']; ?>",
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

//Construcción tabla en Excel
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
					"FECHA": "<?php echo $reg['fecha_evaluacion']; ?>",
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
					THEN "Inasistencia de niños" END as descripcion1,
			CASE d.unidad
				WHEN 1
					THEN "Gramos"
				WHEN 2
					THEN "Centimetros Cúbicos"
				WHEN 3
					THEN "Unidad"
				WHEN 4
					THEN "Niños faltantes" END as unidad1,
			d.alimento,	d.detallealimento,	d.faltante,	d.grupo,	d.descontar,	d.observaciones,	d.estado
		FROM descuentos d
		LEFT JOIN acta a ON (a.id_acta=d.id_acta)
		LEFT JOIN prestador p ON (p.id_prestador=a.id_prestador)
		LEFT JOIN modalidad m ON (m.id_modalidad=a.id_modalidad)
		left JOIN sede s ON (s.id_sede=a.id_sede)
		WHERE d.estado = 3 AND (fecha_evaluacion >="'.$fecha_inicial.'" AND fecha_evaluacion <= "'.$fecha_final.'");
		', $conexion)
	//Construcción tabla en Excel
	or die("Problemas en el select:".mysql_error());
?>

<?php $i=0;
while ($reg=mysql_fetch_array($registros))
{
?>
	<script type="text/javascript">
			Export.push({
					"#": "<?php echo $i++; ?>",
					"fecha_evaluacion": "<?php echo $reg['fecha_evaluacion']; ?>",
					"id_acta": "<?php echo $reg['id_acta']; ?>",
					"id_contrato": "<?php echo $reg['id_contrato']; ?>",
					"nombre_prestador": "<?php echo $reg['nombre_prestador']; ?>",
					"abr_modalidad": "<?php echo $reg['abr_modalidad']; ?>",
					"nombre_sede": "<?php echo $reg['nombre_sede']; ?>",
					"id_interventor": "<?php echo $reg['id_interventor']; ?>",
					"numero_visita": "<?php echo $reg['numero_visita']; ?>",
					"id": "<?php echo $reg['id']; ?>",
					"id_acta": "<?php echo $reg['id_acta']; ?>",
					"fecha_acta": "<?php echo $reg['fecha_acta']; ?>",
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

 //Consulta  valoracion consolidada
	$registros=mysql_query("
				SELECT
				operacion.id_contrato,
				operacion.nombre_prestador,
				operacion.nombre_modalidad,
				operacion.fecha_de_corte,
				round(sum(operacion.porc_componente_x_inicial)/sum(operacion.porc_componente_x_modalidad),4) promedio_componente_inicial,
				round(sum(operacion.porc_componente_x_final)/sum(operacion.porc_componente_x_modalidad),4) promedio_componente_final
				FROM
				(
					SELECT
					a.id_contrato,
    				p.nombre_prestador,
    				m.nombre_modalidad,
					'$fecha_final' as fecha_de_corte,
					a.porc_inicial,
					a.porc_final,
					a.porc_componente_x_modalidad,
					porc_inicial*porc_componente_x_modalidad porc_componente_x_inicial,
					porc_final*porc_componente_x_modalidad porc_componente_x_final
					FROM
					acta a, prestador p, modalidad m
					WHERE
    				a.id_prestador=p.id_prestador and a.id_modalidad=m.id_modalidad and
					fecha_evaluacion<='$fecha_final'
				) as operacion
				group by operacion.id_contrato
				order by operacion.id_contrato
	",$conexion)

//Construcción tabla en Excel
	or die("Problemas en el select:".mysql_error());
?>

<?php
while ($reg=mysql_fetch_array($registros))
{
?>
	<script type="text/javascript">
			Export.push({
					"CONTRATO": "<?php echo $reg['id_contrato']; ?>",
					"NOMBRE OFERENTE": "<?php echo $reg['nombre_prestador']; ?>",
					"MODALIDAD": "<?php echo $reg['nombre_modalidad']; ?>",
					"FECHA DE CORTE": "<?php echo $reg['fecha_de_corte']; ?>",
					"% CALIDAD": "<?php echo $reg['promedio_componente_inicial']; ?>",
					"% CUMPLIMIENTO": "<?php echo $reg['promedio_componente_final']; ?>",
			});
	</script>
<?php } ?>
<script type="text/javascript">
	alasql('SELECT * INTO XLSX("valoracion.xlsx",{headers:true}) FROM ?', [Export]);
	window.location="exportar.php";
</script>
<?php

break;

// GENERAR SEMÁFORO
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

//Construcción tabla en Excel
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

	} //End Case


} //End If login

else {
	header('Location: index.php');

}

?>
