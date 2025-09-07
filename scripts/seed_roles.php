<?php
// This is a simple, standalone script to seed the database roles.
// To run it, you would typically execute `php scripts/seed_roles.php` from your command line.
// This requires including a minimal bootstrap to get the DB connection.

require_once dirname(__DIR__) . '/src/bootstrap.php';

class RoleSeeder {
    private $db;

    public function __construct() {
        // We are directly using the Database class here for this script
        $this->db = \Core\Database::getInstance();
    }

    public function run() {
        $roles = [
            'Admin',
            'Manager',
            'Sales',
            'Accountant'
        ];

        echo "Seeding roles...\n";

        foreach ($roles as $role) {
            $this->db->query("SELECT id FROM roles WHERE name = :name");
            $this->db->bind(':name', $role);
            $existing = $this->db->single();

            if ($existing) {
                echo "Role '{$role}' already exists. Skipping.\n";
                continue;
            }

            $this->db->query("INSERT INTO roles (name) VALUES (:name)");
            $this->db->bind(':name', $role);

            if ($this->db->execute()) {
                echo "Created role: {$role}\n";
            } else {
                echo "Failed to create role: {$role}\n";
            }
        }

        echo "Role seeding complete.\n";
    }
}

// Execute the seeder
$seeder = new RoleSeeder();
$seeder->run();
