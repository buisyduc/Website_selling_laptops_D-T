@if ($reviews->count())
    @foreach ($reviews->sortByDesc('created_at') as $review)
        <div class="border-b py-4">
            <!-- Tên người đánh giá và số sao -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="font-medium mr-2">{{ $review->user->name ?? 'Người dùng ẩn danh' }}</span>
                    <div class="flex items-center text-yellow-500">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <!-- Nội dung đánh giá -->
                        <p class="mt-2 text-gray-800">{{ $review->comment }}</p>
                    </div>
                </div>
                <span class="text-gray-500 text-sm">
                    Đánh giá đã đăng vào {{ $review->created_at->diffForHumans() }}
                </span>
            </div>

        </div>
    @endforeach
@else
    <p class="text-gray-500 py-4">Chưa có đánh giá nào cho bộ lọc này.</p>
@endif
