<?php
require_once 'vendor/autoload.php';
require_once 'conexion.php';
//require_once 'index.php';
//$exportar = new Exportar();
//$id_contrato = "4600069314";
$id_contrato = $_GET["code"];

$informefinal = mysql_query(("
SELECT informes_finales.id_contrato, tema.id_tema, tema.nombre_tema, informes_finales.observacion_contrato, prestador.nombre_prestador
FROM informes_finales, tema,prestador
WHERE informes_finales.id_tema = tema.id_tema
AND informes_finales.id_contrato = '$id_contrato'
AND informes_finales.id_prestador = prestador.id_prestador
GROUP BY informes_finales.id_tema
ORDER BY tema.nombre_tema"),$conexion);

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

$styleTable = array('borderSize'=>6,'cellMargin'=>80);
//$styleFirstRow = array('bgColor'=>'d9d9d9');
$styleCellTitle = array('valign'=>'center','bgColor'=>'d9d9d9');
$fontStyleTitle = array('bold'=>true, 'align'=>'center', 'name' => 'Arial', 'size'=>10);
$fontStyleText = array('name' => 'Arial', 'size'=>10);

$info_contrato = mysql_query(("
SELECT id_contrato, id_modalidad
FROM contrato_x_sede
WHERE id_contrato = '$id_contrato'"),$conexion);

$contrato = mysql_fetch_assoc($info_contrato);

$modalidad = $contrato["id_modalidad"];

if ($modalidad == 5) {
  if ($contrato["id_contrato"] == 4600069314) {
    $id_modalidad = 3;
  }else {
    $id_modalidad = 2;
  }
}elseif ($modalidad == 1) {
  $id_modalidad = 1;
}elseif ($modalidad ==7) {
  $id_modalidad = 4;
}elseif ($modalida == 6) {
  $id_modalidad = 5;
}

$obligaciones = mysql_query(("
SELECT id_modalidad, obligacion, observacion
FROM obligaciones
WHERE id_modalidad = '$id_modalidad' and estado = 1"), $conexion);

if (mysql_num_rows($informefinal) > 0 ) {
  while ($row = mysql_fetch_assoc($informefinal)) {
    $lista_temas[] = $row["id_tema"];
    $observacion_contrato[] = $row["observacion_contrato"];
    $prestador[] = $row["nombre_prestador"];
  }
  $nombre_prestador = $prestador[0];




  $section->addText();
  //Sección pedagogico

  $section->addText();

  $NoContrato = "CONTRATO No. ".$id_contrato." DE 2017";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('ic1', array('align'=>'center'));
  $section->addText($NoContrato, 'f1', 'ic1');

  $styleTable = array('borderSize'=>6,'cellMargin'=>80);
  //$styleFirstRow = array('bgColor'=>'d9d9d9');
  $styleCellTitle = array('valign'=>'center','bgColor'=>'d9d9d9');
  $fontStyleTitle = array('bold'=>true, 'align'=>'center', 'name' => 'Arial', 'size'=>10);
  $fontStyleText = array('name' => 'Arial', 'size'=>10);
  // $phpWord->addTableStyle('myOwnTableStyle', $styleTable);
  // $table = $section->addTable('myOwnTableStyle');
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('CONTRATANTE:', $fontStyleTitle);
  // $table->addCell(6000)->addText("MUNICIPIO DE MEDELLÍN – SECRETARÍA DE EDUCACIÓN", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('CONTRATISTA:', $fontStyleTitle);
  // $table->addCell(6000)->addText("EMPRESA SOCIAL DEL ESTADO - METROSALUD", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('NIT:', $fontStyleTitle);
  // $table->addCell(6000)->addText("800.058.016-1", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('REPRESENTANTE LEGAL:', $fontStyleTitle);
  // $table->addCell(6000)->addText("OLGA CECILIA MEJÍA JARAMILLO", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('CEDULA DEL REPRESENTANTE LEGAL:', $fontStyleTitle);
  // $table->addCell(6000)->addText("43.046.029", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('OBJETO:', $fontStyleTitle);
  // $table->addCell(6000)->addText("Contrato Interadministrativo para la atención integral a familias gestantes/lactantes,
  // niños/niñas durante sus seis (6) primeros meses de vida en zona urbana y en zona rural  hasta los cinco (5) años de vida
  // en la modalidad Entorno Familiar.", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('VALOR INICIAL:', $fontStyleTitle);
  // $table->addCell(6000)->addText("DOS MIL SEISCIENTOS CUARENTA Y SEIS MILLONES DE PESOS ML ($2.646.000.000) Exento de IVA", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('DURACIÓN DEL CONTRATO:', $fontStyleTitle);
  // $table->addCell(6000)->addText("CIENTO CUATRO (104) DÍAS CALENDARIOS", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('FECHA DE INICIO:', $fontStyleTitle);
  // $table->addCell(6000)->addText("17 DE FEBRERO DE 2017", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('FECHA DE TERMINACIÓN:', $fontStyleTitle);
  // $table->addCell(6000)->addText("31 DE MAYO DE 2017", $fontStyleText);
  // $table->addRow(900);
  // $table->addCell(1500, $styleCellTitle)->addText('VALOR EJECUTADO:', $fontStyleTitle);
  // $table->addCell(6000)->addText("DOS MIL TRESCIENTOS SESENTA Y CINCO MILLONES TRESCIENTOS SESENTA Y DOS MIL NOVECIENTOS VEINTISIETE PESOS ML ($2.365.362.927)", $fontStyleText);

  $section->addText();

  $infoContrato = "Con el objeto de describir el seguimiento realizado a la ejecución del contrato ".$id_contrato." de 2017,
  se presenta el Informe final de interventoría del contrato de la referencia, con fundamento en la Ley 80 de 1993,
  Ley 1150 de 2007, Ley 1474 de 2011, Decreto 1082 de 2015, Decreto Municipal 1920 de diciembre 01 de 2015 y demás normas
  que regulan la materia, previos los siguientes ítems:";
  $phpWord->addFontStyle('dc1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('dc2', array('align'=>'both'));
  $section->addText($infoContrato, 'dc1', 'dc2');

  $section->addText();

  $titulo1 = "1	INFORME TÉCNICO DEL CONTRATO:";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo1, 'f1', 'p1');

  $section->addText();

  $texto1 = "Conforme a las visitas realizadas y a las valoraciones porcentuales obtenidas en las mismas la interventoría
  indica para el operador ".$nombre_prestador.", con relación al cumplimiento de los estándares verificados para los componentes
  de la atención integral, lo siguiente";
  $phpWord->addFontStyle('f2', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p2', array('align'=>'both'));
  $section->addText($texto1, 'f2', 'p2');

  $section->addText();
  //Sección pedagogico
  $titulo_pedagogico = "1.1	COMPONENTE PEDAGÓGICO";
  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo_pedagogico, 'f1', 'p1');

  $titulo_visitas_pedagogico = "Visitas realizadas durante la vigencia del contrato";
  $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p12', array('align'=>'both'));
  $section->addText($titulo_visitas_pedagogico, 'f12', 'p12');

  $visitas_pedagogia = mysql_query(("
  SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
  FROM acta, componente
  WHERE acta.id_componente = componente.id_componente and componente.id_componente = '8'
  and acta.id_contrato = '$id_contrato'"), $conexion);

  $visitas_fallidas_pedagogia = mysql_query(("
  SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
  FROM acta_fallida as acta, componente
  WHERE acta.id_componente = componente.id_componente and componente.id_componente = '8'
  and acta.id_contrato = '$id_contrato'"), $conexion);

  $total_visitas_pedagogia = mysql_num_rows($visitas_pedagogia) + mysql_num_rows($visitas_fallidas_pedagogia);

  $phpWord->addTableStyle('visitas_pedagogia', $styleTable);
  $table_vis_ped = $section->addTable('visitas_pedagogia');
  $table_vis_ped->addRow(900);
  $table_vis_ped->addCell(4000, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
  $table_vis_ped->addCell(4000, $styleCellTitle)->addText('TOTAL VISITAS REALIZADAS', $fontStyleTitle);
  $table_vis_ped->addRow(900);
  $table_vis_ped->addCell(4000)->addText("Pedagogía", $fontStyleText);
  $table_vis_ped->addCell(4000)->addText($total_visitas_pedagogia, $fontStyleText);

  $section->addText();

  $titulo_cal_cum_pedagogia = "Resultados cuantitativos y cualitativos de la prestación del servicio";
  $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
  $section->addText($titulo_cal_cum_pedagogia, 'f12', 'p1');

  $calidad_pp= mysql_query(("
  SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
  FROM
  (
    SELECT
    sum(porcentaje_inicial) promedio_componente,
    sum(porcentaje_referencia) porcentaje_referencia
    FROM
    (
      SELECT
      sum(porc_inicial) porcentaje_inicial,
      sum(porc_referencia) porcentaje_referencia
      FROM
      evaluacion
      WHERE
      id_contrato='$id_contrato' and
      id_componente='8' and
      id_tema='801' and
      estado='1'
      group by id_subtema, id_acta
      ) as operacion) promedio
      "),$conexion);

      while ($row=mysql_fetch_assoc($calidad_pp)) {
        $calidad_pro_ped = round($row['promedio_componente'],4)*100;
      }

      $cumplimiento_pp= mysql_query(("
      SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
      FROM
      (
        SELECT
        sum(porcentaje_final) promedio_componente,
        sum(porcentaje_referencia) porcentaje_referencia
        FROM
        (
          SELECT
          sum(porc_final) porcentaje_final,
          sum(porc_referencia) porcentaje_referencia
          FROM
          evaluacion
          WHERE
          id_contrato='$id_contrato' and
          id_componente='8' and
          id_tema='801' and
          estado='1'
          group by id_subtema, id_acta
          ) as operacion) promedio
          "),$conexion);

          while ($row=mysql_fetch_assoc($cumplimiento_pp)) {
            $cumplimiento_pro_ped = round($row['promedio_componente'],4)*100;
          }

          $calidad_va= mysql_query(("
          SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
          FROM
          (
            SELECT
            sum(porcentaje_inicial) promedio_componente,
            sum(porcentaje_referencia) porcentaje_referencia
            FROM
            (
              SELECT
              sum(porc_inicial) porcentaje_inicial,
              sum(porc_referencia) porcentaje_referencia
              FROM
              evaluacion
              WHERE
              id_contrato='$id_contrato' and
              id_componente='8' and
              id_tema='802' and
              estado='1'
              group by id_subtema, id_acta
              ) as operacion) promedio
              "),$conexion);

              while ($row=mysql_fetch_assoc($calidad_va)) {
                $calidad_va_ped = round($row['promedio_componente'],4)*100;
              }

              $cumplimiento_va= mysql_query(("
              SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
              FROM
              (
                SELECT
                sum(porcentaje_final) promedio_componente,
                sum(porcentaje_referencia) porcentaje_referencia
                FROM
                (
                  SELECT
                  sum(porc_final) porcentaje_final,
                  sum(porc_referencia) porcentaje_referencia
                  FROM
                  evaluacion
                  WHERE
                  id_contrato='$id_contrato' and
                  id_componente='8' and
                  id_tema='802' and
                  estado='1'
                  group by id_subtema, id_acta
                  ) as operacion) promedio
                  "),$conexion);

                  while ($row=mysql_fetch_assoc($cumplimiento_va)) {
                    $cumplimiento_va_ped = round($row['promedio_componente'],4)*100;
                  }

                  $phpWord->addTableStyle('cal_cum_pedagogia', $styleTable);
                  $table_cal_cum_ped = $section->addTable('cal_cum_pedagogia');
                  $table_cal_cum_ped->addRow(900);
                  $table_cal_cum_ped->addCell(2666, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                  $table_cal_cum_ped->addCell(2666, $styleCellTitle)->addText('% CALIDAD', $fontStyleTitle);
                  $table_cal_cum_ped->addCell(2666, $styleCellTitle)->addText('% CUMPLIMIENTO', $fontStyleTitle);
                  $table_cal_cum_ped->addRow(900);
                  $table_cal_cum_ped->addCell(2666)->addText("Procesos Pedagógicos", $fontStyleText);
                  $table_cal_cum_ped->addCell(2666)->addText($calidad_pro_ped."%", $fontStyleText);
                  $table_cal_cum_ped->addCell(2666)->addText($cumplimiento_pro_ped."%", $fontStyleText);
                  $table_cal_cum_ped->addRow(900);
                  $table_cal_cum_ped->addCell(2666)->addText("Valoración del Desarrollo", $fontStyleText);
                  $table_cal_cum_ped->addCell(2666)->addText($calidad_va_ped."%", $fontStyleText);
                  $table_cal_cum_ped->addCell(2666)->addText($cumplimiento_va_ped."%", $fontStyleText);

                  $section->addText();

                  $titulo_proc_ped = "Proceso pedagógico";
                  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                  $section->addText($titulo_proc_ped, 'f1', 'p1');

                  $indice_pp = array_search(801, $lista_temas, false);

                  if ($indice_pp !== false) {
                    $texto_proc_ped = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_pp]);
                    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                    $section->addText($texto_proc_ped, 'l1', 'l2');

                    $section->addText();
                  }

                  $titulo_va_ped = "Valoración del Desarrollo";
                  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                  $section->addText($titulo_va_ped, 'f1', 'p1');

                  $indice_val_des = array_search(802, $lista_temas, false);

                  if ($indice_val_des !== false) {
                    $texto_proc_ped = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_val_des]);
                    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                    $section->addText($texto_proc_ped, 'l1', 'l2');

                    $section->addText();
                  }

                  //Sección psicosocial
                  $titulo_psicosocial = "1.2	COMPONENTE PSICOSOCIAL";
                  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                  $section->addText($titulo_psicosocial, 'f1', 'p1');

                  $titulo_visitas_psicosocial = "Visitas realizadas durante la vigencia del contrato";
                  $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                  $section->addText($titulo_visitas_psicosocial, 'f12', 'p1');

                  $visitas_psicosocial = mysql_query(("
                  SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                  FROM acta, componente
                  WHERE acta.id_componente = componente.id_componente and componente.id_componente = '9'
                  and acta.id_contrato = '$id_contrato'"), $conexion);

                  $visitas_fallidas_psicosocial = mysql_query(("
                  SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                  FROM acta_fallida as acta, componente
                  WHERE acta.id_componente = componente.id_componente and componente.id_componente = '9'
                  and acta.id_contrato = '$id_contrato'"), $conexion);

                  $total_visitas_psicosocial = mysql_num_rows($visitas_psicosocial) + mysql_num_rows($visitas_fallidas_psicosocial);

                  $phpWord->addTableStyle('visitas_psicosocial', $styleTable);
                  $table_vis_psico = $section->addTable('visitas_psicosocial');
                  $table_vis_psico->addRow(900);
                  $table_vis_psico->addCell(4000, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                  $table_vis_psico->addCell(4000, $styleCellTitle)->addText('TOTAL VISITAS REALIZADAS', $fontStyleTitle);
                  $table_vis_psico->addRow(900);
                  $table_vis_psico->addCell(4000)->addText("Psicosocial", $fontStyleText);
                  $table_vis_psico->addCell(4000)->addText($total_visitas_psicosocial, $fontStyleText);

                  $section->addText();

                  $titulo_cal_cum_psicosocial = "Resultados cuantitativos y cualitativos de la prestación del servicio";
                  $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                  $section->addText($titulo_cal_cum_psicosocial, 'f12', 'p1');

                  $calidad_pa= mysql_query(("
                  SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                  FROM
                  (
                    SELECT
                    sum(porcentaje_inicial) promedio_componente,
                    sum(porcentaje_referencia) porcentaje_referencia
                    FROM
                    (
                      SELECT
                      sum(porc_inicial) porcentaje_inicial,
                      sum(porc_referencia) porcentaje_referencia
                      FROM
                      evaluacion
                      WHERE
                      id_contrato='$id_contrato' and
                      id_componente='9' and
                      id_tema='901' and
                      estado='1'
                      group by id_subtema, id_acta
                      ) as operacion) promedio
                      "),$conexion);

                      while ($row=mysql_fetch_assoc($calidad_pa)) {
                        $calidad_pa_psico = round($row['promedio_componente'],4)*100;
                      }

                      $cumplimiento_pa= mysql_query(("
                      SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                      FROM
                      (
                        SELECT
                        sum(porcentaje_final) promedio_componente,
                        sum(porcentaje_referencia) porcentaje_referencia
                        FROM
                        (
                          SELECT
                          sum(porc_final) porcentaje_final,
                          sum(porc_referencia) porcentaje_referencia
                          FROM
                          evaluacion
                          WHERE
                          id_contrato='$id_contrato' and
                          id_componente='9' and
                          id_tema='901' and
                          estado='1'
                          group by id_subtema, id_acta
                          ) as operacion) promedio
                          "),$conexion);

                          while ($row=mysql_fetch_assoc($cumplimiento_pa)) {
                            $cumplimiento_pa_psico = round($row['promedio_componente'],4)*100;
                          }

                          $calidad_pr= mysql_query(("
                          SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                          FROM
                          (
                            SELECT
                            sum(porcentaje_inicial) promedio_componente,
                            sum(porcentaje_referencia) porcentaje_referencia
                            FROM
                            (
                              SELECT
                              sum(porc_inicial) porcentaje_inicial,
                              sum(porc_referencia) porcentaje_referencia
                              FROM
                              evaluacion
                              WHERE
                              id_contrato='$id_contrato' and
                              id_componente='9' and
                              id_tema='902' and
                              estado='1'
                              group by id_subtema, id_acta
                              ) as operacion) promedio
                              "),$conexion);

                              while ($row=mysql_fetch_assoc($calidad_pr)) {
                                $calidad_pr_psico = round($row['promedio_componente'],4)*100;
                              }

                              $cumplimiento_pr= mysql_query(("
                              SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                              FROM
                              (
                                SELECT
                                sum(porcentaje_final) promedio_componente,
                                sum(porcentaje_referencia) porcentaje_referencia
                                FROM
                                (
                                  SELECT
                                  sum(porc_final) porcentaje_final,
                                  sum(porc_referencia) porcentaje_referencia
                                  FROM
                                  evaluacion
                                  WHERE
                                  id_contrato='$id_contrato' and
                                  id_componente='9' and
                                  id_tema='902' and
                                  estado='1'
                                  group by id_subtema, id_acta
                                  ) as operacion) promedio
                                  "),$conexion);

                                  while ($row=mysql_fetch_assoc($cumplimiento_pr)) {
                                    $cumplimiento_pr_psico = round($row['promedio_componente'],4)*100;
                                  }

                                  $calidad_if= mysql_query(("
                                  SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                  FROM
                                  (
                                    SELECT
                                    sum(porcentaje_inicial) promedio_componente,
                                    sum(porcentaje_referencia) porcentaje_referencia
                                    FROM
                                    (
                                      SELECT
                                      sum(porc_inicial) porcentaje_inicial,
                                      sum(porc_referencia) porcentaje_referencia
                                      FROM
                                      evaluacion
                                      WHERE
                                      id_contrato='$id_contrato' and
                                      id_componente='9' and
                                      id_tema='903' and
                                      estado='1'
                                      group by id_subtema, id_acta
                                      ) as operacion) promedio
                                      "),$conexion);

                                      while ($row=mysql_fetch_assoc($calidad_if)) {
                                        $calidad_if_psico = round($row['promedio_componente'],4)*100;
                                      }

                                      $cumplimiento_if= mysql_query(("
                                      SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                      FROM
                                      (
                                        SELECT
                                        sum(porcentaje_final) promedio_componente,
                                        sum(porcentaje_referencia) porcentaje_referencia
                                        FROM
                                        (
                                          SELECT
                                          sum(porc_final) porcentaje_final,
                                          sum(porc_referencia) porcentaje_referencia
                                          FROM
                                          evaluacion
                                          WHERE
                                          id_contrato='$id_contrato' and
                                          id_componente='9' and
                                          id_tema='903' and
                                          estado='1'
                                          group by id_subtema, id_acta
                                          ) as operacion) promedio
                                          "),$conexion);

                                          while ($row=mysql_fetch_assoc($cumplimiento_if)) {
                                            $cumplimiento_if_psico = round($row['promedio_componente'],4)*100;
                                          }

                                          $phpWord->addTableStyle('cal_cum_psicosocial', $styleTable);
                                          $table_cal_cum_psico = $section->addTable('cal_cum_psicosocial');
                                          $table_cal_cum_psico->addRow(900);
                                          $table_cal_cum_psico->addCell(2666, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                                          $table_cal_cum_psico->addCell(2666, $styleCellTitle)->addText('% CALIDAD', $fontStyleTitle);
                                          $table_cal_cum_psico->addCell(2666, $styleCellTitle)->addText('% CUMPLIMIENTO', $fontStyleTitle);
                                          $table_cal_cum_psico->addRow(900);
                                          $table_cal_cum_psico->addCell(2666)->addText("Participación", $fontStyleText);
                                          $table_cal_cum_psico->addCell(2666)->addText($calidad_pa_psico."%", $fontStyleText);
                                          $table_cal_cum_psico->addCell(2666)->addText($cumplimiento_pa_psico."%", $fontStyleText);
                                          $table_cal_cum_psico->addRow(900);
                                          $table_cal_cum_psico->addCell(2666)->addText("Protección", $fontStyleText);
                                          $table_cal_cum_psico->addCell(2666)->addText($calidad_pr_psico."%", $fontStyleText);
                                          $table_cal_cum_psico->addCell(2666)->addText($cumplimiento_pr_psico."%", $fontStyleText);
                                          $table_cal_cum_psico->addRow(900);
                                          $table_cal_cum_psico->addCell(2666)->addText("Interacción con Familias", $fontStyleText);
                                          $table_cal_cum_psico->addCell(2666)->addText($calidad_if_psico."%", $fontStyleText);
                                          $table_cal_cum_psico->addCell(2666)->addText($cumplimiento_if_psico."%", $fontStyleText);

                                          $section->addText();

                                          $titulo_pa_psico = "Participación y Movilización Social";
                                          $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                          $section->addText($titulo_pa_psico, 'f1', 'p1');

                                          $indice_pa_psico = array_search(901, $lista_temas, false);

                                          if ($indice_pa_psico !== false) {
                                            $texto_pa_psico = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_pa_psico]);
                                            $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                            $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                            $section->addText($texto_pa_psico, 'l1', 'l2');

                                            $section->addText();
                                          }

                                          $titulo_pr_psico = "Protección";
                                          $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                          $section->addText($titulo_pr_psico, 'f1', 'p1');

                                          $indice_pr_psico = array_search(902, $lista_temas, false);

                                          if ($indice_pr_psico !== false) {
                                            $texto_pr_psico = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_pr_psico]);
                                            $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                            $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                            $section->addText($texto_pr_psico, 'l1', 'l2');

                                            $section->addText();
                                          }

                                          $titulo_if_psico = "Procesos de interacción con familias y otras personas significativas";
                                          $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                          $section->addText($titulo_if_psico, 'f1', 'p1');

                                          $indice_if_psico = array_search(903, $lista_temas, false);

                                          if ($indice_if_psico !== false) {
                                            $texto_if_psico = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_if_psico]);
                                            $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                            $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                            $section->addText($texto_if_psico, 'l1', 'l2');

                                            $section->addText();
                                          }

                                          //Sección Salud
                                          $titulo_salud = "1.3	COMPONENTE EDUCACIÓN EN SALUD Y EDUCACIÓN EN GESTIÓN DEL RIESGO";
                                          $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                          $section->addText($titulo_salud, 'f1', 'p1');

                                          $titulo_visitas_salud = "Visitas realizadas durante la vigencia del contrato";
                                          $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                          $section->addText($titulo_visitas_salud, 'f12', 'p1');

                                          $visitas_salud = mysql_query(("
                                          SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                                          FROM acta, componente
                                          WHERE acta.id_componente = componente.id_componente and componente.id_componente = '1'
                                          and acta.id_contrato = '$id_contrato'"), $conexion);

                                          $visitas_fallidas_salud = mysql_query(("
                                          SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                                          FROM acta_fallida as acta, componente
                                          WHERE acta.id_componente = componente.id_componente and componente.id_componente = '1'
                                          and acta.id_contrato = '$id_contrato'"), $conexion);

                                          $total_visitas_salud = mysql_num_rows($visitas_salud) + mysql_num_rows($visitas_fallidas_salud);

                                          $phpWord->addTableStyle('visitas_salud', $styleTable);
                                          $table_vis_sa = $section->addTable('visitas_salud');
                                          $table_vis_sa->addRow(900);
                                          $table_vis_sa->addCell(4000, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                                          $table_vis_sa->addCell(4000, $styleCellTitle)->addText('TOTAL VISITAS REALIZADAS', $fontStyleTitle);
                                          $table_vis_sa->addRow(900);
                                          $table_vis_sa->addCell(4000)->addText("Salud y Educación en Gestión del Riesgo", $fontStyleText);
                                          $table_vis_sa->addCell(4000)->addText($total_visitas_salud, $fontStyleText);

                                          $section->addText();

                                          $titulo_cal_cum_salud = "Resultados cuantitativos y cualitativos de la prestación del servicio";
                                          $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                          $section->addText($titulo_cal_cum_salud, 'f12', 'p1');

                                          $calidad_sa= mysql_query(("
                                          SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                          FROM
                                          (
                                            SELECT
                                            sum(porcentaje_inicial) promedio_componente,
                                            sum(porcentaje_referencia) porcentaje_referencia
                                            FROM
                                            (
                                              SELECT
                                              sum(porc_inicial) porcentaje_inicial,
                                              sum(porc_referencia) porcentaje_referencia
                                              FROM
                                              evaluacion
                                              WHERE
                                              id_contrato='$id_contrato' and
                                              id_componente='1' and
                                              id_tema='101' and
                                              estado='1'
                                              group by id_subtema, id_acta
                                              ) as operacion) promedio
                                              "),$conexion);

                                              while ($row=mysql_fetch_assoc($calidad_sa)) {
                                                $calidad_sa_sa = round($row['promedio_componente'],4)*100;
                                              }

                                              $cumplimiento_sa= mysql_query(("
                                              SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                              FROM
                                              (
                                                SELECT
                                                sum(porcentaje_final) promedio_componente,
                                                sum(porcentaje_referencia) porcentaje_referencia
                                                FROM
                                                (
                                                  SELECT
                                                  sum(porc_final) porcentaje_final,
                                                  sum(porc_referencia) porcentaje_referencia
                                                  FROM
                                                  evaluacion
                                                  WHERE
                                                  id_contrato='$id_contrato' and
                                                  id_componente='1' and
                                                  id_tema='101' and
                                                  estado='1'
                                                  group by id_subtema, id_acta
                                                  ) as operacion) promedio
                                                  "),$conexion);

                                                  while ($row=mysql_fetch_assoc($cumplimiento_sa)) {
                                                    $cumplimiento_sa_sa = round($row['promedio_componente'],4)*100;
                                                  }

                                                  $calidad_se= mysql_query(("
                                                  SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                                  FROM
                                                  (
                                                    SELECT
                                                    sum(porcentaje_inicial) promedio_componente,
                                                    sum(porcentaje_referencia) porcentaje_referencia
                                                    FROM
                                                    (
                                                      SELECT
                                                      sum(porc_inicial) porcentaje_inicial,
                                                      sum(porc_referencia) porcentaje_referencia
                                                      FROM
                                                      evaluacion
                                                      WHERE
                                                      id_contrato='$id_contrato' and
                                                      id_componente='1' and
                                                      id_tema='102' and
                                                      estado='1'
                                                      group by id_subtema, id_acta
                                                      ) as operacion) promedio
                                                      "),$conexion);

                                                      while ($row=mysql_fetch_assoc($calidad_se)) {
                                                        $calidad_se_sa = round($row['promedio_componente'],4)*100;
                                                      }

                                                      $cumplimiento_se= mysql_query(("
                                                      SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                                      FROM
                                                      (
                                                        SELECT
                                                        sum(porcentaje_final) promedio_componente,
                                                        sum(porcentaje_referencia) porcentaje_referencia
                                                        FROM
                                                        (
                                                          SELECT
                                                          sum(porc_final) porcentaje_final,
                                                          sum(porc_referencia) porcentaje_referencia
                                                          FROM
                                                          evaluacion
                                                          WHERE
                                                          id_contrato='$id_contrato' and
                                                          id_componente='1' and
                                                          id_tema='102' and
                                                          estado='1'
                                                          group by id_subtema, id_acta
                                                          ) as operacion) promedio
                                                          "),$conexion);

                                                          while ($row=mysql_fetch_assoc($cumplimiento_se)) {
                                                            $cumplimiento_se_sa = round($row['promedio_componente'],4)*100;
                                                          }

                                                          $phpWord->addTableStyle('cal_cum_salud', $styleTable);
                                                          $table_cal_cum_sa = $section->addTable('cal_cum_salud');
                                                          $table_cal_cum_sa->addRow(900);
                                                          $table_cal_cum_sa->addCell(2666, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                                                          $table_cal_cum_sa->addCell(2666, $styleCellTitle)->addText('% CALIDAD', $fontStyleTitle);
                                                          $table_cal_cum_sa->addCell(2666, $styleCellTitle)->addText('% CUMPLIMIENTO', $fontStyleTitle);
                                                          $table_cal_cum_sa->addRow(900);
                                                          $table_cal_cum_sa->addCell(2666)->addText("Educación en Salud", $fontStyleText);
                                                          $table_cal_cum_sa->addCell(2666)->addText($calidad_sa_sa."%", $fontStyleText);
                                                          $table_cal_cum_sa->addCell(2666)->addText($cumplimiento_sa_sa."%", $fontStyleText);
                                                          $table_cal_cum_sa->addRow(900);
                                                          $table_cal_cum_sa->addCell(2666)->addText("Educación en Gestión del Riesgo", $fontStyleText);
                                                          $table_cal_cum_sa->addCell(2666)->addText($calidad_se_sa."%", $fontStyleText);
                                                          $table_cal_cum_sa->addCell(2666)->addText($cumplimiento_se_sa."%", $fontStyleText);

                                                          $section->addText();

                                                          $titulo_sa_sa = "Educación en Salud";
                                                          $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                          $section->addText($titulo_sa_sa, 'f1', 'p1');

                                                          $indice_salud = array_search(101, $lista_temas, false);

                                                          if ($indice_salud !== false) {
                                                            $texto_sa_sa = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_salud]);
                                                            $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                            $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                            $section->addText($texto_sa_sa, 'l1', 'l2');

                                                            $section->addText();
                                                          }

                                                          $titulo_se_sa = "Educación en Gestión del Riesgo";
                                                          $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                          $section->addText($titulo_se_sa, 'f1', 'p1');

                                                          $indice_seguridad = array_search(102, $lista_temas, false);

                                                          if ($indice_seguridad !== false) {
                                                            $texto_proc_ped = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_seguridad]);
                                                            $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                            $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                            $section->addText($texto_proc_ped, 'l1', 'l2');

                                                            $section->addText();
                                                          }

                                                          //Sección Nutrición

                                                          $titulo_nutricion = "1.4	COMPONENTE ALIMENTACION Y NUTRICION";
                                                          $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                          $section->addText($titulo_nutricion, 'f1', 'p1');

                                                          $titulo_visitas_nutricion = "Visitas realizadas durante la vigencia del contrato";
                                                          $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                          $section->addText($titulo_visitas_nutricion, 'f12', 'p1');

                                                          $visitas_nutricion = mysql_query(("
                                                          SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                                                          FROM acta, componente
                                                          WHERE acta.id_componente = componente.id_componente and componente.id_componente = '7'
                                                          and acta.id_contrato = '$id_contrato'"), $conexion);

                                                          $visitas_fallidas_nutricion = mysql_query(("
                                                          SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                                                          FROM acta_fallida as acta, componente
                                                          WHERE acta.id_componente = componente.id_componente and componente.id_componente = '7'
                                                          and acta.id_contrato = '$id_contrato'"), $conexion);

                                                          $total_visitas_nutricion = mysql_num_rows($visitas_nutricion) + mysql_num_rows($visitas_fallidas_nutricion);

                                                          $phpWord->addTableStyle('visitas_nutricion', $styleTable);
                                                          $table_vis_nut = $section->addTable('visitas_nutricion');
                                                          $table_vis_nut->addRow(900);
                                                          $table_vis_nut->addCell(4000, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                                                          $table_vis_nut->addCell(4000, $styleCellTitle)->addText('TOTAL VISITAS REALIZADAS', $fontStyleTitle);
                                                          $table_vis_nut->addRow(900);
                                                          $table_vis_nut->addCell(4000)->addText("Nutrición", $fontStyleText);
                                                          $table_vis_nut->addCell(4000)->addText($total_visitas_nutricion, $fontStyleText);

                                                          $section->addText();

                                                          $titulo_cal_cum_nutricion = "Resultados cuantitativos y cualitativos de la prestación del servicio";
                                                          $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                          $section->addText($titulo_cal_cum_nutricion, 'f12', 'p1');

                                                          $calidad_nt= mysql_query(("
                                                          SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                                          FROM
                                                          (
                                                            SELECT
                                                            sum(porcentaje_inicial) promedio_componente,
                                                            sum(porcentaje_referencia) porcentaje_referencia
                                                            FROM
                                                            (
                                                              SELECT
                                                              sum(porc_inicial) porcentaje_inicial,
                                                              sum(porc_referencia) porcentaje_referencia
                                                              FROM
                                                              evaluacion
                                                              WHERE
                                                              id_contrato='$id_contrato' and
                                                              id_componente='7' and
                                                              id_tema='701' and
                                                              estado='1'
                                                              group by id_subtema, id_acta
                                                              ) as operacion) promedio
                                                              "),$conexion);

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
                                                              descuentos_x_valoracion.id_contrato = '$id_contrato'
                                                              ORDER BY prestador.nombre_prestador"), $conexion);

                                                              $descuento = 0;
                                                              $descuento_no_patogeno = 0;
                                                              while ($row1=mysql_fetch_assoc($descuentos_calculo_calidad)) {
                                                                if ($row1['tipo_descuento'] == 3) {
                                                                  $descuento_no_patogeno = 0;
                                                                  $descuento_no_patogeno = $row1["descuento"];
                                                                }else {
                                                                  $descuento = $descuento + $row1["descuento"];
                                                                }
                                                              }

                                                              $descuento = $descuento + $descuento_no_patogeno;
                                                              $descuento = round($descuento,4);

                                                              while ($row=mysql_fetch_assoc($calidad_nt)) {
                                                                if ($row['promedio_componente']!="") {
                                                                  $promedio_componente = round($row['promedio_componente'],4)*100;
                                                                  $total_calidad_nutricion = $promedio_componente - $descuento;
                                                                }
                                                              }


                                                              $cumplimiento_nt= mysql_query(("
                                                              SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                                              FROM
                                                              (
                                                                SELECT
                                                                sum(porcentaje_final) promedio_componente,
                                                                sum(porcentaje_referencia) porcentaje_referencia
                                                                FROM
                                                                (
                                                                  SELECT
                                                                  sum(porc_final) porcentaje_final,
                                                                  sum(porc_referencia) porcentaje_referencia
                                                                  FROM
                                                                  evaluacion
                                                                  WHERE
                                                                  id_contrato='$id_contrato' and
                                                                  id_componente='7' and
                                                                  id_tema='701' and
                                                                  estado='1'
                                                                  group by id_subtema, id_acta
                                                                  ) as operacion) promedio
                                                                  "),$conexion);

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

                                                                  while ($row=mysql_fetch_assoc($cumplimiento_nt)) {
                                                                    if ($row['promedio_componente']!="") {
                                                                      $promedio_componente1 = round($row['promedio_componente'],4)*100;
                                                                      $total_cumplimiento_nutricion = $promedio_componente1 + $descuento1;
                                                                      if ($total_cumplimiento_nutricion > 100) {
                                                                        $total_cumplimiento_nutricion = 100;
                                                                      }
                                                                    }
                                                                  }

                                                                  $phpWord->addTableStyle('cal_cum_nutricion', $styleTable);
                                                                  $table_cal_cum_nut = $section->addTable('cal_cum_nutricion');
                                                                  $table_cal_cum_nut->addRow(900);
                                                                  $table_cal_cum_nut->addCell(2666, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                                                                  $table_cal_cum_nut->addCell(2666, $styleCellTitle)->addText('% CALIDAD', $fontStyleTitle);
                                                                  $table_cal_cum_nut->addCell(2666, $styleCellTitle)->addText('% CUMPLIMIENTO', $fontStyleTitle);
                                                                  $table_cal_cum_nut->addRow(900);
                                                                  $table_cal_cum_nut->addCell(2666)->addText("Alimentación y Nutrición", $fontStyleText);
                                                                  $table_cal_cum_nut->addCell(2666)->addText($total_calidad_nutricion."%", $fontStyleText);
                                                                  $table_cal_cum_nut->addCell(2666)->addText($total_cumplimiento_nutricion."%", $fontStyleText);

                                                                  $section->addText();

                                                                  $titulo_nut_nut = "Alimentación y Nutrición";
                                                                  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                  $section->addText($titulo_nut_nut, 'f1', 'p1');

                                                                  $indice_nutricion = array_search(701, $lista_temas, false);

                                                                  if ($indice_nutricion !== false) {
                                                                    $texto_nut_nut = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_nutricion]);
                                                                    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                                    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                                    $section->addText($texto_nut_nut, 'l1', 'l2');

                                                                    $section->addText();
                                                                  }

                                                                  //Sección dotación

                                                                  $titulo_dotacion = "1.5	COMPONENTE DOTACIÓN";
                                                                  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                  $section->addText($titulo_dotacion, 'f1', 'p1');

                                                                  $titulo_visitas_dotacion = "Visitas realizadas durante la vigencia del contrato";
                                                                  $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                                                                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                  $section->addText($titulo_visitas_dotacion, 'f12', 'p1');

                                                                  $visitas_dotacion = mysql_query(("
                                                                  SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                                                                  FROM acta, componente
                                                                  WHERE acta.id_componente = componente.id_componente and componente.id_componente = '2'
                                                                  and acta.id_contrato = '$id_contrato'"), $conexion);

                                                                  $visitas_fallidas_dotacion = mysql_query(("
                                                                  SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                                                                  FROM acta_fallida as acta, componente
                                                                  WHERE acta.id_componente = componente.id_componente and componente.id_componente = '2'
                                                                  and acta.id_contrato = '$id_contrato'"), $conexion);

                                                                  $total_visitas_dotacion = mysql_num_rows($visitas_dotacion) + mysql_num_rows($visitas_fallidas_dotacion);

                                                                  $phpWord->addTableStyle('visitas_dotacion', $styleTable);
                                                                  $table_vis_dot = $section->addTable('visitas_dotacion');
                                                                  $table_vis_dot->addRow(900);
                                                                  $table_vis_dot->addCell(4000, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                                                                  $table_vis_dot->addCell(4000, $styleCellTitle)->addText('TOTAL VISITAS REALIZADAS', $fontStyleTitle);
                                                                  $table_vis_dot->addRow(900);
                                                                  $table_vis_dot->addCell(4000)->addText("Dotación", $fontStyleText);
                                                                  $table_vis_dot->addCell(4000)->addText($total_visitas_dotacion, $fontStyleText);

                                                                  $section->addText();

                                                                  $titulo_cal_cum_dot = "Resultados cuantitativos y cualitativos de la prestación del servicio";
                                                                  $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                                                                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                  $section->addText($titulo_cal_cum_dot, 'f12', 'p1');

                                                                  $calidad_dot= mysql_query(("
                                                                  SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                                                  FROM
                                                                  (
                                                                    SELECT
                                                                    sum(porcentaje_inicial) promedio_componente,
                                                                    sum(porcentaje_referencia) porcentaje_referencia
                                                                    FROM
                                                                    (
                                                                      SELECT
                                                                      sum(porc_inicial) porcentaje_inicial,
                                                                      sum(porc_referencia) porcentaje_referencia
                                                                      FROM
                                                                      evaluacion
                                                                      WHERE
                                                                      id_contrato='$id_contrato' and
                                                                      id_componente='2' and
                                                                      id_tema='201' and
                                                                      estado='1'
                                                                      group by id_subtema, id_acta
                                                                      ) as operacion) promedio
                                                                      "),$conexion);

                                                                      while ($row=mysql_fetch_assoc($calidad_dot)) {
                                                                        $total_calidad_dot = round($row['promedio_componente'],4)*100;
                                                                      }

                                                                      $cumplimiento_dot = mysql_query(("
                                                                      SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                                                      FROM
                                                                      (
                                                                        SELECT
                                                                        sum(porcentaje_final) promedio_componente,
                                                                        sum(porcentaje_referencia) porcentaje_referencia
                                                                        FROM
                                                                        (
                                                                          SELECT
                                                                          sum(porc_final) porcentaje_final,
                                                                          sum(porc_referencia) porcentaje_referencia
                                                                          FROM
                                                                          evaluacion
                                                                          WHERE
                                                                          id_contrato='$id_contrato' and
                                                                          id_componente='2' and
                                                                          id_tema='201' and
                                                                          estado='1'
                                                                          group by id_subtema, id_acta
                                                                          ) as operacion) promedio
                                                                          "),$conexion);

                                                                          while ($row=mysql_fetch_assoc($cumplimiento_dot)) {
                                                                            $total_cumplimiento_dot = round($row['promedio_componente'],4)*100;
                                                                          }

                                                                          $phpWord->addTableStyle('cal_cum_dot', $styleTable);
                                                                          $table_cal_cum_dot = $section->addTable('cal_cum_dot');
                                                                          $table_cal_cum_dot->addRow(900);
                                                                          $table_cal_cum_dot->addCell(2666, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                                                                          $table_cal_cum_dot->addCell(2666, $styleCellTitle)->addText('% CALIDAD', $fontStyleTitle);
                                                                          $table_cal_cum_dot->addCell(2666, $styleCellTitle)->addText('% CUMPLIMIENTO', $fontStyleTitle);
                                                                          $table_cal_cum_dot->addRow(900);
                                                                          $table_cal_cum_dot->addCell(2666)->addText("Dotación", $fontStyleText);
                                                                          $table_cal_cum_dot->addCell(2666)->addText($total_calidad_dot."%", $fontStyleText);
                                                                          $table_cal_cum_dot->addCell(2666)->addText($total_cumplimiento_dot."%", $fontStyleText);

                                                                          $section->addText();

                                                                          $titulo_dot_dot = "Dotación";
                                                                          $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                          $section->addText($titulo_dot_dot, 'f1', 'p1');

                                                                          $indice_dotacion = array_search(201, $lista_temas, false);

                                                                          if ($indice_dotacion !== false) {
                                                                            $texto_dot = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_dotacion]);
                                                                            $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                                            $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                                            $section->addText($texto_dot, 'l1', 'l2');

                                                                            $section->addText();
                                                                          }



                                                                          //Sección infraestructura

                                                                          $titulo_infraestructura = "1.6	COMPONENTE INFRAESTRUCTURA";
                                                                          $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                          $section->addText($titulo_infraestructura, 'f1', 'p1');

                                                                          $titulo_visitas_infraestructura = "Visitas realizadas durante la vigencia del contrato";
                                                                          $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                                                                          $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                          $section->addText($titulo_visitas_infraestructura, 'f12', 'p1');

                                                                          $visitas_infraestructura = mysql_query(("
                                                                          SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                                                                          FROM acta, componente
                                                                          WHERE acta.id_componente = componente.id_componente and componente.id_componente = '5'
                                                                          and acta.id_contrato = '$id_contrato'"), $conexion);

                                                                          $visitas_fallidas_infraestructura = mysql_query(("
                                                                          SELECT acta.id_acta,componente.id_componente, componente.nombre_componente
                                                                          FROM acta_fallida as acta, componente
                                                                          WHERE acta.id_componente = componente.id_componente and componente.id_componente = '5'
                                                                          and acta.id_contrato = '$id_contrato'"), $conexion);

                                                                          $total_visitas_infraestructura = mysql_num_rows($visitas_infraestructura) + mysql_num_rows($visitas_fallidas_infraestructura);

                                                                          $phpWord->addTableStyle('visitas_infraestructura', $styleTable);
                                                                          $table_vis_infra = $section->addTable('visitas_infraestructura');
                                                                          $table_vis_infra->addRow(900);
                                                                          $table_vis_infra->addCell(4000, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                                                                          $table_vis_infra->addCell(4000, $styleCellTitle)->addText('TOTAL VISITAS REALIZADAS', $fontStyleTitle);
                                                                          $table_vis_infra->addRow(900);
                                                                          $table_vis_infra->addCell(4000)->addText("Infraestructura", $fontStyleText);
                                                                          $table_vis_infra->addCell(4000)->addText($total_visitas_infraestructura, $fontStyleText);

                                                                          $section->addText();

                                                                          $titulo_cal_cum_infra = "Resultados cuantitativos y cualitativos de la prestación del servicio";
                                                                          $phpWord->addFontStyle('f12', array('bold'=>true, 'italic'=>true, 'name' => 'Arial', 'size'=>10));
                                                                          $phpWord->addParagraphStyle('p12', array('align'=>'both'));
                                                                          $section->addText($titulo_cal_cum_infra, 'f12', 'p1');

                                                                          $calidad_inf= mysql_query(("
                                                                          SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                                                          FROM
                                                                          (
                                                                            SELECT
                                                                            sum(porcentaje_inicial) promedio_componente,
                                                                            sum(porcentaje_referencia) porcentaje_referencia
                                                                            FROM
                                                                            (
                                                                              SELECT
                                                                              sum(porc_inicial) porcentaje_inicial,
                                                                              sum(porc_referencia) porcentaje_referencia
                                                                              FROM
                                                                              evaluacion
                                                                              WHERE
                                                                              id_contrato='$id_contrato' and
                                                                              id_componente='5' and
                                                                              id_tema='501' and
                                                                              estado='1'
                                                                              group by id_subtema, id_acta
                                                                              ) as operacion) promedio
                                                                              "),$conexion);

                                                                              while ($row=mysql_fetch_assoc($calidad_inf)) {
                                                                                $total_calidad_inf = round($row['promedio_componente'],4)*100;
                                                                              }

                                                                              $cumplimiento_inf= mysql_query(("
                                                                              SELECT avg(promedio_componente/porcentaje_referencia) promedio_componente
                                                                              FROM
                                                                              (
                                                                                SELECT
                                                                                sum(porcentaje_final) promedio_componente,
                                                                                sum(porcentaje_referencia) porcentaje_referencia
                                                                                FROM
                                                                                (
                                                                                  SELECT
                                                                                  sum(porc_final) porcentaje_final,
                                                                                  sum(porc_referencia) porcentaje_referencia
                                                                                  FROM
                                                                                  evaluacion
                                                                                  WHERE
                                                                                  id_contrato='$id_contrato' and
                                                                                  id_componente='5' and
                                                                                  id_tema='501' and
                                                                                  estado='1'
                                                                                  group by id_subtema, id_acta
                                                                                  ) as operacion) promedio
                                                                                  "),$conexion);

                                                                                  while ($row=mysql_fetch_assoc($cumplimiento_inf)) {
                                                                                    $total_cumplimiento_inf = round($row['promedio_componente'],4)*100;
                                                                                  }

                                                                                  $phpWord->addTableStyle('cal_cum_infra', $styleTable);
                                                                                  $table_cal_cum_inf = $section->addTable('cal_cum_infra');
                                                                                  $table_cal_cum_inf->addRow(900);
                                                                                  $table_cal_cum_inf->addCell(2666, $styleCellTitle)->addText('NOMBRE COMPONENTE', $fontStyleTitle);
                                                                                  $table_cal_cum_inf->addCell(2666, $styleCellTitle)->addText('% CALIDAD', $fontStyleTitle);
                                                                                  $table_cal_cum_inf->addCell(2666, $styleCellTitle)->addText('% CUMPLIMIENTO', $fontStyleTitle);
                                                                                  $table_cal_cum_inf->addRow(900);
                                                                                  $table_cal_cum_inf->addCell(2666)->addText("Infraestructura", $fontStyleText);
                                                                                  $table_cal_cum_inf->addCell(2666)->addText($total_calidad_inf."%", $fontStyleText);
                                                                                  $table_cal_cum_inf->addCell(2666)->addText($total_cumplimiento_inf."%", $fontStyleText);

                                                                                  $section->addText();

                                                                                  $titulo_inf_inf = "Infraestructura";
                                                                                  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                                                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                                  $section->addText($titulo_inf_inf, 'f1', 'p1');

                                                                                  $indice_infra = array_search(501, $lista_temas, false);

                                                                                  if ($indice_infra !== false) {
                                                                                    $texto_inf_inf = str_replace("\r\n",'<w:br/>',$observacion_contrato[$indice_infra]);
                                                                                    $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                                                    $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                                                    $section->addText($texto_inf_inf, 'l1', 'l2');

                                                                                    $section->addText();
                                                                                  }


                                                                                  $titulo_cal_cum_total = "1.8.	VALORACIÓN TOTAL DEL CONTRATO";
                                                                                  $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                                                  $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                                  $section->addText($titulo_cal_cum_total, 'f1', 'p1');

                                                                                  $section->addText();


                                                                                  $texto1_total = "Como parte de la labor de seguimiento, vigilancia y control que realizó la Interventoría al Prestador del Servicio del Programa Buen Comienzo y de la verificación in situ de todos los Componentes con respecto a la prestación del servicio de Atención Integral a la Primera Infancia, se obtuvo como resultado una valoración cuantitativa que ilustra los porcentajes y presenta el cumplimiento referente a la meta establecida. Para ello se realizaron dos valoraciones, de la siguiente manera:";
                                                                                  $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                                                  $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                                                  $section->addText($texto1_total, 'l1', 'l2');

                                                                                  $texto_calidad_total = "Porcentaje Calidad: correspondiente a la valoración porcentual (%) obtenida en la visita en situ, donde se verifica el cumplimiento de los requisitos básicos exigidos en la resolución y documentos técnicos, para la prestación del servicio de Atención Integral a la Primera Infancia. ";
                                                                                  $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                                                  $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                                                  $section->addText($texto_calidad_total, 'l1', 'l2');

                                                                                  $texto_cumplimiento_total = "Porcentaje Cumplimiento: correspondiente a la valoración porcentual (%), obtenida del seguimiento, vigilancia y control por parte de la Interventoría después de actualizar el cumplimiento de los hallazgos encontrados en la visita en situ.";
                                                                                  $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                                                  $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                                                  $section->addText($texto_cumplimiento_total, 'l1', 'l2');

                                                                                  $timestamp = new DateTime();
                                                                                  $tabla_temp = "m".$timestamp->getTimestamp();

                                                                                  $create_temp = mysql_query(("CREATE TEMPORARY TABLE $tabla_temp (id_contrato bigint, nombre_prestador varchar(100), nombre_modalidad varchar(100), id_tema int, id_componente int, nombre_componente varchar(100), promedio_componente_inicial float, promedio_componente_final float, porc_componente_x_modalidad float)  CHARACTER SET utf8 COLLATE utf8_bin"),$conexion);
                                                                                  $porcentajes = mysql_query(("
                                                                                  INSERT INTO  $tabla_temp (id_contrato, nombre_prestador, nombre_modalidad, id_tema, id_componente, nombre_componente, promedio_componente_inicial, promedio_componente_final, porc_componente_x_modalidad)
                                                                                  SELECT
                                                                                  operacion1.id_contrato,
                                                                                  operacion1.nombre_prestador,
                                                                                  operacion1.nombre_modalidad,
                                                                                  operacion1.id_tema,
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
                                                                                      evaluacion.id_componente = componente.id_componente
                                                                                      and id_contrato='$id_contrato' and
                                                                                      evaluacion.estado='1'
                                                                                      group by id_subtema, id_acta
                                                                                      ) as operacion
                                                                                      group by id_tema
                                                                                      )as operacion1, componente_x_modalidad
                                                                                      where componente_x_modalidad.id_modalidad = operacion1.id_modalidad
                                                                                      and componente_x_modalidad.id_tema = operacion1.id_tema
                                                                                      group by operacion1.id_tema"), $conexion);

                                                                                      $porcentaje_cobertura = mysql_query(("
                                                                                      INSERT INTO  $tabla_temp (id_contrato, nombre_prestador, nombre_modalidad, id_tema, id_componente, nombre_componente, promedio_componente_inicial, promedio_componente_final, porc_componente_x_modalidad)
                                                                                      SELECT
                                                                                      operacion.id_contrato,
                                                                                      operacion.nombre_prestador,
                                                                                      operacion.nombre_modalidad,
                                                                                      operacion.id_tema,
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
                                                                                        tema.id_tema,
                                                                                        componente.id_componente,
                                                                                        componente.nombre_componente,
                                                                                        componente_x_modalidad.porc_componente_x_modalidad
                                                                                        FROM
                                                                                        acta, prestador, modalidad, componente, componente_x_modalidad, tema
                                                                                        WHERE
                                                                                        acta.id_prestador = prestador.id_prestador and
                                                                                        acta.id_modalidad = modalidad.id_modalidad and
                                                                                        acta.id_componente = componente.id_componente and
                                                                                        acta.id_componente = componente_x_modalidad.id_componente and
                                                                                        acta.id_modalidad = componente_x_modalidad.id_modalidad and
                                                                                        acta.id_contrato='$id_contrato' and
                                                                                        componente.id_componente='3' and
                                                                                        acta.estado='0' and
                                                                                        componente.id_componente = tema.id_componente
                                                                                        group by acta.id_acta
                                                                                        ) as operacion"), $conexion);

                                                                                        $porcentajes_nutricion = mysql_query(("
                                                                                        SELECT id_contrato, nombre_componente, id_componente, promedio_componente_inicial, promedio_componente_final
                                                                                        FROM $tabla_temp
                                                                                        WHERE id_componente = 7 and id_contrato='$id_contrato'"),$conexion);

                                                                                        while ($row = mysql_fetch_array($porcentajes_nutricion)) {
                                                                                          $contrato = $row["id_contrato"];
                                                                                          $porc_inicial2 = $row["promedio_componente_inicial"];
                                                                                          $porc_final2 = $row["promedio_componente_final"];
                                                                                        }

                                                                                        $descuentos_calculo_calidad1 = mysql_query(("
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

                                                                                        $descuentos_calculo_cumplimiento1 = mysql_query(("
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

                                                                                        //desceuentos de calidad
                                                                                        $descuento2 = 0;
                                                                                        $descuento_no_patogeno2 = 0;
                                                                                        while ($row1=mysql_fetch_assoc($descuentos_calculo_calidad1)) {
                                                                                          if ($row1['tipo_descuento'] == 3) {
                                                                                            $descuento_no_patogeno2 = null;
                                                                                            $descuento_no_patogeno2 = $row1["descuento"];
                                                                                          }else {
                                                                                            $descuento2 = $descuento2 + $row1["descuento"];
                                                                                          }
                                                                                        }
                                                                                        $descuento2 = $descuento2 + $descuento_no_patogeno2;
                                                                                        $descuento2 = round($descuento2,4)/100;
                                                                                        $porc_inicial2 = $porc_inicial2 - $descuento2;

                                                                                        //descuentos cumplimiento
                                                                                        $descuento12 = 0;
                                                                                        $descuento_no_patogeno12 = 0;
                                                                                        while ($row1=mysql_fetch_assoc($descuentos_calculo_cumplimiento1)) {
                                                                                          if ($row1['estado'] != 0) {
                                                                                            if ($row1['tipo_descuento'] == 3) {
                                                                                              $descuento_no_patogeno12 = null;
                                                                                              $descuento_no_patogeno12 = $row1["descuento"];
                                                                                            }else {
                                                                                              $descuento12 = $descuento12 + $row1["descuento"];
                                                                                            }
                                                                                          }
                                                                                        }
                                                                                        $descuento12 = $descuento12 + $descuento_no_patogeno12;
                                                                                        $descuento12 = round($descuento12,4)/100;
                                                                                        $porc_final2 = $porc_final2 + $descuento12;
                                                                                        if ($porc_final2 > 1) {
                                                                                          $porc_final2 = 1;
                                                                                        }

                                                                                        $update_temp = mysql_query(("
                                                                                        UPDATE $tabla_temp SET
                                                                                        promedio_componente_inicial = '$porc_inicial2',
                                                                                        promedio_componente_final = '$porc_final2'
                                                                                        WHERE id_componente = 7 and id_contrato='$id_contrato'"), $conexion);

                                                                                        $consulta_datos_temp = mysql_query(("
                                                                                        SELECT
                                                                                        ROUND(SUM(promedio.promedio_componente_inicial) / sum(promedio.porc_componente_x_modalidad),4) AS promedio_componente_inicial,
                                                                                        ROUND(SUM(promedio.promedio_componente_final) / sum(promedio.porc_componente_x_modalidad),4) AS promedio_componente_final
                                                                                        FROM(
                                                                                        SELECT
                                                                                        operacion.promedio_inicial * operacion.porc_componente_x_modalidad as promedio_componente_inicial,
                                                                                        operacion.promedio_final * operacion.porc_componente_x_modalidad as promedio_componente_final,
                                                                                        operacion.id_contrato,
                                                                                        operacion.nombre_prestador,
                                                                                        operacion.nombre_modalidad,
                                                                                        operacion.id_tema,
                                                                                        porc_componente_x_modalidad
                                                                                        FROM
                                                                                        (SELECT
                                                                                        id_contrato,
                                                                                        nombre_prestador,
                                                                                        nombre_modalidad,
                                                                                        id_componente,
                                                                                        id_tema,
                                                                                        nombre_componente,
                                                                                        promedio_componente_inicial as promedio_inicial,
                                                                                        promedio_componente_final as promedio_final,
                                                                                        porc_componente_x_modalidad
                                                                                        FROM $tabla_temp
                                                                                        ) as operacion) AS promedio
                                                                                        "), $conexion);

                                                                                        $registros_total = mysql_fetch_assoc($consulta_datos_temp);
                                                                                        $calculo_total_calidad = $registros_total["promedio_componente_inicial"]*100;
                                                                                        $calculo_total_cumplimiento = $registros_total["promedio_componente_final"]*100;


                                                                                        $phpWord->addTableStyle('table_cal_cum_total', $styleTable);
                                                                                        $table_total = $section->addTable('table_cal_cum_total');
                                                                                        $table_total->addRow(900);
                                                                                        $table_total->addCell(2666, $styleCellTitle)->addText($nombre_prestador, $fontStyleTitle);
                                                                                        $table_total->addCell(2666, $styleCellTitle)->addText('% CALIDAD', $fontStyleTitle);
                                                                                        $table_total->addCell(2666, $styleCellTitle)->addText('% CUMPLIMIENTO', $fontStyleTitle);
                                                                                        $table_total->addRow(900);
                                                                                        $table_total->addCell(2666)->addText("TOTAL", $fontStyleText);
                                                                                        $table_total->addCell(2666)->addText($calculo_total_calidad, $fontStyleText);
                                                                                        $table_total->addCell(2666)->addText($calculo_total_cumplimiento, $fontStyleText);

                                                                                        $section->addText();

                                                                                        $texto3_total = "Las valoraciones indicadas corresponden al nivel de desempeño en la calidad y cumplimiento en la prestación del servicio para la atención integral a la primera infancia, durante la ejecución del contrato.";
                                                                                        $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                                                        $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                                                        $section->addText($texto3_total, 'l1', 'l2');

                                                                                        $texto4_total = "Finalmente, la interventoría indica que lo evidenciado al momento de las verificaciones tanto a puntos de atención como a sede administrativa, podrá encontrarse en las actas de visita (AVI), evidencias (EAC) y en los informes mensuales que se efectuaron a la entidad, en los cuales se reflejaron los respectivos cumplimientos, o en su defecto, la subsanación de los hallazgos para aquellos puntos de atención en los cuales fueron evidenciados, para lo cual la entidad atendió de manera oportuna la solicitud de la interventoría de enviar las respectivas acciones correctivas en los tiempos establecidos al momento de las visitas en sitio, sin que hubiese sido necesario solicitar información adicional, proyectar solicitudes de aclaración (OSA) o requerimientos (ORQ).";
                                                                                        $phpWord->addFontStyle('l1', array('bold'=>false, 'name' => 'Arial', 'size'=>10));
                                                                                        $phpWord->addParagraphStyle('l2', array('align'=>'both'));
                                                                                        $section->addText($texto4_total, 'l1', 'l2');

                                                                                        $titulo_obligaciones = "1.9.	OBLIGACIONES CONTRACTUALES";
                                                                                        $phpWord->addFontStyle('f1', array('bold'=>true, 'name' => 'Arial', 'size'=>10));
                                                                                        $phpWord->addParagraphStyle('p1', array('align'=>'both'));
                                                                                        $section->addText($titulo_obligaciones, 'f1', 'p1');

                                                                                        $section->addText();


                                                                                        $phpWord->addTableStyle('table_obligaciones', $styleTable);
                                                                                        $table_obligaciones = $section->addTable('table_obligaciones');
                                                                                        $table_obligaciones->addRow(900);
                                                                                        $table_obligaciones->addCell(500, $styleCellTitle)->addText('#', $fontStyleTitle);
                                                                                        $table_obligaciones->addCell(5000, $styleCellTitle)->addText('OBLIGACIONES DEL OPERADOR SEGÚN CONTRATO', $fontStyleTitle);
                                                                                        $table_obligaciones->addCell(300, $styleCellTitle)->addText('ESTADO', $fontStyleTitle);
                                                                                        $table_obligaciones->addCell(2900, $styleCellTitle)->addText('OBSERVACIONES', $fontStyleTitle);

                                                                                        $i=0;

                                                                                        $info_sede = mysql_query(("
                                                                                        SELECT contrato_x_sede.id_contrato, sede.nombre_sede,sede.barrio_sede, sede.direccion_sede
                                                                                        FROM contrato_x_sede, sede
                                                                                        WHERE contrato_x_sede.id_sede = sede.id_sede
                                                                                        and contrato_x_sede.id_contrato = '$id_contrato'
                                                                                        and sede.barrio_sede != ''"),$conexion);

                                                                                        $sede = mysql_fetch_assoc($info_sede);

                                                                                        while ($row = mysql_fetch_assoc($obligaciones)) {
                                                                                          $i++;
                                                                                          $table_obligaciones->addRow(900);
                                                                                          $table_obligaciones->addCell(500)->addText($i, $fontStyleText);
                                                                                          if ($i == 1) {
                                                                                            $row["obligacion"] = str_replace("@nombre_sede", $sede["nombre_sede"], $row["obligacion"]);
                                                                                            $row["obligacion"] = str_replace("@direccion_sede",$sede["direccion_sede"],$row["obligacion"]);
                                                                                            $row["obligacion"] = str_replace("@barrio_sede",$sede["barrio_sede"],$row["obligacion"]);
                                                                                          }
                                                                                          if ($i == 2) {
                                                                                            $row["obligacion"] = str_replace("@nombre_sede", $sede["nombre_sede"], $row["obligacion"]);
                                                                                          }
                                                                                          $table_obligaciones->addCell(5000)->addText($row["obligacion"], $fontStyleText);
                                                                                          $table_obligaciones->addCell(2900)->addText("", $fontStyleText);
                                                                                          $table_obligaciones->addCell(2900)->addText($row["observacion"], $fontStyleText);
                                                                                        }


                                                                                        $section->addText();
                                                                                      }else {
                                                                                        $phpWord->addTableStyle('table_obligaciones', $styleTable);
                                                                                        $table_obligaciones = $section->addTable('table_obligaciones');
                                                                                        $table_obligaciones->addRow(900);
                                                                                        $table_obligaciones->addCell(500, $styleCellTitle)->addText('#', $fontStyleTitle);
                                                                                        $table_obligaciones->addCell(5000, $styleCellTitle)->addText('OBLIGACIONES DEL OPERADOR SEGÚN CONTRATO', $fontStyleTitle);
                                                                                        $table_obligaciones->addCell(300, $styleCellTitle)->addText('ESTADO', $fontStyleTitle);
                                                                                        $table_obligaciones->addCell(2900, $styleCellTitle)->addText('OBSERVACIONES', $fontStyleTitle);

                                                                                        $i=0;

                                                                                        $info_sede = mysql_query(("
                                                                                        SELECT contrato_x_sede.id_contrato, sede.nombre_sede,sede.barrio_sede, sede.direccion_sede
                                                                                        FROM contrato_x_sede, sede
                                                                                        WHERE contrato_x_sede.id_sede = sede.id_sede
                                                                                        and contrato_x_sede.id_contrato = '$id_contrato'
                                                                                        and sede.barrio_sede != ''"),$conexion);

                                                                                        $sede = mysql_fetch_assoc($info_sede);

                                                                                        while ($row = mysql_fetch_assoc($obligaciones)) {
                                                                                          $i++;
                                                                                          $table_obligaciones->addRow(900);
                                                                                          $table_obligaciones->addCell(500)->addText($i, $fontStyleText);
                                                                                          if ($i == 1) {
                                                                                            $row["obligacion"] = str_replace("@nombre_sede", $sede["nombre_sede"], $row["obligacion"]);
                                                                                            $row["obligacion"] = str_replace("@direccion_sede",$sede["direccion_sede"],$row["obligacion"]);
                                                                                            $row["obligacion"] = str_replace("@barrio_sede",$sede["barrio_sede"],$row["obligacion"]);
                                                                                          }
                                                                                          if ($i == 2) {
                                                                                            $row["obligacion"] = str_replace("@nombre_sede", $sede["nombre_sede"], $row["obligacion"]);
                                                                                          }
                                                                                          $table_obligaciones->addCell(5000)->addText($row["obligacion"], $fontStyleText);
                                                                                          $table_obligaciones->addCell(2900)->addText("", $fontStyleText);
                                                                                          $table_obligaciones->addCell(2900)->addText($row["observacion"], $fontStyleText);
                                                                                        }


                                                                                        $section->addText();
                                                                                      }



                                                                                      ob_clean();
                                                                                      header('Content-Description: File Transfer');
                                                                                      header('Content-type: application/force-download');
                                                                                      header("Content-Disposition: attachment;filename='IF-".$id_contrato.".docx'");

                                                                                      $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                                                                                      $objWriter->save('php://output');
                                                                                      //return $response;

                                                                                      ?>
