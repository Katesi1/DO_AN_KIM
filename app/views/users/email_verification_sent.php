<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-envelope fa-3x"></i>
                        </div>
                        <h2 class="fw-bold mt-4 mb-2">Kiểm tra email của bạn</h2>
                        <p class="mb-0 text-muted">Chúng tôi đã gửi một email xác thực đến:</p>
                        <p class="mb-4 fw-bold"><?php echo $data['email']; ?></p>
                        
                        <?php if (isset($data['email_sent']) && !$data['email_sent']): ?>
                            <?php if (isset($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1')): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Lưu ý:</strong> Ứng dụng đang chạy trên môi trường phát triển.
                                    <p class="mb-0 mt-2">Liên kết xác thực đã được lưu vào tập tin. Vui lòng liên hệ quản trị viên để xác thực tài khoản của bạn.</p>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="<?php echo URLROOT; ?>/users/view_verification_links" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-link me-1"></i>Xem liên kết xác thực (Admin)
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>Lỗi:</strong> Không thể gửi email xác thực.
                                    <p class="mb-0 mt-2">Vui lòng liên hệ quản trị viên qua email: <?php echo MAIL_USERNAME; ?></p>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Hướng dẫn:</strong>
                                <ul class="mb-0 mt-2 text-start">
                                    <li>Vui lòng kiểm tra hộp thư đến của bạn.</li>
                                    <li>Nếu không tìm thấy, hãy kiểm tra thư mục spam.</li>
                                    <li>Liên kết xác thực có hiệu lực trong 24 giờ.</li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (strpos($data['email'], 'gmail.com') !== false): ?>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-envelope me-2"></i>
                            <strong>Lưu ý cho người dùng Gmail:</strong>
                            <ul class="mb-0 mt-2 text-start">
                                <li>Kiểm tra thư mục "Quảng cáo" hoặc "Spam" trong Gmail</li>
                                <li>Thêm <?php echo MAIL_FROM_ADDRESS; ?> vào danh sách liên hệ của bạn</li>
                                <li>Kiểm tra bộ lọc email nếu bạn đã thiết lập</li>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2 col-md-8 mx-auto mt-4">
                            <a href="<?php echo URLROOT; ?>/users/resend_verification" class="btn btn-primary py-2 fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>Gửi lại email xác thực
                            </a>
                            <a href="<?php echo URLROOT; ?>" class="btn btn-outline-secondary py-2">
                                <i class="fas fa-home me-2"></i>Về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 