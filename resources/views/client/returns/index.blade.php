@extends('layouts.client')

@section('content')
<h2>Đơn hàng đủ điều kiện hoàn</h2>
@foreach($orders as $order)
    <div>
        <p>Đơn hàng #{{ $order->id }} - Tổng: {{ number_format($order->total, 0, ',', '.') }}₫</p>
        <a href="{{ route('returns.create', $order->id) }}">Yêu cầu hoàn hàng</a>
    </div>
    @if($return->status === 'approved')
    <form action="{{ route('admin.returns.refund', $return->id) }}" method="POST" onsubmit="return confirm('Xác nhận hoàn tiền?')">
        @csrf
        <button type="submit">Hoàn tiền</button>
    </form>
@endif

@endforeach
@endsection
