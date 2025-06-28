@extends('layouts.client')

@section('content')
<h2>Lịch sử yêu cầu hoàn hàng</h2>
@foreach($returns as $return)
    <div>
        <p>Đơn hàng #{{ $return->order_id }} - Trạng thái: {{ ucfirst($return->status) }}</p>
        <p>Lý do: {{ $return->reason }}</p>
        <p>Ngày gửi: {{ $return->created_at->format('d/m/Y') }}</p>
    </div>
@endforeach
@endsection
