<?php
namespace Controllers;

use Core\Controller;

class ProductCategories extends Controller {
    private $categoryModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/public/users/login');
            exit();
        }
        $this->categoryModel = $this->model('ProductCategory');
    }

    public function index() {
        $categories = $this->categoryModel->findAllByCompany($_SESSION['company_id']);
        $data = [
            'title' => 'Product Categories',
            'categories' => $categories
        ];
        $this->view('product_categories/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'name' => trim($_POST['name']),
                'company_id' => $_SESSION['company_id'],
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter category name';
            }

            if (empty($data['name_err'])) {
                if ($this->categoryModel->create($data)) {
                    header('Location: ' . URL_ROOT . '/public/productcategories');
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('product_categories/form', $data);
            }
        } else {
            $data = [
                'name' => '',
                'name_err' => ''
            ];
            $this->view('product_categories/form', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'company_id' => $_SESSION['company_id'],
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter category name';
            }

            if (empty($data['name_err'])) {
                if ($this->categoryModel->update($data)) {
                    header('Location: ' . URL_ROOT . '/public/productcategories');
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('product_categories/form', $data);
            }
        } else {
            $category = $this->categoryModel->findById($id, $_SESSION['company_id']);
            if (!$category) {
                header('Location: ' . URL_ROOT . '/public/productcategories');
                exit();
            }
            $data = [
                'id' => $id,
                'name' => $category->name,
                'name_err' => ''
            ];
            $this->view('product_categories/form', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->categoryModel->delete($id, $_SESSION['company_id'])) {
                header('Location: ' . URL_ROOT . '/public/productcategories');
                exit();
            } else {
                die('Something went wrong.');
            }
        } else {
            header('Location: ' . URL_ROOT . '/public/productcategories');
            exit();
        }
    }
}
