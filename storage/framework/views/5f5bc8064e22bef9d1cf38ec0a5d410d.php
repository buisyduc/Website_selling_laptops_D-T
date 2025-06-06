<?php $__env->startSection('container-fluid'); ?>
    <div class="container-fluid">

        <!-- Enhanced Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary p-4 rounded-3 text-white shadow-sm">
                    <div>
                        <h3 class="mb-1 fw-bold">Brand Management</h3>
                        <p class="mb-0 opacity-75">Manage your product brands efficiently</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="javascript:void(0);" class="text-white-50 text-decoration-none">Products</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Brands</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Enhanced Create Form -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-success text-white">
                        <div class="d-flex align-items-center">
                            <i class="ri-add-circle-line fs-4 me-2"></i>
                            <h5 class="card-title mb-0 fw-semibold">Create New Brand</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="ri-check-circle-line me-2"></i><?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('brands.store')); ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <?php echo csrf_field(); ?>

                            <!-- Brand Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="ri-bookmark-line me-1 text-primary"></i>Brand Name
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-text text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="name" name="name" placeholder="Enter brand name"
                                           value="<?php echo e(old('name')); ?>" required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="mb-4">
                                <label for="slug" class="form-label fw-semibold">
                                    <i class="ri-link me-1 text-info"></i>Slug
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-hashtag text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="slug" name="slug" placeholder="auto-generated-slug"
                                           value="<?php echo e(old('slug')); ?>" required>
                                    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Logo Upload -->
                            <div class="mb-4">
                                <label for="logo" class="form-label fw-semibold">
                                    <i class="ri-image-line me-1 text-warning"></i>Brand Logo
                                </label>
                                <div class="position-relative">
                                    <input type="file" class="form-control" id="logo" name="logo"
                                           accept="image/*" onchange="previewLogo(this)">
                                    <div class="mt-2" id="logo-preview" style="display: none;">
                                        <img id="preview-logo" class="rounded border" style="max-width: 120px; max-height: 120px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="ri-file-text-line me-1 text-info"></i>Description
                                </label>
                                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          id="description" name="description" rows="4"
                                          placeholder="Enter brand description"><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg fw-semibold">
                                    <i class="ri-add-line me-2"></i>Create Brand
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Enhanced Brands List -->
            <div class="col-xl-8">
                <!-- Search Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3">
                        <form method="GET" action="<?php echo e(route('brands')); ?>" class="d-flex gap-2">
                            <div class="input-group flex-grow-1">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                       placeholder="Search brands..." class="form-control border-start-0">
                            </div>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ri-search-line me-1"></i>Search
                            </button>
                            <a href="<?php echo e(route('brands')); ?>" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line me-1"></i>Reset
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Brands Grid -->
                <div class="row g-4" id="brands-list">
                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card h-100 border-0 shadow-sm brand-card">
                                <!-- Logo Section -->
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    <?php if($brand->logo): ?>
                                        <img src="<?php echo e(asset('storage/' . $brand->logo)); ?>"
                                             alt="<?php echo e($brand->name); ?>"
                                             class="card-img-top h-100 object-fit-cover">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                            <i class="ri-building-line fs-1 text-muted"></i>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Brand Badge -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-primary">
                                            <i class="ri-price-tag-line me-1"></i>Brand
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-2 fw-bold text-truncate"><?php echo e($brand->name); ?></h5>
                                        <p class="text-muted small mb-2">
                                            <i class="ri-link me-1"></i><?php echo e($brand->slug); ?>

                                        </p>
                                        <?php if($brand->description): ?>
                                            <p class="card-text text-muted small">
                                                <?php echo e(Str::limit($brand->description, 80)); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 mt-auto">
                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm flex-fill brand-link"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#overviewOffcanvas"
                                                data-title="<?php echo e($brand->name); ?>"
                                                data-slug="<?php echo e($brand->slug); ?>"
                                                data-description="<?php echo e($brand->description); ?>"
                                                data-logo="<?php echo e($brand->logo ? asset('storage/' . $brand->logo) : ''); ?>">
                                            <i class="ri-eye-line me-1"></i>View
                                        </button>
                                           <a href="<?php echo e(route('brands.edit', $brand->id)); ?>" class="btn btn-outline-secondary btn-sm flex-fill">
                                            <i class="ri-edit-line me-1"></i>Edit
                                        </a>
                                        <form action="<?php echo e(route('brands.destroy', $brand->id)); ?>" method="POST" class="d-inline flex-fill">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn xóa thương hiệu này không?')"
                                                    class="btn btn-outline-danger btn-sm w-100">
                                                <i class="ri-delete-bin-line me-1"></i>Delete
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($brands->links()); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="overviewOffcanvas" style="width: 400px;">
        <div class="offcanvas-header bg-gradient-primary text-white">
            <h5 class="offcanvas-title fw-bold">
                <i class="ri-information-line me-2"></i>Brand Details
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <!-- Logo Section -->
            <div class="text-center p-4 bg-light">
                <div class="position-relative d-inline-block">
                    <img src="" alt="Brand Logo"
                         class="overview-logo rounded-circle border border-3 border-white shadow"
                         style="width: 120px; height: 120px; object-fit: cover;">
                </div>
            </div>

            <!-- Details Section -->
            <div class="p-4">
                <div class="text-center mb-4">
                    <h4 class="overview-title fw-bold mb-1">Brand Name</h4>
                    <p class="text-muted mb-0">
                        <i class="ri-link me-1"></i><span class="overview-slug"></span>
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2">
                                    <i class="ri-file-text-line text-info me-2"></i>Description
                                </h6>
                                <p class="overview-description text-muted mb-0 small"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Footer -->
        <div class="p-3 border-top">
            <div class="row g-2">
                <div class="col-6">
                    <button type="button" class="btn btn-outline-danger w-100" data-bs-dismiss="offcanvas">
                        <i class="ri-delete-bin-line me-1"></i>Delete
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="offcanvas">
                        <i class="ri-pencil-line me-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logo Preview Function
        function previewLogo(input) {
            const preview = document.getElementById('logo-preview');
            const previewImg = document.getElementById('preview-logo');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }

        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/\s+/g, '-')
                            .replace(/-+/g, '-')
                            .trim();
            document.getElementById('slug').value = slug;
        });

        // Offcanvas Brand Details
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.brand-link').forEach(item => {
                item.addEventListener("click", function () {
                    const title = this.getAttribute("data-title");
                    const slug = this.getAttribute("data-slug");
                    const description = this.getAttribute("data-description") || "No description available";
                    const logo = this.getAttribute("data-logo");

                    // Update logo
                    const logoElement = document.querySelector(".overview-logo");
                    if (logo) {
                        logoElement.src = logo;
                    } else {
                        logoElement.src = "<?php echo e(asset('default-logo.jpg')); ?>";
                    }

                    // Update content
                    document.querySelector(".overview-title").innerText = title;
                    document.querySelector(".overview-slug").innerText = slug;
                    document.querySelector(".overview-description").innerText = description;
                });
            });
        });

        // Card hover effects
        document.querySelectorAll('.brand-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                const forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .brand-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .brand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
        }

        .card {
            border-radius: 12px;
        }

        .input-group-text {
            border-radius: 8px 0 0 8px;
        }

        .form-control {
            border-radius: 0 8px 8px 0;
        }

        .form-control:first-child {
            border-radius: 8px;
        }

        .offcanvas {
            border-radius: 20px 0 0 20px;
        }

        .pagination .page-link {
            border-radius: 8px;
            margin: 0 2px;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.7);
        }

        .form-control::placeholder {
            color: var(--bs-secondary);
            opacity: 0.5;
        }

        /* Custom scrollbar for offcanvas */
        .offcanvas-body::-webkit-scrollbar {
            width: 6px;
        }

        .offcanvas-body::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .offcanvas-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .offcanvas-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Website_selling_laptops_D-T\resources\views/admin/Brand/brands.blade.php ENDPATH**/ ?>