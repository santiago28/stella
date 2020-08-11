<?php
session_start();
if ($_SESSION['login']) {
  include "conexion.php";

  $id_grupo=$_SESSION["grupo"];
  $id_componente=$_SESSION["componente"];
  $nombre=$_SESSION["nombre_usuario"];
  $id_interventor=$_SESSION["login"];
  $fotoperfil = $_SESSION["fotoperfil"];

  if ($id_grupo == 2) {
    $contrato_x_descuentos = mysql_query(("
    SELECT
    id_contrato
    FROM descuentos_x_valoracion
    WHERE descuentos_x_valoracion.id_interventor = '$id_interventor'
    GROUP BY id_contrato
    "), $conexion);
  }else {
    $contrato_x_descuentos = mysql_query(("
    SELECT
    descuentos_x_valoracion.id_contrato, prestador.nombre_prestador
    FROM descuentos_x_valoracion, prestador
    WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador
    GROUP BY descuentos_x_valoracion.id_contrato
    "), $conexion);
  }

  if (isset($_GET['id_contrato'])) {
    $id_contrato = $_GET['id_contrato'];
    if ($id_contrato != 0) {

      if ($id_grupo == 2) {
        $descuentos = mysql_query(("
        SELECT
        descuentos_x_valoracion.id_sede as nombre_sede,
        descuentos_x_valoracion.id,
        prestador.nombre_prestador,
        modalidad.nombre_modalidad,
        descuentos_x_valoracion.id_interventor,
        DATE_FORMAT(descuentos_x_valoracion.fecha,'%Y-%m-%d') as fecha,
        descuentos_x_valoracion.id_contrato,
        descuentos_x_valoracion.fecha_solicitud_aclaracion,
        descuentos_x_valoracion.id_radicado_osa,
        descuentos_x_valoracion.fecha_requerimiento,
        descuentos_x_valoracion.id_radicado_orq,
        descuentos_x_valoracion.fecha_envio_evidencia,
        descuentos_x_valoracion.subsanacion,
        tipo_descuento.tipo_descuento as nombre_descuento,
        detalle_tipo_descuento.tipo_descuento,
        descuentos_x_valoracion.estado,
        detalle_tipo_descuento.descuento
        FROM descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
        WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador and
        descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
        detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
        tipo_descuento.id = detalle_tipo_descuento.tipo_descuento and
        descuentos_x_valoracion.id_interventor = '$id_interventor' and
        descuentos_x_valoracion.id_contrato = '$id_contrato'
        ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
      }else {
        $descuentos = mysql_query(("
        SELECT
        descuentos_x_valoracion.id_sede as nombre_sede,
        descuentos_x_valoracion.id,
        prestador.nombre_prestador,
        modalidad.nombre_modalidad,
        descuentos_x_valoracion.id_interventor,
        DATE_FORMAT(descuentos_x_valoracion.fecha,'%Y-%m-%d') as fecha,
        descuentos_x_valoracion.id_contrato,
        descuentos_x_valoracion.fecha_solicitud_aclaracion,
        descuentos_x_valoracion.id_radicado_osa,
        descuentos_x_valoracion.fecha_requerimiento,
        descuentos_x_valoracion.id_radicado_orq,
        descuentos_x_valoracion.fecha_envio_evidencia,
        descuentos_x_valoracion.subsanacion,
        tipo_descuento.tipo_descuento as nombre_descuento,
        detalle_tipo_descuento.tipo_descuento,
        descuentos_x_valoracion.estado,
        detalle_tipo_descuento.descuento
        FROM descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
        WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador and
        descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
        detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
        tipo_descuento.id = detalle_tipo_descuento.tipo_descuento and
        descuentos_x_valoracion.id_contrato = '$id_contrato'
        ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
      }

      if ($id_grupo == 2) {
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
        descuentos_x_valoracion.id_interventor = '$id_interventor' and
        descuentos_x_valoracion.id_contrato = '$id_contrato'
        ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
      }else {
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
        ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
      }

      if ($id_grupo == 2) {
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
        descuentos_x_valoracion.id_interventor = '$id_interventor' and
        descuentos_x_valoracion.id_contrato = '$id_contrato'
        ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
      }else {
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
        ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
      }



      $porcentaje_calidad = mysql_query(("
      SELECT
      avg(porcentaje_inicial) promedio_componente

      FROM
      (
        SELECT
        sum(porc_inicial)/sum(porc_referencia) porcentaje_inicial
        FROM
        evaluacion
        WHERE
        id_contrato='$id_contrato' and
        id_componente='7' and
        id_tema='701' and
        estado='1'
        group by id_subtema, id_acta
        ) as operacion
        "),$conexion);

        $porcentaje_cumplimiento = mysql_query(("
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
          id_componente='7' and
          id_tema='701' and
          estado='1'
          group by id_subtema, id_acta
          ) as operacion
          "),$conexion);

        }else {
          if ($id_grupo==1 || $id_grupo==4 || $id_componente == 7) {
            if ($id_grupo == 2) {
              $descuentos = mysql_query(("
              SELECT
              descuentos_x_valoracion.id_sede as nombre_sede,
              descuentos_x_valoracion.id,
              prestador.nombre_prestador,
              modalidad.nombre_modalidad,
              descuentos_x_valoracion.id_interventor,
              DATE_FORMAT(descuentos_x_valoracion.fecha,'%Y-%m-%d') as fecha,
              descuentos_x_valoracion.id_contrato,
              descuentos_x_valoracion.fecha_solicitud_aclaracion,
              descuentos_x_valoracion.id_radicado_osa,
              descuentos_x_valoracion.fecha_requerimiento,
              descuentos_x_valoracion.id_radicado_orq,
              descuentos_x_valoracion.fecha_envio_evidencia,
              descuentos_x_valoracion.estado,
              descuentos_x_valoracion.subsanacion,
              tipo_descuento.tipo_descuento as nombre_descuento,
              detalle_tipo_descuento.tipo_descuento,
              detalle_tipo_descuento.descuento
              FROM descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
              WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador and
              descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
              detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
              descuentos_x_valoracion.id_interventor = '$id_interventor' and
              tipo_descuento.id = detalle_tipo_descuento.tipo_descuento
              ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
            }else {
              if ($id_grupo == 2) {
                $descuentos = mysql_query(("
                SELECT
                descuentos_x_valoracion.id_sede as nombre_sede,
                descuentos_x_valoracion.id,
                prestador.nombre_prestador,
                modalidad.nombre_modalidad,
                descuentos_x_valoracion.id_interventor,
                DATE_FORMAT(descuentos_x_valoracion.fecha,'%Y-%m-%d') as fecha,
                descuentos_x_valoracion.id_contrato,
                descuentos_x_valoracion.fecha_solicitud_aclaracion,
                descuentos_x_valoracion.id_radicado_osa,
                descuentos_x_valoracion.fecha_requerimiento,
                descuentos_x_valoracion.id_radicado_orq,
                descuentos_x_valoracion.fecha_envio_evidencia,
                descuentos_x_valoracion.estado,
                descuentos_x_valoracion.subsanacion,
                tipo_descuento.tipo_descuento as nombre_descuento,
                detalle_tipo_descuento.tipo_descuento,
                detalle_tipo_descuento.descuento
                FROM descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
                WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador and
                descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
                detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
                descuentos_x_valoracion.id_interventor = '$id_interventor' and
                tipo_descuento.id = detalle_tipo_descuento.tipo_descuento
                ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
              }else {
                $descuentos = mysql_query(("
                SELECT
                descuentos_x_valoracion.id_sede as nombre_sede,
                descuentos_x_valoracion.id,
                prestador.nombre_prestador,
                modalidad.nombre_modalidad,
                descuentos_x_valoracion.id_interventor,
                DATE_FORMAT(descuentos_x_valoracion.fecha,'%Y-%m-%d') as fecha,
                descuentos_x_valoracion.id_contrato,
                descuentos_x_valoracion.fecha_solicitud_aclaracion,
                descuentos_x_valoracion.id_radicado_osa,
                descuentos_x_valoracion.fecha_requerimiento,
                descuentos_x_valoracion.id_radicado_orq,
                descuentos_x_valoracion.fecha_envio_evidencia,
                descuentos_x_valoracion.estado,
                descuentos_x_valoracion.subsanacion,
                tipo_descuento.tipo_descuento as nombre_descuento,
                detalle_tipo_descuento.tipo_descuento,
                detalle_tipo_descuento.descuento
                FROM descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
                WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador and
                descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
                detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
                tipo_descuento.id = detalle_tipo_descuento.tipo_descuento
                ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
              }

            }

          }
        }
      }else {
        if ($id_grupo==1 || $id_grupo==4 || $id_componente == 7) {
          if ($id_grupo == 2) {
            $descuentos = mysql_query(("
            SELECT
            descuentos_x_valoracion.id_sede as nombre_sede,
            descuentos_x_valoracion.id,
            prestador.nombre_prestador,
            modalidad.nombre_modalidad,
            descuentos_x_valoracion.id_interventor,
            DATE_FORMAT(descuentos_x_valoracion.fecha,'%Y-%m-%d') as fecha,
            descuentos_x_valoracion.id_contrato,
            descuentos_x_valoracion.fecha_solicitud_aclaracion,
            descuentos_x_valoracion.id_radicado_osa,
            descuentos_x_valoracion.fecha_requerimiento,
            descuentos_x_valoracion.estado,
            descuentos_x_valoracion.id_radicado_orq,
            descuentos_x_valoracion.fecha_envio_evidencia,
            descuentos_x_valoracion.subsanacion,
            tipo_descuento.tipo_descuento as nombre_descuento,
            detalle_tipo_descuento.tipo_descuento,
            detalle_tipo_descuento.descuento
            FROM descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
            WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador and
            descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
            detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
            descuentos_x_valoracion.id_interventor = '$id_interventor' and
            tipo_descuento.id = detalle_tipo_descuento.tipo_descuento
            ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
          }else {
            $descuentos = mysql_query(("
            SELECT
            descuentos_x_valoracion.id_sede as nombre_sede,
            descuentos_x_valoracion.id,
            prestador.nombre_prestador,
            modalidad.nombre_modalidad,
            descuentos_x_valoracion.id_interventor,
            DATE_FORMAT(descuentos_x_valoracion.fecha,'%Y-%m-%d') as fecha,
            descuentos_x_valoracion.id_contrato,
            descuentos_x_valoracion.fecha_solicitud_aclaracion,
            descuentos_x_valoracion.id_radicado_osa,
            descuentos_x_valoracion.fecha_requerimiento,
            descuentos_x_valoracion.estado,
            descuentos_x_valoracion.id_radicado_orq,
            descuentos_x_valoracion.fecha_envio_evidencia,
            descuentos_x_valoracion.subsanacion,
            tipo_descuento.tipo_descuento as nombre_descuento,
            detalle_tipo_descuento.tipo_descuento,
            detalle_tipo_descuento.descuento
            FROM descuentos_x_valoracion,prestador, modalidad, detalle_tipo_descuento, tipo_descuento
            WHERE descuentos_x_valoracion.id_prestador = prestador.id_prestador and
            descuentos_x_valoracion.id_modalidad = modalidad.id_modalidad and
            detalle_tipo_descuento.id = descuentos_x_valoracion.tipo_descuento and
            tipo_descuento.id = detalle_tipo_descuento.tipo_descuento
            ORDER BY prestador.nombre_prestador, descuentos_x_valoracion.fecha"), $conexion);
          }



        }else {
          //$descuentos =
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

        <title>Descuentos Valoración</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/datepicker.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="css/jumbotron-narrow.css" rel="stylesheet">

        <!-- JavaScript para los filtros de las tablas -->

        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/jquery.tablesorter.js"></script>
        <script src="js/jquery.tablesorter.widgets.js"></script>
        <script src="js/bootstrap-datepicker.js"></script>
        <script src="js/bootstrap.js"></script>
        <link href="css/theme.default.css" rel="stylesheet">

        <!-- Para validacion de campos -->
        <script src="js/parsley.js"></script>
        <!-- Estilos menú principal -->
        <link rel="stylesheet" href="css/estilos.css">

        <!-- Material Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
    <?php include("menu.php"); ?>
    <div class="container">

      <div class="jumbotron">
        <h2>Seleccione el contrato a descontar</h2>

        <form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">
          <input type="hidden" name="caso" value="24">
          <select data-parsley-min="1" class="form-control" name="id_prestador" id="select1">
            <option value="0" required>Seleccione el Prestador...</option>
          </select>

          <select data-parsley-min="1" class="form-control" name="id_modalidad" id="select2">
            <option value="0" required>Seleccione la Modalidad...</option>
          </select>

          <select data-parsley-min="1" class="form-control" name="id_contrato" id="select3">
            <option value="0" required>Seleccione el Contrato...</option>
          </select>
          <select data-parsley-min="1" class="form-control" name="tipo_descuento" id="select4">
            <option value="0" required>Tipo Descuento...</option>
          </select>
          <select class="form-control" name="id_sede" id="select5">
            <option value="0">Seleccione la Sede...</option>
          </select>

          <input type="text" required name="fecha_descuento" class="form-control" placeholder="Fecha Descuento" id="FechaDescuento">
          <button  class="btn btn-pascual" type="submit">Guardar</button>
        </form>
      </div>
    </div> <!-- /container -->
    <?php if (mysql_num_rows($descuentos) > 0){ ?>
      <div class="boton-modal-prestador" id="AbrirModal">
        <i class="material-icons" title="Descargar descuentos por sede">&#xE84F;</i>
      </div>
      <div class="modal fade" id="ModalReporte" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Reporte Sedes</h4>
            </div>
            <div class="modal-body">
              <!-- <div class="form-group">
                <label>Seleccione el prestador</label>
                <select class="form-control" id="id_prestador">
                </select>
              </div> -->
              <div class="form-group">
                <label>Fecha Inicial</label>
                <input type="text" class="form-control" id="fecha_inicial_modal">
              </div>
              <div class="form-group">
                <label>Fecha Final</label>
                <input type="text" class="form-control" id="fecha_final_modal">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary" id="GenerarReporte">Generar Reporte</button>
            </div>
          </div>
        </div>
      </div>


      <div class="boton-cargar-precio">
        <i class="material-icons" title="Descargar descuentos valoración">&#xE2C4;</i>
      </div>
      <div class="contenedor-precio-carga"></div>
      <div class="card-precio-carga">
        <div class="col-md-12">
          <div class="col-md-5">
            <div class="form-group">
              <label for="exampleInputEmail1">Fecha Inicial</label>
              <input type="text" class="form-control datepicker" id="fecha_inicial">
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label for="exampleInputEmail1">Fecha Final</label>
              <input type="text" class="form-control datepicker" id="fecha_final">
            </div>
          </div>
          <div class="col-md-2">
            <div style="margin-top:100%; cursor:pointer;" id="descargar">
              <i class="material-icons">&#xE2C4;</i>
            </div>
          </div>
        </div>
      </div>
      <div class="">
        <div class="bs-docs-section" align="center">
          <h2 id="tables-example">Consultar Descuentos por Valoraciones</h2>

        </div>
        <div class="col-md-12 informacion" style="margin-left:14%;">
          <form class="" action="descuentosValoracion.php" method="get">
            <div class="col-md-10">
              <div class="col-md-5">
                <select class="form-control col-md-" name="id_contrato">
                  <option value="0">Todos</option>
                  <?php  	while($row=mysql_fetch_assoc($contrato_x_descuentos)){ ?>
                    <option  value="<?php  echo  $row['id_contrato']; ?>"><?php echo  $row['id_contrato']." - ".$row["nombre_prestador"]; ?></option>	<?php 	}	?>
                  </select>
                </div>
                <div class="col-md-1">
                  <input type="submit" name="" class="btn btn-pascual" value="Consultar">
                </div>
                <div class="col-md-3">
                  <input type="text" class="form-control" id="filtrar" placeholder="Buscar">
                </div>
              </div>
            </form>
          </div>
          <br>
          <div>
            <br>
            <div class="col-md-12 informacion" style="margin-left:15%;">
              <form class="" action="inserts.php" method="post">
                <div class="panel-group col-md-8" id="" role="tablist" aria-multiselectable="true">
                  <?php
                  $i = 0;
                  while ($row = mysql_fetch_assoc($descuentos)) {
                    $i++;
                    ?>
                    <div class="panel panel-default busqueda">
                      <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title">


                          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo<?=$i?>" aria-expanded="false" aria-controls="collapseTwo">
                            <i class="glyphicon glyphicon-chevron-up arriba"></i>
                            <i class="glyphicon glyphicon-chevron-down abajo"></i>
                            <input type="hidden" class="id_descuento" value="<?php echo $row['id']; ?>">
                            <input type="hidden" class="tipo_descuento" value="<?php echo $row['tipo_descuento']; ?>">
                            <input type="hidden" class="descuento" value="<?php echo $row['descuento']; ?>">
                            <input type="hidden" class="id_contrato" value="<?php echo $row['id_contrato']; ?>">
                            <div class="col-md-1" style="float:right;">
                              <?php if ($id_grupo == 1 ||($id_componente == 7  && $id_grupo == 3)){ ?>
                                <?php if ($row["estado"] == 1){ ?>
                                  <i class="glyphicon glyphicon-ok subsanar" style="color:#00ff00"></i>
                                <?php }else{ ?>
                                  <i class="glyphicon glyphicon-ok subsanar"></i>
                                <?php } ?>
                                <i class="glyphicon glyphicon-remove eliminar"></i>
                              <?php } ?>

                            </div>
                            <?php echo $row["nombre_prestador"]." - ".$row["id_contrato"]; ?>
                          </a>
                        </h4>
                      </div>
                      <div id="collapseTwo<?=$i?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                          <table class="table table-bordered table-hover">
                            <input type="hidden" name="caso" value="27">
                            <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">

                            <tr>
                              <th colspan="3"><div style="text-align:center">INFORMACIÓN DEL DESCUENTO</div></th>
                            </tr>

                            <tbody>
                              <tr>
                                <td class="col-md-6">Prestador: <strong><?php echo$row["nombre_prestador"]; ?></strong></td>
                                <td class="col-md-6">Contrato: <strong><?php echo $row["id_contrato"]; ?></td>
                                </tr>
                                <tr>
                                  <td class="col-md-6">Modalidad: <strong><?php echo$row["nombre_modalidad"]; ?></strong></td>
                                  <td class="col-md-6">Tipo Desuento: <strong><?php echo $row["nombre_descuento"]; ?></td>
                                  </tr>
                                  <tr>
                                    <td class="col-md-6">Interventor: <strong><?php echo$row["id_interventor"]; ?></strong></td>
                                    <td class="col-md-6">Fecha Descuento: <input type="text" class="form-control datepicker" style="width:285px;" name="fecha_descuento[]" value="<?php echo$row["fecha"]; ?>"></td>
                                  </tr>
                                  <?php if ($row["tipo_descuento"] == 3 || $row["tipo_descuento"] == 4 || $row["tipo_descuento"] == 5){ ?>
                                    <td class="col-md-6">Nombre Sede: <strong><?php echo$row["nombre_sede"]; ?></strong></td>

                                  <?php } ?>
                                  <tr>
                                    <td class="col-md-6">Plazo Solicitud Aclaracion:<input type="text" class="form-control datepicker" name="fecha_solicitud_aclaracion[]" style="width:285px;" value="<?php if($row['fecha_solicitud_aclaracion']=="0000-00-00"){echo "";} else{echo $row['fecha_solicitud_aclaracion'];} ?>"></td>
                                    <td class="col-md-6">Radicado Solicitud Aclaracion:<input type="text" class="form-control"  name="id_radicado_osa[]" style="width:285px;" value="<?php echo $row['id_radicado_osa'];  ?>"></td>
                                  </tr>
                                  <tr>
                                    <td class="col-md-6">Plazo Requerimiento:<input type="text" class="form-control datepicker" name="fecha_requerimiento[]" style="width:285px;" value="<?php if($row['fecha_requerimiento']=="0000-00-00"){echo "";} else{echo $row['fecha_requerimiento'];} ?>"></td>
                                    <td class="col-md-6">Radicado Requerimiento:<input type="text" class="form-control" name="id_radicado_orq[]"  style="width:285px;" value="<?php echo $row['id_radicado_orq'];  ?>"></td>
                                  </tr>
                                  <tr>
                                    <td class="col-md-6">Envío Evidencias:<input type="text" class="form-control datepicker" name="fecha_envio_evidencia[]" style="width:285px;" value="<?php if($row['fecha_envio_evidencia']=="0000-00-00"){echo "";} else{echo $row['fecha_envio_evidencia'];} ?>"></td>
                                    <td class="col-md-6">
                                      <!-- <div class="col-md-8" style="margin-left:-3%;"> -->
                                      Subsanación
                                      <select class="form-control" name="subsanacion[]" style="width:285px;">
                                        <option value="<?php echo $row['subsanacion'];  ?>"><?php if($row['subsanacion'] == 1){echo "SI";}else{echo "NO";} ?></option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                      </select>
                                      <!-- </div> -->
                                      <!-- <div class="col-md-2">
                                      <input type="hidden" class="id_descuento" value="<?php echo $row['id']; ?>">
                                      <input type="hidden" class="tipo_descuento" value="<?php echo $row['tipo_descuento']; ?>">
                                      <input type="hidden" class="descuento" value="<?php echo $row['descuento']; ?>">
                                      <img src="images/chulo.jpg" width="24px" height="24px" style=" margin-left:0%; margin-top:37%; cursor:pointer;" value="1" class="subsanar">
                                    </div> -->
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                    <br><br>
                    <center><button class="btn btn-pascual" type="submit">Modificar Descuentos</button></center>
                  </div>
                </form>
                <?php if (isset($_GET['id_contrato'])){
                  if ($_GET['id_contrato'] != 0) {?>
                    <div class="col-md-2">
                      <div class="panel panel-default">
                        <div class="panel-heading"><center>Calidad</center></div>
                        <div class="panel-body">
                          <?php
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
                          while($row=mysql_fetch_assoc($porcentaje_calidad)){

                            if($row['promedio_componente']!=""){
                              $promedio_componente = round($row['promedio_componente'],4)*100;
                              $total = $promedio_componente - $descuento;
                              ?>
                              <center><h4><?php echo $total ?>%</h4></center>
                              <?php
                            }
                          }//end While
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="panel panel-default">
                        <div class="panel-heading"><center>Cumplimiento</center></div>
                        <div class="panel-body">
                          <?php
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
                          while($row=mysql_fetch_assoc($porcentaje_cumplimiento)){

                            if($row['promedio_componente']!=""){
                              $promedio_componente1 = round($row['promedio_componente'],4)*100;
                              $total1 = $promedio_componente1 + $descuento1;
                              if ($total1 > 100) {
                                $total1 = 100;
                              }
                              ?>
                              <center><h4><?php echo $total1 ?>%</h4></center>
                              <?php
                              //$promedio_cumplimiento = round($row['promedio_componente'],4)*100;

                            }
                          }//end While

                          ?>
                        </div>
                      </div>
                    </div>
                  <?php }
                }?>
              </div>
            </div>
          </div>
        <?php }else {?>
          <div class="page-header">

            <div class="alert alert-warning" role="alert">
              <center><strong>¡Advertencia!</strong> No hay registros que coincidan con los filtros seleccionados.</center>
            </div>
          </div>
        <?php  }
        include "cerrarconexion.php";
        ?>
        <div class="container">

          <div class="footer">
            <center> <p> &copy; 2020 Sistema de Información de la interventoría Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

            </div>

          </div> <!-- /container -->


          <!-- Bootstrap core JavaScript-->

        </body>
        <script>

        $("#AbrirModal").click(function(){
          $("#ModalReporte").modal("show");
        });

        $(".boton-cargar-precio").click(function(){
          $(".card-precio-carga").fadeIn();
          $(".contenedor-precio-carga").fadeIn();
        });

        $(".contenedor-precio-carga").click(function(){
          $(".card-precio-carga").fadeOut();
          $(".contenedor-precio-carga").fadeOut();
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

        $("#filtrar").keyup(function(){
          var rex = new RegExp($(this).val(), 'i');
          $(".busqueda").hide();
          $(".busqueda").filter(function(){
            if (rex.test($(this).text()) < 0) {
              $(this).parent().parent().parent().parent().parent().addClass("hidden");
            } else {
              $(this).parent().parent().parent().parent().parent().removeClass("hidden");
            }
            return rex.test($(this).text());
          }).show();
        });

        $(".arriba").hide();
        $(".collapsed").click(function(){
          var visible = $(this).children('.abajo').is(":visible");
          if (visible == true) {
            $(this).children('.abajo').hide();
            $(this).children('.arriba').show();
          }else {
            $(this).children('.abajo').show();
            $(this).children('.arriba').hide();
          }

        });

        $("#descargar").click(function(){
          var caso = "12";
          var fecha_inicial = $("#fecha_inicial").val();
          var fecha_final = $("#fecha_final").val();
          window.open("http://192.168.2.4/2017/stella/download.php?caso="+caso+"&fecha_inicial="+fecha_inicial+"&fecha_final="+fecha_final+"&id_componente="+0);
          //window.open("http://localhost/stella/download.php?caso="+caso+"&fecha_inicial="+fecha_inicial+"&fecha_final="+fecha_final+"&id_componente="+0);
        });

        $("#GenerarReporte").click(function(){
          var caso = "16";
          //var id_prestador = $("#id_prestador").val();
          var fecha_inicial = $("#fecha_inicial_modal").val();
          var fecha_final = $("#fecha_final_modal").val();
          window.location = "http://192.168.2.4/2017/stella/download.php?caso="+caso+"&fecha_inicial="+fecha_inicial+"&fecha_final="+fecha_final;
          //window.location = "http://localhost/stella/download.php?caso="+caso+"&fecha_inicial="+fecha_inicial+"&fecha_final="+fecha_final;
        });

        $(".eliminar").click(function(){
          var caso = "23";
          var id_descuento = $(this).parent().parent().children(".id_descuento").val();
          var id_contrato = $(this).parent().parent().children(".id_contrato").val();
          $.post("deletes.php",{ caso: caso, id_descuento: id_descuento},
          function(resultado)
          {
            location.reload();
          });
        });

        $(".subsanar").click(function(){
          var caso = "28";
          var id_descuento = $(this).parent().parent().children(".id_descuento").val();
          var tipo_descuento = $(this).parent().parent().children(".tipo_descuento").val();
          var descuento = $(this).parent().parent().children(".descuento").val();
          var id_contrato = $(this).parent().parent().children(".id_contrato").val();
          $.post("inserts.php",{caso: caso, id_descuento: id_descuento, tipo_descuento: tipo_descuento, descuento: descuento},
          function(resultado){
            window.location.href = "descuentosValoracion.php?id_contrato="+id_contrato;
          });
        });

        // $(".arriba").click(function(){
        //   $(this).hide();
        //   $(this).parent().children(".abajo").show();
        // });

        </script>
        </html>
        <script type="text/javascript">
        $(function(){
          $(document).ready(function(){

            $('.datepicker').datepicker({
              format: 'yyyy-mm-dd'
            });

            cargar_menu1();
            $("#select1").change(function(){cargar_menu2();});
            $("#select2").change(function(){cargar_menu3();});
            $("#select2").attr("disabled",true);
            $("#select3").attr("disabled",true);
            $("#select4").attr("disabled",true);
            <!-- Fecha Datepicker-->
            $('#FechaDescuento').datepicker({
              format: 'yyyy-mm-dd'
            });
            $('#fecha_inicial_modal').datepicker({
              format: 'yyyy-mm-dd'
            });
            $('#fecha_final_modal').datepicker({
              format: 'yyyy-mm-dd'
            });
          });

          <?php
          if (isset($_GET['id_contrato'])) {
            $id_contrato = $_GET['id_contrato'];
            if ($id_contrato!=0) {?>
              $(".informacion").css("margin-left","0%");
              <?php
            }
          }
          ?>
          function cargar_menu1()
          {
            $.get("lib/combo-configevaluaciones/option-select1.php", function(resultado){
              if(resultado == false)
              {
                alert("Error");
              }
              else
              {
                $('#select1').append(resultado);
                $("#id_prestador").append(resultado);
              }
            });
          }

          function cargar_menu2()
          {
            var code = $("#select1").val();
            $.get("lib/combo-configevaluaciones/option-select2.php", { code: code },
            function(resultado)
            {
              if(resultado == false)
              {
                alert("Error");
              }
              else
              {
                $("#select2").attr("disabled",false);
                document.getElementById("select2").options.length=1;
                $('#select2').append(resultado);
              }
            }

          );
        }
      });

      function cargar_menu3()
      {
        var code = $("#select1").val();
        var code2 = $("#select2").val();
        $.get("lib/combo-configevaluaciones/option-select4.php?", { code: code, code2: code2 },
        function(resultado)
        {
          console.log(resultado);
          if(resultado == false)
          {
            alert("Error");
          }
          else
          {
            $("#select3").attr("disabled",false);
            document.getElementById("select3").options.length=1;
            $('#select3').append(resultado);
          }
        }
      );

      $.get("lib/combo-configevaluaciones/option-select5.php", {code: code2},
      function(resultado){
        if (resultado == false) {
          alert("Error");
        }else {
          $("#select4").attr("disabled",false);
          document.getElementById("select4").options.length=1;
          $('#select4').append(resultado);
        }
      });

    }


    $("#select5").hide();
    $("#select4").change(function(){
      var tipo_descuento = $('select[name="tipo_descuento"] :selected').attr('class')
      console.log(tipo_descuento);
      if (tipo_descuento == "RASTREO PATOGENO" || tipo_descuento == "RASTREO NO PATOGENO" || tipo_descuento == "ETA") {
        $("#select5").show();
        var id_contrato = $("#select3").val();
        $.get("lib/combo-configevaluaciones/option-select6.php", {code: id_contrato},
        function(resultado){
          $("#select5").attr("disabled",false);
          document.getElementById("select5").options.length=1;
          $('#select5').append(resultado);
        });
      }else {
        $("#select5").hide();
      }
    });


    </script>
    <?php
  }
  ?>
