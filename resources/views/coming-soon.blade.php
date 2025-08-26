@extends('client.layouts.layout')

@section('content')
<div class="text-center" style="padding: 100px 0;">
    <h1>🚧 Chúng tôi sẽ phát triển thêm 🚧</h1>
    <p>Trang này đang được xây dựng. Vui lòng quay lại sau!</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Quay lại trang</a>
</div>
@endsection
@section('footer')
    @include('client.layouts.partials.footer')
@endsection