@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Sản phẩm mới nhất -->
    <section class="mb-5">
        <h2 class="mb-4">Sản phẩm mới nhất</h2>
        <div class="row">
            @foreach($newestProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    <!-- Sản phẩm khuyến mãi -->
    <section class="mb-5">
        <h2 class="mb-4">Sản phẩm khuyến mãi</h2>
        <div class="row">
            @foreach($saleProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    <!-- Sản phẩm bán chạy -->
    <section class="mb-5">
        <h2 class="mb-4">Sản phẩm bán chạy</h2>
        <div class="row">
            @foreach($bestSellingProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
</div>
@endsection
 