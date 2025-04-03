<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Register user
    public function register($data) {
        $this->db->query('INSERT INTO Users (username, email, password, full_name, phone, faculty, class, student_id, verification_token) VALUES(:username, :email, :password, :full_name, :phone, :faculty, :class, :student_id, :verification_token)');
        
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':faculty', $data['faculty']);
        $this->db->bind(':class', $data['class']);
        $this->db->bind(':student_id', $data['student_id']);
        $this->db->bind(':verification_token', $data['verification_token']);

        // Execute
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password) {
        $this->db->query('SELECT * FROM Users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if(!$row) {
            return false;
        }

        $hashed_password = $row->password;
        if(password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM Users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by username
    public function findUserByUsername($username) {
        $this->db->query('SELECT * FROM Users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get User by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM Users WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    // Verify user email
    public function verifyEmail($token) {
        $this->db->query('SELECT * FROM Users WHERE verification_token = :token AND is_email_verified = 0');
        $this->db->bind(':token', $token);

        $row = $this->db->single();

        // Check if token exists
        if($this->db->rowCount() > 0) {
            $this->db->query('UPDATE Users SET is_email_verified = 1, verification_token = NULL WHERE verification_token = :token');
            $this->db->bind(':token', $token);

            if($this->db->execute()) {
                return $row;
            }
        }

        return false;
    }

    // Reset password request
    public function setResetToken($email, $token, $expiry) {
        $this->db->query('UPDATE Users SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email');
        $this->db->bind(':token', $token);
        $this->db->bind(':expiry', $expiry);
        $this->db->bind(':email', $email);

        return $this->db->execute();
    }

    // Verify reset token
    public function verifyResetToken($token) {
        $this->db->query('SELECT * FROM Users WHERE reset_token = :token AND reset_token_expiry > NOW()');
        $this->db->bind(':token', $token);

        return $this->db->single();
    }

    // Update password
    public function updatePassword($user_id, $password) {
        $this->db->query('UPDATE Users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :user_id');
        $this->db->bind(':password', $password);
        $this->db->bind(':user_id', $user_id);

        return $this->db->execute();
    }

    // Update user profile
    public function updateProfile($data) {
        $this->db->query('UPDATE Users SET full_name = :full_name, phone = :phone, faculty = :faculty, class = :class, student_id = :student_id WHERE id = :id');
        
        // Bind values
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':faculty', $data['faculty']);
        $this->db->bind(':class', $data['class']);
        $this->db->bind(':student_id', $data['student_id']);
        $this->db->bind(':id', $data['id']);

        // Execute
        return $this->db->execute();
    }

    // Get user activities
    public function getUserActivities($user_id) {
        $this->db->query('SELECT i.*, c.name as category_name, 
                         (SELECT file_path FROM Images WHERE item_id = i.id LIMIT 1) as image,
                         (SELECT COUNT(*) FROM Images WHERE item_id = i.id) as image_count
                         FROM Items i
                         LEFT JOIN Categories c ON i.category_id = c.id
                         WHERE i.user_id = :user_id
                         ORDER BY i.created_at DESC');
        
        $this->db->bind(':user_id', $user_id);

        return $this->db->resultSet();
    }

    // Update trust points
    public function updateTrustPoints($user_id, $points) {
        $this->db->query('UPDATE Users SET trust_points = trust_points + :points WHERE id = :user_id');
        $this->db->bind(':points', $points);
        $this->db->bind(':user_id', $user_id);

        return $this->db->execute();
    }

    // Get top users by trust points
    public function getTopUsers($limit = 5) {
        $this->db->query('SELECT u.*, COUNT(i.id) as items_count 
                         FROM Users u
                         LEFT JOIN Items i ON u.id = i.user_id
                         GROUP BY u.id
                         ORDER BY u.trust_points DESC, items_count DESC
                         LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    /**
     * Tăng điểm uy tín của người dùng
     *
     * @param int $userId ID của người dùng
     * @param int $points Số điểm cần tăng
     * @return bool
     */
    public function incrementTrustPoints($userId, $points = 1) {
        $this->db->query("UPDATE Users SET trust_points = trust_points + :points WHERE id = :user_id");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':points', $points);
        
        return $this->db->execute();
    }

    /**
     * Lấy thông tin người dùng theo email
     *
     * @param string $email Email cần tìm
     * @return object|false
     */
    public function getUserByEmail($email) {
        $this->db->query('SELECT * FROM Users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Cập nhật token xác thực email
     *
     * @param string $email Email của người dùng
     * @param string $token Token mới
     * @return bool
     */
    public function updateVerificationToken($email, $token) {
        $this->db->query('UPDATE Users SET verification_token = :token WHERE email = :email AND is_email_verified = 0');
        $this->db->bind(':token', $token);
        $this->db->bind(':email', $email);

        return $this->db->execute();
    }

    // Verify email directly by email address
    public function verifyEmailDirectly($email) {
        $this->db->query('UPDATE Users SET is_email_verified = 1, 
                          email_verified_at = NOW(), 
                          verification_token = NULL 
                          WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    /**
     * Get all users with pagination
     * 
     * @param int $limit Number of users per page
     * @param int $offset Offset for pagination
     * @return array Array of user objects
     */
    public function getAllUsers($limit = 10, $offset = 0) {
        $this->db->query('SELECT u.*, r.name as role_name 
                         FROM Users u
                         JOIN Roles r ON u.role_id = r.id
                         ORDER BY u.id
                         LIMIT :limit OFFSET :offset');
                         
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }
    
    /**
     * Count total number of users
     * 
     * @return int Total number of users
     */
    public function getUserCount() {
        $this->db->query('SELECT COUNT(*) as count FROM Users');
        $result = $this->db->single();
        return $result->count;
    }

    // Auto verify email for new users (skip email verification)
    public function autoVerifyEmail($userId) {
        $this->db->query('UPDATE Users SET is_email_verified = 1, verification_token = NULL WHERE id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
}
?> 