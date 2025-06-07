<?php

session_start();
if ($_SESSION['login'])
{
  $interventor = $_SESSION['login'];
  include "conexion.php";
  $id_grupo=$_SESSION["grupo"];
  $id_componente=$_SESSION["componente"];
  $nombre=$_SESSION["nombre_usuario"];
  $fotoperfil = $_SESSION["fotoperfil"];
  $msg=$_GET['msg'];
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $select = "SELECT * FROM hist_cambios WHERE id = $id;";
    $resource = mysql_query($select, $conexion);
    $registro = mysql_fetch_assoc($resource);
  }

  //Consulta de actas
  if ($id_grupo == 2) {
    $actas_usuario = mysql_query(("
    SELECT id_acta, id_interventor
    FROM acta
    WHERE id_interventor = '$interventor'"), $conexion);
  }else {
    $actas_usuario = mysql_query(("
    SELECT id_acta, id_interventor
    FROM acta
    WHERE id_componente = '$id_componente'"), $conexion);
  }


  //Consultas de acuerdo al perfil
  if($id_grupo==1){
    //Grupo Administrador
    $historial= mysql_query(("
    SELECT h.*, m.nombre_modalidad,
    c.nombre_componente,
    p.descripcion_pregunta
    FROM hist_cambios h LEFT JOIN acta a ON (h.id_acta=a.id_acta)
    LEFT JOIN modalidad m ON (a.id_modalidad=m.id_modalidad)
    LEFT JOIN componente c ON (a.id_componente=c.id_componente) LEFT JOIN pregunta p ON (h.id_pregunta=p.id_pregunta)
    WHERE ejecutado NOT LIKE 'SI'"),$conexion);
  }
  else{
    //Grupo Members
    $historial= mysql_query(("
    SELECT h.*, m.nombre_modalidad,
    c.nombre_componente,
    p.descripcion_pregunta
    FROM hist_cambios h LEFT JOIN acta a ON (h.id_acta=a.id_acta)
    LEFT JOIN modalidad m ON (a.id_modalidad=m.id_modalidad)
    LEFT JOIN componente c ON (a.id_componente=c.id_componente)
    LEFT JOIN pregunta p ON (h.id_pregunta=p.id_pregunta)
    WHERE interventor = '$interventor'"),$conexion);
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

    <title>Configuraciones</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/datepicker.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">
    <link href="css/theme.default.css" rel="stylesheet">

    <!-- bootstrap widget theme -->
    <link href="css/theme.bootstrap.css" rel="stylesheet" >

    <link rel="stylesheet" href="css/select2.css">
    <!-- Estilos menú principal -->
    <link rel="stylesheet" href="css/estilos.css">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- JavaScript para los filtros de las tablas -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery.tablesorter.js"></script>
    <script src="js/jquery.tablesorter.widgets.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/select2.js">

    </script>

    <!-- Para validacion de campos -->
    <script src="js/parsley.js"></script>
    <script>


    function ocultarxcambio(){ //eliminar
      document.getElementById('caso').value = 2;
      document.getElementById('cambio').hidden=true;
      document.getElementById('fecha').hidden=true;
    }

    function mostrarxcambio(){ //cambiar
      document.getElementById('caso').value = 3;
      document.getElementById('cambio').hidden=false;
      document.getElementById('fecha').hidden=true;
    }

    function mostrarxfecha() { //fecha
      document.getElementById('caso').value = 5;
      document.getElementById('fecha').hidden=false;
    }
    function habilitarxacta() {
      document.getElementById('caso').value = 4;
    }

    function revisaracta(numacta) {
      var $mesgacta = $('#mesgacta');
      var interv = $('interventor').value;
      var revisa = {};
      revisa.url = "lib/newfunctions.php";
      revisa.method = "POST";
      revisa.data = ({id_acta:numacta, interventor:interv, accion:"Listar"});
      $.ajax(revisa)
      .done(function(data){
        if (data.indexOf("correcto")>=0) {
          dataArray = data.split(";");
          $('#contrato').val(dataArray[1]);
          $mesgacta.html(dataArray[0]);
          $mesgacta.css('border', '1px solid #0f0');
          $mesgacta.css('background-color', '#05ff05');
        } else {
          $mesgacta.css('border', '1px solid #e80808');
          $mesgacta.css('background-color', '#e80808');
          $mesgacta.html('Error, el N&uacute;mero del Acta no existe');
          $('#acta').value="";
          $('#acta').focus();
        }
      })
      .fail(function(data) {

      });
    }
    </script>
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
    if($msg==1){


      ?>

      <div id="mensaje" class="alert alert-success" role="alert">
        <h5 align="center"><strong>¡Felicitaciones!</strong>
          El registro ha sido Modificado/Eliminado exitosamente.<button type="button" class="close" aria-hidden="true">x</button></h5>
        </div>
        <?php
      }
      else{
        ?>

        <div id="mensaje" class="alert alert-danger" role="alert">
          <h5 align="center"><strong>¡Advertencia!</strong>
            Acaba de Eliminar un registro o se generaron inconvenientes al realizar la transacción.<button type="button" class="close" aria-hidden="true">x</button></h5>
          </div>
          <?php
        } // End else msg=2
      } //End msg=0
      ?>






      <div class="jumbotron">
        <h2>Portal Administrativo</h2>
        <h5>Aqu&iacute; podr&aacute;s cambiar o eliminar una Acta</h5>

        <form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="lib/newfunctions.php">
          <?php if (isset($id)) {
            switch($registro['accion']) {
              case 'Eliminar':
              $caso = 2;
              break;
              case 'Cambiar':
              $caso = 3;
              break;
              case 'Habilitar':
              $caso = 4;
              break;
              case 'Fecha':
              $caso = 5;
              break;
            }
          }	?>
          <input type="hidden" id="caso" name="caso" value="<?php if(isset($caso)){ echo $caso; } ?>">
          <input type="hidden" id="contrato" name="contrato" value="">
          <input type="hidden" id="contrato" name="id_componente" value="<?php echo $id_componente; ?>">
          <!-- <br>N&uacute;mero de Acta -->
          <!-- <input name="id_acta" type="text" class="form-control" id="acta" placeholder="No. de Acta" onBlur="revisaracta(this.value);" data-parsley-required ><div id="mesgacta"></div> -->
          <select class="form-control" name="id_acta" id="id_acta">
            <option value="0">Seleccione un acta...</option>
            <?php while ($row = mysql_fetch_array($actas_usuario)) {?>
              <option value="<?php echo $row["id_acta"]; ?>" ><?php echo $row["id_acta"]; ?></option>
            <?php } ?>
          </select>
          <p><input name="accion" type="radio" class="" id="Eliminar" placeholder="Accion a realizar" onClick="ocultarxcambio();" value="Eliminar" data-parsley-required>Eliminar
            <input name="accion" type="radio" class="" id="Cambiar" placeholder="Accion a realizar" onClick="mostrarxcambio();" value="Cambiar" data-parsley-required>Cambiar
            <input name="accion" type="radio" class="" id="Habilitar" placeholder="Accion a realizar" onClick="ocultarxcambio();" value="Habilitar">Habilitar
            <input name="accion" type="radio" class="" id="Fecha" placeholder="Accion a realizar" onClick="mostrarxfecha();" value="Fecha">Fecha</p>
            <div id="cambio" hidden="true">
              Tema
              <select id="tema" name="tema" class="form-control">
                <option value="">Seleccione una opci&oacute;n</option>
                <?php

                ?>
              </select>
              SubTema
              <select id="subtema" name="subtema" class="form-control">
                <option value="">Seleccione una opci&oacute;n</option>
                <?php

                ?>
              </select>
              Numero de la Pregunta
              <select id="preg" name="preg" class="form-control">
                <option value="">Seleccione una opci&oacute;n</option>
                <?php

                ?>
              </select>
              <!-- <input class="form-control" type="number" name="preg" id="preg" placeholder="Numero de la evaluaci&oacute;n" > -->
              Valor Actual
              <select class="form-control" name="respini" id="selectini">
                <option id="option1_js" value="0" required>Seleccione el valor actual...</option>
                <option value="1">Cumple</option>
                <option value="2">Cumple Parcial</option>
                <option value="3">No Cumple</option>
                <option value="4">No Subsanable</option>
                <option value="5">No Aplica</option>
              </select>
              Nuevo Valor
              <select class="form-control" name="respfin" id="selectfin">
                <option id="option1_js" value="0" required>Seleccione el nuevo valor...</option>
                <option value="1">Cumple</option>
                <option value="2">Cumple Parcial</option>
                <option value="3">No Cumple</option>
                <option value="4">No Subsanable</option>
                <option value="5">No Aplica</option>
              </select>
            </div>
            <div id="fecha"> Nueva fecha
              <input type="text" name="fechaacta" id="fechaacta" class="form-control datepicker"></div>
              <br> Nombre Interventor
              <input class="form-control" readonly type="text" name="interventor" id="interventor" value="<?php echo $_SESSION['login']; ?>">
              <br> Motivo para cambio
              <textarea style="width:100%" class="form-control" name="motivo" id="motivo"></textarea>
              <br>
              <?php if (isset($id)){
                echo '<input type="text" name="aprobado" id="aprobado" value="">';
              }
              if ($interventor == "alaro.lean" OR $interventor == "jairo.sanchez") {
                echo '<input class="btn btn-pascual" type="submit" name="ejecutar" value="Ejecutar Acci&oacute;n">';
              }else if($id_grupo != 4) {
                echo '<input class="btn btn-pascual" type="submit" name="guardar" value="Enviar Acci&oacute;n">';
              } ?>



              <br>
              <br>
            </form>

          </div> <!-- /jumbotron -->

          <?php
          //SECCION HISTORIAL DE CAMBIOS

          if(@mysql_num_rows($historial) > 0){ ?>

            <div align="center" class="bs-docs-section">
              <h2 id="tables-example">Historial de Cambios</h2>
            </div>
            <div class="footer">
            </div>

          </div> <!-- /container -->

          <table align="center" class="table table-bordered table-hover" id='table' style="width: 80%">

            <thead>
              <tr>
                <th class="info">id</th>
                <th class="info">Contrato</th>
                <th class="info">Modalidad</th>
                <th class="info">Componente</th>
                <th class="info">Acta</th>
                <th class="info">Pregunta</th>
                <th class="info">Valor Anterior</th>
                <th class="info">Valor Inicial</th>
                <th class="info">Motivo</th>
                <th class="info">Acci&oacute;n</th>
                <th class="info">Interventor</th>
                <th class="info">Fecha Registro</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while($row = mysql_fetch_assoc($historial)){ ?>
                <tr>
                  <td class="active"><?php echo $row['id'];  ?></td>
                  <td class="active"><a href="corregirActas.php?msg=0&id=<?php echo $row['id'];  ?>" target="_self"><?php echo $row['id_contrato']; ?></a></td>
                  <td class="active"><?php echo $row['nombre_modalidad']; ?></td>
                  <td class="active"><?php echo $row['nombre_componente']; ?></td>
                  <!-- <td class="active"><a href="corregirActas.php?msg=0&id=<?php echo $row['id'];  ?>" target="_self"><?php echo $row['id_acta'];  ?></a></td> -->
                  <td class="active"><a href='imprimiractas.php?id_acta=<?php echo $row['id_acta'] ?>&msg=0' target="_blank"><?php echo $row['id_acta']?></a></td>
                  <td class="active"><textarea class="form-control" rows="3" cols="600" wrap="soft" readonly><?php echo $row['id_pregunta']." - ".$row['descripcion_pregunta'];  ?></textarea></td>
                  <td class="active"><?php echo $row['valor_ant'];  ?></td>
                  <td class="active"><?php echo $row['valor_nue']; ?></td>
                  <td class="active"><?php echo $row['motivo']; ?></td>
                  <td class="active"><?php echo $row['accion']; ?></td>
                  <td class="active"><?php echo $row['interventor']; ?></td>
                  <td class="active"><?php echo $row['fecha_reg']; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        <?php }

        else {

          ?>
          <div class="page-header">

            <div align="center" class="alert alert-warning" role="alert">
              <strong>¡Advertencia!</strong> No hay registros que coincidan con los filtros seleccionados.
            </div>
          </div>

          <?php
        }


        include "cerrarconexion.php"; ?>


        <div class="container">

          <div class="footer">
            <center> <p> &copy; 2024 Sistema de Información de la Supervisión de Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

            </div>

          </div> <!-- /container -->


          <!-- Bootstrap core JavaScript-->
          <script>
          $(function() {

            //Configuración calendario
            $('.datepicker').datepicker({
              format: 'yyyy-mm-dd'
            });

            <?php if (isset($id)) { ?>
              $('#contrato').val('<?php echo $registro['id_contrato']; ?>');
              $('#acta').val('<?php echo $registro['id_acta']; ?>');
              $('#preg').val('<?php echo $registro['id_pregunta']; ?>');
              var text1 = '<?php echo @$registro['valor_ant']; ?>';
              $("respini").filter(function() {
                return $(this).text() == text1;
              }).attr('selected', true);

              $('#respfin').val('<?php echo @$registro['valor_nue']; ?>');
              $('#motivo').val('<?php echo $registro['motivo']; ?>');
              <?php switch($registro['accion']) {
                case 'Eliminar':
                echo "$('#accion_1').attr('checked','checked');";
                break;
                case 'Cambiar':
                echo "$('#accion_2').attr('checked','checked');";
                break;
                case 'Habilitar':
                echo "$('#accion_3').attr('checked','checked');";
                break;
                case 'Fecha':
                echo "$('#accion_4').attr('checked','checked');";
                break;
              }	?>
              $('#interventor').val("<?php echo $registro['interventor']; ?>");
              $('#aprobado').val("<?php echo $registro['aprobado']; ?>");
              $('#cambio').show();
              $('#fecha').show();
              <?php } ?>

              <!--  Menus desplegables-->
              cargar_menu1();
              $("#tema").change(function(){cargar_menu2();});
              $("#subtema").change(function(){cargar_menu3();});
              $("#tema").attr("disabled",true);
              $("#subtema").attr("disabled",true);
              $("#preg").attr("disabled",true);
              $("#id_acta").select2();
            });

            $("#id_acta").change(function(){
              $("#tema").attr("disabled",false);
            });

            function cargar_menu1()
            {
              var code = "<?php echo $id_componente; ?>";
              $.get("lib/combo-cambiaractas/option-select1.php?", { code: code },
              function(resultado){
                if(resultado == false)
                {
                  alert("Error No existe tema asignado a este componente");
                }
                else
                {
                  $('#tema').append(resultado);
                }
              });
            }
            function cargar_menu2()
            {
              var code = "<?php echo $id_componente; ?>";
              var code1 = $("#tema").val();
              var code2 = $("#id_acta").val();
              $.get("lib/combo-cambiaractas/option-select2.php", { code: code, code1: code1, code2: code2 },
              function(resultado)
              {
                if(resultado == false)
                {
                  alert("Error");
                }
                else
                {
                  $("#subtema").attr("disabled",false);
                  document.getElementById("subtema").options.length=1;
                  $('#subtema').append(resultado);
                }
              }

            );
          }


          function cargar_menu3()
          {
            var code = "<?php echo $id_componente; ?>";
            var code1 = $("#tema").val();
            var code2 = $("#subtema").val();
            $.get("lib/combo-cambiaractas/option-select3.php?", { code: code, code1: code1, code2: code2 },
            function(resultado)
            {
              if(resultado == false)
              {
                alert("Error");
              }
              else
              {
                $("#preg").attr("disabled",false);
                document.getElementById("preg").options.length=1;
                $('#preg').append(resultado);
              }
            }
          );
        }





        <!-- Cerrar el boton emergente-->
        $('.close').click(function() {
          $(this).parent().parent().fadeOut();
        });



        <!-- Filtros para las tablas-->
        // $.extend($.tablesorter.themes.bootstrap, {
        //   // these classes are added to the table. To see other table classes available,
        //   // look here: http://twitter.github.com/bootstrap/base-css.html#tables
        //   table      : 'table table-bordered',
        //   caption    : 'caption',
        //   header     : 'bootstrap-header', // give the header a gradient background
        //   footerRow  : '',
        //   footerCells: '',
        //   icons      : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
        //   sortNone   : 'bootstrap-icon-unsorted',
        //   sortAsc    : 'icon-chevron-up glyphicon glyphicon-chevron-up',     // includes classes for Bootstrap v2 & v3
        //   sortDesc   : 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
        //   active     : '', // applied when column is sorted
        //   hover      : '', // use custom css here - bootstrap class may not override it
        //   filterRow  : '', // filter row class
        //   even       : '', // odd row zebra striping
        //   odd        : ''  // even row zebra striping
        // });
        //
        // // call the tablesorter plugin and apply the uitheme widget
        // $("table").tablesorter({
        //   // this will apply the bootstrap theme if "uitheme" widget is included
        //   // the widgetOptions.uitheme is no longer required to be set
        //   theme : "bootstrap",
        //
        //   widthFixed: true,
        //
        //   headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
        //
        //   // widget code contained in the jquery.tablesorter.widgets.js file
        //   // use the zebra stripe widget if you plan on hiding any rows (filter widget)
        //   widgets : [ "uitheme", "filter", "zebra" ],
        //
        //   widgetOptions : {
        //     // using the default zebra striping class name, so it actually isn't included in the theme variable above
        //     // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
        //     zebra : ["even", "odd"],
        //
        //     // reset filters button
        //     filter_reset : ".reset"
        //
        //     // set the uitheme widget to use the bootstrap theme class names
        //     // this is no longer required, if theme is set
        //     // ,uitheme : "bootstrap"
        //
        //   }
        // })
        // .tablesorterPager({
        //
        //   // target the pager markup - see the HTML block below
        //   container: $(".ts-pager"),
        //
        //   // target the pager page select dropdown - choose a page
        //   cssGoto  : ".pagenum",
        //
        //   // remove rows from the table to speed up the sort of large tables.
        //   // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
        //   removeRows: false,
        //
        //   // output string - default is '{page}/{totalPages}';
        //   // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
        //   output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'
        //
        // });
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
