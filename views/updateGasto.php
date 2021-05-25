<?php
require '../controllers/funciones.php';
include('../db/config.php');
include('../session.php');
$userDetails = $userClass->userDetails($session_uid);
?>
<!DOCTYPE html>
<html>

<head>
    <?php include("encabezados.php"); ?>
</head>

<body>
    <?php $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no existe.'); ?>
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
                    <h2 class="h5 no-margin-bottom">Actualizacion de Gastos</h2>
                </div>
            </div>
            <!-- Breadcrumb-->
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Actualizar Gasto</li>
                </ul>
            </div>
            <section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong>Actualización del Gasto N# <?php echo $id; ?></strong></div>
                                <div class="block-body">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <?php gastoaActualizar($id); ?>
                                            <?php ActualizarGasto($id); ?>    
                                            <input type='submit' value='Actualizar Gasto' class='btn btn-primary' />
                                            <a href='listarGastos.php' class='btn btn-info'>Volver al Listado</a>
                                        </div>
                                        </fieldset>
                                        <br><br>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include("footer.php"); ?>    
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