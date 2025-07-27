<div class="comment" id="comment-{{ $comment->id }}">
    <strong>{{ $comment->user->name }} •</strong>
    ({{ $comment->created_at->diffForHumans() }})
    <p>{{ $comment->content }}</p>
</div>
