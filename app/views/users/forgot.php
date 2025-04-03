<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-1">Quên mật khẩu</h2>
                        <p class="text-muted">Nhập email của bạn để nhận liên kết đặt lại mật khẩu</p>
                    </div>
                    
                    <form action="<?php echo URLROOT; ?>/users/forgot" method="post">
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" class="form-control border-start-0 <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Nhập email của bạn" value="<?php echo $data['email']; ?>">
                                <div class="invalid-feedback"><?php echo $data['email_err']; ?></div>
                            </div>
                        </div>
                        
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>Gửi liên kết
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">Nhớ mật khẩu? <a href="<?php echo URLROOT; ?>/users/login" class="text-decoration-none fw-bold">Đăng nhập</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 