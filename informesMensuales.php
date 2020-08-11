<?php
session_start();
if ($_SESSION['login'])
{
  include "conexion.php";
  $id_grupo=$_SESSION["grupo"];
  $id_componente=$_SESSION["componente"];
  $nombre=$_SESSION["nombre_usuario"];
  $username = $_SESSION['login'];
  $fotoperfil = $_SESSION["fotoperfil"];
  $mes = "3";
  $contratos = mysql_query(("SELECT id_contrato FROM semaforo WHERE id_mes = '$mes' GROUP BY id_contrato"), $conexion);
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Portal de Administración</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/datepicker.css" rel="stylesheet">
    <!-- Estilos menú principal -->
    <link rel="stylesheet" href="css/estilos.css">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Para validacion de campos -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/parsley.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>


    <!-- Custom styles for this template -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">
  </head>
  <body>
    <div class="barra-menu">
      <div class="col-md-12">

        <div style="margin-top: 1%; float: right;">
          <a class="glyphicon glyphicon-home" href="homeadmin.php" style="font-size:35px; color:#ffffff; text-decoration:none;" role="button"></a>

        </div>
      </div>
    </div>
    <?php include("menu.php"); ?>
    <div style="margin-top:5%; margin-left: 17%;">
      <div class="row">
        <div class="col-md-9">
          <div class="panel with-nav-tabs panel-default">
            <div class="panel-heading">
              <ul class="nav nav-tabs">
                <li id="tab1" class="active"><a href="#tab1default" data-toggle="tab">Informe x Contrato</a></li>
                <li><a href="#tab2default" data-toggle="tab">Exportar todos</a></li>
              </ul>
            </div>
            <div class="panel-body">
              <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1default">
                  <h2 align="center">Exportar Informes</h2>
                  <div align="center">
                    <select data-parsley-min="1" class="form-control" style="width:700px;" name="id_contrato" id="id_contrato">
                      <option id="option1_js" value="0" required>Seleccione el Contrato...</option>
                      <?php  	while($row=mysql_fetch_assoc($contratos)){ ?>
                        <option  value="<?php  echo  $row['id_contrato']; ?>"><?php echo  $row['id_contrato']; ?></option>	<?php 	}	?>
                      </select>
                      <br>
                      <select data-parsley-min="1" class="form-control" style="width:700px;" name="mes" id="mes">
                        <option value="0">Seleccione el mes...</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                      </select>
                      <br>
                      <button type="button" id="Exportar_informe_id" class="btn btn-pascual">Exportar</button>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="tab2default">
                    <h2 align="center">Exportar todos los informes</h2>
                    <div align="center">
                      <select data-parsley-min="1" class="form-control" style="width:700px;" name="mes" id="mestodos">
                        <option value="0">Seleccione el mes...</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                      </select>
                      <br>
                      <button type="button" id="Exportar_informes" class="btn btn-pascual">Exportar informes</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
    <div class="container">

      <div class="footer">
        <center> <p> &copy; 2020 Sistema de Información de la interventoría Buen Comienzo | <img src="images/pascualbravo.jpg" width="260" height="60"> </p>

        </div>

      </div> <!-- /container -->
      </html>
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
      $("#Exportar_informes").click(function(){
        var mes = $("#mestodos").val();
        $.get("word/consulta_contratos.php",{ mes: mes},
        function(resultado)
        {
          if(resultado == false)
          {
            alert("Error");
          }
          else
          {
            var contratos = resultado.split(",");
            $.each(contratos, function(index, value){
              var id_contrato = value;
              if (id_contrato != "") {
                //open("http://localhost/stella/word?code="+ id_contrato, '_self').close();
                //location.href = "http://localhost/stella/word?code="+ id_contrato;
                window.open("http://localhost:8080/stella/word?code="+ id_contrato + "&mes=" + mes);
                //window.open("http://localhost/stella/word?code="+ id_contrato + "&mes=" + mes);
              }
            });
          }
        }

      );
    });

    $("#Exportar_informe_id").click(function(){
      var id_contrato = $("#id_contrato").val();
      var mes = $("#mes").val();
      window.open("http://localhost:8080/stella/word?code="+ id_contrato + "&mes=" + mes);
      //window.open("http://localhost/stella/word?code="+ id_contrato + "&mes=" + mes);
    });
    </script>
    <?php
  }else {
    ?>
    <script>
    window.location='index.php';
    </script>
    <?php
  }
  ?>
