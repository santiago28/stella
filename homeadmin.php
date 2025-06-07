<?php
session_start();
if ($_SESSION['login'])
{
	include "conexion.php";
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$nombre=$_SESSION["nombre_usuario"];
	$username = $_SESSION['login'];
	$fotoperfil = $_SESSION["fotoperfil"];

	if (isset($_GET["id_componente"])) {
		$id_componenteS=$_GET["id_componente"];
	}


	$hoy=date("Y-m-d");

	$interventores = mysql_query(("
	SELECT username
	FROM users
	WHERE id_componente = '$id_componente'
	AND id_group = 2 "), $conexion);

	$componentes = mysql_query(("
	SELECT
	id_componente,
	nombre_componente
	FROM componente
	where id_componente in (1,2,4,5,7,8,9)"), $conexion);

	if (isset($_POST["consultas"])) {
		$consultas = $_POST["consultas"];
		if ($consultas == "Ajustes") {
			if (isset($_POST["id_mes"]) && $_POST["id_mes"] != 0 && isset($_POST["id_interventor_consulta"]) && $_POST["id_interventor_consulta"] != "0" && $id_grupo == 3) {
				$id_mes = $_POST["id_mes"];
				$id_interventor_consulta = $_POST["id_interventor_consulta"];
				$month = $id_mes;
				$year = 2020;
				$day = date("d", mktime(0,0,0, $month+1, 0, $year));
				$fechaInicial = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
				$fechaFinal = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
				$actas_ajuste = mysql_query(("
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta.fecha_evaluacion) AS MES,
				MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
				acta.id_acta,
				acta.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta, gastos_desplazamiento
				WHERE acta.id_acta = gastos_desplazamiento.id_acta and
				acta.fecha_evaluacion <= now() and
				acta.id_interventor = '$id_interventor_consulta' and
				acta.id_componente = '$id_componente' and
				acta.fecha_evaluacion <='$fechaFinal' and acta.fecha_evaluacion >='$fechaInicial'
				and gastos_desplazamiento.pago_desplazamiento > 6000
				GROUP BY acta.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_proveedor.fecha_evaluacion) AS MES,
				MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
				acta_proveedor.id_acta,
				acta_proveedor.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_proveedor, gastos_desplazamiento
				WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
				acta_proveedor.fecha_evaluacion <= now() and
				acta_proveedor.id_interventor = '$id_interventor_consulta' and
				acta_proveedor.id_componente = '$id_componente' and
				acta_proveedor.fecha_evaluacion <='$fechaFinal' and acta_proveedor.fecha_evaluacion >='$fechaInicial'
				and gastos_desplazamiento.pago_desplazamiento > 6000
				GROUP BY acta_proveedor.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_fallida.fecha_evaluacion) AS MES,
				MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
				acta_fallida.id_acta,
				acta_fallida.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_fallida, gastos_desplazamiento
				WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
				acta_fallida.fecha_evaluacion <= now() and
				acta_fallida.id_interventor = '$id_interventor_consulta' and
				acta_fallida.id_componente = '$id_componente' and
				acta_fallida.fecha_evaluacion <='$fechaFinal' and acta_fallida.fecha_evaluacion >='$fechaInicial'
				and gastos_desplazamiento.pago_desplazamiento > 6000
				GROUP BY acta_fallida.id_acta
				ORDER BY MES ASC)
				"),$conexion);
				?>
				<script type="text/javascript">
				setTimeout(function(){
					$("#tab1").removeClass("active");
					$("#tab1default").removeClass("in active");
					$("#gastos").addClass("active");
					$("#tab3default").addClass("in active");
				},100);
				</script>
				<?php

			} elseif (isset($_POST["id_interventor_consulta"]) && $_POST["id_interventor_consulta"] != "0" && $id_grupo == 3) {
				$id_interventor_consulta = $_POST["id_interventor_consulta"];
				$actas_ajuste = mysql_query(("
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta.fecha_evaluacion) AS MES,
				MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
				acta.id_acta,
				acta.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta, gastos_desplazamiento
				WHERE acta.id_acta = gastos_desplazamiento.id_acta and
				acta.fecha_evaluacion <= now() and
				acta.id_interventor = '$id_interventor_consulta'
				and gastos_desplazamiento.pago_desplazamiento > 6000
				GROUP BY acta.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_proveedor.fecha_evaluacion) AS MES,
				MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
				acta_proveedor.id_acta,
				acta_proveedor.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_proveedor, gastos_desplazamiento
				WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
				acta_proveedor.fecha_evaluacion <= now() and
				acta_proveedor.id_interventor = '$id_interventor_consulta'
				GROUP BY acta_proveedor.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_fallida.fecha_evaluacion) AS MES,
				MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
				acta_fallida.id_acta,
				acta_fallida.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_fallida, gastos_desplazamiento
				WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
				acta_fallida.fecha_evaluacion <= now() and
				acta_fallida.id_interventor = '$id_interventor_consulta'
				GROUP BY acta_fallida.id_acta
				ORDER BY MES ASC)
				"),$conexion);
				?>
				<script type="text/javascript">
				setTimeout(function(){
					$("#tab1").removeClass("active");
					$("#tab1default").removeClass("in active");
					$("#gastos").addClass("active");
					$("#tab3default").addClass("in active");
				},100);
				</script>
				<?php
			} elseif (isset($_POST["id_mes"]) && $_POST["id_mes"] != 0) {
				$id_mes = $_POST["id_mes"];
				$month = $id_mes;
				$year = 2020;
				$day = date("d", mktime(0,0,0, $month+1, 0, $year));
				$fechaInicial = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
				$fechaFinal = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
				if ($id_grupo == 3) {
					$actas_ajuste = mysql_query(("
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta.fecha_evaluacion) AS MES,
					MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
					acta.id_acta,
					acta.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta, gastos_desplazamiento
					WHERE acta.id_acta = gastos_desplazamiento.id_acta and
					acta.fecha_evaluacion <= now() and
					acta.id_componente = '$id_componente' and
					acta.fecha_evaluacion <='$fechaFinal' and acta.fecha_evaluacion >='$fechaInicial'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta.id_acta
					ORDER BY MES ASC)
					UNION ALL
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta_proveedor.fecha_evaluacion) AS MES,
					MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
					acta_proveedor.id_acta,
					acta_proveedor.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta_proveedor, gastos_desplazamiento
					WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
					acta_proveedor.fecha_evaluacion <= now() and
					acta_proveedor.id_componente = '$id_componente' and
					acta_proveedor.fecha_evaluacion <='$fechaFinal' and acta_proveedor.fecha_evaluacion >='$fechaInicial'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta_proveedor.id_acta
					ORDER BY MES ASC)
					UNION ALL
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta_fallida.fecha_evaluacion) AS MES,
					MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
					acta_fallida.id_acta,
					acta_fallida.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta_fallida, gastos_desplazamiento
					WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
					acta_fallida.fecha_evaluacion <= now() and
					acta_fallida.id_componente = '$id_componente' and
					acta_fallida.fecha_evaluacion <='$fechaFinal' and acta_fallida.fecha_evaluacion >='$fechaInicial'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta_fallida.id_acta
					ORDER BY MES ASC)
					"),$conexion);
					?>
					<script type="text/javascript">
					setTimeout(function(){
						$("#tab1").removeClass("active");
						$("#tab1default").removeClass("in active");
						$("#gastos").addClass("active");
						$("#tab3default").addClass("in active");
					},100);
					</script>
					<?php
				}elseif ($id_grupo == 2) {
					$actas_ajuste = mysql_query(("
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta.fecha_evaluacion) AS MES,
					MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
					acta.id_acta,
					acta.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta, gastos_desplazamiento
					WHERE acta.id_acta = gastos_desplazamiento.id_acta and
					acta.fecha_evaluacion <= now() and
					acta.id_interventor = '$username' and
					acta.fecha_evaluacion <='$fechaFinal' and acta.fecha_evaluacion >='$fechaInicial'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta.id_acta
					ORDER BY MES ASC)
					UNION ALL
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta_proveedor.fecha_evaluacion) AS MES,
					MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
					acta_proveedor.id_acta,
					acta_proveedor.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta_proveedor, gastos_desplazamiento
					WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
					acta_proveedor.fecha_evaluacion <= now() and
					acta_proveedor.id_interventor = '$username' and
					acta_proveedor.fecha_evaluacion <='$fechaFinal' and acta_proveedor.fecha_evaluacion >='$fechaInicial'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta_proveedor.id_acta
					ORDER BY MES ASC)
					UNION ALL
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta_fallida.fecha_evaluacion) AS MES,
					MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
					acta_fallida.id_acta,
					acta_fallida.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta_fallida, gastos_desplazamiento
					WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
					acta_fallida.fecha_evaluacion <= now() and
					acta_fallida.id_interventor = '$username' and
					acta_fallida.fecha_evaluacion <='$fechaFinal' and acta_fallida.fecha_evaluacion >='$fechaInicial'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta_fallida.id_acta
					ORDER BY MES ASC)
					"),$conexion);
					?>
					<script type="text/javascript">
					setTimeout(function(){
						$("#tab1").removeClass("active");
						$("#tab1default").removeClass("in active");
						$("#gastos").addClass("active");
						$("#tab3default").addClass("in active");
					},100);
					</script>
					<?php
				}
			}else {
				if ($id_grupo == 3) {
					$actas_ajuste = mysql_query(("
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta.fecha_evaluacion) AS MES,
					MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
					acta.id_acta,
					acta.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta, gastos_desplazamiento
					WHERE acta.id_acta = gastos_desplazamiento.id_acta and
					acta.fecha_evaluacion <= now() and
					acta.id_componente = '$id_componente'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta.id_acta
					ORDER BY MES ASC)
					UNION ALL
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta_proveedor.fecha_evaluacion) AS MES,
					MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
					acta_proveedor.id_acta,
					acta_proveedor.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta_proveedor, gastos_desplazamiento
					WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
					acta_proveedor.fecha_evaluacion <= now() and
					acta_proveedor.id_componente = '$id_componente'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta_proveedor.id_acta
					ORDER BY MES ASC)
					UNION ALL
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta_fallida.fecha_evaluacion) AS MES,
					MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
					acta_fallida.id_acta,
					acta_fallida.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta_fallida, gastos_desplazamiento
					WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
					acta_fallida.fecha_evaluacion <= now() and
					acta_fallida.id_componente = '$id_componente'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta_fallida.id_acta
					ORDER BY MES ASC)
					"),$conexion);
				}elseif ($id_grupo == 2) {
					$actas_ajuste = mysql_query(("
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta.fecha_evaluacion) AS MES,
					MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
					acta.id_acta,
					acta.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta, gastos_desplazamiento
					WHERE acta.id_acta = gastos_desplazamiento.id_acta and
					acta.fecha_evaluacion <= now() and
					acta.id_interventor = '$username'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta.id_acta
					ORDER BY MES ASC)
					UNION ALL
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta_proveedor.fecha_evaluacion) AS MES,
					MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
					acta_proveedor.id_acta,
					acta_proveedor.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta_proveedor, gastos_desplazamiento
					WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
					acta_proveedor.fecha_evaluacion <= now() and
					acta_proveedor.id_interventor = '$username'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta_proveedor.id_acta
					ORDER BY MES ASC)
					UNION ALL
					(SELECT
					gastos_desplazamiento.id,
					MONTH(acta_fallida.fecha_evaluacion) AS MES,
					MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
					acta_fallida.id_acta,
					acta_fallida.id_interventor,
					gastos_desplazamiento.pago_desplazamiento,
					gastos_desplazamiento.justificacion,
					gastos_desplazamiento.transporte_interventoria,
					gastos_desplazamiento.estado
					from acta_fallida, gastos_desplazamiento
					WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
					acta_fallida.fecha_evaluacion <= now() and
					acta_fallida.id_interventor = '$username'
					and gastos_desplazamiento.pago_desplazamiento > 6000
					GROUP BY acta_fallida.id_acta
					ORDER BY MES ASC)
					"),$conexion);
				}
			}
		}else {
			goto consultar;
		}
	}else {
		consultar:
		if (isset($_POST["id_mes"]) && $_POST["id_mes"] != 0 && isset($_POST["id_interventor_consulta"]) && $_POST["id_interventor_consulta"] != "0" && $id_grupo == 3) {
			$id_mes = $_POST["id_mes"];
			$id_interventor_consulta = $_POST["id_interventor_consulta"];
			$month = $id_mes;
			$year = 2020;
			$day = date("d", mktime(0,0,0, $month+1, 0, $year));
			$fechaInicial = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$fechaFinal = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
			$actas_ajuste = mysql_query(("
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta.fecha_evaluacion) AS MES,
			MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
			acta.id_acta,
			acta.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta, gastos_desplazamiento
			WHERE acta.id_acta = gastos_desplazamiento.id_acta and
			acta.fecha_evaluacion <= now() and
			acta.id_interventor = '$id_interventor_consulta' and
			acta.id_componente = '$id_componente' and
			acta.fecha_evaluacion <='$fechaFinal' and acta.fecha_evaluacion >='$fechaInicial'
			GROUP BY acta.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_proveedor.fecha_evaluacion) AS MES,
			MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
			acta_proveedor.id_acta,
			acta_proveedor.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_proveedor, gastos_desplazamiento
			WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
			acta_proveedor.fecha_evaluacion <= now() and
			acta_proveedor.id_interventor = '$id_interventor_consulta' and
			acta_proveedor.id_componente = '$id_componente' and
			acta_proveedor.fecha_evaluacion <='$fechaFinal' and acta_proveedor.fecha_evaluacion >='$fechaInicial'
			GROUP BY acta_proveedor.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_fallida.fecha_evaluacion) AS MES,
			MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
			acta_fallida.id_acta,
			acta_fallida.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_fallida, gastos_desplazamiento
			WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
			acta_fallida.fecha_evaluacion <= now() and
			acta_fallida.id_interventor = '$id_interventor_consulta' and
			acta_fallida.id_componente = '$id_componente' and
			acta_fallida.fecha_evaluacion <='$fechaFinal' and acta_fallida.fecha_evaluacion >='$fechaInicial'
			GROUP BY acta_fallida.id_acta
			ORDER BY MES ASC)
			"),$conexion);
			?>
			<script type="text/javascript">
			setTimeout(function(){
				$("#tab1").removeClass("active");
				$("#tab1default").removeClass("in active");
				$("#gastos").addClass("active");
				$("#tab3default").addClass("in active");
			},100);
			</script>
			<?php

		} elseif (isset($_POST["id_interventor_consulta"]) && $_POST["id_interventor_consulta"] != "0" && $id_grupo == 3) {
			$id_interventor_consulta = $_POST["id_interventor_consulta"];
			$actas_ajuste = mysql_query(("
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta.fecha_evaluacion) AS MES,
			MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
			acta.id_acta,
			acta.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta, gastos_desplazamiento
			WHERE acta.id_acta = gastos_desplazamiento.id_acta and
			acta.fecha_evaluacion <= now() and
			acta.id_interventor = '$id_interventor_consulta'
			GROUP BY acta.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_proveedor.fecha_evaluacion) AS MES,
			MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
			acta_proveedor.id_acta,
			acta_proveedor.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_proveedor, gastos_desplazamiento
			WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
			acta_proveedor.fecha_evaluacion <= now() and
			acta_proveedor.id_interventor = '$id_interventor_consulta'
			GROUP BY acta_proveedor.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_fallida.fecha_evaluacion) AS MES,
			MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
			acta_fallida.id_acta,
			acta_fallida.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_fallida, gastos_desplazamiento
			WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
			acta_fallida.fecha_evaluacion <= now() and
			acta_fallida.id_interventor = '$id_interventor_consulta'
			GROUP BY acta_fallida.id_acta
			ORDER BY MES ASC)
			"),$conexion);
			?>
			<script type="text/javascript">
			setTimeout(function(){
				$("#tab1").removeClass("active");
				$("#tab1default").removeClass("in active");
				$("#gastos").addClass("active");
				$("#tab3default").addClass("in active");
			},100);
			</script>
			<?php
		} elseif (isset($_POST["id_mes"]) && $_POST["id_mes"] != 0) {
			$id_mes = $_POST["id_mes"];
			$month = $id_mes;
			$year = 2020;
			$day = date("d", mktime(0,0,0, $month+1, 0, $year));
			$fechaInicial = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$fechaFinal = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
			if ($id_grupo == 3) {
				$actas_ajuste = mysql_query(("
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta.fecha_evaluacion) AS MES,
				MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
				acta.id_acta,
				acta.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta, gastos_desplazamiento
				WHERE acta.id_acta = gastos_desplazamiento.id_acta and
				acta.fecha_evaluacion <= now() and
				acta.id_componente = '$id_componente' and
				acta.fecha_evaluacion <='$fechaFinal' and acta.fecha_evaluacion >='$fechaInicial'
				GROUP BY acta.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_proveedor.fecha_evaluacion) AS MES,
				MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
				acta_proveedor.id_acta,
				acta_proveedor.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_proveedor, gastos_desplazamiento
				WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
				acta_proveedor.fecha_evaluacion <= now() and
				acta_proveedor.id_componente = '$id_componente' and
				acta_proveedor.fecha_evaluacion <='$fechaFinal' and acta_proveedor.fecha_evaluacion >='$fechaInicial'
				GROUP BY acta_proveedor.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_fallida.fecha_evaluacion) AS MES,
				MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
				acta_fallida.id_acta,
				acta_fallida.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_fallida, gastos_desplazamiento
				WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
				acta_fallida.fecha_evaluacion <= now() and
				acta_fallida.id_componente = '$id_componente' and
				acta_fallida.fecha_evaluacion <='$fechaFinal' and acta_fallida.fecha_evaluacion >='$fechaInicial'
				GROUP BY acta_fallida.id_acta
				ORDER BY MES ASC)
				"),$conexion);
				?>
				<script type="text/javascript">
				setTimeout(function(){
					$("#tab1").removeClass("active");
					$("#tab1default").removeClass("in active");
					$("#gastos").addClass("active");
					$("#tab3default").addClass("in active");
				},100);
				</script>
				<?php
			}elseif ($id_grupo == 2) {
				$actas_ajuste = mysql_query(("
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta.fecha_evaluacion) AS MES,
				MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
				acta.id_acta,
				acta.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta, gastos_desplazamiento
				WHERE acta.id_acta = gastos_desplazamiento.id_acta and
				acta.fecha_evaluacion <= now() and
				acta.id_interventor = '$username' and
				acta.fecha_evaluacion <='$fechaFinal' and acta.fecha_evaluacion >='$fechaInicial'
				GROUP BY acta.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_proveedor.fecha_evaluacion) AS MES,
				MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
				acta_proveedor.id_acta,
				acta_proveedor.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_proveedor, gastos_desplazamiento
				WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
				acta_proveedor.fecha_evaluacion <= now() and
				acta_proveedor.id_interventor = '$username' and
				acta_proveedor.fecha_evaluacion <='$fechaFinal' and acta_proveedor.fecha_evaluacion >='$fechaInicial'
				GROUP BY acta_proveedor.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_fallida.fecha_evaluacion) AS MES,
				MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
				acta_fallida.id_acta,
				acta_fallida.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_fallida, gastos_desplazamiento
				WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
				acta_fallida.fecha_evaluacion <= now() and
				acta_fallida.id_interventor = '$username' and
				acta_fallida.fecha_evaluacion <='$fechaFinal' and acta_fallida.fecha_evaluacion >='$fechaInicial'
				GROUP BY acta_fallida.id_acta
				ORDER BY MES ASC)
				"),$conexion);
				?>
				<script type="text/javascript">
				setTimeout(function(){
					$("#tab1").removeClass("active");
					$("#tab1default").removeClass("in active");
					$("#gastos").addClass("active");
					$("#tab3default").addClass("in active");
				},100);
				</script>
				<?php
			}
		}else {
			if ($id_grupo == 3) {
				$actas_ajuste = mysql_query(("
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta.fecha_evaluacion) AS MES,
				MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
				acta.id_acta,
				acta.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta, gastos_desplazamiento
				WHERE acta.id_acta = gastos_desplazamiento.id_acta and
				acta.fecha_evaluacion <= now() and
				acta.id_componente = '$id_componente'
				GROUP BY acta.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_proveedor.fecha_evaluacion) AS MES,
				MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
				acta_proveedor.id_acta,
				acta_proveedor.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_proveedor, gastos_desplazamiento
				WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
				acta_proveedor.fecha_evaluacion <= now() and
				acta_proveedor.id_componente = '$id_componente'
				GROUP BY acta_proveedor.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_fallida.fecha_evaluacion) AS MES,
				MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
				acta_fallida.id_acta,
				acta_fallida.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_fallida, gastos_desplazamiento
				WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
				acta_fallida.fecha_evaluacion <= now() and
				acta_fallida.id_componente = '$id_componente'
				GROUP BY acta_fallida.id_acta
				ORDER BY MES ASC)
				"),$conexion);
			}elseif ($id_grupo == 2) {
				$actas_ajuste = mysql_query(("
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta.fecha_evaluacion) AS MES,
				MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
				acta.id_acta,
				acta.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta, gastos_desplazamiento
				WHERE acta.id_acta = gastos_desplazamiento.id_acta and
				acta.fecha_evaluacion <= now() and
				acta.id_interventor = '$username'
				GROUP BY acta.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_proveedor.fecha_evaluacion) AS MES,
				MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
				acta_proveedor.id_acta,
				acta_proveedor.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_proveedor, gastos_desplazamiento
				WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
				acta_proveedor.fecha_evaluacion <= now() and
				acta_proveedor.id_interventor = '$username'
				GROUP BY acta_proveedor.id_acta
				ORDER BY MES ASC)
				UNION ALL
				(SELECT
				gastos_desplazamiento.id,
				MONTH(acta_fallida.fecha_evaluacion) AS MES,
				MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
				acta_fallida.id_acta,
				acta_fallida.id_interventor,
				gastos_desplazamiento.pago_desplazamiento,
				gastos_desplazamiento.justificacion,
				gastos_desplazamiento.transporte_interventoria,
				gastos_desplazamiento.estado
				from acta_fallida, gastos_desplazamiento
				WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
				acta_fallida.fecha_evaluacion <= now() and
				acta_fallida.id_interventor = '$username'
				GROUP BY acta_fallida.id_acta
				ORDER BY MES ASC)
				"),$conexion);
			}
		}
	}



	if ($id_grupo == 3) {
		$actascreadasxusuario = mysql_query(("
		SELECT
		MONTH(fecha_evaluacion) AS MES,
		MONTHNAME(fecha_evaluacion) AS NOMBRE,
		COUNT(*) as NUMERO
		from acta
		WHERE fecha_evaluacion <= now() and
		id_componente = '$id_componente'
		GROUP BY MES"), $conexion);
		$actasfallidascreadasxusuario = mysql_query(("
		SELECT
		MONTH(fecha_evaluacion) AS MES,
		MONTHNAME(fecha_evaluacion) AS NOMBRE,
		COUNT(*) as NUMERO
		from acta_fallida
		WHERE fecha_evaluacion <= now() and
		id_componente = '$id_componente'
		GROUP BY MES"), $conexion);
		$actasproveedorcreadasxusuario = mysql_query(("
		SELECT
		MONTH(fecha_evaluacion) AS MES,
		MONTHNAME(fecha_evaluacion) AS NOMBRE,
		COUNT(*) as NUMERO
		from acta_proveedor
		WHERE fecha_evaluacion <= now() and
		id_componente = '$id_componente'
		GROUP BY MES"), $conexion);
	}elseif ($id_grupo == 2) {
		$actascreadasxusuario = mysql_query(("
		SELECT
		MONTH(fecha_evaluacion) AS MES,
		MONTHNAME(fecha_evaluacion) AS NOMBRE,
		COUNT(*) as NUMERO
		from acta
		WHERE fecha_evaluacion <= now() and
		id_interventor = '$username'
		GROUP BY MES"), $conexion);
		$actasfallidascreadasxusuario = mysql_query(("
		SELECT
		MONTH(fecha_evaluacion) AS MES,
		MONTHNAME(fecha_evaluacion) AS NOMBRE,
		COUNT(*) as NUMERO
		from acta_fallida
		WHERE fecha_evaluacion <= now() and
		id_interventor = '$username'
		GROUP BY MES"), $conexion);
		$actasproveedorcreadasxusuario = mysql_query(("
		SELECT
		MONTH(fecha_evaluacion) AS MES,
		MONTHNAME(fecha_evaluacion) AS NOMBRE,
		COUNT(*) as NUMERO
		from acta_proveedor
		WHERE fecha_evaluacion <= now() and
		id_interventor = '$username'
		GROUP BY MES"), $conexion);
	}



	if ($id_grupo == 3) {
		$alertaactas = mysql_query(("
		SELECT
		acta.id_acta,
		acta.fecha_evaluacion,
		componente.nombre_componente,
		acta.id_contrato,
		prestador.nombre_prestador,
		modalidad.abr_modalidad,
		acta.nombre_sede,
		acta.id_interventor,
		acta.numero_visita,
		acta.porc_inicial,
		acta.porc_final,
		acta.estado,
		subsanacion.etapa,
		subsanacion.fecha_subsanacion,
		subsanacion.fecha_subsanacion_final,
		subsanacion.fecha_solicitud_aclaracion,
		subsanacion.id_radicado_osa,
		subsanacion.fecha_requerimiento,
		subsanacion.id_radicado_orq,
		subsanacion.fecha_envio_evidencia
		FROM
		acta,prestador,modalidad,componente,subsanacion
		WHERE
		acta.id_prestador=prestador.id_prestador and
		acta.id_modalidad=modalidad.id_modalidad and
		acta.id_componente=componente.id_componente and
		acta.id_acta = subsanacion.id_acta and
		acta.id_componente = '$id_componente' and
		subsanacion.etapa != 'OK'
		group by acta.id_acta
		order by acta.fecha_evaluacion desc"), $conexion);
	}elseif ($id_grupo == 2) {
		$alertaactas = mysql_query(("
		SELECT
		acta.id_acta,
		acta.fecha_evaluacion,
		acta.id_contrato,
		prestador.nombre_prestador,
		modalidad.abr_modalidad,
		acta.nombre_sede,
		acta.id_interventor,
		acta.numero_visita,
		acta.porc_inicial,
		acta.porc_final,
		acta.estado,
		subsanacion.etapa,
		subsanacion.fecha_subsanacion,
		subsanacion.fecha_subsanacion_final,
		subsanacion.fecha_solicitud_aclaracion,
		subsanacion.id_radicado_osa,
		subsanacion.fecha_requerimiento,
		subsanacion.id_radicado_orq,
		subsanacion.fecha_envio_evidencia
		FROM
		acta,prestador,modalidad,componente,subsanacion
		WHERE
		acta.id_prestador=prestador.id_prestador and
		acta.id_modalidad=modalidad.id_modalidad and
		acta.id_componente=componente.id_componente and
		acta.id_acta = subsanacion.id_acta and
		acta.id_interventor = '$username' and
		subsanacion.etapa != 'OK'
		group by acta.id_acta
		order by acta.fecha_evaluacion desc"), $conexion);
	}
	if ($id_grupo == 5 || $id_grupo == 1) {
		if (isset($_GET["id_componente"])) {

		}else {
			$id_componenteS = 1;
		}
		$interventores = mysql_query(("
		SELECT username
		FROM users
		WHERE id_componente = '$id_componenteS'
		AND id_group = 2 "), $conexion);
		if (isset($_POST["id_mes"]) && $_POST["id_mes"] != 0 && isset($_POST["id_interventor_consulta"]) && $_POST["id_interventor_consulta"] != "0") {
			$id_interventor_consulta = $_POST["id_interventor_consulta"];
			$id_mes = $_POST["id_mes"];
			$month = $id_mes;
			$year = 2020;
			$day = date("d", mktime(0,0,0, $month+1, 0, $year));
			$fechaInicial = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$fechaFinal = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
			$actas_ajuste = mysql_query(("
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta.fecha_evaluacion) AS MES,
			MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
			acta.id_acta,
			acta.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta, gastos_desplazamiento
			WHERE acta.id_acta = gastos_desplazamiento.id_acta and
			acta.fecha_evaluacion <= now() and
			acta.id_interventor = '$id_interventor_consulta' and
			acta.id_componente = '$id_componenteS' and
			acta.fecha_evaluacion <='$fechaFinal' and acta.fecha_evaluacion >='$fechaInicial'
			GROUP BY acta.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_proveedor.fecha_evaluacion) AS MES,
			MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
			acta_proveedor.id_acta,
			acta_proveedor.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_proveedor, gastos_desplazamiento
			WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
			acta_proveedor.fecha_evaluacion <= now() and
			acta_proveedor.id_interventor = '$id_interventor_consulta' and
			acta_proveedor.id_componente = '$id_componenteS' and
			acta_proveedor.fecha_evaluacion <='$fechaFinal' and acta_proveedor.fecha_evaluacion >='$fechaInicial'
			GROUP BY acta_proveedor.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_fallida.fecha_evaluacion) AS MES,
			MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
			acta_fallida.id_acta,
			acta_fallida.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_fallida, gastos_desplazamiento
			WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
			acta_fallida.fecha_evaluacion <= now() and
			acta_fallida.id_interventor = '$id_interventor_consulta' and
			acta_fallida.id_componente = '$id_componenteS' and
			acta_fallida.fecha_evaluacion <='$fechaFinal' and acta_fallida.fecha_evaluacion >='$fechaInicial'
			GROUP BY acta_fallida.id_acta
			ORDER BY MES ASC)
			"),$conexion);?>
			<script type="text/javascript">
			setTimeout(function(){
				$("#tab1").removeClass("active");
				$("#tab1default").removeClass("in active");
				$("#gastos").addClass("active");
				$("#tab3default").addClass("in active");
			},100);
			</script>
			<?php
		}elseif (isset($_POST["id_interventor_consulta"]) && $_POST["id_interventor_consulta"] != "0") {
			$id_interventor_consulta = $_POST["id_interventor_consulta"];
			$actas_ajuste = mysql_query(("
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta.fecha_evaluacion) AS MES,
			MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
			acta.id_acta,
			acta.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta, gastos_desplazamiento
			WHERE acta.id_acta = gastos_desplazamiento.id_acta and
			acta.fecha_evaluacion <= now() and
			acta.id_interventor = '$id_interventor_consulta'
			GROUP BY acta.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_proveedor.fecha_evaluacion) AS MES,
			MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
			acta_proveedor.id_acta,
			acta_proveedor.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_proveedor, gastos_desplazamiento
			WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
			acta_proveedor.fecha_evaluacion <= now() and
			acta_proveedor.id_interventor = '$id_interventor_consulta'
			GROUP BY acta_proveedor.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_fallida.fecha_evaluacion) AS MES,
			MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
			acta_fallida.id_acta,
			acta_fallida.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_fallida, gastos_desplazamiento
			WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
			acta_fallida.fecha_evaluacion <= now() and
			acta_fallida.id_interventor = '$id_interventor_consulta'
			GROUP BY acta_fallida.id_acta
			ORDER BY MES ASC)
			"),$conexion);?>
			<script type="text/javascript">
			setTimeout(function(){
				$("#tab1").removeClass("active");
				$("#tab1default").removeClass("in active");
				$("#gastos").addClass("active");
				$("#tab3default").addClass("in active");
			},100);
			</script>
			<?php
		}elseif (isset($_POST["id_mes"]) && $_POST["id_mes"] != 0) {

			$id_mes = $_POST["id_mes"];
			$month = $id_mes;
			$year = 2020;
			$day = date("d", mktime(0,0,0, $month+1, 0, $year));
			$fechaInicial = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
			$fechaFinal = date('Y-m-d', mktime(0,0,0, $month, $day, $year));

			$actas_ajuste = mysql_query(("
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta.fecha_evaluacion) AS MES,
			MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
			acta.id_acta,
			acta.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta, gastos_desplazamiento
			WHERE acta.id_acta = gastos_desplazamiento.id_acta and
			acta.fecha_evaluacion <= now() and
			acta.id_componente = '$id_componenteS' and
			acta.fecha_evaluacion <='$fechaFinal' and acta.fecha_evaluacion >='$fechaInicial'
			GROUP BY acta.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_proveedor.fecha_evaluacion) AS MES,
			MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
			acta_proveedor.id_acta,
			acta_proveedor.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_proveedor, gastos_desplazamiento
			WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
			acta_proveedor.fecha_evaluacion <= now() and
			acta_proveedor.id_componente = '$id_componenteS' and
			acta_proveedor.fecha_evaluacion <='$fechaFinal' and acta_proveedor.fecha_evaluacion >='$fechaInicial'
			GROUP BY acta_proveedor.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_fallida.fecha_evaluacion) AS MES,
			MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
			acta_fallida.id_acta,
			acta_fallida.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_fallida, gastos_desplazamiento
			WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
			acta_fallida.fecha_evaluacion <= now() and
			acta_fallida.id_componente = '$id_componenteS' and
			acta_fallida.fecha_evaluacion <='$fechaFinal' and acta_fallida.fecha_evaluacion >='$fechaInicial'
			and gastos_desplazamiento.pago_desplazamiento > 6000
			GROUP BY acta_fallida.id_acta
			ORDER BY MES ASC)
			"),$conexion);?>
			<script type="text/javascript">
			setTimeout(function(){
				$("#tab1").removeClass("active");
				$("#tab1default").removeClass("in active");
				$("#gastos").addClass("active");
				$("#tab3default").addClass("in active");
			},100);
			</script>
			<?php
		}else{
			$actas_ajuste = mysql_query(("
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta.fecha_evaluacion) AS MES,
			MONTHNAME(acta.fecha_evaluacion) AS NOMBREMES,
			acta.id_acta,
			acta.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta, gastos_desplazamiento
			WHERE acta.id_acta = gastos_desplazamiento.id_acta and
			acta.fecha_evaluacion <= now() and
			acta.id_componente = '$id_componenteS'
			GROUP BY acta.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_proveedor.fecha_evaluacion) AS MES,
			MONTHNAME(acta_proveedor.fecha_evaluacion) AS NOMBREMES,
			acta_proveedor.id_acta,
			acta_proveedor.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_proveedor, gastos_desplazamiento
			WHERE acta_proveedor.id_acta = gastos_desplazamiento.id_acta and
			acta_proveedor.fecha_evaluacion <= now() and
			acta_proveedor.id_componente = '$id_componenteS'
			GROUP BY acta_proveedor.id_acta
			ORDER BY MES ASC)
			UNION ALL
			(SELECT
			gastos_desplazamiento.id,
			MONTH(acta_fallida.fecha_evaluacion) AS MES,
			MONTHNAME(acta_fallida.fecha_evaluacion) AS NOMBREMES,
			acta_fallida.id_acta,
			acta_fallida.id_interventor,
			gastos_desplazamiento.pago_desplazamiento,
			gastos_desplazamiento.justificacion,
			gastos_desplazamiento.transporte_interventoria,
			gastos_desplazamiento.estado
			from acta_fallida, gastos_desplazamiento
			WHERE acta_fallida.id_acta = gastos_desplazamiento.id_acta and
			acta_fallida.fecha_evaluacion <= now() and
			acta_fallida.id_componente = '$id_componenteS'
			GROUP BY acta_fallida.id_acta
			ORDER BY MES ASC)
			"),$conexion);
			?>
			<script type="text/javascript">
			setTimeout(function(){
				$("#tab1").removeClass("active");
				$("#tab1default").removeClass("in active");
				$("#gastos").addClass("active");
				$("#tab3default").addClass("in active");
			},100);
			</script>

			<?php
		}
	}
	?>

	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">

		<title>Portal de Administración</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/datepicker.css" rel="stylesheet">
		<!-- Estilos menú principal -->
		<link rel="stylesheet" href="css/estilos.css">

		<!-- Material Icons -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!-- Para validacion de campos -->
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/parsley.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>


		<!-- Custom styles for this template -->
		<link href="css/jumbotron-narrow.css" rel="stylesheet">

	</head>

	<body>
		<!-- <div class="barra-menu"> -->
			<!-- <div class="col-md-12">

			<div style="margin-top: 1%; float: right;">
			<?php if ($id_grupo == 4){ ?>
			<form name="reports" action="exportar.php" method="post"  id="general" style="float:left; margin-right:4px;">
			<input type="hidden" name="msg"  value="0">
			<input class="btn btn-default" type="submit" value="Exportar a Excel">
		</form>
	<?php } else {?>
	<div class="btn-group" role="group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	Reportes
	<span class="caret"></span>
</button>
<ul class="dropdown-menu" role="menu">
<li><a href="reportes.php?id_contrato=0">Valoraciones Detalladas x Sede</a></li>
<li><a href="reportes2.php">Valoraciones Consolidadas Cumplimiento</a></li>
<li><a href="reportes4.php">Valoraciones Consolidadas Calidad</a></li>
<li><a href="reportes3.php?msg=0&componente_selected=0&id_tema=0&id_mes=0">Semáforo Consolidado</a></li>
</ul>
</div>
<a class="btn btn-default" href="creacionusuarios.php?id=0" role="button">Creación/Edición Usuarios</a>
<form name="reports" action="exportar.php" method="post"  id="general" style="float:left; margin-right:4px;">
<input type="hidden" name="msg"  value="0">
<input class="btn btn-default" type="submit" value="Exportar a Excel">
</form>
<?php } ?>

</div>
</div> -->
<!-- </div> -->
<div class="barra-menu">
	<div class="col-md-12">

		<div style="margin-top: 1%; float: right; color:white;">
			<!-- <a class="glyphicon glyphicon-home" href="homeadmin.php" style="font-size:35px; color:#ffffff; text-decoration:none;" role="button"></a> -->
			Técnico
			<img src="images/logo_pascual.png"  style="width:40px;">
		</div>
	</div>
</div>
<?php include("menu.php"); ?>
<div style="margin-top:5%; margin-left: 7%;">
	<div class="row">
		<div class="col-md-11">
			<div class="panel with-nav-tabs panel-default">
				<div class="panel-heading">
					<ul class="nav nav-tabs">
						<li id="tab1" class="active"><a href="#tab1default" data-toggle="tab">Alertas</a></li>
						<!-- <li><a href="#tab2default" data-toggle="tab">Gráfico</a></li> -->
						<!-- <li id="gastos"><a href="#tab3default" data-toggle="tab">Gastos desplazamiento</a></li> -->
					</ul>
				</div>
				<div class="panel-body">
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab1default">
							<div class="bs-docs-section" align="center">
								<h2 id="tables-example">Alertas</h2>
							</div>

							<?php

							if (isset($alertaactas)) {
								while ($row = mysql_fetch_assoc($alertaactas)) {
									$fechaactual = date('Y-m-d');
									// ACCIONES CORRECTIVAS
									if ($row['etapa'] == 'AC'){

										//Plazo Acciones Correctivas
										$diasdiferencia = (strtotime($fechaactual) - strtotime($row['fecha_subsanacion']))/86400;
										$diasdiferencia = abs($diasdiferencia);
										$diasdiferencia = floor($diasdiferencia);

										//Prorroga Acctiones Correctivas
										$diasdiferenciaP = (strtotime($fechaactual) - strtotime($row['fecha_subsanacion_final']))/86400;
										$diasdiferenciaP = abs($diasdiferenciaP);
										$diasdiferenciaP = floor($diasdiferenciaP);
										if ($diasdiferenciaP > $diasdiferencia) {
											if ($diasdiferenciaP <= 365) {
												?>
												<div class="alert alert-warning" style="background-color:#ffe680;" role="alert">
													<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
													<span class="sr-only">Alerta:</span>
													<span>Fecha máxima de acciones correctivas del acta: </span>
													<a href='imprimiractas.php?id_acta=<?php echo $row['id_acta'] ?>&msg=0'><?php echo $row['id_acta']?></a>
													<span>se vence el día <?php echo $row['fecha_subsanacion_final'] ?></span>
												</div>
												<?php
											}
										}elseif ($diasdiferencia <= 365) {
											?>
											<div class="alert alert-warning" style="background-color:#ffe680;" role="alert">
												<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
												<span class="sr-only">Alerta:</span>
												<span>Fecha máxima de acciones correctivas del acta: </span>
												<a href='imprimiractas.php?id_acta=<?php echo $row['id_acta'] ?>&msg=0'><?php echo $row['id_acta']?></a>
												<span>se vence el día <?php echo $row['fecha_subsanacion'] ?></span>
											</div>
											<?php
										}

										//SOLICITUD DE ACLARACIÓN
									}elseif ($row['etapa'] == 'SA') {

										//Plazo Solicitud de Aclaración
										$diasdiferencia = (strtotime($fechaactual) - strtotime($row['fecha_solicitud_aclaracion']))/86400;
										$diasdiferencia = abs($diasdiferencia);
										$diasdiferencia = floor($diasdiferencia);
										if ($diasdiferencia <= 365) {
											?>
											<div class="alert alert-warning" style="background-color:#ffe680;" role="alert">
												<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
												<span class="sr-only">Alerta:</span>
												<span>Fecha máxima de solicitud de aclaración del acta: </span>
												<a href='imprimiractas.php?id_acta=<?php echo $row['id_acta'] ?>&msg=0'><?php echo $row['id_acta']?></a>
												<span>se vence el día <?php echo $row['fecha_solicitud_aclaracion'] ?></span>
											</div>
											<?php
										}
										//PLAZO REQUERIMIENTO
									}elseif ($row['etapa'] == 'REQ') {

										//Plazo final requerimiento
										$diasdiferencia = (strtotime($fechaactual) - strtotime($row['fecha_requerimiento']))/86400;
										$diasdiferencia = abs($diasdiferencia);
										$diasdiferencia = floor($diasdiferencia);
										if ($diasdiferencia <= 365) {
											?>
											<div class="alert alert-danger" role="alert">
												<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
												<span class="sr-only">Alerta:</span>
												<span>Fecha máxima de plazo de requerimiento del acta: </span>
												<a href='imprimiractas.php?id_acta=<?php echo $row['id_acta'] ?>&msg=0'><?php echo $row['id_acta']?></a>
												<span>se vence el día <?php echo $row['fecha_requerimiento'] ?></span>
											</div>
											<?php
										}
									}
								}
							}else {
								?>
								<center><h1>No tiene actas pendientes</h1></center>
								<?php
							}
							?>

						</div>
						<!-- <div class="tab-pane fade" id="tab2default">
							<div class="col-md-12">
								<div class="col-md-8">
									<div style="width: 500px; height: 500px;">
										<canvas id="ChartVisitas" width="400" height="400"></canvas>
									</div>
								</div>
								<div class="col-md-4">
									<div class="row">
										<div style="width: 250px; height: 250px;">
											<div class="col-md-12 vcenter">
												<canvas id="ChartVisitasFallidas" width="250" height="250"></canvas>
											</div>
										</div>
										<div style="width: 250px; height: 250px;">
											<div class="col-md-12 vcenter">
												<canvas id="ChartVisitasProveedores" width="250" height="250"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div> -->
						<!-- <div class="tab-pane fadel" id="tab3default">
							<?php if (isset($actas_ajuste)){ ?>
								<form class="" action="" method="post">
									<div class="col-md-12">
										<?php if ($id_grupo == 5 || $id_grupo == 1){ ?>
											<div class="col-md-3">
												<select class="form-control" name="componentes" id="componentes">
													<option value="0" required>Seleccione el componente...</option>
													<?php while ($rowc = mysql_fetch_assoc($componentes)) {?>
														<option value="<?php echo $rowc['id_componente']; ?>"><?php echo $rowc["nombre_componente"]; ?></option>
													<?php } ?>
												</select>
											</div>
										<?php } ?>
										<?php if ($id_grupo == 3 || $id_grupo == 5 || $id_grupo == 1){ ?>
											<div class="col-md-3">
												<select class="form-control" name="id_interventor_consulta" id="id_interventor_consulta">
													<option value="0" required>Todos los interventores...</option>
													<?php  	while($row=mysql_fetch_assoc($interventores)){ ?>
														<option  value="<?php  echo  $row['username']; ?>"><?php echo  $row['username']; ?></option>	<?php 	}	?>
													</select>
												</div>
											<?php } ?>
											<div class="col-md-3">
												<select class="form-control" name="id_mes" id="id_mes">
													<option value="0">Todos los meses</option>
													<option value="1">Enero</option>
													<option value="2">Febrero</option>
													<option value="3">Marzo</option>
													<option value="4">Abril</option>
													<option value="5">Mayo</option>
													<option value="6">Junio</option>
													<option value="7">Julio</option>
													<option value="8">Agosto</option>
													<option value="9">Septiembre</option>
													<option value="10">Octubre</option>
													<option value="11">Noviembre</option>
													<option value="12">Diciembre</option>
												</select>
											</div>

											<div class="col-md-1">
												<input class="btn btn-pascual" type="submit" name="consultas" value="Consultar" />
											</div>
											<?php if ($id_grupo != 5){ ?>
												<div class="col-md-1">
													<input class="btn btn-pascual" type="submit" name="consultas" value="Ajustes" />
												</div>
											<?php } ?>
										</div>
										<br><br>
									</form>
									<div class="col-md-3" style="float:right; margin-right: 4%;">
										<table class="table table-bordered">
											<tr>
												<th>Total visitas</th>
												<th>Total pagar</th>
											</tr>
											<tr>
												<td id="totalvisitas"></td>
												<td id="valorpagar"></td>
											</tr>
										</table>
									</div>
									<?php if ($id_grupo == 1 || $id_grupo == 5){ ?>
										<div style="margin-left:2%;">
											<button type="button" id="generar_informe" class="btn btn-pascual" name="button">Generar Informe</button>
										</div>
									<?php } ?>
									<form class="" action="inserts.php" method="post">
										<input type="hidden" name="caso" value="25">
										<?php if ($id_grupo == 3 || $id_grupo == 1){ ?>
											<div class="col-md-12" >
												<div style="float:right; margin-right: 4%;">
													<label>Aceptar todos</label>
													<input type="checkbox" name="aceptartodos" value="" id="aceptartodos">
												</div>
											</div>
										<?php } ?>
										<table class="table table-bordered " id='table' style="width: 95%">
											<thead>
												<tr>
													<th class="info">Mes</th>
													<th class="info">Acta</th>
													<th class="info">Interventor</th>
													<th class="info">Gasto Desplazamiento</th>
													<th class="info">Justificación</th>
													<th class="info">Transporte Interventoria</th>
													<?php if ($id_grupo == 3 || $id_grupo == 1){ ?>
														<th class="info">Estado</th>
													<?php } ?>
												</tr>
											</thead>
											<tbody>
												<?php
												$totalpago = 0;
												$totalvisitas = 0;
												while ($row = mysql_fetch_assoc($actas_ajuste)) {
													$totalvisitas = $totalvisitas+1;
													$totalpago =  $totalpago + $row['pago_desplazamiento'];
													?>
													<?php if ($row['estado'] == 1){ ?>
														<tr class="success">
														<?php }else if($row['estado'] == 2){ ?>
															<tr class="danger">
															<?php }else{ ?>
																<tr>
																<?php } ?>
																<input type="hidden" name="id_gastos_desplazamiento[]" class="id_gastos_desplazamiento" value="<?php echo $row['id']; ?>">
																<td class="active"><?php echo $row['NOMBREMES']; ?></td>
																<input type="hidden" name="id_acta[]" value="<?php echo $row['id_acta']; ?>">
																<td class="active"><a href='imprimiractas.php?id_acta=<?php echo $row['id_acta'] ?>&msg=0' target="_blank"><?php echo $row['id_acta']?></a></td>
																<td class="active"><?php echo $row['id_interventor']; ?></td>
																<?php if ($id_grupo == 2 && $row["estado"] == 1){ ?>
																	<td class="active"><input readonly type="text" class="form-control valor"  name="pago_desplazamiento[]" value="<?php echo $row['pago_desplazamiento']; ?>"></td>
																	<td class="active"><textarea readonly style="resize:none;" name="justificacion[]" rows="4" cols="30" class="form-control justificacion"><?php echo $row['justificacion']; ?></textarea></td>
																	<td class=active><select disabled class="form-control transporte_interventoria" name="transporte_interventoria[]">
																		<option value="<?php echo $row['transporte_interventoria'] ?>" selected><?php echo $row['transporte_interventoria'] ?></option>
																		<option value="SI">SI</option>
																		<option value="NO">NO</option>
																	</select></td>
																<?php }else {?>
																	<td class="active"><input type="text" class="form-control valor"  name="pago_desplazamiento[]" value="<?php echo $row['pago_desplazamiento']; ?>"></td>
																	<td class="active"><textarea style="resize:none;" name="justificacion[]" rows="4" cols="30" class="form-control justificacion"><?php echo $row['justificacion']; ?></textarea></td>
																	<td class=active><select class="form-control transporte_interventoria" name="transporte_interventoria[]">
																		<option value="<?php echo $row['transporte_interventoria'] ?>" selected><?php echo $row['transporte_interventoria'] ?></option>
																		<option value="SI">SI</option>
																		<option value="NO">NO</option>
																	</select></td>
																<?php } ?>


																<?php if ($id_grupo == 3 || $id_grupo == 1){ ?>
																	<td>
																		<?php if ($row['estado'] == 1 || $row['estado'] == 2){ ?>
																			<button type="button" class="btn btn-default editar">Editar</button>
																			<img src="images/chulo.jpg" width="24px" height="24px" style="float:left; cursor:pointer; display:none;" value="1" class="aprobar_gastos botones_gastos">
																			<img src="images/eliminar.png" width="20px" height="20px" style="float:right; cursor:pointer; display:none;" value="2" class="rechazar_gastos botones_gastos">
																		<?php }else { ?>
																			<button type="button" class="btn btn-default editar" style="display:none;">Editar</button>
																			<img src="images/chulo.jpg" width="24px" height="24px" style="float:left; cursor:pointer;" value="1" class="aprobar_gastos botones_gastos">
																			<img src="images/eliminar.png" width="20px" height="20px" style="float:right; cursor:pointer;" value="2" class="rechazar_gastos botones_gastos">
																		<?php } ?>
																	</td>
																<?php } ?>
															</tr>
														<?php } ?>
													</tbody>
												</table>
												<div class="col-md-5" style="float:right">
													<h4><b>Total a pagar:</b><?php echo number_format($totalpago,0,",","."); ?></h4>
												</div>
												<br><br><br>
												<?php if ($id_grupo != 5){ ?>
													<center><input type="submit" class="	btn btn-pascual" value="Guardar"></center>
												<?php } ?>

											</form>
										<?php } ?>
									</div>
								</div>
							</div>
						</div> -->
					</div>
				</div>
			</div>
			<div class="container">

				<div class="footer">
					<center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

					</div>

				</div> <!-- /container -->
				<script>
				var labels = [];
				var data1 = [];
				var labelsf = [];
				var dataf = [];
				var labelsp = [];
				var datap = [];
				</script>
				<?php
				if (isset($actascreadasxusuario)) {
					while ($row = mysql_fetch_assoc($actascreadasxusuario)) {
						?>
						<script>

						labels.push("<?php echo $row['NOMBRE']; ?>");
						data1.push("<?php echo $row['NUMERO']; ?>");

						</script>
						<?php
					}
				}
				if (isset($actasfallidascreadasxusuario)) {
					while ($rowf = mysql_fetch_assoc($actasfallidascreadasxusuario)) {
						?>
						<script>

						labelsf.push("<?php echo $rowf['NOMBRE']; ?>");
						dataf.push("<?php echo $rowf['NUMERO']; ?>");

						</script>
						<?php
					}
				}
				if (isset($actasproveedorcreadasxusuario)) {
					while ($rowp = mysql_fetch_assoc($actasproveedorcreadasxusuario)) {
						?>
						<script>

						labelsp.push("<?php echo $rowp['NOMBRE']; ?>");
						datap.push("<?php echo $rowp['NUMERO']; ?>");

						</script>
						<?php
					}
				}
				?>
				<script>
				$(document).ready(function() {
					var ctx = document.getElementById("ChartVisitas");

					var ChartVisitas = new Chart(ctx, {

						type: 'bar',
						data: {
							labels: labels,
							datasets: [{
								label: '# de visitas',
								data: data1,
								backgroundColor: [
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)'
								],
								borderColor: [
									'rgba(255,99,132,1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)'
								],
								borderWidth: 1
							}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							}
						}
					});

					var ctxf = document.getElementById("ChartVisitasFallidas");

					var ChartVisitasFallidas = new Chart(ctxf, {

						type: 'bar',
						data: {
							labels: labelsf,
							datasets: [{
								label: '# de visitas fallidas',
								data: dataf,
								backgroundColor: [
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)'
								],
								borderColor: [
									'rgba(255,99,132,1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)'
								],
								borderWidth: 1
							}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							}
						}
					});

					var ctxp = document.getElementById("ChartVisitasProveedores");

					var ChartVisitasProveedores = new Chart(ctxp, {

						type: 'bar',
						data: {
							labels: labelsp,
							datasets: [{
								label: '# de visitas proveedores',
								data: datap,
								backgroundColor: [
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)'
								],
								borderColor: [
									'rgba(255,99,132,1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)'
								],
								borderWidth: 1
							}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							}
						}
					});

				});


				</script>
			</body>
			<script>
			//$('#contenido').load('prueba.php #MenuPrincipal');
			$(".transporte_interventoria").change(function(){
				if ($(this).val() == "SI") {
					$(this).parent().parent().children().children(".valor").val(0);
					$(this).parent().parent().children().children(".valor").attr('readonly', true);
				}else {
					$(this).parent().parent().children().children(".valor").val(6000);
					$(this).parent().parent().children().children(".valor").attr('readonly', false);
				}
			});

			$(".editar").click(function(){
				$(this).parent().children(".botones_gastos").css("display", "block");
				$(this).css("display", "none");
			});

			$(".aprobar_gastos").click(function(){
				var caso = "26";
				var id = $(this).parent().parent().children(".id_gastos_desplazamiento").val();
				var valor = $(this).parent().parent().children().children(".valor").val();
				var justificacion = $(this).parent().parent().children().children(".justificacion").val();
				var transporte_interventoria = $(this).parent().parent().children().children(".transporte_interventoria").val();
				$(this).parent().children(".editar").css("display", "block");
				$(this).parent().children(".botones_gastos").css("display", "none");
				$(this).parent().parent().addClass("success");
				$(this).parent().parent().removeClass("danger");
				$.post("inserts.php",{ caso: caso, id: id, valor: valor, justificacion: justificacion, transporte_interventoria: transporte_interventoria, estado: 1 },
				function(resultado)
				{

				});
			});
			$(".rechazar_gastos").click(function(){
				var caso = "26";
				var id = $(this).parent().parent().children(".id_gastos_desplazamiento").val();
				var valor = $(this).parent().parent().children().children(".valor").val();
				var justificacion = $(this).parent().parent().children().children(".justificacion").val();
				var transporte_interventoria = $(this).parent().parent().children().children(".transporte_interventoria").val();
				$(this).parent().children(".editar").css("display", "block");
				$(this).parent().children(".botones_gastos").css("display", "none");
				$(this).parent().parent().addClass("danger");
				$(this).parent().parent().removeClass("success");
				$.post("inserts.php",{ caso: caso, id: id, valor: valor, justificacion: justificacion, transporte_interventoria: transporte_interventoria, estado: 2},
				function(resultado)
				{

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


			<?php
			if (isset($id_componenteS)) {
				?>
				var id_componenteS = <?php echo $id_componenteS; ?>;
				$("#componentes option[value="+ id_componenteS +"]").attr("selected",true);
				<?php } ?>

				<?php
				if (isset($id_mes)) {
					?>
					var id_mes = <?php echo $id_mes; ?>;
					$('#id_mes option[value='+ id_mes +']').attr("selected",true);
					<?php } ?>

					<?php
					if (isset($id_interventor_consulta)) {
						?>
						var id_interventor_consulta = '<?php echo $id_interventor_consulta; ?>';
						$('#id_interventor_consulta option[value='+ "'" + id_interventor_consulta + "'" +']').attr("selected",true);
						<?php } ?>

						$("#componentes").change(function(){
							var id_componente = $(this).val();
							location.href="homeadmin.php?id_componente=" + id_componente;
						});

						<?php if(isset($totalvisitas)) { ?>
							$("#totalvisitas").html(<?php echo $totalvisitas; ?>);
							<?php } ?>
							<?php if(isset($totalpago)) { ?>
								$("#valorpagar").html('<?php echo number_format($totalpago,0,",","."); ?>')
								<?php } ?>
								// $("#id_mes").change(function(){
								// 	var id_mes = $(this).val();
								// 	console.log(id_mes);
								// });
								$("#ExportarReportes").click(function(){
									$.get("word/consulta_contratos.php",
									function(resultado)
									{
										if(resultado == false)
										{
											alert("Error");
										}
										else
										{
											var contratos = resultado.split(",");
											window.open("http://192.168.2.8/2020/stella/word?code="+ contratos[1]);
											window.open("http://192.168.2.8/2020/stella/word?code="+ contratos[0]);
											$.each(contratos, function(index, value){
												var id_contrato = value;
												if (id_contrato != "") {
													//open("http://localhost/stella/word?code="+ id_contrato, '_self').close();
													//location.href = "http://localhost/stella/word?code="+ id_contrato;
													window.open("http://192.168.2.8/2020/stella/word?code="+ id_contrato);
												}
											});
										}
									});
								});

								$("#generar_informe").click(function(){
									var caso = 13;
									var fecha_inicial = '2020-11-01';
									var fecha_final = '2020-11-30';
									location.href = "http://localhost:8080/stella/download.php?caso="+caso+"&fecha_inicial="+fecha_inicial+"&fecha_final="+fecha_final+"&id_componente="+0;
									// location.href = "http://192.168.2.8/2020/stella/download.php?caso="+caso+"&fecha_inicial="+fecha_inicial+"&fecha_final="+fecha_final+"&id_componente="+0;
								})

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
