<?php

namespace Core;

/*
 * Base Controller
 * Loads the models and views
 */
abstract class Controller {

    /**
     * Loads a model file and returns an instance of it.
     * @param string $model The name of the model, e.g., 'User'
     * @return object The model object
     */
    public function model($model) {
        $modelFile = '../src/Models/' . $model . '.php';
        // Require model file
        if (file_exists($modelFile)) {
            require_once $modelFile;
            // Instantiate model
            $modelClass = 'Models\\' . $model;
            return new $modelClass();
        } else {
            // Model does not exist
            die('Model does not exist: ' . $model);
        }
    }

    /**
     * Loads a view file with its data.
     * @param string $view The path of the view, e.g., 'pages/index'
     * @param array $data Data to pass to the view
     */
    public function view($view, $data = []) {
        $viewFile = '../src/Views/' . $view . '.php';
        // Check for view file
        if (file_exists($viewFile)) {
            // Extract data array into individual variables
            extract($data);

            require_once $viewFile;
        } else {
            // View does not exist
            die('View does not exist: ' . $view);
        }
    }
}
