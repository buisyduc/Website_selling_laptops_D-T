

<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
    <h1><?php echo e($product->name); ?></h1>
    <?php if($product->image): ?>
        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" style="max-width: 400px;" alt="<?php echo e($product->name); ?>">
    <?php endif; ?>

    <p><strong>Giá:</strong> <?php echo e(number_format($product->price, 0, ',', '.')); ?> đ</p>
    <p><?php echo e($product->description); ?></p>

    <a href="<?php echo e(route('client.products.index')); ?>">⬅ Quay lại danh sách</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Website_selling_laptops_D-T\resources\views/client/products/show.blade.php ENDPATH**/ ?>