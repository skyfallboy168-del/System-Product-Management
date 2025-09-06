<?php

namespace Core;

/*
 * Main Application Class (Front Controller)
 * Handles routing and dispatching requests.
 * URL FORMAT: /controller/method/params
 */
class App {
    protected $currentController = 'Pages'; // Default controller
    protected $currentMethod = 'index';     // Default method
    protected $params = [];                 // Parameters from URL

    public function __construct() {
        $url = $this->getUrl();

        // Look for the controller file based on the first part of the URL.
        // e.g., 'users' -> 'src/Controllers/Users.php'
        if (isset($url[0]) && file_exists('../src/Controllers/' . ucwords($url[0]) . '.php')) {
            // If exists, set as current controller
            $this->currentController = ucwords($url[0]);
            // Unset 0 Index
            unset($url[0]);
        }

        // Require the controller file
        require_once '../src/Controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        // e.g., $pages = new Pages;
        $controllerClassName = 'Controllers\\' . $this->currentController;
        $this->currentController = new $controllerClassName;

        // Check for the second part of the URL (the method)
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                // Unset 1 index
                unset($url[1]);
            }
        }

        // Get params - the rest of the URL parts
        $this->params = $url ? array_values($url) : [];

        // Call the controller method with the params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    /**
     * Gets the URL parts from the query string.
     * @return array
     */
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
