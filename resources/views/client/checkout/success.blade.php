@extends('client.layouts.app')

@section('content')
    <div class="alert alert-success mt-4">
        <h4>🎉 Cảm ơn bạn đã đặt hàng!</h4>
        <p>Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>
        <a href="{{ route('index') }}" class="btn btn-primary mt-3">Quay về trang chủ</a>
    </div>
@endsection
