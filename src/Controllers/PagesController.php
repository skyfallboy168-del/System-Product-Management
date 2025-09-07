<?php
namespace Controllers;

use Core\Controller;

class PagesController extends Controller {

    public function __construct() {
        // This constructor is intentionally empty for the pages controller.
    }

    public function index() {
        $data = [
            'title' => 'Welcome to BizMaster',
            'description' => 'This is a lightweight, custom-built PHP MVC framework for the BizMaster application. The core structure is now in place.'
        ];
        $this->view('pages/index', $data);
    }

    public function about() {
        $data = [
            'title' => 'About Us',
            'description' => 'BizMaster is a multi-company business management system.'
        ];
        $this->view('pages/about', $data);
    }
}
