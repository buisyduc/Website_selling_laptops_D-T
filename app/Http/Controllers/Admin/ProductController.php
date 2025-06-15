<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Services\ProductService;
use App\Models\Brand;
use App\Models\categorie;
use App\Models\product;
use App\Models\product_image;
use App\Models\product_variants;
use App\Models\product_variant_options;
use App\Models\variant_attributes;
use App\Models\variant_options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    protected $productService;

    // Configuration constants
    private const DEFAULT_PER_PAGE = 15;
    private const DEFAULT_SORT_BY = 'created_at';
    private const DEFAULT_SORT_DIRECTION = 'desc';
    private const KNOWN_ATTRIBUTE_NAMES = ['color', 'size', 'material', 'style', 'weight', 'brand'];

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        // Bắt đầu query sản phẩm
        $query = Product::query();

        // Tìm kiếm theo tên sản phẩm
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sắp xếp theo id giảm dần
        $query->orderBy('id', 'desc');

        // Lấy danh sách sản phẩm kèm:
        // - Tổng số lượng đã bán (chỉ đơn hàng đã hoàn thành)
        // - Review
        // - Danh mục
        $products = $query
            ->with(['reviews', 'category'])
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
        return view('admin/Product/product-list', compact('products', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create(): View
    {
        $categories = categorie::all();
        $brands = Brand::all();
        $availableAttributes = variant_attributes::all(); // Hoặc query theo bảng bạn có
        $attributes = $this->productService->getVariantAttributes();

        return view('admin/Product/product-create', compact('categories', 'brands', 'attributes', 'availableAttributes'));
    }

    /**
     * Store a newly created product
     */
    public function store(ProductStoreRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            Log::info('Full request data:', request()->all());
            $validatedData = $request->getValidatedData();

            $this->logProductData('STORING PRODUCT', $validatedData);

            // Create product
            $product = $this->createProductWithData($request, $validatedData);

            // Handle related data
            $this->handleProductImages($product->id, $request);
            $this->handleProductVariants($product->id, $validatedData['variants'] ?? []);

            DB::commit();

            return redirect()
                ->route('product.create')
                ->with('message', 'Sản phẩm đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            $this->logError('ERROR CREATING PRODUCT', $e);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Có lỗi xảy ra khi tạo sản phẩm: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified product
     */
    public function show($id): View|RedirectResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return redirect()
                ->route('product.index')
                ->withErrors(['error' => 'Không tìm thấy sản phẩm.']);
        }

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */


    public function edit($id)
    {
        $product = Product::with([
            'variants.variantOptions.attribute',
            'variants.variantOptions.option',
            'images',
            'brand',
            'category'
        ])->findOrFail($id);

        $variants = $product->variants;
        $brands = Brand::all();
        $categories = categorie::all();
        $availableAttributes = \App\Models\variant_attributes::all(); // Lấy toàn bộ thuộc tính

        return view('admin/Product/editProduct', compact('product', 'variants', 'brands', 'categories', 'availableAttributes'));
    }
    /**
     * Update the specified product
     */

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // Validate request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:products,slug,' . $id,
                'description' => 'required|string',
                'brand_id' => 'required|exists:brands,id',
                'category_id' => 'required|exists:categories,id',
                'status' => 'required|boolean',
                'release_date' => 'nullable|date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'variants' => 'nullable|array',
                'variants.*.id' => 'nullable|exists:product_variants,id',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.stock_quantity' => 'required|integer|min:0',
                'variants.*.sku' => 'nullable|string|max:100',
            ]);
            // Update product basic info
            $product->update([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'description' => $validatedData['description'],
                'brand_id' => $validatedData['brand_id'],
                'category_id' => $validatedData['category_id'],
                'status' => $validatedData['status'],
                'release_date' => $validatedData['release_date'],
            ]);


            // Handle main image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                $imagePath = $request->file('image')->store('products', 'public');
                $product->update(['image' => $imagePath]);
            }

            // Handle gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('products/gallery', 'public');
                    $product->productImages()->create([
                        'image_path' => $imagePath
                    ]);
                }
            }

            if ($request->has('variants') && is_array($request->variants)) {

                // Lấy danh sách ID của variants hiện có từ request
                $existingVariantIds = collect($request->variants)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // Xóa các variants không còn trong request
                $product->variants()
                    ->whereNotIn('id', $existingVariantIds)
                    ->delete();


                foreach ($request->variants as $variantData) {
                    if (isset($variantData['id']) && !empty($variantData['id'])) {
                        // Cập nhật variant cũ
                        $variant = product_variants::find($variantData['id']);
                        if ($variant && $variant->product_id == $product->id) {
                            if ((int)$variant->stock_quantity !== (int)$variantData['stock_quantity']) {
                                throw new \Exception("Không được thay đổi số lượng tồn kho của biến thể có ID {$variant->id}.");
                            }

                            $variant->update([
                                'price' => $variantData['price'],
                                'sale_price' => $variantData['sale_price'] ?? null,
                                'sku' => $variantData['sku'] ?? null,
                            ]);

                            $this->updateVariantOptions($variant, $variantData);
                        }
                    } else {
                        // Tạo mới variant
                        $variant = $product->variants()->create([
                            'price' => $variantData['price'],
                            'sale_price' => $variantData['sale_price'] ?? null,
                            'stock_quantity' => $variantData['stock_quantity'],
                            'sku' => $variantData['sku'] ?? null,
                        ]);

                        $this->createVariantOptions($variant, $variantData);
                    }
                }

                // Kiểm tra nếu cần:

            }

            DB::commit();

            return redirect()->route('product-list', $product->id)
                ->with('message', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Product update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }


    private function createVariantOptions($variant, $variantData)
    {
        $optionsToProcess = [];

        if (isset($variantData['grouped_options']) && is_array($variantData['grouped_options'])) {
            $optionsToProcess = $variantData['grouped_options'];
        } elseif (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
            $optionsToProcess = $variantData['attributes'];
        }

        foreach ($optionsToProcess as $optionData) {
            if (isset($optionData['attribute_id'], $optionData['values'])) {
                $values = is_string($optionData['values']) ? explode(',', $optionData['values']) : $optionData['values'];
                foreach ($values as $value) {
                    $value = trim($value);
                    if (!empty($value)) {
                        $option = variant_options::firstOrCreate([
                            'attribute_id' => $optionData['attribute_id'],
                            'value' => $value,
                        ]);

                        $variant->variantOptions()->create([
                            'attribute_id' => $option['attribute_id'],
                            'option_id' => $option->id,
                        ]);
                    }
                }
            }
        }
    }
    protected function updateVariantOptions($variant, $variantData)
    {
        $optionsToProcess = [];

        if (isset($variantData['grouped_options']) && is_array($variantData['grouped_options'])) {
            $optionsToProcess = $variantData['grouped_options'];
        } elseif (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
            $optionsToProcess = $variantData['attributes'];
        } elseif (isset($variantData['options']) && is_array($variantData['options'])) {
            $optionsToProcess = $variantData['options'];
        } else {
            return;
        }

        // Xóa các option cũ để tránh dư thừa
        $variant->variantOptions()->delete();

        foreach ($optionsToProcess as $optionData) {
            if (isset($optionData['attribute_id'], $optionData['values'])) {
                $values = is_string($optionData['values']) ? explode(',', $optionData['values']) : $optionData['values'];
                foreach ($values as $value) {
                    $value = trim($value);
                    if (!empty($value)) {
                        $option = variant_options::firstOrCreate([
                            'attribute_id' => $optionData['attribute_id'],
                            'value' => $value,
                        ]);

                        $variant->variantOptions()->create([
                            'attribute_id' => $option['attribute_id'],
                            'option_id' => $option->id,
                        ]);
                    }
                }
            }
        }
    }






    /**
     * Toggle product status
     */
    public function toggleStatus($id): JsonResponse
    {
        try {
            $product = product::find($id);
            if (!$product) {
                return response()->json(['error' => 'Không tìm thấy sản phẩm.'], 404);
            }

            $product->status = !$product->status;
            $product->save();

            return response()->json([
                'message' => 'Trạng thái sản phẩm đã được cập nhật.',
                'status' => $product->status
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling product status: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra.'], 500);
        }
    }

    // =====================================
    // PRIVATE HELPER METHODS
    // =====================================

    /**
     * Build filters array from request
     */
    private function buildFilters(Request $request): array
    {
        return [
            'search' => $request->get('search'),
            'category_id' => $request->get('category_id'),
            'brand_id' => $request->get('brand_id'),
            'status' => $request->get('status'),
            'sort_by' => $request->get('sort_by', self::DEFAULT_SORT_BY),
            'sort_direction' => $request->get('sort_direction', self::DEFAULT_SORT_DIRECTION)
        ];
    }

    /**
     * Create product with validated data
     */
    private function createProductWithData(Request $request, array $validatedData): product
    {
        $productData = [
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'description' => $validatedData['description'],
            'brand_id' => $validatedData['brand_id'],
            'category_id' => $validatedData['category_id'],
            'status' => $validatedData['status'],
            'release_date' => $validatedData['release_date'] ?? null,
        ];

        // Handle main image upload
        if ($request->hasFile('image')) {
            $productData['image'] = $this->uploadImage($request->file('image'), 'products');
        }

        $product = $this->productService->createProduct($productData);
        Log::info('Product created with ID: ' . $product->id);

        return $product;
    }

    /**
     * Handle product images (gallery)
     */
    private function handleProductImages(int $productId, Request $request): void
    {
        if ($request->hasFile('images')) {
            $this->handleGalleryImages($productId, $request->file('images'));
        }
    }

    /**
     * Find product or throw exception
     */
    private function findProductOrFail(int $id): product
    {
        $product = product::find($id);
        if (!$product) {
            throw new \Exception('Không tìm thấy sản phẩm.');
        }
        return $product;
    }

    /**
     * Update product data
     */
    private function updateProductData(product $product, Request $request, array $validatedData): void
    {
        $updateData = [
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'description' => $validatedData['description'],
            'brand_id' => $validatedData['brand_id'],
            'category_id' => $validatedData['category_id'],
            'status' => $validatedData['status'],
            'release_date' => $validatedData['release_date'] ?? null,
        ];

        // Handle main image update
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $updateData['image'] = $this->uploadImage($request->file('image'), 'products');
        }

        $this->productService->updateProduct($product->id, $updateData);
    }

    /**
     * Update product images
     */
    private function updateProductImages(product $product, Request $request): void
    {
        if ($request->hasFile('images')) {
            // Delete old gallery images
            $this->deleteGalleryImages($product->id);
            // Upload new gallery images
            $this->handleGalleryImages($product->id, $request->file('images'));
        }
    }

    /**
     * Update product variants
     */
    private function updateProductVariants(int $productId, array $variants): void
    {
        foreach ($variants as $variantData) {
            // Nếu biến thể cũ (đã có ID)
            if (!empty($variantData['id'])) {
                $variant = \App\Models\product_variants::where('product_id', $productId)
                    ->where('id', $variantData['id'])
                    ->first();

                if ($variant) {
                    // Kiểm tra nếu người dùng thay đổi số lượng
                    if ((int)$variant->stock_quantity !== (int)($variantData['stock_quantity'] ?? 0)) {
                        throw new \Exception("Không được thay đổi số lượng tồn kho của biến thể ID {$variant->id}.");
                    }

                    // Cập nhật các trường khác, ngoại trừ stock_quantity
                    $variant->update([
                        'price' => $variantData['price'] ?? 0,
                        'sku'   => $variantData['sku'] ?? '',
                        // Không cập nhật stock_quantity!
                    ]);
                }
            } else {
                // Nếu là biến thể mới → cho phép thêm
                \App\Models\product_variants::create([
                    'product_id'     => $productId,
                    'price'          => $variantData['price'] ?? 0,
                    'sku'            => $variantData['sku'] ?? '',
                    'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                ]);
            }
        }
    }


    /**
     * Delete product and all related data
     */
    private function deleteProductAndRelatedData(product $product): void
    {
        // Delete main image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete gallery images
        $this->deleteGalleryImages($product->id);

        // Delete variants and variant options
        $this->deleteExistingVariants($product->id);

        // Delete product
        $product->delete();
    }

    /**
     * Delete gallery images
     */
    private function deleteGalleryImages(int $productId): void
    {
        $galleryImages = product_image::where('product_id', $productId)->get();
        foreach ($galleryImages as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }

    /**
     * Delete existing variants
     */
    private function deleteExistingVariants(int $productId): void
    {
        $existingVariants = product_variants::where('product_id', $productId)->get();
        foreach ($existingVariants as $variant) {
            product_variant_options::where('variant_id', $variant->id)->delete();
            $variant->delete();
        }
    }

    // =====================================
    // VARIANT HANDLING METHODS
    // =====================================

    /**
     * Handle product variants creation
     */
    private function handleProductVariants(int $productId, array $variants): void
    {
        Log::info("=== HANDLING VARIANTS FOR PRODUCT {$productId} ===");

        foreach ($variants as $index => $variantData) {
            Log::info("Variant #{$index} data:", $variantData);

            if (!$this->isValidVariantData($variantData)) {
                Log::warning("Skipping invalid variant at index {$index}");
                continue;
            }

            try {
                $this->createProductVariant($productId, $variantData, $index);
            } catch (\Exception $e) {
                Log::error("Error creating variant at index {$index}: " . $e->getMessage());
                throw $e;
            }
        }
    }


    /**
     * Validate variant data
     */
    private function isValidVariantData(array $variantData): bool
    {
        return !empty($variantData['price']) && !empty($variantData['stock_quantity']);
    }

    /**
     * Create a single product variant
     */
    private function createProductVariant(int $productId, array $variantData, int $index): void
{
    $commonAttributes = [];
    $colors = [];

    // Tách riêng thuộc tính màu và các thuộc tính còn lại
    foreach ($variantData['attributes'] as $attr) {
        $attributeId = $attr['attribute_id'];
        $options = $attr['options'];

        if (!$attributeId || !is_array($options)) continue;

        $attribute = variant_attributes::find($attributeId);
        if (!$attribute) continue;

        if (stripos($attribute->name, 'màu') !== false) {
            $colors = $options; // Ưu tiên 1 thuộc tính màu sắc
        } else {
            $commonAttributes[] = [
                'attribute' => $attribute,
                'options' => $options,
            ];
        }
    }

    // Nếu không có màu → tạo 1 biến thể duy nhất
    if (empty($colors)) {
        $variant = $this->createSingleVariant($productId, $variantData, $index);
        $this->attachAttributes($variant, $commonAttributes);
        return;
    }

    // Nếu có màu → tách ra mỗi màu là một biến thể riêng
    foreach ($colors as $colorValue) {
        $variant = $this->createSingleVariant($productId, $variantData, $index++);
        $this->attachAttributes($variant, $commonAttributes);
        $this->attachSingleAttribute($variant, 'Màu sắc', $colorValue);
    }
}
private function createSingleVariant(int $productId, array $data, int $index): product_variants
{
    $variant = product_variants::create([
        'product_id' => $productId,
        'sku' => $data['sku'] ?? $this->generateSku($productId, $index),
        'price' => (int) $data['price'],
        'compare_price' => $data['compare_price'] ?? null,
        'cost_price' => $data['cost_price'] ?? null,
        'stock_quantity' => (int) $data['stock_quantity'],
        'weight' => $data['weight'] ?? null,
        'length' => $data['length'] ?? null,
        'width' => $data['width'] ?? null,
        'height' => $data['height'] ?? null,
        'status' => $data['status'] ?? 1,
    ]);

    return $variant;
}

private function attachAttributes(product_variants $variant, array $attributes): void
{
    foreach ($attributes as $item) {
        foreach ($item['options'] as $value) {
            $this->attachSingleAttribute($variant, $item['attribute']->name, $value);
        }
    }
}

private function attachSingleAttribute(product_variants $variant, string $attributeName, string $value): void
{
    $attribute = variant_attributes::firstOrCreate(['name' => $attributeName]);
    $option = variant_options::firstOrCreate([
        'attribute_id' => $attribute->id,
        'value' => trim($value),
    ]);

    product_variant_options::firstOrCreate([
        'variant_id' => $variant->id,
        'attribute_id' => $attribute->id,
        'option_id' => $option->id,
    ]);
}

    /**
     * Extract attributes from flat variant structure
     */
    private function extractFlatAttributes(array $variantData): array
    {
        $attributes = [];

        foreach (self::KNOWN_ATTRIBUTE_NAMES as $attrName) {
            if (isset($variantData[$attrName]) && !empty($variantData[$attrName])) {
                $attributes[] = [
                    'name' => $attrName,
                    'value' => $variantData[$attrName]
                ];
            }
        }

        if (!empty($attributes)) {
            Log::info("Extracted flat attributes:", $attributes);
        }

        return $attributes;
    }

    /**
     * Extract attributes data from variant with multiple strategies
     */
    private function extractAttributesFromVariant(array $variantData): array
    {
        // Strategy 1: Check for explicit attributes structure
        $explicitKeys = ['attributes', 'variant_attributes', 'options', 'properties'];
        foreach ($explicitKeys as $key) {
            if (isset($variantData[$key]) && is_array($variantData[$key])) {
                Log::info("Found attributes in key: {$key}");
                return $variantData[$key];
            }
        }

        // Strategy 2: Extract from flat structure
        return $this->extractFlatAttributes($variantData);
    }
    /**
     * Process a single variant attribute
     */
    private function processVariantAttribute(int $variantId, array $attributeData): void
    {
        Log::info(' Input attribute data:', $attributeData);
        try {
            $attributeName = $this->extractAttributeName($attributeData);
            $optionValue = $this->extractOptionValue($attributeData);

            if (empty($attributeName) || empty($optionValue)) {
                Log::warning('Skipping attribute due to missing data', $attributeData);
                return;
            }

            $attribute = $this->findOrCreateAttribute($attributeName);
            $option = $this->findOrCreateOption($attribute->id, $optionValue);
            $this->createVariantOptionLink($variantId, $option->id);
        } catch (\Exception $e) {
            Log::error("Error processing attribute for variant {$variantId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle variant attributes with improved structure
     */
    private function handleVariantAttributes(int $variantId, array $variantData): void
    {
        $attributesData = $this->extractAttributesFromVariant($variantData);

        if (empty($attributesData)) {
            Log::info("No attributes found for variant {$variantId}");
            return;
        }

        foreach ($attributesData as $attributeData) {
            $this->processVariantAttribute($variantId, $attributeData);
        }
    }

    /**
     * Extract attributes data from variant with multiple strategies
     */



    /**
     * Extract attribute name from various data structures
     */
    private function extractAttributeName(array $attributeData): ?string
    {
        $nameKeys = ['name', 'attribute_name', 'attr_name', 'key', 'type'];

        foreach ($nameKeys as $key) {
            if (isset($attributeData[$key])) {
                return strtolower(trim($attributeData[$key]));
            }
        }

        // Handle array with single key-value pair
        if (count($attributeData) === 1) {
            $key = array_keys($attributeData)[0];
            if (!is_numeric($key)) {
                return strtolower(trim($key));
            }
        }

        return null;
    }

    /**
     * Extract option value from various data structures
     */
    private function extractOptionValue(array $attributeData): ?string
    {
        $valueKeys = ['value', 'option_value', 'val', 'option'];

        foreach ($valueKeys as $key) {
            if (isset($attributeData[$key])) {
                return trim($attributeData[$key]);
            }
        }

        // Handle array with single value
        if (count($attributeData) === 1) {
            $value = array_values($attributeData)[0];
            return is_string($value) ? trim($value) : null;
        }

        return null;
    }

    /**
     * Find or create variant attribute
     */
    private function findOrCreateAttribute(string $attributeName): variant_attributes
    {
        $slug = Str::slug($attributeName);

        $attribute = variant_attributes::where('slug', $slug)
            ->orWhere('name', $attributeName)
            ->first();

        if (!$attribute) {
            $attribute = variant_attributes::create([
                'name' => ucfirst($attributeName),
                'slug' => $slug,
                'type' => 'select',
                'is_required' => false,
                'sort_order' => variant_attributes::max('sort_order') + 1 ?? 0,
            ]);

            Log::info("Created new attribute: {$attribute->name}");
        }

        return $attribute;
    }

    /**
     * Find or create variant option
     */
    private function findOrCreateOption(int $attributeId, string $optionValue): variant_options
    {
        $option = variant_options::where([
            'attribute_id' => $attributeId,
            'value' => $optionValue
        ])->first();

        if (!$option) {
            $option = variant_options::create([
                'attribute_id' => $attributeId,
                'value' => $optionValue,
                'display_name' => $optionValue,
                'sort_order' => variant_options::where('attribute_id', $attributeId)->max('sort_order') + 1 ?? 0,
                'is_active' => true,
            ]);

            Log::info("🆕 Created new option: {$option->value}");
        }

        return $option;
    }

    /**
     * Create variant-option link
     */
    private function createVariantOptionLink(int $variantId, int $optionId): product_variant_options
    {
        $existingLink = product_variant_options::where([
            'variant_id' => $variantId,
            'option_id' => $optionId
        ])->first();

        if ($existingLink) {
            Log::info("⚠️ Link already exists: variant {$variantId} -> option {$optionId}");
            return $existingLink;
        }

        $link = product_variant_options::create([
            'variant_id' => $variantId,
            'option_id' => $optionId,
        ]);

        Log::info("🔗 Created variant-option link: variant {$variantId} -> option {$optionId}");
        return $link;
    }

    /**
     * Generate SKU for variant
     */
    private function generateSku(int $productId, int $variantIndex): string
    {
        $product = product::find($productId);
        $baseSlug = $product ? Str::upper(Str::substr($product->slug, 0, 3)) : 'PRD';
        return $baseSlug . '-' . $productId . '-' . ($variantIndex + 1) . '-' . time();
    }

    // =====================================
    // UTILITY METHODS
    // =====================================

    /**
     * Upload image helper
     */
    private function uploadImage($file, string $folder = 'products'): string
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * Handle gallery images upload
     */
    private function handleGalleryImages(int $productId, array $images): void
    {
        foreach ($images as $image) {
            $path = $this->uploadImage($image, 'products/gallery');

            product_image::create([
                'product_id' => $productId,
                'image_path' => $path,
                'alt_text' => null,
                'sort_order' => 0
            ]);
        }
    }

    /**
     * Log product data for debugging
     */
    private function logProductData(string $message, array $data): void
    {
        Log::info(message: "=== {$message} ===");
        Log::info('Data received:', $data);
    }

    /**
     * Log errors with context
     */
    private function logError(string $message, \Exception $e): void
    {
        Log::error("=== {$message} ===");
        Log::error('Error message: ' . $e->getMessage());
        Log::error('Error file: ' . $e->getFile() . ':' . $e->getLine());
        Log::error('Stack trace: ' . $e->getTraceAsString());
    }


    /**
     * Get variant options for AJAX
     */
    public function getVariantOptions(int $attributeId): JsonResponse
    {
        try {
            $options = $this->productService->getAttributeOptions($attributeId);
            return response()->json($options);
        } catch (\Exception $e) {
            Log::error('Error getting variant options: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra.'], 500);
        }
    }

    /**
     * Get all attributes
     */
    public function getAttributes(): JsonResponse
    {
        try {
            $attributes = variant_attributes::with('options')->orderBy('sort_order')->get();
            return response()->json(['success' => true, 'data' => $attributes]);
        } catch (\Exception $e) {
            Log::error('Error getting attributes: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error getting attributes'], 500);
        }
    }

    /**
     * Create new attribute via API
     */
    public function createAttribute(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'sometimes|string|in:select,text,number,color',
            'is_required' => 'sometimes|boolean'
        ]);

        try {
            $attribute = $this->findOrCreateAttribute($request->name);

            // Update additional properties if provided
            $updates = [];
            if ($request->has('type')) $updates['type'] = $request->type;
            if ($request->has('is_required')) $updates['is_required'] = $request->is_required;

            if (!empty($updates)) {
                $attribute->update($updates);
            }

            return response()->json([
                'success' => true,
                'message' => 'Attribute created successfully',
                'data' => $attribute
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating attribute: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating attribute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug variants structure
     */
    public function debugVariantsStructure(Request $request): JsonResponse
    {
        Log::info('=== DEBUG VARIANTS STRUCTURE ===');
        Log::info('Request data:', $request->all());

        return response()->json([
            'message' => 'Debug completed - check logs',
            'request_data' => $request->all(),
            'database_stats' => [
                'attributes_count' => variant_attributes::count(),
                'options_count' => variant_options::count(),
                'available_attributes' => variant_attributes::pluck('name', 'id')->toArray()
            ]
        ]);
    }

    /**
     * Bulk actions for products
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $action = $request->get('action');
        $productIds = $request->get('product_ids', []);

        if (empty($productIds)) {
            return response()->json(['error' => 'Vui lòng chọn ít nhất một sản phẩm.'], 400);
        }

        DB::beginTransaction();

        try {
            $message = match ($action) {
                'delete' => $this->bulkDelete($productIds),
                'activate' => $this->bulkActivate($productIds),
                'deactivate' => $this->bulkDeactivate($productIds),
                default => throw new \Exception('Hành động không hợp lệ.')
            };

            DB::commit();
            return response()->json(['message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in bulk action: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Bulk delete products
     */
    private function bulkDelete(array $productIds): string
    {
        foreach ($productIds as $id) {
            $this->destroy($id);
        }
        return 'Đã xóa ' . count($productIds) . ' sản phẩm.';
    }

    /**
     * Bulk activate products
     */
    private function bulkActivate(array $productIds): string
    {
        product::whereIn('id', $productIds)->update(['status' => 1]);
        return 'Đã kích hoạt ' . count($productIds) . ' sản phẩm.';
    }

    /**
     * Bulk deactivate products
     */
    private function bulkDeactivate(array $productIds): string
    {
        product::whereIn('id', $productIds)->update(['status' => 0]);
        return 'Đã vô hiệu hóa ' . count($productIds) . ' sản phẩm.';
    }







    public function destroy(product $product)
    {
        $product->delete(); // xóa mềm danh mục

        return redirect()->route('product-list')
            ->with('message', 'Đã xóa mềm danh mục thành công');
    }

    public function trashed()
    {
        $products = product::onlyTrashed()->paginate(8);

        return view('admin/Product/SoftDeletedProduct', compact('products'));
    }
    public function restore($id)
    {
        $product = product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('product.trashed')
            ->with('message', 'Khôi phục sản phẩm thành công');
    }
    public function forceDelete($id)
    {
        $product = product::withTrashed()->findOrFail($id);
        $product->forceDelete();

        return redirect()->route('product.trashed')
            ->with('message', 'Đã xóa vĩnh viễn sản phẩm');
    }
    public function restoreAll()
    {
        product::onlyTrashed()->restore();

        return redirect()->back()->with('success', 'Tất cả sản phẩm đã được khôi phục thành công.');
    }
    public function forceDeleteAll()
    {
        $products = product::onlyTrashed()->get();

        foreach ($products as $product) {
            $product->forceDelete();
        }

        return redirect()->back()->with('success', 'Đã xóa vĩnh viễn tất cả sản phẩm đã xoá mềm.');
    }
    function view($id){


         $product = Product::with('category')->findOrFail($id);

        return view('admin/Product/product-view', compact('product'));
    }
}
