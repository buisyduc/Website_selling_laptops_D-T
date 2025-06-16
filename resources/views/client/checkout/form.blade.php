@extends('client.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Thông tin người nhận</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Họ tên</label>
        <input type="text" name="fullname" class="form-control" value="{{Auth::user()->name}}" >
    </div>

    <div class="mb-3">
        <label>Số điện thoại</label>
        <input type="text" name="phone" class="form-control"value="{{Auth::user()->phone}}" >
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control"value="{{Auth::user()->email}}">
    </div>

    <div class="mb-3">
        <label>Địa chỉ giao hàng</label>
        <input type="text" name="address" class="form-control" value="{{Auth::user()->address}}">
    </div>

    <h5>Phương thức thanh toán</h5>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
        <label class="form-check-label" for="cod">
            Thanh toán khi nhận hàng (COD)
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="payment_method" value="vnpay" id="vnpay">
        <label class="form-check-label" for="vnpay">
            Thanh toán qua VNPay
        </label>
    </div>
    <h5 class="mt-4">Thông tin đơn hàng</h5>
        <ul class="list-group mb-3">
            @php $total = 0; @endphp
            @foreach ($cart as $item)
                @php $itemTotal = $item['price'] * $item['quantity']; $total += $itemTotal; @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $item['product_name'] }}</strong> (x{{ $item['quantity'] }})
                    </div>
                    <span>{{ number_format($itemTotal, 0, ',', '.') }}₫</span>
                </li>
            @endforeach
            <li class="list-group-item d-flex justify-content-between">
                <strong>Tổng cộng</strong>
                <strong>{{ number_format($total, 0, ',', '.') }}₫</strong>
            </li>
        </ul>

    <button type="submit" class="btn btn-primary mt-3">Đặt hàng</button>
</form>
</div>
@endsection
