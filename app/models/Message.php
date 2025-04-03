<?php
class Message {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Send a message
    public function sendMessage($data) {
        $this->db->query('INSERT INTO Messages (claim_id, sender_id, content) VALUES(:claim_id, :sender_id, :content)');
        
        // Bind values
        $this->db->bind(':claim_id', $data['claim_id']);
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':content', $data['content']);

        // Execute
        return $this->db->execute();
    }

    // Get messages for a claim
    public function getMessagesByClaim($claimId) {
        $this->db->query('SELECT m.*, u.username, u.full_name
                         FROM Messages m
                         JOIN Users u ON m.sender_id = u.id
                         WHERE m.claim_id = :claim_id
                         ORDER BY m.created_at');
        
        $this->db->bind(':claim_id', $claimId);
        return $this->db->resultSet();
    }

    // Mark messages as read
    public function markAsRead($claimId, $userId) {
        $this->db->query('UPDATE Messages 
                          SET read_status = 1 
                          WHERE claim_id = :claim_id AND sender_id != :user_id AND read_status = 0');
        
        $this->db->bind(':claim_id', $claimId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }

    // Count unread messages for a user
    public function countUnreadMessages($userId) {
        $this->db->query('SELECT COUNT(*) as count 
                          FROM Messages m
                          JOIN Claims c ON m.claim_id = c.id
                          WHERE (c.claimer_id = :user_id OR c.owner_id = :user_id) 
                          AND m.sender_id != :user_id
                          AND m.read_status = 0');
        
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        
        return $result->count;
    }

    // Get latest message for a claim
    public function getLatestMessage($claimId) {
        $this->db->query('SELECT m.*, u.username, u.full_name
                         FROM Messages m
                         JOIN Users u ON m.sender_id = u.id
                         WHERE m.claim_id = :claim_id
                         ORDER BY m.created_at DESC
                         LIMIT 1');
        
        $this->db->bind(':claim_id', $claimId);
        return $this->db->single();
    }

    // Delete messages for a claim
    public function deleteMessagesByClaim($claimId) {
        $this->db->query('DELETE FROM Messages WHERE claim_id = :claim_id');
        $this->db->bind(':claim_id', $claimId);
        
        return $this->db->execute();
    }

    // Get message by ID
    public function getMessageById($id) {
        $this->db->query('SELECT m.*, u.username, u.full_name
                         FROM Messages m
                         JOIN Users u ON m.sender_id = u.id
                         WHERE m.id = :id');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
?> 