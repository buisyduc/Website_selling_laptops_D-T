@extends('client.layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
<h3>Danh sách sản phẩm</h3>

  <div class="product-grid">
        @foreach ($products as $product)
            <div class="product-card">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                @else
                    <div class="no-image-placeholder">No image</div>
                @endif

                <h4>{{ $product->name }}</h4>
                <p>{{ number_format($product->price, 0, ',', '.') }} đ</p>
                <a href="{{ route('client.products.show', $product->id) }}">Xem chi tiết</a>
            </div>
        @endforeach
    </div>
@endsection
