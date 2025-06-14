@extends('client.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Hình ảnh -->
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p class="text-muted">{{ $product->category->name ?? '' }}</p>

            {{-- Giá mặc định hoặc theo biến thể --}}
            @if ($product->variants->count())
            <h4 class="text-danger">
                {{ number_format($product->variants->first()->price, 0, ',', '.') }}₫
            </h4>
            @else
            <h4 class="text-danger">
                {{ number_format($product->price, 0, ',', '.') }}₫
            </h4>
            @endif

            <p>{{ $product->short_description }}</p>

            <!-- Biến thể sản phẩm -->
            @if ($product->variants->count())
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="mb-3">
                    <label for="variant_id">Chọn cấu hình:</label>
                    <select name="variant_id" id="variant_id" class="form-select" required>
                        @foreach ($product->variants as $variant)
                        <option value="{{ $variant->id }}">
                            {{ $variant->cpu }} / {{ $variant->ram }} / {{ $variant->storage }} / {{ $variant->color }} -
                            {{ number_format($variant->price, 0, ',', '.') }}₫
                        </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    🛒 Thêm vào giỏ hàng
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Mô tả chi tiết -->
    <div class="mt-5">
        <h4>Chi tiết sản phẩm</h4>
        <div>{!! nl2br(e($product->description)) !!}</div>
    </div>
</div>
@endsection
