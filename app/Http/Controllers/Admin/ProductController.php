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
        $filters = [
            'search' => $request->get('search'),
            'category_id' => $request->get('category_id'),
            'brand_id' => $request->get('brand_id'),
            'status' => $request->get('status'),
            'sort_by' => $request->get('sort_by', default: 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc')
        ];

        $products = $this->productService->getAllProducts($filters, $request->get('per_page', 15));
        $categories = categorie::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();

        return view('admin/product-list', compact('products', 'categories', 'brands', 'filters'));
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

        return view('admin/product-create', compact('categories', 'brands', 'attributes','availableAttributes'));
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
    public function edit($id): View|RedirectResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return redirect()
                ->route('product.index')
                ->withErrors(['error' => 'Không tìm thấy sản phẩm.']);
        }

        $categories = categorie::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();
        $attributes = $this->productService->getVariantAttributes();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'attributes'));
    }

    /**
     * Update the specified product
     */
    public function update(ProductStoreRequest $request, $id): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $product = $this->findProductOrFail($id);
            $validatedData = $request->getValidatedData();

            // Update product
            $this->updateProductData($product, $request, $validatedData);

            // Handle images and variants
            $this->updateProductImages($product, $request);
            $this->updateProductVariants($product->id, $validatedData['variants'] ?? []);

            DB::commit();

            return redirect()
                ->route('product.show', $id)
                ->with('message', 'Sản phẩm đã được cập nhật thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating product: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật sản phẩm: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $product = product::find($id);
            if (!$product) {
                return response()->json(['error' => 'Không tìm thấy sản phẩm.'], 404);
            }

            $this->deleteProductAndRelatedData($product);
            DB::commit();

            return response()->json(['message' => 'Sản phẩm đã được xóa thành công!']);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting product: ' . $e->getMessage());

            return response()->json(['error' => 'Có lỗi xảy ra khi xóa sản phẩm.'], 500);
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
        if (!empty($variants)) {
            // Delete existing variants
            $this->deleteExistingVariants($productId);
            // Create new variants
            $this->handleProductVariants($productId, $variants);
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
    private function createProductVariant(int $productId, array $variantData, int $index): product_variants
{
    $variantCreateData = [
        'product_id' => $productId,
        'sku' => $variantData['sku'] ?? $this->generateSku($productId, $index),
        'price' => (int) $variantData['price'],
        'compare_price' => !empty($variantData['compare_price']) ? (float) $variantData['compare_price'] : null,
        'cost_price' => !empty($variantData['cost_price']) ? (float) $variantData['cost_price'] : null,
        'stock_quantity' => (int) $variantData['stock_quantity'],
        'weight' => !empty($variantData['weight']) ? (float) $variantData['weight'] : null,
        'length' => !empty($variantData['length']) ? (float) $variantData['length'] : null,
        'width' => !empty($variantData['width']) ? (float) $variantData['width'] : null,
        'height' => !empty($variantData['height']) ? (float) $variantData['height'] : null,
        'status' => $variantData['status'] ?? 1,
    ];

    $variant = product_variants::create($variantCreateData);
    Log::info("Created variant {$variant->id}");

   // Xử lý attributes của biến thể
   if (!empty($variantData['attributes']) && is_array($variantData['attributes'])) {
    Log::info("Attributes data for variant: ", $variantData['attributes']);

    foreach ($variantData['attributes'] as $attributeData) {
        $attributeId = $attributeData['attribute_id'] ?? null;
        if (!$attributeId) continue;

        $attribute = variant_attributes::find($attributeId);
        if (!$attribute) {
            Log::warning("Attribute ID {$attributeId} not found.");
            continue;
        }

        $options = $attributeData['options'] ?? [];

        foreach ($options as $optionValue) {
            $optionValue = trim($optionValue);
            if ($optionValue === '') continue;

            $option = variant_options::firstOrCreate([
                'attribute_id' => $attribute->id,
                'value' => $optionValue,
            ]);

            $exists = product_variant_options::where([
                'variant_id' => $variant->id,
                'attribute_id' => $attribute->id,
                'option_id' => $option->id,
            ])->exists();

            if (!$exists) {
                product_variant_options::create([
                    'variant_id' => $variant->id,
                    'attribute_id' => $attribute->id,
                    'option_id' => $option->id,
                ]);
            }
        }
    }
}






    return $variant;
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

    // =====================================
    // API ENDPOINTS
    // =====================================

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
            $message = match($action) {
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
}
