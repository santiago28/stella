<?php
session_start();

function get_client_ip()
{
  $ipaddress = '';
  if (getenv('HTTP_CLIENT_IP'))
    $ipaddress = getenv('HTTP_CLIENT_IP');
  else if(getenv('HTTP_X_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
  else if(getenv('HTTP_X_FORWARDED'))
    $ipaddress = getenv('HTTP_X_FORWARDED');
  else if(getenv('HTTP_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_FORWARDED_FOR');
  else if(getenv('HTTP_FORWARDED'))
    $ipaddress = getenv('HTTP_FORWARDED');
  else if(getenv('REMOTE_ADDR'))
    $ipaddress = getenv('REMOTE_ADDR');
  else
    $ipaddress = 'UNKNOWN';

  return $ipaddress;
}

$error = "";
$msg = "";
if(!empty($_SESSION['login'])){
	header("Location: homeadmin.php");

}else{
  header("Location: /2020/interventoria/index.php");
}

if(!empty($_POST['username'])){
	$login=$_POST['username'];
	//$password=$_POST['password'];

	$password=md5(htmlspecialchars($_POST['password']));

	include("./lib/clsFunciones.php");
	$clsFunciones = new clsFunciones;
	if($clsFunciones->validarSesion($login,$password)){
    if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip1 = $_SERVER['HTTP_CLIENT_IP'];
    } else { $ip1 = '0.0.0.0'; }
    if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else { $ip2 = '0.0.0.0'; }
    if ( isset($_SERVER['REMOTE_ADDR']) && ! empty($_SERVER['REMOTE_ADDR'])){
      $ip3 = $_SERVER['REMOTE_ADDR'];
    } else { $ip3 = '0.0.0.0'; }
    $clsFunciones->bitacora($login, $ip1, $ip2, $ip3);
    header('Location:homeadmin.php');
  }else{
    $error = "<font color='red' face='Trebuchet MS' size='2'><b>". $clsFunciones->msg ."</b></font>";
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

  <title>STELLA - SI Interventoría Buen Comienzo</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Estilos menú principal -->
  <link rel="stylesheet" href="css/estilos.css">

  <!-- Custom styles for this template -->
  <link href="css/signin.css" rel="stylesheet">

  <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
  <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
  <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>

    <body>

      <div id="bodyLogin" style="">
          <div align="center">
              <div class="container well" id="sha">
                  <div class="row">
                      <div class="col-xs-12">
                          <img src="images/logo.png" id="avatarLogin" />
                          <center><h4 class="form-signin-heading">STELLA <?=date('Y')?></h2>
                      </div>
                  </div>
                  <section>
                    <form class="form-signin" role="form" name="formulario" METHOD="post" action="#">
                      <div class="form-group">
                          <div class="col-md-offset-0">
                              <input type="text" name="username" class="form-control" placeholder="Usuario" required autofocus>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-md-offset-0">
                              <input type="password" name="password" class="form-control" placeholder="Password" required>
                          </div>
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" value="remember-me"> Recordar Contraseña
                        </label>
                      </div>
                      <div class="form-group">
                          <div class="col-md-offset-1 col-XS-10">
                            <input type="hidden" id="ips" name="ips" value="<?php echo get_client_ip(); ?>">
                            <button class="btn btn-lg btn-pascual btn-block" type="submit">Aceptar</button>
                          </div>
                      </div>
                    </form>
                    <br>
                    <?php echo $error; ?>
                    <div class="footer">
                     <center> <p> &copy; <?=date('Y')?> Sistema de Información de la interventoría Buen Comienzo </p>
                     </div>
                  </section>
              </div>
          </div>
      </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
  </html>
