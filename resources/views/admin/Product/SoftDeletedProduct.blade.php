@extends('admin.index')

@section('container-fluid')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-trash-restore me-2"></i>
                        Sản phẩm đã xóa mềm
                    </h3>
                    <div class="card-tools">
                        <span class="badge bg-secondary">{{ $products->total() }} sản phẩm</span>
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

                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" width="8%">
                                            <i class="fas fa-hashtag me-1"></i>
                                            ID
                                        </th>
                                        <th scope="col" width="10%">
                                            <i class="fas fa-image me-1"></i>
                                            Ảnh
                                        </th>
                                        <th scope="col" width="25%">
                                            <i class="fas fa-box me-1"></i>
                                            Tên sản phẩm
                                        </th>
                                        <th scope="col" width="15%">
                                            <i class="fas fa-tag me-1"></i>
                                            Danh mục
                                        </th>
                                        <th scope="col" width="15%">
                                            <i class="fas fa-trademark me-1"></i>
                                            Thương hiệu
                                        </th>
                                        <th scope="col" width="12%">
                                            <i class="fas fa-clock me-1"></i>
                                            Thời gian xóa
                                        </th>
                                        <th scope="col" width="15%" class="text-center">
                                            <i class="fas fa-cogs me-1"></i>
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark">#{{ $product->id }}</span>
                                        </td>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                     alt="{{ $product->name }}"
                                                     class="img-thumbnail"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px; border-radius: 0.375rem;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->slug)
                                                <br>
                                                <small class="text-muted">{{ $product->slug }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->category)
                                                <span class="badge bg-info">{{ $product->category->name }}</span>
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->brand)
                                                <span class="badge bg-primary">{{ $product->brand->name }}</span>
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $product->deleted_at->format('d/m/Y') }}
                                                <br>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $product->deleted_at->format('H:i:s') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group" aria-label="Actions">
                                                <form action="{{ route('product.restore', $product->id) }}"
                                                      method="POST"
                                                      class="d-inline-block">
                                                    @csrf
                                                    <button type="submit"
                                                            onclick="return confirm('Bạn có chắc chắn muốn khôi phục sản phẩm này không?')"
                                                            class="btn btn-sm btn-success"
                                                            title="Khôi phục sản phẩm"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fas fa-undo me-1"></i>
                                                        Khôi phục
                                                    </button>
                                                </form>

                                                <form action="{{ route('product.forceDelete', $product->id) }}"
                                                      method="POST"
                                                      class="d-inline-block ms-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Xóa vĩnh viễn"
                                                            data-bs-toggle="tooltip"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm này không? Tất cả dữ liệu liên quan sẽ bị mất!')">
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
                                Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}
                                trong tổng số {{ $products->total() }} sản phẩm
                            </div>
                            <div>
                                {{ $products->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-inbox fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Không có sản phẩm nào đã bị xóa</h5>
                            <p class="text-muted">Tất cả sản phẩm hiện đang hoạt động</p>
                            <a href="{{ route('product-list') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="{{ route('product-list') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                        <div class="col-sm-6 text-end">
                            @if($products->count() > 0)
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
                <p class="text-muted">Chọn hành động bạn muốn thực hiện với tất cả sản phẩm đã xóa:</p>

                <form action="{{ route('product.restoreAll') }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Khôi phục tất cả sản phẩm đã xóa?')">
                        <i class="fas fa-undo me-2"></i>
                        Khôi phục tất cả
                    </button>
                </form>

                <form action="{{ route('product.forceDeleteAll') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirm('⚠️ CẢNH BÁO!\n\nXóa vĩnh viễn TẤT CẢ sản phẩm đã xóa?\nTất cả dữ liệu liên quan (đánh giá, đơn hàng, hình ảnh...) sẽ bị mất!\nHành động này không thể hoàn tác!')">
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
