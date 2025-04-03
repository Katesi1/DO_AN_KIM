<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><?= $data['title'] ?></h1>
            <p class="lead">
                <?php if($data['type'] == 'lost'): ?>
                    Danh sách đồ vật bị thất lạc trong khuôn viên trường. Nếu bạn tìm thấy, hãy liên hệ với người đăng.
                <?php else: ?>
                    Danh sách đồ vật được tìm thấy trong khuôn viên trường. Nếu bạn là chủ sở hữu, hãy liên hệ để nhận lại.
                <?php endif; ?>
            </p>
        </div>
        <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
            <?php if($this->isLoggedIn()): ?>
                <a href="<?= URLROOT ?>/items/add/<?= $data['type'] ?>" class="btn btn-<?= $data['type'] == 'lost' ? 'primary' : 'success' ?> ms-2">
                    <i class="fas fa-plus"></i> Đăng tin mới
                </a>
            <?php else: ?>
                <a href="<?= URLROOT ?>/users/login" class="btn btn-outline-secondary">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập để đăng tin
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="<?= URLROOT ?>/items/search" method="GET" class="d-flex">
                <input type="hidden" name="type" value="<?= $data['type'] ?>">
                <input type="text" name="q" class="form-control" placeholder="Tìm kiếm đồ vật...">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-4">
            <form action="" method="GET" id="categoryFilterForm">
                <select name="category" class="form-select" id="categoryFilter">
                    <option value="">Tất cả danh mục</option>
                    <?php foreach($data['categories'] as $category): ?>
                        <option value="<?= $category->id ?>" <?= $data['selectedCategory'] == $category->id ? 'selected' : '' ?>>
                            <?= $category->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <?php if(empty($data['items'])): ?>
        <div class="alert alert-info">
            <p class="mb-0">Không có đồ vật nào trong danh mục này.</p>
        </div>
    <?php else: ?>
        <!-- Items List -->
        <div class="row">
            <?php foreach($data['items'] as $item): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <?php if(isset($item->image) && $item->image): ?>
                            <img src="<?= URLROOT ?>/uploads/items/<?= basename($item->image->file_path) ?>" class="card-img-top" alt="<?= $item->title ?>" style="height: 200px; object-fit: contain;">
                        <?php else: ?>
                            <div class="bg-light text-center py-5">
                                <i class="fas fa-image fa-3x text-muted"></i>
                                <p class="mt-2 text-muted">Không có hình ảnh</p>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= $item->title ?></h5>
                            <p class="card-text text-muted">
                                <small><i class="fas fa-map-marker-alt"></i> <?= $item->location ?></small>
                            </p>
                            <p class="card-text">
                                <?= substr($item->description, 0, 80) ?>
                                <?= strlen($item->description) > 80 ? '...' : '' ?>
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="far fa-clock"></i> <?= date('d/m/Y', strtotime($item->created_at)) ?>
                            </small>
                            <a href="<?= URLROOT ?>/items/show/<?= $item->id ?>" class="btn btn-sm btn-<?= $data['type'] == 'lost' ? 'primary' : 'success' ?>">
                                Chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($data['totalPages'] > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Previous page link -->
                    <?php if ($data['currentPage'] > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= URLROOT ?>/items/<?= $data['type'] ?>?page=<?= $data['currentPage'] - 1 ?><?= $data['selectedCategory'] ? '&category=' . $data['selectedCategory'] : '' ?>">
                                Trước
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Trước</a>
                        </li>
                    <?php endif; ?>

                    <!-- Page numbers -->
                    <?php
                    $startPage = max(1, $data['currentPage'] - 2);
                    $endPage = min($data['totalPages'], $data['currentPage'] + 2);
                    
                    if ($startPage > 1) {
                        echo '<li class="page-item"><a class="page-link" href="' . URLROOT . '/items/' . $data['type'] . '?page=1' . ($data['selectedCategory'] ? '&category=' . $data['selectedCategory'] : '') . '">1</a></li>';
                        if ($startPage > 2) {
                            echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                        }
                    }
                    
                    for ($i = $startPage; $i <= $endPage; $i++) {
                        echo '<li class="page-item ' . ($i == $data['currentPage'] ? 'active' : '') . '">';
                        echo '<a class="page-link" href="' . URLROOT . '/items/' . $data['type'] . '?page=' . $i . ($data['selectedCategory'] ? '&category=' . $data['selectedCategory'] : '') . '">' . $i . '</a>';
                        echo '</li>';
                    }
                    
                    if ($endPage < $data['totalPages']) {
                        if ($endPage < $data['totalPages'] - 1) {
                            echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="' . URLROOT . '/items/' . $data['type'] . '?page=' . $data['totalPages'] . ($data['selectedCategory'] ? '&category=' . $data['selectedCategory'] : '') . '">' . $data['totalPages'] . '</a></li>';
                    }
                    ?>

                    <!-- Next page link -->
                    <?php if ($data['currentPage'] < $data['totalPages']): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= URLROOT ?>/items/<?= $data['type'] ?>?page=<?= $data['currentPage'] + 1 ?><?= $data['selectedCategory'] ? '&category=' . $data['selectedCategory'] : '' ?>">
                                Tiếp
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Tiếp</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    // Category filter change event
    document.getElementById('categoryFilter').addEventListener('change', function() {
        document.getElementById('categoryFilterForm').submit();
    });
    
    // Focus vào ô tìm kiếm nếu người dùng đến từ trang chủ
    document.addEventListener('DOMContentLoaded', function() {
        if (sessionStorage.getItem('focusSearch') === 'true') {
            // Focus vào ô input tìm kiếm
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput) {
                searchInput.focus();
            }
            // Xóa trạng thái đã lưu
            sessionStorage.removeItem('focusSearch');
        }
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?> 