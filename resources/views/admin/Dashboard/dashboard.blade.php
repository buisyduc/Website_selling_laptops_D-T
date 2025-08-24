@extends('admin.index') @section('container-fluid')
    <div class="container py-4">
        <h1 class="mb-4">📊 Thống kê</h1> <!-- Hàng 1: Tổng quan -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card bg-primary text-white">
                    <div class="card-icon">💰</div>
                    <h5>Tổng doanh thu</h5>
                    <h3>{{ number_format($totalRevenue) }}₫</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card bg-success text-white">
                    <div class="card-icon">🛒</div>
                    <h5>Tổng đơn hàng</h5>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card bg-warning text-white">
                    <div class="card-icon">📦</div>
                    <h5>Sản phẩm đã bán</h5>
                    <h3>{{ $totalProductsSold }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card bg-info text-white">
                    <div class="card-icon">📊</div>
                    <h5>Doanh thu trung bình / đơn</h5>
                    <h3>{{ number_format($avgOrderValue) }}₫</h3>
                </div>
            </div>
        </div> <!-- Hàng 2: Khách hàng & trạng thái -->
        <div class="row g-4 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="stat-card bg-secondary text-white">
                    <div class="card-icon">🆕</div>
                    <h5>Khách hàng mới</h5>
                    <h3>{{ $newCustomers }}</h3>
                </div>
            </div>
            <div class="col-md-8 col-sm-6">
                <div class="stat-card bg-light text-dark">
                    <h5>Đơn hàng theo trạng thái</h5>
                    <ul class="status-list">
                        @foreach ($ordersByStatus as $status => $count)
                            <li>{{ ucfirst($status) }}: {{ $count }}</li>
                            @endforeach
                    </ul>
                </div>
            </div>
        </div> <!-- Hàng 3: Top sản phẩm / khách hàng -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="stat-card bg-light text-dark">
                    <h5>Top sản phẩm bán chạy</h5>
                    <ul>
                        @foreach ($topProducts as $p)
                            <li>{{ $p->product->name ?? '' }}: {{ $p->total_qty }}</li>
                            @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card bg-light text-dark">
                    <h5>Top khách hàng</h5>
                    <ul>
                        @foreach ($topCustomers as $c)
                            <li>{{ $c->user->name ?? '' }}: {{ number_format($c->total_spent) }}₫</li>
                            @endforeach
                    </ul>
                </div>
            </div>
        </div> <!-- Hàng 4: Danh mục / đơn hàng gần đây -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="stat-card bg-light text-dark">
                    <h5>Danh mục bán chạy</h5>
                    <ul>
                        @foreach ($topCategories as $cat)
                            <li>{{ $cat->name }}: {{ $cat->total_sold }}</li>
                            @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card bg-light text-dark">
                    <h5>Đơn hàng gần đây</h5>
                    <ul class="list-group list-group-flush">
                        @foreach ($recentOrders as $order)
                            <li class="list-group-item"> Đơn #{{ $order->id }} - {{ $order->user->name ?? '' }} -
                                {{ number_format($order->total_amount) }}₫ </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endsection @push('styles')
    <style>
        .stat-card {
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h5 {
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .stat-card h3 {
            font-size: 22px;
            font-weight: bold;
        }

        .card-icon {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .status-list li {
            margin-bottom: 5px;
        }

        .list-group-item {
            display: flex;
            justify-content: space-between;
        }

        .bg-primary {
            background-color: #0d6efd !important;
        }

        .bg-success {
            background-color: #198754 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .bg-info {
            background-color: #0dcaf0 !important;
        }

        .bg-secondary {
            background-color: #6c757d !important;
        }
    </style>
@endpush
