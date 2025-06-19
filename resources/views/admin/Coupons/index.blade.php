@extends('admin.index')

@section('container-fluid')
    <div class="row mb-4">
        <div class="col-12">
            <div
                class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary p-4 rounded-3 text-white shadow-sm">
                <div>
                    <h3 class="mb-1 fw-bold">Coupon Management</h3>
                    <p class="mb-0 opacity-75">Manage your coupon efficiently</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 bg-transparent">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"
                                class="text-white-50 text-decoration-none">Coupons</a></li>

                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-xl-4">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-success text-white">
                    <div class="d-flex align-items-center">
                        <i class="ri-add-circle-line fs-4 me-2"></i>
                        <h5 class="card-title mb-0 fw-semibold">Create New Coupons</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-check-circle-line me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Mã giảm giá</label>
                            <input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
                            @error('code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phần trăm giảm (%)</label>
                            <input type="number" class="form-control" name="discount_percent"
                                value="{{ old('discount_percent') }}" required min="0" max="100">
                            @error('discount_percent')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giảm tối đa (VNĐ)</label>
                            <input type="number" class="form-control" name="max_discount" value="{{ old('max_discount') }}"
                                required min="0">
                            @error('max_discount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá trị đơn tối thiểu (VNĐ)</label>
                            <input type="number" class="form-control" name="min_order_amount"
                                value="{{ old('min_order_amount') }}" required min="0">
                            @error('min_order_amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số lần sử dụng toàn hệ thống (tùy chọn)</label>
                            <input type="number" class="form-control" name="usage_limit" value="{{ old('usage_limit') }}"
                                required min="1">
                            @error('usage_limit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số lần dùng / người (tùy chọn)</label>

                            <input type="number" class="form-control" name="per_user_limit"
                                value="{{ old('per_user_limit') }}" required min="1">
                            @error('per_user_limit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                    <label class="form-label">Ngày hết hạn (tùy chọn)</label>

                    <input type="date" class="form-control" name="expires_at" value="{{ old('expires_at') }}" required>
                    @error('expires_at')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>







                <button type="submit" class="btn btn-primary">Tạo mã</button>

                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <h2 class="mb-3">Danh sách mã giảm giá</h2>

       

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Giảm (%)</th>
                    <th>Giảm tối đa</th>
                    <th>Đơn tối thiểu</th>
                    <th>Giới hạn</th>
                    <th>HSD</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->discount_percent }}%</td>
                        <td>{{ number_format($coupon->max_discount) }}đ</td>
                        <td>{{ number_format($coupon->min_order_amount) }}đ</td>
                        <td>{{ $coupon->used_count }}/{{ $coupon->usage_limit ?? '∞' }}</td>
                        <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Không' }}</td>
                        <td>
                            {{-- <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">Sửa</a> --}}
                            {{-- <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Xoá mã này?')">Xoá</button>
                                </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $coupons->links() }}
    </div>
    </div>
@endsection
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .category-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .card {
        border-radius: 12px;
    }

    .input-group-text {
        border-radius: 8px 0 0 8px;
    }

    .form-control {
        border-radius: 0 8px 8px 0;
    }

    .form-control:first-child {
        border-radius: 8px;
    }

    .offcanvas {
        border-radius: 20px 0 0 20px;
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
    }

    .form-check-input:checked {
        background-color: #11998e;
        border-color: #11998e;
    }

    .alert {
        border-radius: 10px;
        border: none;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.7);
    }

    .form-control::placeholder {
        color: var(--bs-secondary);
        /* biến màu xám của Bootstrap */
        opacity: 0.5;
    }
</style>
