<?php
namespace Models;

use Core\Database;

class ProductCategory {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all categories for a specific company.
     * @param int $company_id
     * @return array
     */
    public function findAllByCompany($company_id) {
        $this->db->query('SELECT * FROM product_categories WHERE company_id = :company_id ORDER BY name ASC');
        $this->db->bind(':company_id', $company_id);
        return $this->db->resultSet();
    }

    /**
     * Find a category by its ID, ensuring it belongs to the company.
     * @param int $id
     * @param int $company_id
     * @return object|false
     */
    public function findById($id, $company_id) {
        $this->db->query('SELECT * FROM product_categories WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Add a new product category.
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $this->db->query('INSERT INTO product_categories (company_id, name) VALUES (:company_id, :name)');
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':name', $data['name']);
        return $this->db->execute();
    }

    /**
     * Update an existing product category.
     * @param array $data
     * @return bool
     */
    public function update($data) {
        $this->db->query('UPDATE product_categories SET name = :name WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':name', $data['name']);
        return $this->db->execute();
    }

    /**
     * Delete a product category.
     * @param int $id
     * @param int $company_id
     * @return bool
     */
    public function delete($id, $company_id) {
        $this->db->query('DELETE FROM product_categories WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        return $this->db->execute();
    }
}
