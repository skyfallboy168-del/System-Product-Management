<?php

namespace Core;

// --- Temporary Debugging ---
echo '<pre style="background: #eee; padding: 10px; border: 1px solid #ccc;">';
echo '<strong>DEBUGGING OUTPUT:</strong><br>';
if (isset($_GET['url'])) {
    echo '<strong>$_GET[\'url\']:</strong> ' . htmlspecialchars($_GET['url']) . '<br>';
} else {
    echo '<strong>$_GET[\'url\']:</strong> Not Set<br>';
}
// --- End Debugging ---

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

        // --- Temporary Debugging ---
        echo '<strong>Parsed URL array:</strong> ';
        print_r($url);
        echo '<br>';
        // --- End Debugging ---

        // Look for the controller file based on the first part of the URL.
        // e.g., 'products' -> 'src/Controllers/ProductsController.php'
        if (isset($url[0]) && !empty($url[0])) {
            $controllerName = ucwords($url[0]);
            $controllerFile = '../src/Controllers/' . $controllerName . 'Controller.php';

            // --- Temporary Debugging ---
            echo '<strong>Attempting to find controller:</strong> ' . htmlspecialchars($controllerFile) . '<br>';
            echo '<strong>Result of file_exists():</strong> ' . (file_exists($controllerFile) ? 'TRUE' : 'FALSE') . '<br>';
            // --- End Debugging ---

            if (file_exists($controllerFile)) {
                // If exists, set as current controller
                $this->currentController = $controllerName . 'Controller';
                // Unset 0 Index
                unset($url[0]);
            }
        }

        // --- Temporary Debugging ---
        echo '<strong>Controller to be loaded:</strong> ' . htmlspecialchars($this->currentController) . '.php<br>';
        echo '</pre>';
        // --- End Debugging ---

        // Require the controller file
        require_once '../src/Controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        // e.g., $pagesController = new \Controllers\PagesController();
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
