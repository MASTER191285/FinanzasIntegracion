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



/*Procedimientos Almacenados*/
try {
  $db = getDB();
  $parametro = $session_uid;
  // execute the stored procedure
  $sql = 'CALL RESUMEN_MENSUAL(' . $parametro . ')';
  // call the stored procedure
  $q = $db->query($sql);
  $q->setFetchMode(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error occurred:" . $e->getMessage());
}

/*Fin Procedimientos Almacenados*/

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
        </div>
      </div>
      <section class="no-padding-top no-padding-bottom">
        <div class="container-fluid">
          <div class="row">
            <?php while ($r = $q->fetch()) : ?>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Alimentacion</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-1"><?php echo '$' . number_format($r['GASTOS_ALIMENTACION'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-credit-card-alt" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Distracciones</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-3"><?php echo '$' . number_format($r['GASTOS_DISTRACCIONES'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Gastos Hormiga</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-2"><?php echo '$' . number_format($r['GASTOS_HORMIGA'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Cuentas</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-2"><?php echo '$' . number_format($r['GASTOS_CUENTAS'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Tarjetas</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-3"><?php echo '$' . number_format($r['GASTOS_TARJETAS'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Compras</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-7"><?php echo '$' . number_format($r['COMPRAS_MES'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-female" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Chaparra</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-5"><?php echo '$' . number_format($r['CHAPARRA'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Transporte</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-6"><?php echo '$' . number_format($r['TOTAL_TRANSPORTE'], 0, ",", ".") ?></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-4 col-12">
                <div class="statistic-block block">
                  <div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                  <div class="name"><strong class="text-uppercase">Total Arriendo - GC</strong><span>Mes Actual</span>
                    <hr>
                    <div class="number dashtext-5"><?php echo '$' . number_format($r['TOTAL_ARRIENDOGC'], 0, ",", ".") ?></div>
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
            <div class="col-lg-12 col-sm-12">
              <div class="stats-with-chart-2 block">
                <div class="title"><strong class="d-block">Gráfico de Gastos</strong><span class="d-block"> Porcentaje Según Tipo.</span></div>
                <div class="piechart chart">
                  <canvas id="pieChartHome2"></canvas>
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
  <script>
    $(document).ready(function() {
      var ctx = $('#pieChartHome2');
      var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: [
            <?php
            getcwd();
            $db = getDB();
            $query = "SELECT sum(g.monto) AS TOTALES, tg.descripcion AS DESCRIPCION FROM gastos g INNER JOIN tipo_gasto tg ON g.id_tipo_gasto=tg.id  WHERE g.id_usuario=:id_usuario AND g.fecha BETWEEN :fechaini AND CURDATE() GROUP BY tg.descripcion";
            $stmt = $db->prepare($query);
            $stmt->bindParam("fechaini", $firstDayMonth, PDO::PARAM_STR, 10);
            $stmt->bindParam("id_usuario", $parametro, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>['<?php echo $row['DESCRIPCION'] ?>'],
            <?php
            }
            ?>
          ],
          borderWidth: [1, 1, 1],
          backgroundColor: ["#0074D9", "#2ECC40", "#FF4136", "#9F40FF", "#FFEAA3", "#B10DC9", "#e8c4ff", "#76ff9e", "#ff1153", "#a57600", "#30ffee", "#caff00", "#480068", "#c13c00", "#28006d", "#707300", "#001a6a", "#2cff08"],
          datasets: [{
            data: [
              <?php
              getcwd();
              $db = getDB();
              $query = "SELECT ROUND(sum(g.monto) * 100.0 / (select monto from ingresos where id_tipo_ingreso = 1 and id_usuario=:id_usuario and fecha BETWEEN :fechaini AND CURDATE()),1) as PORCENTAJE
                          ,tg.descripcion AS DESCRIPCION FROM gastos g INNER JOIN tipo_gasto tg ON g.id_tipo_gasto=tg.id WHERE g.id_usuario=:id_usuario AND g.fecha BETWEEN :fechaini AND CURDATE() GROUP BY tg.descripcion ORDER BY tg.descripcion";
              $stmt = $db->prepare($query);
              $stmt->bindParam("fechaini", $firstDayMonth, PDO::PARAM_STR, 10);
              $stmt->bindParam("id_usuario", $parametro, PDO::PARAM_INT);
              $stmt->execute();
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>['<?php echo $row['PORCENTAJE'] ?>'],
              <?php
              }
              ?>
            ],
            borderWidth: [1, 1, 1],
            backgroundColor: ["#0074D9", "#2ECC40", "#FF4136", "#9F40FF", "#FFEAA3", "#B10DC9", "#e8c4ff", "#76ff9e", "#ff1153", "#a57600", "#30ffee", "#caff00", "#480068", "#c13c00", "#28006d", "#707300", "#001a6a", "#2cff08"],
          }]
        }
      });
    });
  </script>
</body>

</html>