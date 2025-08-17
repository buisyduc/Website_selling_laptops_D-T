@if($reviews->count())
    @foreach($reviews as $review)
        <div class="border-b py-3">
            <div class="flex items-center">
                {{-- Hiển thị số sao --}}
                <div class="text-yellow-500 mr-2">
                    @for($i=1; $i<=5; $i++)
                        @if($i <= $review->rating)
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <span class="text-gray-600 text-sm">
                    {{ $review->user->name ?? 'Người dùng ẩn danh' }}
                </span>
            </div>
            <p class="mt-1 text-gray-800">{{ $review->content }}</p>
        </div>
    @endforeach
@else
    <p class="text-gray-500">Chưa có đánh giá nào cho bộ lọc này.</p>
@endif
