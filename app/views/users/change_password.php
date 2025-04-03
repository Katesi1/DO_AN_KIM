<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h1 class="h4 mb-0 fw-bold"><i class="fas fa-key me-2"></i>Đổi mật khẩu</h1>
                </div>
                <div class="card-body p-4">
                    <?php flash('change_password_success'); ?>
                    <?php flash('change_password_fail'); ?>

                    <form action="<?php echo URLROOT; ?>/users/change_password" method="POST" class="needs-validation" novalidate>
                        <!-- Mật khẩu hiện tại -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control <?php echo (!empty($data['current_password_err'])) ? 'is-invalid' : ''; ?>" 
                                    id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    <?php echo $data['current_password_err']; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Mật khẩu mới -->
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>" 
                                    id="new_password" name="new_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    <?php echo $data['new_password_err']; ?>
                                </div>
                            </div>
                            <div class="form-text">
                                Mật khẩu phải có ít nhất 6 ký tự
                            </div>
                        </div>

                        <!-- Xác nhận mật khẩu mới -->
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" 
                                    id="confirm_password" name="confirm_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    <?php echo $data['confirm_password_err']; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Đổi mật khẩu
                            </button>
                            <a href="<?php echo URLROOT; ?>/users/profile" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hiển thị/ẩn mật khẩu
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
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
});
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?> 