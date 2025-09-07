<?php
namespace Controllers;

use Core\Controller;

class Customers extends Controller {
    private $customerModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/public/users/login');
            exit();
        }
        $this->customerModel = $this->model('Customer');
    }

    public function index() {
        \Core\Auth::gate('customer-manage');
        $customers = $this->customerModel->findAllByCompany($_SESSION['company_id']);
        $data = [
            'title' => 'Customers',
            'customers' => $customers
        ];
        $this->view('customers/index', $data);
    }

    public function add() {
        \Core\Auth::gate('customer-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = $this->getPostData();
            $data['company_id'] = $_SESSION['company_id'];

            // Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter customer name';
            }
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email';
            }

            if (empty($data['name_err']) && empty($data['email_err'])) {
                if ($this->customerModel->create($data)) {
                    header('Location: ' . URL_ROOT . '/public/customers');
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('customers/form', $data);
            }
        } else {
            $this->view('customers/form', $this->getPostData(true));
        }
    }

    public function edit($id) {
        \Core\Auth::gate('customer-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = $this->getPostData();
            $data['id'] = $id;
            $data['company_id'] = $_SESSION['company_id'];

            // Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter customer name';
            }
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email';
            }

            if (empty($data['name_err']) && empty($data['email_err'])) {
                if ($this->customerModel->update($data)) {
                    header('Location: ' . URL_ROOT . '/public/customers');
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('customers/form', $data);
            }
        } else {
            $customer = $this->customerModel->findById($id, $_SESSION['company_id']);
            if (!$customer) {
                header('Location: ' . URL_ROOT . '/public/customers');
                exit();
            }
            $this->view('customers/form', (array)$customer);
        }
    }

    public function delete($id) {
        \Core\Auth::gate('customer-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->customerModel->delete($id, $_SESSION['company_id'])) {
                header('Location: ' . URL_ROOT . '/public/customers');
                exit();
            } else {
                die('Something went wrong.');
            }
        } else {
            header('Location: ' . URL_ROOT . '/public/customers');
            exit();
        }
    }

    /**
     * Helper to get POST data for add/edit.
     * @param bool $empty Returns empty data if true.
     * @return array
     */
    private function getPostData($empty = false) {
        if ($empty) {
            return [
                'name' => '', 'email' => '', 'phone' => '', 'customer_company_name' => '',
                'tax_id' => '', 'address_line_1' => '', 'city' => '', 'state' => '',
                'postal_code' => '', 'country' => '', 'name_err' => '', 'email_err' => ''
            ];
        }
        return [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'phone' => trim($_POST['phone']),
            'customer_company_name' => trim($_POST['customer_company_name']),
            'tax_id' => trim($_POST['tax_id']),
            'address_line_1' => trim($_POST['address_line_1']),
            'city' => trim($_POST['city']),
            'state' => trim($_POST['state']),
            'postal_code' => trim($_POST['postal_code']),
            'country' => trim($_POST['country']),
            'name_err' => '',
            'email_err' => ''
        ];
    }
}
