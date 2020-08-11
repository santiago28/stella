<?php
require_once 'vendor/autoload.php';
require_once 'conexion.php';
//require_once 'index.php';
//$exportar = new Exportar();
//$id_contrato = "4600069314";
$id_contrato = $_GET["code"];

$informeliquidacion = mysql_query(("
SELECT informe_liquidacion.id_contrato, informe_liquidacion.observacion_contrato, prestador.nombre_prestador, componente.id_componente ,componente.nombre_componente
FROM informe_liquidacion, prestador, componente
WHERE informe_liquidacion.id_componente = componente.id_componente
AND informe_liquidacion.id_contrato = '$id_contrato'
AND informe_liquidacion.id_prestador = prestador.id_prestador
GROUP BY informe_liquidacion.id_componente
ORDER BY componente.id_componente"),$conexion);

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

$styleTable = array('borderSize'=>6,'cellMargin'=>80);
//$styleFirstRow = array('bgColor'=>'d9d9d9');
$styleCellTitle = array('valign'=>'center','bgColor'=>'d9d9d9');
$fontStyleTitle = array('bold'=>true, 'align'=>'center', 'name' => 'Arial', 'size'=>10);
$fontStyleText = array('name' => 'Arial', 'size'=>10);



if (mysql_num_rows($informeliquidacion) > 0) {

  while ($row = mysql_fetch_assoc($informeliquidacion)) {
    $lista_componentes[] = $row["id_componente"];
    $observacion_contrato[] = $row["observacion_contrato"];
    $prestador[] = $row["nombre_prestador"];
  }

  $section->addText();

  $titulo1 = "1	INFORME TÉCNICO DEL CONTRATO:";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo1, 'f1', 'p1');

  $section->addText();
  //Sección psicosocial
  $titulo_pedagogico = "1.1	COMPONENTE PEDAGÓGICO";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo_pedagogico, 'f1', 'p1');

  $indice_ped = array_search(8, $lista_componentes, false);

  if ($indice_ped !== false) {
    $texto_ped = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_ped]);
    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
    $section->addText($texto_ped, 'l1', 'l2');

    $section->addText();
  }
  //Sección psicosocial
  $titulo_psicosocial = "1.2	COMPONENTE PSICOSOCIAL";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo_psicosocial, 'f1', 'p1');

  $indice_psico = array_search(9, $lista_componentes, false);

  if ($indice_psico !== false) {
    $texto_psico = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_psico]);
    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
    $section->addText($texto_psico, 'l1', 'l2');

    $section->addText();
  }

  //Sección Salud
  $titulo_salud = "1.3	COMPONENTE EDUCACIÓN EN SALUD Y EDUCACIÓN EN GESTIÓN DEL RIESGO";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo_salud, 'f1', 'p1');

  $indice_salud = array_search(1, $lista_componentes, false);

  if ($indice_salud !== false) {
    $texto_salud = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_salud]);
    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
    $section->addText($texto_salud, 'l1', 'l2');

    $section->addText();
  }


  //Sección Nutrición
  $titulo_nutricion = "1.4	COMPONENTE ALIMENTACION Y NUTRICION";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo_nutricion, 'f1', 'p1');

  $indice_nutricion = array_search(7, $lista_componentes, false);

  if ($indice_nutricion !== false) {
    $texto_nut = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_nutricion]);
    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
    $section->addText($texto_nut, 'l1', 'l2');

    $section->addText();
  }

  //Sección Verficación de dotación
  $titulo_ver_dot = "1.5	COMPONENTE VERIFICACIÓN DE DOTACIÓN";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo_ver_dot, 'f1', 'p1');

  $indice_dotacion = array_search(2, $lista_componentes, false);

  if ($indice_dotacion !== false) {
    $texto_ver_dot = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_dotacion]);
    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
    $section->addText($texto_ver_dot, 'l1', 'l2');

    $section->addText();
  }

  //Sección Infraestructura
  $titulo_ver_dot = "1.6	COMPONENTE INFRAESTRUCTURA";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo_ver_dot, 'f1', 'p1');

  $indice_infra = array_search(5, $lista_componentes, false);

  if ($indice_infra !== false) {
    $texto_infra = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_infra]);
    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
    $section->addText($texto_infra, 'l1', 'l2');
  }

}



ob_clean();
header('Content-Description: File Transfer');
header('Content-type: application/force-download');
header("Content-Disposition: attachment;filename='IL-".$id_contrato.".docx'");

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
//return $response;

?>
