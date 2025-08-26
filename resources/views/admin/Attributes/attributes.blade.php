@extends('admin.index')
@section('container-fluid')
    <div class="container-fluid">

        <!-- Tiêu đề trang nâng cao với nền gradient -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-1 fw-bold">
                                    <i class="ri-settings-3-line me-2"></i>
                                    Thuộc tính sản phẩm
                                </h3>
                                <p class="mb-0 opacity-75">Quản lý thuộc tính và biến thể sản phẩm</p>
                            </div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 bg-transparent">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);" class="text-white-50 text-decoration-none">
                                            <i class="ri-dashboard-line me-1"></i>Bảng điều khiển
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);" class="text-white-50 text-decoration-none">Sản
                                            phẩm</a>
                                    </li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">Thuộc tính</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Form tạo thuộc tính nâng cao -->
            <div class="col-xxl-4 col-xl-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm bg-primary rounded d-flex align-items-center justify-content-center">
                                    <i class="ri-add-circle-line text-white fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-0 fw-semibold">Tạo thuộc tính mới</h5>
                                <p class="card-text text-muted small mb-0">Thêm một thuộc tính sản phẩm mới</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="ri-check-circle-line me-2 fs-5"></i>
                                    <div>
                                        <strong>Thành công!</strong> {{ session('success') }}
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('attributes.store') }}" enctype="multipart/form-data"
                            class="needs-validation" novalidate>
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="ri-price-tag-3-line me-1 text-primary"></i>
                                    Tên thuộc tính
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-edit-line text-muted"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}"
                                        placeholder="Nhập tên thuộc tính (ví dụ: Màu sắc, Kích thước)" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Giá trị thuộc tính <span
                                        class="text-danger">*</span></label>

                                <div id="value-fields">
                                    <div class="input-group mb-2">
                                        <input type="text" name="values[]" class="form-control" required>
                                        <button type="button" class="btn btn-danger remove-value"><i
                                                class="ri-close-line"></i></button>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-value">
                                    <i class="ri-add-line me-1"></i> Thêm giá trị
                                </button>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ri-add-line me-2"></i>
                                    Tạo thuộc tính
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh sách biến thể sản phẩm -->
            <div class="col-xxl-8 col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light border-0 py-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-info rounded d-flex align-items-center justify-content-center">
                                        <i class="ri-list-check-2 text-white fs-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-0 fw-semibold">Quản lý biến thể</h5>
                                    <p class="card-text text-muted small mb-0">Quản lý các biến thể đã tồn tại</p>
                                </div>
                            </div>

                            <!-- Form tìm kiếm nâng cao -->
                            <div class="flex-shrink-0">
                                <form method="GET" action="{{ route('attributes') }}"
                                    class="d-flex align-items-center gap-2">
                                    <div class="input-group" style="min-width: 280px;">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-search-line text-muted"></i>
                                        </span>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Tìm kiếm biến thể..." class="form-control border-start-0 ps-0">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-search-line"></i>
                                        </button>
                                        @if (request('search'))
                                            <a href="{{ route('attributes') }}" class="btn btn-outline-secondary">
                                                <i class="ri-close-line"></i>
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold text-uppercase small px-4 py-3">
                                            <i class="ri-product-hunt-line me-1"></i>Tên biến thể
                                        </th>
                                        <th class="border-0 fw-semibold text-uppercase small px-2 py-3">
                                            <i class="ri-barcode-line me-1"></i>Giá trị biến thể
                                        </th>
                                        <th class="border-0 fw-semibold text-uppercase small px-2 py-3">
                                            <i class="ri-settings-3-line me-1"></i>Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attributes as $attribute)
                                        <tr>
                                            <td class="px-4 py-2">{{ $attribute->name }}</td>
                                            <td class="px-2 py-2">
                                                @foreach ($attribute->options as $option)
                                                    <span class="badge bg-primary me-1">{{ $option->value }}</span>
                                                @endforeach
                                            </td>
                                            <!-- Thêm SweetAlert2 -->
                                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    // Xử lý thêm giá trị thuộc tính (giữ nguyên của bạn)
                                                    const valueFields = document.getElementById('value-fields');
                                                    const addValueBtn = document.getElementById('add-value');

                                                    addValueBtn.addEventListener('click', function() {
                                                        const group = document.createElement('div');
                                                        group.className = 'input-group mb-2';
                                                        group.innerHTML = `
            <input type="text" name="values[]" class="form-control"  required>
            <button type="button" class="btn btn-danger remove-value"><i class="ri-close-line"></i></button>
        `;
                                                        valueFields.appendChild(group);
                                                    });

                                                    valueFields.addEventListener('click', function(e) {
                                                        if (e.target.closest('.remove-value')) {
                                                            e.target.closest('.input-group').remove();
                                                        }
                                                    });

                                                    // Xử lý xóa bằng SweetAlert2
                                                    const deleteButtons = document.querySelectorAll('.btn-delete');

                                                    deleteButtons.forEach(button => {
                                                        button.addEventListener('click', function() {
                                                            const form = this.closest('.delete-form');

                                                            Swal.fire({
                                                                title: 'Bạn có chắc muốn xóa?',
                                                                text: "Hành động này không thể hoàn tác!",
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#d33',
                                                                cancelButtonColor: '#3085d6',
                                                                confirmButtonText: 'Xóa',
                                                                cancelButtonText: 'Hủy',
                                                                reverseButtons: true,
                                                                showClass: {
                                                                    popup: 'animate__animated animate__fadeInDown'
                                                                },
                                                                hideClass: {
                                                                    popup: 'animate__animated animate__fadeOutUp'
                                                                }
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    form.submit();
                                                                }
                                                            });
                                                        });
                                                    });
                                                });
                                            </script>

                                            <!-- Sửa lại form xóa trong bảng -->
                                            <td class="px-2 py-2">
                                                <a href="{{ route('attributes.edit', $attribute->id) }}"
                                                    class="btn btn-sm btn-warning">Chỉnh sửa</a>
                                                <form action="{{ route('attributes.destroy', $attribute->id) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                        Xóa
                                                    </button>
                                                </form>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Phân trang nếu có -->
                        @if (method_exists($variants, 'links'))
                            <div class="card-footer bg-light border-0 py-3">
                                {{ $variants->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const valueFields = document.getElementById('value-fields');
            const addValueBtn = document.getElementById('add-value');

            addValueBtn.addEventListener('click', function() {
                const group = document.createElement('div');
                group.className = 'input-group mb-2';
                group.innerHTML = `
                <input type="text" name="values[]" class="form-control"  required>
                <button type="button" class="btn btn-danger remove-value"><i class="ri-close-line"></i></button>
            `;
                valueFields.appendChild(group);
            });

            valueFields.addEventListener('click', function(e) {
                if (e.target.closest('.remove-value')) {
                    e.target.closest('.input-group').remove();
                }
            });
        });
    </script>

    <style>
        /* Styling nâng cao */
        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .form-control::placeholder {
            color: rgba(0, 0, 0, 0.5);
            font-style: italic;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .avatar-sm {
            width: 2.5rem;
            height: 2.5rem;
        }

        .empty-state {
            padding: 2rem;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .input-group-text {
            background-color: transparent;
        }

        .border-start-0 {
            border-left: 0 !important;
        }

        .border-end-0 {
            border-right: 0 !important;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .form-check-input:disabled {
            opacity: 0.7;
        }

        .font-monospace {
            font-family: 'Courier New', monospace;
        }

        /* Cải thiện responsive */
        @media (max-width: 768px) {
            .d-flex.flex-wrap.gap-3 {
                flex-direction: column;
            }

            .input-group {
                min-width: auto !important;
            }

            .table-responsive {
                font-size: 0.875rem;
            }
        }
    </style>
@endsection
