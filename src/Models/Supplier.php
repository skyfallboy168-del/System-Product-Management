<?php
namespace Models;

use Core\Database;

class Supplier {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all suppliers for a specific company.
     * @param int $company_id
     * @return array
     */
    public function findAllByCompany($company_id) {
        $this->db->query('SELECT * FROM suppliers WHERE company_id = :company_id ORDER BY name ASC');
        $this->db->bind(':company_id', $company_id);
        return $this->db->resultSet();
    }

    /**
     * Find a supplier by its ID, ensuring it belongs to the company.
     * @param int $id
     * @param int $company_id
     * @return object|false
     */
    public function findById($id, $company_id) {
        $this->db->query('SELECT * FROM suppliers WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Add a new supplier.
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $this->db->query('INSERT INTO suppliers (company_id, name, contact_person, email, phone) VALUES (:company_id, :name, :contact_person, :email, :phone)');
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_person', $data['contact_person']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        return $this->db->execute();
    }

    /**
     * Update an existing supplier.
     * @param array $data
     * @return bool
     */
    public function update($data) {
        $this->db->query('UPDATE suppliers SET name = :name, contact_person = :contact_person, email = :email, phone = :phone WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_person', $data['contact_person']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        return $this->db->execute();
    }

    /**
     * Delete a supplier.
     * @param int $id
     * @param int $company_id
     * @return bool
     */
    public function delete($id, $company_id) {
        $this->db->query('DELETE FROM suppliers WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        return $this->db->execute();
    }
}
