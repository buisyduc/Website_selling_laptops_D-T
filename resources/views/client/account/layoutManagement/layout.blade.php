<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thành Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light py-4">

    <div class="container" style="max-width: 80vw;">
        <!-- Card Tổng thể -->
        <div class="bg-white rounded shadow-sm p-4">

            <!-- Header -->
            <div class="row align-items-center mb-4">
                <!-- User Info -->
                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill fs-4"></i>
                        </div>
                        <div>
                            @auth
                                <h5 class="mb-1 fw-semibold">{{ Auth::user()->name }}</h5>
                                <small class="text-muted">Thành viên đăng nhập</small>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="col-lg-6 col-md-6 mb-3 mb-lg-0">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-light p-3 rounded text-center border h-100">
                                <i class="bi bi-cart3 text-primary fs-3 mb-2"></i>
                                <h6 class="fw-bold mb-0">0</h6>
                                <small class="text-muted">Đơn hàng đã mua</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded text-center border h-100">
                                <i class="bi bi-currency-dollar text-success fs-3 mb-2"></i>
                                <h6 class="fw-bold mb-1">0đ</h6>
                                <small class="text-muted d-block">Tổng chi tiêu</small>
                                <small class="text-muted">Còn 3.000.000đ để lên S-NEW</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Brand -->
                <div class="col-lg-2 text-end">
                    <div class="text-muted small mb-2">Kênh thành viên</div>
                    <select class="form-select form-select-sm text-white bg-primary border-0">
                        <option selected>LAPTOP D&T</option>
                    </select>
                </div>
            </div>

            <!-- Content -->
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="list-group shadow-sm rounded">
                        <a href="#" class="list-group-item list-group-item-action active py-2">
                            <i class="bi bi-house me-2"></i> Tổng quan
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2">
                            <i class="bi bi-list-ul me-2"></i> Lịch sử mua hàng
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2">
                            <i class="bi bi-search me-2"></i> Tra cứu bảo hành
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2">
                            <i class="bi bi-heart me-2"></i> Ưu đãi thành viên
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2">
                            <i class="bi bi-mortarboard me-2"></i> Ưu đãi sinh viên - giáo viên
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2">
                            <i class="bi bi-gear me-2"></i> Thông tin tài khoản
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2">
                            <i class="bi bi-geo-alt me-2"></i> Tìm cửa hàng
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2">
                            <i class="bi bi-shield-check me-2"></i> Chính sách bảo hành
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2">
                            <i class="bi bi-chat-dots me-2"></i> Góp ý - Phản hồi
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2">
                            <i class="bi bi-file-text me-2"></i> Điều khoản sử dụng
                        </a>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="javascript:;" onclick="confirmLogout()"
                           class="list-group-item list-group-item-action text-danger fw-semibold py-2">
                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                        </a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="bg-white p-4 rounded shadow-sm">
                        <h5 class="fw-bold mb-3">Tổng Quan Tài Khoản</h5>
                        <hr>
                        @yield('content')
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SweetAlert logout -->
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Bạn muốn đăng xuất?',
                text: "Bạn sẽ cần đăng nhập lại để tiếp tục.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Đăng xuất',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>

</body>
</html>
