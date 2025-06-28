@extends('layouts.client')

@section('content')
<h2>Yêu cầu hoàn hàng - Đơn #{{ $order->id }}</h2>
<form action="{{ route('returns.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="order_id" value="{{ $order->id }}">

    <label>Lý do hoàn hàng:</label>
    <input type="text" name="reason" required>

    <label>Ghi chú thêm:</label>
    <textarea name="note"></textarea>

    <label>Ảnh minh chứng (nếu có):</label>
    <input type="file" name="image">

    <button type="submit">Gửi yêu cầu</button>
</form>
@endsection
