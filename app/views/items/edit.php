<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 fw-bold">Chỉnh sửa tin đăng</h1>
                <div>
                    <a href="<?php echo URLROOT; ?>/items/show/<?php echo $data['id']; ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại tin
                    </a>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo URLROOT; ?>/items/edit/<?php echo $data['id']; ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-4">
                            <h5 class="card-title mb-3">
                                <?php if($data['type'] == 'lost'): ?>
                                    <span class="badge bg-danger me-2"><i class="fas fa-search me-1"></i> Đồ thất lạc</span>
                                <?php else: ?>
                                    <span class="badge bg-success me-2"><i class="fas fa-hand-holding me-1"></i> Đồ tìm thấy</span>
                                <?php endif; ?>
                                Thông tin chung
                            </h5>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo $data['title']; ?>" placeholder="VD: Mất ví da màu đen">
                                <div class="invalid-feedback"><?php echo $data['title_err']; ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select class="form-select <?php echo (!empty($data['category_id_err'])) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id">
                                    <option value="" disabled selected>-- Chọn danh mục --</option>
                                    <?php foreach($data['categories'] as $category): ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo ($data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                            <?php echo $category->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback"><?php echo $data['category_id_err']; ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                                <textarea class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="5" placeholder="Mô tả chi tiết về đồ vật"><?php echo $data['description']; ?></textarea>
                                <div class="invalid-feedback"><?php echo $data['description_err']; ?></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Địa điểm <?php echo ($data['type'] == 'lost') ? 'mất' : 'tìm thấy'; ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php echo (!empty($data['location_err'])) ? 'is-invalid' : ''; ?>" id="location" name="location" value="<?php echo $data['location']; ?>" placeholder="VD: Phòng B1-402, Khu KTX">
                                    <div class="invalid-feedback"><?php echo $data['location_err']; ?></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="lost_found_date" class="form-label">Ngày <?php echo ($data['type'] == 'lost') ? 'mất' : 'tìm thấy'; ?> <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control <?php echo (!empty($data['lost_found_date_err'])) ? 'is-invalid' : ''; ?>" id="lost_found_date" name="lost_found_date" value="<?php echo $data['lost_found_date']; ?>">
                                    <div class="invalid-feedback"><?php echo $data['lost_found_date_err']; ?></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="private_info" class="form-label">Thông tin riêng</label>
                                <textarea class="form-control" id="private_info" name="private_info" rows="3" placeholder="Thông tin chỉ hiển thị cho người có đồ vật (đặc điểm nhận dạng đặc biệt)"><?php echo $data['private_info']; ?></textarea>
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Đây là thông tin bí mật giúp xác minh đồ vật, chỉ hiển thị cho người có đồ vật thực sự.
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="card-title mb-3">Hình ảnh</h5>
                            
                            <?php if(!empty($data['images'])): ?>
                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh hiện tại</label>
                                    <div class="row g-3 mb-3">
                                        <?php foreach($data['images'] as $image): ?>
                                            <div class="col-md-4 col-6">
                                                <div class="card h-100">
                                                    <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo $image->file_path; ?>" class="card-img-top" alt="Image" style="height: 150px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="delete_image[]" value="<?php echo $image->id; ?>" id="delete_image_<?php echo $image->id; ?>">
                                                            <label class="form-check-label" for="delete_image_<?php echo $image->id; ?>">
                                                                Xóa hình ảnh này
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="images" class="form-label">Thêm hình ảnh mới</label>
                                <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Bạn có thể chọn nhiều hình ảnh cùng lúc. Kích thước tối đa: 5MB/ảnh.
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary me-md-2 px-4 py-2">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                            <a href="<?php echo URLROOT; ?>/items/show/<?php echo $data['id']; ?>" class="btn btn-outline-secondary px-4 py-2">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 