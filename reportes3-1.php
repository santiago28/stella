<?php session_start();
if ($_SESSION['login'])
{

  include "conexion.php";

  //Variables Globales
  $id_grupo=$_SESSION["grupo"];
  $id_componente=$_SESSION["componente"];
  $nombre=$_SESSION["nombre_usuario"];
  $fotoperfil = $_SESSION["fotoperfil"];


  //Variables recibidas via GET para la consulta
  $componente_selected=$_GET['componente_selected'];
  $id_tema=$_GET['id_tema'];
  $id_mes=$_GET['id_mes'];
  $msg=$_GET['msg'];

  if($componente_selected==0){} //Condicion para cuando el get sea 0
    else{

      $titulos= mysql_query(("        SELECT
        nombre_componente,
        nombre_tema
        FROM
        tema,componente
        WHERE
        tema.id_componente=componente.id_componente and
        tema.id_componente='$componente_selected' and
        tema.id_tema='$id_tema' and
        tema.estado='1'
        GROUP BY
        nombre_componente,nombre_tema
        "),$conexion);

        $observaciones_creadas= mysql_query(("
        SELECT
        semaforo.id_semaforo,
        semaforo.porc_calidad,
        semaforo.porc_deb_proc,
        semaforo.porc_descuento,
        semaforo.id_contrato,
        prestador.nombre_prestador,
        modalidad.abr_modalidad,
        semaforo.descripcion_incumplimiento_grave,
        semaforo.logros_prestador,
        semaforo.dificultades_prestador,
        semaforo.debido_proceso,
        semaforo.asistencia_tecnica,
        semaforo.otras_observaciones,
        semaforo.id_interventor,
        semaforo.incumplimiento_grave,
        semaforo.gi1,
        semaforo.gi2,
        semaforo.gi3,
        semaforo.gi4,
        semaforo.gi5,
        semaforo.gi6,
        semaforo.gi7,
        semaforo.estado
        FROM
        semaforo,prestador,modalidad
        WHERE
        semaforo.id_prestador=prestador.id_prestador and
        semaforo.id_modalidad=modalidad.id_modalidad and
        semaforo.id_componente='$componente_selected' and
        semaforo.id_tema='$id_tema' and
        semaforo.id_mes='$id_mes'

        ORDER BY
        semaforo.id_contrato

        "),$conexion);

      } //End Else
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

        <title>Semáforo Gesti&oacute;n Institucional</title>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="css/jumbotron-narrow.css" rel="stylesheet">
        <link href="css/theme.default.css" rel="stylesheet">
        <!-- bootstrap widget theme -->
        <link href="css/theme.bootstrap.css" rel="stylesheet" >
        <!-- JavaScript para los filtros de las tablas -->
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/jquery.tablesorter.js"></script>
        <script src="js/jquery.tablesorter.widgets.js"></script>
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
      <?php
      if($msg!=0){
        if($msg==1){ ?>

          <div id="mensaje" class="alert alert-success" role="alert">
            <h5 align="center">
              <strong>¡Felicitaciones!</strong>
              El registro ha sido Insertado/Actualizado exitosamente.<button type="button" class="close" aria-hidden="true">x</button>
            </h5>
          </div>
        <?php   } else {  ?>
          <div id="mensaje" class="alert alert-danger" role="alert">
            <h5 align="center"><strong>¡Advertencia!</strong>
              Acaba de Eliminar un registro o se generaron inconvenientes al realizar la transacción.
              <button type="button" class="close" aria-hidden="true">x</button>
            </h5>
          </div>
        <?php   } // End else msg=2
      } //End msg=0
      if($componente_selected!=0){ //1er if

        $numrows= 1; //mysql_num_rows($observaciones_creadas);
        if ($numrows == 0){
          ?>
          <div align="center" class="page-header">
            <div class="alert alert-warning" role="alert">
              <strong>¡Advertencia!</strong> No hay registros que coincidan con los filtros seleccionados.
            </div>
          </div>
          <?php //Cierro else interno
        } else{
          $row=mysql_fetch_assoc($titulos); ?>

          <div align="center" class="bs-docs-section">
            <h2 id="tables-example">Semáforo del Estado de la Prestación del Servicio</h2>
            <h4 id="tables-example">Componente Técnico: <strong><?php echo $row['nombre_tema']  ?></strong></h4>
            <h3 id="tables-example">Mes: <strong><?php echo $id_mes;  ?></strong></h3>
            <h3 id="tables-example">Cantidad Contratos: <span id="cantidad" ></span></h3>
            <a href="reportes3-2.php?componente_selected=4&id_tema=401&msg=0&id_mes=<?php echo $id_mes; ?>">Exportar a Excel</a>
          </div>
          <div class="footer">
          </div>

        </div> <!-- /container -->

        <form class="form-signin" role="form" name="formulario2" METHOD="post" action="inserts.php">
          <input type="hidden" name="caso" value="17">
          <input type="hidden" name="componente_selected" value="<?php echo $componente_selected; ?>">
          <input type="hidden" name="id_tema" value="<?php echo $id_tema; ?>">
          <input type="hidden" name="id_mes" value="<?php echo $id_mes; ?>">


          <table align="center" class="table table-bordered table-hover" id='table' style="width: 95%">
            <thead>
              <tr>
                <th class="info">#</th>
                <th class="info">Contrato</th>
                <th class="info">Prestador</th>
                <th class="info">Modalidad</th>
                <th class="info">Interventor</th>
                <th class="info">Porcentaje Final</th>
                <th class="info">Porcentaje Inicial</th>
                <?php
                $sqlcabeceras = "SELECT criterio, id FROM criterio WHERE idTema = ".$id_tema.";";
                $respcabeceras = mysql_query($sqlcabeceras,$conexion);
                $listadoPreg = array();
                while ($cabeceras = mysql_fetch_assoc($respcabeceras)) {
                  echo '<th class="info">'.$cabeceras['criterio'].'</th>';
                  $listadoPreg[] = $cabeceras['id'];
                } ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $sqlnivel1 = "SELECT s.id_semaforo, s.id_contrato, s.id_prestador, p.nombre_prestador, s.id_modalidad, m.nombre_modalidad, s.id_componente, c.nombre_componente, s.id_tema, i.id_interventor, s.id_mes FROM semaforo s
              LEFT JOIN prestador p ON (s.id_prestador=p.id_prestador)
              LEFT JOIN modalidad m ON (m.id_modalidad=s.id_modalidad)
              LEFT JOIN componente c ON (s.id_componente=c.id_componente)
              LEFT JOIN bddelfi_2016.contrato_x_interventor i ON (s.id_contrato=i.id_contrato)
              WHERE s.id_semaforo in (SELECT idSemaforo FROM calificacion GROUP BY idSemaforo) AND s.id_mes = $id_mes AND s.id_tema = $id_tema; ";
              $respnivel1 = mysql_query($sqlnivel1, $conexion);
              $cantidad = mysql_num_rows($respnivel1);
              while ($nivel1 = mysql_fetch_assoc($respnivel1)) {
                $sumac=0; $sumat=0;$castigo=0;$aux=0;
                echo '<tr>';
                echo '<td class="active">'.$nivel1['id_semaforo'].'</td>';
                echo '<td class="active"><a href="evaluarsemaforo.php?msg=0&id_mes='.$id_mes.'&id_tema=401&id_contrato='.$nivel1['id_contrato'].'" target="_blank">'.$nivel1['id_contrato'].'</a></td>';
                echo '<td class="active">'.$nivel1['nombre_prestador'].'</td>';
                echo '<td class="active">'.$nivel1['nombre_modalidad'].'</td>';
                echo '<td class="active">'.$nivel1['id_interventor'].'</td>';
                $sqlnivel2="SELECT ca.idCriterio AS numero, cr.criterio AS pregunta, ca.evaluacion AS respuesta, cr.porcentaje AS porcentaje, cr.tipo, cr.excluir  FROM calificacion ca INNER JOIN criterio cr ON (ca.idCriterio=cr.id) WHERE ca.idSemaforo =".$nivel1['id_semaforo']." AND cr.idTema=".$id_tema;
                $respnivel2 = mysql_query($sqlnivel2,$conexion);
                $cantPreg = mysql_num_rows($respnivel2);
                while($nivel2 = mysql_fetch_assoc($respnivel2)) {
                  if ($nivel2['tipo'] == "Contar"){
                    switch($nivel2['respuesta']) {
                      case "C":
                      $sumac = $sumac + $nivel2['porcentaje'];
                      $sumat = $sumat + $nivel2['porcentaje'];
                      break;
                      case "NC":
                      $sumat = $sumat + $nivel2['porcentaje'];
                      break;
                    }
                  } else if ($nivel2['tipo'] == "Castigar"){
                    if ($nivel2['respuesta']=="NC") {
                      $castigo = $castigo + $nivel2['porcentaje'];
                    }
                  }
                  $resto[$nivel2['numero']] = $nivel2['respuesta'];

                }
                $operacion = ($sumac/$sumat)*100;
                $descuento = (($sumac/$sumat)+$castigo)*100;
                if ($descuento < 60) {
                  $fondo='background-color:#ED1717; font-weight: bold; color:#fff;';
                } else if ($descuento < 80) {
                  $fondo='background-color:orange;';
                } else if ($descuento < 95) {
                  $fondo='background-color:#5EFF61;';
                } else {
                  $fondo='background-color:green;';
                }
                if ($operacion < 60) {
                  $fondo1='background-color:#ED1717; font-weight: bold; color:#fff;';
                } else if ($operacion < 80) {
                  $fondo1='background-color:orange;';
                } else if ($operacion < 95) {
                  $fondo1='background-color:#5EFF61;';
                } else {
                  $fondo1='background-color:green;';
                }
                echo '<td class="active" style="'.$fondo.'" align="center">'.number_format($descuento,2).'%</td>';
                echo '<td class="active" style="'.$fondo1.'" align="center">'.number_format($operacion,2).'%</td>';
                //for ($i=1;$i<23;$i++){
                foreach ($listadoPreg as $i) {
                  if ($resto[$i]=="Abierto") {
                    $fondo3='background-color:#ED1717; font-weight: bold; color:#fff;';
                    $texto = "Abierto";
                  } else if ($resto[$i]=="NC") {
                    $fondo3='background-color:#ED1717; font-weight: bold; color:#fff;';
                    $texto="No Cumple";
                  } else if ($resto[$i]=="C"){
                    $fondo3='background-color:#5EFF61;';
                    $texto = "Cumple";
                  } else if ($resto[$i]=="NA") {
                    $fondo3='background-color:orange;';
                    $texto = "No Aplica";
                  } else if ($resto[$i]=="Cerrado"){
                    $fondo3='background-color:green;';
                    $texto = $resto[$i];
                  } else {
                    $fondo3="";
                    $texto="Sin Info - Paso Algo averiguar";
                  }

                  echo '<td class="active" style="'.$fondo3.'">'.$texto.'</td>';
                }
                echo '</tr>';
              }

              mysql_free_result($respnivel1);
              ?>

            </tbody>
          </table>
        </form>
        <br>
        <br>


        <?php

      }//Cierro else


    }//Cierro 1er if


  } ?>


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
<script>
$(function() {

  $("#cantidad").html("<?php echo $cantidad; ?>");

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
  $("table").tablesorter({
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

});
</script>
