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
    </div>
    <!-- Doanh thu theo ngày -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Doanh thu</span>
            <span id="totalRevenue">0 VNĐ</span>
        </div>
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
    @push('styles')
        <style>
            /*bo tròn thành hình tròn */
            .chartjs-render-monitor+.chartjs-legend li span,
            .chartjs-legend li span {
                border-radius: 50% !important;
                width: 10px !important;
                height: 10px !important;
            }
        </style>
        <style>
            .chart-container {
                position: relative;
                height: 250px;
                width: 100%;
                margin-bottom: 20px;
            }

            .dashboard-row {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
            }

            .dashboard-col {
                flex: 1 1 45%;
                min-width: 300px;
            }
        </style>
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let revenueChart, topProductsChart, topCustomersChart, topCategoriesChart;

                function loadDashboardData(start = '', end = '') {
                    let url = "{{ route('admin.dashboard.data') }}";

                    // Thêm query string nếu có start hoặc end
                    const params = new URLSearchParams();
                    if (start) params.append('start_date', start);
                    if (end) params.append('end_date', end);
                    if ([...params].length > 0) url += '?' + params.toString();

                    const filterBtn = document.getElementById('filterBtn');
                    filterBtn.disabled = true;
                    filterBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang tải...';

                    fetch(url, {
                            credentials: 'same-origin'
                        })
                        .then(res => res.json())
                        .then(data => {
                            // Tổng doanh thu
                            const totalRevenueEl = document.getElementById('totalRevenue');
                            if (totalRevenueEl) {
                                totalRevenueEl.textContent = new Intl.NumberFormat('vi-VN').format(data
                                    .totalRevenue || 0) + ' VNĐ';
                            }

                            // Biểu đồ doanh thu
                            const revenueData = Array.isArray(data.revenueData) ? data.revenueData : [];
                            const revenueLabels = revenueData.map(item => item.date ? new Date(item.date)
                                .toLocaleDateString('vi-VN') : '');
                            const revenueValues = revenueData.map(item => item.total || 0);

                            if (revenueChart) revenueChart.destroy();
                            revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
                                type: 'line',
                                data: {
                                    labels: revenueLabels,
                                    datasets: [{
                                        label: 'Doanh thu (VNĐ)',
                                        data: revenueValues,
                                        borderColor: 'rgba(75,192,192,1)',
                                        backgroundColor: 'rgba(75,192,192,0.2)',
                                        fill: true,
                                        tension: 0.1,
                                        pointBackgroundColor: 'rgba(75,192,192,1)',
                                        pointRadius: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        title: {
                                            display: revenueData.length === 0,
                                            text: 'Không có dữ liệu trong khoảng thời gian này',
                                            font: {
                                                size: 16
                                            }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: ctx => 'Doanh thu: ' + (ctx.parsed.y || 0)
                                                    .toLocaleString('vi-VN') + ' VNĐ'
                                            }
                                        },
                                        legend: {
                                            labels: {
                                                usePointStyle: true,
                                                pointStyle: 'circle'
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                callback: val => (val || 0).toLocaleString('vi-VN') + ' VNĐ'
                                            }
                                        }
                                    }
                                }
                            });

                            // Top sản phẩm
                            const topProducts = Array.isArray(data.topProducts) ? data.topProducts : [];
                            const productLabels = topProducts.map(p => p.product?.name || '');
                            const productQty = topProducts.map(p => p.total_qty || 0);
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
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            labels: {
                                                usePointStyle: true,
                                                pointStyle: 'circle'
                                            }
                                        }
                                    }
                                }
                            });

                            // Top khách hàng
                            const topCustomers = Array.isArray(data.topCustomers) ? data.topCustomers : [];
                            const customerLabels = topCustomers.map(c => c.user?.name || '');
                            const customerSpent = topCustomers.map(c => c.total_spent || 0);
                            if (topCustomersChart) topCustomersChart.destroy();
                            topCustomersChart = new Chart(document.getElementById('topCustomersChart'), {
                                type: 'bar',
                                data: {
                                    labels: customerLabels,
                                    datasets: [{
                                        label: 'Tổng chi tiêu (VNĐ)',
                                        data: customerSpent,
                                        backgroundColor: 'green'
                                    }]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            labels: {
                                                usePointStyle: true,
                                                pointStyle: 'circle'
                                            }
                                        }
                                    }
                                }
                            });

                            // Top danh mục
                            const topCategories = Array.isArray(data.topCategories) ? data.topCategories : [];
                            const categoryLabels = topCategories.map(cat => cat.name || '');
                            const categoryQty = topCategories.map(cat => cat.total_sold || 0);
                            if (topCategoriesChart) topCategoriesChart.destroy();
                            topCategoriesChart = new Chart(document.getElementById('topCategoriesChart'), {
                                type: 'pie',
                                data: {
                                    labels: categoryLabels,
                                    datasets: [{
                                        data: categoryQty,
                                        backgroundColor: ['red', 'blue', 'green', 'orange',
                                            'purple']
                                    }]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                usePointStyle: true,
                                                pointStyle: 'circle'
                                            }
                                        }
                                    }
                                }
                            });

                            // Đơn hàng gần đây
                            const recentOrders = Array.isArray(data.recentOrders) ? data.recentOrders : [];
                            const recentOrdersList = document.getElementById('recentOrdersList');
                            recentOrdersList.innerHTML = '';
                            recentOrders.forEach(o => {
                                let li = document.createElement('li');
                                li.classList.add('list-group-item');
                                li.textContent =
                                    `Đơn #${o.id || ''} - ${o.user?.name || ''} - ${o.total_amount || 0} VNĐ`;
                                recentOrdersList.appendChild(li);
                            });

                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi khi tải dữ liệu: ' + error.message);
                        })
                        .finally(() => {
                            filterBtn.disabled = false;
                            filterBtn.textContent = 'Lọc';
                        });
                }

                // Load lần đầu
                loadDashboardData();

                // Bắt sự kiện lọc
                document.getElementById('filterBtn').addEventListener('click', function() {
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;

                    // Gọi loadDashboardData mà không cần alert bắt buộc
                    loadDashboardData(startDate, endDate);
                });
            });
        </script>
    @endpush
@endsection
