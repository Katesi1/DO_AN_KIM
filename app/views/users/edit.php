<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h1 class="h4 mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Chỉnh sửa hồ sơ</h1>
                </div>
                <div class="card-body p-4">
                    <?php flash('profile_success'); ?>
                    <?php flash('profile_fail'); ?>

                    <form action="<?php echo URLROOT; ?>/users/edit" method="POST" class="needs-validation" novalidate>
                        <!-- Họ tên -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo (!empty($data['full_name_err'])) ? 'is-invalid' : ''; ?>" 
                                id="full_name" name="full_name" value="<?php echo $data['full_name']; ?>" required>
                            <div class="invalid-feedback">
                                <?php echo $data['full_name_err']; ?>
                            </div>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" 
                                id="phone" name="phone" value="<?php echo $data['phone']; ?>">
                            <div class="invalid-feedback">
                                <?php echo $data['phone_err']; ?>
                            </div>
                            <div class="form-text">
                                Số điện thoại giúp người dùng khác liên hệ với bạn khi tìm thấy đồ thất lạc
                            </div>
                        </div>

                        <!-- Thông tin trường học -->
                        <h5 class="text-muted mb-3 mt-4 border-bottom pb-2">Thông tin trường học</h5>
                        <div class="mb-3">
                            <label for="faculty" class="form-label">Khoa</label>
                            <select class="form-select <?php echo (!empty($data['faculty_err'])) ? 'is-invalid' : ''; ?>" 
                                id="faculty" name="faculty">
                                <option value="" <?php echo empty($data['faculty']) ? 'selected' : ''; ?>>-- Chọn khoa --</option>
                                <option value="Công nghệ thông tin" <?php echo $data['faculty'] == 'Công nghệ thông tin' ? 'selected' : ''; ?>>Công nghệ thông tin</option>
                                <option value="Kinh tế" <?php echo $data['faculty'] == 'Kinh tế' ? 'selected' : ''; ?>>Kinh tế</option>
                                <option value="Kế toán" <?php echo $data['faculty'] == 'Kế toán' ? 'selected' : ''; ?>>Kế toán</option>
                                <option value="Ngoại ngữ" <?php echo $data['faculty'] == 'Ngoại ngữ' ? 'selected' : ''; ?>>Ngoại ngữ</option>
                                <option value="Quản trị kinh doanh" <?php echo $data['faculty'] == 'Quản trị kinh doanh' ? 'selected' : ''; ?>>Quản trị kinh doanh</option>
                                <option value="Khác" <?php echo $data['faculty'] == 'Khác' ? 'selected' : ''; ?>>Khác</option>
                            </select>
                            <div class="invalid-feedback">
                                <?php echo $data['faculty_err']; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="class" class="form-label">Lớp</label>
                            <input type="text" class="form-control <?php echo (!empty($data['class_err'])) ? 'is-invalid' : ''; ?>" 
                                id="class" name="class" value="<?php echo $data['class']; ?>">
                            <div class="invalid-feedback">
                                <?php echo $data['class_err']; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="student_id" class="form-label">Mã sinh viên</label>
                            <input type="text" class="form-control <?php echo (!empty($data['student_id_err'])) ? 'is-invalid' : ''; ?>" 
                                id="student_id" name="student_id" value="<?php echo $data['student_id']; ?>">
                            <div class="invalid-feedback">
                                <?php echo $data['student_id_err']; ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
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

<?php require APPROOT . '/views/includes/footer.php'; ?> 