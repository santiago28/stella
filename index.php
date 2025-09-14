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
$msg   = "";

// 🚨 Si ya está logueado → redirige y termina
if (!empty($_SESSION['login'])) {
    header("Location: homeadmin.php");
    exit;
}

// 🚨 Si no está logueado → envíalo a interventoria y termina
if (empty($_SESSION['login']) && empty($_POST['username'])) {
    header("Location: /2020/interventoria/index.php");
    exit;
}

// 🚨 Solo llegas aquí si viene un POST con username/password
if (!empty($_POST['username'])) {
    $login    = $_POST['username'];
    $password = md5(htmlspecialchars($_POST['password']));

    include("./lib/clsFunciones.php");
    $clsFunciones = new clsFunciones;

    if ($clsFunciones->validarSesion($login, $password)) {
     


        header('Location: homeadmin.php');
        exit;
    } else {
        $error = "<font color='red' face='Trebuchet MS' size='2'><b>". $clsFunciones->msg ."</b></font>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>STELLA - SI Interventoría Buen Comienzo</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
  <link href="css/signin.css" rel="stylesheet">
</head>
<body>
  <div id="bodyLogin" style="">
    <div align="center">
      <div class="container well" id="sha">
        <div class="row">
          <div class="col-xs-12">
            <img src="images/logo.png" id="avatarLogin" />
            <center><h4 class="form-signin-heading">STELLA <?=date('Y')?></h4></center>
          </div>
        </div>
        <section>
          <form class="form-signin" role="form" name="formulario" method="post" action="#">
            <div class="form-group">
              <input type="text" name="username" class="form-control" placeholder="Usuario" required autofocus>
            </div>
            <div class="form-group">
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me"> Recordar Contraseña
              </label>
            </div>
            <input type="hidden" id="ips" name="ips" value="<?php echo get_client_ip(); ?>">
            <button class="btn btn-lg btn-pascual btn-block" type="submit">Aceptar</button>
          </form>
          <br>
          <?php echo $error; ?>
          <div class="footer">
            <center><p>&copy; <?=date('Y')?> Sistema de Información de la interventoría Buen Comienzo</p></center>
          </div>
        </section>
      </div>
    </div>
  </div>
</body>
</html>
