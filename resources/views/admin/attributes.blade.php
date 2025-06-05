



@extends('admin.index')
@section('container-fluid')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Attributes</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                            <li class="breadcrumb-item active">Attributes</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xxl-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Create Attributes</h6>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('attributes.store') }}" enctype="multipart/form-data">

                            @csrf

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Attributes Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nhập thuộc tính " required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                    <div class="col-md-12 mb-3">
                                    <label for="parent_id" class="form-label">Parent Attributes</label>
                                    <select class="form-select" id="parent_id" name="parent_id">
                                        <option value="">None</option>
                                      {!! (new \App\Http\Controllers\Admin\AttributesProduct)->renderCategoryOptions($allAttributes) !!}

                                    </select>
                                </div>

                                 <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-success px-4">Add Attributes</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- end card -->
            </div>
            {{-- <div class="col-xxl-9">
                <div class="row justify-content-between mb-4">
                    <div class="col-xxl-3 col-lg-6">
                        <div class="search-box mb-3">
                            <form method="GET" action="{{ route('brands') }}" class="mb-3 d-flex align-items-center gap-2" style="margin-top:10px">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search brand..."
                                    class="form-control" style="width: 300px;">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('brands') }}" class="btn btn-info">Reset</a>
                            </form>
                        </div>

                    </div><!--end col-->

                </div><!--end row-->

                <div class="row" id="categories-list">
                    @foreach ($brands as $brand)
                        <div class="col-xxl-4 col-md-6">
                            <div class="card categrory-widgets overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="flex-grow-1 mb-0">{{$brand->name}}</h5>
                                        <ul class="flex-shrink-0 list-unstyled hstack gap-1 mb-0">
                                            <li><a href="#!" class="badge bg-info-subtle text-info">Edit</a></li>
                                            <li><a href="#delteModal" data-bs-toggle="modal" class="badge bg-danger-subtle text-danger">Delete</a></li>
                                        </ul>
                                    </div>

                                    <div class="mt-3">
                                        <a href="#overviewOffcanvas"
                                        data-bs-toggle="offcanvas"
                                        data-title="{{ $brand->name }}"
                                        data-slug="{{ $brand->slug }}"
                                        data-description="{{ $brand->description }}"
                                        data-logo="{{ $brand->logo ? asset('storage/' . $brand->logo) : asset('default-logo.jpg') }}"

                                        class="fw-medium link-effect category-link">
                                        Read More <i class="ri-arrow-right-line align-bottom ms-1"></i>
                                     </a>


                                    </div>
                                    @if($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid brand-img object-fit-cover">
                                    @else
                                        <img src="{{ asset('default-logo.jpg') }}" alt="Default logo" class="img-fluid brand-img object-fit-cover">
                                    @endif
                                </div>
                            </div>
                        </div>

                    @endforeach
                   {{$brands->links()}}
                </div><!--end row-->

                <div class="row" id="pagination-element">
                    <div class="col-lg-12">
                        <div class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                            <div class="page-item">
                                <a href="javascript:void(0);" class="page-link" id="page-prev">←</a>
                            </div>
                            <span id="page-num" class="pagination"></span>
                            <div class="page-item">
                                <a href="javascript:void(0);" class="page-link" id="page-next">→</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end col--> --}}
        </div><!--end row-->

    </div>
    <style>
        .form-control::placeholder {
            color: rgba(0, 0, 0, 0.5); /* Màu chữ mờ hơn */
            font-style: italic; /* Làm chữ nghiêng */
        }
    </style>

    {{-- <div class="offcanvas offcanvas-end" tabindex="-1" id="overviewOffcanvas" aria-labelledby="overviewOffcanvasLabel">
        <div class="offcanvas-header bg-primary-subtle">
            <h5 class="offcanvas-title" id="overviewOffcanvasLabel">
                #TB<span class="overview-id"></span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="avatar-lg mx-auto">
                <div class="avatar-lg mx-auto">
                    <div class="avatar-title bg-light rounded">
                        <img src="" alt="brand logo" class="avatar-sm overview-logo">
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <h5 class="overview-title">Clothing</h5>
                <p class="text-muted">Slug: <span class="overview-slug"></span></p>
                <p class="text-muted">Mô tả: <span class="overview-description"></span></p>
            </div>
        </div>
        <div class="p-3 border-top">
            <div class="row">
                <div class="col-sm-6">
                    <div data-bs-dismiss="offcanvas">
                        <button type="button" class="btn btn-danger w-100 remove-list" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="ri-delete-bin-line me-1 align-bottom"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <button type="button" class="btn btn-secondary w-100 edit-list" data-bs-dismiss="offcanvas">
                        <i class="ri-pencil-line me-1 align-bottom"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <script>
           document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll('.category-link').forEach(item => {
                    item.addEventListener("click", function () {
                        // Lấy dữ liệu từ data attributes
                        let title = this.getAttribute("data-title");
                        let slug = this.getAttribute("data-slug");
                        let description = this.getAttribute("data-description");
                        let logo = this.getAttribute("data-logo");
                        let logoElement = document.querySelector(".overview-logo");

                        if (logo) {
                            logoElement.src = logo.startsWith("http") ? logo : `/storage/${logo}`;
                        } else {
                            logoElement.src = "{{ asset('default-logo.jpg') }}";
                        }




                        // Cập nhật nội dung của Offcanvas
                        document.querySelector(".overview-title").innerText = title;
                        document.querySelector(".overview-slug").innerText = slug;
                        document.querySelector(".overview-description").innerText = description;


                        // Hiển thị Offcanvas
                        let offcanvas = new bootstrap.Offcanvas(document.getElementById("overviewOffcanvas"));
                        offcanvas.show();
                    });
                });
            });


    </script>
    <style>
        .form-control::placeholder {
            color: rgba(0, 0, 0, 0.5); /* Màu chữ mờ hơn */
            font-style: italic; /* Làm chữ nghiêng */
        }
    </style>

    <!-- container-fluid --> --}}
@endsection
