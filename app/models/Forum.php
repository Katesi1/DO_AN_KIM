<?php
class Forum {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all forum posts with pagination
    public function getPosts($limit = 10, $offset = 0) {
        $this->db->query('SELECT f.*, u.username, u.full_name, COUNT(c.id) as comment_count
                         FROM Forums f
                         LEFT JOIN Users u ON f.user_id = u.id
                         LEFT JOIN Comments c ON f.id = c.forum_id
                         GROUP BY f.id
                         ORDER BY f.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    // Get total count of forum posts
    public function getTotalCount() {
        $this->db->query('SELECT COUNT(*) as total FROM Forums');
        $result = $this->db->single();
        return $result->total;
    }

    // Get forum post by ID
    public function getPostById($id) {
        $this->db->query('SELECT f.*, u.username, u.full_name, u.trust_points
                         FROM Forums f
                         LEFT JOIN Users u ON f.user_id = u.id
                         WHERE f.id = :id');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add forum post
    public function addPost($data) {
        $this->db->query('INSERT INTO Forums (title, content, user_id) VALUES(:title, :content, :user_id)');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':user_id', $data['user_id']);

        // Execute
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Update forum post
    public function updatePost($data) {
        $this->db->query('UPDATE Forums SET title = :title, content = :content WHERE id = :id AND user_id = :user_id');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);

        // Execute
        return $this->db->execute();
    }

    // Delete forum post
    public function deletePost($id, $user_id) {
        $this->db->query('DELETE FROM Forums WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);

        return $this->db->execute();
    }

    // Increment view count
    public function incrementViews($id) {
        $this->db->query('UPDATE Forums SET views = views + 1 WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // Search forum posts
    public function searchPosts($query, $limit = 10, $offset = 0) {
        $this->db->query('SELECT f.*, u.username, u.full_name, COUNT(c.id) as comment_count
                         FROM Forums f
                         LEFT JOIN Users u ON f.user_id = u.id
                         LEFT JOIN Comments c ON f.id = c.forum_id
                         WHERE f.title LIKE :query OR f.content LIKE :query
                         GROUP BY f.id
                         ORDER BY f.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':query', '%' . $query . '%');
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    // Search count
    public function searchCount($query) {
        $this->db->query('SELECT COUNT(*) as total FROM Forums WHERE title LIKE :query OR content LIKE :query');
        $this->db->bind(':query', '%' . $query . '%');
        
        $result = $this->db->single();
        return $result->total;
    }

    // Get popular forum posts
    public function getPopularPosts($limit = 5) {
        $this->db->query('SELECT f.*, u.username, u.full_name, COUNT(c.id) as comment_count
                         FROM Forums f
                         LEFT JOIN Users u ON f.user_id = u.id
                         LEFT JOIN Comments c ON f.id = c.forum_id
                         GROUP BY f.id
                         ORDER BY f.views DESC
                         LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Get user's forum posts
    public function getUserPosts($userId, $limit = 10, $offset = 0) {
        $this->db->query('SELECT f.*, COUNT(c.id) as comment_count
                         FROM Forums f
                         LEFT JOIN Comments c ON f.id = c.forum_id
                         WHERE f.user_id = :user_id
                         GROUP BY f.id
                         ORDER BY f.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    // Get count of user's forum posts
    public function getUserPostCount($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM Forums WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        return $result->count;
    }
}
?> 