@extends('admin.index')

@section('container-fluid')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="fas fa-file-invoice me-2"></i>
                                Chi tiết đơn hàng #{{ $order->order_code }}
                            </h3>
                            @php
                                $statusMap = [
                                    'pending' => ['bg-warning', 'Chờ xác nhận'],
                                    'processing_seller' => ['bg-primary', 'Đã xác nhận'],
                                    'processing' => ['bg-info', 'Đang giao hàng'],
                                    'shipping' => ['bg-secondary', 'Đã giao hàng'],
                                    'completed' => ['bg-success', 'Hoàn thành'],
                                    'cancelled' => ['bg-danger', 'Đã hủy'],
                                    'canceled' => ['bg-danger', 'Đã hủy'],
                                    'returned' => ['bg-secondary', 'Trả hàng/Hoàn tiền'],
                                ];

                                [$statusClass, $statusText] = $statusMap[$order->status] ?? [
                                    'bg-secondary',
                                    ucfirst($order->status),
                                ];

                                // Tùy biến nhãn cho trạng thái 'returned' theo phương thức thanh toán và yêu cầu của khách
                                if (($order->status ?? '') === 'returned') {
                                    $method = strtolower((string) ($order->payment_method ?? ''));
                                    if ($method === 'cod') {
                                        // Thanh toán COD: chỉ trả hàng
                                        $statusText = 'Trả hàng';
                                    } elseif (in_array($method, ['vnpay', 'momo', 'bank'], true)) {
                                        // Thanh toán online: phân biệt Hoàn tiền khi hủy đơn vs Trả hàng hoàn tiền
                                        $latestReturn = \App\Models\OrderReturn::where('order_id', $order->id)
                                            ->orderByDesc('created_at')
                                            ->first();

                                        $customerRequest = strtolower((string) ($order->customer_request ?? ''));

                                        if ($latestReturn && ($latestReturn->type === 'return_refund')) {
                                            // Nếu có dấu hiệu là khách yêu cầu hủy đơn + hoàn tiền
                                            if (strpos($customerRequest, 'hủy') !== false || strpos($customerRequest, 'huy') !== false) {
                                                $statusText = 'Hủy đơn hoàn tiền';
                                            } else {
                                                $statusText = 'Trả hàng hoàn tiền';
                                            }
                                        } else {
                                            // Mặc định chỉ trả hàng (không hoàn tiền)
                                            $statusText = 'Trả hàng';
                                        }
                                    }
                                }
                            @endphp

                            <span class="badge badge-lg {{ $statusClass }}">
                                {{ $statusText }}
                            </span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Customer Information -->
        <div class="row mb-4">
            <!-- Customer Information -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2 text-primary"></i>
                            Thông tin khách hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle text-muted me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Họ tên</small>
                                        <strong>{{ $order->name }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope text-muted me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Email</small>
                                        <strong>{{ $order->email }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone-alt text-muted me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Số điện thoại</small>
                                        <strong>{{ $order->phone }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-map-marker-alt text-muted me-3 mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Địa chỉ giao hàng</small>
                                        <strong>{{ $order->shipping_address }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2 text-primary"></i>
                            Tóm tắt đơn hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Mã đơn hàng</small>
                                <p class="fw-bold">#{{ $order->order_code }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Ngày đặt</small>
                                <p class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Số lượng sản phẩm</small>
                                <p class="fw-bold">{{ $order->items->sum('quantity') }} sản phẩm</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Tổng tiền</small>
                                <p class="fw-bold text-success fs-5">
                                    {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                </p>
                            </div>
                            <!-- Phương thức thanh toán -->
                            <div class="col-12 mt-3">
                                <small class="text-muted">Phương thức thanh toán: </small>
                                @php
                                    $paymentMethodMap = [
                                        'cod' => 'Thanh toán khi nhận hàng (COD)',
                                        'vnpay' => 'VNPay',
                                        'momo' => 'Momo',
                                        'bank' => 'Chuyển khoản ngân hàng',
                                    ];
                                    $paymentMethod =
                                        $paymentMethodMap[$order->payment_method] ?? ucfirst($order->payment_method);
                                @endphp
                                <span class="fw-bold">{{ $paymentMethod }}</span>
                            </div>
                            <!-- Trạng thái thanh toán -->
                            <div class="col-12 mt-3">
                                <small class="text-muted">Trạng thái thanh toán: </small>
                                @php
                                    // Ưu tiên dùng payment_status trong DB nếu có
                                    $paymentStatus = $order->payment_status ?? null;

                                    // Fallback kế thừa nếu chưa có payment_status
                                    if (!$paymentStatus) {
                                        if ($order->payment_method === 'cod') {
                                            // COD: nếu đã hoàn thành thì coi là đã thanh toán
                                            $paymentStatus = $order->status === 'completed' ? 'paid' : 'unpaid';
                                        } elseif (in_array($order->payment_method, ['vnpay', 'momo'])) {
                                            // Thanh toán online: nếu đơn đã bị hủy thì hoàn tiền
                                            $paymentStatus = $order->status === 'canceled' ? 'refunded' : 'paid';
                                        } else {
                                            $paymentStatus = 'processing';
                                        }
                                    }

                                    // Nếu đơn ở trạng thái trả hàng và là COD -> hiển thị Đã trả hàng
                                    if (($order->status ?? '') === 'returned' && strtolower((string)($order->payment_method)) === 'cod') {
                                        $paymentStatus = 'returned';
                                    }

                                    $paymentMap = [
                                        'unpaid' => ['bg-warning', 'Chưa thanh toán'],
                                        'waiting_payment' => ['bg-warning', 'Chờ thanh toán'],
                                        'Waiting_for_order_confirmation' => ['bg-info', 'Chờ xác nhận đơn hàng'],
                                        'returned' => ['bg-warning', 'Đã trả hàng'],
                                        'pending' => ['bg-info', 'Đang xử lý'],
                                        'paid' => ['bg-success', 'Đã thanh toán'],
                                        'refunded' => ['bg-secondary', 'Hoàn tiền'],
                                        'refund_pending' => ['bg-info', 'Đang hoàn tiền'],
                                        'refund_canceled' => ['bg-danger', 'Hủy hoàn tiền'],
                                        'returned_refunded' => ['bg-secondary', 'Trả hàng/Hoàn tiền'],
                                        'failed' => ['bg-danger', 'Thanh toán thất bại'],
                                        'processing' => ['bg-info', 'Đang xử lý'],
                                    ];

                                    [$paymentClass, $paymentText] = $paymentMap[$paymentStatus] ?? ['bg-secondary', ucfirst((string)$paymentStatus)];
                                @endphp
                                <span class="badge {{ $paymentClass }} px-3 py-2">{{ $paymentText }}</span>
                            </div>
                            @if ($order->customer_request_status === 'pending')
                                <div class="alert alert-warning">
                                    <strong>Yêu cầu của khách hàng:</strong>
                                    {{ $order->customer_request }} <br>
                                    <strong>Lý do:</strong> {{ $order->customer_request_reason }}
                                    <form action="{{ route('admin.orders.approveRequest', $order->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Duyệt</button>
                                    </form>
                                    <form action="{{ route('admin.orders.rejectRequest', $order->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">Từ chối</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Products List -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-box me-2 text-primary"></i>
                            Danh sách sản phẩm
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Sản phẩm</th>
                                        <th class="border-0 text-center">Đơn giá</th>
                                        <th class="border-0 text-center">Số lượng</th>
                                        <th class="border-0 text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded p-2 me-3">
                                                        <i class="fas fa-cube text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->variant->product->name }}</h6>
                                                        <small class="text-muted">Biến thể:
                                                            {{ $item->variant->name ?? 'Mặc định' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center py-3">
                                                <span
                                                    class="fw-bold">{{ number_format($item->variant->price, 0, ',', '.') }}₫</span>
                                            </td>
                                            <td class="text-center py-3">
                                                <span class="badge bg-secondary">{{ $item->quantity }}</span>

                                            </td>
                                            <td class="text-end py-3">
                                                <span class="fw-bold text-success">
                                                    {{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }}₫
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <!-- Tổng tiền sản phẩm -->
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold py-2">Tổng tiền sản phẩm:</td>
                                        <td class="text-end py-2">
                                            <span class="fw-bold text-success">
                                                {{ number_format($order->items->sum(fn($item) => $item->variant->price * $item->quantity), 0, ',', '.') }}₫
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Mã giảm giá -->
                                    @if ($order->coupon)
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold py-2">Mã giảm giá
                                                ({{ $order->coupon->code }}):</td>
                                            <td class="text-end py-2">
                                                <span class="fw-bold text-danger">
                                                    -{{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}₫
                                                </span>
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Phí vận chuyển -->
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold py-2">Phí vận chuyển:</td>
                                        <td class="text-end py-2">
                                            <span class="fw-bold text-primary">
                                                @if ($order->shipping_fee && $order->shipping_fee > 0)
                                                    {{ number_format($order->shipping_fee, 0, ',', '.') }}₫
                                                @else
                                                    Free ship
                                                @endif
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Tổng thanh toán cuối -->
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold py-3">Tổng thanh toán:</td>
                                        <td class="text-end py-3">
                                            <span class="fw-bold text-success fs-5">
                                                {{ number_format(
                                                    $order->items->sum(fn($item) => $item->variant->price * $item->quantity) -
                                                        ($order->discount_amount ?? 0) +
                                                        ($order->shipping_fee ?? 0),
                                                    0,
                                                    ',',
                                                    '.',
                                                ) }}₫
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>


                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update Form -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2 text-primary"></i>
                            Cập nhật trạng thái
                        </h5>
                    </div>
                    <div class="card-body">
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}"
                            id="statusForm">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold">Trạng thái đơn hàng</label>
                                <select name="status" class="form-select form-select-lg"
                                    @if (in_array($order->status, ['completed', 'canceled'])) disabled @endif>
                                    @php
                                        $statusOptions = [
                                            'pending' => ['class' => 'text-warning', 'label' => 'Chờ xác nhận'],
                                            'processing_seller' => [
                                                'class' => 'text-primary',
                                                'label' => 'Đã xác nhận',
                                            ],
                                            'processing' => ['class' => 'text-info', 'label' => 'Đang giao hàng'],
                                            'shipping' => ['class' => 'text-secondary', 'label' => 'Đã giao hàng'],
                                            'completed' => ['class' => 'text-success', 'label' => 'Hoàn thành'],
                                            'canceled' => ['class' => 'text-danger', 'label' => 'Đã hủy'],
                                            'returned' => [
                                                'class' => 'text-secondary',
                                                'label' => 'Trả hàng/Hoàn tiền',
                                            ],
                                        ];

                                        // Tùy biến nhãn cho option 'returned' theo phương thức thanh toán và yêu cầu
                                        $method = strtolower((string) ($order->payment_method ?? ''));
                                        $dynamicReturnedLabel = 'Trả hàng/Hoàn tiền';
                                        if ($method === 'cod') {
                                            $dynamicReturnedLabel = 'Trả hàng';
                                        } elseif (in_array($method, ['vnpay','momo','bank'], true)) {
                                            $latestReturn = \App\Models\OrderReturn::where('order_id', $order->id)
                                                ->orderByDesc('created_at')
                                                ->first();
                                            $customerRequest = strtolower((string) ($order->customer_request ?? ''));
                                            if ($latestReturn && $latestReturn->type === 'return_refund') {
                                                if (strpos($customerRequest, 'hủy') !== false || strpos($customerRequest, 'huy') !== false) {
                                                    $dynamicReturnedLabel = 'Hủy đơn hoàn tiền';
                                                } else {
                                                    $dynamicReturnedLabel = 'Trả hàng hoàn tiền';
                                                }
                                            } else {
                                                $dynamicReturnedLabel = 'Trả hàng';
                                            }
                                        }
                                        $statusOptions['returned']['label'] = $dynamicReturnedLabel;

                                        $statusOrder = array_keys($statusOptions);
                                        $currentIndex = array_search($order->status, $statusOrder);
                                    @endphp

                                    @foreach ($statusOptions as $status => $config)
                                        @php
                                            $statusIndex = array_search($status, $statusOrder);
                                        @endphp
                                        {{-- Chỉ cho phép chọn trạng thái hiện tại và các trạng thái tiếp theo --}}
                                        @if ($statusIndex >= $currentIndex)
                                            <option value="{{ $status }}" class="{{ $config['class'] }}"
                                                @if ($status === 'completed') disabled @endif
                                                @selected($order->status === $status)>
                                                {{ $config['label'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>


                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                                    </a>

                                    {{-- Chỉ hiển thị nút cập nhật nếu không phải completed/cancelled/returned --}}
                                    @if (!in_array($order->status, ['completed', 'cancelled', 'returned']))
                                        <button type="submit" class="btn btn-success btn-lg" id="updateBtn">
                                            <i class="fas fa-save me-1"></i> Cập nhật trạng thái
                                        </button>
                                    @endif

                                    {{-- Nút xem thông tin trả hàng/hoàn tiền: chỉ hiển thị khi đơn ở trạng thái returned --}}
                                    @if (($order->status ?? '') === 'returned')
                                        @php
                                            $returnInfoLabel = 'Thông tin trả hàng';
                                            $method = strtolower((string)($order->payment_method ?? ''));
                                            if (in_array($method, ['vnpay','momo','bank'], true)) {
                                                $latestReturn = \App\Models\OrderReturn::where('order_id', $order->id)
                                                    ->orderByDesc('created_at')
                                                    ->first();
                                                $customerRequest = strtolower((string)($order->customer_request ?? ''));
                                                if ($latestReturn && $latestReturn->type === 'return_refund') {
                                                    if (strpos($customerRequest, 'hủy') !== false || strpos($customerRequest, 'huy') !== false) {
                                                        $returnInfoLabel = 'Thông tin hủy đơn hoàn tiền';
                                                    } else {
                                                        $returnInfoLabel = 'Thông tin trả hàng hoàn tiền';
                                                    }
                                                }
                                            }
                                        @endphp
                                        <a href="{{ route('admin.orders.returnInfo', $order->id) }}" class="btn btn-info btn-lg">
                                            <i class="fas fa-undo me-1"></i> {{ $returnInfoLabel }}
                                        </a>
                                    @endif
                                </div>
                            </div>


                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let form = document.getElementById('statusForm');
                                let selectStatus = document.querySelector('select[name="status"]');
                                let updateBtn = document.getElementById('updateBtn');

                                // Nếu đã hoàn thành thì thông báo ngay
                                @if ($order->status === 'completed')
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Đơn hàng đã hoàn thành',
                                        text: 'Bạn không thể thay đổi trạng thái của đơn này.',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#3085d6'
                                    });
                                @endif

                                if (updateBtn) {
                                    updateBtn.addEventListener('click', function(e) {
                                        e.preventDefault();

                                        let status = selectStatus.value;

                                        if (status === 'canceled') {
                                            Swal.fire({
                                                title: 'Xác nhận hủy đơn hàng?',
                                                text: "Hành động này không thể hoàn tác!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Có, hủy ngay',
                                                cancelButtonText: 'Không'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    form.submit();
                                                }
                                            });
                                        } else {
                                            form.submit();
                                        }
                                    });
                                }
                            });
                        </script>


                        <script>
                            document.getElementById('statusForm').addEventListener('submit', function(e) {
                                let status = document.querySelector('select[name="status"]').value;
                                if (status === 'cancelled') {
                                    if (!confirm('⚠ Bạn có chắc muốn hủy đơn hàng này không?')) {
                                        e.preventDefault();
                                    }
                                }
                            });
                        </script>

                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-tools me-2 text-primary"></i>
                            Thao tác khác
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>
                                In đơn hàng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary">Gửi email</button>
    </div>
    </form>

    </div>
    </div>
    </div>
    </div>

    <style>
        .badge-lg {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        @media print {

            .btn,
            .modal,
            .card-header {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
@endsection
