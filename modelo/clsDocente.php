<?php

class clsDocente {

    public function actualizarDocentes($param){
        extract($param);
        $IdUsuario = $_SESSION['idUsuario'];
        $resp = 'ok';
        //sleep(2);
        foreach ($datos as $d) {
            if($d[0] != ''){
                $conexion->getPDO()->query("SET NAMES 'utf8'"); 
                   
                $sql = "CALL SPMODIFICARDOCENTE($d[0],'$d[1]',$d[2],$d[3],$d[4],$d[5],$d[6],'$d[7]','$d[8]','$d[9]',$d[10],
                                               $d[11],$d[12],$d[13],$d[14],$d[15],'$d[16]','$d[17]',$d[18],'$d[19]',
                                               '$d[20]',$IdUsuario);";
                //echo $sql;
                if (!$rs = $conexion->getPDO()->query($sql)) {
                    $resp = 'fail';
                }
            }
            
        }
        echo json_encode($resp);
        
    }

    public function consultarDocentes($param) {
        extract($param);
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARDOCENTES();";
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

    public function CargarInformacionCompletaDocente($param) {
        extract($param);
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCARGARDOCENTES1();";        
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

    public function consultarDocentesEntreFechas($param) {
        extract($param);
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARDOCENTESPORHORARIOCURSO ($idHorarioCurso, '$fechaInicial','$fechaFinal');";
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

    public function ConsultarModulosPorDocente($param) {
        extract($param);
        $resultado = array();
        $registro = array();
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPCONSULTARMODULOSPORDOCENTE1($IdDocente);";
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


     public function consultarCalendarioPreprogramacion($param) {
        extract($param);
        $sql = "CALL SPCONSULTARCALENDARIOPREPROGRAMACION($idPreprogramacion);";
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        if ($rs = $conexion->getPDO()->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                $diasClase=[];
                foreach ($filas as $fila) {
                    //Generar arrays dias preprogramación
                    switch ($fila['Nombre']) {
                        case "Lunes a Viernes":
                             $diasClase= array("Lunes","Martes","Miercoles","Jueves","Viernes");
                        break;
                        case "Lunes a Sabado":
                             $diasClase= array("Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
                        break;
                        case "Lunes a Miercoles":
                             $diasClase= array("Lunes","Martes","Miercoles");
                        break;
                        case "Jueves a Sabados":
                             $diasClase= array("Jueves","Viernes","Sabado");
                        break;
                        case "Martes y Miercoles": //--
                             $diasClase= array("Martes","Miercoles");
                        break;
						case "Martes a Sabado":
                             $diasClase= array("Martes","Miercoles","Jueves","Viernes","Sabado");
                        break;
                        case "Sabado":
                             $diasClase= array("Sabado");
                        break;
						case "Jueves a Martes":
                             $diasClase= array("jueves","viernes","sabado","lunes","martes");
                        break;
						case "Lunes a Jueves":
                             $diasClase= array("lunes","martes","miercoles","jueves");
                        break;
						case "Lunes, Viernes y Sábado":
                             $diasClase= array("lunes","viernes","sabado");
                        break;
						case "Martes a Viernes":
                             $diasClase= array("martes","miercoles","jueves","viernes");
                        break;
						case "Martes y Jueves":
                             $diasClase= array("martes","miercoles","jueves");
                        break;
						case "Domingo":
                             $diasClase= array("domingo");
                        break;
						case "Jueves":
                             $diasClase= array("jueves");
                        break;
						case "Jueves y Viernes":
                             $diasClase= array("jueves","viernes");
                        break;
						case "Lunes y Martes":
                             $diasClase= array("lunes","martes");
                        break;
						case "Lunes, Martes, Jueves y Viernes":
                             $diasClase= array("lunes","martes","jueves","viernes");
                        break;
						case "Viernes y Sábado":
                             $diasClase= array("viernes","sabado");
                        break;
						case "Lunes, Miércoles y Viernes":
                             $diasClase= array("lunes","miercoles","viernes");
                        break;
						case "Lunes y miércoles":
                             $diasClase= array("lunes","miercoles");
                        break;
						case "Lunes y viernes":
                             $diasClase= array("lunes","viernes");
                        break;
						case "Lunes martes y jueves":
                             $diasClase= array("lunes","martes","jueves");
                        break;
						case "Lunes":
                             $diasClase= array("lunes");
                        break;
						case "Martes":
                             $diasClase= array("martes");
                        break;
						case "Miércoles a sábado":
                             $diasClase= array("miercoles","jueves","viernes","sabado");
                        break;
						case "Miércoles a viernes":
                             $diasClase= array("miercoles","jueves","viernes");
                        break;
						case "Miércoles y jueves":
                             $diasClase= array("miercoles","jueves");
                        break;
						case "Miércoles y viernes":
                             $diasClase= array("miercoles","viernes");
                        break;
						case "Miércoles":
                             $diasClase= array("miercoles");
                        break;
						case "Viernes":
                             $diasClase= array("viernes");
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
                            $fecha = $dias[date('N', strtotime($fechamostrar))];
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
                      $array[] = $NoSesion;
            }
        } else {
            $array = 0;
        }
        echo json_encode($array);
    }



    public function agregarEvaluacion($param) {
        extract($param);
        $conexion->getPDO()->query("SET NAMES 'utf8'");
        $sql = "CALL SPAGREGAREVALUACION($docente,'$satisfaccion', '$descripcionSatisfaccion', '$descripcionServicio',
        '$aspectosPositivos', '$aspectosParaMejorar', '$claridad',
        '$metodologia', '$contenidos', '$material',
        '$instalaciones', '$objetivos', '$tiempos','$codigo', $idTercero);";
        // push($array, $sql); si se devuelve >0 si inserto
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
        // echo $sql;
        // $arrayName = array('sql' => $sql);
        echo json_encode($array);
    }
}

?>