@extends('client.layouts.layout')

@section('content')
    <div class="container py-4 fs-4">
        <h4 class="mb-4">Chi tiết đơn hàng #{{ $order->order_code }}</h4>

        <div class="row">
            <!-- Thông tin người nhận -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm p-4">
                    <h5 class="fs-5">Thông tin người nhận</h5>
                    <p class="fs-6"><strong>Họ tên:</strong> {{ $order->name }}</p>
                    <p class="fs-6"><strong>SĐT:</strong> {{ $order->phone }}</p>
                    <p class="fs-6"><strong>Email:</strong> {{ $order->email }}</p>
                    <p class="fs-6"><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</p>
                    <p class="fs-6"><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
                    <p class="fs-6"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p class="fs-6"><strong>Trạng thái:</strong>
                        @switch($order->status)
                            @case('pending')
                                <span class="badge bg-warning">Chờ thanh toán</span>
                                @break
                            @case('processing_seller')
                                <span class="badge bg-info">Chờ lấy hàng</span>
                                @break
                            @case('processing')
                                <span class="badge bg-info">Chờ giao hàng</span>
                                @break
                            @case('shipping')
                                <span class="badge bg-primary">Đang vận chuyển</span>
                                @break
                            @case('completed')
                                <span class="badge bg-success">Hoàn thành</span>
                                @break
                            @case('cancelled')
                            @case('canceled')
                                <span class="badge bg-danger">Đã hủy</span>
                                @break
                            @case('returned')
                                <span class="badge bg-danger">Trả hàng/Hoàn tiền</span>
                                @break
                            @default
                                <span class="badge bg-secondary">Không xác định</span>
                        @endswitch
                    </p>
                </div>
            </div>

            <!-- Thông tin thanh toán -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm p-4">
                    <h5 class="fs-5">Thông tin thanh toán</h5>
                    <p class="fs-6"><strong>Hình thức thanh toán:</strong> {{ ucfirst($order->payment_method ?? 'Không rõ') }}</p>
                    <p class="fs-6"><strong>Trạng thái thanh toán:</strong>
                        @php
                            switch ($order->payment_status) {
                                case 'paid':
                                    $paymentStatusVN = 'Đã thanh toán';
                                    break;
                                case 'unpaid':
                                    $paymentStatusVN = 'Chưa thanh toán';
                                    break;
                                case 'pending':
                                    $paymentStatusVN = 'Đang xử lý';
                                    break;
                                case 'failed':
                                    $paymentStatusVN = 'Thanh toán thất bại';
                                    break;
                                default:
                                    $paymentStatusVN = 'Không xác định';
                            }
                        @endphp
                        {{ $paymentStatusVN }}
                    </p>
                    <p class="fs-6"><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }}₫</p>
                    @if ($order->discount_amount > 0)
                        <p class="fs-6"><strong>Giảm giá:</strong> -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</p>
                    @endif
                    <p class="fs-5 fw-bold"><strong>Tổng cộng:</strong> <span class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span></p>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="card shadow-sm p-4">
            <h5 class="fs-5">Sản phẩm trong đơn hàng</h5>
            <div class="table-responsive mt-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="fs-6">
                            <th>Sản phẩm</th>
                            <th>Phân loại</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                        </tr>
                    </thead>
                    <tbody class="fs-6">
                        @foreach ($order->items as $item)
                            @php
                                $product = optional(optional($item->variant)->product);
                                $productName = $product->name ?? 'Sản phẩm đã bị xóa';
                                $imagePath = $product->image ?? null;
                                $productImage = $imagePath ? asset('storage/' . $imagePath) : asset('images/default-product.jpg');
                                $price = $item->variant->price ?? 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $productImage }}" alt="Ảnh" width="60" class="me-2 rounded">
                                        <div>{{ $productName }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->variant && $item->variant->variantOptions)
                                        @foreach ($item->variant->variantOptions as $option)
                                            <div>{{ $option->attribute->name ?? 'Thuộc tính' }}: {{ $option->option->value ?? 'Giá trị' }}</div>
                                        @endforeach
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td>{{ number_format($price, 0, ',', '.') }}₫</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="fs-6">
                        <tr>
                            <td colspan="3" class="text-end">Tạm tính:</td>
                            <td>{{ number_format($order->items->sum(fn($item) => $item->variant->price * $item->quantity), 0, ',', '.') }}₫</td>
                        </tr>
                        @if ($order->coupon)
                            <tr>
                                <td colspan="3" class="text-end">Mã giảm giá ({{ $order->coupon->code }}) :</td>
                                <td>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
                            </tr>
                        @endif
                        @if ($order->shipping_fee > 0)
                            <tr>
                                <td colspan="3" class="text-end">Phí vận chuyển:</td>
                                <td>{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                            <td class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        {{-- rate --}}
         @if ($order->status === 'completed')
            @php
                // Eager load quan hệ cần thiết
                $order->load(['orderItems.product', 'reviews']);
            @endphp

            <div class="card mt-4">
                <div class="card-header">Đánh giá sản phẩm</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đánh giá</th>
                                    <th>Số lần đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->orderItems as $item)
                                    @php
                                        $product = $item->product;
                                        $productId = $product->id;
                                        $reviews = $order->reviews->where('product_id', $productId);
                                        $hasReviews = $reviews->count() > 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <!-- Phần thêm đánh giá mới (chỉ hiển thị nếu chưa đạt giới hạn) -->
                                            @if ($order->canReviewProduct($productId))
                                                <a href="{{ route('reviews.create', ['order' => $order, 'product' => $product]) }}"
                                                    class="btn btn-sm btn-outline-primary mb-2 me-2">
                                                    Thêm đánh giá mới
                                                </a>
                                            @endif

                                            <!-- Phần hiển thị các đánh giá cũ (luôn hiển thị nếu có) -->
                                            @if ($hasReviews)
                                                <div class="d-flex flex-column gap-2">
                                                    @foreach ($reviews as $review)
                                                        <div class="d-flex align-items-center gap-2">
                                                            <a href="{{ route('reviews.edit', $review) }}"
                                                                class="btn btn-sm btn-outline-secondary">
                                                                Xem/Sửa #{{ $loop->iteration }}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Chưa có đánh giá</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $reviews->count() }} đánh giá
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Không có sản phẩm nào trong đơn hàng</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        <!-- Nút quay lại -->
        <div class="pt-2 mb-5">
            <a href="{{ route('client.orders.index') }}"
                class="btn btn-light border d-inline-flex align-items-center shadow-sm px-3 py-2 rounded">
                <i class="bi bi-arrow-left me-2"></i> Quay lại đơn hàng
            </a>
        </div>

        <!-- Nút hủy đơn -->
        @if ($order->status === 'pending')
            <div class="mt-4 text-end">
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Bạn có chắc muốn hủy đơn hàng?')">
                        Hủy đơn hàng
                    </button>
                </form>
            </div>
        @endif
    </div>

    @section('footer')
        @include('client.layouts.partials.footer')
    @endsection
@endsection
@push('scripts')
    <script>
        let isProcessing = false;

        function handleReviewClick(element) {
            if (isProcessing) {
                return false;
            }
            isProcessing = true;

            // Thêm hiệu ứng loading (tuỳ chọn)
            element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

            // Cho phép click lại sau 2s nếu trang không chuyển
            setTimeout(() => {
                isProcessing = false;
                element.innerHTML = 'Viết đánh giá';
            }, 2000);

            return true;
        }
    </script>
@endpush