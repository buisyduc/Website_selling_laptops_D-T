<!-- resources/views/reviews/edit.blade.php -->

@extends('client.layouts.layout')

@section('title', 'Chỉnh sửa đánh giá')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Chỉnh sửa đánh giá: {{ $review->product->name }}</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('reviews.update', $review) }}" enctype="multipart/form-data"
                            id="review-form">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label fw-bold">Đánh giá của bạn</label>
                                <div class="rating-input d-flex justify-content-center">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating"
                                            value="{{ $i }}"
                                            {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}"><i class="fas fa-star fa-2x"></i></label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="comment" class="form-label fw-bold">Nhận xét chi tiết</label>
                                <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="5"
                                    placeholder="Hãy chia sẻ cảm nhận của bạn về sản phẩm...">{{ old('comment', $review->comment) }}</textarea>
                                @error('comment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            @if ($review->images)
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Hình ảnh hiện tại</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($review->images as $image)
                                            <div class="position-relative" style="width: 100px; height: 100px;">
                                                <img src="{{ Storage::url($image) }}"
                                                    class="img-thumbnail w-100 h-100 object-fit-cover">
                                                <div class=" form-check position-absolute top-0 end-0 p-1">
                                                    <input type="checkbox" name="remove_images[]"
                                                        value="{{ $image }}"
                                                        class="form-check-input bg-danger border-danger">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label for="images" class="form-label fw-bold">Thêm hình ảnh mới</label>
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                    id="images" name="images[]" multiple accept="image/*">
                                @error('images.*')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">
                                    Bạn có thể tải lên tối đa {{ config('reviews.max_images', 5) }} ảnh, mỗi ảnh không quá
                                    2MB.
                                </div>
                                <div id="image-preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <!-- Form cập nhật (form chính) -->
                                <form method="POST" action="{{ route('reviews.update', $review) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary me-1">
                                        <i class="fas fa-save me-1"></i> Cập nhật
                                    </button>
                                </form>

                                <!-- Form xóa (riêng biệt) -->
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="me-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Bạn chắc chắn muốn xóa đánh giá này?')">
                                        <i class="fas fa-trash me-1"></i> Xóa
                                    </button>
                                </form>

                                <!-- Nút quay lại -->
                                <a href="{{ route('client.orders.show', $review->order_id) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tương tự script trong create.blade.php
    </script>
@endpush
