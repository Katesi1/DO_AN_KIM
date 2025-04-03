<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Kết quả tìm kiếm</h1>
            <p class="lead">Tìm kiếm "<?= htmlspecialchars($data['query']) ?>" 
                <?= isset($data['type']) && !empty($data['type']) ? 'trong danh mục ' . ($data['type'] == 'lost' ? 'đồ thất lạc' : 'đồ tìm thấy') : '' ?>
            </p>
        </div>
        <div class="col-md-4">
            <form action="<?= URLROOT ?>/items/search" method="GET" class="mt-2">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" value="<?= htmlspecialchars($data['query']) ?>" placeholder="Tìm kiếm...">
                    <?php if(isset($data['type']) && !empty($data['type'])): ?>
                        <input type="hidden" name="type" value="<?= $data['type'] ?>">
                    <?php endif; ?>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="btn-group" role="group">
                <a href="<?= URLROOT ?>/items/search?q=<?= urlencode($data['query']) ?>" 
                   class="btn btn-outline-secondary <?= !isset($data['type']) || empty($data['type']) ? 'active' : '' ?>">
                   Tất cả
                </a>
                <a href="<?= URLROOT ?>/items/search?q=<?= urlencode($data['query']) ?>&type=lost" 
                   class="btn btn-outline-primary <?= isset($data['type']) && $data['type'] == 'lost' ? 'active' : '' ?>">
                   Đồ thất lạc
                </a>
                <a href="<?= URLROOT ?>/items/search?q=<?= urlencode($data['query']) ?>&type=found" 
                   class="btn btn-outline-success <?= isset($data['type']) && $data['type'] == 'found' ? 'active' : '' ?>">
                   Đồ tìm thấy
                </a>
            </div>
        </div>
    </div>

    <?php if(empty($data['items'])): ?>
        <div class="alert alert-info">
            <p class="mb-0">Không tìm thấy kết quả nào phù hợp với từ khóa "<?= htmlspecialchars($data['query']) ?>".</p>
            <p class="mb-0 mt-2">Gợi ý:</p>
            <ul class="mb-0">
                <li>Kiểm tra lỗi chính tả</li>
                <li>Sử dụng các từ khóa khác</li>
                <li>Sử dụng các từ khóa ngắn gọn hơn</li>
                <li>Thử tìm kiếm không giới hạn loại đồ vật</li>
            </ul>
        </div>
    <?php else: ?>
        <p class="mb-4">Tìm thấy <?= count($data['items']) ?> kết quả.</p>
        
        <!-- Results -->
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
                            <div class="mb-2">
                                <?php if($item->type == 'lost'): ?>
                                    <span class="badge bg-primary">Đồ thất lạc</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Đồ tìm thấy</span>
                                <?php endif; ?>
                            </div>
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
                            <a href="<?= URLROOT ?>/items/show/<?= $item->id ?>" class="btn btn-sm btn-<?= $item->type == 'lost' ? 'primary' : 'success' ?>">
                                Chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?> 