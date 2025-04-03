<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-5">
                <div class="card-header bg-<?= $data['type'] == 'lost' ? 'primary' : 'success' ?> text-white">
                    <h1 class="h4 mb-0">
                        <i class="fas fa-<?= $data['type'] == 'lost' ? 'search' : 'hand-holding' ?>"></i> 
                        <?= $data['title'] ?>
                    </h1>
                </div>
                <div class="card-body">
                    <form action="<?= URLROOT ?>/items/add/<?= $data['type'] ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= !empty($data['title_err']) ? 'is-invalid' : '' ?>" 
                                id="title" name="title" value="<?= $data['title_item'] ?>" placeholder="Nhập tiêu đề mô tả ngắn gọn đồ vật">
                            <div class="invalid-feedback"><?= $data['title_err'] ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select <?= !empty($data['category_id_err']) ? 'is-invalid' : '' ?>" 
                                id="category_id" name="category_id">
                                <option value="">Chọn danh mục</option>
                                <?php foreach($data['categories'] as $category): ?>
                                    <option value="<?= $category->id ?>" <?= $data['category_id'] == $category->id ? 'selected' : '' ?>>
                                        <?= $category->name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= $data['category_id_err'] ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control <?= !empty($data['description_err']) ? 'is-invalid' : '' ?>" 
                                id="description" name="description" rows="5" 
                                placeholder="Mô tả chi tiết về đồ vật: màu sắc, kích thước, đặc điểm nhận dạng..."><?= $data['description'] ?></textarea>
                            <div class="invalid-feedback"><?= $data['description_err'] ?></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="location" class="form-label">Địa điểm <?= $data['type'] == 'lost' ? 'mất' : 'tìm thấy' ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= !empty($data['location_err']) ? 'is-invalid' : '' ?>" 
                                    id="location" name="location" value="<?= $data['location'] ?>" 
                                    placeholder="Ví dụ: Tòa nhà A, Phòng 201,...">
                                <div class="invalid-feedback"><?= $data['location_err'] ?></div>
                            </div>
                            <div class="col-md-6">
                                <label for="lost_found_date" class="form-label">Ngày <?= $data['type'] == 'lost' ? 'mất' : 'tìm thấy' ?> <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?= !empty($data['lost_found_date_err']) ? 'is-invalid' : '' ?>" 
                                    id="lost_found_date" name="lost_found_date" value="<?= $data['lost_found_date'] ?>">
                                <div class="invalid-feedback"><?= $data['lost_found_date_err'] ?></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="images" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control <?= !empty($data['images_err']) ? 'is-invalid' : '' ?>" 
                                id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">Tối đa 5 hình ảnh, mỗi ảnh không quá 5MB. Hỗ trợ định dạng: JPG, PNG, GIF.</div>
                            <div class="invalid-feedback"><?= $data['images_err'] ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="private_info" class="form-label">Thông tin riêng tư (tùy chọn)</label>
                            <textarea class="form-control" id="private_info" name="private_info" rows="3" 
                                placeholder="Nhập thông tin riêng tư về đồ vật mà chỉ bạn mới biết. Người nhận lại đồ sẽ cần xác minh các thông tin này."><?= $data['private_info'] ?></textarea>
                            <div class="form-text">Thông tin này chỉ bạn mới nhìn thấy và dùng để xác minh chủ sở hữu thực sự.</div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Lưu ý: Tin đăng sẽ tự động hết hạn sau 30 ngày.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= URLROOT ?>/items/<?= $data['type'] ?>" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-<?= $data['type'] == 'lost' ? 'primary' : 'success' ?>">
                                <i class="fas fa-paper-plane"></i> Đăng tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview uploaded images script -->
<script>
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.createElement('div');
        previewContainer.classList.add('mt-3', 'row');
        previewContainer.id = 'imagePreviewContainer';
        
        // Remove any existing preview
        const existingPreview = document.getElementById('imagePreviewContainer');
        if (existingPreview) {
            existingPreview.remove();
        }
        
        const files = event.target.files;
        
        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.classList.add('col-md-3', 'mb-2');
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-thumbnail');
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        
                        col.appendChild(img);
                        previewContainer.appendChild(col);
                    }
                    
                    reader.readAsDataURL(file);
                }
            }
            
            this.parentNode.appendChild(previewContainer);
        }
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?> 