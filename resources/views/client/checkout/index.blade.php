@extends('client.layouts.layout')

@section('content')
    <div class="container checkout-form-custom">
        <div class="row gx-lg-5">
            <!-- Right Column: Order Summary -->
            <div class="col-lg-7 order-lg-1">
                <div class="content-wrapper delivery-wrapper">
                    <div class="checkout-stepper mb-4" data-current-step="1">
                        <div class="step active" data-step="1" onclick="switchToInformationTab()">
                            <div class="flex-fill text-center">
                                <H6 class="fw-bold" style="cursor: pointer;">THÔNG TIN</H6>
                            </div>
                        </div>
                        <div class="step step-payment disabled-step" data-step="2"
                            onclick="if(!this.classList.contains('disabled-step')){switchToPaymentTab();}">
                            <div class="flex-fill text-center">
                                <H6 class="fw-bold payment-step-label" style="cursor: pointer;">THANH TOÁN</H6>
                            </div>
                        </div>
                    </div>
                    <!-- Form 1: Thông tin khách hàng -->
                    <form method="POST" action="{{ route('checkout.store', ['orderId' => $order->id ?? null]) }}"
                        id="information-form-content" style="display: block;">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Tên</label>
                                <input type="text" id="name" name="name" class="form-control form-control-custom"
                                    value="{{ old('name', $reorderInfo['name'] ?? ($order->name ?? (auth()->user()->name ?? ''))) }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control form-control-custom"
                                    value="{{ old('email', $reorderInfo['email'] ?? ($order->email ?? (auth()->user()->email ?? ''))) }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" id="phone" name="phone" class="form-control form-control-custom"
                                value="{{ old('phone', $reorderInfo['phone'] ?? ($order->phone ?? (auth()->user()->phone ?? ''))) }}">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tỉnh / Thành phố</label>
                                <input type="text" name="province" class="form-control form-control-custom"
                                    value="{{ old('province', $reorderInfo['province'] ?? ($order->province ?? '')) }}">
                                @error('province')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quận / Huyện</label>
                                <input type="text" name="district" class="form-control form-control-custom"
                                    value="{{ old('district', $reorderInfo['district'] ?? ($order->district ?? '')) }}">
                                @error('district')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phường / Xã</label>
                                <input type="text" name="ward" class="form-control form-control-custom"
                                    value="{{ old('ward', $reorderInfo['ward'] ?? ($order->ward ?? '')) }}">
                                @error('ward')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số nhà, tên đường</label>
                                <input type="text" name="address" class="form-control form-control-custom"
                                    value="{{ old('address', $reorderInfo['address'] ?? ($order->address ?? '')) }}">
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <input type="text" name="note" class="form-control form-control-custom"
                                style="height: 100px;"
                                value="{{ old('note', $reorderInfo['note'] ?? ($order->note ?? '')) }}">
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="button" class="btn payment-btn w-100 mt-3"
                            onclick="handleContinueToPayment()">TIẾP TỤC THANH TOÁN</button>


                    </form>

                    <!-- Form 2: Thanh toán -->
                    <form method="POST" action="{{ route('checkout.paymentStore') }}" id="payment-form-content"
                        style="display: none;">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">

                        <!-- Nhập mã giảm giá thủ công -->
                        <div id="manual-coupon-input">
                            <div class="rounded-3 px-3 py-2 d-flex justify-content-between fw-medium">
                                <input id="coupon_input" type="text" class="form-control border-0" name="coupon_input"
                                    placeholder="Nhập mã giảm giá (chỉ áp dụng 1 lần)">
                                <button type="button" class="btn btn-outline-success btn-sm" id="manualApplyBtn"
                                    onclick="applyManualCoupon()" disabled>Áp dụng</button>
                            </div>
                            <div id="coupon-error" class="text-danger small mt-2" style="display:none;"></div>
                        </div>

                        <!-- Vùng hiển thị mã đã chọn -->
                        <p id="coupon-description" class="mt-2" style="display: none;">
                            Mã đang áp dụng: <strong id="selected-coupon-text">Không</strong>
                            – <a href="#" onclick="resetCoupon()">Chọn lại mã</a>
                        </p>

                        <!-- Nút mở modal chọn mã -->
                        <div class="border border-success rounded-3 px-3 py-2 d-flex justify-content-between align-items-center text-success fw-medium mb-3"
                            style="cursor: pointer; background-color: #f8fbff;" data-bs-toggle="modal"
                            data-bs-target="#couponModal">
                            <span>hoặc chọn từ 1 mã giảm giá có sẵn</span>
                            <i class="fas fa-chevron-right"></i>
                        </div>

                        <!-- Hidden input lưu coupon_id -->
                        <input type="hidden" name="totalAmount" value="{{ $totalAmount }}">
                        <input type="hidden" name="coupon_id" id="selected-coupon-id">
                        <input type="hidden" name="payment_method" id="payment_method">

                        <!-- Modal chọn phương thức thanh toán -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">THÔNG TIN THANH TOÁN</h6>
                            <div class="bg-light rounded d-flex justify-content-between align-items-center p-3"
                                style="cursor:pointer;" onclick="showPaymentModal()">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded p-2 me-3">💳</div>
                                    <div>
                                        <div class="fw-bold" id="selectedPaymentText">Chọn phương thức thanh toán
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- THÔNG TIN NHẬN HÀNG -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">THÔNG TIN NHẬN HÀNG</h6>
                            <div class="bg-light rounded p-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Khách hàng</span>
                                    <span
                                        class="fw-medium">{{ isset($user) ? $user->name : auth()->user()->name ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Số điện thoại</span>
                                    <span class="fw-medium">{{ isset($order) ? $order->phone : 'N/A' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Email</span>
                                    <span
                                        class="fw-medium">{{ isset($user) ? $user->email : auth()->user()->email ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Nhận hàng tại</span>
                                    <span class="fw-medium">{{ isset($order) ? $order->shipping_address : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger w-100 py-3" onclick="submitPaymentForm()">
                            Đặt hàng
                        </button>


                        <!-- Payment Modal -->
                        <!-- Payment Modal -->
                        <div id="paymentModal" class="position-absolute d-none"
                            style="top: 50%; left: 50%; transform: translate(-50%, -50%);
                                z-index: 1055;
                                width: 100%; height: 100%; border-radius: 8px;">

                            <div class="d-flex justify-content-center align-items-center w-100 h-100">
                                <div class="bg-white rounded p-5 shadow"
                                    style="width: 800px; max-width: 95%; min-height: 400px;">

                                    <h5 class="mb-3 text-center" id="paymentModalTitle">Chọn phương thức thanh toán</h5>

                                    <div class="list-group" id="paymentOptions">
                                        <button type="button" class="list-group-item list-group-item-action"
                                            data-method="cod">
                                            Thanh toán khi nhận hàng (COD)
                                        </button>


                                        <button type="button" class="list-group-item list-group-item-action"
                                            data-method="vnpay">
                                            VNPay
                                        </button>
                                    </div>

                                    <button type="button" class="btn btn-danger mt-3 w-100"
                                        onclick="closePaymentModal()">Huỷ</button>
                                </div>
                            </div>
                        </div>



                        <!-- Coupon Selection Modal -->
                        <div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="couponModalLabel">Chọn mã giảm giá</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if (isset($availableCoupons) && count($availableCoupons) > 0)
                                            @php
                                                $orderTotal = $cartTotal ?? ($totalAmount ?? 0);
                                            @endphp
                                            @foreach ($availableCoupons as $coupon)
                                                @php
                                                    $isDisabled = $orderTotal < $coupon->min_order_amount;
                                                @endphp
                                                @php
                                                    $isDisabled =
                                                        $orderTotal < $coupon->min_order_amount ||
                                                        ($coupon->per_user_limit !== null &&
                                                            $coupon->used_count >= $coupon->per_user_limit);
                                                @endphp

                                                <div
                                                    class="border rounded p-3 mb-3 d-flex align-items-start{{ $isDisabled ? ' bg-light text-muted coupon-disabled' : '' }}">
                                                    <div class="flex-grow-1 text-start">
                                                        <div class="mb-1">
                                                            <strong
                                                                class="text-dark fw-bold d-block">{{ $coupon->code }}</strong>
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
                                                            HSD:
                                                            {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}
                                                        </div>
                                                        @if ($isDisabled)
                                                            <div class="text-danger small mt-1">
                                                                @if ($orderTotal < $coupon->min_order_amount)
                                                                    Không đủ điều kiện áp dụng
                                                                @elseif ($coupon->per_user_limit !== null && $coupon->used_count >= $coupon->per_user_limit)
                                                                    Mã đã đạt giới hạn sử dụng cho bạn
                                                                @else
                                                                    Không thể áp dụng mã giảm giá này
                                                                @endif
                                                            </div>
                                                        @endif

                                                    </div>
                                                    <div class="ms-3">
                                                        <input class="form-check-input mt-1" type="radio"
                                                            name="selected_coupon" value="{{ $coupon->id }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center text-muted py-4">
                                                <p>Không có mã giảm giá nào khả dụng</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="button" class="btn btn-primary" onclick="applySelectedCoupon()"
                                            id="couponConfirmBtn">Xác nhận</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
            <!-- Left Column -->
            <div class="col-lg-5 order-lg-2 mb-4 mb-lg-0">
                <div class="content-wrapper summary-wrapper">
                    <h3 class="fw-bold mb-4 fs-5">ĐƠN HÀNG </h3>

                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted">Tổng phụ</span>
                        <span class="fw-bold" id="order-subtotal">
                            {{ number_format($cartTotal ?? $totalAmount, 0, ',', '.') }}đ
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Phí vận chuyển</span>
                        <span class="fw-bold">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center" id="order-discount-row"
                        style="display: none;">
                        <span class="text-muted" id="order-discount-label"></span>
                        <span class="fw-bold text-danger" id="order-discount-amount"></span>
                    </div>
                    <div class="my-2">
                        <p class="text-success mb-1">Bạn có thể nhận hàng miễn phí!</p>
                        <div class="shipping-progress-bar"></div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center py-2 mb-4">
                        <span class="fw-bold fs-5">Tổng</span>
                        <span class="fw-bold fs-4">{{ number_format($totalAmount, 0, ',', '.') }}đ</span>
                    </div>
                    <p class="fw-bold mb-2" >Dự kiến giao: {{ \Carbon\Carbon::now()->addDays(3)->locale('vi')->isoFormat('dddd, DD/MM') }}</p>
                    @foreach ($cartItems as $item)
                        @php
                            $product = $item->variant->product;
                            $imgPath = $product->image ?? 'default-image.jpg';
                        @endphp
                        <div class="mb-4">
                            <div class="d-flex">
                                <!-- Ảnh sản phẩm -->
                                <div class="flex-shrink-0 me-4 h-100" style="width: 140px; height:140px; margin-right: 10px;">
                                    <img src="{{ asset('storage/' . $imgPath) }}" alt="{{ $product->name }}"
                                        class="img-fluid" style="border-radius: 12px;">
                                </div>
                                <!-- Thông tin sản phẩm -->
                                <div class="flex-grow-1 h-100">
                                    <div class="d-flex flex-column align-items-start h-100">
                                        <p class="fw-bold mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px;">{{ $product->name }}</p>
                                        <!-- Nội dung ẩn -->
                                        <div class="more-info" style="display: none;">
                                            <p class="text-muted mb-1">Qty {{ $item->quantity }}</p>
                                            @foreach ($item->variant->options as $opt)
                                                <p class="text-muted mb-1">{{ $opt->attribute->name }}: {{ $opt->option->value }}</p>
                                            @endforeach
                                            <p class="fw-bold mb-0">{{ number_format($item->variant->price, 0, ',', '.') }}đ</p>
                                        </div>
                                        <!-- Nút Xem thêm -->
                                        <button type="button" class="btn btn-link p-0 toggle-info" style="font-size: 14px;">
                                            Xem thêm...
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll(".toggle-info").forEach(function (btn) {
                                btn.addEventListener("click", function () {
                                    let info = btn.previousElementSibling; // .more-info
                                    if (info.style.display === "none") {
                                        info.style.display = "block";
                                        btn.textContent = "Ẩn bớt";
                                    } else {
                                        info.style.display = "none";
                                        btn.textContent = "Xem thêm";
                                    }
                                });
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
        <!-- Các modal -->
    </div>
    <style>
        /* Coupon disabled style */
        .coupon-disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        /* --- Checkout Stepper (Curved Chrome Tab Style v2) --- */
        .checkout-stepper {
            display: flex;
            position: relative;
            margin-bottom: -1px;
            padding: 0 5px;
        }

        .checkout-stepper .step {
            position: relative;
            flex: 1;
            padding: 0.8rem 1.5rem;
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            z-index: 1;
        }

        .checkout-stepper .step.active {
            background: #fff;
            color: #000;
            z-index: 3;
            border-bottom: 3px solid #dc3545;
            /* Gạch chân đỏ khi active */
        }

        .checkout-stepper .step::before,
        .checkout-stepper .step::after {
            content: '';
            position: absolute;
            bottom: 0;
            width: 25px;
            height: 25px;
            background: transparent;
        }

        .checkout-stepper .step::before {
            left: -25px;
            background-image: radial-gradient(circle at 100% 0, transparent 25px, #fff 25.5px);
        }

        .checkout-stepper .step::after {
            right: -25px;
            background-image: radial-gradient(circle at 100% 0, transparent 25px, #e9ecef 25.5px);
        }

        .checkout-stepper .step.active::before {
            background-image: radial-gradient(circle at 0 0, transparent 25px, #fff 25.5px);
        }

        .checkout-stepper .step.active::after {
            right: -25px;
            width: 25px;
            height: 25px;
            background-image: radial-gradient(circle at 100% 0, transparent 25px, #fff 25.5px);
        }

        .checkout-stepper .step:first-child::before,
        .checkout-stepper .step:last-child::after {
            display: none;
        }

        .form-control.is-invalid,
        .form-control-custom.is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .form-control.is-valid,
        .form-control-custom.is-valid {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
        }

        .form-control-custom.is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .payment-btn,
        .btn-success {
            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        }

        /* Fade and disable payment step label if not valid */
        .checkout-stepper .step.disabled-step {
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
        }

        .checkout-stepper .step.disabled-step .payment-step-label {
            opacity: 0.5;
            cursor: not-allowed !important;
            pointer-events: none;
        }
    </style>


    <script>
        let paymentMethod = null;

        // Global functions that need to be accessible from onclick handlers

        // Validate step 1 (Information)
        function validateStep1() {
            const requiredFields = [
                'name', 'email', 'phone', 'province',
                'district', 'ward', 'address'
            ];

            let isValid = true;
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field && !field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });

            return isValid;
        }

        // Show validation error
        function showValidationError() {
            // Remove existing toast if any
            const existingToast = document.querySelector('.validation-toast');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className = 'alert alert-danger position-fixed validation-toast';
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                <strong>Vui lòng điền đầy đủ các trường bắt buộc!</strong>
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }

        // Switch to specific step with animation
        function switchToStep(stepNumber) {
            const stepper = document.querySelector('.checkout-stepper');
            const steps = document.querySelectorAll('.checkout-stepper .step');

            // Update data attribute for progress bar
            stepper.setAttribute('data-current-step', stepNumber);

            steps.forEach((step, index) => {
                const stepNum = index + 1;

                // Remove all classes first
                step.classList.remove('active', 'completed');

                if (stepNum < stepNumber) {
                    // Previous steps are completed
                    step.classList.add('completed');
                } else if (stepNum === stepNumber) {
                    // Current step is active
                    step.classList.add('active');

                    // Trigger animation
                    step.style.animation = 'none';
                    step.offsetHeight; // Trigger reflow
                    step.style.animation = null;
                }
            });

            // Add ripple effect
            const activeStep = steps[stepNumber - 1];
            createRipple(activeStep);

            // Show/hide form content
            if (stepNumber === 1) {
                document.getElementById('information-form-content').style.display = 'block';
                document.getElementById('payment-form-content').style.display = 'none';
            } else if (stepNumber === 2) {
                document.getElementById('information-form-content').style.display = 'none';
                document.getElementById('payment-form-content').style.display = 'block';
            }
        }

        // Create ripple effect
        function createRipple(element) {
            const ripple = document.createElement('span');
            const rect = element.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = rect.width / 2;
            const y = rect.height / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (x - size / 2) + 'px';
            ripple.style.top = (y - size / 2) + 'px';
            ripple.classList.add('ripple');

            element.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        }

        // Handle Continue to Payment button click
        function handleContinueToPayment() {
            // Validate dữ liệu bước 1 nếu cần
            if (!validateStep1()) {
                showValidationError();
                return false;
            }

            const form = document.getElementById('information-form-content');
            if (!form) {
                console.error('Không tìm thấy form!');
                return false;
            }

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Lưu đơn hàng thành công:', data);

                    // Chuyển sang bước thanh toán
                    switchToStep(2);
                    updateDeliveryInfo(); // Nếu có hàm cập nhật hiển thị
                    enablePaymentButtonIfValid();
                })
                .catch(error => {
                    console.error('Lỗi khi gửi đơn hàng:', error);
                    alert('Vui lòng kiểm tra lại thông tin. Một số trường có thể đang sai hoặc thiếu.');
                });
        }

        // Enable payment button only if all required info is filled
        function enablePaymentButtonIfValid() {
            const requiredFields = [
                'name', 'email', 'phone', 'province',
                'district', 'ward', 'address'
            ];
            let isValid = true;
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (!field || !field.value.trim()) {
                    isValid = false;
                }
            });
            const btn = document.getElementById('submitPaymentBtn');
            if (btn) btn.disabled = !isValid;
        }
        // Listen for changes in required fields to enable/disable payment button
        document.addEventListener('DOMContentLoaded', function() {
            const requiredFields = [
                'name', 'email', 'phone', 'province',
                'district', 'ward', 'address'
            ];
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('input', enablePaymentButtonIfValid);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const stepper = document.querySelector('.checkout-stepper');
            const steps = document.querySelectorAll('.checkout-stepper .step');
            const form = document.querySelector('.checkout-form-custom');

            // Initialize stepper
            function initStepper() {
                steps.forEach((step, index) => {
                    step.addEventListener('click', function() {
                        // Only allow clicking back to step 1, or step 2 if already validated
                        const currentStep = parseInt(stepper.getAttribute('data-current-step'));
                        if (index === 0) {
                            // Always allow going back to step 1
                            switchToStep(1);
                        } else if (index === 1 && currentStep >= 2) {
                            // Only allow step 2 if we're already on step 2 or higher
                            switchToStep(2);
                        }
                        // Don't handle step progression here - let form submit handle it
                    });
                });
            }



            // Handle form submission - only for step 2 (payment)
            if (form) {
                form.addEventListener('submit', function(e) {
                    const currentStep = parseInt(stepper.getAttribute('data-current-step'));

                    if (currentStep === 1) {
                        // Should not happen since button is type="button" now
                        e.preventDefault();
                        return false;
                    }

                    // Step 2 - allow normal form submission
                    return true;
                });
            }

            // Add input validation styling
            const inputs = document.querySelectorAll('input[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            });

            // Initialize the stepper
            initStepper();
        });

        // Tab switching functions
        function switchToInformationTab() {
            // Update tab appearance
            document.querySelector('.step[data-step="1"]').classList.add('active');
            document.querySelector('.step[data-step="2"]').classList.remove('active');

            // Show Information form, hide Payment form
            document.getElementById('information-form-content').style.display = 'block';
            document.getElementById('payment-form-content').style.display = 'none';

            // Update stepper data attribute
            document.querySelector('.checkout-stepper').setAttribute('data-current-step', '1');
        }

        function switchToPaymentTab() {
            // Update tab appearance
            document.querySelector('.step[data-step="1"]').classList.remove('active');
            document.querySelector('.step[data-step="2"]').classList.add('active');

            // Hide Information form, show Payment form
            document.getElementById('information-form-content').style.display = 'none';
            document.getElementById('payment-form-content').style.display = 'block';

            // Update stepper data attribute
            document.querySelector('.checkout-stepper').setAttribute('data-current-step', '2');

            // Update delivery info when switching to payment tab
            if (typeof updateDeliveryInfo === 'function') {
                updateDeliveryInfo();
            }
            // Also enable payment button if valid
            if (typeof enablePaymentButtonIfValid === 'function') {
                enablePaymentButtonIfValid();
            }
            // Reset trạng thái nút Đặt hàng
            resetPlaceOrderButton();
        } 

        function resetPlaceOrderButton() {
            const btn = document.querySelector('[onclick="submitPaymentForm()"]');
            if (btn) {
                btn.disabled = false;
                btn.style.cursor = '';
                btn.classList.remove('disabled-place-order');
            }
        }
        // Đảm bảo nút Đặt hàng luôn bật khi quay lại trang (kể cả từ cache/bfcache)
        window.addEventListener('pageshow', function (event) {
    // Nếu quay lại từ cache hoặc từ VNPay thì reset bước về thông tin giao hàng
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
        switchToInformationTab(); // Đưa về bước 1
    }

    // Luôn reset nút đặt hàng
    resetPlaceOrderButton();
});


        // Check if we should switch to payment tab on page load (after successful form submission)
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('switch_to_payment'))
                // Switch to payment tab if form was submitted successfully
                switchToPaymentTab();

                // Update delivery information in payment form
                updateDeliveryInfo();

                // Show success message
                @if (session('success'))
                    // You can show a toast or other notification here instead of alert
                    // alert('{{ session('success') }}');
                @endif
            @endif
        });

        // Update delivery information in payment form
        function updateDeliveryInfo() {
            // Lấy giá trị từ input form
            const name = document.querySelector('[name="name"]')?.value || 'N/A';
            const phone = document.querySelector('[name="phone"]')?.value || 'N/A';
            const email = document.querySelector('[name="email"]')?.value || 'N/A';
            const address = document.querySelector('[name="address"]')?.value || '';
            const ward = document.querySelector('[name="ward"]')?.value || '';
            const district = document.querySelector('[name="district"]')?.value || '';
            const province = document.querySelector('[name="province"]')?.value || '';
            const fullAddress = [address, ward, district, province].filter(Boolean).join(', ');

            // Lấy đúng khối THÔNG TIN NHẬN HÀNG (thường là khối .bg-light.rounded.p-3 thứ hai trong #payment-form-content)
            const infoBoxes = document.querySelectorAll('#payment-form-content .bg-light.rounded.p-3');
            // Thông tin nhận hàng là box thứ hai (index 1)
            const infoBox = infoBoxes[1];
            if (infoBox) {
                const spans = infoBox.querySelectorAll('.fw-medium');
                if (spans.length >= 4) {
                    spans[0].textContent = name;
                    spans[1].textContent = phone;
                    spans[2].textContent = email;
                    spans[3].textContent = fullAddress || 'N/A';
                }
            }
        }

        // Payment form functions (move to global scope)
        function showPaymentModal() {
            document.getElementById('paymentModal').classList.remove('d-none');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('d-none');
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.innerText = message;
            toast.style.position = 'fixed';
            toast.style.top = '10%';
            toast.style.right = '0';
            toast.style.left = 'auto';
            toast.style.transform = 'none';
            toast.style.background = 'rgba(220,53,69,0.97)';
            toast.style.color = '#fff';
            toast.style.padding = '12px 16px';
            toast.style.borderRadius = '12px';
            toast.style.fontSize = '0.9rem';
            toast.style.zIndex = '99999';
            toast.style.boxShadow = '0 4px 32px rgba(0,0,0,0.18)';
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function submitPaymentForm() {
    // Kiểm tra đã chọn phương thức thanh toán chưa
    const selectedMethod = document.getElementById('payment_method')?.value;
    if (!selectedMethod) {
        showToast("Vui lòng chọn phương thức thanh toán");
        return;
    }

    // Disable nút và đổi con trỏ
    const btn = document.querySelector('[onclick="submitPaymentForm()"]');
    if (btn) {
        btn.disabled = true;
        btn.style.cursor = 'not-allowed';
        btn.classList.add('disabled-place-order');
    }

    // Gán lại payment_method (đề phòng)
    const form = document.getElementById('payment-form-content');
    let hiddenInput = form.querySelector('input[name="payment_method"]');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'payment_method';
        form.appendChild(hiddenInput);
    }
    hiddenInput.value = selectedMethod;

    // Submit form như bình thường
    if (form && typeof form.submit === 'function') {
        form.submit();
    }
}

        // Coupon functions (move to global scope)
        async function applyManualCoupon() {
            const code = document.getElementById('coupon_input').value.trim();
            const hiddenCouponId = document.getElementById('selected-coupon-id');
            const manualInputBox = document.getElementById('manual-coupon-input');
            const couponDesc = document.getElementById('coupon-description');

            if (!code) {
                showCenterError('Vui lòng nhập mã giảm giá trước khi áp dụng.');
                return;
            }

            try {
                const response = await fetch('{{ route('checkout.applyCoupon') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        code
                    })
                });

                const result = await response.json();

                if (!response.ok) {
                    showCenterError(result.error || 'Không tìm thấy mã giảm giá!');
                    return;
                }

                // Update UI
                updateCouponUI(result);
                updateOrderSummary(result);

                if (manualInputBox) manualInputBox.style.display = 'none';
                if (couponDesc) couponDesc.style.display = 'block';

            } catch (error) {
                console.error(error);
                showCenterError('Không thể kết nối đến server. Vui lòng thử lại sau!');
            }
        }
        // Hiển thị lỗi giữa màn hình (đặt ngoài cùng để các hàm khác đều gọi được)
        function showCenterError(message) {
            // Xóa toast cũ nếu có
            const old = document.getElementById('center-toast-error');
            if (old) old.remove();
            const toast = document.createElement('div');
            toast.id = 'center-toast-error';
            toast.style.position = 'fixed';
            toast.style.top = '10%';
            toast.style.right = '0';
            toast.style.left = 'auto';
            toast.style.transform = 'none';
            toast.style.background = 'rgba(220,53,69,0.97)';
            toast.style.color = '#fff';
            toast.style.padding = '12px 16px';
            toast.style.borderRadius = '12px';
            toast.style.fontSize = '0.9rem';
            toast.style.zIndex = '99999';
            toast.style.boxShadow = '0 4px 32px rgba(0,0,0,0.18)';
            document.body.appendChild(toast);
            setTimeout(() => {
                if (toast.parentElement) toast.remove();
            }, 3500);
        }

        function resetCoupon() {
            document.getElementById('coupon_input').value = '';
            document.getElementById('selected-coupon-id').value = '';
            document.getElementById('selected-coupon-text').innerText = 'Không';
            document.getElementById('coupon-description').style.display = 'none';
            document.getElementById('manual-coupon-input').style.display = 'block';

            // Reset to original values
            const originalTotal = {{ $cartTotal ?? 0 }};
            updateOrderSummary({
                discount_amount: 0,
                total_amount: originalTotal
            });
        }

        // Apply selected coupon from modal
        async function applySelectedCoupon() {
            const selectedRadio = document.querySelector('input[name="selected_coupon"]:checked');
            if (!selectedRadio) {
                showCenterError("Vui lòng chọn mã giảm giá");
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

                let result;
                try {
                    result = await response.json();
                } catch (e) {
                    showCenterError('Lỗi không xác định từ server.');
                    if (selectedRadio) selectedRadio.checked = false;
                    return;
                }

                // Debug: log kết quả trả về từ backend
                console.log('Coupon API result:', result, 'Response:', response);

                // Nếu response không ok, hoặc có trường error/message, hoặc là string => báo lỗi
                let errorMsg = null;
                if (!response.ok) {
                    if (typeof result === 'string') {
                        errorMsg = result;
                    } else if (result && (result.error || result.message)) {
                        errorMsg = result.error || result.message;
                    } else {
                        errorMsg = 'Không áp dụng được mã này';
                    }
                } else if (result && (result.error || result.message)) {
                    errorMsg = result.error || result.message;
                }

                if (errorMsg) {
                    showCenterError(errorMsg);
                    if (selectedRadio) selectedRadio.checked = false;
                    return;
                }

                // Update UI
                updateCouponUI(result);
                updateOrderSummary(result);

                // Hide manual input and show description
                document.getElementById('manual-coupon-input').style.display = 'none';
                document.getElementById('coupon-description').style.display = 'block';

                // Only close the modal if coupon applied successfully
                const modal = bootstrap.Modal.getInstance(document.getElementById('couponModal'));
                if (modal) {
                    modal.hide();
                } else {
                    document.querySelector('[data-bs-dismiss="modal"]').click();
                }

            } catch (error) {
                console.error('Lỗi server:', error);
                showCenterError('Không thể kết nối đến server. Vui lòng thử lại sau!');
                if (selectedRadio) selectedRadio.checked = false;
            }
        }

        // Update coupon UI in payment form
        function updateCouponUI(result) {
            document.getElementById('selected-coupon-text').innerText = result.code;
            document.getElementById('selected-coupon-id').value = result.coupon_id;

            // Update payment form amounts
            document.querySelectorAll('.discount-amount, #discount-amount')
                .forEach(e => e.innerText = formatCurrency(result.discount_amount));
            document.querySelectorAll('.total-amount, #total-amount')
                .forEach(e => e.innerText = formatCurrency(result.total_amount));
        }

        // Update order summary in right column
        function updateOrderSummary(result) {
            // Update total amount in order summary
            const totalElements = document.querySelectorAll('.col-lg-5 .fw-bold.fs-4');
            if (totalElements.length > 0) {
                totalElements[0].textContent = formatCurrency(result.total_amount);
            }

            // Subtotal: giữ nguyên giá gốc
            const subtotalSpan = document.getElementById('order-subtotal');
            const originalSubtotal = {{ $cartTotal ?? ($totalAmount ?? 0) }};
            if (subtotalSpan) {
                subtotalSpan.textContent = formatCurrency(originalSubtotal);
            }

            // Discount row: show/hide and update value
            const discountRow = document.getElementById('order-discount-row');
            const discountLabel = document.getElementById('order-discount-label');
            const discountAmountSpan = document.getElementById('order-discount-amount');
            if (result.discount_amount && result.discount_amount > 0) {
                if (discountRow) discountRow.style.display = 'flex';
                if (discountLabel) discountLabel.textContent = 'Direct Discount';
                if (discountAmountSpan) discountAmountSpan.textContent = '-' + formatCurrency(result.discount_amount);
            } else {
                if (discountRow) discountRow.style.display = 'none';
                if (discountLabel) discountLabel.textContent = '';
                if (discountAmountSpan) discountAmountSpan.textContent = '';
            }
        }

        // Format currency helper
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                minimumFractionDigits: 0
            }).format(amount).replace('₫', '₫');
        }

        // Enable/disable manual coupon apply button
        document.addEventListener('DOMContentLoaded', function() {
            const couponInput = document.getElementById('coupon_input');
            if (couponInput) {
                couponInput.addEventListener('input', function() {
                    const btn = document.getElementById('manualApplyBtn');
                    if (btn) {
                        btn.disabled = this.value.trim() === '';
                    }
                });
            }

            // Payment method selection handlers
            const iconMap = {
                cod: '💳',
                vnpay: '🌐'
            };

            // Add event listeners to payment options
            const paymentButtons = document.querySelectorAll('#paymentOptions button');
            paymentButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const method = this.dataset.method;
                    const text = this.textContent.trim();
                    const icon = iconMap[method] || '💳';

                    const selectedPaymentText = document.getElementById('selectedPaymentText');
                    const iconElement = document.querySelector('.bg-primary');

                    if (selectedPaymentText) {
                        selectedPaymentText.textContent = text;
                    }
                    if (iconElement) {
                        iconElement.textContent = icon;
                    }

                    // Cập nhật input hidden cho payment_method
                    const paymentInput = document.getElementById('payment_method');
                    if (paymentInput) {
                        paymentInput.value = method;
                    }

                    closePaymentModal();
                });
            });
        });
    </script>


    <script>
        // Helper to enable/disable payment step tab
        function updatePaymentStepState() {
            const requiredFields = [
                'name', 'email', 'phone', 'province',
                'district', 'ward', 'address'
            ];
            let isValid = true;
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (!field || !field.value.trim()) {
                    isValid = false;
                }
            });
            const paymentStep = document.querySelector('.step[data-step="2"]');
            if (paymentStep) {
                if (isValid) {
                    paymentStep.classList.remove('disabled-step');
                } else {
                    paymentStep.classList.add('disabled-step');
                }
            }
        }
        // Listen for changes in required fields to update payment step state
        document.addEventListener('DOMContentLoaded', function() {
            const requiredFields = [
                'name', 'email', 'phone', 'province',
                'district', 'ward', 'address'
            ];
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('input', updatePaymentStepState);
                }
            });
            updatePaymentStepState();
        });
    </script>
    <script>
        // Show and hide payment modal functions (must be global)
        function showPaymentModal() {
            var modal = document.getElementById('paymentModal');
            if (modal) modal.classList.remove('d-none');
        }

        function closePaymentModal() {
            var modal = document.getElementById('paymentModal');
            if (modal) modal.classList.add('d-none');
        }
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Khởi tạo stepper
            initStepper();

            // 2. Bắt sự kiện enable/disable nút thanh toán
            enablePaymentButtonIfValid();

            const requiredFields = ['name', 'email', 'phone', 'province', 'district', 'ward', 'address'];
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('input', () => {
                        enablePaymentButtonIfValid();
                        updatePaymentStepState();
                    });
                }
            });

            // 3. Nếu có session chuyển bước
            @if (session('switch_to_payment'))
                switchToPaymentTab();
                updateDeliveryInfo();
            @endif

            // 4. Sự kiện chọn phương thức thanh toán
            const iconMap = {
                cod: '💳',
                vnpay: '🌐'
            };

            // Add event listeners to payment options
            const paymentButtons = document.querySelectorAll('#paymentOptions button');
            paymentButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const method = this.dataset.method;
                    const text = this.textContent.trim();
                    const icon = iconMap[method] || '💳';

                    const selectedPaymentText = document.getElementById('selectedPaymentText');
                    const iconElement = document.querySelector('.bg-primary');

                    if (selectedPaymentText) selectedPaymentText.textContent = text;
                    if (iconElement) iconElement.textContent = icon;

                    const paymentInput = document.getElementById('payment_method');
                    if (paymentInput) {
                        paymentInput.value = method;
                    }

                    closePaymentModal();
                });
            });

            // 5. Sự kiện nhập mã coupon
            const couponInput = document.getElementById('coupon_input');
            if (couponInput) {
                couponInput.addEventListener('input', function() {
                    const btn = document.getElementById('manualApplyBtn');
                    if (btn) {
                        btn.disabled = this.value.trim() === '';
                    }
                });
            }
        });
    </script>



@endsection
