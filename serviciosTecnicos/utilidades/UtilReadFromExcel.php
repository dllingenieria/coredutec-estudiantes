<?php

/**
 * Description of ReadFromExcel
 *
 * @author Sebas
 */
$new = new ReadFromExcel();
$new->leerHojaHorarios();

class UtilReadFromExcel {

    public static function prueba() {
        error_log("INGRESA A LA CLASE READ FROM EXCEL!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
    }

    public static function leerHojaHorarios() {
        $obj = new ReadFromExcel();
        $baseDeDatos = 'USI';
        $servidor = 'localhost';  // 127.0.0.1:80
        $puerto = '5432';  // puerto postgres
        $usuario = 'postgres';
        $contrasena = 'zerimar';
        // ver http://www.phpro.org/tutorials/Introduction-to-PHP-PDO.html
        $pdo = new PDO("pgsql:host=$servidor port=$puerto dbname=$baseDeDatos", $usuario, $contrasena);

        $archivos = glob("../serviciosTecnicos/varios/*.xlsx");  // sensible a mayúsculas

        foreach ($archivos as $archivo) {
            $objPHPExcel = PHPExcel_IOFactory::load($archivo);

            $hojas = $objPHPExcel->getSheetNames();

            foreach ($hojas as $hoja) {
                $objWorksheet = $objPHPExcel->getSheetByName($hoja);
                $id = $obj->leerHojacedula($objWorksheet);
                $first_name = $obj->leerHojanombre($objWorksheet);
                $last_name = $obj->LeerHojaApellido($objWorksheet);
                for ($i = 0; $i < count($id); $i++) {
                    $usuarioQuery = "INSERT INTO usuario(cedula,nombre,apellidos,telefono,celular) VALUES('$id[$i]','$first_name[$i]','$last_name[$i]','','');";
//            echo $usuarioQuery;
//            echo '<br>';
                    $pdo->exec($usuarioQuery);
                }


                $highestRow = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $row = array();
                $hojaEntera = array();
                for ($cont2 = 1; $cont2 < $highestRow; $cont2++) {
                    for ($cont1 = 0; $cont1 <= $highestColumnIndex; $cont1++) {
                        $data = $objWorksheet->getCellByColumnAndRow($cont1, $cont2)->getValue();
                        if (($cont1 === 6) || ($cont1 === 7)) {
                            if ((!strstr($data, '/')) && ($data != "Inicio") && ($data != "")) {
                                $timestamp = (intval($data) - 25569) * 86400;
                                $dateSwap = (string) date("m/d/Y", $timestamp);
                                array_push($row, $dateSwap);
                            } else {
                                array_push($row, $data);
                            }
                        } else {
                            array_push($row, $data);
                        }
                    }
                    array_push($hojaEntera, $row);
                    $row = array();
                }

                $objPHPExcel->disconnectWorksheets();
                unset($objPHPExcel);

                for ($cont3 = 1; $cont3 < $highestRow - 1; $cont3++) {
                    for ($cont4 = 0; $cont4 < count($hojaEntera[$cont3]); $cont4++) {
                        if ($cont4 === 0) {
                            if ($hojaEntera[$cont3][0] != '' && $hojaEntera[$cont3][1] != '' && $hojaEntera[$cont3][2] != '' && $hojaEntera[$cont3][1] != 'materia' && $hojaEntera[$cont3][1] != 'Materia') {
                                $asignaturaQuery = "INSERT INTO asignatura values('" . $hojaEntera[$cont3][0] . "','" . $hojaEntera[$cont3][1] . "','" . $hojaEntera[$cont3][2] . "');";
                                $pdo->exec($asignaturaQuery);
                                $idgrupo = $hojaEntera[$cont3][0] . '-' . $hojaEntera[$cont3][3];

                                $grupoQuery = "INSERT INTO grupo(codigo, cupos, fecha_inicio, fecha_fin, fk_asignatura,fk_profesor)VALUES ('" . $idgrupo . "', '" . $hojaEntera[$cont3][4] . "', to_date('" . $hojaEntera[$cont3][6] . "', 'DD-MM-YYYY') , to_date('" . $hojaEntera[$cont3][7] . "', 'DD-MM-YYYY'), '" . $hojaEntera[$cont3][0] . "',null);";
                                $pdo->exec($grupoQuery);
                                $horarios = $obj->disgregarHorario($hojaEntera[$cont3][8] . '');
                                $diasClases = $obj->getDias($horarios);
                                $sala;
                                $fk_grupo = $idgrupo;
                                $tipo = "Restrinccion";
                                if (isset($id[$cont3])) {
                                    $responsable = $id[$cont3];
                                } else {
                                    $responsable = 'Desconocido';
                                }

                                $inicio;
                                $duracion;
                                for ($i = 0; $i < count($horarios); $i++) {
                                    $sala = $obj->obtenerSala($horarios[$i]); //obtengo la sala para insertar en restrinccion_calendario

                                    $inicioDuracion = $obj->lexemaHorarios($pdo, $horarios[$i], $idgrupo); //retorno el un arreglo [INICIO,DURACION]
                                    $inicio = $inicioDuracion[0];
                                    $duracion = $inicioDuracion[1];
                                }
                                $obj->fecha($pdo, $hojaEntera[$cont3][6], $hojaEntera[$cont3][7], $inicio, $duracion, $responsable, $tipo, $fk_grupo, $sala, $diasClases);
                            }
                        }
                    }
                }
            }
        }
        return 'recorrio';
    }

    public static function getDias($horarios) {
        $dias = array();
        for ($i = 0; $i < count($horarios); $i++) {
            $aux = $horarios[$i];
            array_push($dias, strstr($aux, " ", true));
        }
        return($dias);
    }

    //insersion horario_grupo
    public static function lexemaHorarios($pdo, $dato, $idgrupo) {
        $dia = '';
        $hora = '';
        $duracion = '';
        $largo = strlen($dato);
        $i = 0;
        //hallar lexema dia
        for ($i; $i < $largo; $i++) {
            if ($dato{$i} === ' ') {
                $i++;
                break;
            }
            $dia = $dia . $dato{$i};
        }
        //hallar lexema hora
        for ($i; $i < $largo; $i++) {
            if ($dato{$i} === '-') {
                $i++;
                break;
            }
            $hora = $hora . $dato{$i};
        }
        $horaNumero = $hora . ':00:00';

        //hallar lexema duracion Sa 8-10 OTRO ESPACIO
        for ($i; $i < $largo; $i++) {
            $duracion = $duracion . $dato{$i};
            if ($dato{$i} === ' ') {
                break;
            }
        }
        $duracionNumber = intval($duracion) * 60;

        //generar query para insercion en horario_grupo
        $queryHorarioGrupo = "INSERT INTO horario_grupo (dia,hora_inicio,duracion,fk_grupo) VALUES('" . $dia . "','" . $horaNumero . "'," . $duracionNumber . ",'" . $idgrupo . "');";
//        echo $queryHorarioGrupo;
//        echo '<br>';

        $pdo->exec($queryHorarioGrupo);

        $inicioDuracion = array();
        array_push($inicioDuracion, $horaNumero);
        array_push($inicioDuracion, $duracion);
        return $inicioDuracion;
    }

    public static function disgregarHorario($horario) {
        $aux = '';
        $horarios = array();
        $largo = strlen($horario);
        $dato = 0;
        for ($i = 0; $i < $largo - 1; $i++) {
            if ($horario{$i} === '[') {
                $dato = 1;
                $aux = '';
                $i++;
            }
            if ($horario{$i} === ']') {
                $dato = 0;
            }
            if ($dato === 1) {
                $aux = $aux . $horario{$i};
            }
            if (($dato === 0) && ($horario{$i} != ' ')) {
                $var = '';
                $var = $aux;
                array_push($horarios, $var);
            }
        }
        return ($horarios);
    }

    //insersion restriccion_calendario
    public static function fecha($pdo, $fechaInicio, $fechaFin, $inicio, $duracion, $responsable, $tipo, $fk_grupo, $sala, $clase) {
        $timestamp = strtotime($fechaInicio);
        $day = date('D', $timestamp); //dia en letra que comenzaron clase

        $diaOrigen = intval($fechaInicio{0} . $fechaInicio{1}); //dia en numero en que comenzaron la clase
        $mesOrigen = intval($fechaInicio{3} . $fechaInicio{4});
        $anioOrigen = intval($fechaInicio{6} . $fechaInicio{7} . $fechaInicio{8} . $fechaInicio{9});
        $diaDestino = intval($fechaFin{0} . $fechaFin{1});
        $mesDestino = intval($fechaFin{3} . $fechaFin{4});
        $anioDestino = intval($fechaFin{6} . $fechaFin{7} . $fechaFin{8} . $fechaFin{9});

        $days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

        $diasDiferencia = $diaDestino - $diaOrigen;
        $mesesDiferencia = $mesDestino - $mesOrigen;
        $deltadias = ($mesesDiferencia * 30) + ($diasDiferencia);

        for ($i = 0; $i < count($clase); $i++) {

            $dia = $clase[$i]; //dia de la clase

            if ($dia == "Lu") {
                $dia = "Mon";
            }
            if ($dia == "Ma") {
                $dia = "Tue";
            }
            if ($dia == "Mi") {
                $dia = "Wed";
            }
            if ($dia == "Ju") {
                $dia = "Thu";
            }
            if ($dia == "Vi") {
                $dia = "Fri";
            }
            if ($dia == "Sa") {
                $dia = "Sat";
            }
            if ($dia == "Dom") {
                $dia = "Sun";
            }
            if ($day != $dia) {
                $contadorDias = 0;
                $posdiaclase = 0; //inicio de la clase
                $posdiainicio = 0;
                for ($k = 0; $k < count($days); $k++) {//obtengo la posicion del dia en la semana
                    if ($dia == $days[$k]) {
                        $posdiaclase = $k;
                    }
                    if ($day == $days[$k]) {
                        $posdiainicio = $k;
                    }
                }
                $bol = 1;
                while ($bol == 1) {

                    if ($posdiainicio === 7) {
                        $posdiainicio = 0;
                    }
                    if ($days[$posdiainicio] === $dia) {

                        $bol = 0;
                    }
                    $contadorDias++;
                    $posdiainicio++;
                }
                $diaClase = $diaOrigen + $contadorDias - 1;
                $mes = $mesOrigen;


                $contsemanas = 0;
                while ($contsemanas < $deltadias) {
                    if ($diaClase <= $diaDestino && $mes <= $mesDestino) {
                        $horarios = "$diaClase-$mes-$anioOrigen";
                        $queryRestriccionCalendario = "INSERT INTO restricciones_calendario(inicio,duracion,responsable,tipo,fk_grupo,sala,fecha) values('" . $inicio . "'," . $duracion . ",'" . $responsable . "','" . $tipo . "','" . $fk_grupo . "','" . $sala . "',to_date('" . $horarios . "', 'DD-MM-YYYY'))";
                        $pdo->exec($queryRestriccionCalendario);
//                        echo $queryRestriccionCalendario;
//                        echo '<br>';
                    }

                    $contsemanas+=7;
                    $diaClase+=7;
                    if ($diaClase > 30) {
                        $diaClase - 30;
                        $mes++;
                    }
                }
            } else {
                $diaClase = $diaOrigen;
                $contsemanas = 0;
                $mes = $mesOrigen;
                while ($contsemanas < $deltadias) {
                    if ($diaClase <= $diaDestino && $mes <= $mesDestino) {
                        $horarios = "$diaClase-$mes-$anioOrigen";
                        $queryRestriccionCalendario = "INSERT INTO restricciones_calendario(inicio,duracion,responsable,tipo,fk_grupo,sala,fecha) values('" . $inicio . "'," . $duracion . ",'" . $responsable . "','" . $tipo . "','" . $fk_grupo . "','" . $sala . "',to_date('" . $horarios . "', 'DD-MM-YYYY'))";
                        $pdo->exec($queryRestriccionCalendario);
                    }

                    $contsemanas+=7;
                    $diaClase+=7;
                    if ($diaClase > 30) {
                        $diaClase - 30;
                        $mes++;
                    }
                }
            }
        }
    }

    public static function obtenerSala($inicial) {
        $espacios = 0;
        $sala = '';
        for ($i = 0; $i < strlen($inicial); $i++) {
            if ($inicial{$i} === " ") {
                $espacios++;
            }
            if ($espacios >= 2) {
                $sala = $sala . $inicial{$i};
            }
        }
        $baseDeDatos = 'USI';
        $servidor = 'localhost';  // 127.0.0.1:80
        $puerto = '5432';  // puerto postgres
        $usuario = 'postgres';
        $contrasena = 'zerimar';
        // ver http://www.phpro.org/tutorials/Introduction-to-PHP-PDO.html
        $pdo = new PDO("pgsql:host=$servidor port=$puerto dbname=$baseDeDatos", $usuario, $contrasena);
        $querySala = "INSERT INTO sala(nombre) values('" . $sala . "');";
        $pdo->exec($querySala);
        return $sala;
    }

    public static function leerHojacedula($objWorksheet) {
        $coordenada = 0;
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        for ($cont1 = 1; $cont1 < $highestRow; $cont1++) {
            for ($cont2 = 0; $cont2 <= $highestColumnIndex; $cont2++) {
                $data = $objWorksheet->getCellByColumnAndRow($cont1, $cont2);
                if ($data == "Profesor") {
                    $coordenada = $cont1;
                }
            }
        }
        $identidades = array();
        for ($cont3 = $coordenada; $cont3 <= $coordenada; $cont3++) {
            for ($cont4 = 3; $cont4 <= $highestRow; $cont4++) {
                $cedula = $objWorksheet->getCellByColumnAndRow($coordenada, $cont4);
                if ($cedula != "Profesor") {

                    $identificacion = strchr($cedula, ':', true); //strstr desde un caracter hasta el final   
                    array_push($identidades, $identificacion); //SE AGREGA LA CEDULA AL VECTOR 
                }
            }
        }
        return $identidades;
    }

    public static function LeerHojaApellido($objWorksheet) {

        $coordenada = 0;

        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        for ($cont1 = 1; $cont1 < $highestRow; $cont1++) {
            for ($cont2 = 0; $cont2 <= $highestColumnIndex; $cont2++) {
                $celda = $objWorksheet->getCellByColumnAndRow($cont1, $cont2);
                if ($celda == "Profesor") {

                    $coordenada = $cont1;
                }
            }
        }
        $nombres = array();
        for ($cont3 = $coordenada; $cont3 <= $coordenada; $cont3++) {
            for ($cont4 = 3; $cont4 <= $highestRow; $cont4++) {
                $name = $objWorksheet->getCellByColumnAndRow($coordenada, $cont4);
                if ($name != "Profesor") {
                    $name = strstr($name, ':'); //strstr desde un caracter hasta el final   :n
                    if ($name === ':N/I') {
                        $name = 'DESCONOCIDO';
                    } else {
                        $name = strstr($name, $name{1});
                        $name = strchr($name, ' ', true);
                    }
                    array_push($nombres, $name); //SE AGREGA EL APELLIDO AL ARRAY
                }
            }
        }
        return $nombres;
    }

    public static function leerHojanombre($objWorksheet) {


        $coordenada = 0;

        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        for ($cont1 = 1; $cont1 < $highestRow; $cont1++) {
            for ($cont2 = 0; $cont2 <= $highestColumnIndex; $cont2++) {
                $celda = $objWorksheet->getCellByColumnAndRow($cont1, $cont2);
                if ($celda == "Profesor") {

                    $coordenada = $cont1;
                }
            }
        }
        $names = array();
        for ($cont3 = $coordenada; $cont3 <= $coordenada; $cont3++) {
            for ($cont4 = 3; $cont4 <= $highestRow; $cont4++) {
                $nombre = $objWorksheet->getCellByColumnAndRow($coordenada, $cont4);
                if ($nombre != "Profesor") {
                    $nombre = strstr($nombre, ':'); //strstr desde un caracter hasta el final   :n
                    if ($nombre === ':N/I') {
                        $nombre = 'DESCONOCIDO';
                        array_push($names, $nombre); //SE AGREGA EL APELLIDO AL ARRAY
                    } else {
                        $nombre = strstr($nombre, $nombre{1});
                        $nombre = strstr($nombre, ' ');
                        $espacios = 0;
                        $apellido = '';
                        for ($i = 0; $i < strlen($nombre); $i++) {    //strlen($nombre)
                            if ($nombre{$i} === " ") {
                                $espacios++;
                            }// }
                            if ($espacios === 2) {
                                $contes = 0;
                                for ($i = 0; $i < strlen($nombre); $i++) {
                                    if ($nombre{$i} === " ") {
                                        $contes++;
                                    }
                                    if ($contes >= 2) {
                                        $apellido = $apellido . $nombre{$i};
                                    }
                                }
                            }
                            if ($espacios === 3) {
                                $contador = 0;
                                for ($i = 0; $i < strlen($nombre); $i++) {
                                    if ($nombre{$i} === " ") {
                                        $contador++;
                                    }
                                    if ($contador >= 3) {
                                        $apellido = $apellido . $nombre{$i};
                                    }
                                }
                            }
                        }
                        array_push($names, $apellido); //SE AGREGA EL APELLIDO AL ARRAY
                    }
                }
            }
        }
        return $names;
    }

}

?>
