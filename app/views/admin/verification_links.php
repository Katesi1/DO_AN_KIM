<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3"><?php echo $data['title']; ?></h1>
                <a href="<?php echo URLROOT; ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại trang chủ
                </a>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Các liên kết xác thực chưa được xử lý</h5>
                        <span class="badge bg-primary"><?php echo count($data['links']); ?> liên kết</span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(empty($data['links'])): ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Không có liên kết xác thực nào được lưu.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" width="15%">Thời gian</th>
                                        <th scope="col" width="20%">Email</th>
                                        <th scope="col">Liên kết xác thực</th>
                                        <th scope="col" width="15%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['links'] as $link): 
                                        // Parse the line to extract date, email and link
                                        $parts = explode(" - ", $link, 2);
                                        $date = isset($parts[0]) ? trim($parts[0]) : '';
                                        
                                        $emailAndLink = isset($parts[1]) ? explode(": ", $parts[1], 2) : ['', ''];
                                        $email = isset($emailAndLink[0]) ? trim($emailAndLink[0]) : '';
                                        $verificationLink = isset($emailAndLink[1]) ? trim($emailAndLink[1]) : '';
                                        
                                        // Extract just the token from the URL
                                        $token = basename($verificationLink);
                                    ?>
                                    <tr>
                                        <td><?php echo $date; ?></td>
                                        <td><?php echo $email; ?></td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm" value="<?php echo $verificationLink; ?>" readonly>
                                                <button class="btn btn-outline-secondary btn-sm copy-btn" type="button" data-clipboard-text="<?php echo $verificationLink; ?>">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="<?php echo $verificationLink; ?>" class="btn btn-primary btn-sm" target="_blank">
                                                <i class="fas fa-external-link-alt me-1"></i> Mở
                                            </a>
                                            <a href="<?php echo URLROOT; ?>/users/admin_verify/<?php echo $token; ?>" class="btn btn-success btn-sm">
                                                <i class="fas fa-check me-1"></i> Xác thực
                                            </a>
                                            <a href="<?php echo URLROOT; ?>/users/verify_by_email/<?php echo urlencode($email); ?>" class="btn btn-info btn-sm text-white">
                                                <i class="fas fa-envelope me-1"></i> Xác thực qua email
                                            </a>
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

<script>
    // Initialize clipboard.js
    document.addEventListener('DOMContentLoaded', function() {
        var clipboard = new ClipboardJS('.copy-btn');
        
        clipboard.on('success', function(e) {
            e.trigger.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(function() {
                e.trigger.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
            e.clearSelection();
        });
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?> 