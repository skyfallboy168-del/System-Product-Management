<?php
namespace Controllers;

use Core\Controller;

class Payments extends Controller {
    private $paymentModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/public/users/login');
            exit();
        }
        $this->paymentModel = $this->model('Payment');
    }

    /**
     * Handle the submission of the "Add Payment" form.
     */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $invoice_id = $_POST['invoice_id'];

            $data = [
                'invoice_id' => $invoice_id,
                'amount' => trim($_POST['amount']),
                'payment_date' => trim($_POST['payment_date']),
                'payment_method' => trim($_POST['payment_method']),
                'notes' => trim($_POST['notes']),
                'amount_err' => ''
            ];

            // Validate amount
            if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
                $data['amount_err'] = 'Please enter a valid amount.';
            }

            // In a real app, you would reload the invoice view with the error.
            // For now, we'll just die on error for simplicity.
            if (!empty($data['amount_err'])) {
                die($data['amount_err']);
            }

            // Process payment
            if ($this->paymentModel->create($data)) {
                // Redirect back to the invoice view
                header('Location: ' . URL_ROOT . '/public/invoices/view/' . $invoice_id);
                exit();
            } else {
                die('Something went wrong while adding the payment.');
            }

        } else {
            // Not a POST request, redirect home
            header('Location: ' . URL_ROOT . '/public');
            exit();
        }
    }
}
