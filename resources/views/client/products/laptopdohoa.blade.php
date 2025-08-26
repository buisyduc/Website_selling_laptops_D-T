@extends('client.layouts.layout')

@section('content')
    <div class="container py-4">

        <!-- Tabs sản phẩm -->
        <div class="mb-4 d-flex justify-content-center gap-3"
            style="border:1px solid #eee; border-radius:16px; padding:16px;">
            <a href="{{ url('/products/laptopvanphong') }}"
                class="btn {{ request()->is('products/laptopvanphong') ? 'btn-danger' : 'btn-outline-danger' }} fw-bold"><i
                    class="fas fa-laptop me-2"></i> Laptop văn phòng</a>
            <a href="{{ url('/products/laptopsinhvien') }}"
                class="btn {{ request()->is('products/laptopsinhvien') ? 'btn-danger' : 'btn-outline-danger' }} fw-bold"><i
                    class="fas fa-desktop me-2"></i> Laptop sinh viên</a>
            <a href="{{ url('/products/laptopmongnhe') }}"
                class="btn {{ request()->is('products/laptopmongnhe') ? 'btn-danger' : 'btn-outline-danger' }} fw-bold"><i
                    class="fas fa-desktop me-2"></i> Laptop mỏng nhẹ</a>
            <a href="{{ url('/products/laptopgaming') }}"
                class="btn {{ request()->is('products/laptopgaming') ? 'btn-danger' : 'btn-outline-danger' }} fw-bold"><i
                    class="fas fa-desktop me-2"></i> Laptop gaming</a>
            <a href="{{ url('/products/laptopdohoa') }}"
                class="btn {{ request()->is('products/laptopdohoa') ? 'btn-danger' : 'btn-outline-danger' }} fw-bold"><i
                    class="fas fa-desktop me-2"></i> Laptop đồ họa</a>
            <a href="{{ url('/products/laptopAI') }}"
                class="btn {{ request()->is('products/laptopAI') ? 'btn-danger' : 'btn-outline-danger' }} fw-bold"><i
                    class="fas fa-desktop me-2"></i> Laptop AI</a>
        </div>
        <!-- Banner ngang 2 ảnh, căn giữa và cân đối, có nút chuyển ảnh -->
        <div class="mb-4 d-flex justify-content-center gap-3">

            <div class="position-relative" style="max-width:100%;">

                <img id="banner-left" src="{{ asset('storage/products/Banner1.webp') }}" alt="Banner Left"
                    style="height:100px; width:100%; object-fit:cover; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                <button class="btn btn-light position-absolute" onclick="changeBanner('left', -1)"
                    style="top:50%; left:10px; transform:translateY(-50%); border-radius:50%; width:32px; height:32px; padding:0;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-light position-absolute" onclick="changeBanner('left', 1)"
                    style="top:50%; right:10px; transform:translateY(-50%); border-radius:50%; width:32px; height:32px; padding:0;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="position-relative" style="max-width:100%;">
                <img id="banner-right" src="{{ asset('storage/products/Banner2.webp') }}" alt="Banner Right"
                    style="height:100px; width:100%; object-fit:cover; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                <button class="btn btn-light position-absolute" onclick="changeBanner('right', -1)"
                    style="top:50%; left:10px; transform:translateY(-50%); border-radius:50%; width:32px; height:32px; padding:0;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-light position-absolute" onclick="changeBanner('right', 1)"
                    style="top:50%; right:10px; transform:translateY(-50%); border-radius:50%; width:32px; height:32px; padding:0;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <script>
            // Danh sách ảnh cho mỗi khung
            const bannersLeft = [
                "{{ asset('storage/products/Banner1.webp') }}",
                "{{ asset('storage/products/Banner3.webp') }}",
                "{{ asset('storage/products/Banner5.webp') }}"
            ];
            const bannersRight = [
                "{{ asset('storage/products/Banner2.webp') }}",
                "{{ asset('storage/products/Banner4.webp') }}",
                "{{ asset('storage/products/Banner6.webp') }}"
            ];
            let indexLeft = 0;
            let indexRight = 0;

            function changeBanner(side, dir) {
                if (side === 'left') {
                    indexLeft = (indexLeft + dir + bannersLeft.length) % bannersLeft.length;
                    document.getElementById('banner-left').src = bannersLeft[indexLeft];
                } else {
                    indexRight = (indexRight + dir + bannersRight.length) % bannersRight.length;
                    document.getElementById('banner-right').src = bannersRight[indexRight];
                }
            }
        </script>
        <!-- Logo hãng -->
        <div class="mb-4 d-flex flex-wrap justify-content-center gap-3">
            <a href="?filter=all"
                class="btn {{ request()->get('filter') == 'all' || !request()->get('brand') ? 'btn-danger' : 'btn-transparent' }} fw-bold">
                <i class="fas fa-filter me-1"></i> Tất cả
            </a>
            @foreach (['macbook', 'asus', 'lenovo', 'msi', 'acer', 'hp', 'dell', 'lg'] as $brand)
                <a href="?brand={{ $brand }}"
                    class="btn {{ request()->get('brand') == $brand ? 'btn-danger' : 'btn-transparent' }} fw-bold d-flex align-items-center">
                    <img src="{{ asset('storage/brands/' . $brand . '.png') }}" alt="{{ strtoupper($brand) }}"
                        style="height:24px; margin-right:8px;">
                    {{ strtoupper($brand) }}
                </a>
            @endforeach
        </div>



    </div>

    <!-- Product list -->
    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-3 col-6 mb-4">
                <div class="card h-100 border rounded shadow-sm">
                    <a href="{{ route('client.products.show', $product->id) }}" class="text-decoration-none">
                        <div class="d-flex justify-content-center align-items-center p-3" style="height:200px;">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}"
                                class="img-fluid" style="max-height: 100%; object-fit: contain;"
                                alt="{{ $product->name }}">
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column p-2">
                        {{-- Tên sản phẩm --}}
                        <h6 class="card-title text-dark text-truncate" style="min-height: 40px;">
                            {{ $product->name }}
                        </h6>

                        {{-- Giá --}}
                        <p class="text-danger fw-bold mb-2">
                            {{ number_format($product->variants->min('price')) }}₫
                        </p>

                        {{-- Đánh giá giả lập --}}
                        <div class="mb-2">
                            <small class="text-muted">
                                ⭐ {{ rand(4, 5) }}.0 | Đã bán {{ rand(50, 500) }}
                            </small>
                        </div>

                        {{-- Nút chi tiết --}}
                        <a href="{{ route('client.products.show', $product->id) }}"
                            class="btn btn-sm btn-danger mt-auto w-100">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">
                    Không có sản phẩm nào có thương hiệu như vậy trong danh mục này
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
    </div>
@endsection
@section('footer')
    @include('client.layouts.partials.footer')
@endsection
