@extends('client.layouts.layout')

@section('content')
<div class="container my-5">
    <form method="POST" action="{{ route('checkout.store', ['orderId' => $order->id ?? null]) }}">
        @csrf
        <div class="card shadow">
            <div class="card-header bg-white border-bottom d-flex">
                <div class="flex-fill text-center {{ request()->routeIs('checkout.index') ? 'fw-bold text-danger border-bottom border-3 border-danger pb-2' : 'text-secondary' }}">
                    <H6 class="fw-bold" style="cursor: default;"> THÔNG TIN</H6>
                </div>
                <div class="flex-fill text-center {{ request()->routeIs('checkout.payment') ? 'fw-bold text-danger border-bottom border-3 border-danger pb-2' : 'text-secondary' }}">
                    @if (isset($order))
                    <a href="{{ route('checkout.payment', ['orderId' => $order->id]) }}" class="text-decoration-none text-danger">THANH TOÁN</a>
                    @else
                    <span class="text-muted" style="cursor: not-allowed;"> THANH TOÁN</span>
                    @endif
                </div>

            </div>


            <div class="card-body">
                <!-- Hiển thị sản phẩm trong giỏ hàng -->
                @foreach ($cartItems as $item)
                @php
                $price = $item->variant->price;
                $qty = $item->quantity;
                $itemTotal = $price * $qty;
                $product = $item->variant->product;
                $imgPath = $product->images->first()->path ?? $product->image;
                @endphp
                <div class="d-flex align-items-center bg-light p-3 rounded mb-4">
                    <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded me-3" style="width: 60px; height: 60px;">
                        <img src="{{ asset('storage/' . $imgPath) }}" width="80" class="img-thumbnail">
                    </div>
                    <div class="flex-grow-1">
                        <div>
                            <h5 class="mb-1 fw-bold">{{ $item->variant->product->name }}
                                <div>
                                    @foreach ($item->variant->options as $opt)
                                    <small>{{ $opt->attribute->name }}: {{ $opt->option->value }}</small><br>
                                    @endforeach

                                </div>
                            </h5>


                        </div>

                        <div class="d-flex align-items-center">
                            <span class="text-danger fw-bold me-2">{{ number_format($item->variant->price, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                    <span class="badge bg-primary ms-auto">Số lượng: {{ $item->quantity }}</span>
                </div>
                @endforeach

                <!-- THÔNG TIN KHÁCH HÀNG -->
                <h6 class="text-primary mb-3">THÔNG TIN KHÁCH HÀNG</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $order->name ?? (auth()->user()->name ?? '')) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $order->email ?? (auth()->user()->email ?? '')) }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $order->phone ?? '') }}" required>
                </div>

                <!-- GIAO HÀNG -->
                <hr class="my-4">
                <h6 class="text-primary mb-3">THÔNG TIN NHẬN HÀNG</h6>

                <div class="d-flex gap-3 mb-3">
                    <div class="form-check flex-fill border border-danger p-3 rounded bg-danger bg-opacity-10 text-center">
                        <input class="form-check-input" type="radio" name="shipping_method" id="delivery" value="home_delivery" {{ old('shipping_method', $order->shipping_method ?? 'home_delivery') == 'home_delivery' ? 'checked' : '' }}>
                        <label class="form-check-label" for="delivery">Giao hàng tận nơi</label>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tỉnh / Thành phố</label>
                        <input type="text" name="province" class="form-control" value="{{ old('province', $order->province ?? (auth()->user()->province ?? '')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Quận / Huyện</label>
                        <input type="text" name="district" class="form-control" value="{{ old('district', $order->district ?? '') }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Phường / Xã</label>
                        <input type="text" name="ward" class="form-control" value="{{ old('ward', $order->ward ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số nhà, tên đường</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $order->address ?? '') }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Ghi chú đơn hàng</label>
                    <input type="text" name="note" class="form-control" value="{{ old('note', $order->note ?? '') }}">
                </div>

                <!-- HÓA ĐƠN -->
                <div class="mb-3">
                    <strong>Quý khách có muốn xuất hóa đơn công ty không?</strong>
                    <div class="ms-3 mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="invoice" value="1" id="invoice-yes" {{ old('invoice', $order->invoice ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="invoice-yes">Có</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="invoice" value="0" id="invoice-no" {{ old('invoice', $order->invoice ?? '0') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="invoice-no">Không</label>
                        </div>
                    </div>
                </div>
                <!-- Tổng tiền -->
                <div class="d-flex justify-content-between bg-light p-3 rounded mb-3">
                    <span class="fw-bold">Tổng tiền tạm tính:</span>
                    <span class="fw-bold text-danger fs-5">{{ number_format($totalAmount, 0, ',', '.') }}đ</span>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-danger w-100 py-2">Tiếp tục</button>
            </div>
        </div>
    </form>
</div>
@endsection
