<?php
include("../conexion.php");

$id_acta=array(
'AVI-01-201700094',

);




//RECALCULAR DE NUEVO LOS VALORES PORCENTUALES DE EVALUACION PARA EL ACTA

for ($i=0; $i < count($id_acta);$i++)
{

//Inicializacion de variables
unset($id_evaluacion);
unset($porc_referencia);
unset($porc_inicial);
unset($porc_final);
unset($porc_componente_x_final);

$id_evaluacion=array();
$porc_referencia=array();
$porc_inicial=array();
$porc_final=array();
$porc_componente_x_final=array();

$suma_porc_referencia=0;
$suma_porc_inicial=0;
$suma_porc_final=0;
$suma_porc_componente_x_final=0;

			//Query para hallar la suma de porcentajes
			$queryporcentaje= mysql_query(("
			SELECT
			promedio.id_evaluacion,
			promedio.id_pregunta,
			promedio.id_subtema,
			promedio.referencia,
			promedio.valor_calificacion,
			promedio.valor_calificacion_final,
			promedio.porc_referencia,
			round(sum(promedio.porc_inicial) /  sum(promedio.porc_referencia),4) as porc_inicial,
			round(sum(promedio.porc_final) /  sum(promedio.porc_referencia),4) as porc_final,
			round(sum(promedio.porc_componente_x_final),4) as porc_componente_x_final
			FROM(
			SELECT
			evaluacion.id_acta as id_acta,
			evaluacion.id_evaluacion as id_evaluacion,
			evaluacion.id_pregunta as id_pregunta,
			evaluacion.id_subtema as id_subtema,
			evaluacion.valor_referencia as referencia,
			evaluacion.valor_calificacion as valor_calificacion,
			evaluacion.valor_calificacion_final as valor_calificacion_final,
			operacion.porcentaje_x_pregunta,
			operacion.porc_componente_x_modalidad,
			round(porcentaje_x_pregunta*valor_referencia,4) porc_referencia,
			round(if(evaluacion.valor_calificacion=1,porcentaje_x_pregunta,if(evaluacion.valor_calificacion=2,porcentaje_x_pregunta*0.6,0)),4) porc_inicial,
			round(if(evaluacion.valor_calificacion_final=1,porcentaje_x_pregunta,if(evaluacion.valor_calificacion_final=2,porcentaje_x_pregunta*0.6,0)),4) porc_final,
			round((if(evaluacion.valor_calificacion_final=1,porcentaje_x_pregunta,if(evaluacion.valor_calificacion_final=2,porcentaje_x_pregunta*0.6,0)))*operacion.porc_componente_x_modalidad,4) porc_componente_x_final

			FROM
			(
				SELECT
				evaluacion.id_subtema,
				componente_x_modalidad.porc_componente_x_modalidad,
				estandar_x_modalidad.porc_estandar_x_modalidad,
				count(evaluacion.id_pregunta) cantidad_preguntas,
				(estandar_x_modalidad.porc_estandar_x_modalidad/count(evaluacion.id_pregunta)) porcentaje_x_pregunta
				FROM
				evaluacion,estandar_x_modalidad,componente_x_modalidad
				WHERE
				evaluacion.id_subtema=estandar_x_modalidad.id_subtema and
				evaluacion.id_tema=estandar_x_modalidad.id_tema and
				evaluacion.id_componente=estandar_x_modalidad.id_componente and
				evaluacion.id_modalidad=estandar_x_modalidad.id_modalidad and
				evaluacion.id_tema=componente_x_modalidad.id_tema and
				evaluacion.id_modalidad=componente_x_modalidad.id_modalidad and
				evaluacion.id_componente=componente_x_modalidad.id_componente and
				evaluacion.id_acta='$id_acta[$i]' and
				evaluacion.estado='1'
				group by id_subtema
				) as operacion

				RIGHT JOIN evaluacion ON evaluacion.id_subtema= operacion.id_subtema

				WHERE
				evaluacion.id_acta='$id_acta[$i]'

				) as promedio
				WHERE
				promedio.id_acta='$id_acta[$i]'
				group by id_subtema
				"),$conexion);

			while($row=mysql_fetch_assoc($queryporcentaje)){
				$id_evaluacion[]=$row['id_evaluacion'];
				$porc_referencia[]=$row['porc_referencia'];
				$porc_inicial[]=$row['porc_inicial'];
				$porc_final[]=$row['porc_final'];
				$porc_componente_x_final[]=$row['porc_componente_x_final'];
			}
				$suma_porc_referencia=array_sum($porc_referencia);
				$suma_porc_inicial=array_sum($porc_inicial)/$suma_porc_referencia;
				$suma_porc_final=array_sum($porc_final)/$suma_porc_referencia;
				$suma_porc_componente_x_final=array_sum($porc_componente_x_final)/$suma_porc_referencia;


		//Update: Actualizar porcentajes en la tabla evaluación

			for ($j=0; $j < count($id_evaluacion);$j++)
			{
				$update_porcentaje_evaluacion = mysql_query("
				UPDATE evaluacion
				SET
				porc_referencia='$porc_referencia[$j]',
				porc_inicial='$porc_inicial[$j]',
				porc_final='$porc_final[$j]',
				porc_componente_x_final='$porc_componente_x_final[$j]'
				where id_evaluacion='$id_evaluacion[$j]'
				");
				$updateporc= mysql_query($update_porcentaje_evaluacion,$conexion);
			} //End For


		//Update: Actualizar porcentajes de evaluacion de tabla acta

			$update_porcentaje_acta = mysql_query("
			UPDATE acta
			SET
			porc_referencia='$suma_porc_referencia',
			porc_inicial='$suma_porc_inicial',
			porc_final='$suma_porc_final',
			porc_componente_x_final='$suma_porc_componente_x_final'
			where id_acta='$id_acta[$i]'
			");
			$updateporc= mysql_query($update_porcentaje_acta,$conexion);

print "ACTA ".$id_acta[$i]." MODIFICADA"."<br>";
} //End FOR id_acta


?>
