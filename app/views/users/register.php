<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-1">Đăng ký tài khoản</h2>
                        <p class="text-muted">Tạo tài khoản để sử dụng dịch vụ</p>
                    </div>
                    
                    <form action="<?php echo URLROOT; ?>/users/register" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" id="username" name="username" placeholder="Nhập tên đăng nhập" value="<?php echo $data['username']; ?>">
                                    <div class="invalid-feedback"><?php echo $data['username_err']; ?></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" class="form-control border-start-0 <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Nhập email của bạn" value="<?php echo $data['email']; ?>">
                                    <div class="invalid-feedback"><?php echo $data['email_err']; ?></div>
                                </div>
                                <small class="text-muted">Email sẽ được sử dụng để xác thực tài khoản</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" class="form-control border-start-0 <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Nhập mật khẩu">
                                    <div class="invalid-feedback"><?php echo $data['password_err']; ?></div>
                                </div>
                                <small class="text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" class="form-control border-start-0 <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu">
                                    <div class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user-tag text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 <?php echo (!empty($data['full_name_err'])) ? 'is-invalid' : ''; ?>" id="full_name" name="full_name" placeholder="Nhập họ và tên" value="<?php echo $data['full_name']; ?>">
                                    <div class="invalid-feedback"><?php echo $data['full_name_err']; ?></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" id="phone" name="phone" placeholder="Nhập số điện thoại" value="<?php echo $data['phone']; ?>">
                                    <div class="invalid-feedback"><?php echo $data['phone_err']; ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="faculty" class="form-label">Khoa</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-building text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 <?php echo (!empty($data['faculty_err'])) ? 'is-invalid' : ''; ?>" id="faculty" name="faculty" placeholder="Nhập khoa" value="<?php echo $data['faculty']; ?>">
                                    <div class="invalid-feedback"><?php echo $data['faculty_err']; ?></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="class" class="form-label">Lớp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-users text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 <?php echo (!empty($data['class_err'])) ? 'is-invalid' : ''; ?>" id="class" name="class" placeholder="Nhập lớp" value="<?php echo $data['class']; ?>">
                                    <div class="invalid-feedback"><?php echo $data['class_err']; ?></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-4">
                                <label for="student_id" class="form-label">Mã sinh viên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-id-card text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 <?php echo (!empty($data['student_id_err'])) ? 'is-invalid' : ''; ?>" id="student_id" name="student_id" placeholder="Nhập mã sinh viên" value="<?php echo $data['student_id']; ?>">
                                    <div class="invalid-feedback"><?php echo $data['student_id_err']; ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">Tôi đồng ý với <a href="#" class="text-decoration-none">điều khoản sử dụng</a> và <a href="#" class="text-decoration-none">chính sách bảo mật</a></label>
                        </div>
                        
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">Đã có tài khoản? <a href="<?php echo URLROOT; ?>/users/login" class="text-decoration-none fw-bold">Đăng nhập</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 