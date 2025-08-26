@extends('admin.index')

@section('container-fluid')
    <div class="container py-3">
        <h2 class="mb-3 fw-bold text-dark">📊 Thống kê tổng quan</h2>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-2 mb-3 align-items-center">
            <div class="col-auto">
                <label for="from" class="col-form-label fw-bold">Từ ngày:</label>
            </div>
            <div class="col-auto">
                <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
            </div>

            <div class="col-auto">
                <label for="to" class="col-form-label fw-bold">Đến ngày:</label>
            </div>
            <div class="col-auto">
                <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-dark">Lọc</button>
            </div>
        </form>

        <div class="row g-3">

            <div class="row g-3 mt-2">
                <!-- Target vs Doanh thu -->
                <div class="col-md-12">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-header bg-dark text-white py-2 small">🎯 Doanh thu vs Target</div>
                        <div class="card-body p-2">
                            <canvas id="targetChart" height="160"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Doanh thu theo tháng -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-dark text-white py-2 small">💰 Doanh thu theo tháng</div>
                    <div class="card-body p-2">
                        <canvas id="revenueChart" height="140"></canvas>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng theo trạng thái -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-dark text-white py-2 small">📦 Đơn hàng theo trạng thái</div>
                    <div class="card-body p-2">
                        <canvas id="orderStatusChart" height="140"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <!-- Top sản phẩm bán chạy -->
            <div class="col-md-12">
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-dark text-white py-2 small">🔥 Top sản phẩm bán chạy</div>
                    <div class="card-body p-2">
                        <canvas id="topProductsChart" height="160"></canvas>
                    </div>
                </div>
            </div>
        </div>



        <div class="row g-3 mt-2">
            <!-- Khách hàng theo tháng -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-dark text-white py-2 small">👥 Khách hàng theo tháng</div>
                    <div class="card-body p-2">
                        <canvas id="customerChart" height="140"></canvas>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng theo tháng -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-dark text-white py-2 small">📦 Đơn hàng theo tháng</div>
                    <div class="card-body p-2">
                        <canvas id="orderMonthChart" height="140"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const months = {!! json_encode($months) !!};

        // Doanh thu vs Target
        new Chart(document.getElementById('targetChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                        label: 'Doanh thu',
                        data: {!! json_encode($revenuesData) !!},
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Target',
                        data: {!! json_encode($targets) !!},
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: false,
                        borderDash: [5, 5],
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Khách hàng theo tháng
        new Chart(document.getElementById('customerChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Khách hàng mới',
                    data: {!! json_encode($customersData) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Đơn hàng theo tháng
        new Chart(document.getElementById('orderMonthChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Đơn hàng',
                    data: {!! json_encode($ordersData) !!},
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Doanh thu theo tháng (chỉ vẽ 1 lần duy nhất)
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenuesData) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Đơn hàng theo trạng thái
        new Chart(document.getElementById('orderStatusChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode(array_keys($ordersByStatus)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($ordersByStatus)) !!},
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Top sản phẩm bán chạy
        new Chart(document.getElementById('topProductsChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($topProducts->pluck('name')) !!},
                datasets: [{
                    label: 'Số lượng bán',
                    data: {!! json_encode($topProducts->pluck('total_qty')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // cho dễ đọc
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endsection
