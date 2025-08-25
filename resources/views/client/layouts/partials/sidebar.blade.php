<div class="container-main">
    <div class="sidebar-menu">
        <div id="menu-main" class="menu-container">
            <div class="menu-wrapper space-bread-crumb">
                <div class="menu-tree">
                    <!-- Điện thoại, Tablet -->
                    <div class="label-menu-tree" data-target="dropdown-phone">
                        <a href="{{ url('/products/laptopsinhvien') }}">
                            <div class="label-item">
                                <i class="bi bi-phone"></i>
                                <span>LapTop Cho sinh viên</span>
                            </div>
                        </a>
                    </div>

                    <!-- Laptop -->
                    <div class="label-menu-tree" data-target="dropdown-laptop">
                        <a href="{{ url('/products/laptopgaming') }}">
                            <div class="label-item">
                                <i class="bi bi-laptop"></i>
                                <span>Laptop gaming</span>
                            </div>
                        </a>
                    </div>

                    <!-- Âm thanh, Mic thu âm -->
                    <div class="label-menu-tree" data-target="dropdown-camera">
                        <a href="{{ url('/products/laptopmongnhe') }}">
                            <div class="label-item">
                                <i class="bi bi-earbuds"></i>
                                <span>Laptop mỏng nhẹ</span>
                            </div>
                        </a>
                    </div>

                    <!-- Đồng hồ, Camera -->
                    <div class="label-menu-tree" data-target="dropdown-audio">
                         <a href="{{ url('/products/laptopvanphong') }}">
                            <div class="label-item">
                                <i class="bi bi-watch"></i>
                                <span>Laptop văn phòng</span>
                            </div>
                        </a>
                    </div>

                    <!-- Đồ gia dụng -->
                    <div class="label-menu-tree" data-target="dropdown-goods">
                        <a href="{{ url('/products/laptopdohoa') }}">
                            <div class="label-item">
                                <i class="bi bi-house"></i>
                                <span>Laptop đồ họa</span>
                            </div>
                        </a>
                    </div>

                    <!-- Phụ kiện -->
                    <div class="label-menu-tree" data-target="dropdown-accessories">
                        <a href="{{ url('/products/laptopAI') }}">
                            <div class="label-item">
                                <i class="bi bi-usb"></i>
                                <span>Laptop AI</span>
                            </div>
                        </a>
                    </div>
<!-- PC, Màn hình, Máy in -->
                    <div class="label-menu-tree" data-target="dropdown-pc">
                        <a href="{{ url('/coming-soon') }}">
                            <div class="label-item">
                                <i class="bi bi-pc-display"></i>
                                <span>PC</span>
                            </div>
                        </a>
                    </div>

                    <!-- Tivi -->
                    <div class="label-menu-tree" data-target="dropdown-tivi">
                        <a href="{{ url('/coming-soon') }}">
                            <div class="label-item">
                                <i class="bi bi-tv"></i>
                                <span>Tivi</span>
                            </div>
                        </a>
                    </div>

                    <!-- Thu cũ đổi mới -->
                    <div class="label-menu-tree" data-target="dropdown-trade">
                        <a href="{{ url('/coming-soon') }}">
                            <div class="label-item">
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Thu cũ đổi mới</span>
                            </div>
                        </a>
                    </div>

                    <!-- Hàng cũ -->
                    <div class="label-menu-tree" data-target="dropdown-used">
                        <a href="{{ url('/coming-soon') }}">
                            <div class="label-item">
                                <i class="bi bi-box"></i>
                                <span>Hàng cũ</span>
                            </div>
                        </a>
                    </div>

                    <!-- Khuyến mãi -->
                    <div class="label-menu-tree" data-target="dropdown-promotion">
                        <a href="{{ url('/coming-soon') }}">
                            <div class="label-item">
                                <i class="bi bi-percent"></i>
                                <span>Khuyến mãi</span>
                            </div>
                        </a>
                    </div>

                    <!-- Tin công nghệ -->
                    <div class="label-menu-tree" data-target="dropdown-news">
                        <a href="{{ url('/coming-soon') }}">
                            <div class="label-item">
                                <i class="bi bi-newspaper"></i>
                                <span>Tin công nghệ</span>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>


        <div data-fetch-key="SlidingBanner:0" class="block-top-home__sliding-banner">
            <div class="block-sliding-home">
                <div class="swiper-container gallery-top">
                    <div class="swiper-wrapper">
<!-- Thay các swiper-slide bên trong block-sliding-home -->
                        <div class="swiper-slide"><img src="{{ asset('storage/GALAXY Z7.png') }}" width="690"
                                height="200" alt="GALAXY Z7&lt;br /&gt;Đặt trước ngay" loading="lazy"></div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/Sliding-iphone-v3.png"
                                width="690" height="200" alt="IPHONE 16 PRO MAX &lt;br /&gt; Mua ngay"
                                loading="lazy">
                        </div>
                        <div class="swiper-slide"><img src="{{ asset('storage/OPPO RENO14.jpg') }}" width="690"
                                height="200" alt="OPPO RENO14&lt;br /&gt;Mua ngay" loading="lazy">
                        </div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/home-watch8.jpg"
                                width="690" height="200" alt="GALAXY WATCH8 &lt;br /&gt; Đặt trước ngay"
                                loading="lazy">
                        </div>
                        <div class="swiper-slide"><img
                                src="{{ asset('storage/acer.png') }}"
                                width="690" height="200" alt="GALAXY WATCH8 &lt;br /&gt; Đặt trước ngay"
                                loading="lazy">
                        </div>
                    </div>
                    <!-- Không còn swiper-pagination, swiper-button-next, swiper-button-prev -->
                    <div class="swiper-button-next swiper-button-white"></div>
                    <div class="swiper-button-prev swiper-button-white"></div>
                </div>

                <div
                    class="swiper-container gallery-thumbs swiper-container-initialized swiper-container-horizontal swiper-container-free-mode swiper-container-thumbs">
                    <div class="swiper-wrapper"
                        style="transition-duration: 0ms; transform: translate3d(-314.857px, 0px, 0px);">
                        <div class="swiper-slide" style="width: 157.429px;">
                            <div>GALAXY Z7<br>Đặt trước ngay</div>
                        </div>
                        <div class="swiper-slide swiper-slide-prev" style="width: 157.429px;">
                            <div>IPHONE 16 PRO MAX <br> Mua ngay</div>
                        </div>
                        <div class="swiper-slide swiper-slide-visible swiper-slide-active" style="width: 157.429px;">
                            <div>OPPO RENO14<br>Mua ngay</div>
                        </div>
<div class="swiper-slide swiper-slide-visible swiper-slide-next" style="width: 157.429px;">
                            <div>GALAXY WATCH8 <br> Đặt trước ngay</div>
                        </div>
                        <div class="swiper-slide swiper-slide-visible swiper-slide-thumb-active"
                            style="width: 157.429px;">
                            <div>Laptop Gaming Nitro 5<br>Giá chỉ 18 triệu</div>
                        </div>
                    </div><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            </div>
        </div>
        <div class="block-top-home__right-banner">
            <div class="right-banner">
                <div class="right-banner__item button__link">
                    <img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:10/plain/https://dashboard.cellphones.com.vn/storage/samsung-galaxy-m55-5g-8gb-256gb.png"
                        width="690" height="300" alt="Galaxy M55 &lt;br /&gt; Giá tốt chốt ngay"
                        class="right-banner__img">
                </div>
                <div class="right-banner__item button__link">
                    <img src="{{ asset('storage/b2s.jpg') }}"
                        width="690" height="300" alt="B2S IPAD" class="right-banner__img">
                </div>
                <div class="right-banner__item button__link">
                    <img src="{{ asset('storage/b2s laptop.jpg') }}"
                        width="690" height="300" alt="B2S Laptop" class="right-banner__img">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Swiper) {
            var thumbsEl = document.querySelector('.gallery-thumbs');
            var topEl = document.querySelector('.gallery-top');
            var galleryThumbs, galleryTop;
            if (thumbsEl) {
                galleryThumbs = new Swiper('.gallery-thumbs', {
                    spaceBetween: 0,
                    slidesPerView: 5,
                    freeMode: true,
                    watchSlidesProgress: true,
                    watchSlidesVisibility: true,
                    slideToClickedSlide: true,
                    breakpoints: {
                        0: {
                            slidesPerView: 2
                        },
                        600: {
                            slidesPerView: 3
                        },
                        900: {
                            slidesPerView: 5
                        }
                    }
                });
            }
            if (topEl) {
                galleryTop = new Swiper('.gallery-top', {
                    loop: true,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                    },
navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    thumbs: galleryThumbs ? {
                        swiper: galleryThumbs
                    } : undefined,
                    effect: 'slide',
                    speed: 600,
                    spaceBetween: 0,
                    slidesPerView: 1,
                });
            }
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuItems = document.querySelectorAll(".label-menu-tree");
        const dropdowns = document.querySelectorAll(".dropdown-menu-custom");

        // Ẩn tất cả dropdown
        const hideAllDropdowns = () => {
            dropdowns.forEach(drop => drop.style.display = "none");
        };

        // Gắn sự kiện hover vào từng menu
        menuItems.forEach(item => {
            item.addEventListener("mouseenter", () => {
                const targetId = item.getAttribute("data-target");
                hideAllDropdowns();

                const targetDropdown = document.getElementById(targetId);
                if (targetDropdown) {
                    targetDropdown.style.display = "block";
                }
            });
        });

        // Ẩn dropdown khi rời khỏi vùng menu
        const menuWrapper = document.querySelector(".menu-wrapper");
        menuWrapper.addEventListener("mouseleave", () => {
            hideAllDropdowns();
        });
    });
</script>
