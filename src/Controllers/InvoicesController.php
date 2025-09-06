<?php
namespace Controllers;

use Core\Controller;

class Invoices extends Controller {
    private $invoiceModel;
    private $paymentModel;
    private $customerModel;
    private $productModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/public/users/login');
            exit();
        }

        $this->invoiceModel = $this->model('Invoice');
        $this->paymentModel = $this->model('Payment');
        $this->customerModel = $this->model('Customer');
        $this->productModel = $this->model('Product');
    }

    public function index() {
        $invoices = $this->invoiceModel->findAllByCompany($_SESSION['company_id']);
        $data = [
            'title' => 'Invoices',
            'invoices' => $invoices
        ];
        $this->view('invoices/index', $data);
    }

    public function view($id) {
        $invoice = $this->invoiceModel->findById($id, $_SESSION['company_id']);
        if (!$invoice) {
            header('Location: ' . URL_ROOT . '/public/invoices');
            exit();
        }

        $items = $this->invoiceModel->findItemsByInvoiceId($id);
        $customer = $this->customerModel->findById($invoice->customer_id, $_SESSION['company_id']);
        $payments = $this->paymentModel->findAllByInvoice($id);

        $data = [
            'title' => 'Invoice ' . $invoice->invoice_number,
            'invoice' => $invoice,
            'items' => $items,
            'customer' => $customer,
            'payments' => $payments
        ];
        $this->view('invoices/view', $data);
    }

    public function add() {
        // This will be very similar to the QuotesController add method.
        // It will use a similar form and JavaScript.
        // For now, redirect to the index.
        header('Location: ' . URL_ROOT . '/public/invoices');
        exit();
    }

    public function createFromQuote($quote_id) {
        // Ensure this is a POST request if you want to prevent accidental creation via URL
        // For simplicity, we'll allow GET for now.

        // 1. Check if an invoice for this quote already exists
        $existingInvoice = $this->invoiceModel->findByQuoteId($quote_id);
        if ($existingInvoice) {
            // Redirect to the existing invoice
            header('Location: ' . URL_ROOT . '/public/invoices/view/' . $existingInvoice->id);
            exit();
        }

        // 2. Get the quote and its items
        $quote = $this->model('Quote')->findById($quote_id, $_SESSION['company_id']);
        $quoteItems = $this->model('Quote')->findItemsByQuoteId($quote_id);

        if (!$quote || $quote->status !== 'approved') {
            // Can't create invoice from non-approved quote or quote that doesn't exist
            // Redirect back with an error message in a real app
            header('Location: ' . URL_ROOT . '/public/quotes/view/' . $quote_id);
            exit();
        }

        // 3. Prepare data for new invoice
        $itemsData = [];
        foreach($quoteItems as $item) {
            $itemsData[] = [
                'product_id' => $item->product_id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total
            ];
        }

        $invoiceData = [
            'company_id' => $_SESSION['company_id'],
            'customer_id' => $quote->customer_id,
            'quote_id' => $quote_id,
            'invoice_number' => 'INV-' . time(), // Generate a unique invoice number
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'subtotal' => $quote->subtotal,
            'tax_amount' => $quote->tax_amount,
            'total' => $quote->total,
            'items' => $itemsData
        ];

        // 4. Create the invoice
        $newInvoiceId = $this->invoiceModel->create($invoiceData);

        if ($newInvoiceId) {
            // Redirect to the new invoice
            header('Location: ' . URL_ROOT . '/public/invoices/view/' . $newInvoiceId);
            exit();
        } else {
            die('Failed to create invoice from quote.');
        }
    }
}
