@extends('admin.index')
@section('container-fluid')

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light p-3 rounded">
                <div>
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-box me-2"></i>Product Management
                    </h4>
                    <p class="text-muted mb-0 mt-1">Manage your product inventory</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none">Products</a>
                        </li>
                        <li class="breadcrumb-item active">Product List</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <!-- Add Product Button -->
                        <div class="col-md-6">
                            <a href="{{route('product.create')}}" class="btn btn-success btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Add New Product
                            </a>
                            <button class="btn btn-outline-info btn-lg ms-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter me-2"></i>Filters
                            </button>
                        </div>

                        <!-- Search Box -->
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('product-list') }}" class="d-flex gap-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="Search products..." class="form-control form-control-lg">
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg">Search</button>
                                <a href="{{ route('product-list') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Product List
                        <span class="badge bg-light text-primary ms-2">{{ $products->total() ?? 0 }} items</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 60px;">#</th>
                                    <th scope="col" style="min-width: 200px;">
                                        <i class="fas fa-tag me-1"></i>Product Details
                                    </th>
                                    <th scope="col" class="text-center" style="width: 100px;">
                                        <i class="fas fa-boxes me-1"></i>Stock
                                    </th>
                                    <th scope="col" class="text-center" style="width: 120px;">
                                        <i class="fas fa-star me-1"></i>Rating
                                    </th>
                                    <th scope="col" class="text-center" style="width: 100px;">
                                        <i class="fas fa-dollar-sign me-1"></i>Price
                                    </th>
                                    <th scope="col" class="text-center" style="width: 80px;">
                                        <i class="fas fa-shopping-cart me-1"></i>Sold
                                    </th>
                                    <th scope="col" class="text-center" style="width: 100px;">
                                        <i class="fas fa-calendar me-1"></i>Published
                                    </th>
                                    <th scope="col" class="text-center" style="width: 100px;">
                                        <i class="fas fa-toggle-on me-1"></i>Status
                                    </th>
                                    <th scope="col" class="text-center" style="width: 120px;">
                                        <i class="fas fa-cogs me-1"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $index => $product)
                                    <tr>
                                        <td class="text-center fw-bold">
                                            {{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}
                                        </td>

                                        <!-- Product Details with Image -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                  @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" width="60" height="60" alt="{{ $product->name }}" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif

                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold">{{ $product->name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-layer-group me-1"></i>
                                                        {{ $product->category->name ?? 'N/A' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Stock with Status -->
                                        <td class="text-center">
                                            @if($product->stock > 10)
                                                <span class="badge bg-success fs-6">{{ $product->stock }}</span>
                                            @elseif($product->stock > 0)
                                                <span class="badge bg-warning fs-6">{{ $product->stock }}</span>
                                            @else
                                                <span class="badge bg-danger fs-6">{{ $product->stock }}</span>
                                            @endif
                                        </td>

                                        <!-- Rating -->
                                        <td class="text-center">
                                            @php
                                                $avgRating = round($product->averageRating(), 1);
                                            @endphp
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="mb-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                                <small class="text-muted">{{ $avgRating }}/5</small>
                                            </div>
                                        </td>

                                        <!-- Price -->
                                        <td class="text-center">
                                            <span class="fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                                        </td>

                                        <!-- Sold -->
                                        <td class="text-center">
                                            <span class="badge bg-info fs-6">{{ $product->total_sold ?? 0 }}</span>
                                        </td>

                                        <!-- Published Date -->
                                        <td class="text-center">
                                            <small class="text-muted">
                                                {{ $product->created_at->format('M d, Y') }}
                                            </small>
                                        </td>

                                        <!-- Status -->
                                        <td class="text-center">
                                            @if($product->status == 'active')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-pause-circle me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{route('product.view',$product->id)}}" class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>view
                                                </a>
                                                 <a href="{{ route('product.edit', $product->id) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                                    <i class="ri-edit-line me-1"></i>Edit
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>xoa
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal for each product -->
                                    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Bạn có chắc chắn muốn xóa mềm sản phẩm  <strong>{{ $product->name }}</strong> không?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="{{ route('product.destroy', $product->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash me-2"></i>YES
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                                <h5>No products found</h5>
                                                <p>Try adjusting your search criteria or add new products.</p>
                                                <a href="{{route('product.create')}}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Add First Product
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>Advanced Filters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="row">
                        <!-- Category Filter -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <div class="border rounded p-3 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" value="">
                                    <label class="form-check-label">All Categories</label>
                                </div>
                                <!-- Add your categories here -->
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" id="minCost" placeholder="Min Price" min="0">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" id="maxCost" placeholder="Max Price" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Minimum Rating</label>
                            <div class="border rounded p-3 bg-light">
                                @for($i = 5; $i >= 1; $i--)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" value="{{ $i }}">
                                    <label class="form-check-label">
                                        @for($j = 1; $j <= $i; $j++)
                                            <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        & up
                                    </label>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div class="border rounded p-3 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="">
                                    <label class="form-check-label">All Status</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="active">
                                    <label class="form-check-label">Active</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="inactive">
                                    <label class="form-check-label">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                    <i class="fas fa-eraser me-2"></i>Clear All
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyFilters">
                    <i class="fas fa-check me-2"></i>Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .table td {
        vertical-align: middle;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .btn {
        border-radius: 0.375rem;
    }

    .badge {
        font-size: 0.75rem;
    }

    .page-title-box {
        border-left: 4px solid #0d6efd;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Filter functionality
    function applyFilters() {
        let category = $("input[name='category']:checked").val();
        let minPrice = $("#minCost").val();
        let maxPrice = $("#maxCost").val();
        let rating = $("input[name='rating']:checked").val();
        let status = $("input[name='status']:checked").val();

        let params = new URLSearchParams();
        if (category) params.append('category', category);
        if (minPrice) params.append('min_price', minPrice);
        if (maxPrice) params.append('max_price', maxPrice);
        if (rating) params.append('rating', rating);
        if (status) params.append('status', status);

        window.location.href = "{{ route('product-list') }}?" + params.toString();
    }

    // Apply filters button
    $("#applyFilters").click(function() {
        applyFilters();
    });

    // Clear filters button
    $("#clearFilters").click(function() {
        $("#filterForm")[0].reset();
        window.location.href = "{{ route('product-list') }}";
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

