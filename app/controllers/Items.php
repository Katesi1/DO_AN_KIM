<?php
class Items extends Controller {
    private $itemModel;
    private $categoryModel;
    private $imageModel;
    private $userModel;
    private $claimModel;

    public function __construct() {
        // Kiểm tra đăng nhập cho các action cần đăng nhập
        if(!$this->isLoggedIn() && in_array($_GET['url'] ?? '', ['items/add', 'items/edit', 'items/delete'])) {
            $this->setFlash('error', 'Vui lòng đăng nhập để thực hiện chức năng này', 'alert alert-danger');
            $this->redirect('users/login');
        }

        $this->itemModel = $this->model('Item');
        $this->categoryModel = $this->model('Category');
        $this->imageModel = $this->model('Image');
        $this->userModel = $this->model('User');
        $this->claimModel = $this->model('Claim');
    }

    // Trang mặc định cho Items
    public function index() {
        // Mặc định chuyển hướng đến trang đồ vật thất lạc
        $this->redirect('items/lost');
    }

    // Hiển thị danh sách đồ thất lạc
    public function lost() {
        // Phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        // Lọc theo danh mục
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
        // Lấy tổng số lượng items
        $totalItems = $this->itemModel->getTotalCount('lost', $categoryId);
        $totalPages = ceil($totalItems / $perPage);
        
        // Lấy danh sách đồ vật
        $items = $this->itemModel->getItemsPaginated('lost', $perPage, $offset, $categoryId);
        
        // Lấy danh sách danh mục
        $categories = $this->categoryModel->getCategories();
        
        // Thêm hình ảnh cho mỗi item
        foreach ($items as $item) {
            $item->image = $this->imageModel->getFirstImage($item->id);
        }
        
        $data = [
            'title' => 'Đồ vật thất lạc',
            'items' => $items,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'selectedCategory' => $categoryId,
            'type' => 'lost'
        ];

        $this->view('items/index', $data);
    }

    // Hiển thị danh sách đồ tìm thấy
    public function found() {
        // Phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        // Lọc theo danh mục
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
        // Lấy tổng số lượng items
        $totalItems = $this->itemModel->getTotalCount('found', $categoryId);
        $totalPages = ceil($totalItems / $perPage);
        
        // Lấy danh sách đồ vật
        $items = $this->itemModel->getItemsPaginated('found', $perPage, $offset, $categoryId);
        
        // Lấy danh sách danh mục
        $categories = $this->categoryModel->getCategories();
        
        // Thêm hình ảnh cho mỗi item
        foreach ($items as $item) {
            $item->image = $this->imageModel->getFirstImage($item->id);
        }
        
        $data = [
            'title' => 'Đồ vật tìm thấy',
            'items' => $items,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'selectedCategory' => $categoryId,
            'type' => 'found'
        ];

        $this->view('items/index', $data);
    }

    // Hiển thị chi tiết một đồ vật
    public function show($id = 0) {
        // Kiểm tra id hợp lệ
        if (!is_numeric($id) || $id <= 0) {
            $this->setFlash('error', 'ID không hợp lệ', 'alert alert-danger');
            $this->redirect('pages/index');
            return;
        }
        
        // Lấy thông tin đồ vật
        $item = $this->itemModel->getItemById($id);
        
        if (!$item) {
            $this->setFlash('error', 'Không tìm thấy đồ vật', 'alert alert-danger');
            $this->redirect('pages/index');
            return;
        }
        
        // Tăng lượt xem
        $this->itemModel->incrementViews($id);
        
        // Lấy thông tin người đăng
        $user = $this->userModel->getUserById($item->user_id);
        
        // Lấy danh sách hình ảnh
        $images = $this->imageModel->getImagesByItemId($id);
        
        // Lấy danh mục
        $category = $this->categoryModel->getCategoryById($item->category_id);
        
        // Kiểm tra xem người dùng hiện tại đã tạo claim chưa
        $hasClaim = false;
        if ($this->isLoggedIn()) {
            $hasClaim = $this->claimModel->userHasClaim($this->getUser()->id, $id);
        }
        
        // Lấy danh sách claims nếu người dùng hiện tại là chủ sở hữu đồ vật
        $claims = [];
        if ($this->isLoggedIn() && $this->getUser()->id == $item->user_id) {
            $claims = $this->claimModel->getClaimsByItemId($id);
        }
        
        // Lấy items liên quan (cùng danh mục, cùng loại)
        $relatedItems = $this->itemModel->getRelatedItems($id, $item->category_id, $item->type, 4);
        
        $data = [
            'title' => $item->title,
            'item' => $item,
            'user' => $user,
            'images' => $images,
            'category' => $category,
            'hasClaim' => $hasClaim,
            'claims' => $claims,
            'relatedItems' => $relatedItems
        ];

        $this->view('items/show', $data);
    }

    // Thêm đồ vật mới (mất hoặc tìm thấy)
    public function add($type = null) {
        // Kiểm tra type hợp lệ
        if (!in_array($type, ['lost', 'found'])) {
            // Hiển thị form chọn loại đồ vật nếu không có type
            $data = [
                'title' => 'Đăng tin mới'
            ];
            $this->view('items/add_select', $data);
            return;
        }

        // Xử lý form submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý dữ liệu form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // File upload processing
            $uploadedImages = $_FILES['images'] ?? null;
            
            $data = [
                'title' => 'Đăng tin ' . ($type == 'lost' ? 'mất đồ' : 'nhặt được đồ'),
                'type' => $type,
                'title_item' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'category_id' => trim($_POST['category_id']),
                'location' => trim($_POST['location']),
                'lost_found_date' => trim($_POST['lost_found_date']),
                'private_info' => trim($_POST['private_info']),
                'title_err' => '',
                'description_err' => '',
                'category_id_err' => '',
                'location_err' => '',
                'lost_found_date_err' => '',
                'images_err' => '',
                'categories' => $this->categoryModel->getCategories()
            ];
            
            // Validate data
            if (empty($data['title_item'])) {
                $data['title_err'] = 'Vui lòng nhập tiêu đề';
            }
            
            if (empty($data['description'])) {
                $data['description_err'] = 'Vui lòng nhập mô tả';
            }
            
            if (empty($data['category_id'])) {
                $data['category_id_err'] = 'Vui lòng chọn danh mục';
            }
            
            if (empty($data['location'])) {
                $data['location_err'] = 'Vui lòng nhập địa điểm';
            }
            
            if (empty($data['lost_found_date'])) {
                $data['lost_found_date_err'] = 'Vui lòng nhập ngày';
            }
            
            // Nếu không có lỗi
            if (empty($data['title_err']) && empty($data['description_err']) && 
                empty($data['category_id_err']) && empty($data['location_err']) && 
                empty($data['lost_found_date_err']) && empty($data['images_err'])) {
                
                // Chuẩn bị dữ liệu để lưu
                $itemData = [
                    'title' => $data['title_item'],
                    'description' => $data['description'],
                    'type' => $type,
                    'category_id' => $data['category_id'],
                    'location' => $data['location'],
                    'lost_found_date' => $data['lost_found_date'],
                    'private_info' => $data['private_info'],
                    'user_id' => $this->getUser()->id,
                    'expiry_date' => date('Y-m-d H:i:s', strtotime('+30 days'))
                ];
                
                // Thêm item vào database
                $itemId = $this->itemModel->addItem($itemData);
                
                if ($itemId) {
                    // Upload images if they exist
                    if ($uploadedImages && $uploadedImages['name'][0] != '') {
                        $uploadDir = 'uploads/items/';
                        
                        // Create upload directory if it doesn't exist
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $totalFiles = count($uploadedImages['name']);
                        
                        for ($i = 0; $i < $totalFiles; $i++) {
                            if ($uploadedImages['error'][$i] === 0) {
                                // Generate a unique filename
                                $filename = uniqid() . '_' . $uploadedImages['name'][$i];
                                $destination = $uploadDir . $filename;
                                
                                // Move uploaded file
                                if (move_uploaded_file($uploadedImages['tmp_name'][$i], $destination)) {
                                    // Save image info to database - only store the filename, not the full path
                                    $imageData = [
                                        'item_id' => $itemId,
                                        'file_path' => $filename
                                    ];
                                    
                                    $this->imageModel->addImage($imageData);
                                }
                            }
                        }
                    }
                    
                    // Upload successful
                    if($itemId) {
                        // Set success message
                        $this->setFlash('success', 'Đăng tin thành công! Tin của bạn đang chờ được kiểm duyệt và sẽ được hiển thị sau khi được phê duyệt.', 'alert alert-success');
                        $this->redirect('users/manage_items');
                    } else {
                        $this->setFlash('error', 'Có lỗi xảy ra, vui lòng thử lại', 'alert alert-danger');
                        $this->view('items/add', $data);
                    }
                } else {
                    $this->setFlash('error', 'Có lỗi xảy ra, vui lòng thử lại', 'alert alert-danger');
                    $this->view('items/add', $data);
                }
            } else {
                // Load view with errors
                $this->view('items/add', $data);
            }
        } else {
            // Load view form
            $data = [
                'title' => 'Đăng tin ' . ($type == 'lost' ? 'mất đồ' : 'nhặt được đồ'),
                'type' => $type,
                'title_item' => '',
                'description' => '',
                'category_id' => '',
                'location' => '',
                'lost_found_date' => date('Y-m-d'),
                'private_info' => '',
                'title_err' => '',
                'description_err' => '',
                'category_id_err' => '',
                'location_err' => '',
                'lost_found_date_err' => '',
                'images_err' => '',
                'categories' => $this->categoryModel->getCategories()
            ];
            
            $this->view('items/add', $data);
        }
    }

    // Tìm kiếm đồ vật
    public function search() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['q'])) {
            $query = trim($_GET['q']);
            $type = isset($_GET['type']) ? trim($_GET['type']) : null;
            
            if (empty($query)) {
                $this->setFlash('error', 'Vui lòng nhập từ khóa tìm kiếm', 'alert alert-danger');
                $this->redirect('pages/index');
                return;
            }
            
            // Tìm kiếm đồ vật với bộ lọc
            $filters = [];
            if ($type) {
                $filters['type'] = $type;
            }
            
            $items = $this->itemModel->searchItems($query, $filters);
            
            // Thêm hình ảnh cho mỗi item
            foreach ($items as $item) {
                $item->image = $this->imageModel->getFirstImage($item->id);
            }
            
            $data = [
                'title' => 'Kết quả tìm kiếm: ' . $query,
                'items' => $items,
                'query' => $query,
                'type' => $type
            ];
            
            $this->view('items/search', $data);
        } else {
            $this->redirect('pages/index');
        }
    }
    
    // Tạo claim yêu cầu nhận đồ
    public function claim($itemId = 0) {
        if (!$this->isLoggedIn()) {
            $this->setFlash('error', 'Vui lòng đăng nhập để thực hiện chức năng này', 'alert alert-danger');
            $this->redirect('users/login');
            return;
        }
        
        if (!$itemId) {
            $this->setFlash('error', 'ID không hợp lệ', 'alert alert-danger');
            $this->redirect('pages/index');
            return;
        }
        
        // Lấy thông tin đồ vật
        $item = $this->itemModel->getItemById($itemId);
        
        if (!$item) {
            $this->setFlash('error', 'Không tìm thấy đồ vật', 'alert alert-danger');
            $this->redirect('pages/index');
            return;
        }
        
        // Không thể tạo claim cho đồ vật của chính mình
        if ($item->user_id == $this->getUser()->id) {
            $this->setFlash('error', 'Bạn không thể tạo yêu cầu cho đồ vật của chính mình', 'alert alert-danger');
            $this->redirect('items/show/' . $itemId);
            return;
        }

        // Kiểm tra đã tạo claim cho đồ vật này chưa
        if ($this->claimModel->userHasClaim($this->getUser()->id, $itemId)) {
            $this->setFlash('error', 'Bạn đã tạo yêu cầu cho đồ vật này rồi', 'alert alert-danger');
            $this->redirect('items/show/' . $itemId);
            return;
        }
        
        // Xử lý form submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý dữ liệu form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'title' => 'Tạo yêu cầu nhận đồ',
                'item' => $item,
                'verification_info' => trim($_POST['verification_info']),
                'meeting_location' => trim($_POST['meeting_location']),
                'meeting_time' => trim($_POST['meeting_time']),
                'verification_info_err' => '',
                'meeting_location_err' => '',
                'meeting_time_err' => ''
            ];
            
            // Validate data
            if (empty($data['verification_info'])) {
                $data['verification_info_err'] = 'Vui lòng nhập thông tin xác minh';
            }
            
            if (empty($data['meeting_location'])) {
                $data['meeting_location_err'] = 'Vui lòng nhập địa điểm gặp mặt';
            }
            
            if (empty($data['meeting_time'])) {
                $data['meeting_time_err'] = 'Vui lòng chọn thời gian gặp mặt';
            }
            
            // Nếu không có lỗi
            if (empty($data['verification_info_err']) && empty($data['meeting_location_err']) && empty($data['meeting_time_err'])) {
                // Chuẩn bị dữ liệu để lưu
                $claimData = [
                    'item_id' => $itemId,
                    'claimer_id' => $this->getUser()->id,
                    'owner_id' => $item->user_id,
                    'verification_info' => $data['verification_info'],
                    'meeting_location' => $data['meeting_location'],
                    'meeting_time' => $data['meeting_time']
                ];
                
                // Thêm claim vào database
                if ($this->claimModel->addClaim($claimData)) {
                    $this->setFlash('success', 'Tạo yêu cầu thành công. Chủ đồ vật sẽ liên hệ với bạn sớm.', 'alert alert-success');
                    $this->redirect('items/show/' . $itemId);
                } else {
                    $this->setFlash('error', 'Có lỗi xảy ra, vui lòng thử lại', 'alert alert-danger');
                    $this->view('items/claim', $data);
                }
            } else {
                // Load view with errors
                $this->view('items/claim', $data);
            }
        } else {
            // Load view form
            $data = [
                'title' => 'Tạo yêu cầu nhận đồ',
                'item' => $item,
                'verification_info' => '',
                'meeting_location' => '',
                'meeting_time' => date('Y-m-d\TH:i'),
                'verification_info_err' => '',
                'meeting_location_err' => '',
                'meeting_time_err' => ''
            ];
            
            $this->view('items/claim', $data);
        }
    }

    /**
     * Đánh dấu đồ vật đã được trao trả
     * 
     * @param int $id ID của đồ vật
     * @return void
     */
    public function mark_resolved($id = 0) {
        // Kiểm tra đăng nhập
        if (!$this->isLoggedIn()) {
            $this->setFlash('error', 'Vui lòng đăng nhập để thực hiện chức năng này', 'alert alert-danger');
            $this->redirect('users/login');
            return;
        }
        
        // Kiểm tra ID hợp lệ
        if (!is_numeric($id) || $id <= 0) {
            $this->setFlash('error', 'ID không hợp lệ', 'alert alert-danger');
            $this->redirect('pages/index');
            return;
        }
        
        // Lấy thông tin đồ vật
        $item = $this->itemModel->getItemById($id);
        
        // Kiểm tra đồ vật tồn tại
        if (!$item) {
            $this->setFlash('error', 'Không tìm thấy đồ vật', 'alert alert-danger');
            $this->redirect('pages/index');
            return;
        }
        
        // Kiểm tra quyền sở hữu
        if ($item->user_id != $this->getUser()->id) {
            $this->setFlash('error', 'Bạn không có quyền thực hiện hành động này', 'alert alert-danger');
            $this->redirect('items/show/' . $id);
            return;
        }
        
        // Cập nhật trạng thái đồ vật
        if ($this->itemModel->updateItemStatus($id, 'resolved')) {
            // Tăng điểm uy tín cho người đăng
            $this->userModel->incrementTrustPoints($item->user_id, 10);
            
            $this->setFlash('success', 'Đã đánh dấu đồ vật đã được trao trả thành công', 'alert alert-success');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra, vui lòng thử lại', 'alert alert-danger');
        }
        
        $this->redirect('items/show/' . $id);
    }
    
    /**
     * Xác nhận trao trả đồ vật cho một người dùng cụ thể
     * 
     * @param int $itemId ID của đồ vật
     * @param int $claimId ID của yêu cầu
     * @return void
     */
    public function resolve($itemId = 0, $claimId = 0) {
        // Kiểm tra đăng nhập
        if (!$this->isLoggedIn()) {
            $this->setFlash('error', 'Vui lòng đăng nhập để thực hiện chức năng này', 'alert alert-danger');
            $this->redirect('users/login');
            return;
        }
        
        // Kiểm tra ID hợp lệ
        if (!is_numeric($itemId) || $itemId <= 0 || !is_numeric($claimId) || $claimId <= 0) {
            $this->setFlash('error', 'ID không hợp lệ', 'alert alert-danger');
            $this->redirect('pages/index');
            return;
        }
        
        // Lấy thông tin đồ vật
        $item = $this->itemModel->getItemById($itemId);
        
        // Kiểm tra đồ vật tồn tại
        if (!$item) {
            $this->setFlash('error', 'Không tìm thấy đồ vật', 'alert alert-danger');
            $this->redirect('pages/index');
            return;
        }
        
        // Kiểm tra quyền sở hữu
        if ($item->user_id != $this->getUser()->id) {
            $this->setFlash('error', 'Bạn không có quyền thực hiện hành động này', 'alert alert-danger');
            $this->redirect('items/show/' . $itemId);
            return;
        }
        
        // Lấy thông tin yêu cầu
        $claim = $this->claimModel->getClaimById($claimId);
        
        // Kiểm tra yêu cầu tồn tại
        if (!$claim || $claim->item_id != $itemId) {
            $this->setFlash('error', 'Không tìm thấy yêu cầu hợp lệ', 'alert alert-danger');
            $this->redirect('items/show/' . $itemId);
            return;
        }
        
        // Cập nhật trạng thái đồ vật
        if ($this->itemModel->updateItemStatus($itemId, 'resolved')) {
            // Cập nhật yêu cầu thành công
            $this->claimModel->updateClaimStatus($claimId, 'approved');
            
            // Từ chối các yêu cầu khác
            $this->claimModel->rejectOtherClaims($itemId, $claimId);
            
            // Tăng điểm uy tín cho người đăng
            $this->userModel->incrementTrustPoints($item->user_id, 10);
            
            // Tăng điểm uy tín cho người nhận
            $this->userModel->incrementTrustPoints($claim->user_id, 10);
            
            $this->setFlash('success', 'Đã xác nhận trao trả đồ vật thành công', 'alert alert-success');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra, vui lòng thử lại', 'alert alert-danger');
        }
        
        $this->redirect('items/show/' . $itemId);
    }
    
    /**
     * Hiển thị hình ảnh từ thư mục uploads/items
     * 
     * @param string $filename Tên file cần hiển thị
     * @return void
     */
    public function getImage($filename = '') {
        if (empty($filename)) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
        
        // Đường dẫn đến file ảnh
        $imagePath = '../public/uploads/items/' . $filename;
        
        // Kiểm tra file tồn tại
        if (!file_exists($imagePath)) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
        
        // Xác định loại MIME để hiển thị đúng loại ảnh
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $imagePath);
        finfo_close($fileInfo);
        
        // Thiết lập header
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($imagePath));
        
        // Đọc và xuất file
        readfile($imagePath);
        exit;
    }

    // Mark item as resolved
    public function markAsResolved($id) {
        return $this->itemModel->markAsResolved($id);
    }

    // Increment view count
    public function incrementViews($id) {
        return $this->itemModel->incrementViews($id);
    }
    
    // Edit an item
    public function edit($id = 0) {
        // Check if logged in
        if(!$this->isLoggedIn()) {
            $this->setFlash('error', 'Vui lòng đăng nhập để thực hiện chức năng này', 'alert alert-danger');
            $this->redirect('users/login');
            return;
        }
        
        // Check if id is valid
        if(!is_numeric($id) || $id <= 0) {
            $this->setFlash('error', 'ID không hợp lệ', 'alert alert-danger');
            $this->redirect('users/manage_items');
            return;
        }
        
        // Get item
        $item = $this->itemModel->getItemById($id);
        
        // Check if item exists
        if(!$item) {
            $this->setFlash('error', 'Không tìm thấy tin', 'alert alert-danger');
            $this->redirect('users/manage_items');
            return;
        }
        
        // Check if user owns the item
        if($item->user_id != $this->getUser()->id) {
            $this->setFlash('error', 'Bạn không có quyền chỉnh sửa tin này', 'alert alert-danger');
            $this->redirect('users/manage_items');
            return;
        }
        
        // Check if item is editable (only active items can be edited)
        if($item->status != 'active') {
            $this->setFlash('error', 'Chỉ có thể chỉnh sửa những tin đang hoạt động', 'alert alert-danger');
            $this->redirect('users/manage_items');
            return;
        }
        
        // Get images
        $images = $this->imageModel->getImagesByItemId($id);
        
        // Process form submission
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // File upload processing
            $uploadedImages = $_FILES['images'] ?? null;
            
            $data = [
                'id' => $id,
                'user_id' => $this->getUser()->id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'category_id' => trim($_POST['category_id']),
                'location' => trim($_POST['location']),
                'lost_found_date' => trim($_POST['lost_found_date']),
                'private_info' => trim($_POST['private_info']),
                'type' => $item->type, // Cannot change the type
                'title_err' => '',
                'description_err' => '',
                'category_id_err' => '',
                'location_err' => '',
                'lost_found_date_err' => '',
                'categories' => $this->categoryModel->getCategories(),
                'images' => $images
            ];
            
            // Validate title
            if(empty($data['title'])) {
                $data['title_err'] = 'Vui lòng nhập tiêu đề';
            }
            
            // Validate description
            if(empty($data['description'])) {
                $data['description_err'] = 'Vui lòng nhập mô tả';
            }
            
            // Validate category
            if(empty($data['category_id'])) {
                $data['category_id_err'] = 'Vui lòng chọn danh mục';
            }
            
            // Validate location
            if(empty($data['location'])) {
                $data['location_err'] = 'Vui lòng nhập địa điểm';
            }
            
            // Validate date
            if(empty($data['lost_found_date'])) {
                $data['lost_found_date_err'] = 'Vui lòng nhập ngày';
            }
            
            // Check if no errors
            if(empty($data['title_err']) && empty($data['description_err']) && 
               empty($data['category_id_err']) && empty($data['location_err']) && 
               empty($data['lost_found_date_err'])) {
                
                // Update item
                if($this->itemModel->updateItem($data)) {
                    // Process image uploads if any
                    if($uploadedImages && $uploadedImages['name'][0] != '') {
                        $uploadDir = 'uploads/items/';
                        
                        // Create directory if it doesn't exist
                        if(!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        // Process each uploaded file
                        $fileCount = count($uploadedImages['name']);
                        
                        for($i = 0; $i < $fileCount; $i++) {
                            if($uploadedImages['error'][$i] == 0) {
                                $fileName = uniqid() . '_' . basename($uploadedImages['name'][$i]);
                                $targetPath = $uploadDir . $fileName;
                                
                                // Move the uploaded file
                                if(move_uploaded_file($uploadedImages['tmp_name'][$i], $targetPath)) {
                                    // Add image to database - only store the filename, not the full path
                                    $imageData = [
                                        'item_id' => $id,
                                        'file_path' => $fileName
                                    ];
                                    
                                    $this->imageModel->addImage($imageData);
                                }
                            }
                        }
                    }
                    
                    // Check if any images were requested to be deleted
                    if(isset($_POST['delete_image']) && is_array($_POST['delete_image'])) {
                        foreach($_POST['delete_image'] as $imageId) {
                            // Get image info
                            $image = $this->imageModel->getImageById($imageId);
                            
                            // Check if image belongs to this item
                            if($image && $image->item_id == $id) {
                                // Delete file
                                $filePath = 'uploads/items/' . $image->file_path;
                                if(file_exists($filePath)) {
                                    unlink($filePath);
                                }
                                
                                // Delete from database
                                $this->imageModel->deleteImage($imageId);
                            }
                        }
                    }
                    
                    $this->setFlash('success', 'Chỉnh sửa tin thành công', 'alert alert-success');
                    $this->redirect('items/show/' . $id);
                } else {
                    $this->setFlash('error', 'Có lỗi xảy ra, vui lòng thử lại', 'alert alert-danger');
                    $this->view('items/edit', $data);
                }
            } else {
                // Load view with errors
                $this->view('items/edit', $data);
            }
        } else {
            // Get data for edit form
            $data = [
                'id' => $id,
                'title' => $item->title,
                'description' => $item->description,
                'category_id' => $item->category_id,
                'location' => $item->location,
                'lost_found_date' => $item->lost_found_date,
                'private_info' => $item->private_info,
                'type' => $item->type,
                'title_err' => '',
                'description_err' => '',
                'category_id_err' => '',
                'location_err' => '',
                'lost_found_date_err' => '',
                'categories' => $this->categoryModel->getCategories(),
                'images' => $images
            ];
            
            $this->view('items/edit', $data);
        }
    }
    
    // Delete an item
    public function delete($id = 0) {
        // Check if logged in
        if(!$this->isLoggedIn()) {
            $this->setFlash('error', 'Vui lòng đăng nhập để thực hiện chức năng này', 'alert alert-danger');
            $this->redirect('users/login');
            return;
        }
        
        // Check if request method is POST
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->setFlash('error', 'Không thể thực hiện yêu cầu này', 'alert alert-danger');
            $this->redirect('users/manage_items');
            return;
        }
        
        // Check if id is valid
        if(!is_numeric($id) || $id <= 0) {
            $this->setFlash('error', 'ID không hợp lệ', 'alert alert-danger');
            $this->redirect('users/manage_items');
            return;
        }
        
        // Get item
        $item = $this->itemModel->getItemById($id);
        
        // Check if item exists
        if(!$item) {
            $this->setFlash('error', 'Không tìm thấy tin', 'alert alert-danger');
            $this->redirect('users/manage_items');
            return;
        }
        
        // Check if user owns the item
        if($item->user_id != $this->getUser()->id) {
            $this->setFlash('error', 'Bạn không có quyền xóa tin này', 'alert alert-danger');
            $this->redirect('users/manage_items');
            return;
        }
        
        // Check if item can be deleted (only active items can be deleted)
        if($item->status != 'active') {
            $this->setFlash('error', 'Chỉ có thể xóa những tin đang hoạt động', 'alert alert-danger');
            $this->redirect('users/manage_items');
            return;
        }
        
        // Get images
        $images = $this->imageModel->getImagesByItemId($id);
        
        // Delete all images
        foreach($images as $image) {
            $filePath = 'uploads/items/' . $image->file_path;
            if(file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete images from database
        $this->imageModel->deleteAllImages($id);
        
        // Delete item
        if($this->itemModel->deleteItem($id, $this->getUser()->id)) {
            $this->setFlash('success', 'Xóa tin thành công', 'alert alert-success');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi xóa tin, vui lòng thử lại', 'alert alert-danger');
        }
        
        $this->redirect('users/manage_items');
    }
} 