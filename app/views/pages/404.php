<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container text-center">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="error-template mt-5">
                <h1 class="display-1">404</h1>
                <h2>Không tìm thấy trang</h2>
                <div class="error-details mb-4">
                    Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
                </div>
                <div class="error-actions">
                    <a href="<?= URLROOT ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Trở về trang chủ
                    </a>
                    <a href="<?= URLROOT ?>/pages/contact" class="btn btn-outline-secondary btn-lg ms-2">
                        <i class="fas fa-envelope"></i> Liên hệ hỗ trợ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 