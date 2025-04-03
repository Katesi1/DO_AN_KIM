<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Quản trị hệ thống</h1>
                <a href="<?php echo URLROOT; ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại trang chủ
                </a>
            </div>
            
            <!-- Admin Navigation -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-0">
                    <ul class="nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="nav-link active" href="<?php echo URLROOT; ?>/admin">
                                <i class="fas fa-tachometer-alt me-2"></i>Tổng quan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/users">
                                <i class="fas fa-users me-2"></i>Người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/items">
                                <i class="fas fa-clipboard-check me-2"></i>Bài chờ duyệt
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/allItems">
                                <i class="fas fa-list-alt me-2"></i>Tất cả bài đăng
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Dashboard Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-primary text-white shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-white-50">Tổng số người dùng</h6>
                                    <h2 class="card-title mb-0"><?php echo $data['total_users']; ?></h2>
                                </div>
                                <div class="icon-circle bg-white-20">
                                    <i class="fas fa-users fa-2x text-white"></i>
                                </div>
                            </div>
                            <a href="<?php echo URLROOT; ?>/admin/users" class="btn btn-outline-light btn-sm mt-3">
                                Quản lý người dùng <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-warning text-white shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-white-50">Bài viết chờ duyệt</h6>
                                    <h2 class="card-title mb-0"><?php echo $data['pending_items']; ?></h2>
                                </div>
                                <div class="icon-circle bg-white-20">
                                    <i class="fas fa-clipboard-list fa-2x text-white"></i>
                                </div>
                            </div>
                            <a href="<?php echo URLROOT; ?>/admin/items" class="btn btn-outline-light btn-sm mt-3">
                                Phê duyệt bài viết <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-success text-white shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-white-50">Tổng số bài đăng</h6>
                                    <h2 class="card-title mb-0"><?php echo $data['total_items']; ?></h2>
                                </div>
                                <div class="icon-circle bg-white-20">
                                    <i class="fas fa-list-alt fa-2x text-white"></i>
                                </div>
                            </div>
                            <a href="<?php echo URLROOT; ?>/admin/allItems" class="btn btn-outline-light btn-sm mt-3">
                                Quản lý tất cả bài đăng <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-info text-white shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-white-50">Xác thực email</h6>
                                    <h2 class="card-title mb-0">Quản lý</h2>
                                </div>
                                <div class="icon-circle bg-white-20">
                                    <i class="fas fa-envelope-open-text fa-2x text-white"></i>
                                </div>
                            </div>
                            <a href="<?php echo URLROOT; ?>/users/verification_links" class="btn btn-outline-light btn-sm mt-3">
                                Liên kết xác thực <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Actions -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Quản lý người dùng</h5>
                        </div>
                        <div class="card-body">
                            <p>Quản lý tài khoản người dùng, phân quyền và xem thông tin chi tiết.</p>
                            <a href="<?php echo URLROOT; ?>/admin/users" class="btn btn-primary">
                                <i class="fas fa-user-shield me-2"></i>Danh sách người dùng
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Quản lý bài viết</h5>
                        </div>
                        <div class="card-body">
                            <p>Quản lý tất cả bài viết, duyệt bài viết mới và thay đổi trạng thái.</p>
                            <div class="d-flex">
                                <a href="<?php echo URLROOT; ?>/admin/items" class="btn btn-warning me-2">
                                    <i class="fas fa-clipboard-check me-2"></i>Bài chờ duyệt
                                </a>
                                <a href="<?php echo URLROOT; ?>/admin/allItems" class="btn btn-primary">
                                    <i class="fas fa-list-alt me-2"></i>Tất cả bài đăng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        height: 60px;
        width: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-white-20 {
        background-color: rgba(255, 255, 255, 0.2);
    }
</style>

<?php require APPROOT . '/views/includes/footer.php'; ?> 