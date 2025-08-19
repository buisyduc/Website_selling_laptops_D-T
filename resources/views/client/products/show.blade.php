@extends('client.layouts.layout')

@section('content')

    <div class="container">
        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                @foreach ($breadcrumbs as $category)
                    <li class="breadcrumb-item">{{ $category->name }}</li>
                @endforeach
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        @if (session('message'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('Thông báo', @json(session('message')), 'success');
                });
            </script>
        @endif



        <!-- PRODUCT CONTENT -->
        <div class="row gx-3">

            <!-- GALLERY SECTION -->
            <div class="col-md-5">
                <!-- PRODUCT HEADER -->
                <div class="mb-3 flex items-center space-x-2 text-sm">
                    <h1 class="h4 fw-bold">{{ $product->name }}</h1>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="text-warning">
                            @php
                                $avgRating = $product->average_rating ?? 0;
                            @endphp

                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($avgRating))
                                    <i class="fas fa-star"></i>
                                @elseif($i == ceil($avgRating) && $avgRating - floor($avgRating) > 0)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span>{{ $product->reviews_count ?? 0 }} đánh giá</span>
                        <span>| Đã bán: {{ $product->totalSold() }}</span>
                    </div>
                </div>
                <!-- ẢNH CHÍNH + COUPON -->


                <!-- ẢNH CHÍNH -->
                <div class="position-relative mb-3">
                    @php
                        $activeCoupon = $product->coupons
                            ->where('status', 'active')
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now())
                            ->first();
                    @endphp

                    @if ($activeCoupon)
                        <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 rounded-pill z-2">
                            -{{ $activeCoupon->discount_type == 'percentage'
                                ? $activeCoupon->discount_value . '%'
                                : number_format($activeCoupon->discount_value, 0, ',', '.') . '₫' }}
                        </span>
                    @endif

                    <div class="border rounded-4 p-2 bg-white">
                        <img id="mainImage"
                            src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x350/e3f2fd/1976d2?text=' . urlencode($product->name) }}"
                            alt="{{ $product->name }}" class="img-fluid w-100"
                            style="max-height: 320px; object-fit: contain;">
                    </div>
                </div>

                <!-- THUMBNAIL CÓ CUỘN -->
                @if ($product->images->count() > 0)
                    <div class="position-relative mb-4">
                        <!-- Nút trái -->
                        <button class="position-absolute start-0 top-50 translate-middle-y z-3 border-0 bg-transparent"
                            onclick="scrollThumbnails(-1)">
                            <i class="bi bi-chevron-left fs-4 text-dark"></i>
                        </button>

                        <!-- Container cuộn ảnh -->
                        <div class="overflow-hidden mx-auto" style="max-width: 540px;">
                            <div id="thumbnailContainer" class="d-flex flex-nowrap gap-2 overflow-auto px-1">
                                <!-- Main -->
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/100x80/e3f2fd/1976d2?text=1' }}"
                                    alt="Main View" class="img-thumbnail border-primary"
                                    style="width: 100px; height: 80px; object-fit: cover; cursor: pointer;"
                                    onclick="changeImage(this, '{{ $product->image ? asset('storage/' . $product->image) : '' }}')">

                                <!-- Các ảnh phụ -->
                                @foreach ($product->images as $index => $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="View {{ $index + 2 }}"
                                        class="img-thumbnail"
                                        style="width: 100px; height: 80px; object-fit: cover; cursor: pointer;"
                                        onclick="changeImage(this, '{{ asset('storage/' . $image->image_path) }}')">
                                @endforeach
                            </div>
                        </div>

                        <!-- Nút phải -->

                        <button class="position-absolute end-0 top-50 translate-middle-y z-3 border-0 bg-transparent"
                            onclick="scrollThumbnails(1)">
                            <i class="bi bi-chevron-right fs-4 text-dark"></i>
                        </button>

                    </div>
                @endif



                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

                <div class=" my-2">
                    <h5 class="fw-bold">Cam kết sản phẩm</h5>
                    <div class="row row-cols-1 row-cols-md-2 g-3 my-2">
                        <!-- Box 1 -->
                        <div class="col">
                            <div class="d-flex gap-3 align-items-start p-3 rounded-4 shadow-sm bg-light h-100">
                                <div class="d-inline-flex justify-content-center align-items-center rounded-3"
                                    style="width: 20px; height: 20px; background: linear-gradient(to bottom, #D5101A, #B2060F);">
                                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0)">
                                            <path
                                                d="M8.625 15.75H6C5.60218 15.75 5.22064 15.592 4.93934 15.3107C4.65804 15.0294 4.5 14.6478 4.5 14.25V3.75C4.5 3.35218 4.65804 2.97064 4.93934 2.68934C5.22064 2.40804 5.60218 2.25 6 2.25H12C12.3978 2.25 12.7794 2.40804 13.0607 2.68934C13.342 2.97064 13.5 3.35218 13.5 3.75V10.875M8.25 3H9.75M9 12.75V12.7575M11.25 14.25L12.75 15.75L15.75 12.75"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0">
                                                <rect width="18" height="18" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>

                                <div class="small">
                                    Nguyên hộp, đầy đủ phụ kiện từ nhà sản xuất<br>
                                    Bảo hành pin và bộ sạc 12 tháng
                                </div>
                            </div>
                        </div>

                        <!-- Box 2 -->
                        <div class="col">
                            <div class="d-flex gap-3 align-items-start p-3 rounded-4 shadow-sm bg-light h-100">
                                <div class="d-flex justify-content-center align-items-center rounded-3"
                                    style="background-color: #E41727;width: 20px; height: 20px; ">
                                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_2)">
                                            <path
                                                d="M8.59506 15.6345C6.33703 14.9346 4.44505 13.3743 3.32797 11.2908C2.21089 9.20739 1.9584 6.76808 2.62506 4.5C4.96171 4.60692 7.24819 3.79993 9.00006 2.25C10.7519 3.79993 13.0384 4.60692 15.3751 4.5C15.8841 6.23183 15.8605 8.07671 15.3076 9.795M11.2501 14.25L12.7501 15.75L15.7501 12.75"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_2">
                                                <rect width="18" height="18" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>

                                <div class="small">
                                    Bảo hành 12 tháng tại trung tâm bảo hành Chính hãng. 1 đổi 1 trong 30 ngày nếu có lỗi
                                    phần cứng từ nhà sản xuất.
                                    <a href="#" class="text-primary text-decoration-none fw-bold">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>

                        <!-- Box 3 -->
                        <div class="col">
                            <div class="d-flex gap-3 align-items-start p-3 rounded-4 shadow-sm bg-light h-100">
                                <div class="d-flex justify-content-center align-items-center rounded-3"
                                    style="background-color: #E41727; width: 20px; height: 20px; ">
                                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.25 7.5H3.75M2.25 10.5H3.75M7.5 2.25V3.75M10.5 2.25V3.75M15.75 7.5H14.25M15.75 10.5H14.25M10.5 15.75V14.25M7.5 15.75V14.25M5.75 14.25H12.25C13.3546 14.25 14.25 13.3546 14.25 12.25V5.75C14.25 4.64543 13.3546 3.75 12.25 3.75H5.75C4.64543 3.75 3.75 4.64543 3.75 5.75V12.25C3.75 13.3546 4.64543 14.25 5.75 14.25ZM7.75 11.25H10.25C10.8023 11.25 11.25 10.8023 11.25 10.25V7.75C11.25 7.19772 10.8023 6.75 10.25 6.75H7.75C7.19772 6.75 6.75 7.19772 6.75 7.75V10.25C6.75 10.8023 7.19772 11.25 7.75 11.25Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <div class="small">
                                    Bộ nguồn, máy, sách hdsd
                                </div>
                            </div>
                        </div>

                        <!-- Box 4 -->
                        <div class="col">
                            <div class="d-flex gap-3 align-items-start p-3 rounded-4 shadow-sm bg-light h-100">
                                <div class="d-flex justify-content-center align-items-center rounded-3"
                                    style="background-color: #E41727; width: 20px; height: 20px; ">
                                    <svg width="24" height="24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13.5 14.25L14.694 13.056C15.3718 12.3781 15.7526 11.4587 15.7526 10.5C15.7526 9.54134 15.3718 8.62193 14.694 7.944L11.25 4.5M5.25 7.5H5.2425M2.25 6V9.129C2.25008 9.52679 2.40818 9.90826 2.6895 10.1895L6.972 14.472C7.31096 14.8109 7.77067 15.0013 8.25 15.0013C8.72933 15.0013 9.18904 14.8109 9.528 14.472L12.222 11.778C12.5609 11.439 12.7513 10.9793 12.7513 10.5C12.7513 10.0207 12.5609 9.56096 12.222 9.222L7.9395 4.9395C7.65826 4.65818 7.27679 4.50008 6.879 4.5H3.75C3.35218 4.5 2.97064 4.65804 2.68934 4.93934C2.40804 5.22064 2.25 5.60218 2.25 6Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <div class="small">
                                    Giá sản phẩm <strong>đã bao gồm thuế VAT</strong>, giúp bạn yên tâm và dễ dàng trong
                                    việc tính toán chi phí.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" my-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold">Thông số kỹ thuật</h5>
                        <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center">
                            Xem tất cả
                            <svg class="ms-1" width="12" height="12" viewBox="0 0 320 512"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor"
                                    d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z" />
                            </svg>
                        </button>
                    </div>

                    <div id="thong-so-ky-thuat" class="my-4 cps-block-technicalInfo mb-3 bg-white  shadow-sm"
                        style="
                        border: 1px solid rgba(0, 0, 0, 0.2);
                        border-radius: 12px;
                        background-color: #f2f1f1 ;
                        transition: 0.2s ease;
                    ">

                        <table class="technical-content table mb-0">
                            <tbody>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold w-25">Loại card đồ họa</td>
                                    <td>
                                        <p class="mb-0">NVIDIA GeForce RTX 2050 (4GB of GDDR6 SDRAM, Bus Width: 64-bit)
                                            <br> Intel UHD Graphics
                                        </p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Dung lượng RAM</td>
                                    <td>
                                        <p class="mb-0">16GB</p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Loại RAM</td>
                                    <td>
                                        <p class="mb-0">DDR4 3200</p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Số khe ram</td>
                                    <td>
                                        <p class="mb-0">Máy nguyên bản 2x8GB, nâng cấp tối đa 32GB</p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Ổ cứng</td>
                                    <td>
                                        <p class="mb-0">512GB SSD PCIe (M.2 2280)</p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Kích thước màn hình</td>
                                    <td>
                                        <p class="mb-0">15.6 inches</p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Công nghệ màn hình</td>
                                    <td>
                                        <p class="mb-0">Màn hình chống chói</p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Pin</td>
                                    <td>
                                        <p class="mb-0">3 Cell Int (52.5Wh)</p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Hệ điều hành</td>
                                    <td>
                                        <p class="mb-0">Windows 11 Home Single Language 64-bit</p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Độ phân giải màn hình</td>
                                    <td>
                                        <p class="mb-0">1920 x 1080 pixels (FullHD)</p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Loại CPU</td>
                                    <td>
                                        <p class="mb-0">Intel Core i5-12450H (8 cores) - Max Turbo Frequency: 4.40 GHz
                                        </p>
                                    </td>
                                </tr>
                                <tr class="technical-content-item">
                                    <td class="fw-semibold">Cổng giao tiếp</td>
                                    <td>
                                        <p class="mb-0">
                                            1 x USB 3.2 Gen 1 Type-C (hỗ trợ DisplayPort, HP Sleep and Charge) <br>
                                            2 x USB 3.2 Gen 1 Type-A (một cổng hỗ trợ HP Sleep and Charge)<br>
                                            1 x HDMI<br>
                                            1 x RJ45 (LAN)<br>
                                            1 x giắc cắm âm thanh kết hợp tai nghe/micrô
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- INFO SECTION -->
            <div class="col-md-7 ">
                <!-- PRICE -->
                <div class="border rounded-4 px-2 py-2 mb-2"
                    style="border-color: #609afa; background-color: #c8d9f5; display: inline-block;">
                    <div class="price-label fw-bold">Giá sản phẩm</div>
                    <div class="d-flex align-items-baseline gap-3">
                        <!-- Hiển thị giá -->
                        <span class="fs-3 fw-bold text-dark" id="currentPrice">
                            {{ number_format($product->variants->first()->price) }}₫
                        </span>

                        <!-- Giá gạch ngang (giá cũ) -->
                        <del class="text-muted small">34.590.000₫</del>
                    </div>

                    <!-- Thêm giá mặc định (ẩn đi) -->
                    <span id="default-price" style="display: none;">
                        {{ number_format($product->variants->first()->price) }}₫
                    </span>
                </div>

                @php
                    $wgroupedAttributes = [];

                    foreach ($product->variants as $variant) {
                        foreach ($variant->options as $option) {
                            $attrId = $option->attribute->id;
                            $attrName = $option->attribute->name;
                            $optId = $option->option->id;
                            $optValue = $option->option->value;

                            if (!isset($groupedAttributes[$attrId])) {
                                $groupedAttributes[$attrId] = [
                                    'name' => $attrName,
                                    'options' => [],
                                ];
                            }

                            // Tránh lặp lại option cùng giá trị
                            $groupedAttributes[$attrId]['options'][$optId] = $optValue;
                        }
                    }
                @endphp
                @php
                    $colorAttributeId = null;

                    // Tìm attribute có tên "Màu sắc"
                    foreach ($attributeOptionsWithPrices as $attrId => $attribute) {
                        if (strtolower($attribute['name']) === 'màu sắc') {
                            $colorAttributeId = $attrId;
                            break;
                        }
                    }
                @endphp

                @if ($colorAttributeId && isset($attributeOptionsWithPrices[$colorAttributeId]))
                    <div class="box-product-variants mb-4">
                        <div class="box-title fw-bold mb-2">Màu sắc</div>
                        <div class="box-content d-flex flex-wrap gap-3">
                            @foreach ($attributeOptionsWithPrices[$colorAttributeId]['options'] as $optId => $option)
                                <div class="option-box color-option-box position-relative d-flex align-items-center"
                                    data-attribute="{{ $colorAttributeId }}" data-option="{{ $optId }}"
                                    onclick="selectOption(this)"
                                    style="
                                        width: 230px;
                                        min-height: 70px;
                                        cursor: pointer;
                                        padding: 10px 12px;
                                        border: 1px solid rgba(0, 0, 0, 0.2);
                                        border-radius: 12px;
                                        background-color: #fff;
                                        opacity: {{ $option['stock'] === 0 ? '0.4' : '1' }};
                                        pointer-events: {{ $option['stock'] === 0 ? 'none' : 'auto' }};
                                        transition: 0.2s ease;
                                    ">
                                    {{-- Ảnh bên trái --}}
                                    <img src="{{ $option['image'] ?? ($product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/80x60') }}"
                                        alt="{{ $option['value'] }}" class="me-3"
                                        style="width: 55px; height: 50px; object-fit: cover; border-radius: 6px;">

                                    {{-- Tên và giá bên phải --}}
                                    <div class="text-start">
                                        <div class="fw-bold">{{ $option['value'] }}</div>
                                        @if (!empty($option['price']))
                                            <div style="font-size: 14px;" class=" fw-semibold">
                                                {{ number_format($option['price'], 0, ',', '.') }}₫
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif



                <div class="p-2"
                    style="
                        border: 1px solid rgba(0, 0, 0, 0.2);
                        border-radius: 12px;
                        background-color: #f2f1f1 ;
                        transition: 0.2s ease;
                    ">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong class="fs-5">Lựa chọn cấu hình tùy chỉnh</strong>
                        <a href="#" id="reset-options" class="text-primary text-decoration-none">🔄 Thiết lập
                            lại</a>
                    </div>

                    @foreach ($attributeOptionsWithPrices as $attrId => $attribute)
                        @continue(strtolower($attribute['name']) === 'màu sắc')

                        <div class="mb-3">
                            <label class="fw-semibold d-block mb-2">{{ $attribute['name'] }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($attribute['options'] as $optId => $option)
                                    <div class="option-box px-3 py-2 border rounded text-center"
                                        data-attribute="{{ $attrId }}" data-option="{{ $optId }}"
                                        onclick="selectOption(this)"
                                        style="
                                            min-width: 120px;
                                            cursor: pointer;
                                            opacity: {{ $option['stock'] === 0 ? '0.5' : '1' }};
                                            pointer-events: {{ $option['stock'] === 0 ? 'none' : 'auto' }};
                                            border: 1px solid transparent;
                                            background-color: #ffffff;
                                        ">
                                        <div class="fw-bold">{{ $option['value'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                </div>

                <input type="hidden" id="selected-variant-id" name="variant_id">


                <div class="p-2 my-2 rounded-3 d-flex align-items-center justify-content-between "
                    style="
                        border-radius: 12px;
                        background-color: #f2f1f1 ;
                        transition: 0.2s ease;
                    ">

                    {{-- Icon bên trái --}}
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; background-color: #d70018;">
                            <i class="fas fa-receipt text-white"></i>
                        </div>

                        {{-- Nội dung text --}}
                        <div>
                            <p class="mb-1" style="font-size: 14px;">
                                Tiết kiệm thêm đến <strong>165.000đ</strong> cho Smember
                            </p>
                            <p class="mb-0" style="font-size: 14px;">
                                Ưu đãi Học sinh - sinh viên, Giảng viên - giáo viên đến
                                <strong>494.700đ</strong>
                            </p>
                        </div>
                    </div>

                    {{-- Link bên phải --}}
                    <a href="#" class="text-danger fw-semibold text-decoration-none" style="font-size: 14px;">
                        Kiểm tra giá cuối <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <!-- Banner khuyến mãi -->
                <div class="my-2">
                    <div class="rounded-3 overflow-hidden">
                        <a href="https://cellphones.com.vn/chao-nam-hoc-moi" target="_blank">
                            <img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:0:120/q:90/plain/https://dashboard.cellphones.com.vn/storage/pdp-b2s-2025.png"
                                alt="Banner Khuyến Mãi" class="img-fluid w-100"
                                style="border-radius: 12px; max-height: 80px; object-fit: cover;">
                        </a>
                    </div>
                </div>

                <div class="p-2" style="border: 1px solid #007bff; border-radius: 12px; background-color:#f0f5fd;;">
                    <div class="d-flex align-items-center fw-semibold mb-3" style="font-size: 18px;">
                        <svg width="24" height="25" fill="none" class="me-2" viewBox="0 0 24 25"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill="url(#gradient)"
                                d="M2 15.5H11V24.5H7C5.67 24.5 4.4 23.97 3.46 23.03C2.52 22.09 2 20.83 2 19.5V15.5ZM24 11.5C24 12.03 23.79 12.54 23.41 12.91C23.04 13.29 22.53 13.5 22 13.5H13V9.46C12.66 9.48 12.33 9.5 12 9.5C11.67 9.5 11.34 9.48 11 9.46V13.5H2C1.47 13.5 0.96 13.29 0.59 12.91C0.21 12.54 0 12.03 0 11.5C0 10.44 0.42 9.42 1.17 8.67C1.92 7.92 2.94 7.5 4 7.5H5.74C5.17 7 4.72 6.38 4.42 5.69C4.12 5 3.98 4.25 4 3.5C4 3.23 4.11 2.98 4.29 2.79C4.48 2.61 4.73 2.5 5 2.5C5.27 2.5 5.52 2.61 5.71 2.79C5.89 2.98 6 3.23 6 3.5C6 6.12 8.37 7.03 10.17 7.34C9.51 6.16 9.11 4.85 9 3.5C9 2.7 9.32 1.94 9.88 1.38C10.44 0.82 11.2 0.5 12 0.5C12.8 0.5 13.56 0.82 14.12 1.38C14.68 1.94 15 2.7 15 3.5C14.89 4.85 14.49 6.16 13.83 7.34C15.63 7.03 18 6.12 18 3.5C18 3.23 18.11 2.98 18.29 2.79C18.48 2.61 18.73 2.5 19 2.5C19.27 2.5 19.52 2.61 19.71 2.79C19.89 2.98 20 3.23 20 3.5C20.02 4.25 19.88 5 19.58 5.69C19.28 6.38 18.83 7 18.26 7.5H20C21.06 7.5 22.08 7.92 22.83 8.67C23.58 9.42 24 10.44 24 11.5ZM13 24.5H17C18.66 24.5 20.07 23.87 20.54 23.03C21 22.57 21.37 22.02 21.62 21.41C21.87 20.81 22 20.16 22 19.5V15.5H13V24.5Z">
                            </path>
                            <defs>
                                <linearGradient id="gradient" x1="18" y1="-13.5" x2="-5.9444"
                                    y2="3.07082" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#ED8A95"></stop>
                                    <stop offset="0.82" stop-color="#C40016"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                        <span>Khuyến mãi hấp dẫn</span>
                    </div>

                    <ul class="list-unstyled m-0">
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">1</span>
                            <span>Giảm ngay 500K khi thanh toán qua thẻ tín dụng HSBC <a href="#">Xem chi
                                    tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">2</span>
                            <span>trả góp 0% lãi suất, tối đa 12 tháng, trả trước từ 10% qua CTTC hoặc 0đ qua thẻ tín dụng
                                <a href="#">Xem chi tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">3</span>
                            <span>tặng Phiếu Mua Hàng 3 triệu nâng cấp lên Win11 Pro (DV.PM.18), giá cuối chỉ 1,190,000 <a
                                    href="#">Xem chi tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">4</span>
                            <span>iảm 3% tới 500K, Tặng bộ quà CellphoneS lên tới 1,6 triệu (balo+quạt/pin dự phòng/tai
                                nghe) &amp; Tài khoản AI Hay Pro 6 tháng trị giá 1,59 triệu cho thành viên S-Student,
                                S-teacher <a href="#">Xem chi tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">5</span>
                            <span>Tặng Sĩ Tử 2025 voucher ưu đãi, nhận quà ngay <a href="#">Xem chi tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">6</span>
                            <span>Tặng Sim data Viettel, chọn SĐT trong kho số, miễn phí sử dụng Youtube, Tiktok, Facebook &
                                có 1GB data/ngày – miễn phí 1 tháng sử dụng (chỉ áp dụng tại cửa hàng) <a
                                    href="#">Xem chi tiết</a></span>
                        </li>
                    </ul>
                </div>
                <div class="d-flex gap-2 my-2">
                    @auth
                        <!-- Trả góp 0% -->
                        <button class="btn btn-outline-primary fw-semibold rounded-3 fw-semibold py-3"
                            style="border-radius: 8px;">
                            Trả góp 0%
                        </button>
                        <!-- Mua ngay -->
                        <button class="btn text-white fw-bold flex-grow-1 rounded-3 py-3" onclick="buyNow()" id="buyNowBtn"
                            {{ $product->variants->count() > 0 && $product->variants->first()->stock_quantity == 0 ? 'disabled' : '' }}
                            style="border-radius: 8px; background: linear-gradient(to bottom, #f42424, #c60000); border: none;">
                            MUA NGAY<br>
                            <small class="fw-normal">Giao nhanh từ 2 giờ hoặc nhận tại cửa hàng</small>
                        </button>
                        <!-- Thêm vào giỏ -->
                        <button class="btn btn-outline-danger rounded-3 py-3" style="border-radius: 8px;"
                            onclick="addToCart()" id="addToCartBtn"
                            {{ $product->variants->count() > 0 && $product->variants->first()->stock_quantity == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                        </button>
                    @else
                        <!-- Trả góp 0% -->
                        <button class="btn btn-outline-primary fw-semibold rounded-3 fw-semibold py-3"
                            style="border-radius: 8px;">
                            Trả góp 0%
                        </button>

                        <!-- Mua ngay -->
                        <button class="btn text-white fw-bold flex-grow-1 rounded-3 py-3" onclick="openLoginModal()"
                            style="border-radius: 8px; background: linear-gradient(to bottom, #f42424, #c60000); border: none;">
                            MUA NGAY<br>
                            <small class="fw-normal">Giao nhanh từ 2 giờ hoặc nhận tại cửa hàng</small>
                        </button>

                        <!-- Thêm vào giỏ -->
                        <button class="btn btn-outline-danger rounded-3 py-3" style="border-radius: 8px;"
                            onclick="openLoginModal()" id="addToCartBtn">
                            <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                        </button>
                    @endauth

                </div>

                <div class="p-3  d-flex align-items-center gap-3"
                    style="
                        border-radius: 12px;
                        background-color: #f2f1f1 ;
                        transition: 0.2s ease;
                    ">

                    <!-- Cột 1: Icon + Text -->
                    <div class="d-flex align-items-center flex-fill">
                        <div class="me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="background: linear-gradient(to bottom right, #ED8A95, #C40016); width: 40px; height: 40px;">
                                <i class="fas fa-upload text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 18px;">Thu cũ lên đời</div>
                            <div style="font-size: 16px;">Chỉ từ <span class="text-danger fw-bold">21.490.000đ</span>
                            </div>
                        </div>
                    </div>


                    <!-- Cột 2: Select -->
                    <div style="width: 390px;">
                        <select class="form-select w-100" style="height: 48px; font-size: 16px; padding: 10px 12px;">
                            <option selected>Tìm sản phẩm muốn thu cũ</option>
                            <option>MacBook Pro 2021</option>
                            <option>iPad Air 4</option>
                        </select>
                    </div>



                    <!-- Cột 3: Button -->
                    <div class="flex-fill">
                        <button class="btn btn-light border border-danger text-danger fw-semibold w-100">
                            Kiểm tra ngay <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>



                <div class="my-2 rounded-3 p-2" style="  border: 1px solid #007bff; background-color: #f0f5fd;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-gift text-danger me-2 fs-5"></i>
                        <h5 class="fw-bold mb-0">Ưu đãi thanh toán</h5>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">1</span>
                            <span>Hoàn tiền đến 2 triệu khi mở thẻ tín dụng HSBC <a href="#">Xem chi tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">2</span>
                            <span>Giảm đến 1 triệu khi thanh toán qua thẻ tín dụng Vietbank <a href="#">Xem chi
                                    tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">3</span>
                            <span>Giảm 500K khi thanh toán qua thẻ tín dụng OCB <a href="#">Xem chi tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">4</span>
                            <span>Giảm đến 500K khi thanh toán qua Kredivo <a href="#">Xem chi tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">5</span>
                            <span>Giảm 200K khi trả góp Visa Sacombank qua MPOS <a href="#">Xem chi tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">6</span>
                            <span>Giảm đến 200K khi thanh toán qua MOMO <a href="#">Xem chi tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">7</span>
                            <span>Giảm đến 1 triệu khi thanh toán qua thẻ Muadee by HDBank <a href="#">Xem chi
                                    tiết</a></span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <span class="badge bg-primary me-2">8</span>
                            <span>Liên hệ B2B để được tư vấn giá tốt khi mua số lượng nhiều <a href="#">Xem chi
                                    tiết</a></span>
                        </li>
                    </ul>
                </div>
                <div class="p-2"
                    style="
                        border-radius: 12px;
                        background-color: #f2f1f1 ;
                        transition: 0.2s ease;
                    ">
                    <div class="d-flex align-items-center mb-3">
                        <svg width="18" height="18" class="me-2" fill="red" viewBox="0 0 24 24">
                            <path d="..."></path> <!-- SVG rút gọn -->
                        </svg>
                        <strong class="me-2">Chọn gói dịch vụ bảo hành</strong>
                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="Thông tin gói bảo hành"></i>
                    </div>

                    <div class="warranty-option border rounded px-3 py-2 bg-white d-inline-block">
                        <label class="d-flex align-items-start gap-2 mb-0">
                            <div style="max-width: 150px;">
                                <div class="fw-semibold text-truncate small">1 đổi 1 VIP 12 tháng</div>
                                <div class="text-danger fw-bold small">1.800.000đ</div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>
        </div>
        <div id="sticky-action-bar"
            style="
                position: fixed;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
                justify-content: space-between;
                align-items: center;
                border: 1px solid #ddd;
                border-radius: 12px;
                padding: 2px 8px;
                max-width: 900px;
                width: 95%;
                background: white;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                z-index: 9999;
                font-family: Arial, sans-serif;
                min-height: 44px;
            ">
            <!-- Product Info -->
            <div style="display: flex; align-items: center;">
                <img id="stickyBarImage"
                    src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x350/e3f2fd/1976d2?text=' . urlencode($product->name) }}"
                    alt="{{ $product->name }}" class="img-fluid"
                    style="width: 40px; height: 40px; border-radius: 6px; margin-right: 8px;" />
                <div>
                    <div class="product-name"
                        style="font-weight: bold; font-size: 15px; display: flex; align-items: baseline;">
                        <span>{{ $product->name }}</span>
                        <span id="sticky-bar-stock"
                            style="font-size: 14px; color: #28a745; font-weight: 500; margin-left: 8px;"></span>
                    </div>
                    <div id="sticky-selected-options" style="font-size: 11px; color: #666; margin-top: 1px;"></div>
                    <div style="font-size: 10px; color: #888;">
                        + mua kèm dịch vụ bảo hành mở rộng
                        <a href="#" style="color: #0066cc;">Xem thêm</a>
                    </div>
                </div>
            </div>


            <!-- Price & Actions -->
            <div style="text-align: right;">

                <div class="price" style="font-size: 18px; color: #d70018; font-weight: bold;">
                    {{ number_format($product->variants->first()->price) }}₫
                </div>
                <div style="text-decoration: line-through; color: #999; font-size: 14px;">
                    34.590.000đ
                </div>
                <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                    @auth
                        <button
                            style="background: white; border: 1px solid #0066cc; color: #0066cc; padding: 5px 10px; border-radius: 6px; cursor: pointer;">
                            Trả góp 0%
                        </button>
                        <button onclick="buyNow()" id="buyNowBtn"
                            {{ $product->variants->count() > 0 && $product->variants->first()->stock_quantity == 0 ? 'disabled' : '' }}
                            style="background: #d70018; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                            Mua Ngay
                        </button>
                        <button onclick="addToCart()" id="addToCartBtn"
                            {{ $product->variants->count() > 0 && $product->variants->first()->stock_quantity == 0 ? 'disabled' : '' }}
                            style="background: #f5f5f5; border: 1px solid #e20808; padding: 6px; border-radius: 6px; cursor: pointer;">
                            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Giỏ hàng"
                                style="width: 16px; height: 16px;" />
                        </button>
                    @else
                        <button
                            style="background: white; border: 1px solid #0066cc; color: #0066cc; padding: 5px 10px; border-radius: 6px; cursor: pointer;">
                            Trả góp 0%
                        </button>
                        <button onclick="openLoginModal()"
                            {{ $product->variants->count() > 0 && $product->variants->first()->stock_quantity == 0 ? 'disabled' : '' }}
                            style="background: #d70018; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                            Mua Ngay
                        </button>
                        <button onclick="openLoginModal()" id="addToCartBtn"
                            {{ $product->variants->count() > 0 && $product->variants->first()->stock_quantity == 0 ? 'disabled' : '' }}
                            style="background: #f5f5f5; border: 1px solid #e20808; padding: 6px; border-radius: 6px; cursor: pointer;">
                            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Giỏ hàng"
                                style="width: 16px; height: 16px;" />
                        </button>

                    @endauth





                </div>
            </div>
        </div>
        <div>
            <div class="nav nav-classic nav-tab">
                <h2 class="nav-link active">Có thể bạn cũng thích</h2>
            </div>
            @if ($relatedProducts->isNotEmpty())
                <div class="tab-pane fade pt-2 show active" id="related-products" role="tabpanel">
                    <ul class="row list-unstyled products-group no-gutters">
                        <div class="container py-5">
                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                                @foreach ($relatedProducts as $relatedProduct)
                                    <div class="col">
                                        <div class="card h-100 border-0 shadow-sm position-relative product-card">
                                            <div class="card-body p-2 d-flex flex-column text-center">
                                                <h6 class="card-title text-truncate mb-1">
                                                    <a href="{{ route('client.products.show', $relatedProduct->slug) }}"
                                                        class="text-blue font-weight-bold">
                                                        {{ $relatedProduct->name }}
                                                    </a>
                                                </h6>

                                                @php
                                                    $firstImage = $relatedProduct->images->first();
                                                @endphp

                                                <a href="{{ route('client.products.show', $relatedProduct->slug) }}">
                                                    <img src="{{ $firstImage ? asset('storage/' . $firstImage->image_path) : asset('images/default.png') }}"
                                                        class="card-img-top p-3" alt="{{ $relatedProduct->name }}"
                                                        style="height: 180px; object-fit: contain;">
                                                </a>

                                                @php
                                                    $variant = $relatedProduct->variants
                                                        ->sortBy(function ($v) {
                                                            return $v->sale_price ?? $v->price;
                                                        })
                                                        ->first();
                                                @endphp

                                                @if ($variant)
                                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                                        <div
                                                            class="d-flex justify-content-center align-items-center flex-column mb-2">
                                                            <del class="text-muted small">
                                                                {{ number_format($variant->price, 0, ',', '.') }} đ
                                                            </del>
                                                            <p class="text-danger fw-bold mb-0">
                                                                {{ number_format($variant->sale_price, 0, ',', '.') }}
                                                                đ
                                                            </p>
                                                        </div>
                                                    @else
                                                        <p class="text-danger fw-bold mb-2">
                                                            {{ number_format($variant->price, 0, ',', '.') }} đ
                                                        </p>
                                                    @endif
                                                @else
                                                    <p class="text-muted small mb-2">Liên hệ để biết giá</p>
                                                @endif

                                                <div class="text-warning mb-2">
                                                    ⭐ {{ number_format($relatedProduct->reviews_avg_rating ?? 0, 1) }}/5
                                                </div>

                                                <div class="d-flex justify-content-center gap-2 mt-auto">
                                                    <button class="btn btn-sm btn-outline-primary w-50">
                                                        <i class="bi bi-cart-plus"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary w-50">
                                                        <i class="bi bi-heart"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Badge: Sản phẩm liên quan --}}
                                            <span class="badge bg-success text-white position-absolute top-0 start-0 m-2">
                                                Liên quan
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </ul>
                </div>
            @endif
            @if ($product->description)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Mô tả sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- rate --}}
    <div class="card p-5" style="font-size: 1.05rem;">
        <h3 class="fw-bold mb-4" style="font-size: 1.5rem;">Đánh giá</h3>

        <div class="row mt-3">
            <!-- Điểm trung bình -->
            <div class="col-md-3 pt-4 text-center">
                <h2 class="mb-1" style="font-size: 2.5rem;">{{ number_format($product->average_rating, 1) }}</h2>
                <p class="text-muted mb-2 fw-bold" style="font-size: 1rem;">{{ $totalReviews }} lượt đánh giá</p>
                <div class="text-warning" style="font-size: 1.3rem;">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($product->average_rating))
                            <i class="fas fa-star"></i>
                        @elseif($i <= ceil($product->average_rating))
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
            </div>

            <!-- Thanh phân bố đánh giá -->
            <div class="col-md-6">
                @for ($i = 5; $i >= 1; $i--)
                    <div class="d-flex align-items-center mb-2" style="font-size: 1rem;">
                        <div class="me-2">{{ $i }} <i class="fas fa-star text-warning"></i></div>
                        <div class="progress flex-grow-1"
                            style="height: 16px; border-radius: 8px; background-color: #e9ecef;">
                            <div class="progress-bar bg-danger" role="progressbar"
                                style="width: {{ $totalReviews > 0 ? ($ratingSummary[$i] / $totalReviews) * 100 : 0 }}%; border-radius: 8px;">
                            </div>
                        </div>
                        <div class="ms-2">{{ $ratingSummary[$i] }}</div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Nút đánh giá & bộ lọc -->
        <div class="row mt-4">
            <!-- Nút đánh giá sản phẩm -->
            <div class="col-md-3 ms-4">
                <button class="btn btn-danger btn-lg w-80" id="btn-review">Đánh giá sản phẩm</button>
            </div>

            <!-- Modal Thông báo đánh giá sản phẩm -->
            <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-center p-3">

                        <!-- Nút đóng -->
                        <button type="button" class="btn-close ms-auto me-2 mt-2" data-bs-dismiss="modal"
                            aria-label="Close"></button>

                        <!-- Hình minh họa -->
                        <img src="https://cdn-icons-png.flaticon.com/512/1828/1828843.png" alt="error"
                            class="mx-auto mb-3" width="100">

                        <!-- Nội dung -->
                        <h5 class="fw-bold text-danger">Gửi đánh giá không thành công!</h5>
                        <p class="text-muted">Quý khách vui lòng mua hàng để tham gia đánh giá sản phẩm.</p>

                        <!-- Nút hành động -->
                        <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">Đã hiểu</button>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc căn thẳng hàng với thanh phân bố -->
            <div class="col-md-6 d-flex flex-wrap justify-content-end gap-2 mt-2 mt-md-0">
                <button class="btn btn-outline-danger btn-sm filter-btn" data-rating="all"
                    data-product="{{ $product->id }}">Tất cả</button>
                @for ($i = 5; $i >= 1; $i--)
                    <button class="btn btn-outline-danger btn-sm filter-btn" data-rating="{{ $i }}"
                        data-product="{{ $product->id }}">{{ $i }} ★</button>
                @endfor
            </div>
        </div>

        {{-- Khu vực hiển thị review --}}
        <div id="reviews-container" class="mt-4">
            @include('client.products.partials.reviews_list', ['reviews' => $product->reviews])
        </div>
    </div>


    {{-- comments --}}
    <div class="card mt-4 mb-3">
        <div class="card-header mb-4">
            <h5>Bình luận ({{ optional($product->comments)->count() ?? 0 }})</h5>
        </div>
        <form class="ms-2 me-2" id="comment-form" method="POST" action="{{ route('comments.store', $product->id) }}"
            style="display: flex; gap: 10px; align-items: center;">
            @csrf
            <input type="hidden" name="post_id" value="{{ $product->id }}">
            <textarea id="comment-content" name="content" placeholder="Viết bình luận..." required
                style="flex: 1; height: 50px; border: 1px solid #ccc; border-radius: 10px; padding: 8px; resize: none; min-height: 40px;"></textarea>
            <button type="submit" class="btn btn-primary"
                style="border-radius: 25px; padding: 8px 15px; border: none; background-color: black; color: white; cursor: pointer; height: 50px;">Gửi
                bình luận</button>
            <div id="loading" style="display: none;">Đang gửi...</div>
        </form>
        <div class="card-body comment-section">
            <div id="comments-list">
                <div id="comment-alert" class="alert" style="display: none;"></div>
                @foreach ($product->comments ?? [] as $comment)
                    @include('client.comments._item', ['comment' => $comment])
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal cho khách chưa đăng nhập -->
    <div class="modal fade" id="guestCommentModal" tabindex="-1" aria-labelledby="guestCommentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guestCommentModalLabel">Thông tin của bạn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="guest-comment-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="male"
                                        value="male" checked>
                                    <label class="form-check-label" for="male">Nam</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="female"
                                        value="female">
                                    <label class="form-check-label" for="female">Nữ</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="guest-name" class="form-label head">Tên</label>
                            <input type="text" class="form-control" id="guest-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="guest-phone" class="form-label head">Số điện thoại</label>
                            <input type="tel" class="form-control" id="guest-phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="guest-email" class="form-label head">Email</label>
                            <input type="email" class="form-control" id="guest-email" name="email" required>
                        </div>
                        <input type="hidden" id="guest-comment-content" name="content">
                        <input type="hidden" name="post_id" value="{{ $product->id }}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="submit-guest-comment">Gửi bình
                        luận</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <style>
        .comment-item {
            padding: 15px;
            border-radius: 8px;
            background-color: #f8f9fa;
            border-left: 3px solid #0d6efd;
        }

        .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .comment-body {
            line-height: 1.5;
        }

        .guest-info {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        #comment-alert {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        #comment-form textarea {
            transition: height 0.2s;
        }

        #comment-form textarea:focus {
            height: 80px;
        }

        .form-label>head {
            text-align: left
        }
    </style>
    {{-- reviews --}}
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                let rating = this.dataset.rating;
                let productId = this.dataset.product;

                console.log("ProductID:", productId, "Rating:", rating);

                // 🔥 Xóa active ở tất cả nút trước
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('active');
                });
                // 🔥 Thêm active cho nút hiện tại
                this.classList.add('active');

                fetch(`/reviews/filter/${productId}?rating=${rating}`, {
                        method: "GET",
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log("API trả về:", data);

                        let container = document.querySelector('#reviews-container');

                        if (data.html && data.html.trim() !== "") {
                            // Có dữ liệu review
                            container.innerHTML = data.html;
                        } else {
                            // Không có review → show thông báo sinh động
                            let msg = "";

                            if (rating === "all") {
                                msg = `Chưa có đánh giá nào cho sản phẩm này.`;
                            } else {
                                msg = `Chưa có đánh giá <span class="text-warning">`;
                                // ⭐ thêm icon sao theo rating
                                for (let i = 0; i < rating; i++) {
                                    msg += `<i class="fas fa-star"></i>`;
                                }
                                msg += `</span> nào cho sản phẩm này.`;
                            }

                            container.innerHTML = `<p class="text-muted py-4 mb-0">${msg}</p>`;
                        }
                    })
                    .catch(err => {
                        console.error("Lỗi khi gọi API filter:", err);
                    });
            });
        });

        // 🔥 Mặc định highlight "Tất cả" khi load trang
        let defaultBtn = document.querySelector('.filter-btn[data-rating="all"]');
        if (defaultBtn) defaultBtn.classList.add('active');
    });
</script>


    <!-- Script mở modal tbao đánh giá sp -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("btn-review").addEventListener("click", function() {
                var myModal = new bootstrap.Modal(document.getElementById('reviewModal'));
                myModal.show();
            });
        });
    </script>




    {{-- comment --}}
    <script>
        // Đảm bảo DOM đã load hoàn toàn
        window.addEventListener('DOMContentLoaded', function() {
            // Kiểm tra sự tồn tại của tất cả phần tử
            const commentForm = document.getElementById('comment-form');
            const guestModalEl = document.getElementById('guestCommentModal');
            const submitGuestBtn = document.getElementById('submit-guest-comment');

            if (!commentForm || !guestModalEl || !submitGuestBtn) {
                console.error('Không tìm thấy các phần tử cần thiết');
                return;
            }

            // Khởi tạo modal với try-catch
            let guestCommentModal;
            try {
                guestCommentModal = new bootstrap.Modal(guestModalEl, {
                    keyboard: true,
                    backdrop: 'static'
                });
            } catch (e) {
                console.error('Lỗi khởi tạo modal:', e);
                return;
            }

            // Kiểm tra trạng thái đăng nhập từ meta tag
            const isLoggedIn = document.querySelector('meta[name="user-logged"]')?.content === 'true';

            // Xử lý submit form chính
            commentForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(commentForm);
                const content = formData.get('content');

                if (!content?.trim()) {
                    showAlert('Vui lòng nhập nội dung bình luận', 'danger');
                    return;
                }

                if (isLoggedIn) {
                    await handleCommentSubmit(formData);
                } else {
                    // Chuẩn bị dữ liệu cho guest
                    document.getElementById('guest-comment-content').value = content;
                    guestCommentModal.show();
                }
            });

            // Xử lý submit từ modal
            submitGuestBtn.addEventListener('click', async function() {
                const guestForm = document.getElementById('guest-comment-form');
                const formData = new FormData(guestForm);

                // Validate dữ liệu khách
                if (!validateGuestData(formData)) {
                    showAlert('Vui lòng điền đầy đủ thông tin', 'danger');
                    return;
                }

                const success = await handleCommentSubmit(formData);
                if (success) {
                    guestCommentModal.hide();
                    commentForm.reset();
                }
            });

            // Hàm validate thông tin khách
            function validateGuestData(formData) {
                return formData.get('name')?.trim() &&
                    formData.get('phone')?.trim() &&
                    formData.get('email')?.trim();
            }

            // Hàm xử lý submit chung
            async function handleCommentSubmit(formData) {
                const loading = document.getElementById('loading');
                const alertDiv = document.getElementById('comment-alert');

                try {
                    if (loading) loading.style.display = 'block';

                    // Thêm CSRF token nếu chưa có
                    if (!formData.has('_token')) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        if (csrfToken) formData.append('_token', csrfToken);
                    }

                    const response = await fetch(commentForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || `Lỗi: ${response.status}`);
                    }

                    if (data.success) {
                        updateCommentsList(data.html);
                        showAlert(data.message || 'Bình luận thành công!', 'success');
                        return true;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert(error.message || 'Có lỗi khi gửi bình luận', 'danger');
                    return false;
                } finally {
                    if (loading) loading.style.display = 'none';
                }
            }

            function updateCommentsList(html) {
                const commentsList = document.getElementById('comments-list');
                if (commentsList && html) {
                    commentsList.insertAdjacentHTML('afterbegin', html);
                }
            }

            function showAlert(message, type) {
                const alertDiv = document.getElementById('comment-alert');
                if (alertDiv) {
                    alertDiv.textContent = message;
                    alertDiv.className = `alert alert-${type}`;
                    alertDiv.style.display = 'block';
                    setTimeout(() => {
                        alertDiv.style.display = 'none';
                    }, 5000);
                }
            }
        });
    </script>
    <script>
        window.variantsForJs = @json($variantsForJs);
        window.attributeOptionsWithPrices = @json($attributeOptionsWithPrices);
        window.totalAttributes = {{ count($attributeOptionsWithPrices) }};
        window.ramAttrId = {{ $ramAttrId ?? 'null' }};
        window.productId = {{ $product->id }};
        window.cartAddUrl = "{{ route('cart.add') }}";
        window.cartBuyNowUrl = "{{ route('cart.buyNow') }}";
        window.selectedPrice = {{ $product->variants->count() > 0 ? $product->variants->first()->price : 0 }};
    </script>
    <script src="{{ asset('client/js/Products/product-show.js') }}"></script>

@section('footer')
    @include('client.layouts.partials.footer')
@endsection
@endsection
