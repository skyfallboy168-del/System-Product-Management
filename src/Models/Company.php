<?php
namespace Models;

use Core\Database;

class Company {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Find a company by its ID.
     * @param int $id
     * @return object|false
     */
    public function findById($id) {
        $this->db->query('SELECT * FROM companies WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Create a default company.
     * @param array $data
     * @return int|false The new company ID or false on failure.
     */
    public function createDefault($data) {
        $this->db->query('INSERT INTO companies (name, address_line_1, city, state, postal_code, country) VALUES (:name, :address_line_1, :city, :state, :postal_code, :country)');

        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address_line_1', $data['address_line_1']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':state', $data['state']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':country', $data['country']);

        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
}
