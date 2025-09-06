<?php
namespace Controllers;

use Core\Controller;

class Products extends Controller {
    private $productModel;
    private $categoryModel;
    private $supplierModel;

    public function __construct() {
        // Redirect non-logged-in users
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/public/users/login');
            exit();
        }

        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('ProductCategory');
        $this->supplierModel = $this->model('Supplier');
    }

    /**
     * Show product list for the company.
     */
    public function index() {
        $products = $this->productModel->findAllByCompany($_SESSION['company_id']);
        $data = [
            'title' => 'Products',
            'products' => $products
        ];
        $this->view('products/index', $data);
    }

    /**
     * Show form to add a new product and handle POST request.
     */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'sku' => trim($_POST['sku']),
                'description' => trim($_POST['description']),
                'unit' => trim($_POST['unit']),
                'purchase_price' => trim($_POST['purchase_price']),
                'selling_price_1' => trim($_POST['selling_price_1']),
                'category_id' => $_POST['category_id'],
                'supplier_id' => $_POST['supplier_id'],
                'company_id' => $_SESSION['company_id'],
                'name_err' => '',
                'sku_err' => '',
                'selling_price_1_err' => ''
            ];

            // Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter product name';
            }
            if (empty($data['sku'])) {
                $data['sku_err'] = 'Please enter SKU';
            }
            if (empty($data['selling_price_1'])) {
                $data['selling_price_1_err'] = 'Please enter a selling price';
            }

            if (empty($data['name_err']) && empty($data['sku_err']) && empty($data['selling_price_1_err'])) {
                if ($this->productModel->create($data)) {
                    // Redirect to product list
                    header('Location: ' . URL_ROOT . '/public/products');
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->loadFormWithData($data);
            }

        } else {
            $this->loadFormWithData();
        }
    }

    /**
     * Show form to edit a product and handle POST request.
     * @param int $id The product ID.
     */
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'sku' => trim($_POST['sku']),
                'description' => trim($_POST['description']),
                'unit' => trim($_POST['unit']),
                'purchase_price' => trim($_POST['purchase_price']),
                'selling_price_1' => trim($_POST['selling_price_1']),
                'category_id' => $_POST['category_id'],
                'supplier_id' => $_POST['supplier_id'],
                'company_id' => $_SESSION['company_id'],
                'name_err' => '',
                'sku_err' => '',
                'selling_price_1_err' => ''
            ];

            // Validation (same as add)
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter product name';
            }
            if (empty($data['sku'])) {
                $data['sku_err'] = 'Please enter SKU';
            }
            if (empty($data['selling_price_1'])) {
                $data['selling_price_1_err'] = 'Please enter a selling price';
            }

            if (empty($data['name_err']) && empty($data['sku_err']) && empty($data['selling_price_1_err'])) {
                if ($this->productModel->update($data)) {
                    header('Location: ' . URL_ROOT . '/public/products');
                    exit();
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->loadFormWithData($data);
            }

        } else {
            $product = $this->productModel->findById($id, $_SESSION['company_id']);
            if (!$product) {
                header('Location: ' . URL_ROOT . '/public/products');
                exit();
            }
            $this->loadFormWithData((array)$product);
        }
    }

    /**
     * Handle product deletion.
     * @param int $id The product ID.
     */
    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->productModel->delete($id, $_SESSION['company_id'])){
                header('Location: ' . URL_ROOT . '/public/products');
                exit();
            } else {
                die('Something went wrong.');
            }
        } else {
            header('Location: ' . URL_ROOT . '/public/products');
            exit();
        }
    }


    /**
     * Helper function to load the add/edit form with necessary data.
     * @param array $data Data to populate the form with.
     */
    private function loadFormWithData($data = []) {
        // Get categories and suppliers for dropdowns
        $categories = $this->categoryModel->findAllByCompany($_SESSION['company_id']);
        $suppliers = $this->supplierModel->findAllByCompany($_SESSION['company_id']);

        $defaultData = [
            'id' => '',
            'name' => '',
            'sku' => '',
            'description' => '',
            'unit' => 'pcs',
            'purchase_price' => '',
            'selling_price_1' => '',
            'category_id' => null,
            'supplier_id' => null,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'name_err' => '',
            'sku_err' => '',
            'selling_price_1_err' => ''
        ];

        $viewData = array_merge($defaultData, $data);

        $this->view('products/form', $viewData);
    }
}
