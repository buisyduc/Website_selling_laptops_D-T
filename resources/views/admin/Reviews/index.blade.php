@extends('admin.index')

@section('container-fluid')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quản lý đánh giá theo đơn hàng</h3>
        </div>
        <div class="card-body">
            <!-- Hiển thị thông báo -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng đánh giá</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->order_code }}</td>
                                <td>
                                    <!--unique('product_id') sẽ chỉ lấy các review có product_id khác nhau-->
                                    @foreach ($order->reviews->unique('product_id') as $review) 
                                        {{ $review->product->name ?? 'N/A' }}<br>
                                    @endforeach

                                    <!--groupBy('product_id') nhóm các review theo product_id, sau đó lấy sản phẩm đầu tiên-->
                                    {{-- @foreach ($order->reviews->groupBy('product_id') as $reviews)
                                        {{ $reviews->first()->product->name ?? 'N/A' }}<br>
                                    @endforeach --}}
                                </td>
                                <td>{{ $order->reviews_count ?? $order->reviews->count() }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#orderReviewsModal{{ $order->id }}">
                                        Xem chi tiết
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $orders->links() }}
        </div>
    </div>

    <!-- Modal hiển thị chi tiết đánh giá của đơn hàng -->
    @foreach ($orders as $order)
        <div class="modal fade" id="orderReviewsModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chi tiết đánh giá - Đơn hàng #{{ $order->order_code }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Điểm đánh giá</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->reviews as $review)
                                        <tr>
                                            <td>{{ $review->id }}</td>
                                            <td>{{ $review->product->name ?? 'N/A' }}</td>
                                            <td>{{ $review->rating }}/5</td>
                                            <td class="d-flex gap-2">
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#reviewDetailModal{{ $review->id }}">
                                                    Xem chi tiết
                                                </button>

                                                <form action="{{ route('admin.reviews.destroy', $review) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                                        Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal xem chi tiết từng đánh giá -->
    @foreach ($orders as $order)
        @foreach ($order->reviews as $review)
            <div class="modal fade" id="reviewDetailModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chi tiết đánh giá #{{ $review->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Đơn hàng:</strong> #{{ $order->order_code }}</p>
                            <p><strong>Sản phẩm:</strong> {{ $review->product->name ?? 'N/A' }}</p>
                            <p><strong>Người đánh giá:</strong> {{ $review->user->name ?? 'N/A' }}</p>
                            <p><strong>Điểm:</strong> {{ $review->rating }}/5</p>
                            <p><strong>Nhận xét:</strong> {{ $review->comment }}</p>

                            @if ($review->images && count($review->images) > 0)
                                <div class="mt-3">
                                    <strong>Hình ảnh:</strong>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        @foreach ($review->images as $image)
                                            <img src="{{ asset('storage/' . $image) }}" width="100"
                                                class="img-thumbnail">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@endsection