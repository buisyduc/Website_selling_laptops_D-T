@extends('client.layouts.layout')

@section('content')
<div class="container my-5">
    <div class="text-center mb-4">
        <h3 class="text-success mt-3 fw-bold animate__animated animate__fadeInDown">🎉 Cảm ơn bạn đã đặt hàng!</h3>
        <p class="text-muted animate__animated animate__fadeInUp">Đơn hàng của bạn đã được ghi nhận. Chúng tôi sẽ xử lý và giao hàng sớm nhất.</p>
    </div>

    <div class="bg-light rounded shadow-sm p-4 mx-auto animate__animated animate__fadeIn" style="max-width: 700px;">
        <div class="row mb-3">
            <div class="col-sm-6"><strong>Mã đơn hàng:</strong></div>
            <div class="col-sm-6 text-end text-primary fw-bold">{{ $order->order_code }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6"><strong>Thời gian đặt:</strong></div>
            <div class="col-sm-6 text-end">{{ $order->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6"><strong>Thanh toán:</strong></div>
            <div class="col-sm-6 text-end text-uppercase">{{ $order->payment_method }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6"><strong>Trạng thái:</strong></div>
            <div class="col-sm-6 text-end">
                <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
        <div class="row border-top pt-3">
            <div class="col-sm-6"><strong>Tổng tiền:</strong></div>
            <div class="col-sm-6 text-end text-danger fw-bold">{{ number_format($order->total_amount) }}₫</div>
        </div>

        {{-- 💸 Hiển thị hướng dẫn chuyển khoản nếu khách chọn chuyển khoản --}}
        @if($order->payment_method === 'bank_transfer')
        <div class="alert alert-warning mt-4">
            <h5 class="fw-bold">💸 Vui lòng chuyển khoản để hoàn tất đơn hàng</h5>
            <ul class="list-unstyled mb-2">
                <li><strong>Ngân hàng:</strong> MB Bank</li>
                <li><strong>Số tài khoản:</strong> 0123456789</li>
                <li><strong>Chủ tài khoản:</strong> TRẦN TUẤN</li>
                <li><strong>Nội dung chuyển khoản:</strong> THANHTOAN {{ $order->order_code }}</li>
                <li><strong>Số tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }}₫</li>
            </ul>
            <small class="text-muted">Sau khi chuyển khoản, đơn hàng sẽ được xác nhận bởi nhân viên hỗ trợ.</small>
        </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('client.orders.show', $order->id) }}" class="btn btn-outline-primary me-2">
                📄 Xem chi tiết đơn hàng
            </a>
            <a href="{{ route('index') }}" class="btn btn-success">
                🏠 Về trang chủ
            </a>
            <p class="mt-3 text-muted small">
                ⏳ Bạn sẽ được chuyển về trang chủ sau <span id="countdown-text" class="fw-bold text-dark">15</span> giây...
            </p>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('client.layouts.partials.footer')

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <script>
        let seconds = 15;
        const countdownText = document.getElementById('countdown-text');
        const interval = setInterval(() => {
            seconds--;
            if (countdownText) countdownText.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('index') }}";
            }
        }, 1000);
    </script>

    <style>
        .thank-you-title {
            animation: glow 1.5s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 5px #28a745, 0 0 10px #28a745;
            }
            to {
                text-shadow: 0 0 10px #28a745, 0 0 20px #28a745;
            }
        }
    </style>
@endsection
