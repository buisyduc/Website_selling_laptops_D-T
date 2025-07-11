@extends('client.layouts.layout')

@section('content')
<div class="container my-5 animate__animated animate__fadeIn">
    <h4 class="mb-4 text-primary fw-bold text-center">📋 Danh sách đơn hàng</h4>

    {{-- Tabs trạng thái --}}
    <ul class="nav nav-tabs mb-4 justify-content-center">
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') == null ? 'active' : '' }}" href="{{ route('client.orders.index') }}">Tất cả</a>
        </li>
        @foreach(['pending' => 'Chờ thanh toán', 'processing' => 'Chờ giao hàng', 'shipping' => 'Vận chuyển', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã huỷ'] as $key => $label)
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') == $key ? 'active' : '' }}" href="{{ route('client.orders.index', ['status' => $key]) }}">{{ $label }}</a>
        </li>
        @endforeach
    </ul>

    @if($orders->isEmpty())
        <div class="alert alert-info text-center">Bạn chưa có đơn hàng nào thuộc trạng thái này.</div>
    @else
        @foreach($orders as $order)
        <div class="border rounded shadow-sm mb-4 bg-white">
            {{-- Header đơn hàng --}}
            <div class="d-flex justify-content-between p-3 border-bottom bg-light">
                <div class="fw-semibold">🛒 {{ $order->shop_name ?? 'Cửa hàng' }}</div>
                <div class="text-uppercase small text-danger fw-bold">{{ ucfirst($order->status) }}</div>
            </div>

            {{-- Sản phẩm --}}
            <div class="px-3 pt-3">
                @foreach ($order->orderItems as $item)
                <div class="d-flex mb-3 align-items-center">
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

            {{-- Tổng tiền + Hành động --}}
            <div class="d-flex justify-content-between align-items-center border-top p-3">
                <div>
                    <span class="fw-bold">Tổng tiền:</span>
                    <span class="text-danger fw-bold">{{ number_format($order->total_amount) }}₫</span>
                </div>
                <div class="text-end">
                    <a href="{{ route('client.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">📄 Xem chi tiết</a>

                    @if($order->status === 'pending')
                        <a href="{{ route('checkout.payment', $order->id) }}" class="btn btn-danger btn-sm ms-2">💳 Thanh toán</a>
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline-block ms-2">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-secondary btn-sm">❌ Huỷ</button>
                        </form>
                    @endif

                    @if($order->status === 'cancelled')
                        <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="d-inline-block ms-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm">🔁 Mua lại</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('index') }}" class="btn btn-outline-secondary">⬅ Về trang chủ</a>
    </div>
</div>
@endsection

@section('footer')
@include('client.layouts.partials.footer')

{{-- Animate.css + font --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>

    .nav-tabs .nav-link.active {
        background-color: #0d6efd;
        color: white;
    }

    .nav-tabs .nav-link {
        font-weight: 500;
    }

    .btn {
        min-width: 100px;
    }
</style>
@endsection
