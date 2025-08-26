<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
public function show($id)
{
    $product = Product::with([
        'variants.options.attribute',
        'variants.options.option',
        'images',
        'brand',
        'category',
        'reviews.user' // load luôn reviews kèm user
    ])->findOrFail($id);

    // Tính trung bình rating (lấy 1 số lẻ)
    $averageRating = round($product->reviews()->avg('rating') ?? 0, 1);

    // Tìm ID của thuộc tính RAM
    $ramAttr = \App\Models\variant_attributes::where('name', 'RAM')->first();
    $ramAttrId = $ramAttr ? $ramAttr->id : null;

    // Dữ liệu phục vụ cho JavaScript
    $variantsForJs = $product->variants->map(function ($variant) {
        return [
            'id' => $variant->id,
            'price' => $variant->price,
            'stock_quantity' => $variant->stock_quantity,
            'options' => $variant->options->mapWithKeys(function ($opt) {
                return [$opt->attribute_id => $opt->option_id];
            }),
        ];
    })->toArray();




    // Cấu trúc dạng readable như yêu cầu
    $variantGroupsReadable = [];

    foreach ($product->variants as $variant) {
        $ramValue = null;
        $otherOptions = [];

        foreach ($variant->options as $option) {
            $attrName = $option->attribute->name;
            $optValue = $option->option->value;

            if (strtolower($attrName) === 'ram') {
                $ramValue = $optValue;
            } else {
                $otherOptions[$attrName][] = $optValue;
            }
        }

        if ($ramValue) {
            $variantGroupsReadable[] = [
                'id' => $variant->id,
                'price' => $variant->price,
                'stock_quantity' => $variant->stock_quantity,
                'options' => [
                    $ramValue => $otherOptions
                ]
            ];
        }
    }


    // Breadcrumbs
    $breadcrumbs = $product->category->getBreadcrumbs();

    // Danh sách thuộc tính kèm giá rẻ nhất và tồn kho
    $attributeOptionsWithPrices = [];
    foreach ($product->variants as $variant) {
        foreach ($variant->options as $option) {
            $attrId = $option->attribute->id;
            $attrName = $option->attribute->name;
            $optId = $option->option->id;
            $optValue = $option->option->value;
            $price = $variant->price;
            $stock = $variant->stock_quantity;

            if (!isset($attributeOptionsWithPrices[$attrId])) {
                $attributeOptionsWithPrices[$attrId] = [
                    'name' => $attrName,
                    'options' => []
                ];
            }

            if (!isset($attributeOptionsWithPrices[$attrId]['options'][$optId])) {
                $attributeOptionsWithPrices[$attrId]['options'][$optId] = [
                    'value' => $optValue,
                    'price' => $price,
                    'stock' => $stock
                ];
            } else {
                $existing = $attributeOptionsWithPrices[$attrId]['options'][$optId];
                if ($price < $existing['price']) {
                    $attributeOptionsWithPrices[$attrId]['options'][$optId]['price'] = $price;
                    $attributeOptionsWithPrices[$attrId]['options'][$optId]['stock'] = $stock;
                }
            }
        }
    }

    // Sắp xếp variant theo giá
    $product->variants = $product->variants->sortBy('price');

    // Sản phẩm liên quan
    $relatedProducts = Product::with(['images', 'variants'])
        ->where('id', '!=', $product->id)
        ->where('status', true)
        ->where(function ($query) use ($product) {
            $query->where('category_id', $product->category_id)
                  ->orWhere('brand_id', $product->brand_id);
        })
        ->limit(4)
        ->get();

    // Debug: Kiểm tra dữ liệu attributeOptionsWithPrices
    if ($product->id == 136) {
        Log::info('Product 136 Debug:', [
            'variants_count' => $product->variants->count(),
            'attributeOptionsWithPrices' => $attributeOptionsWithPrices,
            'variantsForJs' => $variantsForJs
        ]);

        // Debug từng variant
        foreach ($product->variants as $variant) {
            Log::info('Variant ' . $variant->id . ' options:', [
                'options' => $variant->options->map(function($opt) {
                    return [
                        'attribute_id' => $opt->attribute_id,
                        'attribute_name' => $opt->attribute->name,
                        'option_id' => $opt->option_id,
                        'option_value' => $opt->option->value
                    ];
                })->toArray()
            ]);
        }
    }

 return view('client.products.show', compact(
        'product',
        'relatedProducts',
        'breadcrumbs',
        'variantsForJs',
        'ramAttrId',
        'attributeOptionsWithPrices',
        'variantGroupsReadable',
        'averageRating' 
    ));
}


    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', true);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Sort products
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        switch ($sortBy) {
            case 'price_asc':
                $query->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->select('products.*', DB::raw('MIN(product_variants.price) as min_price'))
                    ->groupBy('products.id')
                    ->orderBy('min_price', 'asc');
                break;
            case 'price_desc':
                $query->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->select('products.*', DB::raw('MIN(product_variants.price) as min_price'))
                    ->groupBy('products.id')
                    ->orderBy('min_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', $sortOrder);
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(12);

        return view('products.index', compact('products'));
    }

    /**
     * Search products via AJAX
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::with(['images', 'variants'])
            ->where('status', true)
            ->where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                $minPrice = $product->variants->min('price');
                $maxPrice = $product->variants->max('price');

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'price' => $minPrice == $maxPrice ?
                        number_format($minPrice) . '₫' :
                        number_format($minPrice) . '₫ - ' . number_format($maxPrice) . '₫',
                    'url' => route('products.show', $product->slug)
                ];
            });

        return response()->json($products);
    }

    /**
     * Get product variants via AJAX
     */
    public function getVariants($productId)
    {
        $product = Product::with(['variants.options.attribute', 'variants.options.option'])
            ->findOrFail($productId);

        $variants = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'price' => $variant->price,
                'formatted_price' => number_format($variant->price),
                'stock_quantity' => $variant->stock_quantity,
                'sku' => $variant->sku,
                'attributes' => $variant->options->map(function ($option) {
                    return [
                        'attribute_name' => $option->attribute->name,
                        'option_name' => $option->option->name
                    ];
                })
            ];
        });

        return response()->json($variants);
    }
    public function laptopsinhvien(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'laptop-cho-sinh-vin');
            });
        return $this->filterAndPaginate($request, $query, 'client.products.laptopsinhvien');
    }

    public function laptopgaming(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'laptop-gaming');
            });
        return $this->filterAndPaginate($request, $query, 'client.products.laptopgaming');
    }
    public function laptopmongnhe(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'laptop-mng-nh');
            });
        return $this->filterAndPaginate($request, $query, 'client.products.laptopmongnhe');
    }
     public function laptopvanphong(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'Laptop văn phòng');
            });
        return $this->filterAndPaginate($request, $query, 'client.products.laptopvanphong');
    }
     public function laptopAI(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'Laptop AI');
            });
        return $this->filterAndPaginate($request, $query, 'client.products.LaptopAI');
    }
     public function laptopdohoa(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'Laptop đồ họa');
            });
        return $this->filterAndPaginate($request, $query, 'client.products.laptopdohoa');
    }

    protected function filterAndPaginate(Request $request, $query, $view)
    {
        // luôn chỉ rõ bảng products.status
        $query->where('products.status', true);

        // Filter brand
        if ($request->has('brand') && $request->brand) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('products.name', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
$sortOrder = $request->get('order', 'desc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('products.name', $sortOrder);
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', $sortOrder);
                break;
            default:
                $query->orderBy('products.created_at', $sortOrder);
        }

        $products = $query->paginate(12)->withQueryString();

        return view($view, compact('products'));
    }

}
