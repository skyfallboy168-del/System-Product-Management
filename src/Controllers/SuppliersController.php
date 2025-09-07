<?php
namespace Controllers;

use Core\Controller;

class Suppliers extends Controller {
    private $supplierModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/public/users/login');
            exit();
        }
        $this->supplierModel = $this->model('Supplier');
    }

    public function index() {
        \Core\Auth::gate('settings-manage');
        $suppliers = $this->supplierModel->findAllByCompany($_SESSION['company_id']);
        $data = [
            'title' => 'Suppliers',
            'suppliers' => $suppliers
        ];
        $this->view('suppliers/index', $data);
    }

    public function add() {
        \Core\Auth::gate('settings-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = $this->getPostData();
            $data['company_id'] = $_SESSION['company_id'];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter supplier name';
            }

            if (empty($data['name_err'])) {
                if ($this->supplierModel->create($data)) {
                    header('Location: ' . URL_ROOT . '/public/suppliers');
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('suppliers/form', $data);
            }
        } else {
            $this->view('suppliers/form', $this->getPostData(true));
        }
    }

    public function edit($id) {
        \Core\Auth::gate('settings-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = $this->getPostData();
            $data['id'] = $id;
            $data['company_id'] = $_SESSION['company_id'];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter supplier name';
            }

            if (empty($data['name_err'])) {
                if ($this->supplierModel->update($data)) {
                    header('Location: ' . URL_ROOT . '/public/suppliers');
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('suppliers/form', $data);
            }
        } else {
            $supplier = $this->supplierModel->findById($id, $_SESSION['company_id']);
            if (!$supplier) {
                header('Location: ' . URL_ROOT . '/public/suppliers');
                exit();
            }
            $this->view('suppliers/form', (array)$supplier);
        }
    }

    public function delete($id) {
        \Core\Auth::gate('settings-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->supplierModel->delete($id, $_SESSION['company_id'])) {
                header('Location: ' . URL_ROOT . '/public/suppliers');
                exit();
            } else {
                die('Something went wrong');
            }
        } else {
            header('Location: ' . URL_ROOT . '/public/suppliers');
            exit();
        }
    }

    private function getPostData($empty = false) {
        $fields = ['name', 'contact_person', 'email', 'phone'];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $empty ? '' : trim($_POST[$field]);
            $data[$field . '_err'] = '';
        }
        return $data;
    }
}
