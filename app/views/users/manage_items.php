<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 fw-bold">Quản lý tin đã đăng</h1>
            <p class="text-muted">Quản lý tất cả các tin bạn đã đăng trên hệ thống</p>
        </div>
        <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
            <div class="dropdown me-2">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-1"></i> Lọc
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item <?php echo !isset($_GET['status']) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/users/manage_items">Tất cả</a></li>
                    <li><a class="dropdown-item <?php echo isset($_GET['status']) && $_GET['status'] == 'active' ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/users/manage_items?status=active">Đang hoạt động</a></li>
                    <li><a class="dropdown-item <?php echo isset($_GET['status']) && $_GET['status'] == 'resolved' ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/users/manage_items?status=resolved">Đã giải quyết</a></li>
                    <li><a class="dropdown-item <?php echo isset($_GET['status']) && $_GET['status'] == 'pending' ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/users/manage_items?status=pending">Chờ duyệt</a></li>
                </ul>
            </div>
            <a href="<?php echo URLROOT; ?>/items/add_select" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Đăng tin mới
            </a>
        </div>
    </div>

    <?php 
    // Tính số lượng tin đang chờ duyệt
    $pendingCount = 0;
    foreach($data['items'] as $item) {
        if($item->status == 'pending') {
            $pendingCount++;
        }
    }
    
    // Hiển thị thông báo nếu có tin đang chờ duyệt
    if($pendingCount > 0): 
    ?>
    <div class="alert alert-info mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <h5 class="mb-1">Tin đang chờ duyệt</h5>
                <p class="mb-0">
                    Bạn có <strong><?php echo $pendingCount; ?></strong> tin đang chờ quản trị viên phê duyệt. Tin của bạn sẽ được hiển thị trên trang chủ sau khi được duyệt.
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <ul class="nav nav-tabs card-header-tabs" id="itemTypeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                        Tất cả <span class="badge bg-secondary ms-1"><?php echo count($data['items']); ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lost-tab" data-bs-toggle="tab" data-bs-target="#lost" type="button" role="tab" aria-controls="lost" aria-selected="false">
                        Đồ thất lạc <span class="badge bg-danger ms-1"><?php echo $data['lostCount']; ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="found-tab" data-bs-toggle="tab" data-bs-target="#found" type="button" role="tab" aria-controls="found" aria-selected="false">
                        Đồ tìm thấy <span class="badge bg-success ms-1"><?php echo $data['foundCount']; ?></span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="itemTypeTabsContent">
                <!-- All Items Tab -->
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <?php if (empty($data['items'])): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Bạn chưa đăng tin nào. <a href="<?php echo URLROOT; ?>/items/add_select" class="alert-link">Đăng tin mới ngay</a>.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Loại</th>
                                        <th>Ngày đăng</th>
                                        <th>Lượt xem</th>
                                        <th>Yêu cầu</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['items'] as $item): ?>
                                    <tr>
                                        <td style="max-width: 300px;">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 rounded" style="width: 50px; height: 50px; overflow: hidden; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                                    <?php if ($item->image_count > 0): ?>
                                                        <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($item->image); ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo $item->title; ?>">
                                                    <?php else: ?>
                                                        <i class="fas fa-image text-muted"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-truncate">
                                                    <h6 class="mb-0 text-truncate" style="max-width: 220px;"><?php echo $item->title; ?></h6>
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
                                        <td><span class="badge bg-light text-dark"><?php echo $item->views ?? 0; ?></span></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($item->claims_count > 0) ? 'primary' : 'light text-dark'; ?>">
                                                <?php echo $item->claims_count ?? 0; ?> yêu cầu
                                            </span>
                                        </td>
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
                                            <div class="btn-group">
                                                <a href="<?php echo URLROOT; ?>/items/show/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if($item->status == 'active'): ?>
                                                <a href="<?php echo URLROOT; ?>/items/edit/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if($item->claims_count > 0): ?>
                                                <a href="<?php echo URLROOT; ?>/items/claims/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-hands-helping"></i>
                                                </a>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $item->id; ?>" data-item-id="<?php echo $item->id; ?>" data-item-title="<?php echo htmlspecialchars($item->title); ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if (isset($data['pagination']) && $data['pagination']['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($data['pagination']['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo URLROOT; ?>/users/manage_items?page=<?php echo $data['pagination']['current_page'] - 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                    <li class="page-item <?php echo $i == $data['pagination']['current_page'] ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo URLROOT; ?>/users/manage_items?page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo URLROOT; ?>/users/manage_items?page=<?php echo $data['pagination']['current_page'] + 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Lost Items Tab -->
                <div class="tab-pane fade" id="lost" role="tabpanel" aria-labelledby="lost-tab">
                    <?php if ($data['lostCount'] == 0): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Bạn chưa đăng tin đồ thất lạc nào. <a href="<?php echo URLROOT; ?>/items/add/lost" class="alert-link">Đăng tin mới ngay</a>.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Ngày đăng</th>
                                        <th>Lượt xem</th>
                                        <th>Yêu cầu</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['items'] as $item): ?>
                                    <?php if($item->type == 'lost'): ?>
                                    <tr>
                                        <td style="max-width: 300px;">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 rounded" style="width: 50px; height: 50px; overflow: hidden; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                                    <?php if ($item->image_count > 0): ?>
                                                        <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($item->image); ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo $item->title; ?>">
                                                    <?php else: ?>
                                                        <i class="fas fa-image text-muted"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-truncate">
                                                    <h6 class="mb-0 text-truncate" style="max-width: 220px;"><?php echo $item->title; ?></h6>
                                                    <small class="text-muted"><?php echo $item->category_name; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><small><?php echo date('d/m/Y', strtotime($item->created_at)); ?></small></td>
                                        <td><span class="badge bg-light text-dark"><?php echo $item->views ?? 0; ?></span></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($item->claims_count > 0) ? 'primary' : 'light text-dark'; ?>">
                                                <?php echo $item->claims_count ?? 0; ?> yêu cầu
                                            </span>
                                        </td>
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
                                            <div class="btn-group">
                                                <a href="<?php echo URLROOT; ?>/items/show/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if($item->status == 'active'): ?>
                                                <a href="<?php echo URLROOT; ?>/items/edit/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if($item->claims_count > 0): ?>
                                                <a href="<?php echo URLROOT; ?>/items/claims/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-hands-helping"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Found Items Tab -->
                <div class="tab-pane fade" id="found" role="tabpanel" aria-labelledby="found-tab">
                    <?php if ($data['foundCount'] == 0): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Bạn chưa đăng tin đồ tìm thấy nào. <a href="<?php echo URLROOT; ?>/items/add/found" class="alert-link">Đăng tin mới ngay</a>.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Ngày đăng</th>
                                        <th>Lượt xem</th>
                                        <th>Yêu cầu</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['items'] as $item): ?>
                                    <?php if($item->type == 'found'): ?>
                                    <tr>
                                        <td style="max-width: 300px;">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 rounded" style="width: 50px; height: 50px; overflow: hidden; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                                    <?php if ($item->image_count > 0): ?>
                                                        <img src="<?php echo URLROOT; ?>/uploads/items/<?php echo basename($item->image); ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo $item->title; ?>">
                                                    <?php else: ?>
                                                        <i class="fas fa-image text-muted"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-truncate">
                                                    <h6 class="mb-0 text-truncate" style="max-width: 220px;"><?php echo $item->title; ?></h6>
                                                    <small class="text-muted"><?php echo $item->category_name; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><small><?php echo date('d/m/Y', strtotime($item->created_at)); ?></small></td>
                                        <td><span class="badge bg-light text-dark"><?php echo $item->views ?? 0; ?></span></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($item->claims_count > 0) ? 'primary' : 'light text-dark'; ?>">
                                                <?php echo $item->claims_count ?? 0; ?> yêu cầu
                                            </span>
                                        </td>
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
                                            <div class="btn-group">
                                                <a href="<?php echo URLROOT; ?>/items/show/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if($item->status == 'active'): ?>
                                                <a href="<?php echo URLROOT; ?>/items/edit/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if($item->claims_count > 0): ?>
                                                <a href="<?php echo URLROOT; ?>/items/claims/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-hands-helping"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
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

<!-- Single Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa tin này không? Hành động này không thể hoàn tác.</p>
                <p class="fw-bold" id="deleteItemTitle"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteItemForm" method="post" action="">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // URL gốc của trang web
    const URLROOT = '<?php echo URLROOT; ?>';
    
    // Data: Lưu thông tin các items để sử dụng trong modal
    const itemsData = <?php echo json_encode(array_map(function($item) {
        return [
            'id' => $item->id,
            'title' => $item->title
        ];
    }, $data['items'] ?? [])); ?>;
    
    // Delete Modal
    const deleteButtons = document.querySelectorAll('button[data-bs-toggle="modal"][data-bs-target^="#deleteModal"]');
    
    deleteButtons.forEach(button => {
        // Đổi target của button để trỏ đến modal duy nhất
        button.setAttribute('data-bs-target', '#deleteModal');
        
        // Thêm event listener
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const itemTitle = this.getAttribute('data-item-title');
            
            console.log('Modal clicked:', itemId, itemTitle);
            
            // Populate modal content
            document.getElementById('deleteItemTitle').textContent = `"${itemTitle}"`;
            document.getElementById('deleteItemForm').action = `${URLROOT}/items/delete/${itemId}`;
        });
    });
});
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?> 