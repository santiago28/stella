<?php

session_start();
if ($_SESSION['login'])
{

  include "conexion.php";

  //Variables Globales declaradas
  $id_grupo=$_SESSION["grupo"];
  $nombre=$_SESSION["nombre_usuario"];
  $user=$_SESSION["login"];
  $fotoperfil = $_SESSION["fotoperfil"];
  //Variables recibidas del _GET
  $msg=$_GET['msg'];
  $id_contrato=$_GET['id_contrato'];
  $id_tema=$_GET['id_tema'];
  $id_mes=$_GET['id_mes'];
  $id_ano=(int)date('Y');
  $porc_descuento=$_GET['porc_descuento'];
  if ($id_mes==4) {
    $acumulado = true;
  } else {
    $acumulado = false;
  }


  if ($acumulado) {
    $id_mes_guarda = $id_mes;
    $id_mes = "%";
    $tmp    = date('Y')."-01-01";
    $tmp2   = mktime( 0, 0, 0, 1, 1, $id_ano );
  } else {
    $id_mes_guarda = $id_mes;
    $tmp=$id_ano."-".$id_mes."-01";
    $tmp2 = mktime( 0, 0, 0, $id_mes, 1, $id_ano );
  }



  if ($acumulado) {
    $nombre_mes="April";
    $fecha_corte = '2020-03-30';
    //date("Y-m-d");
  } else {
    $nombre_mes=strftime("%B",strtotime($tmp));

    switch ($nombre_mes) {
      case 'January':
      $nombre_mes = 'Enero';
      break;
      case 'February':
      $nombre_mes = 'Febrero';
      break;
      case 'March':
      $nombre_mes = 'Marzo';
      break;
      case 'April':
      $nombre_mes = 'Abril';
      break;
      case 'May':
      $nombre_mes = 'Mayo';
      break;
      case 'June':
      $nombre_mes = 'Junio';
      break;
      case 'July':
      $nombre_mes = 'Julio';
      break;
      case 'August':
      $nombre_mes = 'Agosto';
      break;
      case 'September':
      $nombre_mes = 'Septiembre';
      break;
      case 'October':
      $nombre_mes = 'Octubre';
      break;
      case 'November':
      $nombre_mes = 'Noviembre';
      break;
      case 'December':
      $nombre_mes = 'Diciembre';
      break;
    }

    switch ($id_mes) {
      case 1:
      case 2:
      case 3:
      case 4:
      case 5:
      case 6:
      case 7:
      case 8:
      case 9:
      case 10:
      case 11:
      case 12:
      $id_mes2 = '0'.$id_mes;
      break;
    }
    $fecha_corte=$id_ano."-".$id_mes2."-".date("t",$tmp2);//date("Y-m-d"); //
    $fecha_corte02=date('Y')."$id_mes2"."-01";
  }

  //$fecha_corte="2015-11-24"; //Para fechas de corte especificas definidas desde las coordinaciones

  //Informacion del contrato
  $info_contrato= mysql_query(("
  SELECT
  contrato_x_sede.id_contrato,
  contrato_x_sede.id_modalidad,
  modalidad.nombre_modalidad,
  contrato_x_sede.id_prestador,
  prestador.nombre_prestador

  FROM
  contrato_x_sede,modalidad,prestador
  where
  contrato_x_sede.id_modalidad=modalidad.id_modalidad and
  contrato_x_sede.id_prestador=prestador.id_prestador and
  contrato_x_sede.id_contrato='$id_contrato' /*and
  contrato_x_sede.estado='1'*/
  "),$conexion);

  $row=mysql_fetch_assoc($info_contrato);
  $id_modalidad=$row['id_modalidad'];
  $nombre_modalidad=$row['nombre_modalidad'];
  $id_prestador=$row['id_prestador'];
  $nombre_prestador=$row['nombre_prestador'];

  //Informacion del contrato
  $info_tema= mysql_query(("
  SELECT
  tema.id_tema,
  tema.nombre_tema,
  tema.id_componente,
  componente.nombre_componente
  FROM
  tema,componente
  WHERE
  tema.id_componente=componente.id_componente and
  tema.id_tema='$id_tema'
  "),$conexion);

  $row=mysql_fetch_assoc($info_tema);
  $nombre_tema=$row['nombre_tema'];
  $id_componente=$row['id_componente'];
  $nombre_componente=$row['nombre_componente'];



  //Consulta datos del semaforo
  // Nota: la funcion de num_rows ya es obsoleta
  $sql1 = "
  SELECT
  *
  FROM
  semaforo
  WHERE
  id_contrato='$id_contrato' and
  id_tema='$id_tema'
  and id_mes like '$id_mes' ORDER BY id_mes DESC
  ";
  $datos_semaforo= mysql_query($sql1,$conexion);//
  if(mysql_num_rows($datos_semaforo) > 0){
    $row=mysql_fetch_assoc($datos_semaforo);
    $id_semaforo=$row['id_semaforo'];
    $incumplimiento_grave=$row['incumplimiento_grave'];
    $nt1=$row['nt1'];
    $nt2=$row['nt2'];
    $nt3=$row['nt3'];
    $gi1=$row['gi1'];
    $gi2=$row['gi2'];
    $gi3=$row['gi3'];
    $gi4=$row['gi4'];
    $gi5=$row['gi5'];
    $gi6=$row['gi6'];
    $gi7=$row['gi7'];
    $descripcion_incumplimiento_grave=$row['descripcion_incumplimiento_grave'];
    $logros_prestador=$row['logros_prestador'];
    $dificultades_prestador=$row['dificultades_prestador'];
    $debido_proceso=$row['debido_proceso'];
    $asistencia_tecnica=$row['asistencia_tecnica'];
    $otras_observaciones=$row['otras_observaciones'];
    $estado=$row['estado'];
    $id_interventor=$row['id_interventor'];

  }
  else{
    $id_semaforo="0";
    $incumplimiento_grave="0";
    $nt1="1";
    $nt2="1";
    $nt3="1";
    $gi1="1";
    $gi2="1";
    $gi3="1";
    $gi4="1";
    $gi5="1";
    $gi6="1";
    $gi7="1";
    $descripcion_incumplimiento_grave=$count_result;
    $logros_prestador="";
    $dificultades_prestador="";
    $debido_proceso="";
    $asistencia_tecnica="";
    $otras_observaciones="";
    $estado="1";
    $id_interventor=$user;

  }




  //Se consulta el primer y últomo día del mes
  $month = $id_mes;
  $year = date('Y');
  $day = date("d", mktime(0,0,0, $month+1, 0, $year));
  $fechaInicial = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
  $fechaFinal = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
  //SECCION PORCENTAJES y VALORES DEBIDO PROCESO

  //Histórico Porcentaje Inicial
  //el porcentaje se modifica en la linea 230 para que no sea acumulado sino solo mensual
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
    id_contrato='$id_contrato' and
    id_tema='$id_tema' and
    fecha_evaluacion <='$fechaFinal' and fecha_evaluacion >='$fechaInicial' and
    estado='1'
    group by  id_subtema
    ) as operacion
    "),$conexion);
    $row=mysql_fetch_assoc($query_inicial);
    $porc_inicial=round($row['promedio_componente'],4);

    //Histórico Porcentaje Final
    $query_final= mysql_query(("
    SELECT
    avg(porcentaje_final) promedio_componente
    FROM
    (
      SELECT
      sum(porc_final)/sum(porc_referencia) porcentaje_final
      FROM
      evaluacion
      WHERE
      id_contrato='$id_contrato' and
      id_tema='$id_tema' and
      fecha_evaluacion <='$fechaFinal' and fecha_evaluacion >='$fechaInicial' and
      estado='1'
      group by  id_subtema
      ) as operacion
      "),$conexion);
      $row=mysql_fetch_assoc($query_final);
      $porc_final=round($row['promedio_componente'],4);


      //Hallazgos, AC, SA, REQ
      $deb_proc= mysql_query(("
      SELECT
      count(tmp.historico) hallazgos_levantados,
      sum(if(tmp.etapa='OK',1,0)) hallazgos_subsanados,
      sum(if(tmp.etapa!='OK',1,0)) hallazgos_pendientes,
      sum(if(tmp.historico='AC',1,0)) acciones_correctivas,
      sum(if(tmp.historico='SA',1,0)) solicitudes_aclaracion,
      sum(if(tmp.historico='REQ',1,0)) requerimientos

      FROM
      (
        SELECT
        subsanacion.id_acta,
        acta.fecha_evaluacion,
        subsanacion.id_pregunta,
        subsanacion.id_componente,
        pregunta.id_tema,
        subsanacion.id_contrato,
        subsanacion.historico,
        subsanacion.etapa

        FROM
        subsanacion,acta,pregunta

        WHERE
        subsanacion.id_acta=acta.id_acta and
        subsanacion.id_pregunta=pregunta.id_pregunta and
        subsanacion.id_contrato='$id_contrato' and
        acta.fecha_evaluacion <='$fechaFinal' and acta.fecha_evaluacion >='$fechaInicial' and
        pregunta.id_tema='$id_tema'
        ) as tmp

        "),$conexion);
        $row=mysql_fetch_assoc($deb_proc);
        $hallazgos_levantados=$row['hallazgos_levantados'];
        $hallazgos_subsanados=$row['hallazgos_subsanados'];
        $hallazgos_pendientes=$row['hallazgos_pendientes'];
        $acciones_correctivas=$row['acciones_correctivas'];
        $solicitudes_aclaracion=$row['solicitudes_aclaracion'];
        $requerimientos=$row['requerimientos'];

        //Preguntas Evaluadas
        $preguntas= mysql_query(("
        SELECT
        count(id_pregunta) cantidad_preguntas

        FROM
        evaluacion

        WHERE
        id_contrato='$id_contrato' and
        fecha_evaluacion <='$fechaFinal' and fecha_evaluacion >='$fechaInicial' and
        id_tema='$id_tema'

        "),$conexion);
        $row=mysql_fetch_assoc($preguntas);
        $cantidad_preguntas=$row['cantidad_preguntas'];


        //CALCULO PORCENTAJES
        $tmp_sa=@($solicitudes_aclaracion/$cantidad_preguntas)*2;
        $tmp_req=@($requerimientos/$cantidad_preguntas)*4;

        if($porc_final-$tmp_sa-$tmp_req<=0){
          $porc_deb_proc=0;
        }else{
          $porc_deb_proc=round($porc_final-$tmp_sa-$tmp_req,4);
        }



        //PHS PARA NUTRICION
        if($id_componente==7){
          if($id_modalidad==6){
            $query_phs= mysql_query(("
            SELECT
            round(avg(operation.porcentaje_estandar_inicial),4) phs_inicial

            FROM
            (
              SELECT
              id_acta,
              id_contrato,
              fecha_evaluacion,
              id_subtema,
              round(sum(porc_inicial)/sum(porc_referencia),4) porcentaje_estandar_inicial,
              round(sum(porc_final)/sum(porc_referencia),4) porcentaje_estandar_final

              FROM
              evaluacion

              WHERE
              id_subtema in (19,20,21,22) and
              id_contrato='$id_contrato' and
              fecha_evaluacion <='$fechaFinal' and fecha_evaluacion >='$fechaInicial'

              GROUP BY
              id_subtema
              ) as operation

              GROUP BY
              id_contrato
              "),$conexion);

              $row=mysql_fetch_assoc($query_phs);
              $phs_inicial=$row['phs_inicial']*100;
            }
            else{
              $query_phs= mysql_query(("
              SELECT
              round(avg(operation.porcentaje_estandar_inicial),4) phs_inicial

              FROM
              (
                SELECT
                id_acta,
                id_contrato,
                fecha_evaluacion,
                id_subtema,
                round(sum(porc_inicial)/sum(porc_referencia),4) porcentaje_estandar_inicial,
                round(sum(porc_final)/sum(porc_referencia),4) porcentaje_estandar_final

                FROM
                evaluacion

                WHERE
                id_subtema in (9,10,11,12,13,14) and
                id_contrato='$id_contrato' and
                fecha_evaluacion <='$fechaFinal' and fecha_evaluacion >='$fechaInicial'

                GROUP BY
                id_subtema
                ) as operation

                GROUP BY
                id_contrato
                "),$conexion);

                $row=mysql_fetch_assoc($query_phs);
                $phs_inicial=$row['phs_inicial']*100;
              }
            }

            ?>


            <!DOCTYPE html>
            <html>
            <head>
              <meta charset="utf-8">
              <meta http-equiv="X-UA-Compatible" content="IE=edge">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <meta name="description" content="">
              <meta name="author" content="">
              <link rel="icon" href="../../favicon.ico">
              <title>Diligenciar Semáforo</title>
              <!-- Bootstrap core CSS -->
              <link href="css/bootstrap.min.css" rel="stylesheet">
              <link href="css/datepicker.css" rel="stylesheet">
              <!-- Para validacion de campos -->
              <script src="js/jquery-1.11.1.min.js"></script>
              <script src="js/bootstrap.js"></script>
              <script src="js/parsley.js"></script>
              <script src="js/bootstrap-datepicker.js"></script>
              <!-- Estilos menú principal -->
              <link rel="stylesheet" href="css/estilos.css">

              <!-- Material Icons -->
              <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
              <!-- Custom styles for this template -->
              <link href="css/jumbotron-narrow.css" rel="stylesheet">
              <script language="javascript" type="text/javascript">
              var sumac=0.0; sumat=0.0;
              </script>
              <style>
              .parsley-errors-list {
                color:#f00;
              }
              </style>
            </head>

            <body onLoad="despliega('gi1', '0')">
              <div class="barra-menu">
                <div class="col-md-12">
                  <!-- <div class="form-group col-md-6">
                  <div style="margin-left: 50%">
                  <img src="images/logo_medellin.png" width="140" height="60">
                </div>
              </div> -->
              <div style="margin-top: 1%; float: right;">
                <a class="glyphicon glyphicon-home" href="homeadmin.php" style="font-size:35px; color:#ffffff; text-decoration:none;" role="button"></a>

              </div>
            </div>
          </div>
          <?php include("menu.php"); ?>
          <div class="container">
            <?php
            if($msg!=0){
              if($msg==1){


                ?>

                <div id="mensaje" class="alert alert-success" role="alert">
                  <h5 align="center"><strong>¡Felicitaciones!</strong>
                    El registro ha sido Insertado/Actualizado exitosamente.<button type="button" class="close" aria-hidden="true">x</button></h5>
                  </div>
                  <?php
                }
                else{
                  ?>

                  <div id="mensaje" class="alert alert-danger" role="alert">
                    <h5 align="center"><strong>¡Advertencia!</strong>
                      ¡Se ha generado un problema en la operación. Consulte con el administrador del sistema.<button type="button" class="close" aria-hidden="true">x</button></h5>
                    </div>
                    <?php
                  } // End else msg=2
                } //End msg=0
                ?>







                <div class="bs-docs-section">
                  <h2 id="tables-example">Semáforo Mensual de la Prestación del Servicio</h2>
                </div>

                <div class="footer"></div>



                <div class="alert alert-info" role="alert">
                  <strong>INFORMACIÓN GENERAL</strong>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                      <tbody>
                        <tr>
                          <td colspan="2">Prestador: <strong><?php echo $nombre_prestador; ?></td>
                          </tr>
                          <tr>
                            <td class="col-md-4">Contrato: <strong><?php echo $id_contrato; ?></td>
                              <td class="col-md-4">Modalidad: <strong><?php echo $nombre_modalidad; ?></td>
                              </tr>
                              <tr>
                                <td class="col-md-4">Mes: <strong><?php echo strtoupper($nombre_mes); ?></td>
                                  <td class="col-md-4">Fecha de Corte: <strong><?php echo $fecha_corte; ?></td>
                                  </tr>
                                  <tr>
                                    <td class="col-md-4">Componente: <strong><?php echo $nombre_componente; ?></td>
                                      <td class="col-md-4">Componente Técnico: <strong><?php echo $nombre_tema; ?></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>

                              </div>

                              <form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">
                                <input type="hidden" name="caso" id="caso" value="15">
                                <input type="hidden" name="id_interventor" value="<?php echo $id_interventor; ?>">
                                <input type="hidden" name="id_semaforo" value="<?php echo $id_semaforo; ?>">
                                <input type="hidden" name="id_prestador" value="<?php echo $id_prestador; ?>">
                                <input type="hidden" name="id_contrato" value="<?php echo $id_contrato; ?>">
                                <input type="hidden" name="id_modalidad" value="<?php echo $id_modalidad; ?>">
                                <input type="hidden" name="id_componente" value="<?php echo $id_componente; ?>">
                                <input type="hidden" name="id_tema" value="<?php echo $id_tema; ?>">
                                <input type="hidden" name="id_mes" value="<?php echo $id_mes_guarda; ?>">
                                <input type="hidden" name="porc_calidad" value="<?php echo $porc_inicial; ?>">
                                <input type="hidden" name="porc_deb_proc" value="<?php echo $porc_deb_proc; ?>">
                                <input type="hidden" name="$usuario" value="<?php echo $user; ?>">

                                <div class="alert alert-info" role="alert">
                                  <strong>VALORACIÓN DE LA CALIDAD DE LA PRESTACIÓN DEL SERVICIO [ <?php
                                  if($id_componente==7){
                                    echo round(($porc_inicial*0.8+$porc_deb_proc*0.2+$porc_descuento)*100,4);}
                                    else{ echo $porc_inicial*100;
                                    }  ?>%]</strong>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <table class="table table-bordered table-hover">
                                        <tbody>
                                          <tr>
                                            <td class="col-md-10">Porcentaje Inicial a la fecha de Corte:</td>
                                            <td class="col-md-2"><span id="pini"><?php echo $porc_inicial*100;	 ?></span>%</td>
                                          </tr>
                                          <tr>
                                            <td class="col-md-10">Porcentaje Final a la fecha de Corte:</td>
                                            <td class="col-md-2"><span id="pfin"><?php echo $porc_final*100;	 ?></span>%</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                  <div class="alert alert-info" role="alert">
                                    <strong>VALORACIÓN PORCENTUAL DEL DEBIDO PROCESO [ <?php echo $porc_deb_proc*100; ?>%]</strong>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <table class="table table-bordered table-hover">
                                        <tbody>
                                          <tr>
                                            <td class="col-md-10">Cantidad de PREGUNTAS EVALUADAS a la fecha de corte: </td>
                                            <td class="col-md-2"><?php echo $cantidad_preguntas;	 ?></td>
                                          </tr>
                                          <tr>
                                            <td class="col-md-10">Cantidad de HALLAZGOS LEVANTADOS a la fecha de corte: </td>
                                            <td class="col-md-2"><?php echo $hallazgos_levantados;	 ?></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"><strong>CLASIFICACIÓN POR ESTADO</strong></td>
                                          </tr>
                                          <tr>
                                            <td class="col-md-10">Cantidad de HALLAZGOS SUBSANADOS a la fecha de corte: </td>
                                            <td class="col-md-2"><?php echo $hallazgos_subsanados;	 ?></td>
                                          </tr>
                                          <tr>
                                            <td class="col-md-10">Cantidad de HALLAZGOS PENDIENTES DE SUBSANAR a la fecha de corte: </td>
                                            <td class="col-md-2"><?php echo $hallazgos_pendientes;	 ?></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"><strong>CLASIFICACIÓN POR ETAPA</strong></td>
                                          </tr>
                                          <tr>
                                            <td class="col-md-10">Cantidad de ACCIONES CORRECTIVAS a la fecha de corte: </td>
                                            <td class="col-md-2"><?php echo $acciones_correctivas;	 ?></td>
                                          </tr>
                                          <tr>
                                            <td class="col-md-10">Cantidad de SOLICITUDES DE ACLARACIÓN a la fecha de corte: </td>
                                            <td class="col-md-2"><?php echo $solicitudes_aclaracion;	 ?></td>
                                          </tr>
                                          <tr>
                                            <td class="col-md-10">Cantidad de REQUERIMIENTOS a la fecha de corte: </td>
                                            <td class="col-md-2"><?php echo $requerimientos;	 ?></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>

                                  <?php if($id_componente==7){ ?>
                                    <div class="alert alert-info" role="alert">
                                      <strong>VARIABLES ESPECIALES DE LOS COMPONENTES</strong>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <table class="table table-bordered table-hover">
                                          <tbody>
                                            <tr>
                                              <td class="col-md-10">0. Perfil Higiénico Sanitario promedio (PHS) para el mes en curso (Recuerde digitar este valor en el campo de "Otras Observaciones")</td>
                                              <td class="col-md-2">
                                                <center><h3><?php if($phs_inicial==""){echo "No visitado este mes";}else {echo $phs_inicial."%";} ?></h3></center>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="col-md-10">1. ¿Durante el mes, el prestador presenta Rastreos Microbiológicos positivos para: levaduras, hongos, bacillus cereus, mohos, mesófilos o coliformes totales? (-10%)</td>
                                              <td class="col-md-2">
                                                <select data-parsley-min="0" class="form-control" id="select1" name="nt1">
                                                  <option value="<?php echo $nt1; ?>" selected	><?php if($nt1==1){echo "NO";} elseif($nt1==0){echo "SI";} else{echo "N/A";} ?></option>
                                                  <option value="1" 			>NO</option>
                                                  <option value="0" 			>SI</option>
                                                </select>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="col-md-10">2. ¿Tiene PQRS relacionadas con el componente de Nutrición y Alimentación, que hayan sido comprobadas como ciertas durante el mes? (-10%)</td>
                                              <td class="col-md-2">
                                                <select data-parsley-min="0" class="form-control" id="select1" name="nt2">
                                                  <option value="<?php echo $nt2; ?>" selected	><?php if($nt2==1){echo "NO";}  elseif($nt2==0){echo "SI";} else{echo "N/A";} ?></option>
                                                  <option value="1" 			>NO</option>
                                                  <option value="0" 			>SI</option>
                                                </select>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="col-md-10">3. ¿Durante el mes, el prestador tuvo Solicitudes de Aclaración o Requerimientos por informe de peso/talla, informe nutricional o consumo? (-5%)</td>
                                              <td class="col-md-2">
                                                <select data-parsley-min="0" class="form-control" id="select1" name="nt3">
                                                  <option value="<?php echo $nt3; ?>" selected	><?php if($nt3==1){echo "NO";} elseif($nt3==0){echo "SI";} else{echo "N/A";} ?></option>
                                                  <option value="1" 			>NO</option>
                                                  <option value="0" 			>SI</option>
                                                </select>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>

                                  <?php } // End IF si el componente es Nutricion ?>


                                  <?php if($id_componente==4){  // Empieza el IF del componente Gestión Institucional Actualizado por JSLL el 07/06/2016 ?>
                                    <div class="alert alert-info" role="alert">
                                      <strong>VARIABLES ESPECIALES DE LOS COMPONENTES</strong>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <table class="table table-bordered table-hover">
                                          <tbody>
                                            <tr>
                                              <td >
                                                <h4> Valoraci&oacute;n </h4>
                                              </td>
                                              <td >
                                                <h2 id="valor">0%</h2>
                                              </td>
                                            </tr>
                                            <?php
                                            $auxn=array(); $auxp=array();
                                            $sqlCalif = "SELECT * FROM calificacion ca LEFT JOIN criterio cr ON (ca.idCriterio=cr.id) WHERE idContrato=$id_contrato AND idMes = ($id_mes_guarda-1) AND cr.idTema = $id_tema;";
                                            $respCalif = mysql_query($sqlCalif, $conexion);
                                            while ($danteriores = @mysql_fetch_assoc($respCalif)) {
                                              $respante[$danteriores['idCriterio']] = $danteriores['comentario'];
                                              switch($danteriores['evaluacion']) {
                                                case 'C':
                                                $valante[$danteriores['idCriterio']] = "Cumple";
                                                break;
                                                case 'NC':
                                                $valante[$danteriores['idCriterio']] = "No Cumple";
                                                break;
                                                case 'NA':
                                                $valante[$danteriores['idCriterio']] = "No Aplica";
                                                break;
                                              }
                                            }
                                            $sqlactual = "SELECT * FROM calificacion ca LEFT JOIN criterio cr ON (ca.idCriterio=cr.id) WHERE idContrato=$id_contrato AND idMes = $id_mes_guarda AND cr.idTema = $id_tema;"; $valactual[] = "";
                                            $resactual = mysql_query($sqlactual, $conexion);
                                            while ($dactual = @mysql_fetch_assoc($resactual)) {
                                              $respactual[$dactual['idCriterio']] = $dactual['comentario'];
                                              switch($dactual['evaluacion']) {
                                                case 'C':
                                                $valactual[$dactual['idCriterio']] = $dactual['evaluacion'];
                                                break;
                                                case 'NC':
                                                $valactual[$dactual['idCriterio']] = $dactual['evaluacion'];
                                                break;
                                                case 'NA':
                                                $valactual[$dactual['idCriterio']] = $dactual['evaluacion'];
                                                break;
                                                case 'Abierto':
                                                $valactual[$dactual['idCriterio']] = $dactual['evaluacion'];
                                                break;
                                                case 'Cerrado':
                                                $valactual[$dactual['idCriterio']] = $dactual['evaluacion'];
                                                break;
                                              }
                                            }

                                            $sqlCriterio = "SELECT * FROM criterio WHERE idTema = $id_tema;";
                                            $respCriterio = mysql_query($sqlCriterio, $conexion); $f=1;
                                            while( $criterio = mysql_fetch_assoc($respCriterio)) {
                                              $f = $criterio['id'];
                                              $crit = $criterio['porcentaje'];
                                              $aux = "gi".$f; $aux2 = "despliega('$aux', '$crit')";
                                              $auxn[] .= $aux; $auxp[] .= $crit;
                                              if ($f>17) { $fondo = 'style="background-color:#cba;"'; } else { $fondo=''; }
                                              if ($criterio['criterio']=="Estado de los requerimientos") {
                                                $aux3 = "alerta('$aux')";
                                                echo '<tr style="background-color:#ccc;">
                                                <td class="col-md-10">'.$criterio['criterio'].'<input type="hidden" name="pgi'.$f.'" value="'.$criterio['id'].'"></td>
                                                <td class="col-md-2"><select class="form-control" id="'.$aux.'" name="'.$aux.'" OnChange="'.$aux3.';"><option value="0">Seleccione</option><option value="Abierto" ';
                                                if(@$valactual[$f]=="Abierto"){ echo "selected"; }
                                                echo' >Abierto</option><option value="Cerrado" ';
                                                if(@$valactual[$f]=="Cerrado"){ echo "selected"; }
                                                echo' >Cerrado</option></select></td></tr>';
                                              } else {
                                                echo '<tr '.$fondo.'>
                                                <td class="col-md-10">'.$f.'. '.$criterio['criterio'].'<input type="hidden" name="pgi'.$f.'" value="'.$criterio['id'].'"></td>
                                                <td class="col-md-2"><select class="form-control" id="'.$aux.'" name="'.$aux.'" OnChange="'.$aux2.';"><option value="0">Seleccione</option><option value="C" ';
                                                if(@$valactual[$f]=="C"){ echo "selected"; }
                                                echo' >Cumple</option><option value="NC" ';
                                                if(@$valactual[$f]=="NC"){ echo "selected"; }
                                                echo' >No Cumple</option><option value="NA" ';
                                                if(@$valactual[$f]=="NA"){ echo "selected"; }
                                                echo' >No Aplica</option></select></td></tr>';
                                                echo '<tr ><td class="col-md-10" colspan="2">';
                                                echo '<textarea data-parsley-required class="form-control" name="t'.$aux.'" id="t'.$aux.'" >&nbsp;'.@$respactual[$f].'</textarea></td>';
                                                echo '<tr><td class="col-md-10" colspan="2">&nbsp;<textarea readonly class="form-control" style="" name="" id="" >'.@$valante[$f].' - '.@$respante[$f].'</textarea></td></tr>';
                                              }
                                              $f++;}
                                              ?>
                                            </tbody>
                                          </table>
                                        </div>

                                      </div>


                                    <?php } // End IF si el componente es Gestion Institucional ?>


                                    <div class="alert alert-info" role="alert">
                                      <strong>ANÁLISIS MENSUAL DEL CONTRATO</strong>
                                    </div>


                                    <div class="bs-docs-section">
                                      <h4 id="tables-example">Durante la ejecución del contrato, al prestador se le generaron reportes de incumplimientos graves (OIG)?</h4>
                                    </div>
                                    <div class="footer"></div>
                                    <select data-parsley-min="0" class="form-control" id="select1" name="incumplimiento_grave">
                                      <option value="<?php echo $incumplimiento_grave; ?>" selected	><?php if($incumplimiento_grave==0){echo "NO";} else{echo "SI";}?></option>
                                      <option value="0" 			>NO</option>
                                      <option value="1" 			>SI</option>
                                    </select>

                                    <div class="bs-docs-section">
                                      <h4 id="tables-example">Si contestó SI en la pregunta anterior, Describa las causas:</h4>
                                    </div>
                                    <div class="footer"></div>
                                    <textarea name="descripcion_incumplimiento_grave" rows="5" style="width:100%"><?php echo $descripcion_incumplimiento_grave;  ?></textarea>


                                    <div class="bs-docs-section">
                                      <h4 id="tables-example">Logros del Prestador: </h4>
                                    </div>
                                    <div class="footer"></div>
                                    <textarea name="logros_prestador" rows="5" style="width:100%"><?php echo $logros_prestador;  ?></textarea>

                                    <div class="bs-docs-section">
                                      <h4 id="tables-example">Dificultades del Prestador (Hallazgos Reiterativos): </h4>
                                    </div>
                                    <div class="footer"></div>
                                    <textarea name="dificultades_prestador" rows="5" style="width:100%"><?php echo $dificultades_prestador;  ?></textarea>

                                    <div class="bs-docs-section">
                                      <h4 id="tables-example">Estado del Debido Proceso: </h4>
                                    </div>
                                    <div class="footer"></div>
                                    <textarea name="debido_proceso" rows="5" style="width:100%"><?php echo $debido_proceso;  ?></textarea>

                                    <div class="bs-docs-section">
                                      <h4 id="tables-example">Sugerencia al programa Buen Comienzo para Asistencia Técnica: </h4>
                                    </div>
                                    <div class="footer"></div>
                                    <textarea name="asistencia_tecnica" rows="5" style="width:100%"><?php echo $asistencia_tecnica;  ?></textarea>

                                    <div class="bs-docs-section">
                                      <h4 id="tables-example">Otras Observaciones y/o Alertas</h4>
                                    </div>
                                    <div class="footer"></div>
                                    <textarea name="otras_observaciones" rows="5" style="width:100%"><?php echo $otras_observaciones;  ?></textarea>


                                    <?php if($estado==1){ ?>
                                      <center><button  class="btn btn-lg btn-pascual" type="submit">Guardar </button></center>
                                    <?php }?>

                                    <br>
                                    <br>
                                  </form>

                                  <?php
                                  include "cerrarconexion.php";
                                  ?>






                                  <div class="footer">
                                    <center> <p> &copy; 2020 Sistema de Información de la interventoría Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>
                                    </div>



                                  </div> <!-- /container -->


                                  <!-- Bootstrap core JavaScript
                                  ================================================== -->
                                  <script>
                                  <!-- Cerrar el boton emergente-->
                                  $('.close').click(function() {
                                    $(this).parent().parent().fadeOut();
                                  });

                                  function despliega(panel1,crit) {
                                    sumac=0;sumat=0;

                                    var peso= <?php echo json_encode($auxp); ?>;
                                    elementos = <?php echo json_encode($auxn); ?>;
                                    if(panel1 != "gi23"){
                                      for (b=0;b<17;b++) {
                                        if (document.getElementById(elementos[b]).selectedIndex==1) {
                                          sumac = parseFloat(sumac) + parseFloat(peso[b]);
                                          sumat = parseFloat(sumat) + parseFloat(peso[b]);
                                        } else if (document.getElementById(elementos[b]).selectedIndex==2) {
                                          sumat = parseFloat(sumat) + parseFloat(peso[b]);
                                        }
                                      }
                                    } else if(panel1 == "gi23"){
                                      if (document.getElementById(elementos[0]).selectedIndex==1) {
                                        sumac = parseFloat(sumac) + parseFloat(peso[0]);
                                        sumat = parseFloat(sumat) + parseFloat(peso[0]);
                                      } else if (document.getElementById(elementos[0]).selectedIndex==2) {
                                        sumat = parseFloat(sumat) + parseFloat(peso[0]);
                                      }
                                    }
                                    operacion = (sumac/sumat)*100;
                                    document.getElementById('valor').innerHTML = operacion.toFixed(2);
                                    document.getElementById('pini').innerHTML = operacion.toFixed(2);
                                    castigo = parseFloat(operacion);
                                    if (panel1<23){
                                      for (h=17;h<21;h++) {
                                        if (document.getElementById(elementos[h]).selectedIndex==2) {
                                          castigo = parseFloat(castigo) - (parseFloat(peso[h])*100);
                                        }
                                      } }
                                      document.getElementById('pfin').innerHTML = castigo.toFixed(2);
                                    }

                                    function alerta(ipanel) {
                                      var ireque = document.getElementById(ipanel).selectedIndex;
                                      igrave = document.getElementById("select1").value;
                                      if ((ireque == 1) || (igrave == 1)) {
                                        document.getElementById('valor').style.backgroundColor = "#FF0033";
                                        document.getElementById('pini').style.backgroundColor = "#FF0033";
                                        document.getElementById('pfin').style.backgroundColor = "#FF0033";
                                      } else {
                                        document.getElementById('valor').style.backgroundColor = "white";
                                        document.getElementById('pini').style.backgroundColor = "white";
                                        document.getElementById('pfin').style.backgroundColor = "white";
                                      }

                                    }
                                    </script>



                                    <!-- Placed at the end of the document so the pages load faster -->
                                  </body>
                                  <script>

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
