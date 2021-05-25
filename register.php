<?php 
include("db/config.php");
include('model/userClass.php');
require 'signup.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Finanzas 3.0 - Registro de Usuario</title>
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
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
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
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
                  <form method="post" action="" name="signup" class="text-left form-validate" enctype="multipart/form-data">
                    <div class="form-group-material">
                      <input id="register-name" type="text" name="nombre" required data-msg="Please enter your name" class="input-material">
                      <label for="register-name" class="label-material">Nombre de Pila</label>
                    </div>
                    <div class="form-group-material">
                      <input id="register-username" type="text" name="registerUsername" required data-msg="Please enter your username" class="input-material">
                      <label for="register-username" class="label-material">Nombre de Usuario</label>
                    </div>
                    <div class="form-group-material">
                      <input id="register-email" type="email" name="registerEmail" required data-msg="Please enter a valid email address" class="input-material">
                      <label for="register-email" class="label-material">Email</label>
                    </div>
                    <<div class="form-group-material">                                                                  
                      <label for="register-photo" id="foto"><i class="fas fa-file-upload"></i>Imagen de Perfil: </label>
                      <input type="file" name="registerPhoto"  id="customFile" class="input-material">
                    </div>
                    <div class="form-group-material">
                      <input id="register-password" type="password" name="registerPassword" required data-msg="Please enter your password" class="input-material">
                      <label for="register-password" class="label-material">Password</label>
                    </div>
                    <div class="errorMsg"><?php echo $errorMsgReg; ?></div>
                    <div class="form-group text-center">
                      <!-- <input id="register" type="submit" value="Registrarse" class="btn btn-primary"> -->
                      <input type="submit" class="btn btn-primary" name="signupSubmit" value="Registrarse">
                    </div>
                  </form><small>Ya tienes cuenta? </small><a href="index.php" class="signup">Iniciar Sesión</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="copyrights text-center">
        <p>Design by <a href="https://bootstrapious.com" class="external">Bootstrapious</a></p>
        <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="js/front.js"></script>
  </body>
</html>