@extends('admin.index')

@section('container-fluid')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-trash-restore me-2"></i>
                        Thương hiệu đã xóa mềm
                    </h3>
                    <div class="card-tools">
                        <span class="badge bg-secondary">{{  $brands->total() }} mục</span>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($brands->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" width="10%">
                                            <i class="fas fa-hashtag me-1"></i>
                                            ID
                                        </th>
                                        <th scope="col" width="40%">
                                            <i class="fas fa-tag me-1"></i>
                                            Tên thương hiệu
                                        </th>
                                        <th scope="col" width="25%">
                                            <i class="fas fa-clock me-1"></i>
                                            Thời gian xóa
                                        </th>
                                        <th scope="col" width="25%" class="text-center">
                                            <i class="fas fa-cogs me-1"></i>
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($brands as  $brand)
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark">#{{ $brand->id }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $brand->name }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $brand->deleted_at->format('d/m/Y') }}
                                                <br>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $brand->deleted_at->format('H:i:s') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group" aria-label="Actions">
                                                <form action="{{ route('brands.restore', $brand->id) }}"
                                                      method="POST"
                                                      class="d-inline-block">
                                                    @csrf
                                                    <button type="submit"
                                                            onclick="return confirm('Bạn có chắc chắn muốn khôi phục danh mục này không?')"
                                                            class="btn btn-sm btn-success"
                                                            title="Khôi phục danh mục"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fas fa-undo me-1"></i>
                                                        Khôi phục
                                                    </button>
                                                </form>

                                                <form action="{{ route('brands.forceDelete', $brand->id) }}"
                                                      method="POST"
                                                      class="d-inline-block ms-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Xóa vĩnh viễn"
                                                            data-bs-toggle="tooltip"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn thương hiệu này không?')">
                                                        <i class="fas fa-trash-alt me-1"></i>
                                                        Xóa vĩnh viễn
                                                    </button>
                                                </form>
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
                                Hiển thị {{ $brands->firstItem() ?? 0 }} - {{ $brands->lastItem() ?? 0 }}
                                trong tổng số {{ $brands->total() }} mục
                            </div>
                            <div>
                                {{ $brands->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-inbox fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Không có danh mục nào đã bị xóa</h5>
                            <p class="text-muted">Tất cả danh mục hiện đang hoạt động</p>
                            <a href="{{ route('brands') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="{{ route('brands') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                        <div class="col-sm-6 text-end">
                            @if($brands->count() > 0)
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                                    <i class="fas fa-tasks me-1"></i>
                                    Thao tác hàng loạt
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-labelledby="bulkActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionsModalLabel">
                    <i class="fas fa-tasks me-2"></i>
                    Thao tác hàng loạt
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Chọn hành động bạn muốn thực hiện với tất cả danh mục đã xóa:</p>

                <form action="{{ route('brands.restoreAll') }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Khôi phục tất cả danh mục đã xóa?')">
                        <i class="fas fa-undo me-2"></i>
                        Khôi phục tất cả
                    </button>
                </form>

                <form action="{{ route('brands.forceDeleteAll') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirm('⚠️ CẢNH BÁO!\n\nXóa vĩnh viễn TẤT CẢ danh mục đã xóa?\nHành động này không thể hoàn tác!')">
                        <i class="fas fa-trash-alt me-2"></i>
                        Xóa vĩnh viễn tất cả
                    </button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@endsection
