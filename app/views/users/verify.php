<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5 text-center">
                    <?php if ($data['verified']): ?>
                        <div class="verification-success mb-4">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-check fa-3x"></i>
                            </div>
                            <h2 class="fw-bold mt-4 mb-2">Xác thực thành công!</h2>
                            <p class="mb-4 text-muted">Email của bạn đã được xác thực thành công.</p>
                            <div class="d-grid gap-2 col-md-8 mx-auto">
                                <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-primary py-2 fw-bold">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập ngay
                                </a>
                                <a href="<?php echo URLROOT; ?>" class="btn btn-outline-secondary py-2">
                                    <i class="fas fa-home me-2"></i>Về trang chủ
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="verification-failed mb-4">
                            <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-times fa-3x"></i>
                            </div>
                            <h2 class="fw-bold mt-4 mb-2">Xác thực thất bại!</h2>
                            <p class="mb-4 text-muted"><?php echo $data['error_message']; ?></p>
                            <div class="d-grid gap-2 col-md-8 mx-auto">
                                <a href="<?php echo URLROOT; ?>/users/resend_verification" class="btn btn-primary py-2 fw-bold">
                                    <i class="fas fa-paper-plane me-2"></i>Gửi lại email xác thực
                                </a>
                                <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-secondary py-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 