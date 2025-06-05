{{-- @extends('admin.index')
@section('container-fluid')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Sub Categories</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                        <li class="breadcrumb-item active">Sub Categories</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
        <div class="col-xxl-12">
            <div class="row justify-content-between mb-4">
                <div class="col-xxl-3 col-lg-6">
                    <div class="search-box mb-3 mb-lg-0">
                        <form method="GET" action="{{ route('sub-categories') }}" class="mb-3 d-flex align-items-center gap-2" style="margin-top:10px">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search category & sub category..."
                                class="form-control" style="width: 300px;">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('sub-categories') }}" class="btn btn-info">Reset</a>
                        </form>
                    </div>
                </div><!--end col-->

            </div><!--end row-->

            <div class="card">
                <div class="card-body">
                    <div id="product-sub-categories" class="table-card"></div>

                    <div class="table-responsive table-card">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Sub Category</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">IMAGE</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $categorie)
                                <tr>
                                    <th scope="row">{{$categorie->id}}</th></th>
                                    <th scope="row">{{$categorie->name}}</th></th>
                                    <td>
                                        @if($categorie->parent)
                                            {{ $categorie->parent->name }}
                                        @else
                                            None
                                        @endif
                                    </td>
                                    <td>
                                        @if($categorie->image)
                                            <img src="{{ asset('storage/' . $categorie->image) }}" width="50" height="50" alt="{{ $categorie->name }}">
                                        @else
                                            <img src="{{ asset('default-image.jpg') }}" width="50" height="50" alt="Default Image">
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="hstack gap-2 list-unstyled mb-0">
                                            <li>
                                                <a href="#!" class="badge bg-success-subtle text-success ">Edit</a>
                                            </li>
                                            <li>
                                                <a href="#removeItemModal" data-bs-toggle="modal" class="badge bg-danger-subtle text-danger ">Delete</a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->

</div>


@endsection --}}
