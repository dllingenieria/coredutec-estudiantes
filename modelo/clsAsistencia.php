<?php 
session_start();
ini_set('memory_limit', '4024M');
set_time_limit(0);
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

class clsAsistencia {

    public function consultarEstudiantesAsistieronConvocatoria($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARESTUADIANTESASISTIERONCONVOCATORIA($jornada, $ruta, '$fecha', $usuario);";
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }

    public function consultarUsuariosRegistraronAsistencia($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARUSUARIOSREGISTRARONASISTENCIA($jornada, $ruta, '$fecha');";
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }

    public function consultarAsistenciaPorSalon($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARASISTENCIAPORSALON($idSalon,$sesion);";
        //echo $sql; die();
		if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[$fila['IdTercero']] = $fila['HorasAsistidas'];
                }
            }
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }

    public function consultarUltimaSesionPorSalon($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARULTIMASESION($idSalon);";
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }


  public function agregarAsistenciaGeneral($param) 
    {
        
        extract($param); 
        // print_r($serializedAsistencia);
		$array = array();
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $usuario =  $_SESSION['idUsuario'];
        //$fecha = date('Y-m-d');
        // $sql = "CALL SPAGREGARASISTENCIA($idPreprogramacion,'$fecha', $sesion,$usuario);";
        $sql = "CALL SPAGREGARASISTENCIA('$serializedAsistencia',$usuario);";
        
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {               
                
                $filas = substr($filas[0]['pIdAsistencias'],1);
                $res = explode(",", $filas);
                //var_dump($res);
                foreach ($res as $resul) {
                	//var_dump($resul);
                    $array[] = array('IdAsistencia' => $resul);

                }
             }
        } else {
            $array = 0;
            print_r($conexion->getPDO()->errorInfo()); die();
            
       }
        	//var_dump($array);
            echo json_encode($array);
    }

    public function agregarAsistenciaDetalle($param) {
        extract($param);
        var_dump($serializedAsistenciaD);
		$array = array();
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $usuario = $_SESSION['idUsuario'];
        $rs = null;
        // $sql = "CALL SPAGREGARASISTENCIADETALLE($idAsistencia, $idTercero, $valorAsistencia,  $idAsistenciaDetalle, $usuario);";
        $sql = "CALL SPAGREGARASISTENCIADETALLE('$serializedAsistenciaD', $usuario);";
        //print_r($sql);
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
        } else {
            $array = 0;
			print_r($conexion->getPDO()->errorInfo()); die();
        }
		// $array = 0;
        echo json_encode($array);
    }
	
	   public function agregarAsistenciaObservacion($param) {
        extract($param);
		$array = array();
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $usuario = $_SESSION['idUsuario'];
        // $sql = "CALL SPAGREGARASISTENCIAOBSERVACION($idAsistencia, $idTercero, '$observacion',$idPreprogramacion, $usuario);";
        $sql = "CALL SPAGREGARASISTENCIAOBSERVACION('$serializedAsistenciaO', $usuario);";
        
        //print_r($sql);
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
        } else {
            $array = 0;
			print_r($conexion->getPDO()->errorInfo()); die();
        }
		// $array = 0;
        echo json_encode($array);
    }

    public function consultarAsistenciaEstudiantes($param) {
        extract($param);
		$rs = null;
        $resultado = array();
        $registro = array();
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARASISTENCIAPORSALON($idPreprogramacion,$sesion);";
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    foreach ($fila as $key => $value) {
                        array_push($registro, $value);
                    }
                    array_push($resultado, $registro);
                    $registro = array();
                }
            }
        } else {
            $registro = 0;
        }
        echo json_encode($resultado);
    }
	
	/*
	*Funcion consultarAsistenciasPorSalon
	*params: IdPreprogramacion
	*return: array con IdTercero y horas asistidas
	*/
	public function consultarAsistenciasPorSalon($param) {
        extract($param);
		$rs = null;
        $resultado = array();
        $registro = array();
        $conexion->getPDO()->query("SET NAMES 'utf8'");
		for ($i=0; $i<=$registros;$i++){
			$sql = "CALL SPCONSULTARASISTENCIASPORSALON($idPreprogramacion);";
			if ($rs = $conexion->getPDO()->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
						foreach ($fila as $key => $value) {
							array_push($registro, $value);
						}
						array_push($resultado, $registro);
						$registro = array();
					}
				}
			} else {
				$registro = 0;
			} //print_r($resultado); die();
		}
        echo json_encode($resultado);
    }
	
	/*
	*Funcion consultarAsistenciaPorPreprogramacion
	*params: IdPreprogramacion
	*return: array los datos de asistencias
	*/
	public function consultarAsistenciaPorPreprogramacion($param) {
        extract($param);
		$rs = null;
		$array = array();
        //print_r("llego");
        $conexion->getPDO()->query("SET NAMES 'utf8'");
			$sql = "CALL SPCONSULTARASISTENCIASPORPREPROGRAMACION($idPreprogramacion);";
			//print_r($sql);
			if ($rs = $conexion->getPDO()->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
						$array[] = $fila;
					}
				}
			} else {
				$array = 0;
				print_r($conexion->getPDO()->errorInfo()); die();
			} //print_r($array);
			//print_r($array);
        echo json_encode($array);
    }
	
	/*
	*Funcion consultarObservacionesPorTercero
	*params: IdPreprogramacion
	*return: array los datos de las observaciones por tercero
	*/
	public function consultarObservacionesPorTercero($param) {
        extract($param);
		$rs = null;
        $array = array();
        $conexion->getPDO()->query("SET NAMES 'utf8'");
	//for ($i=0; $i<count($idTerceroHorasTotales);$i++){
			$sql = "CALL SPCONSULTAROBSERVACIONESPORTERCERO($idPreprogramacion, $idTerceroHorasTotales);";
			if ($rs = $conexion->getPDO()->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
						$array[] = $fila;
					}
					unset($rs);
				}
			} else {
				$array = 0;
				print_r($conexion->getPDO()->errorInfo()); die();
			} //print_r($array); die();
		//}
        echo json_encode($array);
    }
	
	/*
	*Funcion CargarMotivosNoAsistencia
	*params: Ninguno
	*return: array los motivos de las no asistencias
	*/
	public function CargarMotivosNoAsistencia($param) {
        extract($param);
        $rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
	//for ($i=0; $i<count($idTerceroHorasTotales);$i++){
			$sql = "CALL SPCARGARMOTIVOSNOASISTENCIA();";
			if ($rs = $conexion->getPDO()->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
						$array[] = $fila;
					}
					unset($rs);
				}
			} else {
				$array = 0;
				print_r($conexion->getPDO()->errorInfo()); die();
			} //print_r($array);
		//}
        echo json_encode($array);
    }
	
	/*
	*Funcion agregarMotivoNoAsistencia
	*params: $idAsistencia, $idTercero, '$motivo',$idPreprogramacion, $usuario
	*return: array si inserto los datos
	*/
	 public function agregarMotivoNoAsistencia($param) {
        extract($param);
		$array = array();
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $usuario = $_SESSION['idUsuario']; //FALTA EL NOMBRE
        // $sql = "CALL SPAGREGARMOTIVONOASISTENCIA($idAsistencia, $idTercero, '$motivo',$idPreprogramacion, $usuario);";
        $rs = null;
		$sql = "CALL SPAGREGARMOTIVONOASISTENCIA('$serializedAsistenciaM', $usuario);";
        
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
        } else {
            $array = 0;
			print_r($conexion->getPDO()->errorInfo()); die();
        }
		// $array = 0;
        echo json_encode($array);
    }
	
	/*
	*Funcion consultarMotivosNoAsistenciaPorTercero
	*params: $idPreprogramacion, $idTerceroHorasTotales
	*return: array los datos de los motivos no asistencia por tercero
	*/
	public function consultarMotivosNoAsistenciaPorTercero($param) {
        extract($param);
		$rs = null;
        $array = array();
        $conexion->getPDO()->query("SET NAMES 'utf8'");
	//for ($i=0; $i<count($idTerceroHorasTotales);$i++){
			$sql = "CALL SPCONSULTARMOTIVOSNOASISTENCIAPORTERCERO($idPreprogramacion, $idTerceroHorasTotales);";
			if ($rs = $conexion->getPDO()->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
						$array[] = $fila;
					}
					unset($rs);
				}
			} else {
				$array = 0;
				print_r($conexion->getPDO()->errorInfo()); die();
			} //print_r($array);
		//}
        echo json_encode($array);
    }
	
	/*
	*Funcion consultarNotasPorTercero
	*params: IdPreprogramacion, idTercero
	*return: array los datos de la nota por tercero
	*/
	public function consultarNotasPorTercero($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
	//for ($i=0; $i<count($idTerceroHorasTotales);$i++){
			$sql = "CALL SPCONSULTARNOTASPARAASISTENCIAS($idPreprogramacion, ".$idTerceroHorasTotales[0].");";
			// $sql = "CALL SPCONSULTARNOTASPORSALON($idPreprogramacion);";
			
			if ($rs = $conexion->getPDO()->query($sql)) {
				$count=0;
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
									$idTercero = $fila['IdTercero'];
									$notaHacer = explode(',',$fila['NotaHacer']);
									$notaSaber = explode(',',$fila['NotaSaber']);
									$notaSer = explode(',',$fila['NotaSer']);
									
									$totalNotas[] =  $notaHacer[0];
									$totalNotas[] =  $notaHacer[1];
									$totalNotas[] =  $notaHacer[2];
									$totalNotas[] =  $notaSaber[0];
									$totalNotas[] =  $notaSaber[1];
									$totalNotas[] =  $notaSaber[2];
									$totalNotas[] =  $notaSer[0];
									$totalNotas[] =  $notaSer[1];
									$totalNotas[] =  $notaSer[2];
									//echo json_encode($totalNotas);
									//print_r($totalNotas);
									$numeroNotas ="";
									foreach ($totalNotas as $nota) {
										if($nota != ''){
											$numeroNotas = ((int)$numeroNotas + 1);
										}
									}
									$notaDef = 0;
									$notaDef = number_format($notaDef, 2);
									if ($numeroNotas != 0 || $numeroNotas != ""){
									//echo json_encode($numeroNotas);
										$notaDef =  ((int)$notaHacer[0]+(int)$notaHacer[1]+(int)$notaHacer[2]+
													(int)$notaSaber[0]+(int)$notaSaber[1]+(int)$notaSaber[2]+
													(int)$notaSer[0]+(int)$notaSer[1]+(int)$notaSer[2]) / $numeroNotas;
										$notaDef = number_format($notaDef, 2);
									}
						$array[$count]['Nota']=$notaDef;
						$array[$count]['IdTercero']=$idTercero;
						$count++;
						
						$numeroNotas = 0;
						$totalNotas = [];
						$notaDef = 0;
					}
					//unset($rs);
				}
			} else {
				$array = 0;
				print_r($conexion->getPDO()->errorInfo()); die();
			} //print_r($array); die();
		//} 
		
		//$array[0]= 1.5; 
        echo json_encode($array);
    }


    public function ArrayColummns($ini, $fin, $NoSesiones){
    	//Array de Datos Columnas 
 				$dataColumnasDatos = array('Id','No', 'Apellidos', 'Nombres', 'Identificacion');		 
 				$sesiones=(int)$NoSesiones; 
 				$int=$ini; 
 				$finInt=$fin; 
 				if($sesiones>0){ 
 					for($i=$ini; $i<=$finInt;$i++) { 
 						$sesion= "s".$i; 
 						array_push($dataColumnasDatos, $sesion); 
 						$int++; 
 					} 
 				} 
  
 				array_push($dataColumnasDatos, 'T/Horas', 'Observaciones', '', 'Motivo no asistencia', '', 'Nota'); 

 			return $dataColumnasDatos;

    }



    //Reporte Asistencias en excel//
  public function consultarReporte($param){
	extract($param); 
//var_dump($param); 
  
 	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />'); 
 	date_default_timezone_set('Europe/London'); 
 	/** PHPExcel_IOFactory */ 
 	  
 	require_once dirname(__FILE__) . '/../includes/PHPExcel/PHPExcel/IOFactory.php'; 
  
 	/* 
 	 * To change this template, choose Tools | Templates 
 	 * and open the template in the editor. 
 	 */ 
  
 	$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp; 
 	$cacheSettings = array('memoryCacheSize ' => '8MB'); 
 	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings); 
  
  
 	$sheetname = 'Informe';  
 	$objReader = PHPExcel_IOFactory::createReader('Excel5'); 
 	$objReader->setLoadSheetsOnly($sheetname); 
 	$objReader->setLoadAllSheets(); 
  
  
  
 	//$objPHPExcel = $objReader->load("../includes/PHPExcel/PHPExcel/Templates/template.xls"); 
 	 
         extract($param); 
 		$data = array('error'=>0,'mensaje'=>'','html'=>''); 

 			$sesiones=(int)$NoSesiones; 
  
 				//Array de Datos Columnas 
 			/*	$dataColumnasDatos = array('No', 'Apellidos', 'Nombres', 'Identificacion');		 
 				$sesiones=(int)$NoSesiones; 
 				$int=1; 
 				$finInt=13; 
 				if($sesiones!==0){ 
 					for($i=1; $i<=$finInt;$i++) { 
 						$sesion= "s".$int; 
 						array_push($dataColumnasDatos, $sesion); 
 						$int++; 
 					} 
 				}   
 				array_push($dataColumnasDatos, 'T/Horas', 'Observaciones', 'Motivo no asistencia', 'Nota'); */
  
   				$objPHPExcel = $objReader->load("../includes/PHPExcel/PHPExcel/Templates/templateReporteAsistencias.xls"); 
 				$objPHPExcel->setActiveSheetIndex(0); 
 				$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0);  
 				$objPHPExcel->getActiveSheet()->getPageMargins()->SetRight(0,4);  
 				$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0,4);  
 				$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0); 
 				$objPHPExcel->getActiveSheet()->setCellValue('E9', $IdCurso."-".$Curso); 
 				$objPHPExcel->getActiveSheet()->setCellValue('T9', $IdModulo."-".$Modulo); 
 				$objPHPExcel->getActiveSheet()->setCellValue('E10', $DiasCurso." - ".$Horario); 
 				$objPHPExcel->getActiveSheet()->setCellValue('T10', $Sede); 
 				$objPHPExcel->getActiveSheet()->setCellValue('E11', $FechaInicial); 
 				$objPHPExcel->getActiveSheet()->setCellValue('I11', $FechaFinal); 
 				$objPHPExcel->getActiveSheet()->setCellValue('E12', $Salon); 
 				$objPHPExcel->getActiveSheet()->setCellValue('L12', $sesiones); 
 				$objPHPExcel->getActiveSheet()->setCellValue('R12', $Duracion); 
 				$objPHPExcel->getActiveSheet()->setCellValue('T12', $Inscritos); 
 				$objPHPExcel->getActiveSheet()->setCellValue('T11', $Docente); 
 				$objPHPExcel->getActiveSheet()->setCellValue('H12', $Ruta); 
 				/*$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Nombre del Servicio educativo'); 
 				 
 				$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Centro/Sede/Lugar de Servicio'); 
 				$objPHPExcel->getActiveSheet()->setCellValue('D2', $Sede." - ".$Salon); 
 				$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Fecha de Inicio'); 
 				$objPHPExcel->getActiveSheet()->setCellValue('D3', $FechaInicial); 
 				$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Horario'); 
 				$objPHPExcel->getActiveSheet()->setCellValue('H2', $Horario); 
 				$objPHPExcel->getActiveSheet()->setCellValue('G3', 'Código'); 
 				$objPHPExcel->getActiveSheet()->setCellValue('H3', $IdCurso." - ".$IdModulo); 
 				$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Inscritos'); 
 				$objPHPExcel->getActiveSheet()->setCellValue('J1', $Inscritos); 
 				$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Fecha de Finalización'); 
 				$objPHPExcel->getActiveSheet()->setCellValue('J2', $FechaFinal); 
 				$objPHPExcel->getActiveSheet()->setCellValue('I3', 'Duración del Módulo'); 
 				$objPHPExcel->getActiveSheet()->setCellValue('J3', $Duracion); 
 				*/ 
 				 
 		    $arrayMasSessiones=[];  
 			$baseRowDatos = 15; 
 			$columnDatos=0; 
            $inis=1;
            $fins=13;

 			$dataColumnasDatos=$this->ArrayColummns($inis,$fins, $sesiones);

 			foreach($dataColumnasDatos as $dataRow) { 
 					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnDatos, $baseRowDatos, $dataRow);      
 					$columnDatos++;                     
 				}	 
 			 
 			$hojaActual=0;   
             $objClonedWorksheet1 =  clone $objPHPExcel->getSheet(0); 
             $hojaActual=$hojaActual+1; 
             $objClonedWorksheet1->setTitle('Informe'.$hojaActual); 
 			$objPHPExcel->addSheet($objClonedWorksheet1); 
 			$objPHPExcel->setActiveSheetIndex($hojaActual);		                  
  
 			$baseRow = 16; 
 			$columnRow= 0; 
			$rs = null;
 			$conexion->getPDO()->query("SET NAMES 'utf8'"); 
 			$sql = "CALL SPCONSULTARESTUDIANTESPORSALON1($idPreprogramacion);"; 
 			if ($rs = $conexion->getPDO()->query($sql)) { 
 					if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) { 
                        //$rs->closeCursor();
 						$data['mensaje'] = 1; 
 						$Estudiantes= count($filas); 
                        $Estudiantes1=count($filas); 
						$totalEstudiantes=0; 
                        $varId=0;
 						foreach ($filas as  $r =>$fila) { 
                            $varId++;
 							$columnRow=0; 
 							$row = $baseRow	+ $totalEstudiantes;	
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow,   $row, $fila['IdTercero']); 	 
 								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $varId); 
 								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $fila['Apellidos']); 
 								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $fila['Nombres']); 
 								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $fila['Identificacion']);						 
 								//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $fila['Telefono1']);
                            
                            $totalEstudiantes++;     

							if($totalEstudiantes>19){	 
 								$hojaActual=$hojaActual+1; 
 								$objClonedWorksheet = clone $objPHPExcel->getSheet(0); 
 					            $objClonedWorksheet->setTitle('Informe'.$hojaActual); 
 					            $objPHPExcel->addSheet($objClonedWorksheet); 
 					            $objPHPExcel->setActiveSheetIndex($hojaActual);		 
 					            //$objPHPExcel= $objClonedWorksheet; 
 								$baseRow = 16; 
 								$columnRow= 0; 
 								$totalEstudiantes=0; 
 								//echo $fila['IdTercero']; 
 							} 

                              
  
 						} 
  
 							unset($rs); 
  
 						$Estudiantes=$Estudiantes+16; 
 						$sheetCount = ($objPHPExcel->getSheetCount())-1;
                   
                        $typeSession="";
 					if($sesiones!==0){ 

                    if($sesiones>13){
                          $typeSession="1";
                          $sesiones=13;
                    } 
                    $ises=1;
                    $sheetCountIni=$sheetCount;
   					for($m=1;$m<=$sheetCount;$m++){ //inicio cantidad sheet 

                        if($typeSession=="1"){
                               $inis=14;
                               $fins=$inis+12;
                               $ahoraHojaActual=1;
                               $ahoraHojaActual= $ises+$sheetCount;
                               $objClonedWorksheet = clone $objPHPExcel->getSheet($m); 
                               $objClonedWorksheet->setTitle('Informe'.$ahoraHojaActual); 
                               $objPHPExcel->addSheet($objClonedWorksheet);                          
                               $dataColumnasDatos=$this->ArrayColummns($inis,$fins, $sesiones);
                               $objPHPExcel->setActiveSheetIndex($ahoraHojaActual); 
                               $baseRowDatos = 15; 
                               $columnDatos=0; 
                               $sheetCountIni= $sheetCountIni+1;
                               $objPHPExcel->getActiveSheet()->setCellValue('V14', $sheetCountIni); 
                               $sheetCountotal=$sheetCount*2;
                               $objPHPExcel->getActiveSheet()->setCellValue('X14', $sheetCountotal); 
                               foreach($dataColumnasDatos as $dataRow) { 
                                       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnDatos, $baseRowDatos, $dataRow);      
                                $columnDatos++;                     
                                }    
                             $arrayMasSessiones[]= $ahoraHojaActual;
                             $ises++;
                        }

                        if($typeSession=="1"){
                            $sheetCountotal=$sheetCount*2;
                        }else{
                            $sheetCountotal=$sheetCount;
                        }


 						$objPHPExcel->setActiveSheetIndex($m); 
                        $objPHPExcel->getActiveSheet()->setCellValue('V14', $m); 
                        $objPHPExcel->getActiveSheet()->setCellValue('X14', $sheetCountotal); 

 						for($i=16;$i<=35;$i++){     

 							$columnRow=4; 
 							$row = $i; 
 							$idTerceroAsistencia=$objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue(); 
  

                            if($idTerceroAsistencia!=""){
 							//Cargar Asistencias por tercero y preprogramacion   
							
 							$sql2 = "CALL SPCONSULTARASISTENCIASPORTERCERO($idPreprogramacion, $idTerceroAsistencia);"; 
 							if($rs2 = $conexion->getPDO()->query($sql2)){ 
 								if ($filasAsistencia = $rs2->fetchAll(PDO::FETCH_ASSOC)) { 
                                     $sum=0;
                                       foreach ($filasAsistencia as $r =>$filaasiscount) {
                                               $sum=$sum+$filaasiscount['HorasAsistidas'];
                                        }


                                    	   $horasAsistidas="NA"; 
 										 for($j=1;$j<=$sesiones;$j++){     
                                             $sesion=0;             
     											foreach ($filasAsistencia as $s =>$filaasis) {
         												if($filaasis['SesionNumero']==$j){ 
                                                                $sesion=1;
         														$horasAsistidas=$filaasis['HorasAsistidas']; 
                                                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $horasAsistidas); 
         												}	

                                              						 
     											}                                       
                                       
                                       if($sesion==0){
     											$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, "NA"); 
     												 

                                        }     	

 										 } 
  
 										 	$totalSesionesFaltantes=13-$sesiones; 
 										  	//echo "<br>col".$columnRow; 
                                            if($totalSesionesFaltantes>0){
         										  	for($k=1;$k<=$totalSesionesFaltantes;$k++){  
         										  		//cellColor($row.$columnRow+1, 'F28A8C'); 
        										  		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnRow=$columnRow+1, $row)->getFill()->applyFromArray(array( 
         												        'type' => PHPExcel_Style_Fill::FILL_SOLID, 
         												        'startcolor' => array( 
         												             'rgb' => '8A7F7D' 
         												        ) 
         												    )); 
         										  	} 
                                             }
  
											$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $sum); 
    
 									unset($rs2); 
 								}else{ //filaAsistencia 
                                    unset($rs2); 

                                     for($j=1;$j<=$sesiones;$j++){   
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, "NA"); 
                                     } 
                                    


                                            $totalSesionesFaltantes=13-$sesiones; 
                                            //echo "<br>col".$columnRow; 
                                            if($totalSesionesFaltantes>0){
                                                    for($k=1;$k<=$totalSesionesFaltantes;$k++){  
                                                        //cellColor($row.$columnRow+1, 'F28A8C'); 
                                                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnRow=$columnRow+1, $row)->getFill()->applyFromArray(array( 
                                                                'type' => PHPExcel_Style_Fill::FILL_SOLID, 
                                                                'startcolor' => array( 
                                                                     'rgb' => '8A7F7D' 
                                                                ) 
                                                            )); 
                                                    } 
                                             }

                                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, 0); 
                                      //echo "<br>ocl".$columnRow;
  
 								}//fin else 
 							}//fin $rs2 
 							//Cargar Observaciones por tercero y preprogramacion 
 						     $sql3 = "CALL SPCONSULTAROBSERVACIONESPORTERCERO($idPreprogramacion, $idTerceroAsistencia);"; 
                            //$sql3 = "CALL SPCONSULTAROBSERVACIONESPORTERCERO(7405, 88442);";
 								if($rs3 = $conexion->getPDO()->query($sql3)){ 
                            
 									if ($filasObservaciones = $rs3->fetchAll(PDO::FETCH_ASSOC)) { 
                                        //$rs3->closeCursor();
 											foreach ($filasObservaciones as $k =>$filaob) { 

                            											$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $filaob['Observacion']); 
 											} 
 									}else{ 
                
 										$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, ''); 
 									} 
                                      
 									unset($rs3); 
 								}else{
                                    unset($rs3); 
                                }//fin rs3 
  
 							//Cargar Motivos asistencia por tercero y preprogramacion 
 							$sql4 = "CALL SPCONSULTARMOTIVOSNOASISTENCIAPORTERCERO($idPreprogramacion, $idTerceroAsistencia);"; 
 							if($rs4 = $conexion->getPDO()->query($sql4)){ 
 									if ($filasMotivoAsistencia = $rs4->fetchAll(PDO::FETCH_ASSOC)) { 


 											foreach ($filasMotivoAsistencia as $s =>$filamot) { 
                                                //echo "aqui";
 										$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+2, $row, $filamot['Nombre']); 
 											} 
 									}else{ 
 										$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+2, $row, ''); 
 									} 
  
 									unset($rs4); 
 								}else{
                                    unset($rs4); 
                                }//fin rs4 
  
 						   //Cargar notas por tercero y preprogramacion 
 						   $sql5 = "CALL SPCONSULTARNOTASPARAASISTENCIASTERCERO($idPreprogramacion, $idTerceroAsistencia);"; 
 						   if ($rs5 = $conexion->getPDO()->query($sql5)) { 
 							$count=0; 
 								if ($filasNotas = $rs5->fetchAll(PDO::FETCH_ASSOC)) { 
 										foreach ($filasNotas as $filanot) { 
  
 											$idTercero = $filanot['IdTercero']; 
 											$notaHacer = explode(',',$filanot['NotaHacer']); 
 											$notaSaber = explode(',',$filanot['NotaSaber']); 
 											$notaSer = explode(',',$filanot['NotaSer']); 
 											 
 										 
 											$totalNotas[] =  $notaHacer[0]; 
 											$totalNotas[] =  $notaHacer[1]; 
 											$totalNotas[] =  $notaHacer[2]; 
 											$totalNotas[] =  $notaSaber[0]; 
 											$totalNotas[] =  $notaSaber[1]; 
 											$totalNotas[] =  $notaSaber[2]; 
 											$totalNotas[] =  $notaSer[0]; 
 											$totalNotas[] =  $notaSer[1]; 
 											$totalNotas[] =  $notaSer[2]; 
 											//echo json_encode($totalNotas); 
 											//print_r($totalNotas); 
 											$numeroNotas =""; 

 											foreach ($totalNotas as $nota) { 
 												if($nota != 0 || $nota != ""){ 
 												$numeroNotas = ((int)$numeroNotas + 1); 
 												} 
 											} 
 									$notaDef = 0; 
 									$notaDef = number_format($notaDef, 2); 
 									 
 									if ($numeroNotas != 0 || $numeroNotas != ""){ 
 									//echo json_encode($numeroNotas); 
 										$notaDef =  ((int)$notaHacer[0]+(int)$notaHacer[1]+(int)$notaHacer[2]+ 
 													(int)$notaSaber[0]+(int)$notaSaber[1]+(int)$notaSaber[2]+ 
 													(int)$notaSer[0]+(int)$notaSer[1]+(int)$notaSer[2]) / (int)$numeroNotas; 
 										$notaDef = number_format($notaDef, 2); 
 									} 
 										 
 										//$count++; 
 										 
 										$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+2, $row, $notaDef); 
 										$numeroNotas = 0; 
 										$totalNotas = []; 
 										$notaDef = 0;	 
 										 
 								} 
 										 
 								}else{ 
 										$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+2, $row, ''); 
 									} 
  
 								unset($rs5); 
 									 
 						}else{
                                unset($rs5); 
                        }	//fin rs5		

                      
 					} 

                    }
 				} 
 			}//fin cantidad for countsheet 

	 
  
 					}else{ 
 						 $data['error']=2; 
 					} 
 					}else { 
 						 $data['error'] = 0; 
 						 print_r($conexion->getPDO()->errorInfo()); die(); 
 					}  

                            //validar si hay mas de 13 sesiones
            if(count($arrayMasSessiones)>0){
                //$Estudiantes=28;

                $Estudiantes1=$Estudiantes1+16; 
                $counse=count($arrayMasSessiones);
                $x=0;
                while ($x < $counse) { 
                    $arraySes= $arrayMasSessiones[$x];
                    $objPHPExcel->setActiveSheetIndex($arraySes);                   
                    $x++;
                
                        for($i=16;$i<=35;$i++){    
                            $sesion1=(int)$NoSesiones;
                            $totalSesionesFaltantes1=($sesion1-13);
                            $columnRow=4; 
                            $row = $i; 
                            $idTerceroAsistencia=$objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue(); 
  
                            if($idTerceroAsistencia!=""){
                            //Cargar Asistencias por tercero y preprogramacion   
                            $sql2 = "CALL SPCONSULTARASISTENCIASPORTERCERO($idPreprogramacion, $idTerceroAsistencia);"; 
                            if($rs2 = $conexion->getPDO()->query($sql2)){ 
                                if ($filasAsistencia = $rs2->fetchAll(PDO::FETCH_ASSOC)) { 
                                            $sum=0;
                                       foreach ($filasAsistencia as $r =>$filaasiscount) {
                                               $sum=$sum+$filaasiscount['HorasAsistidas'];
                                        }
                                            $horasAsistidas="NA"; 
                                         
                                         for($j=14;$j<=$sesion1;$j++){ 

                                                $sesion=0;  
                                                foreach ($filasAsistencia as $s =>$filaasis) { 
                                                    if($filaasis['SesionNumero']==$j){     
                                                            $sesion=1;
                                                                $horasAsistidas=$filaasis['HorasAsistidas']; 
                                                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $horasAsistidas); 
                                              
                                                    }                                                
                                                } 
      
      
                                                                                    
                                       if($sesion==0){
                                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, "NA"); 
                                               
                                        }       

                                         } 
  
                                           //echo  "totalfal".$totalSesionesFaltantes1=$sesiones-13; 
                                         if($totalSesionesFaltantes1>0){
                                              $totalSesionesFaltantes=13-$totalSesionesFaltantes1;
                                          }else{
                                              $totalSesionesFaltantes=0;
                                          }
                                          
                                            if($totalSesionesFaltantes>0){
                                                    for($k=1;$k<=$totalSesionesFaltantes;$k++){  
                                                        //cellColor($row.$columnRow+1, 'F28A8C'); 
                                                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnRow=$columnRow+1, $row)->getFill()->applyFromArray(array( 
                                                                'type' => PHPExcel_Style_Fill::FILL_SOLID, 
                                                                'startcolor' => array( 
                                                                     'rgb' => '8A7F7D' 
                                                                ) 
                                                            )); 
                                                    } 
                                             }
  
                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $sum); 
  
  
                                         
  
                                    unset($rs2); 
                                }else{ //filaAsistencia 
                                      unset($rs2); 
                                      for($j=1;$j<=$totalSesionesFaltantes1;$j++){   
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, "NA"); 
                                     } 
  

                                        $totalSesionesFaltantes=13-$totalSesionesFaltantes1; 
                                            //echo "<br>col".$columnRow; 
                                            if($totalSesionesFaltantes>0){
                                                    for($k=1;$k<=$totalSesionesFaltantes;$k++){  
                                                        //cellColor($row.$columnRow+1, 'F28A8C'); 
                                                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnRow=$columnRow+1, $row)->getFill()->applyFromArray(array( 
                                                                'type' => PHPExcel_Style_Fill::FILL_SOLID, 
                                                                'startcolor' => array( 
                                                                     'rgb' => '8A7F7D' 
                                                                ) 
                                                            )); 
                                                    } 
                                             }


                                   
                                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, 0); 
  
                                }//fin else 
                            }//fin $rs2 
  
                            //Cargar Observaciones por tercero y preprogramacion 
                           $sql3 = "CALL SPCONSULTAROBSERVACIONESPORTERCERO($idPreprogramacion, $idTerceroAsistencia);"; 
                                if($rs3 = $conexion->getPDO()->query($sql3)){ 
                                    if ($filasObservaciones = $rs3->fetchAll(PDO::FETCH_ASSOC)) { 
                                            foreach ($filasObservaciones as $s =>$filaob) { 
                                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, $filaob['Observacion']); 
                                            } 
                                    }else{ 
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+1, $row, ''); 
                                    } 
  
                                    unset($rs3); 
                                }else{
                                     unset($rs3);
                                }//fin rs3 
  
                            //Cargar Motivos asistencia por tercero y preprogramacion 
                            $sql4 = "CALL SPCONSULTARMOTIVOSNOASISTENCIAPORTERCERO($idPreprogramacion, $idTerceroAsistencia);"; 
                            if($rs4 = $conexion->getPDO()->query($sql4)){ 
                                    if ($filasMotivoAsistencia = $rs4->fetchAll(PDO::FETCH_ASSOC)) { 
                                            foreach ($filasMotivoAsistencia as $s =>$filamot) { 
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+2, $row, $filamot['Nombre']); 
                                            } 
                                    }else{ 
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+2, $row, ''); 
                                    } 
  
                                    unset($rs4); 
                                }else{
                                    unset($rs4); 
                                }//fin rs4 
  
                           //Cargar notas por tercero y preprogramacion 
                           $sql5 = "CALL SPCONSULTARNOTASPARAASISTENCIASTERCERO($idPreprogramacion, $idTerceroAsistencia);"; 
                           if ($rs5 = $conexion->getPDO()->query($sql5)) { 
                            $count=0; 
                                if ($filasNotas = $rs5->fetchAll(PDO::FETCH_ASSOC)) { 
                                        foreach ($filasNotas as $filanot) { 
  
                                            $idTercero = $filanot['IdTercero']; 
                                            $notaHacer = explode(',',$filanot['NotaHacer']); 
                                            $notaSaber = explode(',',$filanot['NotaSaber']); 
                                            $notaSer = explode(',',$filanot['NotaSer']); 
                                             
                                         
                                            $totalNotas[] =  $notaHacer[0]; 
                                            $totalNotas[] =  $notaHacer[1]; 
                                            $totalNotas[] =  $notaHacer[2]; 
                                            $totalNotas[] =  $notaSaber[0]; 
                                            $totalNotas[] =  $notaSaber[1]; 
                                            $totalNotas[] =  $notaSaber[2]; 
                                            $totalNotas[] =  $notaSer[0]; 
                                            $totalNotas[] =  $notaSer[1]; 
                                            $totalNotas[] =  $notaSer[2]; 
                                            //echo json_encode($totalNotas); 
                                            //print_r($totalNotas); 
                                            $numeroNotas =""; 
                                            foreach ($totalNotas as $nota) { 
                                                if($nota != 0 || $nota != ""){ 
                                                $numeroNotas = $numeroNotas + 1; 
                                                } 
                                            } 
                                    $notaDef = 0; 
                                    $notaDef = number_format($notaDef, 2); 
                                     
                                    if ($numeroNotas != 0 || $numeroNotas != ""){ 
                                    //echo json_encode($numeroNotas); 
                                        $notaDef =  ($notaHacer[0]+$notaHacer[1]+$notaHacer[2]+ 
                                                    $notaSaber[0]+$notaSaber[1]+$notaSaber[2]+ 
                                                    $notaSer[0]+$notaSer[1]+$notaSer[2]) / $numeroNotas; 
                                        $notaDef = number_format($notaDef, 2); 
                                    } 
                                         
                                        //$count++; 
                                         
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+2, $row, $notaDef); 
                                        $numeroNotas = 0; 
                                        $totalNotas = []; 
                                        $notaDef = 0;    
                                         
                                } 
                                         
                                }else{ 
                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnRow=$columnRow+2, $row, ''); 
                                    } 
  
                                unset($rs5); 
                                     
                        }  else{
                             unset($rs5); 

                        }     //fin rs5   


                    
                 }

                }

             }

            }

                    $styleArray = array( 
                                  'borders' => array( 
                                      'allborders' => array( 
                                          'style' => PHPExcel_Style_Border::BORDER_THIN 
                                      ) 
                                  ) 
                              ); 
                            $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray); 
                             
                            //foreach externo 
                             //$objPHPExcel->removeSheetByIndex(0); 
                             $sheetIndex = $objPHPExcel->getIndex($objPHPExcel-> getSheetByName('Informe')); 
                            $objPHPExcel->removeSheetByIndex($sheetIndex); 
                            //$objPHPExcel->addSheet(); 
                            $objPHPExcel->setActiveSheetIndex(0); 
  
  
                             //$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1); 
                             $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
                             
                             $FechaMod=strtotime("now"); 
                             $filename = '../tmp/reporteAsistencias/reporteAsistencias_'.$FechaMod.'.xls'; 
                             $objWriter->save(str_replace('.php', '.xls', $filename)); 
                             $data['html']=$filename; 
  
  

           
 						
                            
 		  echo json_encode($data); 
	}

    public function SPCONSULTARESTUDIANTESCONASISTENCIA0($param) {
        extract($param);
        $resultado = array();
        $registro = array();
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARESTUDIANTESCONASISTENCIA0($pIdPreprogramacion,'$pFechaInicial','$pFechaFinal')";

        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) { 
            // print_r($sql);
            // print_r($filas);
                foreach ($filas as $fila) {
                    foreach ($fila as $key => $value) {
                        array_push($registro, $value);
                    }
                    array_push($resultado, $registro);
                    $registro = array();
                }
                // foreach ($filas as $fila) {
                    // $array[] = $fila;
                // }
            }
        } else {
            $registro = 0; print_r($conexion->getPDO()->errorInfo()); die();
        }
        
        //print_r($filas);
        echo json_encode($resultado);
    }



}
?>