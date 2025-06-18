<?php session_start();
if ($_SESSION['login'])
{

	//Includes de conexion a la BD y a las funciones
	include "conexion.php";
	include("./lib/clsFunciones.php");


	//Variables Globales
	$id_grupo=$_SESSION["grupo"];
	$id_componente=$_SESSION["componente"];
	$nombre=$_SESSION["nombre_usuario"];


	//Casos para insertar de acuerdo a su origen
	//caso 1: Insertar Tema
	//caso 2: Insertar Subtema
	//caso 3: Insertar Pregunta
	//caso 4: Actualizar pregunta-Esta ya no se utiliza!!!
	//caso 5: Actualizar Preguntas, Hallazgos y Acciones Correctivas desde ConfigObservaciones
	//caso 6: Insertar evaluacion
	//caso 7: Actualizar evaluacion
	//caso 8: Insertar observacion interventor desde el acta creada
	//caso 9: Insertar observacion prestador desde el acta creada
	//caso 10: Insertar Hallazgos, acciones correctivas y Observaciones desde Evaluacion- Ya no se utiliza!!!
	//caso 11: Insertar usuarios
	//caso 12: Actualizar usuarios
	//caso 13: Insertar reservas radicados
	//caso 14: Insertar Evaluaciones Fallidas
	//caso 15: Insertar/Editar observaciones en Semaforo
	//caso 16: Insertar Imagen
	//caso 17: Actualizar observaciones del semaforo desde reportes3
	//caso 18: Insertar evaluacion de proveedores
	//caso 19: Insertar hallazgo proveedores
	//caso 20: Insertar observacion interventor desde el acta creada (proveedores)
	//caso 21: Insertar observacion prestador desde el acta creada (proveedores)
	//caso 22: Insertar descuentos de nutrición
	//caso 23: Insertar semaforo de Gestión Institucional
	//caso 24: Insertar descuentos x valoraciones
	//caso 25: Actualziar pago desplazamiento

	$caso=$_POST['caso'];


	switch ($caso) {
		case "1":

		$nombre_tema=strtoupper($_POST['descripcion_tema']);
		$id_componente=$_POST['id_componente'];
		$porcentaje_tema=$_POST['porcentaje_tema'];
		$estado="1";
		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->inserttema($nombre_tema,$id_componente,$porcentaje_tema,$estado);
		$regresar="configtemas.php?";
		break;

		case "2":

		$nombre_subtema=$_POST['nombre_subtema'];
		$id_componente=$_POST['id_componente'];
		$id_tema=$_POST['id_tema'];
		$estado="1";
		$modalidades= $_POST['modalidades'];
		$porcentaje_modalidad= $_POST['porcentaje_modalidad'];
		$clsFunciones = new clsFunciones;
	    $insert = $clsFunciones->insertsubtema($nombre_subtema,$id_componente,$id_tema,$estado,$modalidades,$porcentaje_modalidad);
		$regresar="configsubtemas.php?";
		break;


		case "3":

		$descripcion_pregunta=$_POST['descripcion_pregunta'];
		$id_componente=$_POST['id_componente'];
		$id_tema=$_POST['id_tema'];
		$id_subtema=$_POST['id_subtema'];
		$id_modalidad=$_POST['id_modalidad'];
		isset($_POST['tipo_acta'])== true? $tipo_acta=$_POST['tipo_acta']:$tipo_acta=0;
		if($tipo_acta==4) $tipo_acta=0;
		$estado="1";
		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertpregunta($descripcion_pregunta,$id_componente,$id_tema,$id_subtema,$id_modalidad,$estado, $tipo_acta);
		$regresar="configpreguntas.php?";
		break;


		case "4":

		$id_pregunta=$_POST['id_pregunta'];
		$descripcion_pregunta=$_POST['descripcion_pregunta'];
		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->updatepregunta($id_pregunta,$descripcion_pregunta);
		$regresar="configpreguntas.php?";
		break;


		case "5":

		$componente_selected=$_POST['componente_selected'];
		$id_tema=$_POST['id_tema'];
		$id_subtema=$_POST['id_subtema'];

		$id_pregunta=$_POST['id_pregunta'];
		$descripcion_pregunta=$_POST['descripcion_pregunta'];
		$descripcion_observacion=$_POST['descripcion_observacion'];
		$descripcion_accion_correctiva=$_POST['descripcion_accion_correctiva'];
		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->update_pre_obs_ac($id_pregunta,$descripcion_pregunta,$descripcion_observacion,$descripcion_accion_correctiva);
		$regresar="configobservaciones.php?componente_selected=".$componente_selected."&id_tema=".$id_tema;
		break;



		case "6":


		$id_componente=$_POST['id_componente'];
		$id_contrato=$_POST['id_contrato'];
		$id_sede=$_POST['id_sede'];
		$id_prestador=$_POST['id_prestador'];
		$id_modalidad=$_POST['id_modalidad'];
		$id_interventor=$_POST['id_interventor'];
		$fecha_evaluacion=$_POST['fecha_evaluacion'];
		$fecha_subsanacion=$_POST['fecha_subsanacion'];
		$id_tema=$_POST['id_tema'];
		$id_subtema=$_POST['id_subtema'];
		$id_pregunta=$_POST['id_pregunta'];
		$descripcion_pregunta=$_POST['descripcion_pregunta'];
		$descripcion_observacion=$_POST['descripcion_observacion'];
		$descripcion_accion_correctiva=$_POST['descripcion_accion_correctiva'];
		$valor_referencia="1";
		$valor_calificacion=$_POST['valor_calificacion'];
		$valor_calificacion_final=$_POST['valor_calificacion'];
		$observacion=$_POST['observacion'];
		$numero_visita=$_POST['numero_visita'];
		$hora_inicio=$_POST['hora_inicio'];
		$hora_fin=$_POST['hora_fin'];
		$estado='1';
		$nombre_sede=$_POST['nombre_sede'];
		$direccion_sede=$_POST['direccion_sede'];
		$telefono_sede=$_POST['telefono_sede'];
		$nombre_asistentes=$_POST['nombre_asistentes'];
		$informacion_complementaria=$_POST['informacion_complementaria'];
		$id_codigoacta=$_POST['id_codigoacta'];
		$acta_reservada=$_POST['acta_reservada'];
		$porc_componente_x_modalidad=$_POST['porc_componente_x_modalidad'];
		$pago_desplazamiento=$_POST['pago_desplazamiento'];
		$transporte_interventoria=$_POST['transporte_interventoria'];
		$justificacion=$_POST['justificacion'];
		$tema_encuentro=$_POST['tema_encuentro'];

		if($acta_reservada=="NO"){
			//Operaciones para hallar el id_acta
			$queryconsecutivo= mysql_query(("
			SELECT
			max(consecutivo_acta) consecutivo
			FROM
			componente
			WHERE
			id_componente='$id_componente' and
			estado='1'
			"),$conexion);
			$row=mysql_fetch_assoc($queryconsecutivo);
			$consecutivo_acta=$row['consecutivo']+1;
			$conversion_consecutivo=sprintf('%05d', $consecutivo_acta);
			$id_acta= "AVI-".$id_codigoacta."-".date("Y").$conversion_consecutivo;
		}
		else
		{
			$queryconsecutivo= mysql_query(("
			SELECT
			max(consecutivo_acta) consecutivo
			FROM
			componente
			WHERE
			id_componente='$id_componente' and
			estado='1'
			"),$conexion);
			$row=mysql_fetch_assoc($queryconsecutivo);
			$consecutivo_acta="";
			$id_acta=$_POST['id_acta'];
		}


		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertevaluacion(
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
		);

		$regresar="imprimiractas.php?id_acta=".$id_acta;
		break;


		case "7":

		$id_acta=$_POST['id_acta'];
		$id_pregunta=$_POST['id_pregunta'];
		$descripcion_observacion=$_POST['descripcion_observacion'];
		$descripcion_accion_correctiva=$_POST['descripcion_accion_correctiva'];
		$id_evaluacion_hallazgo=$_POST['id_evaluacion'];
		$id_subsanacion=$_POST['id_subsanacion'];
		$valor_calificacion_final=$_POST['valor_calificacion_final'];
		$fecha_subsanacion=$_POST['fecha_subsanacion'];
		$fecha_subsanacion_final=$_POST['fecha_subsanacion_final'];
		$fecha_solicitud_aclaracion=$_POST['fecha_solicitud_aclaracion'];
		$id_radicado_osa=$_POST['id_radicado_osa'];
		$fecha_requerimiento=$_POST['fecha_requerimiento'];
		$id_radicado_orq=$_POST['id_radicado_orq'];
		$fecha_envio_evidencia=$_POST['fecha_envio_evidencia'];
		$etapa=$_POST['etapa'];
		$etapa_anterior=$_POST['etapa_anterior'];





		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->updateevaluacion(
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

		);
		$regresar="imprimiractas.php?id_acta=".$id_acta;
		break;


		case "8":


		$id_acta=$_POST['id_acta'];
		$id_interventor=$_POST['id_interventor'];
		$fecha_observacion_evaluador=$_POST['fecha_observacion_evaluador'];
		$descripcion_observacion_evaluador=$_POST['descripcion_observacion_evaluador'];
		$estado='1';

		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertobsevaluador($id_acta,$fecha_observacion_evaluador,$descripcion_observacion_evaluador,$id_interventor,$estado);
		$regresar="imprimiractas.php?id_acta=".$id_acta;
		break;

		case "9":


		$id_acta=$_POST['id_acta'];
		$id_interventor=$_POST['id_interventor'];
		$fecha_observacion_usuario=$_POST['fecha_observacion_usuario'];
		$descripcion_observacion_usuario=$_POST['descripcion_observacion_usuario'];
		$estado='1';

		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertobsusuario($id_acta,$fecha_observacion_usuario,$descripcion_observacion_usuario,$id_interventor,$estado);
		$regresar="imprimiractas.php?id_acta=".$id_acta;
		break;

		case "10":


		$id_acta=$_POST['id_acta'];
		$id_pregunta=$_POST['id_pregunta'];
		$descripcion_pregunta=$_POST['descripcion_pregunta'];
		$descripcion_observacion=$_POST['descripcion_observacion'];
		$descripcion_accion_correctiva=$_POST['descripcion_accion_correctiva'];
		$id_componente=$_POST['id_componente'];
		$id_contrato=$_POST['id_contrato'];
		$id_prestador=$_POST['id_prestador'];
		$id_modalidad=$_POST['id_modalidad'];
		$id_sede=$_POST['id_sede'];
		$fecha_subsanacion=$_POST['fecha_subsanacion'];
		$fecha_subsanacion_final=$_POST['fecha_subsanacion'];
		$subsanada="NO";
		$etapa="AC";
		$estado='1';

		$fecha_observacion_evaluador=$_POST['fecha_observacion'];
		$descripcion_observacion_evaluador=$_POST['descripcion_observacion_evaluador'];
		$id_interventor=$_POST['id_interventor'];

		$fecha_observacion_usuario=$_POST['fecha_observacion'];
		$descripcion_observacion_usuario=$_POST['descripcion_observacion_usuario'];


		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->inserthallazgos(
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
		);


		$regresar="imprimiractas.php?id_acta=".$id_acta;

		break;


		case "11":

		$username=strtolower($_POST['username']);
		$password=md5(htmlspecialchars($_POST['password']));
		$documento = $_POST['documento'];
		$first_name=strtoupper($_POST['first_name']);
		$last_name=strtoupper($_POST['last_name']);
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		$id_componente=$_POST['id_componente'];
		$id_group=$_POST['id_group'];
		$foto = $_POST["foto"];
		$created_on= date("Y-m-d H:i:s");
		$active="1";
		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertusuarios($username,$password,$documento,$first_name,$last_name,$email,$phone,$id_componente,$id_group,$created_on,$active,$foto);
		$regresar="creacionusuarios.php?id=0";
		break;

		case "12":

		$id=$_POST['id'];
		$username=strtolower($_POST['username']);
		$password=md5(htmlspecialchars($_POST['password']));
		$documento = $_POST['documento'];
		$first_name=strtoupper($_POST['first_name']);
		$last_name=strtoupper($_POST['last_name']);
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		$id_componente=$_POST['id_componente'];
		$id_group=$_POST['id_group'];
		$foto = $_POST["foto"];
		$created_on= date("Y-m-d H:i:s");
		$active="1";
		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->updateusuarios($id,$username,$password,$documento,$first_name,$last_name,$email,$phone,$id_componente,$id_group,$created_on,$active,$foto);
		$regresar="creacionusuarios.php?id=0";
		break;


		case "13":
		$descripcion_reserva=$_POST['descripcion_reserva'];
		$fecha_reserva=$_POST['fecha_reserva'];
		$id_interventor=$_POST['id_interventor'];
		$id_componente=$_POST['id_componente'];
		$estado="1";
		$id_codigoacta=sprintf('%02d', $id_componente);

		//Operaciones para hallar el id_acta
		$queryconsecutivo= mysql_query(("
		SELECT
		max(consecutivo_acta) consecutivo
		FROM
		componente
		WHERE
		id_componente='$id_componente' and
		estado='1'
		"),$conexion);
		$row=mysql_fetch_assoc($queryconsecutivo);
		$consecutivo_acta=$row['consecutivo']+1;
		$conversion_consecutivo=sprintf('%05d', $consecutivo_acta);
		$id_acta= "AVI-".$id_codigoacta."-".date("Y").$conversion_consecutivo;


		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertreservaradicado(
			$id_acta,
			$descripcion_reserva,
			$fecha_reserva,
			$id_interventor,
			$id_componente,
			$estado,
			$consecutivo_acta
		);
		$regresar="reservaradicado.php?";

		break;

		case "14":

		$id_componente=$_POST['id_componente'];
		$id_contrato=$_POST['id_contrato'];
		$id_sede=$_POST['id_sede'];
		$id_prestador=$_POST['id_prestador'];
		$id_modalidad=$_POST['id_modalidad'];
		$id_interventor=$_POST['id_interventor'];
		$fecha_evaluacion=$_POST['fecha_evaluacion'];
		$hora_inicio=$_POST['hora_inicio'];
		$hora_fin=$_POST['hora_fin'];
		$estado='1';
		$nombre_sede=$_POST['nombre_sede'];
		$direccion_sede=$_POST['direccion_sede'];
		$telefono_sede=$_POST['telefono_sede'];
		$observacion_interventor=$_POST['observacion_interventor'];
		$id_codigoacta=$_POST['id_codigoacta'];
		$acta_reservada=$_POST['acta_reservada'];

		if($acta_reservada=="NO"){
			//Operaciones para hallar el id_acta
			$queryconsecutivo= mysql_query(("
			SELECT
			max(consecutivo_acta) consecutivo
			FROM
			componente
			WHERE
			id_componente='$id_componente' and
			estado='1'
			"),$conexion);
			$row=mysql_fetch_assoc($queryconsecutivo);
			$consecutivo_acta=$row['consecutivo']+1;
			$conversion_consecutivo=sprintf('%05d', $consecutivo_acta);
			$id_acta= "AVI-".$id_codigoacta."-".date("Y").$conversion_consecutivo;
		}
		else
		{
			$queryconsecutivo= mysql_query(("
			SELECT
			max(consecutivo_acta) consecutivo
			FROM
			componente
			WHERE
			id_componente='$id_componente' and
			estado='1'
			"),$conexion);
			$row=mysql_fetch_assoc($queryconsecutivo);
			$consecutivo_acta="";
			$id_acta=$_POST['id_acta'];
		}


		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertactafallida(
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
		);

		$regresar="visitasfallidas.php?";
		break;


		case "15":

		$id_semaforo=$_POST['id_semaforo'];
		$id_contrato=$_POST['id_contrato'];
		$id_prestador=$_POST['id_prestador'];
		$id_modalidad=$_POST['id_modalidad'];
		$id_componente=$_POST['id_componente'];
		$id_tema=$_POST['id_tema'];
		$id_ano=(int)date("Y");
		$id_mes=$_POST['id_mes'];
		$incumplimiento_grave=$_POST['incumplimiento_grave'];
		$nt1=@$_POST['nt1'];
		$nt2=@$_POST['nt2'];
		$nt3=@$_POST['nt3'];
		$gi1=$_POST['gi1'];
		$gi2=$_POST['gi2'];
		$gi3=$_POST['gi3'];
		$gi4=$_POST['gi4'];
		$gi5=$_POST['gi5'];
		$gi6=$_POST['gi6'];
		$gi7=$_POST['gi7'];
		$descripcion_incumplimiento_grave=$_POST['descripcion_incumplimiento_grave'];
		$logros_prestador=$_POST['logros_prestador'];
		$dificultades_prestador=$_POST['dificultades_prestador'];
		$debido_proceso=$_POST['debido_proceso'];
		$asistencia_tecnica=$_POST['asistencia_tecnica'];
		$otras_observaciones=$_POST['otras_observaciones'];
		$porc_calidad=$_POST['porc_calidad'];
		$porc_deb_proc=$_POST['porc_deb_proc'];
		$id_interventor=$_POST['id_interventor'];
		$estado='1';
		$porc_descuento=1;
		$requerimientos=$_POST['requerimientos'];

		//Para Nutrición
		if($id_componente==7){
			$suma_descuento=0;

			if($nt1==0){ //-10%
				$suma_descuento=$suma_descuento-0.10;
			}
			if($nt2==0){ //-10%
				$suma_descuento=$suma_descuento-0.10;
			}
			if($nt3==0){ //-5%
				$suma_descuento=$suma_descuento-0.05;
			}
			if($incumplimiento_grave==1){ //Reporte de Incumplimiento Grave -41%
				$suma_descuento=$suma_descuento-0.41;
			}

			$porc_descuento=$suma_descuento;

		}


		//Para GInst
		if($id_componente==4){
			if($gi3==0 || $incumplimiento_grave==1){
				$porc_descuento=0;
			}else{
				$porc_descuento=($gi1+$gi2+$gi3+$gi4+$gi5+$gi6+$gi7)/7;
			}
			$clsFunciones = new clsFunciones;
			if ($id_tema == 401) {
				$numpreg = 23;
			} else if ($id_tema==402) {
				$numpreg = 2;
			}
			for ($d=1;$d<$numpreg;$d++) {
				if ($id_tema==402) {
					$d = 23;
				}
				$preg = "pgi".$d; $resp = "gi".$d; $comt = "tgi".$d;
				$semaforo 	= $id_semaforo;
				$contrato	= $id_contrato;
				$mes 		= $id_mes;
				$pregunta 	= $_POST[$preg];
				$respuesta 	= $_POST[$resp];
				$comentario = $_POST[$comt];
				$usuario	= $_SESSION["login"];
				switch ($respuesta) { case "C": $tipo = "Logro"; break; case "NC": $tipo = "Dificultad"; break; case "NA": $tipo = "Observaciones"; break; }
				//echo $semaforo."-Contrato:".$contrato."-Mes:".$mes."-Pregunta:".$pregunta."-Resp:".$respuesta."-Comentario:".$comentario."-Tipo:".$tipo."-usuario:".$usuario;
				$inserta = $clsFunciones->calificacion($semaforo, $contrato,$id_tema, $mes, $pregunta, $respuesta, $comentario, $tipo, $usuario);
			}
			$cadena = "4,401,0,0,1,0,0,'XXX-".$mes."201600".$semaforo."',".$contrato.",'',$id_prestador,$id_modalidad,$id_interventor,1,NOW(),(proc-referencia),(porc-ini),(porc-fin),(porc-comp-fin),0";
		}

		//Para todos los componentes
		if($id_componente==1 || $id_componente==2 || $id_componente==5 || $id_componente==8 || $id_componente==9 ){
			if($incumplimiento_grave==1){
				$porc_descuento=0; //-0.41; Cambiado el 13/05/2016 - JSLL
			}else{
				$porc_descuento=0;
			}
		}


		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->updatesemaforo(
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
		);

		$regresar="evaluarsemaforo.php?id_contrato=".$id_contrato."&id_mes=".$id_mes."&id_tema=".$id_tema."&porc_descuento=".$porc_descuento;
		break;

		case "16":

		//Variables de configuracion
		$peso_mb=7; //Tamaño limite en MB del archivo a subir
		$extensiones = "pdf,jpg,png,jpeg,xls,xlsx"; // Extensiones permitidas (usar una coma para separarlas)

		//Variables recibidas via POST
		$folder=$_POST['folder'];
		$id_acta=$_POST['id_acta'];
		$id_interventor=$_POST['id_interventor'];
		$fecha_archivo=$_POST['fecha_archivo'];
		$descripcion_archivo=$_POST['descripcion_archivo'];
		$ruta_temporal=$_FILES['archivo']['tmp_name']; //Ruta temporal donde se carga el archivo
		$filesize = $_FILES['archivo']['size']; // Tamaño del archivo
		$filename = strtolower($_FILES['archivo']['name']); // Nombre del archivo


		$maxlimit = $peso_mb*1024*1024; // Máximo límite de tamaño (en bytes)
		$file_ext = preg_split("/\./",$filename); // Separar el nombre de la extension
		$allowed = preg_split("/\,/",$extensiones); // Ingresa en un array las extensiones permitidas
		$match1 = "";
		$match2 = "";
		$match3 = "";
		$error1 = "";
		$error2 = "";
		$error3 = "";
		$estado='1';



		//Validacion tamaño del archivo
		if($filesize < $maxlimit){
			$match1 = 1;
		}else{
			$error1="El archivo supera el tamaño máximo permitido.";
		}

		//Validacion tipo de extension
		foreach($allowed as $ext){
			if($ext==$file_ext[1])
			{
				$match2 = 1;
			}
		}

		if($match2!=1){
			$error2="La extensión del archivo no es permitida.";
		}

		//Validacion nombre archivo
		if(file_exists($folder.$filename))
		{
			$error3="¡El nombre del archivo ya existe! Debes corregirlo y cargarlo nuevamente.";
		}else{
			$match3=1;
		}


		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertimagen(
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
		);

		$errores=$error1.". ".$error2.". ".$error3;
		$regresar="imprimiractas.php?id_acta=".$id_acta."&errores=".$errores;
		break;


		case "17":

		$id_semaforo=$_POST['id_semaforo'];
		$componente_selected=$_POST['componente_selected'];
		$id_tema=$_POST['id_tema'];
		$id_mes=$_POST['id_mes'];
		$logros_prestador=$_POST['logros_prestador'];
		$dificultades_prestador=$_POST['dificultades_prestador'];
		$debido_proceso=$_POST['debido_proceso'];
		$asistencia_tecnica=$_POST['asistencia_tecnica'];
		$otras_observaciones=$_POST['otras_observaciones'];
		$descripcion_incumplimiento_grave=$_POST['descripcion_incumplimiento_grave'];


		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->update_reporte_semaforo(
			$id_semaforo,
			$logros_prestador,
			$dificultades_prestador,
			$debido_proceso,
			$asistencia_tecnica,
			$otras_observaciones,
			$descripcion_incumplimiento_grave
		);
		$regresar="reportes3.php?componente_selected=".$componente_selected."&id_tema=".$id_tema."&id_mes=".$id_mes;
		break;


		case "18":

		$id_componente=$_POST['id_componente'];
		$id_proveedor=$_POST['id_proveedor'];
		$id_modalidad=$_POST['id_modalidad'];
		$id_interventor=$_POST['id_interventor'];
		$fecha_evaluacion=$_POST['fecha_evaluacion'];
		$id_tema=$_POST['id_tema'];
		$id_subtema=$_POST['id_subtema'];
		$id_pregunta=$_POST['id_pregunta'];
		$descripcion_pregunta=$_POST['descripcion_pregunta'];
		$descripcion_observacion=$_POST['descripcion_observacion'];
		$descripcion_accion_correctiva=$_POST['descripcion_accion_correctiva'];
		$valor_referencia="1";
		$valor_calificacion=$_POST['valor_calificacion'];
		$valor_calificacion_final=$_POST['valor_calificacion'];
		$numero_visita=$_POST['numero_visita'];
		$hora_inicio=$_POST['hora_inicio'];
		$hora_fin=$_POST['hora_fin'];
		$estado='1';
		$direccion_proveedor=$_POST['direccion_proveedor'];
		$numero_telefono=$_POST['numero_telefono'];
		$nombre_asistentes=$_POST['nombre_asistentes'];
		$nombre_prestadores=$_POST['nombre_prestadores'];
		$id_codigoacta=$_POST['id_codigoacta'];
		$acta_reservada=$_POST['acta_reservada'];
		$porc_componente_x_modalidad=$_POST['porc_componente_x_modalidad'];

		if($acta_reservada=="NO"){
			//Operaciones para hallar el id_acta
			$queryconsecutivo= mysql_query(("
			SELECT
			max(consecutivo_acta) consecutivo
			FROM
			componente
			WHERE
			id_componente='$id_componente' and
			estado='1'
			"),$conexion);
			$row=mysql_fetch_assoc($queryconsecutivo);
			$consecutivo_acta=$row['consecutivo']+1;
			$conversion_consecutivo=sprintf('%05d', $consecutivo_acta);
			$id_acta= "AVP-".$id_codigoacta."-".date("Y").$conversion_consecutivo;
		}
		else
		{
			$queryconsecutivo= mysql_query(("
			SELECT
			max(consecutivo_acta) consecutivo
			FROM
			componente
			WHERE
			id_componente='$id_componente' and
			estado='1'
			"),$conexion);
			$row=mysql_fetch_assoc($queryconsecutivo);
			$consecutivo_acta="";
			$id_acta=$_POST['id_acta'];
		}


		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertevaluacionproveedor(
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
		);

		$regresar="imprimiractasproveedor.php?id_acta=".$id_acta;
		break;

		case "19":

		$id_acta=$_POST['id_acta'];
		//$id_pregunta=$_POST['id_pregunta'];
		$descripcion_observacion=$_POST['descripcion_observacion'];
		//$descripcion_accion_correctiva=$_POST['descripcion_accion_correctiva'];
		//$id_evaluacion_hallazgo=$_POST['id_evaluacion'];
		$id_subsanacion=$_POST['id_subsanacion'];
		//$valor_calificacion_final=$_POST['valor_calificacion_final'];
		//$fecha_subsanacion=$_POST['fecha_subsanacion'];
		//$fecha_subsanacion_final=$_POST['fecha_subsanacion_final'];
		//$fecha_solicitud_aclaracion=$_POST['fecha_solicitud_aclaracion'];
		//$id_radicado_osa=$_POST['id_radicado_osa'];
		//$fecha_requerimiento=$_POST['fecha_requerimiento'];
		//$id_radicado_orq=$_POST['id_radicado_orq'];
		//$fecha_envio_evidencia=$_POST['fecha_envio_evidencia'];
		//$etapa=$_POST['etapa'];
		//$etapa_anterior=$_POST['etapa_anterior'];





		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->updateevaluacionproveedor(
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

		);
		$regresar="imprimiractasproveedor.php?id_acta=".$id_acta;
		break;

		case "20":


		$id_acta=$_POST['id_acta'];
		$id_interventor=$_POST['id_interventor'];
		$fecha_observacion_evaluador=$_POST['fecha_observacion_evaluador'];
		$descripcion_observacion_evaluador=$_POST['descripcion_observacion_evaluador'];
		$estado='1';

		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertobsevaluadorproveedores($id_acta,$fecha_observacion_evaluador,$descripcion_observacion_evaluador,$id_interventor,$estado);
		$regresar="imprimiractasproveedor.php?id_acta=".$id_acta;
		break;

		case "21":


		$id_acta=$_POST['id_acta'];
		$id_interventor=$_POST['id_interventor'];
		$fecha_observacion_usuario=$_POST['fecha_observacion_usuario'];
		$descripcion_observacion_usuario=$_POST['descripcion_observacion_usuario'];
		$estado='1';

		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertobsusuarioproveedores($id_acta,$fecha_observacion_usuario,$descripcion_observacion_usuario,$id_interventor,$estado);
		$regresar="imprimiractasproveedor.php?id_acta=".$id_acta;
		break;

		case "22":
		global $nombre;
		$acta = $_POST['acta'];
		$fecha = $_POST['fecha'];
		$interventor = $_POST['id_interventor'];
		$matriculados = $_POST['matriculados'];
		$asistentes = $_POST['asistentes'];
		$alimentacion = $_POST['alimentacion'];
		$descripcion = $_POST['descripcion'];
		$medida = $_POST['medida'];
		$alimento = $_POST['alimento'];
		$detallealimento = $_POST['detallealimento'];
		$faltante = $_POST['faltante'];
		$grupoedad = $_POST['grupoedad'];
		$total = $_POST['total'];
		$observaciones = $_POST['observaciones'];
		$estado="1";
		$usuario = $nombre;

		$clsFunciones = new clsFunciones;
		$insert = $clsFunciones->insertdescuentos($acta, $fecha, $interventor, $matriculados, $asistentes, $alimentacion, $descripcion, $medida, $alimento, $detallealimento, $faltante, $grupoedad, $total, $observaciones, $estado, $_SESSION['login']);
		$regresar="descontaractas.php?id_acta=".$acta;
		break;

		case "23";
		$acta=$_POST['acta'];
		$usuario = $_SESSION['login'];
		$clave = $_POST['clave'];
		$msg=6;
		$codigo="SELECT * FROM users where username='$usuario' and password='$clave' and active='1';";
		$query=mysql_query($codigo,$conexion) OR die(mysql_error());
		if(!$query){
			$msg = "Error al validar datos, favor intente nuevamente";
			$insert = false;
		}
		$traeusuario = mysql_fetch_array($query);
		if (false){
			$msg = "Usuario o contrase&ntilde;a incorrecto, int&eacute;ntelo nuevamente";
			$insert= false; $msg=3;
		} else {
			//Daniel Gallo 30/03/2017
			$sqlDes="SELECT estado FROM descuentos WHERE id_acta LIKE '".$acta."';";
			$queryDes=mysql_query($sqlDes,$conexion) OR die(mysql_error());
			if(!$queryDes){
				$msg = "Error al consultar los datos de descuentos, favor intente nuevamente";
				$insert = false;
			}
			$rowDes=mysql_fetch_assoc($queryDes);
			$estado_descuento=$rowDes['estado'];
			// Fin

			if ($usuario == "marcela.gomez" && $estado_descuento == 1) {
				$sqldesc = "UPDATE descuentos SET estado = 2 WHERE id_acta LIKE '".$acta."'";
			} else if ($usuario == "marcela.gomez" && $estado_descuento == 2){
				$sqldesc = "UPDATE descuentos SET estado = 3 WHERE id_acta LIKE '".$acta."'";
			}
			if ($usuario == "marcela.gomez") {
				$querydesc = mysql_query($sqldesc,$conexion) OR die(mysql_error());
				$insert= true; $msg=1;
			} else {
				echo "No tienes permiso para autorizar este descuento.";
				echo "<br><span Onclick='javascript:window.close();'>Da click aqui para regresar</span>";
				$insert= false; $msg=5;
			}
		}
		echo "<html><head></head><body><script languaje='javascript' type='text/javascript'>opener.location.reload(); window.close(); </script></body></html>";
		$regresar="apruebadescuento.php?acta=".$acta;
		header('Location: '.$regresar.'&msg='.$msg);
		break;

		case "24":
		$id_prestador = $_POST['id_prestador'];
		$id_contrato = $_POST['id_contrato'];
		$id_modalidad = $_POST['id_modalidad'];
		$tipo_descuento = $_POST['tipo_descuento'];
		$fecha = $_POST['fecha_descuento'];
		$usuario = $_SESSION['login'];
		$id_sede = $_POST['id_sede'];

		$clsFunciones = new clsFunciones;

		$insert = $clsFunciones->insertdescuentosxvalorecion($id_prestador, $id_contrato,$id_sede, $id_modalidad, $tipo_descuento, $fecha, $usuario);
		$regresar="descuentosValoracion.php?msg=0";
		header('Location: '.$regresar);
		break;

		case "25":
		$id = $_POST['id_gastos_desplazamiento'];
		$id_acta = $_POST['id_acta'];
		$pago_desplazamiento = $_POST['pago_desplazamiento'];
		$justificacion = $_POST['justificacion'];
		$transporte_interventoria = $_POST['transporte_interventoria'];
		$clsFunciones = new clsFunciones;
		if (isset($_REQUEST["aceptartodos"])){
			$update = $clsFunciones->aceptartodospagodesplazamiento($id,$id_acta, $pago_desplazamiento, $justificacion, $transporte_interventoria);
		}else {
			$update = $clsFunciones->updatepagodesplazamiento($id,$id_acta, $pago_desplazamiento, $justificacion, $transporte_interventoria);
		}

		$regresar="homeadmin.php#";
		header('Location: '.$regresar);
		break;

		case "26":
		$id = $_POST['id'];
		$valor = $_POST['valor'];
		$justificacion =  $_POST['justificacion'];
		$transporte_interventoria = $_POST['transporte_interventoria'];
		$estado = $_POST['estado'];
		$clsFunciones = new clsFunciones;
		$update = $clsFunciones->update_estado_gastos_desplazamiento($id, $valor, $justificacion, $transporte_interventoria, $estado);
		$regresar="homeadmin.php#";
		return true;
		break;

		case "27":
		$id = $_POST['id'];
		$fecha_solicitud_aclaracion = $_POST['fecha_solicitud_aclaracion'];
		$id_radicado_osa = $_POST['id_radicado_osa'];
		$fecha_requerimiento = $_POST['fecha_requerimiento'];
		$id_radicado_orq = $_POST['id_radicado_orq'];
		$fecha_envio_evidencia = $_POST['fecha_envio_evidencia'];
		$subsanacion = $_POST['subsanacion'];
		$fecha_descuento = $_POST['fecha_descuento'];
		$clsFunciones = new clsFunciones;
		$update = $clsFunciones->update_descuentos_x_valoracion($id, $fecha_solicitud_aclaracion, $id_radicado_osa, $fecha_requerimiento, $id_radicado_orq, $fecha_envio_evidencia,$subsanacion,$fecha_descuento);
		$regresar="descuentosValoracion.php";
		header('Location: '.$regresar);
		return true;
		break;

		case "28":
		$id_descuento = $_POST['id_descuento'];
		$tipo_descuento = $_POST['tipo_descuento'];
		$descuento = $_POST['descuento'];
		$clsFunciones = new clsFunciones;
		$delete = $clsFunciones->update_estado_descuentos_x_valoracion($id_descuento, $tipo_descuento, $descuento);
		$regresar="descuentosValoracion.php";
		header('Location: '.$regresar);
		return true;
		break;

		case "29":
		$id=$_POST['id'];
		$id_contrato = $_POST['id_contrato'];
		//$fecha_inicio_contrato = $_POST['fecha_inicio_contrato'];
		//$fecha_fin_contrato =$_POST['fecha_fin_contrato'];
		$id_prestador=$_POST['id_prestador'];
		$id_modalidad=$_POST['id_modalidad'];
		$id_componente=$_POST['id_componente'];
		$id_tema=$_POST['id_tema'];
		$usuario = $_SESSION['login'];
		$observacion_contrato=$_POST['observacion_contrato'];
		$msg =$_POST['msg'];
		$id_grupo=$_POST['id_grupo'];
		$clsFunciones= new clsFunciones;
		$insert=$clsFunciones->insertinformefinal($id, $id_contrato, $id_prestador, $id_modalidad, $id_componente, $id_tema, $observacion_contrato, $usuario, $id_grupo);
		$regresar="informesFinal.php?msg=".$msg."&id_componente=".$id_componente."&id_tema=".$id_tema."&id_contrato=".$id_contrato."&id=".$id."&id_grupo=".$id_grupo;
		if (is_array($id) == true) {
			$regresar="informesFinal.php?msg=".$msg."&id_componente=".$id_componente[0]."&id_tema=".$id_tema[0]."&id_contrato=".$id_contrato[0]."&id=".$id[0]."&id_grupo=".$id_grupo;
		}else {
			$regresar="informesFinal.php?msg=".$msg."&id_componente=".$id_componente."&id_tema=".$id_tema."&id_contrato=".$id_contrato."&id=".$id."&id_grupo=".$id_grupo;
		}
		header ('Location: '.$regresar);
		return true;
		break;

		case "30":
		$id_modalidad = $_POST["id_modalidad"];
		$obligacion = $_POST["obligacion"];
		$observacion = $_POST["observacion"];
		$clsFunciones= new clsFunciones;
		$insert=$clsFunciones->insertobligacionescontratos($id_modalidad, $obligacion, $observacion);
		$regresar="obligacionescontrato.php";
		header ('Location: '.$regresar);
		return true;
		break;

		case "31":
		$id = $_POST["id"];
		$obligacion = $_POST["obligaciones"];
		$observacion = $_POST["observaciones"];
		$clsFunciones= new clsFunciones;
		$update = $clsFunciones->updateobligacionescontratos($id, $obligacion, $observacion);
		$regresar="obligacionescontrato.php";
		header ('Location: '.$regresar);
		return true;
		break;

		case "32":
		$id=$_POST['id'];
		$id_contrato = $_POST['id_contrato'];
		$id_prestador=$_POST['id_prestador'];
		$id_modalidad=$_POST['id_modalidad'];
		$id_componente=$_POST['id_componente'];
		$usuario = $_SESSION['login'];
		$observacion_contrato=$_POST['observacion_contrato'];
		$msg =$_POST['msg'];
		$id_grupo=$_POST['id_grupo'];
		$clsFunciones= new clsFunciones;
		$insert=$clsFunciones->insertinformeliquidacion($id, $id_contrato, $id_prestador, $id_modalidad, $id_componente, $observacion_contrato, $usuario, $id_grupo);
		if (is_array($id) == true) {
			$regresar="informesLiquidacion.php?msg=".$msg."&id_componente=".$id_componente[0]."&id=".$id[0]."&id_grupo=".$id_grupo;
		}else {
			$regresar="informesLiquidacion.php?msg=".$msg."&id_componente=".$id_componente."&id_contrato=".$id_contrato."&id=".$id."&id_grupo=".$id_grupo;
		}

		// header ('Location: '.$regresar);
		return true;
		break;

		case "33":
		$id_acta = $_POST["id_acta"];
		$firma = $_POST["firma"];
		$clsFunciones= new clsFunciones;
		$update = $clsFunciones->actualizarfirma($id_acta, $firma);
		$regresar="imprimiractas.php?id_acta=".$id_acta;
		header ('Location: '.$regresar);
		return true;
		break;


	} //End Case


	if ($insert){
		 header('Location: '.$regresar.'&msg=1');
	}
	else{
	   header('Location: '.$regresar.'&msg=2');
	}




} //End If login

else {
	  header('Location: index.php');

}

?>
