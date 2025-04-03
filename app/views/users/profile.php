<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Thông tin cá nhân -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="bg-primary text-white p-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x"></i>
                    </div>
                    <h3 class="fw-bold mb-1"><?php echo $data['user']->full_name ?: $data['user']->username; ?></h3>
                    <p class="mb-0">Thành viên từ <?php echo date('d/m/Y', strtotime($data['user']->created_at)); ?></p>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="text-center">
                            <div class="d-flex mb-2 justify-content-center">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <?php if ($i < floor(($data['user']->trust_points ?? 0) / 20)): ?>
                                        <i class="fas fa-star text-warning mx-1"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-muted mx-1"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <p class="text-muted"><?php echo $data['user']->trust_points ?? 0; ?> điểm uy tín</p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i> Thông tin cá nhân</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-2 d-flex">
                            <span class="text-muted w-40"><i class="fas fa-envelope me-2"></i> Email:</span>
                            <span class="text-break"><?php echo $data['user']->email; ?></span>
                        </li>
                        <?php if (!empty($data['user']->phone)): ?>
                        <li class="list-group-item px-0 py-2 d-flex">
                            <span class="text-muted w-40"><i class="fas fa-phone me-2"></i> SĐT:</span>
                            <span><?php echo $data['user']->phone; ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($data['user']->faculty)): ?>
                        <li class="list-group-item px-0 py-2 d-flex">
                            <span class="text-muted w-40"><i class="fas fa-university me-2"></i> Khoa:</span>
                            <span><?php echo $data['user']->faculty; ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($data['user']->class)): ?>
                        <li class="list-group-item px-0 py-2 d-flex">
                            <span class="text-muted w-40"><i class="fas fa-users me-2"></i> Lớp:</span>
                            <span><?php echo $data['user']->class; ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($data['user']->student_id)): ?>
                        <li class="list-group-item px-0 py-2 d-flex">
                            <span class="text-muted w-40"><i class="fas fa-id-card me-2"></i> Mã SV:</span>
                            <span><?php echo $data['user']->student_id; ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $data['user']->id): ?>
                    <div class="d-grid gap-2 mt-4">
                        <a href="<?php echo URLROOT; ?>/users/edit" class="btn btn-outline-primary">
                            <i class="fas fa-user-edit me-2"></i>Chỉnh sửa hồ sơ
                        </a>
                        <a href="<?php echo URLROOT; ?>/users/change_password" class="btn btn-outline-secondary">
                            <i class="fas fa-key me-2"></i>Đổi mật khẩu
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Hoạt động và tin đã đăng -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">Thống kê hoạt động</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                                <div class="mb-2"><i class="fas fa-clipboard-list fa-2x text-primary"></i></div>
                                <h5 class="fw-bold"><?php echo count($data['items']); ?></h5>
                                <p class="mb-0 text-muted">Tin đã đăng</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="rounded-3 bg-success bg-opacity-10 p-3">
                                <div class="mb-2"><i class="fas fa-handshake fa-2x text-success"></i></div>
                                <h5 class="fw-bold"><?php 
                                    echo isset($data['resolvedCount']) ? $data['resolvedCount'] : '0'; 
                                ?></h5>
                                <p class="mb-0 text-muted">Đã giải quyết</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-3 bg-info bg-opacity-10 p-3">
                                <div class="mb-2"><i class="fas fa-star fa-2x text-info"></i></div>
                                <h5 class="fw-bold"><?php echo $data['user']->trust_points ?? 0; ?></h5>
                                <p class="mb-0 text-muted">Điểm uy tín</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tin đã đăng -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">Tin đã đăng</h4>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $data['user']->id): ?>
                        <a href="<?php echo URLROOT; ?>/users/manage_items" class="btn btn-primary btn-sm">
                            <i class="fas fa-tasks me-1"></i> Quản lý tin
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($data['items'])): ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Chưa có tin nào được đăng.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Loại</th>
                                        <th>Ngày đăng</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 rounded" style="width: 45px; height: 45px; overflow: hidden; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                                    <?php if (isset($item->image) && $item->image): ?>
                                                        <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($item->image); ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo $item->title; ?>">
                                                    <?php else: ?>
                                                        <i class="fas fa-image text-muted"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo $item->title; ?></h6>
                                                    <small class="text-muted"><?php echo $item->category_name; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($item->type == 'lost'): ?>
                                                <span class="badge bg-danger">Đồ thất lạc</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Đồ tìm thấy</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><small><?php echo date('d/m/Y', strtotime($item->created_at)); ?></small></td>
                                        <td>
                                            <?php if($item->status == 'active'): ?>
                                                <span class="badge bg-primary">Đang hoạt động</span>
                                            <?php elseif($item->status == 'pending'): ?>
                                                <span class="badge bg-warning">Chờ duyệt</span>
                                            <?php elseif($item->status == 'resolved'): ?>
                                                <span class="badge bg-success">Đã giải quyết</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Khác</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/items/show/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $data['user']->id && $item->status == 'active'): ?>
                                            <a href="<?php echo URLROOT; ?>/items/edit/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 