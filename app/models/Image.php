<?php
class Image {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all images for an item
    public function getImages($itemId) {
        $this->db->query('SELECT * FROM Images WHERE item_id = :item_id');
        $this->db->bind(':item_id', $itemId);
        return $this->db->resultSet();
    }
    
    // Get all images for an item (alias for getImages)
    public function getImagesByItemId($itemId) {
        return $this->getImages($itemId);
    }

    // Get first (main) image for an item
    public function getFirstImage($itemId) {
        $this->db->query('SELECT * FROM Images WHERE item_id = :item_id ORDER BY id ASC LIMIT 1');
        $this->db->bind(':item_id', $itemId);
        return $this->db->single();
    }

    // Get image by ID
    public function getImageById($id) {
        $this->db->query('SELECT * FROM Images WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add image
    public function addImage($itemId, $filePath = null) {
        // If the first parameter is an array, extract values from it
        if (is_array($itemId)) {
            $data = $itemId;
            $itemId = $data['item_id'];
            $filePath = $data['file_path'];
        }

        $this->db->query('INSERT INTO Images (item_id, file_path) VALUES(:item_id, :file_path)');
        
        // Bind values
        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':file_path', $filePath);

        // Execute
        return $this->db->execute();
    }

    // Delete image
    public function deleteImage($id) {
        $this->db->query('DELETE FROM Images WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Delete all images for an item
    public function deleteAllImages($itemId) {
        $this->db->query('DELETE FROM Images WHERE item_id = :item_id');
        $this->db->bind(':item_id', $itemId);
        return $this->db->execute();
    }
}
?> 