@extends('client.layouts.layout')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <div class="container py-5">
        <h2 class="mb-4">🛒 Giỏ hàng</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($cartItems->count() > 0)
            <form action="{{ route('cart.updateAll') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="table-responsive">
                    <!-- Toast container góc phải -->
                    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
                        <div id="cartToast" class="toast align-items-center text-white bg-danger border-0" role="alert"
                            aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body" id="cartToastBody">
                                   <div class="text-danger small mt-1 qty-error" style="display:none;"></div>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th>Xoá</th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            @php $grandTotal = 0; @endphp

                            @foreach ($cartItems as $item)
                                @php
                                    $price = $item->variant->price;
                                    $qty = $item->quantity;
                                    $itemTotal = $price * $qty;
                                    $grandTotal += $itemTotal;
                                    $product = $item->variant->product;
                                    $imgPath = $product->images->first()->path ?? $product->image;
                                @endphp
                                <tr data-variant-id="{{ $item->variant_id }}">
                                    <td class="text-center">
                                        <img src="{{ asset('storage/' . $imgPath) }}" width="80" class="img-thumbnail">
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong><br>
                                        @foreach ($item->variant->options as $opt)
                                            <small>{{ $opt->attribute->name }}: {{ $opt->option->value }}</small><br>
                                        @endforeach
                                    </td>
                                    <td class="text-end">{{ number_format($price, 0, ',', '.') }}₫</td>
                                    <td class="text-center" style="width:100px">
                                        <input type="number" name="quantities[{{ $item->variant_id }}]"
                                            value="{{ $qty }}" min="1" class="form-control update-quantity"
                                            data-variant-id="{{ $item->variant_id }}">
                                    </td>

                                    <td class="text-end item-total">{{ number_format($itemTotal, 0, ',', '.') }}₫</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger delete-item">Xoá</button>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td colspan="2" class="text-end"><strong
                                        id="cart-total-in-table">{{ number_format($grandTotal, 0, ',', '.') }}₫</strong>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-outline-danger" onclick="clearCart()">🗑 Xoá toàn bộ</button>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('index') }}" class="btn btn-outline-secondary">← Tiếp tục mua hàng</a>
                    <div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-success">Mua ngay</a>
                    </div>
                </div>
            </form>
        @else
            <div class="alert alert-info">Giỏ hàng của bạn đang trống.</div>
        @endif
    </div>
 <script>
document.addEventListener('DOMContentLoaded', function() {
  const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
  const toastEl = document.getElementById('cartToast');
  const toastBody = document.getElementById('cartToastBody');
  // Khởi tạo toast với delay 5000ms và autohide = true
  const bsToast = new bootstrap.Toast(toastEl, { delay: 5000, autohide: true });

  document.querySelectorAll('.update-quantity').forEach(input => {
    input.addEventListener('change', function() {
      const variantId = this.dataset.variantId;
      const quantity = parseInt(this.value, 10);

      fetch('/cart/update-item', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ variant_id: variantId, quantity: quantity })
      })
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          // Hiển thị toast góc phải với nội dung lỗi
          toastBody.textContent = data.message;
          bsToast.show();

          // reset input về số kho cho phép
          this.value = data.allowed_quantity;
        } else {
          // Cập nhật lại UI khi thành công
          const row = this.closest('tr');
          row.querySelector('.item-total').textContent = data.item_total;
          document.getElementById('cart-total-in-table').textContent = data.cart_total;
        }
      });
    });
  });
});

</script>




@section('footer')
    @include('client.layouts.partials.footer')
@endsection
@endsection
