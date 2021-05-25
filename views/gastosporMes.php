<?php
require '../controllers/funciones.php';
include('../db/config.php');
include('../session.php');
$userDetails = $userClass->userDetails($session_uid);
$mesActual = $meses[date('n') - 1];
$anioActual = date("Y");
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
                    <h2 class="h5 no-margin-bottom">Listado de Gastos por Mes </h2>
                </div>
            </div>
            
            <!-- Breadcrumb-->
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Gastos por Mes</li>
                </ul>
            </div>

            <section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong>Listado de Gastos por Mes</strong></div>
                                <div class="block-body">
                                    <label class="col-8 col-md-6" id="tipoIngreso"><i class="fas fa-dollar-sign"></i> Mes a Consultar: </label>
                                    <div class="col-sm-4 col-md-8">
                                        <select class="custom-select" id="selectIng" name="tipoIngreso" required>
                                            <option value='' disabled selected>SELECCIONE MES</option>
                                            <option value='1'>Enero</option>
                                            <option value='2'>Febrero</option>
                                            <option value='3'>Marzo</option>
                                            <option value='4'>Abril</option>
                                            <option value='5'>Mayo</option>
                                            <option value='6'>Junio</option>
                                            <option value='7'>Julio</option>
                                            <option value='8'>Agosto</option>
                                            <option value='9'>Septiembre</option>
                                            <option value='10'>Octubre</option>
                                            <option value='11'>Noviembre</option>
                                            <option value='12'>Diciembre</option>
                                        </select>
                                    </div>
                                    <hr>
                                    <table id="listadoGastos" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Monto</th>
                                                <th scope="col">Observaciones</th>
                                                <th scope="col">Tipo de Gasto</th>
                                            </tr>
                                        </thead>
                                        <?php $uid = $userDetails->uid; ?>
                                        <tbody id="cuerpoGastos">
                                        </tbody>
                                    </table>
                                    <hr>
                                    <input type="button" id="exportar" class="btn btn-info" value="Exportar a PDF">
                                    <input type="button" id="exportar2" class="btn btn-info" value="Exportar a Excel">
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
    <script>
        // Get the modal
        $(function() {
            var ExportButtons = document.getElementById('listadoGastos');  
            $("#exportar").attr("disabled", true);
            $("#exportar2").attr("disabled", true);
            
            /*Script Mes*/
            $("#selectIng").on("change", function(e) {
                e.preventDefault();
                var idMes = $(this).val();
                var idU = "<?php echo $uid; ?>";
                var anio = "<?php echo $anioActual; ?>";
                $.ajax({
                    type: 'POST',
                    url: '../controllers/funciones.php',
                    data: {
                        action: "buscaGasto",
                        valor: idMes,
                        id: idU,
                        anioact: anio
                    },
                    success: function(data) {
                        //console.log(data.length);
                        if (data.length > 0) {
                            $("#cuerpoGastos").hide(500);                        
                            $("#cuerpoGastos").html(data);
                            $("#cuerpoGastos").show(500);
                            $("#exportar").attr("disabled", false);
                            $("#exportar2").attr("disabled", false);



                        }else{
                            $("#cuerpoGastos").hide(500); 
                            $("#exportar").attr("disabled", true);
                            $("#exportar2").attr("disabled", true);
                        }                        
                    }
                });
            });
            /*Fin Script Mes*/

            /*Script Exportar a PDF*/
            $("#exportar").click(function() {
                var idMes = $("#selectIng").val();
                var idU = "<?php echo $uid; ?>";
                var anio = "<?php echo $anioActual; ?>";
                window.open("../controllers/verPDF.php?action=verPDF&idMes=" + idMes + "&id=" + idU + "&anio=" + anio);
            });
            /*Fin Script Exportar a PDF*/

               /*Script Exportar Excel*/
            $(document).on('click', '#exportar2', function(e) {                                
                let selectElement = document.getElementById("selectIng");
                var mes = selectElement.options[selectElement.selectedIndex].text; 
                let anio = new Date().getFullYear();
                console.log(mes);               
                TableExport.prototype.typeConfig.date.assert = function(value){return false;}; //Función que corrige problemas de Fechas
                var instance = new TableExport(ExportButtons, {
                        filename: 'Listado Gastos del Mes de ' + mes + ' de ' + anio,
                        formats: ['xlsx'],
                        exportButtons: false
                });            
                var exportData = instance.getExportData()['listadoGastos']['xlsx'];
                instance.export2file(exportData.data, exportData.mimeType, exportData.filename, exportData.fileExtension);
                // var exportaraExcel = document.getElementById("exportar2");
                // exportaraExcel.addEventListener('click', function (e) {                
                //     instance.export2file(exportData.data, exportData.mimeType, exportData.filename, exportData.fileExtension);
                // });   
            });
            /*Fin Script Exportar Excel*/

          
        });
    </script>
</body>