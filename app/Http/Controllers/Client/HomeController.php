<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\categorie;
use App\Models\brand;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy sản phẩm nổi bật (có thể dựa trên số lượng bán hoặc đánh giá cao)


        $featuredProducts = Product::where('status', 1)
            ->with(['category', 'brand', 'images', 'variants'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating') // Lấy điểm trung bình đánh giá
            ->addSelect([
                'total_sold' => DB::table('order_items')
                    ->selectRaw('COALESCE(SUM(quantity),0)')
                    ->whereColumn('order_items.product_id', 'products.id')
            ])
            ->orderByDesc('total_sold')       // Ưu tiên sản phẩm bán nhiều nhất
            ->orderByDesc('reviews_avg_rating') // Sau đó ưu tiên sản phẩm được đánh giá cao
            ->limit(10)
            ->get();


        // Lấy sản phẩm mới nhất
        $newProducts = product::where('status', 1)
            ->with(['category', 'brand', 'images', 'variants'])
            ->latest('created_at')
            ->limit(10)
            ->get();

        // Lấy sản phẩm bán chạy (dựa trên tổng số lượng đã bán)
        $bestSellingProducts = product::where('status', 1)
            ->with(['category', 'brand', 'images', 'variants'])
            ->withSum('orderItems', 'quantity')
            ->orderBy('order_items_sum_quantity', 'desc')
            ->limit(10)
            ->get();

        // Lấy sản phẩm có đánh giá cao
        $topRatedProducts = product::where('status', 1)
            ->with(['category', 'brand', 'images', 'variants', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->having('reviews_avg_rating', '>=', 4)
            ->orderBy('reviews_avg_rating', 'desc')
            ->limit(10)
            ->get();

        // Lấy danh mục sản phẩm chính (parent categories)
        $categories = categorie::where('status', 1)
            ->whereNull('parent_id') // Chỉ lấy danh mục cha
            ->withCount('products')
            ->orderBy('order', 'asc')
            ->limit(6)
            ->get();

        // Lấy thương hiệu
        $brands = brand::orderBy('name', 'asc')
            ->limit(10)
            ->get();

        // Lấy sản phẩm theo danh mục cụ thể
        $pcCategory = categorie::where('slug', 'pc')->first();
        $pcProducts = collect();
        if ($pcCategory) {
            $pcProducts = product::where('status', 1)
                ->where('category_id', $pcCategory->id)
                ->with(['category', 'brand', 'images', 'variants'])
                ->latest('id')
                ->limit(5)
                ->get();
        }
        $screenCategory = categorie::where('slug', 'man-hinh')->first();
        $screenProducts = collect();
        if ($screenCategory) {
            $screenProducts = product::where('status', 1)
                ->where('category_id', $screenCategory->id)
                ->with(['category', 'brand', 'images', 'variants'])
                ->latest('id')
                ->limit(5)
                ->get();
        }

        $laptopCategory = categorie::where('slug', 'laptop')->first();
        $laptopProducts = collect();
        if ($laptopCategory) {
            $laptopProducts = product::where('status', 1)
                ->where('category_id', $laptopCategory->id)
                ->with(['category', 'brand', 'images', 'variants'])
                ->latest('id')
                ->limit(4)
                ->get();
        }

        // Lấy sản phẩm mới phát hành (dựa trên release_date)
        $recentReleases = product::where('status', 1)
            ->whereNotNull('release_date')
            ->where('release_date', '<=', now())
            ->with(['category', 'brand', 'images', 'variants'])
            ->orderBy('release_date', 'desc')
            ->limit(6)
            ->get();
        $bestDealVariant = \App\Models\product_variants::whereNotNull('sale_price')
            ->whereColumn('price', '>', 'sale_price')
            ->whereHas('product', function ($query) {
                $query->where('status', 1);
            })
            ->with('product') // lấy luôn quan hệ product
            ->select('*')
            ->selectRaw('(price - sale_price) as discount_amount')
            ->orderByDesc('discount_amount')
            ->first();


        // Thống kê tổng quan
        $totalProducts = product::where('status', 1)->whereNull('deleted_at')->count();
        $totalCategories = categorie::where('status', 1)->whereNull('deleted_at')->count();
        $totalBrands = brand::whereNull('deleted_at')->count();

        return view('client.index', compact(
            'featuredProducts',
            'newProducts',
            'bestSellingProducts',
            'pcProducts',
            'topRatedProducts',
            'categories',
            'brands',
            'screenProducts',
            'laptopProducts',
            'recentReleases',
            'totalProducts',
            'totalCategories',
            'totalBrands',
            'bestDealVariant'
        ));
    }

    // Phương thức tìm kiếm nhanh cho homepage
    public function quickSearch(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập từ khóa tìm kiếm'
            ]);
        }

        $products = product::where('status', 1)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->with(['category', 'brand', 'images'])
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products,
            'total' => $products->count()
        ]);
    }

    // Phương thức lấy sản phẩm theo Ajax (cho infinite scroll hoặc load more)
    public function loadMoreProducts(Request $request)
    {
        $type = $request->get('type', 'featured');
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 8);
        $offset = ($page - 1) * $limit;

        $query = product::where('status', 1)->with(['category', 'brand', 'images', 'variants']);

        switch ($type) {
            case 'featured':
                $query->withCount('reviews')->latest('id');
                break;
            case 'new':
                $query->latest('created_at');
                break;
            case 'bestselling':
                $query->withSum('orderItems', 'quantity')
                    ->orderBy('order_items_sum_quantity', 'desc');
                break;
            case 'toprated':
                $query->with('reviews')
                    ->withAvg('reviews', 'rating')
                    ->having('reviews_avg_rating', '>=', 4)
                    ->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'recent':
                $query->whereNotNull('release_date')
                    ->where('release_date', '<=', now())
                    ->orderBy('release_date', 'desc');
                break;
            default:
                $query->latest('id');
        }

        $products = $query->offset($offset)->limit($limit)->get();
        $hasMore = $query->offset($offset + $limit)->limit(1)->exists();

        // Thêm thông tin tính toán cho mỗi sản phẩm
        $products->each(function ($product) {
            $product->average_rating = $product->averageRating();
            $product->total_sold = $product->totalSold();
            $product->reviews_count = $product->reviews()->count();
        });

        return response()->json([
            'success' => true,
            'products' => $products,
            'hasMore' => $hasMore,
            'currentPage' => $page
        ]);
    }

    // Phương thức lấy sản phẩm theo danh mục (Ajax)
    public function getProductsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        $limit = $request->get('limit', 8);

        $products = product::where('status', 1)
            ->where('category_id', $categoryId)
            ->with(['category', 'brand', 'images', 'variants'])
            ->latest('id')
            ->limit($limit)
            ->get();

        // Thêm thông tin tính toán
        $products->each(function ($product) {
            $product->average_rating = $product->averageRating();
            $product->total_sold = $product->totalSold();
        });

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    // Phương thức lấy sản phẩm theo thương hiệu (Ajax)
    public function getProductsByBrand(Request $request)
    {
        $brandId = $request->get('brand_id');
        $limit = $request->get('limit', 8);

        $products = product::where('status', 1)
            ->where('brand_id', $brandId)
            ->with(['category', 'brand', 'images', 'variants'])
            ->latest('id')
            ->limit($limit)
            ->get();

        // Thêm thông tin tính toán
        $products->each(function ($product) {
            $product->average_rating = $product->averageRating();
            $product->total_sold = $product->totalSold();
        });

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    // Phương thức lấy thống kê nhanh
    public function getStats()
    {
        $stats = [
            'total_products' => product::where('status', 1)->count(),
            'total_categories' => categorie::where('status', 1)->count(),
            'total_brands' => brand::count(),
            'total_reviews' => \App\Models\review::count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
