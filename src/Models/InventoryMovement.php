<?php
namespace Models;

use Core\Database;

class InventoryMovement {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Create a new inventory movement record.
     * This should typically be called within a transaction.
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $this->db->query('
            INSERT INTO inventory_movements
            (product_id, user_id, type, quantity, reason, related_document_type, related_document_id)
            VALUES
            (:product_id, :user_id, :type, :quantity, :reason, :related_document_type, :related_document_id)
        ');

        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':quantity', $data['quantity']); // e.g., -5 for a stock-out of 5 units
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':related_document_type', $data['related_document_type']); // e.g., 'Invoice'
        $this->db->bind(':related_document_id', $data['related_document_id']); // e.g., the invoice ID

        return $this->db->execute();
    }
}
