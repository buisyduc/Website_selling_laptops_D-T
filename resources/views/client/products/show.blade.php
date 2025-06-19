@extends('client.layouts.layout')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}" class="text-decoration-none">Trang chủ</a>
            </li>

            @foreach ($breadcrumbs as $category)
                <li class="breadcrumb-item">
                    {{-- <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none">
                    {{ $category->name }}
                </a> --}}
                    {{ $category->name }}
                </li>
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

    {{-- resources/views/products/show.blade.php --}}
    <div class="container mt-4">
        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="position-relative">
                    @if ($product->coupons->isNotEmpty())
                        @php
                            $activeCoupon = $product->coupons
                                ->where('status', 'active')
                                ->where('start_date', '<=', now())
                                ->where('end_date', '>=', now())
                                ->first();
                        @endphp
                        @if ($activeCoupon)
                            <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 rounded-end mt-2"
                                style="font-size: 0.8rem;">
                                -{{ $activeCoupon->discount_type == 'percentage' ? $activeCoupon->discount_value . '%' : number_format($activeCoupon->discount_value) . '₫' }}
                            </span>
                        @endif
                    @endif

                    <img id="mainImage"
                        src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x350/e3f2fd/1976d2?text=' . urlencode($product->name) }}"
                        alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 400px; width: auto;">

                </div>

                @if ($product->images->count() > 0)
                    <div class="d-flex mt-3 gap-2 flex-wrap">
                        <!-- Main product image as first thumbnail -->
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/80x60/e3f2fd/1976d2?text=1' }}"
                            alt="Main View" class="img-thumbnail border-primary"
                            style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                            onclick="changeImage(this, '{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x350/e3f2fd/1976d2?text=' . urlencode($product->name) }}')">

                        <!-- Additional product images -->
                        @foreach ($product->images as $index => $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="View {{ $index + 2 }}"
                                class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                onclick="changeImage(this, '{{ asset('storage/' . $image->image_path) }}')">
                        @endforeach
                    </div>
                @endif
            </div>


            <!-- Product Details -->
            <div class="col-md-6">
                <h1 class="h3 mb-3 fw-bold">{{ $product->name }}</h1>



                <!-- Ratings -->
                <div class="mb-3">
                    <span class="text-warning">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($product->averageRating()))
                                <i class="fas fa-star"></i>
                            @elseif($i <= ceil($product->averageRating()))
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </span>
                    <span class="ms-2">| {{ $product->reviews->count() }} Đánh Giá</span>
                    @if ($product->totalSold() > 0)
                        <span class="ms-2">| Đã bán: {{ $product->totalSold() }}</span>
                    @endif
                </div>

                <!-- Brand -->
                @if ($product->brand)
                    <div class="mb-3">
                        <span class="badge bg-success">{{ $product->brand->name }}</span>
                    </div>
                @endif

                <!-- Price Display -->
                <div class="mb-4">
                    <span class="display-6 text-danger fw-bold" id="currentPrice">
                        {{ number_format($product->variants->first()->price) }}₫
                    </span>
                </div>


                @php
                    $groupedAttributes = [];

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

                <div class="mb-4">
                    <h5>Tùy chọn sản phẩm:</h5>
                    @foreach ($attributeOptionsWithPrices as $attrId => $attribute)
                        <div class="mb-3">
                            <strong>{{ $attribute['name'] }}</strong>
                            <div class="d-flex flex-wrap gap-3 mt-2">
                                @foreach ($attribute['options'] as $optId => $option)
                                    <div class="option-box p-3 border rounded text-center"
                                        data-attribute="{{ $attrId }}" data-option="{{ $optId }}"
                                        onclick="selectOption(this)"
                                        style="width: 150px;
                                                cursor: pointer;
                                                opacity: {{ $option['stock'] === 0 ? '0.4' : '1' }};
                                                pointer-events: {{ $option['stock'] === 0 ? 'none' : 'auto' }};">
                                        <div class="fw-bold">{{ $option['value'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach


                </div>
                <input type="hidden" id="selected-variant-id" name="variant_id">



                <!-- Promotion Box -->
                @if ($product->coupons->isNotEmpty())
                    <div class="alert alert-danger border-danger" role="alert">
                        <h6 class="alert-heading text-danger">
                            <i class="fas fa-gift"></i> KHUYẾN MÃI
                        </h6>
                        <p class="mb-2 fw-bold">Chương trình khuyến mãi:</p>
                        <ul class="mb-0">
                            @foreach ($product->coupons->where('status', 'active') as $coupon)
                                @if ($coupon->start_date <= now() && $coupon->end_date >= now())
                                    <li>
                                        {{ $coupon->description ?? $coupon->code }} -
                                        Giảm
                                        {{ $coupon->discount_type == 'percentage' ? $coupon->discount_value . '%' : number_format($coupon->discount_value) . '₫' }}
                                        @if ($coupon->end_date)
                                            (Đến {{ $coupon->end_date->format('d/m/Y') }})
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-flex mb-4">
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex mb-4">
                        @auth
                            <button class="btn btn-danger btn-lg flex-md-fill" onclick="addToCart()" id="addToCartBtn"
                                {{ $product->variants->count() > 0 && $product->variants->first()->stock_quantity == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cart-plus"></i> Thêm Vào Giỏ Hàng
                            </button>
                            <button class="btn btn-danger btn-lg flex-md-fill" onclick="buyNow()" id="buyNowBtn"
                                {{ $product->variants->count() > 0 && $product->variants->first()->stock_quantity == 0 ? 'disabled' : '' }}>
                                Mua ngay
                            </button>
                        @else
                            <button class="btn btn-danger btn-lg flex-md-fill" onclick="openLoginModal()" id="addToCartBtn">
                                <i class="fas fa-cart-plus"></i> Thêm Vào Giỏ Hàng
                            </button>
                            <button onclick="openLoginModal()" class="btn btn-danger btn-lg flex-md-fill">Mua ngay</button>

                        @endauth
                    </div>
                </div>
            </div>


            <!-- Product Info -->
            <div class="row mt-5">
                <div class="col-md-8">
                    <!-- Product Description -->
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

                    <!-- Reviews Section -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Đánh giá sản phẩm ({{ $product->reviews->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @if ($product->reviews->count() > 0)
                                @foreach ($product->reviews->take(5) as $review)
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $review->user->name ?? 'Khách hàng' }}</strong>
                                                <div class="text-warning">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        @if ($review->comment)
                                            <p class="mt-2 mb-0">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                @endforeach

                                @if ($product->reviews->count() > 5)
                                    <div class="text-center">
                                        <a href="#" class="btn btn-outline-primary">Xem thêm đánh giá</a>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-bold">Thông tin sản phẩm</h6>

                            @if ($product->brand)
                                <p class="card-text">
                                    <strong>Thương hiệu:</strong> {{ $product->brand->name }}
                                </p>
                            @endif

                            @if ($product->category)
                                <p class="card-text">
                                    <strong>Danh mục:</strong> {{ $product->category->name }}
                                </p>
                            @endif

                            @if ($product->release_date)
                                <p class="card-text">
                                    <strong>Ngày phát hành:</strong> {{ $product->release_date->format('d/m/Y') }}
                                </p>
                            @endif

                            <h6 class="card-title fw-bold mt-3">Tình trạng</h6>
                            <p class="card-text">
                                @if ($product->variants->count() > 0)
                                    @php $totalStock = $product->variants->sum('stock_quantity'); @endphp
                                    @if ($totalStock > 0)
                                        <span class="badge bg-success">Còn hàng ({{ $totalStock }} sản phẩm)</span>
                                    @else
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @endif
                                @else
                                    <span class="badge bg-warning">Hết hàng</span>
                                @endif
                            </p>

                            <h6 class="card-title fw-bold">Chính sách</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-shield-alt text-success"></i> Bảo hành chính hãng</li>
                                <li><i class="fas fa-sync-alt text-info"></i> Đổi trả trong 7 ngày</li>
                                <li><i class="fas fa-shipping-fast text-primary"></i> Giao hàng toàn quốc</li>
                                <li><i class="fas fa-headset text-warning"></i> Hỗ trợ 24/7</li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
            <div class="nav nav-classic nav-tab">
                <h2 class="nav-link active">Related Products</h2>
            </div>
            @if ($relatedProducts->isNotEmpty())
                <div class="tab-pane fade pt-2 show active" id="related-products" role="tabpanel">
                    <ul class="row list-unstyled products-group no-gutters">
                        <div class="container py-5">
                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                                @foreach ($relatedProducts as $product)
                                    <div class="col">
                                        <div class="card h-100 border-0 shadow-sm position-relative product-card">
                                            <div class="card-body p-2 d-flex flex-column text-center">
                                                <h6 class="card-title text-truncate mb-1">
                                                    <a href="{{ route('client.products.show', $product->slug) }}"
                                                        class="text-blue font-weight-bold">
                                                        {{ $product->name }}
                                                    </a>
                                                </h6>

                                                @php
                                                    $firstImage = $product->images->first();
                                                @endphp

                                                <a href="{{ route('client.products.show', $product->slug) }}">
                                                    <img src="{{ $firstImage ? asset('storage/' . $firstImage->image_path) : asset('images/default.png') }}"
                                                        class="card-img-top p-3" alt="{{ $product->name }}"
                                                        style="height: 180px; object-fit: contain;">
                                                </a>

                                                @php
                                                    $variant = $product->variants
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
                                                    ⭐ {{ number_format($product->reviews_avg_rating ?? 0, 1) }}/5
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



            <script>
                const variantCombinations = @json($variantsForJs);
                const totalAttributes = {{ count($attributeOptionsWithPrices) }};
                const ramAttrId = {{ $ramAttrId ?? 'null' }};
                let selectedOptions = {};
                let selectedVariantId = null;
                let selectedPrice = {{ $product->variants->count() > 0 ? $product->variants->first()->price : 0 }};

                function selectOption(el) {
                    const attrId = el.dataset.attribute;
                    const optId = parseInt(el.dataset.option);

                    selectedOptions[attrId] = optId;

                    document.querySelectorAll(`.option-box[data-attribute="${attrId}"]`).forEach(box => {
                        box.classList.remove('border-primary', 'bg-primary', 'text-white');
                    });
                    el.classList.add('border-primary', 'bg-primary', 'text-white');

                    updateAvailableOptions();

                    let matchedVariant = null;
                    for (const variant of variantCombinations) {
                        let isMatch = true;
                        for (const [aId, oId] of Object.entries(selectedOptions)) {
                            if (parseInt(variant.options[aId]) !== parseInt(oId)) {
                                isMatch = false;
                                break;
                            }
                        }

                        if (isMatch && Object.keys(variant.options).length === Object.keys(selectedOptions).length) {
                            matchedVariant = variant;
                            break;
                        }
                    }

                    if (matchedVariant) {
                        selectedVariantId = matchedVariant.id;
                        selectedPrice = parseFloat(matchedVariant.price);
                        document.getElementById('selected-variant-id').value = selectedVariantId;
                        document.getElementById('currentPrice').textContent = selectedPrice.toLocaleString('vi-VN') + ' ₫';
                    } else {
                        selectedVariantId = null;
                        document.getElementById('selected-variant-id').value = '';
                    }
                }

                function updateAvailableOptions() {
                    const attributes = Object.keys(@json($attributeOptionsWithPrices));
                    for (const attrId of attributes) {
                        document.querySelectorAll(`.option-box[data-attribute="${attrId}"]`).forEach(optionEl => {
                            const currentOptId = parseInt(optionEl.dataset.option);
                            const simulatedSelection = {
                                ...selectedOptions,
                                [attrId]: currentOptId
                            };

                            const isValid = variantCombinations.some(variant => {
                                return Object.entries(simulatedSelection).every(([aId, oId]) =>
                                    variant.options[aId] && parseInt(variant.options[aId]) === parseInt(oId)
                                );
                            });

                            if (isValid) {
                                optionEl.classList.remove('d-none');
                                optionEl.style.pointerEvents = 'auto';
                                optionEl.style.opacity = 1;
                            } else {
                                optionEl.classList.add('d-none');
                                optionEl.style.pointerEvents = 'none';
                                optionEl.style.opacity = 0.3;
                            }
                        });
                    }
                }

                async function addToCart() {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const productId = "{{ $product->id }}";

                    if (!selectedVariantId || Object.keys(selectedOptions).length < totalAttributes) {
                        showToast('Cảnh báo', 'Vui lòng chọn đầy đủ các phiên bản sản phẩm!', 'error');
                        return;
                    }

                    try {
                        const response = await fetch("{{ route('cart.add') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                variant_id: selectedVariantId,
                                quantity: 1
                            })
                        });

                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const data = await response.json();
                            if (data.status === 'success') {
                                if (document.getElementById('cart-count')) {
                                    document.getElementById('cart-count').textContent = data.total_quantity;
                                }
                                if (document.getElementById('cart-total')) {
                                    document.getElementById('cart-total').textContent = data.total_amount;
                                }
                                showToast('Thành công', 'Đã thêm sản phẩm vào giỏ hàng!', 'success');
                            } else {
                                showToast('Lỗi', data.message || 'Thêm vào giỏ hàng thất bại!', 'error');
                            }
                        } else {
                            const raw = await response.text();
                            console.error('Phản hồi không phải JSON:', raw);
                            showToast('Lỗi', 'Phản hồi không hợp lệ từ máy chủ!', 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        showToast('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại!', 'error');
                    }
                }

                function showToast(title, message, type = 'success') {
                    const toastId = 'custom-toast-' + Date.now();
                    const toastHtml = `
            <div id="${toastId}" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 fade show" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>${title}:</strong> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>`;
                    document.body.insertAdjacentHTML('beforeend', toastHtml);
                    setTimeout(() => {
                        const toast = document.getElementById(toastId);
                        if (toast) toast.remove();
                    }, 3000);
                }

                function changeImage(element, imageUrl) {
                    // Đổi ảnh chính
                    document.getElementById('mainImage').src = imageUrl;

                    // Xoá border thumbnail đang active
                    document.querySelectorAll('.img-thumbnail').forEach(img => {
                        img.classList.remove('border-primary');
                    });

                    // Gắn border cho ảnh đang chọn
                    element.classList.add('border-primary');
                }
            </script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                function buyNow() {
                    const variantId = selectedVariantId;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    if (!variantId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thiếu thông tin',
                            text: 'Vui lòng chọn đầy đủ các phiên bản sản phẩm trước khi mua!',
                            confirmButtonText: 'Đã hiểu',
                            confirmButtonColor: '#3085d6'
                        });
                        return;
                    }

                    // Tạo form ẩn để submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('cart.buyNow') }}";

                    // CSRF
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = csrfToken;
                    form.appendChild(csrf);

                    // variant_id
                    const variantInput = document.createElement('input');
                    variantInput.type = 'hidden';
                    variantInput.name = 'variant_id';
                    variantInput.value = variantId;
                    form.appendChild(variantInput);

                    // quantity
                    const qtyInput = document.createElement('input');
                    qtyInput.type = 'hidden';
                    qtyInput.name = 'quantity';
                    qtyInput.value = 1;
                    form.appendChild(qtyInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            </script>



  @section('footer')
     @include('client.layouts.partials.footer')
@endsection
        @endsection
