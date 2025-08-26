@extends('admin.index')
@section('container-fluid')

    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Chỉnh sửa sản phẩm</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Sản phẩm</a></li>
                            <li class="breadcrumb-item active">Chỉnh sửa sản phẩm</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <form id="product-form" method="POST" action="{{ route('product.update', $product->id) }}"
                enctype="multipart/form-data">
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
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name', $product->name) }}" placeholder="Nhập tên sản phẩm"
                                                required>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Slug sản phẩm</label>
                                        <input type="text" class="form-control" name="slug"
                                            value="{{ old('slug', $product->slug) }}" placeholder="Nhập slug sản phẩm"
                                            required>
                                        @error('slug')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Mô tả sản phẩm</label>
                                        <textarea class="form-control form-control-lg" id="description" name="description" rows="5"
                                            placeholder="Nhập mô tả chi tiết..." required>{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Thương hiệu</label>
                                        <select class="form-select" name="brand_id" required>
                                            <option value="">Chọn thương hiệu</option>
                                            @if (isset($brands) && $brands->count())
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('brand_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Danh mục</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Chọn danh mục</option>
                                            @if (isset($categories) && $categories->count())
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                @if ($product->image)
                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh hiện tại</label>
                                        <div class="current-image mb-2">
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="Current product image" class="img-thumbnail"
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
                                @if ($product->productImages && $product->productImages->count() > 0)
                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh gallery hiện tại</label>
                                        <div class="current-gallery d-flex flex-wrap gap-2 mb-3">
                                            @foreach ($product->productImages as $image)
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                                        alt="Gallery image" class="img-thumbnail"
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
@php
    $availableAttributesJson = $availableAttributes->map(function($attr) {
        return [
            'id' => $attr->id,
            'name' => $attr->name,
            'options' => $attr->options->map(fn($opt) => ['id' => $opt->id, 'value' => $opt->value]),
        ];
    });
@endphp

@foreach ($product->variants as $index => $variant)
    @php
        $groupedOptions = [];
        foreach ($variant->variantOptions as $variantOption) {
            if (!$variantOption->attribute || !$variantOption->option) continue;
            $attrId = $variantOption->attribute->id;
            $optionId = $variantOption->option->id;
            $optionValue = $variantOption->option->value;

            if (!isset($groupedOptions[$attrId])) {
                $groupedOptions[$attrId] = ['options' => []];
            }

            $groupedOptions[$attrId]['options'][] = [
                'id' => $optionId,
                'value' => $optionValue
            ];
        }
    @endphp

    <div class="border p-3 rounded bg-light-subtle position-relative mb-4" id="variant-{{ $index }}">
        <button type="button" class="btn btn-sm btn-outline-danger position-absolute end-0 top-0 m-2"
            onclick="document.getElementById('variant-{{ $index }}').remove()">
            <i class="bi bi-trash"></i>
        </button>

        <h6 class="text-primary mb-3"><i class="bi bi-gear"></i> Biến thể #{{ $index + 1 }}</h6>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>SKU</label>
                <input type="text" class="form-control" name="variants[{{ $index }}][sku]" value="{{ $variant->sku }}">
            </div>
            <div class="col-md-4">
                <label>Giá (VND)</label>
                <input type="number" class="form-control" name="variants[{{ $index }}][price]" value="{{ $variant->price }}">
            </div>
            <div class="col-md-4">
                <label>Số lượng</label>
                <input type="number" class="form-control" name="variants[{{ $index }}][stock_quantity]" value="{{ $variant->stock_quantity }}">
            </div>
        </div>

        <div id="attributes-container-{{ $index }}">
            <label class="form-label">Thuộc tính biến thể</label>

            @foreach ($groupedOptions as $attributeId => $data)
                <div class="row g-2 mb-2 attribute-row">
                    <div class="col-md-6">
                        <select class="form-select attribute-select"
                            data-index="{{ $index }}"
                            data-row="{{ $loop->index }}"
                            name="variants[{{ $index }}][attributes][{{ $loop->index }}][attribute_id]">

                            @foreach ($availableAttributes as $attr)
                                <option value="{{ $attr->id }}" {{ $attr->id == $attributeId ? 'selected' : '' }}>
                                    {{ $attr->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <select class="form-select option-select"
                            id="option-select-{{ $index }}-{{ $loop->index }}"
                            name="variants[{{ $index }}][attributes][{{ $loop->index }}][options][]">
                            <option value="">-- Chọn giá xxxxtrị --</option>
                            @foreach ($availableAttributes->find($attributeId)?->options ?? [] as $option)
                                <option value="{{ $option->id }}"
                                    {{ in_array($option->id, array_column($data['options'], 'id')) ? 'selected' : '' }}>
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="this.closest('.attribute-row').remove()">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-link fw-bold text-primary" onclick="addAttribute({{ $index }})">
            + THÊM THUỘC TÍNH
        </button>

        <div class="text-end mt-3">
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyVariant({{ $index }})">
                <i class="bi bi-files"></i> COPY BIẾN THỂ
            </button>
        </div>

        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
    </div>
@endforeach




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
window.availableAttributes = @json($availableAttributesJson);

document.addEventListener('DOMContentLoaded', function () {
    let variantCount = 0;

    // Tự động dò biến thể có sẵn (edit page)
    const ids = Array.from(document.querySelectorAll('[id^="variant-"]'))
        .map(el => parseInt(el.id.replace('variant-', '')))
        .filter(id => !isNaN(id));
    if (ids.length > 0) {
        variantCount = Math.max(...ids);
    }

    const availableAttributes = window.availableAttributes || [];

    window.addVariant = function (variantKey = null, variantData = null) {
        const id = variantKey ?? (++variantCount);
        variantCount = Math.max(variantCount, parseInt(id));

        const container = document.getElementById('variants-container');
        const sku = variantData?.sku || '';
        const price = variantData?.price || '';
        const stock = variantData?.stock_quantity || '';

        const variantDiv = document.createElement('div');
        variantDiv.className = 'variant-item border p-3 rounded bg-light-subtle position-relative mb-4';
        variantDiv.id = `variant-${id}`;
        variantDiv.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-danger position-absolute end-0 top-0 m-2"
                onclick="document.getElementById('variant-${id}').remove()">
                <i class="bi bi-trash"></i>
            </button>

            <h6 class="text-primary mb-3"><i class="bi bi-gear"></i> Biến thể #${id}</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>SKU</label>
                    <input type="text" class="form-control" name="variants[${id}][sku]" value="${sku}" />
                </div>
                <div class="col-md-4">
                    <label>Giá (VND)</label>
                    <input type="number" class="form-control" name="variants[${id}][price]" value="${price}" />
                </div>
                <div class="col-md-4">
                    <label>Số lượng</label>
                    <input type="number" class="form-control" name="variants[${id}][stock_quantity]" value="${stock}" />
                </div>
            </div>

            <div id="attributes-container-${id}">
                <label class="form-label">Thuộc tính biến thể</label>
            </div>

            <button type="button" class="btn btn-link fw-bold text-primary" onclick="addAttribute(${id})">
                + THÊM THUỘC TÍNH
            </button>

            <div class="text-end mt-3">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyVariant(${id})">
                    <i class="bi bi-files"></i> COPY BIẾN THỂ
                </button>
            </div>
        `;
        container.appendChild(variantDiv);

        if (variantData?.attributes) {
            Object.entries(variantData.attributes).forEach(([key, attr]) => {
                addAttribute(id, key, attr);
            });
        }
    };

    window.addAttribute = function (variantId, attributeKey = null, attributeData = null) {
        const container = document.getElementById(`attributes-container-${variantId}`);
        const index = attributeKey ?? container.querySelectorAll('.attribute-row').length;

        const selectedAttribute = attributeData?.attribute_id || '';
        const selectedOption = attributeData?.options?.[0] || '';

        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 attribute-row';
        row.innerHTML = `
            <div class="col-md-6">
                <select class="form-select attribute-select"
                    name="variants[${variantId}][attributes][${index}][attribute_id]"
                    data-index="${variantId}" data-row="${index}">
                    <option value="">-- Chọn thuộc tính --</option>
                    ${availableAttributes.map(attr =>
                        `<option value="${attr.id}" ${attr.id == selectedAttribute ? 'selected' : ''}>${attr.name}</option>`
                    ).join('')}
                </select>
            </div>
            <div class="col-md-5">
                <select class="form-select option-select"
                    name="variants[${variantId}][attributes][${index}][options][]"
                    id="option-select-${variantId}-${index}">
                    <option value="">-- Chọn giá trị --</option>
                </select>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-danger"
                    onclick="this.closest('.attribute-row').remove()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);

        const attrSelect = row.querySelector('.attribute-select');
        const optionSelect = row.querySelector('.option-select');

        function renderOptions(attributeId, selected = '') {
            const attribute = availableAttributes.find(attr => attr.id == attributeId);
            optionSelect.innerHTML = '<option value="">-- Chọn giá trị --</option>';
            if (attribute) {
                attribute.options.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt.id;
                    option.textContent = opt.value;
                    if (opt.id == selected) option.selected = true;
                    optionSelect.appendChild(option);
                });
            }
        }

        if (selectedAttribute) {
            renderOptions(selectedAttribute, selectedOption);
        }

        attrSelect.addEventListener('change', function () {
            renderOptions(this.value);
        });
    };

    window.copyVariant = function (variantId) {
        const el = document.getElementById(`variant-${variantId}`);
        if (!el) return;

        const sku = el.querySelector(`[name="variants[${variantId}][sku]"]`)?.value || '';
        const price = el.querySelector(`[name="variants[${variantId}][price]"]`)?.value || '';
        const stock = el.querySelector(`[name="variants[${variantId}][stock_quantity]"]`)?.value || '';

        const attributes = [];
        el.querySelectorAll('.attribute-row').forEach(row => {
            const attrSelect = row.querySelector('.attribute-select');
            const optSelect = row.querySelector('.option-select');
            if (attrSelect?.value && optSelect?.value) {
                attributes.push({
                    attribute_id: attrSelect.value,
                    options: [optSelect.value]
                });
            }
        });

        const attrObj = {};
        attributes.forEach((a, i) => attrObj[i] = a);

        addVariant(null, {
            sku,
            price,
            stock_quantity: stock,
            attributes: attrObj
        });
    };

});
</script>



@endsection
