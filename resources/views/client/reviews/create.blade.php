@extends('client.layouts.layout')

@section('title', 'Đánh giá sản phẩm')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Đánh giá sản phẩm: {{ $product->name }}</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('reviews.store', ['order' => $order, 'product' => $product]) }}" 
                          enctype="multipart/form-data" id="review-form">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Đánh giá của bạn</label>
                            <div class="rating-input d-flex justify-content-center">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                           {{ old('rating') == $i ? 'checked' : '' }} required>
                                    <label for="star{{ $i }}"><i class="fas fa-star fa-2x"></i></label>
                                @endfor
                            </div>
                            @error('rating')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Nhận xét chi tiết</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" name="comment" rows="5"
                                      placeholder="Hãy chia sẻ cảm nhận của bạn về sản phẩm...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="images" class="form-label fw-bold">Hình ảnh đính kèm</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            @error('images.*')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">
                                Bạn có thể tải lên tối đa {{ config('reviews.max_images', 5) }} ảnh, mỗi ảnh không quá 2MB.
                            </div>
                            <div id="image-preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('client.orders.show', $order) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Gửi đánh giá
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.rating-input input {
    display: none;
}
.rating-input label {
    color: #ddd;
    cursor: pointer;
    padding: 0 5px;
}
.rating-input input:checked ~ label {
    color: #ffc107;
}
.rating-input input:checked + label {
    color: #ffc107;
}
.rating-input label:hover,
.rating-input label:hover ~ label {
    color: #ffc107;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý hiển thị preview ảnh
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');
    
    imageInput.addEventListener('change', function() {
        imagePreview.innerHTML = '';
        
        if (this.files) {
            const maxFiles = {{ config('reviews.max_images', 5) }};
            const files = Array.from(this.files).slice(0, maxFiles);
            
            files.forEach(file => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'position-relative';
                    div.style.width = '100px';
                    div.style.height = '100px';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail w-100 h-100 object-fit-cover';
                    
                    div.appendChild(img);
                    imagePreview.appendChild(div);
                }
                
                reader.readAsDataURL(file);
            });
        }
    });
});
</script>
@endpush