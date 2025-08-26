@extends('admin.index')
@section('container-fluid')

    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light p-3 rounded">
                    <div>
                        <h4 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-box me-2"></i>Quản lý sản phẩm
                        </h4>
                        <p class="text-muted mb-0 mt-1">Quản lý kho sản phẩm của bạn</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-decoration-none">Bảng điều khiển</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#" class="text-decoration-none">Sản phẩm</a>
                            </li>
                            <li class="breadcrumb-item active">Danh sách sản phẩm</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Thanh thao tác -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <!-- Nút thêm sản phẩm -->
                            <div class="col-md-6">
                                <a href="{{ route('product.create') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Thêm sản phẩm mới
                                </a>
                                <button class="btn btn-outline-info btn-lg ms-2" data-bs-toggle="modal"
                                    data-bs-target="#filterModal">
                                    <i class="fas fa-filter me-2"></i>Bộ lọc
                                </button>
                            </div>

                            <!-- Ô tìm kiếm -->
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('product-list') }}" class="d-flex gap-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Tìm kiếm sản phẩm..." class="form-control form-control-lg">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg">Tìm kiếm</button>
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

        <!-- Bảng sản phẩm -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Danh sách sản phẩm
                            <span class="badge bg-light text-primary ms-2">{{ $products->total() ?? 0 }} sản phẩm</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="text-center" style="width: 60px;">#</th>
                                        <th scope="col" style="min-width: 200px;">
                                            <i class="fas fa-tag me-1"></i>Thông tin sản phẩm
                                        </th>
                                        <th scope="col" class="text-center" style="width: 100px;">
                                            <i class="fas fa-boxes me-1"></i>Tồn kho
                                        </th>
                                        <th scope="col" class="text-center" style="width: 120px;">
                                            <i class="fas fa-star me-1"></i>Đánh giá
                                        </th>
                                        <th scope="col" class="text-center" style="width: 80px;">
                                            <i class="fas fa-shopping-cart me-1"></i>Đã bán
                                        </th>
                                        <th scope="col" class="text-center" style="width: 100px;">
                                            <i class="fas fa-calendar me-1"></i>Ngày đăng
                                        </th>
                                        <th scope="col" class="text-center" style="width: 100px;">
                                            <i class="fas fa-toggle-on me-1"></i>Trạng thái
                                        </th>
                                        <th scope="col" class="text-center" style="width: 120px;">
                                            <i class="fas fa-cogs me-1"></i>Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $index => $product)
                                        <tr>
                                            <td class="text-center fw-bold">
                                                {{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}
                                            </td>
                                            <!-- Thông tin sản phẩm kèm ảnh -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        @if ($product->image)
                                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                                width="60" height="60" alt="{{ $product->name }}"
                                                                style="object-fit: cover;">
                                                        @else
                                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                                                style="width: 60px; height: 60px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        @endif

                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $product->name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-layer-group me-1"></i>
                                                            {{ $product->category->name ?? 'Chưa có' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-- Tồn kho -->
                                            <td class="text-center">
                                                @if ($product->stock > 10)
                                                    <span class="badge bg-success fs-6">{{ $product->stock }}</span>
                                                @elseif($product->stock > 0)
                                                    <span class="badge bg-warning fs-6">{{ $product->stock }}</span>
                                                @else
                                                    <span class="badge bg-danger fs-6">{{ $product->stock }}</span>
                                                @endif
                                            </td>
                                            <!-- Đánh giá -->
                                            <td class="text-center">
                                                @php
                                                    $avgRating = round($product->averageRating(), 1);
                                                @endphp
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="mb-1">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fas fa-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <small class="text-muted">{{ $avgRating }}/5</small>
                                                </div>
                                            </td>
                                            <!-- Đã bán -->
                                            <td class="text-center">
                                                <span class="badge bg-info fs-6">{{ $product->total_sold ?? 0 }}</span>
                                            </td>
                                            <!-- Ngày đăng -->
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    {{ $product->created_at->format('d/m/Y') }}
                                                </small>
                                            </td>
                                            <!-- Trạng thái -->
                                            <td class="text-center">
                                                @if ($product->status == 'active')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Đang bán
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-pause-circle me-1"></i>Ngừng bán
                                                    </span>
                                                @endif
                                            </td>
                                            <!-- Thao tác -->
                                            <td>
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('product.view', $product->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Xem">
                                                        <i class="fas fa-eye"></i>Xem
                                                    </a>
                                                    <a href="{{ route('product.edit', $product->id) }}"
                                                        class="btn btn-outline-secondary btn-sm flex-fill">
                                                        <i class="ri-edit-line me-1"></i>Sửa
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-btn"
                                                        data-bs-toggle="tooltip" title="Xóa"
                                                        data-product-name="{{ $product->name }}">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </button>

                                                    <form method="POST"
                                                        action="{{ route('product.destroy', $product->id) }}"
                                                        class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                                    <h5>Không tìm thấy sản phẩm</h5>
                                                    <p>Hãy thử thay đổi tiêu chí tìm kiếm hoặc thêm sản phẩm mới.</p>
                                                    <a href="{{ route('product.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Phân trang -->
                    @if ($products->hasPages())
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Hiển thị từ {{ $products->firstItem() }} đến {{ $products->lastItem() }} trên tổng số
                                    {{ $products->total() }} kết quả
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

    <!-- Modal xóa cho từng sản phẩm - Đặt ngoài bảng -->
    @foreach ($products as $product)
        <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa mềm sản phẩm <strong>{{ $product->name }}</strong> không?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form method="POST" action="{{ route('product.destroy', $product->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal bộ lọc -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-filter me-2"></i>Bộ lọc nâng cao
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="row">
                            <!-- Lọc theo danh mục -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Danh mục</label>
                                <div class="border rounded p-3 bg-light">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" value="">
                                        <label class="form-check-label">Tất cả danh mục</label>
                                    </div>
                                    <!-- Thêm các danh mục tại đây -->
                                </div>
                            </div>

                            <!-- Khoảng giá -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Khoảng giá</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" class="form-control" id="minCost"
                                            placeholder="Giá thấp nhất" min="0">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control" id="maxCost"
                                            placeholder="Giá cao nhất" min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Lọc theo đánh giá -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Đánh giá tối thiểu</label>
                                <div class="border rounded p-3 bg-light">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="rating"
                                                value="{{ $i }}">
                                            <label class="form-check-label">
                                                @for ($j = 1; $j <= $i; $j++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                                & trở lên
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Lọc theo trạng thái -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <div class="border rounded p-3 bg-light">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="">
                                        <label class="form-check-label">Tất cả trạng thái</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="active">
                                        <label class="form-check-label">Đang bán</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="inactive">
                                        <label class="form-check-label">Ngừng bán</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                        <i class="fas fa-eraser me-2"></i>Xóa tất cả
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="applyFilters">
                        <i class="fas fa-check me-2"></i>Áp dụng bộ lọc
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productName = this.getAttribute('data-product-name');
                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: `Sản phẩm "${productName}" sẽ bị xóa!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545', // màu đỏ
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Có, xóa!',
                        cancelButtonText: 'Hủy bỏ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.closest('td').querySelector('.delete-form').submit();
                        }
                    });
                });
            });
        });
    </script>


@endsection
