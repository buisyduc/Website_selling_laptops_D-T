@extends('client.layouts.layout')

@section('sidebar')
    @include('client.layouts.partials.sidebar')
@endsection

@section('title', 'Trang chủ')


@section('content')
    <div class="container p-0">
        <div class="banner-section rounded-3 overflow-hidden"
            style="border-radius: 12px; background: transparent; min-height: 135px;">
            <div class="banner-section d-flex flex-wrap align-items-stretch" style="gap: 16px; flex-grow:1; height: 100%;">
                <div class="banner_one col-xl-3">
                    <a href="#" class="d-black text-gray-90 text-decoration-none">
                        <div class="min-height-132 py-1 d-flex bg-gray-1 align-items-center">
                            <div class="col-6 col-xl-5 col-wd-6 pr-0">
                                <img class="img-fluid"
                                    src="{{ asset('client/img/1920X422/asus-rog-strix-gaming-g513r-r7-hn038w-170822-061143-600x600-removebg-preview.png') }}"
                                    alt="Image Description">
                            </div>
                            <div class="col-6 col-xl-7 col-wd-6">
                                <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23 ">
                                    CATCH BIG <strong>DEALS</strong> ON THE LAPTOP
                                </div>
                                <div class="link text-gray-90 font-weight-bold font-size-15" href="#">
                                    Shop now
                                    <span class="link__icon ml-1">
                                        <span class="link__icon-inner"><i class="ec ec-arrow-right-categproes"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="banner_two col-xl-3">
                    <a href="#" class="d-black text-gray-90 text-decoration-none">
                        <div class="min-height-132 py-1 d-flex bg-gray-1 align-items-center">
                            <div class="col-6 col-xl-5 col-wd-6 pr-0">
                                <img class="img-fluid"
                                    src="{{ asset('client/img/1920X422/pc-gaming-hacom2-removebg-preview.png') }}"
                                    alt="Image Description">
                            </div>
                            <div class="col-6 col-xl-7 col-wd-6">
                                <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                    CATCH BIG <strong>DEALS</strong> ON THE ACCESORY
                                </div>
                                <div class="link text-gray-90 font-weight-bold font-size-15" href="#">
                                    Shop now
                                    <span class="link__icon ml-1">
                                        <span class="link__icon-inner"><i class="ec ec-arrow-right-categproes"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="bbanner_three col-xl-3">
                    <a href="#" class="d-black text-gray-90 text-decoration-none">
                        <div class="min-height-132 py-1 d-flex bg-gray-1 align-items-center">
                            <div class="col-6 col-xl-5 col-wd-6 pr-0">
                                <img class="img-fluid"
                                    src="{{ asset('client/img/1920X422/10875_dsc07967_26c1ee1d8ebb49dcb5369c56329c2368_master-removebg-preview.png') }}"
                                    alt="Image Description">
                            </div>
                            <div class="col-6 col-xl-7 col-wd-6">
                                <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                    CATCH BIG <strong>DEALS</strong> ON THE PC
                                </div>
                                <div class="link text-gray-90 font-weight-bold font-size-15" href="#">
                                    Shop now
                                    <span class="link__icon ml-1">
                                        <span class="link__icon-inner"><i class="ec ec-arrow-right-categproes"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="banner_four col-xl-3">
                    <a href="#" class="d-black text-gray-90 text-decoration-none">
                        <div class="min-height-132 py-1 d-flex bg-gray-1 align-items-center">
                            <div class="col-6 col-xl-5 col-wd-6 pr-0">
                                <img class="img-fluid"
                                    src="{{ asset('client/img/1920X422/z3488965753247_9740d023e93f41cb195f48294ffbb0fe-removebg-preview.png') }}"
                                    alt="Image Description">
                            </div>
                            <div class="col-6 col-xl-7 col-wd-6">
                                <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                    CATCH BIG <strong>DEALS</strong> ON THE FURNITURE
                                </div>
                                <div class="link text-gray-90 font-weight-bold font-size-15" href="#">
                                    Shop now
                                    <span class="link__icon ml-1">
                                        <span class="link__icon-inner"><i class="ec ec-arrow-right-categproes"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
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
                                        <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i> Yêu thích
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
                                        <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i> Yêu thích
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
                                    <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i> Yêu thích
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
                                    <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i> Yêu thích
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


@section('footer')
    @include('client.layouts.partials.footer')
@endsection
@endsection
