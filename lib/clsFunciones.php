<?php
class clsFunciones {

	var $msg = "";


	//VALIDACION DE USUARIOS
	function validarSesion($login,$password) {
		if ($login&&$password)
		{
			include("./conexion.php");
			$codigo="	SELECT * FROM users WHERE username='$login' AND password='$password' AND active='1' ";
			$query=mysql_query ($codigo,$conexion);
			if(!$query){
				$this->msg = "Error al validar datos, favor intente nuevamente";
				return false;
			}
			$numrows= mysql_num_rows($query);
			if ($numrows == 0){
				$this->msg = "Usuario o contrase&ntilde;a incorrecto, int&eacute;ntelo nuevamente";
				return false;
			}
			while ($row = mysql_fetch_assoc($query)){
				$dbusername = $row['username'];
				$dpassword = $row['password'];
				$dcodigorol = $row['id_group'];
				$idcomponente = $row['id_componente'];
				$nombre = $row['first_name'];
				$fotoperfil = $row["foto"];

				//Variables globales que se necesitaran en todos los archivos
				$_SESSION["grupo"]=$dcodigorol;
				$_SESSION["nombre_usuario"]=$nombre;
				$_SESSION["componente"]=$idcomponente;
				$_SESSION['login']=strtolower($login);
				$_SESSION["fotoperfil"] = $fotoperfil;
				$this->msg = "Usuario y contrase&ntilde;a Correctos.";
				return true;
			}

		}else{
			$this->msg = "Usuario o contrase&ntilde;a incorrecto, int&eacute;ntelo nuevamente";
			return false;
		}
	}



	//INSERCIONES
	function bitacora($login, $ip1, $ip2, $ip3) {
		include("conexion.php");
		$ip1 = filter_var($ip1, FILTER_VALIDATE_IP);
		$ip1 = ($ip1 === false) ? '0.0.0.0' : $ip1;
		$ip2 = filter_var($ip2, FILTER_VALIDATE_IP);
		$ip2 = ($ip2 === false) ? '0.0.0.0' : $ip2;
		$ip3 = filter_var($ip3, FILTER_VALIDATE_IP);
		$ip3 = ($ip3 === false) ? '0.0.0.0' : $ip3;
		$sqllog = "INSERT INTO log(usuario, iplocal, ipremota, ipforw) VALUES ('".$login."','".$ip1."','".$ip2."','".$ip3."');";
		$reslog = mysql_query($sqllog, $conexion);
	}
	function insertusuarios($username,$password,$documento,$first_name,$last_name,$email,$phone,$id_componente,$id_group,$created_on,$active,$foto){
		include("conexion.php");
		$query=		"INSERT INTO users
		(username,password,documento,first_name,last_name,email,phone,id_componente,id_group,created_on,active, foto)
		values
		('$username','$password','$documento','$first_name','$last_name','$email','$phone','$id_componente','$id_group','$created_on','$active','$foto')";
		$insertreg= mysql_query($query,$conexion);
		if(mysql_affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}


	function inserttema($nombre_tema,$id_componente,$porcentaje_tema,$estado){
		include("conexion.php");
		$query=		"INSERT INTO tema
		(nombre_tema,id_componente,porcentaje_tema,estado)
		values
		('$nombre_tema','$id_componente','$porcentaje_tema','$estado')";
		$insertreg= mysql_query($query,$conexion);
		if(mysql_affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function insertsubtema($nombre_subtema,$id_componente,$id_tema,$estado,$modalidades,$porcentaje_modalidad){
		include("conexion.php");
		$query=		"INSERT INTO subtema
		(nombre_subtema,id_componente,id_tema,estado)
		values
		('$nombre_subtema','$id_componente','$id_tema','$estado')";

        $insertreg= mysql_query($query,$conexion);
		$id_subtema = mysql_insert_id($conexion);
		$porcentajes= array();

		foreach ($porcentaje_modalidad as $key => $value) {
			
			if($value != 0){
				array_push($porcentajes, $value);
			}
		}

		foreach ($modalidades as $key => $value) {
			// $var = floatval()
			$query1=		"INSERT INTO estandar_x_modalidad
			(id_modalidad,id_componente,id_tema,id_subtema,porc_estandar_x_modalidad,estado)
			values
			('$value','$id_componente','$id_tema','$id_subtema','$porcentajes[$key]','1')";
			$insertreg1= mysql_query($query1,$conexion);
		}

		if(mysql_affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function insertpregunta($descripcion_pregunta,$id_componente,$id_tema,$id_subtema,$id_modalidad,$estado, $tipo_acta){
		include("conexion.php");
		$query="
		INSERT INTO pregunta
		(descripcion_pregunta,id_componente,id_tema,id_subtema,estado)
		values
		('$descripcion_pregunta','$id_componente','$id_tema','$id_subtema','$estado')
		";
		$insertreg= mysql_query($query,$conexion);

		if(mysql_affected_rows() > 0){
			$last= mysql_query(("
			SELECT
			max(id_pregunta) ultima_pregunta
			FROM
			pregunta
			"),$conexion);
			$row=mysql_fetch_assoc($last);
			$ultima_pregunta=$row['ultima_pregunta'];

			for ($i=0; $i < count($id_modalidad);$i++){
				$query2="
				INSERT INTO pregunta_x_modalidad
				(id_pregunta,id_modalidad,id_subtema,id_tema,id_componente,estado,tipo_acta)
				values
				('$ultima_pregunta','$id_modalidad[$i]','$id_subtema','$id_tema','$id_componente','$estado',$tipo_acta)";
				$insertreg2= mysql_query($query2,$conexion);
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function insertobsevaluador($id_acta,$fecha_observacion_evaluador,$descripcion_observacion_evaluador,$id_interventor,$estado){
		include("conexion.php");
		$query=		"INSERT INTO observacion_evaluador
		(id_acta,fecha_observacion_evaluador,descripcion_observacion_evaluador,id_interventor,estado)
		values
		('$id_acta','$fecha_observacion_evaluador','$descripcion_observacion_evaluador','$id_interventor','$estado')";
		$insertreg= mysql_query($query,$conexion);
		if(mysql_affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function insertobsevaluadorproveedores($id_acta,$fecha_observacion_evaluador,$descripcion_observacion_evaluador,$id_interventor,$estado){
		include("conexion.php");
		$query=		"INSERT INTO observacion_evaluador
		(id_acta,fecha_observacion_evaluador,descripcion_observacion_evaluador,id_interventor,estado)
		values
		('$id_acta','$fecha_observacion_evaluador','$descripcion_observacion_evaluador','$id_interventor','$estado')";
		$insertreg= mysql_query($query,$conexion);
		if(mysql_affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}


	function insertobsusuario($id_acta,$fecha_observacion_usuario,$descripcion_observacion_usuario,$id_interventor,$estado){
		include("conexion.php");
		$query=		"INSERT INTO observacion_usuario
		(id_acta,fecha_observacion_usuario,descripcion_observacion_usuario,id_interventor,estado)
		values
		('$id_acta','$fecha_observacion_usuario','$descripcion_observacion_usuario','$id_interventor','$estado')";
		$insertreg= mysql_query($query,$conexion);
		if(mysql_affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function insertobsusuarioproveedores($id_acta,$fecha_observacion_usuario,$descripcion_observacion_usuario,$id_interventor,$estado){
		include("conexion.php");
		$query=		"INSERT INTO observacion_usuario
		(id_acta,fecha_observacion_usuario,descripcion_observacion_usuario,id_interventor,estado)
		values
		('$id_acta','$fecha_observacion_usuario','$descripcion_observacion_usuario','$id_interventor','$estado')";
		$insertreg= mysql_query($query,$conexion);
		if(mysql_affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}


	function insertevaluacion(
		$id_componente,
		$id_tema,
		$id_subtema,
		$id_pregunta,
		$descripcion_pregunta,
		$descripcion_observacion,
		$descripcion_accion_correctiva,
		$valor_referencia,
		$valor_calificacion,
		$valor_calificacion_final,
		$id_acta,
		$id_contrato,
		$id_sede,
		$id_prestador,
		$id_modalidad,
		$id_interventor,
		$numero_visita,
		$fecha_evaluacion,
		$fecha_subsanacion,
		$hora_inicio,
		$hora_fin,
		$estado,
		$consecutivo_acta,
		$nombre_sede,
		$direccion_sede,
		$telefono_sede,
		$nombre_asistentes,
		$informacion_complementaria,
		$acta_reservada,
		$porc_componente_x_modalidad,
		$pago_desplazamiento,
		$transporte_interventoria,
		$justificacion,
		$tema_encuentro,
		$observacion
	  ){

		include("conexion.php");
		for ($i=0; $i < count($id_pregunta);$i++)
		{
			if($valor_calificacion[$i]==5){;}else{
				$query=		"INSERT INTO evaluacion
				(
					id_componente,
					id_tema,
					id_subtema,
					id_pregunta,
					valor_referencia,
					valor_calificacion,
					valor_calificacion_final,
					id_acta,
					id_contrato,
					id_sede,
					id_prestador,
					id_modalidad,
					id_interventor,
					numero_visita,
					fecha_evaluacion,
					estado,
					observacion
				)
				values
				(
					'$id_componente',
					'$id_tema[$i]',
					'$id_subtema[$i]',
					'$id_pregunta[$i]',
					'$valor_referencia',
					'$valor_calificacion[$i]',
					'$valor_calificacion_final[$i]',
					'$id_acta',
					'$id_contrato',
					'$id_sede',
					'$id_prestador',
					'$id_modalidad',
					'$id_interventor',
					'$numero_visita',
					'$fecha_evaluacion',
					'$estado',
					'$observacion[$i]'
				)";
				$insertreg= mysql_query($query,$conexion);
			}
		}

		if(mysql_affected_rows() > 0){

			if($acta_reservada=="NO"){
				//Update: Aumentar consecutivo en la tabla componente
				$update_consecutivo = mysql_query("
				UPDATE componente SET consecutivo_acta='$consecutivo_acta'
				where id_componente='$id_componente'
				");
				$updatereg= mysql_query($update_consecutivo,$conexion);
			}
			else{
				$query=	" UPDATE reserva_radicado SET estado='0' where id_acta='$id_acta'";
				$updatereg= mysql_query($query,$conexion);
			}

			//Insert: Crear registro del acta para las consultas
			$insert_acta="INSERT INTO acta
			(
				id_acta,
				fecha_evaluacion,
				hora_inicio,
				hora_fin,
				id_componente,
				id_contrato,
				id_prestador,
				id_modalidad,
				id_sede,
				numero_visita,
				nombre_sede,
				direccion_sede,
				telefono_sede,
				nombre_asistentes,
				informacion_complementaria,
				id_interventor,
				porc_componente_x_modalidad,
				estado,
				tema_encuentro
			)
			values
			(
				'$id_acta',
				'$fecha_evaluacion',
				'$hora_inicio',
				'$hora_fin',
				'$id_componente',
				'$id_contrato',
				'$id_prestador',
				'$id_modalidad',
				'$id_sede',
				'$numero_visita',
				'$nombre_sede',
				'$direccion_sede',
				'$telefono_sede',
				'$nombre_asistentes',
				'$informacion_complementaria',
				'$id_interventor',
				'$porc_componente_x_modalidad',
				'$estado',
				'$tema_encuentro'
			)";
			$insertacta= mysql_query($insert_acta,$conexion);

			if ($pago_desplazamiento == 6000) {
				$insertgastos_desplazamiento = "INSERT INTO gastos_desplazamiento
				(
					id_acta,
					id_interventor,
					fecha_evaluacion,
					pago_desplazamiento,
					justificacion,
					transporte_interventoria,
					estado) values
					(
						'$id_acta',
						'$id_interventor',
						'$fecha_evaluacion',
						'$pago_desplazamiento',
						'$justificacion',
						'$transporte_interventoria',
						'1'
					)";
				}else {
					$insertgastos_desplazamiento = "INSERT INTO gastos_desplazamiento
					(
						id_acta,
						id_interventor,
						fecha_evaluacion,
						pago_desplazamiento,
						justificacion,
						transporte_interventoria) values
						(
							'$id_acta',
							'$id_interventor',
							'$fecha_evaluacion',
							'$pago_desplazamiento',
							'$justificacion',
							'$transporte_interventoria'
						)";
					}


					$insert_gastos = mysql_query($insertgastos_desplazamiento,$conexion);
					//Query para hallar la suma de porcentajes evaluación
					$queryporcentaje_evaluacion= mysql_query(("
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

						while($row=mysql_fetch_assoc($queryporcentaje_evaluacion)){
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

						for ($i=0; $i < count($id_evaluacion);$i++)
						{
							$update_porcentaje_evaluacion = mysql_query("
							UPDATE evaluacion
							SET
							porc_referencia='$porc_referencia[$i]',
							porc_inicial='$porc_inicial[$i]',
							porc_final='$porc_final[$i]',
							porc_componente_x_final='$porc_componente_x_final[$i]'
							where id_evaluacion='$id_evaluacion[$i]'
							");
							$updateporc= mysql_query($update_porcentaje_evaluacion,$conexion);
						} //End For

						//Query para hallar la suma de porcentajes evaluación
						$queryporcentaje_acta = mysql_query(("
						select
					  sum(suma_tema.porc_inicial)/ sum(porc_referencia) as porc_inicial,
					  sum(suma_tema.porc_final)/ sum(porc_referencia) as porc_final,
					  suma_tema.id_tema
					  FROM
					  (
					    SELECT
					    promedio.id_evaluacion,
					    promedio.id_pregunta,
					    promedio.id_subtema,
					    promedio.referencia,
					    promedio.valor_calificacion,
					    promedio.valor_calificacion_final,
					    sum(promedio.porc_referencia) as porc_referencia,
					    round(sum(promedio.porc_inicial),4) as porc_inicial,
					    round(sum(promedio.porc_final),4) as porc_final,
					    round(sum(promedio.porc_componente_x_final),4) as porc_componente_x_final,
					    promedio.id_tema
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
					      round((if(evaluacion.valor_calificacion_final=1,porcentaje_x_pregunta,if(evaluacion.valor_calificacion_final=2,porcentaje_x_pregunta*0.6,0)))*operacion.porc_componente_x_modalidad,4) porc_componente_x_final,
					      operacion.id_tema
					      FROM
					      (
					        SELECT
					        evaluacion.id_subtema,
					        componente_x_modalidad.porc_componente_x_modalidad,
					        estandar_x_modalidad.porc_estandar_x_modalidad,
					        count(evaluacion.id_pregunta) cantidad_preguntas,
					        (estandar_x_modalidad.porc_estandar_x_modalidad/count(evaluacion.id_pregunta)) porcentaje_x_pregunta,
					        evaluacion.id_tema
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
					        ) as promedio
					        WHERE
					        promedio.id_acta='$id_acta'
					        group by id_subtema) as suma_tema
					        group by suma_tema.id_tema
								"),$conexion);

								$cantidad = 0;
								$porc_referencia_acta = array();
								$porc_inicial_acta = array();
								$porc_final_acta = array();

								while ($row=mysql_fetch_assoc($queryporcentaje_acta)) {
									$cantidad = $cantidad + 1;
									// $id_evaluacion_acta[] = $row["id_evaluacion"];
									$porc_referencia_acta[]=$row['porc_referencia'];
									$porc_inicial_acta[]=$row['porc_inicial'];
									$porc_final_acta[]=$row['porc_final'];
									// $porc_componente_x_final_acta[]=$row['porc_componente_x_final'];
								}

								$suma_porc_referencia_acta=array_sum($porc_referencia_acta);
								$suma_inicial_acta = array_sum($porc_inicial_acta);
								$suma_porc_inicial_acta=array_sum($porc_inicial_acta)/$cantidad;
								$suma_porc_final_acta=array_sum($porc_final_acta)/$cantidad;
								// $suma_porc_componente_x_final_acta=array_sum($porc_componente_x_final_acta)/$suma_porc_referencia_acta;

								//Update: Actualizar porcentajes de evaluacion de tabla acta

								$update_porcentaje_acta = mysql_query("
								UPDATE acta
								SET
								/*porc_referencia='$suma_porc_referencia_acta',*/
								porc_inicial='$suma_porc_inicial_acta',
								porc_final='$suma_porc_final_acta'/*,
								porc_componente_x_final='$suma_porc_componente_x_final_acta'*/
								where id_acta='$id_acta'
								");
								$updateporc= mysql_query($update_porcentaje_acta,$conexion);


								//Insert: Hallazgos en la tabla subsanacion
								for ($i=0; $i < count($id_pregunta);$i++)
								{
									if($valor_calificacion[$i]==2 || $valor_calificacion[$i]==3 || $valor_calificacion[$i]==4 ){
										if($valor_calificacion[$i]==4){
											$etapa="OK";
											$historico="AC";
										}
										else {
											$etapa="AC";
											$historico="AC";
										}
										$query=		"INSERT INTO subsanacion
										(
											id_acta,
											id_pregunta,
											descripcion_pregunta,
											descripcion_observacion,
											descripcion_accion_correctiva,
											id_componente,
											id_contrato,
											id_prestador,
											id_modalidad,
											id_sede,
											id_interventor,
											fecha_subsanacion,
											fecha_subsanacion_final,
											etapa,
											historico,
											estado
										)
										values
										(
											'$id_acta',
											'$id_pregunta[$i]',
											'$descripcion_pregunta[$i]',
											'$descripcion_observacion[$i]',
											'$descripcion_accion_correctiva[$i]',
											'$id_componente',
											'$id_contrato',
											'$id_prestador',
											'$id_modalidad',
											'$id_sede',
											'$id_interventor',
											'$fecha_subsanacion',
											'$fecha_subsanacion',
											'$etapa',
											'$historico',
											'$estado'
										)";
										$insertreg= mysql_query($query,$conexion);
									} //End if
								} //End for


								return TRUE;




							} else {
								return FALSE;
							}

						}


						function insertevaluacionproveedor(
							$id_componente,
							$id_tema,
							$id_subtema,
							$id_pregunta,
							$descripcion_pregunta,
							$descripcion_observacion,
							$descripcion_accion_correctiva,
							$valor_referencia,
							$valor_calificacion,
							$valor_calificacion_final,
							$id_acta,
							$id_proveedor,
							$id_modalidad,
							$id_interventor,
							$numero_visita,
							$fecha_evaluacion,
							$hora_inicio,
							$hora_fin,
							$estado,
							$consecutivo_acta,
							$direccion_proveedor,
							$numero_telefono,
							$nombre_asistentes,
							$nombre_prestadores,
							$acta_reservada,
							$porc_componente_x_modalidad
						){

							include("conexion.php");
							for ($i=0; $i < count($id_pregunta);$i++)
							{
								if($valor_calificacion[$i]==5){}else{
									$query=		"INSERT INTO evaluacion_proveedor
									(
										id_componente,
										id_tema,
										id_subtema,
										id_pregunta,
										valor_referencia,
										valor_calificacion,
										valor_calificacion_final,
										id_acta,
										id_proveedor,
										id_modalidad,
										id_interventor,
										numero_visita,
										fecha_evaluacion,
										estado
									)
									values
									(
										'$id_componente',
										'$id_tema[$i]',
										'$id_subtema[$i]',
										'$id_pregunta[$i]',
										'$valor_referencia',
										'$valor_calificacion[$i]',
										'$valor_calificacion_final[$i]',
										'$id_acta',
										'$id_proveedor',
										'$id_modalidad',
										'$id_interventor',
										'$numero_visita',
										'$fecha_evaluacion',
										'$estado'
									)";
									$insertreg= mysql_query($query,$conexion);
								}
							}

							if(mysql_affected_rows() > 0){

								if($acta_reservada=="NO"){
									//Update: Aumentar consecutivo en la tabla componente
									$update_consecutivo = mysql_query("
									UPDATE componente SET consecutivo_acta='$consecutivo_acta'
									where id_componente='$id_componente'
									");
									$updatereg= mysql_query($update_consecutivo,$conexion);
								}
								else{
									$query=	" UPDATE radicado SET estado='0' where id_radicado='$id_acta'";
									$updatereg= mysql_query($query,$conexion);
								}

								//Insert: Crear registro del acta para las consultas
								$insert_acta="INSERT INTO acta_proveedor
								(
									id_acta,
									fecha_evaluacion,
									hora_inicio,
									hora_fin,
									id_componente,
									id_proveedor,
									id_modalidad,
									numero_visita,
									direccion_proveedor,
									numero_telefono,
									nombre_asistentes,
									nombre_prestadores,
									id_interventor,
									porc_componente_x_modalidad,
									estado
								)
								values
								(
									'$id_acta',
									'$fecha_evaluacion',
									'$hora_inicio',
									'$hora_fin',
									'$id_componente',
									'$id_proveedor',
									'$id_modalidad',
									'$numero_visita',
									'$direccion_proveedor',
									'$numero_telefono',
									'$nombre_asistentes',
									'$nombre_prestadores',
									'$id_interventor',
									'$porc_componente_x_modalidad',
									'$estado'
								)";

								$insertacta= mysql_query($insert_acta,$conexion);

								$insertgastos_desplazamiento = "INSERT INTO gastos_desplazamiento
								(
									id_acta,
									id_interventor,
									fecha_evaluacion,
									pago_desplazamiento) values
									(
										'$id_acta',
										'$id_interventor'
										'$fecha_evaluacion',
										'6000'
									)";

									$insert_gastos = mysql_query($insertgastos_desplazamiento,$conexion);

									//Query para hallar la suma de porcentajes
									$queryporcentaje_evaluacion= mysql_query(("
									SELECT
									evaluacion_proveedor.id_evaluacion,
									evaluacion_proveedor.id_pregunta,
									evaluacion_proveedor.id_subtema,
									evaluacion_proveedor.valor_referencia,
									evaluacion_proveedor.valor_calificacion,
									evaluacion_proveedor.valor_calificacion_final,
									operacion.porcentaje_x_pregunta,
									operacion.porc_componente_x_modalidad,
									round(porcentaje_x_pregunta*valor_referencia,4) porc_referencia,
									round(if(evaluacion_proveedor.valor_calificacion=1,porcentaje_x_pregunta,if(evaluacion_proveedor.valor_calificacion=2,porcentaje_x_pregunta*0.6,0)),4) porc_inicial,
									round(if(evaluacion_proveedor.valor_calificacion_final=1,porcentaje_x_pregunta,if(evaluacion_proveedor.valor_calificacion_final=2,porcentaje_x_pregunta*0.6,0)),4) porc_final,
									round((if(evaluacion_proveedor.valor_calificacion_final=1,porcentaje_x_pregunta,if(evaluacion_proveedor.valor_calificacion_final=2,porcentaje_x_pregunta*0.6,0)))*operacion.porc_componente_x_modalidad,4) porc_componente_x_final

									FROM
									(
										SELECT
										evaluacion_proveedor.id_subtema,
										componente_x_modalidad.porc_componente_x_modalidad,
										estandar_x_modalidad.porc_estandar_x_modalidad,
										count(evaluacion_proveedor.id_pregunta) cantidad_preguntas,
										(estandar_x_modalidad.porc_estandar_x_modalidad/count(evaluacion_proveedor.id_pregunta)) porcentaje_x_pregunta
										FROM
										evaluacion_proveedor,estandar_x_modalidad,componente_x_modalidad
										WHERE
										evaluacion_proveedor.id_subtema=estandar_x_modalidad.id_subtema and
										evaluacion_proveedor.id_tema=estandar_x_modalidad.id_tema and
										evaluacion_proveedor.id_componente=estandar_x_modalidad.id_componente and
										evaluacion_proveedor.id_modalidad=estandar_x_modalidad.id_modalidad and
										evaluacion_proveedor.id_tema=componente_x_modalidad.id_tema and
										evaluacion_proveedor.id_modalidad=componente_x_modalidad.id_modalidad and
										evaluacion_proveedor.id_componente=componente_x_modalidad.id_componente and
										evaluacion_proveedor.id_acta='$id_acta' and
										evaluacion_proveedor.estado='1'
										group by id_subtema
										) as operacion

										RIGHT JOIN evaluacion_proveedor ON evaluacion_proveedor.id_subtema= operacion.id_subtema

										WHERE
										evaluacion_proveedor.id_acta='$id_acta'
										"),$conexion);

										while($row=mysql_fetch_assoc($queryporcentaje_evaluacion)){
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


										//Update: Actualizar porcentajes en la tabla evaluación_proveedor

										for ($i=0; $i < count($id_evaluacion);$i++)
										{
											$update_porcentaje_evaluacion = mysql_query("
											UPDATE evaluacion_proveedor
											SET
											porc_referencia='$porc_referencia[$i]',
											porc_inicial='$porc_inicial[$i]',
											porc_final='$porc_final[$i]',
											porc_componente_x_final='$porc_componente_x_final[$i]'
											where id_evaluacion='$id_evaluacion[$i]'
											");
											$updateporc= mysql_query($update_porcentaje_evaluacion,$conexion);
										} //End For



										$queryporcentaje_acta= mysql_query(("
										SELECT
										promedio.id_evaluacion,
										promedio.id_pregunta,
										promedio.id_subtema,
										promedio.referencia,
										promedio.valor_calificacion,
										promedio.valor_calificacion_final,
										sum(promedio.porc_referencia) as porc_referencia,
										round(sum(promedio.porc_inicial) /  sum(promedio.porc_referencia),4) as porc_inicial,
										round(sum(promedio.porc_final) /  sum(promedio.porc_referencia),4) as porc_final,
										round(sum(promedio.porc_componente_x_final),4) as porc_componente_x_final
										FROM(
											SELECT
											evaluacion_proveedor.id_acta as id_acta,
											evaluacion_proveedor.id_evaluacion as id_evaluacion,
											evaluacion_proveedor.id_pregunta as id_pregunta,
											evaluacion_proveedor.id_subtema as id_subtema,
											evaluacion_proveedor.valor_referencia as referencia,
											evaluacion_proveedor.valor_calificacion as valor_calificacion,
											evaluacion_proveedor.valor_calificacion_final as valor_calificacion_final,
											operacion.porcentaje_x_pregunta,
											operacion.porc_componente_x_modalidad,
											round(porcentaje_x_pregunta*valor_referencia,4) porc_referencia,
											round(if(evaluacion_proveedor.valor_calificacion=1,porcentaje_x_pregunta,if(evaluacion_proveedor.valor_calificacion=2,porcentaje_x_pregunta*0.6,0)),4) porc_inicial,
											round(if(evaluacion_proveedor.valor_calificacion_final=1,porcentaje_x_pregunta,if(evaluacion_proveedor.valor_calificacion_final=2,porcentaje_x_pregunta*0.6,0)),4) porc_final,
											round((if(evaluacion_proveedor.valor_calificacion_final=1,porcentaje_x_pregunta,if(evaluacion_proveedor.valor_calificacion_final=2,porcentaje_x_pregunta*0.6,0)))*operacion.porc_componente_x_modalidad,4) porc_componente_x_final
											FROM
											(
												SELECT
												evaluacion_proveedor.id_subtema,
												componente_x_modalidad.porc_componente_x_modalidad,
												estandar_x_modalidad.porc_estandar_x_modalidad,
												count(evaluacion_proveedor.id_pregunta) cantidad_preguntas,
												(estandar_x_modalidad.porc_estandar_x_modalidad/count(evaluacion_proveedor.id_pregunta)) porcentaje_x_pregunta
												FROM
												evaluacion_proveedor,estandar_x_modalidad,componente_x_modalidad
												WHERE
												evaluacion_proveedor.id_subtema=estandar_x_modalidad.id_subtema and
												evaluacion_proveedor.id_tema=estandar_x_modalidad.id_tema and
												evaluacion_proveedor.id_componente=estandar_x_modalidad.id_componente and
												evaluacion_proveedor.id_modalidad=estandar_x_modalidad.id_modalidad and
												evaluacion_proveedor.id_tema=componente_x_modalidad.id_tema and
												evaluacion_proveedor.id_modalidad=componente_x_modalidad.id_modalidad and
												evaluacion_proveedor.id_componente=componente_x_modalidad.id_componente and
												evaluacion_proveedor.id_acta='$id_acta' and
												evaluacion_proveedor.estado='1'
												group by id_subtema
												) as operacion
												RIGHT JOIN evaluacion_proveedor ON evaluacion_proveedor.id_subtema= operacion.id_subtema
												WHERE
												evaluacion_proveedor.id_acta='$id_acta'
												) as promedio
												WHERE
												promedio.id_acta='$id_acta'
												group by id_subtema
												"),$conexion);

												$cantidad = 0;
												$id_evaluacion_acta = array();
												$porc_referencia_acta = array();
												$porc_inicial_acta = array();
												$porc_final_acta = array();
												$porc_componente_x_final_acta = array();

												while ($row=mysql_fetch_assoc($queryporcentaje_acta)) {
													$cantidad = $cantidad + 1;
													$id_evaluacion_acta[] = $row["id_evaluacion"];
													$porc_referencia_acta[]=$row['porc_referencia'];
													$porc_inicial_acta[]=$row['porc_inicial'];
													$porc_final_acta[]=$row['porc_final'];
													$porc_componente_x_final_acta[]=$row['porc_componente_x_final'];
												}

												$suma_porc_referencia_acta=array_sum($porc_referencia_acta);
												$suma_inicial_acta = array_sum($porc_inicial_acta);
												$suma_porc_inicial_acta=array_sum($porc_inicial_acta)/$cantidad;
												$suma_porc_final_acta=array_sum($porc_final_acta)/$cantidad;
												$suma_porc_componente_x_final_acta=array_sum($porc_componente_x_final_acta)/$suma_porc_referencia_acta;

												//Update: Actualizar porcentajes de evaluacion de tabla acta_proveedor
												$update_porcentaje_acta = mysql_query("
												UPDATE acta_proveedor
												SET
												porc_referencia='$suma_porc_referencia_acta',
												porc_inicial='$suma_porc_inicial_acta',
												porc_final='$suma_porc_final_acta',
												porc_componente_x_final='$suma_porc_componente_x_final_acta'
												where id_acta='$id_acta'
												");
												$updateporc= mysql_query($update_porcentaje_acta,$conexion);


												//Insert: Hallazgos en la tabla subsanacion_proveedor
												for ($i=0; $i < count($id_pregunta);$i++)
												{
													if($valor_calificacion[$i]==2 || $valor_calificacion[$i]==3 || $valor_calificacion[$i]==4 ){
														if($valor_calificacion[$i]==4){
															$etapa="OK";
															$historico="AC";
														}
														else {
															$etapa="OK";
															$historico="AC";
														}
														$query=		"INSERT INTO subsanacion_proveedor
														(
															id_acta,
															id_pregunta,
															descripcion_pregunta,
															descripcion_observacion,
															descripcion_accion_correctiva,
															id_componente,
															id_proveedor,
															id_modalidad,
															id_interventor,
															fecha_subsanacion,
															fecha_subsanacion_final,
															etapa,
															historico,
															estado
														)
														values
														(
															'$id_acta',
															'$id_pregunta[$i]',
															'$descripcion_pregunta[$i]',
															'$descripcion_observacion[$i]',
															'$descripcion_accion_correctiva[$i]',
															'$id_componente',
															'$id_proveedor',
															'$id_modalidad',
															'$id_interventor',
															'$fecha_evaluacion',
															'$fecha_evaluacion',
															'$etapa',
															'$historico',
															'$estado'
														)";
														$insertreg= mysql_query($query,$conexion);
													} //End if
												} //End for


												return TRUE;




											} else {
												return FALSE;
											}

										}



										function inserthallazgos(	//Ya no se utiliza
											$id_acta,
											$id_pregunta,
											$descripcion_pregunta,
											$descripcion_observacion,
											$descripcion_accion_correctiva,
											$id_componente,
											$id_contrato,
											$id_prestador,
											$id_modalidad,
											$id_sede,
											$fecha_subsanacion,
											$fecha_subsanacion_final,
											$subsanada,
											$etapa,
											$estado,
											$fecha_observacion_evaluador,
											$descripcion_observacion_evaluador,
											$id_interventor,
											$fecha_observacion_usuario,
											$descripcion_observacion_usuario
										){

											include("conexion.php");

											//Insert: Crear Observacion Interventor
											$queryobsinterventor=	"INSERT INTO observacion_evaluador
											(
												fecha_observacion_evaluador,
												id_acta,
												descripcion_observacion_evaluador,
												id_interventor,
												estado
											)
											values
											(
												'$fecha_observacion_evaluador',
												'$id_acta',
												'$descripcion_observacion_evaluador',
												'$id_interventor',
												'$estado'
											)";
											$insertqueryinterventor= mysql_query($queryobsinterventor,$conexion);

											//Insert: Crear Observacion Usuario
											$queryobsusuario=	"INSERT INTO observacion_usuario
											(
												fecha_observacion_usuario,
												id_acta,
												descripcion_observacion_usuario,
												id_interventor,
												estado
											)
											values
											(
												'$fecha_observacion_usuario',
												'$id_acta',
												'$descripcion_observacion_usuario',
												'$id_interventor',
												'$estado'
											)";
											$insertqueryusuario= mysql_query($queryobsusuario,$conexion);


											//Insert: Registros en Subsanacion
											if($id_pregunta==0){}
												else{
													for ($i=0; $i < count($id_pregunta);$i++)
													{

														$query=		"INSERT INTO subsanacion
														(
															id_acta,
															id_pregunta,
															descripcion_pregunta,
															descripcion_observacion,
															descripcion_accion_correctiva,
															id_componente,
															id_contrato,
															id_prestador,
															id_modalidad,
															id_sede,
															id_interventor,
															fecha_subsanacion,
															fecha_subsanacion_final,
															subsanada,
															etapa,
															estado
														)
														values
														(
															'$id_acta',
															'$id_pregunta[$i]',
															'$descripcion_pregunta[$i]',
															'$descripcion_observacion[$i]',
															'$descripcion_accion_correctiva[$i]',
															'$id_componente',
															'$id_contrato',
															'$id_prestador',
															'$id_modalidad',
															'$id_sede',
															'$id_interventor',
															'$fecha_subsanacion[$i]',
															'$fecha_subsanacion_final[$i]',
															'$subsanada',
															'$etapa',
															'$estado'
														)";
														$insertreg= mysql_query($query,$conexion);

													} //Fin For

												}//Fin else

												//Pregunta para retornar exitoso el mensaje al usuario
												if(mysql_affected_rows() > 0){
													return TRUE;
												} else {
													return FALSE;
												}

											}




											function insertreservaradicado(
												$id_acta,
												$descripcion_reserva,
												$fecha_reserva,
												$id_interventor,
												$id_componente,
												$estado,
												$consecutivo_acta
											){

												include("conexion.php");

												//Insert: Insertar una reserva de radicado
												$queryreserva=	"INSERT INTO reserva_radicado
												(
													id_acta,
													descripcion_reserva,
													fecha_reserva,
													id_interventor,
													id_componente,
													estado
												)
												values
												(
													'$id_acta',
													'$descripcion_reserva',
													'$fecha_reserva',
													'$id_interventor',
													'$id_componente',
													'$estado'
												)";
												$insertreserva= mysql_query($queryreserva,$conexion);

												//Pregunta para retornar exitoso el mensaje al usuario
												if(mysql_affected_rows() > 0){

													//Update: Aumentar consecutivo en la tabla componente
													$update_consecutivo = mysql_query("
													UPDATE componente SET consecutivo_acta='$consecutivo_acta'
													where id_componente='$id_componente'
													");
													$updatereg= mysql_query($update_consecutivo,$conexion);


													return TRUE;
												} else {
													return FALSE;
												}

											}



											function insertactafallida(
												$id_componente,
												$id_acta,
												$id_contrato,
												$id_sede,
												$id_prestador,
												$id_modalidad,
												$id_interventor,
												$fecha_evaluacion,
												$hora_inicio,
												$hora_fin,
												$estado,
												$consecutivo_acta,
												$nombre_sede,
												$direccion_sede,
												$telefono_sede,
												$observacion_interventor,
												$acta_reservada
											){

												include("conexion.php");

												//Insert: Crear registro del acta
												$insert_acta="INSERT INTO acta_fallida
												(
													id_acta,
													fecha_evaluacion,
													hora_inicio,
													hora_fin,
													id_componente,
													id_contrato,
													id_prestador,
													id_modalidad,
													id_sede,
													nombre_sede,
													direccion_sede,
													telefono_sede,
													observacion_interventor,
													id_interventor,
													estado
												)
												values
												(
													'$id_acta',
													'$fecha_evaluacion',
													'$hora_inicio',
													'$hora_fin',
													'$id_componente',
													'$id_contrato',
													'$id_prestador',
													'$id_modalidad',
													'$id_sede',
													'$nombre_sede',
													'$direccion_sede',
													'$telefono_sede',
													'$observacion_interventor',
													'$id_interventor',
													'$estado'
												)";
												$insertacta= mysql_query($insert_acta,$conexion);

												$insertgastos_desplazamiento = "INSERT INTO gastos_desplazamiento
												(
													id_acta,
													id_interventor,
													fecha_evaluacion,
													pago_desplazamiento) values
													(
														'$id_acta',
														'$id_interventor',
														'$fecha_evaluacion',
														'6000'
													)";

													$insert_gastos = mysql_query($insertgastos_desplazamiento,$conexion);

													if(mysql_affected_rows() > 0){

														if($acta_reservada=="NO"){
															//Update: Aumentar consecutivo en la tabla componente
															$update_consecutivo = mysql_query("
															UPDATE componente SET consecutivo_acta='$consecutivo_acta'
															where id_componente='$id_componente'
															");
															$updatereg= mysql_query($update_consecutivo,$conexion);
														}
														else{
															$query=	" UPDATE reserva_radicado SET estado='0' where id_acta='$id_acta'";
															$updatereg= mysql_query($query,$conexion);
														}

														return TRUE;
													} else {
														return FALSE;
													}

												}

												function insertdescuentosxvalorecion(
													$id_prestador,
													$id_contrato,
													$id_sede,
													$id_modalidad,
													$tipo_descuento,
													$fecha,
													$usuario
												){
													include("conexion.php");

													//Insert: Crear descuentos x valoracion
													$insert_descuento ="
													INSERT INTO descuentos_x_valoracion
													(
														id_prestador,
														id_modalidad,
														id_contrato,
														id_sede,
														tipo_descuento,
														porc_descuento,
														id_interventor,
														fecha
														)
														values
														(
															'$id_prestador',
															'$id_modalidad',
															'$id_contrato',
															'$id_sede',
															'$tipo_descuento',
															'0',
															'$usuario',
															'$fecha'
															)";
															$insert = mysql_query($insert_descuento,$conexion);
															return TRUE;
														}

														function insertimagen(
															$id_acta,
															$id_interventor,
															$fecha_archivo,
															$descripcion_archivo,
															$ruta_temporal,
															$folder,
															$filename,
															$match1,
															$match2,
															$match3,
															$error1,
															$error2,
															$error3,
															$estado
														){

															include("conexion.php");

															//Carga del archivo al servidor
															if($match1==1 && $match2==1 && $match3==1)
															{
																if(move_uploaded_file($ruta_temporal, $folder.$filename)){


																	//Insert: Crear registro de la imagen
																	$insert_archivo="
																	INSERT INTO archivo
																	(
																		id_acta,
																		id_interventor,
																		fecha_archivo,
																		descripcion_archivo,
																		nombre_archivo,
																		estado
																		)
																		values
																		(
																			'$id_acta',
																			'$id_interventor',
																			'$fecha_archivo',
																			'$descripcion_archivo',
																			'$filename',
																			'1'
																			)";
																			$insert= mysql_query($insert_archivo,$conexion);

																			return TRUE;

																		}else{
																			return FALSE;
																		}

																	}else{

																		return FALSE;
																	}


																}


																function insertdescuentos($acta, $fecha, $interventor, $matriculados, $asistentes, $alimentacion, $descripcion, $medida, $alimento, $detallealimento, $faltante, $grupoedad, $total, $observaciones, $estado, $usuario) {
																	include("conexion.php");

																	//Insert: Crear registro de descuento de nutrición
																	$query_desc="
																	REPLACE INTO descuentos(id_acta,fecha_acta, interventor, matriculados, asistentes, alimentacion, descripcion, unidad, alimento, detallealimento, faltante, grupo, descontar, observaciones, estado, usuario) VALUES ('$acta', '$fecha', '$interventor', '$matriculados', '$asistentes', '$alimentacion', '$descripcion', '$medida', '$alimento', '$detallealimento', '$faltante', '$grupoedad', '$total', '$observaciones', $estado, '$usuario')";
																	$insert_desc= mysql_query($query_desc,$conexion);

																	//Pregunta para retornar exitoso el mensaje al usuario
																	if(mysql_affected_rows() > 0){
																		return TRUE;
																	} else {
																		return FALSE;
																	}
																}

																function calificacion($semaforo, $contrato, $tema, $mes, $pregunta, $respuesta, $comentario, $tipo, $usuario) {
																	include("conexion.php");

																	//Insert: Crear registro Semaforo de Gestión Institucional
																	$query_desc="REPLACE INTO calificacion(idSemaforo, idContrato, idTema, idMes, idCriterio, evaluacion, comentario, tipocoment, estado, usuario) VALUES ('$semaforo', '$contrato', $tema, '$mes', '$pregunta', '$respuesta', '$comentario', '$tipo', '1', '$usuario')";
																	$insert_desc= mysql_query($query_desc,$conexion);

																	//Pregunta para retornar exitoso el mensaje al usuario
																	if(mysql_affected_rows() > 0){
																		return TRUE;
																	} else {
																		return FALSE;
																	}
																}

																function insertobligacionescontratos($id_modalidad, $obligacion, $observacion){
																	include("conexion.php");
																	$query=		"INSERT INTO obligaciones
																	(id_modalidad,obligacion,observacion,estado)
																	values
																	('$id_modalidad','$obligacion','$observacion','1')";
																	$insertreg= mysql_query($query,$conexion);
																	if(mysql_affected_rows() > 0){
																		return TRUE;
																	} else {
																		return FALSE;
																	}
																}



																//UPDATES
																function updateusuarios($id,$username,$password,$documento,$first_name,$last_name,$email,$phone,$id_componente,$id_group,$created_on,$active,$foto){
																	include("conexion.php");

																	$query = mysql_query("
																	UPDATE
																	users
																	SET
																	username='$username',
																	password='$password',
																	documento='$documento',
																	first_name='$first_name',
																	last_name='$last_name',
																	email='$email',
																	phone='$phone',
																	id_componente='$id_componente',
																	id_group='$id_group',
																	created_on='$created_on',
																	active='$active',
																	foto='$foto'
																	where id='$id'
																	");
																	$insertreg= mysql_query($query,$conexion);


																	return TRUE;


																}


																function updatepregunta($id_pregunta,$descripcion_pregunta){
																	include("conexion.php");
																	$suma=0;
																	for ($i=0; $i < count($descripcion_pregunta);$i++)
																	{
																		if($descripcion_pregunta[$i]==""){}
																			else{
																				$query = mysql_query("
																				UPDATE pregunta SET descripcion_pregunta='$descripcion_pregunta[$i]'
																				where id_pregunta='$id_pregunta[$i]'
																				");
																				$insertreg= mysql_query($query,$conexion);
																				$suma=$suma+1;
																			}

																		}
																		if($suma > 0){
																			return TRUE;
																		} else {
																			return FALSE;
																		}

																	}


																	function update_pre_obs_ac($id_pregunta,$descripcion_pregunta,$descripcion_observacion,$descripcion_accion_correctiva){
																		include("conexion.php");
																		$suma=0;
																		for ($i=0; $i < count($descripcion_pregunta);$i++)
																		{
																			if($descripcion_pregunta[$i]==""){}
																				else{
																					$query = mysql_query("
																					UPDATE pregunta SET descripcion_pregunta='$descripcion_pregunta[$i]',descripcion_observacion='$descripcion_observacion[$i]', descripcion_accion_correctiva='$descripcion_accion_correctiva[$i]'
																					where id_pregunta='$id_pregunta[$i]'
																					");
																					$insertreg= mysql_query($query,$conexion);
																					$suma=$suma+1;
																				}

																			}
																			if($suma > 0){
																				return TRUE;
																			} else {
																				return FALSE;
																			}

																		}

																		function updatepagodesplazamiento($id,$id_acta, $pago_desplazamiento, $justificacion, $transporte_interventoria){
																			include("conexion.php");
																			for ($i=0; $i < count($id); $i++) {
																				$query = mysql_query("
																				UPDATE gastos_desplazamiento SET pago_desplazamiento='$pago_desplazamiento[$i]', justificacion='$justificacion[$i]', transporte_interventoria='$transporte_interventoria[$i]'
																				WHERE id='$id[$i]'");
																				$update = mysql_query($query, $conexion);
																			}
																			return TRUE;
																		}

																		function aceptartodospagodesplazamiento($id,$id_acta, $pago_desplazamiento, $justificacion, $transporte_interventoria){
																			include("conexion.php");
																			for ($i=0; $i < count($id); $i++) {
																				$query = mysql_query("
																				UPDATE gastos_desplazamiento SET pago_desplazamiento='$pago_desplazamiento[$i]', justificacion='$justificacion[$i]', transporte_interventoria='$transporte_interventoria[$i]', estado = 1
																				WHERE id='$id[$i]'");
																				$update = mysql_query($query, $conexion);
																			}
																			return TRUE;
																		}

																		function updateevaluacion(
																			$id_evaluacion_hallazgo,
																			$valor_calificacion_final,
																			$id_acta,
																			$id_pregunta,
																			$descripcion_observacion,
																			$descripcion_accion_correctiva,
																			$id_subsanacion,
																			$fecha_subsanacion,
																			$fecha_subsanacion_final,
																			$fecha_solicitud_aclaracion,
																			$id_radicado_osa,
																			$fecha_requerimiento,
																			$id_radicado_orq,
																			$fecha_envio_evidencia,
																			$etapa_anterior,
																			$etapa

																		){
																			include("conexion.php");
																			$suma=0;
																			for ($i=0; $i < count($id_evaluacion_hallazgo);$i++)
																			{

																				//cambiar el valor de calificacion final por cada una de las preguntas
																				$query = mysql_query("
																				UPDATE evaluacion SET valor_calificacion_final='$valor_calificacion_final[$i]'
																				where id_evaluacion='$id_evaluacion_hallazgo[$i]'
																				");
																				$insertreg= mysql_query($query,$conexion);

																				//
																				if($etapa[$i]=="OK"){
																					$historico=$etapa_anterior[$i];
																				}else{
																					$historico=$etapa[$i];
																				}

																				//cambiar los valores del debido proceso en subsanacion
																				$querysubsanada = mysql_query("
																				UPDATE subsanacion SET
																				descripcion_observacion='$descripcion_observacion[$i]',
																				descripcion_accion_correctiva='$descripcion_accion_correctiva[$i]',
																				fecha_subsanacion='$fecha_subsanacion[$i]',
																				fecha_subsanacion_final='$fecha_subsanacion_final[$i]',
																				fecha_solicitud_aclaracion='$fecha_solicitud_aclaracion[$i]',
																				fecha_requerimiento='$fecha_requerimiento[$i]',
																				fecha_envio_evidencia='$fecha_envio_evidencia[$i]',
																				id_radicado_osa='$id_radicado_osa[$i]',
																				id_radicado_orq='$id_radicado_orq[$i]',
																				historico='$historico',
																				etapa='$etapa[$i]'
																				where
																				id_subsanacion='$id_subsanacion[$i]'
																				");
																				$insertsubsanada= mysql_query($querysubsanada,$conexion);


																				$suma=$suma+1;
																			}

																			//RECALCULAR DE NUEVO LOS VALORES PORCENTUALES DE EVALUACION PARA EL ACTA
																			//Query para hallar la suma de porcentajes evaluación
																			$queryporcentaje_evaluacion= mysql_query(("
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

																				while($row=mysql_fetch_assoc($queryporcentaje_evaluacion)){
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

																				for ($i=0; $i < count($id_evaluacion);$i++)
																				{
																					$update_porcentaje_evaluacion = mysql_query("
																					UPDATE evaluacion
																					SET
																					porc_referencia='$porc_referencia[$i]',
																					porc_inicial='$porc_inicial[$i]',
																					porc_final='$porc_final[$i]',
																					porc_componente_x_final='$porc_componente_x_final[$i]'
																					where id_evaluacion='$id_evaluacion[$i]'
																					");
																					$updateporc= mysql_query($update_porcentaje_evaluacion,$conexion);
																				} //End For

																				//Query para hallar la suma de porcentajes evaluación
																				$queryporcentaje_acta = mysql_query(("
																				select
																			  sum(suma_tema.porc_inicial)/ sum(porc_referencia) as porc_inicial,
																			  sum(suma_tema.porc_final)/ sum(porc_referencia) as porc_final,
																			  suma_tema.id_tema
																			  FROM
																			  (
																			    SELECT
																			    promedio.id_evaluacion,
																			    promedio.id_pregunta,
																			    promedio.id_subtema,
																			    promedio.referencia,
																			    promedio.valor_calificacion,
																			    promedio.valor_calificacion_final,
																			    sum(promedio.porc_referencia) as porc_referencia,
																			    round(sum(promedio.porc_inicial),4) as porc_inicial,
																			    round(sum(promedio.porc_final),4) as porc_final,
																			    round(sum(promedio.porc_componente_x_final),4) as porc_componente_x_final,
																			    promedio.id_tema
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
																			      round((if(evaluacion.valor_calificacion_final=1,porcentaje_x_pregunta,if(evaluacion.valor_calificacion_final=2,porcentaje_x_pregunta*0.6,0)))*operacion.porc_componente_x_modalidad,4) porc_componente_x_final,
																			      operacion.id_tema
																			      FROM
																			      (
																			        SELECT
																			        evaluacion.id_subtema,
																			        componente_x_modalidad.porc_componente_x_modalidad,
																			        estandar_x_modalidad.porc_estandar_x_modalidad,
																			        count(evaluacion.id_pregunta) cantidad_preguntas,
																			        (estandar_x_modalidad.porc_estandar_x_modalidad/count(evaluacion.id_pregunta)) porcentaje_x_pregunta,
																			        evaluacion.id_tema
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
																			        ) as promedio
																			        WHERE
																			        promedio.id_acta='$id_acta'
																			        group by id_subtema) as suma_tema
																			        group by suma_tema.id_tema

																						"),$conexion);

																						$cantidad = 0;
																						$porc_referencia_acta = array();
																						$porc_inicial_acta = array();
																						$porc_final_acta = array();

																						while ($row=mysql_fetch_assoc($queryporcentaje_acta)) {
																							$cantidad = $cantidad + 1;
																							// $id_evaluacion_acta[] = $row["id_evaluacion"];
																							$porc_referencia_acta[]=$row['porc_referencia'];
																							$porc_inicial_acta[]=$row['porc_inicial'];
																							$porc_final_acta[]=$row['porc_final'];
																							// $porc_componente_x_final_acta[]=$row['porc_componente_x_final'];
																						}

																						$suma_porc_referencia_acta=array_sum($porc_referencia_acta);
																						$suma_inicial_acta = array_sum($porc_inicial_acta);
																						$suma_porc_inicial_acta=array_sum($porc_inicial_acta)/$cantidad;
																						$suma_porc_final_acta=array_sum($porc_final_acta)/$cantidad;
																						// $suma_porc_componente_x_final_acta=array_sum($porc_componente_x_final_acta)/$suma_porc_referencia_acta;

																						//Update: Actualizar porcentajes de evaluacion de tabla acta

																						$update_porcentaje_acta = mysql_query("
																						UPDATE acta
																						SET
																						/*porc_referencia='$suma_porc_referencia_acta',*/
																						porc_inicial='$suma_porc_inicial_acta',
																						porc_final='$suma_porc_final_acta'/*,
																						porc_componente_x_final='$suma_porc_componente_x_final_acta'*/
																						where id_acta='$id_acta'
																						");
																						$updateporc= mysql_query($update_porcentaje_acta,$conexion);



																						if($suma > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}

																					}


																					function updateevaluacionproveedor(
																						//$id_evaluacion_hallazgo,
																						//$valor_calificacion_final,
																						//$id_pregunta,
																						//$descripcion_accion_correctiva,
																						//$fecha_subsanacion,
																						//$fecha_subsanacion_final,
																						//$fecha_solicitud_aclaracion,
																						//$id_radicado_osa,
																						//$fecha_requerimiento,
																						//$id_radicado_orq,
																						//$fecha_envio_evidencia,
																						//$etapa_anterior,
																						//$etapa,
																						$id_acta,
																						$descripcion_observacion,
																						$id_subsanacion

																					){
																						include("conexion.php");
																						$suma=0;
																						for ($i=0; $i < count($id_subsanacion);$i++)
																						{

																							//cambiar los valores del debido proceso en subsanacion
																							$querysubsanada = mysql_query("
																							UPDATE subsanacion_proveedor SET
																							descripcion_observacion='$descripcion_observacion[$i]'
																							where
																							id_subsanacion='$id_subsanacion[$i]'
																							");
																							$insertsubsanada= mysql_query($querysubsanada,$conexion);


																							$suma=$suma+1;
																						}

																						/*
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

																					for ($i=0; $i < count($id_evaluacion);$i++)
																					{
																					$update_porcentaje_evaluacion = mysql_query("
																					UPDATE evaluacion
																					SET
																					porc_referencia='$porc_referencia[$i]',
																					porc_inicial='$porc_inicial[$i]',
																					porc_final='$porc_final[$i]',
																					porc_componente_x_final='$porc_componente_x_final[$i]'
																					where id_evaluacion='$id_evaluacion[$i]'
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

																				*/

																				if($suma > 0){
																					return TRUE;
																				} else {
																					return FALSE;
																				}

																			}



																			function updatesemaforo(
																				$id_semaforo,
																				$id_contrato,
																				$id_prestador,
																				$id_modalidad,
																				$id_componente,
																				$id_tema,
																				$id_ano,
																				$id_mes,
																				$incumplimiento_grave,
																				$nt1,
																				$nt2,
																				$nt3,
																				$gi1,
																				$gi2,
																				$gi3,
																				$gi4,
																				$gi5,
																				$gi6,
																				$gi7,
																				$descripcion_incumplimiento_grave,
																				$logros_prestador,
																				$dificultades_prestador,
																				$debido_proceso,
																				$asistencia_tecnica,
																				$otras_observaciones,
																				$porc_calidad,
																				$porc_deb_proc,
																				$porc_descuento,
																				$id_interventor,
																				$estado
																			){
																				include("conexion.php");

																				$query = mysql_query("
																				REPLACE INTO
																				semaforo
																				(
																					id_semaforo,
																					id_contrato,
																					id_prestador,
																					id_modalidad,
																					id_componente,
																					id_tema,
																					id_ano,
																					id_mes,
																					incumplimiento_grave,
																					nt1,
																					nt2,
																					nt3,
																					gi1,
																					gi2,
																					gi3,
																					gi4,
																					gi5,
																					gi6,
																					gi7,
																					descripcion_incumplimiento_grave,
																					logros_prestador,
																					dificultades_prestador,
																					debido_proceso,
																					asistencia_tecnica,
																					otras_observaciones,
																					porc_calidad,
																					porc_deb_proc,
																					porc_descuento,
																					id_interventor,
																					estado
																					)
																					VALUES
																					(
																						'$id_semaforo',
																						'$id_contrato',
																						'$id_prestador',
																						'$id_modalidad',
																						'$id_componente',
																						'$id_tema',
																						'$id_ano',
																						'$id_mes',
																						'$incumplimiento_grave',
																						'$nt1',
																						'$nt2',
																						'$nt3',
																						'$gi1',
																						'$gi2',
																						'$gi3',
																						'$gi4',
																						'$gi5',
																						'$gi6',
																						'$gi7',
																						'$descripcion_incumplimiento_grave',
																						'$logros_prestador',
																						'$dificultades_prestador',
																						'$debido_proceso',
																						'$asistencia_tecnica',
																						'$otras_observaciones',
																						'$porc_calidad',
																						'$porc_deb_proc',
																						'$porc_descuento',
																						'$id_interventor',
																						'$estado'
																						)
																						");
																						$insertreg= mysql_query($query,$conexion);


																						return TRUE;


																					}


																					function update_reporte_semaforo(
																						$id_semaforo,
																						$logros_prestador,
																						$dificultades_prestador,
																						$debido_proceso,
																						$asistencia_tecnica,
																						$otras_observaciones,
																						$descripcion_incumplimiento_grave
																					){
																						include("conexion.php");

																						$suma=0;
																						for ($i=0; $i < count($id_semaforo);$i++)
																						{

																							$query = mysql_query("
																							UPDATE
																							semaforo
																							SET
																							logros_prestador='$logros_prestador[$i]',
																							dificultades_prestador='$dificultades_prestador[$i]',
																							debido_proceso='$debido_proceso[$i]',
																							asistencia_tecnica='$asistencia_tecnica[$i]',
																							otras_observaciones='$otras_observaciones[$i]',
																							descripcion_incumplimiento_grave='$descripcion_incumplimiento_grave[$i]'
																							WHERE
																							id_semaforo='$id_semaforo[$i]'
																							");
																							$insertreg= mysql_query($query,$conexion);
																							$suma=$suma+1;


																						}
																						if($suma > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}


																					}

																					function update_estado_gastos_desplazamiento($id, $valor, $justificacion, $transporte_interventoria, $estado){
																						include("conexion.php");
																						$query = mysql_query("
																						UPDATE
																						gastos_desplazamiento
																						SET
																						pago_desplazamiento='$valor',
																						justificacion='$justificacion',
																						transporte_interventoria='$transporte_interventoria',
																						estado='$estado'
																						WHERE
																						id='$id'
																						");
																						$insertreg= mysql_query($query,$conexion);
																						return TRUE;
																					}

																					function update_descuentos_x_valoracion($id, $fecha_solicitud_aclaracion, $id_radicado_osa, $fecha_requerimiento, $id_radicado_orq, $fecha_envio_evidencia, $subsanacion, $fecha_descuento){
																						include("conexion.php");
																						for ($i=0; $i < count($id);$i++)
																						{
																							$query = mysql_query("
																							UPDATE
																							descuentos_x_valoracion
																							SET
																							fecha_solicitud_aclaracion='$fecha_solicitud_aclaracion[$i]',
																							id_radicado_osa='$id_radicado_osa[$i]',
																							fecha_requerimiento='$fecha_requerimiento[$i]',
																							id_radicado_orq='$id_radicado_orq[$i]',
																							fecha_envio_evidencia='$fecha_envio_evidencia[$i]',
																							subsanacion='$subsanacion[$i]',
																							fecha ='$fecha_descuento[$i]'
																							WHERE
																							id='$id[$i]'
																							");
																							$insertreg= mysql_query($query,$conexion);
																						}
																						return TRUE;
																					}

																					function updateobligacionescontratos($id, $obligacion, $observacion){
																						include("conexion.php");
																						for ($i=0; $i < count($id);$i++)
																						{
																							$query = mysql_query("
																							UPDATE
																							obligaciones
																							SET
																							obligacion = '$obligacion[$i]',
																							observacion = '$observacion[$i]'
																							WHERE
																							id='$id[$i]'
																							");
																							$insertreg= mysql_query($query,$conexion);
																						}
																						return TRUE;
																					}

																					function update_estado_descuentos_x_valoracion($id_descuento, $tipo_descuento, $descuento){
																						include("conexion.php");
																						$query = mysql_query("
																						UPDATE
																						descuentos_x_valoracion
																						SET
																						estado = 1,
																						subsanacion = 1
																						WHERE id = '$id_descuento'
																						");
																						$insertreg= mysql_query($query,$conexion);
																						return TRUE;
																					}

																				function insertinformefinal($id, $id_contrato, $id_prestador, $id_modalidad, $id_componente, $id_tema, $observacion_contrato, $usuario){
																												include("conexion.php");
																												if(is_array($id) == true){
																													for ($i=0; $i < count($id);$i++) {
																														$query= "UPDATE informes_finales SET observacion_contrato= '$observacion_contrato[$i]' WHERE id='$id[$i]'";
																														$insertreg= mysql_query($query,$conexion);
																													}

																													if(mysql_affected_rows() > 0){
																														return TRUE;
																													} else {
																														return FALSE;
																													}

																												}else{
																													if ($id > 0 || $id != null) {
																														$query= "UPDATE informes_finales SET observacion_contrato= '$observacion_contrato' WHERE id='$id'";
																														$insertreg= mysql_query($query,$conexion);
																													}else {
																														$query=	"INSERT INTO informes_finales
																														(id_contrato, id_prestador, id_modalidad, id_componente, id_tema, observacion_contrato, fecha_registro, id_interventor, estado)
																														values
																														('$id_contrato', '$id_prestador', '$id_modalidad', '$id_componente', '$id_tema', '$observacion_contrato', NOW(), '$usuario', '1')";
																														$insertreg= mysql_query($query,$conexion);
																													}

																													if(mysql_affected_rows() > 0){
																														return TRUE;

																													} else {
																														return FALSE;
																													}

																												}

																											}


																					function insertinformeliquidacion($id, $id_contrato, $id_prestador, $id_modalidad, $id_componente, $observacion_contrato, $usuario){
																												include("conexion.php");
																												if (is_array($id) == true) {
																													for ($i=0; $i < count($id);$i++) {
																														$query= "UPDATE informe_liquidacion SET observacion_contrato= '$observacion_contrato[$i]' WHERE id='$id[$i]'";
																														$insertreg= mysql_query($query,$conexion);
																													}
																													if(mysql_affected_rows() > 0){
																														return TRUE;
																													} else {
																														return FALSE;
																													}
																												}else {
																													if ($id > 0 || $id != null) {
																														$query= "UPDATE informe_liquidacion SET observacion_contrato= '$observacion_contrato' WHERE id='$id'";
																														$insertreg= mysql_query($query,$conexion);
																													}else {
																														$query=	"INSERT INTO informe_liquidacion
																														(id_contrato, id_prestador, id_modalidad, id_componente, observacion_contrato, fecha_registro, id_interventor, estado)
																														values
																														('$id_contrato', '$id_prestador', '$id_modalidad', '$id_componente', '$observacion_contrato', NOW(), '$usuario', '1')";
																														$insertreg= mysql_query($query,$conexion);
																													}
																													if(mysql_affected_rows() > 0){
																														return TRUE;
																													} else {
																														return FALSE;
																													}
																												}
																											}


																					//DELETES
																					function deleteusuario($id_eliminar){
																						include("conexion.php");
																						$query=	" UPDATE users SET active='0' where id='$id_eliminar'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}
																					}


																					function deletetema($id_eliminar){
																						include("conexion.php");
																						$query=	" UPDATE tema SET estado='0' where id_tema='$id_eliminar'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}
																					}

																					function deletesubtema($id_eliminar){
																						include("conexion.php");
																						$query=	" UPDATE subtema SET estado='0' where id_subtema='$id_eliminar'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}
																					}

																					function deletepreguntaxmodalidad($id_eliminar){
																						include("conexion.php");
																						$query=	" UPDATE pregunta_x_modalidad SET estado='0' where id_pregunta_x_modalidad='$id_eliminar'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}
																					}

																					function deleteacta($id_eliminar){
																						include("conexion.php");
																						$query=	" UPDATE acta SET estado='0' where id_acta='$id_eliminar'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return TRUE;
																						}
																					}

																					function deleteactaproveedor($id_eliminar){
																						include("conexion.php");
																						$query=	" UPDATE acta_proveedor SET estado='0' where id_acta='$id_eliminar'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return TRUE;
																						}
																					}


																					function deleteradicadoreservado($id_eliminar){
																						include("conexion.php");
																						$query=	" UPDATE reserva_radicado SET estado='0' where id_reserva_radicado='$id_eliminar'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}
																					}

																					function deletedescuento($id_descuento){
																						include("conexion.php");
																						$query = "DELETE FROM descuentos_x_valoracion WHERE id='$id_descuento'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}
																					}

																					function deleteactadescuentos($id_eliminar){
																						include("conexion.php");
																						$query=	" UPDATE descuentos SET estado='0' where id='$id_eliminar'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}
																					}

																					function actualizarfirma($id_acta, $firma, $firma2){
																						include("conexion.php");
																						$query=	" UPDATE acta SET firma='$firma', firma2='$firma2' where id_acta='$id_acta'";
																						$deletereg= mysql_query($query,$conexion);
																						if(mysql_affected_rows() > 0){
																							return TRUE;
																						} else {
																							return FALSE;
																						}
																					}



																				}


																				?>
