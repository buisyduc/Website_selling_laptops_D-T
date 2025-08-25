@extends('admin.index')
@section('container-fluid')
    <div class="container-fluid">

        <!-- Enhanced Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary p-4 rounded-3 text-white shadow-sm">
                    <div>
                        <h3 class="mb-1 fw-bold">Chỉnh sửa danh mục</h3>
                        <p class="mb-0 opacity-75">Cập nhật thông tin danh mục sản phẩm</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 bg-transparent">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);" class="text-white-50 text-decoration-none">Sản phẩm</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('categories') }}" class="text-white-50 text-decoration-none">Danh mục</a>
                            </li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Chỉnh sửa danh mục</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Enhanced Edit Form -->
            <div class="col-xl-8 col-lg-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-info text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="ri-edit-circle-line fs-4 me-2"></i>
                                <h5 class="card-title mb-0 fw-semibold">Chỉnh sửa danh mục: {{ $category->name }}</h5>
                            </div>
                            <a href="{{ route('categories') }}" class="btn btn-light btn-sm">
                                <i class="ri-arrow-left-line me-1"></i>Quay lại danh sách
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="ri-check-circle-line me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="ri-error-warning-line me-2"></i>Vui lòng sửa các lỗi sau:
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('categories.update', $category->id) }}" method="POST"
                              enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
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
                                                   value="{{ old('name', $category->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Slug -->
                                    <div class="mb-4">
                                        <label for="slug" class="form-label fw-semibold">
                                            <i class="ri-link me-1 text-info"></i>Đường dẫn (slug)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-hashtag text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0 @error('slug') is-invalid @enderror"
                                                   id="slug" name="slug" placeholder="slug-tự-động"
                                                   value="{{ old('slug', $category->slug) }}" required>
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Parent Category -->
                                    <div class="mb-4">
                                        <label for="parent_id" class="form-label fw-semibold">
                                            <i class="ri-node-tree me-1 text-secondary"></i>Danh mục cha
                                        </label>
                                        <select class="form-select @error('parent_id') is-invalid @enderror"
                                                id="parent_id" name="parent_id">
                                            <option value="">-- Không có danh mục cha --</option>
                                            @foreach ($allCategories as $cat)
                                                @if($cat->id !== $category->id) {{-- Prevent self-selection --}}
                                                    <option value="{{ $cat->id }}"
                                                            {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Display Order -->
                                    <div class="mb-4">
                                        <label for="order" class="form-label fw-semibold">
                                            <i class="ri-sort-asc me-1 text-primary"></i>Thứ tự hiển thị
                                        </label>
                                        <input type="number" class="form-control @error('order') is-invalid @enderror"
                                               id="order" name="order" value="{{ old('order', $category->order) }}"
                                               min="0" placeholder="0">
                                        @error('order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- Current Image Display -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class="ri-image-line me-1 text-warning"></i>Ảnh hiện tại
                                        </label>
                                        <div class="current-image-container p-3 bg-light rounded">
                                            @if($category->image)
                                                <div class="text-center">
                                                    <img src="{{ asset('storage/' . $category->image) }}"
                                                         alt="{{ $category->name }}"
                                                         class="img-fluid rounded border shadow-sm current-image"
                                                         style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                                    <p class="text-muted small mt-2 mb-0">
                                                        <i class="ri-information-line me-1"></i>Ảnh danh mục hiện tại
                                                    </p>
                                                </div>
                                            @else
                                                <div class="text-center text-muted py-4">
                                                    <i class="ri-image-line fs-1 mb-2"></i>
                                                    <p class="mb-0">Chưa tải ảnh nào</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- New Image Upload -->
                                    <div class="mb-4">
                                        <label for="image" class="form-label fw-semibold">
                                            <i class="ri-upload-line me-1 text-warning"></i>Cập nhật ảnh
                                        </label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                               id="image" name="image" accept="image/*" onchange="previewNewImage(this)">
                                        <div class="form-text">
                                            <i class="ri-information-line me-1"></i>Để trống nếu muốn giữ nguyên ảnh hiện tại.
                                            Định dạng hỗ trợ: JPG, PNG, GIF (Tối đa: 2MB)
                                        </div>

                                        <!-- New Image Preview -->
                                        <div class="mt-3" id="new-image-preview" style="display: none;">
                                            <label class="form-label small text-success fw-semibold">Xem trước ảnh mới:</label>
                                            <div class="text-center p-3 bg-success-subtle rounded">
                                                <img id="preview-new-img" class="img-fluid rounded border"
                                                     style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                            </div>
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status Toggle -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class="ri-eye-line me-1 text-success"></i>Trạng thái hiển thị
                                        </label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status" name="status"
                                                   value="1" {{ old('status', $category->status) ? 'checked' : '' }}
                                                   onchange="updateStatusDisplay()">
                                            <label class="form-check-label fw-medium" for="status" id="status-label">
                                                @if(old('status', $category->status))
                                                    <span class="badge bg-success">
                                                        <i class="ri-eye-line me-1"></i>Hiển thị
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="ri-eye-off-line me-1"></i>Ẩn
                                                    </span>
                                                @endif
                                            </label>
                                        </div>
                                        <div class="form-text">
                                            <i class="ri-information-line me-1"></i>Bật/tắt để hiển thị/ẩn danh mục này trên website
                                        </div>
                                    </div>
                                </div>

                                <!-- Full Width Description -->
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label for="description" class="form-label fw-semibold">
                                            <i class="ri-file-text-line me-1 text-info"></i>Mô tả
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="4"
                                                  placeholder="Nhập mô tả chi tiết cho danh mục">{{ old('description', $category->description) }}</textarea>
                                        <div class="form-text">
                                            <i class="ri-information-line me-1"></i>Cung cấp mô tả chi tiết cho danh mục này
                                        </div>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                                        <a href="{{ route('categories') }}" class="btn btn-light btn-lg px-4">
                                            <i class="ri-close-line me-2"></i>Hủy
                                        </a>
                                        
                                        <button type="submit" class="btn btn-success btn-lg px-5">
                                            <i class="ri-save-line me-2"></i>Cập nhật danh mục
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Category Info Card -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0">
                            <i class="ri-information-line me-2 text-info"></i>Thông tin danh mục
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="badge bg-primary-subtle text-primary p-2 rounded-circle mb-2">
                                        <i class="ri-calendar-line fs-5"></i>
                                    </div>
                                    <h6 class="mb-1">Ngày tạo</h6>
                                    <p class="text-muted small mb-0">{{ $category->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="badge bg-info-subtle text-info p-2 rounded-circle mb-2">
                                        <i class="ri-refresh-line fs-5"></i>
                                    </div>
                                    <h6 class="mb-1">Cập nhật lần cuối</h6>
                                    <p class="text-muted small mb-0">{{ $category->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="badge bg-warning-subtle text-warning p-2 rounded-circle mb-2">
                                        <i class="ri-database-line fs-5"></i>
                                    </div>
                                    <h6 class="mb-1">Mã danh mục</h6>
                                    <p class="text-muted small mb-0">#{{ $category->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="badge bg-success-subtle text-success p-2 rounded-circle mb-2">
                                        <i class="ri-eye-line fs-5"></i>
                                    </div>
                                    <h6 class="mb-1">Trạng thái hiện tại</h6>
                                    <p class="mb-0">
                                        @if($category->status)
                                            <span class="badge bg-success">Hiển thị</span>
                                        @else
                                            <span class="badge bg-secondary">Ẩn</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // New Image Preview Function
        function previewNewImage(input) {
            const preview = document.getElementById('new-image-preview');
            const previewImg = document.getElementById('preview-new-img');

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
        function updateStatusDisplay() {
            const checkbox = document.getElementById('status');
            const label = document.getElementById('status-label');

            if (checkbox.checked) {
                label.innerHTML = '<span class="badge bg-success"><i class="ri-eye-line me-1"></i>Hiển thị</span>';
            } else {
                label.innerHTML = '<span class="badge bg-secondary"><i class="ri-eye-off-line me-1"></i>Ẩn</span>';
            }
        }

        // Auto-generate slug from name (optional for editing)
        document.getElementById('name').addEventListener('input', function() {
            const slugField = document.getElementById('slug');
            // Only auto-generate if slug field is empty
            if (!slugField.value.trim()) {
                const name = this.value;
                const slug = name.toLowerCase()
                                .replace(/[^\w\s-]/g, '')
                                .replace(/\s+/g, '-')
                                .replace(/-+/g, '-')
                                .trim();
                slugField.value = slug;
            }
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

        // Confirm before leaving with unsaved changes
        let formChanged = false;
        document.querySelector('form').addEventListener('input', function() {
            formChanged = true;
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Reset form changed flag on submit
        document.querySelector('form').addEventListener('submit', function() {
            formChanged = false;
        });
    </script>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #00d4ff 0%, #090979 100%);
        }

        .current-image {
            transition: transform 0.3s ease;
        }

        .current-image:hover {
            transform: scale(1.05);
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
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

        .form-control:first-child, .form-select {
            border-radius: 8px;
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

        .current-image-container {
            border: 2px dashed #dee2e6;
            transition: border-color 0.3s ease;
        }

        .current-image-container:hover {
            border-color: #adb5bd;
        }

        .badge {
            font-size: 0.875em;
        }

        .needs-validation .form-control:invalid {
            border-color: #dc3545;
        }

        .needs-validation .form-control:valid {
            border-color: #198754;
        }
    </style>

@endsection
