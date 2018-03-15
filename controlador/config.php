<?php

// Posibilidades para deshabilitar el caché según varios tipos de navegadores. ¿**Deberían ir en el controlador**?
header('Content-Type: text/html; charset=UTF-8');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Constantes propias de la aplicación
define('COMSPEC', filter_input(INPUT_SERVER, 'COMSPEC'));
define('ROOT', filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'));
define('DOCUMENT_ROOT', substr('ROOT', -1) == '/' ? 'ROOT' : 'ROOT' . '/');
define('RUTA_APLICACION', DOCUMENT_ROOT . 'PDF/');
define('PHPEXCEL_ROOT', '../includes/PHPExcel/');
define('TCPDF_ROOT', '../includes/PDF/');

//LINEA AGREGADA PARA MANEJAR PHPWORD 14-11-14
define('PHPWORD_ROOT', '../includes/PHPWord/');
//define('TMP_PATH', sys_get_temp_dir() . DIRECTORY_SEPARATOR );
//define('TMP_PATH', 'C:\wamp\www\boleteriayabonos\PDF' . DIRECTORY_SEPARATOR ); // Directorio para guardar y descargar los archivos del sistema
define('TMP_PATH', '/Applications/XAMPP/xamppfiles/htdocs/corfuturoHV/PDF' . DIRECTORY_SEPARATOR ); // Directorio para guardar y descargar los archivos del sistema
 


//require_once '../../includes/swiftMailer/swift_required.php';

define('BASE_DATOS', 'SINFOMPC');
define('SERVIDOR', '201.217.194.205');
define('PUERTO', '3306');
define('USUARIO', 'DLL-Ingenieria');
define('CONTRASENA', 'd09;LU>l');

/*define('USUARIO', 'sirex');
define('CONTRASENA', 'x0uJcss2');

define('BASE_DATOS', 'CET');
define('SERVIDOR', '190.0.49.18');
define('PUERTO', '3306');
define('USUARIO', 'dit');
define('CONTRASENA', 'd09;LU>l');*/

spl_autoload_register('__autoload');
// Para PHP 6 E_STRICT es parte de E_ALL -- error_reporting(E_ALL | E_STRICT); para verificación exhaustivo --
error_reporting(E_ERROR);

/**
 * Intenta cargar una clase siguiendo la siguiente convención:
 * Si el nombre de la clase comienza con Util, la clase será una clase de utilidades con 
 * métodos estáticos que se cargada desde la carpeta "Utilidades", en caso contrario se
 * cargará desde la carpeta "Modelo" y no definirá métodos estáticos
 * @param type $nombreClase El nombre de la clase a cargar
 */
function __autoload($nombreClase) {
    include_once('config.php');
    //require_once ('Certificados.php');
    if (substr($nombreClase, 0, 7) == 'Reporte') {
        $nombreClase = "../serviciosTecnicos/reportes/$nombreClase.php";
    } else if (substr($nombreClase, 0, 4) == 'Util') {
        $nombreClase = "../serviciosTecnicos/utilidades/$nombreClase.php";
    } else if (substr($nombreClase, 0, 8) == 'PHPExcel') {
        $nombreClase = PHPEXCEL_ROOT . str_replace('_', '/', $nombreClase) . '.php';
    } else if (substr($nombreClase, 0, 7) == 'PHPWord') {
        $nombreClase = PHPWORD_ROOT . str_replace('_', '/', $nombreClase) . '.php';
    } else {
        $nombreClase = "../modelo/$nombreClase.php";
    }
    include_once($nombreClase);
    //require_once ($nombreClase);
}
