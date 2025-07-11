@extends('admin.index')

@section('container-fluid')
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold">✏️ Chỉnh sửa đơn hàng #{{ $order->order_code }}</h2>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">Khách hàng</label>
        <input type="text" id="name" name="name" value="{{ old('name', $order->name) }}" class="form-control" required>
   </div>
    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $order->email) }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $order->phone) }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label for="payment_method" class="form-label fw-semibold">Phương thức thanh toán</label>
        <select id="payment_method" name="payment_method" class="form-select">
            @foreach(['vnpay','momo','cod'] as $method)
                <option value="{{ $method }}" {{ old('payment_method', $order->payment_method) === $method ? 'selected' : '' }}>
                    {{ strtoupper($method) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
    <label class="form-label fw-semibold">Trạng thái đơn hàng</label>

    <!-- Trạng thái hiện tại -->
    <div class="mb-2">
        <span class="badge bg-{{ [
            'pending' => 'warning',
            'confirmed' => 'primary',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger'
        ][$order->status] ?? 'secondary' }}">
            Hiện tại: {{ strtoupper($order->status) }}
        </span>
    </div>

    @php
        $selectedStatus = old('status') ?? $order->status;
    @endphp
    <select name="status" id="status" class="form-select">
            @foreach(['pending','confirmed','processing','completed','cancelled'] as $status)
                <option value="{{ $status }}" {{ $selectedStatus === $status ? 'selected' : '' }}>
                    {{ strtoupper($status) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
                        <label for="province" class="form-label fw-semibold">Tỉnh/Thành</label>
                        <input type="text" id="province" name="province" value="{{ old('province',$order->province) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="district" class="form-label fw-semibold">Quận/Huyện</label>
                        <input type="text" id="district" name="district" value="{{ old('district',$order->district) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="ward" class="form-label fw-semibold">Phường/Xã</label>
                        <input type="text" id="ward" name="ward" value="{{ old('ward',$order->ward) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="address" class="form-label fw-semibold">Địa chỉ cụ thể</label>
                        <input type="text" id="address" name="address" value="{{ old('address',$order->address) }}" class="form-control">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Lưu thay đổi
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
              
       </form>

            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="ri-delete-bin-line me-1"></i> Xóa đơn hàng
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
