<nav id="sidebar">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
          <!-- <div class="avatar"><img src="../img/yo.jpg" alt="..." class="img-fluid rounded-circle"></div> -->
          <?php echo "<div class='avatar'>"; ?>
          <?php echo "<img  src='../img/$userDetails->profile_pic'". "class='img-fluid rounded-circle'". "width='100px'" ."alt='...'". ">"; ?>
          <?php echo "</div>"; ?>
          <div class="title">
            <h1 class="h5"><?php echo $userDetails->name; ?></h1>            
            <!-- <p>Web Designer</p> -->
          </div>
        </div>
        <!-- Sidebar Navidation Menus--><span class="heading">Menú</span>
        <ul class="list-unstyled">
          <li class="active"><a href="dashboard.php"> <i class="icon-home"></i>Inicio </a></li>
          <li><a href="ingresos.php"> <i class="fa fa-money"></i>Ingresos </a></li>
          <li><a href="gastos.php"> <i class="fa fa-money"></i>Gastos </a></li>
          <!-- <li><a href="../charts.html"> <i class="fa fa-bar-chart"></i>Charts </a></li>
          <li><a href="../forms.html"> <i class="icon-padnote"></i>Forms </a></li> -->
          <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Reportes </a>
            <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                <li><a href="listarGastos.php"><i class="fa fa-list" aria-hidden="true"></i>Listado de Gastos del Mes Actual</a></li>
                <li><a href="gastosporMes.php"><i class="fa fa-history" aria-hidden="true"></i>Gastos x Mes</a></li>
                <li><a href="gastosporAnioMes.php"><i class="fa fa-history" aria-hidden="true"></i>Gastos x Mes & Año</a></li>
                <li><a href="resumenxmes.php"><i class="fa fa-usd" aria-hidden="true"></i>Resumen por Concepto</a></li>
            </ul>
          </li>          
          <li><a href="#dropdownMant" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-tasks" aria-hidden="true"></i>Mantenedores </a>
            <ul id="dropdownMant" class="collapse list-unstyled ">
                <li><a href="listarIngresos.php"><i class="fa fa-usd" aria-hidden="true"></i>Mantenedor de Ingresos</a></li>                
            </ul>
          </li>            
        </ul>
        <!-- <span class="heading">Extras</span>
        <ul class="list-unstyled">
          <li> <a href="#"> <i class="icon-settings"></i>Demo </a></li>
          <li> <a href="#"> <i class="icon-writing-whiteboard"></i>Demo </a></li>
          <li> <a href="#"> <i class="icon-chart"></i>Demo </a></li>
        </ul> -->
</nav>