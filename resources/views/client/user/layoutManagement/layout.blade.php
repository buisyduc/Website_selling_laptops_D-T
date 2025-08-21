@extends('client.layouts.layout')
@section('content')
    <style>
        .shopee-sidebar {
            background: #fff;
            border-radius: 8px;
            /* box-shadow: 0 1px 3px rgba(0,0,0,0.1); */
            overflow: hidden;
        }

        .user-profile {
            padding: 25px;
            /* border-bottom: 1px solid #f0f0f0; */
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: #1976d2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
            color: white;
            font-weight: bold;
        }

        .user-info h6 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .user-info small {
            color: #999;
            font-size: 12px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            /* border-bottom: 1px solid #f5f5f5; */
        }

        .nav-item:last-child {
            /* border-bottom: none; */
        }

        .nav-link {
            display: flex;
            align-items: center;

            color: #333;
            text-decoration: none !important;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            text-decoration: none !important;
        }

        .nav-link:focus {
            text-decoration: none !important;
        }


        .nav-link.active {
            background-color: #e3f2fd;
            color: #1976d2;
            border-right: 3px solid #1976d2;
        }

        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        .nav-link.text-danger:hover {
            background-color: #ffebee;
            color: #d32f2f;
        }

        .notification-badge {
            background: #ff5722;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
        }

        .update-badge {
            background: #4caf50;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
        }

        .special-badge {
            background: #ff9800;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
        }

        /* Submenu styles */
        .nav-submenu {
            display: none;
            padding-left: 0;
            margin: 0;
            list-style: none;
        }

        .nav-submenu.show {
            display: block;
        }

        .nav-submenu .nav-link {
            padding-left: 52px;
            font-size: 13px;
            color: #666;
        }

        .nav-submenu .nav-link:hover {
            background-color: #e9ecef;
            color: #333;
        }

        .nav-link.has-submenu {
            position: relative;
        }



        .nav-link.has-submenu.expanded::after {
            transform: rotate(180deg);
        }
    </style>

    <div class="container" style="max-width: 80vw;">
        <div class="row">
            <!-- Shopee-style Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="shopee-sidebar">
                    <!-- User Profile Section -->
                    <div class="user-profile">
                        <div class="user-avatar">
                            B
                        </div>
                        <div class="user-info">
                            @auth
                                <h6>{{ Auth::user()->name }}</h6>
                                <small>🖊️ Sửa Hồ Sơ</small>
                            @endauth
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link has-submenu" onclick="toggleSubmenu('notifications')">
                                <i class="bi bi-bell" style="color: #ff5722;"></i>
                                Thông Báo
                            </a>
                            <ul class="nav-submenu" id="notifications">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-receipt" style="color: #ff5722;"></i>
                                        Cập Nhật Đơn Hàng
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-receipt" style="color: #ff5722;"></i>
                                        Khuyến Mãi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-wallet2" style="color: #ff5722;"></i>
                                        Cập Nhật Ví
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-bag-check" style="color: #ff5722;"></i>
                                        Cập Nhật Shopee
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link has-submenu" onclick="toggleSubmenu('account')">
                                <i class="bi bi-person-circle" style="color: #1976d2;"></i>
                                Tài Khoản Của Tôi
                            </a>
                            <ul class="nav-submenu" id="account">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-person-fill" style="color: #1976d2;"></i>
                                        Hồ sơ
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-bank" style="color: #4caf50;"></i>
                                        Ngân hàng
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-geo-alt-fill" style="color: #ff5722;"></i>
                                        Địa chỉ
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-key-fill" style="color: #ff9800;"></i>
                                        Đổi mật khẩu
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-bell-fill" style="color: #9c27b0;"></i>
                                        Cài đặt thông báo
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-shield-lock-fill" style="color: #607d8b;"></i>
                                        Những thiết lập riêng tư
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="bi bi-info-circle-fill" style="color: #2196f3;"></i>
                                        Thông tin cá nhân
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-calendar-check" style="color: #1976d2;"></i>
                                Đơn Mua
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-gift" style="color: #ff9800;"></i>
                                Kho Voucher
                            </a>
                        </li>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:;" onclick="confirmLogout()"
                                class="list-group-item list-group-item-action text-danger fw-semibold py-2">
                                <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                            </a>

                        </li>


                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="bg-white rounded shadow-sm ">
                    @yield('box')
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

        // Toggle submenu function
        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const parentLink = document.querySelector(`[onclick="toggleSubmenu('${submenuId}')"]`);

            if (submenu.classList.contains('show')) {
                submenu.classList.remove('show');
                parentLink.classList.remove('expanded');
            } else {
                submenu.classList.add('show');
                parentLink.classList.add('expanded');
            }
        }
    </script>
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

@endsection
