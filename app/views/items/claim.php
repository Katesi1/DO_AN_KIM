<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-5">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0">
                        <i class="fas fa-hand-holding-heart"></i> 
                        <?php if($data['item']->type == 'lost'): ?>
                            Tôi đã tìm thấy đồ này
                        <?php else: ?>
                            Đây là đồ của tôi
                        <?php endif; ?>
                    </h1>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-info-circle"></i> Thông tin đồ vật:</h5>
                        <p class="mb-0"><strong>Tiêu đề:</strong> <?= $data['item']->title ?></p>
                    </div>

                    <div class="alert alert-warning mb-4">
                        <h5><i class="fas fa-exclamation-triangle"></i> Quy trình xác minh:</h5>
                        <p class="mb-0">Thông tin bạn cung cấp sẽ được đối chiếu với mô tả chi tiết của người đăng. Quản trị viên sẽ xem xét và xác nhận nếu thông tin trùng khớp.</p>
                    </div>

                    <form action="<?= URLROOT ?>/items/claim/<?= $data['item']->id ?>" method="POST">
                        <div class="mb-3">
                            <label for="verification_info" class="form-label">Thông tin xác minh <span class="text-danger">*</span></label>
                            <textarea class="form-control <?= !empty($data['verification_info_err']) ? 'is-invalid' : '' ?>" 
                                id="verification_info" name="verification_info" rows="5" 
                                placeholder="<?php if($data['item']->type == 'lost'): ?>Mô tả chi tiết đặc điểm của đồ vật (ví dụ: các vết xước, sticker, hình nền, mật khẩu...)<?php else: ?>Mô tả chi tiết các đặc điểm riêng biệt của đồ vật mà chỉ chủ sở hữu mới biết<?php endif; ?>"><?= $data['verification_info'] ?></textarea>
                            <div class="invalid-feedback"><?= $data['verification_info_err'] ?></div>
                            <div class="form-text">
                                <?php if($data['item']->type == 'lost'): ?>
                                    Hãy mô tả đặc điểm nhận dạng mà chỉ người mất đồ mới biết để chứng minh bạn đã tìm thấy đồ vật.
                                <?php else: ?>
                                    Hãy mô tả chi tiết các đặc điểm riêng biệt hoặc thông tin cá nhân về đồ vật để chứng minh bạn là chủ sở hữu.
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="meeting_location" class="form-label">Địa điểm gặp mặt <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= !empty($data['meeting_location_err']) ? 'is-invalid' : '' ?>" 
                                    id="meeting_location" name="meeting_location" value="<?= $data['meeting_location'] ?>" 
                                    placeholder="Ví dụ: Sảnh tòa nhà A, Quán cà phê...">
                                <div class="invalid-feedback"><?= $data['meeting_location_err'] ?></div>
                            </div>
                            <div class="col-md-6">
                                <label for="meeting_time" class="form-label">Thời gian gặp mặt đề xuất <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control <?= !empty($data['meeting_time_err']) ? 'is-invalid' : '' ?>" 
                                    id="meeting_time" name="meeting_time" value="<?= $data['meeting_time'] ?>">
                                <div class="invalid-feedback"><?= $data['meeting_time_err'] ?></div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Lưu ý quan trọng:</h5>
                            <ul class="mb-0">
                                <li>Việc cung cấp thông tin sai lệch có thể dẫn đến việc tài khoản của bạn bị khóa.</li>
                                <li>Luôn gặp mặt ở nơi công cộng hoặc trong khuôn viên trường để đảm bảo an toàn.</li>
                                <li>Khi gặp mặt, hãy mang theo thẻ sinh viên hoặc giấy tờ tùy thân để xác minh danh tính.</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= URLROOT ?>/items/show/<?= $data['item']->id ?>" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Gửi yêu cầu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 