<?php
namespace Models;

use Core\Database;

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

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

    public function findById($id, $company_id) {
        $this->db->query('SELECT * FROM products WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    public function create($data) {
        $this->db->query('
            INSERT INTO products
            (company_id, category_id, supplier_id, sku, barcode, name, description, unit,
            purchase_price, markup_percentage, selling_price_1, selling_price_2,
            discount_price_1, discount_price_2, discount_price_3)
            VALUES
            (:company_id, :category_id, :supplier_id, :sku, :barcode, :name, :description, :unit,
            :purchase_price, :markup_percentage, :selling_price_1, :selling_price_2,
            :discount_price_1, :discount_price_2, :discount_price_3)
        ');

        $this->bindProductData($data);
        return $this->db->execute();
    }

    public function update($data) {
        $this->db->query('
            UPDATE products SET
                category_id = :category_id, supplier_id = :supplier_id, sku = :sku, barcode = :barcode,
                name = :name, description = :description, unit = :unit, purchase_price = :purchase_price,
                markup_percentage = :markup_percentage, selling_price_1 = :selling_price_1,
                selling_price_2 = :selling_price_2, discount_price_1 = :discount_price_1,
                discount_price_2 = :discount_price_2, discount_price_3 = :discount_price_3
            WHERE id = :id AND company_id = :company_id
        ');

        $this->bindProductData($data);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    private function bindProductData($data) {
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':barcode', $data['barcode']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':purchase_price', $data['purchase_price']);
        $this->db->bind(':markup_percentage', $data['markup_percentage']);
        $this->db->bind(':selling_price_1', $data['selling_price_1']);
        $this->db->bind(':selling_price_2', $data['selling_price_2']);
        $this->db->bind(':discount_price_1', $data['discount_price_1']);
        $this->db->bind(':discount_price_2', $data['discount_price_2']);
        $this->db->bind(':discount_price_3', $data['discount_price_3']);
    }

    public function delete($id, $company_id) {
        $this->db->query('DELETE FROM products WHERE id = :id AND company_id = :company_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':company_id', $company_id);
        return $this->db->execute();
    }

    public function adjustStock($product_id, $quantity) {
        $this->db->query('UPDATE products SET stock_quantity = stock_quantity + :quantity WHERE id = :id');
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $product_id);
        return $this->db->execute();
    }
}
