@extends('admin.index')
@section('container-fluid')
    <div class="container-fluid">

        <!-- Enhanced Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary p-4 rounded-3 text-white shadow-sm">
                    <div>
                        <h3 class="mb-1 fw-bold">Quản lý danh mục</h3>
                        <p class="mb-0 opacity-75">Quản lý danh mục sản phẩm hiệu quả</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="javascript:void(0);" class="text-white-50 text-decoration-none">Sản phẩm</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Danh mục</li>
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
                            <h5 class="card-title mb-0 fw-semibold">Thêm danh mục</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="ri-check-circle-line me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf

                            <!-- Category Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="ri-bookmark-line me-1 text-primary"></i>Tên danh mục
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-text text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror"
                                           id="name" name="name" placeholder="Nhập tên danh mục"
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    <input type="text" class="form-control border-start-0 @error('slug') is-invalid @enderror"
                                           id="slug" name="slug" placeholder="slug-tu-dong-tao"
                                           value="{{ old('slug') }}" required>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-4">
                                <label for="image" class="form-label fw-semibold">
                                    <i class="ri-image-line me-1 text-warning"></i>Ảnh danh mục
                                </label>
                                <div class="position-relative">
                                    <input type="file" class="form-control" id="image" name="image"
                                           accept="image/*" onchange="previewImage(this)">
                                    <div class="mt-2" id="image-preview" style="display: none;">
                                        <img id="preview-img" class="rounded border" style="max-width: 120px; max-height: 120px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>

                            <!-- Parent Category -->
                            <div class="mb-4">
                                <label for="parent_id" class="form-label fw-semibold">
                                    <i class="ri-node-tree me-1 text-secondary"></i>Danh mục cha
                                </label>
                                <select class="form-select" id="parent_id" name="parent_id">
                                    <option value="">-- Chọn danh mục cha --</option>
                                    {!! (new App\Http\Controllers\Admin\CategorieController)->renderCategoryOptions($categories) !!}
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="ri-file-text-line me-1 text-info"></i>Mô tả
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4"
                                          placeholder="Nhập mô tả danh mục">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status Toggle -->
                            <div class="mb-4">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="ri-eye-line me-1 text-success"></i>Trạng thái
                                </label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status"
                                           value="1" checked onchange="updateStatusText()">
                                    <label class="form-check-label fw-medium" for="status" id="status-label">
                                        <span class="badge bg-success">Hiển thị</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Order -->
                            <div class="mb-4">
                                <label for="order" class="form-label fw-semibold">
                                    <i class="ri-sort-asc me-1 text-primary"></i>Thứ tự hiển thị
                                </label>
                                <input type="number" class="form-control" id="order" name="order"
                                       value="0" min="0" placeholder="0">
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg fw-semibold">
                                    <i class="ri-add-line me-2"></i>Tạo danh mục
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Enhanced Categories List -->
            <div class="col-xl-8">
                <!-- Search Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('categories') }}" class="d-flex gap-2">
                            <div class="input-group flex-grow-1">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Tìm kiếm danh mục..." class="form-control border-start-0">
                            </div>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ri-search-line me-1"></i>Tìm kiếm
                            </button>
                            <a href="{{ route('categories') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line me-1"></i>Làm mới
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Categories Grid -->
                <div class="row g-4" id="categories-list">
                    @foreach ($categories as $category)
                        <div class="col-lg-6 col-xl-4">
                            <div class="card h-100 border-0 shadow-sm category-card">
                                <!-- Image Section -->
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                             alt="{{ $category->name }}"
                                             class="card-img-top h-100 object-fit-cover">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                            <i class="ri-image-line fs-1 text-muted"></i>
                                        </div>
                                    @endif

                                    <!-- Status Badge -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        @if($category->status == 1)
                                            <span class="badge bg-success">
                                                <i class="ri-eye-line me-1"></i>Hiển thị
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="ri-eye-off-line me-1"></i>Ẩn
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-2 fw-bold text-truncate">{{ $category->name }}</h5>
                                        <p class="text-muted small mb-2">
                                            <i class="ri-link me-1"></i>{{ $category->slug }}
                                        </p>
                                        @if($category->description)
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($category->description, 80) }}
                                            </p>
                                        @endif

                                        <!-- Meta Info -->
                                        <div class="d-flex justify-content-between align-items-center text-muted small mb-3">
                                            <span><i class="ri-sort-asc me-1"></i>Thứ tự: {{ $category->order }}</span>
                                            @if($category->parent_id)
                                                <span><i class="ri-node-tree me-1"></i>Có danh mục cha</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 mt-auto">
                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm flex-fill category-link"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#overviewOffcanvas"
                                                data-title="{{ $category->name }}"
                                                data-slug="{{ $category->slug }}"
                                                data-description="{{ $category->description }}"
                                                data-image="{{ $category->image ? asset('storage/' . $category->image) : '' }}"
                                                data-parent_id="{{ $category->parent_id }}"
                                                data-parent_name="{{ $category->parent ? $category->parent->name : 'Không có' }}"

                                                data-status="{{ $category->status }}"
                                                data-order="{{ $category->order }}">
                                            <i class="ri-eye-line me-1"></i>Xem
                                        </button>
                                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                            <i class="ri-edit-line me-1"></i>Sửa
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline flex-fill">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa tạm thời danh mục này không?')"
                                                    class="btn btn-outline-danger btn-sm w-100">
                                                <i class="ri-delete-bin-line me-1"></i>Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="overviewOffcanvas" style="width: 400px;">
        <div class="offcanvas-header bg-gradient-primary text-white">
            <h5 class="offcanvas-title fw-bold">
                <i class="ri-information-line me-2"></i>Chi tiết danh mục
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <!-- Image Section -->
            <div class="text-center p-4 bg-light">
                <div class="position-relative d-inline-block">
                    <img src="" alt="Category Image"
                         class="overview-image rounded-circle border border-3 border-white shadow"
                         style="width: 120px; height: 120px; object-fit: cover;">
                </div>
            </div>

            <!-- Details Section -->
            <div class="p-4">
                <div class="text-center mb-4">
                    <h4 class="overview-title fw-bold mb-1">Tên danh mục</h4>
                    <p class="text-muted mb-0">
                        <i class="ri-link me-1"></i><span class="overview-slug"></span>
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2">
                                    <i class="ri-file-text-line text-info me-2"></i>Mô tả
                                </h6>
                                <p class="overview-description text-muted mb-0 small"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3 text-center">
                                <h6 class="card-title mb-2">
                                    <i class="ri-eye-line text-success me-1"></i>Trạng thái
                                </h6>
                                <span class="overview-status badge bg-success"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3 text-center">
                                <h6 class="card-title mb-2">
                                    <i class="ri-sort-asc text-primary me-1"></i>Thứ tự
                                </h6>
                                <span class="overview-order badge bg-primary"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2">
                                    <i class="ri-node-tree text-secondary me-2"></i>Danh mục cha
                                </h6>
                                <p class="overview-parent-id text-muted mb-0"></p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image Preview Function
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

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

        // Status Toggle Function
        function updateStatusText() {
            const checkbox = document.getElementById('status');
            const label = document.getElementById('status-label');

            if (checkbox.checked) {
                label.innerHTML = '<span class="badge bg-success">Hiển thị</span>';
            } else {
                label.innerHTML = '<span class="badge bg-secondary">Ẩn</span>';
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

        // Offcanvas Category Details
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.category-link').forEach(item => {
                item.addEventListener("click", function () {
                    const title = this.getAttribute("data-title");
                    const slug = this.getAttribute("data-slug");
                    const description = this.getAttribute("data-description") || "Chưa có mô tả";
                    const image = this.getAttribute("data-image");
                    const parentId = this.getAttribute("data-parent_id") || "Không có";
                    const parentName = this.getAttribute("data-parent_name") || "Không có"; // Tên cha
                    const status = this.getAttribute("data-status");
                    const order = this.getAttribute("data-order");

                    // Update image
                    const imageElement = document.querySelector(".overview-image");
                    if (image) {
                        imageElement.src = image;
                    } else {
                        imageElement.src = "{{ asset('default-image.jpg') }}";
                    }

                    // Update content
                    document.querySelector(".overview-title").innerText = title;
                    document.querySelector(".overview-slug").innerText = slug;
                    document.querySelector(".overview-description").innerText = description;

                    document.querySelector(".overview-parent-id").innerText = parentName;


                    const statusElement = document.querySelector(".overview-status");
                    if (status == "1") {
                        statusElement.innerText = "Hiển thị";
                        statusElement.className = "badge bg-success";
                    } else {
                        statusElement.innerText = "Ẩn";
                        statusElement.className = "badge bg-secondary";
                    }

                    document.querySelector(".overview-order").innerText = order;
                });
            });
        });

        // Card hover effects
        document.querySelectorAll('.category-card').forEach(card => {
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

        .category-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .category-card:hover {
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

        .form-check-input:checked {
            background-color: #11998e;
            border-color: #11998e;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.7);
        }
        .form-control::placeholder {
    color: var(--bs-secondary); /* biến màu xám của Bootstrap */
    opacity: 0.5;
}
    </style>

@endsection



