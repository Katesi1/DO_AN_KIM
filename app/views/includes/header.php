<?php
// Get current user
$user = null;
if(method_exists($this, 'getUser')) {
    $user = $this->getUser();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($data['title']) ? $data['title'] . ' - ' . SITENAME : SITENAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/custom/main.css">
    <style>
        :root {
            --primary: #0066cc;
            --primary-dark: #004d99;
            --primary-light: #e6f0ff;
            --secondary: #6c757d;
            --accent: #ff9900;
            --danger: #dc3545;
            --success: #28a745;
            --dark: #343a40;
            --light: #f8f9fa;
            --gray: #dee2e6;
            --bs-primary: #3B71CA;
            --bs-secondary: #6c757d;
            --bs-success: #14A44D;
            --bs-info: #54B4D3;
            --bs-warning: #E4A11B;
            --bs-danger: #DC4C64;
            --bs-light: #FBFBFB;
            --bs-dark: #332D2D;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            color: #495057;
            width: 100%;
            overflow-x: hidden;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary);
        }

        .navbar-nav .active .nav-link {
            color: var(--primary);
        }

        .logo-container {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            margin-right: 1rem;
            font-weight: 600;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
        }

        @media (max-width: 767.98px) {
            .navbar-brand {
                max-width: 75%;
            }
            
            .logo-container {
                flex-direction: column;
                align-items: flex-start;
                padding: 0.5rem;
            }
            
            .logo-container .text-secondary {
                font-size: 0.75rem;
            }
        }
        
        /* Fix hiển thị modal */
        body.modal-open {
            overflow: hidden;
            padding-right: 0 !important;
        }
        
        .modal-backdrop {
            width: 100vw !important;
            height: 100vh !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="<?php echo URLROOT; ?>">
                <div class="logo-container">
                    <span class="text-white fw-bold">Đồ vật thất lạc</span>
                    <span class="text-white-50 ms-1">- ĐH Phương Đông</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item <?php echo ($_SERVER['REQUEST_URI'] == URLROOT || $_SERVER['REQUEST_URI'] == URLROOT . '/') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo URLROOT; ?>">Trang chủ</a>
                    </li>
                    <li class="nav-item <?php echo ($_SERVER['REQUEST_URI'] == URLROOT . '/items/lost') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/items/lost">Đồ thất lạc</a>
                    </li>
                    <li class="nav-item <?php echo ($_SERVER['REQUEST_URI'] == URLROOT . '/items/found') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/items/found">Đồ tìm thấy</a>
                    </li>
                    <li class="nav-item <?php echo ($_SERVER['REQUEST_URI'] == URLROOT . '/pages/about') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">Giới thiệu</a>
                    </li>
                </ul>
                
                <div class="d-flex">
                    <?php if(isset($_SESSION['user_id'])) : ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/users/profile"><i class="fas fa-id-card me-2"></i>Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/items/manage"><i class="fas fa-list me-2"></i>Quản lý tin</a></li>
                                <?php if (isset($_SESSION['user_role_name']) && $_SESSION['user_role_name'] === 'Admin') : ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Quản trị hệ thống</h6></li>
                                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/admin"><i class="fas fa-tachometer-alt me-2"></i>Bảng điều khiển</a></li>
                                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/admin/users"><i class="fas fa-users me-2"></i>Quản lý người dùng</a></li>
                                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/admin/items"><i class="fas fa-clipboard-check me-2"></i>Phê duyệt bài đăng</a></li>
                                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/admin/allItems"><i class="fas fa-list-alt me-2"></i>Tất cả bài đăng</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/users/logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-primary me-2">
                            <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                        </a>
                        <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i> Đăng ký
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="flash-container container mt-3">
        <?php flash('message'); ?>
        <?php flash('error'); ?>
        <?php flash('success'); ?>
        <?php flash('register_success'); ?>
        <?php flash('register_fail'); ?>
        <?php flash('login_fail'); ?>
        <?php flash('verify_success'); ?>
        <?php flash('verify_fail'); ?>
        <?php flash('forgot_success'); ?>
        <?php flash('forgot_fail'); ?>
        <?php flash('reset_success'); ?>
        <?php flash('reset_fail'); ?>
        <?php flash('profile_success'); ?>
        <?php flash('profile_fail'); ?>
    </div>

    <div class="container mt-4">
        <!-- Main content starts here -->
    </div>
</body>
</html> 