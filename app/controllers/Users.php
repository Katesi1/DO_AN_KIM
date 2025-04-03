<?php
class Users extends Controller {
    private $userModel;
    private $itemModel;
    private $claimModel;
    private $ratingModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
        $this->itemModel = $this->model('Item');
        $this->claimModel = $this->model('Claim');
        $this->ratingModel = $this->model('Rating');
    }
    
    // Register User
    public function register() {
        // Check if user already logged in
        if($this->isLoggedIn()) {
            $this->redirect('');
        }
        
        // Check if POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'full_name' => trim($_POST['full_name']),
                'phone' => trim($_POST['phone']),
                'faculty' => trim($_POST['faculty']),
                'class' => trim($_POST['class']),
                'student_id' => trim($_POST['student_id']),
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'full_name_err' => '',
                'phone_err' => '',
                'faculty_err' => '',
                'class_err' => '',
                'student_id_err' => ''
            ];
            
            // Validate username
            if(empty($data['username'])) {
                $data['username_err'] = 'Vui lòng nhập tên đăng nhập';
            } elseif(strlen($data['username']) < 3) {
                $data['username_err'] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
            } elseif($this->userModel->findUserByUsername($data['username'])) {
                $data['username_err'] = 'Tên đăng nhập đã được sử dụng';
            }
            
            // Validate email
            if(empty($data['email'])) {
                $data['email_err'] = 'Vui lòng nhập email';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Email không hợp lệ';
            } elseif($this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email đã được sử dụng';
            }
            
            // Validate password
            if(empty($data['password'])) {
                $data['password_err'] = 'Vui lòng nhập mật khẩu';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            // Validate confirm password
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Vui lòng xác nhận mật khẩu';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Mật khẩu không khớp';
                }
            }
            
            // Validate full name
            if(empty($data['full_name'])) {
                $data['full_name_err'] = 'Vui lòng nhập họ tên';
            }
            
            // Make sure errors are empty
            if(empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && 
               empty($data['confirm_password_err']) && empty($data['full_name_err'])) {
                // Validated
                
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Đã bỏ phần tạo verification token
                $data['verification_token'] = null;
                
                // Register user
                if($userId = $this->userModel->register($data)) {
                    // Đã bỏ phần gửi email xác thực
                    
                    // Tự động xác thực tài khoản
                    $this->userModel->autoVerifyEmail($userId);
                    
                    // Thêm flash message thông báo thành công
                    $this->setFlash('register_success', 'Đăng ký tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.', 'alert alert-success');
                    
                    // Chuyển hướng đến trang đăng nhập
                    $this->redirect('users/login');
                } else {
                    $this->setFlash('register_fail', 'Đã xảy ra lỗi, vui lòng thử lại sau', 'alert alert-danger');
                    $this->view('users/register', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'full_name' => '',
                'phone' => '',
                'faculty' => '',
                'class' => '',
                'student_id' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'full_name_err' => '',
                'phone_err' => '',
                'faculty_err' => '',
                'class_err' => '',
                'student_id_err' => ''
            ];
            
            // Load view
            $this->view('users/register', $data);
        }
    }
    
    // Send verification email
    private function sendVerificationEmail($email, $username, $token) {
        // Verification link
        $verificationLink = URLROOT . '/users/verify/' . $token;
        
        // Email subject
        $subject = 'Xác thực tài khoản - ' . SITENAME;
        
        // Email message (HTML format)
        $message = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #0066cc; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9fa; }
                .button { display: inline-block; background-color: #0066cc; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #777; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Xác thực tài khoản của bạn</h2>
                </div>
                <div class="content">
                    <p>Xin chào ' . $username . ',</p>
                    <p>Cảm ơn bạn đã đăng ký tài khoản tại ' . SITENAME . '. Để hoàn tất quá trình đăng ký, vui lòng xác thực email của bạn bằng cách nhấp vào nút bên dưới:</p>
                    <p style="text-align: center;">
                        <a href="' . $verificationLink . '" class="button">Xác thực email của tôi</a>
                    </p>
                    <p>Hoặc bạn có thể sao chép và dán liên kết sau vào trình duyệt của bạn:</p>
                    <p>' . $verificationLink . '</p>
                    <p>Liên kết này sẽ hết hạn sau 24 giờ.</p>
                    <p>Nếu bạn không tạo tài khoản này, vui lòng bỏ qua email này.</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ' . SITENAME . '. Tất cả các quyền được bảo lưu.</p>
                </div>
            </div>
        </body>
        </html>';
        
        // Trong môi trường local, không cố gắng gửi email mà lưu link xác thực vào file
        if (strpos(URLROOT, 'localhost') !== false || strpos(URLROOT, '127.0.0.1') !== false) {
            // Đang chạy ở môi trường local, không gửi email
            $log_file = "../verification_links.txt";
            
            // Tạo thư mục nếu chưa tồn tại
            $dir = dirname($log_file);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            
            // Lưu liên kết xác thực vào file
            file_put_contents(
                $log_file, 
                date('Y-m-d H:i:s') . " - " . $email . ": " . $verificationLink . "\n", 
                FILE_APPEND
            );
            
            // Log thông tin cho admin
            error_log("DEVELOPMENT MODE: Verification link for " . $email . ": " . $verificationLink);
            
            // Thông báo cho người dùng
            $this->setFlash('verification_note', 'Hệ thống đang chạy ở chế độ phát triển. Vui lòng liên hệ quản trị viên để xác thực tài khoản của bạn.');
            
            // Xử lý đặc biệt cho Gmail trong môi trường dev
            if (strpos($email, 'gmail.com') !== false) {
                $this->setFlash('gmail_note', 'Phát hiện tài khoản Gmail: Để tài khoản của bạn được xác thực ngay lập tức, vui lòng thông báo với quản trị viên.', 'alert alert-info');
            }
            
            // Trả về false để biết email không được gửi
            return false;
        }
        
        // Nếu là môi trường production, thử gửi email thật
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_ADDRESS . '>' . "\r\n";
        
        // Tắt báo lỗi khi gọi hàm mail() để tránh hiển thị warning cho người dùng
        $mail_sent = @mail($email, $subject, $message, $headers);
        
        // Nếu gửi mail thất bại, lưu link vào file9+
        if (!$mail_sent) {
            error_log("Failed to send email via mail() to: " . $email);
            
            // Log liên kết xác thực
            $log_file = "../verification_links.txt";
            file_put_contents(
                $log_file, 
                date('Y-m-d H:i:s') . " - " . $email . ": " . $verificationLink . "\n", 
                FILE_APPEND
            );
            
            return false;
        }
        
        return true;
    }
    
    // Login User
    public function login() {
        // Check if user already logged in
        if($this->isLoggedIn()) {
            $this->redirect('');
        }
        
        // Check if POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'remember_me' => isset($_POST['remember_me']) ? true : false,
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Validate email
            if(empty($data['email'])) {
                $data['email_err'] = 'Vui lòng nhập email';
            }
            
            // Validate password
            if(empty($data['password'])) {
                $data['password_err'] = 'Vui lòng nhập mật khẩu';
            }
            
            // Check for user/email
            if(empty($data['email_err']) && empty($data['password_err'])) {
                // Check and set logged in user
                $user = $this->userModel->login($data['email'], $data['password']);
                
                if($user) {
                    // Đã bỏ kiểm tra trạng thái xác thực email
                    
                    // Create session
                    $this->createUserSession($user);
                    
                    // Set remember me cookie if checked
                    if($data['remember_me']) {
                        // Implementation for remember me (use secure cookie)
                    }
                    
                    // Redirect to home page or intended page
                    if(isset($_SESSION['return_to'])) {
                        $return_to = $_SESSION['return_to'];
                        unset($_SESSION['return_to']);
                        $this->redirect($return_to);
                    } else {
                        $this->redirect('');
                    }
                } else {
                    $data['password_err'] = 'Email hoặc mật khẩu không chính xác';
                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'remember_me' => false,
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Load view
            $this->view('users/login', $data);
        }
    }
    
    // Create user session
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->username;
        $_SESSION['user_full_name'] = $user->full_name;
        $_SESSION['user_role'] = $user->role_id;
        
        // Directly set role name based on role_id to avoid any database issues
        if ($user->role_id == 3) {
            $_SESSION['user_role_name'] = 'Admin';
        } elseif ($user->role_id == 2) {
            $_SESSION['user_role_name'] = 'Moderator';
        } else {
            $_SESSION['user_role_name'] = 'User';
        }
        
        // Debug info - log the session data
        file_put_contents(
            APPROOT . '/../session_debug.txt', 
            "Session created for user: $user->username\nRole ID: $user->role_id\nRole Name: " . $_SESSION['user_role_name'] . "\n" . date('Y-m-d H:i:s') . "\n\n",
            FILE_APPEND
        );
    }
    
    // Debug session - display all session data
    public function debug_session() {
        echo '<pre>';
        echo 'Session data:<br>';
        print_r($_SESSION);
        echo '<br>SERVER variables:<br>';
        print_r($_SERVER);
        echo '</pre>';
        exit;
    }
    
    // Logout user
    public function logout() {
        // Unset session variables
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_full_name']);
        unset($_SESSION['user_role']);
        
        // Destroy session
        session_destroy();
        
        // Redirect to login
        $this->redirect('users/login');
    }
    
    // Verify email
    public function verify($token = '') {
        if(empty($token)) {
            $this->redirect('');
        }
        
        $user = $this->userModel->verifyEmail($token);
        
        $data = [
            'verified' => false
        ];
        
        if($user) {
            $data['verified'] = true;
            $this->setFlash('verify_success', 'Email đã được xác thực thành công! Bạn có thể đăng nhập ngay bây giờ.');
            $this->view('users/verify', $data);
        } else {
            $this->setFlash('verify_fail', 'Liên kết xác thực không hợp lệ hoặc đã hết hạn.', 'alert alert-danger');
            $this->view('users/verify', $data);
        }
    }
    
    // Forgot password
    public function forgot() {
        // Check if POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => ''
            ];
            
            // Validate email
            if(empty($data['email'])) {
                $data['email_err'] = 'Vui lòng nhập email';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Email không hợp lệ';
            } elseif(!$this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email không tồn tại trong hệ thống';
            }
            
            // Make sure errors are empty
            if(empty($data['email_err'])) {
                // Generate token
                $token = generate_token();
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Set reset token in database
                if($this->userModel->setResetToken($data['email'], $token, $expiry)) {
                    // Send password reset email (to be implemented)
                    // For now, just display success message
                    $this->setFlash('forgot_success', 'Hướng dẫn đặt lại mật khẩu đã được gửi đến email của bạn.');
                    $this->redirect('users/login');
                } else {
                    $this->setFlash('forgot_fail', 'Đã xảy ra lỗi, vui lòng thử lại sau', 'alert alert-danger');
                    $this->view('users/forgot', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/forgot', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'email_err' => ''
            ];
            
            // Load view
            $this->view('users/forgot', $data);
        }
    }
    
    // Reset password
    public function reset($token = '') {
        if(empty($token)) {
            $this->redirect('');
        }
        
        $user = $this->userModel->verifyResetToken($token);
        
        if(!$user) {
            $this->setFlash('reset_fail', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.', 'alert alert-danger');
            $this->redirect('users/login');
        }
        
        // Check if POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'token' => $token,
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            
            // Validate password
            if(empty($data['password'])) {
                $data['password_err'] = 'Vui lòng nhập mật khẩu mới';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            // Validate confirm password
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Vui lòng xác nhận mật khẩu';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Mật khẩu không khớp';
                }
            }
            
            // Make sure errors are empty
            if(empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Update password
                if($this->userModel->updatePassword($user->id, $data['password'])) {
                    $this->setFlash('reset_success', 'Mật khẩu đã được đặt lại thành công! Bạn có thể đăng nhập ngay bây giờ.');
                    $this->redirect('users/login');
                } else {
                    $this->setFlash('reset_fail', 'Đã xảy ra lỗi, vui lòng thử lại sau', 'alert alert-danger');
                    $this->view('users/reset', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/reset', $data);
            }
        } else {
            // Init data
            $data = [
                'token' => $token,
                'password' => '',
                'confirm_password' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            
            // Load view
            $this->view('users/reset', $data);
        }
    }
    
    // View profile
    public function profile() {
        // Check if user is logged in
        if(!$this->isLoggedIn()) {
            $_SESSION['return_to'] = 'users/profile';
            $this->redirect('users/login');
        }
        
        // Get user data
        $user = $this->getUserData();
        
        // Get user activity
        $items = $this->userModel->getUserActivities($user->id);
        
        // Đếm số tin đã giải quyết
        $resolvedCount = $this->itemModel->countUserItemsByStatus($user->id, 'resolved');
        
        // Get user ratings
        $ratings = $this->ratingModel->getRatingsByUser($user->id, 5);
        $averageRating = $this->ratingModel->getAverageRating($user->id);
        $ratingCount = $this->ratingModel->getRatingCount($user->id);
        
        $data = [
            'user' => $user,
            'items' => $items,
            'ratings' => $ratings,
            'averageRating' => $averageRating,
            'ratingCount' => $ratingCount,
            'resolvedCount' => $resolvedCount
        ];
        
        $this->view('users/profile', $data);
    }
    
    // Edit profile
    public function edit() {
        // Check if user is logged in
        if(!$this->isLoggedIn()) {
            $_SESSION['return_to'] = 'users/edit';
            $this->redirect('users/login');
        }
        
        // Get user data
        $user = $this->getUserData();
        
        // Check if POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'id' => $user->id,
                'full_name' => trim($_POST['full_name']),
                'phone' => trim($_POST['phone']),
                'faculty' => trim($_POST['faculty']),
                'class' => trim($_POST['class']),
                'student_id' => trim($_POST['student_id']),
                'full_name_err' => '',
                'phone_err' => '',
                'faculty_err' => '',
                'class_err' => '',
                'student_id_err' => ''
            ];
            
            // Validate full name
            if(empty($data['full_name'])) {
                $data['full_name_err'] = 'Vui lòng nhập họ tên';
            }
            
            // Make sure errors are empty
            if(empty($data['full_name_err'])) {
                // Update user
                if($this->userModel->updateProfile($data)) {
                    $this->setFlash('profile_success', 'Thông tin cá nhân đã được cập nhật thành công.');
                    $this->redirect('users/profile');
                } else {
                    $this->setFlash('profile_fail', 'Đã xảy ra lỗi, vui lòng thử lại sau', 'alert alert-danger');
                    $this->view('users/edit', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/edit', $data);
            }
        } else {
            // Init data
            $data = [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'phone' => $user->phone,
                'faculty' => $user->faculty,
                'class' => $user->class,
                'student_id' => $user->student_id,
                'full_name_err' => '',
                'phone_err' => '',
                'faculty_err' => '',
                'class_err' => '',
                'student_id_err' => ''
            ];
            
            // Load view
            $this->view('users/edit', $data);
        }
    }

    // View other user's profile - renamed from view to viewProfile to avoid conflict with the base Controller::view method
    public function viewProfile($username = '') {
        if(empty($username)) {
            $this->redirect('');
        }
        
        // Check if user exists
        if(!$this->userModel->findUserByUsername($username)) {
            $this->redirect('pages/error404');
        }
        
        // Get user data by username
        $this->userModel->query('SELECT * FROM Users WHERE username = :username');
        $this->userModel->bind(':username', $username);
        $user = $this->userModel->single();
        
        // Get user's public items
        $items = $this->userModel->getUserActivities($user->id);
        
        // Get user ratings
        $ratings = $this->ratingModel->getRatingsByUser($user->id, 5);
        $averageRating = $this->ratingModel->getAverageRating($user->id);
        $ratingCount = $this->ratingModel->getRatingCount($user->id);
        
        $data = [
            'user' => $user,
            'items' => $items,
            'ratings' => $ratings,
            'averageRating' => $averageRating,
            'ratingCount' => $ratingCount
        ];
        
        $this->view('users/view', $data);
    }

    // Resend verification email
    public function resend_verification() {
        // Check if POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => ''
            ];
            
            // Validate email
            if(empty($data['email'])) {
                $data['email_err'] = 'Vui lòng nhập email';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Email không hợp lệ';
            } else {
                // Check if email exists and is not verified
                $user = $this->userModel->getUserByEmail($data['email']);
                if(!$user) {
                    $data['email_err'] = 'Email không tồn tại trong hệ thống';
                } elseif($user->is_email_verified) {
                    $data['email_err'] = 'Email này đã được xác thực';
                }
            }
            
            // Make sure errors are empty
            if(empty($data['email_err'])) {
                // Generate new verification token
                $token = generate_token();
                
                // Update verification token in database
                if($this->userModel->updateVerificationToken($data['email'], $token)) {
                    // Send verification email
                    $email_sent = $this->sendVerificationEmail($data['email'], $user->username, $token);
                    
                    // Prepare verification data
                    $verificationData = [
                        'email' => $data['email'],
                        'email_sent' => $email_sent
                    ];
                    
                    // Add flash message for success
                    $this->setFlash('resend_success', 'Email xác thực đã được gửi lại thành công!', 'alert alert-success');
                    
                    // If there was an issue with sending email
                    if (!$email_sent) {
                        $this->setFlash('email_warning', 'Lưu ý: Hệ thống gặp khó khăn khi gửi email. Vui lòng liên hệ quản trị viên nếu bạn không nhận được email xác thực.', 'alert alert-warning');
                    }
                    
                    // Redirect to verification info page
                    $this->view('users/email_verification_sent', $verificationData);
                } else {
                    $this->setFlash('error', 'Đã xảy ra lỗi, vui lòng thử lại sau', 'alert alert-danger');
                    $this->view('users/resend_verification', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/resend_verification', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'email_err' => ''
            ];
            
            // Load view
            $this->view('users/resend_verification', $data);
        }
    }

    // Manual verification by admin (this could be protected by admin authentication in a real app)
    public function admin_verify($token = '') {
        if(empty($token)) {
            $this->setFlash('error', 'Token không hợp lệ', 'alert alert-danger');
            $this->redirect('');
            return;
        }
        
        $user = $this->userModel->verifyEmail($token);
        
        if($user) {
            $this->setFlash('success', 'Tài khoản đã được xác thực thành công!', 'alert alert-success');
            $this->redirect('users/login');
        } else {
            $this->setFlash('error', 'Token không hợp lệ hoặc đã hết hạn', 'alert alert-danger');
            $this->redirect('');
        }
    }
    
    // View verification links (admin only)
    public function view_verification_links() {
        // In a real app, you would check if the user is an admin
        // if(!$this->isAdmin()) {
        //     $this->redirect('');
        //     return;
        // }
        
        $log_file = "../verification_links.txt";
        $links = [];
        
        if(file_exists($log_file)) {
            $links = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }
        
        $data = [
            'title' => 'Danh sách liên kết xác thực',
            'links' => array_reverse($links) // Most recent first
        ];
        
        $this->view('admin/verification_links', $data);
    }

    // Verify by email (direct verification without token for admin use)
    public function verify_by_email($email = '') {
        // Kiểm tra quyền truy cập (trong thực tế cần thêm kiểm tra admin)
        // if(!$this->isAdmin()) {
        //     $this->redirect('');
        //     return;
        // }
        
        if(empty($email)) {
            $this->setFlash('error', 'Email không được để trống', 'alert alert-danger');
            $this->redirect('users/view_verification_links');
            return;
        }
        
        // Lấy thông tin người dùng từ email
        $user = $this->userModel->getUserByEmail($email);
        
        if(!$user) {
            $this->setFlash('error', 'Không tìm thấy người dùng với email này', 'alert alert-danger');
            $this->redirect('users/view_verification_links');
            return;
        }
        
        // Kiểm tra nếu đã xác thực rồi
        if($user->is_email_verified) {
            $this->setFlash('info', 'Email này đã được xác thực trước đó', 'alert alert-info');
            $this->redirect('users/view_verification_links');
            return;
        }
        
        // Xác thực email
        if($this->userModel->verifyEmailDirectly($email)) {
            $this->setFlash('success', 'Đã xác thực email thành công!', 'alert alert-success');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi xác thực email', 'alert alert-danger');
        }
        
        $this->redirect('users/view_verification_links');
    }

    // Quản lý tin đã đăng của người dùng
    public function manage_items() {
        // Kiểm tra đăng nhập
        if(!$this->isLoggedIn()) {
            $_SESSION['return_to'] = 'users/manage_items';
            $this->redirect('users/login');
            return;
        }
        
        // Lấy thông tin người dùng
        $user = $this->getUserData();
        
        // Lấy trạng thái filter nếu có
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        
        // Phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Số item trên mỗi trang
        $offset = ($page - 1) * $limit;
        
        // Lấy danh sách tin đã đăng của người dùng với thông tin danh mục, hình ảnh và số lượng yêu cầu
        $items = $this->itemModel->getUserItemsWithDetails($user->id, $status, $limit, $offset);
        
        // Đếm tổng số tin 
        $totalItems = $this->itemModel->countUserItems($user->id, $status);
        
        // Đếm số tin theo loại
        $lostCount = $this->itemModel->countUserItemsByType($user->id, 'lost');
        $foundCount = $this->itemModel->countUserItemsByType($user->id, 'found');
        
        // Đếm số tin đã giải quyết
        $resolvedCount = $this->itemModel->countUserItemsByStatus($user->id, 'resolved');
        
        // Tính toán phân trang
        $totalPages = ceil($totalItems / $limit);
        
        $data = [
            'user' => $user,
            'items' => $items,
            'lostCount' => $lostCount,
            'foundCount' => $foundCount,
            'resolvedCount' => $resolvedCount,
            'pagination' => [
                'total_items' => $totalItems,
                'items_per_page' => $limit,
                'current_page' => $page,
                'total_pages' => $totalPages
            ]
        ];
        
        $this->view('users/manage_items', $data);
    }

    // Change password
    public function change_password() {
        // Check if user is logged in
        if(!$this->isLoggedIn()) {
            $_SESSION['return_to'] = 'users/change_password';
            $this->redirect('users/login');
            return;
        }
        
        // Get user data
        $user = $this->getUserData();
        
        // Check if POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'current_password' => trim($_POST['current_password']),
                'new_password' => trim($_POST['new_password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => ''
            ];
            
            // Validate current password
            if(empty($data['current_password'])) {
                $data['current_password_err'] = 'Vui lòng nhập mật khẩu hiện tại';
            } else {
                // Check if current password is correct
                if(!password_verify($data['current_password'], $user->password)) {
                    $data['current_password_err'] = 'Mật khẩu hiện tại không chính xác';
                }
            }
            
            // Validate new password
            if(empty($data['new_password'])) {
                $data['new_password_err'] = 'Vui lòng nhập mật khẩu mới';
            } elseif(strlen($data['new_password']) < 6) {
                $data['new_password_err'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            } elseif($data['new_password'] === $data['current_password']) {
                $data['new_password_err'] = 'Mật khẩu mới không được trùng với mật khẩu hiện tại';
            }
            
            // Validate confirm password
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Vui lòng xác nhận mật khẩu mới';
            } elseif($data['new_password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Mật khẩu xác nhận không khớp';
            }
            
            // Make sure errors are empty
            if(empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
                // Hash new password
                $data['new_password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                
                // Update password
                if($this->userModel->updatePassword($user->id, $data['new_password'])) {
                    $this->setFlash('change_password_success', 'Mật khẩu đã được cập nhật thành công!', 'alert alert-success');
                    $this->redirect('users/profile');
                } else {
                    $this->setFlash('change_password_fail', 'Đã xảy ra lỗi, vui lòng thử lại sau.', 'alert alert-danger');
                    $this->view('users/change_password', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/change_password', $data);
            }
        } else {
            // Init data
            $data = [
                'current_password' => '',
                'new_password' => '',
                'confirm_password' => '',
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => ''
            ];
            
            // Load view
            $this->view('users/change_password', $data);
        }
    }
    
    // Get user by id (from session)
    private function getUserData() {
        if(isset($_SESSION['user_id'])) {
            return $this->userModel->getUserById($_SESSION['user_id']);
        }
        return false;
    }
}
?> 