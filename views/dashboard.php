<?php
require '../controllers/funciones.php';
include('../db/config.php');
include('../session.php');
//setlocale(LC_ALL, "es_ES");
date_default_timezone_set('UTC');
date_default_timezone_set("America/Mexico_City");
setlocale(LC_TIME, 'Spanish');
$fecha = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1]; //Variable que trae la fecha formateada
$firstDayMonth = date('Y-m-01'); //Trae el primer día del mes
$userDetails = $userClass->userDetails($session_uid); //Datos del usuario

/*Logica del 1er día de la semana*/
$diaAct = date("d"); //Dia Actual
$mesAct = date("m"); //Mes Actual
$anioAct = date("y"); //Año Actual
//$semana=date("W",mktime(0,0,0,$mesAct,$diaAct,$diaAct));
$diaSemana = date("w", mktime(0, 0, 0, $mesAct, $diaAct, $anioAct));
if ($diaSemana == 0) $diaSemana = 7; //Dia 0 es domingo, lo cambia a 7 a efectos que el primero sea Lunes
$primerDia = date("Y/m/d", mktime(0, 0, 0, $mesAct, $diaAct - $diaSemana + 1, $anioAct));
/*Fin*/

/*Procedimientos Almacenados*/
try {
  $db = getDB();
  $parametro = $session_uid;
  // execute the stored procedure
  $sql = 'CALL GET_TOTALESV2(' . $parametro . ')';
  // call the stored procedure
  $q = $db->query($sql);
  $q->setFetchMode(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error occurred:" . $e->getMessage());
}

/**/
try {
  $db = getDB();
  $parametro = $session_uid;
  // execute the stored procedure
  $sql = 'CALL INFO_DIARIA(' . $parametro . ')';
  // call the stored procedure
  $q2 = $db->query($sql);
  $q2->setFetchMode(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error occurred:" . $e->getMessage());
}
/*Fin Procedimientos Almacenados*/


/* Api Indicadores Economicos */
$apiUrl = 'https://mindicador.cl/api';
//Es necesario tener habilitada la directiva allow_url_fopen para usar file_get_contents
if ( ini_get('allow_url_fopen') ) {
    $json = file_get_contents($apiUrl);
} else {
    //De otra forma utilizamos cURL
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    curl_close($curl);
} 
$dailyIndicators = json_decode($json);
$valorIPC = $dailyIndicators->ipc->valor;
//echo 'El valor actual de la UF es $' . $dailyIndicators->uf->valor;
/*Fin Api Indicadores Economicos */

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
      <div class="page-header">
        <div class="container-fluid">
          <h2 class="h5 no-margin-bottom">Dashboard Finanzas</h2>
          <!--Update Indicadores-->
          <div class="row">
          <div class="col-xl-2 col-md-2 col-6">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Valor de la UF</strong><span>Día Actual</span>
                    <hr>
                    <div class="number dashtext-7"><?php echo '$' . number_format($dailyIndicators->uf->valor, 2, ",", ".") ?></div>
                  </div>
                </div>
            </div>    
            <div class="col-xl-2 col-md-2 col-6">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Dólar Observado</strong><span>Día Actual</span>
                    <hr>
                    <div class="number dashtext-7"><?php echo '$' . number_format($dailyIndicators->dolar->valor, 2, ",", ".") ?></div>
                  </div>
                </div>
            </div>    
            <div class="col-xl-2 col-md-2 col-6">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Valor Euro</strong><span>Día Actual</span>
                    <hr>
                    <div class="number dashtext-7"><?php echo '$' . number_format($dailyIndicators->euro->valor, 2, ",", ".")  ?></div>
                  </div>
                </div>
            </div>   
            <div class="col-xl-2 col-md-2 col-6">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Valor UTM</strong><span>Día Actual</span>
                    <hr>
                    <div class="number dashtext-7"><?php echo '$' .number_format($dailyIndicators->utm->valor, 0, ",", ".")  ?></div>
                  </div>
                </div>
            </div>      
            <div class="col-xl-2 col-md-2 col-6">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Valor IPC</strong><span>Día Actual</span>
                    <hr>
                    <div class="number dashtext-7"><?php echo '$' . number_format($valorIPC,2,",",".") ?></div>
                  </div>
                </div>
            </div>                                            
            </div>
            <!--Update Indicadores--> 
        </div>
      </div>
      <section class="no-padding-top no-padding-bottom">
        <div class="container-fluid">
          <div class="row">
            <?php while ($r = $q->fetch()) : ?>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Ingresos</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-1"><?php echo '$' . number_format($r['INGRESOS'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-credit-card-alt" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Gastos</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-3"><?php echo '$' . number_format($r['GASTOS'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total General</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-2"><?php echo '$' . number_format($r['TOTAL'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
      <section class="no-padding-top no-padding-bottom">
        <div class="container-fluid">
          <div class="row">
            <?php while ($r2 = $q2->fetch()) : ?>
              <div class="col-xl-3 col-md-3 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-cutlery" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Alimentación</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-1"><?php echo '$' . number_format($r2['GASTOS_ALIMENTACION'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-md-3 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-beer" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Distracciones</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-3"><?php echo '$' . number_format($r2['GASTOS_DISTRACCIONES'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-md-3 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-bug" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Gastos Hormiga</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-5"><?php echo '$' . number_format($r2['GASTOS_HORMIGA'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-md-3 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-credit-card" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Cuentas</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-2"><?php echo '$' . number_format($r2['GASTOS_CUENTAS'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
      <section class="no-padding-bottom">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 col-lg-5">
              <div class="statistic-block block">
                <div class="name"><strong class="text-uppercase">Total Gastos Semana Actual: <?php montoSemanal($primerDia, $parametro);?></strong></div>
                <hr>
                <div class="table-responsive">
                  <div class="listaditoGastos">
                    <table class="table table-bordered">
                      <tr>
                        <th width="10%">Total </th>
                        <th width="20%">Dia</th>
                        <th width="20%">Fecha</th>
                      </tr>
                      <?php totalSemanal($primerDia, $parametro); ?>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="col-12 col-lg-7">
              <div class="statistic-block block">
                <div class="name"><strong class="text-uppercase">Últimos gastos registrados hoy, <?php echo $fecha; ?></strong></div>
                <!-- <h2 class="display h4">Últimos gastos registrados hoy, <?php echo $fecha; ?> </h2> -->
                <p>Total Gastado Hoy: <?php totalDiario($parametro); ?></p>
                <div class="table-responsive">
                  <div class="listaditoGastos">
                    <table class="table table-bordered">
                      <tr>
                        <th width="10%">Monto </th>
                        <th width="30%">Observaciones</th>
                        <th width="20%">Tipo de Gasto</th>
                      </tr>
                      <?php detalleDiario($parametro); ?>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <?php include("footer.php"); ?>
    </div>
  </div>
  <!-- JavaScript files-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/popper.js/umd/popper.min.js"> </script>
  <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="../vendor/jquery.cookie/jquery.cookie.js"> </script>
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="../vendor/jquery-validation/jquery.validate.min.js"></script>
  <!-- <script src="../js/charts-home.js"></script>     -->
  <script src="../js/front.js"></script>

</body>

</html>