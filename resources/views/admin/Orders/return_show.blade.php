@extends('admin.index')

@section('container-fluid')
<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="fas fa-undo me-2 text-primary"></i>
                Thông tin trả hàng/hoàn tiền - Đơn #{{ $order->order_code }}
            </h3>
            <div>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại đơn hàng
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @php
            $isVnpay = strtolower((string)($order->payment_method ?? '')) === 'vnpay';
            $showBank = $isVnpay && ($orderReturn->type === 'return_refund');
        @endphp
        <div class="col-lg-{{ $showBank ? '7' : '12' }} mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i> Chi tiết yêu cầu</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Loại yêu cầu</dt>
                        <dd class="col-sm-8">
                            @php
                                $typeText = $orderReturn->type === 'return_refund' ? 'Trả hàng hoàn tiền' : 'Trả hàng';
                            @endphp
                            <span class="badge bg-secondary">{{ $typeText }}</span>
                        </dd>

                        <dt class="col-sm-4">Trạng thái xử lý</dt>
                        <dd class="col-sm-8">
                            @php
                                $statusMap = [
                                    'pending' => ['bg-warning','Chờ duyệt'],
                                    'approved' => ['bg-success','Đã duyệt'],
                                    'rejected' => ['bg-danger','Từ chối'],
                                    'canceled' => ['bg-secondary','Đã hủy'],
                                ];
                                [$badgeClass, $label] = $statusMap[$orderReturn->status] ?? ['bg-secondary', ucfirst($orderReturn->status)];
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                        </dd>

                        <dt class="col-sm-4">Khách hàng</dt>
                        <dd class="col-sm-8">{{ $order->user->name ?? $order->name }} (ID: {{ $order->user_id }})</dd>

                        <dt class="col-sm-4">Ngày yêu cầu</dt>
                        <dd class="col-sm-8">{{ optional($orderReturn->created_at)->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Lý do</dt>
                        <dd class="col-sm-8">{{ $orderReturn->reason }}</dd>

                        <dt class="col-sm-4">Phương thức thanh toán</dt>
                        <dd class="col-sm-8">{{ strtoupper($order->payment_method ?? 'N/A') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        @if($showBank)
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-university me-2 text-primary"></i> Thông tin ngân hàng (nếu có)</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Ngân hàng</dt>
                            <dd class="col-sm-7">{{ $orderReturn->bank_name ?? '—' }}</dd>

                            <dt class="col-sm-5">Chủ tài khoản</dt>
                            <dd class="col-sm-7">{{ $orderReturn->bank_account_name ?? '—' }}</dd>

                            <dt class="col-sm-5">Số tài khoản</dt>
                            <dd class="col-sm-7">{{ $orderReturn->bank_account_number ?? '—' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="fas fa-images me-2 text-primary"></i> Hình ảnh minh chứng</h5>
                </div>
                <div class="card-body">
                    @php $images = $orderReturn->images ?? []; @endphp
                    @if(!empty($images))
                        <div class="row g-3">
                            @foreach($images as $img)
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-1 h-100 d-flex align-items-center justify-content-center bg-light">
                                        <img src="{{ asset('storage/'.$img) }}" alt="proof" class="img-fluid rounded">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Không có hình ảnh đính kèm.</p>
                    @endif
                </div>
            </div>
        </div>

        @if(($orderReturn->status ?? null) === 'pending')
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-end">
                <form action="{{ route('admin.orders.return.reject', $order) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn từ chối yêu cầu này?');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-times me-1"></i> Từ chối
                    </button>
                </form>

                <form action="{{ route('admin.orders.return.approve', $order) }}" method="POST" onsubmit="return confirm('Xác nhận duyệt yêu cầu trả hàng/hoàn tiền?');">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Xác nhận
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
