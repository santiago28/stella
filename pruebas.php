<?php

session_start();
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


$caso=$_POST['caso'];



     
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
	
print "id_proveedor=".$id_proveedor."</br>";
print "id_interventor=".$id_interventor."</br>";
print "numero_visita=".$numero_visita."</br>";
print "fecha_evaluacion=".$fecha_evaluacion."</br>";
print "estado=".$estado."</br>";
	
	
	
	
}	
	




/*
//Histórico Porcentaje Inicial
$query_inicial= mysql_query(("
SELECT 
avg(porcentaje_final) promedio_componente

FROM
	(
	SELECT
	sum(porc_inicial)/sum(porc_referencia) porcentaje_final
	FROM
	evaluacion
	WHERE
	id_contrato='4600057863' and
	id_tema='101' and
	fecha_evaluacion <='2015-4-30' and
	estado='1'
	group by id_acta
	) as operacion	
"),$conexion);
while($row=mysql_fetch_assoc($query_inicial)){					
$porc_inicial=$row['promedio_componente'];
}

echo "PORC INICIAL= ".$porc_inicial;					
*/





/*
if(file_exists("/home/archivos/IBC_2015/2015/2-UPLOAD/amalgama.txt")){
	echo "SI EXISTE";
} else{
	echo "NO EXISTE";
}


/*
	if($_FILES['archivo']['error']>0){
		echo "ha ocurrido un error";
	}else {
		
	
	$match1 = "";
	$match2 = "";
	$match3 = "";
	
	$error1 = "";
	$error2 = "";
	$error3 = "";
	
	$id_acta=$_POST['id_acta'];
	$id_interventor=$_POST['id_interventor'];
	$fecha_foto=$_POST['fecha_foto'];
	$estado='1';
	$folder = "upload/"; // Carpeta a la que queremos subir los archivos
	$peso_mb=5; //Peso en MB
	$maxlimit = $peso_mb*1024*1024; // Máximo límite de tamaño (en bytes)
	$filesize = $_FILES['archivo']['size']; // toma el tamaño del archivo
	$filename = strtolower($_FILES['archivo']['name']); // toma el nombre del archivo y lo pasa a minúsculas
	$extensiones = "xls,xlsx,pdf,jpg,png"; // Extensiones permitidas (usar una coma para separarlas)
	$file_ext = preg_split("/\./",$filename); // Separar el nombre de la
	$allowed = preg_split("/\,/",$extensiones); // ídem, algo con las extensiones



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

print "maxlimit=".$maxlimit."</br>";
print "filesize=".$filesize."</br>";
print "match1=".$match1."</br>";
print "match2=".$match2."</br>";
print "match3=".$match3."</br>";
print "error1=".$error1."</br>";
print "error2=".$error2."</br>";
print "error3=".$error3."</br>";

//Carga del archivo al servidor
if($match1==1 && $match2==1 && $match3==1)
{
	move_uploaded_file($_FILES['archivo']['tmp_name'], $folder.$filename);
}else{
	print $error1." - ".$error2." - ".$error3;
}

	//}



*/	
	
/*
$id_acta='AVI-01-201500003';

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
				$suma_porc_inicial=array_sum($porc_inicial); 
				$suma_porc_final=array_sum($porc_final); 
				$suma_porc_componente_x_final=array_sum($porc_componente_x_final); 				
				
		
echo "<pre>"."id_evaluacion_";
			print_r($id_evaluacion);
		echo "</pre>";

		
echo "<pre>"."porc_referencia_";
			print_r($porc_referencia);
		echo "</pre>";
		
		
echo "<pre>"."porc_inicial_";
			print_r($porc_inicial);
		echo "</pre>";

		
echo "<pre>"."porc_final_";
			print_r($porc_final);
		echo "</pre>";

		
echo "<pre>"."porc_componente_x_final_";
			print_r($porc_componente_x_final);
		echo "</pre>";		
		
print "suma_porc_referencia=".$suma_porc_referencia."</br>";
print "suma_porc_inicial=".$suma_porc_inicial."</br>";
print "suma_porc_final=".$suma_porc_final."</br>";
print "suma_porc_componente_x_final=".$suma_porc_componente_x_final."</br>";		
				
		//Update: Actualizar porcentajes en la tabla evaluaciÃ³n
			
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







$numero_visita=$_POST['numero_visita'];
print "numero_visita=".$numero_visita."</br>";

//Operaciones para hallar el id_acta
	include "conexion.php";
	$queryconsecutivo= mysql_query(("
		SELECT 
		max(consecutivo_acta) consecutivo,
		porc_componente
		FROM
		componente
		WHERE
		id_componente='1' and
		estado='1'
		"),$conexion);
	$row=mysql_fetch_assoc($queryconsecutivo);
	$consecutivo_acta=$row['consecutivo']+1;
	$porc_componente=$row['porc_componente'];

	
	print "consecutivo_acta=".$consecutivo_acta."</br>";
	print "porc_componente=".$porc_componente."</br>";
	

$username=$_POST['username'];
print "username=".$username."</br>";

$password=md5(htmlspecialchars($_POST['password']));
print "password=".$password."</br>";

$first_name=strtoupper($_POST['first_name']);
print "first_name=".$first_name."</br>";

$last_name=strtoupper($_POST['last_name']);
print "last_name=".$last_name."</br>";

$email=$_POST['email'];
print "email=".$email."</br>";

$phone=$_POST['phone'];
print "phone=".$phone."</br>";

$id_componente=$_POST['id_componente'];
print "id_componente=".$id_componente."</br>";

$id_group=$_POST['id_group'];
print "id_group=".$id_group."</br>";

$created_on= date("Y-m-d H:i:s");
print "created_on=".$created_on."</br>";

$active="1";
print "id_group=".$id_group."</br>";



		
$valor_calificacion=$_POST['valor_calificacion'];
echo "<pre>"."valor_calificacion";
			print_r($valor_calificacion);
		echo "</pre>";
		

$valor_calificacion_final=$_POST['valor_calificacion_final'];
echo "<pre>"."valor_calificacion_final";
			print_r($valor_calificacion_final);
		echo "</pre>";		
		

//$id_acta=$_GET['id_acta'];
//print "id_acta=".$id_acta."</br>";
//print "hola";

/*
include "conexion.php";


		$caso=$_POST['caso'];
		$consecutivo="0001";
		$id_componente=$_POST['id_componente'];
		$id_codigoacta=$_POST['id_codigoacta'];
		$id_contrato=$_POST['id_contrato'];
		$id_sede=$_POST['id_sede'];
		$nombre_sede=$_POST['nombre_sede'];
		$telefono_sede=$_POST['telefono_sede'];
		$direccion_sede=$_POST['direccion_sede'];
		$id_prestador=$_POST['id_prestador'];
		$id_modalidad=$_POST['id_modalidad'];
		$id_interventor=$_POST['id_interventor'];
		$fecha_evaluacion=$_POST['fecha_evaluacion'];
		$id_tema=$_POST['id_tema'];
		$porcentaje_tema=$_POST['porcentaje_tema'];
		$id_subtema=$_POST['id_subtema'];
		$porcentaje_subtema=$_POST['porcentaje_subtema'];
		$id_pregunta=$_POST['id_pregunta'];
		$valor_calificacion=$_POST['valor_calificacion'];
		$hora_inicio=$_POST['hora_inicio'];
		$hora_fin=$_POST['hora_fin'];
		$estado='1';
		$id_acta= date("Y")."-".$id_codigoacta."-1-1-".$consecutivo;
		$nombre_asistentes=$_POST['nombre_asistentes'];
		

		
for ($i=0; $i < count($id_pregunta);$i++)
	{
		//Query para saber la cantidad de preguntas que se reparten el porcentaje del subtema
		$query= mysql_query(("
		select id_pregunta from pregunta
		where id_componente='1' and
		id_tema='$id_tema[$i]' and
		id_subtema='$id_subtema[$i]' and
		estado='1'
		"),$conexion);		
		$cantidad_preguntas=mysql_num_rows($query);		
		$matriz_cantidad_preguntas[]=$cantidad_preguntas;
		
		//Ciclo para formar el array de porcentaje_calificacion
		if( $valor_calificacion[$i]==1 || $valor_calificacion[$i]==5 ){
		$porcentaje_calificacion[]=($porcentaje_subtema[$i]/$matriz_cantidad_preguntas[$i])*$porcentaje_tema[$i];
		}
		else{
		$porcentaje_calificacion[]=0;
		}
		
	}	
		
		
		print "caso=".$caso."</br>";
		print "id_componente=".$id_componente."</br>";
		print "id_contrato=".$id_contrato."</br>";
		print "id_sede=".$id_sede."</br>";
		print "nombre_sede=".$nombre_sede."</br>";
		print "telefono_sede=".$telefono_sede."</br>";
		print "direccion_sede=".$direccion_sede."</br>";
		print "id_prestador=".$id_prestador."</br>";
		print "id_modalidad=".$id_modalidad."</br>";
		print "id_interventor=".$id_interventor."</br>";
		print "fecha_evaluacion=".$fecha_evaluacion."</br>";
		print "hora_inicio=".$hora_inicio."</br>";
		print "hora_fin=".$hora_fin."</br>";
		print "id_acta=".$id_acta."</br>";
		print "nombre_asistentes=".$nombre_asistentes."</br>";
		
		
		echo "<pre>"."matriz_cantidad_preguntas_";
			print_r($matriz_cantidad_preguntas);
		echo "</pre>";
		
		
		echo "<pre>"."id_tema_";
			print_r($id_tema);
		echo "</pre>";
		
		echo "<pre>"."porcentaje_tema_";
			print_r($porcentaje_tema);
		echo "</pre>";
		
		echo "<pre>"."id_subtema_";
			print_r($id_subtema);
		echo "</pre>";
		
		echo "<pre>"."porcentaje_subtema_";
			print_r($porcentaje_subtema);
		echo "</pre>";
		
		echo "<pre>"."id_pregunta_";
			print_r($id_pregunta);
		echo "</pre>";
		
		echo "<pre>"."valor_calificacion_";
			print_r($valor_calificacion);
		echo "</pre>";
			
		echo "<pre>"."porcentaje_calificacion_";
			print_r($porcentaje_calificacion);
		echo "</pre>";

*/
		
?>

