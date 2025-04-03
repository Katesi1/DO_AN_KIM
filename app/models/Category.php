<?php
class Category {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all categories
    public function getCategories() {
        $this->db->query('SELECT * FROM Categories ORDER BY name');
        return $this->db->resultSet();
    }

    // Get category by ID
    public function getCategoryById($id) {
        $this->db->query('SELECT * FROM Categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add category (admin function)
    public function addCategory($data) {
        $this->db->query('INSERT INTO Categories (name, description, icon) VALUES(:name, :description, :icon)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':icon', $data['icon']);

        // Execute
        return $this->db->execute();
    }

    // Update category (admin function)
    public function updateCategory($data) {
        $this->db->query('UPDATE Categories SET name = :name, description = :description, icon = :icon WHERE id = :id');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':id', $data['id']);

        // Execute
        return $this->db->execute();
    }

    // Delete category (admin function)
    public function deleteCategory($id) {
        $this->db->query('DELETE FROM Categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Get category item count
    public function getCategoryItemCount($categoryId) {
        $this->db->query('SELECT COUNT(*) as count FROM Items WHERE category_id = :category_id AND status = "active"');
        $this->db->bind(':category_id', $categoryId);
        $result = $this->db->single();
        return $result->count;
    }

    // Get popular categories (most items)
    public function getPopularCategories($limit = 5) {
        $this->db->query('SELECT c.id, c.name, c.icon, COUNT(i.id) as item_count
                         FROM Categories c
                         LEFT JOIN Items i ON c.id = i.category_id AND i.status = "active"
                         GROUP BY c.id
                         ORDER BY item_count DESC
                         LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Get category statistics for charts
    public function getCategoryStatistics() {
        $this->db->query('SELECT c.id, c.name, c.icon,
                         COUNT(CASE WHEN i.type = "lost" THEN 1 ELSE NULL END) as lost_count,
                         COUNT(CASE WHEN i.type = "found" THEN 1 ELSE NULL END) as found_count,
                         COUNT(CASE WHEN i.status = "resolved" THEN 1 ELSE NULL END) as resolved_count
                         FROM Categories c
                         LEFT JOIN Items i ON c.id = i.category_id
                         GROUP BY c.id
                         ORDER BY (lost_count + found_count) DESC');
        
        return $this->db->resultSet();
    }
}
?> 