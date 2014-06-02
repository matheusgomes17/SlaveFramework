<?php defined('DACCESS') or die ('Acceso restringido!');
/**
 * Clase principal que arrancara el sistema.
 * @author David Unay Santisteban <slavepens@gamil.com>
 * @package SlaveFramework
 * @version 1.0
 */
class Application {
    
    protected $_config;
    
    private $_router;
    private $_controller;
    private $_datagram = array();
    
    /**
     * Contructor de la aplicacion.
     */
    public function __construct() {
        $this->_config = $this->loadClass('config', 'includes');
        session_start();
        mb_internal_encoding($this->_config->encoding);
    }
    
    /**
     * Llama al router y realiza el enrutamiento de la 
     * ruta solicitada, para llamar al controlador y 
     * metodo adecuado.
     */
    public function route(){
        $this->_router = $this->loadClass('Router', 'system');
        $this->_router->configure($this->_config);
        $this->_router->match();
        $this->_router->segment();
        $this->_datagram = $this->_router->dispatch();
    }
    
    /**
     * Carga el controlador especificado por el router y ejecuta la
     * llamada de los metodos con sus respectivos argumentos.
     */
    public function dispatch(){
        include CTRLS_PATH.$this->_datagram['controller'].'.php';
        
        $class = ucfirst($this->_datagram['controller']);
        $this->_controller = new $class();
        
        if(isset($this->_datagram['method'])){
            $method = $this->_datagram['method'];
        } else {
            //metodo por defecto
            $method = 'index';
        }

        if(method_exists($class,$method)){

            if(isset($this->_datagram['args'])){
                $args = $this->_datagram['args'];
            } else {
                $args = array(null);
            }
            // ejecutamos el metodo con sus argumentos, si es que existen.
            call_user_func_array(array($this->_controller,$method),$args);
        }
        
    }
    
    /**
     * Renderiza el resultado final de la aplicaicon.
     */
    public function render(){
        print $this->_controller->buffer;
    }
    
    /**
     * Se encarga de iniciar cada clase soliciada como propiedad
     * del controlador permitiendo hacer uso de la misma desde 
     * cualquier parte de la aplicacion como si de un super objeto
     * se tratase.
     * @param string $class
     * @param string $directory
     * @return object
     */
    public static function loadClass($class,$directory){
        require $directory.DS.$class.'.php';
        return new $class();
    }
}
