<?php
/**
 * Admin Controller
 * Handles admin dashboard, user management, and item approval
 */
class Admin extends Controller {
    private $userModel;
    private $itemModel;
    private $roleModel;

    public function __construct() {
        // Check if user is logged in and is admin
        if(!isset($_SESSION['user_id'])) {
            $this->redirect('users/login');
        }

        // Load models
        $this->userModel = $this->model('User');
        $this->itemModel = $this->model('Item');
        $this->roleModel = $this->model('Role');

        // Debug info
        $isAdminCheck = $this->roleModel->isAdmin($_SESSION['user_id']);
        $userId = $_SESSION['user_id'];
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Not set';
        $userRoleName = isset($_SESSION['user_role_name']) ? $_SESSION['user_role_name'] : 'Not set';
        
        // Save debug info to a file
        file_put_contents(
            APPROOT . '/../admin_debug.txt', 
            "User ID: $userId\nUser Role: $userRole\nUser Role Name: $userRoleName\nisAdmin check: " . ($isAdminCheck ? 'true' : 'false') . "\n" . date('Y-m-d H:i:s') . "\n\n",
            FILE_APPEND
        );

        // Check if user is admin
        if(!$isAdminCheck) {
            // Not authorized
            $this->setFlash('admin_error', 'You are not authorized to access admin area', 'alert alert-danger');
            $this->redirect('pages/index');
        }
    }

    /**
     * Admin Dashboard
     */
    public function index() {
        // Get stats for dashboard
        $totalUsers = $this->userModel->getUserCount();
        $pendingItems = $this->itemModel->countItemsByStatus('pending');
        $totalItems = $this->itemModel->getTotalCount();
        
        $data = [
            'title' => 'Admin Dashboard',
            'total_users' => $totalUsers,
            'pending_items' => $pendingItems,
            'total_items' => $totalItems
        ];

        $this->view('admin/index', $data);
    }

    /**
     * User Management
     */
    public function users($page = 1) {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Get users with pagination
        $users = $this->userModel->getAllUsers($limit, $offset);
        $totalUsers = $this->userModel->getUserCount();
        $totalPages = ceil($totalUsers / $limit);
        
        // Get all roles for dropdown
        $roles = $this->roleModel->getRoles();
        
        $data = [
            'title' => 'User Management',
            'users' => $users,
            'roles' => $roles,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];

        $this->view('admin/users', $data);
    }

    /**
     * Update user role
     */
    public function updateUserRole() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Process form
            $data = [
                'user_id' => trim($_POST['user_id']),
                'role_id' => trim($_POST['role_id'])
            ];

            // Update user role
            if($this->roleModel->updateUserRole($data['user_id'], $data['role_id'])) {
                $this->setFlash('admin_message', 'User role updated successfully');
                $this->redirect('admin/users');
            } else {
                $this->setFlash('admin_error', 'Something went wrong', 'alert alert-danger');
                $this->redirect('admin/users');
            }
        } else {
            $this->redirect('admin/users');
        }
    }

    /**
     * Item Approval
     */
    public function items($page = 1) {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Get pending items
        $pendingItems = $this->itemModel->getItemsByStatus('pending', $limit, $offset);
        $totalItems = $this->itemModel->countItemsByStatus('pending');
        $totalPages = ceil($totalItems / $limit);
        
        $data = [
            'title' => 'Item Approval',
            'items' => $pendingItems,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];

        $this->view('admin/items', $data);
    }
    
    /**
     * Manage All Items
     */
    public function allItems($page = 1) {
        // Get page from query string if provided (for compatibility with new pagination links)
        if(isset($_GET['page']) && is_numeric($_GET['page'])) {
            $page = (int)$_GET['page'];
        }
        
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Get status filter if provided
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        
        // Get items based on status filter
        if($status && in_array($status, ['active', 'pending', 'resolved', 'rejected'])) {
            $items = $this->itemModel->getItemsByStatus($status, $limit, $offset);
            $totalItems = $this->itemModel->countItemsByStatus($status);
        } else {
            // Get all items with pagination
            $items = $this->itemModel->getAllItemsForAdmin($limit, $offset);
            $totalItems = $this->itemModel->getTotalItemCount();
        }
        
        $totalPages = ceil($totalItems / $limit);
        
        $data = [
            'title' => 'All Items Management',
            'items' => $items,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];

        $this->view('admin/all_items', $data);
    }

    /**
     * Approve item
     */
    public function approveItem($id) {
        if($this->itemModel->updateItemStatus($id, 'active')) {
            $this->setFlash('admin_message', 'Tin đã được phê duyệt thành công', 'alert alert-success');
        } else {
            $this->setFlash('admin_error', 'Có lỗi xảy ra khi phê duyệt tin', 'alert alert-danger');
        }
        $this->redirect('admin/items');
    }

    /**
     * Reject item
     */
    public function rejectItem($id) {
        if($this->itemModel->updateItemStatus($id, 'rejected')) {
            $this->setFlash('admin_message', 'Tin đã bị từ chối thành công', 'alert alert-success');
        } else {
            $this->setFlash('admin_error', 'Có lỗi xảy ra khi từ chối tin', 'alert alert-danger');
        }
        $this->redirect('admin/items');
    }
    
    /**
     * Change item status
     */
    public function changeItemStatus($id) {
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirect('admin/allItems');
            return;
        }
        
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $status = trim($_POST['status']);
        $validStatuses = ['active', 'resolved', 'rejected', 'pending'];
        
        if(!in_array($status, $validStatuses)) {
            $this->setFlash('admin_error', 'Trạng thái không hợp lệ', 'alert alert-danger');
            $this->redirect('admin/allItems');
            return;
        }
        
        if($this->itemModel->updateItemStatus($id, $status)) {
            $this->setFlash('admin_message', 'Cập nhật trạng thái thành công', 'alert alert-success');
        } else {
            $this->setFlash('admin_error', 'Có lỗi xảy ra khi cập nhật trạng thái', 'alert alert-danger');
        }
        
        // Preserve status filter when redirecting
        $redirectUrl = 'admin/allItems';
        if(isset($_GET['status'])) {
            $redirectUrl .= '?status=' . $_GET['status'];
        }
        
        $this->redirect($redirectUrl);
    }
    
    /**
     * Delete item (for admin)
     */
    public function deleteItem($id) {
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirect('admin/allItems');
            return;
        }
        
        // Get item to check if it exists
        $item = $this->itemModel->getItemById($id);
        
        if(!$item) {
            $this->setFlash('admin_error', 'Không tìm thấy tin đăng', 'alert alert-danger');
            $this->redirect('admin/allItems');
            return;
        }
        
        // Load the image model
        $imageModel = $this->model('Image');
        
        // Get images
        $images = $imageModel->getImagesByItemId($id);
        
        // Delete all images from filesystem
        foreach($images as $image) {
            $filePath = 'uploads/items/' . $image->file_path;
            if(file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete images from database
        $imageModel->deleteAllImages($id);
        
        // Delete item
        if($this->itemModel->deleteItemAdmin($id)) {
            $this->setFlash('admin_message', 'Xóa tin đăng thành công', 'alert alert-success');
        } else {
            $this->setFlash('admin_error', 'Có lỗi xảy ra khi xóa tin đăng', 'alert alert-danger');
        }
        
        // Preserve status filter when redirecting
        $redirectUrl = 'admin/allItems';
        if(isset($_GET['status'])) {
            $redirectUrl .= '?status=' . $_GET['status'];
        }
        
        $this->redirect($redirectUrl);
    }
}
?> 