<?php $__env->startSection('container-fluid'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-trash-restore me-2"></i>
                        Danh mục đã xóa mềm
                    </h3>
                    <div class="card-tools">
                        <span class="badge bg-secondary"><?php echo e($categories->total()); ?> mục</span>
                    </div>
                </div>

                <div class="card-body">
                    <?php if(session('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo e(session('message')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if($categories->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" width="10%">
                                            <i class="fas fa-hashtag me-1"></i>
                                            ID
                                        </th>
                                        <th scope="col" width="40%">
                                            <i class="fas fa-tag me-1"></i>
                                            Tên danh mục
                                        </th>
                                        <th scope="col" width="25%">
                                            <i class="fas fa-clock me-1"></i>
                                            Thời gian xóa
                                        </th>
                                        <th scope="col" width="25%" class="text-center">
                                            <i class="fas fa-cogs me-1"></i>
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark">#<?php echo e($category->id); ?></span>
                                        </td>
                                        <td>
                                            <strong><?php echo e($category->name); ?></strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                <?php echo e($category->deleted_at->format('d/m/Y')); ?>

                                                <br>
                                                <i class="fas fa-clock me-1"></i>
                                                <?php echo e($category->deleted_at->format('H:i:s')); ?>

                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group" aria-label="Actions">
                                                <form action="<?php echo e(route('categories.restore', $category->id)); ?>"
                                                      method="POST"
                                                      class="d-inline-block">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit"
                                                            onclick="return confirm('Bạn có chắc chắn muốn khôi phục danh mục này không?')"
                                                            class="btn btn-sm btn-success"
                                                            title="Khôi phục danh mục"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fas fa-undo me-1"></i>
                                                        Khôi phục
                                                    </button>
                                                </form>

                                                <form action="<?php echo e(route('categories.forceDelete', $category->id)); ?>"
                                                      method="POST"
                                                      class="d-inline-block ms-1">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Xóa vĩnh viễn"
                                                            data-bs-toggle="tooltip"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn danh mục này không?')">
                                                        <i class="fas fa-trash-alt me-1"></i>
                                                        Xóa vĩnh viễn
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Hiển thị <?php echo e($categories->firstItem() ?? 0); ?> - <?php echo e($categories->lastItem() ?? 0); ?>

                                trong tổng số <?php echo e($categories->total()); ?> mục
                            </div>
                            <div>
                                <?php echo e($categories->links()); ?>

                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-inbox fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Không có danh mục nào đã bị xóa</h5>
                            <p class="text-muted">Tất cả danh mục hiện đang hoạt động</p>
                            <a href="<?php echo e(route('categories')); ?>" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="<?php echo e(route('categories')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                        <div class="col-sm-6 text-end">
                            <?php if($categories->count() > 0): ?>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                                    <i class="fas fa-tasks me-1"></i>
                                    Thao tác hàng loạt
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-labelledby="bulkActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionsModalLabel">
                    <i class="fas fa-tasks me-2"></i>
                    Thao tác hàng loạt
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Chọn hành động bạn muốn thực hiện với tất cả danh mục đã xóa:</p>

                <form action="<?php echo e(route('categories.restoreAll')); ?>" method="POST" class="mb-3">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Khôi phục tất cả danh mục đã xóa?')">
                        <i class="fas fa-undo me-2"></i>
                        Khôi phục tất cả
                    </button>
                </form>

                <form action="<?php echo e(route('categories.forceDeleteAll')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirm('⚠️ CẢNH BÁO!\n\nXóa vĩnh viễn TẤT CẢ danh mục đã xóa?\nHành động này không thể hoàn tác!')">
                        <i class="fas fa-trash-alt me-2"></i>
                        Xóa vĩnh viễn tất cả
                    </button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Website_selling_laptops_D-T\resources\views/admin/Category/SoftDeletedCategories.blade.php ENDPATH**/ ?>