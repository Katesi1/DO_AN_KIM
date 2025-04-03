<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Quản lý người dùng</h1>
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
                            <a class="nav-link active" href="<?php echo URLROOT; ?>/admin/users">
                                <i class="fas fa-users me-2"></i>Người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/items">
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
                        <h5 class="mb-0">Danh sách người dùng</h5>
                        <span class="badge bg-primary"><?php echo count($data['users']); ?> người dùng</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên người dùng</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Vai trò</th>
                                    <th scope="col">Ngày đăng ký</th>
                                    <th scope="col">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['users'] as $user): ?>
                                <tr>
                                    <td><?php echo $user->id; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2 bg-primary text-white rounded-circle">
                                                <?php echo strtoupper(substr($user->username, 0, 1)); ?>
                                            </div>
                                            <?php echo $user->username; ?>
                                        </div>
                                    </td>
                                    <td><?php echo $user->email; ?></td>
                                    <td>
                                        <span class="badge <?php echo ($user->role_name == 'Admin') ? 'bg-danger' : (($user->role_name == 'Moderator') ? 'bg-warning' : 'bg-info'); ?>">
                                            <?php echo $user->role_name; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($user->created_at)); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal<?php echo $user->id; ?>" data-user-id="<?php echo $user->id; ?>" data-username="<?php echo htmlspecialchars($user->username); ?>" data-email="<?php echo htmlspecialchars($user->email); ?>" data-role-id="<?php echo $user->role_id; ?>">
                                            <i class="fas fa-user-cog"></i> Phân quyền
                                        </button>
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
                                <a class="page-link" href="<?php echo URLROOT; ?>/admin/users/<?php echo $data['current_page']-1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $data['total_pages']; $i++): ?>
                            <li class="page-item <?php echo ($i == $data['current_page']) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo URLROOT; ?>/admin/users/<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if($data['current_page'] < $data['total_pages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo URLROOT; ?>/admin/users/<?php echo $data['current_page']+1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Single Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Phân quyền người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm" action="<?php echo URLROOT; ?>/admin/updateUserRole" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="user_id" value="">
                    <div class="mb-3">
                        <label for="editUserName" class="form-label">Tên người dùng</label>
                        <input type="text" class="form-control" id="editUserName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editUserEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editUserRole" class="form-label">Vai trò</label>
                        <select class="form-select" id="editUserRole" name="role_id">
                            <?php foreach($data['roles'] as $role): ?>
                                <option value="<?php echo $role->id; ?>"><?php echo $role->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small id="roleWarning" class="text-danger d-none">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Không thể thay đổi quyền này
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="saveRoleButton">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data: Lưu thông tin các users để sử dụng trong modal
    const usersData = <?php echo json_encode(array_map(function($user) {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role_id' => $user->role_id
        ];
    }, $data['users'])); ?>;
    
    // Lấy ID của người dùng hiện tại (admin đang đăng nhập)
    const currentUserId = <?php echo $_SESSION['user_id']; ?>;
    
    // Lấy ID vai trò Admin từ dữ liệu
    let adminRoleId = null;
    <?php foreach($data['roles'] as $role): ?>
        <?php if(strtolower($role->name) === 'admin'): ?>
            adminRoleId = <?php echo $role->id; ?>;
        <?php endif; ?>
    <?php endforeach; ?>
    
    // Edit Role Modal
    const editRoleButtons = document.querySelectorAll('button[data-bs-toggle="modal"][data-bs-target^="#editRoleModal"]');
    editRoleButtons.forEach(button => {
        // Đổi target của button để trỏ đến modal duy nhất
        button.setAttribute('data-bs-target', '#editRoleModal');
        
        // Thêm event listener
        button.addEventListener('click', function() {
            // Lấy thông tin từ data attributes
            const userId = this.getAttribute('data-user-id');
            const username = this.getAttribute('data-username');
            const email = this.getAttribute('data-email');
            const roleId = this.getAttribute('data-role-id');
            
            console.log('Edit role modal clicked:', userId, username, email, roleId);
            
            // Populate form fields
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUserName').value = username;
            document.getElementById('editUserEmail').value = email;
            
            // Reset warning và button state
            document.getElementById('roleWarning').classList.add('d-none');
            document.getElementById('saveRoleButton').disabled = false;
            
            // Set selected role
            const roleSelect = document.getElementById('editUserRole');
            
            // Xử lý logic giới hạn phân quyền
            if (parseInt(userId) === currentUserId && parseInt(roleId) === adminRoleId) {
                // Nếu admin đang chỉnh sửa chính mình
                // Vô hiệu hóa khả năng thay đổi vai trò
                console.log("Không thể thay đổi quyền Admin của chính mình");
                for (let i = 0; i < roleSelect.options.length; i++) {
                    if (roleSelect.options[i].value != adminRoleId) {
                        roleSelect.options[i].disabled = true;
                    }
                }
                document.getElementById('roleWarning').textContent = "Bạn không thể xóa quyền Admin của chính mình";
                document.getElementById('roleWarning').classList.remove('d-none');
            } else {
                // Nếu đang chỉnh sửa người dùng khác
                // Cho phép thay đổi vai trò nhưng không cho phép set Admin
                for (let i = 0; i < roleSelect.options.length; i++) {
                    if (roleSelect.options[i].value == adminRoleId && parseInt(roleId) !== adminRoleId) {
                        roleSelect.options[i].disabled = true;
                        document.getElementById('roleWarning').textContent = "Không thể cấp quyền Admin cho người dùng khác";
                        document.getElementById('roleWarning').classList.remove('d-none');
                    } else {
                        roleSelect.options[i].disabled = false;
                    }
                }
            }
            
            // Set selected role sau khi đã xử lý disable
            for (let i = 0; i < roleSelect.options.length; i++) {
                if (roleSelect.options[i].value == roleId) {
                    roleSelect.options[i].selected = true;
                    break;
                }
            }
        });
    });
});
</script>

<style>
    .avatar-sm {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
</style>

<?php require APPROOT . '/views/includes/footer.php'; ?> 