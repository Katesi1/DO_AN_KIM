<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-5">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0"><?= $data['title'] ?></h1>
                </div>
                <div class="card-body">
                    <p class="lead"><?= $data['description'] ?></p>
                    
                    <h2 class="mt-4">Mục Tiêu</h2>
                    <p>Hệ thống Đồ Vật Thất Lạc - ĐH Phương Đông được xây dựng với mục tiêu:</p>
                    <ul>
                        <li>Tạo môi trường kết nối nhanh chóng giữa người mất đồ và người nhặt được đồ</li>
                        <li>Nâng cao ý thức giữ gìn tài sản cá nhân trong sinh viên</li>
                        <li>Xây dựng văn hóa học đường tích cực</li>
                        <li>Cải thiện công tác quản lý đồ thất lạc trong trường</li>
                    </ul>

                    <h2 class="mt-4">Đội Ngũ Phát Triển</h2>
                    <p>Hệ thống được phát triển bởi sinh viên trường Đại học Phương Đông với sự hướng dẫn của Khoa Công nghệ thông tin.</p>
                    
                    <div class="alert alert-info mt-4">
                        <h4>Phiên bản: <?= APPVERSION ?></h4>
                        <p class="mb-0">Đây là phiên bản đầu tiên của hệ thống. Mọi góp ý xin gửi về email: contact@example.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 