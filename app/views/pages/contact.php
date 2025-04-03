<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-5">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0"><?= $data['title'] ?></h1>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>Thông tin liên hệ</h4>
                            <address>
                                <strong>ĐH Phương Đông</strong><br>
                                171 Trung Kính, Cầu Giấy<br>
                                Hà Nội, Việt Nam<br>
                                <abbr title="Phone">Tel:</abbr> (024) 3784 8513<br>
                                <abbr title="Email">Email:</abbr> lostfound@phuongdong.edu.vn
                            </address>
                            
                            <h4 class="mt-4">Giờ làm việc</h4>
                            <p>Thứ Hai - Thứ Sáu: 8:00 - 17:00<br>
                            Thứ Bảy: 8:00 - 12:00<br>
                            Chủ Nhật: Nghỉ</p>
                            
                            <h4 class="mt-4">Kết nối</h4>
                            <div class="social-icons">
                                <a href="#" class="me-2 fs-4"><i class="fab fa-facebook-square"></i></a>
                                <a href="#" class="me-2 fs-4"><i class="fab fa-twitter-square"></i></a>
                                <a href="#" class="me-2 fs-4"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="fs-4"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="embed-responsive">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.2537985216356!2d105.7943088765426!3d21.018610485937176!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab7abd453137%3A0x8b37a0c6ea41022f!2zxJDhuqFpIGjhu41jIFBoxrDGoW5nIMSQ4buNbmc!5e0!3m2!1svi!2s!4v1711966583097!5m2!1svi!2s" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h4 class="mb-3">Gửi liên hệ</h4>
                    <form action="<?= URLROOT ?>/pages/contact" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= !empty($data['name_err']) ? 'is-invalid' : '' ?>" 
                                id="name" name="name" value="<?= $data['name'] ?>" placeholder="Nhập họ tên">
                            <div class="invalid-feedback"><?= $data['name_err'] ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?= !empty($data['email_err']) ? 'is-invalid' : '' ?>" 
                                id="email" name="email" value="<?= $data['email'] ?>" placeholder="Nhập địa chỉ email">
                            <div class="invalid-feedback"><?= $data['email_err'] ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= !empty($data['subject_err']) ? 'is-invalid' : '' ?>" 
                                id="subject" name="subject" value="<?= $data['subject'] ?>" placeholder="Nhập tiêu đề">
                            <div class="invalid-feedback"><?= $data['subject_err'] ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control <?= !empty($data['message_err']) ? 'is-invalid' : '' ?>" 
                                id="message" name="message" rows="5" placeholder="Nhập nội dung liên hệ"><?= $data['message'] ?></textarea>
                            <div class="invalid-feedback"><?= $data['message_err'] ?></div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Gửi liên hệ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Câu hỏi thường gặp</h4>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionFAQ">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Làm thế nào để đăng tin về đồ vật bị mất?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Để đăng tin về đồ vật bị mất, bạn cần đăng nhập vào tài khoản, sau đó nhấn vào nút "Đăng tin" ở góc phải màn hình và chọn "Đồ vật bị mất". Điền đầy đủ thông tin về đồ vật, thời gian và địa điểm mất.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Tôi có thể liên hệ với người đăng tin như thế nào?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Khi bạn xem chi tiết một bài đăng, bạn có thể nhấn vào nút "Liên hệ" hoặc "Yêu cầu nhận đồ" để gửi yêu cầu và thông tin liên hệ đến người đăng. Sau đó, hai bên có thể trao đổi thông tin và sắp xếp gặp mặt.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Làm thế nào để nhận lại đồ vật đã mất?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Khi bạn tìm thấy bài đăng về đồ vật của mình, hãy gửi yêu cầu nhận đồ với thông tin xác minh chính xác. Người giữ đồ sẽ liên hệ và sắp xếp thời gian, địa điểm gặp mặt. Khi gặp mặt, bạn cần mang theo giấy tờ tùy thân hoặc thẻ sinh viên để xác minh danh tính.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 