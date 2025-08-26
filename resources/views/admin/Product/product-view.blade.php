@extends('admin.index')
@section('container-fluid')

    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-light p-3 rounded">
                    <div>
                        <h4 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-eye me-2"></i>Chi tiết sản phẩm
                        </h4>
                        <p class="text-muted mb-0 mt-1">Xem thông tin chi tiết về sản phẩm</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-decoration-none">Bảng điều khiển</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('product-list') }}" class="text-decoration-none">Sản phẩm</a>
                            </li>
                            <li class="breadcrumb-item active">Chi tiết sản phẩm</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Thanh hành động -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('product-list') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-lg me-2">
                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa sản phẩm
                                </a>
                                <button class="btn btn-outline-danger btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">
                                    <i class="fas fa-trash me-2"></i>Xóa sản phẩm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-images me-2"></i>Hình ảnh sản phẩm
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="img-fluid rounded border" style="max-height: 400px; object-fit: cover;">
                            @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                    style="height: 300px;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p>Không có hình ảnh</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <p class="text-muted text-center mb-0">
                                    <small>Hình chính của sản phẩm</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Thông tin sản phẩm
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Tên sản phẩm -->
                            <div class="col-12 mb-3">
                                <h2 class="text-primary fw-bold">{{ $product->name }}</h2>
                            </div>

                            <!-- Thông tin cơ bản -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Danh mục</label>
                                <p class="fs-5">
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-layer-group me-1"></i>
                                        {{ $product->category->name ?? 'N/A' }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Giá</label>
                                <p class="fs-3 fw-bold text-success mb-0">
                                    ${{ number_format($product->price, 2) }}
                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Số lượng tồn kho</label>
                                <p class="fs-5">
                                    @if ($totalStock > 10)
                                        <span class="badge bg-success">{{ $totalStock }} có sẵn</span>
                                    @elseif($totalStock > 0)
                                        <span class="badge bg-warning">{{ $totalStock }} hàng ít</span>
                                    @else
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @endif

                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Trạng thái</label>
                                <p class="fs-5">
                                    @if ($product->status == 'active')
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check-circle me-1"></i>Đang hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-secondary fs-6">
                                            <i class="fas fa-pause-circle me-1"></i>Ngưng hoạt động
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Đã bán</label>
                                <p class="fs-5">
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-shopping-cart me-1"></i>
                                        {{ $product->total_sold ?? 0 }} sản phẩm
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Đánh giá</label>
                                <div class="d-flex align-items-center">
                                    @php
                                        $avgRating = round($product->averageRating(), 1);
                                    @endphp
                                    <div class="me-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="fs-6 text-muted">{{ $avgRating }}/5</span>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold text-muted">Ngày tạo</label>
                                <p class="fs-6 text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $product->created_at->format('d F, Y \l\ú\c g:i A') }}
                                </p>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold text-muted">Cập nhật lần cuối</label>
                                <p class="fs-6 text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $product->updated_at->format('d F, Y \l\ú\c g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mô tả sản phẩm -->
        @if ($product->description)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-align-left me-2"></i>Mô tả sản phẩm
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

        <!-- Thống kê sản phẩm -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Thống kê sản phẩm
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                                    <h4 class="mb-1">{{ $product->views ?? 0 }}</h4>
                                    <small class="text-muted">Lượt xem</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <i class="fas fa-shopping-cart fa-2x text-success mb-2"></i>
                                    <h4 class="mb-1">{{ $product->total_sold ?? 0 }}</h4>
                                    <small class="text-muted">Số lượng bán</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                    <h4 class="mb-1">{{ $avgRating }}</h4>
                                    <small class="text-muted">Điểm đánh giá trung bình</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <i class="fas fa-boxes fa-2x text-info mb-2"></i>
                                    <h4 class="mb-1">{{ $product->stock }}</h4>
                                    <small class="text-muted">Tồn kho</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đơn hàng gần đây -->
        @if (isset($recentOrders) && $recentOrders->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock me-2"></i>Đơn hàng gần đây
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mã đơn</th>
                                            <th>Khách hàng</th>
                                            <th>Số lượng</th>
                                            <th>Tổng</th>
                                            <th>Ngày</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentOrders as $order)
                                            <tr>
                                                <td><strong>#{{ $order->id }}</strong></td>
                                                <td>{{ $order->customer_name }}</td>
                                                <td>{{ $order->quantity }}</td>
                                                <td>${{ number_format($order->total, 2) }}</td>
                                                <td>{{ $order->created_at->format('d M, Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
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

    <!-- Modal xóa sản phẩm -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa sản phẩm <strong>{{ $product->name }}</strong> không?</p>
                    <p class="text-muted">Hành động này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form method="POST" action="{{ route('product.destroy', $product->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Xóa sản phẩm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
