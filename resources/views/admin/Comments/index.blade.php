@extends('admin.index')

@section('container-fluid')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quản lý bình luận</h3>
        </div>
        <div class="card-body">
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
                                <a href="{{ route('admin.comments.show', $product->id) }}"
                                    style="text-decoration: none; color: black;">Xem chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
