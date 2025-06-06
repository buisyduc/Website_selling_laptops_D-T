<?php $__env->startSection('container-fluid'); ?>
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Attributes</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                            <li class="breadcrumb-item active">Attributes</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xxl-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Create Attributes</h6>
                    </div>
                    <div class="card-body">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('attributes.store')); ?>" enctype="multipart/form-data">

                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Attributes Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="<?php echo e(old('name')); ?>" placeholder="Nhập thuộc tính " required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                    <div class="col-md-12 mb-3">
                                    <label for="parent_id" class="form-label">Parent Attributes</label>
                                    <select class="form-select" id="parent_id" name="parent_id">
                                        <option value="">None</option>
                                      <?php echo (new \App\Http\Controllers\Admin\AttributesProduct)->renderCategoryOptions($allAttributes); ?>


                                    </select>
                                </div>

                                 <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-success px-4">Add Attributes</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- end card -->
            </div>
            
        </div><!--end row-->

    </div>
    <style>
        .form-control::placeholder {
            color: rgba(0, 0, 0, 0.5); /* Màu chữ mờ hơn */
            font-style: italic; /* Làm chữ nghiêng */
        }
    </style>

    

    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Website_selling_laptops_D-T\resources\views/admin/attributes.blade.php ENDPATH**/ ?>