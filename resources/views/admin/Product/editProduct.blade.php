
@extends('admin.index')
@section('container-fluid')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit product</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Product</a></li>
                        <li class="breadcrumb-item active">Edit product</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <form id="product-form" method="POST" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

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
                                <input type="text" class="form-control" name="name"
                                       value="{{ old('name', $product->name) }}"
                                       placeholder="Nhập tên sản phẩm" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug sản phẩm</label>
                                <input type="text" class="form-control" name="slug"
                                       value="{{ old('slug', $product->slug) }}"
                                       placeholder="Nhập slug sản phẩm" required>
                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả sản phẩm</label>
                                <textarea class="form-control form-control-lg" id="description" name="description"
                                          rows="5" placeholder="Nhập mô tả chi tiết..." required>{{ old('description', $product->description) }}</textarea>
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
                                            <option value="{{ $brand->id }}"
                                                {{ (old('brand_id', $product->brand_id) == $brand->id) ? 'selected' : '' }}>
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
                                            <option value="{{ $category->id }}"
                                                {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
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
                            <!-- Current Featured Image -->
                            @if($product->image)
                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh hiện tại</label>
                                    <div class="current-image mb-2">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="Current product image"
                                             class="img-thumbnail"
                                             style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $product->image ? 'Thay đổi hình ảnh đại diện' : 'Hình ảnh đại diện' }}
                                </label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Current Gallery Images -->
                            @if($product->productImages && $product->productImages->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh gallery hiện tại</label>
                                    <div class="current-gallery d-flex flex-wrap gap-2 mb-3">
                                        @foreach($product->productImages as $image)
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                     alt="Gallery image"
                                                     class="img-thumbnail"
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                                <button type="button"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                        style="transform: translate(25%, -25%);"
                                                        onclick="removeGalleryImage({{ $image->id }})">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Thêm hình ảnh gallery mới</label>
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
                                <!-- Existing Variants -->
                                @if($product->variants && $product->variants->count() > 0)
                                    @foreach($product->variants as $index => $variant)
                                        <div class="variant-item border rounded p-3 mb-3" data-variant-index="{{ $index }}">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Biến thể #{{ $index + 1 }}</h6>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeVariant({{ $index }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Attributes Section for existing variants -->
                                            <div class="attributes-section mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label class="form-label mb-0">Thuộc tính</label>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAttributeToExistingVariant({{ $index }})">
                                                        <i class="bi bi-plus"></i> Thêm thuộc tính
                                                    </button>
                                                </div>
                                                <div class="attributes-container" id="attributes-{{ $index }}">
                                                    {{-- Hiển thị các thuộc tính hiện có --}}
                                                    @php
                                                        $groupedOptions = [];
                                                        foreach ($variant->variantOptions as $variantOption) {
                                                            if (empty($variantOption->attribute)) {
                                                                continue; // tránh trường hợp attribute null => id = 0
                                                            }
                                                            $attrId = $variantOption->attribute->id;
                                                            $attrName = $variantOption->attribute->name ?? 'Không xác định';
                                                            $groupedOptions[$attrId]['name'] = $attrName;
                                                            $groupedOptions[$attrId]['values'][] = $variantOption->option->value ?? '';
                                                        }
                                                    @endphp


                                                    @foreach ($groupedOptions as $attributeId => $data)
                                                        <div class="row mb-2 attribute-row">
                                                            <div class="col-md-4">
                                                                <select class="form-select" name="variants[{{ $index }}][attributes][{{ $loop->index }}][attribute_id]" required>
                                                                    <option value="">-- Chọn thuộc tính --</option>
                                                                    @foreach ($availableAttributes as $attr)
                                                                        <option value="{{ $attr->id }}" {{ $attr->id == $attributeId ? 'selected' : '' }}>
                                                                            {{ $attr->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                               <input type="text" class="form-control"
                                                                    name="variants[{{ $index }}][attributes][{{ $loop->index }}][values]"
                                                                    value="{{ implode(', ', $data['values']) }}"
                                                                    placeholder="Nhập giá trị, cách nhau bởi dấu phẩy" required>

                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeAttribute(this)">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Giá</label>
                                                    <input type="number" class="form-control" step="0.01"
                                                        name="variants[{{ $index }}][price]"
                                                        value="{{ old('variants.'.$index.'.price', $variant->price) }}"
                                                        placeholder="0" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Số lượng</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $index }}][stock_quantity]"
                                                        value="{{ old('variants.'.$index.'.stock_quantity', $variant->stock_quantity) }}"
                                                        placeholder="0" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">SKU</label>
                                                    <input type="text" class="form-control"
                                                        name="variants[{{ $index }}][sku]"
                                                        value="{{ old('variants.'.$index.'.sku', $variant->sku) }}"
                                                        placeholder="SKU">
                                                </div>
                                            </div>

                                            <!-- Hidden field for existing variant ID -->
                                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                        </div>
                                    @endforeach
                                @endif
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
                                    <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                                    <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày xuất bản</label>
                                <input type="date" class="form-control" name="release_date"
                                       value="{{ old('release_date', $product->release_date ? $product->release_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-2"></i>
                        Cập Nhật Sản Phẩm
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
let variantIndex = {{ $product->variants ? $product->variants->count() : 0 }};

// Thêm loading state cho form
function showLoading() {
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Đang cập nhật...';
}

// Form validation trước khi submit
document.getElementById('product-form').addEventListener('submit', function(e) {
    // Loại bỏ các variant rỗng trước khi submit
    removeEmptyVariants();

    if (!validateForm()) {
        e.preventDefault();
        return false;
    }
    showLoading();
});

// HÀM MỚI: Loại bỏ các variant không có dữ liệu
function removeEmptyVariants() {
    const variants = document.querySelectorAll('.variant-item');
    variants.forEach(variant => {
        const price = variant.querySelector('[name*="[price]"]');
        const stockQuantity = variant.querySelector('[name*="[stock_quantity]"]');
        const hasAttributes = variant.querySelectorAll('[name*="[grouped_options]"]').length > 0;
        const hasExistingId = variant.querySelector('[name*="[id]"]');

        // Nếu variant không có giá, số lượng, thuộc tính và không phải là variant đã tồn tại
        if ((!price || !price.value) &&
            (!stockQuantity || !stockQuantity.value) &&
            !hasAttributes &&
            (!hasExistingId || !hasExistingId.value)) {
            variant.remove();
        }
    });
}

function validateForm() {
    let isValid = true;
    const errors = [];

    // Validate required fields
    const requiredFields = ['name', 'slug', 'description', 'brand_id', 'category_id'];
    requiredFields.forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (!input || !input.value.trim()) {
            errors.push(`Trường ${field} là bắt buộc`);
            isValid = false;
        }
    });

    // Validate variants - CẢI THIỆN VALIDATION
    const variants = document.querySelectorAll('.variant-item');
    variants.forEach((variant, index) => {
        const price = variant.querySelector(`[name*="[price]"]`);
        const salePrice = variant.querySelector(`[name*="[sale_price]"]`);
        const stock = variant.querySelector(`[name*="[stock_quantity]"]`);


        if (stock && stock.value && parseInt(stock.value) < 0) {
            errors.push(`Số lượng biến thể #${index + 1} không được âm`);
            isValid = false;
        }
    });

    if (!isValid) {
        alert('Lỗi validation:\n' + errors.join('\n'));
    }

    return isValid;
}

function addVariant() {
    const container = document.getElementById('variants-container');
    const newVariantIndex = variantIndex; // Sử dụng index hiện tại

    const variantHtml = `
        <div class="variant-item border rounded p-3 mb-3" data-variant-index="${newVariantIndex}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Biến thể #${newVariantIndex + 1}</h6>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeVariant(${newVariantIndex})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <!-- Attributes Section -->
            <div class="attributes-section mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Thuộc tính</label>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAttribute(${newVariantIndex})">
                        <i class="bi bi-plus"></i> Thêm thuộc tính
                    </button>
                </div>
                <div class="attributes-container" id="attributes-${newVariantIndex}">
                    <!-- Attributes will be added here -->
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Giá <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" step="0.01" min="0"
                           name="variants[${newVariantIndex}][price]"
                           placeholder="0" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" min="0"
                           name="variants[${newVariantIndex}][stock_quantity]"
                           placeholder="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control"
                           name="variants[${newVariantIndex}][sku]"
                           placeholder="SKU-${newVariantIndex + 1}">
                </div>
            </div>

            <!-- KHÔNG CÓ hidden input id cho variant mới -->
        </div>
    `;

    container.insertAdjacentHTML('beforeend', variantHtml);
    variantIndex++; // Tăng index sau khi thêm
}

function addAttribute(variantIndex) {
    const container = document.getElementById(`attributes-${variantIndex}`);
    const attributeIndex = container.children.length;

    const attributeHtml = `
        <div class="row mb-2 attribute-row">
            <div class="col-md-4">
                <select class="form-select" name="variants[${variantIndex}][grouped_options][${attributeIndex}][attribute_id]" required>
                    <option value="">-- Chọn thuộc tính --</option>
                    @foreach ($availableAttributes as $attr)
                        <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control"
                       name="variants[${variantIndex}][grouped_options][${attributeIndex}][values]"
                       placeholder="Nhập giá trị, cách nhau bởi dấu phẩy" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeAttribute(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', attributeHtml);
}

function removeAttribute(button) {
    button.closest('.attribute-row').remove();
}

function removeVariant(index) {
    if (confirm('Bạn có chắc chắn muốn xóa biến thể này?')) {
        const variantItem = document.querySelector(`[data-variant-index="${index}"]`);
        if (variantItem) {
            // Nếu là variant đã tồn tại, thêm input hidden để đánh dấu xóa
            const existingIdInput = variantItem.querySelector('[name*="[id]"]');
            if (existingIdInput && existingIdInput.value) {
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = `deleted_variants[]`;
                deleteInput.value = existingIdInput.value;
                document.getElementById('product-form').appendChild(deleteInput);
            }

            variantItem.remove();
        }
    }
}

// Cải thiện xử lý xóa gallery image
async function removeGalleryImage(imageId) {
    if (!confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')) {
        return;
    }

    const imageContainer = event.target.closest('.position-relative');
    const deleteBtn = event.target.closest('button');

    // Show loading
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<i class="spinner-border spinner-border-sm"></i>';

    try {
        // AJAX call to delete image
        const response = await fetch(`/admin/product/image/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        });

        if (response.ok) {
            imageContainer.remove();
            showToast('Đã xóa hình ảnh thành công', 'success');
        } else {
            throw new Error('Không thể xóa hình ảnh');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra khi xóa hình ảnh', 'error');

        // Restore button
        deleteBtn.disabled = false;
        deleteBtn.innerHTML = '<i class="bi bi-x"></i>';
    }
}

// Phần còn lại giữ nguyên...
document.getElementById('upload-icon').addEventListener('click', function() {
    document.getElementById('product-images-input').click();
});

document.getElementById('product-images-input').addEventListener('change', function(e) {
    const container = document.getElementById('image-preview-container');
    const maxFileSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

    container.innerHTML = '';

    Array.from(e.target.files).forEach((file, index) => {
        if (file.size > maxFileSize) {
            showToast(`File ${file.name} quá lớn. Kích thước tối đa 5MB`, 'error');
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            showToast(`File ${file.name} không đúng định dạng. Chỉ chấp nhận JPG, PNG, GIF`, 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.createElement('div');
            preview.className = 'position-relative';
            preview.innerHTML = `
                <img src="${e.target.result}"
                     class="img-thumbnail"
                     style="width: 100px; height: 100px; object-fit: cover;">
                <button type="button"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                        style="transform: translate(25%, -25%);"
                        onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </button>
                <div class="file-info">
                    <small class="text-muted">${file.name}</small>
                    <br>
                    <small class="text-muted">${(file.size / 1024).toFixed(1)}KB</small>
                </div>
            `;
            container.appendChild(preview);
        };
        reader.readAsDataURL(file);
    });
});

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

// Auto-generate slug from product name
document.querySelector('[name="name"]').addEventListener('input', function(e) {
    const slugInput = document.querySelector('[name="slug"]');
    if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
        const slug = e.target.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');

        slugInput.value = slug;
        slugInput.dataset.autoGenerated = 'true';
    }
});

document.querySelector('[name="slug"]').addEventListener('input', function(e) {
    e.target.dataset.autoGenerated = 'false';
});
</script>

<style>
        /* Custom Variables */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --card-shadow: 0 10px 30px 0 rgba(62, 87, 111, 0.2);
            --card-hover-shadow: 0 20px 60px 0 rgba(62, 87, 111, 0.3);
            --border-radius: 15px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Background & Layout */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .container-fluid {
            padding: 2rem;
        }

        /* Page Title Enhancement */
        .page-title-box {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            border-left: 5px solid #667eea;
        }

        .page-title-box h4 {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            margin: 0;
        }

        .breadcrumb {
            background: transparent;
            margin: 0;
            padding: 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #667eea;
            content: "→";
        }

        /* Enhanced Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card:hover {
            box-shadow: var(--card-hover-shadow);
            transform: translateY(-5px);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1.5rem 2rem;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #fff 0%, transparent 100%);
            opacity: 0.3;
        }

        .card-header .avatar-sm {
            width: 50px;
            height: 50px;
        }

        .card-header .avatar-title {
            background: rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(10px);
            color: white !important;
            font-size: 1.5rem;
        }

        .card-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-body {
            padding: 2rem;
            background: white;
        }

        /* Form Controls Enhancement */
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
            background: #fafafa;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
            transform: translateY(-2px);
        }

        .form-control:hover, .form-select:hover {
            border-color: #667eea;
            background: white;
        }

        /* Button Enhancements */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: var(--transition);
            border: none;
            padding: 0.75rem 1.5rem;
        }

        .btn-primary {
            background: var(--primary-gradient);
            box-shadow: 0 4px 15px 0 rgba(102, 126, 234, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px 0 rgba(102, 126, 234, 0.5);
        }

        .btn-success {
            background: var(--success-gradient);
            box-shadow: 0 4px 15px 0 rgba(79, 172, 254, 0.35);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px 0 rgba(79, 172, 254, 0.5);
        }

        .btn-danger {
            background: var(--danger-gradient);
            box-shadow: 0 4px 15px 0 rgba(250, 112, 154, 0.35);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px 0 rgba(250, 112, 154, 0.5);
        }

        .btn-add-variant {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: white;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .btn-add-variant:hover {
            background: linear-gradient(135deg, #8fd3f4 0%, #84fab0 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(132, 250, 176, 0.4);
            color: white;
        }

        /* Variant Items */
        .variant-item {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            border: 2px solid #e8ecff !important;
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .variant-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
        }

        .variant-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2);
            border-color: #667eea !important;
        }

        .variant-item h6 {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        /* Image Upload Area */
        .dropzone {
            border: 3px dashed #667eea;
            border-radius: var(--border-radius);
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .dropzone::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent 0%, rgba(102, 126, 234, 0.1) 50%, transparent 100%);
            transition: left 0.5s;
        }

        .dropzone:hover::before {
            left: 100%;
        }

        .dropzone:hover {
            border-color: #4facfe;
            background: linear-gradient(135deg, #fff 0%, #f8f9ff 100%);
            transform: scale(1.02);
        }

        .dropzone .dz-message {
            position: relative;
            z-index: 2;
        }

        /* Image Previews */
        .current-image img, .current-gallery img, #image-preview-container img {
            border-radius: 10px;
            transition: var(--transition);
            border: 3px solid #e9ecef;
        }

        .current-image img:hover, .current-gallery img:hover, #image-preview-container img:hover {
            transform: scale(1.05);
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        /* Alert Enhancements */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border-left: 5px solid;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left-color: #28a745;
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f1b5b9 100%);
            border-left-color: #dc3545;
            color: #721c24;
        }

        /* Sidebar Enhancement */
        .col-3 .card {
            position: sticky;
            top: 2rem;
        }

        /* Custom Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        .card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .card:nth-child(4) {
            animation-delay: 0.3s;
        }

        /* Text Enhancements */
        .text-muted {
            color: #8392a5 !important;
        }

        .text-danger {
            color: #e74c3c !important;
            font-weight: 600;
        }

        /* Responsive Improvements */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .page-title-box {
                padding: 1rem 1.5rem;
            }

            .col-9, .col-3 {
                max-width: 100%;
                flex: 0 0 100%;
            }
        }

        /* Loading States */
        .btn:disabled {
            opacity: 0.7;
            transform: none !important;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Icon Enhancements */
        .bi {
            transition: var(--transition);
        }

        .card-header .bi {
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Form Group Spacing */
        .mb-3 {
            margin-bottom: 2rem !important;
        }

        /* Status Badge */
        .form-select[name="status"] option {
            padding: 0.5rem;
        }

        /* Date Input Enhancement */
        input[type="date"] {
            background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            color: #495057;
        }

        /* Floating Labels Effect */
        .form-floating {
            position: relative;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            color: #667eea;
        }
    </style>

@endsection














