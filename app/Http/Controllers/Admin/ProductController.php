<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\brand;
use App\Models\categorie;
use App\Models\product;
use Carbon\Carbon;
use App\Models\product_image;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index(Request $request)
    {
        // Bắt đầu query sản phẩm
        $query = Product::query();

        // Tìm kiếm theo tên sản phẩm
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lấy danh sách sản phẩm kèm:
        // - Tổng số lượng đã bán (chỉ đơn hàng đã hoàn thành)
        // - Review
        // - Danh mục
        $products = $query
            ->with(['reviews', 'category']) // nếu cần hiển thị danh mục
            ->withSum(['orderItems as total_sold' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                });
            }], 'quantity')
            ->paginate(8);

        // Lấy toàn bộ danh mục (dùng cho bộ lọc / hiển thị)
        $categories = categorie::all();
        // Lấy toàn bộ brands (dùng cho bộ lọc / hiển thị)
        $brands = brand::all();

        // Trả về view với dữ liệu
        return view('admin/product-list', compact('products', 'categories'));
    }

    //create
    public function create(){

        $brands = Brand::all();  // Get all brands
        $categories = categorie::all();  // Get all categories
        return view('admin/product-create',compact('brands','categories'));



    }


public function store(Request $request)
{

    // Validate form data
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|unique:products,slug',
        'description' => 'required|string',
        'brand_id' => 'required|integer|exists:brands,id',
        'category_id' => 'required|integer|exists:categories,id',
        'stock' => 'required|integer',
        'price' => 'required|numeric',
        'discount' => 'required|nullable|numeric',
        'battery' => 'required|nullable|string',
        'dimensions' => 'required|nullable|string',
        'color' => 'required|nullable|string',
        'weight' => 'required|nullable|string',
        'warranty' => 'required|nullable|string',
        'release_date' => 'nullable|string',
        'status' => 'required|boolean',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);
    // Xử lý ngày phát hành nếu có
    $releaseDate = null;
    if (!empty($request->release_date)) {
        try {
            $releaseDate = Carbon::createFromFormat('d/m/Y', $request->release_date)->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withErrors(['release_date' => 'Định dạng ngày không hợp lệ (dd/mm/yyyy)']);
        }
    }

    // Xử lý ảnh chính
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    // Tạo sản phẩm
    $product = product::create([
        'name' => $request->name,
        'slug' => $request->slug,
        'description' => $request->description,
        'image' => $imagePath ? 'storage/' . $imagePath : null,
        'category_id' => $request->category_id,
        'brand_id' => $request->brand_id,
        'price' => $request->price,
        'discount' => $request->discount,
        'stock' => $request->stock,
        'views' => $request->views ?? 0,
        'sold' => $request->sold ?? 0,
        'warranty' => $request->warranty ?? null,
        'battery' => $request->battery,
        'dimensions' => $request->dimensions,
        'color' => $request->color,
        'weight' => $request->weight,
        'release_date' => $releaseDate,
        'status' => $request->status,
    ]);

   // Ảnh phụ
   if ($request->hasFile('images')) {
        foreach ($request->file('images') as $img) {
            $path = $img->store('product_images', 'public');
            product_image::create([
                'product_id' => $product->id,
                'image_path' => 'storage/' . $path,
            ]);
        }
    }

    return redirect()->route('product.create')->with('message', 'Tạo sản phẩm thành công.');
}
}
