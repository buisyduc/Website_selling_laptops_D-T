<style>
    .menu-wrapper {
        position: relative;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #eee;
        display: flex;
    }

    /* Categories left */
    .menu-categories {
        width: 240px;
        background: #fff;
        border-right: 1px solid #eee;
        font-family: Arial, sans-serif;
    }

    .menu-categories ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .menu-categories li {
        display: flex;
        align-items: center;
        justify-content: space-between; /* icon + text bên trái, arrow sang phải */
        padding: 12px 16px;
        cursor: pointer;
        font-size: 14px;
        border-bottom: 1px solid #f7f7f7;
        transition: background 0.2s, color 0.2s;
    }

    .menu-categories li:last-child {
        border-bottom: none;
    }

    .menu-categories li:hover {
        background: #f9f9f9;
        color: #e60012;
    }

    .menu-categories li i.fa {
        color: #555;
        font-size: 16px;
    }

    .menu-categories li span {
        flex: 1; /* chiếm hết khoảng trống giữa icon và arrow */
        margin-left: 10px; /* cách icon một chút */
        color: #333;
    }

    .menu-categories li .arrow {
        margin-left: auto; /* đẩy arrow sát phải */
        color: #aaa;
        font-size: 14px;
        flex-shrink: 0;
    }

    /* Mega content */
    .menu-content {
        position: absolute;
        top: 0;
        left: 240px;
        width: calc(100% - 240px);
        min-height: 100%;
        background: #fff;
        display: none;
        padding: 20px;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        z-index: 50;
    }

    .menu-content.active {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .menu-column h3 {
        font-size: 15px;
        margin-bottom: 10px;
        color: #e60012;
    }

    .menu-column ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .menu-column li {
        font-size: 14px;
        padding: 5px 0;
        cursor: pointer;
        color: #333;
    }

    .menu-column li:hover {
        color: #e60012;
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .menu-wrapper {
            display: block;
        }

        .menu-categories {
            width: 100%;
            border: 1px solid #ddd;
        }

        .menu-content {
            position: relative;
            left: unset;
            width: 100%;
            box-shadow: none;
        }

        .menu-content.active {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Banner mặc định */
    .menu-content.banner-default {
        position: absolute;
        top: 0;
        left: 240px; /* đúng bằng chiều rộng menu */
        width: calc(100% - 240px);
        height: 100%;
        padding: 0; /* bỏ padding để ảnh sát cạnh */
        background-size: cover;
        background-position: center;
        border-radius: 0;
        box-shadow: none;
    }

    /* Nếu dùng slide bên trong banner */
    .menu-content.banner-default .swiper,
    .menu-content.banner-default .swiper-wrapper,
    .menu-content.banner-default .swiper-slide {
        width: 100%;
        height: 100%;
    }

    .menu-content.banner-default img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* ảnh auto co theo khung */
    }

    /* Hiệu ứng mượt khi đổi ảnh */
    .menu-content.banner-default.fade {
        animation: fadeEffect 1.5s ease-in-out;
    }

    @keyframes fadeEffect {
        from {
            opacity: 0.4;
        }
        to {
            opacity: 1;
        }
    }

    /* PC (>=769px) */
    @media (min-width: 769px) {
        .menu-toggle {
            display: none; /* Ẩn nút Danh mục */
        }

        .menu-categories {
            display: block !important; /* Giữ menu hiển thị sẵn */
        }

        .menu-content.banner-default {
            display: block; /* Giữ banner */
        }
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .menu-wrapper {
            position: relative;
            max-width: 100%;
            border: none;
            display: block;
        }

        /* Ẩn menu categories mặc định */
        .menu-categories {
            display: none;
            width: 100%;
            border-top: 1px solid #ddd;
            background: #fff;
        }

        .menu-categories.active {
            display: block; /* Hiện khi bật */
        }

        .menu-categories li {
            padding: 12px 16px;
            font-size: 15px;
        }

        /* Banner chiếm full */
        .menu-content.banner-default {
            position: relative;
            width: 100% !important;
            height: 200px;
            background-size: cover !important;
            background-position: center !important;
            margin: 0;
        }

        /* Nút toggle menu */
        .menu-toggle {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 12px 16px;
            background: #e60012;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        .menu-toggle i {
            margin-right: 8px;
            font-size: 18px;
        }

        /* Ẩn mega content trong mobile */
        .menu-content {
            display: none !important;
        }
    }


</style>

<div class="menu-wrapper">

    <div class="menu-toggle">
        <i class="fa fa-bars"></i> Danh mục
    </div>

    <!-- DANH MỤC BÊN TRÁI -->
    <nav class="menu-categories">
        <ul>
            <li data-target="dien-thoai">
                <i class="fa fa-mobile-alt"></i>
                <span><a class="text-dark" href="/dien-thoai">Điện thoại, Tablet</a></span>
                <i class="fa fa-angle-right arrow"></i>
            </li>
            <li data-target="laptop">
                <i class="fa fa-laptop"></i>
                <span><a class="text-dark" href="/laptop">Laptop</a></span>
                <i class="fa fa-angle-right arrow"></i>
            </li>
            <li data-target="am-thanh">
                <i class="fa fa-headphones"></i>
                <span><a class="text-dark" href="/am-thanh">Âm thanh, Mic</a></span>
                <i class="fa fa-angle-right arrow"></i>
            </li>
            <li data-target="dong-ho">
                <i class="fa fa-clock"></i>
                <span><a class="text-dark" href="/dong-ho">Đồng hồ, Camera</a></span>
                <i class="fa fa-angle-right arrow"></i>
            </li>
            <li data-target="gia-dung">
                <i class="fa fa-blender"></i>
                <span><a class="text-dark" href="/gia-dung">Đồ gia dụng</a></span>
                <i class="fa fa-angle-right arrow"></i>
            </li>
            <li data-target="pc">
                <i class="fa fa-desktop"></i>
                <span><a class="text-dark" href="/pc">PC, Màn hình</a></span>
                <i class="fa fa-angle-right arrow"></i>
            </li>
            <li data-target="tivi">
                <i class="fa fa-tv"></i>
                <span><a class="text-dark" href="/tivi">Tivi</a></span>
                <i class="fa fa-angle-right arrow"></i>
            </li>
            <li data-target="phu-kien">
                <i class="fa fa-plug"></i>
                <span><a class="text-dark" href="/phu-kien">Phụ kiện</a></span>
                <i class="fa fa-angle-right arrow"></i>
            </li>
            <li data-target="uu-dai">
                <i class="fa fa-tags"></i>
                <span><a class="text-dark" href="/uu-dai">Ưu đãi HOT</a></span>
                <i class="fa fa-angle-right arrow"></i>
            </li>
        </ul>
    </nav>

    <div class="menu-content banner-default active"
         style="width:70%; height:auto;
            background:url('{{asset('client/img/1920X1080/img1.jpg')}}') no-repeat center center;
            background-size:contain; border-radius:0;">
    </div>


    <!-- Điện thoại, Tablet -->
    <div class="menu-content" id="dien-thoai">
        <div class="menu-column">
            <h3>Thương hiệu</h3>
            <ul>
                <li><a class="text-dark" href="/dien-thoai/iphone">iPhone</a></li>
                <li><a class="text-dark" href="/dien-thoai/samsung">Samsung</a></li>
                <li><a class="text-dark" href="/dien-thoai/xiaomi">Xiaomi</a></li>
                <li><a class="text-dark" href="/dien-thoai/oppo">OPPO</a></li>
                <li><a class="text-dark" href="/dien-thoai/vivo">vivo</a></li>
                <li><a class="text-dark" href="/dien-thoai/realme">Realme</a></li>
                <li><a class="text-dark" href="/dien-thoai/tecno">TECNO</a></li>
                <li><a class="text-dark" href="/dien-thoai/nokia">Nokia</a></li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Tablet</h3>
            <ul>
                <li>iPad</li>
                <li>Samsung Galaxy Tab</li>
                <li>Xiaomi Pad</li>
                <li>Lenovo</li>
                <li>Huawei</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Theo giá</h3>
            <ul>
                <li>Dưới 5 triệu</li>
                <li>5 - 10 triệu</li>
                <li>10 - 20 triệu</li>
                <li>Trên 20 triệu</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Nhu cầu</h3>
            <ul>
                <li>Chơi game</li>
                <li>Chụp ảnh</li>
                <li>Pin trâu</li>
                <li>Học tập / Văn phòng</li>
            </ul>
        </div>
    </div>

    <!-- Laptop -->
    <div class="menu-content" id="laptop">
        <div class="menu-column">
            <h3>Thương hiệu</h3>
            <ul>
                <li>MacBook</li>
                <li>Dell</li>
                <li>HP</li>
                <li>Asus</li>
                <li>Acer</li>
                <li>Lenovo</li>
                <li>MSI</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Nhu cầu</h3>
            <ul>
                <li>Học tập - Văn phòng</li>
                <li>Gaming</li>
                <li>Đồ họa - Kỹ thuật</li>
                <li>Mỏng nhẹ - Cao cấp</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Theo giá</h3>
            <ul>
                <li>Dưới 15 triệu</li>
                <li>15 - 25 triệu</li>
                <li>Trên 25 triệu</li>
            </ul>
        </div>
    </div>

    <!-- Âm thanh, Mic -->
    <div class="menu-content" id="am-thanh">
        <div class="menu-column">
            <h3>Tai nghe</h3>
            <ul>
                <li>Tai nghe Bluetooth</li>
                <li>Tai nghe Gaming</li>
                <li>Tai nghe chụp tai</li>
                <li>Tai nghe có dây</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Loa</h3>
            <ul>
                <li>Loa Bluetooth</li>
                <li>Loa Karaoke</li>
                <li>Loa vi tính</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Micro</h3>
            <ul>
                <li>Micro thu âm</li>
                <li>Micro Karaoke</li>
                <li>Micro không dây</li>
            </ul>
        </div>
    </div>

    <!-- Đồng hồ, Camera -->
    <div class="menu-content" id="dong-ho">
        <div class="menu-column">
            <h3>Smartwatch</h3>
            <ul>
                <li>Apple Watch</li>
                <li>Samsung Galaxy Watch</li>
                <li>Xiaomi</li>
                <li>Huawei</li>
                <li>Garmin</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Đồng hồ thời trang</h3>
            <ul>
                <li>Nam</li>
                <li>Nữ</li>
                <li>Trẻ em</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Camera</h3>
            <ul>
                <li>Camera hành trình</li>
                <li>Camera an ninh</li>
                <li>Webcam</li>
            </ul>
        </div>
    </div>

    <!-- Đồ gia dụng -->
    <div class="menu-content" id="gia-dung">
        <div class="menu-column">
            <h3>Nhà bếp</h3>
            <ul>
                <li>Nồi chiên không dầu</li>
                <li>Nồi cơm điện</li>
                <li>Bếp điện từ</li>
                <li>Máy xay sinh tố</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Vệ sinh</h3>
            <ul>
                <li>Robot hút bụi</li>
                <li>Máy hút bụi</li>
                <li>Cây lau nhà điện</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Sức khỏe</h3>
            <ul>
                <li>Máy lọc không khí</li>
                <li>Máy lọc nước</li>
                <li>Máy massage</li>
            </ul>
        </div>
    </div>

    <!-- PC, Màn hình -->
    <div class="menu-content" id="pc">
        <div class="menu-column">
            <h3>PC</h3>
            <ul>
                <li>PC Gaming</li>
                <li>PC Văn phòng</li>
                <li>PC All-in-one</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Màn hình</h3>
            <ul>
                <li>Màn hình Gaming</li>
                <li>Màn hình Đồ họa</li>
                <li>Màn hình Văn phòng</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Linh kiện</h3>
            <ul>
                <li>CPU</li>
                <li>RAM</li>
                <li>Ổ cứng SSD / HDD</li>
                <li>Card màn hình</li>
                <li>Mainboard</li>
                <li>Nguồn máy tính</li>
            </ul>
        </div>
    </div>

    <!-- Tivi -->
    <div class="menu-content" id="tivi">
        <div class="menu-column">
            <h3>Loại TV</h3>
            <ul>
                <li>Smart TV</li>
                <li>Android TV</li>
                <li>Google TV</li>
                <li>TV thường</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Kích thước</h3>
            <ul>
                <li>Dưới 43 inch</li>
                <li>43 - 55 inch</li>
                <li>Trên 55 inch</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Thương hiệu</h3>
            <ul>
                <li>Samsung</li>
                <li>LG</li>
                <li>Sony</li>
                <li>Xiaomi</li>
                <li>TCL</li>
            </ul>
        </div>
    </div>

    <!-- Phụ kiện -->
    <div class="menu-content" id="phu-kien">
        <div class="menu-column">
            <h3>Sạc & Cáp</h3>
            <ul>
                <li>Sạc dự phòng</li>
                <li>Cáp sạc</li>
                <li>Củ sạc nhanh</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Bảo vệ</h3>
            <ul>
                <li>Ốp lưng</li>
                <li>Dán màn hình</li>
                <li>Miếng dán camera</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Thiết bị khác</h3>
            <ul>
                <li>Balo - Túi chống sốc</li>
                <li>Chuột - Bàn phím</li>
                <li>USB - Ổ cứng</li>
            </ul>
        </div>
    </div>

    <!-- Ưu đãi HOT -->
    <div class="menu-content" id="uu-dai">
        <div class="menu-column">
            <h3>Khuyến mãi</h3>
            <ul>
                <li>Giảm giá sốc</li>
                <li>Trả góp 0%</li>
                <li>Đổi cũ lấy mới</li>
                <li>Voucher giảm thêm</li>
            </ul>
        </div>
        <div class="menu-column">
            <h3>Ưu đãi theo hãng</h3>
            <ul>
                <li>iPhone</li>
                <li>Samsung</li>
                <li>Xiaomi</li>
                <li>OPPO</li>
            </ul>
        </div>
    </div>

</div>

<script>
    const categoryItems = document.querySelectorAll(".menu-categories li");
    const contentBlocks = document.querySelectorAll(".menu-content");
    const banner = document.querySelector(".banner-default");

    categoryItems.forEach(item => {
        item.addEventListener("mouseenter", () => {
            const target = item.getAttribute("data-target");
            contentBlocks.forEach(block => {
                block.classList.remove("active");
                if (block.id === target) {
                    block.classList.add("active");
                }
            });
        });
    });

    document.querySelector(".menu-wrapper").addEventListener("mouseleave", () => {
        contentBlocks.forEach(block => block.classList.remove("active"));
        banner.classList.add("active");
    });

    document.addEventListener("DOMContentLoaded", function () {
        const banner = document.querySelector(".menu-content.banner-default");
        const images = [
            "{{asset('client/img/1920X1080/img1.jpg')}}",
            "{{asset('client/img/1920X1080/img2.jpg')}}",
            "{{asset('client/img/1920X1080/img3.jpg')}}"
        ];

        let index = 0;

        function changeBanner() {
            index = (index + 1) % images.length;
            banner.style.backgroundImage = `url('${images[index]}')`;
            banner.classList.add("fade");
            setTimeout(() => banner.classList.remove("fade"), 1500);
        }

        setInterval(changeBanner, 5000); // đổi ảnh mỗi 5 giây
    });

    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu-categories");

        toggleBtn.addEventListener("click", function () {
            menu.classList.toggle("active");
        });
    });

</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
