@extends('client.layout.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Chi tiết đơn hàng</h3>
        @if(isset($order) && in_array($order->status, ['shipping','completed']))
            <a href="{{ route('orders.return.form', $order->id) }}" class="btn btn-outline-danger">
                Yêu cầu trả hàng / hoàn tiền
            </a>
        @endif
    </div>

    {{-- Nội dung chi tiết đơn hàng hiện có của bạn đặt ở đây --}}
    <h1>jjjjjjjjjj</h1>
    {{-- ... --}}
</div>
@endsection