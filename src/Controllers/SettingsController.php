<?php
namespace Controllers;

use Core\Controller;

class Settings extends Controller {
    private $settingsModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/public/users/login');
            exit();
        }
        $this->settingsModel = $this->model('Settings');
    }

    public function index() {
        \Core\Auth::gate('settings-manage');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $dataToSave = [
                'currency_symbol' => trim($_POST['currency_symbol']),
                'default_tax_rate' => trim($_POST['default_tax_rate']),
                'invoice_prefix' => trim($_POST['invoice_prefix']),
                'quote_prefix' => trim($_POST['quote_prefix'])
            ];

            if ($this->settingsModel->updateSettings($_SESSION['company_id'], $dataToSave)) {
                // Optionally show a success message
                header('Location: ' . URL_ROOT . '/public/settings');
                exit();
            } else {
                die('Something went wrong updating settings.');
            }

        } else {
            // Display form
            $settings = $this->settingsModel->getSettings($_SESSION['company_id']);
            $data = [
                'title' => 'Application Settings',
                'currency_symbol' => $settings['currency_symbol'] ?? '$',
                'default_tax_rate' => $settings['default_tax_rate'] ?? '0',
                'invoice_prefix' => $settings['invoice_prefix'] ?? 'INV-',
                'quote_prefix' => $settings['quote_prefix'] ?? 'Q-'
            ];
            $this->view('settings/index', $data);
        }
    }
}
