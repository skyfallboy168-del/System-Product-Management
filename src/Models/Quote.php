<?php
namespace Models;

use Core\Database;

class Quote {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all quotes for a specific company.
     * @param int $company_id
     * @return array
     */
    public function findAllByCompany($company_id) {
        $this->db->query('
            SELECT
                q.*,
                c.name as customer_name
            FROM quotes q
            JOIN customers c ON q.customer_id = c.id
            WHERE q.company_id = :company_id
            ORDER BY q.quote_date DESC
        ');
        $this->db->bind(':company_id', $company_id);
        return $this->db->resultSet();
    }

    /**
     * Find a quote by its ID, ensuring it belongs to the company.
     * @param int $id
     * @param int $company_id
     * @return object|false
     */
    public function findById($id, $company_id) {
        $this->db->query('SELECT * FROM quotes WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Add a new quote and its items in a transaction.
     * @param array $data
     * @return int|false The new quote ID or false on failure.
     */
    public function create($data) {
        // The database engine must support transactions (e.g., InnoDB)
        try {
            $this->db->query('START TRANSACTION');

            // Insert into quotes table
            $this->db->query('
                INSERT INTO quotes
                (company_id, customer_id, quote_number, quote_date, expiry_date, subtotal, tax_amount, total, status)
                VALUES
                (:company_id, :customer_id, :quote_number, :quote_date, :expiry_date, :subtotal, :tax_amount, :total, :status)
            ');
            $this->db->bind(':company_id', $data['company_id']);
            $this->db->bind(':customer_id', $data['customer_id']);
            $this->db->bind(':quote_number', $data['quote_number']);
            $this->db->bind(':quote_date', $data['quote_date']);
            $this->db->bind(':expiry_date', $data['expiry_date']);
            $this->db->bind(':subtotal', $data['subtotal']);
            $this->db->bind(':tax_amount', $data['tax_amount']);
            $this->db->bind(':total', $data['total']);
            $this->db->bind(':status', 'draft');
            $this->db->execute();

            $quoteId = $this->db->lastInsertId();

            // Insert into quote_items table
            foreach ($data['items'] as $item) {
                $this->db->query('
                    INSERT INTO quote_items (quote_id, product_id, description, quantity, unit_price, total)
                    VALUES (:quote_id, :product_id, :description, :quantity, :unit_price, :total)
                ');
                $this->db->bind(':quote_id', $quoteId);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':description', $item['description']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':total', $item['total']);
                $this->db->execute();
            }

            $this->db->query('COMMIT');
            return $quoteId;

        } catch (\Exception $e) {
            $this->db->query('ROLLBACK');
            // In a real app, you would log the error $e->getMessage()
            return false;
        }
    }

    /**
     * Get all items for a given quote.
     * @param int $quote_id
     * @return array
     */
    public function findItemsByQuoteId($quote_id) {
        $this->db->query('SELECT * FROM quote_items WHERE quote_id = :quote_id');
        $this->db->bind(':quote_id', $quote_id);
        return $this->db->resultSet();
    }

    /**
     * Update the status of a quote.
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status) {
        $this->db->query('UPDATE quotes SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
