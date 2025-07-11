@extends('admin.index')

@section('container-fluid')
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold">Quản lý đơn hàng</h2>

    <div class="table-responsive shadow border rounded">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Liên hệ</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $index => $order)
                <tr class="text-center">
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start">{{ $order->name }}</td>
                    <td>
                        <div>{{ $order->email }}</div>
                        <div>{{ $order->phone }}</div>
                    </td>
                    <td>{{ number_format($order->total_amount) }}đ</td>
                    <td>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'confirmed' => 'primary',
                                'processing' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $order->payment_method === 'momo' ? 'success' : 'secondary' }}">
                            {{ strtoupper($order->payment_method) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-warning">Sửa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection