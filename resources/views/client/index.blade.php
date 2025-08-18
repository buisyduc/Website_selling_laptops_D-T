@extends('client.layouts.layout')
@section('sidebar')
    @include('client.layouts.partials.sidebar')
@endsection
@section('title', 'Trang chủ')
@section('content')
   
    <div class="container p-0">
        <div class="horizontal-banner"
            style="margin:0; padding:0; background:#b8001c; border-radius: 12px; overflow: hidden;">
            <a href="#" class="horizontal-banner__item button__link" style="display:block;">
                <img src="{{ asset('client/img/special-b2s-dday3-d.gif') }}" width="100%" height="auto" alt="B2S 2025 - D&T"
                    loading="lazy" class="horizontal-banner__img desktop" style="width:100%; height:auto; display:block;">
                <img src="{{ asset('client/img/special-b2s-dday3-m.gif') }}" width="100%" height="auto"
                    alt="B2S 2025 - D&T" loading="lazy" class="horizontal-banner__img mobile"
                    style="width:100%; height:auto; display:none;">
            </a>
        </div>
        <div id="noibat"
            style="background: #ff4663; border-radius: 18px; padding: 18px 10px 30px 10px; margin-bottom: 30px; margin-top: 16px">
           
            <div class="block-featured-product">
                <div class="product-list-title">
                    <div class="product-list-title__header">
                        <a href="#" class="title button__link">
                            <h2 class="block-title-hot-sale">
                                <span class="hot-sale-icon">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                        <path
                                            d="M16 8c2 2 2 4 0 6 2-1 4 1 4 3 0 2-2 4-4 4s-4-2-4-4c0-2 2-4 4-3-2-2-2-4 0-6z"
                                            fill="#fff" />
                                    </svg>
                                </span>
                                <span>SẢN PHẨM NỔI BẬT</span>
                            </h2>
                        </a>
                    </div>

                </div>
                <div class="product-list">
                    <div class="swiper product-slider">
                        <div class="swiper-wrapper">
                            @foreach ($featuredProducts->take(10) as $product)
                                @if ($product->variants->where('stock_quantity', '>', 0)->count() === 0)
                                    @continue
                                @endif
                                @php
                                    $variant = $product->variants
                                        ->sortBy(function ($v) {
                                            return $v->sale_price ?? $v->price;
                                        })
                                        ->first();
                                    $discount = 0;
                                    if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                        $discount = round(
                                            (($variant->price - $variant->sale_price) / $variant->price) * 100,
                                        );
                                    }
                                @endphp
                                <div class="swiper-slide">
                                    <div class="product-card-container">
                                        @if ($discount > 0)
                                            <div class="discount-badge">Giảm {{ $discount }}%</div>
                                        @endif
                                        <div class="install-0-tag"><span>Trả góp <strong>0%</strong></span></div>
                                        <a href="{{ route('client.products.show', $product->id) }}" class="product-link">
                                            <div class="product-image">
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                            </div>

                                            <h3 class="product-name">{{ $product->name }}</h3>
                                            <div class="product-price">
                                                @if ($variant)
                                                    <span class="current-price">{{ number_format($variant->sale_price ?? $variant->price, 0, ',', '.') }}₫</span>
                                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                                        <span class="original-price">{{ number_format($variant->price, 0, ',', '.') }}₫</span>
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="product-benefits">
                                                <div class="smember-discount">Smember giảm đến 198.000₫</div>
                                                <div class="sstudent-discount">S-Student giảm thêm 500.000₫</div>
                                            </div>
                                            <div class="product-promo">
                                                Không phí chuyển đổi khi trả góp 0% qua thẻ tín dụng kỳ hạn 3-6 tháng
                                            </div>
                                        </a>
                                        @php
                                            $isFavorite = in_array($product->id, $favoriteIds ?? []);
                                        @endphp
                                        <div class="product-actions">
                                            <div class="product-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($product->averageRating()))
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i <= ceil($product->averageRating()))
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="product-wishlist" data-product-id="{{ $product->id }}"
                                                onclick="handleFavoriteClick(event, this)">
                                                <i class="bi icon-toggle {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i> Yêu
                                                thích
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Swiper Navigation Buttons (chỉ giữ 1 cặp ngoài .swiper-wrapper) -->
                        <div class="swiper-button-prev custom-swiper-nav">
                            
                        </div>
                        <div class="swiper-button-next custom-swiper-nav">
                          
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div id="moinhat">
            <div class="block-featured-product">
                <div class="product-list-title">
                    <div class="product-list-title__header">
                        <a href="" class="title button__link">
                            <h2>Sản phẩm mới nhất</h2>
                        </a>
                    </div>

                </div>
                <div class="product-list">
                    <div class="product-grid">
                        @foreach ($newProducts->take(10) as $product)
                            @if ($product->variants->where('stock_quantity', '>', 0)->count() === 0)
                                @continue
                            @endif
                            @php
                                $variant = $product->variants
                                    ->sortBy(function ($v) {
                                        return $v->sale_price ?? $v->price;
                                    })
                                    ->first();
                                $discount = 0;
                                if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                    $discount = round(
                                        (($variant->price - $variant->sale_price) / $variant->price) * 100,
                                    );
                                }
                            @endphp
                            <div class="product-card-container">
                                @if ($discount > 0)
                                    <div class="discount-badge">Giảm {{ $discount }}%</div>
                                @endif
                                <div class="install-0-tag"><span>Trả góp <strong>0%</strong></span></div>
                                <a href="{{ route('client.products.show', $product->id) }}" class="product-link">
                                    <div class="product-image">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                    </div>

                                    <h3 class="product-name">{{ $product->name }}</h3>
                                    <div class="product-price">
                                        @if ($variant)
                                            <span
                                                class="current-price">{{ number_format($variant->sale_price ?? $variant->price, 0, ',', '.') }}₫</span>
                                            @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                                <span
                                                    class="original-price">{{ number_format($variant->price, 0, ',', '.') }}₫</span>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="product-benefits">
                                        <div class="smember-discount">Smember giảm đến 198.000₫</div>
                                        <div class="sstudent-discount">S-Student giảm thêm 500.000₫</div>
                                    </div>
                                    <div class="product-promo">
                                        Không phí chuyển đổi khi trả góp 0% qua thẻ tín dụng kỳ hạn 3-6 tháng
                                    </div>
                                </a>
                                @php
                                    $isFavorite = in_array($product->id, $favoriteIds ?? []);
                                @endphp
                                <div class="product-actions">
                                    <div class="product-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($product->averageRating()))
                                                <i class="fas fa-star"></i>
                                            @elseif($i <= ceil($product->averageRating()))
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="product-wishlist" data-product-id="{{ $product->id }}"
                                        onclick="handleFavoriteClick(event, this)">
                                        <i class="bi icon-toggle {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i> Yêu
                                        thích
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
        <div id="laptop" style="margin-top: 26px;">
            <div class="block-featured-product">
                <div class="product-list-title">
                    <div class="product-list-title__header">
                        <a href="" class="title button__link">
                            <h2>LAPTOP</h2>
                        </a>
                        <a href="" class="more-product button__link">Xem tất cả</a>
                    </div>
                    <div class="list-related-tag"><a href="" class="related-tag button__link">Macbook</a><a
                            href="" class="related-tag button__link">Asus</a><a href=""
                            class="related-tag button__link">MSI</a><a href=""
                            class="related-tag button__link">Lenovo</a><a href=""
                            class="related-tag button__link">HP</a><a href=""
                            class="related-tag button__link">Acer</a><a href=""
                            class="related-tag button__link">Dell</a><a href=""
                            class="related-tag button__link">Huawei</a><a href=""
                            class="related-tag button__link">Gigabyte</a><a href=""
                            class="related-tag button__link">Laptop
                            AI</a>
                        <a href="" class="related-tag button__link">Xem tất cả</a>
                    </div>
                </div>
                <div class="product-list">
                    <div class="product-grid">
                        @foreach ($laptopProducts->take(5) as $laptopProduct)
                            @if ($laptopProduct->variants->where('stock_quantity', '>', 0)->count() === 0)
                                @continue
                            @endif
                            @php
                                $variant = $laptopProduct->variants
                                    ->sortBy(function ($v) {
                                        return $v->sale_price ?? $v->price;
                                    })
                                    ->first();
                                $discount = 0;
                                if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                    $discount = round(
                                        (($variant->price - $variant->sale_price) / $variant->price) * 100,
                                    );
                                }
                            @endphp
                            <div class="product-card-container">
                                @if ($discount > 0)
                                    <div class="discount-badge">Giảm {{ $discount }}%</div>
                                @endif
                                <div class="install-0-tag"><span>Trả góp <strong>0%</strong></span></div>
                                <a href="{{ route('client.products.show', $laptopProduct->id) }}" class="product-link">
                                    <div class="product-image">
                                        <img src="{{ asset('storage/' . $laptopProduct->image) }}"
                                            alt="{{ $laptopProduct->name }}">
                                    </div>

                                    <h3 class="product-name">{{ $laptopProduct->name }}</h3>
                                    <div class="product-price">
                                        @if ($variant)
                                            <span
                                                class="current-price">{{ number_format($variant->sale_price ?? $variant->price, 0, ',', '.') }}₫</span>
                                            @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                                <span
                                                    class="original-price">{{ number_format($variant->price, 0, ',', '.') }}₫</span>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="product-benefits">
                                        <div class="smember-discount">Smember giảm đến 198.000₫</div>
                                        <div class="sstudent-discount">S-Student giảm thêm 500.000₫</div>
                                    </div>
                                    <div class="product-promo">
                                        Không phí chuyển đổi khi trả góp 0% qua thẻ tín dụng kỳ hạn 3-6 tháng
                                    </div>
                                </a>
                                @php
                                    $isFavorite = in_array($laptopProduct->id, $favoriteIds ?? []);
                                @endphp
                                <div class="product-actions">
                                    <div class="product-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($laptopProduct->averageRating()))
                                                <i class="fas fa-star"></i>
                                            @elseif($i <= ceil($laptopProduct->averageRating()))
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="product-wishlist" data-product-id="{{ $laptopProduct->id }}"
                                        onclick="handleFavoriteClick(event, this)">
                                        <i class="bi icon-toggle {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                        Yêu thích
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
        <div id="man-hinh" style="margin-top: 26px;">
            <div class="block-featured-product">
                <div class="product-list-title">
                    <div class="product-list-title__header">
                        <a href="" class="title button__link">
                            <h2>MÀN HÌNH, MÁY TÍNH ĐỂ BÀN</h2>
                        </a>
                        <a href="" class="more-product button__link">Xem tất cả</a>
                    </div>
                    <div class="list-related-tag"><a href="" class="related-tag button__link">Build PC</a><a
                            href="" class="related-tag button__link">Máy tính bàn</a>
                        <a href="" class="related-tag button__link">PC Gaming</a>
                        <a href="" class="related-tag button__link">PC Đồ họa</a><a href=""
                            class="related-tag button__link">PC đồng bộ</a>
                        <a href="" class="related-tag button__link">Màn hình Gaming</a>
                        <a href="" class="related-tag button__link">Xem tất cả</a>
                    </div>
                </div>
            </div>
            <div class="product-list">
                <div class="product-grid">
                    @foreach ($screenProducts->take(5) as $screenProduct)
                        @if ($screenProduct->variants->where('stock_quantity', '>', 0)->count() === 0)
                            @continue
                        @endif
                        @php
                            $variant = $screenProduct->variants
                                ->sortBy(function ($v) {
                                    return $v->sale_price ?? $v->price;
                                })
                                ->first();
                            $discount = 0;
                            if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                $discount = round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                            }
                        @endphp
                        <div class="product-card-container">
                            @if ($discount > 0)
                                <div class="discount-badge">Giảm {{ $discount }}%</div>
                            @endif
                            <div class="install-0-tag"><span>Trả góp <strong>0%</strong></span></div>
                            <a href="{{ route('client.products.show', $screenProduct->id) }}" class="product-link">
                                <div class="product-image">
                                    <img src="{{ asset('storage/' . $screenProduct->image) }}"
                                        alt="{{ $screenProduct->name }}">
                                </div>

                                <h3 class="product-name">{{ $screenProduct->name }}</h3>
                                <div class="product-price">
                                    @if ($variant)
                                        <span
                                            class="current-price">{{ number_format($variant->sale_price ?? $variant->price, 0, ',', '.') }}₫</span>
                                        @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                            <span
                                                class="original-price">{{ number_format($variant->price, 0, ',', '.') }}₫</span>
                                        @endif
                                    @endif
                                </div>

                                <div class="product-benefits">
                                    <div class="smember-discount">Smember giảm đến 198.000₫</div>
                                    <div class="sstudent-discount">S-Student giảm thêm 500.000₫</div>
                                </div>
                                <div class="product-promo">
                                    Không phí chuyển đổi khi trả góp 0% qua thẻ tín dụng kỳ hạn 3-6 tháng
                                </div>
                            </a>
                            @php
                                $isFavorite = in_array($screenProduct->id, $favoriteIds ?? []);
                            @endphp
                            <div class="product-actions">
                                <div class="product-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($screenProduct->averageRating()))
                                            <i class="fas fa-star"></i>
                                        @elseif($i <= ceil($screenProduct->averageRating()))
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="product-wishlist" data-product-id="{{ $screenProduct->id }}"
                                    onclick="handleFavoriteClick(event, this)">
                                    <i class="bi icon-toggle {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i> Yêu
                                    thích
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div id="pc" style="margin-top: 26px;">
            <div class="block-featured-product">
                <div class="product-list-title">
                    <div class="product-list-title__header">
                        <a href="" class="title button__link">
                            <h2>PC</h2>
                        </a>
                        <a href="" class="more-product button__link">Xem tất cả</a>
                    </div>
                    <div class="list-related-tag"><a href="" class="related-tag button__link">Build PC</a>
                        <a href="" class="related-tag button__link">PC Gaming</a>
                        <a href="" class="related-tag button__link">PC Đồ họa</a>
                        <a href="" class="related-tag button__link">MSI</a>
                        <a href="" class="related-tag button__link">ASUS</a>
                        <a href="" class="related-tag button__link">Xem tất cả</a>
                    </div>
                </div>
            </div>
            <div class="product-list">
                <div class="product-grid">
                    @foreach ($pcProducts->take(5) as $pcProduct)
                        @if ($pcProduct->variants->where('stock_quantity', '>', 0)->count() === 0)
                            @continue
                        @endif
                        @php
                            $variant = $pcProduct->variants
                                ->sortBy(function ($v) {
                                    return $v->sale_price ?? $v->price;
                                })
                                ->first();
                            $discount = 0;
                            if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                $discount = round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                            }
                        @endphp
                        <div class="product-card-container">
                            @if ($discount > 0)
                                <div class="discount-badge">Giảm {{ $discount }}%</div>
                            @endif
                            <div class="install-0-tag"><span>Trả góp <strong>0%</strong></span></div>
                            <a href="{{ route('client.products.show', $pcProduct->id) }}" class="product-link">
                                <div class="product-image">
                                    <img src="{{ asset('storage/' . $pcProduct->image) }}" alt="{{ $pcProduct->name }}">
                                </div>

                                <h3 class="product-name">{{ $pcProduct->name }}</h3>
                                <div class="product-price">
                                    @if ($variant)
                                        <span
                                            class="current-price">{{ number_format($variant->sale_price ?? $variant->price, 0, ',', '.') }}₫</span>
                                        @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                            <span
                                                class="original-price">{{ number_format($variant->price, 0, ',', '.') }}₫</span>
                                        @endif
                                    @endif
                                </div>

                                <div class="product-benefits">
                                    <div class="smember-discount">Smember giảm đến 198.000₫</div>
                                    <div class="sstudent-discount">S-Student giảm thêm 500.000₫</div>
                                </div>
                                <div class="product-promo">
                                    Không phí chuyển đổi khi trả góp 0% qua thẻ tín dụng kỳ hạn 3-6 tháng
                                </div>
                            </a>
                            @php
                                $isFavorite = in_array($pcProduct->id, $favoriteIds ?? []);
                            @endphp
                            <div class="product-actions">
                                <div class="product-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($pcProduct->averageRating()))
                                            <i class="fas fa-star"></i>
                                        @elseif($i <= ceil($pcProduct->averageRating()))
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="product-wishlist" data-product-id="{{ $pcProduct->id }}"
                                    onclick="handleFavoriteClick(event, this)">
                                    <i class="bi icon-toggle {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i> Yêu
                                    thích
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
// --- Swiper Slider for #noibat Featured Products ---
window.addEventListener('load', function () {
    if (typeof Swiper !== 'undefined') {
        console.log('Swiper loaded, initializing #noibat slider...');
        var noibatSwiper = new Swiper('#noibat .product-slider', {
    autoplay: {
        delay: 3500, // thời gian chuyển slide (ms)
        disableOnInteraction: false // vẫn tự chạy dù người dùng vừa thao tác
    },
            slidesPerView: 5,
            slidesPerGroup: 5,
            spaceBetween: 16,
            navigation: {
                nextEl: '#noibat .swiper-button-next',
                prevEl: '#noibat .swiper-button-prev',
            },
            speed: 600,
            grabCursor: true,
            loop: false,
            watchOverflow: true,
            allowTouchMove: true,
            breakpoints: {
                1200: {
                    slidesPerView: 5,
                    slidesPerGroup: 5,
                },
                992: {
                    slidesPerView: 4,
                    slidesPerGroup: 4,
                },
                768: {
                    slidesPerView: 3,
                    slidesPerGroup: 3,
                },
                480: {
                    slidesPerView: 2,
                    slidesPerGroup: 2,
                },
                0: {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                }
            }
        });
    }
});

    </script>


@section('footer')
    @include('client.layouts.partials.footer')
@endsection
@endsection
