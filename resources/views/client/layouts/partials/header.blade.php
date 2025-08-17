<!-- ========== HEADER ========== -->
<header class="fixed-top shadow-sm" style="background-color: #dc3545;">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <!-- Left Section: Logo & Category -->
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <!-- Logo -->
                    <a href="/" class="navbar-brand font-weight-bold text-dark mr-3" style="font-size: 28px;">
                        <span style="color: floralwhite">D&T</span>
                    </a>
                    <!-- Category -->
                    <button class="navbar__item button__menu" fdprocessedid="a8tkuu"><svg width="24" height="24"
                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.7041 4H10.7041V10H4.7041V4Z" stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M14.7041 4H20.7041V10H14.7041V4Z" stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M4.7041 14H10.7041V20H4.7041V14Z" stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path
                            d="M14.7041 17C14.7041 17.7956 15.0202 18.5587 15.5828 19.1213C16.1454 19.6839 16.9085 20 17.7041 20C18.4998 20 19.2628 19.6839 19.8254 19.1213C20.388 18.5587 20.7041 17.7956 20.7041 17C20.7041 16.2044 20.388 15.4413 19.8254 14.8787C19.2628 14.3161 18.4998 14 17.7041 14C16.9085 14 16.1454 14.3161 15.5828 14.8787C15.0202 15.4413 14.7041 16.2044 14.7041 17Z"
                            stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg> <span class="navbar__item-text">Danh mục</span> <svg width="24" height="24"
                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.7041 9L12.7041 15L18.7041 9" stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg></button>
                </div>
            </div>
            <div class="col d-none d-xl-block">
                <form class="js-focus-state">
                    <div class="cps-group-input is-flex">
                        <div class="input-group-btn"><button type="submit"
                                class="border-0 shadow-none outline-none text-dark" fdprocessedid="pg7eio"><svg
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_4895_10933)">
                                        <path
                                            d="M3.7041 10C3.7041 10.9193 3.88516 11.8295 4.23694 12.6788C4.58873 13.5281 5.10434 14.2997 5.75435 14.9497C6.40436 15.5998 7.17604 16.1154 8.02532 16.4672C8.8746 16.8189 9.78485 17 10.7041 17C11.6234 17 12.5336 16.8189 13.3829 16.4672C14.2322 16.1154 15.0038 15.5998 15.6538 14.9497C16.3039 14.2997 16.8195 13.5281 17.1713 12.6788C17.523 11.8295 17.7041 10.9193 17.7041 10C17.7041 9.08075 17.523 8.1705 17.1713 7.32122C16.8195 6.47194 16.3039 5.70026 15.6538 5.05025C15.0038 4.40024 14.2322 3.88463 13.3829 3.53284C12.5336 3.18106 11.6234 3 10.7041 3C9.78485 3 8.8746 3.18106 8.02532 3.53284C7.17604 3.88463 6.40436 4.40024 5.75435 5.05025C5.10434 5.70026 4.58873 6.47194 4.23694 7.32122C3.88516 8.1705 3.7041 9.08075 3.7041 10Z"
                                            stroke="#1D1D20" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M21.7041 21L15.7041 15" stroke="#1D1D20" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_4895_10933">
                                            <rect width="24" height="24" fill="white"
                                                transform="translate(0.704102)"></rect>
                                        </clipPath>
                                    </defs>
                                </svg></button></div> <input id="inp$earch" type="text"
                            placeholder="Bạn muốn mua gì hôm nay?" autocomplete="off" value="" class="cps-input"
                            fdprocessedid="aonpa"> <span id="btn-close-search" style="display:none;">×</span>

                        <div id="suggestSearch" class="suggest-search" style="display:none;"><!----> <!----> <!---->
                        </div>
                    </div>
                </form>
                <div id="overlaySearch" class="header-overlay"></div>
            </div>

            <!-- Cart and Authentication Section -->
            <div class="col-auto ml-auto">
                <div class="d-flex align-items-center">
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
                                $totalPrice = $cart->items->sum(
                                    fn($item) => $item->quantity * ($item->variant->price ?? 0),
                                );
                            }
                        }
                    @endphp

                    <a href="{{ route('cart.index') }}" id="cart-button"
                        class="cart-btn position-relative d-flex align-items-center mr-3 text-decoration-none">
                        Giỏ hàng <i class="fas fa-shopping-cart fa-lg ml-1"></i>
                        <span id="cart-count"
                            class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle">
                            {{ $totalQty }}
                        </span>
                        <span class="d-none d-xl-inline ml-2 font-weight-bold" id="cart-total">
                            {{ number_format($totalPrice, 0, ',', '.') }}đ
                        </span>
                    </a>


                    <!-- Authentication -->
                    @auth
                        <div class="dropdown">
                            <a href="{{ route('client.orders.index') }}"
                                class="d-flex align-items-center text-decoration-none font-weight-bold"
                                style="background-color: #ef6969; padding: 10px; border-radius: 12px; color: white; font-size: 20px;">
                                {{ Auth::user()->name }} <i class="fas fa-user-circle ml-1" style="font-size: 20px; color: white;"></i>
                            </a>
                        </div>
                    @else
                        <!-- Account Sidebar Toggle Button -->
                        <a id="sidebarNavToggler" href="javascript:;" role="button"
                                style="background-color: #ef6969; padding: 10px; border-radius: 12px; color: white;"
                                class="d-flex align-items-center text-decoration-none font-weight-bold"
                                aria-controls="sidebarContent" aria-haspopup="true" aria-expanded="false"
                                data-unfold-event="click" data-unfold-hide-on-scroll="false"
                                data-unfold-target="#sidebarContent" data-unfold-type="css-animation"
                                data-unfold-animation-in="fadeInRight" data-unfold-animation-out="fadeOutRight"
                                data-unfold-duration="500">
                                Đăng nhập <i class="fas fa-user-circle mr-1" style="font-size: 20px; color: white;"></i>
                            </a>
                            


                        <!-- End Account Sidebar Toggle Button -->
                    @endauth
                </div>
            </div>
        </div>
    </div>
    
</header>


<script>
    window.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('header.fixed-top');
        if (header) {
            const headerHeight = header.offsetHeight;
            document.body.style.paddingTop = headerHeight + 'px';
        }
    });
</script>
