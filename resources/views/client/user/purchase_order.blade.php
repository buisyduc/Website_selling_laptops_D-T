@extends('client.user.layoutManagement.layout')
@section('box')
    <div class="container  animate__animated animate__fadeIn" style="margin-top: 16px;">
        {{-- Session thông báo --}}
        @if (session('error'))
            <script>
                window.addEventListener('DOMContentLoaded', function () {
                    showToast(@json(session('error')), 'error');
                });
            </script>
        @endif

        @if (session('success'))
            <script>
                window.addEventListener('DOMContentLoaded', function () {
                    showToast(@json(session('success')), 'success');
                });
            </script>
        @endif




        {{-- Tabs trạng thái --}}
        <div class="order-tabs-container mb-4">
            <ul class="order-tabs nav nav-tabs justify-content-center border-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->get('status') == null ? 'active' : '' }}"
                        href="{{ route('client.orders.index') }}">Tất cả</a>
                </li>
                @foreach (['pending' => 'Chờ thanh toán', 'processing_seller' => 'Chờ lấy hàng', 'processing' => 'Vận chuyển','shipping'=>'Chờ giao hàng', 'completed' => 'Hoàn thành', 'canceled' => 'Đã huỷ', 'returned' => 'Trả hàng/Hoàn tiền'] as $key => $label)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->get('status') == $key ? 'active' : '' }}"
                            href="{{ route('client.orders.index', ['status' => $key]) }}">{{ $label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>


        @if (isset($orders) && $orders->isEmpty())
            <div class="empty-orders-container">
                <div class="empty-orders-icon">
                    <svg width="140" height="140" viewBox="0 0 140 140" fill="none">
                        <!-- Background circle -->
                        <circle cx="70" cy="70" r="65" fill="#f8f9fa" stroke="#e9ecef" stroke-width="2" />

                        <!-- Clipboard -->
                        <rect x="45" y="35" width="50" height="70" rx="6" fill="#ffffff" stroke="#dee2e6" stroke-width="2" />

                        <!-- Clipboard clip -->
                        <rect x="60" y="30" width="20" height="10" rx="5" fill="#28a745" />

                        <!-- Document lines -->
                        <line x1="55" y1="50" x2="85" y2="50" stroke="#ced4da" stroke-width="2" stroke-linecap="round" />
                        <line x1="55" y1="60" x2="80" y2="60" stroke="#ced4da" stroke-width="2" stroke-linecap="round" />
                        <line x1="55" y1="70" x2="75" y2="70" stroke="#ced4da" stroke-width="2" stroke-linecap="round" />
                        <line x1="55" y1="80" x2="70" y2="80" stroke="#ced4da" stroke-width="2" stroke-linecap="round" />

                        <!-- Pencil -->
                        <g transform="translate(95, 85) rotate(45)">
                            <rect x="0" y="0" width="4" height="18" rx="2" fill="#ffc107" />
                            <rect x="0" y="-3" width="4" height="6" rx="2" fill="#fd7e14" />
                            <polygon points="0,18 4,18 2,22" fill="#6c757d" />
                        </g>

                        <!-- Decorative elements -->
                        <circle cx="25" cy="45" r="3" fill="#fd7e14" opacity="0.7" />
                        <circle cx="115" cy="55" r="2.5" fill="#28a745" opacity="0.7" />
                        <circle cx="20" cy="85" r="2" fill="#007bff" opacity="0.7" />
                        <circle cx="120" cy="95" r="3.5" fill="#fd7e14" opacity="0.7" />
                        <circle cx="30" cy="110" r="2" fill="#6f42c1" opacity="0.7" />
                        <circle cx="110" cy="25" r="2.5" fill="#dc3545" opacity="0.7" />
                    </svg>
                </div>
                <div class="empty-orders-text">
                    <h5>Chưa có đơn hàng</h5>
                </div>
            </div>
        @elseif(isset($orders) && $orders->count() > 0)
            @foreach ($orders as $order)
                <div class="border rounded shadow-sm mb-4 bg-white">



                    {{-- Sản phẩm --}}
                    <div class="px-3 pt-3">
                        <div class="jgIyoX" style="right: 0;">
                            <div class="bv3eJE" tabindex="0">
                                @if ($order->status === 'canceled')
                                    <span class="text-danger">Đã huỷ</span>
                                @elseif ($order->status === 'pending')
                                    <span class="text-warning">Chờ thanh toán</span>
                                @elseif ($order->status === 'processing_seller')
                                    <span class="text-success">Đã thanh toán</span>
                                @elseif ($order->status === 'processing')
                                    <span class="text-primary">Đơn hàng đang được chuẩn bị</span>
                                @elseif ($order->status === 'shipping')
                                    <span class="text-info">Đang vận chuyển</span>
                                @elseif ($order->status === 'completed')
                                    <span class="text-success">Hoàn thành</span>
                                @elseif ($order->status === 'returned')
                                    <span class="text-secondary">Đã hoàn tiền</span>
                                @else
                                    <span class="text-muted">Không xác định</span>
                                @endif
                            </div>
                        </div>

                        @foreach ($order->items as $item)
                            <a style="color: black" href="{{ route('client.orders.show', $order->id) }}">

                                <div class="d-flex mb-3 align-items-center">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" width="80" height="60"
                                        class="rounded border">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold" style="font-size: 20px">{{ $item->product_name }}</div>
                                        @if ($item->variant && $item->variant->options->count())
                                                    <div style="font-size: 11x; color: #666; margin-top: 1px;">
                                                        {{ $item->variant->options->map(function ($opt) {
                                                return $opt->attribute->name . ': ' . $opt->option->value;
                                            })->implode(' • ') }}
                                                    </div>
                                        @endif

                                    </div>
                                    <div class="text-end text-danger fw-bold">
                                        {{ number_format($item->price * $item->quantity) }}₫
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Tổng tiền + Hành động --}}
                    <div class="d-flex justify-content-between align-items-center border-top p-3">

                        <div>
                            <span class="fw-bold">Tổng tiền:</span>
                            <span class="text-danger fw-bold">{{ number_format($order->total_amount) }}₫</span>
                        </div>
                        <div class="text-end">


                            @if ($order->status === 'pending')
                                <form class="cancel-order-form d-inline-block ms-2" data-order-id="{{ $order->id }}" method="POST"
                                    action="{{ route('orders.cancel', $order->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-secondary btn-sm" style="border-radius: 12px">
                                        Hủy đơn hàng
                                    </button>
                                </form>
                            @endif
                                 @if ($order->status === 'completed')
                                <form
                                    action="#">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-secondary btn-sm" style="border-radius: 12px">
                                        Trả hàng/Hoàn tiền
                                    </button>
                                </form>
                            @endif
                            @if ($order->status === 'processing_seller')
                                <form
                                    action="#">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-secondary btn-sm" style="border-radius: 12px">
                                        Hủy đơn/Hoàn tiền
                                    </button>
                                </form>
                            @endif

                            @if ($order->status === 'canceled')
                                <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="d-inline-block ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-red btn-sm"
                                        style="border-radius: 12px; color: #ffffff;background-color: #ee4d2d"> Mua
                                        lại</button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        @else
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle"></i>
                Không thể tải danh sách đơn hàng. Vui lòng thử lại sau.
            </div>
        @endif

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.cancel-order-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
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


    <script>

        function showToast(message, type = 'error') {
            const toast = document.createElement('div');
            toast.innerText = message;
            toast.style.position = 'fixed';
            toast.style.top = '10%';
            toast.style.right = 0;
            toast.style.padding = '12px 16px';
            toast.style.borderRadius = '8px';
            toast.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
            toast.style.zIndex = '9999';
            toast.style.maxWidth = '300px';
            toast.style.wordWrap = 'break-word';
            toast.style.whiteSpace = 'normal';
            toast.style.lineHeight = '1.4';

            // Màu theo loại thông báo
            if (type === 'success') {
                toast.style.backgroundColor = '#4CAF50'; // xanh lá
            } else {
                toast.style.backgroundColor = '#f44336'; // đỏ
            }

            toast.style.color = 'white';
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>

    <style>
        .bv3eJE {
            color: #ee4d2d;
            line-height: 24px;
            text-align: right;
            text-transform: uppercase;
            white-space: nowrap;
            position: 5px;
        }

        .empty-orders-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 80px 20px;
            background: #ffffff;
            margin: 40px 0;
            min-height: 400px;
        }

        .empty-orders-icon {
            margin-bottom: 24px;
            animation: float 3s ease-in-out infinite;
        }

        .empty-orders-text {
            text-align: center;
        }

        .empty-orders-text h5 {
            color: #495057;
            font-weight: 500;
            margin-bottom: 0;
            font-size: 20px;
            letter-spacing: -0.5px;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 768px) {
            .empty-orders-container {
                padding: 60px 15px;
                min-height: 300px;
            }

            .empty-orders-icon svg {
                width: 120px;
                height: 120px;
            }

            .empty-orders-text h5 {
                font-size: 18px;
            }
        }
    </style>
    <style>
        .order-tabs-container {
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            padding: 0;
        }

        .order-tabs {
            border: none !important;
            background: transparent;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .order-tabs .nav-item {
            margin: 0;
            flex: 1;
            display: flex;
        }



        .order-tabs .nav-link {
            border: none !important;
            background: transparent !important;
            color: #666 !important;
            font-size: 14px;
            font-weight: 500;
            margin: 0;
            border-radius: 0 !important;
            position: relative;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            text-align: center;
        }

        .order-tabs .nav-link:hover {
            color: #ee4d2d !important;
            background: transparent !important;
        }

        .order-tabs .nav-link.active {
            color: #ee4d2d !important;
            background: transparent !important;
            font-weight: 500;
            border: none !important;
        }

        .order-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 2px;
            background: #ee4d2d;
            border-radius: 1px;
        }

        /* Responsive cho mobile */
        @media (max-width: 768px) {
            .order-tabs {
                overflow-x: auto;
                justify-content: flex-start !important;
                padding: 0 10px;
            }

            .order-tabs .nav-item {
                flex: none;
                min-width: auto;
            }

            .order-tabs .nav-link {
                padding: 12px 8px;
                font-size: 14px;
                white-space: nowrap;
            }


        }

        /* Scrollbar cho mobile */
        .order-tabs::-webkit-scrollbar {
            display: none;
        }

        .order-tabs {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection
