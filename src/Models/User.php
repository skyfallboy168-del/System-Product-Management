<?php
namespace Models;

use Core\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Find user by email.
     * @param string $email
     * @return object|false The user object or false if not found.
     */
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Register a new user.
     * @param array $data
     * @return bool True on success, false on failure.
     */
    public function register($data) {
        // Note: In a real multi-company app, you'd also create or assign a company_id.
        // For now, we'll assume a default company_id=1 for simplicity.
        $this->db->query('INSERT INTO users (company_id, name, email, password) VALUES (:company_id, :name, :email, :password)');

        // Bind values
        $this->db->bind(':company_id', $data['company_id']); // This needs to be handled properly later
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Login user.
     * @param string $email
     * @param string $password
     * @return object|false The logged-in user object or false on failure.
     */
    public function login($email, $password) {
        $row = $this->findUserByEmail($email);

        if ($row === false) {
            return false;
        }

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }
}
