@extends('layouts.admin')

@section('content')
<h2>Danh sách hoàn hàng</h2>
<table>
    <thead>
        <tr>
            <th>Mã</th>
            <th>Khách hàng</th>
            <th>Đơn hàng</th>
            <th>Lý do</th>
            <th>Ảnh</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($returns as $return)
        <tr>
            <td>#{{ $return->id }}</td>
            <td>{{ $return->user->name }}</td>
            <td>#{{ $return->order->id }}</td>
            <td>{{ $return->reason }}</td>
            <td>
                @if($return->image)
                    <img src="{{ asset('storage/' . $return->image) }}" width="80">
                @endif
            </td>
            <td>{{ ucfirst($return->status) }}</td>
            <td>
                <form action="{{ route('admin.returns.update', $return->id) }}" method="POST">
                    @csrf
                    <select name="status">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="refunded">Refunded</option>
                    </select>
                    <button type="submit">Cập nhật</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
