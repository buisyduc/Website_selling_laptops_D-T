<!-- ========== HEADER ========== -->
<header class="bg-warning py-2">
    <!-- Topbar -->
    <div class="container d-none d-xl-flex justify-content-between align-items-center">
        <div class="text-dark small">
            <div class="topbar-left">
                <a href="#" class="text-gray-110 font-size-13 hover-on-dark">Chào Mừng bạn đến với website bán
                    laptop D&T</a>
            </div>
        </div>
        <ul class="list-inline mb-0">
            <li class="list-inline-item mr-0 u-header-topbar__nav-item u-header-topbar__nav-item-border">
                <a href="contact-v2.html" class="u-header-topbar__nav-link"><i class="ec ec-map-pointer mr-1"></i>
                    Vị Trí cửa hàng</a>
            </li>
            <li class="list-inline-item mr-0 u-header-topbar__nav-item">
                @auth
                    <div class="dropdown">
                        <a href="{{ route('management') }}" class="u-header-topbar__nav-link">
                            <i class="ec ec-user mr-1"></i> {{ Auth::user()->name }}
                        </a>

                    </div>
                @else
                    <!-- Account Sidebar Toggle Button -->
                    <a id="sidebarNavToggler" href="javascript:;" role="button" class="u-header-topbar__nav-link"
                        aria-controls="sidebarContent" aria-haspopup="true" aria-expanded="false" data-unfold-event="click"
                        data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent"
                        data-unfold-type="css-animation" data-unfold-animation-in="fadeInRight"
                        data-unfold-animation-out="fadeOutRight" data-unfold-duration="500">
                        <i class="ec ec-user mr-1"></i> Đăng Ký <span class="text-gray-50">or</span> Đăng Nhập
                    </a>
                    <!-- End Account Sidebar Toggle Button -->
                @endauth
            </li>

        </ul>
    </div>

    <!-- Logo, Search, Icons -->
    <div class="container py-2">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <!-- Logo -->
                    <a href="/" class="navbar-brand font-weight-bold text-dark mr-3" style="font-size: 28px;">
                        <span>D&T</span>
                    </a>

                    <!-- Fullscreen Toggle Button -->
                    <button id="sidebarHeaderInvokerMenu" type="button" class="navbar-toggler d-block btn u-hamburger"
                        aria-controls="sidebarHeader" aria-haspopup="true" aria-expanded="false"
                        data-unfold-event="click" data-unfold-hide-on-scroll="false"
                        data-unfold-target="#sidebarHeader1" data-unfold-type="css-animation"
                        data-unfold-animation-in="fadeInLeft" data-unfold-animation-out="fadeOutLeft"
                        data-unfold-duration="500">
                        <span id="hamburgerTriggerMenu" class="u-hamburger__box">
                            <span class="u-hamburger__inner"></span>
                        </span>
                    </button>
                    <aside id="sidebarHeader1" class="u-sidebar u-sidebar--left" aria-labelledby="sidebarHeaderInvoker">
                        <div class="u-sidebar__scroller">
                            <div class="u-sidebar__container">
                                <div class="u-header-sidebar__footer-offset">
                                    <!-- Toggle Button -->
                                    <div class="position-absolute top-0 right-0 z-index-2 pt-4 pr-4 bg-white">
                                        <button type="button" class="close ml-auto" aria-controls="sidebarHeader"
                                            aria-haspopup="true" aria-expanded="false" data-unfold-event="click"
                                            data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarHeader1"
                                            data-unfold-type="css-animation" data-unfold-animation-in="fadeInLeft"
                                            data-unfold-animation-out="fadeOutLeft" data-unfold-duration="500">
                                            <span aria-hidden="true"><i
                                                    class="ec ec-close-remove text-gray-90 font-size-20"></i></span>
                                        </button>
                                    </div>
                                    <!-- End Toggle Button -->

                                    <!-- Content -->
                                    <div class="js-scrollbar u-sidebar__body">
                                        <div id="headerSidebarContent"
                                            class="u-sidebar__content u-header-sidebar__content">
                                            <!-- Logo -->
                                            <a class="navbar-brand u-header__navbar-brand u-header__navbar-brand-center mb-3"
                                                href="/" aria-label="PC365">
                                                <img
                                                    src="https://www.shutterstock.com/image-vector/dt-initial-logo-ampersand-monogram-260nw-335528063.jpg">
                                            </a>
                                            <!-- End Logo -->

                                            <!-- List -->
                                            <ul id="headerSidebarList" class="u-header-collapse__nav">
                                                <!-- Value of the Day -->
                                                <li class="">
                                                    <a class="u-header-collapse__nav-link font-weight-bold"
                                                        href="#">Sản phẩm của ngày</a>
                                                </li>
                                                <!-- End Value of the Day -->

                                                <!-- Top 100 Offers -->
                                                <li class="">
                                                    <a class="u-header-collapse__nav-link font-weight-bold"
                                                        href="#">100 ưu đãi hành đầu</a>
                                                </li>
                                                <!-- End Top 100 Offers -->

                                                <!-- New Arrivals -->
                                                <li class="">
                                                    <a class="u-header-collapse__nav-link font-weight-bold"
                                                        href="#">Sản phẩm mới</a>
                                                </li>
                                                <!-- End New Arrivals -->

                                                <!-- Computers & Accessories -->
                                                <li class="u-has-submenu u-header-collapse__submenu">
                                                    <a class="u-header-collapse__nav-link font-weight-bold u-header-collapse__nav-pointer"
                                                        href="javascript:;"
                                                        data-target="#headerSidebarComputersCollapse" role="button"
                                                        data-toggle="collapse" aria-expanded="false"
                                                        aria-controls="headerSidebarComputersCollapse">
                                                        LapTop
                                                    </a>

                                                    <div id="headerSidebarComputersCollapse" class="collapse"
                                                        data-parent="#headerSidebarContent">
                                                        <ul class="u-header-collapse__nav-list">
                                                            <li class=""><a
                                                                    class="u-header-collapse__submenu-nav-link"
                                                                    href="#">Gaming</a></li>
                                                            <li class=""><a
                                                                    class="u-header-collapse__submenu-nav-link"
                                                                    href="#">Business</a></li>
                                                            <li class=""><a
                                                                    class="u-header-collapse__submenu-nav-link"
                                                                    href="#">Student / Office</a></li>
                                                            <li class=""><a
                                                                    class="u-header-collapse__submenu-nav-link"
                                                                    href="#">Workstation</a></li>
                                                            <li class=""><a
                                                                    class="u-header-collapse__submenu-nav-link"
                                                                    href="#">Convertible / 2-in-1</a></li>
                                                    </div>
                                                </li>
                                                <!-- End Computers & Accessories -->

                                                <!-- Cameras, Audio & Video -->
                                        </div>
                                    </div>
                                    <!-- End Content -->
                                </div>
                                <!-- Footer -->
                                <footer id="SVGwaveWithDots" class="svg-preloader u-header-sidebar__footer">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item pr-3">
                                            <a class="u-header-sidebar__footer-link text-gray-90"
                                                href="#">Privacy</a>
                                        </li>
                                        <li class="list-inline-item pr-3">
                                            <a class="u-header-sidebar__footer-link text-gray-90"
                                                href="#">Terms</a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a class="u-header-sidebar__footer-link text-gray-90" href="#">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- SVG Background Shape -->
                                    <div class="position-absolute right-0 bottom-0 left-0 z-index-n1">
                                        <img class="js-svg-injector"
                                            src="https://transvelo.github.io/electro-html/2.0/assets/svg/components/wave-bottom-with-dots.svg"
                                            alt="Image Description" data-parent="#SVGwaveWithDots">
                                    </div>
                                    <!-- End SVG Background Shape -->
                                </footer>
                                <!-- End Footer -->
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
            <!-- Search -->
            <div class="col d-none d-xl-block">
                <form class="js-focus-state">
                    <label class="sr-only" for="searchproduct">Tìm Kiếm</label>
                    <div class="input-group">
                        <input type="email"
                            class="form-control py-2 pl-5 font-size-15 border-right-0 height-40 border-width-2 rounded-left-pill border-primary"
                            name="email" id="searchproduct-item" placeholder="Tìm Kiếm"
                            aria-label="Search for Products" aria-describedby="searchProduct1" required>
                        <div class="input-group-append">
                            <button class="btn btn-primary height-40 py-2 px-3 rounded-right-pill" type="button"
                                id="searchProduct1">
                                <span class="ec ec-search font-size-24"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Cart -->
            @php
                use App\Models\Cart;

                $totalQty = 0;
                $totalPrice = 0;

                if (auth()->check()) {
                    $cart = Cart::with('items.variant')
                        ->where('user_id', auth()->id())
                        ->first();
                    if ($cart) {
                        $totalQty = $cart->items->sum('quantity');
                        $totalPrice = $cart->items->sum(fn($item) => $item->quantity * ($item->variant->price ?? 0));
                    }
                }
            @endphp

            <div class="col-auto">
                <a href="{{ route('cart.index') }}" id="cart-button"
                    class="text-dark position-relative d-flex align-items-center">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                    <span id="cart-count"
                        class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle">
                        {{ $totalQty }}
                    </span>
                    <span class="d-none d-xl-inline ml-2 font-weight-bold" id="cart-total">
                        {{ number_format($totalPrice, 0, ',', '.') }}₫
                    </span>
                </a>
            </div>




        </div>
    </div>

</header>
