@extends('admin.index')
@section('container-fluid')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Categories</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                            <li class="breadcrumb-item active">Categories</li>
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
                        <h6 class="card-title mb-0">Create Categories</h6>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">

                            @csrf

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter category name" value="{{ old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="Enter slug" value="{{ old('slug') }}">
                                    @error('slug')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="image" class="form-label">Category Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <select class="form-select" id="parent_id" name="parent_id">
                                        <option value="">None</option>
                                        {!! (new App\Http\Controllers\Admin\CategorieController)->renderCategoryOptions($categories) !!}
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter category description" value="{{ old('description') }}"></textarea>
                                    @error('description')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <div class="position-relative">
                                        <span id="status-text" class="form-control bg-light pe-5">Hiển thị</span>
                                        <select class="form-select position-absolute top-0 end-0 h-100 w-auto opacity-0" id="status" name="status"
                                            onchange="document.getElementById('status-text').innerText = this.options[this.selectedIndex].text">
                                            <option value="1" selected>Hiển thị</option>
                                            <option value="0">Ẩn</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-12 mb-3">
                                    <label for="order" class="form-label">Order</label>
                                    <input type="number" class="form-control" id="order" name="order" value="0">
                                </div>

                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-success px-4">Add Category</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- end card -->
            </div>
            <div class="col-xxl-9">
                <div class="row justify-content-between mb-4">
                    <div class="col-xxl-3 col-lg-6">
                        <div class="search-box mb-3">
                            <form method="GET" action="{{ route('categories') }}" class="mb-3 d-flex align-items-center gap-2" style="margin-top:10px">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Category..."
                                    class="form-control" style="width: 300px;">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('categories') }}" class="btn btn-info">Reset</a>
                            </form>
                        </div>

                    </div><!--end col-->

                </div><!--end row-->

                <div class="row" id="categories-list">
                    @foreach ($categories as $category)
                        <div class="col-xxl-4 col-md-6">
                            <div class="card categrory-widgets overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="flex-grow-1 mb-0">{{$category->name}}</h5>

                                    </div>

                                    <div class="mt-3">
                                        <a href="#overviewOffcanvas"
                                        data-bs-toggle="offcanvas"
                                        data-title="{{ $category->name }}"
                                        data-slug="{{ $category->slug }}"
                                        data-description="{{ $category->description }}"
                                        data-image="{{ $category->image ? asset('storage/' . $category->image) : asset('default-image.jpg') }}"
                                        data-parent_id="{{ $category->parent_id }}"
                                        data-status="{{ $category->status }}"
                                        data-order="{{ $category->order }}"
                                        class="fw-medium link-effect category-link">
                                        Read More <i class="ri-arrow-right-line align-bottom ms-1"></i>
                                     </a>


                                    </div>
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid category-img object-fit-cover">
                                    @else
                                        <img src="{{ asset('default-image.jpg') }}" alt="Default Image" class="img-fluid category-img object-fit-cover">
                                    @endif
                                    <div class="p-3 border-top">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div data-bs-dismiss="offcanvas">

                                                        <form action="{{route('categories.destroy',parameters: $category->id)}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"  class="btn btn-danger w-100 remove-list"  onclick="return confirm('ban co chac chan muon xoa moive khong?')" >Delete</button>
                                                        </form>

                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-secondary w-100 edit-list" data-bs-dismiss="offcanvas">
                                                    <i class="ri-pencil-line me-1 align-bottom"></i> Edit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                   {{$categories->links()}}
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
            </div><!--end col-->
        </div><!--end row-->

    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="overviewOffcanvas" aria-labelledby="overviewOffcanvasLabel">
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
                        <img src="" alt="Category Image" class="avatar-sm overview-image">
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <h5 class="overview-title">Clothing</h5>
                <p class="text-muted">Slug: <span class="overview-slug"></span></p>
                <p class="text-muted">Mô tả: <span class="overview-description"></span></p>
                <p class="text-muted">Danh mục cha: <span class="overview-parent-id"></span></p>
                <p class="text-muted">Trạng thái: <span class="overview-status"></span></p>
                <p class="text-muted">Thứ tự hiển thị: <span class="overview-order"></span></p>
            </div>
        </div>

    </div>

    <script>
           document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll('.category-link').forEach(item => {
                    item.addEventListener("click", function () {
                        // Lấy dữ liệu từ data attributes
                        let title = this.getAttribute("data-title");
                        let slug = this.getAttribute("data-slug");
                        let description = this.getAttribute("data-description");
                        let image = this.getAttribute("data-image");
                        let parentId = this.getAttribute("data-parent_id");
                        let status = this.getAttribute("data-status");
                        let order = this.getAttribute("data-order");

                        // Kiểm tra và cập nhật hình ảnh
                        let imageElement = document.querySelector(".overview-image");
                        if (image) {
                            imageElement.src = image.startsWith("http") ? image : `/storage/${image}`;
                        } else {
                            imageElement.src = "{{ asset('default-image.jpg') }}"; // Ảnh mặc định nếu không có
                        }

                        // Cập nhật nội dung của Offcanvas
                        document.querySelector(".overview-title").innerText = title;
                        document.querySelector(".overview-slug").innerText = slug;
                        document.querySelector(".overview-description").innerText = description;
                        document.querySelector(".overview-parent-id").innerText = parentId;
                        document.querySelector(".overview-status").innerText = status == "1" ? "Hiển thị" : "Ẩn";
                        document.querySelector(".overview-order").innerText = order;

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

    <!-- container-fluid -->
@endsection
