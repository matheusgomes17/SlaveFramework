<?php defined('DACCESS') or die ('Acceso restringido!');

/* establecemos los niveles de error*/
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

/* importamos el archivo de definicion de rutas. **/
if(!@require 'includes/defines.php'){
    echo "Error loading defines.php";
}

/* Cargamos las librerias pricipales del sistema. */
spl_autoload_register(function ($class) {
    if(!@require SYSTEM_PATH. $class . '.php'){
        echo "Error loading ".$class." class";
    }
});
