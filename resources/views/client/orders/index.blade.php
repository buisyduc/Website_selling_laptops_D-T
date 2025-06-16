@extends('client.layouts.layout')

@section('content')
    <h2>Đơn hàng của bạn</h2>
    @foreach ($orders as $order)
        <div>
            <p>Mã đơn: {{ $order->order_code }}</p>
           <p>Tổng tiền: {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</p>
            <td>Trạng Thái: {{ $order->status_text }}</td>

            <hr>
        </div>
    @endforeach
@endsection
