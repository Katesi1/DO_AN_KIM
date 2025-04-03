<?php
class Claim {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Create a new claim
    public function createClaim($data) {
        $this->db->query('INSERT INTO Claims (item_id, claimer_id, owner_id) VALUES(:item_id, :claimer_id, :owner_id)');
        
        // Bind values
        $this->db->bind(':item_id', $data['item_id']);
        $this->db->bind(':claimer_id', $data['claimer_id']);
        $this->db->bind(':owner_id', $data['owner_id']);

        // Execute
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Add a new claim - alias for createClaim
     * 
     * @param array $data Claim data
     * @return int|bool
     */
    public function addClaim($data) {
        return $this->createClaim($data);
    }

    /**
     * Lấy thông tin yêu cầu theo ID
     *
     * @param int $id ID của yêu cầu
     * @return object|bool
     */
    public function getClaimById($id) {
        $this->db->query("SELECT * FROM Claims WHERE id = :id");
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Get claims by claimer ID
    public function getClaimsByClaimer($claimerId) {
        $this->db->query('SELECT c.*, i.title as item_title, i.type as item_type, 
                          u.username as owner_username, u.full_name as owner_name,
                          (SELECT COUNT(*) FROM Messages WHERE claim_id = c.id AND read_status = 0 AND sender_id != :claimer_id) as unread_messages
                          FROM Claims c
                          JOIN Items i ON c.item_id = i.id
                          JOIN Users u ON c.owner_id = u.id
                          WHERE c.claimer_id = :claimer_id
                          ORDER BY c.created_at DESC');
        
        $this->db->bind(':claimer_id', $claimerId);
        return $this->db->resultSet();
    }

    // Get claims by owner ID
    public function getClaimsByOwner($ownerId) {
        $this->db->query('SELECT c.*, i.title as item_title, i.type as item_type, 
                          u.username as claimer_username, u.full_name as claimer_name,
                          (SELECT COUNT(*) FROM Messages WHERE claim_id = c.id AND read_status = 0 AND sender_id != :owner_id) as unread_messages
                          FROM Claims c
                          JOIN Items i ON c.item_id = i.id
                          JOIN Users u ON c.claimer_id = u.id
                          WHERE c.owner_id = :owner_id
                          ORDER BY c.created_at DESC');
        
        $this->db->bind(':owner_id', $ownerId);
        return $this->db->resultSet();
    }

    // Get claims by item ID
    public function getClaimsByItem($itemId) {
        $this->db->query('SELECT c.*, u.username as claimer_username, u.full_name as claimer_name
                          FROM Claims c
                          JOIN Users u ON c.claimer_id = u.id
                          WHERE c.item_id = :item_id
                          ORDER BY c.created_at DESC');
        
        $this->db->bind(':item_id', $itemId);
        return $this->db->resultSet();
    }

    // Check if user has already claimed an item
    public function hasUserClaimedItem($userId, $itemId) {
        $this->db->query('SELECT COUNT(*) as count FROM Claims WHERE claimer_id = :user_id AND item_id = :item_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':item_id', $itemId);
        
        $result = $this->db->single();
        return $result->count > 0;
    }

    /**
     * Check if user has a claim for an item
     * This is an alias for hasUserClaimedItem to fix the error
     *
     * @param int $userId
     * @param int $itemId
     * @return bool
     */
    public function userHasClaim($userId, $itemId) {
        return $this->hasUserClaimedItem($userId, $itemId);
    }

    /**
     * Cập nhật trạng thái yêu cầu
     *
     * @param int $id ID của yêu cầu
     * @param string $status Trạng thái mới (pending/approved/rejected)
     * @return bool
     */
    public function updateClaimStatus($id, $status) {
        $this->db->query("UPDATE Claims SET status = :status, updated_at = NOW() WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
    }

    // Set verification score
    public function setVerificationScore($id, $score) {
        $this->db->query('UPDATE Claims SET verification_score = :score, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':score', $score);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    // Set meeting details
    public function setMeetingDetails($id, $location, $time) {
        $this->db->query('UPDATE Claims SET meeting_location = :location, meeting_time = :time, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':location', $location);
        $this->db->bind(':time', $time);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    // Mark claim as completed
    public function markAsCompleted($id) {
        $this->db->query('UPDATE Claims SET status = "verified", completed_at = NOW(), updated_at = NOW() WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if($this->db->execute()) {
            // Get the item ID associated with this claim
            $this->db->query('SELECT item_id FROM Claims WHERE id = :id');
            $this->db->bind(':id', $id);
            $result = $this->db->single();
            
            if($result) {
                // Mark the item as resolved
                $this->db->query('UPDATE Items SET status = "resolved", updated_at = NOW() WHERE id = :item_id');
                $this->db->bind(':item_id', $result->item_id);
                return $this->db->execute();
            }
        }
        
        return false;
    }

    // Get recently completed claims
    public function getRecentlyCompletedClaims($limit = 5) {
        $this->db->query('SELECT c.*, i.title as item_title, i.type as item_type, 
                         u_claimer.username as claimer_username, u_owner.username as owner_username
                         FROM Claims c
                         JOIN Items i ON c.item_id = i.id
                         JOIN Users u_claimer ON c.claimer_id = u_claimer.id
                         JOIN Users u_owner ON c.owner_id = u_owner.id
                         WHERE c.status = "verified" AND c.completed_at IS NOT NULL
                         ORDER BY c.completed_at DESC
                         LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Count of pending claims for a user (for notifications)
    public function getPendingClaimsCount($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM Claims WHERE owner_id = :user_id AND status = "pending"');
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        return $result->count;
    }

    /**
     * Lấy danh sách yêu cầu theo item_id
     *
     * @param int $itemId
     * @return array
     */
    public function getClaimsByItemId($itemId) {
        $this->db->query("SELECT c.*, u.username, u.email, u.phone, u.trust_points 
                          FROM Claims c 
                          JOIN Users u ON c.claimer_id = u.id 
                          WHERE c.item_id = :item_id 
                          ORDER BY c.created_at DESC");
        
        $this->db->bind(':item_id', $itemId);
        
        return $this->db->resultSet();
    }

    /**
     * Từ chối các yêu cầu khác khi một yêu cầu được chấp nhận
     *
     * @param int $itemId ID của đồ vật
     * @param int $approvedClaimId ID của yêu cầu đã được chấp nhận
     * @return bool
     */
    public function rejectOtherClaims($itemId, $approvedClaimId) {
        $this->db->query("UPDATE Claims SET status = 'rejected', updated_at = NOW() 
                          WHERE item_id = :item_id AND id != :approved_claim_id");
        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':approved_claim_id', $approvedClaimId);
        
        return $this->db->execute();
    }
}
?> 