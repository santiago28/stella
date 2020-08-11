<?php
session_start();
if ($_SESSION['login'])
{

  include "conexion.php";
  $id_grupo=$_SESSION["grupo"];
  $id_componente=$_SESSION["componente"];
  $nombre=$_SESSION["nombre_usuario"];
  $id_interventor=$_SESSION["login"];
  $fotoperfil = $_SESSION["fotoperfil"];
  $msg=@$_GET['msg'];
  $acta=$_GET['acta'];

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
    <title>Consulta de Actas - Aprobaci&oacute;n Descuento</title>
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
    <script language="JavaScript" src="js/md5.js"></script>
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
    if($msg==1){    ?>
      <div id="mensaje" class="alert alert-success" role="alert">
        <h5 align="center"><strong>¡Felicitaciones!</strong>
          El registro ha sido Insertado/Actualizado exitosamente.<button type="button" class="close" aria-hidden="true" onclick="javascript:window.close();">x</button></h5>
        </div>
      <?php } else { ?>
        <div id="mensaje" class="alert alert-danger" role="alert">
          <h5 align="center"><strong>¡Advertencia!</strong>
            Acaba de Eliminar un registro o se generaron inconvenientes al realizar la transacción.<button type="button" class="close" aria-hidden="true" onclick="javascript:window.close();">x</button></h5>
          </div>
        <?php } // End else msg=2
      } //End msg=0
      ?>

      <div class="jumbotron">
        <h2>Acta de Descuento a Aprobar</h2>
        <form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">
          <input type="hidden" name="caso" value="23">
          <p><input type="hidden" name="acta" value="<?php echo $acta; ?>">Acta: <?php echo $acta; ?></p>
          <p>Usuario: <?php echo $nombre ?></p>
          <p><input type="password" name="clave" id="clave" value=""></p>
          <br>
          <button  class="btn btn-pascual" type="submit" onClick="document.getElementById('clave').value=calcMD5(document.getElementById('clave').value);">Agregar Aprobación de Descuento</button>
          <br>
        </form>
      </div>

    </div><!--  /container -->
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
