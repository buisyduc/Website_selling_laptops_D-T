@extends('admin.index')
@section('container-fluid')
<div class="container-fluid py-4">
    <h2 class="fw-bold mb-3">📌 Quản lý Đánh giá</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Sản phẩm</th>
                            <th>Người dùng</th>
                            <th>Nội dung</th>
                            <th>Đánh giá</th>
                            <th>Ngày tạo</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->product->name }}</td>
                            <td>{{ $review->user->name }}</td>
                            <td>{{ Str::limit($review->comment, 50) }}</td>
                            <td>{{ $review->rating }}/5</td>
                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="ri-delete-bin-line me-1"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .btn {
        border-radius: 8px;
        font-weight: 500;
    }
    .card {
        border-radius: 12px;
    }
    .pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
    }
</style>
@endsection
