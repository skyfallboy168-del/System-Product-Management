<?php
namespace Controllers;

use Core\Controller;

class Quotes extends Controller {
    private $quoteModel;
    private $customerModel;
    private $productModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/public/users/login');
            exit();
        }

        $this->quoteModel = $this->model('Quote');
        $this->customerModel = $this->model('Customer');
        $this->productModel = $this->model('Product');
    }

    public function index() {
        $quotes = $this->quoteModel->findAllByCompany($_SESSION['company_id']);
        $data = [
            'title' => 'Quotes',
            'quotes' => $quotes
        ];
        $this->view('quotes/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            $subtotal = 0;
            $itemsData = [];

            if (!empty($_POST['items'])) {
                foreach ($_POST['items'] as $item) {
                    if (empty($item['product_id']) || empty($item['quantity'])) {
                        continue;
                    }
                    // Security: Fetch product price from DB, don't trust client
                    $product = $this->productModel->findById($item['product_id'], $_SESSION['company_id']);
                    if ($product) {
                        $lineTotal = $product->selling_price_1 * $item['quantity'];
                        $subtotal += $lineTotal;
                        $itemsData[] = [
                            'product_id' => $item['product_id'],
                            'description' => $product->name, // Or a custom description field if you add one
                            'quantity' => $item['quantity'],
                            'unit_price' => $product->selling_price_1,
                            'total' => $lineTotal
                        ];
                    }
                }
            }

            // For now, tax is 0
            $tax = 0;
            $total = $subtotal + $tax;

            $data = [
                'customer_id' => $_POST['customer_id'],
                'quote_number' => $_POST['quote_number'],
                'quote_date' => $_POST['quote_date'],
                'expiry_date' => $_POST['expiry_date'],
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'total' => $total,
                'items' => $itemsData,
                'company_id' => $_SESSION['company_id'],
                'customer_err' => '',
                'items_err' => ''
            ];

            // Validation
            if (empty($data['customer_id'])) {
                $data['customer_err'] = 'Please select a customer.';
            }
            if (empty($data['items'])) {
                $data['items_err'] = 'Please add at least one item to the quote.';
            }

            if (empty($data['customer_err']) && empty($data['items_err'])) {
                if ($this->quoteModel->create($data)) {
                    header('Location: ' . URL_ROOT . '/public/quotes');
                    exit();
                } else {
                    die('Something went wrong creating the quote.');
                }
            } else {
                // Reload form with errors
                $customers = $this->customerModel->findAllByCompany($_SESSION['company_id']);
                $products = $this->productModel->findAllByCompany($_SESSION['company_id']);
                $data['title'] = 'Create New Quote';
                $data['customers'] = $customers;
                $data['products'] = $products;
                $this->view('quotes/form', $data);
            }

        } else {
            // GET request: Show the form
            $customers = $this->customerModel->findAllByCompany($_SESSION['company_id']);
            $products = $this->productModel->findAllByCompany($_SESSION['company_id']);

            $data = [
                'title' => 'Create New Quote',
                'customers' => $customers,
                'products' => $products,
                'quote_number' => 'Q-' . time(), // Simple unique number
                'quote_date' => date('Y-m-d'),
                'expiry_date' => date('Y-m-d', strtotime('+30 days')),
                'customer_id' => null,
                'items' => []
                // Add error fields as needed
            ];
            $this->view('quotes/form', $data);
        }
    }

    public function view($id) {
        $quote = $this->quoteModel->findById($id, $_SESSION['company_id']);
        if (!$quote) {
            header('Location: ' . URL_ROOT . '/public/quotes');
            exit();
        }

        $items = $this->quoteModel->findItemsByQuoteId($id);
        $customer = $this->customerModel->findById($quote->customer_id, $_SESSION['company_id']);

        $data = [
            'title' => 'Quote ' . $quote->quote_number,
            'quote' => $quote,
            'items' => $items,
            'customer' => $customer
        ];
        $this->view('quotes/view', $data);
    }

    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            // Add validation for status value if needed
            if ($this->quoteModel->updateStatus($id, $status)) {
                header('Location: ' . URL_ROOT . '/public/quotes/view/' . $id);
                exit();
            } else {
                die('Something went wrong updating status.');
            }
        } else {
            header('Location: ' . URL_ROOT . '/public/quotes/view/' . $id);
            exit();
        }
    }
}
