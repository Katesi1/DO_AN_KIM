<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">Tìm kiếm & Kết nối</h1>
                <p class="lead mb-4">Nền tảng giúp sinh viên, giảng viên, và nhân viên ĐH Phương Đông tìm kiếm hoặc thông báo về đồ vật thất lạc trong khuôn viên trường.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?php echo URLROOT; ?>/items/add_select" class="btn btn-light btn-lg">
                        <i class="fas fa-plus-circle me-2"></i> Đăng tin ngay
                    </a>
                    <a href="<?php echo URLROOT; ?>/items/lost" class="btn btn-outline-light btn-lg" id="searchButton">
                        <i class="fas fa-search me-2"></i> Tìm kiếm
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg p-4">
                    <div class="card-body p-0">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="p-3">
                                    <h3 class="fw-bold text-primary"><?php echo $data['totalLost']; ?></h3>
                                    <p class="text-muted small mb-0">Đồ thất lạc</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3">
                                    <h3 class="fw-bold text-success"><?php echo $data['totalFound']; ?></h3>
                                    <p class="text-muted small mb-0">Đồ tìm thấy</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3">
                                    <h3 class="fw-bold text-info"><?php echo $data['totalResolved']; ?></h3>
                                    <p class="text-muted small mb-0">Đã trao trả</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Danh mục phổ biến -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-th-large text-primary me-2"></i> Danh mục phổ biến</h2>
            <a href="<?php echo URLROOT; ?>/items/categories" class="text-decoration-none text-primary">
                Xem tất cả <i class="fas fa-angle-right"></i>
            </a>
        </div>
        <div class="row g-3">
            <?php foreach ($data['categories'] as $category) : ?>
                <div class="col-md-2 col-sm-4 col-6">
                    <a href="<?php echo URLROOT; ?>/items/lost?category=<?php echo $category->id; ?>" class="text-decoration-none">
                        <div class="card bg-white h-100 text-center">
                            <div class="card-body py-4">
                                <div class="icon-wrapper mb-3">
                                    <i class="fas <?php echo $category->icon; ?> fa-2x text-primary"></i>
                                </div>
                                <h6 class="card-title mb-1 text-dark"><?php echo $category->name; ?></h6>
                                <small class="text-muted">(<?php echo $category->item_count; ?>)</small>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Đồ vật thất lạc gần đây -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-search text-danger me-2"></i> Đồ vật thất lạc gần đây</h2>
            <a href="<?php echo URLROOT; ?>/items/lost" class="text-decoration-none text-primary">
                Xem tất cả <i class="fas fa-angle-right"></i>
            </a>
        </div>
        <div class="row g-4">
            <?php if (empty($data['lostItems'])) : ?>
                <div class="col-12">
                    <div class="alert alert-info rounded-3">Không có đồ vật thất lạc nào được đăng gần đây.</div>
                </div>
            <?php else : ?>
                <?php foreach ($data['lostItems'] as $item) : ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100">
                            <div class="position-relative">
                                <div class="card-img-top bg-light" style="height: 180px; overflow: hidden;">
                                    <?php if (isset($item->image) && $item->image) : ?>
                                        <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($item->image->file_path); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo $item->title; ?>">
                                    <?php else : ?>
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2 px-2 py-1">
                                    <i class="fas fa-search me-1"></i> Thất lạc
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-2" style="height: 48px; overflow: hidden;">
                                    <?php echo substr($item->title, 0, 45) . (strlen($item->title) > 45 ? '...' : ''); ?>
                                </h5>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-secondary me-2"></i>
                                    <small class="text-muted text-truncate"><?php echo $item->location; ?></small>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-calendar-alt text-secondary me-2"></i>
                                    <small class="text-muted"><?php echo date('d/m/Y', strtotime($item->lost_found_date)); ?></small>
                                </div>
                                <a href="<?php echo URLROOT; ?>/items/show/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-info-circle me-1"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Đồ vật tìm thấy gần đây -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-hand-holding text-success me-2"></i> Đồ vật tìm thấy gần đây</h2>
            <a href="<?php echo URLROOT; ?>/items/found" class="text-decoration-none text-primary">
                Xem tất cả <i class="fas fa-angle-right"></i>
            </a>
        </div>
        <div class="row g-4">
            <?php if (empty($data['foundItems'])) : ?>
                <div class="col-12">
                    <div class="alert alert-info rounded-3">Không có đồ vật tìm thấy nào được đăng gần đây.</div>
                </div>
            <?php else : ?>
                <?php foreach ($data['foundItems'] as $item) : ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100">
                            <div class="position-relative">
                                <div class="card-img-top bg-light" style="height: 180px; overflow: hidden;">
                                    <?php if (isset($item->image) && $item->image) : ?>
                                        <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($item->image->file_path); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo $item->title; ?>">
                                    <?php else : ?>
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-success position-absolute top-0 end-0 m-2 px-2 py-1">
                                    <i class="fas fa-hand-holding me-1"></i> Tìm thấy
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-2" style="height: 48px; overflow: hidden;">
                                    <?php echo substr($item->title, 0, 45) . (strlen($item->title) > 45 ? '...' : ''); ?>
                                </h5>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-secondary me-2"></i>
                                    <small class="text-muted text-truncate"><?php echo $item->location; ?></small>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-calendar-alt text-secondary me-2"></i>
                                    <small class="text-muted"><?php echo date('d/m/Y', strtotime($item->lost_found_date)); ?></small>
                                </div>
                                <a href="<?php echo URLROOT; ?>/items/show/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-info-circle me-1"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Đồ vật đã được trao trả gần đây -->
    <?php if (!empty($data['recentResolved'])) : ?>
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-check-circle text-info me-2"></i> Đồ vật đã được trao trả</h2>
            <a href="<?php echo URLROOT; ?>/items/resolved" class="text-decoration-none text-primary">
                Xem tất cả <i class="fas fa-angle-right"></i>
            </a>
        </div>
        <div class="row g-3">
            <?php foreach ($data['recentResolved'] as $item) : ?>
                <div class="col-md-4">
                    <div class="card bg-white border-0 h-100">
                        <div class="card-body d-flex">
                            <div class="me-3" style="min-width: 80px; height: 80px; overflow: hidden;">
                                <?php if (isset($item->image) && $item->image) : ?>
                                    <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($item->image->file_path); ?>" class="img-fluid rounded" alt="<?php echo $item->title; ?>" style="height: 100%; object-fit: cover;">
                                <?php else : ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <span class="badge bg-info mb-2">
                                    <?php echo $item->type == 'lost' ? 'Đã tìm thấy' : 'Đã trả lại'; ?>
                                </span>
                                <h6 class="card-title"><?php echo $item->title; ?></h6>
                                <p class="card-text small text-muted mb-0"><?php echo substr($item->description, 0, 60) . '...'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Thành viên tích cực -->
    <?php if (!empty($data['topUsers'])) : ?>
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-users text-primary me-2"></i> Thành viên tích cực</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($data['topUsers'] as $user) : ?>
                <div class="col-md-2 col-sm-4 col-6 text-center">
                    <div class="card bg-white border-0 h-100">
                        <div class="card-body">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user-circle fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title"><?php echo $user->username; ?></h5>
                            <div class="d-flex justify-content-center gap-1 mb-2">
                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                    <?php if ($i < floor($user->trust_points / 20)) : ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else : ?>
                                        <i class="far fa-star text-muted"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <p class="mb-0"><span class="badge bg-light text-primary"><?php echo $user->items_count; ?> đồ vật</span></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Hướng dẫn sử dụng -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-info-circle text-primary me-2"></i> Hướng dẫn sử dụng</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-plus-circle fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title">Đăng tin</h4>
                        <p class="card-text text-muted">Đăng thông tin về đồ vật bạn đã đánh mất hoặc nhặt được trong khuôn viên trường.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-search fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title">Tìm kiếm</h4>
                        <p class="card-text text-muted">Tìm kiếm thông tin về đồ vật đã mất hoặc được tìm thấy theo nhiều tiêu chí khác nhau.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-handshake fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title">Kết nối</h4>
                        <p class="card-text text-muted">Liên hệ với người đăng tin và sắp xếp thời gian, địa điểm để nhận lại đồ.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút tìm kiếm
    const searchButton = document.getElementById('searchButton');
    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '<?php echo URLROOT; ?>/items/lost';
            // Lưu trạng thái để focus vào ô tìm kiếm
            sessionStorage.setItem('focusSearch', 'true');
        });
    }
});
</script> 