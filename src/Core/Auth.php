<?php
namespace Core;

use Models\Role;

class Auth {

    // This is a simple, hardcoded role-permission map.
    // In a more complex system, this could be stored in the database.
    private static $permissions = [
        'Admin' => [
            'user-manage', 'settings-manage', 'product-manage', 'customer-manage', 'quote-manage', 'invoice-manage'
        ],
        'Manager' => [
            'product-manage', 'customer-manage', 'quote-manage', 'invoice-manage'
        ],
        'Sales' => [
            'product-view', 'customer-manage', 'quote-manage', 'invoice-view'
        ],
        'Accountant' => [
            'invoice-manage', 'payment-manage'
        ]
    ];

    /**
     * Check if the current logged-in user has a specific permission.
     * @param string $permission The permission to check for (e.g., 'product-manage').
     * @return bool
     */
    public static function check($permission) {
        if (!isLoggedIn()) {
            return false;
        }

        // Fetch user roles from the database
        $roleModel = new Role();
        $userRoles = $roleModel->findUserRoles($_SESSION['user_id']);

        if (empty($userRoles)) {
            return false;
        }

        // Check if any of the user's roles have the required permission
        foreach ($userRoles as $role) {
            if (isset(self::$permissions[$role]) && in_array($permission, self::$permissions[$role])) {
                return true;
            }
        }

        return false;
    }

    /**
     * A helper function to be used as a gate in controllers.
     * If the check fails, it redirects or shows an error.
     * @param string $permission
     */
    public static function gate($permission) {
        if (!self::check($permission)) {
            // In a real app, you'd show a proper 403 Forbidden page.
            // For now, we'll just kill the script with a message.
            die('Access Denied. You do not have permission to perform this action.');
        }
    }
}
