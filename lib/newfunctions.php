<?php session_start();
if ($_SESSION['login'])
{

	require_once ("../conexion.php");
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$nombre=$_SESSION["nombre_usuario"];

	$caso = htmlentities(@$_POST['caso']);
	$acta = htmlentities(@$_POST['id_acta']);
	$accion = htmlentities($_POST['accion']);

	//if (isset($_POST['guardar'])) {
	function historico(){
		global $acta, $conexion, $id_contrato;
		if ($_POST['contrato'] != "") {
			$id_contrato = htmlentities($_POST['contrato']);
		}
		$id_acta = $acta;
		$id_pregunta = htmlentities($_POST['preg']);
		$valor_ant = htmlentities($_POST['respini']);
		$valor_nue = htmlentities($_POST['respfin']);
		$fechanueva = htmlentities($_POST['fechaacta']);
		$motivo = htmlentities($_POST['motivo']);
		$accion = htmlentities($_POST['accion']);
		$interventor = htmlentities($_POST['interventor']);
		$aprobado = "";
		$ejecutado = "";
		$usuario = $_SESSION['login'];
		$sqlguardar = "INSERT INTO hist_cambios(id_contrato, id_acta, id_pregunta, valor_ant, valor_nue, fechanueva, motivo, accion, interventor, aprobado, ejecutado, usuario) ";
		$sqlguardar .= "VALUES ('$id_contrato', '$id_acta', '$id_pregunta', '$valor_ant', '$valor_nue', '$fechanueva', '$motivo', '$accion', '$interventor', '$aprobado', '$ejecutado', '$usuario');";
		$resguardar = mysql_query($sqlguardar,$conexion) OR DIE(mysql_error());
	}
	//} else {
	switch(	$accion ) {
		case "Listar":
		$sqlacta = "SELECT id_acta, id_contrato FROM acta WHERE id_acta LIKE '".$acta."';";
		$resacta = mysql_query($sqlacta,$conexion);
		$existeacta = mysql_num_rows($resacta);
		if ($existeacta > 0) {
			echo "correcto;";
			$row = mysql_fetch_row($resacta);
			echo $row[1].";";
		} else {
			echo $acta;
		}
		mysql_free_result($resacta);
		break;

		case "Eliminar": // acción = Eliminar
		$interventor = htmlentities($_POST['interventor']);
		$sqleliminar = mysql_query("DELETE FROM acta WHERE id_acta='$acta' AND id_interventor = '$interventor'; ",$conexion);
		$sqleliminar .= mysql_query("DELETE FROM evaluacion WHERE id_acta='$acta' AND id_interventor = '$interventor'; ",$conexion);
		$sqleliminar .= mysql_query("DELETE FROM observacion_evaluador WHERE id_acta='$acta' AND id_interventor = '$interventor'; ",$conexion);
		$sqleliminar .= mysql_query("DELETE FROM observacion_usuario WHERE id_acta='$acta'; ",$conexion);
		$sqleliminar .= mysql_query("DELETE FROM subsanacion WHERE id_acta='$acta' AND id_interventor = '$interventor'; ",$conexion);
		$sqleliminar .= mysql_query("DELETE FROM gastos_desplazamiento WHERE id_acta='$acta'", $conexion);
		$sqleliminar .= mysql_query("UPDATE reserva_radicado SET estado='1' WHERE id_acta='$acta' AND id_interventor = '$interventor'; ",$conexion);
		//$reseliminar = mysql_query($sqleliminar, $conexion);
		if (!$sqleliminar) {
			historico();
			//echo $sqleliminar."<br>";
			//die(mysql_error());
			header('location: ../corregirActas.php?msg=2');
		} else {
			historico();
			header('location: ../corregirActas.php?msg=1&acta=$acta');
		}
		break;

		case "Cambiar":
		$califini = htmlentities($_POST['respini']);
		$califfin = htmlspecialchars($_POST['respfin']);
		$sqlacta = "SELECT * FROM acta WHERE id_acta LIKE '".$acta."';";
		$resacta = mysql_query($sqlacta, $conexion);
		$rowacta = mysql_fetch_assoc($resacta);
		$id_componente=htmlentities($_POST['id_componente']);
		$id_tema=htmlentities($_POST['tema']);
		$id_subtema=htmlentities($_POST['subtema']);
		$id_pregunta=htmlentities($_POST['preg']);
		$calificacion=$califfin;
		$id_acta=$acta;
		$id_contrato=$rowacta['id_contrato'];
		$id_sede=$rowacta['id_sede'];
		$id_prestador=$rowacta['id_prestador'];
		$id_modalidad=$rowacta['id_modalidad'];
		$id_interventor=$rowacta['id_interventor'];
		$numero_visita=$rowacta['numero_visita'];;
		$fecha_evaluacion=$rowacta['fecha_evaluacion'];

		$sqlpregunta = "SELECT descripcion_pregunta FROM pregunta WHERE id_pregunta = $id_pregunta";
		$respregunta = mysql_query($sqlpregunta,$conexion);
		$pregunta = mysql_fetch_row($respregunta);
		$nombre_pregunta = $pregunta[0];

		if ($califini == 1) { // 1. Cumple ->
			if ($califfin == 2 || $califfin == 3 || $califfin == 4){
				$sqlcambio = "UPDATE evaluacion
				SET valor_calificacion = '$calificacion', valor_calificacion_final = '$calificacion'
				WHERE id_acta = '$id_acta' AND id_pregunta = '$id_pregunta' AND id_componente = '$id_componente' AND id_interventor = '$id_interventor';";
				$rescambio = mysql_query($sqlcambio,$conexion);
				echo $sqlcambio = "REPLACE INTO subsanacion (id_acta, id_pregunta, descripcion_pregunta, id_componente, id_contrato, id_prestador, id_modalidad, id_sede, id_interventor, fecha_subsanacion, etapa, historico, estado) VALUES ('$id_acta', $id_pregunta, '$nombre_pregunta', $id_componente, '$id_contrato', '$id_prestador', $id_modalidad, $id_sede, '$id_interventor', '$fecha_evaluacion','AC','AC','1');";
				$rescambio = mysql_query($sqlcambio,$conexion);
			} else if($califfin == 5) {
				$sqlcambio="DELETE FROM evaluacion WHERE id_acta = '$id_acta' AND id_pregunta = '$id_pregunta' AND id_componente = '$id_componente' AND id_interventor = '$id_interventor';";
				$rescambio = mysql_query($sqlcambio, $conexion);
			}
			if (!$rescambio) {
				historico();
				header('location: ../corregirActas.php?msg=2');
			} else {
				historico();
				header('location: ../corregirActas.php?msg=1&acta='.$acta);
			}
		} else if ($califini == 5) { // 5. No Aplica ->
			/* Insertar registro en la tabla "evaluacion" */;
			$sqlcambio1="REPLACE INTO evaluacion (id_componente,id_tema,id_subtema,id_pregunta,valor_referencia,valor_calificacion,valor_calificacion_final,id_acta,id_contrato,id_sede,id_prestador,id_modalidad,id_interventor,numero_visita,fecha_evaluacion,estado) VALUES ($id_componente,$id_tema,$id_subtema,$id_pregunta,'1',$calificacion,$calificacion,'$id_acta',$id_contrato,$id_sede,$id_prestador,$id_modalidad,'$id_interventor',$numero_visita,'$fecha_evaluacion','1');";
			$rescambio1 = mysql_query($sqlcambio1, $conexion);
			if($califfin != 1) { // de 5. No Aplica -> 1. Cumple
				/* Insertar Hallazgo en la tabla 'subsanacion' */
				$sqlcambio2="REPLACE INTO subsanacion (id_acta, id_pregunta, descripcion_pregunta, id_componente, id_contrato, id_prestador, id_modalidad, id_sede, id_interventor, etapa, historico, estado) VALUES ('$id_acta', $id_pregunta,'$nombre_pregunta', $id_componente, '$id_contrato', '$id_prestador', $id_modalidad, $id_sede, '$id_interventor','AC','AC','1');";
				$rescambio2 = mysql_query($sqlcambio2, $conexion);
			}
			if (!$rescambio1) {
				historico();
				header('location: ../corregirActas.php?msg=2');
			} else {
				historico();
				header('location: ../corregirActas.php?msg=1&acta=$acta');
			}
		} else {
			$sqlcambio = "UPDATE evaluacion SET valor_calificacion = $calificacion, valor_calificacion_final = $calificacion WHERE id_acta = '$id_acta' AND id_pregunta = $id_pregunta AND id_componente = $id_componente AND id_interventor = '$id_interventor';";
			$rescambio = mysql_query($sqlcambio, $conexion);

			$sqlcambio2="UPDATE subsanacion SET etapa='AC', historico='AC', estado=1 WHERE id_acta = '$id_acta' AND id_pregunta = $id_pregunta AND id_componente = $id_componente AND id_interventor = '$id_interventor'";
			if ($califfin == 1 || $califfin == 5) {
				$sqlborrado1 = "DELETE FROM evaluacion WHERE id_acta = '$id_acta' AND id_pregunta = $id_pregunta AND id_componente = $id_componente AND id_interventor = '$id_interventor';";
				$resborrado1 = mysql_query($sqlborrado1, $conexion);
				$sqlborrado2 = "DELETE FROM subsanacion WHERE id_acta = '$id_acta' AND id_pregunta = $id_pregunta AND id_componente = $id_componente AND id_interventor = '$id_interventor';";
				$resborrado2 = mysql_query($sqlborrado2, $conexion);
			}

			//RECALCULAR DE NUEVO LOS VALORES PORCENTUALES DE EVALUACION PARA EL ACTA
			//Query para hallar la suma de porcentajes
			$queryporcentaje= mysql_query(("
			SELECT
			evaluacion.id_evaluacion,
			evaluacion.id_pregunta,
			evaluacion.id_subtema,
			evaluacion.valor_referencia,
			evaluacion.valor_calificacion,
			evaluacion.valor_calificacion_final,
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
				evaluacion.id_acta='$id_acta' and
				evaluacion.estado='1'
				group by id_subtema
				) as operacion

				RIGHT JOIN evaluacion ON evaluacion.id_subtema= operacion.id_subtema

				WHERE
				evaluacion.id_acta='$id_acta'
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
				where id_acta='$id_acta'
				");
				$updateporc= mysql_query($update_porcentaje_acta,$conexion);

				if (!$rescambio) {
					historico();
					header('location: ../corregirActas.php?msg=2');
				} else {
					historico();
					header('location: ../corregirActas.php?msg=1&acta=$acta');
				}
			}
			break;

			case "Habilitar":
			$acta = htmlspecialchars($_POST['id_acta']);
			$interventor = htmlentities($_POST['interventor']);
			$sqlcambio = "UPDATE acta SET estado = 1 WHERE id_acta = '$acta' AND id_interventor = '$interventor';";
			$rescambio = mysql_query($sqlcambio, $conexion);
			$sqlacta = "SELECT * FROM acta WHERE id_acta LIKE '".$acta."';";
			$resacta = mysql_query($sqlacta, $conexion);
			$rowacta = mysql_fetch_assoc($resacta);
			$id_contrato=$rowacta['id_contrato'];
			if (!$rescambio) {
				historico();
				header('location: ../corregirActas.php?msg=2');
			} else {
				historico();
				header('location: ../corregirActas.php?msg=1&acta=$acta');
			}
			break;

			case "Fecha": //Cambio de fecha
			$acta = htmlspecialchars($_POST['id_acta']);
			$fechanueva = htmlentities($_POST['fechaacta']);
			$interventor = htmlentities($_POST['interventor']);
			$sqlcambio1 = "UPDATE acta SET fecha_evaluacion = '$fechanueva' WHERE id_acta = '$acta' AND id_interventor= '$interventor';";
			$rescambio1 = mysql_query($sqlcambio1, $conexion);
			$sqlcambio2 = "UPDATE evaluacion SET fecha_evaluacion = '$fechanueva' WHERE id_acta = '$acta' AND id_interventor= '$interventor';";
			$rescambio2 = mysql_query($sqlcambio2, $conexion);
			$sqlcambio = "UPDATE acta SET estado = 1 WHERE id_acta = '$acta' AND id_interventor = '$interventor';";
			$rescambio = mysql_query($sqlcambio, $conexion);
			$sqlacta = "SELECT * FROM acta WHERE id_acta LIKE '".$acta."';";
			$resacta = mysql_query($sqlacta, $conexion);
			$rowacta = mysql_fetch_assoc($resacta);
			$id_contrato=$rowacta['id_contrato'];
			if (!$rescambio1) {
				historico();
				header('location: ../corregirActas.php?msg=2');
			} else {
				historico();
				header('location: ../corregirActas.php?msg=1&acta='.$acta.'&sql='.$sqlcambio1);
			}
			break;

			case "borrar"; //borra las observaciones del interventor o del prestador
			$id_observacion = htmlentities($_POST['id']);
			$aux = htmlentities($_POST['tabla']);
			$contrato = htmlentities($_POST['contrato']);
			$acta = htmlentities($_POST['acta']);
			$interventor = htmlentities($_POST['interventor']);
			$usuario = htmlentities($_POST['usuario']);

			if ($aux == "e") {
				$tabla = "observacion_evaluador";
				$campo = "id_observacion_evaluador";
			} else if ($aux == "u") {
				$tabla = "observacion_usuario";
				$campo = "id_observacion_usuario";
			}
			$sqlhist="INSERT INTO hist_cambios(id_contrato, id_acta, accion, interventor,aprobado, ejecutado, usuario) VALUES ('$contrato', '$acta', 'Borrar - $tabla', '$interventor', '$usuario', 'Ejecutado', '$usuario');";
			$reshist = mysql_query($sqlhist,$conexion) OR DIE(mysql_error());
			$sqlcambio = "DELETE FROM $tabla WHERE  $campo = $id_observacion;";

			$rescambio = mysql_query($sqlcambio, $conexion);
			if (!$rescambio) {
				return true;
			} else {
				return false;
			}
			break;

			// Función para guardar los folios de Salud
			case "folios":
			$total = htmlentities($_POST['total']);
			$acta = htmlentities($_POST['acta']);
			$interventor = htmlentities($_POST['interventor']);
			// $tv = htmlentities($_POST['tv']);
			// $sd = htmlentities($_POST['sd']);
			// $sr = htmlentities($_POST['sr']);
			// $sv = htmlentities($_POST['sv']);
			$fd = htmlentities($_POST['fd']);
			$fr = htmlentities($_POST['fr']);
			$fv = htmlentities($_POST['fv']);
			$cd = htmlentities($_POST['cd']);
			$cr = htmlentities($_POST['cr']);
			$cv = htmlentities($_POST['cv']);
			// $vd = htmlentities($_POST['vd']);
			// $vr = htmlentities($_POST['vr']);
			// $vv = htmlentities($_POST['vv']);
			$sqlfolios = "REPLACE INTO folios_salud (id_acta, total, fosyga_doc, fosyga_ruta, fosyga_sinv, cyd_doc, cyd_ruta, cyd_sinv,  id_interventor) ";
			$sqlfolios .= "VALUES('$acta', $total, $fd, $fr, $fv, $cd, $cr, $cv, '$interventor');";
			echo $sqlfolios;
			$resfolios = mysql_query($sqlfolios, $conexion);
			// if (!$resfolios) {
			// 	return true;
			// } else {
			// 	return false;
			// }
			break;

			case "Modalidad":
			$componente = htmlentities($_POST['componente']);
			$sqlmodalidad = "SELECT cm.id_componente, cm.id_modalidad, m.nombre_modalidad   FROM `componente_x_modalidad` cm LEFT JOIN modalidad m ON (cm.id_modalidad=m.id_modalidad) WHERE `id_componente` = $componente GROUP BY id_modalidad ORDER BY `cm`.`id_modalidad` ASC";
			$resmodalidad = mysql_query($sqlmodalidad, $conexion);
			if (mysql_num_rows($resmodalidad)>0) {
				echo '<option value="">Seleccione una Modalidad</option>';
				while ($modalidad = mysql_fetch_array($resmodalidad)) {
					echo '<option value="'.$modalidad['id_modalidad'].'">'.$modalidad['nombre_modalidad'].'</option>';
				}
			} else {
				echo '<option value="">Error no hay modalidad relacionada</option>';
			}
			break;

			case "compXmodal":
			$componente = htmlentities($_POST['componente']);
			$modalidad = htmlentities($_POST['modalidad']);
			$sqlcm = "SELECT c.nombre_componente, cs.id_contrato, p.nombre_prestador, m.nombre_modalidad, cs.id_sede, s.nombre_sede, a.id_interventor, COUNT(a.id_acta) AS total FROM contrato_x_sede cs LEFT JOIN sede s ON (s.id_sede=cs.id_sede) LEFT JOIN acta a ON (a.id_sede=cs.id_sede and a.id_contrato = cs.id_contrato) LEFT JOIN modalidad m ON (cs.id_modalidad=m.id_modalidad) LEFT JOIN componente c ON (a.id_componente=c.id_componente) LEFT JOIN prestador p ON (cs.id_prestador=p.id_prestador) WHERE cs.id_modalidad = ".$modalidad." AND (c.id_componente = ".$componente." OR c.id_componente IS NULL) GROUP BY cs.id_sede, a.id_componente, cs.id_modalidad, cs.id_contrato ORDER BY cs.id_contrato DESC";
			$rescm = mysql_query($sqlcm, $conexion);
			if (true) {
				echo '<thead><tr>
				<th bgcolor="#09227E"><font color="#ffffff">No.</th>
				<th bgcolor="#09227E"><font color="#ffffff">Componente</th>
				<th bgcolor="#09227E"><font color="#ffffff">Contrato</th>
				<th bgcolor="#09227E"><font color="#ffffff">Prestador</th>
				<th bgcolor="#09227E"><font color="#ffffff">Modalidad</th>
				<th bgcolor="#09227E"><font color="#ffffff">Sede</th>
				<th bgcolor="#09227E"><font color="#ffffff">Sede</th>
				<th bgcolor="#09227E"><font color="#ffffff">Interventor</th>
				<th bgcolor="#09227E"><font color="#ffffff">Total de Actas</th>
				</tr></thead><tbody>';
				$i=0;
				while ($cxm = mysql_fetch_array($rescm)) {
					echo '<tr><td>'.$i++.'</td>';
					echo '<td>'.$cxm['nombre_componente'].'</tb>';
					echo '<td>'.$cxm['id_contrato'].'</td>';
					echo '<td>'.$cxm['nombre_prestador'].'</td>';
					echo '<td>'.$cxm['nombre_modalidad'].'</td>';
					echo '<td>'.$cxm['id_sede'].'</tb>';
					echo '<td>'.$cxm['nombre_sede'].'</tb>';
					echo '<td>'.$cxm['id_interventor'].'</tb>';
					echo '<td class="">'.$cxm['total'].'</tb></<tr>';
				}
				echo "</tbody>";
			} else {
				echo '<tr><td colspan=10>Error no hay registros</td></tr>';
			}
			break;

		} // end case
		//} // fin guardar o ejecutar

	} else {
		echo "<script>window.location='index.php';</script>";
	}


	?>
