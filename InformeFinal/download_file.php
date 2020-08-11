<?php
require_once 'vendor/autoload.php';
require_once 'conexion.php';
require_once 'index.php';
//$exportar = new Exportar();
$id_contrato = $_GET["code"];
//$exportar->ExportarContratos();
$semaforo = mysql_query(("
SELECT semaforo.id_mes, semaforo.id_contrato, tema.nombre_tema, semaforo.logros_prestador, semaforo.dificultades_prestador, semaforo.debido_proceso
FROM semaforo, tema
WHERE semaforo.id_tema = tema.id_tema
AND semaforo.id_mes = 5 AND semaforo.id_contrato = '$id_contrato'
GROUP BY semaforo.id_tema
ORDER BY tema.nombre_tema"), $conexion);

$tabla_informe = mysql_query(("
SELECT MONTH(acta.fecha_evaluacion) as mes, COUNT(MONTH(acta.fecha_evaluacion)) AS total, MONTHNAME(acta.fecha_evaluacion) AS NOMBRE, componente.id_componente, componente.nombre_componente
from acta, componente
where acta.id_componente = componente.id_componente and componente.id_componente in (1,2,5,7,8,9)
and acta.fecha_evaluacion <= now() and acta.id_contrato = '$id_contrato'
GROUP BY  componente.id_componente, mes
ORDER BY mes asc"), $conexion);

while ($row = mysql_fetch_assoc($semaforo)) {
  $logros[] = $row['logros_prestador'];
  $dificultades[] = $row['dificultades_prestador'];
  $debido_proceso[] = $row['debido_proceso'];
}

// Se crea el documento
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

// Define table style arrays
// $styleTable = array('borderSize'=>6, 'borderColor'=>'000000', 'cellMargin'=>80);
// $styleFirstRow = array('borderBottomSize'=>18, 'borderBottomColor'=>'000000', 'bgColor'=>'bfbfbf');
//
// // Define cell style arrays
// $styleCell = array('valign'=>'center');
//
// // Define font style for first row
// $fontStyle = array('bold'=>true, 'align'=>'center');
//
// // Add table style
// $phpWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

// Add table
// $table = $section->addTable('myOwnTableStyle');
//
// // Add row
// $table->addRow(900);
//
// // Add cells
// $table->addCell(300, $styleCell)->addText('  ', $fontStyle);
// $table->addCell(300, $styleCell)->addText('ENE', $fontStyle);
// $table->addCell(300, $styleCell)->addText('FEB', $fontStyle);
// $table->addCell(300, $styleCell)->addText('MAR', $fontStyle);
// $table->addCell(300, $styleCell)->addText('ABR', $fontStyle);
// $table->addCell(300, $styleCell)->addText('MAY', $fontStyle);
// $table->addCell(300, $styleCell)->addText('JUN', $fontStyle);
// $table->addCell(300, $styleCell)->addText('JUL', $fontStyle);
// $table->addCell(300, $styleCell)->addText('AGO', $fontStyle);
// $table->addCell(300, $styleCell)->addText('SEP', $fontStyle);
// $table->addCell(300, $styleCell)->addText('OCT', $fontStyle);
// $table->addCell(300, $styleCell)->addText('NOV', $fontStyle);
// $table->addCell(300, $styleCell)->addText('DIC', $fontStyle);
//
// // Add more rows / cells
//
// while ($row = mysql_fetch_assoc($tabla_informe)) {
//   if ($row['id_componente'] == 1) {
//     $table->addRow();
//     $table->addCell(300)->addText("");
//     $table->addCell(300)->addText("");
//     $table->addCell(300)->addText("");
//     $table->addCell(300)->addText("");
//     $table->addCell(300)->addText("");
//     $table->addCell(300)->addText("");
//     $table->addCell(300)->addText("");
//     $table->addCell(300)->addText("");
//     $table->addCell(300)->addText("");
//     $table->addCell(300)->addText("");
//   }
// }


// Título

$titulo1 = "1.	INFORME TÉCNICO DEL CONTRATO:";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo1, 'f1', 'p1');

$texto1 = "La verificación del cumplimiento de las obligaciones contractuales y los lineamientos vigentes realizada por
la interventoría, se ha efectuado a través de visitas al prestador del servicio en las sedes de atención y sede administrativa,
según se muestra en el siguiente cuadro:";
$phpWord->addFontStyle('f2', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p2', array('align'=>'both'));
$section->addText($texto1, 'f2', 'p2');

$section->addText();
//Sección pedagogico
$titulo_pedagogico = "1.1	COMPONENTE PROCESO PEDAGÓGICO";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_pedagogico, 'f1', 'p1');

$pedagogico = "Este componente se encarga de verificar que la entidad prestadora del servicio articule los procesos de interacción educativa con los niños y las niñas, reconociendo las particularidades que caracterizan su desarrollo infantil, desde la gestación hasta los cinco (5) años, sus familias y otras personas significativas; y que cumpla con las orientaciones para la planeación pedagógica soportada en los pilares que potencian el desarrollo integral en la primera infancia (ambientes, ritmos cotidianos, lúdica, juego y recreación, motricidad y actividad física, lenguajes de expresión artística, la literatura y la expresión literaria y la exploración del entorno y el cuidado del medio ambiente).";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($pedagogico, 'dc1', 'dc2');

$section->addText();

$debido_proceso_pedagogico = $debido_proceso[5];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_pedagogico, 'dp1', 'dp2');

$titulo_logros_pedagogico = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_pedagogico, 'tl1', 'tl2');

$logros_pedagogicos = $logros[5];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_pedagogicos, 'l1', 'l2');

$titulo_deficultades_pedagogico = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_pedagogico, 'td1', 'td2');

$dificultades_pedagogicos = $dificultades[5];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_pedagogicos, 'd1', 'd2');

$section->addText();
//Sección valoración de desarrollo
$titulo_valoracion_desarrollo = "1.2	COMPONENTE VALORACIÓN DEL DESARROLLO";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_valoracion_desarrollo, 'f1', 'p1');

$valoracion_desarrollo = "Este componente tiene por objetivo verificar que la entidad prestadora del servicio evidencie la realización del seguimiento al desarrollo que tienen los niños y las niñas, como resultado de la práctica educativa orientada a su desarrollo integral. De igual manera, velar porque la entidad cumpla con la identificación de posibles alertas o factores de riesgo que incidan en el desarrollo infantil.";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($valoracion_desarrollo, 'dc1', 'dc2');

$section->addText();

$debido_proceso_valoracion_d = $debido_proceso[9];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_valoracion_d, 'dp1', 'dp2');

$titulo_logros_valoracion_d = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_valoracion_d, 'tl1', 'tl2');

$logros_valoracion_d = $logros[9];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_valoracion_d, 'l1', 'l2');

$titulo_deficultades_valoracion_d = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_valoracion_d, 'td1', 'td2');

$dificultades_valoracion_d = $dificultades[9];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_valoracion_d, 'd1', 'd2');

$section->addText();
//Sección educación en salud
$titulo_educacion_salud = "1.3	COMPONENTE EDUCACIÓN EN SALUD";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_educacion_salud, 'f1', 'p1');

$educacion_salud = "El componente de Educación en Salud de la Interventoría verifica el cumplimiento de la calidad de la prestación del servicio de Atención Integral a la Primera Infancia enmarcados en la Resolución 000439 de 2017, los Lineamientos Técnicos para la operación de la modalidad con sus respectivos anexos, específicamente lo establecido para Salud y Gestión del Riesgo.
En el marco de la atención integral, la salud se entiende como un derecho fundamental, irrenunciable e impostergable.
De esta manera, el componente de salud está orientado a la promoción y el cumplimiento del derecho fundamental a la salud integral de las niñas y los niños desde la gestación hasta cumplidos los 6 años de edad.";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($educacion_salud, 'dc1', 'dc2');

$section->addText();

$debido_proceso_educacion_s = $debido_proceso[7];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_educacion_s, 'dp1', 'dp2');

$titulo_logros_educacion_s = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_educacion_s, 'tl1', 'tl2');

$logros_educacion_s = $logros[7];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_educacion_s, 'l1', 'l2');

$titulo_deficultades_educacion_s = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_educacion_s, 'td1', 'td2');

$dificultades_educacion_s = $dificultades[7];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_educacion_s, 'd1', 'd2');

$section->addText();
//Sección educación en gestión riesgo
$titulo_gestion_riesgo = "1.4	COMPONENTE EDUCACIÓN EN GESTIÓN DEL RIESGO EN EMERGENCIAS Y DESASTRES";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_gestion_riesgo, 'f1', 'p1');

$gestion_riesgo = "El componente de Educación en Gestión del Riesgo en Emergencias y Desastres está estrechamente ligado al concepto de promoción de la salud y prevención de la enfermedad, no solo preparando a la población frente a las eventualidades que se puedan presentar, sino además, permitiendo identificar riesgos potenciales y tomando medidas ante dichos riesgos.
La Interventoría se encarga de verificar que las entidades cuenten con procesos de planeación, identificación, prevención, mitigación, atención y primera respuesta frente a los casos que se presenten con respecto a emergencias, desastres y eventualidades en la salud de los niños, niñas y agentes educativos.";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($gestion_riesgo, 'dc1', 'dc2');

$section->addText();

$debido_proceso_gestion_riesgo = $debido_proceso[8];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_gestion_riesgo, 'dp1', 'dp2');

$titulo_logros_gestion_r = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_gestion_r, 'tl1', 'tl2');

$logros_gestion_r = $logros[8];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_gestion_r, 'l1', 'l2');

$titulo_deficultades_gestion_r = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_gestion_r, 'td1', 'td2');

$dificultades_gestion_r = $dificultades[8];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_gestion_r, 'd1', 'd2');

$section->addText();
//Sección alimentación y nutrición
$titulo_nutricion = "1.5	COMPONENTE ALIMENTACIÓN Y NUTRICIÓN";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_nutricion, 'f1', 'p1');

$nutricion = "El componente de Alimentación y Nutrición contribuye al cumplimiento de la promoción y protección del derecho a la alimentación adecuada y a no padecer hambre, así como las acciones de restablecimiento de este derecho, cuando ello es necesario, mediante estrategias que pueden comprenderse desde el concepto de seguridad alimentaria y nutricional, con incidencia directa en los ejes de acceso, consumo, aprovechamiento biológico y calidad e inocuidad de la alimentación de madres gestantes y lactantes, niños y niñas.";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($nutricion, 'dc1', 'dc2');

$section->addText();

$debido_proceso_nutricion = $debido_proceso[0];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_nutricion, 'dp1', 'dp2');

$titulo_logros_nutricion = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_nutricion, 'tl1', 'tl2');

$logros_nutricion = $logros[0];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_nutricion, 'l1', 'l2');

$titulo_deficultades_nutricion = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_nutricion, 'td1', 'td2');

$dificultades_nutricion = $dificultades[0];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_nutricion, 'd1', 'd2');

$section->addText();
//Sección protección
$titulo_proteccion = "1.6	COMPONENTE PROTECCIÓN";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_proteccion, 'f1', 'p1');

$proteccion = "Enmarcado desde lo estipulado en la Ley 1098 Código de Infancia y Adolescencia, se verifica: planeación e implementación de acciones orientadas a propiciar la garantía de derechos, y la prevención de la amenaza, inobservancia y vulneración de derechos de los niños y de las niñas; protocolo para la detección y atención de casos de presunta inobservancia, amenaza y vulneración de derechos; protocolo para prevenir y mitigar riesgos frente a situaciones de violencia social, y finalmente, la construcción e implementación de procedimientos para la atención de ingreso y salida, situaciones fortuitas de extravío, y fallecimiento de niños, niñas y agentes educativos.";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($proteccion, 'dc1', 'dc2');

$section->addText();

$debido_proceso_proteccion = $debido_proceso[6];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_proteccion, 'dp1', 'dp2');

$titulo_logros_proteccion = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_proteccion, 'tl1', 'tl2');

$logros_proteccion = $logros[6];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_proteccion, 'l1', 'l2');

$titulo_deficultades_proteccion = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_proteccion, 'td1', 'td2');

$dificultades_proteccion = $dificultades[6];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_proteccion, 'd1', 'd2');

$section->addText();
//Sección participación
$titulo_participacion = "1.7	COMPONENTE PARTICIPACIÓN Y MOVILIZACIÓN SOCIAL";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_participacion, 'f1', 'p1');

$participacion = "Para este componente, se verifica la construcción participativa y socialización del acuerdo de convivencia basado en los principios de inclusión, equidad y respeto, promoviendo la corresponsabilidad en la atención integral. Igualmente, se verifica la participación mensual en las mesas de primera infancia y acciones efectivas de articulaciones y movilizaciones sociales como estrategia interinstitucional, intersectorial e interdisciplinar, que permitan promover la generación de capital social para favorecer el desarrollo integral de los niños y de las niñas.";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($participacion, 'dc1', 'dc2');

$section->addText();

$debido_proceso_participacion = $debido_proceso[4];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_participacion, 'dp1', 'dp2');

$titulo_logros_participacion = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_participacion, 'tl1', 'tl2');

$logros_participacion = $logros[4];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_participacion, 'l1', 'l2');

$titulo_deficultades_participacion = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_participacion, 'td1', 'td2');

$dificultades_participacion = $dificultades[4];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_participacion, 'd1', 'd2');

$section->addText();
//Sección interacción con familias
$titulo_interaccion = "1.8	COMPONENTE INTERACCIÓN CON FAMILIAS Y OTRAS PERSONAS SIGNIFICATIVAS";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_interaccion, 'f1', 'p1');

$interaccion = "Desde este componente, se verifica la implementación de acciones de acompañamiento; la planeación, ejecución y evaluación del plan de formación a familias y otras personas significativas, a través de estrategias participativas, reflexivas y vivenciales. Así mismo, la implementación de estrategias comunicaciones, que permitan a la familia fortalecer vínculos afectivos y sus capacidades en el acompañamiento e interacción con los niños y niñas, facilitando su reconocimiento como sujetos de derechos y protagonistas de su propio desarrollo.";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($interaccion, 'dc1', 'dc2');

$section->addText();

$debido_proceso_interaccion = $debido_proceso[3];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_interaccion, 'dp1', 'dp2');

$titulo_logros_interaccion = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_interaccion, 'tl1', 'tl2');

$logros_interaccion = $logros[3];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_interaccion, 'l1', 'l2');

$titulo_deficultades_interaccion = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_interaccion, 'td1', 'td2');

$dificultades_interaccion = $dificultades[3];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_interaccion, 'd1', 'd2');

$section->addText();
//Sección verificación de dotación
$titulo_dotacion = "1.9	COMPONENTE VERIFICACIÓN DE DOTACIÓN";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_dotacion, 'f1', 'p1');

$dotacion = "El componente de Dotación verifica las características de la dotación, el material didáctico y de consumo necesarios para la atención con calidad de los niños y las niñas; acorde con los documentos técnicos vigentes y anexos establecidos por el Programa Buen Comienzo según las modalidades de atención.";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($dotacion, 'dc1', 'dc2');

$section->addText();

$debido_proceso_dotacion = $debido_proceso[1];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_dotacion, 'dp1', 'dp2');

$titulo_logros_dotacion = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_dotacion, 'tl1', 'tl2');

$logros_dotacion = $logros[1];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_dotacion, 'l1', 'l2');

$titulo_deficultades_dotacion = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_dotacion, 'td1', 'td2');

$dificultades_dotacion = $dificultades[1];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_dotacion, 'd1', 'd2');

$section->addText();
//Sección infraestructura
$titulo_infraestructura = "1.10	COMPONENTE INFRAESTRUCTURA";
$phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('p1', array('align'=>'both'));
$section->addText($titulo_infraestructura, 'f1', 'p1');

$infraestructura = "El componente de Infraestructura verifica las condiciones mínimas que deben cumplir los espacios de atención de los niños y niñas, incluyendo aspectos como: ambiente y entorno, seguridad de la infraestructura, espacios de servicio y espacios de atención y recreativos, de acuerdo con los estándares de calidad establecidos en el lineamiento técnico para la prestación del servicio de atención integral a la primera infancia.";
$phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dc2', array('align'=>'both'));
$section->addText($infraestructura, 'dc1', 'dc2');

$section->addText();

$debido_proceso_infraestructura = $debido_proceso[2];
$phpWord->addFontStyle('dp1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('dp2', array('align'=>'both'));
$section->addText($debido_proceso_infraestructura, 'dp1', 'dp2');

$titulo_logros_infraestructura = "Logros";
$phpWord->addFontStyle('tl1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('tl2', array('align'=>'both'));
$section->addText($titulo_logros_infraestructura, 'tl1', 'tl2');

$logros_infraestructura = $logros[2];
$phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('l2', array('align'=>'both'));
$section->addText($logros_infraestructura, 'l1', 'l2');

$titulo_deficultades_infraestructura = "Dificultades";
$phpWord->addFontStyle('td1', array('bold'=>true, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('td2', array('align'=>'both'));
$section->addText($titulo_deficultades_infraestructura, 'td1', 'td2');

$dificultades_infraestructura = $dificultades[2];
$phpWord->addFontStyle('d1', array('bold'=>false, 'name' => 'Arial', 'size'=>12));
$phpWord->addParagraphStyle('d2', array('align'=>'both'));
$section->addText($dificultades_infraestructura, 'd1', 'd2');

ob_clean();
header('Content-Description: File Transfer');
header('Content-type: application/force-download');
header("Content-Disposition: attachment;filename='$id_contrato.docx'");

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
//return $response;
?>
