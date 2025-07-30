@extends('admin.index')

@section('container-fluid')
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th style="text-align: center;">Số lượng bình luận</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td style="text-align: center;">{{ $product->comments_count }}</td>
                    <td>
                        <a href="{{ route('admin.comments.show', $product->id) }}" style="text-decoration: none; color: black;">Xem chi tiết</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection