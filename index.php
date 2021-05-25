<?php 
include("db/config.php");
include('model/userClass.php');
require 'login.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Finanzas 3.0 - Inicio de Sesión</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom Font Icons CSS-->
    <link rel="stylesheet" href="css/font.css">
    <!-- Google fonts - Muli-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.blue.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/favicon.ico">
    <!-- JavaScript files-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>    
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>    
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="js/front.js"></script>
  </head>
  <body>
    <div class="login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="info d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                    <h1>Finanzas 3.0</h1>
                  </div>
                  <p>Manejo de Finanzas Personales</p>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6">
              <div class="form d-flex align-items-center">
                <div class="content">
                  <form class="form"  role="form" autocomplete="off" id="formLogin" method="POST">
                    <div class="form-group">
                      <input id="usernameEmail" type="text" name="usernameEmail" required data-msg="Please enter your username" class="input-material">
                      <label for="usernameEmail" class="label-material">Nombre de Usuario</label>
                      <!-- <div class="invalid-feedback">Campo Obligatorio.</div>  -->
                    </div>
                    <div class="form-group">
                      <input id="password" type="password" name="password" required data-msg="Please enter your password" class="input-material">
                      <label for="password" class="label-material">Contraseña</label>
                      <!-- <div class="invalid-feedback">Contraseña Requerida!</div>      -->
                      <div class="errorMsg"><?php echo $errorMsgLogin; ?></div>
                    </div>                    
                    <button type="submit" class="btn btn-primary" id="btnLogin" name="loginSubmit" value="Login">Ingresar</button>
                  </form><br><small>Sin Cuenta? </small><a href="register.php" class="signup">Registrarse</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="copyrights text-center">
        <p>Design by <a href="https://bootstrapious.com/p/bootstrap-4-dark-admin" class="external">Bootstrapious</a></p>
        <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
      </div>
    </div>
    <!-- JavaScript files-->
    <!--<script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>    
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="js/front.js"></script>-->

  </body>
</html>