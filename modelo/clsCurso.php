<?php
set_time_limit(0);
session_start();
error_reporting(E_ALL);
/**
 * Description of clsCurso
 *
 * @author Wilmer Andres Escobar Naranjo
 */
class clsCurso {
    /*
     * function validarNombreEspecifico()
     * validar nombre especifico curso sólo si  ruta = 'RUTA_3'
     */

    public function validarNombreCursoEspecifico($rut, $nom_cur) {
        $men_err = '';
        if (strlen(trim($nom_cur) > 0)) {
            if (trim($rut) !== 'RUTA_3') {
                $men_err = "Sólo se puede asociar el nombre especifico (" . $nom_cur . ")  si ruta es: RUTA_3.";
            }
        }
        return $men_err;
    }

    public function CargarInformacionCompletaCurso($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARDATOSCURSO1();";        
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

    public function CargarCursosPorRuta($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARCURSOSPORRUTA($pIdRuta);";        
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


    
    public function CargarEstados($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARESTADOSPARTICIPANTE();";        
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                   // $fila = $this->CodificarEnUtf8($fila);
                    $array[] = $fila;
                }
            }
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }

    public function CargarCursoAnteriorTercero($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARCARGAPORIDTERCERO($idTercero);";        
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

    public function CargarHoras($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARHORAS();";        
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $fila = $this->CodificarEnUtf8($fila);
                    $array[] = $fila;
                }
            }
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }

    public function CargarCursoPorCodigo($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARDATOSCURSO(\"$rut_cod\");";
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
            else{
                $array = 0;
            }
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }

    public function ConsultarMatriculasPre($param) {
        extract($param);
		$rs = null;
		$array=array();
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARSALONES2('$pCodigoCurso','$pCodigoModulo','$pModalidad');";
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
			else{
				$array = 0;
			}
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }


    public function ConsultarMatriculasPre2($param) {
        extract($param);
		$rs = null;
		$array=array();
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARSALONES3('$pCodigoCurso','$pCodigoModulo','$pModalidad');";
		if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
			else{
				$array = 0;
			}
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }


    public function CargarSalonesPorSede($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARSALONESPORSEDE($sede);";
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
    
    public function ConsultarDiasCurso($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARDIASCURSO();";
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

    public function ConsultarModulos($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARMODULOSPORCURSO($pIdCurso);";
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

    public function ConsultarModulosSinPreprogramacion($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARMODULOSNOPREPROGRAMADOS('$pIdMatricula', '$pIdCurso');";
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                 }
            }
            else{
                $array = 0;
            }
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }


    public function CargarModalidades($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARMODALIDAD();";
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

    public function CargarHorarios($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARHORARIOSCURSO();";
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
            }
        } else {
            $array = 0;
        }
        // $array = 0;
        echo json_encode($array);
    }

    public function CargarSedes($param) {
        extract($param);
		$rs = null;
        $sql = "CALL SPCARGARSEDES();";
        $conexion->getPDO()->query("SET NAMES 'utf8'");
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
    
    public function CargarEntregables($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARENTREGABLES();";
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


    ///***Carga combo de Rutas en Preprogramación***////
     public function CargarRutas($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARRUTAS();";
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
    
    
    public function CargarTipoCertificacion($param) {
        extract($param);
		$rs = null;
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARTIPOCERTIFICACION();";
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

	public function consultarCalendarioPreprogramacion($param) {
        extract($param);
		$rs = null;
        $sql = "CALL SPCONSULTARCALENDARIOPREPROGRAMACION($idPreprogramacion);";
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                $diasClase=[];
                foreach ($filas as $fila) {
					$nombreDia=trim($fila['Nombre']);
                    //Generar arrays dias preprogramación
                    switch ($nombreDia) {
                        case "Lunes a Viernes":
                             $diasClase= array("Lunes","Martes","Miercoles","Jueves","Viernes");
                        break;
                        case "Lunes a Sábado":
                             $diasClase= array("Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
                        break;
                        case "Lunes a Miércoles":
                             $diasClase= array("Lunes","Martes","Miercoles");
                        break;
                        case "Jueves a Sábados":
                             $diasClase= array("Jueves","Viernes","Sabado");
                        break;
                        case "Martes y Miércoles": //--
                             $diasClase= array("Martes","Miercoles");
                        break;
						case "Martes a Sábado":
                             $diasClase= array("Martes","Miercoles","Jueves","Viernes","Sabado");
                        break;
                        case "Sábado":
                             $diasClase= array("Sabado");
                        break;
						case "Jueves a Martes":
                             $diasClase= array("Jueves","Viernes","Sabado","Lunes","Martes");
                        break;
						case "Lunes a Jueves":
                             $diasClase= array("Lunes","Martes","Miercoles","Jueves");
                        break;
						case "Lunes, Viernes y Sábado":
                             $diasClase= array("Lunes","Viernes","Sabado");
                        break;
						case "Martes a Viernes":
                             $diasClase= array("Martes","Miercoles","Jueves","Viernes");
                        break;
						case "Martes y Jueves":
                             $diasClase= array("Martes","Miercoles","Jueves");
                        break;
						case "Domingo":
                             $diasClase= array("Domingo");
                        break;
						case "Jueves":
                             $diasClase= array("Jueves");
                        break;
						case "Jueves y Viernes":
                             $diasClase= array("Jueves","Viernes");
                        break; 
						case "Lunes y Martes":
                             $diasClase= array("Lunes","Martes");
                        break;
						case "Lunes, Martes, Jueves y Viernes":
                             $diasClase= array("Lunes","Martes","Jueves","Viernes");
                        break;
						case "Viernes y Sábado":
                             $diasClase= array("Viernes","Sabado");
                        break;
						case "Lunes, Miércoles y Viernes":
                             $diasClase= array("Lunes","Miercoles","Viernes");
                        break;
						case "Lunes y miércoles":
                             $diasClase= array("Lunes","Miercoles");
                        break;
						case "Lunes y viernes":
                             $diasClase= array("Lunes","Viernes");
                        break;
						case "Lunes martes y jueves":
                             $diasClase= array("Lunes","Martes","Jueves");
                        break;
						case "Lunes":
                             $diasClase= array("Lunes");
                        break;
						case "Martes":
                             $diasClase= array("Martes");
                        break;
						case "Miércoles a sábado":
                             $diasClase= array("Miercoles","Jueves","Viernes","Sabado");
                        break;
						case "Miércoles a viernes":
                             $diasClase= array("Miercoles","Jueves","Viernes");
                        break;
						case "Miércoles y jueves":
                             $diasClase= array("Miercoles","Jueves");
                        break;
						case "Miércoles y viernes":
                             $diasClase= array("Miercoles","Viernes");
                        break;
						case "Miércoles":
                             $diasClase= array("Miercoles");
                        break;
						case "Viernes":
                             $diasClase= array("Viernes");
                        break;
						
                    }
                        //Array con todas las dias de la semana para ser comparado
                        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
                        $fechaFinal= $fila['FechaFinal'];
                        $fechaInicial=$fila['FechaInicial'];
                        $fechamostrar = $fechaInicial;
                        $NoSesion=0;

                        //Recorre desde la dias desde la fecha inicio hasta la fecha fin
                        while(strtotime($fechaFinal) >= strtotime($fechaInicial)){
                            //Devuelve el número del dia al que corresponde la fecha
                            @$fecha = $dias[date('N', strtotime($fechamostrar))];
                            //Busca si ese dia esta en el array de dias de clase
                            $buscar= array_search($fecha, $diasClase); 
                            //Verifica si la busqueda del dia en el array de dias clase sea diferente de false      
                            if(false !== $buscar){
                                //Aumenta el contador de No de Sesiones
                                $NoSesion=$NoSesion+1; 
                            }   
                            //Verifica si el dia que esta recorriendo el while es diferente del dia final para pasar al siguiente
                            if(strtotime($fechaFinal) != strtotime($fechamostrar)){
                                $fechamostrar = date("d-m-Y", strtotime($fechamostrar . " + 1 day"));
                            }else{
                                //Termina el while si ya se termino el rango de fechas
                                break;
                            }

                        }        
                }
                      $NoSesion;
            }
        } else {
            $NoSesion = -1;
        }
		
        return($NoSesion);
    }
	
	

    public function cerrarCurso($param){ 
        extract($param);
        $data = array('error'=>"", 'mensaje'=>'', 'html'=>'','parametros'=>'','idModulos'=>'','noModulos'=>'','asistencias'=>'', 'notas'=>'', 'planeacion'=>'');
		$parametros=array();
		$array=array();
		$parametros['idPreprogramacion']=$idPreprogramacion;
		$IdUsuario = $_SESSION['idUsuario'];
        //validaciones para poder cerrar el curso
		//----------CONSULTA DE SESIONES
		$numeroSesiones=$this->consultarCalendarioPreprogramacion($param); 
			if($numeroSesiones == -1){
				$data["error"]="No se consulto el numero de sesiones";
			}
				//----------FIN CONSULTA DE SESIONES
		
				//---------- VALIDACION DE ASISTENCIA
			if($numeroSesiones !== -1){
				$conexion->getPDO()->query("SET NAMES 'utf8'");
                $rs=null;
				$sql = "CALL SPCONSULTARTOTALASISTENCIASPORPREPROGRAMACION ($idPreprogramacion,$numeroSesiones);";
				if ($rs = $conexion->getPDO()->query($sql)) {
					$fila = $rs->fetch(PDO::FETCH_ASSOC);
					if ($fila['NumeroAsistencias'] == 0){
						$data["error"]="No se puede cerrar el curso, faltan asistencias por ingresar";
						echo json_encode($data);
						exit;
					}
					else{
						$data['asistencias']="ok";
					}
				}
				else{
					$data["error"]="No se encontraro las asistencias";
					print_r($conexion->getPDO()->errorInfo()); die();
				}	
			}
			
		//---------- FIN VALIDACION DE ASISTENCIA
		//---------- VALIDACION DE PLANEACION
			if($numeroSesiones !== -1){
				$conexion->getPDO()->query("SET NAMES 'utf8'");
                 $rs=null;
				$sql = "CALL SPCONSULTARTOTALPLANEACIONPORPREPROGRAMACION ($idPreprogramacion,$numeroSesiones);";
				if ($rs = $conexion->getPDO()->query($sql)) {
					$fila = $rs->fetch(PDO::FETCH_ASSOC);
					if ($fila['NumeroPlaneaciones'] == 0){
						$data["error"]="No se puede cerrar el curso, faltan planeaciones por ingresar";
						echo json_encode($data);
						exit;
					}
					else{
						$data['planeacion']="ok";
					}
				}
				else{
					$data["error"]="No se consulto la planeación";
					print_r($conexion->getPDO()->errorInfo()); die();
				}	
			}
		
		//---------- FIN VALIDACION DE PLANEACION
		//---------- VALIDACION DE EVALUACION
				$conexion->getPDO()->query("SET NAMES 'utf8'");
                $rs=null;
				$sql = "CALL SPVALIDAREVALUACIONESCERRARCURSO($idPreprogramacion);";
				if ($rs = $conexion->getPDO()->query($sql)) {
					$fila = $rs->fetch(PDO::FETCH_ASSOC);
					if ($fila['Resultado'] == 0){
						$data["error"]="No se puede cerrar el curso, faltan evaluaciones por ingresar";
						echo json_encode($data);
						exit;
					}
					else{
						$data['evaluacion']="ok";
					}
				}
				else{
					$data["error"]="No se consulto la evaluación";
					print_r($conexion->getPDO()->errorInfo()); die();
				}	
			
		
		//---------- FIN VALIDACION DE EVALUACION
		
		if($data['asistencias']=="ok" and $data['planeacion']=="ok" and $data['evaluacion']=="ok"){//inicio validacion para cerrar
			
			$data = array('error'=>"", 'mensaje'=>'', 'html'=>'','parametros'=>'','idModulos'=>'','noModulos'=>'','asistencias'=>'', 'notas'=>'', 'planeacion'=>'');
			$conexion->getPDO()->query("SET NAMES 'utf8'");
            $rs=null;
			$sql = "CALL SPCERRARCURSO($idPreprogramacion,$IdUsuario);";
				
				if ($rs = $conexion->getPDO()->query($sql)) {//validar conexion y consulta cerrar curso
					$conexion->getPDO()->query("SET NAMES 'utf8'");
                    $rs=null;
					$sql = "CALL SPAGREGARPORCENTAJESDEASISTENCIASPORSALON($idPreprogramacion,$IdUsuario);";
					if ($rs = $conexion->getPDO()->query($sql)) {//validar conexion y consulta porcentajeAsistenciasporsalon
						$conexion->getPDO()->query("SET NAMES 'utf8'");
                       $rs=null;
						$sql = "CALL SPCONSULTARNOTASPORSALON($idPreprogramacion);";
						if ($rs = $conexion->getPDO()->query($sql)) { //validar conexion y consulta consultarnotasporsalon
							if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) { //si existen filas en notas por salon
								foreach ($filas as $fila) {//inicio foreach notas por salon
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
									$numeroNotas ="";
									foreach ($totalNotas as $nota) {//foreach totalnotas
										if(is_numeric($nota) && ($nota>0)){
											$numeroNotas = ((float)$numeroNotas + 1);
										}
									}//foreach totalnotas
									
									$notaDef =  ((float)$notaHacer[0]+(float)$notaHacer[1]+(float)$notaHacer[2]+
												(float)$notaSaber[0]+(float)$notaSaber[1]+(float)$notaSaber[2]+
												(float)$notaSer[0]+(float)$notaSer[1]+(float)$notaSer[2]) / $numeroNotas;
									$notaDef = number_format($notaDef, 2);
									
									$conexion->getPDO()->query("SET NAMES 'utf8'");
									$sql = "CALL SPAGREGARNOTADEFINITIVAPORSALON($idPreprogramacion,$idTercero,$notaDef,$IdUsuario);";
									$conexion->getPDO()->query($sql);									
									$numeroNotas = 0;
									$totalNotas = [];
									$notaDef = 0;
								} //inicio foreach notas por salon
							} //si existen filas en notas por salon
							
							
							$conexion->getPDO()->query("SET NAMES 'utf8'");
							$sql = "CALL SPCERRARMATRICULA($idPreprogramacion,$IdUsuario);";
							$conexion->getPDO()->query($sql);
			
			////////
			
		
							/* --------------------------------------------- */
							/* Inicio modulo siguiente a matricula */
														
							//se consulta en la tabla preprogramacion con el id preprogramacion para traer los datos
							//de matricula curso y salon
							$conexion->getPDO()->query("SET NAMES 'utf8'");
                            $rs=null;
							$sql = "CALL SPCONSULTARPREPROGRAMACIONPORIDPARACLONAR($idPreprogramacion);";
							if ($rs = $conexion->getPDO()->query($sql)) {//consulta y conexion consutarpreprogramacionporIdClonar
								$fila = $rs->fetch(PDO::FETCH_ASSOC);
								$salon = explode('.',$fila['Salon']);
								$parametros['salon']=$fila['Salon']; //salon actual
								$salonMatricula = $salon[0];
								
								$modulo = explode('.',$fila['Modulo']); //modulo actual
								$total_array= count($modulo)-1;
                                $cod_mod= $modulo[$total_array];

								$parametros['modulo']=$fila['Modulo'];			   
								
								$matricula = $fila['Matricula'];
								$parametros['matricula']=$matricula; //matricula
								
								$curso =  $fila['Curso'];
								$parametros['curso']=$curso; //curso
																
								//----------------------
								$data['parametros']=$parametros;
								
									//Se consulta en la tabla TCURSO para extraer el id y con este se consulta en la tabla TMODULO siguiente 4.05.T2
									$conexion->getPDO()->query("SET NAMES 'utf8'"); //este devuelve el codigo del modulo 4.05.T2
									//$sql = "CALL SPCONSULTARSIGUIENTEMODULOPORCURSO('".$curso."', '".$modulocompleto."','".$salonMatricula."');"; 
                                    $rs=null;
									$sql = "CALL SPCONSULTARSIGUIENTEMODULOPORCURSO('".$curso."', '".$salonMatricula."');"; 
									
									if ($rs = $conexion->getPDO()->query($sql)) 
									{ //Inicio conexion y consulta consultarsiguientemoduloporcurso
										if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) { //Validar si hay algun valor consultarsiguiente modulo
											foreach ($filas as $fila) {
												$array[] = $fila;
											}
										}//Validar si hay algun valor consultarsiguiente modulo
										
										if (count($filas)>0){// validar si hay modulosiguienteporcurso
											$data['idModulos']=$array;
											//se arma el div con el formulario para mostrar con los modulos faltantes
											$data[ 'html' ] = "<div>";
											$data[ 'html' ] .= "<br><label class='popup'>Seleccione el modulo siguiente</label><br><br>";
											$data[ 'html' ] .="<div style='width:70%; margin: 0 auto;'>";
											$data[ 'html' ] .="<Select id='SelModulo' name='SelModulo'>";
											$data['html'] .="<option value=''>Seleccione..</option>";
											
											for($i=0;$i<count($array);$i++){//For para recorreo la cantidad de modulossiguientes
													
												//SE CONCATENA EL CODIGO DEL CURSO
													$data['html'].="<option value='".$array[$i]['Id']."-".$array[$i]['Codigo']."-".$array[$i]['Nombre']."'>".$array[$i]['Codigo']." - ".$array[$i]['Nombre']."</option>";
											}	//For para recorreo la cantidad de modulossiguientes
											
											$data[ 'html' ] .="</select>";
											$data[ 'html' ] .="<br><br><button id='btnMatricularSiguienteModulo' class='seleccionar'>Confirmar</button>";
											$data[ 'html' ] .="&nbsp;&nbsp;<button id='btnCerrarModal' class='seleccionar'>Cancelar</button>";
											$data[ 'html' ] .="</div>";
											$data[ 'html' ] .="</div>";
										} // validar si hay modulosiguienteporcurso
										else{
																						
												$data["error"]="Curso Cerrado, No se encontraron mas módulos para asignar";
												$data["noModulos"]="1";
							
											
										}
											
										
									} //Inicio conexion y consulta consultarsiguientemoduloporcurso
									else{
										$data["error"]="No se pudo consultar el siguiente módulo";
										print_r($conexion->getPDO()->errorInfo()); die();
									}
						}//consulta y conexion consutarpreprogramacionporIdClonar
						
						/* Fin validacion de consultar siguiente modulo */
						/* ---------------------------------------------------------- */
				}else{ //validar conexion y consulta consultarnotasporsalon
						$data["error"]="No se encontraron las notas por salon";
						print_r($conexion->getPDO()->errorInfo()); die();
					}
				
				
				}else{//validar conexion y consulta porcentajeAsistenciasporsalon
					$data["error"]="No se agrego el porcentaje de asistencia";
				}
				
					
			}//validar conexion y consulta cerrar curso
			else{
				$data["error"]="No se pudo cerrar el curso";
				print_r($conexion->getPDO()->errorInfo()); die();
			}
	
			
			
	}//fin validacion cerrar curso		
		//print_r($data);
		
		echo json_encode($data);
		
	}
	
	/*
	*Funcion matricularSiguienteModulo
	*param: recibe el id del modulo a matricular, el salon, idpreprogramcioncion, idPreNuevo, modulo completo
	*return: os si se matricularon los estudiantes
	*/
	function matricularSiguienteModulo($param){
		extract($param);
		$data = array('error'=>"", 'mensaje'=>'', 'html'=>'','Otro_mensaje'=>'');
		$IdUsuario = $_SESSION['idUsuario'];
		//se consulta ese codigo modulo en la tabla preprogramacion

									if ($id != ""){
										
										//extraer el los parametros
										$saloncompleto=$parametros['matricula'].".".$t;
										$modulocompleto=$codigo;
										
										$rs = null;
										$conexion->getPDO()->query("SET NAMES 'utf8'"); //este devuelve el codigo del modulo 4.05.T2
										$sql = "CALL SPCONSULTARPREPROGRAMACIONPORSALONSIGUIENTE('".$saloncompleto."');"; 
										//print_r($sql);
										if ($rs = $conexion->getPDO()->query($sql)) {
												$fila = $rs->fetch(PDO::FETCH_ASSOC);
													if($fila != ""){
														$idPreNuevo = $fila['Id'];
														$rs = null;
														$conexion->getPDO()->query("SET NAMES 'utf8'"); //preguntar si ese tercero en ese modulo ya existe no lo inserta
														//en este procedimiento si inserta estudiantes se cambia el estado a 1 a la preprogramacion con el id $idPreNuevo
														$sql = "CALL SPAGREGARESTUDIANTESAPROBADOSPORSALONALSIGUIENTEMODULO($idPreprogramacion,$idPreNuevo,'$modulocompleto',$IdUsuario);";
														
														if ($rs = $conexion->getPDO()->query($sql)) {
															$fila = $rs->fetch(PDO::FETCH_ASSOC);
															$numEstudiantes = $fila['cont'];
															$rs = null;
															$conexion->getPDO()->query("SET NAMES 'utf8'");
															$sql = "CALL SPAGREGARCANTIDADESTUDIANTESSALON($idPreNuevo,$numEstudiantes,$IdUsuario);";
															$rs = $conexion->getPDO()->query($sql);
															
															$cerrarCurso=cerrarCursoMatriculaTercero();
															
														if($cerrarCurso["error"]!=""){
																$data["error"]=$cerrarCurso;
															}
															
															//$data["mensaje"] = "Agrego estudiantes aprobados"; 
														}
														else{
															$data["error"] = "No se agregaron los estudiantes";
															print_r($conexion->getPDO()->errorInfo()); die();
														}
														//$data["mensaje"] = "Preprogramacion encontrada"; 
													}
													else{
														$data["error"]= "No hay preprogramación para el sálon ".$saloncompleto." y módulo ".$modulocompleto." - ".$nombre." o verifique el estado"; 
													}
										}
										else{
											$data["error"] = "No se realizo la consulta a la preprogramación";
											print_r($conexion->getPDO()->errorInfo()); die();
										}
										//$data["mensaje"] = "Se encontro el modulo siguiente"; 
									}
									else{
										$data["error"] = "No hay siguiente módulo";
										print_r($conexion->getPDO()->errorInfo()); die();
									}
	echo json_encode($data);
	}

    private function CodificarEnUtf8($fila) {
        $aux;
        foreach ($fila as $value) {
            $aux[] = utf8_encode($value);
        }
        return $aux;
    }
}

?>
