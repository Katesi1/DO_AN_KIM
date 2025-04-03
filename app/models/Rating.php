<?php
class Rating {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Add a rating
    public function addRating($data) {
        $this->db->query('INSERT INTO Ratings (claim_id, rater_id, rated_id, rating, comment) 
                         VALUES(:claim_id, :rater_id, :rated_id, :rating, :comment)');
        
        // Bind values
        $this->db->bind(':claim_id', $data['claim_id']);
        $this->db->bind(':rater_id', $data['rater_id']);
        $this->db->bind(':rated_id', $data['rated_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);

        // Execute
        return $this->db->execute();
    }

    // Check if user has already rated a claim
    public function hasUserRatedClaim($raterId, $claimId) {
        $this->db->query('SELECT COUNT(*) as count FROM Ratings WHERE rater_id = :rater_id AND claim_id = :claim_id');
        $this->db->bind(':rater_id', $raterId);
        $this->db->bind(':claim_id', $claimId);
        
        $result = $this->db->single();
        return $result->count > 0;
    }

    // Get ratings by rated user
    public function getRatingsByUser($userId, $limit = 10, $offset = 0) {
        $this->db->query('SELECT r.*, u.username as rater_username, u.full_name as rater_name, c.item_id
                         FROM Ratings r
                         JOIN Users u ON r.rater_id = u.id
                         JOIN Claims c ON r.claim_id = c.id
                         WHERE r.rated_id = :user_id
                         ORDER BY r.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    // Get average rating for a user
    public function getAverageRating($userId) {
        $this->db->query('SELECT AVG(rating) as avg_rating FROM Ratings WHERE rated_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        return $result->avg_rating ? round($result->avg_rating, 1) : 0;
    }

    // Get rating count for a user
    public function getRatingCount($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM Ratings WHERE rated_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        return $result->count;
    }

    // Get rating distribution for a user (1-5 stars)
    public function getRatingDistribution($userId) {
        $this->db->query('SELECT rating, COUNT(*) as count 
                         FROM Ratings 
                         WHERE rated_id = :user_id 
                         GROUP BY rating 
                         ORDER BY rating');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Get rating by ID
    public function getRatingById($id) {
        $this->db->query('SELECT r.*, u_rater.username as rater_username, u_rater.full_name as rater_name,
                          u_rated.username as rated_username, u_rated.full_name as rated_name
                          FROM Ratings r
                          JOIN Users u_rater ON r.rater_id = u_rater.id
                          JOIN Users u_rated ON r.rated_id = u_rated.id
                          WHERE r.id = :id');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update trust points based on ratings
    public function updateTrustPointsFromRating($userId, $rating) {
        // Calculate points: 5-star = +3, 4-star = +2, 3-star = +1, 2-star = -1, 1-star = -2
        $points = 0;
        
        switch($rating) {
            case 5:
                $points = 3;
                break;
            case 4:
                $points = 2;
                break;
            case 3:
                $points = 1;
                break;
            case 2:
                $points = -1;
                break;
            case 1:
                $points = -2;
                break;
        }
        
        if($points != 0) {
            $this->db->query('UPDATE Users SET trust_points = trust_points + :points WHERE id = :user_id');
            $this->db->bind(':points', $points);
            $this->db->bind(':user_id', $userId);
            
            return $this->db->execute();
        }
        
        return true;
    }
}
?> 