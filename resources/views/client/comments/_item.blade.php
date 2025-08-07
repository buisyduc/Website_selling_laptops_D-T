<div class="comment-item mb-3" id="comment-{{ $comment->id }}">
    <div class="d-flex">
        <div class="flex-shrink-0">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white"
                style="width: 30px; height: 30px; font-size: 15px; font-weight: bold;">
                @if ($comment->user_id && $comment->user)
                    {{ strtoupper(mb_substr($comment->user->name, 0, 1)) }}
                @elseif($comment->guest_name)
                    {{ strtoupper(mb_substr($comment->guest_name, 0, 1)) }}
                @else
                    {{ strtoupper(mb_substr('Khách', 0, 1)) }}
                @endif
            </div>
        </div>
        <div class="flex-grow-1 ms-2"> 
            <div class="comment-header">
                <strong>
                    @if ($comment->user_id && $comment->user)
                        {{ $comment->user->name }} •
                    @elseif($comment->guest_name)
                        {{ $comment->guest_name }} (Khách) •
                    @else
                        Khách •
                    @endif
                </strong>
                <span class="text-muted small ms-2">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <div class="comment-body">
                {{ $comment->content }}
            </div>
        </div>
    </div>
</div>
