@extends('admin.index')

@section('container-fluid')
    <h2>Bình luận cho sản phẩm: {{ $product->name }}</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product->comments as $comment)
                <tr id="comment-row-{{ $comment->id }}">
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->user_name}}</td>
                    <td>{{ $comment->content }}</td>
                    <td>{{ $comment->is_active ? 'Hiển thị' : 'Đã ẩn' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.comments.toggle', $comment->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit">
                                {{ $comment->is_active ? 'Ẩn' : 'Hiển thị' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            
        </tbody>
    </table>
@endsection
