@extends('client.layouts.layout')

@section('sidebar')
    @include('client.layouts.partials.sidebar')
@endsection

@section('title', 'Trang chủ')

@section('content')
    <main id="content" role="main">
        <div class="container">
            <!-- Banner -->
            <div class="mb-5">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-xl-0 col-xl-3">
                        <a href="#"
                            class="d-black text-gray-90">
                            <div class="min-height-132 py-1 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-5 col-wd-6 pr-0">
                                   <img class="img-fluid" src="{{ asset('client/img/1920X422/asus-rog-strix-gaming-g513r-r7-hn038w-170822-061143-600x600-removebg-preview.png') }}" alt="Image Description">
                                </div>
                                <div class="col-6 col-xl-7 col-wd-6">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        CATCH BIG <strong>DEALS</strong> ON THE LAPTOP
                                    </div>
                                    <div class="link text-gray-90 font-weight-bold font-size-15" href="#">
                                        Shop now
                                        <span class="link__icon ml-1">
                                            <span class="link__icon-inner"><i
                                                    class="ec ec-arrow-right-categproes"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-4 mb-xl-0 col-xl-3">
                        <a href="#"
                            class="d-black text-gray-90">
                            <div class="min-height-132 py-1 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-5 col-wd-6 pr-0">
                                    <img class="img-fluid" src="{{ asset('client/img/1920X422/pc-gaming-hacom2-removebg-preview.png') }}" alt="Image Description">
                                </div>
                                <div class="col-6 col-xl-7 col-wd-6">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        CATCH BIG <strong>DEALS</strong> ON THE ACCESORY
                                    </div>
                                    <div class="link text-gray-90 font-weight-bold font-size-15" href="#">
                                        Shop now
                                        <span class="link__icon ml-1">
                                            <span class="link__icon-inner"><i
                                                    class="ec ec-arrow-right-categproes"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-4 mb-xl-0 col-xl-3">
                        <a href="#"
                            class="d-black text-gray-90">
                            <div class="min-height-132 py-1 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-5 col-wd-6 pr-0">
                                    <img class="img-fluid" src="{{ asset('client/img/1920X422/10875_dsc07967_26c1ee1d8ebb49dcb5369c56329c2368_master-removebg-preview.png') }}" alt="Image Description">
                                </div>
                                <div class="col-6 col-xl-7 col-wd-6">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        CATCH BIG <strong>DEALS</strong> ON THE PC
                                    </div>
                                    <div class="link text-gray-90 font-weight-bold font-size-15" href="#">
                                        Shop now
                                        <span class="link__icon ml-1">
                                            <span class="link__icon-inner"><i
                                                    class="ec ec-arrow-right-categproes"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-4 mb-xl-0 col-xl-3">
                        <a href="#"
                            class="d-black text-gray-90">
                            <div class="min-height-132 py-1 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-5 col-wd-6 pr-0">
                                    <img class="img-fluid" src="{{ asset('client/img/1920X422/z3488965753247_9740d023e93f41cb195f48294ffbb0fe-removebg-preview.png') }}" alt="Image Description">
                                </div>
                                <div class="col-6 col-xl-7 col-wd-6">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        CATCH BIG <strong>DEALS</strong> ON THE FURNITURE
                                    </div>
                                    <div class="link text-gray-90 font-weight-bold font-size-15" href="#">
                                        Shop now
                                        <span class="link__icon ml-1">
                                            <span class="link__icon-inner"><i
                                                class="ec ec-arrow-right-categproes"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- End Banner -->
            <!-- Deals-and-tabs -->
            <div class="mb-5">
                <div class="row">
                    <!-- Deal -->
                   @if ($bestDealVariant)
                        <div class="col-md-auto mb-6 mb-md-0">
                            <div class="p-3 border border-width-2 border-primary borders-radius-20 bg-white min-width-370">
                                <div class="d-flex justify-content-between align-items-center m-1 ml-2">
                                    <h3 class="font-size-22 mb-0 font-weight-normal text-lh-28 max-width-120">Khuyến mãi đặc biệt</h3>
                                    <div class="d-flex align-items-center flex-column justify-content-center bg-primary rounded-pill height-75 width-75 text-lh-1">
                                        <span class="font-size-12">Tiết kiệm</span>
                                        <div class="font-size-20 font-weight-bold">
                                            {{ number_format($bestDealVariant->price - $bestDealVariant->sale_price, 0, ',', '.') }} đ
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <a href="{{ route('client.products.show', $bestDealVariant->product->id) }}" class="d-block text-center">
                                        <img class="img-fluid" src="{{ asset('storage/' . $bestDealVariant->product->image) }}" alt="{{ $bestDealVariant->product->name }}">
                                    </a>
                                </div>

                                <h5 class="mb-2 font-size-14 text-center mx-auto max-width-180 text-lh-18">
                                    <a href="{{ route('client.products.show', $bestDealVariant->product->id) }}" class="text-blue font-weight-bold">
                                        {{ $bestDealVariant->product->name }}
                                    </a>
                                </h5>

                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <del class="font-size-18 mr-2 text-gray-2">
                                        {{ number_format($bestDealVariant->price, 0, ',', '.') }} đ
                                    </del>
                                    <ins class="font-size-30 text-red text-decoration-none">
                                        {{ number_format($bestDealVariant->sale_price, 0, ',', '.') }} đ
                                    </ins>
                                </div>

                                <div class="mb-3 mx-2">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Sẵn có: <strong>{{ $bestDealVariant->stock_quantity ?? 'N/A' }}</strong></span>
                                        <span>Đã bán: <strong>{{ $bestDealVariant->sold ?? 0 }}</strong></span>
                                    </div>

                                    @php
                                        $sold = $bestDealVariant->sold ?? 0;
                                        $available = $bestDealVariant->stock_quantity ?? 1;
                                        $percentSold = min(100, intval($sold * 100 / ($sold + $available)));
                                    @endphp

                                    <div class="rounded-pill bg-gray-3 height-20 position-relative">
                                        <span class="position-absolute left-0 top-0 bottom-0 rounded-pill bg-primary" style="width: {{ $percentSold }}%"></span>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <h6 class="font-size-15 text-gray-2 text-center mb-3">Nhanh tay! Ưu đãi kết thúc sau:</h6>
                                    <div class="js-countdown d-flex justify-content-center" data-end-date="2025/06/30"
                                        data-hours-format="%H" data-minutes-format="%M" data-seconds-format="%S">
                                        <div class="text-lh-1">
                                            <div class="text-gray-2 font-size-30 bg-gray-4 py-2 px-2 rounded-sm mb-2">
                                                <span class="js-cd-hours">00</span>
                                            </div>
                                            <div class="text-gray-2 font-size-12 text-center">GIỜ</div>
                                        </div>
                                        <div class="mx-1 pt-1 text-gray-2 font-size-24">:</div>
                                        <div class="text-lh-1">
                                            <div class="text-gray-2 font-size-30 bg-gray-4 py-2 px-2 rounded-sm mb-2">
                                                <span class="js-cd-minutes">00</span>
                                            </div>
                                            <div class="text-gray-2 font-size-12 text-center">PHÚT</div>
                                        </div>
                                        <div class="mx-1 pt-1 text-gray-2 font-size-24">:</div>
                                        <div class="text-lh-1">
                                            <div class="text-gray-2 font-size-30 bg-gray-4 py-2 px-2 rounded-sm mb-2">
                                                <span class="js-cd-seconds">00</span>
                                            </div>
                                            <div class="text-gray-2 font-size-12 text-center">GIÂY</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- End Deal -->
                    <!-- Tab Prodcut -->
                    <div class="col">
                        <!-- Features Section -->
                        <div class="">
                            <!-- Nav Classic -->
                            <div class="position-relative bg-white text-center z-index-2">
                                <ul class="nav nav-classic nav-tab justify-content-center" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active " id="pills-one-example1-tab" data-toggle="pill"
                                            href="#pills-one-example1" role="tab" aria-controls="pills-one-example1"
                                            aria-selected="true">
                                            <div class="d-md-flex justify-content-md-center align-items-md-center">
                                                Featured
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="pills-two-example1-tab" data-toggle="pill"
                                            href="#pills-two-example1" role="tab" aria-controls="pills-two-example1"
                                            aria-selected="false">
                                            <div class="d-md-flex justify-content-md-center align-items-md-center">
                                                On Sale
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="pills-three-example1-tab" data-toggle="pill"
                                            href="#pills-three-example1" role="tab" aria-controls="pills-three-example1"
                                            aria-selected="false">
                                            <div class="d-md-flex justify-content-md-center align-items-md-center">
                                                Top Rated
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- End Nav Classic -->

                            <!-- Tab Content -->
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade pt-2 show active" id="pills-one-example1" role="tabpanel"
                                    aria-labelledby="pills-one-example1-tab">
                                    <ul class="row list-unstyled products-group no-gutters">
                                        <div class="container py-5">
                                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
                                               @foreach ($featuredProducts as $product)
                                                    <div class="col">
                                                        <div class="card h-100 border-0 shadow-sm position-relative product-card">
                                                            <div class="card-body p-2 d-flex flex-column text-center">
                                                                <h6 class="card-title text-truncate mb-1">
                                                                    <a href="{{ route('client.products.show', $product->id) }}" class="text-blue font-weight-bold">
                                                                        {{ $product->name }}
                                                                    </a>
                                                                </h6>

                                                                <a href="{{ route('client.products.show', $product->id) }}">
                                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                                        class="card-img-top p-3"
                                                                        alt="{{ $product->name }}"
                                                                        style="height: 180px; object-fit: contain;">
                                                                </a>

                                                                @php
                                                                    $variant = $product->variants->sortBy(function ($v) {
                                                                        return $v->sale_price ?? $v->price;
                                                                    })->first();

                                                                    // Tính % giảm giá nếu có
                                                                    $discount = 0;
                                                                    if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                                                        $discount = round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                                                                    }
                                                                @endphp

                                                                @if ($variant)
                                                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                                                        <div class="d-flex justify-content-center align-items-center flex-column mb-2">
                                                                            <del class="text-muted small">
                                                                                {{ number_format($variant->price, 0, ',', '.') }} đ
                                                                            </del>
                                                                            <p class="text-danger fw-bold mb-0">
                                                                                {{ number_format($variant->sale_price, 0, ',', '.') }} đ
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

                                                                <div class="d-flex justify-content-center gap-2 mt-auto">
                                                                    <button class="btn btn-sm btn-outline-primary w-50">
                                                                        <i class="bi bi-cart-plus"></i>
                                                                    </button>
                                                                    <button class="btn btn-sm btn-outline-secondary w-50">
                                                                        <i class="bi bi-heart"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            {{-- Badge: Nổi bật --}}
                                                            <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                                                Nổi bật
                                                            </span>

                                                            {{-- Badge: Giảm giá --}}
                                                            @if ($discount > 0)
                                                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                                                    -{{ $discount }}%
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach


                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="tab-pane fade pt-2" id="pills-two-example1" role="tabpanel"
                                    aria-labelledby="pills-two-example1-tab">
                                    <ul class="row list-unstyled products-group no-gutters">
                                        <div class="container py-5">

                                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
                                                @foreach ($bestSellingProducts as $product)
                                                    <div class="col">
                                                        <div class="card h-100 border-0 shadow-sm position-relative product-card">
                                                            <div class="card-body p-2 d-flex flex-column text-center">
                                                                <h6 class="card-title text-truncate mb-1">
                                                                    <a href="{{ route('client.products.show', $product->id) }}" class="text-blue font-weight-bold">
                                                                        {{ $product->name }}
                                                                    </a>
                                                                </h6>

                                                                <a href="{{ route('client.products.show', $product->id) }}">
                                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                                        class="card-img-top p-3"
                                                                        alt="{{ $product->name }}"
                                                                        style="height: 180px; object-fit: contain;">
                                                                </a>

                                                                @php
                                                                    // Lấy biến thể tốt nhất
                                                                    $variant = $product->variants->sortBy(function ($v) {
                                                                        return $v->sale_price ?? $v->price;
                                                                    })->first();

                                                                    // Tính % giảm giá nếu có
                                                                    $discount = 0;
                                                                    if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                                                        $discount = round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                                                                    }
                                                                @endphp

                                                                @if ($variant)
                                                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                                                        <div class="d-flex justify-content-center align-items-center flex-column mb-2">
                                                                            <del class="text-muted small">
                                                                                {{ number_format($variant->price, 0, ',', '.') }} đ
                                                                            </del>
                                                                            <p class="text-danger fw-bold mb-0">
                                                                                {{ number_format($variant->sale_price, 0, ',', '.') }} đ
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

                                                                <div class="d-flex justify-content-center gap-2 mt-auto">
                                                                    <button class="btn btn-sm btn-outline-primary w-50">
                                                                        <i class="bi bi-cart-plus"></i>
                                                                    </button>
                                                                    <button class="btn btn-sm btn-outline-secondary w-50">
                                                                        <i class="bi bi-heart"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            {{-- Badge: Bán chạy --}}
                                                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                                                                Bán chạy
                                                            </span>

                                                            {{-- Badge: Giảm giá --}}
                                                            @if ($discount > 0)
                                                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                                                    -{{ $discount }}%
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="tab-pane fade pt-2" id="pills-three-example1" role="tabpanel"
                                    aria-labelledby="pills-three-example1-tab">
                                    <ul class="row list-unstyled products-group no-gutters">
                                        <div class="container py-5">
                                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
                                               @foreach ($topRatedProducts as $product)
                                                    <div class="col">
                                                        <div class="card h-100 border-0 shadow-sm position-relative product-card">
                                                            <div class="card-body p-2 d-flex flex-column text-center">
                                                                <h6 class="card-title text-truncate mb-1">
                                                                    <a href="{{ route('client.products.show', $product->id) }}" class="text-blue font-weight-bold">
                                                                        {{ $product->name }}
                                                                    </a>
                                                                </h6>

                                                                <a href="{{ route('client.products.show', $product->id) }}">
                                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                                        class="card-img-top p-3"
                                                                        alt="{{ $product->name }}"
                                                                        style="height: 180px; object-fit: contain;">
                                                                </a>

                                                                @php
                                                                    // Lấy biến thể có giá ưu đãi nhất
                                                                    $variant = $product->variants->sortBy(function($v) {
                                                                        return $v->sale_price ?? $v->price;
                                                                    })->first();
                                                                @endphp

                                                                @if ($variant)
                                                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                                                        <div class="d-flex justify-content-center align-items-center flex-column mb-2">
                                                                            <del class="text-muted small">
                                                                                {{ number_format($variant->price, 0, ',', '.') }} đ
                                                                            </del>
                                                                            <p class="text-danger fw-bold mb-0">
                                                                                {{ number_format($variant->sale_price, 0, ',', '.') }} đ
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
                                                                    ⭐ {{ number_format($product->reviews_avg_rating, 1) }}/5
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

                                                            {{-- Badge: Đánh giá cao --}}
                                                            <span class="badge bg-info text-dark position-absolute top-0 start-0 m-2">
                                                                Đánh giá cao
                                                            </span>
                                                            @if (isset($discount) && $discount > 0)
                                                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                                                    -{{ $discount }}%
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            <!-- End Tab Content -->
                        </div>
                        <!-- End Features Section -->
                    </div>
                    <!-- End Tab Prodcut -->
                </div>
            </div>
            <!-- End Deals-and-tabs -->
        </div>
        <div class="nav nav-classic nav-tab">
            <h2 class="nav-link active">latest products</h2>
        </div>

        <div class="container py-5">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
                @foreach ($newProducts as $product)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm position-relative product-card">
                            <div class="card-body p-2 d-flex flex-column text-center">
                                <h6 class="card-title text-truncate mb-1">
                                    <a href="{{ route('client.products.show', $product->id) }}" class=" text-blue font-weight-bold">
                                        {{ $product->name }}
                                    </a>
                                </h6>

                                <a href="{{ route('client.products.show', $product->id) }}">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top p-3" alt="{{ $product->name }}" style="height: 180px; object-fit: contain;">
                                </a>

                                @php
                                    $variant = $product->variants->sortBy(function($v) {
                                        return $v->sale_price ?? $v->price;
                                    })->first();

                                    $discount = 0;
                                    if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                        $discount = round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                                    }
                                @endphp

                                @if ($variant)
                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                        <div class="d-flex justify-content-center align-items-center flex-column">
                                            <del class="text-muted small">
                                                {{ number_format($variant->price, 0, ',', '.') }} đ
                                            </del>
                                            <p class="text-danger fw-bold mb-2 mb-0">
                                                {{ number_format($variant->sale_price, 0, ',', '.') }} đ
                                            </p>
                                        </div>
                                    @else
                                        <p class="text-danger fw-bold mb-2">
                                            {{ number_format($variant->price, 0, ',', '.') }} đ
                                        </p>
                                    @endif
                                @else
                                    <p class="text-muted small">Liên hệ để biết giá</p>
                                @endif

                                <div class="d-flex justify-content-center gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary w-50">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary w-50">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Badge: Mới --}}
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">Mới</span>

                            {{-- Badge: Giảm giá --}}
                            @if ($discount > 0)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    -{{ $discount }}%
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="nav nav-classic nav-tab">
            <h2 class="nav-link active">PC</h2>
        </div>

        <div class="container py-5">
            <div class="row row-cols-2 row-cols-md-4 g-3">
                @foreach ($pcProducts as $product)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm position-relative product-card">
                            <div class="card-body p-2 d-flex flex-column text-center">
                                <h6 class="card-title text-truncate mb-1">
                                    <a href="{{ route('client.products.show', $product->id) }}" class="text-blue font-weight-bold">
                                        {{ $product->name }}
                                    </a>
                                </h6>

                                <a href="{{ route('client.products.show', $product->id) }}">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="card-img-top p-3"
                                        alt="{{ $product->name }}"
                                        style="height: 180px; object-fit: contain;">
                                </a>

                                @php
                                    $variant = $product->variants->sortBy(function($v) {
                                        return $v->sale_price ?? $v->price;
                                    })->first();

                                    $discount = 0;
                                    if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                        $discount = round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                                    }
                                @endphp

                                @if ($variant)
                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                        <div class="d-flex justify-content-center align-items-center flex-column">
                                            <del class="text-muted small">
                                                {{ number_format($variant->price, 0, ',', '.') }} đ
                                            </del>
                                            <p class="text-danger fw-bold mb-2 mb-0">
                                                {{ number_format($variant->sale_price, 0, ',', '.') }} đ
                                            </p>
                                        </div>
                                    @else
                                        <p class="text-danger fw-bold mb-2">
                                            {{ number_format($variant->price, 0, ',', '.') }} đ
                                        </p>
                                    @endif
                                @else
                                    <p class="text-muted small">Liên hệ để biết giá</p>
                                @endif

                                <div class="d-flex justify-content-center gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary w-50">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary w-50">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Badge danh mục --}}
                            <span class="badge bg-secondary position-absolute top-0 start-0 m-2">PC</span>

                            {{-- Badge giảm giá nếu có --}}
                            @if ($discount > 0)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    -{{ $discount }}%
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
        <div class="nav nav-classic nav-tab">
            <h2 class="nav-link active">Laptop</h2>
        </div>

        <div class="container py-5">
          <div class="row row-cols-2 row-cols-md-4 g-3">
                @foreach ($laptopProducts as $product)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm position-relative product-card">
                            <div class="card-body p-2 d-flex flex-column text-center">
                                <h6 class="card-title text-truncate mb-1">
                                    <a href="{{ route('client.products.show', $product->id) }}" class="text-blue font-weight-bold">
                                        {{ $product->name }}
                                    </a>
                                </h6>

                                <a href="{{ route('client.products.show', $product->id) }}">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="card-img-top p-3"
                                        alt="{{ $product->name }}"
                                        style="height: 180px; object-fit: contain;">
                                </a>

                                @php
                                    $variant = $product->variants->sortBy(function($v) {
                                        return $v->sale_price ?? $v->price;
                                    })->first();

                                    $discount = 0;
                                    if ($variant && $variant->sale_price && $variant->sale_price < $variant->price) {
                                        $discount = round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                                    }
                                @endphp

                                @if ($variant)
                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                        <div class="d-flex justify-content-center align-items-center flex-column">
                                            <del class="text-muted small">
                                                {{ number_format($variant->price, 0, ',', '.') }} đ
                                            </del>
                                            <p class="text-danger fw-bold mb-2 mb-0">
                                                {{ number_format($variant->sale_price, 0, ',', '.') }} đ
                                            </p>
                                        </div>
                                    @else
                                        <p class="text-danger fw-bold mb-2">
                                            {{ number_format($variant->price, 0, ',', '.') }} đ
                                        </p>
                                    @endif
                                @else
                                    <p class="text-muted small">Liên hệ để biết giá</p>
                                @endif

                                <div class="d-flex justify-content-center gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary w-50">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary w-50">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Badge danh mục --}}
                            <span class="badge bg-secondary position-absolute top-0 start-0 m-2">Laptop</span>

                            {{-- Badge giảm giá nếu có --}}
                            @if ($discount > 0)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    -{{ $discount }}%
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection

