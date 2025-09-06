<?php
namespace Models;

use Core\Database;

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all products for a specific company.
     * @param int $company_id
     * @return array
     */
    public function findAllByCompany($company_id) {
        $this->db->query('
            SELECT
                p.*,
                c.name as category_name,
                s.name as supplier_name
            FROM products p
            LEFT JOIN product_categories c ON p.category_id = c.id
            LEFT JOIN suppliers s ON p.supplier_id = s.id
            WHERE p.company_id = :company_id
            ORDER BY p.created_at DESC
        ');
        $this->db->bind(':company_id', $company_id);
        return $this->db->resultSet();
    }

    /**
     * Find a product by its ID, ensuring it belongs to the company.
     * @param int $id
     * @param int $company_id
     * @return object|false
     */
    public function findById($id, $company_id) {
        $this->db->query('SELECT * FROM products WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Add a new product.
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $this->db->query('
            INSERT INTO products
            (company_id, category_id, supplier_id, sku, name, description, unit, purchase_price, selling_price_1)
            VALUES
            (:company_id, :category_id, :supplier_id, :sku, :name, :description, :unit, :purchase_price, :selling_price_1)
        ');

        // Bind values
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':purchase_price', $data['purchase_price']);
        $this->db->bind(':selling_price_1', $data['selling_price_1']);

        return $this->db->execute();
    }

    /**
     * Update an existing product.
     * @param array $data
     * @return bool
     */
    public function update($data) {
        $this->db->query('
            UPDATE products SET
                category_id = :category_id,
                supplier_id = :supplier_id,
                sku = :sku,
                name = :name,
                description = :description,
                unit = :unit,
                purchase_price = :purchase_price,
                selling_price_1 = :selling_price_1
            WHERE id = :id AND company_id = :company_id
        ');

        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':purchase_price', $data['purchase_price']);
        $this->db->bind(':selling_price_1', $data['selling_price_1']);

        return $this->db->execute();
    }

    /**
     * Delete a product.
     * @param int $id
     * @param int $company_id
     * @return bool
     */
    public function delete($id, $company_id) {
        $this->db->query('DELETE FROM products WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        return $this->db->execute();
    }

    /**
     * Adjust the stock quantity for a product.
     * Can be positive (stock-in) or negative (stock-out).
     * @param int $product_id
     * @param float $quantity
     * @return bool
     */
    public function adjustStock($product_id, $quantity) {
        $this->db->query('UPDATE products SET stock_quantity = stock_quantity + :quantity WHERE id = :id');
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $product_id);
        return $this->db->execute();
    }
}
