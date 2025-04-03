<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-1">Đặt lại mật khẩu</h2>
                        <p class="text-muted">Tạo mật khẩu mới cho tài khoản của bạn</p>
                    </div>
                    
                    <form action="<?php echo URLROOT; ?>/users/reset/<?php echo $data['token']; ?>" method="post">
                        <input type="hidden" name="email" value="<?php echo $data['email']; ?>">
                        <input type="hidden" name="token" value="<?php echo $data['token']; ?>">
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Nhập mật khẩu mới">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback"><?php echo $data['password_err']; ?></div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu mới">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                <i class="fas fa-save me-2"></i>Đặt lại mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?> 