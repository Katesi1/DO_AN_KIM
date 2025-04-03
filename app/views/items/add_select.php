<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-5">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0">
                        <i class="fas fa-plus-circle"></i> Đăng tin mới
                    </h1>
                </div>
                <div class="card-body">
                    <p class="lead text-center mb-4">Vui lòng chọn loại tin đăng:</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-search fa-5x text-primary mb-3"></i>
                                    <h3>Tôi bị mất đồ</h3>
                                    <p>Đăng tin về đồ vật bạn đã bị mất và đang tìm kiếm</p>
                                    <a href="<?= URLROOT ?>/items/add/lost" class="btn btn-primary btn-lg mt-2">
                                        <i class="fas fa-file-alt"></i> Đăng tin mất đồ
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-hand-holding fa-5x text-success mb-3"></i>
                                    <h3>Tôi tìm thấy đồ</h3>
                                    <p>Đăng tin về đồ vật bạn đã tìm thấy và đang tìm chủ</p>
                                    <a href="<?= URLROOT ?>/items/add/found" class="btn btn-success btn-lg mt-2">
                                        <i class="fas fa-file-alt"></i> Đăng tin tìm thấy đồ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <h5><i class="fas fa-info-circle"></i> Lưu ý khi đăng tin:</h5>
                        <ul class="mb-0">
                            <li>Cung cấp thông tin chính xác và đầy đủ về đồ vật</li>
                            <li>Thêm hình ảnh để tăng khả năng tìm kiếm hoặc nhận dạng</li>
                            <li>Nếu đăng tin về đồ vật tìm thấy, hãy giữ lại một số đặc điểm riêng để xác minh chủ sở hữu thực sự</li>
                            <li>Tin đăng sẽ tự động hết hạn sau 30 ngày</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 