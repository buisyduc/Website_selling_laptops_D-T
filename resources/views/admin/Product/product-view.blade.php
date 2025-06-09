@extends('admin.index')
@section('container-fluid')

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light p-3 rounded">
                <div>
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-eye me-2"></i>Product Details
                    </h4>
                    <p class="text-muted mb-0 mt-1">View detailed information about the product</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('product-list') }}" class="text-decoration-none">Products</a>
                        </li>
                        <li class="breadcrumb-item active">Product Details</li>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('product-list') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to Products
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-lg me-2">
                                <i class="fas fa-edit me-2"></i>Edit Product
                            </a>
                            <button class="btn btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-2"></i>Delete Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Image and Gallery -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-images me-2"></i>Product Images
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid rounded border"
                                 style="max-height: 400px; object-fit: cover;">
                        @else
                            <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                 style="height: 300px;">
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <p>No image available</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Additional images can be added here -->
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted text-center mb-0">
                                <small>Main product image</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Product Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Name -->
                        <div class="col-12 mb-3">
                            <h2 class="text-primary fw-bold">{{ $product->name }}</h2>
                        </div>

                        <!-- Basic Info -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Category</label>
                            <p class="fs-5">
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-layer-group me-1"></i>
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Price</label>
                            <p class="fs-3 fw-bold text-success mb-0">
                                ${{ number_format($product->price, 2) }}
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Stock Quantity</label>
                            <p class="fs-5">
                                @if($product->stock > 10)
                                    <span class="badge bg-success fs-6">{{ $product->stock }} In Stock</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning fs-6">{{ $product->stock }} Low Stock</span>
                                @else
                                    <span class="badge bg-danger fs-6">Out of Stock</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <p class="fs-5">
                                @if($product->status == 'active')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-pause-circle me-1"></i>Inactive
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Total Sold</label>
                            <p class="fs-5">
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-shopping-cart me-1"></i>
                                    {{ $product->total_sold ?? 0 }} units
                                </span>
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Rating</label>
                            <div class="d-flex align-items-center">
                                @php
                                    $avgRating = round($product->averageRating(), 1);
                                @endphp
                                <div class="me-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <span class="fs-6 text-muted">{{ $avgRating }}/5</span>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Created Date</label>
                            <p class="fs-6 text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $product->created_at->format('F d, Y \a\t g:i A') }}
                            </p>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Last Updated</label>
                            <p class="fs-6 text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $product->updated_at->format('F d, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    @if($product->description)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-align-left me-2"></i>Product Description
                    </h5>
                </div>
                <div class="card-body">
                    <div class="fs-6 lh-base">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Product Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Product Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                                <h4 class="mb-1">{{ $product->views ?? 0 }}</h4>
                                <small class="text-muted">Total Views</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-shopping-cart fa-2x text-success mb-2"></i>
                                <h4 class="mb-1">{{ $product->total_sold ?? 0 }}</h4>
                                <small class="text-muted">Units Sold</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                <h4 class="mb-1">{{ $avgRating }}</h4>
                                <small class="text-muted">Average Rating</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-boxes fa-2x text-info mb-2"></i>
                                <h4 class="mb-1">{{ $product->stock }}</h4>
                                <small class="text-muted">In Stock</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders (if available) -->
    @if(isset($recentOrders) && $recentOrders->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Recent Orders
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the product <strong>{{ $product->name }}</strong>?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('product.destroy', $product->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .page-title-box {
        border-left: 4px solid #0d6efd;
    }

    .badge {
        font-size: 0.75rem;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .table td {
        vertical-align: middle;
    }

    .btn {
        border-radius: 0.375rem;
    }

    .border {
        border-color: #dee2e6 !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirm delete
    $('#deleteModal form').on('submit', function(e) {
        e.preventDefault();

        if (confirm('Are you absolutely sure you want to delete this product?')) {
            this.submit();
        }
    });
});
</script>
@endpush
