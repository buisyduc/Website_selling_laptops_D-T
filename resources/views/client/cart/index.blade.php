@extends('client.layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">🛒 Giỏ hàng</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($cart) > 0)
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $key => $item)
                    @php
                        $item_total = $item['price'] * $item['quantity'];
                        $total += $item_total;
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . ($item['image'] ?? 'default.jpg')) }}" width="80" class="img-thumbnail">
                        </td>
                        <td>
                            <strong>{{ $item['product_name'] }}</strong><br>
                            <small>SKU: {{ $item['sku'] ?? 'N/A' }}</small>
                        </td>
                        <td>{{ number_format($item['price'], 0, ',', '.') }}₫</td>
                        <td style="width: 100px;">
                            <input type="number"
                                   value="{{ $item['quantity'] }}"
                                   min="1"
                                   class="form-control quantity-input"
                                   data-id="{{ $key }}"
                                   data-price="{{ $item['price'] }}">
                        </td>
                        <td class="item-total">{{ number_format($item_total, 0, ',', '.') }}₫</td>
                        <td>
                            <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                                @csrf
                                <input type="hidden" name="variant_id" value="{{ $key }}">
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                    <td colspan="2"><strong id="cart-total">{{ number_format($total, 0, ',', '.') }}₫</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <a href="#" class="btn btn-primary">Tiến hành thanh toán</a>
    </div>

    @else
        <div class="alert alert-info">Giỏ hàng của bạn đang trống.</div>
    @endif
</div>
@endsection
