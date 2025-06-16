@extends('client.layouts.layout')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Trang Thanh Toán</h2>
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Thông tin người nhận -->
            <div class="col-md-6">
                <h5>Thông tin người nhận</h5>
                <div class="form-group mb-2">
                    <label>Họ và tên</label>
                    <input type="text" name="name" class="form-control" placeholder="Tên người nhận" required>
                </div>
                <div class="form-group mb-2">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" required>
                </div>
                <div class="form-group mb-2">
                    <label>Địa chỉ thanh toán</label>
                    <input type="text" name="address" class="form-control" placeholder="Địa chỉ thanh toán" required>
                </div>
                <div class="form-group mb-2">
                    <label>Địa chỉ giao hàng</label>
                    <input type="text" name="shipping_address" class="form-control" placeholder="Địa chỉ giao hàng" required>
                </div>
                <div class="form-group mb-2">
                    <label>ID mã giảm giá (nếu có)</label>
                    <input type="text" name="coupon_id" class="form-control" placeholder="Coupon ID">
                </div>
                <div class="form-group mb-2">
                    <label>Ghi chú</label>
                    <textarea name="note" class="form-control" rows="3" placeholder="Ghi chú thêm..."></textarea>
                </div>
            </div>

            <!-- Phương thức và tóm tắt -->
            <div class="col-md-6">
                <label for="payment_method" class="form-label">💳 Chọn phương thức thanh toán</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                    <label class="form-check-label">🚚 Thanh toán khi nhận hàng (COD)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" value="banking">
                    <label class="form-check-label">🏦 Chuyển khoản ngân hàng</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" value="momo">
                    <label class="form-check-label">📱 Ví Momo</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" value="vnpay">
                    <label class="form-check-label">🌐 VNPay / ZaloPay</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" value="credit_card">
                    <label class="form-check-label">💳 Thẻ tín dụng / ghi nợ</label>
                </div>

                <hr>
                <h4 class="mt-4 mb-3">🛒 Tóm tắt đơn hàng</h4>
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $item->variant->product->name ?? 'Sản phẩm đã xoá' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->variant->price, 0, ',', '.') }}₫</td>
                            <td>{{ number_format($item->quantity * $item->variant->price, 0, ',', '.') }}₫</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Tổng cộng</th>
                            <th>{{ number_format($total, 0, ',', '.') }}₫</th>
                        </tr>
                    </tfoot>
                </table>


                <button type="submit" class="btn btn-success w-100 mt-3">🛒 Đặt hàng ngay</button>
            </div>
        </div>
    </form>
</div>
@endsection
