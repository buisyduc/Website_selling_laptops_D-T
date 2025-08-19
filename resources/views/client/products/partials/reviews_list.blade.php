@if ($reviews->count())
    @foreach ($reviews->sortByDesc('created_at') as $review)
        <div class="d-flex align-items-start border-bottom mt-2 py-3">
            <!-- Avatar = chữ cái đầu -->
            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                 style="width:30px;height:30px;flex:0 0 30px;font-weight:700;">
                {{ mb_strtoupper(mb_substr($review->user->name ?? 'N', 0, 1)) }}
            </div>

            <!-- Nội dung đánh giá -->
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="fw-bold">{{ $review->user->name ?? 'Người dùng ẩn danh' }} •</span>
                        <small class="text-muted ms-2">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <!-- Số sao -->
                <div class="text-warning mt-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $review->rating)
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>

                <!-- Nội dung -->
                <p class="mt-2 mb-0 text-dark">{{ $review->comment }}</p>
            </div>
        </div>
    @endforeach
@endif
