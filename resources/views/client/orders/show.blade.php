@extends('client.layouts.layout')

@section('content')
    <div class="container my-5 animate__animated animate__fadeIn">
        <h4 class="mb-4 text-primary fw-bold text-center">Chi tiết đơn hàng #{{ $order->order_code }}</h4>

        {{-- Trạng thái --}}
        <div class="text-center mb-4">
            <span
                class="badge bg-{{ $order->status === 'completed'
                    ? 'success'
                    : ($order->status === 'cancelled'
                        ? 'danger'
                        : ($order->status === 'processing'
                            ? 'warning'
                            : 'secondary')) }} px-3 py-2 fs-6 rounded-pill">
                Trạng thái: {{ ucfirst($order->status) }}
            </span>
        </div>

        {{-- Thông tin người nhận --}}
        <div class="bg-white p-4 rounded shadow-sm mb-4">
            <h6 class="fw-bold mb-3">👤 Thông tin người nhận</h6>
            <ul class="list-unstyled mb-0">
                <li><strong>Họ tên:</strong> {{ $order->name }}</li>
                <li><strong>Điện thoại:</strong> {{ $order->phone }}</li>
                <li><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->district }},
                    {{ $order->province }}</li>
                <li><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</li>
            </ul>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="bg-white p-4 rounded shadow-sm mb-4">
            <h6 class="fw-bold mb-3">🛍️ Sản phẩm đã đặt</h6>
            @foreach ($order->orderItems as $item)
                <div class="d-flex border-bottom pb-3 mb-3">
                    <img src="{{ asset('storage/' . $item->product->image) }}" width="80" class="rounded border">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $item->product_name }}</div>
                        <div class="small text-muted">Phân loại: {{ $item->variant_name ?? 'Mặc định' }}</div>
                        <div class="small">Số lượng: x{{ $item->quantity }}</div>
                    </div>
                    <div class="text-end text-danger fw-bold">
                        {{ number_format($item->price * $item->quantity) }}₫
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Thông tin đơn hàng --}}
        <div class="bg-white p-4 rounded shadow-sm mb-4">
            <h6 class="fw-bold mb-3">🧾 Thông tin đơn hàng</h6>
            <div class="d-flex justify-content-between mb-2">
                <span>Ngày đặt:</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Phương thức thanh toán:</span>
                <span class="text-uppercase">{{ $order->payment_method }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Trạng thái thanh toán:</span>
                <span>{{ ucfirst($order->payment_status) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Phí vận chuyển:</span>
                <span>{{ number_format($order->shipping_fee) }}₫</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Giảm giá:</span>
                <span class="text-success">-{{ number_format($order->discount_amount) }}₫</span>
            </div>
            <div class="border-top pt-2 d-flex justify-content-between fw-bold fs-5">
                <span>Tổng cộng:</span>
                <span class="text-danger">{{ number_format($order->total_amount) }}₫</span>
            </div>
        </div>
        <!-- resources/views/orders/show.blade.php -->
        @if ($order->status === 'completed')
            <div class="card mt-4">
                <div class="card-header">Đánh giá sản phẩm</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->orderItems as $item)
                                    @php
                                        $product = $item->product;
                                        $productId = $product->id;
                                        $canReview = $order->canReviewProduct($productId) ?? false;
                                        $review = $order->reviews->where('product_id', $productId)->first();
                                    @endphp

                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            @if ($canReview && !$review)
                                                <a href="{{ route('reviews.create', ['order' => $order, 'product' => $product]) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Viết đánh giá
                                                </a>
                                            @elseif($review)
                                                <a href="{{ route('reviews.edit', $review) }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    Xem/Sửa đánh giá
                                                </a>
                                            @else
                                                <span class="text-muted">
                                                    Không thể đánh giá
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Không có sản phẩm nào trong đơn hàng</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        <div class="text-center mt-4">
            <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary">⬅ Quay lại danh sách đơn
                hàng</a>
        </div>
    </div>
@endsection

@section('footer')
    @include('client.layouts.partials.footer')

    {{-- Font đẹp + Animate CSS --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        body {
            background-color: #f8f9fa;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .fw-semibold {
            font-weight: 600;
        }
    </style>
@endsection
