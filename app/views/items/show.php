<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item">
                        <?php if($data['item']->type == 'lost'): ?>
                            <a href="<?php echo URLROOT; ?>/items/lost" class="text-decoration-none">Đồ thất lạc</a>
                        <?php else: ?>
                            <a href="<?php echo URLROOT; ?>/items/found" class="text-decoration-none">Đồ tìm thấy</a>
                        <?php endif; ?>
                    </li>
                    <li class="breadcrumb-item active"><?php echo $data['item']->title; ?></li>
                </ol>
            </nav>
        </div>
        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $data['item']->user_id): ?>
        <div class="col-md-4 text-end">
            <a href="<?php echo URLROOT; ?>/items/edit/<?php echo $data['item']->id; ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit me-1"></i> Sửa
            </a>
            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-1"></i> Xóa
            </button>
            <a href="<?php echo URLROOT; ?>/items/mark_resolved/<?php echo $data['item']->id; ?>" class="btn btn-sm btn-outline-success">
                <i class="fas fa-check-circle me-1"></i> Đánh dấu đã trao trả
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php if($data['item']->status == 'pending'): ?>
        <div class="alert alert-warning rounded-3 shadow-sm mb-4">
            <div class="d-flex">
                <i class="fas fa-clock fa-2x me-3 text-warning"></i>
                <div>
                    <h6 class="fw-bold">Đang chờ duyệt</h6>
                    <p class="mb-0">Bài đăng này đang chờ được quản trị viên phê duyệt. Bài đăng sẽ được hiển thị công khai sau khi được duyệt.</p>
                </div>
            </div>
        </div>
    <?php elseif($data['item']->status == 'rejected'): ?>
        <div class="alert alert-danger rounded-3 shadow-sm mb-4">
            <div class="d-flex">
                <i class="fas fa-times-circle fa-2x me-3 text-danger"></i>
                <div>
                    <h6 class="fw-bold">Bài đăng bị từ chối</h6>
                    <p class="mb-0">Bài đăng này đã bị quản trị viên từ chối và không hiển thị công khai. Vui lòng kiểm tra lại nội dung hoặc liên hệ với quản trị viên để biết thêm chi tiết.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Images Section -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-0">
                    <?php if(!empty($data['images'])): ?>
                        <div id="itemImageCarousel" class="carousel slide carousel-fade" data-bs-ride="false">
                            <div class="carousel-inner rounded-top">
                                <?php foreach($data['images'] as $index => $image): ?>
                                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" style="height: 350px; background-color: #f8f9fa;">
                                        <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($image->file_path); ?>" class="d-block w-100 h-100 rounded-top" alt="<?php echo $data['item']->title; ?>" style="object-fit: contain; max-height: 350px;">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if(count($data['images']) > 1): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#itemImageCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#itemImageCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                                
                                <div class="carousel-indicators position-static m-0 pt-2 pb-0 d-flex justify-content-center flex-wrap">
                                    <?php foreach($data['images'] as $index => $image): ?>
                                        <button type="button" data-bs-target="#itemImageCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?> mx-1 mb-2" aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $index + 1; ?>" style="width: 70px; height: auto; padding: 2px;">
                                            <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($image->file_path); ?>" class="d-block w-100 img-thumbnail" style="height: 50px; object-fit: cover; width: 100%;">
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-light d-flex flex-column align-items-center justify-content-center py-5 rounded" style="height: 350px;">
                            <i class="fas fa-image fa-5x text-muted mb-3"></i>
                            <p class="text-muted">Không có hình ảnh</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Item Details -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h4 mb-0 fw-bold"><?php echo $data['item']->title; ?></h1>
                        <?php if($data['item']->type == 'lost'): ?>
                            <span class="badge bg-danger py-2 px-3">
                                <i class="fas fa-search me-1"></i> Đồ thất lạc
                            </span>
                        <?php else: ?>
                            <span class="badge bg-success py-2 px-3">
                                <i class="fas fa-hand-holding me-1"></i> Đồ tìm thấy
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark">Mô tả</h5>
                        <p class="text-secondary"><?php echo nl2br(htmlspecialchars($data['item']->description)); ?></p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <h5 class="fw-bold text-dark mb-2">Danh mục</h5>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                    <i class="fas <?php echo $data['category']->icon; ?> text-primary"></i>
                                </div>
                                <span class="text-secondary"><?php echo $data['category']->name; ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5 class="fw-bold text-dark mb-2">Địa điểm</h5>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </div>
                                <span class="text-secondary"><?php echo $data['item']->location; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <h5 class="fw-bold text-dark mb-2"><?php echo $data['item']->type == 'lost' ? 'Ngày mất' : 'Ngày tìm thấy'; ?></h5>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                    <i class="far fa-calendar-alt text-primary"></i>
                                </div>
                                <span class="text-secondary"><?php echo date('d/m/Y', strtotime($data['item']->lost_found_date)); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5 class="fw-bold text-dark mb-2">Ngày đăng</h5>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                    <i class="far fa-clock text-primary"></i>
                                </div>
                                <span class="text-secondary"><?php echo date('d/m/Y H:i', strtotime($data['item']->created_at)); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-2">Người đăng</h5>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">
                                    <a href="<?php echo URLROOT; ?>/users/profile/<?php echo $data['user']->id; ?>" class="text-decoration-none">
                                        <?php echo $data['user']->full_name ?: $data['user']->username; ?>
                                    </a>
                                </h6>
                                <div class="d-flex align-items-center">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <?php if ($i < floor(($data['user']->trust_points ?? 0) / 20)): ?>
                                            <i class="fas fa-star text-warning me-1"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-muted me-1"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="text-muted ms-1 small">(<?php echo $data['user']->trust_points ?? 0; ?> điểm)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $data['item']->user_id): ?>
                        <?php if($data['item']->status == 'active'): ?>
                            <?php if(!$data['hasClaim']): ?>
                                <div class="d-grid">
                                    <a href="<?php echo URLROOT; ?>/items/claim/<?php echo $data['item']->id; ?>" class="btn btn-primary py-2">
                                        <?php if($data['item']->type == 'lost'): ?>
                                            <i class="fas fa-hand-holding-heart me-2"></i> Tôi đã tìm thấy đồ này
                                        <?php else: ?>
                                            <i class="fas fa-hand-holding-heart me-2"></i> Đây là đồ của tôi
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info rounded-3 shadow-sm">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                                        <div>
                                            <h6 class="fw-bold">Yêu cầu đã được gửi</h6>
                                            <p class="mb-0">Bạn đã gửi yêu cầu nhận đồ vật này. Vui lòng chờ phản hồi từ người đăng.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-success rounded-3 shadow-sm">
                                <div class="d-flex">
                                    <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                                    <div>
                                        <h6 class="fw-bold">Đã trao trả</h6>
                                        <p class="mb-0">Đồ vật này đã được trao trả cho chủ sở hữu.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php elseif(!isset($_SESSION['user_id'])): ?>
                        <div class="alert alert-warning rounded-3 shadow-sm">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-circle fa-2x me-3 text-warning"></i>
                                <div>
                                    <h6 class="fw-bold">Yêu cầu đăng nhập</h6>
                                    <p class="mb-0">Vui lòng <a href="<?php echo URLROOT; ?>/users/login" class="alert-link">đăng nhập</a> để liên hệ với người đăng.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="text-center text-muted mt-4">
                        <div class="d-flex justify-content-center gap-3">
                            <span><i class="fas fa-eye me-1"></i> <?php echo $data['item']->views ?? 0; ?> lượt xem</span>
                            <span><i class="fas fa-hashtag me-1"></i> ID: <?php echo $data['item']->id; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if(!empty($data['item']->private_info) && isset($_SESSION['user_id']) && $_SESSION['user_id'] == $data['item']->user_id): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning bg-opacity-10 py-3 border-0">
                        <h5 class="mb-0 fw-bold text-warning"><i class="fas fa-lock me-2"></i> Thông tin riêng tư</h5>
                    </div>
                    <div class="card-body">
                        <p><?php echo nl2br(htmlspecialchars($data['item']->private_info)); ?></p>
                        <small class="text-muted fst-italic">(Chỉ bạn mới nhìn thấy thông tin này)</small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Contact Information Section for Claimants -->
    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $data['item']->user_id && !empty($data['claims'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="h5 mb-0 fw-bold">Những người liên hệ</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Thông tin xác nhận</th>
                                    <th>Ngày yêu cầu</th>
                                    <th>Liên hệ</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['claims'] as $claim): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/users/profile/<?php echo $claim->user_id; ?>" class="d-flex align-items-center text-decoration-none">
                                            <i class="fas fa-user-circle fa-2x me-2 text-muted"></i>
                                            <div>
                                                <span class="d-block"><?php echo $claim->username; ?></span>
                                                <div class="small">
                                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                                        <?php if ($i < floor($claim->trust_points / 20)): ?>
                                                            <i class="fas fa-star text-warning"></i>
                                                        <?php else: ?>
                                                            <i class="far fa-star text-muted"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td><?php echo nl2br(htmlspecialchars($claim->verification_info)); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($claim->created_at)); ?></td>
                                    <td>
                                        <a href="mailto:<?php echo $claim->email; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-envelope me-1"></i> Email
                                        </a>
                                        <?php if(!empty($claim->phone)): ?>
                                        <a href="tel:<?php echo $claim->phone; ?>" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-phone me-1"></i> Gọi điện
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/items/resolve/<?php echo $data['item']->id; ?>/<?php echo $claim->id; ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-check-circle me-1"></i> Xác nhận trao trả
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Related Items Section -->
    <?php if(!empty($data['relatedItems'])): ?>
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Đồ vật liên quan</h3>
            <div class="row g-4">
                <?php foreach($data['relatedItems'] as $relatedItem): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <div style="height: 160px; background-color: #f8f9fa;" class="d-flex align-items-center justify-content-center overflow-hidden">
                                <?php if(!empty($relatedItem->image)): ?>
                                    <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($relatedItem->image); ?>" class="card-img-top w-100 h-100" alt="<?php echo $relatedItem->title; ?>" style="object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                <?php endif; ?>
                            </div>
                            <?php if($relatedItem->type == 'lost'): ?>
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    <i class="fas fa-search me-1"></i> Đồ thất lạc
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                    <i class="fas fa-hand-holding me-1"></i> Đồ tìm thấy
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-3" style="min-height: 48px;"><?php echo $relatedItem->title; ?></h5>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                <small class="text-muted"><?php echo $relatedItem->location; ?></small>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-calendar text-muted me-2"></i>
                                <small class="text-muted"><?php echo date('d/m/Y', strtotime($relatedItem->lost_found_date)); ?></small>
                            </div>
                            <a href="<?php echo URLROOT; ?>/items/show/<?php echo $relatedItem->id; ?>" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-info-circle me-1"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $data['item']->user_id): ?>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle text-danger fa-3x"></i>
                    </div>
                    <div>
                        <h5 class="text-danger fw-bold">Bạn có chắc chắn muốn xóa đồ vật này?</h5>
                        <p class="mb-0">Hành động này không thể hoàn tác và tất cả thông tin liên quan sẽ bị xóa vĩnh viễn.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <a href="<?php echo URLROOT; ?>/items/delete/<?php echo $data['item']->id; ?>" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Xóa vĩnh viễn
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require APPROOT . '/views/includes/footer.php'; ?> 