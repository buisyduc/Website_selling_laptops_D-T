<?php $__env->startSection('title', 'Trang chủ'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Sản phẩm mới nhất</h3>
    <div class="product-grid">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="product-card">
                <?php if($product->image): ?>
                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="product-image">
                <?php else: ?>
                    <div class="no-image-placeholder">No image</div>
                <?php endif; ?>

                <h4><?php echo e($product->name); ?></h4>
                <p><?php echo e(number_format($product->price, 0, ',', '.')); ?> đ</p>
                <a href="<?php echo e(route('client.products.show', $product->id)); ?>">Xem chi tiết</a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Website_selling_laptops_D-T\resources\views/client/index.blade.php ENDPATH**/ ?>