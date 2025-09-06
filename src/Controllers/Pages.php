<?php

namespace Controllers;

use Core\Controller;

class Pages extends Controller {

    public function __construct() {
        // In a real app, you might load a model here
        // $this->pageModel = $this->model('Page');
    }

    /**
     * Default method, handles the home page.
     */
    public function index() {
        $data = [
            'title' => 'Welcome to BizMaster',
            'description' => 'This is a lightweight, custom-built PHP MVC framework for the BizMaster application. The core structure is now in place.'
        ];

        // The view method is inherited from the base Controller
        $this->view('pages/index', $data);
    }

    /**
     * An example of another page.
     * URL: /pages/about
     */
    public function about() {
        $data = [
            'title' => 'About Us',
            'description' => 'BizMaster is a multi-company business management system.'
        ];

        $this->view('pages/about', $data);
    }
}
