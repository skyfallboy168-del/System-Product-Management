<?php
namespace Controllers;

use Core\Controller;

class Products extends Controller {
    private $productModel;
    private $categoryModel;
    private $supplierModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/public/users/login');
            exit();
        }

        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('ProductCategory');
        $this->supplierModel = $this->model('Supplier');
    }

    public function index() {
        \Core\Auth::gate('product-view'); // Or 'product-manage' for full access
        $products = $this->productModel->findAllByCompany($_SESSION['company_id']);
        $data = [
            'title' => 'Products',
            'products' => $products
        ];
        $this->view('products/index', $data);
    }

    public function add() {
        \Core\Auth::gate('product-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = $this->getPostData();
            $data['company_id'] = $_SESSION['company_id'];

            if (empty($data['name'])) $data['name_err'] = 'Please enter product name';
            if (empty($data['sku'])) $data['sku_err'] = 'Please enter SKU';
            if (empty($data['selling_price_1'])) $data['selling_price_1_err'] = 'Please enter a default selling price';

            if (empty($data['name_err']) && empty($data['sku_err']) && empty($data['selling_price_1_err'])) {
                if ($this->productModel->create($data)) {
                    header('Location: ' . URL_ROOT . '/public/products');
                    exit();
                } else {
                    die('Something went wrong creating the product.');
                }
            } else {
                $this->loadFormWithData($data);
            }
        } else {
            $this->loadFormWithData();
        }
    }

    public function edit($id) {
        \Core\Auth::gate('product-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = $this->getPostData();
            $data['id'] = $id;
            $data['company_id'] = $_SESSION['company_id'];

            if (empty($data['name'])) $data['name_err'] = 'Please enter product name';
            if (empty($data['sku'])) $data['sku_err'] = 'Please enter SKU';
            if (empty($data['selling_price_1'])) $data['selling_price_1_err'] = 'Please enter a default selling price';

            if (empty($data['name_err']) && empty($data['sku_err']) && empty($data['selling_price_1_err'])) {
                if ($this->productModel->update($data)) {
                    header('Location: ' . URL_ROOT . '/public/products');
                    exit();
                } else {
                    die('Something went wrong updating the product.');
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

    public function delete($id) {
        \Core\Auth::gate('product-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->productModel->delete($id, $_SESSION['company_id'])) {
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

    private function getPostData() {
        $data = [
            'name' => trim($_POST['name']),
            'sku' => trim($_POST['sku']),
            'barcode' => trim($_POST['barcode']),
            'description' => trim($_POST['description']),
            'unit' => trim($_POST['unit']),
            'category_id' => $_POST['category_id'],
            'supplier_id' => $_POST['supplier_id'],
            'purchase_price' => trim($_POST['purchase_price']) ?: 0,
            'markup_percentage' => trim($_POST['markup_percentage']) ?: 0,
            'selling_price_1' => trim($_POST['selling_price_1']) ?: 0,
            'selling_price_2' => trim($_POST['selling_price_2']) ?: 0,
            'discount_price_1' => trim($_POST['discount_price_1']) ?: 0,
            'discount_price_2' => trim($_POST['discount_price_2']) ?: 0,
            'discount_price_3' => trim($_POST['discount_price_3']) ?: 0,
            'name_err' => '',
            'sku_err' => '',
            'selling_price_1_err' => ''
        ];
        return $data;
    }

    private function loadFormWithData($data = []) {
        $categories = $this->categoryModel->findAllByCompany($_SESSION['company_id']);
        $suppliers = $this->supplierModel->findAllByCompany($_SESSION['company_id']);

        $defaultData = [
            'id' => '', 'name' => '', 'sku' => '', 'barcode' => '', 'description' => '', 'unit' => 'pcs',
            'category_id' => null, 'supplier_id' => null, 'purchase_price' => '', 'markup_percentage' => '',
            'selling_price_1' => '', 'selling_price_2' => '', 'discount_price_1' => '', 'discount_price_2' => '', 'discount_price_3' => '',
            'categories' => $categories, 'suppliers' => $suppliers,
            'name_err' => '', 'sku_err' => '', 'selling_price_1_err' => ''
        ];

        $viewData = array_merge($defaultData, $data);
        $this->view('products/form', $viewData);
    }

    public function adjustments() {
        \Core\Auth::gate('product-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'product_id' => $_POST['product_id'],
                'type' => $_POST['type'],
                'quantity' => trim($_POST['quantity']),
                'reason' => trim($_POST['reason']),
                'product_err' => '',
                'quantity_err' => ''
            ];

            if(empty($data['product_id'])) $data['product_err'] = 'Please select a product.';
            if(empty($data['quantity']) || !is_numeric($data['quantity']) || $data['quantity'] == 0) {
                $data['quantity_err'] = 'Please enter a valid, non-zero quantity.';
            }

            if(empty($data['product_err']) && empty($data['quantity_err'])) {
                $quantity = $data['type'] === 'stock-out' ? -$data['quantity'] : $data['quantity'];

                // Use a transaction to ensure both operations succeed or fail together
                $this->productModel->adjustStock($data['product_id'], $quantity);

                $inventoryMovementModel = $this->model('InventoryMovement');
                $movementData = [
                    'product_id' => $data['product_id'],
                    'user_id' => $_SESSION['user_id'],
                    'type' => $data['type'],
                    'quantity' => $quantity,
                    'reason' => $data['reason'],
                    'related_document_type' => 'Manual Adjustment',
                    'related_document_id' => null
                ];
                $inventoryMovementModel->create($movementData);

                header('Location: ' . URL_ROOT . '/public/products');
                exit();
            } else {
                // Reload form with errors
                $products = $this->productModel->findAllByCompany($_SESSION['company_id']);
                $data['products'] = $products;
                $data['title'] = 'Stock Adjustments';
                $this->view('products/adjustments', $data);
            }

        } else {
            $products = $this->productModel->findAllByCompany($_SESSION['company_id']);
            $data = [
                'title' => 'Stock Adjustments',
                'products' => $products,
                'product_id' => '', 'type' => 'stock-in', 'quantity' => '', 'reason' => '',
                'product_err' => '', 'quantity_err' => ''
            ];
            $this->view('products/adjustments', $data);
        }
    }
}
