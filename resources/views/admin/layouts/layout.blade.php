@include('admin.layouts.partials.head')

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <div class="top-tagbar">
            <div class="container-fluid">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-4 col-9">
                        <div class="fs-14 fw-medium">
                            <i class="bi bi-clock align-middle me-2"></i> <span id="current-time"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 d-none d-xxl-block">
                        <div class="d-flex align-items-center justify-content-center gap-3 fs-14 fw-medium">
                            <div>
                                <i class="bi bi-envelope align-middle me-2"></i> Email: tuan@gmail.com
                            </div>
                            <div>
                                <i class="bi bi-globe align-middle me-2"></i> Website: www.LaptopD&T.vn
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4 col-3">
                        <div class="d-flex align-items-center justify-content-end gap-3 fs-14">

                            <hr class="vr d-none d-lg-block">
                            <div class="dropdown topbar-head-dropdown topbar-tag-dropdown justify-content-end">
                                <button type="button"
                                    class="btn btn-topbar text-reset d-flex align-items-center gap-2 rounded-full p-2 shadow-sm"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    aria-label="Chọn ngôn ngữ" style="transition: box-shadow .2s;">
                                    <span aria-label="Tiếng Việt"
                                        style="display:inline-block; width:24px; height:24px; border-radius:50%; overflow:hidden;">
                                        <svg viewBox="0 0 29 27" preserveAspectRatio="xMidYMid slice"
                                            xmlns="http://www.w3.org/2000/svg"
                                            style="width:100%; height:100%; display:block;">
                                            <!-- nền đỏ -->
                                            <rect width="29" height="27" fill="#da251d" />
                                            <!-- sao vàng -->
                                            <polygon
                                                points="15,4 17.59,11.18 25.51,11.18 18.96,14.82 21.55,22 15,17.27 8.45,22 11.04,14.82 4.49,11.18 12.41,11.18"
                                                fill="#ff0" />
                                        </svg>
                                    </span>

                                    <span id="lang-name" class="fw-medium">Tiếng Việt</span>
                                </button>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">


                        <button type="button"
                            class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                            id="topnav-hamburger-icon" aria-label="Mở/đóng menu">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <button type="button"
                            class="btn btn-sm px-3 fs-15 user-name-text header-item d-none d-md-block"
                            data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="Tìm kiếm">
                            <span class="bi bi-search me-2"></span> Tìm kiếm Toner...
                        </button>
                    </div>


                    <div class="d-flex align-items-center">
                        @php
                            $user = auth()->user();
                            $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
                            $totalCount = $user ? $user->notifications()->count() : 0;
                            $notifications = $user ? $user->notifications()->latest()->take(10)->get() : collect();
                            $newNotifications = $notifications->whereNull('read_at');
                            $readNotifications = $notifications->whereNotNull('read_at');
                        @endphp

                        <div class="dropdown topbar-head-dropdown ms-1 header-item dropdown-hover-end"
                            id="notificationDropdown">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle"
                                id="page-header-notifications-dropdown" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                                <i class='bi bi-bell fs-18'></i>
                                <span
                                    class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"><span
                                        class="notification-badge">{{ $unreadCount }}</span><span
                                        class="visually-hidden">unread
                                        messages</span></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">

                                <div class="dropdown-head rounded-top">
                                    <div class="p-3 border-bottom border-bottom-dashed">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="mb-0 fs-16 fw-semibold"> Thông báo <span
                                                        class="badge bg-danger-subtle text-danger  fs-13 notification-badge">
                                                        {{ $totalCount }}</span></h6>
                                                <p class="fs-14 text-muted mt-1 mb-0">Bạn có <span
                                                        class="fw-semibold notification-unread">{{ $unreadCount }}</span>
                                                    tin nhắn chưa đọc</p>
                                            </div>
                                            <div class="col-auto dropdown">
                                                <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                                    class="link-secondar2 fs-15"><i
                                                        class="bi bi-three-dots-vertical"></i></a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">All Clear</a></li>
                                                    <li><a class="dropdown-item" href="#">Mark all as read</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#">Archive All</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="py-2 ps-2" id="notificationItemsTabContent">
                                    <div data-simplebar style="max-height: 300px;" class="pe-2">

                                        @forelse($newNotifications as $n)
                                            <div
                                                class="text-reset notification-item d-block dropdown-item position-relative unread-message">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3 flex-shrink-0">
                                                        <span
                                                            class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                                                            <i class="bi bi-bell"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="{{ route('admin.notifications.redirect', $n->id) }}"
                                                            class="stretched-link">
                                                            <h6 class="mt-0 fs-14 mb-2 lh-base">
                                                                {{ data_get($n->data, 'title', 'Thông báo mới') }}</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">
                                                                {{ data_get($n->data, 'message', json_encode($n->data)) }}
                                                            </p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i>
                                                                {{ $n->created_at->diffForHumans() }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse

                                        @if ($readNotifications->count())
                                            <h6
                                                class="text-overflow text-muted fs-13 my-2 text-uppercase notification-title">
                                                Đã đọc</h6>
                                            @foreach ($readNotifications as $n)
                                                <div
                                                    class="text-reset notification-item d-block dropdown-item position-relative">
                                                    <div class="d-flex">
                                                        <div class="avatar-xs me-3 flex-shrink-0">
                                                            <span
                                                                class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-16">
                                                                <i class="bi bi-bell"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <a href="{{ route('admin.notifications.redirect', $n->id) }}"
                                                                class="stretched-link">
                                                                <h6 class="mt-0 fs-14 mb-1 fw-semibold">
                                                                    {{ data_get($n->data, 'title', 'Thông báo') }}</h6>
                                                            </a>
                                                            <div class="fs-13 text-muted">
                                                                <p class="mb-1">
                                                                    {{ data_get($n->data, 'message', json_encode($n->data)) }}
                                                                </p>
                                                            </div>
                                                            <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                                <span><i class="mdi mdi-clock-outline"></i>
                                                                    {{ $n->created_at->diffForHumans() }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="notification-actions" id="notification-actions">
                                        <div class="d-flex text-muted justify-content-center align-items-center">
                                            <span class="me-2">Tổng:</span>
                                            <div id="select-content" class="text-body fw-semibold px-1">
                                                {{ $totalCount }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user topbar-head-dropdown dropdown-hover-end">
                            <button type="button" class="btn" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <svg width="40" height="40" viewBox="0 0 64 64"
                                        aria-label="Ảnh đại diện admin" xmlns="http://www.w3.org/2000/svg"
                                        style="border-radius:50%; background:#e2e8f0;">
                                        <circle cx="32" cy="32" r="32" fill="#c7d2fe" />
                                        <circle cx="32" cy="22" r="12" fill="#fff" />
                                        <path d="M16 50c0-8.837 7.163-16 16-16s16 7.163 16 16v2H16v-2z"
                                            fill="#fff" />
                                    </svg>

                                    <span class="text-start ms-xl-2">
                                        <span
                                            class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">Admin</span>
                                        <span class="d-none d-xl-block ms-1 fs-13 user-name-sub-text">Người sáng
                                            lập</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right text-muted fs-15 align-middle me-1"></i>
                                    <span class="align-middle" data-key="t-logout">Đăng xuất</span>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </header>





        <!-- removeNotificationModal -->
        <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"
                            id="NotificationModalbtn-close"></button>
                    </div>
                    <div class="modal-body p-md-5">
                        <div class="text-center">
                            <div class="text-danger">
                                <i class="bi bi-trash display-4"></i>
                            </div>
                            <div class="mt-4 fs-15">
                                <h4 class="mb-1">Bạn có chắc không?</h4>
                                <p class="text-muted mx-4 mb-0">Bạn có chắc muốn xóa thông báo này không?</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn w-sm btn-danger" id="delete-notification">Có, xóa
                                ngay!</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <div id="scrollbar">
                <div class="container-fluid">
                    <div id="two-column-menu"></div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span>Menu</span></li>

                        {{-- <li class="nav-item">
                            <a href="http://127.0.0.1:8000/admin/index" class="nav-link menu-link">
                                <i class="bi bi-speedometer2"></i> <span>Bảng điều khiển</span>
                                <span class="badge badge-pill bg-danger-subtle text-danger">Hot</span>
                            </a>
                        </li> --}}
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link menu-link"><i
                                    class="bi bi-pie-chart"></i>
                                <span>Thống kê</span></a></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarProducts" data-bs-toggle="collapse">
                                <i class="bi bi-box-seam"></i> <span>Sản phẩm</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarProducts">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="{{ route('categories') }}" class="nav-link">Danh mục</a></li>
                                    <li><a href="{{ route('categories.trashed') }}" class="nav-link">Danh mục đã
                                            xóa</a></li>
                                    <li><a href="{{ route('brands') }}" class="nav-link">Thương hiệu</a></li>
                                    <li><a href="{{ route('brands.trashed') }}" class="nav-link">Thương hiệu đã
                                            xóa</a></li>
                                    <li><a href="{{ route('product-list') }}" class="nav-link">Danh sách sản phẩm</a>
                                    </li>
                                    <li><a href="{{ route('product.trashed') }}" class="nav-link">Sản phẩm đã xóa
                                        </a></li>
                                    <li><a href="{{ route('attributes') }}" class="nav-link">Thuộc tính</a></li>
                                    <li><a href="{{ route('attributes.trashed') }}" class="nav-link">Thuộc tính đã
                                            xóa </a></li>
                                    <li><a href="{{ route('product.create') }}" class="nav-link">Tạo sản phẩm</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarOrders" data-bs-toggle="collapse">
                                <i class="bi bi-cart4"></i> <span>Đơn hàng</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarOrders">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="{{ route('admin.orders.index') }}" class="nav-link">Danh sách</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{-- <li><a href="calendar.html" class="nav-link menu-link"><i class="bi bi-calendar-week"></i>
                                <span>Lịch</span></a></li> --}}

                        {{-- <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarSellers" data-bs-toggle="collapse">
                                <i class="bi bi-binoculars"></i> <span>Người bán</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarSellers">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="sellers-list-view.html" class="nav-link">Danh sách</a></li>
                                    <li><a href="sellers-list-view.html" class="nav-link">Thuộc tính</a></li>
                                    <li><a href="sellers-grid-view.html" class="nav-link">Dạng lưới</a></li>
                                    <li><a href="seller-overview.html" class="nav-link">Tổng quan</a></li>
                                </ul>
                            </div>
                        </li> --}}

                        {{-- <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarInvoice" data-bs-toggle="collapse">
                                <i class="bi bi-archive"></i> <span>Hóa đơn</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarInvoice">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="invoices-list.html" class="nav-link">Danh sách</a></li>
                                    <li><a href="invoices-list.html" class="nav-link">Thuộc tính sản phẩm</a></li>
                                    <li><a href="invoices-details.html" class="nav-link">Chi tiết</a></li>
                                    <li><a href="invoices-create.html" class="nav-link">Tạo hóa đơn</a></li>
                                </ul>
                            </div>
                        </li> --}}

                        {{-- <li><a href="users-list.html" class="nav-link menu-link"><i
                                    class="bi bi-person-bounding-box"></i> <span>Danh sách người dùng</span></a></li> --}}

                        {{-- <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarShipping" data-bs-toggle="collapse">
                                <i class="bi bi-truck"></i> <span>Vận chuyển</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarShipping">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="shipping-list.html" class="nav-link">Danh sách vận chuyển</a></li>
                                    <li><a href="shipments.html" class="nav-link">Lô hàng</a></li>
                                </ul>
                            </div>
                        </li> --}}

                        <li><a href="{{ route('coupons-list') }}" class="nav-link menu-link"><i
                                    class="bi bi-tag"></i> <span>Mã giảm giá</span></a></li>

                        <li><a href="{{ route('admin.reviews.index') }}" class="nav-link menu-link"><i
                                    class="bi bi-star"></i>
                                <span>Đánh giá </span></a></li>

                        {{-- <li><a href="{{ route('admin.comments.index') }}" class="nav-link menu-link"><i
                                    class="bi bi-star"></i>
                                <span>Bình luận </span></a></li> --}}
                        {{-- <li><a href="brands.html" class="nav-link menu-link"><i class="bi bi-shop"></i> <span>Thương
                                    hiệu</span></a></li> --}}





                        {{-- <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarAccounts" data-bs-toggle="collapse">
                                <i class="bi bi-person-circle"></i> <span>Tài khoản</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarAccounts">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="account.html" class="nav-link">Tài khoản của tôi</a></li>
                                    <li><a href="account-settings.html" class="nav-link">Cài đặt</a></li>
                                    <li><a href="auth-signup-basic.html" class="nav-link">Đăng ký</a></li>
                                    <li><a href="auth-signin-basic.html" class="nav-link">Đăng nhập</a></li>
                                    <li><a href="auth-pass-reset-basic.html" class="nav-link">Đặt lại mật khẩu</a>
                                    </li>
                                    <li><a href="auth-pass-change-basic.html" class="nav-link">Tạo mật khẩu</a></li>
                                    <li><a href="auth-success-msg-basic.html" class="nav-link">Thông báo thành
                                            công</a></li>
                                    <li><a href="auth-twostep-basic.html" class="nav-link">Xác minh 2 bước</a></li>
                                    <li><a href="auth-logout-basic.html" class="nav-link">Đăng xuất</a></li>
                                    <li><a href="auth-404.html" class="nav-link">Lỗi 404</a></li>
                                    <li><a href="auth-500.html" class="nav-link">Lỗi 500</a></li>
                                    <li><a href="coming-soon.html" class="nav-link">Sắp ra mắt</a></li>
                                </ul>
                            </div>
                        </li> --}}

                        {{-- <li><a href="https://themesbrand.com/toner/html/components/index.html" target="_blank"
                                class="nav-link menu-link">
                                <i class="bi bi-layers"></i> <span>Thành phần</span> <span
                                    class="badge badge-pill bg-secondary">v1.0</span>
                            </a></li> 
                        
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse">
                                <i class="bi bi-share"></i> <span>Menu nhiều cấp</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarMultilevel">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="#" class="nav-link">Cấp 1.1</a></li>
                                    <li><a href="#sidebarAccount" class="nav-link" data-bs-toggle="collapse">Cấp
                                            1.2</a>
                                        <div class="collapse menu-dropdown" id="sidebarAccount">
                                            <ul class="nav nav-sm flex-column">
                                                <li><a href="#" class="nav-link">Cấp 2.1</a></li>
                                                <li><a href="#sidebarCrm" class="nav-link"
                                                        data-bs-toggle="collapse">Cấp 2.2</a>
                                                    <div class="collapse menu-dropdown" id="sidebarCrm">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li><a href="#" class="nav-link">Cấp 3.1</a></li>
                                                            <li><a href="#" class="nav-link">Cấp 3.2</a></li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>  --}}
                    </ul>
                </div>
            </div>
        </div>


        <div class="sidebar-background"></div>
    </div>
    <!-- Left Sidebar End -->
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            @yield('container-fluid')
        </div>
        <!-- End Page-content -->


    </div>

    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-info btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->



    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    @include('admin.layouts.partials.script')
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Mirrored from themesbrand.com/toner/html/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 16 Mar 2025 17:50:20 GMT -->

</html>
