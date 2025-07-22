<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var galleryThumbs = new Swiper('.gallery-thumbs', {
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
        var galleryTop = new Swiper('.gallery-top', {
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            thumbs: {
                swiper: galleryThumbs
            },
            effect: 'slide',
            speed: 600,
            spaceBetween: 0,
            slidesPerView: 1,
        });
    });
</script>
<div class="container-main">
    <div class="sidebar-menu">
        <div id="menu-main" class="menu-container">
            <div class="menu-wrapper space-bread-crumb">
                <div class="menu-tree">
                    <!-- Điện thoại, Tablet -->
                    <div class="label-menu-tree" data-target="dropdown-phone">
                        <div class="label-item">
                            <i class="bi bi-phone"></i>
                            <span>Điện thoại, Tablet</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Laptop -->
                    <div class="label-menu-tree" data-target="dropdown-laptop">
                        <div class="label-item">
                            <i class="bi bi-laptop"></i>
                            <span>Laptop</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Âm thanh, Mic thu âm -->
                    <div class="label-menu-tree" data-target="dropdown-camera">
                        <div class="label-item">
                            <i class="bi bi-earbuds"></i>
                            <span>Âm thanh, Mic thu âm</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Đồng hồ, Camera -->
                    <div class="label-menu-tree" data-target="dropdown-audio">
                        <div class="label-item">
                            <i class="bi bi-watch"></i>
                            <span>Đồng hồ, Camera</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Đồ gia dụng -->
                    <div class="label-menu-tree" data-target="dropdown-goods">
                        <div class="label-item">
                            <i class="bi bi-house"></i>
                            <span>Đồ gia dụng</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Phụ kiện -->
                    <div class="label-menu-tree" data-target="dropdown-accessories">
                        <div class="label-item">
                            <i class="bi bi-usb"></i>
                            <span>Phụ kiện</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- PC, Màn hình, Máy in -->
                    <div class="label-menu-tree" data-target="dropdown-pc">
                        <div class="label-item">
                            <i class="bi bi-pc-display"></i>
                            <span>PC, Màn hình, Máy in</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Tivi -->
                    <div class="label-menu-tree" data-target="dropdown-tivi">
                        <div class="label-item">
                            <i class="bi bi-tv"></i>
                            <span>Tivi</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Thu cũ đổi mới -->
                    <div class="label-menu-tree" data-target="dropdown-trade">
                        <div class="label-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Thu cũ đổi mới</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Hàng cũ -->
                    <div class="label-menu-tree" data-target="dropdown-used">
                        <div class="label-item">
                            <i class="bi bi-box"></i>
                            <span>Hàng cũ</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Khuyến mãi -->
                    <div class="label-menu-tree" data-target="dropdown-promotion">
                        <div class="label-item">
                            <i class="bi bi-percent"></i>
                            <span>Khuyến mãi</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                    <!-- Tin công nghệ -->
                    <div class="label-menu-tree" data-target="dropdown-news">
                        <div class="label-item">
                            <i class="bi bi-newspaper"></i>
                            <span>Tin công nghệ</span>
                        </div>
                        <div class="icon-right"><svg height="15" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 320 512">
                                <path
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
                                </path>
                            </svg></div>
                    </div>
                </div>
                <div class="dropdown-wrapper">
                    <div id="dropdown-phone" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Hãng điện thoại -->
                            <div class="col-md-3">
                                <h4>Hãng điện thoại</h4>
                                <ul>
                                    <li>iPhone</li>
                                    <li>Samsung</li>
                                    <li>Xiaomi</li>
                                    <li>OPPO</li>
                                    <li>realme</li>
                                    <li>TECNO</li>
                                    <li>vivo</li>
                                    <li>Infinix</li>
                                    <li>Nokia</li>
                                    <li>Nubia</li>
                                    <li>Nothing Phone <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Masstel</li>
                                    <li>Sony</li>
                                    <li>Itel</li>
                                    <li>Điện thoại phổ thông</li>
                                </ul>
                            </div>

                            <!-- Mức giá điện thoại -->
                            <div class="col-md-2">
                                <h4>Mức giá điện thoại</h4>
                                <ul>
                                    <li>Dưới 2 triệu</li>
                                    <li>Từ 2 - 4 triệu</li>
                                    <li>Từ 4 - 7 triệu</li>
                                    <li>Từ 7 - 13 triệu</li>
                                    <li>Từ 13 - 20 triệu</li>
                                    <li>Trên 20 triệu</li>
                                </ul>
                            </div>

                            <!-- Điện thoại HOT -->
                            <div class="col-md-3">
                                <h4>Điện thoại HOT ⚡</h4>
                                <ul>
                                    <li>iPhone 16</li>
                                    <li>iPhone 15 Pro Max</li>
                                    <li>Galaxy Z Flip7 <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Galaxy Z Fold7 <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>S25 Ultra</li>
                                    <li>OPPO Find N5</li>
                                    <li>Xiaomi 15</li>
                                    <li>Samsung Galaxy A56</li>
                                    <li>Redmi Note 14</li>
                                    <li>Samsung Galaxy A36</li>
                                    <li>OPPO Reno14</li>
                                    <li>Nothing Phone 3A</li>
                                    <li>Tecno Camon 30S</li>
                                    <li>Xiaomi POCO C71</li>
                                </ul>
                            </div>

                            <!-- Máy tính bảng -->
                            <div class="col-md-4">


                                <h4>Máy tính bảng HOT ⚡</h4>
                                <ul>
                                    <li>iPad Air M3 <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>iPad A16 <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>iPad Air 2024</li>
                                    <li>iPad Pro 2024</li>
                                    <li>iPad mini 7</li>
                                    <li>Galaxy Tab S10 Series</li>
                                    <li>Galaxy Tab S9 FE 5G</li>
                                    <li>Xiaomi Pad 7 Pro <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Xiaomi Pad 7</li>
                                    <li>Huawei Matepad 11.5''S</li>
                                    <li>Xiaomi Pad SE</li>
                                    <li>Teclast M50</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="dropdown-laptop" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Thương hiệu -->
                            <div class="col-md-2">
                                <h4>Thương hiệu</h4>
                                <ul>
                                    <li>Mac</li>
                                    <li>ASUS</li>
                                    <li>Lenovo</li>
                                    <li>Dell</li>
                                    <li>HP</li>
                                    <li>Acer</li>
                                    <li>LG</li>
                                    <li>Huawei</li>
                                    <li>MSI</li>
                                    <li>Gigabyte</li>
                                    <li>Vaio</li>
                                    <li>Masstel</li>
                                </ul>
                            </div>

                            <!-- Phân khúc giá -->
                            <div class="col-md-2">
                                <h4>Phân khúc giá</h4>
                                <ul>
                                    <li>Dưới 10 triệu</li>
                                    <li>Từ 10 - 15 triệu</li>
                                    <li>Từ 15 - 20 triệu</li>
                                    <li>Từ 20 - 25 triệu</li>
                                    <li>Từ 25 - 30 triệu</li>
                                </ul>
                            </div>

                            <!-- Nhu cầu sử dụng -->
                            <div class="col-md-2">
                                <h4>Nhu cầu sử dụng</h4>
                                <ul>
                                    <li>Văn phòng</li>
                                    <li>Gaming</li>
                                    <li>Mỏng nhẹ</li>
                                    <li>Đồ họa - kỹ thuật</li>
                                    <li>Sinh viên</li>
                                    <li>Cảm ứng</li>
                                    <li>Laptop AI <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Mac CTO - Nâng cấp theo cách của bạn <span class="text-danger fw-bold">HOT &
                                            NEW</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Dòng chip -->
                            <div class="col-md-3">
                                <h4>Dòng chip</h4>
                                <ul>
                                    <li>Laptop Core i3</li>
                                    <li>Laptop Core i5</li>
                                    <li>Laptop Core i7</li>
                                    <li>Laptop Core i9</li>
                                    <li>Apple M1 Series</li>
                                    <li>Apple M3 Series</li>
                                    <li>Apple M4 Series <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>AMD Ryzen</li>
                                    <li>Intel Core Ultra <span class="text-danger fw-bold">HOT & NEW</span></li>
                                </ul>
                            </div>

                            <!-- Kích thước màn hình -->
                            <div class="col-md-3">
                                <h4>Kích thước màn hình</h4>
                                <ul>
                                    <li>Laptop 12 inch</li>
                                    <li>Laptop 13 inch</li>
                                    <li>Laptop 14 inch</li>
                                    <li>Laptop 15.6 inch</li>
                                    <li>Laptop 16 inch</li>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div id="dropdown-audio" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Chọn loại tai nghe -->
                            <div class="col-md-2">
                                <h4>Chọn loại tai nghe</h4>
                                <ul>
                                    <li>Bluetooth</li>
                                    <li>Chụp tai</li>
                                    <li>Nhét tai</li>
                                    <li>Có dây</li>
                                    <li>Thể thao</li>
                                    <li>Gaming</li>
                                    <li>Xem tất cả tai nghe</li>
                                </ul>

                                <h4 class="mt-4">Mic</h4>
                                <ul>
                                    <li>Mic cài áo</li>
                                    <li>Mic phòng thu, podcast</li>
                                    <li>Mic livestream</li>
                                    <li>Micro không dây</li>
                                </ul>
                            </div>

                            <!-- Hãng tai nghe -->
                            <div class="col-md-2">
                                <h4>Hãng tai nghe</h4>
                                <ul>
                                    <li>Apple</li>
                                    <li>Sony</li>
                                    <li>JBL</li>
                                    <li>Samsung</li>
                                    <li>Marshall</li>
                                    <li>Soundpeats</li>
                                    <li>Bose</li>
                                    <li>Edifier</li>
                                    <li>Xiaomi</li>
                                    <li>Huawei</li>
                                    <li>Sennheiser</li>
                                    <li>Havit</li>
                                    <li>Beats</li>
                                    <li>Tronsmart</li>
                                </ul>
                            </div>

                            <!-- Chọn theo giá -->
                            <div class="col-md-2">
                                <h4>Chọn theo giá</h4>
                                <ul>
                                    <li>Tai nghe dưới 200K</li>
                                    <li>Tai nghe dưới 500K</li>
                                    <li>Tai nghe dưới 1 triệu</li>
                                    <li>Tai nghe dưới 2 triệu</li>
                                    <li>Tai nghe dưới 5 triệu</li>
                                </ul>
                            </div>

                            <!-- Chọn loại loa -->
                            <div class="col-md-2">
                                <h4>Chọn loại loa</h4>
                                <ul>
                                    <li>Loa Bluetooth</li>
                                    <li>Loa Karaoke</li>
                                    <li>Loa kéo</li>
                                    <li>Loa Soundbar</li>
                                    <li>Loa vi tính</li>
                                    <li>Xem tất cả loa</li>
                                </ul>
                            </div>

                            <!-- Hãng loa -->
                            <div class="col-md-2">
                                <h4>Hãng loa</h4>
                                <ul>
                                    <li>JBL</li>
                                    <li>Marshall</li>
                                    <li>Harman Kardon</li>
                                    <li>Acnos</li>
                                    <li>Samsung</li>
                                    <li>Sony</li>
                                    <li>Arirang</li>
                                    <li>LG</li>
                                    <li>Alpha Works</li>
                                    <li>Edifier</li>
                                    <li>Bose</li>
                                    <li>Nanomax</li>
                                    <li>Tronsmart</li>
                                </ul>
                            </div>

                            <!-- Sản phẩm nổi bật -->
                            <div class="col-md-2">
                                <h4>Sản phẩm nổi bật</h4>
                                <ul>
                                    <li>AirPods 4</li>
                                    <li>AirPods Pro 2</li>
                                    <li>Galaxy Buds 3 pro</li>
                                    <li>JBL Tour Pro 3</li>
                                    <li>Sony WH-1000XM5</li>
                                    <li>OPPO Enco Air3i - Chỉ có tại CPS</li>
                                    <li>Redmi Buds 6 Pro</li>
                                    <li>Onyx Studio 9</li>
                                    <li>Marshall Willen II</li>
                                    <li>JBL Partybox Encore 2</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="dropdown-camera" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Loại đồng hồ -->
                            <div class="col-md-2">
                                <h4>Loại đồng hồ</h4>
                                <ul>
                                    <li>Đồng hồ thông minh</li>
                                    <li>Vòng đeo tay thông minh</li>
                                    <li>Đồng hồ định vị trẻ em</li>
                                    <li>Dây đeo</li>
                                </ul>
                            </div>

                            <!-- Thương hiệu đồng hồ -->
                            <div class="col-md-2">
                                <h4>Chọn theo thương hiệu</h4>
                                <ul>
                                    <li>Apple Watch</li>
                                    <li>Samsung</li>
                                    <li>Xiaomi</li>
                                    <li>Huawei</li>
                                    <li>Coros</li>
                                    <li>Garmin</li>
                                    <li>Kieslect</li>
                                    <li>Amazfit</li>
                                    <li>Black Shark</li>
                                    <li>Mibro</li>
                                    <li>Masstel</li>
                                    <li>imoo</li>
                                    <li>Kospet</li>
                                    <li>MyKID</li>
                                    <li>KAVVO</li>
                                </ul>
                            </div>

                            <!-- Sản phẩm đồng hồ nổi bật -->
                            <div class="col-md-2">
                                <h4>Sản phẩm nổi bật ⚡</h4>
                                <ul>
                                    <li>Apple Watch Series 10</li>
                                    <li>Apple Watch Ultra 2</li>
                                    <li>Samsung Galaxy Watch 8 <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Samsung Galaxy Watch 8 Classic <span class="text-danger fw-bold">HOT &
                                            NEW</span></li>
                                    <li>Samsung Galaxy Watch Ultra <span class="text-danger fw-bold">HOT & NEW</span>
                                    </li>
                                    <li>Huawei Watch Fit 4</li>
                                    <li>Huawei Watch Fit 4 Pro</li>
                                    <li>Apple Watch SE</li>
                                    <li>imoo Z1</li>
                                    <li>Viettel MyKID 4G Lite</li>
                                    <li>Xiaomi Mi Band 10 <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Garmin Instinct 3</li>
                                </ul>
                            </div>

                            <!-- Camera các loại -->
                            <div class="col-md-3">
                                <h4>Camera</h4>
                                <ul>
                                    <li>Camera an ninh</li>
                                    <li>Camera hành trình</li>
                                    <li>Action Camera</li>
                                    <li>Camera AI <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Gimbal</li>
                                    <li>Tripod</li>
                                    <li>Máy ảnh</li>
                                    <li>Flycam</li>
                                    <li>Xem tất cả camera</li>
                                </ul>
                            </div>

                            <!-- Camera nổi bật -->
                            <div class="col-md-3">
                                <h4>Camera nổi bật</h4>
                                <ul>
                                    <li>Camera an ninh Imou</li>
                                    <li>Camera an ninh Ezviz</li>
                                    <li>Camera an ninh Xiaomi</li>
                                    <li>Camera an ninh TP-Link</li>
                                    <li>Camera Tiandy <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Camera DJI</li>
                                    <li>Camera Insta360</li>
                                    <li>Máy ảnh Fujifilm</li>
                                    <li>Máy ảnh Canon <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Máy ảnh Sony <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Gopro Hero 13</li>
                                    <li>Flycam DJI</li>
                                    <li>DJI Action 5 Pro</li>
                                    <li>DJI Action 4</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="dropdown-goods" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Gia dụng nhà bếp -->
                            <div class="col-md-2">
                                <h4>Gia dụng nhà bếp</h4>
                                <ul>
                                    <li>Nồi chiên không dầu <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Máy rửa bát</li>
                                    <li>Lò vi sóng</li>
                                    <li>Nồi cơm điện <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Máy xay sinh tố</li>
                                    <li>Máy ép trái cây</li>
                                    <li>Máy làm sữa hạt</li>
                                    <li>Bếp điện</li>
                                    <li>Ấm siêu tốc</li>
                                    <li>Nồi áp suất</li>
                                    <li>Nồi nấu chậm</li>
                                    <li>Nồi lẩu điện</li>
                                </ul>
                            </div>

                            <!-- Sức khỏe - Làm đẹp -->
                            <div class="col-md-2">
                                <h4>Sức khỏe - Làm đẹp</h4>
                                <ul>
                                    <li>Máy đo huyết áp</li>
                                    <li>Máy sấy tóc</li>
                                    <li>Máy massage</li>
                                    <li>Máy cạo râu</li>
                                    <li>Cân sức khoẻ</li>
                                    <li>Bàn chải điện</li>
                                    <li>Máy tăm nước</li>
                                    <li>Tông đơ cắt tóc</li>
                                    <li>Máy tỉa lông mũi</li>
                                    <li>Máy rửa mặt</li>
                                </ul>
                            </div>

                            <!-- Thiết bị gia đình -->
                            <div class="col-md-2">
                                <h4>Thiết bị gia đình</h4>
                                <ul>
                                    <li>Robot hút bụi</li>
                                    <li>Máy lọc không khí</li>
                                    <li>Quạt</li>
                                    <li>Máy hút bụi cầm tay</li>
                                    <li>Máy rửa chén</li>
                                    <li>TV Box</li>
                                    <li>Máy chiếu</li>
                                    <li>Đèn thông minh</li>
                                    <li>Bàn ủi</li>
                                    <li>Máy hút ẩm</li>
                                </ul>
                            </div>

                            <!-- Chăm sóc thú cưng -->
                            <div class="col-md-2">
                                <h4 >Sản phẩm nổi bật ⚡</h4>
                                <ul>
                                    <li>Robot hút bụi Dreame X50 Ultra</li>
                                    <li>Máy chơi game Sony PS5 Slim</li>
                                    <li>Máy chiếu Beecube X2 Max Gen 5</li>
                                    <li>Robot Roborock Q Revo EDGE 5V1</li>
                                    <li>Robot Ecovacs T30 Pro Omni</li>
                                    <li>Robot Xiaomi X20+</li>
                                    <li>Máy lọc không khí Xiaomi</li>
                                    <li>Robot hút bụi Ecovacs</li>
                                    <li>Robot hút bụi Roborock</li>
                                </ul>
                            </div>

                            <!-- Thương hiệu gia dụng -->
                            <div class="col-md-2">
                                <h4>Thương hiệu gia dụng</h4>
                                <ul>
                                    <li>Philips</li>
                                    <li>Panasonic</li>
                                    <li>Sunhouse</li>
                                    <li>Sharp</li>
                                    <li>Gaabor</li>
                                    <li>Bear</li>
                                    <li>AQUA <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Toshiba <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Midea <span class="text-danger fw-bold">HOT & NEW</span></li>
                                    <li>Dreame</li>
                                    <li>Xiaomi</li>
                                    <li>Cuckoo</li>
                                </ul>
                            </div>


                        </div>
                    </div>
                    <div id="dropdown-accessories" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Phụ kiện di động -->
                            <div class="col-md-2">
                                <h4>Phụ kiện di động</h4>
                                <ul>
                                    <li>Phụ kiện Apple</li>
                                    <li>Dán màn hình</li>
                                    <li>Ốp lưng - Bao da</li>
                                    <li>Thẻ nhớ</li>
                                    <li>Apple Care+</li>
                                    <li>Samsung Care+</li>
                                    <li>Sim 4G</li>
                                    <li>Cáp, sạc</li>
                                    <li>Pin dự phòng</li>
                                    <li>Trạm sạc dự phòng</li>
                                </ul>
                            </div>

                            <!-- Phụ kiện laptop -->
                            <div class="col-md-2">
                                <h4>Phụ kiện Laptop</h4>
                                <ul>
                                    <li>Chuột, bàn phím</li>
                                    <li>Balo Laptop | Túi chống sốc</li>
                                    <li>Phần mềm</li>
                                    <li>Webcam</li>
                                    <li>Giá đỡ</li>
                                    <li>Thảm, lót chuột</li>
                                    <li>Sạc laptop</li>
                                    <li>Camera phòng họp</li>
                                </ul>
                            </div>

                            <!-- Thiết bị mạng -->
                            <div class="col-md-2">
                                <h4>Thiết bị mạng</h4>
                                <ul>
                                    <li>Thiết bị phát sóng WiFi</li>
                                    <li>Bộ phát wifi di động</li>
                                    <li>Bộ kích sóng WiFi</li>
                                    <li>Xem tất cả thiết bị mạng</li>
                                </ul>

                                <h4 class="mt-4">Gaming Gear</h4>
                                <ul>
                                    <li>PlayStation</li>
                                    <li>ROG Ally</li>
                                    <li>MSI Claw</li>
                                    <li>Bàn phím Gaming</li>
                                    <li>Chuột chơi game</li>
                                    <li>Tai nghe Gaming</li>
                                    <li>Tay cầm chơi game</li>
                                    <li>Xem tất cả Gaming Gear</li>
                                </ul>
                            </div>

                            <!-- Phụ kiện khác -->
                            <div class="col-md-2">
                                <h4>Phụ kiện khác</h4>
                                <ul>
                                    <li>Dây đeo đồng hồ</li>
                                    <li>Dây đeo Airtag</li>
                                    <li>Phụ kiện tiện ích</li>
                                    <li>Phụ kiện ô tô</li>
                                    <li>Bút cảm ứng</li>
                                    <li>Thiết bị định vị</li>
                                </ul>

                                <h4 class="mt-4">Thiết bị lưu trữ</h4>
                                <ul>
                                    <li>Thẻ nhớ</li>
                                    <li>USB</li>
                                    <li>Ổ cứng di động</li>
                                </ul>
                            </div>

                            <!-- Phụ kiện hot -->
                            <div class="col-md-2">
                                <h4>Phụ kiện hot <span class="text-danger fw-bold">🔥</span></h4>
                                <ul>
                                    <li>Ốp lưng iPhone 16</li>
                                    <li>Dán màn hình iPhone 16</li>
                                    <li>Ốp lưng S25 Series</li>
                                    <li>Dán màn hình S25 Series</li>
                                    <li>Khăn lau màn hình Apple</li>
                                    <li>Cáp sạc iPhone 15</li>
                                    <li>Nhẫn thông minh 9Fit</li>
                                    <li>DJI Air 3</li>
                                    <li>Săn deal đồng giá</li>
                                </ul>
                            </div>


                        </div>
                    </div>
                    <div id="dropdown-pc" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Loại PC -->
                            <div class="col-md-2">
                                <h4>Loại PC</h4>
                                <ul>
                                    <li>Build PC</li>
                                    <li>Cấu hình sẵn</li>
                                    <li>All In One</li>
                                    <li>PC bộ</li>
                                </ul>

                                <h4 class="mt-4">Chọn theo nhu cầu</h4>
                                <ul>
                                    <li>Gaming</li>
                                    <li>Đồ họa</li>
                                    <li>Văn phòng</li>
                                </ul>
                            </div>

                            <!-- Linh kiện máy tính -->
                            <div class="col-md-2">
                                <h4>Linh kiện máy tính</h4>
                                <ul>
                                    <li>CPU</li>
                                    <li>Main</li>
                                    <li>RAM</li>
                                    <li>Ổ cứng</li>
                                    <li>Nguồn</li>
                                    <li>VGA</li>
                                    <li>Tản nhiệt</li>
                                    <li>Case</li>
                                    <li>Xem tất cả</li>
                                </ul>
                            </div>

                            <!-- Màn hình theo hãng -->
                            <div class="col-md-2">
                                <h4>Màn hình theo hãng</h4>
                                <ul>
                                    <li>ASUS</li>
                                    <li>Samsung</li>
                                    <li>DELL</li>
                                    <li>LG</li>
                                    <li>MSI</li>
                                    <li>Acer</li>
                                    <li>Xiaomi</li>
                                    <li>ViewSonic</li>
                                    <li>Philips</li>
                                    <li>AOC</li>
                                    <li>Dahua</li>
                                </ul>
                            </div>

                            <!-- Màn hình theo nhu cầu -->
                            <div class="col-md-2">
                                <h4>Màn hình theo nhu cầu</h4>
                                <ul>
                                    <li>Gaming</li>
                                    <li>Văn phòng</li>
                                    <li>Đồ họa</li>
                                    <li>Lập trình</li>
                                    <li>Màn hình di động</li>
                                    <li>Arm màn hình</li>
                                    <li>Xem tất cả</li>
                                </ul>
                            </div>

                            <!-- Gaming Gear -->
                            <div class="col-md-2">
                                <h4>Gaming Gear</h4>
                                <ul>
                                    <li>PlayStation</li>
                                    <li>ROG Ally</li>
                                    <li>Bàn phím Gaming</li>
                                    <li>Chuột chơi game</li>
                                    <li>Tai nghe Gaming</li>
                                    <li>Tay cầm chơi Game</li>
                                    <li>Xem tất cả</li>
                                </ul>
                            </div>

                            <!-- Thiết bị văn phòng -->
                            <div class="col-md-2">
                                <h4>Thiết bị văn phòng</h4>
                                <ul>
                                    <li>Máy in</li>
                                    <li>Phần mềm</li>
                                    <li>Decor bàn làm việc</li>
                                </ul>


                            </div>
                        </div>
                    </div>
                    <div id="dropdown-tivi" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Chọn theo hãng -->
                            <div class="col-md-2">
                                <h4>Chọn theo hãng</h4>
                                <ul>
                                    <li>Samsung</li>
                                    <li>LG</li>
                                    <li>Xiaomi</li>
                                    <li>Coocaa</li>
                                    <li>Sony</li>
                                    <li>Toshiba</li>
                                    <li>TCL</li>
                                    <li>Hisense</li>
                                    <li>Aqua <span class="text-danger fw-bold">HOT and NEW</span></li>
                                </ul>
                            </div>

                            <!-- Chọn theo mức giá -->
                            <div class="col-md-2">
                                <h4>Chọn theo mức giá</h4>
                                <ul>
                                    <li>Dưới 5 triệu</li>
                                    <li>Từ 5 - 9 triệu</li>
                                    <li>Từ 9 - 12 triệu</li>
                                    <li>Từ 12 - 15 triệu</li>
                                    <li>Trên 15 triệu</li>
                                </ul>
                            </div>

                            <!-- Chọn theo độ phân giải -->
                            <div class="col-md-2">
                                <h4>Chọn theo độ phân giải</h4>
                                <ul>
                                    <li>Tivi 4K</li>
                                    <li>Tivi 8K</li>
                                    <li>Tivi Full HD</li>
                                    <li>Tivi OLED</li>
                                    <li>Tivi QLED</li>
                                    <li>Android Tivi</li>
                                </ul>
                            </div>

                            <!-- Chọn theo kích thước -->
                            <div class="col-md-2">
                                <h4>Chọn theo kích thước</h4>
                                <ul>
                                    <li>Tivi 32 inch</li>
                                    <li>Tivi 43 inch</li>
                                    <li>Tivi 50 inch</li>
                                    <li>Tivi 55 inch</li>
                                    <li>Tivi 65 inch</li>
                                    <li>Tivi 70 inch</li>
                                    <li>Tivi 85 inch</li>
                                </ul>
                            </div>

                            <!-- Sản phẩm nổi bật -->
                            <div class="col-md-2">
                                <h4>Sản phẩm nổi bật <span class="text-danger fw-bold">⚡</span></h4>
                                <ul>
                                    <li>Tivi Samsung UHD 4K 55 inch</li>
                                    <li>Tivi NanoCell LG 4K 55 inch</li>
                                    <li>Tivi LG 4K 55 inch Evo OLED Pose</li>
                                    <li>Tivi Samsung QLED 4K 65 inch</li>
                                    <li>Tivi Samsung UHD 4K 65 inch 2024</li>
                                    <li>Tivi LG 43LM5750PTC FHD 43 inch</li>
                                    <li>Tivi Xiaomi A 4K 2025 55 inch</li>
                                    <li>Tivi Coocaa khung tranh QLED 4K 55 inch</li>
                                    <li>Tivi Coocaa 4K 55 inch</li>
                                </ul>
                            </div>


                        </div>
                    </div>
                    <div id="dropdown-trade" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Chọn theo hãng -->
                            <div class="col-md-3">
                                <h4>Chọn theo hãng</h4>
                                <ul>
                                    <li>Thu cũ iPhone</li>
                                    <li>Thu cũ Samsung</li>
                                    <li>Thu cũ Xiaomi</li>
                                    <li>Thu cũ Laptop</li>
                                    <li>Thu cũ Mac</li>
                                    <li>Thu cũ iPad</li>
                                    <li>Thu cũ đồng hồ</li>
                                    <li>Thu cũ Apple Watch</li>
                                </ul>
                            </div>

                            <!-- Sản phẩm trợ giá cao -->
                            <div class="col-md-3">
                                <h4>Sản phẩm trợ giá cao</h4>
                                <ul>
                                    <li>iPhone 16 Pro Max <span class="text-success">» 3 triệu</span></li>
                                    <li>iPhone 15 Pro Max <span class="text-success">» 3 triệu</span></li>
                                    <li>Galaxy S25 Ultra <span class="text-success">» 4 triệu</span></li>
                                    <li>Galaxy Z Fold 6 <span class="text-success">» 4 triệu</span></li>
                                    <li>Galaxy Z Flip 6 <span class="text-success">» 1.5 triệu</span></li>
                                    <li>Macbook <span class="text-success">» 3 triệu</span></li>
                                    <li>Laptop <span class="text-success">» 4 triệu</span></li>
                                </ul>
                            </div>

                            <!-- Sản phẩm giá thu cao -->
                            <div class="col-md-3">
                                <h4>Sản phẩm giá thu cao <span class="text-danger fw-bold">⚡</span></h4>
                                <ul>
                                    <li>iPhone 15 Pro Max</li>
                                    <li>iPhone 14 Pro Max</li>
                                    <li>iPhone 13 Pro Max</li>
                                    <li>Samsung Galaxy Z Fold 5</li>
                                    <li>Samsung Galaxy Z Flip 5</li>
                                    <li>Samsung Galaxy S24 Ultra</li>
                                    <li>Macbook Air M1</li>
                                </ul>
                            </div>


                        </div>
                    </div>
                    <div id="dropdown-used" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Cột 1: Loại sản phẩm cũ -->
                            <div class="col-md-2">
                                <h4>Chọn loại sản phẩm cũ</h4>
                                <ul>
                                    <li>Điện thoại cũ</li>
                                    <li>Máy tính bảng cũ</li>
                                    <li>Mac cũ</li>
                                    <li>Laptop cũ</li>
                                    <li>Tai nghe cũ</li>
                                    <li>Loa cũ</li>
                                    <li>Đồng hồ thông minh cũ</li>
                                    <li>Đồ gia dụng cũ</li>
                                    <li>Màn hình cũ</li>
                                    <li>Phụ kiện cũ</li>
                                    <li>Tivi cũ</li>
                                </ul>
                            </div>

                            <!-- Cột 2: iPhone cũ -->
                            <div class="col-md-2">
                                <h4>Chọn dòng iPhone cũ</h4>
                                <ul>
                                    <li>iPhone 16 series cũ</li>
                                    <li>iPhone 15 series cũ</li>
                                    <li>iPhone 14 series cũ</li>
                                    <li>iPhone 13 series cũ</li>
                                    <li>iPhone 12 series cũ</li>
                                    <li>iPhone 11 series cũ</li>
                                    <li>Xem tất cả iPhone cũ</li>
                                </ul>
                            </div>

                            <!-- Cột 3: Android cũ -->
                            <div class="col-md-2">
                                <h4>Điện thoại Android cũ</h4>
                                <ul>
                                    <li>Samsung cũ</li>
                                    <li>Xiaomi cũ</li>
                                    <li>OPPO cũ</li>
                                    <li>Nokia cũ</li>
                                    <li>realme cũ</li>
                                    <li>vivo cũ</li>
                                    <li>ASUS cũ</li>
                                    <li>TCL cũ</li>
                                    <li>Infinix cũ</li>
                                </ul>
                            </div>

                            <!-- Cột 4: Laptop cũ -->
                            <div class="col-md-2">
                                <h4>Chọn hãng laptop cũ</h4>
                                <ul>
                                    <li>Laptop Dell cũ</li>
                                    <li>Laptop ASUS cũ</li>
                                    <li>Laptop Acer cũ</li>
                                    <li>Laptop HP cũ</li>
                                    <li>Laptop Surface cũ</li>
                                </ul>
                            </div>

                            <!-- Cột 5: Sản phẩm nổi bật -->
                            <div class="col-md-2">
                                <h4>Sản phẩm nổi bật <span class="text-danger fw-bold">⚡</span></h4>
                                <ul>
                                    <li>iPhone 16 Pro Max - Cũ đẹp</li>
                                    <li>iPhone 15 Pro Max cũ đẹp</li>
                                    <li>iPhone 14 Pro Max cũ đẹp</li>
                                    <li>iPhone 13 Pro Max cũ đẹp</li>
                                    <li>Apple Watch SE 44mm 4G cũ đẹp</li>
                                    <li>S23 Ultra cũ đẹp</li>
                                    <li>S22 Ultra cũ đẹp</li>
                                    <li>S24 Ultra cũ</li>
                                </ul>
                            </div>

                            <!-- Cột 6: Apple cũ + banner -->
                            <div class="col-md-2">
                                <h4>Sản phẩm Apple cũ</h4>
                                <ul>
                                    <li>Apple Watch cũ</li>
                                    <li>iPad cũ</li>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div id="dropdown-promotion" class="dropdown-menu-custom">
                        <div class="row">
                            <!-- Cột 1: Khuyến mãi sản phẩm -->
                            <div class="col-md-6">
                                <h4>Khuyến mãi HOT</h4>
                                <ul>
                                    <li>Hotsale cuối tuần</li>
                                    <li>Ưu đãi thanh toán</li>
                                    <li>Khách hàng doanh nghiệp B2B</li>
                                    <li>Mua kèm gia dụng giảm 500K <span class="text-danger fw-bold">HOT and NEW</span>
                                    </li>
                                    <li>Thu cũ đổi mới giá hời</li>
                                    <li>iPhone 16 Series trợ giá đến 3 triệu</li>
                                    <li>S25 Series trợ giá 1 triệu</li>
                                    <li>Xiaomi 15 trợ giá đến 3 triệu</li>
                                    <li>Laptop trợ giá đến 4 triệu</li>
                                </ul>
                            </div>

                            <!-- Cột 2: Ưu đãi thành viên và sinh viên -->
                            <div class="col-md-6">
                                <h4>Ưu đãi thành viên & sinh viên</h4>
                                <ul>
                                    <li>Chính sách Smember 2025 <span class="text-danger fw-bold">HOT and NEW</span>
                                    </li>
                                    <li>Ưu đãi sinh viên</li>
                                    <li>Chào năm học mới - Ưu đãi khủng</li>
                                    <li>Nhập hội S-Student</li>
                                    <li>Đăng ký S-Student <span class="text-danger fw-bold">HOT and NEW</span></li>
                                    <li>Laptop giảm đến 500K</li>
                                    <li>Điện thoại giảm đến 6%</li>
                                    <li>Loa - tai nghe giảm thêm 5%</li>
                                    <li>Hàng cũ giảm thêm 10% <span class="text-danger fw-bold">HOT and NEW</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="dropdown-news" class="dropdown-menu-custom">
                        <div class="row">
                            <div class="col-md-3">
                                <h4>Chuyên mục</h4>
                                <ul>
                                    <li>Tin công nghệ</li>
                                    <li>Khám phá</li>
                                    <li>S-Games</li>
                                    <li>Tư vấn</li>
                                    <li>Trên tay</li>
                                    <li>Thị trường</li>
                                    <li>Thủ thuật - Hỏi đáp</li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div data-fetch-key="SlidingBanner:0" class="block-top-home__sliding-banner">
            <div class="block-sliding-home">
                <div class="swiper-container gallery-top">
                    <div class="swiper-wrapper">
                        <!-- Thay các swiper-slide bên trong block-sliding-home -->
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/z7-f7-pre-home.png"
                                width="690" height="200" alt="GALAXY Z7&lt;br /&gt;Đặt trước ngay"
                                loading="lazy"></div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/Sliding-iphone-v3.png"
                                width="690" height="200" alt="IPHONE 16 PRO MAX &lt;br /&gt; Mua ngay"
                                loading="lazy">
                        </div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/reno14-home.png"
                                width="690" height="200" alt="OPPO RENO14&lt;br /&gt;Mua ngay" loading="lazy">
                        </div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/home-watch8.jpg"
                                width="690" height="200" alt="GALAXY WATCH8 &lt;br /&gt; Đặt trước ngay"
                                loading="lazy">
                        </div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/redmipad2-home.png"
                                width="690" height="200" alt="REDMI PAD 2&lt;br /&gt;Giá chỉ 5.39 triệu"
                                loading="lazy">
                        </div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/nothing-phone-3a-sliding-0625.png"
                                width="690" height="200" alt="NOTHING PHONE&lt;br /&gt;Giá tốt chỉ có tại CPS"
                                loading="lazy"></div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/meizu-lucky-08-sliding-home-1.png"
                                width="690" height="200" alt="MEIZU LUCKY 8&lt;br /&gt;Giá chỉ 5.49 triệu"
                                loading="lazy"></div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/Tecno-pova-7-HOME.jpg"
                                width="690" height="200" alt="TECNO POVA 7&lt;br /&gt;Giá chỉ 3.99 triệu"
                                loading="lazy"></div>
                        <div class="swiper-slide"><img
                                src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/Home (2).jpg"
                                width="690" height="300" alt="MUA ĐIỆN THOẠI&lt;br /&gt;Tặng combo voucher"
                                loading="lazy"></div>
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
                            <div>REDMI PAD 2<br>Giá chỉ 5.39 triệu</div>
                        </div>
                        <div class="swiper-slide swiper-slide-visible" style="width: 157.429px;">
                            <div>NOTHING PHONE<br>Giá tốt chỉ có tại CPS</div>
                        </div>
                        <div class="swiper-slide" style="width: 157.429px;">
                            <div>MEIZU LUCKY 8<br>Giá chỉ 5.49 triệu</div>
                        </div>
                        <div class="swiper-slide" style="width: 157.429px;">
                            <div>TECNO POVA 7<br>Giá chỉ 3.99 triệu</div>
                        </div>
                        <div class="swiper-slide" style="width: 157.429px;">
                            <div>MUA ĐIỆN THOẠI<br>Tặng combo voucher</div>
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
                    <img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:10/plain/https://dashboard.cellphones.com.vn/storage/RightBanner-ipad-b2s-2025.png"
                        width="690" height="300" alt="B2S IPAD" class="right-banner__img">
                </div>
                <div class="right-banner__item button__link">
                    <img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:10/plain/https://dashboard.cellphones.com.vn/storage/rightbanner-lap-b2s.png"
                        width="690" height="300" alt="B2S Laptop" class="right-banner__img">
                </div>
            </div>
        </div>
    </div>
</div>

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
