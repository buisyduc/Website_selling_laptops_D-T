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
                            <a href="#!" class="text-reset fw-normal d-none d-lg-block">
                                Số dư: <span class="fw-semibold">0₫</span>
                            </a>
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


                                <div class="dropdown-menu dropdown-menu-end">

                                    <!-- item-->
                                    <a href="javascript:void(0);"
                                        class="dropdown-item notify-item language d-flex align-items-center py-2 gap-2"
                                        data-lang="vi" title="Tiếng Việt" aria-label="Tiếng Việt">
                                        <span aria-label="Tiếng Việt"
                                            style="display:inline-block; width:24px; height:24px; border-radius:50%; overflow:hidden;">
                                            <svg viewBox="0 0 29 27" preserveAspectRatio="xMidYMid slice"
                                                xmlns="http://www.w3.org/2000/svg"
                                                style="width:100%; height:100%; display:block;">
                                                <rect width="29" height="27" fill="#da251d" />
                                                <polygon
                                                    points="15,4 17.59,11.18 25.51,11.18 18.96,14.82 21.55,22 15,17.27 8.45,22 11.04,14.82 4.49,11.18 12.41,11.18"
                                                    fill="#ff0" />
                                            </svg>
                                        </span>
                                        <span class="align-middle">Tiếng Việt</span>
                                    </a>


                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item language"
                                        data-lang="en" title="English">
                                        <img src="https://themesbrand.com/toner/html/assets/images/flags/us.svg"
                                            alt="user-image" class="me-2 rounded-circle" height="18">
                                        <span class="align-middle">Tiếng Anh</span>
                                    </a>


                                </div>
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
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="" class="logo logo-dark" aria-label="Trang chủ">
                                <span class="logo-sm">
                                    <img src="{{ asset('admin/images/logo-sm.png') }}" alt="Logo nhỏ" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('admin/images/logo-dark.png') }}" alt="Logo tối" height="25">
                                </span>
                            </a>

                            <a href="index.html" class="logo logo-light" aria-label="Trang chủ (phiên bản sáng)">
                                <span class="logo-sm">
                                    <img src="{{ asset('admin/images/logo-sm.png') }}" alt="Logo nhỏ" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('admin/images/logo-light.png') }}" alt="Logo sáng"
                                        height="25">
                                </span>
                            </a>
                        </div>

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

                        <div class="d-md-none topbar-head-dropdown header-item">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle"
                                id="page-header-search-dropdown" data-bs-toggle="modal" data-bs-target="#searchModal"
                                aria-label="Tìm kiếm">
                                <i class="bi bi-search fs-16"></i>
                            </button>
                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item dropdown-hover-end">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                aria-label="Thương hiệu hàng đầu">
                                <i class='bi bi-grid fs-18'></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                                <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fw-semibold fs-15">Thương hiệu nổi bật</h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="brands.html" class="btn btn-sm btn-soft-primary">Xem tất cả
                                                thương hiệu
                                                <i class="ri-arrow-right-s-line align-middle"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2">
                                    <div class="row g-0">
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="../assets/images/brands/img-2.png" alt="Thương hiệu 1">
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="../assets/images/brands/img-3.png" alt="Thương hiệu 2">
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="../assets/images/brands/img-13.png" alt="Thương hiệu 3">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row g-0">
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="../assets/images/brands/img-5.png" alt="Thương hiệu 4">
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="../assets/images/brands/img-6.png" alt="Thương hiệu 5">
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="#!">
                                                <img src="../assets/images/brands/img-4.png" alt="Thương hiệu 6">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item dropdown-hover-end">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle"
                                id="page-header-cart-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                aria-haspopup="true" aria-expanded="false" aria-label="Giỏ hàng">
                                <i class='bi bi-bag fs-18'></i>
                                <span
                                    class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info">5</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart"
                                aria-labelledby="page-header-cart-dropdown">
                                <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fs-16 fw-semibold">Giỏ hàng của tôi</h6>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge bg-info-subtle text-info fs-13"><span
                                                    class="cartitem-badge">7</span> sản phẩm</span>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 300px;">
                                    <div class="p-2">
                                        <div class="text-center empty-cart" id="empty-cart">
                                            <div class="avatar-md mx-auto my-3">
                                                <div
                                                    class="avatar-title bg-info-subtle text-info fs-36 rounded-circle">
                                                    <i class='bx bx-cart'></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-3">Giỏ hàng đang trống!</h5>
                                            <a href="apps-ecommerce-products.html"
                                                class="btn btn-success w-md mb-3">Mua ngay</a>
                                        </div>

                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="../assets/images/products/img-1.png"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="sản phẩm">
                                                <div class="flex-grow-1">
                                                    <h6 class="mt-0 mb-2 fs-15">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">Áo thun thương
                                                            hiệu</a>
                                                    </h6>
                                                    <p class="mb-0 fs-13 text-muted">
                                                        Số lượng: <span>10 x $32</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span
                                                            class="cart-item-price">320</span></h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-danger remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="../assets/images/products/img-2.png"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="sản phẩm">
                                                <div class="flex-grow-1">
                                                    <h6 class="mt-0 mb-2 fs-15">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">Ghế Bentwood</a>
                                                    </h6>
                                                    <p class="mb-0 fs-13 text-muted">
                                                        Số lượng: <span>5 x $18</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span class="cart-item-price">89</span>
                                                    </h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-danger remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="../assets/images/products/img-3.png"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="sản phẩm">
                                                <div class="flex-grow-1">
                                                    <h6 class="mt-0 mb-2 fs-15">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">
                                                            Cốc giấy Borosil</a>
                                                    </h6>
                                                    <p class="mb-0 fs-13 text-muted">
                                                        Số lượng: <span>3 x $250</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span
                                                            class="cart-item-price">750</span></h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-danger remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="../assets/images/products/img-6.png"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="sản phẩm">
                                                <div class="flex-grow-1">
                                                    <h6 class="mt-0 mb-2 fs-15">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">Áo thun xám phong
                                                            cách</a>
                                                    </h6>
                                                    <p class="mb-0 fs-13 text-muted">
                                                        Số lượng: <span>1 x $1250</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span
                                                            class="cart-item-price">1250</span></h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-danger remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="../assets/images/products/img-5.png"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="sản phẩm">
                                                <div class="flex-grow-1">
                                                    <h6 class="mt-0 mb-2 fs-15">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">Mũ bảo
                                                            hiểm Stillbird</a>
                                                    </h6>
                                                    <p class="mb-0 fs-13 text-muted">
                                                        Số lượng: <span>2 x $495</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span
                                                            class="cart-item-price">990</span></h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-danger remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border"
                                    id="checkout-elem">
                                    <div class="d-flex justify-content-between align-items-center pb-3">
                                        <h5 class="m-0 text-muted">Tổng:</h5>
                                        <div class="px-2">
                                            <h5 class="m-0" id="cart-item-total">$1258.58</h5>
                                        </div>
                                    </div>

                                    <a href="apps-ecommerce-checkout.html" class="btn btn-success text-center w-100">
                                        Thanh toán
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle"
                                data-toggle="fullscreen" aria-label="Toàn màn hình">
                                <i class='bi bi-arrows-fullscreen fs-16'></i>
                            </button>
                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item dropdown-hover-end">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                aria-label="Chế độ sáng/tối">
                                <i class="bi bi-sun align-middle fs-20"></i>
                            </button>
                            <div class="dropdown-menu p-2 dropdown-menu-end" id="light-dark-mode">
                                <a href="#!" class="dropdown-item" data-mode="light"><i
                                        class="bi bi-sun align-middle me-2"></i> Mặc định (chế độ sáng)</a>
                                <a href="#!" class="dropdown-item" data-mode="dark"><i
                                        class="bi bi-moon align-middle me-2"></i> Tối</a>
                                <a href="#!" class="dropdown-item" data-mode="auto"><i
                                        class="bi bi-moon-stars align-middle me-2"></i> Tự động (theo hệ thống)</a>
                            </div>
                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item dropdown-hover-end"
                            id="notificationDropdown">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle"
                                id="page-header-notifications-dropdown" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false"
                                aria-label="Thông báo">
                                <i class='bi bi-bell fs-18'></i>
                                <span
                                    class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"><span
                                        class="notification-badge">4</span><span class="visually-hidden">tin nhắn chưa
                                        đọc</span></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">

                                <div class="dropdown-head rounded-top">
                                    <div class="p-3 border-bottom border-bottom-dashed">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="mb-0 fs-16 fw-semibold">Thông báo <span
                                                        class="badge bg-danger-subtle text-danger fs-13 notification-badge">4</span>
                                                </h6>
                                                <p class="fs-14 text-muted mt-1 mb-0">Bạn có <span
                                                        class="fw-semibold notification-unread">3</span> tin chưa đọc
                                                </p>
                                            </div>
                                            <div class="col-auto dropdown">
                                                <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                                    class="link-secondar2 fs-15"><i
                                                        class="bi bi-three-dots-vertical"></i></a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">Xóa tất cả</a></li>
                                                    <li><a class="dropdown-item" href="#">Đánh dấu đã đọc</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#">Lưu trữ tất cả</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="py-2 ps-2" id="notificationItemsTabContent">
                                    <div data-simplebar style="max-height: 300px;" class="pe-2">
                                        <h6
                                            class="text-overflow text-muted fs-13 my-2 text-uppercase notification-title">
                                            Mới</h6>
                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative unread-message">
                                            <div class="d-flex">
                                                <div class="avatar-xs me-3 flex-shrink-0">
                                                    <span
                                                        class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                                                        <i class="bx bx-badge-check"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <a href="#!" class="stretched-link">
                                                        <h6 class="mt-0 fs-14 mb-2 lh-base">Phần thưởng tối ưu hóa đồ
                                                            họa <b>Elite</b> của bạn đã sẵn sàng!</h6>
                                                    </a>
                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                        <span><i class="mdi mdi-clock-outline"></i> 30 giây
                                                            trước</span>
                                                    </p>
                                                </div>
                                                <div class="px-2 fs-15">
                                                    <div class="form-check notification-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="" id="all-notification-check01">
                                                        <label class="form-check-label"
                                                            for="all-notification-check01"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative unread-message">
                                            <div class="d-flex">
                                                <div class="position-relative me-3 flex-shrink-0">
                                                    <img src="../assets/images/users/avatar-2.jpg"
                                                        class="rounded-circle avatar-xs" alt="ảnh người dùng">
                                                    <span
                                                        class="active-badge position-absolute start-100 translate-middle p-1 bg-success rounded-circle">
                                                        <span class="visually-hidden">Cảnh báo mới</span>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <a href="#!" class="stretched-link">
                                                        <h6 class="mt-0 mb-1 fs-14 fw-semibold">Angela Bernier</h6>
                                                    </a>
                                                    <div class="fs-13 text-muted">
                                                        <p class="mb-1">Đã trả lời bình luận của bạn về biểu đồ dự
                                                            báo dòng tiền 🔔.</p>
                                                    </div>
                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                        <span><i class="mdi mdi-clock-outline"></i> 48 phút
                                                            trước</span>
                                                    </p>
                                                </div>
                                                <div class="px-2 fs-15">
                                                    <div class="form-check notification-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="" id="all-notification-check02">
                                                        <label class="form-check-label"
                                                            for="all-notification-check02"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative unread-message">
                                            <div class="d-flex">
                                                <div class="avatar-xs me-3 flex-shrink-0">
                                                    <span
                                                        class="avatar-title bg-danger-subtle text-danger rounded-circle fs-16">
                                                        <i class='bx bx-message-square-dots'></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <a href="#!" class="stretched-link">
                                                        <h6 class="mt-0 mb-2 fs-14 lh-base">Bạn đã nhận được <b
                                                                class="text-success">20</b> tin nhắn mới trong cuộc trò
                                                            chuyện</h6>
                                                    </a>
                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                        <span><i class="mdi mdi-clock-outline"></i> 2 giờ trước</span>
                                                    </p>
                                                </div>
                                                <div class="px-2 fs-15">
                                                    <div class="form-check notification-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="" id="all-notification-check03">
                                                        <label class="form-check-label"
                                                            for="all-notification-check03"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h6
                                            class="text-overflow text-muted fs-13 my-2 text-uppercase notification-title">
                                            Đã đọc trước</h6>

                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative">
                                            <div class="d-flex">
                                                <div class="position-relative me-3 flex-shrink-0">
                                                    <img src="../assets/images/users/avatar-8.jpg"
                                                        class="rounded-circle avatar-xs" alt="ảnh người dùng">
                                                    <span
                                                        class="active-badge position-absolute start-100 translate-middle p-1 bg-warning rounded-circle">
                                                        <span class="visually-hidden">Cảnh báo mới</span>
                                                    </span>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <a href="#!" class="stretched-link">
                                                        <h6 class="mt-0 mb-1 fs-14 fw-semibold">Maureen Gibson</h6>
                                                    </a>
                                                    <div class="fs-13 text-muted">
                                                        <p class="mb-1">Chúng tôi đã nói về một dự án trên LinkedIn.
                                                        </p>
                                                    </div>
                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                        <span><i class="mdi mdi-clock-outline"></i> 4 giờ trước</span>
                                                    </p>
                                                </div>
                                                <div class="px-2 fs-15">
                                                    <div class="form-check notification-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="" id="all-notification-check04">
                                                        <label class="form-check-label"
                                                            for="all-notification-check04"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="notification-actions" id="notification-actions">
                                        <div class="d-flex text-muted justify-content-center align-items-center">
                                            Chọn <div id="select-content" class="text-body fw-semibold px-1">0</div>
                                            kết quả <button type="button" class="btn btn-link link-danger p-0 ms-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#removeNotificationModal">Xóa</button>
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
                                <!-- item-->
                                <h6 class="dropdown-header">Chào mừng Admin!</h6>
                                <a class="dropdown-item" href="account.html"><i
                                        class="bi bi-person-circle text-muted fs-15 align-middle me-1"></i> <span
                                        class="align-middle">Hồ sơ</span></a>
                                <a class="dropdown-item" href="calendar.html"><i
                                        class="bi bi-cart4 text-muted fs-15 align-middle me-1"></i> <span
                                        class="align-middle">Theo dõi đơn hàng</span></a>
                                <a class="dropdown-item" href="product-list.html"><i
                                        class="bi bi-box-seam text-muted fs-15 align-middle me-1"></i> <span
                                        class="align-middle">Sản phẩm</span></a>
                                <a class="dropdown-item"
                                    href="https://themesbrand.com/toner/html/frontend/index.html"><span
                                        class="badge bg-success-subtle text-success float-end ms-2">Mới</span><i
                                        class="bi bi-cassette text-muted fs-15 align-middle me-1"></i> <span
                                        class="align-middle">Giao diện trước</span></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="account-settings.html"><i
                                        class="bi bi-gear text-muted fs-15 align-middle me-1"></i> <span
                                        class="align-middle">Cài đặt</span></a>
                                <a class="dropdown-item" href="auth-logout-basic.html"><i
                                        class="bi bi-box-arrow-right text-muted fs-15 align-middle me-1"></i> <span
                                        class="align-middle" data-key="t-logout">Đăng xuất</span></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>


        <!-- Modal -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded">
                    <div class="modal-header p-3">
                        <div class="position-relative w-100">
                            <input type="text" class="form-control form-control-lg border-2"
                                placeholder="Tìm kiếm..." autocomplete="off" id="search-options" value="">
                            <span class="bi bi-search search-widget-icon fs-17"></span>
                            <a href="javascript:void(0);"
                                class="search-widget-icon fs-14 link-secondary text-decoration-underline search-widget-icon-close d-none"
                                id="search-close-options">Xóa</a>
                        </div>
                    </div>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 overflow-hidden"
                        id="search-dropdown">

                        <div class="dropdown-head rounded-top">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-14 text-muted fw-semibold">TÌNH ĐÃ TÌM GẦN ĐÂY</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-item bg-transparent text-wrap">
                                <a href="index.html" class="btn btn-soft-secondary btn-sm btn-rounded">cách cài đặt <i
                                        class="mdi mdi-magnify ms-1 align-middle"></i></a>
                                <a href="index.html" class="btn btn-soft-secondary btn-sm btn-rounded">nút bấm <i
                                        class="mdi mdi-magnify ms-1 align-middle"></i></a>
                            </div>
                        </div>

                        <div data-simplebar style="max-height: 300px;" class="pe-2 ps-3 mt-3">
                            <div class="list-group list-group-flush border-dashed">
                                <div class="notification-group-list">
                                    <h5
                                        class="text-overflow text-muted fs-13 mb-2 mt-3 text-uppercase notification-title">
                                        Trang ứng dụng</h5>
                                    <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                            class="bi bi-speedometer2 me-2"></i> <span>Bảng điều khiển phân
                                            tích</span></a>
                                    <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                            class="bi bi-filetype-psd me-2"></i> <span>Toner.psd</span></a>
                                    <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                            class="bi bi-ticket-detailed me-2"></i> <span>Phiếu hỗ trợ</span></a>
                                    <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                            class="bi bi-file-earmark-zip me-2"></i> <span>Toner.zip</span></a>
                                </div>

                                <div class="notification-group-list">
                                    <h5
                                        class="text-overflow text-muted fs-13 mb-2 mt-3 text-uppercase notification-title">
                                        Liên kết</h5>
                                    <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                            class="bi bi-link-45deg me-2 align-middle"></i>
                                        <span>www.themesbrand.com</span></a>
                                </div>

                                <div class="notification-group-list">
                                    <h5
                                        class="text-overflow text-muted fs-13 mb-2 mt-3 text-uppercase notification-title">
                                        Người dùng</h5>

                                    <!-- item người dùng mẫu với SVG avatar -->
                                    <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item">
                                        <div class="d-flex align-items-center">
                                            <span
                                                style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background:#e2e8f0; flex-shrink:0; margin-right:.5rem;">
                                                <svg width="24" height="24" viewBox="0 0 64 64"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="32" cy="32" r="32" fill="#c7d2fe" />
                                                    <circle cx="32" cy="22" r="12" fill="#fff" />
                                                    <path d="M16 50c0-8.837 7.163-16 16-16s16 7.163 16 16v2H16v-2z"
                                                        fill="#fff" />
                                                </svg>
                                            </span>
                                            <div>
                                                <h6 class="mb-0">Ayaan Bowen</h6>
                                                <span class="fs-12 text-muted">Lập trình viên React</span>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item">
                                        <div class="d-flex align-items-center">
                                            <span
                                                style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background:#fde68a; flex-shrink:0; margin-right:.5rem;">
                                                <svg width="24" height="24" viewBox="0 0 64 64"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="32" cy="32" r="32" fill="#fcd34d" />
                                                    <circle cx="32" cy="22" r="12" fill="#fff" />
                                                    <path d="M16 50c0-8.837 7.163-16 16-16s16 7.163 16 16v2H16v-2z"
                                                        fill="#fff" />
                                                </svg>
                                            </span>
                                            <div>
                                                <h6 class="mb-0">Alexander Kristi</h6>
                                                <span class="fs-12 text-muted">Lập trình viên React</span>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item">
                                        <div class="d-flex align-items-center">
                                            <span
                                                style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background:#a7f3d0; flex-shrink:0; margin-right:.5rem;">
                                                <svg width="24" height="24" viewBox="0 0 64 64"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="32" cy="32" r="32" fill="#6ee7b7" />
                                                    <circle cx="32" cy="22" r="12" fill="#fff" />
                                                    <path d="M16 50c0-8.837 7.163-16 16-16s16 7.163 16 16v2H16v-2z"
                                                        fill="#fff" />
                                                </svg>
                                            </span>
                                            <div>
                                                <h6 class="mb-0">Alan Carla</h6>
                                                <span class="fs-12 text-muted">Lập trình viên React</span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


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

                        <li class="nav-item">
                            <a href="index.html" class="nav-link menu-link">
                                <i class="bi bi-speedometer2"></i> <span>Bảng điều khiển</span>
                                <span class="badge badge-pill bg-danger-subtle text-danger">Hot</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarProducts" data-bs-toggle="collapse">
                                <i class="bi bi-box-seam"></i> <span>Sản phẩm</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarProducts">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="{{ route('categories') }}" class="nav-link">Danh mục</a></li>
                                    <li><a href="{{ route('categories.trashed') }}" class="nav-link">Danh mục đã xóa
                                            mềm</a></li>
                                    <li><a href="{{ route('brands') }}" class="nav-link">Thương hiệu</a></li>
                                    <li><a href="{{ route('brands.trashed') }}" class="nav-link">Thương hiệu đã xóa
                                            mềm</a></li>
                                    <li><a href="{{ route('product-list') }}" class="nav-link">Danh sách sản phẩm</a>
                                    </li>
                                    <li><a href="{{ route('product.trashed') }}" class="nav-link">Sản phẩm đã xóa
                                            mềm</a></li>
                                    <li><a href="{{ route('attributes') }}" class="nav-link">Thuộc tính</a></li>
                                    <li><a href="{{ route('attributes.trashed') }}" class="nav-link">Thuộc tính đã
                                            xóa mềm</a></li>
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

                        <li><a href="calendar.html" class="nav-link menu-link"><i class="bi bi-calendar-week"></i>
                                <span>Lịch</span></a></li>

                        <li class="nav-item">
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
                        </li>

                        <li class="nav-item">
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
                        </li>

                        <li><a href="users-list.html" class="nav-link menu-link"><i
                                    class="bi bi-person-bounding-box"></i> <span>Danh sách người dùng</span></a></li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarShipping" data-bs-toggle="collapse">
                                <i class="bi bi-truck"></i> <span>Vận chuyển</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarShipping">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="shipping-list.html" class="nav-link">Danh sách vận chuyển</a></li>
                                    <li><a href="shipments.html" class="nav-link">Lô hàng</a></li>
                                </ul>
                            </div>
                        </li>

                        <li><a href="{{ route('coupons-list') }}" class="nav-link menu-link"><i
                                    class="bi bi-tag"></i> <span>Mã giảm giá</span></a></li>
                        <li><a class="nav-link menu-link" href="#sidebarRates" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarRates">
                                <i class="bi bi-star"></i> <span data-key="t-orders">Reviews $ Comments</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarRates">
                                <ul class="nav nav-sm flex-column">
                                    <a href="{{ route('admin.reviews.index') }}" class="nav-link">Danh sách đánh
                                        giá</a>
                                </ul>
                            </div>
                            <div class="collapse menu-dropdown" id="sidebarRates">
                                <ul class="nav nav-sm flex-column">
                                    <a href="{{ route('admin.comments.index') }}" class="nav-link">Danh sách bình
                                        luận</a>
                                </ul>
                            </div>
                        </li>
                        <li><a href="brands.html" class="nav-link menu-link"><i class="bi bi-shop"></i> <span>Thương
                                    hiệu</span></a></li>
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link menu-link"><i
                                    class="bi bi-pie-chart"></i>
                                <span>Thống kê</span></a></li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarLocalization" data-bs-toggle="collapse">
                                <i class="bi bi-coin"></i> <span>Bản địa hóa</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarLocalization">
                                <ul class="nav nav-sm flex-column">
                                    <li><a href="transactions.html" class="nav-link">Giao dịch</a></li>
                                    <li><a href="currency-rates.html" class="nav-link">Tỷ giá</a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
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
                        </li>

                        <li><a href="https://themesbrand.com/toner/html/components/index.html" target="_blank"
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
                        </li>
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

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Toner.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Thiết kế & Phát triển bởi Admin
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-info btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->
    <a class="btn btn-danger shadow-lg chat-button rounded-bottom-0 d-none d-lg-block" data-bs-toggle="collapse"
        href="#chatBot" role="button" aria-expanded="false" aria-controls="chatBot">Chat trực tuyến</a>
    <div class="collapse chat-box" id="chatBot">
        <div class="card shadow-lg border-0 rounded-bottom-0 mb-0">
            <div class="card-header bg-success d-flex align-items-center border-0">
                <h5 class="text-white fs-16 fw-medium flex-grow-1 mb-0">Chào, Raquel Murillo 👋</h5>
                <button type="button" class="btn-close btn-close-white flex-shrink-0" onclick="chatBot()"
                    data-bs-dismiss="collapse" aria-label="Đóng"></button>
            </div>
            <div class="card-body p-0">
                <div id="users-chat-widget">
                    <div class="chat-conversation p-3" id="chat-conversation" data-simplebar style="height: 280px;">
                        <ul class="list-unstyled chat-conversation-list chat-sm" id="users-conversation">
                            <li class="chat-list left">
                                <div class="conversation-list">
                                    <div class="chat-avatar">
                                        <img src="../assets/images/logo-sm.png" alt="">
                                    </div>
                                    <div class="user-chat-content">
                                        <div class="ctext-wrap">
                                            <div class="ctext-wrap-content">
                                                <p class="mb-0 ctext-content">Chào mừng đến với Themesbrand. Chúng tôi
                                                    ở đây để hỗ trợ bạn. Bạn cũng có thể gửi email trực tiếp cho chúng
                                                    tôi tại Support@themesbrand.com để lên lịch gặp gỡ với Chuyên gia
                                                    Công nghệ.</p>
                                            </div>
                                            <div class="dropdown align-self-start message-box-drop">
                                                <a class="dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="ri-more-2-fill"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#"><i
                                                            class="ri-reply-line me-2 text-muted align-bottom"></i>Phản
                                                        hồi</a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="ri-file-copy-line me-2 text-muted align-bottom"></i>Sao
                                                        chép</a>
                                                    <a class="dropdown-item delete-item" href="#"><i
                                                            class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Xóa</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="conversation-name">
                                            <small class="text-muted time">09:07</small>
                                            <span class="text-success check-message-icon"><i
                                                    class="ri-check-double-line align-bottom"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="chat-list right">
                                <div class="conversation-list">
                                    <div class="user-chat-content">
                                        <div class="ctext-wrap">
                                            <div class="ctext-wrap-content">
                                                <p class="mb-0 ctext-content">Chào buổi sáng, bạn khỏe không? Còn cuộc
                                                    họp tiếp theo của chúng ta thì sao?</p>
                                            </div>
                                            <div class="dropdown align-self-start message-box-drop">
                                                <a class="dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="ri-more-2-fill"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#"><i
                                                            class="ri-reply-line me-2 text-muted align-bottom"></i>Phản
                                                        hồi</a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="ri-file-copy-line me-2 text-muted align-bottom"></i>Sao
                                                        chép</a>
                                                    <a class="dropdown-item delete-item" href="#"><i
                                                            class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Xóa</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="conversation-name">
                                            <small class="text-muted time">09:08</small>
                                            <span class="text-success check-message-icon"><i
                                                    class="ri-check-double-line align-bottom"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="chat-list left">
                                <div class="conversation-list">
                                    <div class="chat-avatar">
                                        <img src="../assets/images/logo-sm.png" alt="">
                                    </div>
                                    <div class="user-chat-content">
                                        <div class="ctext-wrap">
                                            <div class="ctext-wrap-content">
                                                <p class="mb-0 ctext-content">Ừ, mọi thứ ổn. Cuộc họp tiếp theo của
                                                    chúng ta là ngày mai lúc 10:00.</p>
                                            </div>
                                            <div class="dropdown align-self-start message-box-drop">
                                                <a class="dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="ri-more-2-fill"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#"><i
                                                            class="ri-reply-line me-2 text-muted align-bottom"></i>Phản
                                                        hồi</a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="ri-file-copy-line me-2 text-muted align-bottom"></i>Sao
                                                        chép</a>
                                                    <a class="dropdown-item delete-item" href="#"><i
                                                            class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Xóa</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="conversation-name">
                                            <small class="text-muted time">09:10</small>
                                            <span class="text-success check-message-icon"><i
                                                    class="ri-check-double-line align-bottom"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="border-top border-top-dashed">
                    <div class="row g-2 mt-2 mx-3 mb-3">
                        <div class="col">
                            <div class="position-relative">
                                <input type="text" class="form-control border-light bg-light"
                                    placeholder="Nhập tin nhắn..." aria-label="Nhập tin nhắn">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-info" aria-label="Gửi"><i
                                    class="mdi mdi-send"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    @include('admin.layouts.partials.script')
    @stack('scripts')
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Mirrored from themesbrand.com/toner/html/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 16 Mar 2025 17:50:20 GMT -->

</html>
