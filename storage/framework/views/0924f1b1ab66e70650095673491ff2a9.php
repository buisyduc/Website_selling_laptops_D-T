<?php $__env->startSection('container-fluid'); ?>

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Product List</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                        <li class="breadcrumb-item active">Product List</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

        <!-- end col -->
        <div class=" mb-12">
            <div class="row g-4 mb-4">
                <div class="col-sm-auto">
                    
                </div>
                <div class="col-sm">
                    <div class="d-flex justify-content-sm-end">
                        <div class="search-box ms-2">
                            <form method="GET" action="<?php echo e(route('product-list')); ?>" class="mb-3 d-flex align-items-center gap-2" style="margin-top:10px">
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search Product..."
                                    class="form-control" style="width: 300px;">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="<?php echo e(route('product-list')); ?>" class="btn btn-info">Reset</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div  class="gridjs-border-none mb-4">
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product name</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Price</th>
                        <th scope="col">Sold </th>
                        <th scope="col">Publish</th>
                        <th scope="col">Image</th>
                        <th scope="col">Category</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row"></th>
                                <td><?php echo e($product->name); ?></td>
                                <td><?php echo e($product->stock); ?></td>
                                <th scope="row">

                                       <?php
                                            $avgRating = round($product->averageRating(), 1); // Lấy rating trung bình, làm tròn 1 chữ số
                                        ?>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo e($i <= $avgRating ? '-fill text-warning' : ''); ?>"></i>
                                        <?php endfor; ?>
                                        (<?php echo e($avgRating); ?>/5)

                                </th>
                                <td><?php echo e($product->price); ?></td>
                                <td scope="row"><?php echo e($product->total_sold ?? 0); ?> </td>

                                <td scope="row"><?php echo e($product->created_at); ?></td>


                                <td>
                                    <?php if($product->image): ?>
    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" width="50" height="50" alt="<?php echo e($product->image); ?>">
<?php else: ?>
    <img src="<?php echo e(asset('images/default-image.jpg')); ?>" width="50" height="50" alt="Default Image">
<?php endif; ?>

                                </td>
                                <td scope="row"><?php echo e($product->category->name); ?></td>
                                <td scope="row"><?php echo e($product->status); ?></td>
                                <td>
                                    <ul class="flex-shrink-0 list-unstyled hstack gap-1 mb-0">
                                        <li><a href="#" class="badge bg-info-subtle text-info">Edit</a></li>
                                        <li><a href="#" data-bs-toggle="modal" class="badge bg-danger-subtle text-danger">Delete</a></li>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>
                  </table>
                  <?php echo e($products->links()); ?>

            </div>
        </div>

        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- container-fluid -->
<?php $__env->stopSection(); ?>
<script>
    $(document).ready(function() {
        function filterProducts() {
            let category = $("input[name='category']:checked").val();
            let minPrice = $("#minCost").val();
            let maxPrice = $("#maxCost").val();
            let color = $("input[name='color']:checked").val();
            let size = $("input[name='size']:checked").val();
            let discount = $("input[name='discount']:checked").val();
            let rating = $("input[name='rating']:checked").val();

            $.ajax({
                url: "<?php echo e(route('product-list')); ?>",
                method: "GET",
                data: {
                    category: category,
                    min_price: minPrice,
                    max_price: maxPrice,
                    color: color,
                    size: size,
                    discount: discount,
                    rating: rating
                },
                success: function(response) {
                    $("#product-list").html(response);
                }
            });
        }

        // Bắt sự kiện thay đổi giá trị lọc
        $("input, select").on("change", function() {
            filterProducts();
        });

        // Nút "Clear All"
        $("#clearall").click(function() {
            $("input[type='checkbox']").prop("checked", false);
            $("#minCost").val(0);
            $("#maxCost").val(1000);
            filterProducts();
        });
    });
    </script>

<?php echo $__env->make('admin.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Website_selling_laptops_D-T\resources\views/admin/product-list.blade.php ENDPATH**/ ?>