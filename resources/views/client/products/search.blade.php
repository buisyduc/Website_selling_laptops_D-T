@extends('client.layouts.layout')

@section('content')
    <h1>Kết quả tìm kiếm cho: "{{ $query }}"</h1>

    @if($products->isEmpty())
        <p>Không tìm thấy sản phẩm nào liên quan.</p>
    @else
        <ul>
            @foreach($products as $product)
                <li>
                    <strong>{{ $product->name }}</strong><br>
                    {{ $product->description }}<br>
                    Giá: {{ number_format($product->price) }} VND
                </li>
            @endforeach
        </ul>
    @endif
@endsection
