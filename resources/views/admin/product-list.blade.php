@extends('admin.index')
@section('container-fluid')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Product List</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                        <li class="breadcrumb-item active">Product List</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

        <!-- end col -->
        <div class=" mb-12">
            <div class="row g-4 mb-4">
                <div class="col-sm-auto">
                    {{-- <div>
                        <a href="{{route('product-create')}}" class="btn btn-success" id="addproduct-btn"><i class="ri-add-line align-bottom me-1"></i> Add Product</a>
                    </div> --}}
                </div>
                <div class="col-sm">
                    <div class="d-flex justify-content-sm-end">
                        <div class="search-box ms-2">
                            <form method="GET" action="{{ route('product-list') }}" class="mb-3 d-flex align-items-center gap-2" style="margin-top:10px">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Product..."
                                    class="form-control" style="width: 300px;">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('product-list') }}" class="btn btn-info">Reset</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div  class="gridjs-border-none mb-4">
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product name</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Price</th>
                        <th scope="col">Sold </th>
                        <th scope="col">Publish</th>
                        <th scope="col">Image</th>
                        <th scope="col">Category</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <th scope="row"></th>
                                <td>{{$product->name}}</td>
                                <td>{{$product->stock}}</td>
                                <th scope="row">

                                       @php
                                            $avgRating = round($product->averageRating(), 1); // Lấy rating trung bình, làm tròn 1 chữ số
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $avgRating ? '-fill text-warning' : '' }}"></i>
                                        @endfor
                                        ({{ $avgRating }}/5)

                                </th>
                                <td>{{$product->price}}</td>
                                <td scope="row">{{ $product->total_sold ?? 0 }} </td>

                                <td scope="row">{{$product->created_at}}</td>


                                <td>
                                    @if($product->image)
    <img src="{{ asset('storage/' . $product->image) }}" width="50" height="50" alt="{{ $product->image }}">
@else
    <img src="{{ asset('images/default-image.jpg') }}" width="50" height="50" alt="Default Image">
@endif

                                </td>
                                <td scope="row">{{$product->category->name}}</td>
                                <td scope="row">{{$product->status}}</td>
                                <td>
                                    <ul class="flex-shrink-0 list-unstyled hstack gap-1 mb-0">
                                        <li><a href="#" class="badge bg-info-subtle text-info">Edit</a></li>
                                        <li><a href="#" data-bs-toggle="modal" class="badge bg-danger-subtle text-danger">Delete</a></li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                  </table>
                  {{$products->links()}}
            </div>
        </div>

        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- container-fluid -->
@endsection
<script>
    $(document).ready(function() {
        function filterProducts() {
            let category = $("input[name='category']:checked").val();
            let minPrice = $("#minCost").val();
            let maxPrice = $("#maxCost").val();
            let color = $("input[name='color']:checked").val();
            let size = $("input[name='size']:checked").val();
            let discount = $("input[name='discount']:checked").val();
            let rating = $("input[name='rating']:checked").val();

            $.ajax({
                url: "{{ route('product-list') }}",
                method: "GET",
                data: {
                    category: category,
                    min_price: minPrice,
                    max_price: maxPrice,
                    color: color,
                    size: size,
                    discount: discount,
                    rating: rating
                },
                success: function(response) {
                    $("#product-list").html(response);
                }
            });
        }

        // Bắt sự kiện thay đổi giá trị lọc
        $("input, select").on("change", function() {
            filterProducts();
        });

        // Nút "Clear All"
        $("#clearall").click(function() {
            $("input[type='checkbox']").prop("checked", false);
            $("#minCost").val(0);
            $("#maxCost").val(1000);
            filterProducts();
        });
    });
    </script>
