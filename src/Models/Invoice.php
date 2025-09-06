<?php
namespace Models;

use Core\Database;

class Invoice {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all invoices for a specific company.
     * @param int $company_id
     * @return array
     */
    public function findAllByCompany($company_id) {
        $this->db->query('
            SELECT
                i.*,
                c.name as customer_name
            FROM invoices i
            JOIN customers c ON i.customer_id = c.id
            WHERE i.company_id = :company_id
            ORDER BY i.invoice_date DESC
        ');
        $this->db->bind(':company_id', $company_id);
        return $this->db->resultSet();
    }

    /**
     * Find an invoice by its ID, ensuring it belongs to the company.
     * @param int $id
     * @param int $company_id
     * @return object|false
     */
    public function findById($id, $company_id) {
        $this->db->query('SELECT * FROM invoices WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Add a new invoice and its items in a transaction.
     * @param array $data
     * @return int|false The new invoice ID or false on failure.
     */
    public function create($data) {
        // We need to instantiate the other models here to use them inside the transaction
        $productModel = new \Models\Product();
        $inventoryMovementModel = new \Models\InventoryMovement();

        try {
            $this->db->query('START TRANSACTION');

            // Insert into invoices table
            $this->db->query('
                INSERT INTO invoices
                (company_id, customer_id, quote_id, invoice_number, invoice_date, due_date, subtotal, tax_amount, total, status)
                VALUES
                (:company_id, :customer_id, :quote_id, :invoice_number, :invoice_date, :due_date, :subtotal, :tax_amount, :total, :status)
            ');
            $this->db->bind(':company_id', $data['company_id']);
            $this->db->bind(':customer_id', $data['customer_id']);
            $this->db->bind(':quote_id', $data['quote_id']); // Can be null
            $this->db->bind(':invoice_number', $data['invoice_number']);
            $this->db->bind(':invoice_date', $data['invoice_date']);
            $this->db->bind(':due_date', $data['due_date']);
            $this->db->bind(':subtotal', $data['subtotal']);
            $this->db->bind(':tax_amount', $data['tax_amount']);
            $this->db->bind(':total', $data['total']);
            $this->db->bind(':status', 'draft');
            $this->db->execute();

            $invoiceId = $this->db->lastInsertId();

            // Insert into invoice_items table
            foreach ($data['items'] as $item) {
                $this->db->query('
                    INSERT INTO invoice_items (invoice_id, product_id, description, quantity, unit_price, total)
                    VALUES (:invoice_id, :product_id, :description, :quantity, :unit_price, :total)
                ');
                $this->db->bind(':invoice_id', $invoiceId);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':description', $item['description']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':total', $item['total']);
                $this->db->execute();

                // Adjust stock and log movement
                $productModel->adjustStock($item['product_id'], -$item['quantity']);

                $movementData = [
                    'product_id' => $item['product_id'],
                    'user_id' => $_SESSION['user_id'], // Assumes user_id is in session
                    'type' => 'stock-out',
                    'quantity' => -$item['quantity'],
                    'reason' => 'Sale',
                    'related_document_type' => 'Invoice',
                    'related_document_id' => $invoiceId
                ];
                $inventoryMovementModel->create($movementData);
            }

            $this->db->query('COMMIT');
            return $invoiceId;

        } catch (\Exception $e) {
            $this->db->query('ROLLBACK');
            // Log error $e->getMessage()
            return false;
        }
    }

    /**
     * Get all items for a given invoice.
     * @param int $invoice_id
     * @return array
     */
    public function findItemsByInvoiceId($invoice_id) {
        $this->db->query('SELECT * FROM invoice_items WHERE invoice_id = :invoice_id');
        $this->db->bind(':invoice_id', $invoice_id);
        return $this->db->resultSet();
    }

    /**
     * Find an invoice by its source quote ID.
     * @param int $quote_id
     * @return object|false
     */
    public function findByQuoteId($quote_id) {
        $this->db->query('SELECT * FROM invoices WHERE quote_id = :quote_id');
        $this->db->bind(':quote_id', $quote_id);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }
}
