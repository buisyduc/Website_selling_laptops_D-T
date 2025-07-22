@extends('admin.index')

@section('container-fluid')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                        Quản lý đơn hàng
                    </h2>
                    <p class="text-muted mb-0">Theo dõi và quản lý tất cả đơn hàng</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-filter me-1"></i>Lọc
                    </button>
                    <button class="btn btn-outline-success">
                        <i class="fas fa-download me-1"></i>Xuất Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Tổng đơn hàng</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $orders->total() ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Đã hoàn thành</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Đang xử lý</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Doanh thu tháng</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">0₫</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Danh sách đơn hàng</h6>
                <div class="d-flex gap-2">
                    <div class="input-group" style="width: 250px;">
                        <input type="text" class="form-control form-control-sm" placeholder="Tìm kiếm đơn hàng...">
                        <button class="btn btn-outline-secondary btn-sm" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-bold text-dark">
                                <i class="fas fa-hashtag me-1"></i>Mã đơn
                            </th>
                            <th class="border-0 fw-bold text-dark">
                                <i class="fas fa-user me-1"></i>Khách hàng
                            </th>
                            <th class="border-0 fw-bold text-dark">
                                <i class="fas fa-phone me-1"></i>Số điện thoại
                            </th>
                            <th class="border-0 fw-bold text-dark">
                                <i class="fas fa-info-circle me-1"></i>Trạng thái
                            </th>
                            <th class="border-0 fw-bold text-dark">
                                <i class="fas fa-money-bill me-1"></i>Tổng tiền
                            </th>
                            <th class="border-0 fw-bold text-dark">
                                <i class="fas fa-calendar me-1"></i>Ngày đặt
                            </th>
                            <th class="border-0 fw-bold text-dark text-center">
                                <i class="fas fa-cogs me-1"></i>Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr class="align-middle">
                                <td class="fw-bold text-primary">
                                    #{{ $order->order_code }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $order->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="fas fa-phone-alt me-1"></i>
                                        {{ $order->phone }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($order->status) {
                                            'pending' => 'bg-warning',
                                            'processing' => 'bg-info',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };

                                        $statusText = match($order->status) {
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy',
                                            default => $order->status
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-3 py-2">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">
                                        {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                    </span>
                                </td>
                                <td class="text-muted">
                                    <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="Xem chi tiết">
                                           show
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-success"
                                                data-bs-toggle="tooltip"
                                                title="Cập nhật trạng thái">
                                                 status
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="tooltip"
                                                title="In hóa đơn">
                                                  invoice

                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>Chưa có đơn hàng nào</h5>
                                        <p>Các đơn hàng sẽ xuất hiện tại đây khi có khách hàng đặt hàng.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($orders->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Hiển thị {{ $orders->firstItem() }} - {{ $orders->lastItem() }}
                    trong tổng số {{ $orders->total() }} đơn hàng
                </div>
                <div>
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn-group .btn {
    border-color: transparent;
}

.btn-group .btn:hover {
    transform: scale(1.1);
    z-index: 2;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }

    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group {
        flex-direction: column;
    }
}
</style>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
