<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of clsGestorArchivosPlanos
 *
 * @author wandres
 */
class clsGestorArchivosPlanos {

    public $arc_pla = '../../anexos/Formatos/ingresoPersonas.txt';

    /*
     * leerArchivoPlano()
     * Función que lee un archivo plano y almacena su contenido en 
     * una arreglo local llamado $tex_arc.
     * Retorna el arreglo $tex_arc.
     */
    public function leerArchivoPlano() {
        $arc_pla = '../anexos/Formatos/ingresoPersonas.txt';
        $tex_arc = array();
        if (file_exists($arc_pla)) {
            $arc_pla = @fopen($arc_pla, "r") or exit("No pudo leer el archivo, verifique el nombre y la ruta");
            $i = 0;
            while (!feof($arc_pla)) {
                $tex_arc[$i] = trim(fgets($arc_pla));
                $i++;
            }
            @fclose($arc_pla);
        }
        return $tex_arc;
    }
}

?>
