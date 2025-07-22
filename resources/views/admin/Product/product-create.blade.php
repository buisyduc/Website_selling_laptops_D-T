@extends('admin.index')
@section('container-fluid')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Create product</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Product</a></li>
                            <li class="breadcrumb-item active">Create product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-3">
            <form id="product-form" method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                @csrf

                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <!-- Left side -->
                    <div class="col-lg-9">
                        <!-- Product Info -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Thông Tin Sản Phẩm</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tên sản phẩm</label>
                                            <input type="text" class="form-control" id="product-name" name="name"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Slug sản phẩm</label>
                                        <input type="text" class="form-control" id="product-slug" name="slug"
                                            value="{{ old('slug') }}">
                                        @error('slug')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Mô tả</label>
                                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Thương hiệu</label>
                                        <select name="brand_id" class="form-select">
                                            <option value="">Chọn thương hiệu</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Danh mục</label>
                                        <select name="category_id" class="form-select">
                                            <option value="">Chọn danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Images -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Hình Ảnh Sản Phẩm</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Hình ảnh đại diện</label>
                                        <input type="file" class="form-control" name="image" accept="image/*">
                                        @error('image')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Hình ảnh gallery</label>
                                        <div class="dropzone my-dropzone" id="productImagesDropzone">
                                            <div class="dz-message text-center">
                                                <div class="mb-3">
                                                    <i class="bi bi-cloud-arrow-up display-4" id="upload-icon"
                                                        style="cursor: pointer;"></i>
                                                    <input type="file" class="form-control" name="images[]"
                                                        id="product-images-input" multiple accept="image/*"
                                                        style="display: none;" />
                                                    <div id="image-preview-container" class="mt-3 d-flex flex-wrap gap-3">
                                                    </div>
                                                </div>
                                                <h5>Drop files here or click to upload.</h5>
                                            </div>
                                        </div>
                                        @error('images')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @error('images.*')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Variants -->
                        <div class="card mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Biến Thể Sản Phẩm</h5>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addVariant()">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm biến thể
                                </button>
                            </div>
                            <div class="card-body" id="variants-container">
                                <!-- Biến thể sẽ được thêm bằng JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Right sidebar -->
                    <div class="col-lg-3">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Xuất Bản</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày xuất bản</label>
                                    <input type="date" name="release_date" class="form-control"
                                        value="{{ old('release_date') }}">
                                    @error('release_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-2"></i> Lưu Sản Phẩm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
        <style>
        /* Global Styles */
        body {
            background-color: #f8f9fa;
            font-size: 14px;
        }

        /* Compact Card Styles */
        .card {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            margin-bottom: 1rem;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 8px 8px 0 0;
            border: none;
        }

        .card-header h5 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .card-header p {
            margin: 0;
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .card-body {
            padding: 1rem;
        }

        /* Compact Avatar */
        .avatar-sm {
            width: 32px;
            height: 32px;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border: 1px solid #d1d3e2;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #5a5c69;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Compact spacing */
        .mb-3 {
            margin-bottom: 0.8rem !important;
        }

        /* Button Styles */
        .btn {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(17, 153, 142, 0.3);
        }

        .btn-add-variant {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add-variant:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(240, 147, 251, 0.4);
            color: white;
        }

        /* Dropzone Styles */
        .my-dropzone {
            border: 2px dashed #667eea;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .my-dropzone:hover {
            border-color: #764ba2;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }

        .my-dropzone h5 {
            color: #667eea;
            font-size: 0.9rem;
            margin: 0;
        }

        /* Page Title */
        .page-title-box {
            margin-bottom: 1rem;
            padding: 1rem 0;
        }

        .page-title-box h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #5a5c69;
            margin: 0;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.8rem;
        }

        .breadcrumb-item a {
            color: #667eea;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #858796;
        }

        /* Alert Messages */
        .alert {
            border-radius: 6px;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.1) 0%, rgba(56, 239, 125, 0.1) 100%);
            border: 1px solid rgba(17, 153, 142, 0.2);
            color: #11998e;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(245, 87, 108, 0.1) 0%, rgba(240, 147, 251, 0.1) 100%);
            border: 1px solid rgba(245, 87, 108, 0.2);
            color: #f5576c;
        }

        /* Sidebar */
        .col-3 .card {
            position: sticky;
            top: 1rem;
        }

        /* Image Preview */
        #image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .image-preview {
            position: relative;
            width: 80px;
            height: 80px;
            border-radius: 6px;
            overflow: hidden;
            border: 2px solid #e3e6f0;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview .remove-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: #f5576c;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Variant Container */
        .variant-item {
            border: 1px solid #e3e6f0;
            border-radius: 6px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
        }

        .variant-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .variant-title {
            font-weight: 600;
            color: #5a5c69;
            font-size: 0.9rem;
        }

        .remove-variant-btn {
            background: #f5576c;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {

            .col-9,
            .col-3 {
                width: 100%;
            }

            .card-body {
                padding: 0.75rem;
            }

            .btn {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }

        /* Animation */
        .card {
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
<script>

document.addEventListener('DOMContentLoaded', function () {
    // 1. Slug tự động từ tên sản phẩm
    const nameInput = document.getElementById('product-name');
    const slugInput = document.getElementById('product-slug');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function () {
            const slug = nameInput.value
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim().replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            slugInput.value = slug;
        });
    }

    // 2. Upload ảnh phụ
    const uploadIcon = document.getElementById('upload-icon');
    const fileInput = document.getElementById('product-images-input');
    const previewContainer = document.getElementById('image-preview-container');
    const dropzone = document.getElementById('productImagesDropzone');

    if (dropzone && fileInput) {
        dropzone.addEventListener('dragover', e => {
            e.preventDefault();
            dropzone.style.borderColor = '#0d6efd';
        });
        dropzone.addEventListener('dragleave', () => {
            dropzone.style.borderColor = '#dee2e6';
        });
        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.style.borderColor = '#dee2e6';
            const dt = new DataTransfer();
            Array.from(fileInput.files).forEach(file => dt.items.add(file));
            Array.from(e.dataTransfer.files).forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            fileInput.dispatchEvent(new Event('change'));
        });
        uploadIcon?.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', event => {
            const files = event.target.files;
            previewContainer.innerHTML = '';
            Array.from(files).forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = e => {
                    const previewItem = document.createElement('div');
                    previewItem.classList.add('preview-item');
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    Object.assign(img.style, {
                        width: '100px',
                        height: '100px',
                        objectFit: 'cover'
                    });

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-danger btn-sm mt-2';
                    removeBtn.textContent = 'Xóa';
                    removeBtn.addEventListener('click', e => {
                        e.preventDefault();
                        previewItem.remove();
                        const dt = new DataTransfer();
                        Array.from(fileInput.files).forEach((f, i) => {
                            if (i !== index) dt.items.add(f);
                        });
                        fileInput.files = dt.files;
                        fileInput.dispatchEvent(new Event('change'));
                    });

                    previewItem.appendChild(img);
                    previewItem.appendChild(removeBtn);
                    previewContainer.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // 3. Biến thể sản phẩm
    let variantCount = 0;
    let attributeCounter = 0;
    const availableAttributes = @json($availableAttributes);
    const oldVariants = @json(old('variants', []));

    if (typeof oldVariants === 'object' && oldVariants !== null) {
        Object.entries(oldVariants).forEach(([key, variant]) => {
            addVariant(key, variant);
        });
    }

    window.addVariant = function (variantKey = null, variantData = null) {
        const id = variantKey || (++variantCount);
        variantCount = Math.max(variantCount, parseInt(id));
        const container = document.getElementById('variants-container');
        const price = variantData?.price || '';
        const stock = variantData?.stock_quantity || '';
        const variantDiv = document.createElement('div');
        variantDiv.className = 'variant-item';
        variantDiv.id = `variant-${id}`;
        variantDiv.innerHTML = `
            <div class="position-relative border rounded p-3 mb-3 bg-light-subtle">
                <button type="button" class="btn btn-outline-danger position-absolute top-0 end-0 m-2 remove-variant-btn"
                    data-variant-id="${id}" aria-label="Xoá"><i class="bi bi-trash"></i></button>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6 class="text-primary"><i class="bi bi-gear me-2"></i> Biến thể #${id}</h6>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><label>Giá (VND)</label>
                        <input type="number" class="form-control" name="variants[${id}][price]" value="${price}" min="0">
                    </div>
                    <div class="col-md-4"><label>Số lượng</label>
                        <input type="number" class="form-control" name="variants[${id}][stock_quantity]" value="${stock}" min="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Thuộc tính biến thể</label>
                    <div id="attributes-container-${id}"></div>
                    <button type="button" class="btn btn-outline-primary btn-sm add-attribute-btn" data-variant-id="${id}">
                        <i class="bi bi-plus"></i> Thêm thuộc tính
                    </button>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-secondary btn-sm copy-variant-btn" data-variant-id="${id}">
                        <i class="bi bi-files"></i> Copy biến thể
                    </button>
                </div>
            </div>`;
        container.appendChild(variantDiv);

        if (variantData?.attributes) {
            Object.entries(variantData.attributes).forEach(([attrKey, attrData]) => {
                addAttribute(id, attrKey, attrData);
            });
        }
    };

    function addAttribute(variantId, attributeKey = null, attributeData = null) {
        const container = document.getElementById(`attributes-container-${variantId}`);
        const attributeId = attributeKey || (++attributeCounter);
        attributeCounter = Math.max(attributeCounter, parseInt(attributeId));
        const selectedAttribute = attributeData?.attribute_id || '';
        const selectedOptions = attributeData?.options || [];
        const attributeDiv = document.createElement('div');
        attributeDiv.className = 'attribute-group mb-3';
        attributeDiv.id = `attribute-${variantId}-${attributeId}`;
        const selectHTML = availableAttributes.map(attr => `
            <option value="${attr.id}" ${attr.id == selectedAttribute ? 'selected' : ''}>${attr.name}</option>
        `).join('');

        attributeDiv.innerHTML = `
            <div class="d-flex gap-2 align-items-center">
                <select class="form-select attribute-select"
                    name="variants[${variantId}][attributes][${attributeId}][attribute_id]"
                    data-variant-id="${variantId}" data-attribute-id="${attributeId}">
                    <option value="">-- Chọn thuộc tính --</option>
                    ${selectHTML}
                </select>
                <select class="form-select option-select"
                    name="variants[${variantId}][attributes][${attributeId}][options][]"
                    id="option-select-${variantId}-${attributeId}" style="width:250px;"></select>
                <span id="selected-value-${variantId}-${attributeId}" class="selected-value"
                    style="display:none;cursor:pointer;padding:5px 10px;border:1px dashed #ccc;border-radius:4px;"></span>
                <button type="button" class="btn btn-outline-danger btn-sm remove-attribute-btn"
                    data-variant-id="${variantId}" data-attribute-id="${attributeId}"><i class="bi bi-trash"></i></button>
            </div>`;
        container.appendChild(attributeDiv);

        const attrSelect = attributeDiv.querySelector('.attribute-select');
        if (attrSelect) {
            attrSelect.addEventListener('change', function () {
                updateOptionsSelect(this.dataset.variantId, this.dataset.attributeId, this.value, []);
            });
        }

        if (selectedAttribute) {
            updateOptionsSelect(variantId, attributeId, selectedAttribute, selectedOptions);
        }
    }

    function updateOptionsSelect(variantId, attributeId, attributeValue, selected = []) {
        const selectEl = document.getElementById(`option-select-${variantId}-${attributeId}`);
        const spanEl = document.getElementById(`selected-value-${variantId}-${attributeId}`);
        if (!selectEl || !spanEl) return;
        const attr = availableAttributes.find(a => a.id == attributeValue);
        if (!attr) return;

        selectEl.innerHTML = attr.options.map(opt => {
            const isSelected = selected.includes(opt.id.toString()) || selected.includes(opt.id);
            return `<option value="${opt.id}" ${isSelected ? 'selected' : ''}>${opt.value}</option>`;
        }).join('');

        selectEl.onchange = () => {
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            if (!selectedOption) return;
            spanEl.innerText = selectedOption.text;
            spanEl.style.display = 'inline-block';
            selectEl.style.display = 'none';
        };

        spanEl.onclick = () => {
            spanEl.style.display = 'none';
            selectEl.style.display = 'block';
        };

        if (selected.length > 0) {
            const selectedOption = attr.options.find(opt => selected.includes(opt.id.toString()) || selected.includes(opt.id));
            if (selectedOption) {
                spanEl.innerText = selectedOption.value;
                spanEl.style.display = 'inline-block';
                selectEl.style.display = 'none';
            }
        } else {
            spanEl.style.display = 'none';
            selectEl.style.display = 'block';
        }
    }

    // 4. Các sự kiện động: thêm, xoá, copy
    document.addEventListener('click', function (e) {
        if (e.target.closest('.add-attribute-btn')) {
            const btn = e.target.closest('.add-attribute-btn');
            addAttribute(btn.dataset.variantId);
        }

        if (e.target.closest('.remove-variant-btn')) {
            const id = e.target.closest('.remove-variant-btn').dataset.variantId;
            document.getElementById(`variant-${id}`)?.remove();
        }

        if (e.target.closest('.remove-attribute-btn')) {
            const btn = e.target.closest('.remove-attribute-btn');
            const variantId = btn.dataset.variantId;
            const attributeId = btn.dataset.attributeId;
            document.getElementById(`attribute-${variantId}-${attributeId}`)?.remove();
        }

        if (e.target.closest('.copy-variant-btn')) {
            const btn = e.target.closest('.copy-variant-btn');
            const variantId = btn.dataset.variantId;
            const el = document.getElementById(`variant-${variantId}`);
            if (!el) return;

            const price = el.querySelector(`[name="variants[${variantId}][price]"]`)?.value || '';
            const stock = el.querySelector(`[name="variants[${variantId}][stock_quantity]"]`)?.value || '';

            const attributes = [];
            el.querySelectorAll('.attribute-group').forEach(group => {
                const attrId = group.querySelector('.attribute-select')?.value;
                const selectedOpts = Array.from(group.querySelectorAll('.option-select option:checked')).map(opt => opt.value);
                if (attrId) {
                    attributes.push({ attribute_id: attrId, options: selectedOpts });
                }
            });

            const attrObj = {};
            attributes.forEach((a, i) => attrObj[i + 1] = a);

            
        }
    });
});
</script>


@endsection
