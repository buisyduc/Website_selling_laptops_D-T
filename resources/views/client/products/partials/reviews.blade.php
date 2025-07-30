@foreach($reviews as $review)
<div class="card mb-4 review-item">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-center">
                <div class="avatar me-3">
                    @if($review->user->avatar)
                        <img src="{{ Storage::url($review->user->avatar) }}" 
                             class="rounded-circle" width="50" height="50" alt="{{ $review->user->name }}">
                    @else
                        <div class="avatar-placeholder rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h6 class="mb-0">{{ $review->user->name }}</h6>
                    <small class="text-muted">
                        {{ $review->created_at->diffForHumans() }}
                        @if($review->is_edited)
                            <span class="badge bg-info ms-2">Đã chỉnh sửa</span>
                        @endif
                    </small>
                </div>
            </div>
            <div class="rating">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-empty' }} text-warning"></i>
                @endfor
            </div>
        </div>
        
        @if($review->comment)
            <div class="review-comment mb-3">
                {!! nl2br(e($review->comment)) !!}
            </div>
        @endif
        
        @if($review->images)
            <div class="review-images d-flex flex-wrap gap-2 mb-3">
                @foreach($review->images as $image)
                    <a href="{{ Storage::url($image) }}" data-lightbox="review-{{ $review->id }}" 
                       data-title="Đánh giá của {{ $review->user->name }}">
                        <img src="{{ Storage::url($image) }}" class="img-thumbnail" width="80" height="80">
                    </a>
                @endforeach
            </div>
        @endif
        
        @can('update', $review)
            <div class="review-actions mt-3 pt-3 border-top d-flex gap-2">
                <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-edit me-1"></i> Sửa
                </a>
                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                            onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                        <i class="fas fa-trash me-1"></i> Xóa
                    </button>
                </form>
            </div>
        @endcan
    </div>
</div>
@endforeach

@if($reviews instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $reviews->withQueryString()->links() }}
@endif