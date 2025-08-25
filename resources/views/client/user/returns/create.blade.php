@extends('client.user.layoutManagement.layout')

@section('content')
<div class="container py-4">
    @php
        $isPending = ($order->status === 'pending');
        $isCompleted = ($order->status === 'completed');
        $isPaid = ($order->payment_status === 'paid');
        $isCancelAction = request('action') === 'cancel';
        $forceCancelRefund = $isOnline && $isPending && $isCancelAction; // VNPay + pending + hủy đơn => buộc hoàn tiền
        // Mặc định: nếu là online (VNPay, v.v.) thì ưu tiên Trả hàng hoàn tiền
        // - Trường hợp hủy đơn khi đang pending + online: buộc hoàn tiền
        // - Các trạng thái online khác (shipping, completed, ...): mặc định hoàn tiền
        // Thiết lập mặc định theo điều kiện mong muốn:
        // - Online + đã giao (completed) + đã thanh toán (paid): Trả hàng hoàn tiền
        // - COD + đã giao (completed): Trả hàng
        // - Hủy đơn khi pending + online: Trả hàng hoàn tiền
        // - Các trường hợp khác: nếu online thì hoàn tiền, nếu COD thì trả hàng
        if ($forceCancelRefund) {
            $defaultType = 'return_refund';
        } elseif ($isOnline && $isCompleted && $isPaid) {
            $defaultType = 'return_refund';
        } elseif (!$isOnline && $isCompleted) {
            $defaultType = 'return';
        } else {
            $defaultType = $isOnline ? 'return_refund' : 'return';
        }
        // Chế độ chỉ xem: khi đã có yêu cầu hoàn tiền lúc hủy đơn (VNPay) và đơn đang pending + payment_status refund_pending
        $isViewOnly = ($order->payment_status === 'refund_pending' && $isPending && isset($orderReturn) && $orderReturn);
    @endphp
    <h3 class="mb-3">
        @if($isViewOnly)
            Thông tin hủy đơn / hoàn tiền
        @else
            {{ $forceCancelRefund ? 'Yêu cầu hủy đơn/hoàn tiền' : ($isOnline ? 'Yêu cầu trả hàng hoàn tiền' : 'Yêu cầu trả hàng') }}
        @endif
    </h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                <strong>Mã đơn:</strong> {{ $order->order_code ?? ('ORDER-' . $order->id) }}<br>
                <strong>Trạng thái:</strong> {{ $order->status }}<br>
                <strong>Phương thức thanh toán:</strong> {{ strtoupper($order->payment_method ?? 'COD') }}
            </div>

            @if($isViewOnly)
                <div class="mb-3">
                    <label class="form-label">Hình thức</label>
                    <input type="text" class="form-control" value="Hủy đơn/hoàn tiền" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lý do hủy đơn</label>
                    <textarea class="form-control" rows="4" disabled>{{ $orderReturn->reason }}</textarea>
                </div>
                @if($isOnline)
                <div class="mb-3">
                    <label class="form-label">Thông tin ngân hàng</label>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Tên ngân hàng" value="{{ $orderReturn->bank_name }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Tên chủ tài khoản" value="{{ $orderReturn->bank_account_name }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Số tài khoản" value="{{ $orderReturn->bank_account_number }}" disabled>
                        </div>
                    </div>
                </div>
                @endif
                @php
                    $images = [];
                    if (!empty($orderReturn->images)) {
                        $decoded = is_array($orderReturn->images) ? $orderReturn->images : json_decode($orderReturn->images, true);
                        $images = is_array($decoded) ? $decoded : [];
                    }
                @endphp
                @if(!empty($images))
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh đính kèm</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($images as $img)
                                <a href="{{ asset('storage/' . ltrim($img, '/')) }}" target="_blank">
                                    <img src="{{ asset('storage/' . ltrim($img, '/')) }}" alt="Ảnh" style="width: 100px; height: 100px; object-fit: cover;" class="rounded border">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <a href="{{ route('client.orders.show', $order->id) }}" class="btn btn-outline-secondary">Quay lại</a>
            @else
            <form method="POST" action="{{ route('orders.return.submit', $order->id) }}" enctype="multipart/form-data">
                @csrf
                @if(request('action'))
                    <input type="hidden" name="action" value="{{ request('action') }}">
                @endif

                <div class="mb-3">
                    <label class="form-label">Hình thức</label>
                    <select name="type" id="returnType" class="form-select" required disabled>
                        @if($forceCancelRefund)
                            {{-- Hủy đơn khi pending + online: hiển thị nhãn riêng --}}
                            <option value="return_refund" selected>Hủy đơn/hoàn tiền</option>
                        @else
                            <option value="return" {{ $defaultType === 'return' ? 'selected' : '' }}>Trả hàng</option>
                            <option value="return_refund" {{ $defaultType === 'return_refund' ? 'selected' : '' }}>Trả hàng hoàn tiền</option>
                        @endif
                    </select>
                    <input type="hidden" name="type" id="hiddenType" value="{{ $forceCancelRefund ? 'return_refund' : $defaultType }}">
                </div>

                <div class="mb-3">
                    <label id="reasonLabel" class="form-label">{{ $forceCancelRefund ? 'Lý do hủy đơn' : 'Lý do trả hàng' }}</label>
                    <textarea id="reasonInput" name="reason" class="form-control" rows="4" placeholder="{{ $forceCancelRefund ? 'Mô tả chi tiết lý do hủy đơn' : 'Mô tả chi tiết lý do (lỗi sản phẩm, thiếu phụ kiện, không đúng mô tả, ...)' }}">{{ old('reason') }}</textarea>
                </div>

                @php $showBank = $isOnline && $defaultType === 'return_refund'; @endphp
                @if($showBank)
                <div id="bankFields" style="display: block;">
                    <div class="mb-3">
                        <label class="form-label">Tên ngân hàng</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="form-control" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên chủ tài khoản</label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" class="form-control" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số tài khoản</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" class="form-control" >
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Hình ảnh minh chứng (tối đa 5)</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <div class="form-text">Vui lòng chụp rõ tình trạng sản phẩm, phụ kiện, hộp, hóa đơn...</div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('client.orders.show', $order->id) }}" class="btn btn-outline-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const isOnline = @json($isOnline);
    const forceCancelRefund = @json($forceCancelRefund);
    const defaultType = @json($defaultType);
    const typeEl = document.getElementById('returnType');
    const bankFields = document.getElementById('bankFields');
    const reasonLabel = document.getElementById('reasonLabel');
    const reasonInput = document.getElementById('reasonInput');
    const hiddenType = document.getElementById('hiddenType');
    function toggleBank(){
        if (!bankFields) return;
        if(isOnline && (typeEl?.value === 'return_refund' || defaultType === 'return_refund')){
            bankFields.style.display = 'block';
        } else {
            bankFields.style.display = 'none';
        }
    }
    function syncReasonLabel(){
        if(!reasonLabel || !reasonInput) return;
        const isCancelRefund = (typeEl && typeEl.value === 'return_refund' || defaultType === 'return_refund') && isOnline && {{ json_encode($order->status === 'pending') }} && {{ json_encode(request('action') === 'cancel') }};
        if(isCancelRefund){
            reasonLabel.textContent = 'Lý do hủy đơn';
            // chỉ đổi placeholder khi chưa có nội dung
            if(!reasonInput.value){
                reasonInput.placeholder = 'Mô tả chi tiết lý do hủy đơn';
            }
        } else {
            reasonLabel.textContent = 'Lý do trả hàng';
            if(!reasonInput.value){
                reasonInput.placeholder = 'Mô tả chi tiết lý do (lỗi sản phẩm, thiếu phụ kiện, không đúng mô tả, ...)';
            }
        }
    }
    if(typeEl){
        typeEl.addEventListener('change', function(){
            toggleBank();
            syncReasonLabel();
        });
        // Cố định lựa chọn theo điều kiện đã tính ở server
        typeEl.value = defaultType;
        hiddenType && (hiddenType.value = defaultType);
        typeEl.setAttribute('disabled', 'disabled');
        toggleBank();
        syncReasonLabel();
    }
})();
</script>
@endpush
