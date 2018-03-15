<?php

include_once('config.php');
// include dirname(__FILE__) . '/conexionBD.php';
new Fachada();

class Fachada {
    public function __construct() {
        try {
            date_default_timezone_set('America/Bogota');
            ini_set('display_errors', 'on');
            ini_set('log_errors', 'On');
            ini_set('error_log', 'log.txt');
            $this->ejecutar($_REQUEST);
        } catch (Exception $e) {
            error_log("Problemas en $clase::$metodo()\n" . $e->getMessage());
            echo json_encode(array("ok" => 0, "mensaje" => $e->getMessage()));
        }
    }

    function ejecutar($args) {
        $args['conexion'] = new UtilConexion();
        $clase = $args['clase'];
        $metodo = $args['oper'];

        if (class_exists($clase)) {
            $obj = new $clase();
            if (method_exists($obj, $metodo)) {
                $obj->{$metodo}($args);
            } else {
                throw new Exception("Imposible responder al mensaje enviado. Argumentos recibidos:\n" . print_r($_REQUEST, 1));
            }
        } else {
            throw new Exception("No se pudo definir un receptor de mensajes. Argumentos recibidos:\n" . print_r($_REQUEST, 1));
        }
    }
}