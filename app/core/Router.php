<?php
namespace app\core;

class Router {
    protected $routes = [];

    public function __construct() {
        $this->loadRoutes();
    }

    private function loadRoutes() {
        $this->add('', 'site', 'index');
        $this->add('cajero', 'cajero', 'index');
        $this->add('cajero/mesas', 'cajero', 'mesas');
    }

    public function add($route, $controller, $action) {
        $this->routes[$route] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch() {
        $url = $_GET['url'] ?? '';
        
        if (array_key_exists($url, $this->routes)) {
            $controller = $this->routes[$url]['controller'];
            $action = $this->routes[$url]['action'];
            
            $controllerClass = "app\\controllers\\".ucfirst($controller)."Controller";
            
            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                
                if (method_exists($controllerInstance, $action.'Action')) {
                    call_user_func([$controllerInstance, $action.'Action']);
                } else {
                    $this->showError(404, "Método no encontrado");
                }
            } else {
                $this->showError(404, "Controlador no encontrado");
            }
        } else {
            $this->showError(404, "Ruta no definida");
        }
    }

    private function showError($code, $message) {
        http_response_code($code);
        echo "<h1>Error $code</h1><p>$message</p>";
        exit;
    }
}