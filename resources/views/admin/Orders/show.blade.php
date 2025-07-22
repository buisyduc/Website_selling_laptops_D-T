@extends('admin.index')

@section('container-fluid')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-file-invoice me-2"></i>
                            Chi tiết đơn hàng #{{ $order->order_code }}
                        </h3>
                        <span class="badge badge-lg
                            @if($order->status === 'pending') bg-warning
                            @elseif($order->status === 'confirmed') bg-info
                            @elseif($order->status === 'processing') bg-secondary
                            @elseif($order->status === 'shipped') bg-primary
                            @elseif($order->status === 'delivered') bg-success
                            @elseif($order->status === 'cancelled') bg-danger
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Thông tin khách hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle text-muted me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Họ tên</small>
                                    <strong>{{ $order->name }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-muted me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <strong>{{ $order->email }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-muted me-3 mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">Địa chỉ giao hàng</small>
                                    <strong>{{ $order->shipping_address }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                        Tóm tắt đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Mã đơn hàng</small>
                            <p class="fw-bold">#{{ $order->order_code }}</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Ngày đặt</small>
                            <p class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Số lượng sản phẩm</small>
                            <p class="fw-bold">{{ $order->items->sum('quantity') }} sản phẩm</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Tổng tiền</small>
                            <p class="fw-bold text-success fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}₫</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products List -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-box me-2 text-primary"></i>
                        Danh sách sản phẩm
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Sản phẩm</th>
                                    <th class="border-0 text-center">Đơn giá</th>
                                    <th class="border-0 text-center">Số lượng</th>
                                    <th class="border-0 text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                <tr>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3">
                                                <i class="fas fa-cube text-muted"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $item->variant->product->name }}</h6>
                                                <small class="text-muted">Biến thể: {{ $item->variant->name ?? 'Mặc định' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-3">
                                        <span class="fw-bold">{{ number_format($item->variant->price, 0, ',', '.') }}₫</span>
                                    </td>
                                    <td class="text-center py-3">
                                        <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                        
                                    </td>
                                    <td class="text-end py-3">
                                        <span class="fw-bold text-success">
                                            {{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }}₫
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold py-3">Tổng cộng:</td>
                                    <td class="text-end py-3">
                                        <span class="fw-bold text-success fs-5">
                                            {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Form -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Cập nhật trạng thái
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Trạng thái đơn hàng</label>
                            <select name="status" class="form-select form-select-lg">
                                @php
                                    $statusOptions = [
                                        'pending' => ['label' => 'Chờ xử lý', 'class' => 'text-warning'],
                                        'confirmed' => ['label' => 'Đã xác nhận', 'class' => 'text-info'],
                                        'processing' => ['label' => 'Đang xử lý', 'class' => 'text-secondary'],
                                        'shipped' => ['label' => 'Đã giao vận', 'class' => 'text-primary'],
                                        'delivered' => ['label' => 'Đã giao hàng', 'class' => 'text-success'],
                                        'cancelled' => ['label' => 'Đã hủy', 'class' => 'text-danger']
                                    ];
                                @endphp
                                @foreach ($statusOptions as $status => $config)
                                    <option value="{{ $status }}" @selected($order->status === $status) class="{{ $config['class'] }}">
                                        {{ $config['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-1"></i>
                                Cập nhật trạng thái
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2 text-primary"></i>
                        Thao tác khác
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>
                            In đơn hàng
                        </button>
                        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#emailModal">
                            <i class="fas fa-envelope me-2"></i>
                            Gửi email khách hàng
                        </button>
                        <button class="btn btn-outline-warning">
                            <i class="fas fa-edit me-2"></i>
                            Chỉnh sửa đơn hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Gửi email cho khách hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="emailSubject" class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" id="emailSubject" value="Cập nhật đơn hàng #{{ $order->order_code }}">
                    </div>
                    <div class="mb-3">
                        <label for="emailContent" class="form-label">Nội dung</label>
                        <textarea class="form-control" id="emailContent" rows="4">Xin chào {{ $order->name }},

Đơn hàng #{{ $order->order_code }} của bạn đã được cập nhật trạng thái.

Cảm ơn bạn đã tin tưởng dịch vụ của chúng tôi!</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary">Gửi email</button>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-lg {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    @media print {
        .btn, .modal, .card-header {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection
