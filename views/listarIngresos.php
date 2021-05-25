<?php
require '../controllers/funciones.php';
include('../db/config.php');
include('../session.php');
$userDetails = $userClass->userDetails($session_uid);
$mesActual = $meses[date('n') - 1];
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
                    <h2 class="h5 no-margin-bottom">Listado de Ingresos por Mes </h2>
                </div>
            </div>

            <!-- Breadcrumb-->
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Ingresos por Mes</li>
                </ul>
            </div>

            <section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong>Listado de Ingresos por Mes</strong></div>
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
                                    <table id="listadoIngresos" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Monto</th>
                                                <th scope="col">Observaciones</th>
                                                <th scope="col">Tipo de Ingreso</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <?php $uid = $userDetails->uid; ?>
                                        <tbody id="cuerpoIngresos">
                                        </tbody>
                                    </table>
                                    <hr>
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
        $(function() {
            /*Script Mes*/
            $("#selectIng").on("change", function(e) {
                e.preventDefault();
                var idMes = $(this).val();
                var idU = "<?php echo $uid; ?>";
                $.ajax({
                    type: 'POST',
                    url: '../controllers/funciones.php',
                    data: {
                        action: "buscaIngreso",
                        valor: idMes,
                        id: idU
                    },
                    success: function(data) {
                        //console.log(data.length);
                        if (data.length > 0) {
                            $("#cuerpoIngresos").hide(500);
                            $("#cuerpoIngresos").html(data);
                            $("#cuerpoIngresos").show(500);                                                     
                        } else {
                            $("#cuerpoIngresos").hide(500);
                        }
                    }
                });
            });
            /*Fin Script Mes*/
            /*Script Eliminar*/            
            $(document).on('click', '#eliminar', function(e) {
                //$("#eliminar").click(function(e) {                
                e.preventDefault();
                console.log("Entró..");
                var id = $(this).attr('data-id');
                var parent = $(this).parent("td").parent("tr");                
                    bootbox.dialog({
                        message: "¿Seguro que desea eliminar?",
                        title: "Eliminar Ingreso",
                        buttons: {
                            danger: {
                                label: "Si",
                                className: "btn-danger",
                                callback: function() {
                                    $.ajax({
                                            type: 'POST',
                                            url: '../controllers/funciones.php',
                                            data: {
                                                action: "eliminarIngreso",
                                                valor: id
                                            },
                                        })
                                        .done(function(response) {
                                            bootbox.alert(response);
                                            parent.fadeOut('slow');
                                        })
                                        .fail(function() {
                                            bootbox.alert('Error al eliminar ....');
                                        })
                                }
                            },
                            success: {
                                label: "No",
                                className: "btn-success",
                                callback: function() {
                                    // cancel button, close dialog box
                                    $('.bootbox').modal('hide');
                                }
                            }
                        }
                    });
            });
        }); //Fin Document Ready
    </script>
</body>

</html>