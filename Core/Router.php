<?php
/**
 * The Router class which allow use urls like
 * index.php?controller=name&action=example
 */
class Router {
    
    /**
     * Set default controller and action
     */
    public function init($app)
    {
        // Default controller is TaskController
        $controller = !empty($_GET['controller']) ? $_GET['controller'] : 'task';
        // Default action name is index
        $action = !empty($_GET['action']) ? $_GET['action'] : 'index';
        $router = new self;
        return $this->useController($app, $controller, $action);
    }
    
    /**
    * Route to controllers action with params
    * @param String $controller name of the controllers
    * @param String $action name of the action
    */
    private function useController($app, $controller, $action)
    {
        $controller     = ucfirst($controller);
        $controllerName = $controller . 'Controller';
        $controllerObj  = new $controllerName($app, $action);
        return $controllerObj;
    }

}
