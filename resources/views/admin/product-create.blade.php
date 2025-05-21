@extends('admin.index')
@section('container-fluid')

<div class="container-fluid ">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create product</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Product</a></li>
                        <li class="breadcrumb-item active">Create product</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <form id="product-form" method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data" >
        @csrf
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Product Information</h5>
                                <p class="text-muted mb-0">Fill all information below.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product-title-input">Product title</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter product title" >
                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" >Product slug</label>
                            <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" placeholder="Enter product slug" >
                            @error('slug')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product description</label>
                            <textarea class="form-control form-control-lg" id="description" name="description" rows="5" placeholder="Enter detailed product description..." required ></textarea>
                            @error('description')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div>
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <label class="form-label">Product brand</label>
                                </div>
                            </div>
                            <div>
                                <select class="form-select" name="brand_id">
                                    <option value="{{ $brands->first()->id }}" selected>{{ $brands->first()->name }}</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <label class="form-label">Product category</label>
                                </div>
                            </div>
                            <div>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="{{ $categories->first()->id }}" selected>{{ $categories->first()->name }}</option>
                                    {!! (new App\Http\Controllers\Admin\CategorieController)->renderCategoryOptions($categories) !!}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                        <i class="bi bi-images"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Product Gallery</h5>
                                <p class="text-muted mb-0">Add product gallery images.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Representative Image</label>
                            <input type="file" class="form-control" name="image" value="{{ old('image') }}">
                            @error('image')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <label class="form-label">Product Images</label>
                        <div class="dropzone my-dropzone" id="productImagesDropzone">
                            <div class="dz-message text-center">
                                <div class="mb-3">
                                    <i class="bi bi-cloud-arrow-up display-4" id="upload-icon" style="cursor: pointer;"></i>
                                    <input type="file" class="form-control" name="images[]" id="product-images-input" multiple style="display: none;" />
                                    <div id="image-preview-container" class="mt-3 d-flex flex-wrap gap-3"></div>
                                </div>
                                @error('images.*')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                                <h5>Drop files here or click to upload.</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card -->


                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                        <i class="bi bi-list-ul"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">General Information</h5>
                                <p class="text-muted mb-0">Fill all information below.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">


                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="stocks-input">Stock</label>
                                    <input type="text" class="form-control" name="stock" placeholder="Stock" value="{{ old('stock') }}" >
                                    @error('stock')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="product-price-input">Price</label>
                                    <div class="input-group has-validation mb-3">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control" name="price" placeholder="Enter price" value="{{ old('price') }}" >
                                    </div>
                                    @error('price')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="product-price-input">Discount</label>
                                    <div class="input-group has-validation mb-3">
                                        <span class="input-group-text">%</span>
                                        <input type="text" class="form-control" name="discount" placeholder="Enter price" value="{{ old('discount') }}" >
                                    </div>
                                    @error('discount')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-3 col-sm-6">

                                <div class="mb-3">
                                    <label class="form-label">Battery</label>
                                    <div class="input-group has-validation mb-3">
                                        <span class="input-group-text" >mAh</span>
                                        <input type="text" class="form-control" name="battery"  placeholder="Enter battery" value="{{ old('battery') }}"  >
                                    </div>
                                    @error('battery')
                                            <span class="text-danger">{{$message}}</span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Warranty</label>
                                    <div class="input-group has-validation mb-3">
                                        <span class="input-group-text" >th</span>
                                        <input type="text" class="form-control" name="warranty"  placeholder="Enter warranty" value="{{ old('warranty') }}" >

                                    </div>
                                    @error('warranty')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>



                            <div class="col-lg-3 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Size</label>
                                    <div class="input-group has-validation mb-3">
                                        <span class="input-group-text" >inch</span>
                                        <input type="text" class="form-control" name="dimensions"  placeholder="Enter dimensions" value="{{ old('dimensions') }}" >
                                        @error('dimensions')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="mb-3">
                                    <div>
                                        <label class="form-label">Colors</label>
                                        <input type="text" class="form-control" name="color" placeholder="color" value="{{ old('color') }}"   >
                                        @error('color')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="mb-3">

                                    <label class="form-label">Weight</label>
                                    <div class="input-group has-validation mb-3">
                                        <span class="input-group-text" >kg</span>
                                        <input type="text" class="form-control" name="weight" value="{{ old('weight') }}"  placeholder="Enter Weight" >

                                    </div>
                                        @error('weight')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->


                    </div>
                </div>
                <div >
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Publish</h5>
                        </div>
                        <div class="card-body">

                            <div class="mb-3 position-relative" style="max-width: 300px;">
                                <label for="status" class="form-label">Product Status</label>

                                <span id="status-text" class="form-control bg-light pe-5">Hiển thị</span>

                                <select class="form-select position-absolute top-1 start-1 w-100 h-100"
                                        style="opacity: 0; cursor: pointer;"
                                        id="status" name="status"
                                        onchange="document.getElementById('status-text').innerText = this.options[this.selectedIndex].text">
                                    <option value="1" selected>Hiển thị</option>
                                    <option value="0">Ẩn</option>
                                </select>
                            </div>

                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Publish Schedule</h5>
                        </div>
                        <!-- end card body -->
                        <div class="card-body">
                            <div>
                                <label for="datepicker-publish-input" class="form-label">Publish Date & Time</label>
                                <input type="text" name="release_date" class="form-control" placeholder="Enter publish date" data-provider="flatpickr" data-date-format="Y-m-d">
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end card -->
                <div class="text-end mb-3">
                    <button type="submit" class="btn btn-success w-sm">Submit</button>
                </div>
            </div>

            <!-- end col -->


            <!-- end col -->
        </div>
        <!-- end row -->
    </form>
</div>
<style>
    .form-control::placeholder {
        color: rgba(0, 0, 0, 0.5); /* Màu chữ mờ hơn */
        font-style: italic; /* Làm chữ nghiêng */
    }
    #productImagesDropzone {
    cursor: pointer; /* Thay đổi con trỏ chuột thành bàn tay khi hover vào khu vực */
    padding: 20px;
    text-align: center;
    }
    .form-select:hover {
        cursor: pointer; /* Thay đổi con trỏ chuột thành bàn tay */
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>



<script>
    // Lắng nghe sự kiện khi người dùng nhấp vào biểu tượng
    document.getElementById('upload-icon').addEventListener('click', function () {
        // Kích hoạt input file ẩn
        document.getElementById('product-images-input').click();
    });

    // Khi người dùng chọn file, xử lý các file được chọn
    document.getElementById('product-images-input').addEventListener('change', function (event) {
        // Lấy các file đã chọn
        const files = event.target.files;

        // Hiển thị preview cho ảnh đã chọn (nếu có)
        const previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = ''; // Xóa các preview cũ

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function (e) {
                // Tạo div chứa ảnh và nút xóa
                const previewItem = document.createElement('div');
                previewItem.classList.add('preview-item');
                previewItem.style.position = 'relative'; // Để nút xóa nằm dưới ảnh

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                img.style.width = '100px';
                img.style.height = '100px';

                // Tạo nút xóa
                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'Xóa';
                removeBtn.classList.add('btn', 'btn-danger', 'btn-sm');
                removeBtn.style.position = 'absolute';
                removeBtn.style.bottom = '5px'; // Nút xóa nằm dưới ảnh
                removeBtn.style.left = '50%';
                removeBtn.style.transform = 'translateX(-50%)';
                removeBtn.style.marginTop = '5px'; // Khoảng cách giữa ảnh và nút

                // Khi nhấp vào nút xóa, xóa ảnh và file
                removeBtn.addEventListener('click', function () {
                    // Xóa ảnh khỏi preview
                    previewContainer.removeChild(previewItem);
                });

                // Thêm ảnh và nút xóa vào div previewItem
                previewItem.appendChild(img);
                previewItem.appendChild(removeBtn);

                // Thêm previewItem vào container preview
                previewContainer.appendChild(previewItem);
            };

            reader.readAsDataURL(file);
        }
    });
</script>









@endsection
