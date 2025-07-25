<?php
session_start();
if ($_SESSION['login']) {
  include "conexion.php";
  $id_grupo=$_SESSION["grupo"];
  $id_componente=$_SESSION["componente"];
  $nombre=$_SESSION["nombre_usuario"];
  $username = $_SESSION['login'];
  $msg=$_GET['msg'];
  $fotoperfil = $_SESSION["fotoperfil"];

  //Consulta de componentes
  if ($id_grupo == 1) {
    $componentes = mysql_query(("
    SELECT id_componente,nombre_componente
    FROM componente
    WHERE id_componente in (4,8,1,7,9,2,5)
    "),$conexion);
  }else {
    $componentes = mysql_query(("
    SELECT id_componente,nombre_componente
    FROM componente
    WHERE id_componente = '$id_componente'
    "),$conexion);
  }


  $contratos = mysql_query(("
  SELECT
  semaforo.id_contrato,
  prestador.nombre_prestador,
  modalidad.abr_modalidad
  FROM
  semaforo,prestador,modalidad
  WHERE
  semaforo.id_prestador=prestador.id_prestador and
  semaforo.id_modalidad=modalidad.id_modalidad
  group by id_contrato
  order by nombre_prestador
  "),$conexion);

  if (isset($_GET['id_contrato'])) {
    //Variables capturadas por get
    $id_contrato=$_GET['id_contrato'];
    $id_componente=$_GET['id_componente'];

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

    $info_componente = mysql_query(("
    SELECT nombre_componente
    FROM componente
    WHERE id_componente = $id_componente"), $conexion);

    $rowcomp=mysql_fetch_assoc($info_componente);
    $nombre_componente = $rowcomp["nombre_componente"];

  }

  if ($id_grupo==2) {
    if (isset($_GET['id_contrato'])) {
      if(isset($_GET['id'])){
        $id=$_GET['id'];
      }
      $info_liquidacion=mysql_query(("
      SELECT
      id,
      observacion_contrato
      FROM
      informe_liquidacion
      WHERE
      id_contrato=$id_contrato and
      id_modalidad=$id_modalidad and
      id_componente=$id_componente
      "),$conexion);
      $row=mysql_fetch_assoc($info_liquidacion);
      $observacion_contrato=$row['observacion_contrato'];
      $id=$row['id'];
    }
  }
  //Consuta de cantidad de visitas
  if (isset($id_contrato)) {
    if ($id_grupo == 3) {
      $actascreadasxusuario = mysql_query(("
      SELECT
      MONTH(fecha_evaluacion) AS MES,
      MONTHNAME(fecha_evaluacion) AS NOMBRE,
      COUNT(*) as NUMERO
      from acta
      WHERE fecha_evaluacion <= now() and
      id_componente = '$id_componente'and
      id_contrato = '$id_contrato'
      GROUP BY MES"), $conexion);
      $actasfallidascreadasxusuario = mysql_query(("
      SELECT
      MONTH(fecha_evaluacion) AS MES,
      MONTHNAME(fecha_evaluacion) AS NOMBRE,
      COUNT(*) as NUMERO
      from acta_fallida
      WHERE fecha_evaluacion <= now() and
      id_componente = '$id_componente'and
      id_contrato = '$id_contrato'
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
      id_interventor = '$username' and
      id_contrato = '$id_contrato'
      GROUP BY MES"), $conexion);
      $actasfallidascreadasxusuario = mysql_query(("
      SELECT
      MONTH(fecha_evaluacion) AS MES,
      MONTHNAME(fecha_evaluacion) AS NOMBRE,
      COUNT(*) as NUMERO
      from acta_fallida
      WHERE fecha_evaluacion <= now() and
      id_interventor = '$username'and
      id_contrato = '$id_contrato'
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

    <title>Informes Liquidación por Contrato</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/datepicker.css" rel="stylesheet">
    <!-- Estilos menú principal -->
    <link rel="stylesheet" href="css/estilos.css">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- bootstrap widget theme -->
    <link href="css/theme.bootstrap.css" rel="stylesheet" >

    <!-- JavaScript para los filtros de las tablas -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery.tablesorter.js"></script>
    <script src="js/jquery.tablesorter.widgets.js"></script>
    <!-- Para validacion de campos -->
    <script src="js/parsley.js"></script>


    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

    <!-- Custom styles for this template -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">
  </head>
  <body>
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
<?php include("menu.php");?>
<div class="container">
  <div class="jumbotron">
    <h2>Ingreso de Informes Liquidación por Contrato</h2>
    <form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="GET" action="">
      <input type="hidden" name="msg" value="1">

      <select data-parsley-min="1" class="form-control" name="id_componente" id="select1">
        <option value="0" required>Seleccione el Componente...</option>
        <?php while($row = mysql_fetch_assoc($componentes)){ ?>
          <option value="<?php echo $row["id_componente"]; ?>"><?php echo $row["nombre_componente"]; ?></option>
        <?php } ?>
      </select>

      <?php if ($id_grupo == 2){ ?>
        <select data-parsley-min="1" class="form-control" name="id_contrato" id="select2">
          <option value="0" required>Seleccione el Contrato...</option>
          <?php while($row = mysql_fetch_assoc($contratos)){ ?>
            <option value="<?php echo $row["id_contrato"]; ?>"><?php echo $row["id_contrato"].":  ".$row["abr_modalidad"]." - ".$row["nombre_prestador"]; ?></option>
          <?php } ?>
        </select>
      <?php } ?>


      <button  class="btn btn-pascual" type="submit">Consultar</button>
    </form>
  </div>

  <?php if ($msg!=0){
    if($id_grupo==2){?>
      <div class="alert alert-info" role="alert">
        <strong><center>INFORMACIÓN GENERAL</center></strong>
      </div>
      <div style="margin-top:5%;">
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
                        <td colspan="2">Componente: <strong><?php echo $nombre_componente; ?></td>
                        </tr>
                      </tbody>
                    </table>

                    <div class="panel with-nav-tabs panel-default">
                      <div class="panel-heading">
                        <ul class="nav nav-tabs">
                          <li id="contrato"  class="active"><a href="#tab1default" data-toggle="tab">Contratos</a></li>
                          <li id="visitas"><a href="#tab2default" data-toggle="tab">Visitas</a></li>
                          <li id="ingreso"><a href="#tab3default" data-toggle="tab">Ingreso de informe</a></li>
                        </ul>
                      </div>
                      <div class="panel-body">
                        <div class="tab-content">
                          <div class="tab-pane fade in active" id="tab1default">
                            <div class="bs-docs-section" align="center">
                              <h2 id="tables-example">Contratos</h2>
                              <?php
                              $id_contrato=$_GET['id_contrato'];
                              $id_componente=$_GET['id_componente'];
                              if ($id_componente == 7) {
                                $porcentajes= mysql_query(("
                                select
                                AVG(operacion1.porcentaje_inicial)  porcentaje_inicial,
                                AVG(operacion1.porcentajes_final)  porcentajes_final
                                FROM
                                (SELECT
                                sum(porcentaje_inicial) / sum(porc_referencia) porcentaje_inicial,
                                sum(porcentajes_final) / sum(porc_referencia) porcentajes_final,
                                id_tema
                                FROM
                                (
                                  SELECT
                                  sum(porc_inicial) porcentaje_inicial,
                                  sum(porc_final) porcentajes_final,
                                  sum(porc_referencia) porc_referencia,
                                  id_tema
                                  FROM
                                  evaluacion
                                  WHERE
                                  id_contrato='$id_contrato' and
                                  id_componente='$id_componente' and
                                  estado='1'
                                  group by id_subtema, id_acta
                                  ) as operacion
                                  GROUP by operacion.id_tema) as operacion1
                                  "),$conexion);

                                  $descuentos_calculo_calidad = mysql_query(("
                                  SELECT
                                  detalle_tipo_descuento.tipo_descuento,
                                  descuentos_x_valoracion.estado,
                                  detalle_tipo_descuento.descuento
                                  FROM
                                  descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
                                  WHERE
                                  descuentos_x_valoracion.id_prestador = prestador.id_prestador and
                                  descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
                                  detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
                                  tipo_descuento.id = detalle_tipo_descuento.tipo_descuento and
                                  descuentos_x_valoracion.id_contrato = '$id_contrato'
                                  ORDER BY prestador.nombre_prestador
                                  "), $conexion);

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
                                  ORDER BY prestador.nombre_prestador
                                  "), $conexion);

                                  $descuento = 0;
                                  $descuento_no_patogeno = 0;
                                  while ($row1=mysql_fetch_assoc($descuentos_calculo_calidad)) {
                                    if ($row1['tipo_descuento'] == 3) {
                                      $descuento_no_patogeno = null;
                                      $descuento_no_patogeno = $row1["descuento"];
                                    }else {
                                      $descuento = $descuento + $row1["descuento"];
                                    }
                                  }
                                  $descuento = $descuento + $descuento_no_patogeno;
                                  $descuento = round($descuento,4);

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

                                  ?>
                                  <table align="center" class="table table-bordered table-hover" id='table' style="width: 95%">
                                    <thead>
                                      <tr>
                                        <th rowspan="2" class="info">Componente Técnico:</th>
                                        <th rowspan="2" class="info">Porcentaje de calidad hasta la fecha:</th>
                                        <th colspan="1" class="info">Porcentaje de cumplimiento hasta la fecha:</th>
                                      </tr>
                                    </thead>

                                    <?php
                                    while ($row=mysql_fetch_assoc($porcentajes)) {
                                      if($row['porcentaje_inicial']!="" &&  $row['porcentajes_final']!="" )
                                      {
                                        $promedio_componente = round($row['porcentaje_inicial'],4)*100;
                                        $total_inicial = $promedio_componente - $descuento;
                                        $promedio_componente1 = round($row['porcentajes_final'],4)*100;
                                        $total_final = $promedio_componente1 + $descuento1;
                                        if ($total_final > 100) {
                                          $total_final= 100;
                                        }
                                        ?>
                                        <tr>
                                          <td class="active" align="center"><?php echo $nombre_componente;?>	</td>
                                          <td class="active" align="center"><?php echo $total_inicial ?>%</td>
                                          <td class="active" align="center"><?php echo $total_final ?>%</td>
                                        </tr>
                                        <?php
                                      }else{
                                        ?>
                                        <td class="active"></td>
                                        <td class="active"></td>
                                        <td class="active"></td>
                                        <?php
                                      } //end else
                                    }// end WHILE
                                    ?>
                                  </table>
                                  <?php
                                }else {
                                  $porcentajes= mysql_query(("
                                  select
                                  AVG(operacion1.porcentaje_inicial)  porcentaje_inicial,
                                  AVG(operacion1.porcentajes_final)  porcentajes_final
                                  FROM
                                  (SELECT
                                  sum(porcentaje_inicial) / sum(porc_referencia) porcentaje_inicial,
                                  sum(porcentajes_final) / sum(porc_referencia) porcentajes_final,
                                  id_tema
                                  FROM
                                  (
                                    SELECT
                                    sum(porc_inicial) porcentaje_inicial,
                                    sum(porc_final) porcentajes_final,
                                    sum(porc_referencia) porc_referencia,
                                    id_tema
                                    FROM
                                    evaluacion
                                    WHERE
                                    id_contrato='$id_contrato' and
                                    id_componente='$id_componente' and
                                    estado='1'
                                    group by id_subtema, id_acta
                                    ) as operacion
                                    GROUP by operacion.id_tema) as operacion1
                                    "),$conexion);


                                    ?>
                                    <table align="center" class="table table-bordered table-hover" id='table' style="width: 95%">
                                      <thead>
                                        <tr>
                                          <th rowspan="2" class="info">Componente Técnico:</th>
                                          <th rowspan="2" class="info">Porcentaje de calidad hasta la fecha:</th>
                                          <th colspan="1" class="info">Porcentaje de cumplimiento hasta la fecha:</th>
                                        </tr>
                                      </thead>
                                      <?php
                                      while($row=mysql_fetch_assoc($porcentajes)){
                                        if($row['porcentaje_inicial']!="" && $row['porcentajes_final']!="" ){
                                          ?>
                                          <tr>
                                            <td class="active" align="center"><?php echo $nombre_componente; ?>	</td>
                                            <td class="active" align="center"><?php echo round($row['porcentaje_inicial'],4)*100; ?>%</td>
                                            <td class="active" align="center"><?php echo round($row['porcentajes_final'],4)*100; ?>%</td>
                                          </tr>
                                          <?php
                                        }else{
                                          ?>
                                          <td class="active"></td>
                                          <td class="active"></td>
                                          <td class="active"></td>
                                          <td class="active"></td>
                                          <?php
                                        } //end else
                                      }//end While
                                    }
                                    ?>
                                  </table>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="tab2default">
                                <div class="bs-docs-section" align="center">
                                  <h2 id="tables-example">Visitas</h2>

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
                                </div>
                              </div>
                              <div class="tab-pane fade" id="tab3default">

                                <div class="bs-docs-section" align="center">
                                  <h2 id="tables-example">Ingreso de informe</h2>
                                  <form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">
                                    <input type="hidden" name="caso" id="caso" value="32">
                                    <input type="hidden" name="id_contrato" value="<?php echo $id_contrato; ?>">
                                    <input type="hidden" name="id_prestador" value="<?php echo $id_prestador; ?>">
                                    <input type="hidden" name="id_modalidad" value="<?php echo $id_modalidad; ?>">
                                    <input type="hidden" name="id_componente" value="<?php echo $id_componente; ?>">
                                    <input type="hidden" name="msg" value="<?php echo $msg; ?>">
                                    <?php if ($id != null){ ?>
                                      <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <?php }?>
                                    <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">
                                    <textarea name="observacion_contrato" class="form-control" rows="5" style="width:100%"><?php echo $observacion_contrato; ?></textarea>
                                    <center><button  class="btn btn-pascual" type="submit">Guardar</button></center>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                <?php }elseif ($id_grupo==1 || $id_grupo==3) {
                  $id_componente=$_GET['id_componente'];
                  $info_final_interventor=mysql_query(("
                  SELECT
                  informe_liquidacion.id,
                  informe_liquidacion.id_contrato,
                  informe_liquidacion.id_interventor,
                  prestador.id_prestador,
                  prestador.nombre_prestador,
                  informe_liquidacion.observacion_contrato,
                  informe_liquidacion.id_prestador,
                  informe_liquidacion.id_modalidad
                  FROM
                  informe_liquidacion, prestador
                  WHERE
                  id_componente='$id_componente' and
                  informe_liquidacion.id_prestador = prestador.id_prestador
                  and informe_liquidacion.estado != 0
                  "),$conexion);
                  ?>
                  <div style="margin-top:5%;">
                    <div class="row">
                      <div class="col-md-12">

                        <div class="bs-docs-section" align="center">
                          <h2 id="tables-example">Revisión de informes</h2>
                        </div>

                        <form data-parsley-validate class="form-signin" role="form" name="formulario2" METHOD="post" action="inserts.php">
                          <input type="hidden" name="caso" id="caso" value="32">
                          <table align="center" class="table table-bordered table-hover" id='table1' style="width: 100%">

                            <thead>
                              <tr>
                                <th class="info" align="center">Contrato</th>
                                <th class="info" align="center">Interventor</th>
                                <th class="info" align="center">Nombre Prestador</th>
                                <th class="info" align="center">Observacion Contrato</th>
                                <th class="info" align="center">% Calidad</th>
                                <th class="info" align="center">% Cumplimiento</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              while($row=mysql_fetch_assoc($info_final_interventor)){
                                if ($id_componente == 7) {


                                  $id=$row['id'];
                                  $observacion_contrato=$row['observacion_contrato'];
                                  $id_prestador=$row['id_prestador'];
                                  $id_modalidad=$row['id_modalidad'];
                                  $id_contrato = $row['id_contrato'];
                                  $porcentajes= mysql_query(("
                                  select
                                  AVG(operacion1.porcentaje_inicial)  porcentaje_inicial,
                                  AVG(operacion1.porcentajes_final)  porcentajes_final
                                  FROM
                                  (SELECT
                                  sum(porcentaje_inicial) / sum(porc_referencia) porcentaje_inicial,
                                  sum(porcentajes_final) / sum(porc_referencia) porcentajes_final,
                                  id_tema
                                  FROM
                                  (
                                    SELECT
                                    sum(porc_inicial) porcentaje_inicial,
                                    sum(porc_final) porcentajes_final,
                                    sum(porc_referencia) porc_referencia,
                                    id_tema
                                    FROM
                                    evaluacion
                                    WHERE
                                    id_contrato='$id_contrato' and
                                    id_componente='$id_componente' and
                                    estado='1'
                                    group by id_subtema, id_acta
                                    ) as operacion
                                    GROUP by operacion.id_tema) as operacion1
                                    "),$conexion);
                                    $rowp=mysql_fetch_assoc($porcentajes);
                                    $porc_inicial=$rowp["porcentaje_inicial"];
                                    $porc_final=$rowp["porcentajes_final"];

                                    $descuentos_calculo_calidad = mysql_query(("
                                    SELECT
                                    detalle_tipo_descuento.tipo_descuento,
                                    descuentos_x_valoracion.estado,
                                    detalle_tipo_descuento.descuento
                                    FROM
                                    descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
                                    WHERE
                                    descuentos_x_valoracion.id_prestador = prestador.id_prestador and
                                    descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
                                    detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
                                    tipo_descuento.id = detalle_tipo_descuento.tipo_descuento and
                                    descuentos_x_valoracion.id_contrato = '$id_contrato'
                                    ORDER BY prestador.nombre_prestador
                                    "), $conexion);

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
                                    ORDER BY prestador.nombre_prestador
                                    "), $conexion);


                                    $descuento = 0;
                                    $descuento_no_patogeno = 0;
                                    while ($row1=mysql_fetch_assoc($descuentos_calculo_calidad)) {
                                      if ($row1['tipo_descuento'] == 3) {
                                        $descuento_no_patogeno = null;
                                        $descuento_no_patogeno = $row1["descuento"];
                                      }else {
                                        $descuento = $descuento + $row1["descuento"];
                                      }
                                    }
                                    $descuento = $descuento + $descuento_no_patogeno;
                                    $descuento = round($descuento,4);

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

                                    $promedio_componente = round($porc_inicial,4)*100;
                                    $porc_inicial = $promedio_componente - $descuento;
                                    $promedio_componente1 = round($porc_final,4)*100;
                                    $porc_final = $promedio_componente1 + $descuento1;
                                    if ($porc_final > 100) {
                                      $porc_final= 100;
                                    }
                                    $porc_inicial = $porc_inicial/100;
                                    $porc_final = $porc_final/100;
                                    ?>

                                    <tr>
                                      <td class="active" name="id_contrato"><?php echo $row['id_contrato'];  ?></td>
                                      <td class="active" name="id_interventor[]"><?php echo $row['id_interventor'];  ?></td>
                                      <td class="active"><?php echo $row['nombre_prestador'];  ?></td>
                                      <td class="active"><textarea name="observacion_contrato[]" class="form-control" rows="8" style="width:350px"><?php echo $observacion_contrato; ?></textarea></td>
                                      <?php if($porc_inicial>=0.95) {?>
                                        <td><div class="alert alert-success"><center><?php echo round($porc_inicial,4)*100;  ?>%</center></div></td>
                                      <?php } elseif($porc_inicial>=0.80) {?>
                                        <td><div class="alert alert-success"><center><?php echo round($porc_inicial,4)*100;  ?>%</center></div></td>
                                      <?php } elseif ($porc_inicial>=0.60){ ?>
                                        <td><div class="alert alert-warning"><center><?php echo round($porc_inicial,4)*100;  ?>%</center></div></td>
                                      <?php } else{ ?>
                                        <td><div class="alert alert-danger"><center><?php echo round($porc_inicial,4)*100;  ?>%</center></div></td>
                                      <?php } ?>
                                      <?php if($porc_final>=0.95) {?>
                                        <td><div class="alert alert-success"><center><?php echo round($porc_final,4)*100;  ?>%</center></div></td>
                                      <?php } elseif($porc_final>=0.80) {?>
                                        <td><div class="alert alert-success"><center><?php echo round($porc_final,4)*100;  ?>%</center></div></td>
                                      <?php } elseif ($porc_final>=0.60){ ?>
                                        <td><div class="alert alert-warning"><center><?php echo round($porc_final,4)*100;  ?>%</center></div></td>
                                      <?php } else{ ?>
                                        <td><div class="alert alert-danger"><center><?php echo round($porc_final,4)*100;  ?>%</center></div></td>
                                      <?php } ?>
                                      <input type="hidden" name="id_contrato[]" value="<?php echo $row['id_contrato'];  ?>">
                                      <input type="hidden" name="id[]" value="<?php echo $id; ?>">
                                      <input type="hidden" name="id_prestador[]" value="<?php echo $id_prestador; ?>">
                                      <input type="hidden" name="id_modalidad[]" value="<?php echo $id_modalidad; ?>">
                                      <input type="hidden" name="id_componente[]" value="<?php echo $id_componente; ?>">
                                      <input type="hidden" name="msg" value="<?php echo $msg; ?>">
                                      <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">


                                    </tr>

                                  <?php }else {
                                    $id=$row['id'];
                                    $observacion_contrato=$row['observacion_contrato'];
                                    $id_prestador=$row['id_prestador'];
                                    $id_modalidad=$row['id_modalidad'];
                                    $id_contrato = $row['id_contrato'];
                                    $porcentajes= mysql_query(("
                                    select
                                    AVG(operacion1.porcentaje_inicial)  porcentaje_inicial,
                                    AVG(operacion1.porcentajes_final)  porcentajes_final
                                    FROM
                                    (SELECT
                                    sum(porcentaje_inicial) / sum(porc_referencia) porcentaje_inicial,
                                    sum(porcentajes_final) / sum(porc_referencia) porcentajes_final,
                                    id_tema
                                    FROM
                                    (
                                      SELECT
                                      sum(porc_inicial) porcentaje_inicial,
                                      sum(porc_final) porcentajes_final,
                                      sum(porc_referencia) porc_referencia,
                                      id_tema
                                      FROM
                                      evaluacion
                                      WHERE
                                      id_contrato='$id_contrato' and
                                      id_componente='$id_componente' and
                                      estado='1'
                                      group by id_subtema, id_acta
                                      ) as operacion
                                      GROUP by operacion.id_tema) as operacion1
                                      "),$conexion);
                                      $rowp=mysql_fetch_assoc($porcentajes);
                                      $porc_inicial=$rowp["porcentaje_inicial"];
                                      $porc_final=$rowp["porcentajes_final"];

                                      ?>
                                      <tr>
                                        <td class="active" name="id_contrato"><?php echo $row['id_contrato'];  ?></td>
                                        <td class="active" name="id_interventor[]"><?php echo $row['id_interventor'];  ?></td>
                                        <td class="active"><?php echo $row['nombre_prestador'];  ?></td>
                                        <td class="active"><textarea name="observacion_contrato[]" class="form-control" rows="8" style="width:350px"><?php echo $observacion_contrato; ?></textarea></td>
                                        <?php if($porc_inicial>=0.95) {?>
                                          <td><div class="alert alert-success"><center><?php echo round($porc_inicial,4)*100;  ?>%</center></div></td>
                                        <?php } elseif($porc_inicial>=0.80) {?>
                                          <td><div class="alert alert-success"><center><?php echo round($porc_inicial,4)*100;  ?>%</center></div></td>
                                        <?php } elseif ($porc_inicial>=0.60){ ?>
                                          <td><div class="alert alert-warning"><center><?php echo round($porc_inicial,4)*100;  ?>%</center></div></td>
                                        <?php } else{ ?>
                                          <td><div class="alert alert-danger"><center><?php echo round($porc_inicial,4)*100;  ?>%</center></div></td>
                                        <?php } ?>
                                        <?php if($porc_final>=0.95) {?>
                                          <td><div class="alert alert-success"><center><?php echo round($porc_final,4)*100;  ?>%</center></div></td>
                                        <?php } elseif($porc_final>=0.80) {?>
                                          <td><div class="alert alert-success"><center><?php echo round($porc_final,4)*100;  ?>%</center></div></td>
                                        <?php } elseif ($porc_final>=0.60){ ?>
                                          <td><div class="alert alert-warning"><center><?php echo round($porc_final,4)*100;  ?>%</center></div></td>
                                        <?php } else{ ?>
                                          <td><div class="alert alert-danger"><center><?php echo round($porc_final,4)*100;  ?>%</center></div></td>
                                        <?php } ?>
                                        <input type="hidden" name="id_contrato[]" value="<?php echo $row['id_contrato'];  ?>">
                                        <input type="hidden" name="id[]" value="<?php echo $id; ?>">
                                        <input type="hidden" name="id_prestador[]" value="<?php echo $id_prestador; ?>">
                                        <input type="hidden" name="id_modalidad[]" value="<?php echo $id_modalidad; ?>">
                                        <input type="hidden" name="id_componente[]" value="<?php echo $id_componente; ?>">
                                        <input type="hidden" name="msg" value="<?php echo $msg; ?>">
                                        <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">


                                      </tr>
                                      <?php
                                    }

                                  } //Fin WHILE ?>

                                </tbody>
                              </table>
                              <center><button  class="btn btn-pascual" type="submit">Guardar</button></center>
                            </form>

                          </div>

                        </div>
                      </div>
                    <?php } ?>
                  <?php } ?>
                  <?php include "cerrarconexion.php"; ?>
                </div>
                <br>
                <div class="footer">
                  <center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

                  </div>
                  <script type="text/javascript">
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
                  <script type="text/javascript">
                  var msg = <?php echo $msg; ?>;
                  var id_grupo = <?php echo $id_grupo; ?>;
                  if(msg != 0 && id_grupo==2){
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

                  }
                  </script>
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

                (function($) {

                  <!-- Cerrar el boton emergente-->
                  $('.close').click(function() {
                    $(this).parent().parent().fadeOut();
                  });


                  <!-- Filtros para las tablas-->
                  $.extend($.tablesorter.themes.bootstrap, {
                    // these classes are added to the table. To see other table classes available,
                    // look here: http://twitter.github.com/bootstrap/base-css.html#tables
                    table      : 'table table-bordered',
                    caption    : 'caption',
                    header     : 'bootstrap-header', // give the header a gradient background
                    footerRow  : '',
                    footerCells: '',
                    icons      : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
                    sortNone   : 'bootstrap-icon-unsorted',
                    sortAsc    : 'icon-chevron-up glyphicon glyphicon-chevron-up',     // includes classes for Bootstrap v2 & v3
                    sortDesc   : 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
                    active     : '', // applied when column is sorted
                    hover      : '', // use custom css here - bootstrap class may not override it
                    filterRow  : '', // filter row class
                    even       : '', // odd row zebra striping
                    odd        : ''  // even row zebra striping
                  });

                  // call the tablesorter plugin and apply the uitheme widget
                  $("#table1").tablesorter({
                    // this will apply the bootstrap theme if "uitheme" widget is included
                    // the widgetOptions.uitheme is no longer required to be set
                    theme : "bootstrap",

                    widthFixed: true,

                    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

                    // widget code contained in the jquery.tablesorter.widgets.js file
                    // use the zebra stripe widget if you plan on hiding any rows (filter widget)
                    widgets : [ "uitheme", "filter", "zebra" ],

                    widgetOptions : {
                      // using the default zebra striping class name, so it actually isn't included in the theme variable above
                      // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
                      zebra : ["even", "odd"],

                      // reset filters button
                      filter_reset : ".reset"

                      // set the uitheme widget to use the bootstrap theme class names
                      // this is no longer required, if theme is set
                      // ,uitheme : "bootstrap"

                    }
                  })
                  .tablesorterPager({

                    // target the pager markup - see the HTML block below
                    container: $(".ts-pager"),

                    // target the pager page select dropdown - choose a page
                    cssGoto  : ".pagenum",

                    // remove rows from the table to speed up the sort of large tables.
                    // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
                    removeRows: false,

                    // output string - default is '{page}/{totalPages}';
                    // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
                    output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

                  });




                })(jQuery);

                </script>
                </html>
              <?php }else{
                ?>
                <script>
                window.location='index.php';
                </script>
                <?php
              }

              ?>
