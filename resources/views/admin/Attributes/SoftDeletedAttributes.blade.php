@extends('admin.index')

@section('container-fluid')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-trash-restore me-2"></i>
                            Thuộc tính đã xóa mềm
                        </h3>
                        <div class="card-tools">
                            <span class="badge bg-secondary">{{ $variant_attributes->total() }} mục</span>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($variant_attributes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="10%">ID</th>
                                            <th width="40%">Tên thuộc tính</th>
                                            <th width="25%">Thời gian xóa</th>
                                            <th width="25%" class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($variant_attributes as $attribute)
                                            <tr>
                                                <td><span class="badge bg-light text-dark">#{{ $attribute->id }}</span></td>
                                                <td><strong>{{ $attribute->name }}</strong></td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $attribute->deleted_at->format('d/m/Y') }}<br>
                                                        {{ $attribute->deleted_at->format('H:i:s') }}
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <form action="{{ route('attributes.restore', $attribute->id) }}"
                                                            method="POST" class="d-inline-block">
                                                            @csrf
                                                            <button type="button"
                                                                class="btn btn-sm btn-success btn-restore">
                                                                <i class="fas fa-undo me-1"></i> Khôi phục
                                                            </button>
                                                        </form>

                                                        {{-- <form action="{{ route('attributes.forceDelete', $attribute->id) }}" method="POST" class="d-inline-block ms-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('⚠️ Xóa VĨNH VIỄN thuộc tính này?')" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash-alt me-1"></i>Xóa vĩnh viễn
                                                    </button>
                                                </form> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Hiển thị {{ $variant_attributes->firstItem() }} - {{ $variant_attributes->lastItem() }}
                                    trong tổng số {{ $variant_attributes->total() }} mục
                                </div>
                                <div>{{ $variant_attributes->links() }}</div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có thuộc tính nào đã bị xóa</h5>
                                <p class="text-muted">Tất cả thuộc tính hiện đang hoạt động</p>
                                <a href="{{ route('attributes') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                                </a>
                            </div>
                        @endif
                    </div>

                    @if ($variant_attributes->count() > 0)
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('attributes') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                            </a>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                                <i class="fas fa-tasks me-1"></i> Thao tác hàng loạt
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thao tác hàng loạt -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-labelledby="bulkActionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkActionsModalLabel">
                        <i class="fas fa-tasks me-2"></i> Thao tác hàng loạt
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('attributes.restoreAll') }}" method="POST" class="mb-3">
                        @csrf
                        <button type="button" class="btn btn-success w-100 btn-restore-all">
                            <i class="fas fa-undo me-2"></i> Khôi phục tất cả
                        </button>
                    </form>


                    {{-- <form action="{{ route('attributes.forceDeleteAll') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('⚠️ XÓA VĨNH VIỄN TẤT CẢ? Hành động này không thể hoàn tác!')">
                        <i class="fas fa-trash-alt me-2"></i> Xóa vĩnh viễn tất cả
                    </button>
                </form> --}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
            });
        </script>
    @endpush
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const restoreAllBtn = document.querySelector('.btn-restore-all');
        if (restoreAllBtn) {
            restoreAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Khôi phục tất cả thuộc tính đã xóa?',
                    text: "Hành động này sẽ khôi phục toàn bộ thuộc tính đã xóa!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Khôi phục tất cả',
                    cancelButtonText: 'Hủy',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý popup khôi phục
            const restoreButtons = document.querySelectorAll('.btn-restore');

            restoreButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Bạn có chắc muốn khôi phục?',
                        text: "Thuộc tính sẽ trở lại trạng thái hoạt động!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754', // xanh lá
                        cancelButtonColor: '#6c757d', // xám
                        confirmButtonText: 'Khôi phục',
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

@endsection
