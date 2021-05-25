<?php 
require '../controllers/funciones.php';
include('../db/config.php');
include('../session.php');
$userDetails=$userClass->userDetails($session_uid);
?>
<!DOCTYPE html>
<html>
  <head> 
  <?php include("encabezados.php"); ?>
  </head>
  <body>
    <!-- Header Navigation-->
    <?php include("header.php"); ?>
    <!-- Header Navigation End-->
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <?php include("sidebar.php"); ?>
      <!-- Sidebar Navigation end-->
      <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Ingresos</h2>
          </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Inicio</a></li>
            <li class="breadcrumb-item active">Ingresos        </li>
          </ul>
        </div>
        
        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                <div class="block">
                  <div class="title"><strong>Registrar Ingreso</strong></div>
                  <div class="block-body">
                    <?php 
                      if ($_POST) { 
                        insertarIngreso();
                      }
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                        <div class="form-group">
                            <label class="col-8 col-md-6" id="tipoIngreso"><i class="fa fa-money" aria-hidden="true"></i> Tipo de Ingreso: </label>
                            <div class="col-sm-2 col-md-4">
                            <select class="custom-select" id="selectIng" name="tipoIngreso" required><?php getTipoIngreso(); ?></select> 	
                            </div>
                            <hr>
                            <label class="col-sm-2" id="monto"><i class="fa fa-usd" aria-hidden="true"></i> Monto: </label>
                            <div class="col-sm-4">
                            <input type="text" class="form-control"  required="" class="ingreso" name="monto" autocomplete="off">
                            </div>
                            <hr>
                            <label class="col-sm-2" id="fecha"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha: </label>
                            <div class="col-sm-4">
                            <input type="date" required="" name="fecha">
                            </div>                            
                            <hr>
                            <input type="hidden" name="id_user" id="id_user" value="<?php echo $session_uid ?>">
                            <label for="comment" id="observaciones"><i class="fa fa-info-circle" aria-hidden="true"></i> Observaciones:</label>
                            <textarea class="form-control" id="txtObservaciones" name="observaciones" rows="3" cols="10" maxlength="100"></textarea>	    
                        </div>
                      <div class="line"></div>
                      <div class="form-group row">
                        <div class="col-sm-9 ml-auto">
                          <button type="submit" class="btn btn-success">Insertar ingreso</button>
                          <button type="reset" class="btn btn-secondary">Limpiar</button>                          
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <?php include("footer.php"); ?>
      </div>
    </div>
    <?php include("scripts.php"); ?>
    <script type="text/javascript">	
    $(document).ready(function(){	
      $("input[name=monto]").on("change keyup",function(){
          var num = $("input[name=monto]").val().replace(/\./g,'');
          if(!isNaN(num)){
          num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,"$1.");
          num = num.split('').reverse().join('').replace(/^[\.]/,'');
          $("input[name=monto]").val(num);
          }
          else{ 
          $("input[name=monto]").val($("input[name=monto]").val().replace(/[^\d\.]*/g,''));
          }
        })
      /**
      * funcion que prohibe presionar todo excepto las teclas comentadas abajito
      * @param  {[type]} e) {                      if(!event) event [description]
      * @return {[type]}    [description]
      */
        $("input[name=monto]").on("keydown",function (e) {
          if(!event) event = event || window.event;
        return (
        (event.keyCode > 7 && event.keyCode < 10)  // delete (8) o tabulador (9)
        || (event.keyCode > 45 && event.keyCode < 60) // numeros del teclado
        || (event.keyCode > 95 && event.keyCode < 106) // teclado numerico
        || event.keyCode == 17  // Ctrl
        || event.keyCode == 116 // F5
        || event.keyCode == 37
        || event.keyCode == 39
        )
        });
    });
    </script>	
  </body>
</html>