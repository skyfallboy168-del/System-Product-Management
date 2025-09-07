<?php
namespace Models;

use Core\Database;

class Role {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all roles assigned to a specific user.
     * @param int $user_id
     * @return array An array of role names.
     */
    public function findUserRoles($user_id) {
        $this->db->query('
            SELECT r.name
            FROM roles r
            JOIN role_user ru ON r.id = ru.role_id
            WHERE ru.user_id = :user_id
        ');
        $this->db->bind(':user_id', $user_id);

        $results = $this->db->resultSet();

        // Return a simple array of role names
        return array_map(function($row) {
            return $row->name;
        }, $results);
    }

    /**
     * Get all available roles.
     * @return array
     */
    public function findAll() {
        $this->db->query('SELECT * FROM roles ORDER BY name ASC');
        return $this->db->resultSet();
    }

    /**
     * Assign a role to a user.
     * @param int $user_id
     * @param int $role_id
     * @return bool
     */
    public function assignRoleToUser($user_id, $role_id) {
        $this->db->query('INSERT INTO role_user (user_id, role_id) VALUES (:user_id, :role_id) ON DUPLICATE KEY UPDATE user_id=user_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':role_id', $role_id);
        return $this->db->execute();
    }
}
