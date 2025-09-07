<?php

namespace Core;

class App {
    protected $currentController = 'PagesController';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        if (isset($url[0]) && !empty($url[0])) {
            $controllerName = ucwords($url[0]) . 'Controller';
            $controllerFile = APP_ROOT . '/src/Controllers/' . $controllerName . '.php';

            if (file_exists($controllerFile)) {
                $this->currentController = $controllerName;
                unset($url[0]);
            }
        }

        require_once APP_ROOT . '/src/Controllers/' . $this->currentController . '.php';

        $controllerClassName = 'Controllers\\' . $this->currentController;
        $this->currentController = new $controllerClassName;

        if (isset($url[1])) {
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
