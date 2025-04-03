<?php
class Item {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all items with pagination
    public function getItems($type = null, $limit = 10, $offset = 0) {
        $sql = 'SELECT i.*, COUNT(im.id) as image_count, c.name as category_name, u.username as username
                FROM Items i
                LEFT JOIN Images im ON i.id = im.item_id
                LEFT JOIN Categories c ON i.category_id = c.id
                LEFT JOIN Users u ON i.user_id = u.id';

        if ($type) {
            $sql .= ' WHERE i.type = :type AND i.status = "active"';
        } else {
            $sql .= ' WHERE i.status = "active"';
        }

        $sql .= ' GROUP BY i.id
                  ORDER BY i.created_at DESC
                  LIMIT :limit OFFSET :offset';

        $this->db->query($sql);
        
        if ($type) {
            $this->db->bind(':type', $type);
        }
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultSet();
    }

    // Get total count of items
    public function getTotalCount($type = null, $categoryId = null) {
        $sql = 'SELECT COUNT(*) as total FROM Items WHERE status = "active"';
        
        if ($type) {
            $sql .= ' AND type = :type';
        }
        
        if ($categoryId) {
            $sql .= ' AND category_id = :category_id';
        }
        
        $this->db->query($sql);
        
        if ($type) {
            $this->db->bind(':type', $type);
        }
        
        if ($categoryId) {
            $this->db->bind(':category_id', $categoryId);
        }
        
        $result = $this->db->single();
        return $result->total;
    }

    // Get item by ID
    public function getItemById($id) {
        $this->db->query('SELECT i.*, c.name as category_name, u.username as username, u.full_name as full_name, u.trust_points as trust_points
                          FROM Items i
                          LEFT JOIN Categories c ON i.category_id = c.id
                          LEFT JOIN Users u ON i.user_id = u.id
                          WHERE i.id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    // Add item
    public function addItem($data) {
        $this->db->query('INSERT INTO Items (title, description, type, category_id, location, lost_found_date, user_id, private_info, expiry_date, status) 
                         VALUES(:title, :description, :type, :category_id, :location, :lost_found_date, :user_id, :private_info, :expiry_date, :status)');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':lost_found_date', $data['lost_found_date']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':private_info', $data['private_info']);
        $this->db->bind(':expiry_date', $data['expiry_date']);
        $this->db->bind(':status', 'pending'); // Set default status to pending

        // Execute
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Update item
    public function updateItem($data) {
        $this->db->query('UPDATE Items SET title = :title, description = :description, category_id = :category_id, 
                         location = :location, lost_found_date = :lost_found_date, private_info = :private_info
                         WHERE id = :id AND user_id = :user_id');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':lost_found_date', $data['lost_found_date']);
        $this->db->bind(':private_info', $data['private_info']);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);

        // Execute
        return $this->db->execute();
    }

    // Delete item
    public function deleteItem($id, $user_id) {
        $this->db->query('DELETE FROM Items WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);

        return $this->db->execute();
    }

    // Mark item as resolved
    public function markAsResolved($id) {
        $this->db->query('UPDATE Items SET status = "resolved" WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // Increment view count
    public function incrementViews($id) {
        $this->db->query('UPDATE Items SET views = views + 1 WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // Search items
    public function searchItems($query, $type = null) {
        $sql = 'SELECT i.*, COUNT(im.id) as image_count, c.name as category_name, u.username as username
                FROM Items i
                LEFT JOIN Images im ON i.id = im.item_id
                LEFT JOIN Categories c ON i.category_id = c.id
                LEFT JOIN Users u ON i.user_id = u.id
                WHERE i.status = "active" AND (i.title LIKE :query OR i.description LIKE :query)';
        
        // Apply type filter if provided
        if ($type && in_array($type, ['lost', 'found'])) {
            $sql .= ' AND i.type = :type';
        }
        
        $sql .= ' GROUP BY i.id
                  ORDER BY i.created_at DESC';
        
        $this->db->query($sql);
        
        // Bind search query
        $this->db->bind(':query', '%' . $query . '%');
        
        // Bind type if provided
        if ($type && in_array($type, ['lost', 'found'])) {
            $this->db->bind(':type', $type);
        }
        
        return $this->db->resultSet();
    }

    // Search items by query and count
    public function searchCount($query, $filters = []) {
        // Search count logic - revert to original
        $sql = 'SELECT COUNT(*) as total FROM Items
                WHERE status = "active" AND (title LIKE :query OR description LIKE :query)';
        
        // Apply filters
        if (!empty($filters['type'])) {
            $sql .= ' AND type = :type';
        }
        
        if (!empty($filters['category_id'])) {
            $sql .= ' AND category_id = :category_id';
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= ' AND lost_found_date >= :date_from';
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= ' AND lost_found_date <= :date_to';
        }
        
        if (!empty($filters['location'])) {
            $sql .= ' AND location LIKE :location';
        }
        
        $this->db->query($sql);
        
        // Bind search query
        $this->db->bind(':query', '%' . $query . '%');
        
        // Bind filter values
        if (!empty($filters['type'])) {
            $this->db->bind(':type', $filters['type']);
        }
        
        if (!empty($filters['category_id'])) {
            $this->db->bind(':category_id', $filters['category_id']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->bind(':date_from', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->bind(':date_to', $filters['date_to']);
        }
        
        if (!empty($filters['location'])) {
            $this->db->bind(':location', '%' . $filters['location'] . '%');
        }
        
        $result = $this->db->single();
        return $result->total;
    }

    // Get similar items
    public function getSimilarItems($itemId, $categoryId, $type, $limit = 4) {
        $this->db->query('SELECT i.*, COUNT(im.id) as image_count, c.name as category_name
                         FROM Items i
                         LEFT JOIN Images im ON i.id = im.item_id
                         LEFT JOIN Categories c ON i.category_id = c.id
                         WHERE i.id != :item_id AND i.category_id = :category_id AND i.type = :type AND i.status = "active"
                         GROUP BY i.id
                         ORDER BY i.created_at DESC
                         LIMIT :limit');
        
        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':type', $type);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Get popular items
    public function getPopularItems($limit = 5) {
        $this->db->query('SELECT i.*, COUNT(im.id) as image_count, c.name as category_name
                         FROM Items i
                         LEFT JOIN Images im ON i.id = im.item_id
                         LEFT JOIN Categories c ON i.category_id = c.id
                         WHERE i.status = "active"
                         GROUP BY i.id
                         ORDER BY i.views DESC
                         LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Get recently resolved items
    public function getRecentlyResolvedItems($limit = 5) {
        $this->db->query('SELECT i.*, COUNT(im.id) as image_count, c.name as category_name
                         FROM Items i
                         LEFT JOIN Images im ON i.id = im.item_id
                         LEFT JOIN Categories c ON i.category_id = c.id
                         WHERE i.status = "resolved"
                         GROUP BY i.id
                         ORDER BY i.updated_at DESC
                         LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
    
    // Get total count of resolved items
    public function getTotalResolvedCount() {
        $this->db->query('SELECT COUNT(*) as total FROM Items WHERE status = "resolved"');
        $result = $this->db->single();
        return $result->total;
    }
    
    // Get monthly statistics
    public function getMonthlyStatistics($months = 6) {
        $this->db->query('SELECT 
                            DATE_FORMAT(created_at, "%Y-%m") as month,
                            SUM(CASE WHEN type = "lost" THEN 1 ELSE 0 END) as lost_count,
                            SUM(CASE WHEN type = "found" THEN 1 ELSE 0 END) as found_count,
                            SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END) as resolved_count
                         FROM Items
                         WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL :months MONTH)
                         GROUP BY DATE_FORMAT(created_at, "%Y-%m")
                         ORDER BY month ASC');
        
        $this->db->bind(':months', $months);
        
        return $this->db->resultSet();
    }

    // Get items with pagination and filters
    public function getItemsPaginated($type = null, $limit = 10, $offset = 0, $categoryId = null) {
        $sql = 'SELECT i.*, COUNT(im.id) as image_count, c.name as category_name, u.username as username
                FROM Items i
                LEFT JOIN Images im ON i.id = im.item_id
                LEFT JOIN Categories c ON i.category_id = c.id
                LEFT JOIN Users u ON i.user_id = u.id
                WHERE i.status = "active"';
        
        if ($type) {
            $sql .= ' AND i.type = :type';
        }
        
        if ($categoryId) {
            $sql .= ' AND i.category_id = :category_id';
        }
        
        $sql .= ' GROUP BY i.id
                  ORDER BY i.created_at DESC
                  LIMIT :limit OFFSET :offset';
        
        $this->db->query($sql);
        
        if ($type) {
            $this->db->bind(':type', $type);
        }
        
        if ($categoryId) {
            $this->db->bind(':category_id', $categoryId);
        }
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    /**
     * Lấy danh sách đồ vật liên quan (cùng danh mục, cùng loại)
     *
     * @param int $itemId ID của đồ vật hiện tại (để loại trừ)
     * @param int $categoryId ID của danh mục
     * @param string $type Loại đồ vật (lost/found)
     * @param int $limit Số lượng đồ vật cần lấy
     * @return array
     */
    public function getRelatedItems($itemId, $categoryId, $type, $limit = 4) {
        $this->db->query("SELECT i.*, 
                          (SELECT file_path FROM Images WHERE item_id = i.id LIMIT 1) as image
                          FROM Items i 
                          WHERE i.id != :item_id 
                          AND i.category_id = :category_id 
                          AND i.type = :type 
                          AND i.status = 'active'
                          ORDER BY i.created_at DESC 
                          LIMIT :limit");
        
        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':type', $type);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    /**
     * Cập nhật trạng thái đồ vật
     *
     * @param int $id ID của đồ vật
     * @param string $status Trạng thái mới (active/resolved/deleted/rejected)
     * @return bool
     */
    public function updateItemStatus($id, $status) {
        $this->db->query('UPDATE Items SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
    }

    /**
     * Lấy danh sách tin đăng của người dùng với thông tin chi tiết
     * 
     * @param int $userId ID của người dùng
     * @param string $status Lọc theo trạng thái (nếu có)
     * @param int $limit Giới hạn số lượng kết quả
     * @param int $offset Vị trí bắt đầu
     * @return array
     */
    public function getUserItemsWithDetails($userId, $status = '', $limit = 10, $offset = 0) {
        $sql = 'SELECT i.*, c.name as category_name, 
                COUNT(im.id) as image_count, 
                (SELECT file_path FROM Images WHERE item_id = i.id LIMIT 1) as image,
                (SELECT COUNT(*) FROM Claims WHERE item_id = i.id) as claims_count
                FROM Items i
                LEFT JOIN Categories c ON i.category_id = c.id
                LEFT JOIN Images im ON i.id = im.item_id';
                
        $sql .= ' WHERE i.user_id = :user_id';
        
        if (!empty($status)) {
            $sql .= ' AND i.status = :status';
        }
        
        $sql .= ' GROUP BY i.id
                 ORDER BY i.created_at DESC
                 LIMIT :limit OFFSET :offset';
        
        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        
        if (!empty($status)) {
            $this->db->bind(':status', $status);
        }
        
        $this->db->bind(':limit', $limit, \PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, \PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    /**
     * Đếm tổng số tin đăng của người dùng
     * 
     * @param int $userId ID của người dùng
     * @param string $status Lọc theo trạng thái (nếu có)
     * @return int
     */
    public function countUserItems($userId, $status = '') {
        $sql = 'SELECT COUNT(*) as count FROM Items WHERE user_id = :user_id';
        
        if (!empty($status)) {
            $sql .= ' AND status = :status';
        }
        
        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        
        if (!empty($status)) {
            $this->db->bind(':status', $status);
        }
        
        $row = $this->db->single();
        return $row->count;
    }
    
    /**
     * Đếm số tin đăng của người dùng theo loại
     * 
     * @param int $userId ID của người dùng
     * @param string $type Loại tin (lost/found)
     * @return int
     */
    public function countUserItemsByType($userId, $type) {
        $this->db->query('SELECT COUNT(*) as count FROM Items WHERE user_id = :user_id AND type = :type');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':type', $type);
        
        $row = $this->db->single();
        return $row->count;
    }
    
    /**
     * Đếm số tin đăng của người dùng theo trạng thái
     * 
     * @param int $userId ID của người dùng
     * @param string $status Trạng thái tin (active/pending/resolved)
     * @return int
     */
    public function countUserItemsByStatus($userId, $status) {
        $this->db->query('SELECT COUNT(*) as count FROM Items WHERE user_id = :user_id AND status = :status');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':status', $status);
        
        $row = $this->db->single();
        return $row->count;
    }

    /**
     * Get items by status
     * 
     * @param string $status Status to filter by (pending, active, resolved, rejected)
     * @param int $limit Pagination limit
     * @param int $offset Pagination offset
     * @return array List of items with the specified status
     */
    public function getItemsByStatus($status, $limit = 10, $offset = 0) {
        $sql = 'SELECT i.*, c.name as category_name, u.username as user_name
                FROM Items i
                LEFT JOIN Categories c ON i.category_id = c.id
                LEFT JOIN Users u ON i.user_id = u.id
                WHERE i.status = :status
                ORDER BY i.created_at DESC
                LIMIT :limit OFFSET :offset';
                
        $this->db->query($sql);
        $this->db->bind(':status', $status);
        $this->db->bind(':limit', $limit, \PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, \PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    /**
     * Count items by status
     * 
     * @param string $status Status to count (pending, active, rejected)
     * @return int Number of items with the specified status
     */
    public function countItemsByStatus($status) {
        $this->db->query('SELECT COUNT(*) as count FROM Items WHERE status = :status');
        $this->db->bind(':status', $status);
        $row = $this->db->single();
        return $row->count;
    }

    /**
     * Get all items for admin management with details
     * 
     * @param int $limit Pagination limit
     * @param int $offset Pagination offset
     * @return array List of all items with details
     */
    public function getAllItemsForAdmin($limit = 10, $offset = 0) {
        $this->db->query('SELECT i.*, c.name as category_name, u.username as user_name, 
                         (SELECT COUNT(*) FROM Claims WHERE item_id = i.id) as claims_count
                         FROM Items i
                         LEFT JOIN Categories c ON i.category_id = c.id
                         LEFT JOIN Users u ON i.user_id = u.id
                         ORDER BY i.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get total count of all items for admin pagination
     * 
     * @return int Total number of items
     */
    public function getTotalItemCount() {
        $this->db->query('SELECT COUNT(*) as count FROM Items');
        $row = $this->db->single();
        return $row->count;
    }
    
    /**
     * Delete item as admin (no user_id check)
     * 
     * @param int $id The item ID to delete
     * @return bool Whether the deletion was successful
     */
    public function deleteItemAdmin($id) {
        $this->db->query('DELETE FROM Items WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?> 