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
            @foreach (['macbook', 'asus', 'lenovo', 'msi', 'acer', 'hp', 'dell', 'lg'] as $brand)
                <a href="?brand={{ $brand }}" class="btn btn-light border px-4 py-2 fw-bold">
                    <img src="{{ asset('storage/brands/' . $brand . '.png') }}" alt="{{ strtoupper($brand) }}"
                        style="height:24px; margin-right:8px;">
                    {{ strtoupper($brand) }}
                </a>
            @endforeach
        </div>

        <!-- Lọc theo tiêu chí -->
     <!-- Bộ lọc dạng nút -->
<div class="mb-4 d-flex flex-wrap justify-content-center gap-2">

    <!-- Tất cả -->
    <a href="?filter=all" class="btn btn-outline-danger fw-bold">
        <i class="fas fa-filter me-1"></i> Tất cả
    </a>

    <!-- Giá -->
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            💰 Giá
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?price=0-10000000">Dưới 10 triệu</a></li>
            <li><a class="dropdown-item" href="?price=10000000-15000000">10 - 15 triệu</a></li>
            <li><a class="dropdown-item" href="?price=15000000-20000000">15 - 20 triệu</a></li>
            <li><a class="dropdown-item" href="?price=20000000-999999999">Trên 20 triệu</a></li>
        </ul>
    </div>

    <!-- Thương hiệu -->
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            🏷 Thương hiệu
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?brand=dell">Dell</a></li>
            <li><a class="dropdown-item" href="?brand=asus">Asus</a></li>
            <li><a class="dropdown-item" href="?brand=hp">HP</a></li>
            <li><a class="dropdown-item" href="?brand=lenovo">Lenovo</a></li>
            <li><a class="dropdown-item" href="?brand=acer">Acer</a></li>
            <li><a class="dropdown-item" href="?brand=apple">Apple</a></li>
            <li><a class="dropdown-item" href="?brand=msi">MSI</a></li>
        </ul>
    </div>

    <!-- CPU -->
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            ⚡ CPU
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?cpu=i3">Intel Core i3</a></li>
            <li><a class="dropdown-item" href="?cpu=i5">Intel Core i5</a></li>
            <li><a class="dropdown-item" href="?cpu=i7">Intel Core i7</a></li>
            <li><a class="dropdown-item" href="?cpu=i9">Intel Core i9</a></li>
            <li><a class="dropdown-item" href="?cpu=ryzen3">AMD Ryzen 3</a></li>
            <li><a class="dropdown-item" href="?cpu=ryzen5">AMD Ryzen 5</a></li>
            <li><a class="dropdown-item" href="?cpu=ryzen7">AMD Ryzen 7</a></li>
            <li><a class="dropdown-item" href="?cpu=ryzen9">AMD Ryzen 9</a></li>
        </ul>
    </div>

    <!-- RAM -->
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            🖥 RAM
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?ram=4">4 GB</a></li>
            <li><a class="dropdown-item" href="?ram=8">8 GB</a></li>
            <li><a class="dropdown-item" href="?ram=16">16 GB</a></li>
            <li><a class="dropdown-item" href="?ram=32">32 GB</a></li>
        </ul>
    </div>

    <!-- Ổ cứng -->
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            💾 Ổ cứng
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?storage=256">SSD 256 GB</a></li>
            <li><a class="dropdown-item" href="?storage=512">SSD 512 GB</a></li>
            <li><a class="dropdown-item" href="?storage=1024">SSD 1 TB</a></li>
            <li><a class="dropdown-item" href="?storage=hdd1tb">HDD 1 TB</a></li>
        </ul>
    </div>
</div>

        <!-- Nút liên hệ -->
        {{-- <div class="mb-4 d-flex justify-content-end">
            <a href="" class="btn btn-danger fw-bold px-4 py-2"><i class="fas fa-headphones me-2"></i> Liên hệ</a> --}}
        </div>

        <!-- Product list -->
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <a href="{{ route('client.products.show', $product->id) }}">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}"
                                class="card-img-top" style="height:180px; object-fit:contain;">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text text-danger fw-bold">{{ number_format($product->variants->min('price')) }}₫
                            </p>
                            <a href="{{ route('client.products.show', $product->id) }}"
                                class="btn btn-sm btn-primary mt-auto">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
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
