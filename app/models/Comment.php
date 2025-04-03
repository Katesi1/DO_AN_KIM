<?php
class Comment {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get comments for a forum post
    public function getCommentsByForumId($forumId) {
        $this->db->query('SELECT c.*, u.username, u.full_name, u.trust_points
                         FROM Comments c
                         JOIN Users u ON c.user_id = u.id
                         WHERE c.forum_id = :forum_id
                         ORDER BY c.created_at');
        
        $this->db->bind(':forum_id', $forumId);
        return $this->db->resultSet();
    }

    // Add comment
    public function addComment($data) {
        $this->db->query('INSERT INTO Comments (forum_id, user_id, content) VALUES(:forum_id, :user_id, :content)');
        
        // Bind values
        $this->db->bind(':forum_id', $data['forum_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':content', $data['content']);

        // Execute
        return $this->db->execute();
    }

    // Update comment
    public function updateComment($data) {
        $this->db->query('UPDATE Comments SET content = :content WHERE id = :id AND user_id = :user_id');
        
        // Bind values
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);

        // Execute
        return $this->db->execute();
    }

    // Delete comment
    public function deleteComment($id, $user_id) {
        $this->db->query('DELETE FROM Comments WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);

        return $this->db->execute();
    }

    // Get comment by ID
    public function getCommentById($id) {
        $this->db->query('SELECT c.*, u.username, u.full_name
                         FROM Comments c
                         JOIN Users u ON c.user_id = u.id
                         WHERE c.id = :id');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get comment count for a forum
    public function getCommentCount($forumId) {
        $this->db->query('SELECT COUNT(*) as count FROM Comments WHERE forum_id = :forum_id');
        $this->db->bind(':forum_id', $forumId);
        
        $result = $this->db->single();
        return $result->count;
    }

    // Get user's recent comments
    public function getUserRecentComments($userId, $limit = 5) {
        $this->db->query('SELECT c.*, f.title as forum_title
                         FROM Comments c
                         JOIN Forums f ON c.forum_id = f.id
                         WHERE c.user_id = :user_id
                         ORDER BY c.created_at DESC
                         LIMIT :limit');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Delete all comments for a forum post
    public function deleteForumComments($forumId) {
        $this->db->query('DELETE FROM Comments WHERE forum_id = :forum_id');
        $this->db->bind(':forum_id', $forumId);
        
        return $this->db->execute();
    }
}
?> 