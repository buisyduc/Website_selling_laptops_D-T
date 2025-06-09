@extends('admin.index')
@section('container-fluid')
    <div class="container-fluid">

        <!-- Enhanced Page Title with gradient background -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-1 fw-bold">
                                    <i class="ri-settings-3-line me-2"></i>
                                    Product Attributes
                                </h3>
                                <p class="mb-0 opacity-75">Manage product attributes and variants</p>
                            </div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 bg-transparent">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);" class="text-white-50 text-decoration-none">
                                            <i class="ri-dashboard-line me-1"></i>Dashboard
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);" class="text-white-50 text-decoration-none">Products</a>
                                    </li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">Attributes</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Enhanced Create Attributes Form -->
            <div class="col-xxl-4 col-xl-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm bg-primary rounded d-flex align-items-center justify-content-center">
                                    <i class="ri-add-circle-line text-white fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-0 fw-semibold">Create New Attribute</h5>
                                <p class="card-text text-muted small mb-0">Add a new product attribute</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="ri-check-circle-line me-2 fs-5"></i>
                                    <div>
                                        <strong>Success!</strong> {{ session('success') }}
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('attributes.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="ri-price-tag-3-line me-1 text-primary"></i>
                                    Attribute Name
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-edit-line text-muted"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Enter attribute name (e.g., Color, Size)"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="parent_id" class="form-label fw-semibold">
                                    <i class="ri-node-tree me-1 text-success"></i>
                                    Parent Attribute
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-arrow-up-line text-muted"></i>
                                    </span>
                                    <select class="form-select border-start-0 ps-0" id="parent_id" name="parent_id">
                                        <option value="">-- Select Parent (Optional) --</option>
                                        {!! (new \App\Http\Controllers\Admin\AttributesProduct)->renderCategoryOptions($allAttributes) !!}
                                    </select>
                                </div>
                                <div class="form-text">
                                    <i class="ri-information-line me-1"></i>
                                    Leave empty to create a top-level attribute
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ri-add-line me-2"></i>
                                    Create Attribute
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Enhanced Product Variants List -->
            <div class="col-xxl-8 col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light border-0 py-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-info rounded d-flex align-items-center justify-content-center">
                                        <i class="ri-list-check-2 text-white fs-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-0 fw-semibold">Product Variants</h5>
                                    <p class="card-text text-muted small mb-0">Manage existing product variants</p>
                                </div>
                            </div>

                            <!-- Enhanced Search Form -->
                            <div class="flex-shrink-0">
                                <form method="GET" action="{{ route('attributes') }}" class="d-flex align-items-center gap-2">
                                    <div class="input-group" style="min-width: 280px;">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-search-line text-muted"></i>
                                        </span>
                                        <input type="text"
                                               name="search"
                                               value="{{ request('search') }}"
                                               placeholder="Search variants..."
                                               class="form-control border-start-0 ps-0">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-search-line"></i>
                                        </button>
                                        @if(request('search'))
                                            <a href="{{ route('attributes') }}" class="btn btn-outline-secondary">
                                                <i class="ri-close-line"></i>
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold text-uppercase small px-4 py-3">
                                            <i class="ri-product-hunt-line me-1"></i>Product
                                        </th>
                                        <th class="border-0 fw-semibold text-uppercase small px-2 py-3">
                                            <i class="ri-barcode-line me-1"></i>SKU
                                        </th>
                                        <th class="border-0 fw-semibold text-uppercase small px-2 py-3">
                                            <i class="ri-settings-3-line me-1"></i>Attributes
                                        </th>
                                        <th class="border-0 fw-semibold text-uppercase small px-2 py-3">
                                            <i class="ri-money-dollar-circle-line me-1"></i>Price
                                        </th>
                                        <th class="border-0 fw-semibold text-uppercase small px-2 py-3">
                                            <i class="ri-stack-line me-1"></i>Stock
                                        </th>
                                        <th class="border-0 fw-semibold text-uppercase small px-2 py-3">
                                            <i class="ri-toggle-line me-1"></i>Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($variants as $variant)
                                        <tr class="border-bottom">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @if($variant->product->image)
                                                            <div class="avatar-sm rounded overflow-hidden border">
                                                                <img src="{{ asset('storage/' . $variant->product->image) }}"
                                                                     alt="{{ $variant->product->name }}"
                                                                     class="img-fluid">
                                                            </div>
                                                        @else
                                                            <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                                                <i class="ri-image-line text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0 fw-semibold">{{ $variant->product->name }}</h6>
                                                        <small class="text-muted">{{ Str::limit($variant->product->description ?? 'No description', 30) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-2 py-3">
                                                <span class="badge bg-primary-subtle text-primary font-monospace">
                                                    {{ $variant->sku }}
                                                </span>
                                            </td>
                                            <td class="px-2 py-3">
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($variant->variantOptions as $variantOption)
                                                        <span class="badge bg-secondary-subtle text-secondary">
                                                            <i class="ri-price-tag-3-line me-1"></i>
                                                            {{ $variantOption->attribute->name }}: {{ $variantOption->option->value }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-2 py-3">
                                                <span class="fw-bold text-success fs-6">
                                                    ${{ number_format($variant->price, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-2 py-3">
                                                <span class="badge fs-6 {{ $variant->stock_quantity > 10 ? 'bg-success-subtle text-success' : ($variant->stock_quantity > 0 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }}">
                                                    <i class="ri-stack-line me-1"></i>
                                                    {{ $variant->stock_quantity }}
                                                    @if($variant->stock_quantity > 10)
                                                        <small>(In Stock)</small>
                                                    @elseif($variant->stock_quantity > 0)
                                                        <small>(Low Stock)</small>
                                                    @else
                                                        <small>(Out of Stock)</small>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-2 py-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           {{ $variant->status ? 'checked' : '' }}
                                                           disabled>
                                                    <label class="form-check-label">
                                                        <span class="badge {{ $variant->status ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                            <i class="ri-{{ $variant->status ? 'check' : 'close' }}-circle-line me-1"></i>
                                                            {{ $variant->status ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <div class="empty-state-icon mb-3">
                                                        <i class="ri-inbox-line display-4 text-muted"></i>
                                                    </div>
                                                    <h5 class="text-muted mb-2">No Variants Found</h5>
                                                    <p class="text-muted mb-3">
                                                        @if(request('search'))
                                                            No variants match your search criteria.
                                                        @else
                                                            You haven't created any product variants yet.
                                                        @endif
                                                    </p>
                                                    @if(request('search'))
                                                        <a href="{{ route('attributes') }}" class="btn btn-outline-primary">
                                                            <i class="ri-refresh-line me-1"></i>Clear Search
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination if exists -->
                        @if(method_exists($variants, 'links'))
                            <div class="card-footer bg-light border-0 py-3">
                                {{ $variants->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        /* Enhanced Styling */
        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .form-control::placeholder {
            color: rgba(0, 0, 0, 0.5);
            font-style: italic;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .avatar-sm {
            width: 2.5rem;
            height: 2.5rem;
        }

        .empty-state {
            padding: 2rem;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .input-group-text {
            background-color: transparent;
        }

        .border-start-0 {
            border-left: 0 !important;
        }

        .border-end-0 {
            border-right: 0 !important;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .form-check-input:disabled {
            opacity: 0.7;
        }

        .font-monospace {
            font-family: 'Courier New', monospace;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .d-flex.flex-wrap.gap-3 {
                flex-direction: column;
            }

            .input-group {
                min-width: auto !important;
            }

            .table-responsive {
                font-size: 0.875rem;
            }
        }
    </style>

@endsection
