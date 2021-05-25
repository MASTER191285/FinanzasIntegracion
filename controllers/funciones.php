<?php
		if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
			switch ($_REQUEST['action']) {
				case 'eliminarGasto':
				$valor = $_REQUEST['valor'];
				return eliminarGasto($valor);
				break;
				case 'eliminarIngreso':
				$valor = $_REQUEST['valor'];
				return eliminarIngreso($valor);
				break;
				case 'buscaGasto':
				$valor = $_REQUEST['valor'];
				$id = $_REQUEST['id'];
				$anioact = $_REQUEST['anioact'];
				return buscaGasto($valor,$id, $anioact);
				break;	
				case 'buscaGastoxAnio':
				$valor = $_REQUEST['valor'];
				$id = $_REQUEST['id'];
				$anioact = $_REQUEST['anioact'];
				return buscaGastoxAnio($valor,$id, $anioact);
				break;
				case 'buscaIngreso':
				$valor = $_REQUEST['valor'];
				$id = $_REQUEST['id'];
				return buscaIngreso($valor,$id);
				break;								
				default:
				break;
			}
		}
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");



		#region AlimentaCombos
		function getTipoIngreso(){

			try {
				getcwd();				
				$db = getDB();
				$query = "SELECT id, descripcion FROM tipo_ingreso";
				$stmt = $db->prepare($query);

				$stmt->execute();   				
				//var_dump($stmt);
				echo "<option value='' disabled selected>SELECCIONE TIPO INGRESO</option>";			
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					extract($row);							
					echo'<OPTION VALUE="'.$row['id'].'">'.strtoupper($row['descripcion']).'</OPTION>';  
				}    				
			}catch (Exception $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		
		}

		function getTipoGasto(){

			try {
				getcwd();				
				$db = getDB();
				$query = "SELECT id, descripcion FROM tipo_gasto";
				$stmt = $db->prepare($query);

				$stmt->execute();				
				echo "<option value='' disabled selected>SELECCIONE TIPO GASTO</option>";			
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					extract($row);							
					echo'<OPTION VALUE="'.$row['id'].'">'.strtoupper($row['descripcion']).'</OPTION>';  
				}    				
			}catch (Exception $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		
		}

		function getAnioListados(){

			try {
				getcwd();				
				$db = getDB();
				$query = "SELECT DISTINCT YEAR(fecha) as Anio FROM GASTOS";
				$stmt = $db->prepare($query);

				$stmt->execute();				
				echo "<option value='' disabled selected>SELECCIONE AÑO</option>";			
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					extract($row);							
					echo'<OPTION VALUE="'.$row['Anio'].'">'.strtoupper($row['Anio']).'</OPTION>';  
				}    				
			}catch (Exception $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		
		}
		#endRegion		


		function insertarIngreso(){			

     		try{    
     			getcwd();  	
     			$db = getDB();
     			$mensaje = "";     							
				$monto=htmlspecialchars(strip_tags(str_replace(".", "", $_POST['monto'])));
		        $fecha=htmlspecialchars(strip_tags($_POST['fecha']));
		        $tipoIngreso=htmlspecialchars(strip_tags($_POST['tipoIngreso']));
		        if (strlen(htmlspecialchars(strip_tags($_POST['observaciones']))) == 0) {
		        	$observaciones = "Sin Observaciones";
		        }else{
		        	$observaciones=htmlspecialchars(strip_tags($_POST['observaciones']));
		        }		        
		        $id_usuario=htmlspecialchars(strip_tags($_POST['id_user']));

		        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		        $query = "INSERT INTO ingresos (monto,fecha, id_tipo_ingreso, observaciones, id_usuario) values(?, ?, ?, ?, ?)";
		        $inserccion = $db->prepare($query);
				$inserccion->execute(
				array(
					$monto
					,date($fecha)
					,$tipoIngreso
					,$observaciones
					,$id_usuario
				));            
                 
		        if($inserccion){		            
		            $mensaje = "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
		            $mensaje.= "<strong>Exito!</strong> Ingreso Insertado.";
		            $mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
		            $mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
		            echo $mensaje;
		        }else{
					$mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
		            $mensaje.= "<strong>Error!</strong> Error al grabar.";
		            $mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
		            $mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
		            echo $mensaje;
		        }
         
    		}     	
    		catch(PDOException $exception){
        	die('ERROR: ' . $exception->getMessage());
    		}
    	}

		function insertarGasto(){			

     		try{    
     			getcwd();  	
     			$db = getDB();
     			$mensaje = "";
				$monto=htmlspecialchars(strip_tags(str_replace(".", "", $_POST['monto'])));
		        $fecha=htmlspecialchars(strip_tags($_POST['fecha']));
		        $tipoGasto=htmlspecialchars(strip_tags($_POST['tipoGasto']));		        
		        if (strlen(htmlspecialchars(strip_tags($_POST['observaciones']))) == 0) {
		        	$observaciones = "Sin Observaciones";
		        }else{
		        	$observaciones=htmlspecialchars(strip_tags($_POST['observaciones']));
		        }
		        $id_usuario=htmlspecialchars(strip_tags($_POST['id_user']));

		        /*Lógica de subida de archivo*/
		        $directorio = "../uploads/";
				$archivo = basename($_FILES["comprobante"]["name"]);
				$destino = $directorio . $archivo;
				$tipoArchivo = pathinfo($destino,PATHINFO_EXTENSION);
				// Extensiones permitidas
				$extPermitida = array('jpg','png','jpeg','JPG','PNG','JPEG');
				
				if (empty($_FILES["comprobante"]["name"])) {
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					        $query = "INSERT INTO gastos (monto,fecha, id_tipo_gasto, observaciones, id_usuario) values(?, ?, ?, ?, ?)";
					        $inserccion = $db->prepare($query);

							$inserccion->execute(
								array(
									$monto
									,date($fecha)
									,$tipoGasto
									,$observaciones
									,$id_usuario									
							));

					        if($inserccion){		            
					            $mensaje = "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
					            $mensaje.= "<strong>Exito!</strong> Gasto Insertado.";
					            $mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
					            $mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
					            echo $mensaje;

					        }else{
								$mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
					            $mensaje.= "<strong>Error!</strong> Error al grabar.";
					            $mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
					            $mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
					            echo $mensaje;
					        }
					}else{
						if(in_array($tipoArchivo, $extPermitida)){
				        	// Subir archivo
				        	if(move_uploaded_file($_FILES["comprobante"]["tmp_name"], $destino)){				            
					        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					        $query = "INSERT INTO gastos (monto,fecha, id_tipo_gasto, observaciones, id_usuario, comprobante) values(?, ?, ?, ?, ?, ?)";
					        $inserccion = $db->prepare($query);

							$inserccion->execute(
								array(
									$monto
									,date($fecha)
									,$tipoGasto
									,$observaciones
									,$id_usuario
									,$archivo
							));

					        if($inserccion){		            
					            $mensaje = "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
					            $mensaje.= "<strong>Exito!</strong> Gasto Insertado.";
					            $mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
					            $mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
					            echo $mensaje;

					        }else{
								$mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
					            $mensaje.= "<strong>Error!</strong> Error al grabar.";
					            $mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
					            $mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
					            echo $mensaje;
					        }

				        	}else{
								$mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
					            $mensaje.= "<strong>Error!</strong> Error al Subir el Archivo.";
					            $mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
					            $mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
					            echo $mensaje;
				        		}
				    		}else{
								$mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
					            $mensaje.= "<strong>Error!</strong> Extensión de archivo no permitida.";
					            $mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
					            $mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
					            echo $mensaje;
			            
				   			 }		
				}		
    		}     	
    		catch(PDOException $exception){
        	die('ERROR: ' . $exception->getMessage());
    		}
		}
		
		function listarGastosMes($fecha, $uid){
			
			try {
				getcwd();								
				$db = getDB();
				// $page = isset($_GET['page']) ? $_GET['page'] : 1;
				// $records_per_page = 10;
				// $from_record_num = ($records_per_page * $page) - $records_per_page;
				$query = 'SELECT g.id, g.monto ,g.fecha ,g.comprobante ,g.observaciones  ,tg.descripcion FROM  gastos g INNER JOIN 
				tipo_gasto tg ON g.id_tipo_gasto=tg.id WHERE fecha BETWEEN :fechaini AND CURDATE() AND id_usuario=:id_usuario ORDER BY g.fecha';
				$stmt = $db->prepare($query);
				$stmt->bindParam("fechaini", $fecha, PDO::PARAM_STR, 10);
				$stmt->bindParam("id_usuario", $uid,PDO::PARAM_INT);
				// $stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
				// $stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
				$stmt->execute();
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					extract($row);
					echo '<tr>';					
					echo '<td>'. date("d/m/Y", strtotime($row['fecha'])).'</td>';		
					echo '<td>'. number_format($row['monto'], 0,",",".").'</td>';													
					echo "<td>"."<a class='pop'>"."<img width='100px' alt='Sin Imagen' id='comprobante' src='../uploads/" .  $row['comprobante'] . "'/>"."</a>"."</td>";
					echo '<td>'. $row['observaciones'].'</td>';
					echo '<td>'. $row['descripcion'].'</td>';
					echo '<td>';										
					echo "<a href='updateGasto.php?id={$id}' class='btn btn-info btn-xs editar'>Editar </a>";
					echo "<a href='javascript:void(0)' data-id='{$id}' class='btn btn-danger btn-xs eliminar'>Eliminar </a>";					
					echo '</td>';
					echo '</tr>';					
				}
				// echo '</table>';
				// PAGINATION
				// count total number of rows
				// $query = "SELECT COUNT(*) as total_rows FROM gastos";
				// $stmt = $db->prepare($query);				
				// // execute query
				// $stmt->execute();				
				// // get total rows
				// $row = $stmt->fetch(PDO::FETCH_ASSOC);
				// $total_rows = $row['total_rows'];
				// $page_url="listarGastos.php?";
				// include_once "paginacion.php";

			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
			
		}

		function buscaGasto($idMes, $id, $anioact){
			
			try {
				require_once('../db/config.php');
				getcwd();								
				$db = getDB();
				$query = 'SELECT g.id, g.monto ,g.fecha ,g.observaciones ,tg.descripcion FROM  gastos g INNER JOIN 
				tipo_gasto tg ON g.id_tipo_gasto=tg.id WHERE MONTH(fecha)=:idMes AND id_usuario=:id AND YEAR(fecha)=:anioact ORDER BY g.fecha';
				$stmt = $db->prepare($query);				
				$stmt->bindParam("idMes", $idMes,PDO::PARAM_INT);
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->bindParam("anioact", $anioact,PDO::PARAM_INT);
				$stmt->execute();
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					extract($row);
					echo '<tr>';					
					echo '<td>'. date("d/m/Y", strtotime($row['fecha'])).'</td>';		
					//echo '<td>'. number_format($row['monto'], 0,",",".").'</td>';			
					echo '<td>'. $row['monto'].'</td>';
					echo '<td>'. $row['observaciones'].'</td>';
					echo '<td>'. $row['descripcion'].'</td>';
					echo '</tr>';					
				}
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}			
		}

		function buscaGastoxAnio($idMes, $id, $anioact){
			
			try {
				require_once('../db/config.php');
				getcwd();								
				$db = getDB();
				$query = 'SELECT g.id, g.monto ,g.fecha ,g.observaciones ,tg.descripcion FROM  gastos g INNER JOIN 
				tipo_gasto tg ON g.id_tipo_gasto=tg.id WHERE MONTH(fecha)=:idMes AND id_usuario=:id AND YEAR(fecha)=:anioact ORDER BY g.fecha';
				$stmt = $db->prepare($query);				
				$stmt->bindParam("idMes", $idMes,PDO::PARAM_INT);
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->bindParam("anioact", $anioact,PDO::PARAM_INT);
				$stmt->execute();
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					extract($row);
					echo '<tr>';					
					echo '<td>'. date("d/m/Y", strtotime($row['fecha'])).'</td>';		
					//echo '<td>'. number_format($row['monto'], 0,",",".").'</td>';			
					echo '<td>'. $row['monto'].'</td>';
					echo '<td>'. $row['observaciones'].'</td>';
					echo '<td>'. $row['descripcion'].'</td>';
					echo '</tr>';					
				}
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}			
		}		

		function buscaIngreso($idMes, $id){
			
			try {
				require_once('../db/config.php');
				getcwd();								
				$db = getDB();
				$query = 'SELECT i.id, i.monto AS monto, i.fecha AS fecha, i.observaciones AS obs, ti.descripcion AS descripcion FROM ingresos i INNER JOIN 
				tipo_ingreso ti ON i.id_tipo_ingreso=ti.id WHERE MONTH(fecha)=:idMes AND id_usuario=:id ORDER BY i.fecha';
				$stmt = $db->prepare($query);				
				$stmt->bindParam("idMes", $idMes,PDO::PARAM_INT);
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->execute();
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					extract($row);
					echo '<tr>';					
					echo '<td>'. date("d/m/Y", strtotime($row['fecha'])).'</td>';		
					echo '<td>'. number_format($row['monto'], 0,",",".").'</td>';																		
					echo '<td>'. $row['obs'].'</td>';
					echo '<td>'. $row['descripcion'].'</td>';
					echo '<td>';										
					echo "<a href='updateIngreso.php?id={$id}' class='btn btn-info btn-xs editar'>Editar </a>";
					echo "<a href='javascript:void(0)' id='eliminar' data-id='{$id}' class='btn btn-danger btn-xs eliminar'>Eliminar </a>";					
					echo '</td>';
					echo '</tr>';					
				}				
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
		}
		
		function exportarPDF($idMes, $id, $anioact){

			try {
				require_once('../db/config.php');
				getcwd();								
				$db = getDB();				
				$query = 'SELECT g.monto ,g.fecha ,g.observaciones ,tg.descripcion FROM  gastos g INNER JOIN 
				tipo_gasto tg ON g.id_tipo_gasto=tg.id WHERE MONTH(fecha)=:idMes AND id_usuario=:id AND YEAR(fecha)=:anio ORDER BY g.fecha';
				$stmt = $db->prepare($query);				
				$stmt->bindParam("idMes", $idMes,PDO::PARAM_INT);
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->bindParam("anio", $anioact,PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->rowCount() > 0) {

					$rawdata = array();
					$i=0;
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$rawdata[$i] = $row;
						$i++;
					}

					return json_encode($rawdata);

				}else{
					echo "No hay datos para los criterios ingresados";
				}

			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
		}

		function ingresoaActualizar($id){

			try {
				getcwd();								
				$db = getDB();
				$query = "SELECT i.id, i.monto as monto, i.fecha as fecha, i.observaciones as observaciones, ti.id as idtipo, ti.descripcion as descripcion FROM ingresos i INNER JOIN tipo_ingreso ti ON i.id_tipo_ingreso=ti.id WHERE i.id=:id";
				$stmt = $db->prepare($query);
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				$tIngresos = "SELECT id, descripcion FROM tipo_ingreso";
				$q2 = $db->query($tIngresos);
				$q2->setFetchMode(PDO::FETCH_ASSOC);
				
				$monto = number_format($row['monto'], 0,",",".");
				$fecha = $row['fecha'];
				$observaciones = $row['observaciones'];
				$idTipo = $row['idtipo'];
				$descripcion = $row['descripcion'];

				echo '<label class="col-8 col-md-6" id="tipoIngreso"><i class="fas fa-dollar-sign"></i> Tipo de Ingreso: </label>';
				echo '<div class="col-sm-4 col-md-6">';
				echo '<select class="custom-select" id="selectIng" name="tipoIngreso" required>';				
				echo '<option value="'.$idTipo.'">'.strtoupper($descripcion).'</option>';  
				while ($r = $q2->fetch()){
					echo'<option value="'.$r['id'].'">'.strtoupper($r['descripcion']).'</option>';  
				 
				}
				echo '</select>';
				echo '</div>';
				echo "
				<hr>
				<label class='col-sm-2' id='monto'><i class='far fa-money-bill-alt'></i> Monto: </label>
				<div class='col-sm-4'>
				  <input type='text' name='monto' value=$monto class='form-control' >
				</div>
				<hr>		
				<label class='col-sm-4' id='fecha'><i class='fas fa-calendar-alt'></i> Fecha: </label>
				<div class='col-sm-4'>
				  <input type='date' required='' name='fecha' value=$fecha>
				</div>
				<hr>      
				<label for='observaciones' id='observaciones'>Observaciones:</label>
				<textarea class='form-control' id='txtObservaciones' name='observaciones' rows='3' cols='30' maxlength='100'>$observaciones</textarea>
				<hr>
				";
					
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
		}

		function gastoaActualizar($id){
			
			try {
				getcwd();								
				$db = getDB();
				$query = "SELECT g.id, g.monto as monto, g.fecha as fecha, g.observaciones as observaciones, g.comprobante as comprobante, tg.id as idtipo, tg.descripcion as descripcion FROM gastos g INNER JOIN tipo_gasto tg ON g.id_tipo_gasto=tg.id WHERE g.id=:id";
				$stmt = $db->prepare($query);
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				$tGastos = "SELECT id, descripcion FROM tipo_gasto";
				$q2 = $db->query($tGastos);
				$q2->setFetchMode(PDO::FETCH_ASSOC);   		

				// $monto = $row['monto'];
				$monto = number_format($row['monto'], 0,",",".");
				$fecha = $row['fecha'];
				$observaciones = $row['observaciones'];
				$idTipo = $row['idtipo'];
				$descripcion = $row['descripcion'];				
				$comprobante = $row['comprobante'];
				
				echo '<label class="col-8 col-md-6" id="tipoGasto"><i class="fas fa-dollar-sign"></i> Tipo de Gasto: </label>';
				echo '<div class="col-sm-4 col-md-6">';
				echo '<select class="custom-select" id="selectIng" name="tipoGasto" required>';				
				echo '<option value="'.$idTipo.'">'.strtoupper($descripcion).'</option>';  
				while ($r = $q2->fetch()){
					echo'<option value="'.$r['id'].'">'.strtoupper($r['descripcion']).'</option>';  
				 
				}
				echo '</select>';
				echo '</div>';
				echo "
				<hr>
				<label class='col-sm-2' id='monto'><i class='far fa-money-bill-alt'></i> Monto: </label>
				<div class='col-sm-4'>
				  <input type='text' name='monto' value=$monto class='form-control' >
				</div>
				<hr>		
				<label class='col-sm-4' id='fecha'><i class='fas fa-calendar-alt'></i> Fecha: </label>
				<div class='col-sm-4'>
				  <input type='date' required='' name='fecha' value=$fecha>
				</div>
				<hr>      
				<label for='observaciones' id='observaciones'>Observaciones:</label>
				<textarea class='form-control' id='txtObservaciones' name='observaciones' rows='3' cols='30' maxlength='100'>$observaciones</textarea>
				<hr>
				<label class='col-sm-2' id='comprobante'><i class='fas fa-file-upload'></i> Comprobante: </label>
				<div class='col-sm-4'>
				  <input type='file'  name='comprobante'>
				  <input type='hidden' name='imagen-guardada' value=$comprobante>
				</div>
				<hr>
				";
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
		}

		function ActualizarIngreso($id){

			try {
				getcwd();
				$db = getDB();
				$mensaje = "";				
				if ($_POST) {
					$query = "UPDATE ingresos SET 
					monto=:monto,
					fecha=:fecha,
					observaciones=:observaciones,					
					id_tipo_ingreso=:tipoIngreso
					WHERE id=:id";

					$stmt = $db->prepare($query);
					$monto=htmlspecialchars(strip_tags(str_replace(".", "", $_POST['monto'])));
					$fecha=$_POST['fecha'];
					$observaciones=$_POST['observaciones'];					
					$tipoIngreso=htmlspecialchars(strip_tags($_POST['tipoIngreso']));

					$stmt->bindParam("id", $id,PDO::PARAM_INT);
					$stmt->bindParam(":monto", $monto);
					$stmt->bindParam(":fecha", $fecha);
					$stmt->bindParam(":observaciones", $observaciones);					
					$stmt->bindParam(":tipoIngreso", $tipoIngreso);
												
					if($stmt->execute()){						
						$mensaje = "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
						$mensaje.= "<strong>Exito!</strong> Ingreso Modificado.";
						$mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
						$mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";						
						echo $mensaje;						
						//header('location: ../views/listarGastos.php');
					}else{						
						$mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
						$mensaje.= "<strong>Error!</strong> Error al Modificar.";
						$mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
						$mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
						echo $mensaje;
					}											
				}				
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
		}

		function ActualizarGasto($id){

			try {
				getcwd();
				$db = getDB();
				$mensaje = "";				
				if ($_POST) {

					$query = "UPDATE gastos SET 
					monto=:monto,
					fecha=:fecha,
					observaciones=:observaciones,
					comprobante=:comprobante,
					id_tipo_gasto=:tipoGasto
					WHERE id=:id";

					$stmt = $db->prepare($query);

					// $monto=htmlspecialchars(strip_tags($_POST['monto']));
					$monto=htmlspecialchars(strip_tags(str_replace(".", "", $_POST['monto'])));
					$fecha=$_POST['fecha'];
					$observaciones=$_POST['observaciones'];
					$comprobante_guardado=$_POST['imagen-guardada'];
					$comprobante=$_FILES['comprobante'];
					$tipoGasto=htmlspecialchars(strip_tags($_POST['tipoGasto']));	
					if (empty($comprobante['name'])) {
						$comprobante = $comprobante_guardado;
					}else {
						$archivo_subido = "../uploads/" . $_FILES['comprobante']['name'];
						move_uploaded_file($_FILES['comprobante']['tmp_name'], $archivo_subido);
						$comprobante = $_FILES['comprobante']['name'];
					}			
					$stmt->bindParam("id", $id,PDO::PARAM_INT);
					$stmt->bindParam(":monto", $monto);
					$stmt->bindParam(":fecha", $fecha);
					$stmt->bindParam(":observaciones", $observaciones);
					$stmt->bindParam(":comprobante", $comprobante);
					$stmt->bindParam(":tipoGasto", $tipoGasto);
												
					if($stmt->execute()){						
						$mensaje = "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
						$mensaje.= "<strong>Exito!</strong> Gasto Modificado.";
						$mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
						$mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";						
						echo $mensaje;						
						//header('location: ../views/listarGastos.php');
					}else{						
						$mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
						$mensaje.= "<strong>Error!</strong> Error al Modificar.";
						$mensaje.= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
						$mensaje.= "<span aria-hidden='true'>&times;</span></button></div>";
						echo $mensaje;
					}
				}

			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
		}


		function eliminarGasto($id){

			try {
				require_once('../db/config.php');
				$mensaje = "";
				getcwd();
				$db = getDB();
				$query = "DELETE FROM gastos WHERE id=:id";
				$stmt = $db->prepare($query);
				$stmt->bindParam("id", $id, PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->execute()) {
					$mensaje = "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
					$mensaje.= "<strong>Exito!</strong> Gasto Eliminado.";					
					echo $mensaje;
				}else{
					$mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
					$mensaje.= "<strong>Error!</strong> Error al Eliminar.";
					echo $mensaje;
				}
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}

			return $mensaje;
		}

		function eliminarIngreso($id){

			try {
				require_once('../db/config.php');
				$mensaje = "";
				getcwd();
				$db = getDB();
				$query = "DELETE FROM ingresos WHERE id=:id";
				$stmt = $db->prepare($query);
				$stmt->bindParam("id", $id, PDO::PARAM_INT);
				$stmt->execute();
				if ($stmt->execute()) {
					$mensaje = "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
					$mensaje.= "<strong>Exito!</strong> Ingreso Eliminado.";					
					echo $mensaje;
				}else{
					$mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
					$mensaje.= "<strong>Error!</strong> Error al Eliminar.";
					echo $mensaje;
				}
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}

			return $mensaje;
		}		

		function detalleDiario($uid){
			try {
				getcwd();				
				$db = getDB();
				$query = "SELECT g.id as id, g.monto as monto ,g.observaciones as observaciones ,tg.descripcion as descripcion FROM gastos g INNER JOIN tipo_gasto tg 
				ON g.id_tipo_gasto=tg.id AND g.fecha = CURDATE()  WHERE id_usuario=:id_usuario ORDER BY monto DESC LIMIT 0,5";
				$stmt = $db->prepare($query);
				$stmt->bindParam("id_usuario", $uid, PDO::PARAM_INT);
				$stmt->execute();
				$cantidad = $stmt->rowCount();
				if ($cantidad > 0) {
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						extract($row);
						echo '<tr>';					
						echo '<td>'. number_format($row['monto'], 0,",",".").'</td>';
						echo '<td>'. $row['observaciones'].'</td>';
						echo '<td>'. $row['descripcion'].'</td>';
						echo '</tr>';
					}					
				}else{
					echo '<tr>';
					echo '<td>0</td>';
					echo '<td>Sin registros el día de hoy</td>';
					echo '<td>No aplica</td>';
					echo '</tr>';
				}
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
		}

		function totalDiario($id){
			
			try {
				getcwd();				
				$db = getDB();
				$query = "SELECT SUM(monto) as Total FROM gastos WHERE fecha = CURDATE()  AND id_usuario=:id_usuario";
				$stmt = $db->prepare($query);
				$stmt->bindParam("id_usuario", $id, PDO::PARAM_INT);
				$stmt->execute();
				$cantidad = $stmt->rowCount();
				if ($cantidad > 0) {
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						extract($row);
						echo '$' .  number_format($row['Total'], 0,",",".");
					}
				}else{
					echo "0";
				}
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
		}

		function totalSemanal($fecha_ini, $id){

			try {
				getcwd();				
				$db = getDB();
				/*TOTAL POR DÍA DE ACUERDO A LA SEMANA ACTUAL*/
				$query = "SELECT SUM(monto) AS Total, 
				DATE_FORMAT(fecha,'%d/%m/%Y') AS Fecha,
				CASE DAYNAME(fecha) 
				WHEN 0 THEN 'Lunes'
				WHEN 1 THEN 'Martes'
				WHEN 2 THEN 'Miércoles'
				WHEN 3 THEN 'Jueves'
				WHEN 4 THEN 'Viernes'
				WHEN 5 THEN 'Sábado'
				WHEN 6 THEN 'Domingo'
				END AS Dia 
				FROM gastos WHERE fecha BETWEEN :fechaini AND CURDATE() AND id_usuario=:id_usuario GROUP BY Dia ORDER BY fecha ASC";
				$stmt = $db->prepare($query);
				$stmt->bindParam("fechaini", $fecha_ini, PDO::PARAM_STR, 10);
				$stmt->bindParam("id_usuario", $id, PDO::PARAM_INT);
				$stmt->execute();
				$cantidad = $stmt->rowCount();
				if ($cantidad > 0) {
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						extract($row);
						extract($row);
						echo '<tr>';					
						echo '<td>'. number_format($row['Total'], 0,",",".").'</td>';
						echo '<td>'. $row['Dia'].'</td>';
						echo '<td>'. $row['Fecha'].'</td>';
						echo '</tr>';						
					}
				}else{
					echo "0";
				}
				/*FIN TOTAL POR DÍA DE ACUERDO A LA SEMANA ACTUAL*/

			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}
		}

		function montoSemanal($fecha_ini, $id){

			try {
				getcwd();				
				$db = getDB();							
				/*TOTAL DE ACUERDO A LA SEMANA ACTUAL*/
				$query = "SELECT SUM(monto) AS SumaTotal FROM gastos WHERE fecha BETWEEN :fechaini AND CURDATE() AND id_usuario=:id_usuario";
				$stmt = $db->prepare($query);
				$stmt->bindParam("fechaini", $fecha_ini, PDO::PARAM_STR, 10);
				$stmt->bindParam("id_usuario", $id, PDO::PARAM_INT);
				$stmt->execute();
				$suma = $stmt->rowCount();
				if ($suma > 0) {
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						// echo '<span>';
						echo "$".number_format($row['SumaTotal'], 0,",",".");
						// echo '</span>';
					}
				}else{
					echo "0";
				}
			} catch (PDOException $exception) {
				die('ERROR: ' . $exception->getMessage());
			}

		}
?>
