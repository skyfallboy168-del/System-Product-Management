<?php
namespace Models;

use Core\Database;

class Payment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Add a payment for an invoice and update the invoice status.
     * @param array $data
     * @return bool
     */
    public function create($data) {
        try {
            $this->db->query('START TRANSACTION');

            // 1. Insert into payments table
            $this->db->query('
                INSERT INTO payments (invoice_id, payment_date, amount, payment_method, notes)
                VALUES (:invoice_id, :payment_date, :amount, :payment_method, :notes)
            ');
            $this->db->bind(':invoice_id', $data['invoice_id']);
            $this->db->bind(':payment_date', $data['payment_date']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':payment_method', $data['payment_method']);
            $this->db->bind(':notes', $data['notes']);
            $this->db->execute();

            // 2. Update the invoice's amount_paid and status
            $this->db->query('
                UPDATE invoices
                SET
                    amount_paid = amount_paid + :amount,
                    status = IF(total <= (amount_paid + :amount), "paid", "partially_paid")
                WHERE id = :invoice_id
            ');
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':invoice_id', $data['invoice_id']);
            $this->db->execute();

            $this->db->query('COMMIT');
            return true;

        } catch (\Exception $e) {
            $this->db->query('ROLLBACK');
            // Log error $e->getMessage()
            return false;
        }
    }

    /**
     * Get all payments for a given invoice.
     * @param int $invoice_id
     * @return array
     */
    public function findAllByInvoice($invoice_id) {
        $this->db->query('SELECT * FROM payments WHERE invoice_id = :invoice_id ORDER BY payment_date DESC');
        $this->db->bind(':invoice_id', $invoice_id);
        return $this->db->resultSet();
    }
}
