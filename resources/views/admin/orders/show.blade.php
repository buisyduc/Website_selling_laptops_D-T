@extends('admin.index')

@section('container-fluid')
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold">📦 Chi tiết đơn hàng #{{ $order->order_code }}</h2>

    <div class="row g-4">
        <!-- Thông tin khách hàng -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Thông tin khách hàng</div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> {{ $order->name }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</p>
                </div>
            </div>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Thông tin đơn hàng</div>
                <div class="card-body">
                    <p><strong>Trạng thái:</strong>
                        <span class="badge bg-{{ [
                            'pending' => 'warning',
                            'confirmed' => 'primary',
                            'processing' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'danger'
                        ][$order->status] ?? 'secondary' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Phương thức thanh toán:</strong> {{ strtoupper($order->payment_method) }}</p>
                    <p><strong>Thời gian đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold">{{ number_format($order->total_amount) }}đ</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-light fw-semibold">Danh sách sản phẩm</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>SKU</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->variant->sku ?? '-' }}</td>
                            <td>{{ number_format($item->price) }}đ</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity) }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="ri-arrow-left-line"></i> Quay lại danh sách
    </a>
</div>
</div>
@endsection