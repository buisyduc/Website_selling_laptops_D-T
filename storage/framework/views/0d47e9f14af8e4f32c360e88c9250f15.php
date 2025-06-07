

<?php $__env->startSection('title', 'Danh sách sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Danh sách sản phẩm</h3>

    <div class="product-grid">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="product-card">
                <?php if($product->image): ?>
                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" class="product-image" alt="<?php echo e($product->name); ?>">
                <?php else: ?>
                    <div style="height: 18px; background: #eee; display:flex; align-items:center; justify-content:center;">No image</div>
                <?php endif; ?>

                <h4><?php echo e($product->name); ?></h4>
                <p><?php echo e(number_format($product->price, 0, ',', '.')); ?> đ</p>
                <p><?php echo e(Str::limit($product->description, 60)); ?></p>
                <a href="<?php echo e(route('client.products.show', $product->id)); ?>">Xem chi tiết</a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Website_selling_laptops_D-T\resources\views/client/products/index.blade.php ENDPATH**/ ?>