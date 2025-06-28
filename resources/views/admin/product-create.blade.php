@extends('admin.index')
@section('container-fluid')

<div class="container-fluid">

    <!-- start page title -->
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

    <div class="container-fluid py-4">
        <form id="product-form" method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
            @csrf
            @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- eroterot --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-9">
                    <!-- Product Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">Thông Tin Sản Phẩm</h5>
                                    <p class="text-muted mb-0">Điền đầy đủ thông tin sản phẩm.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nhập tên sản phẩm" required>
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug sản phẩm</label>
                                <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" placeholder="Nhập slug sản phẩm" required>
                                @error('slug')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Giá sản phẩm</label>
                                <input type="number" name="price" id="price" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="discount">Giảm giá (nếu có)</label>
                                <input type="number" name="discount" id="discount" class="form-control" value="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả sản phẩm</label>
                                <textarea class="form-control form-control-lg" id="description" name="description" rows="5" placeholder="Nhập mô tả chi tiết..." required>{{ old('description') }}</textarea>
                                @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Thương hiệu</label>
                                <select class="form-select" name="brand_id" required>
                                    <option value="">Chọn thương hiệu</option>
                                    @if(isset($brands) && $brands->count())
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('brand_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Danh mục</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Chọn danh mục</option>
                                    @if(isset($categories) && $categories->count())
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Product Images Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                            <i class="bi bi-images"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">Hình Ảnh Sản Phẩm</h5>
                                    <p class="text-muted mb-0">Thêm hình ảnh đại diện và gallery.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh đại diện</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hình ảnh gallery</label>
                                <div class="dropzone my-dropzone" id="productImagesDropzone">
                                    <div class="dz-message text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-cloud-arrow-up display-4" id="upload-icon" style="cursor: pointer;"></i>
                                            <input type="file" class="form-control" name="images[]" id="product-images-input" multiple accept="image/*" style="display: none;" />
                                            <div id="image-preview-container" class="mt-3 d-flex flex-wrap gap-3"></div>
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

                    <!-- Product Variants Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                            <i class="bi bi-list-ul"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">Biến Thể Sản Phẩm</h5>
                                    <p class="text-muted mb-0">Thêm các biến thể với thuộc tính khác nhau.</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addVariant()">
                                        <i class="bi bi-plus"></i> Thêm biến thể
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="variants-container">
                                <!-- Variants will be added here dynamically -->
                            </div>
                            <button type="button" class="btn btn-add-variant w-100 py-3" onclick="addVariant()">
                                <i class="bi bi-plus-circle me-2"></i>
                                Thêm Biến Thể Mới
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-3">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Xuất Bản</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày xuất bản</label>
                                <input type="date" class="form-control" name="release_date" value="{{ old('release_date') }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-2"></i>
                        Lưu Sản Phẩm
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .form-control::placeholder {
        color: rgba(0, 0, 0, 0.5);
        font-style: italic;
    }

    #productImagesDropzone {
        cursor: pointer;
        padding: 20px;
        text-align: center;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    #productImagesDropzone:hover {
        border-color: #0d6efd;
        background-color: #e7f1ff;
    }

    .form-select:hover {
        cursor: pointer;
    }

    .variant-item {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        position: relative;
    }

    .variant-item:hover {
        border-color: #0d6efd;
        background-color: #e7f1ff;
    }

    .attribute-group {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .option-tag {
        display: inline-block;
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        margin: 2px;
        border: 1px solid #bbdefb;
    }

    .option-tag .remove-option {
        margin-left: 8px;
        cursor: pointer;
        color: #f44336;
    }

    .btn-add-variant {
        border: 2px dashed #28a745;
        background: #f8fff9;
        color: #28a745;
        transition: all 0.3s ease;
    }

    .btn-add-variant:hover {
        background: #28a745;
        color: white;
    }

    .remove-variant-btn {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #dc3545;
        border: none;
        color: white;
        font-size: 12px;
        z-index: 10;
    }

    .preview-item {
        position: relative;
        display: inline-block;
        margin: 5px;
    }

    .preview-item img {
        display: block;
    }

    .preview-item button {
        width: 100%;
        margin-top: 5px;
    }

</style>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ==== 1. Upload ảnh phụ với preview và xóa ====
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

                // Append new files to existing ones
                const dt = new DataTransfer();
                // Lấy các file cũ
                Array.from(fileInput.files).forEach(file => dt.items.add(file));
                // Thêm file mới
                Array.from(e.dataTransfer.files).forEach(file => dt.items.add(file));

                fileInput.files = dt.files;
                fileInput.dispatchEvent(new Event('change'));
            });

            uploadIcon ? .addEventListener('click', () => fileInput.click());

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
                            width: '100px'
                            , height: '100px'
                            , objectFit: 'cover'
                        });

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-danger btn-sm mt-2';
                        removeBtn.textContent = 'Xóa';

                        removeBtn.addEventListener('click', e => {
                            e.preventDefault();
                            previewItem.remove();

                            const dt = new DataTransfer();
                            // Khi xóa, cập nhật lại fileInput.files, bỏ file index bị xóa
                            Array.from(fileInput.files).forEach((f, i) => {
                                if (i !== index) dt.items.add(f);
                            });
                            fileInput.files = dt.files;

                            // Sau khi xóa, cập nhật lại preview để index chính xác
                            // Gọi lại event change để render lại preview
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

        // ==== 2. Xử lý biến thể sản phẩm ====
        let variantCount = 0;
        let attributeCounter = 0; // Counter cho attributes

        // Dữ liệu thuộc tính lấy từ backend (Laravel Blade)
        const availableAttributes = @json($availableAttributes);

        // Dữ liệu biến thể cũ (nếu có) khi validate lỗi quay lại
        const oldVariants = @json(old('variants', []));

        if (typeof oldVariants === 'object' && oldVariants !== null) {
            Object.entries(oldVariants).forEach(([key, variant]) => {
                addVariant(key, variant);
            });
        }

        function updateOptionsDisplay(variantId, attributeId) {
            const container = document.getElementById(`options-display-${variantId}-${attributeId}`);
            const hiddenContainer = document.getElementById(`options-hidden-container-${variantId}-${attributeId}`);
            if (!hiddenContainer) return;

            const options = Array.from(hiddenContainer.querySelectorAll('input')).map(i => i.value);

            container.innerHTML = options.map(opt => `
        <span class="option-tag me-2 mb-1 d-inline-block">
            ${opt}
            <button type="button" class="btn btn-sm btn-link text-danger remove-option"
                data-variant-id="${variantId}" data-attribute-id="${attributeId}" data-option-value="${opt}">
                <i class="bi bi-x"></i>
            </button>
        </span>
    `).join('');
        }

        function addAttribute(variantId, attributeKey = null, attributeData = null) {
            const container = document.getElementById(`attributes-container-${variantId}`);
            const attributeId = attributeKey || (++attributeCounter);
            attributeCounter = Math.max(attributeCounter, parseInt(attributeId));

            const selectedAttribute = attributeData ? .attribute_id || '';
            const options = attributeData ? .options || [];

            const attributeDiv = document.createElement('div');
            attributeDiv.className = 'attribute-group mb-3';
            attributeDiv.id = `attribute-${variantId}-${attributeId}`;

            attributeDiv.innerHTML = `
    <div class="d-flex align-items-center gap-2">
        <select class="form-select attribute-select" name="variants[${variantId}][attributes][${attributeId}][attribute_id]" required style="flex: 1;">
            <option value="">-- Chọn thuộc tính --</option>
            ${availableAttributes.map(attr => `
                <option value="${attr.id}" ${attr.id == selectedAttribute ? 'selected' : ''}>${attr.name}</option>
            `).join('')}
        </select>

        <div style="flex: 2; display: flex; align-items: center; gap: 4px;">
            <input type="text" class="form-control form-control-sm" id="option-input-${variantId}-${attributeId}" placeholder="Nhập giá trị và nhấn Enter">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addOptionToAttribute(${variantId}, ${attributeId})">
                <i class="bi bi-plus"></i>
            </button>
        </div>

        <button type="button" class="btn btn-sm btn-outline-danger remove-attribute-btn" data-variant-id="${variantId}" data-attribute-id="${attributeId}">
            <i class="bi bi-trash"></i>
        </button>
    </div>

    <div id="options-hidden-container-${variantId}-${attributeId}">
        ${options.map(opt => `<input type="hidden" name="variants[${variantId}][attributes][${attributeId}][options][]" value="${opt}">`).join('')}
    </div>

    <div id="options-display-${variantId}-${attributeId}" class="mt-2">
        ${options.map(opt => `
            <span class="option-tag me-2 mb-1 d-inline-block">
                ${opt}
                <button type="button" class="btn btn-sm btn-link text-danger remove-option"
                    data-variant-id="${variantId}" data-attribute-id="${attributeId}" data-option-value="${opt}">
                    <i class="bi bi-x"></i>
                </button>
            </span>
        `).join('')}
    </div>
`;


            container.appendChild(attributeDiv);
        }


        window.addVariant = function(variantKey = null, variantData = null) {
            const id = variantKey || (++variantCount);
            variantCount = Math.max(variantCount, parseInt(id));

            const container = document.getElementById('variants-container');

            const sku = variantData ? .sku || '';
            const price = variantData ? .price || '';
            const stock = variantData ? .stock_quantity || '';

            const variantDiv = document.createElement('div');
            variantDiv.className = 'variant-item';
            variantDiv.id = `variant-${id}`;

            variantDiv.innerHTML = `
            <button type="button" class="remove-variant-btn btn-close" data-variant-id="${id}"></button>
            <div class="row mb-3">
                <div class="col-md-12">
                    <h6 class="text-primary"><i class="bi bi-gear me-2"></i> Biến thể #${id}</h6>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="variants[${id}][sku]" value="${sku}" placeholder="Mã SKU">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Giá (VND)</label>
                    <input type="text" class="form-control" name="variants[${id}][price]" value="${price}" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Số lượng</label>
                    <input type="number" class="form-control" name="variants[${id}][stock_quantity]" value="${stock}" min="0" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Thuộc tính biến thể</label>
                <div id="attributes-container-${id}"></div>
                <button type="button" class="btn btn-outline-primary btn-sm add-attribute-btn" data-variant-id="${id}">
                    <i class="bi bi-plus"></i> Thêm thuộc tính
                </button>
            </div>
        `;

            container.appendChild(variantDiv);

            if (variantData ? .attributes) {
                Object.entries(variantData.attributes).forEach(([attrKey, attrData]) => {
                    addAttribute(id, attrKey, attrData);
                });
            }
        };

        function addOptionToAttribute(variantId, attributeId) {
            const input = document.getElementById(`option-input-${variantId}-${attributeId}`);
            const value = input.value.trim();
            if (!value) return;

            // Kiểm tra xem option này đã tồn tại chưa
            const container = document.getElementById(`options-hidden-container-${variantId}-${attributeId}`);
            if (!container) return;

            // Tạo input hidden mới cho option
            const exists = Array.from(container.querySelectorAll('input')).some(i => i.value === value);
            if (exists) return;

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `variants[${variantId}][attributes][${attributeId}][options][]`;
            hiddenInput.value = value;
            container.appendChild(hiddenInput);

            updateOptionsDisplay(variantId, attributeId);

            input.value = '';
        }

        function removeOptionFromAttribute(variantId, attributeId, value) {
            const hiddenContainer = document.getElementById(`options-hidden-container-${variantId}-${attributeId}`);
            if (!hiddenContainer) return;

            const inputs = Array.from(hiddenContainer.querySelectorAll('input'));
            inputs.forEach(i => {
                if (i.value === value) {
                    hiddenContainer.removeChild(i);
                }
            });

            updateOptionsDisplay(variantId, attributeId);
        }

        // ==== 3. Event delegation ====
        document.addEventListener('click', e => {
            const btn = e.target.closest('button');
            if (!btn) return;

            const {
                variantId
                , attributeId
                , optionValue
            } = btn.dataset;

            if (btn.classList.contains('remove-variant-btn')) {
                document.getElementById(`variant-${variantId}`) ? .remove();
            }

            if (btn.classList.contains('add-attribute-btn')) {
                addAttribute(variantId);
            }

            if (btn.classList.contains('remove-attribute-btn')) {
                document.getElementById(`attribute-${variantId}-${attributeId}`) ? .remove();
            }

            if (btn.classList.contains('remove-option')) {
                removeOptionFromAttribute(variantId, attributeId, optionValue);
            }
        });

        // ==== 4. Enter để thêm option nhanh ====
        document.addEventListener('keydown', e => {
            if (e.key === 'Enter' && e.target.id ? .startsWith('option-input-')) {
                e.preventDefault();
                const parts = e.target.id.split('-');
                if (parts.length >= 4) {
                    addOptionToAttribute(parts[2], parts[3]);
                }
            }
        });

        // ==== 5. Form validation trước khi submit ====
        document.getElementById('product-form') ? .addEventListener('submit', function(e) {
            const variants = document.querySelectorAll('.variant-item');
            let hasError = false;

            variants.forEach(variant => {
                const attributes = variant.querySelectorAll('.attribute-group');

                attributes.forEach(attr => {
                    const select = attr.querySelector('.attribute-select');
                    // Lấy tất cả input hidden options
                    const optionInputs = attr.querySelectorAll('input[type="hidden"]');

                    if (select) {
                        const attributeId = select.value;
                        const options = Array.from(optionInputs).map(i => i.value);

                        if (!attributeId) {
                            alert('Vui lòng chọn thuộc tính cho tất cả biến thể!');
                            hasError = true;
                            return;
                        }

                        if (options.length === 0) {
                            alert('Vui lòng thêm ít nhất một giá trị cho thuộc tính!');
                            hasError = true;
                            return;
                        }
                    }
                });
            });

            if (hasError) {
                e.preventDefault();
            }
        });

    });

</script>

@endsection
