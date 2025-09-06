<?php
namespace Models;

use Core\Database;

class Customer {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all customers for a specific company.
     * @param int $company_id
     * @return array
     */
    public function findAllByCompany($company_id) {
        $this->db->query('SELECT * FROM customers WHERE company_id = :company_id ORDER BY name ASC');
        $this->db->bind(':company_id', $company_id);
        return $this->db->resultSet();
    }

    /**
     * Find a customer by its ID, ensuring it belongs to the company.
     * @param int $id
     * @param int $company_id
     * @return object|false
     */
    public function findById($id, $company_id) {
        $this->db->query('SELECT * FROM customers WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Add a new customer.
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $this->db->query('
            INSERT INTO customers
            (company_id, name, email, phone, customer_company_name, tax_id, address_line_1, city, state, postal_code, country)
            VALUES
            (:company_id, :name, :email, :phone, :customer_company_name, :tax_id, :address_line_1, :city, :state, :postal_code, :country)
        ');

        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':customer_company_name', $data['customer_company_name']);
        $this->db->bind(':tax_id', $data['tax_id']);
        $this->db->bind(':address_line_1', $data['address_line_1']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':state', $data['state']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':country', $data['country']);

        return $this->db->execute();
    }

    /**
     * Update an existing customer.
     * @param array $data
     * @return bool
     */
    public function update($data) {
        $this->db->query('
            UPDATE customers SET
                name = :name,
                email = :email,
                phone = :phone,
                customer_company_name = :customer_company_name,
                tax_id = :tax_id,
                address_line_1 = :address_line_1,
                city = :city,
                state = :state,
                postal_code = :postal_code,
                country = :country
            WHERE id = :id AND company_id = :company_id
        ');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':customer_company_name', $data['customer_company_name']);
        $this->db->bind(':tax_id', $data['tax_id']);
        $this->db->bind(':address_line_1', $data['address_line_1']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':state', $data['state']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':country', $data['country']);

        return $this->db->execute();
    }

    /**
     * Delete a customer.
     * @param int $id
     * @param int $company_id
     * @return bool
     */
    public function delete($id, $company_id) {
        $this->db->query('DELETE FROM customers WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        return $this->db->execute();
    }
}
