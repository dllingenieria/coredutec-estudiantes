<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utilidades
 *
 * @author Administrador
 */
class Utilidades {

    /**
     * Defina aquí metodos estáticos que vayan a ser usados por toda la aplicación 
     */
    public static function verificarComunicacion() {
        // Hacer algo...
        echo json_encode(["mensaje" => "Todo bien"]);
    }

    /**
     * Permite descargar un archivo
     * @param type $argumentos un array asociativo con el elemento 'archivo' que contiene el nombre del archivo a descargar
     * @throws Exception
     */
    public static function descargar($argumentos) {
        extract($argumentos);
        try {
            // Por su salud mental, en lo posible utilice rutas relativas y no absotutas
            $rutaArchivo = DOCUMENT_ROOT . "demoCRUD/serviciosTecnicos/varios/$archivo";
            if (!is_file($rutaArchivo)) {
                throw new Exception("El archivo $archivo no se encuentre disponible");
            } else {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Disposition: attachment; filename=\"$archivo\"\n");  // Oculta la ruta de descarga y permite espacios en nombres de archivos
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . filesize($rutaArchivo));
                @readfile($rutaArchivo);
            }
        } catch (Exception $e) {
            echo json_encode(['ok' => 0, 'mensaje' => $e->getMessage()]);  // Este mensaje no soporta formateo del html incluso usando htmlspecialchars()
        }
    }

    public static function subirArchivo() {
        $carpetaDestino = DOCUMENT_ROOT . "demoCRUD/serviciosTecnicos/varios/";
        $archivoDestino = isset($_REQUEST["name"]) ? $_REQUEST["name"] : ('basura_' . substr(md5(rand()), 0, 10) . '.tmp');
        $archivoTemporal = $_FILES['file']['tmp_name'];

        if (is_file($archivoTemporal)) {
            if (is_dir($carpetaDestino)) {
                if (move_uploaded_file($archivoTemporal, $carpetaDestino . $archivoDestino)) {
                    // Todo bien. Es posible que haya que hacer algo con el archivo que se acaba de subir
                    // ...
                } else {
                    echo('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Falló la carga de <b>' . $archivoDestino . '</b>."}, "id" : "id"}');
                }
            } else {
                echo('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "No se encontró la carpeta destino"}, "id" : "id"}');
            }
        } else {
            echo('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "No se pudo abrir el archivo <b>' . $archivoDestino . '</b>."}, "id" : "id"}');
        }
    }

    public static function getSelect($elementos) {
        $select = "<select>";
        foreach ($elementos as $key => $value) {
            $select = $select . "<option value='$key'>$value</option> ";
        };
        $select = $select . "</select>";
        return $select;
    }

    /**
     * Ejemplo de uso de la librería PHPExcel
     *   Create new PHPExcel object
     *   echo date('H:i:s') . " Create new PHPExcel object\n";
     *   $objPHPExcel = new PHPExcel();
     *   $objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
     *   ...
     *   $objPHPExcel->setActiveSheetIndex(0);
     *   ...
     *   $objWorksheet = self::$objPHPExcel->createSheet();
     * 
     *   $objWorksheet->setCellValue("A4", 'xxxx');
     * 
     *   $objWorksheet->setCellValueByColumnAndRow($j, $i, $valor);
     * 
     */
    public static function pruebaXLSXXXXXXXX() {
        $archivos = glob("../serviciosTecnicos/varios/bds/*.xlsx");  // sensible a mayúsculas

        foreach ($archivos as $archivo) {
            error_log("    archivo --> $archivo");
            $objPHPExcel = PHPExcel_IOFactory::load($archivo);
            $hojas = $objPHPExcel->getSheetNames();

            foreach ($hojas as $hoja) {
                $objWorksheet = $objPHPExcel->getSheetByName($hoja);
                $idAsignatura = explode('-', $hoja)[0];
                if (ctype_digit($idAsignatura)) {
                    $asignaturaDada = UtilConexion::$pdo->query("SELECT * FROM asignatura_select WHERE id = $idAsignatura")->fetch();
                    if ($id = $asignaturaDada['id']) {
                        // notas-profesor-idAsignatura-nombreCorto-fila-columna-[0:valor definitiva|1:calcular definitiva]-[0:no guardar parciales|1:guardar parciales]-ColColceptos-año-periodo-colINASE-colINACE (AK es poco probable que tenga un valor)
                        // notas-7920096 -120         -Geo        -6-   F-      1-                                         0-                                          R-2013-1-E-D
                        $datosAsignatura = 'notas-' . $asignaturaDada["fk_profesor"] . '-' . $asignaturaDada["id"] . '-' . $asignaturaDada["nombre_corto"] . "-6-F-1-0-R-2013-1-E-D";
                        error_log("datosAsignatura = $datosAsignatura");
                        $objWorksheet->setCellValue('AF1', $datosAsignatura);
                        for ($i = 6; $i < 70; $i++) {
                            $objWorksheet->setCellValue("AF$i", 1);
                        }
                        $objWorksheet->getColumnDimension('AF')->setVisible(FALSE);
                    }
                } else {
                    error_log("      ******    Hoja con problemas $archivo - $hoja");
                }
            }
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($archivo);
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
        }
    }

    /**
     * Un ejemplo de cómo se enviarían los datos del calendario a la capa de presentación
     * Falta filtrar por fecha inicio y fecha fin que fullCalendar manda por defecto
     */
    public function getEventos($argumentos) {
        //error_log(print_r($argumentos,1));
        extract($argumentos);
        $where = "WHERE fk_sala = $idSala";

        $eventos = [];
        foreach (UtilConexion::$pdo->query("SELECT * FROM calendario $where") as $evento) {
            $eventos[] = [
                'id' => $evento['id'],
                'title' => $evento['profesor'],
                'start' => $evento['hora_inicio'],
                'end' => $evento['hora_fin'],
                'sala' => $evento['sala'],
                'allDay' => FALSE,
                'color' => $evento['color'],
                'anotacion' => $evento['anotacion'],
                    //'ignoreTimezone' => true
            ];
        }
        //error_log(print_r($eventos, TRUE));
        echo json_encode($eventos);
    }

    public function eliminarReserva($argumentos) {///////////*******************************
        extract($argumentos);
        error_log(print_r($argumentos, 1));
        error_log("DELETE FROM reserva_sala WHERE id=$idReserva");
        UtilConexion::$pdo->exec("DELETE FROM reserva_sala WHERE id_reserva_sala=$idReserva");
        UtilConexion::getEstado();
    }

}

?>
