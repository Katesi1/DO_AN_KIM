<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Quản lý bài viết</h1>
                <div>
                    <a href="<?php echo URLROOT; ?>/admin" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại dashboard
                    </a>
                    <a href="<?php echo URLROOT; ?>" class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i>Trang chủ
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
                            <a class="nav-link active" href="<?php echo URLROOT; ?>/admin/items">
                                <i class="fas fa-clipboard-check me-2"></i>Bài chờ duyệt
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/allItems">
                                <i class="fas fa-list-alt me-2"></i>Tất cả bài đăng
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Bài viết chờ duyệt</h5>
                        <span class="badge bg-warning text-dark"><?php echo count($data['items']); ?> bài viết</span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(empty($data['items'])): ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Không có bài viết nào đang chờ duyệt.
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
                                        <td><?php echo date('d/m/Y H:i', strtotime($item->created_at)); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#previewModal<?php echo $item->id; ?>">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/admin/approveItem/<?php echo $item->id; ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Duyệt
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/admin/rejectItem/<?php echo $item->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn từ chối bài viết này?');">
                                                    <i class="fas fa-times"></i> Từ chối
                                                </a>
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
                                    <a class="page-link" href="<?php echo URLROOT; ?>/admin/items/<?php echo $data['current_page']-1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $data['total_pages']; $i++): ?>
                                <li class="page-item <?php echo ($i == $data['current_page']) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo URLROOT; ?>/admin/items/<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <?php if($data['current_page'] < $data['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo URLROOT; ?>/admin/items/<?php echo $data['current_page']+1; ?>" aria-label="Next">
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
</div>

<!-- Single Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Xem chi tiết bài viết</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewModalBody">
                <!-- Nội dung sẽ được cập nhật bằng JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <a href="#" id="approveItemBtn" class="btn btn-success">
                    <i class="fas fa-check"></i> Duyệt
                </a>
                <a href="#" id="rejectItemBtn" class="btn btn-danger">
                    <i class="fas fa-times"></i> Từ chối
                </a>
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
            'description' => $item->description,
            'location' => $item->location,
            'lost_found_date' => $item->lost_found_date
        ];
    }, $data['items'])); ?>;
    
    // Preview Modal
    const previewButtons = document.querySelectorAll('a[data-bs-toggle="modal"][data-bs-target^="#previewModal"]');
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
                
                <hr>
                
                <div class="mb-3">
                    <h6>Mô tả:</h6>
                    <div class="card-text">
                        ${item.description}
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6>Thông tin liên hệ:</h6>
                    <p><strong>Địa điểm:</strong> ${item.location}</p>
                    <p><strong>Thời gian:</strong> ${new Date(item.lost_found_date).toLocaleDateString('vi-VN')}</p>
                </div>`;
            
            document.getElementById('previewModalBody').innerHTML = content;
            
            // Cập nhật URL cho các nút hành động
            document.getElementById('approveItemBtn').href = `${URLROOT}/admin/approveItem/${itemId}`;
            document.getElementById('rejectItemBtn').href = `${URLROOT}/admin/rejectItem/${itemId}`;
        });
    });
});
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?> 