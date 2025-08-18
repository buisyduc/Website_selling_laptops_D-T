@extends('admin.index')

@section('container-fluid')
    <div class="container">
        <h1>📊 Thống kê</h1>

        <!-- Bộ lọc ngày -->
        <div class="row mb-4" id="filterForm">
            <div class="col-md-3">
                <label>Từ ngày</label>
                <input type="date" id="start_date" class="form-control" value="{{ now()->startOfMonth()->toDateString() }}">
            </div>
            <div class="col-md-3">
                <label>Đến ngày</label>
                <input type="date" id="end_date" class="form-control" value="{{ now()->endOfMonth()->toDateString() }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-primary w-100" id="filterBtn">Lọc</button>
            </div>
        </div>

        <!-- Doanh thu theo ngày -->
        <div class="card mb-4">
            <div class="card-header">Doanh thu</div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Top sản phẩm -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Top sản phẩm bán chạy</div>
                    <div class="card-body">
                        <canvas id="topProductsChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <!-- Top khách hàng -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Top khách hàng mua nhiều</div>
                    <div class="card-body">
                        <canvas id="topCustomersChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Danh mục bán chạy -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Danh mục bán chạy</div>
                    <div class="card-body">
                        <canvas id="topCategoriesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <!-- Đơn hàng gần đây -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Đơn hàng gần đây</div>
                    <div class="card-body">
                        <ul id="recentOrdersList" class="list-group list-group-flush"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        {{-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                let revenueChart, topProductsChart, topCustomersChart, topCategoriesChart;

                // Hàm load dữ liệu
                function loadDashboardData(start = '', end = '') {
                    let url = "{{ route('admin.dashboard.data') }}";
                    if (start && end) {
                        url += `?start_date=${start}&end_date=${end}`;
                    }

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            // Doanh thu
                            const revenueLabels = data.revenueData.map(item => item.date);
                            const revenueTotals = data.revenueData.map(item => item.total);

                            if (revenueChart) revenueChart.destroy();
                            revenueChart = new Chart(document.getElementById('revenueChart'), {
                                type: 'line',
                                data: {
                                    labels: revenueLabels,
                                    datasets: [{
                                        label: 'Doanh thu',
                                        data: revenueTotals,
                                        borderColor: 'blue',
                                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                                        fill: true
                                    }]
                                }
                            });

                            // Top sản phẩm
                            const productLabels = data.topProducts.map(p => p.product.name);
                            const productQty = data.topProducts.map(p => p.total_qty);

                            if (topProductsChart) topProductsChart.destroy();
                            topProductsChart = new Chart(document.getElementById('topProductsChart'), {
                                type: 'bar',
                                data: {
                                    labels: productLabels,
                                    datasets: [{
                                        label: 'Số lượng bán',
                                        data: productQty,
                                        backgroundColor: 'orange'
                                    }]
                                }
                            });

                            // Top khách hàng
                            const customerLabels = data.topCustomers.map(c => c.user.name);
                            const customerSpent = data.topCustomers.map(c => c.total_spent);

                            if (topCustomersChart) topCustomersChart.destroy();
                            topCustomersChart = new Chart(document.getElementById('topCustomersChart'), {
                                type: 'bar',
                                data: {
                                    labels: customerLabels,
                                    datasets: [{
                                        label: 'Tổng chi tiêu (VND)',
                                        data: customerSpent,
                                        backgroundColor: 'green'
                                    }]
                                }
                            });

                            // Danh mục bán chạy
                            const categoryLabels = data.topCategories.map(cat => cat.name);
                            const categoryQty = data.topCategories.map(cat => cat.total_sold);

                            if (topCategoriesChart) topCategoriesChart.destroy();
                            topCategoriesChart = new Chart(document.getElementById('topCategoriesChart'), {
                                type: 'pie',
                                data: {
                                    labels: categoryLabels,
                                    datasets: [{
                                        data: categoryQty,
                                        backgroundColor: ['red', 'blue', 'green', 'orange',
                                            'purple'
                                        ]
                                    }]
                                }
                            });

                            // Đơn hàng gần đây
                            const recentOrdersList = document.getElementById('recentOrdersList');
                            recentOrdersList.innerHTML = "";
                            data.recentOrders.forEach(o => {
                                let li = document.createElement('li');
                                li.classList.add('list-group-item');
                                li.textContent = `Đơn #${o.id} - ${o.user.name} - ${o.total_amount} VND`;
                                recentOrdersList.appendChild(li);
                            });
                        });
                }

                // Lần đầu load
                loadDashboardData();

                // Bắt sự kiện lọc
                document.getElementById('filterBtn').addEventListener('click', function() {
                    const start = document.getElementById('start_date').value;
                    const end = document.getElementById('end_date').value;
                    loadDashboardData(start, end);
                });
            });
        </script> --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let revenueChart, topProductsChart, topCustomersChart, topCategoriesChart;

                // Hàm load dữ liệu
                function loadDashboardData(start = '', end = '') {
                    let url = "{{ route('admin.dashboard.data') }}";
                    if (start && end) {
                        url += `?start_date=${start}&end_date=${end}`;
                    }

                    // Hiển thị trạng thái loading
                    const filterBtn = document.getElementById('filterBtn');
                    filterBtn.disabled = true;
                    filterBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang tải...';

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Dữ liệu nhận được:', data); // Kiểm tra dữ liệu trong console

                            // 1. Xử lý biểu đồ doanh thu
                            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                            const revenueLabels = data.revenueData.map(item => {
                                // Format lại ngày tháng nếu cần
                                return new Date(item.date).toLocaleDateString('vi-VN');
                            });
                            const revenueValues = data.revenueData.map(item => item.total);

                            if (revenueChart) revenueChart.destroy();

                            revenueChart = new Chart(revenueCtx, {
                                type: 'line',
                                data: {
                                    labels: revenueLabels,
                                    datasets: [{
                                        label: 'Doanh thu (VNĐ)',
                                        data: revenueValues,
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderWidth: 2,
                                        tension: 0.1,
                                        fill: true,
                                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                                        pointRadius: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                callback: function(value) {
                                                    return value.toLocaleString('vi-VN') + ' VNĐ';
                                                }
                                            }
                                        }
                                    },
                                    plugins: {
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    return 'Doanh thu: ' + context.parsed.y
                                                        .toLocaleString('vi-VN') + ' VNĐ';
                                                }
                                            }
                                        }
                                    }
                                }
                            });

                            // Top sản phẩm
                            const productLabels = data.topProducts.map(p => p.product.name);
                            const productQty = data.topProducts.map(p => p.total_qty);

                            if (topProductsChart) topProductsChart.destroy();
                            topProductsChart = new Chart(document.getElementById('topProductsChart'), {
                                type: 'bar',
                                data: {
                                    labels: productLabels,
                                    datasets: [{
                                        label: 'Số lượng bán',
                                        data: productQty,
                                        backgroundColor: 'orange'
                                    }]
                                }
                            });

                            // Top khách hàng
                            const customerLabels = data.topCustomers.map(c => c.user.name);
                            const customerSpent = data.topCustomers.map(c => c.total_spent);

                            if (topCustomersChart) topCustomersChart.destroy();
                            topCustomersChart = new Chart(document.getElementById('topCustomersChart'), {
                                type: 'bar',
                                data: {
                                    labels: customerLabels,
                                    datasets: [{
                                        label: 'Tổng chi tiêu (VND)',
                                        data: customerSpent,
                                        backgroundColor: 'green'
                                    }]
                                }
                            });

                            // Danh mục bán chạy
                            const categoryLabels = data.topCategories.map(cat => cat.name);
                            const categoryQty = data.topCategories.map(cat => cat.total_sold);

                            if (topCategoriesChart) topCategoriesChart.destroy();
                            topCategoriesChart = new Chart(document.getElementById('topCategoriesChart'), {
                                type: 'pie',
                                data: {
                                    labels: categoryLabels,
                                    datasets: [{
                                        data: categoryQty,
                                        backgroundColor: ['red', 'blue', 'green', 'orange',
                                            'purple'
                                        ]
                                    }]
                                }
                            });

                            // Đơn hàng gần đây
                            const recentOrdersList = document.getElementById('recentOrdersList');
                            recentOrdersList.innerHTML = "";
                            data.recentOrders.forEach(o => {
                                let li = document.createElement('li');
                                li.classList.add('list-group-item');
                                li.textContent = `Đơn #${o.id} - ${o.user.name} - ${o.total_amount} VND`;
                                recentOrdersList.appendChild(li);
                            });

                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi khi tải dữ liệu: ' + error.message);
                        })
                        .finally(() => {
                            // Khôi phục trạng thái nút lọc
                            filterBtn.disabled = false;
                            filterBtn.textContent = 'Lọc';
                        });
                }

                // Lần đầu load
                loadDashboardData();

                // Bắt sự kiện lọc
                document.getElementById('filterBtn').addEventListener('click', function() {
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;

                    if (!startDate || !endDate) {
                        alert('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc');
                        return;
                    }

                    loadDashboardData(startDate, endDate);
                });
            });
        </script>
    @endpush
@endsection
