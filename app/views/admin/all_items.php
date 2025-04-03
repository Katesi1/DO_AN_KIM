<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Quản lý tất cả bài đăng</h1>
                <div>
                    <a href="<?php echo URLROOT; ?>/admin" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại dashboard
                    </a>
                    <a href="<?php echo URLROOT; ?>/admin/items" class="btn btn-warning me-2">
                        <i class="fas fa-clipboard-list me-2"></i>Bài chờ duyệt
                    </a>
                </div>
            </div>
            
            <?php flash('admin_message'); ?>
            <?php flash('admin_error'); ?>
            
            <!-- Admin Navigation -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-0">
                    <ul class="nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/admin">
                                <i class="fas fa-tachometer-alt me-2"></i>Tổng quan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/users">
                                <i class="fas fa-users me-2"></i>Người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/items">
                                <i class="fas fa-clipboard-check me-2"></i>Bài chờ duyệt
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="<?php echo URLROOT; ?>/admin/allItems">
                                <i class="fas fa-list-alt me-2"></i>Tất cả bài đăng
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách tất cả bài đăng</h5>
                        <div class="d-flex">
                            <span class="badge bg-primary me-2"><?php echo count($data['items']); ?> bài viết</span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="itemFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> 
                                    <?php 
                                    $current_status = isset($_GET['status']) ? $_GET['status'] : 'all';
                                    if($current_status == 'active') {
                                        echo 'Đang hoạt động';
                                    } elseif($current_status == 'pending') {
                                        echo 'Chờ duyệt';
                                    } elseif($current_status == 'resolved') {
                                        echo 'Đã giải quyết';
                                    } elseif($current_status == 'rejected') {
                                        echo 'Đã từ chối';
                                    } else {
                                        echo 'Tất cả';
                                    }
                                    ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="itemFilterDropdown">
                                    <li><a class="dropdown-item <?php echo (!isset($_GET['status'])) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/admin/allItems">Tất cả</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/admin/allItems?status=pending">Chờ duyệt</a></li>
                                    <li><a class="dropdown-item <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/admin/allItems?status=active">Đang hoạt động</a></li>
                                    <li><a class="dropdown-item <?php echo (isset($_GET['status']) && $_GET['status'] == 'resolved') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/admin/allItems?status=resolved">Đã giải quyết</a></li>
                                    <li><a class="dropdown-item <?php echo (isset($_GET['status']) && $_GET['status'] == 'rejected') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/admin/allItems?status=rejected">Đã từ chối</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(empty($data['items'])): ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Không có bài viết nào trong hệ thống.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tiêu đề</th>
                                        <th scope="col">Loại</th>
                                        <th scope="col">Danh mục</th>
                                        <th scope="col">Người đăng</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['items'] as $item): ?>
                                    <tr>
                                        <td><?php echo $item->id; ?></td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/items/show/<?php echo $item->id; ?>" target="_blank" class="text-decoration-none">
                                                <?php echo $item->title; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo ($item->type == 'lost') ? 'bg-danger' : 'bg-success'; ?>">
                                                <?php echo ($item->type == 'lost') ? 'Mất đồ' : 'Nhặt được'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $item->category_name; ?></td>
                                        <td><?php echo $item->user_name; ?></td>
                                        <td>
                                            <?php if($item->status == 'active'): ?>
                                                <span class="badge bg-success">Đang hoạt động</span>
                                            <?php elseif($item->status == 'pending'): ?>
                                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                            <?php elseif($item->status == 'resolved'): ?>
                                                <span class="badge bg-info">Đã giải quyết</span>
                                            <?php elseif($item->status == 'rejected'): ?>
                                                <span class="badge bg-danger">Đã từ chối</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Khác</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($item->created_at)); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#previewModal<?php echo $item->id; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if($item->status == 'pending'): ?>
                                                <a href="<?php echo URLROOT; ?>/admin/approveItem/<?php echo $item->id; ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/admin/rejectItem/<?php echo $item->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn từ chối bài viết này?');">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                                <?php elseif($item->status == 'active'): ?>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#changeStatusModal<?php echo $item->id; ?>">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $item->id; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>                                    
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if($data['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if($data['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo URLROOT; ?>/admin/allItems?page=<?php echo $data['current_page']-1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $data['total_pages']; $i++): ?>
                                <li class="page-item <?php echo ($i == $data['current_page']) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo URLROOT; ?>/admin/allItems?page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <?php if($data['current_page'] < $data['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo URLROOT; ?>/admin/allItems?page=<?php echo $data['current_page']+1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modals -->
    <?php foreach($data['items'] as $item): ?>
    <div class="modal fade" id="deleteModal<?php echo $item->id; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $item->id; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="deleteModalLabel<?php echo $item->id; ?>">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa bài viết này?</p>
                    <p class="text-danger mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Hành động này không thể hoàn tác!
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form action="<?php echo URLROOT; ?>/admin/deleteItem/<?php echo $item->id; ?>" method="POST" style="display:inline;">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                            <i class="fas fa-trash me-2"></i>Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Single Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="previewModalLabel">Xem chi tiết bài viết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="previewModalBody">
                    <!-- Nội dung sẽ được cập nhật bằng JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Single Change Status Modal -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="changeStatusModalLabel">Thay đổi trạng thái</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="changeStatusForm" method="POST" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Trạng thái mới</label>
                            <select class="form-select" id="statusSelect" name="status" required>
                                <option value="active">Đang hoạt động</option>
                                <option value="resolved">Đã giải quyết</option>
                                <option value="rejected">Đã từ chối</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Single Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="deleteModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa bài viết này?</p>
                    <p class="text-danger mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Hành động này không thể hoàn tác!
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" action="" style="display:inline;">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chuẩn bị dữ liệu cho modal
document.addEventListener('DOMContentLoaded', function() {
    // URL gốc của trang web
    const URLROOT = '<?php echo URLROOT; ?>';
    
    // Data: Lưu thông tin các items để sử dụng trong modal
    const itemsData = <?php echo json_encode(array_map(function($item) {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'type' => $item->type,
            'category_name' => $item->category_name,
            'user_name' => $item->user_name,
            'created_at' => $item->created_at,
            'status' => $item->status,
            'views' => $item->views,
            'description' => $item->description,
            'image' => $item->image ?? ''
        ];
    }, $data['items'])); ?>;
    
    // Preview Modal
    const previewButtons = document.querySelectorAll('button[data-bs-toggle="modal"][data-bs-target^="#previewModal"]');
    previewButtons.forEach(button => {
        button.setAttribute('data-bs-target', '#previewModal');
        const itemId = button.closest('tr').querySelector('td:first-child').textContent;
        button.setAttribute('data-item-id', itemId);
        
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const item = itemsData.find(i => i.id == itemId);
            if (!item) return;
            
            document.getElementById('previewModalLabel').textContent = item.title;
            
            let content = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Loại:</strong> 
                        <span class="badge ${item.type === 'lost' ? 'bg-danger' : 'bg-success'}">
                            ${item.type === 'lost' ? 'Mất đồ' : 'Nhặt được'}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Danh mục:</strong> ${item.category_name}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Người đăng:</strong> ${item.user_name}
                    </div>
                    <div class="col-md-6">
                        <strong>Ngày đăng:</strong> ${new Date(item.created_at).toLocaleString('vi-VN')}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Trạng thái:</strong> 
                        <span class="badge ${
                            item.status === 'active' ? 'bg-success' : 
                            item.status === 'pending' ? 'bg-warning text-dark' :
                            item.status === 'resolved' ? 'bg-info' : 'bg-danger'
                        }">
                            ${
                                item.status === 'active' ? 'Đang hoạt động' : 
                                item.status === 'pending' ? 'Chờ duyệt' :
                                item.status === 'resolved' ? 'Đã giải quyết' : 'Đã từ chối'
                            }
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Lượt xem:</strong> ${item.views}
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <h6>Mô tả:</h6>
                    <div class="card-text">
                        ${item.description}
                    </div>
                </div>`;
                
            if (item.image) {
                content += `
                    <div class="mb-3">
                        <h6>Hình ảnh:</h6>
                        <img src="${URLROOT}/uploads/items/${item.image}" 
                             alt="${item.title}" 
                             class="img-fluid rounded">
                    </div>`;
            }
            
            document.getElementById('previewModalBody').innerHTML = content;
        });
    });
    
    // Change Status Modal
    const changeStatusButtons = document.querySelectorAll('button[data-bs-toggle="modal"][data-bs-target^="#changeStatusModal"]');
    changeStatusButtons.forEach(button => {
        button.setAttribute('data-bs-target', '#changeStatusModal');
        const itemId = button.closest('tr').querySelector('td:first-child').textContent;
        button.setAttribute('data-item-id', itemId);
        
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const item = itemsData.find(i => i.id == itemId);
            if (!item) return;
            
            document.getElementById('changeStatusForm').action = `${URLROOT}/admin/changeItemStatus/${itemId}`;
            
            // Thiết lập trạng thái hiện tại
            const statusSelect = document.getElementById('statusSelect');
            for (let i = 0; i < statusSelect.options.length; i++) {
                if (statusSelect.options[i].value === item.status) {
                    statusSelect.options[i].selected = true;
                    break;
                }
            }
        });
    });
    
    // Delete Modal
    const deleteButtons = document.querySelectorAll('button[data-bs-toggle="modal"][data-bs-target^="#deleteModal"]');
    deleteButtons.forEach(button => {
        button.setAttribute('data-bs-target', '#deleteModal');
        const itemId = button.closest('tr').querySelector('td:first-child').textContent;
        button.setAttribute('data-item-id', itemId);
        
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            document.getElementById('deleteForm').action = `${URLROOT}/admin/deleteItem/${itemId}`;
        });
    });
});
</script>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 