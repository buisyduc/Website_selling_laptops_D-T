@extends('client.layouts.app')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>
    @if($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" style="max-width: 400px;" alt="{{ $product->name }}">
    @endif

    <p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} đ</p>
    <p>{{ $product->description }}</p>

    <a href="{{ route('client.products.index') }}">⬅ Quay lại danh sách</a>
@endsection
