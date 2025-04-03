<?php
class Pages extends Controller {
    private $itemModel;
    private $categoryModel;
    private $imageModel;
    private $userModel;
    private $claimModel;

    public function __construct() {
        $this->itemModel = $this->model('Item');
        $this->categoryModel = $this->model('Category');
        $this->imageModel = $this->model('Image');
        $this->userModel = $this->model('User');
        $this->claimModel = $this->model('Claim');
    }

    // Home page
    public function index() {
        // Get latest items
        $lostItems = $this->itemModel->getItems('lost', 4);
        $foundItems = $this->itemModel->getItems('found', 4);
        
        // Get popular categories
        $categories = $this->categoryModel->getPopularCategories(6);
        
        // Get statistics
        $totalLost = $this->itemModel->getTotalCount('lost');
        $totalFound = $this->itemModel->getTotalCount('found');
        $totalResolved = $this->itemModel->getTotalResolvedCount(); 
        $topUsers = $this->userModel->getTopUsers(5);
        $recentResolved = $this->itemModel->getRecentlyResolvedItems(3);
        
        // Add images to items
        foreach ($lostItems as $item) {
            $item->image = $this->imageModel->getFirstImage($item->id);
        }
        
        foreach ($foundItems as $item) {
            $item->image = $this->imageModel->getFirstImage($item->id);
        }
        
        foreach ($recentResolved as $item) {
            $item->image = $this->imageModel->getFirstImage($item->id);
        }
        
        $data = [
            'title' => 'Trang chủ',
            'lostItems' => $lostItems,
            'foundItems' => $foundItems,
            'categories' => $categories,
            'totalLost' => $totalLost,
            'totalFound' => $totalFound,
            'totalResolved' => $totalResolved,
            'topUsers' => $topUsers,
            'recentResolved' => $recentResolved
        ];

        $this->view('pages/index', $data);
    }

    // About page
    public function about() {
        $data = [
            'title' => 'Giới thiệu',
            'description' => 'Hệ thống Đồ Vật Thất Lạc - ĐH Phương Đông là nền tảng giúp các sinh viên, giảng viên và nhân viên của trường tìm kiếm hoặc thông báo về những đồ vật bị thất lạc trong khuôn viên trường.'
        ];

        $this->view('pages/about', $data);
    }

    // Contact page
    public function contact() {
        // Check if POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'title' => 'Liên hệ',
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'name_err' => '',
                'email_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];
            
            // Validate input
            if(empty($data['name'])) {
                $data['name_err'] = 'Vui lòng nhập họ tên';
            }
            
            if(empty($data['email'])) {
                $data['email_err'] = 'Vui lòng nhập email';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Email không hợp lệ';
            }
            
            if(empty($data['subject'])) {
                $data['subject_err'] = 'Vui lòng nhập tiêu đề';
            }
            
            if(empty($data['message'])) {
                $data['message_err'] = 'Vui lòng nhập nội dung';
            }
            
            // Make sure there are no errors
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['subject_err']) && empty($data['message_err'])) {
                // Send email (implement later)
                // For now, just simulate sending email
                
                // In a real application, you would use a library like PHPMailer
                // to send the email with proper formatting and attachments if needed
                
                // Example code (commented out for now):
                /*
                $to = 'admin@example.com';
                $subject = 'Contact Form: ' . $data['subject'];
                $message = 'Name: ' . $data['name'] . "\r\n";
                $message .= 'Email: ' . $data['email'] . "\r\n\r\n";
                $message .= $data['message'];
                $headers = 'From: ' . $data['email'] . "\r\n";
                $headers .= 'Reply-To: ' . $data['email'] . "\r\n";
                
                mail($to, $subject, $message, $headers);
                */
                
                $this->setFlash('success', 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.', 'alert alert-success');
                $this->redirect('pages/contact');
            } else {
                // Load view with errors
                $this->view('pages/contact', $data);
            }
        } else {
            $data = [
                'title' => 'Liên hệ',
                'name' => '',
                'email' => '',
                'subject' => '',
                'message' => '',
                'name_err' => '',
                'email_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];
            
            $this->view('pages/contact', $data);
        }
    }

    // FAQ page
    public function faq() {
        $data = [
            'title' => 'Câu hỏi thường gặp',
            'faqs' => [
                [
                    'question' => 'Làm thế nào để đăng bài về đồ vật bị mất?',
                    'answer' => 'Để đăng bài về đồ vật bị mất, bạn cần đăng nhập vào tài khoản, sau đó nhấn vào nút "Đăng tin" ở góc phải màn hình và chọn "Đồ vật bị mất".'
                ],
                [
                    'question' => 'Làm thế nào để báo cáo đồ vật tìm thấy?',
                    'answer' => 'Để báo cáo đồ vật tìm thấy, bạn cần đăng nhập vào tài khoản, sau đó nhấn vào nút "Đăng tin" ở góc phải màn hình và chọn "Đồ vật tìm thấy".'
                ],
                [
                    'question' => 'Làm thế nào để liên hệ với người đăng bài?',
                    'answer' => 'Khi bạn xem chi tiết một bài đăng, bạn có thể nhấn vào nút "Liên hệ" để gửi tin nhắn cho người đăng bài.'
                ],
                [
                    'question' => 'Tôi có thể chỉnh sửa hoặc xóa bài đăng của mình không?',
                    'answer' => 'Có, bạn có thể chỉnh sửa hoặc xóa bài đăng của mình bằng cách vào phần "Tài khoản" > "Quản lý bài đăng" và chọn tùy chọn tương ứng.'
                ],
                [
                    'question' => 'Làm thế nào để nhận thông báo về đồ vật mới?',
                    'answer' => 'Bạn có thể bật thông báo trong phần "Tài khoản" > "Cài đặt" để nhận thông báo về các đồ vật mới được đăng phù hợp với tiêu chí của bạn.'
                ],
                [
                    'question' => 'Tôi làm sao để xác nhận đã nhận lại đồ của mình?',
                    'answer' => 'Sau khi nhận lại đồ, bạn cần đăng nhập vào tài khoản, tìm kiếm bài đăng trong mục "Yêu cầu đã gửi" và chọn "Xác nhận đã nhận lại đồ".'
                ],
                [
                    'question' => 'Làm thế nào để nâng cao độ tin cậy của tài khoản?',
                    'answer' => 'Để nâng cao độ tin cậy, bạn cần xác minh email, hoàn thành đầy đủ thông tin cá nhân, tích cực tham gia vào các hoạt động trả đồ và nhận được đánh giá tốt từ người dùng khác.'
                ],
            ]
        ];

        $this->view('pages/faq', $data);
    }

    // Terms of service
    public function terms() {
        $data = [
            'title' => 'Điều khoản sử dụng'
        ];

        $this->view('pages/terms', $data);
    }

    // Privacy policy
    public function privacy() {
        $data = [
            'title' => 'Chính sách bảo mật'
        ];

        $this->view('pages/privacy', $data);
    }

    // 404 page
    public function error404() {
        $data = [
            'title' => 'Không tìm thấy trang'
        ];

        $this->view('pages/404', $data);
    }
    
    // Statistics page
    public function statistics() {
        // Get statistics
        $totalLost = $this->itemModel->getTotalCount('lost');
        $totalFound = $this->itemModel->getTotalCount('found');
        $totalResolved = $this->itemModel->getTotalResolvedCount();
        $resolvedRate = ($totalLost + $totalFound > 0) ? round(($totalResolved / ($totalLost + $totalFound)) * 100, 1) : 0;
        
        // Get category statistics
        $categoryStats = $this->categoryModel->getCategoryStatistics();
        
        // Get monthly statistics for the past 6 months
        $monthlyStats = $this->itemModel->getMonthlyStatistics(6);
        
        $data = [
            'title' => 'Thống kê',
            'totalLost' => $totalLost,
            'totalFound' => $totalFound,
            'totalResolved' => $totalResolved,
            'resolvedRate' => $resolvedRate,
            'categoryStats' => $categoryStats,
            'monthlyStats' => $monthlyStats
        ];
        
        $this->view('pages/statistics', $data);
    }
}
?> 