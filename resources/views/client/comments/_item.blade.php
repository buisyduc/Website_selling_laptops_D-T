@if($comment)
    <div class="comment" id="comment-{{ $comment->id }}">
        <strong>{{ $comment->user_name ?? 'Không xác định' }} •</strong>
        ({{ $comment->created_at->diffForHumans() }})
        <p>{{ $comment->content }}</p>
    </div>
@else
    <div class="text-muted">Bình luận không tồn tại</div>
@endif
 