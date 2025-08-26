@extends('client.layouts.layout')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            @if (isset($order) && in_array($order->status, ['shipping', 'completed']))
                <a href="{{ route('orders.return.form', $order->id) }}" class="btn btn-outline-danger">
                    Yêu cầu trả hàng / hoàn tiền
                </a>
            @endif
            @php
                $method = strtolower(trim((string) ($order->payment_method ?? '')));
                $codKeywords = ['cod', 'code', 'cash_on_delivery', 'cash', 'offline'];
                $isOnline = $method !== '' && !in_array($method, $codKeywords, true);
            @endphp
            @if (isset($order) && $isOnline && $order->status === 'pending' && $order->payment_status === 'refund_pending')
                <a href="{{ route('orders.return.form', [$order->id, 'action' => 'cancel']) }}"
                    class="btn btn-outline-secondary ms-2">
                    Thông tin hủy đơn / hoàn tiền
                </a>
            @endif
        </div>

        {{-- Nội dung chi tiết đơn hàng hiện có của bạn đặt ở đây --}}
        <div class="container py-4 fs-4">

            <!-- Nút quay lại -->
            <div class="pt-2 mb-5">
                <a href="{{ route('client.orders.index') }}"
                    class="btn btn-light border d-inline-flex align-items-center shadow-sm px-3 py-2 rounded">
                    <i class="bi bi-arrow-left me-2"></i> Quay lại đơn hàng
                </a>
            </div>

            <h4 class="mb-4">Chi tiết đơn hàng #{{ $order->order_code }}</h4>

            <div class="row g-4 mb-4">

                <!-- Thông tin người nhận -->
                <div class="col-md-6">
                    <div class="card shadow-sm p-4 h-100">
                        <h5 class="fs-5 mb-3">Thông tin người nhận</h5>
                        <p><strong>Họ tên:</strong> {{ $order->name }}</p>
                        <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                        <p><strong>Email:</strong> {{ $order->email }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->district }},
                            {{ $order->province }}</p>
                        <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Thông tin thanh toán -->
                <div class="col-md-6">
                    <div class="card shadow-sm p-4 h-100">
                        <h5 class="fs-5 mb-3">Thông tin thanh toán</h5>
                        <p><strong>Hình thức thanh toán:</strong> {{ ucfirst($order->payment_method ?? 'Không rõ') }}</p>
                        <p><strong>Trạng thái thanh toán:</strong>
                            @php
                                $paymentStatusVN = match ($order->payment_status) {
                                    'paid' => 'Đã thanh toán',
                                    'unpaid' => 'Chưa thanh toán',
                                    'pending' => 'Đang xử lý',
                                    'failed' => 'Thanh toán thất bại',
                                    'refunded' => 'Đã hoàn tiền',
                                    'Waiting_for_order_confirmation' => 'Chưa thanh toán.',
                                    default => 'Không xác định',
                                };
                            @endphp
                            {{ $paymentStatusVN }}
                        </p>
                        <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }}₫</p>
                        @if ($order->discount_amount > 0)
                            <p><strong>Giảm giá:</strong> -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</p>
                        @endif
                        <p class="fw-bold fs-5"><strong>Tổng cộng:</strong> <span
                                class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span></p>
                    </div>
                </div>

            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card shadow-sm p-4 mb-4">
                <h5 class="fs-5 mb-3">Sản phẩm trong đơn hàng</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Phân loại</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                @php
                                    $product = optional(optional($item->variant)->product);
                                    $productName = $product->name ?? 'Sản phẩm đã bị xóa';
                                    $productImage =
                                        $product && $product->image
                                            ? asset('storage/' . $product->image)
                                            : asset('images/default-product.jpg');
                                    $price = $item->variant->price ?? 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $productImage }}" alt="Ảnh" width="60"
                                                class="me-2 rounded">
                                            <div>{{ $productName }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->variant && $item->variant->variantOptions)
                                            @foreach ($item->variant->variantOptions as $option)
                                                <div>{{ $option->attribute->name ?? 'Thuộc tính' }}:
                                                    {{ $option->option->value ?? 'Giá trị' }}</div>
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
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end">Tạm tính:</td>
                                <td>{{ number_format($order->items->sum(fn($i) => $i->variant->price * $i->quantity), 0, ',', '.') }}₫
                                </td>
                            </tr>
                            @if ($order->coupon)
                                <tr>
                                    <td colspan="3" class="text-end">Mã giảm giá ({{ $order->coupon->code }}):</td>
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
                                <td class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.cancel-order-form').forEach(function(form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault(); // Ngăn submit mặc định

                            Swal.fire({
                                title: 'Hủy đơn hàng?',
                                text: 'Bạn có chắc muốn hủy đơn hàng này?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Hủy đơn',
                                cancelButtonText: 'Thoát'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit(); // Submit nếu xác nhận
                                }
                            });
                        });
                    });
                });
            </script>
            <!-- Phần đánh giá (chỉ khi completed và có sản phẩm) -->
            @if ($order->status === 'completed' && $product)
                <div id="review-section" class="card shadow-sm p-4 mb-4">

                    <h5 class="fs-5 mb-3">Đánh giá sản phẩm</h5>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <p><strong>{{ $product->name }}</strong></p>
                    @if ($product->reviews->count() > 0)
                        <p>
                            Đánh giá trung bình: {{ $product->average_rating }}/5 ⭐
                            ({{ $product->reviews->count() }} đánh giá)
                        </p>
                    @else
                        <p>Chưa có đánh giá nào.</p>
                    @endif

                    @auth
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="mb-2">
                                <label>Đánh giá:</label>
                                <select name="rating" class="form-control w-auto">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-2">
                                <textarea name="comment" class="form-control" placeholder="Viết cảm nghĩ của bạn..." rows="3"></textarea>
                            </div>
                            <button class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    @else
                        <p><a href="{{ route('login') }}">Đăng nhập</a> để gửi đánh giá.</p>
                    @endauth

                    <hr>

                    <!-- Danh sách đánh giá -->
                    @foreach ($product->reviews()->latest()->get() as $review)
                        <div class="comment-item mb-3 border p-2 rounded" id="review-{{ $review->id }}">
                            <p><strong>{{ $review->user->name }}</strong> — <span>{{ $review->rating }} ⭐</span></p>
                            <p id="review-text-{{ $review->id }}">{{ $review->comment }}</p>

                            @if ($review->user_id == Auth::id())
                                <div class="d-flex gap-2 mb-2">
                                    <!-- Nút sửa -->
                                    <button class="btn btn-sm btn-warning" onclick="toggleEditForm({{ $review->id }})">
                                        ✏️ Sửa
                                    </button>

                                    <!-- Nút xóa với confirm -->
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑 Xóa</button>
                                    </form>
                                </div>

                                <!-- Form chỉnh sửa ẩn sẵn -->
                                <div id="edit-form-{{ $review->id }}" class="mt-2" style="display:none;">
                                    <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-2">
                                            <label for="rating-{{ $review->id }}" class="form-label">Đánh giá:</label>
                                            <select name="rating" id="rating-{{ $review->id }}" class="form-select"
                                                required>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}"
                                                        {{ $review->rating == $i ? 'selected' : '' }}>
                                                        {{ $i }} ⭐
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="mb-2">
                                            <label for="comment-{{ $review->id }}" class="form-label">Bình luận:</label>
                                            <textarea name="comment" id="comment-{{ $review->id }}" class="form-control" rows="3" maxlength="1000">{{ $review->comment }}</textarea>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm">💾 Cập nhật</button>
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                onclick="toggleEditForm({{ $review->id }})">❌ Hủy</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <script>
                            function toggleEditForm(id) {
                                const form = document.getElementById('edit-form-' + id);
                                form.style.display = (form.style.display === 'none') ? 'block' : 'none';
                            }
                        </script>
                    @endforeach

                </div>
            @endif

        </div>
    @endsection

    @section('footer')
        @include('client.layouts.partials.footer')
    @endsection
