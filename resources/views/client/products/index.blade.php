@extends('client.layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <h3>Danh sách sản phẩm</h3>

    <div class="product-grid">
        @foreach ($products as $product)
            <div class="product-card">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="product-image" alt="{{ $product->name }}">
                @else
                    <div style="height: 18px; background: #eee; display:flex; align-items:center; justify-content:center;">No image</div>
                @endif

                <h4>{{ $product->name }}</h4>
                <p>{{ number_format($product->price, 0, ',', '.') }} đ</p>
                <p>{{ Str::limit($product->description, 60) }}</p>
                <a href="{{ route('client.products.show', $product->id) }}">Xem chi tiết</a>
            </div>
        @endforeach
    </div>
@endsection
