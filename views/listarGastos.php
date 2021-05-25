<?php
require '../controllers/funciones.php';
include('../db/config.php');
include('../session.php');
$userDetails = $userClass->userDetails($session_uid);
$mesActual = $meses[date('n')-1];
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
          <h2 class="h5 no-margin-bottom">Listado de Gastos <?php echo "(".$mesActual.")"; ?> </h2>
        </div>
      </div>
      <!-- Breadcrumb-->
      <div class="container-fluid">
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Inicio</a></li>
          <li class="breadcrumb-item active">Listado de Gastos del Mes Actual</li>
        </ul>
      </div>

      <section class="no-padding-top">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <div class="block">
                <div class="title"><strong>Gastos del mes de <?php echo $mesActual; ?></strong></div>
                <div class="block-body">
                  <table id="listadoGastos" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                      <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Monto</th>
                        <th scope="col">Comprobante</th>
                        <th scope="col">Observaciones</th>
                        <th scope="col">Tipo de Gasto</th>
                        <th scope="col">Opciones</th>
                      </tr>
                    </thead>
                    <?php $uid = $userDetails->uid; ?>
                    <?php $first_day = date('Y-m-01');  ?>
                    <tbody>
                      <?php listarGastosMes($first_day, $uid); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="5" style="text-align:right">Total: </th>
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>
                  <!-- The Modal -->
                  <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" data-dismiss="modal">
                      <div class="modal-content">
                        <div class="modal-body">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                          <img src="" class="imagepreview" style="width: 100%;">
                        </div>
                        <div class="modal-footer">
                          <div class="col-xs-12">
                            <p class="text-left">Comprobante Asociado al Gasto</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Fin Modal -->
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
      /*Script PopUP Imagen*/
      //$('.pop').on('click', function() {
      $(document).on('click', '.pop', function(e){
        $('.imagepreview').attr('src', $(this).find('img').attr('src'));
        $('#imagemodal').modal('show');
      });
      /*Fin Script PopUP Imagen*/

      /*Script Eliminar*/
      //$(".eliminar").on("click", function(e) {
      $(document).on('click', '.eliminar', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var parent = $(this).parent("td").parent("tr");
        //bootbox.confirm("Are you sure?", function(result) {
        bootbox.dialog({
          message: "¿Seguro que desea eliminar?",
          title: "Eliminar Gasto",
          buttons: {
            danger: {
              label: "Si",
              className: "btn-danger",
              callback: function() {
                $.ajax({
                    type: 'POST',
                    url: '../controllers/funciones.php',
                    data: {
                      action: "eliminarGasto",
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
      /*Fin Script Eliminar*/

      /*Script Listado*/
      $('#listadoGastos').DataTable({
        "language":  {
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sSearch":         "Buscar:",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
          },          
          "oAria": {
						"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
        },

        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {              
                return typeof i === 'string' ?
                    i.replace(/[\$,.]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
                        
            };
                        
            // Total over all pages
            total = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 1, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            
            var totalF = new Intl.NumberFormat("cl-CL", {style: "currency", currency: "CLP"}).format(pageTotal);
            var totalGF = new Intl.NumberFormat("cl-CL", {style: "currency", currency: "CLP"}).format(total);
            // Update footer
            $( api.column( 1 ).footer() ).html(              
              totalF +' ('+ totalGF +' total)'
                //'$'+pageTotal +' ( $'+ total +' total)'
                
            );
            //new Intl.NumberFormat("cl-CL", {style: "currency", currency: "CLP"}).format(pageTotal);
        }



      });
      /*Fin Script Listado*/
      //$(document).find("#listadoGastos thead th:first-child, #listadoGastos td:first-child").addClass('custom-class');
    });
  </script>
</body>

</html>