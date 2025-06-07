<?php

session_start();
if ($_SESSION['login'])
{

  include "conexion.php";
  $id_grupo=$_SESSION["grupo"];
  $id_componente=$_SESSION["componente"];
  $nombre=$_SESSION["nombre_usuario"];
  $id_interventor=$_SESSION["login"];
  $acta = $_GET['id_acta'];
  $msg=$_GET['msg'];
  $fotoperfil = $_SESSION["fotoperfil"];

  //Consultas de acuerdo al perfil
  if($id_grupo==1){
    $sql="SELECT
    a.fecha_evaluacion, a.id_acta, c.nombre_componente, a.id_contrato AS contrato, p.nombre_prestador AS prestador, m.nombre_modalidad AS modalidad, a.nombre_sede AS sede, a.id_interventor, a.numero_visita, d.matriculados, d.asistentes, d.alimentacion, d.descripcion, a.estado
    FROM acta a
    INNER JOIN prestador p ON (a.id_prestador=p.id_prestador)
    INNER JOIN modalidad m ON (a.id_modalidad=m.id_modalidad)
    INNER JOIN componente c ON (a.id_componente=c.id_componente)
    LEFT JOIN descuentos d ON (a.id_acta=d.id_acta)
    WHERE a.id_acta LIKE '".$acta."'
    ORDER BY a.fecha_evaluacion DESC
    ";
    $radicados_reservados= mysql_query($sql,$conexion);

  }
  else{
    $sql="SELECT a.fecha_evaluacion, a.id_acta, c.nombre_componente, a.id_contrato AS contrato, p.nombre_prestador AS prestador, m.nombre_modalidad AS modalidad, a.nombre_sede AS sede, a.id_interventor, a.numero_visita, d.matriculados, d.asistentes, d.alimentacion, d.descripcion, a.estado FROM acta a INNER JOIN prestador p ON (a.id_prestador=p.id_prestador) INNER JOIN modalidad m ON (a.id_modalidad=m.id_modalidad) INNER JOIN componente c ON (a.id_componente=c.id_componente) LEFT JOIN descuentos d ON (a.id_acta=d.id_acta) WHERE a.id_acta LIKE '".$acta."' AND a.id_componente=7 ORDER BY a.fecha_evaluacion DESC;";
    $radicados_reservados= mysql_query($sql,$conexion);
  }
  $sql="SELECT * FROM descuentos WHERE id_acta LIKE '$acta' AND estado>0;";
  $listado = mysql_query($sql,$conexion);

  if (false){
    $verifica="";
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
    <title>Descuentos</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">
    <link href="css/theme.default.css" rel="stylesheet">
    <!-- bootstrap widget theme -->
    <link href="css/theme.bootstrap.css" rel="stylesheet">
    <!-- Estilos menú principal -->
    <link rel="stylesheet" href="css/estilos.css">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- JavaScript para los filtros de las tablas -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery.tablesorter.js"></script>
    <script src="js/jquery.tablesorter.widgets.js"></script>
    <!-- Para validacion de campos -->
    <script src="js/parsley.js"></script>
    <script>
    $(document).ready(function(){
      $("#descripcion").change(function(){
        var dato = "";
        switch(this.value)
        {
          case "1":
          dato =  '<option value="">Escoger una opci&oacute;n</option><option value="1">Gramos</option><option value="2">Centrimetos C&uacute;bicos</option><option value="3">Unidad</option>';
          break;

          case "2":
          dato = '<option value="">Escoger una opci&oacute;n</option><option value="1">Gramos</option><option value="2">Centrimetos C&uacute;bicos</option><option value="3">Unidad</option>';
          break;
          case "3":
          dato =  '<option value="">Escoger una opci&oacute;n</option><option value="1">Gramos</option><option value="2">Centrimetos C&uacute;bicos</option><option value="3">Unidad</option>';
          break;
          case "4":
          dato =  '<option value="">Escoger una opci&oacute;n</option><option value="4">Ni&ntilde;os faltantes</option>';
          break;
        };
        $("#medida").html("");
        $("#medida").html(dato);
      });
      $("#medida").change(function(){
        var datoM = "";
        switch(this.value)
        {
          case "1":
          datoM =  '<option value="">Escoger una opci&oacute;n</option><option value="3D triángulos">3D triángulos</option><option value="Acelga">Acelga</option><option value="Ahuyama">Ahuyama</option><option value="Alas de pollo">Alas de pollo</option><option value="Almojábana">Almojábana</option><option value="Apio">Apio</option><option value="Arepa">Arepa</option><option value="Arequipe">Arequipe</option><option value="Arracacha">Arracacha</option><option value="Arroz">Arroz</option><option value="Arveja">Arveja</option><option value="Arveja fresca">Arveja fresca</option><option value="Atún (en aceite)">Atún (en aceite)</option><option value="Avena">Avena</option><option value="Avena Saborizada">Avena Saborizada</option><option value="Azúcar">Azúcar</option><option value="Bagre">Bagre</option><option value="Banano">Banano</option><option value="Banano común">Banano común</option><option value="Blanquillo">Blanquillo</option><option value="Bocadillo">Bocadillo</option><option value="Boli cheetos">Boli cheetos</option><option value="Borojó">Borojó</option><option value="Brevas en almibar">Brevas en almibar</option><option value="Brownie">Brownie</option><option value="Brocoli">Brocoli</option><option value="Cabello de angel (cocido)">Cabello de angel (cocido)</option><option value="Café ">Café </option><option value="Callo o panza">Callo o panza</option><option value="Caramelos">Caramelos</option><option value="Carne de Res">Carne de Res</option><option value="Carne de ternera">Carne de ternera</option><option value="Carne molida">Carne molida</option><option value="Cebada">Cebada</option><option value="Cebolla">Cebolla</option><option value="Cerdo (pierna)">Cerdo (pierna)</option><option value="Cereal ">Cereal </option><option value="Cerezas en almibar">Cerezas en almibar</option><option value="Cheese tris">Cheese tris</option><option value="Chirimolla">Chirimolla</option><option value="Choclitos ">Choclitos </option><option value="Choco polf">Choco polf</option><option value="Chocolate ">Chocolate </option><option value="Chocolatina de leche">Chocolatina de leche</option><option value="Chontaduro">Chontaduro</option><option value="Chorizo común ">Chorizo común </option><option value="Cilantro">Cilantro</option><option value="Ciruela claudia">Ciruela claudia</option><option value="Ciruela común">Ciruela común</option><option value="Ciruelas pasas">Ciruelas pasas</option><option value="Cocada de panela">Cocada de panela</option><option value="Coco pulpa">Coco pulpa</option><option value="Cocoa">Cocoa</option><option value="Coliflor">Coliflor</option><option value="Colmbineta">Colmbineta</option><option value="Confites duros">Confites duros</option><option value="Contramuslo carne sin piel cruda sin hueso">Contramuslo carne sin piel cruda sin hueso</option><option value="Crema de leche">Crema de leche</option><option value="Croissant ">Croissant </option><option value="Cuajada de leche de vaca">Cuajada de leche de vaca</option><option value="Cucas">Cucas</option><option value="Cuchuco de cebada">Cuchuco de cebada</option><option value="Curuba fruta">Curuba fruta</option><option value="Curuba pulpa">Curuba pulpa</option><option value="Doritos">Doritos</option><option value="Empanada de arequipe y queso ">Empanada de arequipe y queso </option><option value="Espinaca">Espinaca</option><option value="Fécula de Maíz ">Fécula de Maíz </option><option value="Feijoa fruta">Feijoa fruta</option><option value="Feijoa pulpa">Feijoa pulpa</option><option value="Fresas fruta">Fresas fruta</option><option value="Fresas pulpa">Fresas pulpa</option><option value="Fríjol ">Fríjol </option><option value="Fríjol verde">Fríjol verde</option><option value="Fruta dulce">Fruta dulce</option><option value="Galletas de leche">Galletas de leche</option><option value="Galletas dulces">Galletas dulces</option><option value="Galletas tipo ducales">Galletas tipo ducales</option><option value="Galletas wafers ">Galletas wafers </option><option value="Garbanzo ">Garbanzo </option><option value="Gelatina con azucar preparada">Gelatina con azucar preparada</option><option value="Gelatina de pata">Gelatina de pata</option><option value="Granadilla ">Granadilla </option><option value="Grasa">Grasa</option><option value="Guanábana fruta">Guanábana fruta</option><option value="Guanábana pulpa">Guanábana pulpa</option><option value="Guayaba común ">Guayaba común </option><option value="Guayaba fruta">Guayaba fruta</option><option value="Guayaba pulpa">Guayaba pulpa</option><option value="Guineo">Guineo</option><option value="Habichuela">Habichuela</option><option value="Harina de trigo">Harina de trigo</option><option value="Helado de agua">Helado de agua</option><option value="Helado de vainilla">Helado de vainilla</option><option value="Hígado de pollo">Hígado de pollo</option><option value="Higado de Res">Higado de Res</option><option value="Higo">Higo</option><option value="Huevo ">Huevo </option><option value="Huevos de codorniz">Huevos de codorniz</option><option value="Jamón ">Jamón </option><option value="Leche condensada">Leche condensada</option><option value="Leche de formula de iniciación">Leche de formula de iniciación</option><option value="Leche de formula de continuación">Leche de formula de continuación</option><option value="Leche polvo">Leche polvo</option><option value="Leche polvo entera">Leche polvo entera</option><option value="Lechuga">Lechuga</option><option value="Lengua de res">Lengua de res</option><option value="Lentejas">Lentejas</option><option value="Lulo">Lulo</option><option value="Maíz (crudo)">Maíz (crudo)</option><option value="Maíz pira">Maíz pira</option><option value="Maíz tierno o choclo enlatado">Maíz tierno o choclo enlatado</option><option value="Mandarina">Mandarina</option><option value="Mango fruta">Mango fruta</option><option value="Mango pulpa">Mango pulpa</option><option value="Maní con sal">Maní con sal</option><option value="Manzana">Manzana</option><option value="Maracuya fruta">Maracuya fruta</option><option value="Maracuyá pulpa">Maracuyá pulpa</option><option value="Margarina">Margarina</option><option value="Mantequilla">Mantequilla</option><option value="Masmelos">Masmelos</option><option value="Mayonesa">Mayonesa</option><option value="Melón fruta">Melón fruta</option><option value="Mermelada">Mermelada</option><option value="Miel de abejas">Miel de abejas</option><option value="Mini cheetos">Mini cheetos</option><option value="Mini chips chocolate">Mini chips chocolate</option><option value="Mini recreo">Mini recreo</option><option value="Mora fruta">Mora fruta</option><option value="Mora pulpa">Mora pulpa</option><option value="Morcilla">Morcilla</option><option value="Mortadela">Mortadela</option><option value="Mulso de pollo sin hueso y sin piel">Mulso de pollo sin hueso y sin piel</option><option value="Naranja fruta">Naranja fruta</option><option value="Nuez del brasil">Nuez del brasil</option><option value="Palito de queso ">Palito de queso </option><option value="Palitos margarita">Palitos margarita</option><option value="Palitos o palitroques">Palitos o palitroques</option><option value="Pan">Pan</option><option value="Pan blanco ">Pan blanco </option><option value="Pan Tajado">Pan Tajado</option><option value="Pan blanco / Pan salchicha">Pan blanco / Pan salchicha</option><option value="Pan Hamburguesa">Pan Hamburguesa</option><option value="Pandequeso ">Pandequeso </option><option value="Pandequeso con Guayaba">Pandequeso con Guayaba</option><option value="Pandeyuca">Pandeyuca</option><option value="Panela">Panela</option><option value="Papa comun">Papa comun</option><option value="Papa criolla">Papa criolla</option><option value="Papas fritas (paquete)">Papas fritas (paquete)</option><option value="Papaya">Papaya</option><option value="Papayuela">Papayuela</option><option value="Pargo especies mezcladas">Pargo especies mezcladas</option><option value="Pastas (cocida)">Pastas (cocida)</option><option value="Pastas (Sopa)">Pastas (Sopa)</option><option value="Pastel de arequipe y queso">Pastel de arequipe y queso</option><option value="Pastel de guayaba y queso">Pastel de guayaba y queso</option><option value="Pastel de jamón y queso ">Pastel de jamón y queso </option><option value="Pastel de queso ">Pastel de queso </option><option value="Pastel dulce ">Pastel dulce </option><option value="Pechuga">Pechuga</option><option value="Pechuga de pollo">Pechuga de pollo</option><option value="Pepino">Pepino</option><option value="Pera ">Pera </option><option value="Pescado sin espinas">Pescado sin espinas</option><option value="Pescado sin espinas (filete)">Pescado sin espinas (filete)</option><option value="Pimentón">Pimentón</option><option value="Piña fruta">Piña fruta</option><option value="Piña pulpa">Piña pulpa</option><option value="Pitahaya">Pitahaya</option><option value="Platanitos (paquete)">Platanitos (paquete)</option><option value="Plátano">Plátano</option><option value="Plátano maduro">Plátano maduro</option><option value="Plátano verde">Plátano verde</option><option value="Polvo chocolatado y azucarado">Polvo chocolatado y azucarado</option><option value="Ponqué">Ponqué</option><option value="Ponqué">Ponqué</option><option value="Producto panificado">Producto panificado</option><option value="Quesito">Quesito</option><option value="Queso">Queso</option><option value="Queso Americano">Queso Americano</option><option value="Queso campesino">Queso campesino</option><option value="Queso crema">Queso crema</option><option value="Queso Mozarella">Queso Mozarella</option><option value="Queso parmesano">Queso parmesano</option><option value="Queso ricotta de leche entera">Queso ricotta de leche entera</option><option value="Queso sabana">Queso sabana</option><option value="Remolacha ">Remolacha </option><option value="Repollo">Repollo</option><option value="Res (corte de primera)">Res (corte de primera)</option><option value="Rosquitas ">Rosquitas </option><option value="Salchicha">Salchicha</option><option value="Salchichón cervecero">Salchichón cervecero</option><option value="Salchichón tradicional">Salchichón tradicional</option><option value="Salmón rosado">Salmón rosado</option><option value="Salsa rosada">Salsa rosada</option><option value="Salsa Rosada o Tomate">Salsa Rosada o Tomate</option><option value="Sandia">Sandia</option><option value="Sanduche">Sanduche</option><option value="Sapito">Sapito</option><option value="Sardina enlatada en salsa de tomate">Sardina enlatada en salsa de tomate</option><option value="Soya">Soya</option><option value="Tabla molida">Tabla molida</option><option value="Tocino">Tocino</option><option value="Tomate">Tomate</option><option value="Tomate de arbol fruta">Tomate de arbol fruta</option><option value="Tomate de arbol pulpa">Tomate de arbol pulpa</option><option value="Tomate rojo">Tomate rojo</option><option value="Torta ">Torta </option><option value="Torta tipo gala">Torta tipo gala</option><option value="Tostada">Tostada</option><option value="Tosti arepa">Tosti arepa</option><option value="Trucha arcoiris">Trucha arcoiris</option><option value="Uchuvas">Uchuvas</option><option value="Uva blanca">Uva blanca</option><option value="Yogueta">Yogueta</option><option value="Yogurt">Yogurt</option><option value="Yuca">Yuca</option><option value="Yupis">Yupis</option><option value="Zanahoria">Zanahoria</option><option value="Zapote">Zapote</option>';
          break;

          case "2":
          datoM = '<option value="">Escoger una opci&oacute;n</option><option value="Aceite">Aceite</option><option value="Aceite de Soya">Aceite de Soya</option><option value="Avena">Avena</option><option value="Claro de maíz">Claro de maíz</option><option value="Gaseosa azucarada">Gaseosa azucarada</option><option value="Gatorade">Gatorade</option><option value="Grasa">Grasa</option><option value="Jugo en Caja o bolsa">Jugo en Caja o bolsa</option><option value="Kumis">Kumis</option><option value="Leche chocolatada">Leche chocolatada</option><option value="Leche de fresa">Leche de fresa</option><option value="Leche liquida">Leche liquida</option><option value="Leche liquida saborizada en caja o bolsa tetrapack">Leche liquida saborizada en caja o bolsa tetrapack</option><option value="Leche liquida Semidescremada">Leche liquida Semidescremada</option><option value="Leche liquida Descremada">Leche liquida Descremada</option><option value="Leche en Polvo Descremada">Leche en Polvo Descremada</option><option value="Leche deslactosada">Leche deslactosada</option><option value="Malta">Malta</option><option value="Yogurt">Yogurt</option><option value="Yogurt de fresa">Yogurt de fresa</option><option value="Yogurt de melocot&oacute;n">Yogurt de melocot&oacute;n</option><option value="Yogurt de mora">Yogurt de mora</option>';
          break;
          case "3":
          datoM =  '<option value="">Escoger una opci&oacute;n</option><option value="Arepa pequeña">Arepa pequeña</option><option value="Avena">Avena</option><option value="Avena UHT">Avena UHT</option><option value="Banano">Banano</option><option value="Galletas de sal">Galletas de sal</option><option value="Galletas tipo ducales">Galletas tipo ducales</option><option value="Galletas tipo Sandwich">Galletas tipo Sandwich</option><option value="Galletas Wafer">Galletas Wafer</option><option value="Huevo">Huevo</option><option value="Leche de vainilla">Leche de vainilla</option><option value="Leche Natural UHT">Leche Natural UHT</option><option value="Leche liquida 1 bolsa pequeña">Leche liquida 1 bolsa pequeña</option><option value="Leche Saborizada UHT">Leche Saborizada UHT</option><option value="Sanduche">Sanduche</option><option value="Tostada">Tostada</option>';
          break;
          case "4":
          datoM =  '<option value="">Escoger una opci&oacute;n</option><option value="Todos los alimentos del dia">Todos los alimentos del dia</option>';
          break;
        };
        $("#alimento").html("");
        $("#alimento").html(datoM);
      });
      $("#faltante").change(function(){
        var datoC= $("#faltante").val() * $("#grupoedad").val();
        $("#total").val(datoC);
      });
      $("#grupoedad").change(function(){
        var datoC= $("#faltante").val() * $("#grupoedad").val();
        $("#total").val(datoC);
      });
    });
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
            El registro ha sido Insertado/Actualizado exitosamente.<button type="button" class="close" aria-hidden="true">x</button></h5>
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
          <?php $row = mysql_fetch_assoc($radicados_reservados); ?>
          <h2>Descontar alimentaci&oacute;n al Acta No <?php echo $acta; ?></h2>
          <h5>Tenga en cuenta los datos para el descuento se aplicaran al acta seleccionada</h5>

          <!-- if ($row['estado']==2 AND $nombre=) -->




          <form data-parsley-validate class="form-signin" role="form" name="formulario" METHOD="post" action="inserts.php">
            <input type="hidden" name="caso" value="22">
            <input type="hidden" name="acta" value="<?php echo $acta; ?>">
            <input type="hidden" name="fecha" value="<?php echo $row['fecha_evaluacion']; ?>">
            <input type="hidden" name="id_interventor" value="<?php echo $id_interventor; ?>">

            <label class="form-control-label">Prestador : <?php echo $row['prestador'];     ?></label><br>
            <label class="form-control-label">Modalidad : <?php echo $row['modalidad'];    ?></label><br>
            <label class="form-control-label">Contrato  : <?php echo $row['contrato'];     ?></label><br>
            <label class="form-control-label">Sede      : <?php echo $row['sede'];         ?></label><br>
            <label for="matriculados" class="form-control-label">Ni&ntilde;os Matriculados</label>
            <input data-parsley-required type="number" name="matriculados" class="form-control" placeholder="Ni&ntilde;os Matriculados" autofocus>
            <br>
            <label for="asistentes" class="form-control-label">Ni&ntilde;os Asistentes</label>
            <input data-parsley-required type="number" name="asistentes" class="form-control" placeholder="Ni&ntilde;os Asistentes" >
            <br>
            <label for="alimentacion" class="form-control-label">Tipo de Alimentaci&oacute;n</label>
            <select class="form-control" name="alimentacion" id="alimentacion">
              <option>Escoger una opci&oacute;n</option>
              <option value="Contratada">Contratada</option>
              <option value="Preparada">Preparada</option>
              <option value="Mixta">Mixta</option>
            </select>
            <br>
            <label For="descripcion">DESCRIPCION DEL INCUMPLIMIENTO</label>
            <select data-parsley-min="1" class="form-control" name="descripcion" id="descripcion">
              <option value="">Escoger una opcion</option>
              <option value="1">Producto en mal estado</option>
              <option value="2">Producto vencido</option>
              <option value="3">Cantidad insuficiente del producto</option>
              <option value="4">Inasistencia de niños</option>
            </select>
            <br>
            <label for="medida">UNIDAD DE MEDIDA</label>
            <select data-parsley-required class="form-control" name="medida" id="medida">
            </select>
            <br>
            <label for="alimento">ALIMENTO</label>
            <select data-parsley-required class="form-control" name="alimento" id="alimento">
              <option value="">Escoger una opci&oacute;n</option>
              <option value=""></option>
            </select>
            <br>
            <label for="detallealimento">DETALLE DEL ALIMENTO</label>
            <input type="text" name="detallealimento" class="form-control" placeholder="Descripci&oacute;n del tipo de Alimento" >
            <br>
            <label for="faltante">CANTIDAD FALTANTE POR NIÑO / NÚMERO DE NIÑOS FALTANTES</label>
            <input data-parsley-type="number" data-parsley-required type="text" name="faltante" id="faltante" class="form-control" placeholder="Cantidad faltante" value="0">
            <br>
            <label for="grupoedad">No. DE NIÑOS POR GRUPO DE EDAD.</label>
            <input data-parsley-type="number" data-parsley-required type="text" name="grupoedad" id="grupoedad" class="form-control" placeholder="No. DE NIÑOS POR GRUPO DE EDAD.(Si el descuento es por INASISTENCIA siempre es '1')" value="0" >
            <br>
            <label for="total">TOTAL ALIMENTO A DESCONTAR</label>
            <input data-parsley-type="number" data-parsley-required type="text" name="total" id="total" class="form-control" placeholder="Calculada" readonly  value="0">
            <br>
            <label for="observaciones">OBSERVACIONES</label>
            <textarea class="form-control" name="observaciones"></textarea>
            <br>
            <?php    $sqlverif="SELECT * FROM descuentos de WHERE de.id_acta = '".$row['id_acta']."' ;";
            $respverif = mysql_query($sqlverif,$conexion);
            $verif=@mysql_fetch_assoc($respverif);
            if (@$verif['estado']>1) {
              echo '<button  class="btn btn-pascual" type="button">Descuento ya Cerrado</button>';
            } else {
              echo '<button  class="btn btn-pascual" type="submit">Agregar Descuento</button>';
            }
            ?>
            <br>
            <br>
          </form>

        </div> <!-- /jumbotron -->


      </div> <!-- /container -->
      <?php
      //SECCION TEMAS CREADOS

      if(mysql_num_rows($radicados_reservados) > 0){ ?>





        <table align="center" class="table table-bordered table-hover" id='table' style="width: 80%">
          <thead>
            <tr>
              <th class="info">Número Acta</th>
              <th class="info">Fecha Acta</th>
              <th class="info">Descripción Incumplimeinto</th>
              <th class="info">Unidad de Medida</th>
              <th class="info">Alimento</th>
              <th class="info">Detalle Alimento</th>
              <th class="info">Cantidad Faltante</th>
              <th class="info">No. de Ni&ntilde;os por grupo</th>
              <th class="info">Total Alimento a Desc.</th>
              <th class="info">Observaciones</th>
              <th class="info">Interventor</th>
              <?php if ($id_grupo==1){ ?>
                <th class="info">Eliminar</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php
              while($row = mysql_fetch_assoc($listado)){
                switch ($row['descripcion']) {
                  case 1:
                  $aux1 = "Producto en mal estado";
                  break;
                  case 2:
                  $aux1 = "Producto vencido";
                  break;
                  case 3:
                  $aux1 = "Cantidad insuficiente del producto";
                  break;
                  case 4:
                  $aux1 = "Inasistencia de niños";
                  break;
                }
                switch ($row['unidad']) {
                  case 1:
                  $aux2 = "Gramos";
                  break;
                  case 2:
                  $aux2 = "Centrimetos C&uacute;bicos";
                  break;
                  case 3:
                  $aux2 = "Unidad";
                  break;
                  case 4:
                  $aux2 = "Ni&ntilde;os faltantes";
                  break;
                }
                ?>
                <tr>
                  <td class="active"><?php echo $row['id_acta'];  ?></td>
                  <td class="active"><?php echo $row['fecha_acta'];  ?></td>
                  <td class="active"><?php echo $aux1; ?></td>
                  <td class="active"><?php echo $aux2; ?></td>
                  <td class="active"><?php echo $row['alimento']; ?></td>
                  <td class="active"><?php echo $row['detallealimento']; ?></td>
                  <td class="active"><?php echo $row['faltante']; ?></td>
                  <td class="active"><?php echo $row['grupo']; ?></td>
                  <td class="active"><?php echo $row['descontar']; ?></td>
                  <td class="active"><?php echo $row['observaciones']; ?></td>
                  <td class="active"><?php echo $row['interventor'];  ?></td>
                  <?php if ($id_grupo==1){ ?>
                    <td class="danger"><a  href="deletes.php?eliminar=<?php echo $row['id'] ?>&acta=<?php echo $row['id_acta'] ?>&caso=22"><center><IMG src='images/eliminar.png' border='0'></center></a></td>
                      <?php } ?>
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


                    <!-- Cerrar el boton emergente-->
                    $('.close').click(function() {
                      $(this).parent().parent().fadeOut();
                    });

                    <!-- Filtros Tablas-->
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
