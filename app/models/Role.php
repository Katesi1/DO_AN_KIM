<?php
class Role {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get all roles from the database
     * 
     * @return array Array of role objects
     */
    public function getRoles() {
        $this->db->query('SELECT * FROM roles ORDER BY id');
        return $this->db->resultSet();
    }

    /**
     * Get a role by ID
     * 
     * @param int $id Role ID
     * @return object|bool Role object or false if not found
     */
    public function getRoleById($id) {
        $this->db->query('SELECT * FROM roles WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    /**
     * Check if user has a specific permission
     * 
     * @param int $userId User ID
     * @param string $permission Permission name
     * @return bool True if user has permission, false otherwise
     */
    public function hasPermission($userId, $permission) {
        $this->db->query('SELECT r.permissions 
                          FROM roles r 
                          JOIN users u ON r.id = u.role_id 
                          WHERE u.id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        
        if (!$result) {
            return false;
        }
        
        // Check if user has full_access
        if (strpos($result->permissions, 'full_access') !== false) {
            return true;
        }
        
        // Check if user has the specific permission
        return strpos($result->permissions, $permission) !== false;
    }

    /**
     * Check if user is admin
     * 
     * @param int $userId User ID
     * @return bool True if user is admin, false otherwise
     */
    public function isAdmin($userId) {
        $this->db->query('SELECT r.name 
                          FROM roles r 
                          JOIN users u ON r.id = u.role_id 
                          WHERE u.id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        
        if (!$result) {
            return false;
        }
        
        return $result->name === 'Admin';
    }

    /**
     * Update a user's role
     * 
     * @param int $userId User ID
     * @param int $roleId New role ID
     * @return bool True if update successful, false otherwise
     */
    public function updateUserRole($userId, $roleId) {
        $this->db->query('UPDATE users SET role_id = :role_id WHERE id = :user_id');
        $this->db->bind(':role_id', $roleId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
}
?> 