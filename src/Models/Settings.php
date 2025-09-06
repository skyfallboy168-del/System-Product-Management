<?php
namespace Models;

use Core\Database;

class Settings {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all settings for a company as an associative array.
     * @param int $company_id
     * @return array
     */
    public function getSettings($company_id) {
        $this->db->query('SELECT setting_key, setting_value FROM settings WHERE company_id = :company_id');
        $this->db->bind(':company_id', $company_id);

        $results = $this->db->resultSet();

        $settings = [];
        foreach ($results as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }

    /**
     * Update settings for a company.
     * Uses INSERT ... ON DUPLICATE KEY UPDATE for efficiency.
     * @param int $company_id
     * @param array $data Associative array of key => value pairs.
     * @return bool
     */
    public function updateSettings($company_id, $data) {
        try {
            $this->db->query('START TRANSACTION');

            foreach ($data as $key => $value) {
                $this->db->query('
                    INSERT INTO settings (company_id, setting_key, setting_value)
                    VALUES (:company_id, :setting_key, :setting_value)
                    ON DUPLICATE KEY UPDATE setting_value = :setting_value
                ');
                $this->db->bind(':company_id', $company_id);
                $this->db->bind(':setting_key', $key);
                $this->db->bind(':setting_value', $value);
                $this->db->execute();
            }

            $this->db->query('COMMIT');
            return true;
        } catch (\Exception $e) {
            $this->db->query('ROLLBACK');
            return false;
        }
    }
}
