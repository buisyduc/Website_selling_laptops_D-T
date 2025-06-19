@extends('client.layouts.layout')
@section('content')
    <div class="container my-5">


        <div class="card-header bg-white border-bottom d-flex">
            <div
                class="flex-fill text-center {{ request()->routeIs('checkout.index') ? 'fw-bold text-danger border-bottom border-3 border-danger pb-2' : 'text-secondary' }}">
                @if (\App\Models\Cart::where('user_id', auth()->id())->whereHas('items')->exists())
                    <a href="{{ route('checkout.index', ['orderId' => $order->id]) }}">THÔNG TIN</a>
                @else
                    <span class="text-muted"> THÔNG TIN</span>
                @endif
            </div>

            <div
                class="flex-fill text-center {{ request()->routeIs('checkout.payment') ? 'fw-bold text-danger border-bottom border-3 border-danger pb-2' : 'text-secondary' }}">
                <H6 class="fw-bold" style="cursor: default;"> THANH TOÁN</H6>
            </div>
        </div>
        <form action="{{ route('checkout.paymentStore') }}" method="POST" onsubmit="return validateBeforeSubmit()">
            @csrf

            <!-- Nhập mã giảm giá thủ công -->
            <div id="manual-coupon-input">
                <div class="rounded-3 px-3 py-2 d-flex justify-content-between align-items-center fw-medium">
                    <input id="coupon_input" type="text" class="form-control border-0" name="coupon_input"
                        placeholder="Nhập mã giảm giá (chỉ áp dụng 1 lần)">
                    <button type="button" class="btn btn-outline-success btn-sm" id="manualApplyBtn"
                        onclick="applyManualCoupon()" disabled>Áp dụng</button>
                </div>
                <!-- Thông báo lỗi (nếu có) -->
                <div id="coupon-error" class="text-danger small mt-2" style="display:none;"></div>
            </div>

            <!-- Vùng hiển thị mã đã chọn -->
            <p id="coupon-description" class="mt-2" style="display: none;">
                Mã đang áp dụng: <strong id="selected-coupon-text">Không</strong>
                – <a href="#" onclick="resetCoupon()">Chọn lại mã</a>
            </p>

            <!-- Nút mở modal chọn mã -->
            <div class="border border-success rounded-3 px-3 py-2 d-flex justify-content-between align-items-center text-success fw-medium"
                style="cursor: pointer; background-color: #f8fbff;" data-bs-toggle="modal" data-bs-target="#couponModal">
                <span>hoặc chọn từ 1 mã giảm giá có sẵn</span>
                <i class="fas fa-chevron-right"></i>
            </div>

            <!-- Hidden input lưu coupon_id -->
            <input type="hidden" name="coupon_id" id="selected-coupon-id">

            <!-- Modal chọn mã giảm giá -->
            <div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4">
                        <div class="modal-header">
                            <h5 class="modal-title" id="couponModalLabel">Chọn mã giảm giá</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            @foreach ($availableCoupons as $coupon)
                                <div class="border rounded p-3 mb-3 d-flex align-items-start">
                                    <div class="flex-grow-1 text-start">
                                        <div class="mb-1">
                                            <strong class="text-dark fw-bold d-block">{{ $coupon->code }}</strong>
                                        </div>
                                        <div class="mb-1">
                                            Giảm <strong
                                                class="text-dark">{{ number_format($coupon->discount_percent, '.00') }}%</strong>
                                            cho đơn từ <strong
                                                class="text-dark">{{ number_format($coupon->min_order_amount, 0, ',', '.') }}₫</strong>,
                                            giảm tối đa <strong
                                                class="text-dark">{{ number_format($coupon->max_discount, 0, ',', '.') }}₫</strong>
                                        </div>
                                        <div class="small text-muted">
                                            HSD: {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <input class="form-check-input mt-1" type="radio" name="selected_coupon"
                                            value="{{ $coupon->id }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-primary" onclick="applySelectedCoupon()">Xác nhận</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin tạm tính -->
            <ul class="list-group list-group-flush my-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Số lượng sản phẩm</span>
                    <span>{{ $cart->items->sum('quantity') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Tổng tiền hàng</span>
                    <span>{{ number_format($cartTotal, 0, ',', '.') }}₫</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Phí vận chuyển</span>
                    <span class="text-success">Miễn phí</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Giảm giá trực tiếp</span>
                    <span class="text-danger discount-amount" id="discount-amount">
                        {{ number_format($discountAmount ?? 0, 0, ',', '.') }}₫
                    </span>
                </li>

                <li class="list-group-item d-flex justify-content-between border-top pt-3">
                    <div>
                        <strong>Tổng tiền</strong>
                        <div class="text-muted small">Đã gồm VAT và được làm tròn</div>
                    </div>
                    <strong class="text-danger fs-5 total-amount" id="total-amount">
                        {{ number_format($totalAmount, 0, ',', '.') }}₫
                    </strong>
                </li>
            </ul>

            <!-- Hidden dữ liệu -->
            <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
            <input type="hidden" name="shipping_fee" value="0">
            <input type="hidden" name="discount_amount" value="{{ $discountAmount ?? 0 }}">
            <input type="hidden" name="shipping_method" value="home_delivery">
            <input type="hidden" name="note" value="{{ $note ?? '' }}">
            <input type="hidden" name="payment_method" id="payment_method_input">

            <!-- Modal chọn phương thức thanh toán -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">THÔNG TIN THANH TOÁN</h6>
                <div class="bg-light rounded d-flex justify-content-between align-items-center p-3"
                    style="cursor:pointer;" onclick="showPaymentModal()">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded p-2 me-3">
                            💳
                        </div>
                        <div>
                            <div class="fw-bold" id="selectedPaymentText">Chọn phương thức thanh toán</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="paymentModal" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="z-index: 1055;">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="bg-white rounded p-5 shadow" style="width: 800px; max-width: 95%; min-height: 400px;">
                        <h5 class="mb-3 text-center">Chọn phương thức thanh toán</h5>
                        <div class="list-group" id="paymentOptions">
                            <button type="button" class="list-group-item list-group-item-action" data-method="cod">
                                Thanh toán khi nhận hàng (COD)
                            </button>
                            <button type="button" class="list-group-item list-group-item-action"
                                data-method="bank_transfer">
                                Chuyển khoản ngân hàng
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" data-method="momo">
                                Ví MoMo
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" data-method="vnpay">
                                VNPay
                            </button>
                        </div>
                        <button class="btn btn-danger mt-3 w-100" onclick="closePaymentModal()">Huỷ</button>
                    </div>
                </div>
            </div>

            <!-- THÔNG TIN NHẬN HÀNG -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">THÔNG TIN NHẬN HÀNG</h6>
                <div class="bg-light rounded p-3">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Khách hàng</span>
                        <span class="fw-medium">{{ $user->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Số điện thoại</span>
                        <span class="fw-medium">{{ $order->phone }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Email</span>
                        <span class="fw-medium">{{ $user->email }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Nhận hàng tại</span>
                        <span class="fw-medium">{{ $order->shipping_address }}</span>
                    </div>
                </div>
            </div>

            <!-- Nút thanh toán -->
            <div class="bg-light rounded p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Tổng tiền tạm tính:</span>
                    <span
                        class="text-danger fw-bold fs-5 total-amount">{{ number_format($totalAmount, 0, ',', '.') }}₫</span>
                </div>

            </div>

            <button type="submit" class="btn btn-danger w-100 py-2 mb-3 fw-bold">Đặt hàng</button>


        </form>

        <!-- Scripts -->
        <script>
            const iconMap = {
                cod: '💳',
                bank_transfer: '🏦',
                momo: '📱',
                vnpay: '🌐'
            };

            function showPaymentModal() {
                document.getElementById('paymentModal').classList.remove('d-none');
            }

            function closePaymentModal() {
                document.getElementById('paymentModal').classList.add('d-none');
            }

            document.querySelectorAll('#paymentOptions button').forEach(btn => {
                btn.addEventListener('click', function() {
                    const method = this.dataset.method;
                    const text = this.textContent.trim();
                    const icon = iconMap[method] || '💳';

                    document.getElementById('selectedPaymentText').textContent = text;
                    document.querySelector('.bg-primary').textContent = icon;
                    document.getElementById('payment_method_input').value = method;

                    closePaymentModal();
                });
            });
        </script>

        <script>
            async function applyManualCoupon() {
                const code = document.getElementById('coupon_input').value.trim();
                const hiddenCouponId = document.getElementById('selected-coupon-id');
                const manualInputBox = document.getElementById('manual-coupon-input');
                const couponDesc = document.getElementById('coupon-description');

                if (!code) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa nhập mã!',
                        text: 'Vui lòng nhập mã giảm giá trước khi áp dụng.',
                    });
                    return;
                }

                try {
                    const response = await fetch('{{ route('checkout.applyCoupon') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            code
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Mã không hợp lệ',
                            text: result.error || 'Không tìm thấy mã giảm giá!',
                        });
                        return;
                    }

                    // ✅ Nếu thành công thì update UI
                    document.getElementById('selected-coupon-text').innerText = result.code;
                    hiddenCouponId.value = result.coupon_id;

                    document.querySelector('input[name="discount_amount"]').value = result.discount_amount;
                    document.querySelector('input[name="total_amount"]').value = result.total_amount;

                    document.querySelectorAll('.discount-amount, #discount-amount')
                        .forEach(e => e.innerText = '- ' + formatCurrency(result.discount_amount));
                    document.querySelectorAll('.total-amount, #total-amount')
                        .forEach(e => e.innerText = formatCurrency(result.total_amount));

                    if (manualInputBox) manualInputBox.classList.add('d-none');
                    if (couponDesc) couponDesc.style.display = '';

                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi máy chủ',
                        text: 'Không thể kết nối đến server. Vui lòng thử lại sau!',
                    });
                }
            }


            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount);
            }

            document.getElementById('coupon_input').addEventListener('input', function() {
                document.getElementById('manualApplyBtn').disabled = this.value.trim() === '';
            });
        </script>

        <script>
            async function applySelectedCoupon() {
                const selectedRadio = document.querySelector('input[name="selected_coupon"]:checked');
                if (!selectedRadio) {
                    alert("Vui lòng chọn mã");
                    return;
                }

                const couponId = selectedRadio.value;
                const couponCode = selectedRadio.closest('.border').querySelector('strong').innerText;

                try {
                    const response = await fetch('{{ route('checkout.applyCoupon') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            code: couponCode
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        alert(result.error || 'Không áp dụng được mã này');
                        return;
                    }

                    document.getElementById('selected-coupon-text').innerText = result.code;
                    document.getElementById('selected-coupon-id').value = result.coupon_id;

                    document.querySelector('input[name="discount_amount"]').value = result.discount_amount;
                    document.querySelector('input[name="total_amount"]').value = result.total_amount;

                    document.querySelectorAll('.discount-amount, #discount-amount')
                        .forEach(e => e.innerText = '- ' + formatCurrency(result.discount_amount));
                    document.querySelectorAll('.total-amount, #total-amount')
                        .forEach(e => e.innerText = formatCurrency(result.total_amount));

                    const manualInput = document.getElementById('manual-coupon-input');
                    if (manualInput) manualInput.style.display = 'none';

                    const desc = document.getElementById('coupon-description');
                    if (desc) desc.style.display = '';

                    const closeBtn = document.querySelector('#couponModal .btn-close');
                    if (closeBtn) closeBtn.click();
                } catch (error) {
                    console.error('Lỗi server:', error);
                }
            }
        </script>
        <script>
            function resetCoupon() {
                document.getElementById('coupon_input').value = '';
                document.getElementById('selected-coupon-id').value = '';
                document.getElementById('selected-coupon-text').innerText = 'hoặc chọn từ 1 mã giảm giá có sẵn';
                document.getElementById('coupon-description').style.display = 'none';
                document.getElementById('manual-coupon-input').style.display = 'block';

                document.querySelector('input[name="discount_amount"]').value = 0;
                document.querySelector('input[name="total_amount"]').value = {{ $cartTotal ?? 0 }};
                document.querySelectorAll('.discount-amount, #discount-amount').forEach(e => e.innerText = '- 0 ₫');
                document.querySelectorAll('.total-amount, #total-amount').forEach(e => e.innerText = formatCurrency(
                    {{ $cartTotal ?? 0 }}));
            }
        </script>
        <script>
            function validateBeforeSubmit() {
                const method = document.getElementById('payment_method_input').value;
                if (!method) {
                    Swal.fire({
                        title: 'Chưa chọn phương thức thanh toán!',
                        text: 'Vui lòng chọn một phương thức thanh toán trước khi tiếp tục.',
                        icon: 'warning',
                        confirmButtonText: 'Đã hiểu',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }
                return true;
            }
        </script>
    @endsection
