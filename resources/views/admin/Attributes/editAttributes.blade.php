@extends('admin.index')

@section('container-fluid')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient"
                style="background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);">
                <div class="card-body text-white">
                    <h3 class="fw-bold mb-0"><i class="ri-edit-2-line me-2"></i>Sửa thuộc tính sản phẩm</h3>
                    <p class="opacity-75 mb-0">Cập nhật thông tin thuộc tính và giá trị</p>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('attributes.update', $attribute->id) }}" class="card shadow-sm border-0">
        @csrf
        @method('PUT')

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-check-line me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Lỗi!</strong> Vui lòng kiểm tra các trường sau:<br><br>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="ri-error-warning-line me-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label for="name" class="form-label fw-semibold">Tên thuộc tính <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $attribute->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Giá trị thuộc tính</label>
                <div id="value-fields">
                    @foreach ($attribute->options as $option)
                        <div class="input-group mb-2">
                            <input type="text" name="values[]" class="form-control" value="{{ $option->value }}" required>
                            <button type="button" class="btn btn-danger remove-value"><i class="ri-close-line"></i></button>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm" id="add-value">
                    <i class="ri-add-line me-1"></i> Thêm giá trị
                </button>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('attributes') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="ri-save-3-line me-1"></i> Lưu thay đổi
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const valueFields = document.getElementById('value-fields');
        const addValueBtn = document.getElementById('add-value');

        addValueBtn.addEventListener('click', function () {
            const group = document.createElement('div');
            group.className = 'input-group mb-2';
            group.innerHTML = `
                <input type="text" name="values[]" class="form-control" required>
                <button type="button" class="btn btn-danger remove-value"><i class="ri-close-line"></i></button>
            `;
            valueFields.appendChild(group);
        });

        valueFields.addEventListener('click', function (e) {
            if (e.target.closest('.remove-value')) {
                e.target.closest('.input-group').remove();
            }
        });
    });
</script>
@endsection
    