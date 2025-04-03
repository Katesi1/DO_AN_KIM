<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-1">Đăng nhập</h2>
                        <p class="text-muted">Vui lòng đăng nhập để tiếp tục</p>
                    </div>
                    
                    <form action="<?php echo URLROOT; ?>/users/login" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" class="form-control border-start-0 <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Nhập email của bạn" value="<?php echo $data['email']; ?>">
                                <div class="invalid-feedback"><?php echo $data['email_err']; ?></div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <a href="<?php echo URLROOT; ?>/users/forgot" class="text-decoration-none small">Quên mật khẩu?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Nhập mật khẩu">
                                <div class="invalid-feedback"><?php echo $data['password_err']; ?></div>
                            </div>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me" <?php echo $data['remember_me'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="remember_me">Ghi nhớ đăng nhập</label>
                        </div>
                        
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">Chưa có tài khoản? <a href="<?php echo URLROOT; ?>/users/register" class="text-decoration-none fw-bold">Đăng ký ngay</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 